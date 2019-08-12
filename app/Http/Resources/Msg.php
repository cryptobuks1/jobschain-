<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\HasPolyMorphicResource;
class Msg extends JsonResource
{
	use HasPolyMorphicResource;
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
            'user_address'=> $this->from_address,
            'user_address_link'=> config('coin.explorer').'/address/'.$this->user_address,
            'other_address_link'=> config('coin.explorer').'/address/'.$this->other_address,
            'stream'=> uc_first(strtolower($this->stream)),
            'other_address'=> $this->other_address,
            'subject'=> $this->subject,
            'un_encrypted'=> $this->un_encrypted,
            'txid'=> $this->txid,
            'blocktime'=> $this->blocktime,
            'confirmations'=> $this->confirmations,
            'status'=> $this->status,
            'user'=> new User($this->whenLoaded('user')),
            'entity'=>$this->morphResource('entity',$this->entity_type),
        ];
    }
}
