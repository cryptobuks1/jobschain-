<?php
namespace App\Api;
use Graze\GuzzleHttp\JsonRpc\Client;
class Rpc {
	public $id = 1;
    public function __construct($url,$username,$password)
    {
		$options = [ 
			"rpc_error" => true, 
			"debug" => false 
		];
		$options['auth'] = [$username,$password ];
        $this->rpcClient = Client::factory($url, $options);
    }
	

    /**
     * @param string $method
     * @param null|array $params
     * @return mixed
     */
   public function jsonRequest($method, $params = null)
   {
	    $this->id++;
		$client = $this->rpcClient;
		try {
			$request = $client->request($this->id, $method, $params);
        	$response = $client->send($request);
		} catch (\GuzzleHttp\Exception\TransferException $e) {
			$err ="";
			if ($e->hasResponse()) {
				$err.=" ". \GuzzleHttp\Psr7\str($e->getResponse());
			}
			$err = " ERROR: ".$e->getMessage();
			throw new \Exception ($err);
		}
	    $json = \Graze\GuzzleHttp\JsonRpc\json_decode($response->getBody());
	    return  $json->result??$json;
    }
	

	/**
     * Returns a list of unspent transaction outputs in the wallet, with between minconf and maxconf confirmations.
     * @param int $minConf
     * @param int $maxConf
     * @param null $addresses
     * @return mixed
     */
    public function listunspent($addresses = [], $minConf = 1, $maxConf = 999999)
    {
        return $this->jsonRPCClient->execute("listunspent", array($minConf, $maxConf, implode(',',$addresses)));
    }
	
	public function send( $address , $amount ){
		$method = 'send';
		$params = [$address , $amount];
		return $this->rpcRequest($method, $params);
	}

	public function gettransaction($hash){
		$method = 'gettransaction';
		$params = [$hash];
		return $this->jsonRequest($method, $params);
	}
	
	
	public function getnewaddress(){
		$method = 'getnewaddress';
		return $this->jsonRequest($method);
	}
	
	public function getinfo(){
		$method = 'getinfo';
		return $this->jsonRequest($method);
	}
	
	
	public function getrawtransaction($txid , $verbose=1){
		return $this->jsonRequest('getrawtransaction',[$txid, $verbose]);
	}
	// -------------
    // Streams
    // -------------

    public function publish($streamID, $key, $data)
    {
        return $this->jsonRequest("publish", [$streamID, $key, $data]);
    }


    public function publishfrom($fromAddress, $streamID, $key, $data)
    {
        return $this->jsonRequest("publishfrom", [$fromAddress, $streamID, $key, $data]);
    }


    public function liststreamitems($streamID, $verbose = true, $count = 10, $start = -10, $localOrdering = false)
    {
        return $this->jsonRequest("liststreamitems", [$streamID, $verbose, $count, $start, $localOrdering]);
    }


    public function liststreamkeys($streamID, $key = "*", $verbose = true, $count = 10, $start = -10, $localOrdering = false)
    {
        return $this->jsonRequest("liststreamkeys", [$streamID, $key, $verbose, $count, $start, $localOrdering]);
    }


    public function liststreamkeyitems($streamID, $key, $verbose = true, $count = 10, $start = -10, $localOrdering = false)
    {
        return $this->jsonRequest("liststreamkeyitems", [$streamID, $key, $verbose, $count, $start, $localOrdering]);
    }


    public function liststreampublishers($streamID, $address = "*", $verbose = true, $count = 10, $start = -10, $localOrdering = false)
    {
        return $this->jsonRequest("liststreampublishers", [$streamID, $address, $verbose, $count, $start, $localOrdering]);
    }


    public function liststreampublisheritems($streamID, $address, $verbose = true, $count = 10, $start = -10, $localOrdering = false)
    {
        return $this->jsonRequest("liststreampublisheritems", [$streamID, $address, $verbose, $count, $start, $localOrdering]);
    }

    public function getstreamkeysummary($streamID, $key, $mode="jsonobjectmerge,ignoreother")
    {
        return $this->jsonRequest("getstreamkeysummary", [$streamID,$key,$mode]);
    }
	
	public function getstreampublishersummary($streamID, $address, $mode="jsonobjectmerge,ignoreother")
    {
        return $this->jsonRequest("getstreampublishersummary", [$streamID,$address,$mode]);
    }
	
	public function liststreamblockitems($streamID, $search)
    {
        return $this->jsonRequest("liststreamblockitems", [$streamID,$search]);
    }

	public function liststreams()
    {
        return $this->jsonRequest("liststreams");
    }
	
	public function create($type, $name, $restrictions,$custom_fields)
    {
        return $this->jsonRequest("create",[$type, $name, $restrictions,$custom_fields]);
    }
}