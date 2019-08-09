<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class Etx extends JsonResource
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
            'symbol'=> $this->symbol,
            'txid'=> $this->txid,
            'address'=> $this->address,
            'amount'=> $this->amount,
			'created_at'=> $this->created_at,
            'response'=> $this->response,
			'txid_link'=> config('coin.explorer').'tx/'.$this->txid,
            'address_link'=>  config('coin.explorer').'tx/'.$this->address,
            'txid_short'=> Str::limit($this->txid,16),
            'address_short'=> Str::limit($this->address, 16),
            'status'=> $this->status,
            'user'=> new User($this->whenLoaded('user')),
            'balance'=> new Balance($this->whenLoaded('balance')),
        ];
    }
}
