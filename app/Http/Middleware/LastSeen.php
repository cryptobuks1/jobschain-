<?php

namespace App\Http\Middleware;

use Closure;

class LastSeen
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
			$last = \Cache::remember('last_seen'.$user->id,5*60,function()use($user){
				$user->last_seen = now();
				$user->save();
				return now();
			});
			$expiresAt = \Carbon\Carbon::now()->addMinutes(15);
			$expiresOut = \Carbon\Carbon::now()->addMinutes(60);
			\Cache::put('user-is-online-' . $user->id, true, $expiresAt);
			\Cache::put('user-was-online-' . $user->id, true, $expiresOut);
		}
        return $next($request);
    }
}
