@extends('layouts.auth')
@section('title', __('app.reset_password'))
@section('content')
<div class="sign-wrapper mg-lg-l-50 mg-xl-l-60">
	<div class="wd-100p">
		@include('layouts.messages')
		<span class="d-flex justify-content-between">
			<h3 class="tx-color-01 mg-b-5 text-primary">{{__('app.reset_password')}}</h3> 
			<div><img height="30px"  src="/assets/images/Jobit.png"/></div>
		</span>
		<p class="tx-color-03 tx-16 mg-b-40">{{__('app.reset_password_message')}}</p>
		  <form method="POST" action="{{ route('password.email') }}" class="forgot-pass-form">
        @csrf
		<div class="form-group">
			<label>{{__('app.email_address')}}</label>
			<input type="email" class="form-control" placeholder="{{__('app.enter_your_email_address')}}">
		</div>
		
		<button class="btn btn-primary btn-block">{{__('app.send_reset_link')}}</button>
		<div class="hr-text">or</div>
		<div class="tx-13 mg-t-20 tx-center"><a href="{{route('login')}}">{{__('app.login_instead')}}</a> 
		</div>
	</div>
</div>
@endsection
