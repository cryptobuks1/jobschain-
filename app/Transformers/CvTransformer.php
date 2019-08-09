<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */
namespace App\\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Cv;

class CvTransformer extends TransformerAbstract
{
    
	protected $availableIncludes = ['user'];
	/**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [ ];

    public function transform(Cv $cv)
    {
        return [
            'user_id'=> $cv->user_id,
            'address'=> $cv->address,
            'publickey'=> $cv->publickey,
            'qualifications'=> $cv->qualifications,
            'country'=> $cv->country,
            'location'=> $cv->location,
            'description'=> $cv->description,
            'salary'=> $cv->salary,
            'expirience'=> $cv->expirience,
            'type'=> $cv->type,
            'active'=> $cv->active,
        ];
    }

	public function includeUser( Cv $cv )
    {
        return $this->item( $cv->user, new \App\Transformers\UserTransformer);
    }
    

}

