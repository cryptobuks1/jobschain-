<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */

namespace App\Policies;

use App\Models\User;
use App\Models\Address;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy
{
    use HandlesAuthorization;
	
	public function before(User $user)
	{
		if ($user->isAdmin()) {
			return true;
		}
	}

    /**
     * Determine whether the user can view the address.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Address  $address
     * @return mixed
     */
    public function view(User $user, Address $address)
    {
        //
		if( $user->hasPermission('view.address'))return true;
		return true;
		
    }

    /**
     * Determine whether the user can create addresses.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
		if( $user->hasPermission('create.address'))return true;
		return true;
    }

    /**
     * Determine whether the user can update the address.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Address  $address
     * @return mixed
     */
    public function update(User $user, Address $address)
    {
        //
		if(!isset($address->user_id))return true;
		if( $user->hasPermission('update.address'))return true;
		return $user->id == $address->user_id;
    }

    /**
     * Determine whether the user can delete the address.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Address  $address
     * @return mixed
     */
    public function delete(User $user, Address $address)
    {
        //
		if(!isset($address->user_id))return true;
		if( $user->hasPermission('delete.address'))return true;
		return $user->id == $address->user_id;
    }

    /**
     * Determine whether the user can restore the address.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Address  $address
     * @return mixed
     */
    public function restore(User $user, Address $address)
    {
        //
		if(!isset($address->user_id))return true;
		if( $user->hasPermission('restore.address'))return true;
		return $user->id == $address->user_id;
    }

    /**
     * Determine whether the user can permanently delete the address.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Address  $address
     * @return mixed
     */
    public function forceDelete(User $user, Address $address)
    {
        //
		if(!isset($address->user_id))return true;
		if( $user->hasPermission('forcedelete.address'))return true;
		return $user->id == $address->user_id;
		
    }
}