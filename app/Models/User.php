<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Laravel\Passport\HasApiTokens;
use Hexters\CoinPayment\Entities\CoinPaymentuserRelation;
class User extends Authenticatable
{
    use HasRoleAndPermission;
    use Notifiable;
    use SoftDeletes;
    use HasApiTokens;
    use CoinPaymentuserRelation;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
	
	protected $casts = [
		'activated'=>'boolean',
		'status'=>'boolean',
		'enable_twofa_email'=>'boolean',
		'enable_twofa_sms'=>'boolean',
	];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'activated',
        'token',
        'signup_ip_address',
        'signup_confirmation_ip_address',
        'signup_sm_ip_address',
        'admin_ip_address',
        'updated_ip_address',
        'deleted_ip_address',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'activated',
        'token',
    ];

    protected $dates = [
        'deleted_at',
    ];

    /**
     * Build Social Relationships.
     *
     * @var array
     */
    public function social()
    {
        return $this->hasMany('App\Models\Social');
    }

    public function balance() {
		return $this->hasOne( \App\Models\Balance::class, 'user_id', 'id' );
	}
	
	public function jobs() {
		return $this->hasOne( \App\Models\Job::class, 'user_id', 'id' );
	}
	
	public function cvs() {
		return $this->hasMany( \App\Models\Cv::class, 'user_id', 'id' );
	}
	public function msgs() {
		return $this->hasMany( \App\Models\Msg::class, 'user_id', 'id' );
	}
	
	public function txs() {
		return $this->hasMany( \App\Models\Tx::class, 'user_id', 'id' );
	}
	
	public function pools() {
		return $this->hasMany( \App\Models\Pool::class, 'user_id', 'id' );
	}
	
	public function etxs() {
		return $this->hasMany( \App\Models\Etx::class, 'user_id', 'id' );
	}
}
