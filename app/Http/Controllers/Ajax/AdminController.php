<?php

namespace App\Http\Controllers\Ajax;

use App\Models\User;
use App\Models\Tx;
use App\Models\Balance;
use App\Models\Transfer;
use App\Models\Etx;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\AdminBalance as BalanceResource;
use App\Http\Resources\AdminEtx as EtxResource;
use App\Http\Resources\AdminTx as TxResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Auth;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\KeyProtectedByPassword;
class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function balances(Request $request)
    {
		$balances = Balance::with('user')->get();
		return BalanceResource::collection($balances);
    }
	
	public function etxs(Request $request)
    {
		$etxs = Etx::with(['user'])->latest()->take(200)->get();
		return EtxResource::collection($etxs);
    }
	
	
    public function destroyEtx(Request $request, $id)
    {
		$etx = Etx::find($id);
		$this->authorize('delete', $etx);
        Etx::destroy($id);
        return $this->etxs($request);
    }	
	
	public function txs(Request $request)
    {
		$etxs = Tx::with(['user'])->latest()->take(200)->get();
		return  TxResource::collection($etxs);
    }
	
    public function destroyTx(Request $request, $id)
    {
		$tx = Tx::find($id);
		$this->authorize('delete', $tx);
        Tx::destroy($id);
        return $this->txs($request);
    }	
	
	
    public function topup(Request $request,  $id)
    {
		$balance = Balance:: findOrFail($id);
        $request->validate([
			'topup' => 'required|numeric',
			'password' => 'required'
		]);
		$address = $balance->address;
		$password = $request->password;
		$amount = $request->topup;
		$coin = config('coin.manager');
		$chain = new $coin();
		try{
			$txhash = $chain->send( $request->user()->balance, $amount, $address, $password);
		}catch(\Exception $e){
			return ['error'=>$e->getMessage()];
		}
		return $this->balances($request);
    }
	
	public function toggle_user(Request $request,  $id)
    {
		$user = User::findOrFail($id);
		$user->status == !$user->status;
		$user->save();
		return $this->balances($request);
    }

}
