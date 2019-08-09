<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */
namespace App\\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Job;

class JobTransformer extends TransformerAbstract
{
    
	protected $availableIncludes = ['country','user','applicants'];
	/**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [ ];

    public function transform(Job $job)
    {
        return [
            'user_id'=> $job->user_id,
            'country'=> $job->country,
            'address'=> $job->address,
            'publickey'=> $job->publickey,
            'company_name'=> $job->company_name,
            'title'=> $job->title,
            'qualifications'=> $job->qualifications,
            'description'=> $job->description,
            'expirience'=> $job->expirience,
            'count'=> $job->count,
            'status'=> $job->status,
            'active'=> $job->active,
        ];
    }

	public function includeCountry( Job $job )
    {
        return $this->item( $job->country, new \App\Transformers\CountryTransformer);
    }
    public function includeUser( Job $job )
    {
        return $this->item( $job->user, new \App\Transformers\UserTransformer);
    }
    public function includeApplicants( Job $job )
    {
        return $this->item( $job->applicants, new \App\Transformers\CvTransformer);
    }
    

}

