<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */
namespace App\\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Msg;

class MsgTransformer extends TransformerAbstract
{
    
	protected $availableIncludes = ['recipient','sender'];
	/**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [ ];

    public function transform(Msg $msg)
    {
        return [
            'user_id'=> $msg->user_id,
            'from_user_id'=> $msg->from_user_id,
            'from_address'=> $msg->from_address,
            'from_publicKey'=> $msg->from_publicKey,
            'to_address'=> $msg->to_address,
            'to_publicKey'=> $msg->to_publicKey,
            'subject'=> $msg->subject,
            'encrypted'=> $msg->encrypted,
            'un_encrypted'=> $msg->un_encrypted,
            'txid'=> $msg->txid,
            'status'=> $msg->status,
        ];
    }

	public function includeRecipient( Msg $msg )
    {
        return $this->item( $msg->recipient, new \App\Transformers\UserTransformer);
    }
    public function includeSender( Msg $msg )
    {
        return $this->item( $msg->sender, new \App\Transformers\UserTransformer);
    }
    

}

