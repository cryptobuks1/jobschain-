<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use PragmaRX\Google2FALaravel\Support\Authenticator;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class AdminUser extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id'=>$this->id,
            'name'=> $this->name,
            'email'=> $this->email,
            'last_seen'=> $this->last_seen,
            'status'=> $this->status,
			'classic'=> new Classic($this->whenLoaded('classic')),
        ];
		return $data;
    }

}
