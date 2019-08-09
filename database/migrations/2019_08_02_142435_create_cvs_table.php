<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cvs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('address',40);
            $table->string('publickey');
            $table->string('txid');
			$table->bigInteger('blocktime')->nullable();
			$table->integer('confirmations')->default(0);
            $table->string('qualifications',160)->nullable();
            $table->string('country',6)->nullable();
            $table->string('location',160)->nullable();
            $table->string('description',300);
            $table->string('salary');
            $table->datetime('expiry')->nullable();
            $table->tinyInteger('expirience')->default(0);
            $table->enum('type', ["full_time","part_time","freelance"])->default('full_time');
            $table->boolean('active')->default(true);
			$table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cvs');
    }
}
