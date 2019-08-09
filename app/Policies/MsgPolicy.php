<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */

namespace App\Policies;

use App\Models\User;
use App\Models\Msg;
use Illuminate\Auth\Access\HandlesAuthorization;

class MsgPolicy
{
    use HandlesAuthorization;
	
	public function before(User $user)
	{
		if ($user->isAdmin()) {
			return true;
		}
	}

    /**
     * Determine whether the user can view the msg.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Msg  $msg
     * @return mixed
     */
    public function view(User $user, Msg $msg)
    {
        //
		if( $user->hasPermission('view.msg'))return true;
		return true;
		
    }

    /**
     * Determine whether the user can create msgs.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
		if( $user->hasPermission('create.msg'))return true;
		return true;
    }

    /**
     * Determine whether the user can update the msg.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Msg  $msg
     * @return mixed
     */
    public function update(User $user, Msg $msg)
    {
        //
		if(!isset($msg->user_id))return true;
		if( $user->hasPermission('update.msg'))return true;
		return $user->id == $msg->user_id;
    }

    /**
     * Determine whether the user can delete the msg.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Msg  $msg
     * @return mixed
     */
    public function delete(User $user, Msg $msg)
    {
        //
		if(!isset($msg->user_id))return true;
		if( $user->hasPermission('delete.msg'))return true;
		return $user->id == $msg->user_id;
    }

    /**
     * Determine whether the user can restore the msg.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Msg  $msg
     * @return mixed
     */
    public function restore(User $user, Msg $msg)
    {
        //
		if(!isset($msg->user_id))return true;
		if( $user->hasPermission('restore.msg'))return true;
		return $user->id == $msg->user_id;
    }

    /**
     * Determine whether the user can permanently delete the msg.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Msg  $msg
     * @return mixed
     */
    public function forceDelete(User $user, Msg $msg)
    {
        //
		if(!isset($msg->user_id))return true;
		if( $user->hasPermission('forcedelete.msg'))return true;
		return $user->id == $msg->user_id;
		
    }
}