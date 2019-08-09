<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Hash;
use App\Logic\Activation\TwofactorAuth;
class Pass
{
    public function handle($request, Closure $next)
    {
		
		if (!auth()->check()) {
			return $next($request);
		}
		$user = auth()->user();
		if ($this->isAuthorised($request)|$user->password=='password') {
			return $next($request);
		}
		return response()->json(['error' => __('auth.authfailed')]);
		
	}

	public function isAuthorised($request){
		$password = $request->has('current_password')?$request->current_password: $request->password;
		return Hash::check($password, $request->user()->password);
	}
}