<?php
namespace App\Logic; 
use ofumbi\Api\Providers\Rpc;
use Graze\GuzzleHttp\JsonRpc\Client;
use ofumbi\Api\ApiInterface;
use Illuminate\Support\Collection;
use BitWasp\Bitcoin\Transaction\SignatureHash\SigHash;
class ALG  implements ApiInterface
{
	public $addressByte = '19';
	public $p2shByte='06';
	public $privByte ='2E'; 
	public $hdPubByte ='0488b21e';
	public $hdPrivByte = '0488ade4' ;
	public $p2pMagic = 'be88c20c';
	public $SegwitBech32Prefix = NULL;
	public $signedMessagePrefix ='DarkNet Signed Message:\n';
	public $net;
	// RPC INFO //
	public $node;
	public $bip44index = '900';

	
    public function __construct()
    {
		$this->net = $this->network();
		$url = config('coin.rpc.url');
		$username = config('coin.rpc.user');
		$password = config('coin.rpc.password');
		$this->node = new Rpc( $url, $username , $password ); 	
	}
	
	public function getNetwork(){
		return $this->net;
	}

    /**
     * @return NetworkInterface
     * @throws \Exception
     */
    public function network()
    {
		return new Network($this->addressByte, $this->p2shByte, $this->privByte, $this->hdPubByte, $this->hdPrivByte, $this->p2pMagic, $this->SegwitBech32Prefix, $this->signedMessagePrefix);
    }
	
	public function sigHash(){
		return SigHash::ALL;
	}
	
	//chainso
	public function addressTx(Collection $addresses, $blocks = []){
		return $this->node->addressTx($addresses, $blocks);
	}
	
	// dash
	public function listunspent($minconf, array $addresses=[], $max = null){
		return $this->node->listunspent($minconf??1, $addresses, $max??99999);
	}
	
	
	public function getBalance($minconf, array $addresses=[]){
		 $bal = $this->listunspent($minconf, $addresses ,999999)->sum('amount');
		return $bal ;
	}
	
	
	public function sendrawtransaction( $hexRawTx ){
		return $this->node->sendrawtransaction( $hexRawTx );
	}
	
	public function getBlock($hash){
	
		return $this->node->getBlock($hash);
	}
	
	public function getBlockByNumber($number){
		return $this->node->getBlockByNumber($number);
	}
	
	public function getTx($hash){
		return $this->node->getTx($hash);
	}
	
	public function currentBlock(){
		return $this->node->currentBlock();
	}
	
	public function feePerKB(){
		return $this->node->feePerKB();
	}
	//
	public function importaddress($address,$wallet_name =null,$rescan =null){
		return $this->node->importaddress($address,$wallet_name,$rescan);
	}

	public function tx_explorer($hash){
		return config('coin.explorer').'/tx/'.$hash;
	}
	
	public function address_explorer($address){
		return config('coin.explorer').'/address/'.$address;
	}
	
	public function __call($method, $params){
		return $this->node->jsonRequest($method,  array_values($params));
	}
}

