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
        return ['json'=>[
				'qualifications'=> $this->qualifications,
				'country'=> $this->country,
				'location'=> $this->location,
				'description'=> $this->description,
				'salary'=> $this->salary,
				'expirience'=> $this->expirience,
				'type'=> $this->type
			]
		];
    }
}
