<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */
namespace App\\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Address;

class AddressTransformer extends TransformerAbstract
{
    
	protected $availableIncludes = ['user','order','balance'];
	/**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [ ];

    public function transform(Address $address)
    {
        return [
            'user_id'=> $address->user_id,
            'balance_id'=> $address->balance_id,
            'path'=> $address->path,
            'idx'=> $address->idx,
            'symbol'=> $address->symbol,
            'address'=> $address->address,
            'balance'=> $address->balance,
            'status'=> $address->status,
        ];
    }

	public function includeUser( Address $address )
    {
        return $this->item( $address->user, new \App\Transformers\UserTransformer);
    }
    public function includeOrder( Address $address )
    {
        return $this->item( $address->order, new \App\Transformers\OrderTransformer);
    }
    public function includeBalance( Address $address )
    {
        return $this->item( $address->balance, new \App\Transformers\BalanceTransformer);
    }
    

}

