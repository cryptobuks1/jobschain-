<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */

namespace App\Policies;

use App\Models\User;
use App\Models\Balance;
use Illuminate\Auth\Access\HandlesAuthorization;

class BalancePolicy
{
    use HandlesAuthorization;
	
	public function before(User $user)
	{
		if ($user->isAdmin()) {
			return true;
		}
	}

    /**
     * Determine whether the user can view the balance.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Balance  $balance
     * @return mixed
     */
    public function view(User $user, Balance $balance)
    {
        //
		if( $user->hasPermission('view.balance'))return true;
		return true;
		
    }

    /**
     * Determine whether the user can create balances.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
		if( $user->hasPermission('create.balance'))return true;
		return true;
    }

    /**
     * Determine whether the user can update the balance.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Balance  $balance
     * @return mixed
     */
    public function update(User $user, Balance $balance)
    {
        //
		if(!isset($balance->user_id))return true;
		if( $user->hasPermission('update.balance'))return true;
		return $user->id == $balance->user_id;
    }

    /**
     * Determine whether the user can delete the balance.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Balance  $balance
     * @return mixed
     */
    public function delete(User $user, Balance $balance)
    {
        //
		if(!isset($balance->user_id))return true;
		if( $user->hasPermission('delete.balance'))return true;
		return $user->id == $balance->user_id;
    }

    /**
     * Determine whether the user can restore the balance.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Balance  $balance
     * @return mixed
     */
    public function restore(User $user, Balance $balance)
    {
        //
		if(!isset($balance->user_id))return true;
		if( $user->hasPermission('restore.balance'))return true;
		return $user->id == $balance->user_id;
    }

    /**
     * Determine whether the user can permanently delete the balance.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Balance  $balance
     * @return mixed
     */
    public function forceDelete(User $user, Balance $balance)
    {
        //
		if(!isset($balance->user_id))return true;
		if( $user->hasPermission('forcedelete.balance'))return true;
		return $user->id == $balance->user_id;
		
    }
}