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

class Balance extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
	
    public function toArray($request)
    {

		$unconf =  $this->txs()->where('confirmations','<',3)->sum('amount');
		$live = $this->livebalance();
        return [
            'id'=>$this->id,
            'user_id'=> $this->user_id,
            'balance'=> bcmul((string)$live, 1 , 8 ),
            'symbol'=> $this->symbol,
            'text'=> config('coin.name'),
            'status'=> $this->status,
            'address'=> $this->address,
			'created_at'=> $this->created_at,
			'qr_link'  => route('public.qrgen',['text'=>$this->address ]),
			'address_link'  =>config('coin.explorer').'/address/'.$this->address,
			'address_short' => Str::limit($this->address, 20),
			'sent' =>  $this->etxs->sum('amount')+0,
			'deposited' => $this->txs->sum('amount')+0,
			'unconfirm' => bcmul((string)$unconf , 1 ,4),
            'user'=> new User($this->whenLoaded('user')),
            'etxs'=> Etx::collection($this->whenLoaded('etxs')),
            'txs'=> Tx::collection($this->whenLoaded('txs')),
        ];
    }
}
