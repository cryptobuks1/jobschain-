<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobChain extends JsonResource
{
    /**
     * Transform the resource into an array for the blockchain.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return[ 'json'=> [
            'country'=> $this->country,
            'address'=> $this->address,
            'company_name'=> $this->company_name,
            'title'=> $this->title,
            'qualifications'=> $this->qualifications,
            'salary'=> $this->salary??0,
            'description'=> $this->description,
            'category'=> $this->category,
            'expirience'=> $this->expirience,
            'count'=> $this->count,
        ]];
    }
}
