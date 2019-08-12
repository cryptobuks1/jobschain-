<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Cv extends JsonResource
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
            'address'=> $this->address,
            'publickey'=> $this->publickey,
            'qualifications'=> $this->qualifications,
            'title'=> $this->qualifications,
            'country'=> $this->country,
            'itype'=> 'cv',
            'location'=> $this->location,
            'description'=> $this->description,
            'salary'=> $this->salary,
            'expirience'=> $this->expirience,
            'type'=> $this->type,
            'txid'=> $this->txid,
            'created_at'=> $this->created_at,
            'active'=> $this->active,
            'user'=> new User($this->whenLoaded('user')),
			'msgs'=> Msg::collection($this->whenLoaded('msgs')),
        ];
    }
}
