<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */
namespace App\\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Etx;

class EtxTransformer extends TransformerAbstract
{
    
	protected $availableIncludes = ['user','balance'];
	/**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [ ];

    public function transform(Etx $etx)
    {
        return [
            'user_id'=> $etx->user_id,
            'balance_id'=> $etx->balance_id,
            'symbol'=> $etx->symbol,
            'txid'=> $etx->txid,
            'address'=> $etx->address,
            'amount'=> $etx->amount,
            'response'=> $etx->response,
            'status'=> $etx->status,
        ];
    }

	public function includeUser( Etx $etx )
    {
        return $this->item( $etx->user, new \App\Transformers\UserTransformer);
    }
    public function includeBalance( Etx $etx )
    {
        return $this->item( $etx->balance, new \App\Transformers\BalanceTransformer);
    }
    

}

