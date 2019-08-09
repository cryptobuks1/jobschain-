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

class AdminBalance extends JsonResource
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
            'balance'=> $this->balance,
            'symbol'=> $this->symbol,
            'user'=> new AdminUser($this->whenLoaded('user')),
            //'etxs'=> Etx::collection($this->whenLoaded('etxs')),
            //'txs'=> Tx::collection($this->whenLoaded('txs')),
        ];
    }
}
