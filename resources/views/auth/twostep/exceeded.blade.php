@extends('layouts.auth')
@section('title', __('app.reset_password'))
@section('content')

<div class="signup-wrapper mg-lg-l-50 mg-xl-l-60">
	<div class="wd-100p text-center">
		<img class="mb-3" width="180px" src="/assets/images/Jobit.png" />
			 
		<div class="card p-5 verification-exceeded-card">
	<div class="card-heading text-center">
		<i class="icon-lock locked-icon text-danger tx-50" aria-hidden="true"></i>
		<h3>
			{{ trans('laravel2step::laravel-verification.exceededTitle') }}
		</h3>
	</div>
	<div class="card-body">
		<h4 class="text-center text-danger">
			<em>
				{{ trans('laravel2step::laravel-verification.lockedUntil') }}
			</em>
			<br />
			<strong>
				{{ $timeUntilUnlock }}
			</strong>
			<br />
			<small>
				{{ trans('laravel2step::laravel-verification.tryAgainIn') }} {{ $timeCountdownUnlock }} &hellip;
			</small>
		</h4>
		<p class="text-center">
			<a class="btn btn-info" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" tabindex="6">
				<i class="glyphicon glyphicon-home" aria-hidden="true"></i> {{ trans('laravel2step::laravel-verification.returnButton') }}
			</a>
			<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
				{{ csrf_field() }}
			</form>
		</p>
	</div>
</div>
	</div>
</div>
@endsection


