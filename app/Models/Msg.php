<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use App\Traits\LoggerTrait;
//use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Msg extends Model
{
    use SoftDeletes;
    
	//use LoggerTrait ,HasUuid;
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'msgs';

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

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
		'user_id', 
		'from_user_id', 
		'from_address', 
		'from_publicKey', 
		'to_address', 
		'to_publicKey', 
		'subject', 
		'encrypted', 
		'un_encrypted', 
		'txid', 
		'to_id', 
		'to_type', 
		'from_id', 
		'from_type', 
		'status'
	];

    public function recipient()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
    public function sender()
    {
        return $this->belongsTo(\App\Models\User::class, 'from_user_id', 'id');
    }
	public function from()
    {
        return $this->morphTo();
    }
    public function to()
    {
        return $this->morphTo();
    }
    
}
