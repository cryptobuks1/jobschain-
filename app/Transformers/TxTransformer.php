<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */
namespace App\\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Tx;

class TxTransformer extends TransformerAbstract
{
    
	protected $availableIncludes = ['user','order','balance'];
	/**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [ ];

    public function transform(Tx $tx)
    {
        return [
            'user_id'=> $tx->user_id,
            'balance_id'=> $tx->balance_id,
            'symbol'=> $tx->symbol,
            'txid'=> $tx->txid,
            'address'=> $tx->address,
            'scriptPubKey'=> $tx->scriptPubKey,
            'amount'=> $tx->amount,
            'satoshis'=> $tx->satoshis,
            'height'=> $tx->height,
            'confirmations'=> $tx->confirmations,
            'status'=> $tx->status,
        ];
    }

	public function includeUser( Tx $tx )
    {
        return $this->item( $tx->user, new \App\Transformers\UserTransformer);
    }
    public function includeOrder( Tx $tx )
    {
        return $this->item( $tx->order, new \App\Transformers\OrderTransformer);
    }
    public function includeBalance( Tx $tx )
    {
        return $this->item( $tx->balance, new \App\Transformers\BalanceTransformer);
    }
    

}

