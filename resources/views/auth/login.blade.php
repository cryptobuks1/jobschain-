@extends( 'layouts.auth', [ 'title' => _( 'app.login' ) ] )
@section( 'title', __( 'app.sign_in' ) )
@section( 'content' )

<div class="sign-wrapper bd pd-25 bd-success">
	<div class="wd-100p">
		
		<span class="d-flex justify-content-between">
			<h3 class="tx-color-01 mg-b-5 text-primary">Sign In</h3> 
			<div><img height="30px"  src="/assets/images/Jobit.png"/></div>
		</span>
		
		<form method="post" action="{{route('login')}}">
			@csrf
			<p class="tx-color-03 tx-16 mg-b-40">{{__('app.signin_welcome')}}</p>
			@include('layouts.messages')
			<div class="form-group">
				<label>{{__('app.email_address')}}</label>
				<input type="email" name="email" class="form-control" placeholder="{{__('app.enter_your_email_address')}}">
			</div>
			<div class="form-group">
				<div class="d-flex justify-content-between mg-b-5">
					<label class="mg-b-0-f">{{__('app.password')}}</label>
					<a href="{{ route('password.request') }}" class="tx-13">{{__('app.forgot_password')}}</a>
				</div>
				<input type="password" name="password" class="form-control" placeholder="Enter your password">
			</div>
			<button class="btn btn-primary btn-block">{{__('app.sign_in')}}</button>
		</form>
		<div class="hr-text">or</div>
		<button class="btn btn-outline-facebook btn-block"><i class="icon-facebook-f"> </i> {{__('app.sign_in_with_facebook')}}</button>
		<button class="btn btn-outline-twitter btn-block"><i class="icon-twitter"> </i> {{__('app.sign_in_with_twitter')}}</button>
		<div class="tx-13 mg-t-20 tx-center">{{__('app.dont_have_account')}}<a class="ml-3" href="{{route('register')}}">{{__('app.create_an_account')}}</a>
		</div>
	</div>
</div>

@endsection