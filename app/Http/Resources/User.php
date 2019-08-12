<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use PragmaRX\Google2FALaravel\Support\Authenticator;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
		$is_user = auth()->check()&&$request->user()->id == $this->id;
        $data = [
            'id'=>$this->id,
            'name'=> $this->name,
            'first_name'=> $this->first_name,
            'last_name'=> $this->last_name,
            'text'=> $this->name." ( $this->email )",
            'balance'=> $this->balance,
            'email'=> $this->email,
            'last_seen'=> $this->last_seen,
            'image_url'=> url('assets/avatar.png'),
            'walletAddress'=> $this->walletAddress,
            'nationality'=> $this->nationality,
            'activated'=> $this->activated,
            'status'=> $this->status,
            'created_at'=> $this->created_at,
			'enable_twofa_sms' =>$this->enable_twofa_sms,
			'enable_twofa_email'=>$this->enable_twofa_email,
			'balance'=> new Balance($this->whenLoaded('balance')),
            'etxs'=> Etx::collection($this->whenLoaded('etxs')),
            'txs'=> Tx::collection($this->whenLoaded('txs')),
            'jobs'=> Job::collection($this->whenLoaded('jobs')),
            'cvs'=> Cv::collection($this->whenLoaded('cvs')),
        ];
		if(!$this->twofa_secret){
			$secretKey = $this->getGoogle()->generateSecretKey();
			$data['secretKey'] = $secretKey;
			$data['inlineUrl'] = $this->qr(
				env('APP_NAME'),
				$this->email,
				$secretKey
			);
		}
		return $data;
    }
	
	public function getGoogle(){
		return app(Authenticator::class)->boot(request());
	}
	
	public function qr($company, $holder, $secret, $size = 150, $encoding = 'utf-8')
    {
        $url = $this->getGoogle()->getQRCodeUrl($company, $holder, $secret);
		$renderer = new ImageRenderer(
			new RendererStyle($size),
			new ImagickImageBackEnd()
		);
		$writer = new Writer($renderer);
		$data = $writer->writeString($url, $encoding);;
        return 'data:image/png;base64,'.base64_encode($data);
    }
}
