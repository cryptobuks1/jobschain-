@extends( 'layouts.auth' )
@section( 'title', __( 'app.activation_error' ) )
@section( 'content' )
	<div class="ht-100p d-flex flex-column align-items-center justify-content-center">
		<div class="wd-150 wd-sm-250 mg-b-30"><img src="/assets/images/Jobit.png" class="img-fluid" alt="">
		</div>
		<h1 class="text-danger tx-sm-24">{{__( 'app.activation_error' )}}</h1>
		<p class="text-danger tx-color-03 mg-b-40">{!! __('app.tooManyEmails', ['email' => 'ofuzak', 'hours' => 6]) !!}</p>
		<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{__('app.logout_here')}} </a><form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form></span>
	</div>
@endsection

