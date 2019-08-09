<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */
namespace App\\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Deposit;

class DepositTransformer extends TransformerAbstract
{
    
	protected $availableIncludes = ['user'];
	/**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [ ];

    public function transform(Deposit $deposit)
    {
        return [
            'user_id'=> $deposit->user_id,
            'amount'=> $deposit->amount,
            'gateway'=> $deposit->gateway,
            'txid'=> $deposit->txid,
            'status'=> $deposit->status,
            'active'=> $deposit->active,
        ];
    }

	public function includeUser( Deposit $deposit )
    {
        return $this->item( $deposit->user, new \App\Transformers\UserTransformer);
    }
    

}

