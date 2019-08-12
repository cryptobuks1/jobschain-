<?php


return [
	'active' => true,
	'symbol' => env( 'SYMBOL','JBT' ),
	'name' => env( 'NAME','JoBit' ),
	'decimals' => 8,
	'jobstream' => env( 'JOBSTREAM','jobs' ),
	'cvstream' => env( 'CVSTREAM','jobs' ),
	'msgstream' => env( 'MSGTREAM','msgs' ),
	'item_valid_days' => env( 'ITEM_VALID_DAYS', 7 ),
	'ticker' => env( 'SYMBOL','JBT' ),
	'explorer' => env( 'EXPLORER','https://envatic.con/#' ),
	'type' => 'rpc',
	'rpc' => [ // required if the type is rpc
		'url' => env( 'RPC_URL' ),
		'user' => env( 'RPC_USER' ),
		'password' => env( 'RPC_PASS' )
	],
	'network' => [
		'addressByte' => env( 'ADDRESS_BYTE' ),
		'p2shByte'=>env( 'P2SH_BYTE' ),
		'privByte' => env( 'PRIV_BYTE' ),
		'hdPubByte' => env( 'HD_PUB_BYTE' ),
		'hdPrivByte' => env( 'HD_PRIV_BYTE' ),
		'p2pMagic' => env( 'P2P_MAGIC' ),
		'SegwitBech32Prefix' =>env( 'SEGWIT_BENCH32_PREFIX',NULL ),
		'signedMessagePrefix' => env( 'SIGNED_MESSAGE_PREFIX' ),
	],
	'manager'=> \App\Logic\CoinManager::class
];