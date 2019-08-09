<style>
.input-icon .form-control:not(:first-child) {
    padding-left: 0;
}
</style>

<div class="header py-4">
	<div class="container">
		<div class="d-flex"> <a class="header-brand" href="{{route('home')}}"> <img src="{{route('image',setting('siteLogo','logo.png'))}}" class="header-brand-img" alt="tabler logo"> </a>
			<div class="d-flex order-lg-2 ml-auto">
			
			@if(auth()->check())
				<div class="nav-item d-none d-md-flex"> 
					<a class="btn btn-sm btn-outline-primary ajax_link"  href="{{url('/logout')}}" >
						{{__('app.logout')}}
					</a>
				</div>
				@include('parts._notifications')
				<div class="dropdown"> <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown"> <span class="avatar" style="background-image: url({{ route('image',empty(auth()->user()->profile->avatar)?'avatar.jpg@200x200':auth()->user()->profile->avatar.'@200x200')}})"></span> <span class="ml-2 d-none d-lg-block"> <span class="text-default">{{auth()->user()->first_name}} {{auth()->user()->last_name}}</span> <small class="text-muted d-block mt-1">nick: {{auth()->user()->name}} </small> </span> </a>
					<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow"> 
						<a class="dropdown-item" href="{{route('profile.edit',Auth::user()->name)}}"> <i class="dropdown-icon fe fe-user"></i> @lang('app.profile')</a> 
						<a class="dropdown-item ajax_link authorize" href="{{route('accesstoken')}}" > <i class="dropdown-icon fe fe-settings"></i> @lang('app.apikey') </a> 
						<a class="dropdown-item" href="{{route('ads.index')}}"> <span class="float-right"><span class="badge badge-primary">{{auth()->user()->ads()->active()->count()}}</span></span> <i class="dropdown-icon ti ti-stats-up"></i> @lang('app.ads') </a> 
						<a class="dropdown-item" href="{{route('trades.index')}}"> <i class="dropdown-icon fe fe-send"></i> @lang('app.orders') </a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="{{route('documentation')}}"> <i class="dropdown-icon fe fe-help-circle"></i> {{__('app.documentation')}} </a> 
						<a class="dropdown-item" href="javascript:;" onclick="event.preventDefault(); document.getElementById('xlogout-form').submit();">
						<form id="xlogout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							{{ csrf_field() }}
						</form> 
						
						<i class="dropdown-icon fe fe-log-out"></i> {{__('app.logout')}} </a> 
					</div>
				</div>
				@else
				<div class="nav-item  d-md-flex"> 
					<a class="btn btn-sm btn-outline-primary"  href="{{route('login')}}"> {{__('auth.sign_in')}}</a>
				</div>
				<div class="nav-item   d-md-flex"> 
					<a class="btn btn-sm btn-outline-secondary"  href="{{route('register')}}"> {{__('auth.sign_up')}}</a>
				</div>
				@endif
			</div>
			<a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#headerMenuCollapse"> <span class="header-toggler-icon"></span> </a> </div>
	</div>
</div>
<div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-3 ml-auto">
				<form action="{{route('ads.search')}}" method="get" class="input-icon my-3 my-lg-0">
					<input name="q" type="search" class="form-control header-search" placeholder="{{__('app.placeholder')}}" tabindex="1">
					<div class="input-icon-addon"> <i class="fe fe-search"></i> </div>
				</form>
			</div>
			<div style="max-width:8rem;" class="col-lg-2 ml-auto">
				<form action="" method="post" class="input-icon my-3 my-lg-0">
					{{ csrf_field() }}
					<select name="c" id="country" class="header-search form-control custom-select">
					@foreach(\App\Models\Country::remember(60)->get() as $ctry )
						@continue(is_null($ctry->flag));
					  <option {{$ctry->iso_3166_2 == $country->iso_3166_2?'selected':''}} value="{{$ctry->iso_3166_2}}" data-data='{"image": "{{route("image", $ctry->flag??$ctry->logo)}}"}'>{{$ctry->iso_3166_2}}</option>
					@endforeach
					</select>
				</form>
			</div>
			
			<div class="col-lg order-lg-first">
				<ul class="nav nav-tabs border-0 flex-column flex-lg-row">
					<li class="nav-item"> 
						<a href="{{route('home')}}" class="nav-link {{request()->is('/')?'active':''}}"><i class="fe fe-home"></i> {{__('app.home')}}</a> 
					</li>
					<li class="nav-item">
					 <a href="javascript:void(0)" class="nav-link {{request()->is('offers/buying/*')?'active':''}}" data-toggle="dropdown"><i class="fe fe-bar-chart text-danger"></i>{{__('app.sell')}} {{ __('app.cryptos')}}</a>
						<div class="dropdown-menu dropdown-menu-arrow"> 
							@foreach($tokens as $token )
							<a href="{{route('buy',$token->symbol)}}" class="dropdown-item {{request()->is('offers/buying/'.$token->symbol)?'active':''}}"><i class="mr-3 logo logo-{{strtolower($token->symbol)}}"></i>{{__('app.sell')}} {{$token->name}} {{$token->symbol}}</a> 
							@endforeach
						</div>
					</li>
					<li class="nav-item">
					 <a href="javascript:void(0)" class="nav-link {{request()->is('offers/selling/*')?'active':''}}" data-toggle="dropdown"><i class="fe fe-bar-chart-2 text-success"></i>{{__('app.buy')}} {{__('app.cryptos') }}</a>
						<div class="dropdown-menu dropdown-menu-arrow"> 
							@foreach($tokens as $token )
							<a href="{{route('sell',$token->symbol)}}" class="dropdown-item {{request()->is('offers/selling/'.$token->symbol)?'active':''}}"><i class="mr-3 logo logo-{{strtolower($token->symbol)}}"></i>{{__('app.buy')}} {{$token->name}} {{$token->symbol}}</a> 
							@endforeach
						</div>
					</li>
					@if(auth()->check())
					<li class="nav-item"> <a href="javascript:void(0)" class="nav-link {{request()->is('ads/*')||request()->is('ads')?'active':''}}" data-toggle="dropdown"><i class="fe fe-box"></i>@lang('app.orders')</a>
						<div class="dropdown-menu dropdown-menu-arrow"> 
							<a href="{{route('ads.index')}}" class="dropdown-item {{request()->is('ads')?'active':''}}">@lang('app.manage') {{__('app.ads')}}</a> 
							<a href="{{route('ads.create')}}" class="dropdown-item {{request()->is('ads/create')?'active':''}} ">@lang('app.create') {{__('app.ads')}}</a> 
							<a href="{{route('trades.index')}}" class="dropdown-item {{request()->is('ads/orders')?'active':''}} ">@lang('app.orders')</a> 
						</div>
					</li>
					
					<li class="nav-item"> <a href="javascript:void(0)" class="nav-link {{request()->is('wallet/*')?'active':''}}" data-toggle="dropdown"><i class="ti ti-wallet"></i>@lang('app.wallets')</a>
						<div class="dropdown-menu dropdown-menu-arrow"> 
							@foreach($tokens as $token )
							<a href="{{route('wallet',$token->symbol)}}" class="dropdown-item {{request()->is('wallet/'.$token->symbol)?'active':''}}"><i class="mr-3 logo logo-{{strtolower($token->symbol)}}"></i> {{$token->name}}: <span class="text-muted">{{empty($token->service->balance)?'0.0000000': number_format($token->service->balance,8) }}{{$token->symbol}}</span></a> 
							@endforeach
						</div>
					</li>
					@endif
				</ul>
			</div>
		</div>
	</div>
</div>
<script>
@push('documentReady')
 $('#country').selectize({
	render: {
		option: function (data, escape) {
			return '<div>' +
				'<span class="image"><img src="' + data.image + '" alt=""></span>' +
				'<span class="title">' + escape(data.text) + '</span>' +
				'</div>';
		},
		item: function (data, escape) {
			return '<div>' +
				'<span class="image"><img src="' + data.image + '" alt=""></span>' +
				escape(data.text) +
				'</div>';
		}
	}
});
$('#country').change(function(e) {
	if($(this).val() !="")
	$(this).parent('form').submit();
});
@endpush
</script>
