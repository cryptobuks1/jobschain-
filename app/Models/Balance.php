<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Balance extends Model
{
    use SoftDeletes;
    
	//use LoggerTrait ,HasUuid;
	/**
     * The database table used by the model.
     *
     * @var string
     */
    //protected $table = 'balances';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
	
	/**
     * Attributes that should be cast to native types.
     *
     * @var string
     */
    protected $casts = [
		//'active'=>'boolean'
	];
	
	protected $guarded = [
		'id',
		'password'
	];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
		'user_id', 
		'balance', 
		'xpub', 
		'xpriv', 
		'symbol', 
		'status'
	];
	
	public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
	
	public function addresses()
    {
        return $this->hasMany(\App\Models\Address::class, 'balance_id', 'id');
    }
	public function txs()
    {
        return $this->hasMany(\App\Models\Tx::class, 'balance_id', 'id');
    }
	public function etxs()
    {
        return $this->hasMany(\App\Models\Etx::class, 'balance_id', 'id');
    }
	
	public function redeems() {
		return $this->hasMany( \App\Models\Redeems::class, 'user_id', 'user_id' );
	}
	
	public function addr(){
		return $this->hasOne(\App\Models\Address::class)
			->latest()
			->where('status',0);
	}

	public function getAddressAttribute($key){
		if(is_null($this->addr()->first())){
			$coin = config('coin.manager');
			$chain = new $coin();
			$chain->deriveAddress($this);
			$address = $this->fresh();
			return $address->addr->address;
		}
		return $this->addr->address;
	}
    
}
