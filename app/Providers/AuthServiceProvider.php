<?php

namespace App\ Providers;

use Illuminate\ Foundation\ Support\ Providers\ AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
class AuthServiceProvider extends ServiceProvider {
	/**
	 * The policy mappings for the application.
	 *
	 * @var array
	 */
	protected $policies = [
		'App\Models\Job' => 'App\Policies\JobPolicy',
		'App\Models\Cv' => 'App\Policies\CvPolicy',
		'App\Models\Etx' => 'App\Policies\EtxPolicy',
		'App\Models\Tx' => 'App\Policies\TxPolicy',
		'App\Models\Balance' => 'App\Policies\BalancePolicy',
		'App\Models\Msg' => 'App\Policies\MsgPolicy',
		'App\Models\Address' => 'App\Policies\AddressPolicy',
		'App\Models\Deposit' => 'App\Policies\DepositPolicy',
	];

	/**
	 * Register any authentication / authorization services.
	 *
	 * @return void
	 */


	public function boot() {
		$this->registerPolicies();
		Passport::routes();
		Passport::tokensExpireIn( now()->addDays( 15 ) );
		Passport::refreshTokensExpireIn( now()->addDays( 30 ) );
		Passport::personalAccessTokensExpireIn( now()->addMonths( 6 ) );
	}
}