<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */

namespace App\Policies;

use App\Models\User;
use App\Models\Deposit;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepositPolicy
{
    use HandlesAuthorization;
	
	public function before(User $user)
	{
		if ($user->isAdmin()) {
			return true;
		}
	}

    /**
     * Determine whether the user can view the deposit.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Deposit  $deposit
     * @return mixed
     */
    public function view(User $user, Deposit $deposit)
    {
        //
		if( $user->hasPermission('view.deposit'))return true;
		return true;
		
    }

    /**
     * Determine whether the user can create deposits.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
		if( $user->hasPermission('create.deposit'))return true;
		return true;
    }

    /**
     * Determine whether the user can update the deposit.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Deposit  $deposit
     * @return mixed
     */
    public function update(User $user, Deposit $deposit)
    {
        //
		if(!isset($deposit->user_id))return true;
		if( $user->hasPermission('update.deposit'))return true;
		return $user->id == $deposit->user_id;
    }

    /**
     * Determine whether the user can delete the deposit.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Deposit  $deposit
     * @return mixed
     */
    public function delete(User $user, Deposit $deposit)
    {
        //
		if(!isset($deposit->user_id))return true;
		if( $user->hasPermission('delete.deposit'))return true;
		return $user->id == $deposit->user_id;
    }

    /**
     * Determine whether the user can restore the deposit.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Deposit  $deposit
     * @return mixed
     */
    public function restore(User $user, Deposit $deposit)
    {
        //
		if(!isset($deposit->user_id))return true;
		if( $user->hasPermission('restore.deposit'))return true;
		return $user->id == $deposit->user_id;
    }

    /**
     * Determine whether the user can permanently delete the deposit.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Deposit  $deposit
     * @return mixed
     */
    public function forceDelete(User $user, Deposit $deposit)
    {
        //
		if(!isset($deposit->user_id))return true;
		if( $user->hasPermission('forcedelete.deposit'))return true;
		return $user->id == $deposit->user_id;
		
    }
}