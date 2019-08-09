@extends('layouts.auth')
@section('title', 'Sign up')
@section('content')



<div class="register-wrapper mg-lg-l-50 mg-xl-l-60">
				<div class="wd-100p">
					<span class="d-flex justify-content-between">
						<h3 class="tx-color-01 mg-b-5 text-primary">{{__('app.sign_up')}}</h3> 
						<div><img height="30px"  src="/assets/images/Jobit.png"/></div>
					</span>
					
					<p class="tx-color-03 tx-16 mg-b-40">{{__('app.sign_up_welcome')}}</p>
					
					
						 <form class="register-form" method="POST" action="{{ route('register') }}" id="register">
					<div class="row">
						<div class="form-group col-md-6">
							<label><i class="icon-user-circle mr-3"  ></i>{{__('app.full_name')}}</label>
							<input type="text" class="form-control" placeholder="John Doe">
						</div>
						<div class="form-group col-md-6">
							<label><i class="icon-envelope-o mr-3"  ></i> {{__('app.email')}}</label>
							<input type="email" class="form-control" placeholder="yourname@yourmail.com">
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-6">
							<label>{{__('app.password')}}</label>
							<input type="password" class="form-control" placeholder="Enter Strong password">
						</div>
						<div class="form-group col-md-6">
							<label>{{__('app.confirm_password')}}</label>
							<input type="password" class="form-control" placeholder="Repeat your password">
							<input name="terms" value="1" type="hidden">
						</div>
					</div>
							 
		
					<div class="row">
						 @if(env('ENABLE_RECAPTCHA'))
						<div class="col-md-6">
							{!! NoCaptcha::display() !!}
						</div>
						@endif
						<div class="col-md-6">
							<button class="btn btn-primary">{{__('app.register_account')}}</button>
						</div>
					</div>
					<div class="hr-text">or</div>
					</form>
					<button class="btn btn-outline-facebook"><i class="icon-facebook-f"> </i> {{__('app.sign_up_with_facebook')}}</button>
					<button class="btn btn-outline-twitter "><i class="icon-twitter"> </i>{{__('app.sign_up_with_twitter')}}</button>
					<span class="tx-13 tx-center">{{__('app.have_account')}} <a href="{{route('login')}}">{{__('app.sign_in')}}</a></span>
				</div>
			</div>
@endsection
@if(env('ENABLE_RECAPTCHA'))
@push('js')
 {!! NoCaptcha::renderJs() !!}
@endpush
@endif
