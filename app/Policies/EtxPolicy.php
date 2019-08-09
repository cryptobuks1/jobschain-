<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */

namespace App\Policies;

use App\Models\User;
use App\Models\Etx;
use Illuminate\Auth\Access\HandlesAuthorization;

class EtxPolicy
{
    use HandlesAuthorization;
	
	public function before(User $user)
	{
		if ($user->isAdmin()) {
			return true;
		}
	}

    /**
     * Determine whether the user can view the etx.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Etx  $etx
     * @return mixed
     */
    public function view(User $user, Etx $etx)
    {
        //
		if( $user->hasPermission('view.etx'))return true;
		return true;
		
    }

    /**
     * Determine whether the user can create etxes.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
		if( $user->hasPermission('create.etx'))return true;
		return true;
    }

    /**
     * Determine whether the user can update the etx.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Etx  $etx
     * @return mixed
     */
    public function update(User $user, Etx $etx)
    {
        //
		if(!isset($etx->user_id))return true;
		if( $user->hasPermission('update.etx'))return true;
		return $user->id == $etx->user_id;
    }

    /**
     * Determine whether the user can delete the etx.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Etx  $etx
     * @return mixed
     */
    public function delete(User $user, Etx $etx)
    {
        //
		if(!isset($etx->user_id))return true;
		if( $user->hasPermission('delete.etx'))return true;
		return $user->id == $etx->user_id;
    }

    /**
     * Determine whether the user can restore the etx.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Etx  $etx
     * @return mixed
     */
    public function restore(User $user, Etx $etx)
    {
        //
		if(!isset($etx->user_id))return true;
		if( $user->hasPermission('restore.etx'))return true;
		return $user->id == $etx->user_id;
    }

    /**
     * Determine whether the user can permanently delete the etx.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Etx  $etx
     * @return mixed
     */
    public function forceDelete(User $user, Etx $etx)
    {
        //
		if(!isset($etx->user_id))return true;
		if( $user->hasPermission('forcedelete.etx'))return true;
		return $user->id == $etx->user_id;
		
    }
}