@extends( 'layouts.auth' )
@section( 'title', __( 'app.verify_email' ) )
@section( 'content' )
	<div class="ht-100p d-flex flex-column align-items-center justify-content-center">
		<div class="wd-150 wd-sm-250 mg-b-30"><img src="/assets/images/Jobit.png" class="img-fluid" alt="">
		</div>
		@if (session('resent'))
		<div class="alert alert-success" role="alert">
			{{ __('app.link_resent') }}
		</div>
		@endif
		<h4 class="tx-20 tx-sm-24">{{__('app.verify_email_address')}}</h4>
		<p class="tx-color-03 mg-b-40">{{__('app.verify_email_address_desc')}}</p>
		<div class="tx-13 tx-lg-14 mg-b-40">
			<a href="" class="btn btn-success d-inline-flex align-items-center">{{__('app.resend_verification')}}</a>
			<a href="" class="btn btn-secondary d-inline-flex align-items-center mg-l-5">{{__('app.contact_support')}}</a>
		</div>
		<span class="tx-12 tx-color-03">{{__('app.already_verified')}} <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{__('app.logout_here')}} </a><form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form></span>
	</div>
@endsection