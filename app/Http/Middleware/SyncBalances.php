<?php

namespace App\Http\Middleware;

use Closure;

class SyncBalances
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

		if(auth()->check()) {
			$user = auth()->user();
			$balance = $user->balance;
			if(!$balance && env('SYMBOL')){
				 $user->balance()->create([
					 'symbol'=> env('SYMBOL'),
					 'balance'=> '0.000',
				 ]);
			}
		}
        return $next($request);
    }
}
