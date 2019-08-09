<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use PragmaRX\Google2FALaravel\Support\Authenticator;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class UserChain extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data =  [
            'id'=>$this->id,
            'name'=> $this->name,
            'first_name'=> $this->first_name,
            'last_name'=> $this->last_name,
            'text'=> $this->name." ( $this->email )",
            //'balance'=> $this->balance,
            'email'=> $this->email,
            'last_seen'=> $this->last_seen,
            'image_url'=> url('assets/avatar.png'),
            'walletAddress'=> $this->walletAddress,
            'nationality'=> $this->nationality,
            'activated'=> $this->activated,
            'status'=> $this->status,
			'classicbalance'=> $this->classic->balance??0.0000,
            'showclassic'=> isset($this->classic->balance) && $this->classic->status == 0,
            'created_at'=> $this->created_at,
			'enable_twofa_sms' =>$this->enable_twofa_sms,
			'enable_twofa_email'=>$this->enable_twofa_email,
			//'balance'=> new Balance($this->whenLoaded('balance')),
            //'etxs'=> Etx::collection($this->whenLoaded('etxs')),
            //'txs'=> Tx::collection($this->whenLoaded('txs')),
        ];
		
		return ['json'=>$data];
    } 

}
