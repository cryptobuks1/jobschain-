<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
| Middleware options can be located in `app/Http/Kernel.php`
|
*/


##
Route::get('/candidates','candController@index')->name('candidates.index');
Route::get('/jobs','jobsController@create')->name('jobs.create');
Route::get('/jobs','jobsController@index')->name('jobs.index');
Route::get('/search','searchController@index')->name('search');

// Homepage Route
Route::group( [ 'middleware' => [ 'web', 'checkblocked' ] ], function () {
	Route::get( '/', 'WelcomeController@welcome' )->name( 'welcome' );
	Route::get( '/privacy-policy', 'WelcomeController@privacy' )->name( 'privacy' );
	Route::get( '/qrgen.png', 'WelcomeController@qrcode' )->name( 'public.qrgen' );

	
} );

// Authentication Routes
Auth::routes();

// Public Routes
Route::group( [ 'middleware' => [ 'web', 'activity', 'checkblocked' ] ], function () {

	// Activation Routes
	Route::get( '/activate', [ 'as' => 'activate', 'uses' => 'Auth\ActivateController@initial' ] );
	Route::get( '/activate/{token}', [ 'as' => 'authenticated.activate', 'uses' => 'Auth\ActivateController@activate' ] );
	Route::get( '/activation', [ 'as' => 'authenticated.activation-resend', 'uses' => 'Auth\ActivateController@resend' ] );
	Route::get( '/exceeded', [ 'as' => 'exceeded', 'uses' => 'Auth\ActivateController@exceeded' ] );
	// Socialite Register Routes
	Route::get( '/social/redirect/{provider}', [ 'as' => 'social.redirect', 'uses' => 'Auth\SocialController@getSocialRedirect' ] );
	Route::get( '/social/handle/{provider}', [ 'as' => 'social.handle', 'uses' => 'Auth\SocialController@getSocialHandle' ] );


	/// app frontend routse
} );






// Registered and Activated User Routes
Route::group( [ 'middleware' => [ 'web','auth', 'activated', 'activity', 'checkblocked' ] ], function () {

	// Activation Routes
	Route::get( '/activation-required', [ 'uses' => 'Auth\ActivateController@activationRequired' ] )->name( 'activation-required' );
	Route::get( '/logout', [ 'uses' => 'Auth\LoginController@logout' ] )->name( 'logout' );
	// two factor auth routes
	Route::get( '/authentication/needed', [
		'as' => 'users.authentication',
		'uses' => 'AuthenticationController@showVerification',
	] );

	Route::get( '/authentication/resend', [
		'as' => 'authentication.resend',
		'uses' => 'AuthenticationController@resend',
	] );

	Route::post( 'authentication/verify', [
		'as' => 'authentication.verify',
		'uses' => 'AuthenticationController@verify'
	] );

	Route::post( 'authentication/toggle-twofa-email', [
		'as' => 'toggle_twofa_email',
		'uses' => 'AuthenticationController@toggle_twofa_email'
	] );

	Route::post( 'authentication/toggle-twofa-sms', [
		'as' => 'toggle_twofa_sms',
		'uses' => 'AuthenticationController@toggle_twofa_sms'
	] );

	Route::post( 'authentication/disable-google-authenticator', [
		'as' => 'disable_google_authenticator',
		'uses' => 'AuthenticationController@disable_google_authenticator'
	] );

	Route::get( 'twofa_setup', [
		'as' => 'twofa_setup',
		'uses' => 'AuthenticationController@twofa_setup'
	] );

	Route::post( 'save_secret', [
		'as' => 'save_secret',
		'uses' => 'AuthenticationController@save_secret'
	] );

} );


// Registered, activated, and is current user routes.
Route::group( [ 'middleware' => ['web', 'auth', 'activated', 'currentUser', 'activity', 'twofactorauth', 'checkblocked' ] ], function () {
	Route::get( '/home', [
		'as' => 'home',
		'uses' => 'AppController@home'
	] );

	Route::get( '/authentication/edit', [
		'as' => 'authentication.edit',
		'uses' => 'AppController@authentication'
	] );

	Route::get( '/profile/edit', [
		'as' => 'profile.edit',
		'uses' => 'AppController@profile'
	] );
	Route::prefix( 'ajax' )->namespace( 'Ajax' )->group( function () {
		##wallets
		Route::get( '/user', 'BalancesController@user' )->name( 'balance.user' );
		Route::get( '/balance', 'BalancesController@index' )->name( 'balance.list' );
		Route::post( '/balance/send', 'BalancesController@send' )->name( 'balance.send' );
		Route::post( '/balance/deposit', 'BalancesController@move_to_deposit' )->name( 'balance.deposit' );
		Route::post( '/balance/setup', 'BalancesController@setup' )->name( 'balance.setup' );
		Route::post( '/balance/get_new_address', 'BalancesController@get_new_address' )->name( 'balance.get_new_address' );
		Route::post( '/balance/mnemonic', 'BalancesController@mnemonic' )->name( 'balance.mnemonic' );
		Route::post( '/user/update', 'BalancesController@updateuser' )->name( 'balance.user' );
		Route::post( '/user/update/password', 'BalancesController@updatepassword' )->name( 'balance.password' )->middleware( 'pass' );
	} );


} );


////user routes

Route::resource( 'jobs', 'JobsController', [
	'names' => [
		'index' => 'jobs.index',
		'create' => 'jobs.create',
		'store' => 'jobs.store',
		'show' => 'jobs.show',
	],
] );
Route::post( 'jobs/table', [ 'as' => 'jobs.table', 'uses' => 'JobsController@table' ] );
Route::post( 'jobs/toggle-status/{id}', [ 'as' => 'jobs.toggle_status', 'uses' => 'JobsController@toggle_status' ] );
Route::post( 'jobs/mass-toggle', [ 'as' => 'jobs.masstoggle', 'uses' => 'JobsController@toggle_statuses' ] );
Route::post( 'jobs/mass-delete', [ 'as' => 'jobs.massdelete', 'uses' => 'JobsController@delete' ] );

Route::resource( 'cvs', 'CvsController', [
		'names' => [
			'index' => 'cvs.index',
			'create' => 'cvs.create',
			'store' => 'cvs.store',
			'show' => 'cvs.show',
			'edit' => 'cvs.edit',
			'update' => 'cvs.update',
			'destroy' => 'cvs.destroy',
		],
	] );
	Route::post( 'cvs/table', [ 'as' => 'cvs.table', 'uses' => 'CvsController@table' ] );
	Route::post( 'cvs/toggle-status/{id}', [ 'as' => 'cvs.toggle_status', 'uses' => 'CvsController@toggle_status' ] );
	Route::post( 'cvs/mass-toggle', [ 'as' => 'cvs.masstoggle', 'uses' => 'CvsController@toggle_statuses' ] );
	Route::post( 'cvs/mass-delete', [ 'as' => 'cvs.massdelete', 'uses' => 'CvsController@delete' ] );

// Registered, activated, and is admin routes.
Route::prefix( 'admin' )->middleware( [
	'web',
	'auth',
	'activated',
	'role:admin',
	'activity',
	'twofactorauth',
	'checkblocked'
] )->name( 'admin.' )->namespace( 'Admin' )->group( function () {
	Route::get( '/', [
		'as' => 'home',
		'uses' => 'AppController@balances'
	] );

	Route::resource( 'jobs', 'JobsController', [
		'names' => [
			'index' => 'jobs.index',
			'create' => 'jobs.create',
			'store' => 'jobs.store',
			'show' => 'jobs.show',
			'edit' => 'jobs.edit',
			'update' => 'jobs.update',
			'destroy' => 'jobs.destroy',
		],
	] );
	Route::post( 'jobs/table', [ 'as' => 'jobs.table', 'uses' => 'JobsController@table' ] );
	Route::post( 'jobs/toggle-status/{id}', [ 'as' => 'jobs.toggle_status', 'uses' => 'JobsController@toggle_status' ] );
	Route::post( 'jobs/mass-toggle', [ 'as' => 'jobs.masstoggle', 'uses' => 'JobsController@toggle_statuses' ] );
	Route::post( 'jobs/mass-delete', [ 'as' => 'jobs.massdelete', 'uses' => 'JobsController@delete' ] );

	Route::prefix( 'ajax' )->namespace( 'Ajax' )->group( function () {
		##wallets
		Route::get( '/balances', 'AdminController@balances' )->name( 'balances' );
		Route::post( '/balances/{id}', 'AdminController@topup' )->name( 'topup' );
		Route::get( '/etxs', 'AdminController@etxs' )->name( 'etxs' );
		Route::delete( '/etxs/{id}', 'AdminController@destroyEtx' )->name( 'etx.delete' );
		Route::get( '/txs', 'AdminController@txs' )->name( 'txs' );
		Route::delete( '/txs/{id}', 'AdminController@destroyTx' )->name( 'tx.delete' );
		Route::delete( '/toggle/{id}', 'AdminController@toggle_user' )->name( 'toggle_user' );
		Route::get( '/classics', 'AdminClassicsController@index' )->name( 'classic.list' );
		Route::post( '/classics/many', 'AdminClassicsController@updateMany' )->name( 'classic.update.many' );
		Route::post( '/classics/{id}', 'AdminClassicsController@update' )->name( 'classic.update' );
		Route::get( '/redeems', 'AdminRedeemsController@index' )->name( 'redeem.list' );
		Route::post( '/redeems/many', 'AdminRedeemsController@updateMany' )->name( 'redeem.update.many' );
		Route::post( '/redeems/{id}', 'AdminRedeemsController@update' )->name( 'redeem.update' );
	} );

	##cvs
	Route::resource( 'cvs', 'CvsController', [
		'names' => [
			'index' => 'cvs.index',
			'create' => 'cvs.create',
			'store' => 'cvs.store',
			'show' => 'cvs.show',
			'edit' => 'cvs.edit',
			'update' => 'cvs.update',
			'destroy' => 'cvs.destroy',
		],
	] );
	Route::post( 'cvs/table', [ 'as' => 'cvs.table', 'uses' => 'CvsController@table' ] );
	Route::post( 'cvs/toggle-status/{id}', [ 'as' => 'cvs.toggle_status', 'uses' => 'CvsController@toggle_status' ] );
	Route::post( 'cvs/mass-toggle', [ 'as' => 'cvs.masstoggle', 'uses' => 'CvsController@toggle_statuses' ] );
	Route::post( 'cvs/mass-delete', [ 'as' => 'cvs.massdelete', 'uses' => 'CvsController@delete' ] );

	##deposits
	Route::resource( 'deposits', 'DepositsController', [
		'only' => [ 'index', 'destroy' ],
		'names' => [
			'index' => 'deposits.index',
			'destroy' => 'deposits.destroy',
		],
	] );
	Route::post( 'deposits/table', [ 'as' => 'deposits.table', 'uses' => 'DepositsController@table' ] );
	Route::post( 'deposits/toggle-status/{id}', [ 'as' => 'deposits.toggle_status', 'uses' => 'DepositsController@toggle_status' ] );
	Route::post( 'deposits/mass-toggle', [ 'as' => 'deposits.masstoggle', 'uses' => 'DepositsController@toggle_statuses' ] );
	Route::post( 'deposits/mass-delete', [ 'as' => 'deposits.massdelete', 'uses' => 'DepositsController@delete' ] );

	
	##msgs
	Route::resource( 'msgs', 'MsgsController', [
		'only' => [ 'index', 'destroy' ],
		'names' => [
			'index' => 'msgs.index',
			'destroy' => 'msgs.destroy',
		],
	] );
	Route::post( 'msgs/table', [ 'as' => 'msgs.table', 'uses' => 'MsgsController@table' ]);
	Route::post( 'msgs/toggle-status/{id}', [ 'as' => 'msgs.toggle_status', 'uses' => 'MsgsController@toggle_status' ] );
	Route::post( 'msgs/mass-toggle', [ 'as' => 'msgs.masstoggle', 'uses' => 'MsgsController@toggle_statuses' ] );
	Route::post( 'msgs/mass-delete', [ 'as' => 'msgs.massdelete', 'uses' => 'MsgsController@delete' ] );

	##balances
	Route::resource( 'balances', 'BalancesController', [
		'only' => [ 'index', 'destroy', 'update' ],
		'names' => [
			'index' => 'balances.index',
			'update' => 'balances.update',
			'destroy' => 'balances.destroy',
		],
	] );
	Route::post( 'balances/table', [ 'as' => 'balances.table', 'uses' => 'BalancesController@table' ] );
	Route::post( 'balances/toggle-status/{id}', [ 'as' => 'balances.toggle_status', 'uses' => 'BalancesController@toggle_status' ] );
	Route::post( 'balances/mass-toggle', [ 'as' => 'balances.masstoggle', 'uses' => 'BalancesController@toggle_statuses' ] );
	Route::post( 'balances/mass-delete', [ 'as' => 'balances.massdelete', 'uses' => 'BalancesController@delete' ] );

	##txs
	Route::resource( 'txs', 'TxsController', [
		'only' => [ 'index', 'destroy' ],
		'names' => [
			'index' => 'txs.index',
			'destroy' => 'txs.destroy',
		],
	] );
	Route::post( 'txs/table', [ 'as' => 'txs.table', 'uses' => 'TxsController@table' ] );
	Route::post( 'txs/toggle-status/{id}', [ 'as' => 'txs.toggle_status', 'uses' => 'TxsController@toggle_status' ] );
	Route::post( 'txs/mass-toggle', [ 'as' => 'txs.masstoggle', 'uses' => 'TxsController@toggle_statuses' ] );
	Route::post( 'txs/mass-delete', [ 'as' => 'txs.massdelete', 'uses' => 'TxsController@delete' ] );

	##etxs
	Route::resource( 'etxs', 'EtxsController', [
		'only' => [ 'index', 'destroy' ],
		'names' => [
			'index' => 'etxs.index',
			'destroy' => 'etxs.destroy',
		],
	] );
	Route::post( 'etxs/table', [ 'as' => 'etxs.table', 'uses' => 'EtxsController@table' ] );
	Route::post( 'etxs/toggle-status/{id}', [ 'as' => 'etxs.toggle_status', 'uses' => 'EtxsController@toggle_status' ] );
	Route::post( 'etxs/mass-toggle', [ 'as' => 'etxs.masstoggle', 'uses' => 'EtxsController@toggle_statuses' ] );
	Route::post( 'etxs/mass-delete', [ 'as' => 'etxs.massdelete', 'uses' => 'EtxsController@delete' ] );

	##addresses
	Route::resource( 'addresses', 'AddressesController', [
		'only' => [ 'index', 'destroy' ],
		'names' => [
			'index' => 'addresses.index',
			'destroy' => 'addresses.destroy',
		],
	] );
	Route::post( 'addresses/table', [ 'as' => 'addresses.table', 'uses' => 'AddressesController@table' ] );
	Route::post( 'addresses/toggle-status/{id}', [ 'as' => 'addresses.toggle_status', 'uses' => 'AddressesController@toggle_status' ] );
	Route::post( 'addresses/mass-toggle', [ 'as' => 'addresses.masstoggle', 'uses' => 'AddressesController@toggle_statuses' ] );
	Route::post( 'addresses/mass-delete', [ 'as' => 'addresses.massdelete', 'uses' => 'AddressesController@delete' ] );
	
} );
