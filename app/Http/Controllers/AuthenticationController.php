<?php

namespace App\Http\Controllers;

use Auth;
use \App\Models\User;
use \App\Http\Resources\User as UserResource;
use jeremykenedy\laravel2step\App\Models\TwoStepAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use PragmaRX\Google2FALaravel\Support\Authenticator;
use App\Traits\Laravel2StepTrait;
use Carbon\Carbon;
use Validator;
use App\Notifications\TwoFa;


class AuthenticationController extends Controller
{
	use Laravel2StepTrait;
	private $google;
	private $_authCount;
    private $_authStatus;
    private $_twoStepAuth;
    private $_remainingAttempts;
    private $_user;
	
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next)
        {
            $this->setUser2StepData();

            return $next($request);
        });
		$this->google = app(Authenticator::class)->boot(request());
    }
	
	 /**
     * Set the User2Step Variables
     *
     * @return void
     */
    private function setUser2StepData()
    {
        $user                       = Auth::User();
        $twoStepAuth                = $this->getTwoStepAuthStatus($user->id);
        $authCount                  = $twoStepAuth->authCount;
        $this->_user                = $user;
        $this->_twoStepAuth         = $twoStepAuth;
        $this->_authCount           = $authCount;
        $this->_authStatus          = $twoStepAuth->authStatus;
        $this->_remainingAttempts   = config('laravel2step.laravel2stepExceededCount') - $authCount;
    }
	
	/**
     * Reset TwoStepAuth collection item and code
     *
     * @param collection $twoStepAuth
     *
     * @return collection
     */
    private function resetAuthStatus($twoStepAuth)
    {
        $twoStepAuth->authCode    = $this->generateCode(6);
        $twoStepAuth->authCount   = 0;
        $twoStepAuth->authStatus  = 0;
        $twoStepAuth->authDate    = NULL;
        $twoStepAuth->requestDate = NULL;

        $twoStepAuth->save();

        return $twoStepAuth;
    }
	
	
	/**
     * Show the twostep verification form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showVerification()
    {
        if (!config('laravel2step.laravel2stepEnabled')) {
            abort(404);
        }

        $twoStepAuth = $this->_twoStepAuth;
        $authStatus  = $this->_authStatus;

        if ($this->checkExceededTime($twoStepAuth->updated_at)) {
            $this->resetExceededTime($twoStepAuth);
        }

        $data = [
            'user'              => $this->_user,
            'remainingAttempts' => $this->_remainingAttempts + 1,
        ];

        if ($this->_authCount > config('laravel2step.laravel2stepExceededCount')) {

            $exceededTimeDetails = $this->exceededTimeParser($twoStepAuth->updated_at);
            $data['timeUntilUnlock']     = $exceededTimeDetails['tomorrow'];
            $data['timeCountdownUnlock'] = $exceededTimeDetails['remaining'];
            return View('laravel2step::twostep.exceeded')->with($data);
        }

        $now = new Carbon();
        $sentTimestamp = $twoStepAuth->requestDate;
		if($this->google->isActivated()){
			$user = auth()->user();
			$code = \Cache::remember('code',3,function()use($user){
				return $this->google->getCurrentOtp($user->twofa_secret);
			});
			$twoStepAuth->authCode = $code;
			$twoStepAuth->save();
		}elseif (!$twoStepAuth->authCode) {
            $twoStepAuth->authCode = $this->generateCode(6);
            $twoStepAuth->save();
        }
		if(env('DEMO'))session(['authcode'=>$twoStepAuth->authCode]);
        if (!$sentTimestamp) {
            $this->sendVerificationCodeNotification($twoStepAuth);
        } else {
            $timeBuffer = config('laravel2step.laravel2stepTimeResetBufferSeconds');
            $timeAllowedToSendCode = $sentTimestamp->addSeconds($timeBuffer);
            if ($now->gt($timeAllowedToSendCode)) {
                $this->sendVerificationCodeNotification($twoStepAuth);
                $twoStepAuth->requestDate = new Carbon();
                $twoStepAuth->save();
            }
        }

        return View('laravel2step::twostep.verification')->with($data);
    }
	
	 /**
     * Resend the validation code triggered by user
     *
     * @return \Illuminate\Http\Response
     */
    public function resend()
    {
        if (!config('laravel2step.laravel2stepEnabled')) {
            abort(404);
        }
		$user = auth()->user();
        $twoStepAuth = $this->_twoStepAuth;
		if(!is_null($user->twofa_secret)){
			$user = auth()->user();
			$code = \Cache::remember('code',3,function()use($user){
				return $this->google->getCurrentOtp($user->twofa_secret);
			});
			$twoStepAuth->authCode = $code;
			$twoStepAuth->save();
		}
        $this->sendVerificationCodeNotification($twoStepAuth);
        return redirect()->back()->with('success',trans('laravel2step::laravel-verification.verificationEmailSentMsg'));

    }
	
	/**
     * Validation and Invalid code failed actions and return message
     *
     * @param array $errors (optional)
     *
     * @return array
     */
    private function invalidCodeReturnData($errors = null)
    {
        $this->_authCount = $this->_twoStepAuth->authCount += 1;
        $this->_twoStepAuth->save();

        $returnData = [
            'message'           => trans('laravel2step::laravel-verification.titleFailed'),
            'authCount'         => $this->_authCount,
            'remainingAttempts' => $this->_remainingAttempts,
        ];

        if ($errors) {
            $returnData['errors'] = $errors;
        }

        return $returnData;
    }

	
	protected function sendVerificationCodeNotification($twoStepAuth , $deliveryMethod = NULL)
    {
		
        $user = Auth::User();
        $user->notify(new TwoFa($twoStepAuth->authCode));
        $twoStepAuth->requestDate = Carbon::now();
        $twoStepAuth->save();
    }
	
	
	 /**
     * Verify the user code input
     *
     * @param  Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request)
    {
        if (!config('laravel2step.laravel2stepEnabled')) {
            abort(404);
        }

        if($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'v_input_1' => 'required|min:1|max:1',
                'v_input_2' => 'required|min:1|max:1',
                'v_input_3' => 'required|min:1|max:1',
                'v_input_4' => 'required|min:1|max:1',
				'v_input_5' => 'required|min:1|max:1',
				'v_input_6' => 'required|min:1|max:1',
            ]);

            if ($validator->fails()) {
                $returnData = $this->invalidCodeReturnData($validator->errors());
                return response()->json($returnData, 418);
            }
            $code  = $request->v_input_1 . $request->v_input_2 . $request->v_input_3 . $request->v_input_4.$request->v_input_5.$request->v_input_6;
            $validCode  = $this->_twoStepAuth->authCode;
			$valid = $validCode == $code;
			if(!$valid && $this->google->isActivated()){
				$valid = $this->google->verifyKey($request->user()->twofa_secret, $code, 8);
					
			}
			if(!$valid){
				$returnData = $this->invalidCodeReturnData();
				return response()->json($returnData, 418);
			}
            $this->resetActivationCountdown($this->_twoStepAuth);
            $returnData = [
                'nextUri' => session('nextUri', '/'),
                'message' => trans('laravel2step::laravel-verification.titlePassed'),
            ];
            return response()->json($returnData, 200);
        } else {
            abort(404);
        }
    }


	
	public function verify_phone()
    {
		$user = auth()->user();
        $code = \Cache::remember('sms_code',38,function(){
			return str_replace('0',7,strval(mt_rand(111111,999999)));
		});
		$phone = preg_replace("/[^\d]/","",request()->input('verify_phone'));
		\Cache::put('sms_'.$code,$phone,38);
		$response = \Notification::route(config('google2fa.sms_provider'), $phone)
		->notify(new \App\Notifications\Verification($code));
		return response()->json(['status'=>'SUCCESS', 'message'=>__('auth.sms_code_sent')]);
    }
	
	

    public function verify_code(Request $request)
    {
		$user = auth()->user();
        $code =$request->verify_code;
		$phone = \Cache::get('sms_'.$code);
		//dd($code, $phone);
		if(empty($phone)){
			return response()->json(['status'=>'ERROR', 'message'=>__('auth.sms_code_error')]);
		}
		$user->phone_number = $phone;
		$user->save();
		return response()->json(['status'=>'SUCCESS', 'message'=>__('auth.sms_code_verified')]);
    }
	
	
	public function twofa_setup(Request $request){
			$secretKey= $this->google->generateSecretKey();
			$inlineUrl = $this->getQRCodeInline(
				env('APP_NAME'),
				$request->user()->email,
				$secretKey
			);
			return [ 'secret'=>$secretKey, 'inlineUrl'=> $inlineUrl ];
	}
	
	

	
	public function getQRCodeInline($company, $holder, $secret, $size = 150, $encoding = 'utf-8')
    {
        $url = $this->google->getQRCodeUrl($company, $holder, $secret);
		$renderer = new ImageRenderer(
			new RendererStyle($size),
			new ImagickImageBackEnd()
		);
		$writer = new Writer($renderer);
		$data = $writer->writeString($url, $encoding);;
        return 'data:image/png;base64,'.base64_encode($data);
    }
	
	public function save_secret(Request $request)
    {
		$request->validate([
			'code'=>'required|numeric',
			'secret'=>'required'
		]);
		$user = auth()->user();
        $secret = $request->secret;
		$code = $request->code ;
		$valid = $this->google->verifyKey($secret, $code, 8);
		if(!$valid){
			return['error'=> __('auth.invalid2facode')];
		}
		$user->twofa_secret = $secret;
		$user->save();
		$this->google->login();
		return new UserResource($user);
    }
	
	public function deactivatetwofa(Request $request){
		$request->validate([
			'code'=>'required|numeric'
		]);
		$code = $request->code;
		$user = $request->user();
		$valid = $this->google->verifyKey($user->twofa_secret, $code, 8);
		if(!$valid){
			return['error'=> __('auth.invalid2facode')];
		}
		$user->twofa_secret = NULL;
		$user->save();
		return new UserResource($user);
	}
	
	
	public function toggle_twofa_email(Request $request){
		$user = $request->user();
		$user->enable_twofa_email = $user->enable_twofa_email?false:true;
		$user->save();
		return new UserResource($user);
	}
	
	public function toggle_twofa_sms(Request $request){
		$user = $request->user();
		$user->enable_twofa_sms = $user->enable_twofa_sms?false:true;
		$user->save();
		return new UserResource($user);
	}
	
	public function disable_google_authenticator(Request $request){
		$request->validate([
			'code'=>"required|min:6"
		]);
		$user = $request->user();
		$valid = $this->google->verifyKey($user->twofa_secret, $request->code, 8);
		if(!$valid){
			return['error'=>__('auth.invalid2facode')];
		}
		$user->twofa_secret = null;
		$user->save();
		return new UserResource($user);
	}
	
	

}
