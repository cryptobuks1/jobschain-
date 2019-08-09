<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMsgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('msgs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('from_user_id')->unsigned();
            $table->string('from_address')->nullable();
            $table->string('from_publicKey',300)->nullable();
            $table->string('to_address')->nullable();
            $table->string('to_publicKey',300)->nullable();
            $table->string('subject',100)->nullable();
            $table->text('encrypted')->nullable();
            $table->text('un_encrypted');
            $table->morphs('to');
            $table->morphs('from');
            $table->string('txid',199)->nullable();
            $table->bigInteger('blocktime')->nullable();
			$table->integer('confirmations')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('msgs');
    }
}
