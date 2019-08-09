@extends('layouts.auth')
@section('title', 'Change Password')
@section('content')
<div class="sign-wrapper mg-lg-l-50 mg-xl-l-60">
	<div class="wd-100p">
		@include('layouts.messages')
		<span class="d-flex justify-content-between">
			<h3 class="tx-color-01 mg-b-5 text-primary">{{__('app.change_your_password')}}</h3> 
			<div><img height="30px"  src="/assets/images/Jobit.png"/></div>
		</span>
		  <form method="POST" action="{{ route('password.update') }}" >
        @csrf
			  <input type="hidden" name="token" value="{{ $token }}">
        @include('layouts.messages')
        <div class="form-group">
			<label>{{__('app.email_address')}}</label>
            <input type="email" placeholder="Your Email"  name="email" value="{{ '' }}" class="form-control" required>
        </div>
        <div class="form-group">
			<label>{{__('app.password')}}</label>
            <input type="password" placeholder="New Password" name="password" id="password" class="form-control" minlength="6" required>
        </div>
        <div class="form-group">
			<label>{{__('app.confirm_password')}}</label>
            <input type="password" placeholder="Again Password" name="password_confirmation" class="form-control" minlength="6" data-rule-equalTo="#password" required>
        </div>

        <div class="py-3"></div>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <button type="submit" class="btn btn-primary">{{__('app.reset_password')}}</button>
            </div>
            <div>
                <a href="{{ route('login') }}">{{__('app.return_to_login')}}</a>
            </div>
        </div>

    </form>
		
	</div>
</div>


@endsection
