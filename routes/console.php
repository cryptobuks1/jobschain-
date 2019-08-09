<?php

use Illuminate\Foundation\Inspiring;
use BitWasp\Bitcoin\Address\AddressCreator;
use BitWasp\Bitcoin\Address\PayToPubKeyHashAddress;
use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Key\Factory\PrivateKeyFactory;
use BitWasp\Bitcoin\Key\Factory\PublicKeyFactory;
use BitWasp\Bitcoin\Network\NetworkFactory;
use BitWasp\Bitcoin\Crypto\Random\Random;
use BitWasp\Bitcoin\Key\Factory\HierarchicalKeyFactory;
use BitWasp\Bitcoin\Transaction\TransactionFactory;
/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/
use \App\Logic\CoinManager;
use \App\Logic\Redemption;
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');


Artisan::command('deposit:discover', function () {
     $cm = new CoinManager;
	 $cm-> discover();
})->describe('Discover Trasansactions');

Artisan::command('balance:update', function () {
     $cm = new CoinManager;
	 $cm->update_all_balance();
})->describe('Discover Trasansactions');

Artisan::command('deposit:confirm', function () {
     $cm = new CoinManager;
	 $cm-> tx_confirm();
})->describe('Confirm Transactions');

Artisan::command('import:all', function () {
     $api = (new CoinManager)->api();
	 $all = \App\Models\Address::all();
	foreach($all as $adr):
		$api->importaddress($adr->address,null,true);
	endforeach;
})->describe('Confirm Transactions');

Artisan::command('t:tx', function () { // test tx push
    $tx = \App\Models\Tx::find(1876);
	$tx->user->notify(new \App\Notifications\Deposit($tx));
})->describe('test tx push');

Artisan::command('c:ms', function () { // test tx pus
	$cm = new \App\Logic\CoinManager;
	$cm->checkStreams();
})->describe('test tx push');


