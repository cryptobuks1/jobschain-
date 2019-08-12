<?php
namespace App\Traits;
use App\Logic\Activation\ActivationRepository;
use App\Models\User;
use App\Models\Address;
use App\Models\Balance;
use App\Models\Etx;
use App\Models\Tx;
use App\Models\Job;
use App\Models\Country;
use App\Models\Msg;
use App\Models\Cv;
use App\Http\Resources\JobChain;
use App\Http\Resources\MsgChain;
use App\Http\Resources\CvChain;
use Illuminate\Database\Eloquent\Collection;
use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Key\Factory\PrivateKeyFactory; 
use BitWasp\Bitcoin\Key\Factory\PublicKeyFactory;
use Elliptic\EC;
trait AltcoinsTrait {
	public $url;
	public $user;
	public $password;
	public $symbol;
	public function toBTC($satoshi)
    {
        return bcdiv((int)(string)$satoshi, 100000000, 8);
    }
	
	public  function toSatoshi($btc)
    {
        $out = bcmul(sprintf("%.8f", (float)$btc), 100000000, 0);
		return (int)$out; 
    }

	protected function api(  ) {
		return new \App\Api\Rpc( config('coin.rpc.url'), config('coin.rpc.user'), config('coin.rpc.password') );
	}
	
	public function setNetwork(){
		$addressByte = config('coin.addressByte');
		$p2shByte=config('coin.p2shByte');
		$privByte =config('coin.privByte');
		$hdPubByte =config('coin.hdPubByte');
		$hdPrivByte = config('coin.hdPrivByte'); 
		$p2pMagic = config('coin.p2pMagic');
		$SegwitBech32Prefix = config('coin.SegwitBech32Prefix');
		$signedMessagePrefix = config('coin.signedMessagePrefix');
		$network = new \App\Logic\Network($addressByte, $p2shByte, $privByte, $hdPubByte, $hdPrivByte, $p2pMagic, $SegwitBech32Prefix, $signedMessagePrefix);
		Bitcoin::setNetwork($network);
		return $network;
	}

	protected function coin( ) {
		return config('coin');
	}


	public function deriveAddress( Balance $balance ) {
		$user = $balance->user()->first();
		$api = $this->api();
		$add = $api->getnewaddress();
		$address = new Address;
		$address->address = $add;
		$address->balance = 0.000;
		$address->status = 0;
		$address->idx = $user->id;;
		$address->user_id = $user->id;
		$address->balance_id = $balance->id;
		$address->symbol = $balance->symbol;
		$address->save();
		return $address;
	} 
	
	public function getPoolAddress($user) {
		$count = $user->pools()->count();
		$api = $this->api();
		if ( $count < 4 ){
			for($i = 0 ;  $i == (4-$count) ; $i++ )
			$address = $api->getnewaddress();
			$pool = new Pool;
			$pool->address = $address;
			$pool->user_id = $user->id;
			$pool->save();
			$api->send( $address, ( float )1);
		}
		$used = $user->pools()->first();
		$used->status = true;
		$used->save();
		return $used;
	}

	public function send( $txs  ) {
		foreach($txs as $etx){
			if (!$etx->is_valid ) {
				$tx->status = 3;
				$tx->response = __( 'node.lowbalance' );
				$tx->save();
				continue;
			}
			$api = $this->api();
			try {
				$txh = $api->send( $etx->to, ( float )$etx->net_amount );
			} catch ( \Exception $e ) {
				throw $e;
			}
			$etx = $ext->complete($txh);
		}
		return $txh??null;
	}
	
	public function adminSend( $amount , $address ) {	
		$api = $this->api();
		try {
			$txh = $api->send( $address, ( float )$amount );
		} catch ( \Exception $e ) {
			throw $e;
		}
		return $txh;
	}


	public function discover( $user = null ) {
		$coin = $this->coin();
		$api = $this->api();
		$query = Address::where( 'symbol', $coin->symbol )->where( 'status', false );
		if ( $user )$query->where( 'user_id', $user->id );
		$addresses = $query->get();
		$txids = Tx::where( 'symbol', $coin->symbol )->pluck( 'txid' )->all();
		$utxos = $api->listunspent( $addresses->pluck( 'address' ) );
		foreach ( $utxos as $utxo ) {
			if ( !in_array( $utxo->hash , $txids ) ) {
				$this->saveUtxo($utxo);
			}
		}
	}
	
	
	public function adminAddress() {
		$api = $this->api();
		$address = $api->getruntimeparams()->handshakelocal;
		$balance = $api->getwalletinfo()->balance;
		$txs = $provider->listwallettransactions(30);
		return [ 
			"addrStr" =>$address,
			"balance"=>bcmul((string)$balance , 1 , 8 ),
			"unconfirmedBalance"=>0,
			"transactions"=>$txs->pluck('txid')->all()
		];
	}

	
	
	public function tx_confirm( $user ) {
		$api = $this->api();
		$query = Tx::where( 'status', 0 );
		if ( $user )$query->where( 'user_id', $user->id );
		$txs = $query->get();
		foreach ( $txs as $tx ) {
			$txr = $api->gettransaction($tx-txid);
			$tx->confirmations = $txr->confirmations;
			$tx->height = $txr->blocktime;
			$tx->save();
			if ( $tx->confirmations > env( 'MIN_TX_CONFIRMATIONS' ) ) {
				 $tx = $tx->complete();
			}
		}
	}
	
	public function get_tx(  $txid ) {
		$api = $this->api();
		$txids = Tx::pluck( 'txid' )->all();
		$txr = $api->gettransaction( $txid );
		if ( !in_array( $txr ->txid , $txids ) ) {
			$this->saveUtxo($txr);
		}
	}
	
	public function saveUtxo($utxo){
		$tx = new Tx;
		$tx->txid = $utxo->txid;
		$tx->address = $utxo->address;
		$tx->scriptPubKey= $utxo->scriptPubKey;
		$tx->amount= $utxo->amount;
		$tx->confirmations= $utxo->confirmations;
		$tx->satoshis = $this->toSatoshi($utxo->amount);
		$tx->status = 0;
		$tx->save();
		$tx->load( 'addr' );
		$addr = $tx->addr;
		$tx->symbol = $addr->symbol;
		$tx->balance_id = $addr->balance_id;
		$tx->user_id = $addr->user_id;
		$tx->save();
		$addr->status = 1;
		$addr->save();
		if ( $tx->confirmations > env( 'MIN_TX_CONFIRMATIONS' ) ) {
			 $tx = $tx->complete();
		}
	}
	
	public function publishJob(Job $job ){
		$jobChain = new JobChain($job);
		$api = $this->api();
		$txid  = $api->publishfrom($job->address, config('coin.jobstream'),$job->address,$jobChain);
		$job->txid = $txid;
		$job->save(); 
		return $job;
	}
	
	public function publishCv(Cv $cv ){
		$cvChain = new CvChain($cv);
		$api = $this->api();
		$txid = $api->publishfrom($cv->address, config('coin.cvstream'),$cv->address, $cvChain);
		$cv->txid = $txid;
		$cv->save();
		return $cv;
	}
	
	public function sendMessage(Msg $msg ){
		$api = $this->api();
		$privWif =  $api->dumpprivkey($msg->user_address);
		$pubKey = $msg->other_publickey;
		$this->setNetwork();
		$privKey = ( new PrivateKeyFactory)->fromWif($privWif)->toHex();
		$encrypted = $this->encrypt($privKey , $pubKey, $msg->un_encrypted );
		$msg->encrypted = $encrypted ;
		$msg->save();
		$msgChain = new MsgChain($msg);
		$txid = $api->publishfrom( $msg->user_address, config('coin.msgstream'),  $msg->other_address, $msgChain);
		$msg->txid = $txid;
		$msg->save();
	}
	
	// update the Jobs database
	public function parseJobs(){
		$last = Job::latest()->first();
		$last_time = $last->blocktime?? now()->subDay()->timestamp;
		$now =  now()->timestamp;
		$api = $this->api();
		$jbdata = $api->liststreamblockitems( 'jobs', ["starttime"=>$last_time,"endtime"=>$now]);
		$txs = Job::pluck('txid')->all();
		foreach($jbdata as $jb ){
			$old_job = Job::where('address',$jb->publishers[0])->first();
			if(in_array($jb->txid , $txs)){
				$old_job->confirmations = $jb->confirmations;
				$old_job->save();
				continue;
			} 
			$data = $jb->data->json;
			$country = Country::where('iso_3166_3' ,$data->country)->first();
			if(is_null($country)) $country =  Country::where('iso_3166_3' , env('DEFAULT_COUNTRY','USA'))->first();
			$pubkey = $this->getPubkey($jb->txid);
			try{ // will skip if data is invalid!!
				$job = new Job;
				$job->user_id = $old_job->user_id??null;
				$job->country_id= $country->id;
				$job->country= $country->name;
				$job->address= $jb->publishers[0];
				$job->publickey= $pubkey;
				$job->txid= $jb->txid;
				$job->blocktime = $jb->blocktime;
				$job->confirmations = $jb->confirmations;
				$job->company_name= $data->company_name;
				$job->title= $data->title;
				$job->category= $data->category;
				$job->salary= $data->salary;
				$job->qualifications= $data->qualifications;
				$job->description= $data->description;
				$job->expirience= $data->expirience;
				$job->expiry = \Carbon\Carbon::createFromTimestamp($jb->blocktime)->addDays(config('coins.item_valid_days',7));
				$job->count = $data->count;
				$job->status = 'open';
				$job->active = true;
				$job->save();
			}catch(\Exception $e){
				continue;
			}
			
		}
		return true;
	}
	
	// update the Jobs database
	public function parseCvs(){
		$last = Cv::latest()->first();
		$last_time = $last->blocktime?? now()->subDay()->timestamp;
		$now =  now()->timestamp;
		$api = $this->api();
		$cvdata = $api->liststreamblockitems( 'cvs', ["starttime"=>$last_time,"endtime"=>$now]);
		$txs = Cv::pluck('txid')->all();
		foreach($cvdata as $cv ){
			$old_cv = Cv::where('address',$cv->publishers[0])->first();
			if(in_array($cv->txid , $txs)) {
				$old_cv->confirmations = $cv->confirmations;
				$old_cv->save();
				continue;
			}
			$data = $cv->data->json;
			$country = Country::where('iso_3166_3' ,$data->country)->first();
			if(is_null($country)) $country =  Country::where('iso_3166_3' , env('DEFAULT_COUNTRY','USA'))->first();
			$pubkey = $this->getPubkey($cv->txid);
			$cv = new Cv;
			$cv->user_id = $old_cv->user_id??null;
			$cv->country_id= $country->id;
			$cv->country= $country->name;
			$cv->address= $cv->publishers[0];
			$cv->publickey= $pubkey;
			$cv->txid= $cv->txid;
			$cv->blocktime = $cv->blocktime;
			$cv->confirmations = $cv->confirmations;
			$cv->location = $data->location;
			$cv->type= $data->type;
			$cv->salary= $data->salary;
			$cv->qualifications= $data->qualifications;
			$cv->description= $data->description;
			$cv->expirience= $data->expirience;
			$cv->count = $data->count;
			$cv->status = 'open';
			$cv->active = true;
			$cv->save();
		}
		return true;
	}
	
	// update the Jobs database
	public function parseMsgs(){
		$last = Msg::latest()->first();
		$last_time = $last->blocktime?? now()->subDay()->timestamp;
		$now =  now()->timestamp;
		$api = $this->api();
		$msg_data = $api->liststreamblockitems( 'msgs', ["starttime"=>$last_time,"endtime"=>$now]);
		$txs = Msg::pluck('txid')->all();
		$last->blocktime = $now;
		$last->save();
		$cv_keys = Cv::pluck('address')->all();
		$job_keys = Job::pluck('address')->all();
		foreach($msg_data as $msg ){
			$is_cv = in_array($msg->keys[0] , $cv_keys);
			$is_job = in_array($msg->keys[0] , $job_keys);
			if(!$is_cv && !$is_job) continue;
			$to = $is_cv?Cv::where('address',$msg->keys[0])->first():Job::where('address',$msg->keys[0])->first();
			$data = $msg->data->json;
			$pubkey = $this->getPubkey($msg->txid);
			$inbox = new msg;
			$inbox->user_id = $to->user_id??null;
			$inbox->user_address = $to->address;
			$inbox->user_publicKey = $to->public_key;
			$inbox->other_address = $msg->publishers[0];;
			$inbox->other_publicKey= $pubkey;
			$inbox->stream = $is_cv?config('coin.cvstsream'):config('coin.msgtsream');
			$inbox->box = 'inbox';
			$inbox->subject = $data->encrypted;
			$inbox->encrypted = $data->subject;
			$inbox->un_encrypted = $this->decryptMsg($to->address ,$pubkey, $inbox->encrypted);
			$inbox->entity_type = get_class($to);
			$inbox->entity_id = $to->id;
			$inbox->txid = $msg->txid;
			$inbox->blocktime = $msg->blocktime;
			$inbox->confirmations = $msg->confirmations;
			$inbox->status = true;
			$inbox->save();
		}
		return true;
	}
	
	public function decryptMsg($me , $other, $msg){
		$api = $this->api();
		$privWif =  $api->dumpprivkey($me);
		$this->setNetwork();
		$privKey = ( new PrivateKeyFactory )->fromWif($privWif)->toHex();
		return  $this->decrypt($privKey , $other , $msg );
	}
	
	public function getPubkey($txid){
		$api = $this->api();
		$tx = $api->getrawtransaction($txid);
		$vin = $tx->vin[0];
		return $this->signature($vin->scriptSig->hex);
	}
		
	public function signature($script){
        $pos = 0;
        $data = array();
        while ($pos < strlen($script)) {
            $code = hexdec(substr($script, $pos, 2)); // hex opcode.
            $pos += 2;
            if ($code < 1) {
                $push = '0';
            } elseif ($code <= 75) {
                $push = substr($script, $pos, ($code * 2));
                $pos += $code * 2;
            } elseif ($code <= 78) {
                $szsz = pow(2, $code - 75);
                $sz = hexdec(substr($script, $pos, ($szsz * 2))); 
                $pos += $szsz;
                $push = substr($script, $pos, ($pos + $sz * 2)); 
                $pos += $sz * 2;
            } elseif ($code <= 96) {
                $push = ($code - 80);
            } else {
                $push = $code;
            }
            $data[] = $push;
        }
        return $data[1];
    }
	
	public function checkStreams(){
		$api = $this->api();
		$jobs = $api->liststreams();
		$names = collect($jobs)->pluck('name')->all();
		if(!in_array('jobs', $names)){
			$api->create('stream', 'jobs',true, ['restrict'=>'offchain'],['purpose'=>'Stores Job Data']);
		}
		if(!in_array('msgs', $names)){
			$api->create('stream', 'msgs',true, ['restrict'=>'offchain'],['purpose'=>'Stores Messages Data']);
		}
		if(!in_array('cvs', $names)){
			$api->create('stream', 'cvs',true, ['restrict'=>'offchain'],['purpose'=>'Stores Cv Data']);
		}
		
	}
	
	
	
	public function getCrypt($privKey , $pubKey){
		$ec = new EC('secp256k1');
		$pub = $ec->keyFromPublic($pubKey, 'hex');
		$priv = $ec->keyFromPrivate($privKey, 'hex');
		$secret = $priv->derive($pub->getPublic());
		return new Encrypter( $secret , 'AES-256-CBC' );
	}

	
	public function encrypt($privateKey, $publicKey, $message){
		$crypt = $this->getCrypt($privateKey, $publicKey);
		return $crypt->encrypt( $message );
	}
	
	public function decrypt($privateKey, $publicKey, $message){
		$crypt = $this->getCrypt($privateKey, $publicKey);
		return $crypt->decrypt( $message );
	}
	
	
	
}