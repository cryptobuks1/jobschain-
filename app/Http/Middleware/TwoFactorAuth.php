<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use App\Traits\Laravel2StepTrait;
class TwoFactorAuth
{
    use Laravel2StepTrait;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $response
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		
        $response   = $next($request);
		if(!auth()->check()) return $response;
        $uri        = $request->path();
        $nextUri    = empty($uri )||$uri =='/'?$uri:config('app.url') . '/' .  $uri;
        switch ($uri) {
			case 'authentication/needed':
			case 'authentication/edit':
			case 'authentication/resend':
			case 'authentication/verify':
			case 'authentication/toggle-twofa-email':
			case 'authentication/toggle-twofa-sms':
			case 'authentication/disable-google-authenticator':
			case 'twofa_setup':
			case 'save_secret':
			case 'verify_phone':
			case 'verify_code':
            case 'password/reset':
            case 'register':
            case 'logout':
            case 'login':
                break;
            default:
                session(['nextUri' => $nextUri]);
                if (config('laravel2step.laravel2stepEnabled')) {
                    if ($this->twoStepVerification($request) !== true) {
                        return redirect('authentication/needed');
                    }
                }
                break;
        }
        return $response;
    }
}
