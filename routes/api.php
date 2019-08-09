<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});

Route::post('/register', [
	'as'=>'api_register',
	'uses'=>'ApiController@register'
]);
Route::post('/password/reset', [
	'as'=>'reset',
	'uses'=>'Auth\ForgotPasswordController@sendResetLinkEmail'
]);
Route::middleware('auth:api')->post('/balance/setup', [
	'as'=>'setup',
	'uses'=>'ApiController@setup'
]);

Route::middleware('auth:api')->post('/get_new_address', [
	'as'=>'get_new_address',
	'uses'=>'ApiController@get_new_address'
]);

Route::middleware('auth:api')->post('/send', [
	'as'=>'send',
	'uses'=>'ApiController@send'
]);
Route::middleware('auth:api')->post('/wallet', [
	'as'=>'wallet',
	'uses'=>'ApiController@index'
]);
Route::middleware('auth:api')->post('/update/password', [
	'as'=>'upassword',
	'uses'=>'ApiController@upassword'
]);
Route::middleware('auth:api')->post('/update/wpassword', [
	'as'=>'wpassword',
	'uses'=>'ApiController@wpassword'
]);
