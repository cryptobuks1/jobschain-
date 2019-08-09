<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */

namespace App\Policies;

use App\Models\User;
use App\Models\Cv;
use Illuminate\Auth\Access\HandlesAuthorization;

class CvPolicy
{
    use HandlesAuthorization;
	
	public function before(User $user)
	{
		if ($user->isAdmin()) {
			return true;
		}
	}

    /**
     * Determine whether the user can view the cv.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cv  $cv
     * @return mixed
     */
    public function view(User $user, Cv $cv)
    {
        //
		if( $user->hasPermission('view.cv'))return true;
		return true;
		
    }

    /**
     * Determine whether the user can create cvs.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
		if( $user->hasPermission('create.cv'))return true;
		return true;
    }

    /**
     * Determine whether the user can update the cv.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cv  $cv
     * @return mixed
     */
    public function update(User $user, Cv $cv)
    {
        //
		if(!isset($cv->user_id))return true;
		if( $user->hasPermission('update.cv'))return true;
		return $user->id == $cv->user_id;
    }

    /**
     * Determine whether the user can delete the cv.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cv  $cv
     * @return mixed
     */
    public function delete(User $user, Cv $cv)
    {
        //
		if(!isset($cv->user_id))return true;
		if( $user->hasPermission('delete.cv'))return true;
		return $user->id == $cv->user_id;
    }

    /**
     * Determine whether the user can restore the cv.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cv  $cv
     * @return mixed
     */
    public function restore(User $user, Cv $cv)
    {
        //
		if(!isset($cv->user_id))return true;
		if( $user->hasPermission('restore.cv'))return true;
		return $user->id == $cv->user_id;
    }

    /**
     * Determine whether the user can permanently delete the cv.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cv  $cv
     * @return mixed
     */
    public function forceDelete(User $user, Cv $cv)
    {
        //
		if(!isset($cv->user_id))return true;
		if( $user->hasPermission('forcedelete.cv'))return true;
		return $user->id == $cv->user_id;
		
    }
}