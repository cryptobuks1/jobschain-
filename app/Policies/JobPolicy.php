<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */

namespace App\Policies;

use App\Models\User;
use App\Models\Job;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobPolicy
{
    use HandlesAuthorization;
	
	public function before(User $user)
	{
		if ($user->isAdmin()) {
			return true;
		}
	}

    /**
     * Determine whether the user can view the job.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Job  $job
     * @return mixed
     */
    public function view(User $user, Job $job)
    {
        //
		if( $user->hasPermission('view.job'))return true;
		return true;
		
    }

    /**
     * Determine whether the user can create jobs.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
		if( $user->hasPermission('create.job'))return true;
		return true;
    }

    /**
     * Determine whether the user can update the job.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Job  $job
     * @return mixed
     */
    public function update(User $user, Job $job)
    {
        //
		if(!isset($job->user_id))return true;
		if( $user->hasPermission('update.job'))return true;
		return $user->id == $job->user_id;
    }

    /**
     * Determine whether the user can delete the job.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Job  $job
     * @return mixed
     */
    public function delete(User $user, Job $job)
    {
        //
		if(!isset($job->user_id))return true;
		if( $user->hasPermission('delete.job'))return true;
		return $user->id == $job->user_id;
    }

    /**
     * Determine whether the user can restore the job.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Job  $job
     * @return mixed
     */
    public function restore(User $user, Job $job)
    {
        //
		if(!isset($job->user_id))return true;
		if( $user->hasPermission('restore.job'))return true;
		return $user->id == $job->user_id;
    }

    /**
     * Determine whether the user can permanently delete the job.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Job  $job
     * @return mixed
     */
    public function forceDelete(User $user, Job $job)
    {
        //
		if(!isset($job->user_id))return true;
		if( $user->hasPermission('forcedelete.job'))return true;
		return $user->id == $job->user_id;
		
    }
}