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

class AdminTx extends JsonResource
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
            'txid'=> $this->txid,
            'address'=> $this->address,
            'created_at'=> $this->created_at,
            'scriptPubKey'=> $this->scriptPubKey,
            'amount'=> $this->amount,
            'satoshis'=> $this->satoshis,
            'height'=> $this->height,
            'confirmations'=> $this->confirmations,
            'status'=> $this->confirmations > 3,
			'txid_link'=> config('coin.explorer').'/tx/'.$this->txid,
            'address_link'=>  config('coin.explorer').'/address/'.$this->address,
            'txid_short'=> Str::limit($this->txid,16),
            'address_short'=> Str::limit($this->address, 16),
            'user'=> new AdminUser($this->whenLoaded('user')),
            'balance'=> new AdminBalance($this->whenLoaded('balance')),
        ];
    }
}
