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
            $table->string('user_address')->nullable();
            $table->string('user_publicKey',300)->nullable();
            $table->string('other_address')->nullable();
            $table->string('other_publicKey',300)->nullable();
            $table->enum('box',['inbox','outbox','drafts','spam'])->nullable();
            $table->string('subject',100)->nullable();
            $table->text('encrypted')->nullable();
            $table->text('un_encrypted');
            $table->morphs('entity');
            $table->enum('stream',['cvs','jobs']);
            $table->string('txid',199)->nullable();
            $table->bigInteger('blocktime')->nullable();
			$table->integer('confirmations')->default(0);
            $table->boolean('status')->default(true);
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
        Schema::drop('msgs');
    }
}
