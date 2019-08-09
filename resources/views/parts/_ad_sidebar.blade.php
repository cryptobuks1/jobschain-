
<div class="col-md-4">
	<div>
		<div class="card card-profile mt-8">
			<div class="card-body text-center">
			
			 <img class="card-profile-img" src="{{route('image',empty($ad->user->profile->avatar)?'avatar.jpg':$ad->user->profile->avatar)}}@80x80">
				 
				<h3 class="mt-4"><a href="{{route('userprofile',$ad->user->name)}}">{{$ad->user->name}}</a></h3>
				@if($ad->user->isOnline())
				<button class="btn btn-outline-success btn-sm">ONLINE</button>
				@else
				<button class="btn btn-outline-danger btn-sm">OFFLINE</button>
				@endif
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h2 class="card-title">Security</h2>
			</div>
			<table class="table card-table">
				<tbody>
					<tr>
						<td> @lang('ads.payment_method')</td>
						<td class="text-right"><span class="badge badge-{{$ad->payment_method=='custom'?'danger':'success'}}">Risk {{$ad->payment_method=='custom'?'50%':'10%'}}</span></td>
					</tr>
					<tr>
						<td>@lang('ads.email')</td>
						<td class="text-right"><span class="badge badge-success">@lang('ads.verified')</span></td>
					</tr>
					<tr>
						<td>@lang('ads.phone')</td>
						<td class="text-right"><span class="badge badge-{{empty($ad->phone_number)?'danger':'success'}}">{{empty($ad->phone_number)?trans('ads.unverified'):trans('ads.verified')}}</span></td>
					</tr>
					<tr>
						<td>@lang('ads.account_age') <a href="{{route('userprofile',$ad->user->name)}}">Profile</a></td>
						<td class="text-right"><span class="badge badge-{{now()->diffInDays($ad->user->created_at) > 30 ?'success':'danger'}}">{{$ad->user->created_at->diffForHumans()}}</span></td>
					</tr>
					<tr>
						<td>@lang('ads.has_trades')</td>
						<td class="text-right"><span class="badge badge-default">{{$ad->user->trades->count()}}</span></td>
					</tr>
				@if(auth()->check())
				<tr>
					<td>@lang('ads.has_trades_with_me')</td>
					<td class="text-right"><span class="badge badge-warning">{{$ad->user->trades()->where('user_id', auth()->user()->id )->count()}}</span></td>
				</tr>
				@endif
					</tbody>
				
			</table>
		</div>
		@include('articles.menu')
	</div>
</div>
