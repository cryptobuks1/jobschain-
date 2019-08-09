<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Msg extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'user_id'=> $this->user_id,
            'from_user_id'=> $this->from_user_id,
            'from_address'=> $this->from_address,
            'from_publicKey'=> $this->from_publicKey,
            'to_address'=> $this->to_address,
            'to_publicKey'=> $this->to_publicKey,
            'subject'=> $this->subject,
            'encrypted'=> $this->encrypted,
            'un_encrypted'=> $this->un_encrypted,
            'txid'=> $this->txid,
            'status'=> $this->status,
            'recipient'=> new User($this->whenLoaded('recipient')),
            'sender'=> new User($this->whenLoaded('sender')),
        ];
    }
}
