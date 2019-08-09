<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Address extends JsonResource
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
            'balance_id'=> $this->balance_id,
            'path'=> $this->path,
            'idx'=> $this->idx,
            'symbol'=> $this->symbol,
            'address'=> $this->address,
            'balance'=> $this->balance,
            'status'=> $this->status,
            'user'=> new User($this->whenLoaded('user')),
            'order'=> new Order($this->whenLoaded('order')),
            'balance'=> new Balance($this->whenLoaded('balance')),
        ];
    }
}
