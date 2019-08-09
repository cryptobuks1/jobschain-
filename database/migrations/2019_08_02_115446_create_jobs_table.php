<?php
/**Envatic Crypto APP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->bigInteger('country_id')->nullable()->unsigned();
            $table->string('country')->nullable();
            $table->string('address',40)->nullable();
            $table->string('publickey',600)->nullable();
            $table->string('txid',300)->nullable();
			$table->bigInteger('blocktime')->nullable();
			$table->integer('confirmations')->default(0);
            $table->string('company_name',100);
            $table->string('title');
            $table->string('salary');
            $table->string('qualifications');
            $table->string('description',600);
            $table->string('category');
            $table->string('expirience')->nullable();
            $table->datetime('expiry')->nullable();
            $table->integer('count')->unsigned()->default(1);
            $table->enum('status', ["open","closed","filled"])->default('open');
            $table->boolean('active')->default(true);
			$table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade')->onUpdate('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('jobs');
    }
}
