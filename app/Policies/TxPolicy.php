<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */

namespace App\Policies;

use App\Models\User;
use App\Models\Tx;
use Illuminate\Auth\Access\HandlesAuthorization;

class TxPolicy
{
    use HandlesAuthorization;
	
	public function before(User $user)
	{
		if ($user->isAdmin()) {
			return true;
		}
	}

    /**
     * Determine whether the user can view the tx.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tx  $tx
     * @return mixed
     */
    public function view(User $user, Tx $tx)
    {
        //
		if( $user->hasPermission('view.tx'))return true;
		return true;
		
    }

    /**
     * Determine whether the user can create txes.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
		if( $user->hasPermission('create.tx'))return true;
		return true;
    }

    /**
     * Determine whether the user can update the tx.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tx  $tx
     * @return mixed
     */
    public function update(User $user, Tx $tx)
    {
        //
		if(!isset($tx->user_id))return true;
		if( $user->hasPermission('update.tx'))return true;
		return $user->id == $tx->user_id;
    }

    /**
     * Determine whether the user can delete the tx.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tx  $tx
     * @return mixed
     */
    public function delete(User $user, Tx $tx)
    {
        //
		if(!isset($tx->user_id))return true;
		if( $user->hasPermission('delete.tx'))return true;
		return $user->id == $tx->user_id;
    }

    /**
     * Determine whether the user can restore the tx.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tx  $tx
     * @return mixed
     */
    public function restore(User $user, Tx $tx)
    {
        //
		if(!isset($tx->user_id))return true;
		if( $user->hasPermission('restore.tx'))return true;
		return $user->id == $tx->user_id;
    }

    /**
     * Determine whether the user can permanently delete the tx.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tx  $tx
     * @return mixed
     */
    public function forceDelete(User $user, Tx $tx)
    {
        //
		if(!isset($tx->user_id))return true;
		if( $user->hasPermission('forcedelete.tx'))return true;
		return $user->id == $tx->user_id;
		
    }
}