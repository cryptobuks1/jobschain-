<?php
namespace App\Logic;
use App\Logic\Rpc;
class Redemption{
	public $rpc;
	public function __construct(){
		$host = "206.189.193.37";
		$port = "8987";
		$username = "bitalgorpc";
		$password = "E3tNG42JGzC51VBPj86fETPHbVmRAWpYavfaQhsnzgtrj";
		$this->rpc = new Rpc($username, $password, $host , $port);
	}
	
	
	public function getnewaddress(){
		return $this->rpc->getnewaddress();
		
	}
	
	public function gettransaction($txid , $address){
		$tx =  $this->rpc->gettransaction($txid);
		$return  = [
			'txid' =>$tx->txid,
			'confirmations' =>$tx->confirmations,
		];
		foreach($tx->vout as $vout){
			if(!in_array($address , $vout->scriptPubKey->addresses)) continue;
			$return['address'] = $address;
			$return['amount'] = $vout->value;
		}
		return (object)$return;
	}
}
	