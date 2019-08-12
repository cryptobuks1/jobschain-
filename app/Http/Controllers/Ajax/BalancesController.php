<?php
//ofumbi stephen  <ofuzak@gmail.com>
namespace App\Http\Controllers\Ajax;

/**
 * User Controller 
 */
use App\Models\User;
use App\Models\Tx;
use App\Models\Balance;
use App\Models\Transfer;
use App\Models\Fee;
use App\Models\Etx;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Balance as BalanceResource;
use App\Http\Resources\Etx as EtxResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Auth;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\KeyProtectedByPassword;

class BalancesController extends Controller
{
    public function __construct()
    {
        //$this->middleware('pass')->only('send');
    }

    /**
     * Show the Wallet ui.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$balance = $request->user()->balance;
		if($balance){
			$address = $balance->address;
			$balance = $balance->fresh();
			$balance->load(['txs'=>function($q){
				return $q->latest()->where('is_change',0);
			},'etxs'=>function($q){
				return $q->latest();
			},'user.classic']);
			return new BalanceResource($balance);
		}
		return [];
    }
	

	/**
     * user.
     *
     * @return \Illuminate\Http\Response
     */
    public function user(Request $request)
    { 
		
		$user = $request->user();
		$balance = $request->user()->balance()->first();
		if($balance){
			$address = $balance->address;
		}else{
			$coin = config('coin.manager');
			$chain = new $coin();
			$balance = $user->balance()->create([
					 'symbol'=> env('SYMBOL'),
					 'balance'=> '0.000',
				 ]);
			$address = $balance->address;
		}
		$user->load([
			'balance',
			'txs'=>function($q){
				return $q->latest();
			},
			'etxs'=>function($q){
				return $q->latest();
			},
			'jobs'=>function($q){
				return $q->latest();
			},
			'jobs.msgs'=>function($q){
				return $q->latest();
			},
			'cvs'=>function($q){
				return $q->latest();
			},
			'cvs.msgs'=>function($q){
				return $q->latest();
			},
		]);
		return new UserResource($user);
    }
	
	
	
	/**
     * Show the app settings . *
     * @return \Illuminate\Http\Response
     */
    public function settings(Request $request)
    {
		return collect([
			"token_symbol"=>setting('token_symbol'),
			"token_name"=>setting('token_name'),
			"token_decimal_min"=>setting('token_decimal_min'),
			"token_decimal_max"=>setting('token_decimal_max'),
		]);
    }
	

	
	
	
	
    /**
     * Send Coins Out.
     *
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
		$request->validate([
			'amount'=>'required|numeric',
			'address'=>'required|string',
			'password'=>'required|string',
		]);
		$address = $request->address;
		$password = $request->password;
		$amount = $request->amount;
		$coin = config('coin.manager');
		$chain = new $coin();
		$balance = $request->user()->balance;
		try{
			$txhash = $chain->send( $request->user()->balance, $amount, $address, $password);
		}catch(\Exception $e){
			return ['error'=>$e->getMessage()];
		}
		$balance->balance -=$amount;
		$balance->save();
		return collect(['txid_link'=>config('coin.explorer').'/tx/'.$txhash,'txid'=>$txhash]);
	}
	
	/**
     * New Address.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_new_address(Request $request)
    {
		$balance = $request->user()->balance;
		$maxaddress = env('MAXIMUN_FREE_ADDRESSES_PER_USER',3);
		if($balance->addresses()->where('status', false)->count() > $maxaddress )
			return ['error'=>__('wallet.max_addresses',['maxaddress'=>$maxaddress])];
		try{
			$coin = config('coin.manager');
			$chain = new $coin();
			$address = $chain->deriveAddress($balance);
		}catch(\Exception $e){
			return ['error'=>$e->getMessage()];
		}
		return $this->user($request);
	}
	/**
     * New Address.
     *
     * @return \Illuminate\Http\Response
     */
    public function mnemonic(Request $request)
    {
		$request->validate(['password'=>'required']);
		$password = $request->password;
		$balance = $request->user()->balance;
		$protected_key =KeyProtectedByPassword::loadFromAsciiSafeString($balance->cypher);
		$key  = $protected_key->unlockKey($password);
		$mnemonic = Crypto::decrypt($balance->mnemonic,$key);
		return  $mnemonic;
	}
	
	public function update(Request $request){
		$request->validate([
			'name'=>'required|alpha_num',
			'first_name'=>'required|alpha_num',
			'last_name'=>'required|alpha_num',
		]);
		$user = auth()->user();
		$user->name = $request->name;
		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->save();
		return $this->user($request);
	}
	
	public function updatePassword(Request $request){
		
		$request->validate([
			'password'=> 'required|min:6|max:20|confirmed',
			'password_confirmation' => 'required|same:password',
		]);
		$user = $request->user();
		if ($request->password != null) {
            $user->password = bcrypt($request->password);
        }
        $user->updated_ip_address = $request->ip();
        $user->save();
		return $this->user($request);
	}
	
	public function updateUser(Request $request){
		
		$request->validate([
			'name'=> 'required|min:4|max:20',
			'first_name'=> 'required|min:3|max:20',
			'last_name'=> 'required|min:3|max:20',
		]);
		$user = $request->user();
		$user->name = $request->name;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->updated_ip_address = $request->ip();
        $user->save();
		return $this->user($request);
	}
	
}
