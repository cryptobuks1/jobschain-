<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::table('users', function (Blueprint $table) {
			$table->string('phone_number')->after('token')->nullable();
			$table->boolean('enable_twofa_sms')->after('token')->default(1);
			$table->boolean('enable_twofa_email')->after('token')->default(1);
			$table->string('twofa_secret')->after('token')->nullable();
			$table->dateTime('last_seen')->nullable()->after('phone_number')->default(NULL);
			$table->boolean('status')->after('token')->default(true);
		});
    }
	
	

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
		Schema::table('users', function (Blueprint $table) {
			$table->drop('phone_number');
			$table->drop('last_seen');
			$table->drop('enable_twofa_sms');
			$table->drop('enable_twofa_email');
			$table->drop('twofa_secret');
			
		});
    }
}
