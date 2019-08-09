<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */
namespace App\\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Balance;

class BalanceTransformer extends TransformerAbstract
{
    
	protected $availableIncludes = ['user'];
	/**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [ ];

    public function transform(Balance $balance)
    {
        return [
            'user_id'=> $balance->user_id,
            'balance'=> $balance->balance,
            'appkey'=> $balance->appkey,
            'ukey'=> $balance->ukey,
            'symbol'=> $balance->symbol,
            'status'=> $balance->status,
        ];
    }

	public function includeUser( Balance $balance )
    {
        return $this->item( $balance->user, new \App\Transformers\UserTransformer);
    }
    

}

