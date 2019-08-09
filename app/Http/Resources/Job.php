<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Job extends JsonResource
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
            'country'=> $this->country,
            'address'=> $this->address,
            'publickey'=> $this->publickey,
            'company_name'=> $this->company_name,
            'title'=> $this->title,
            'qualifications'=> $this->qualifications,
            'description'=> $this->description,
            'expirience'=> $this->expirience,
            'count'=> $this->count,
            'status'=> $this->status,
            'active'=> $this->active,
            'country'=> new Country($this->whenLoaded('country')),
            'user'=> new User($this->whenLoaded('user')),
            'applicants'=> Cv::collection($this->whenLoaded('applicants')),
        ];
    }
}
