
<div class="col-md-4">
	<div>
		<div class="card card-profile mt-8">
			<div class="card-body text-center">
			
			 <img class="card-profile-img" src="{{$user->avatar}}@80x80">
				 
				<h3 class="mt-4">{{$user->name}}</h3>
				<p class="mb-4"> {{$user->profile_info}}</p>
				<p class="mb-4"> {{$user->business_info}}</p>
				@if($user->isOnline())
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
						<td> @lang('ads.feedback_rating')</td>
						<td class="text-right"><span class="badge badge-{{$user->percentFeedback < 30 ?'danger':'success'}}">Risky {{100 - $user->percentFeedback }}%</span></td>
					</tr>
					<tr>
						<td>@lang('ads.email')</td>
						<td class="text-right"><span class="badge badge-success">@lang('ads.verified')</span></td>
					</tr>
					<tr>
						<td>@lang('ads.phone')</td>
						<td class="text-right"><span class="badge badge-{{empty($phone_number)?'danger':'success'}}">{{empty($phone_number)?trans('ads.unverified'):trans('ads.verified')}}</span></td>
					</tr>
					<tr>
						<td>@lang('ads.account_age') <a href="{{route('userprofile',$user->name)}}">Profile</a></td>
						<td class="text-right"><span class="badge badge-{{now()->diffInDays($user->created_at) > 30 ?'success':'danger'}}">{{$user->created_at->diffForHumans()}}</span></td>
					</tr>
					<tr>
						<td>@lang('ads.has_trades')</td>
						<td class="text-right"><span class="badge badge-default">{{$user->trades->count()}}</span></td>
					</tr>
				@if(auth()->check())
				<tr>
					<td>@lang('ads.has_trades_with_me')</td>
					<td class="text-right"><span class="badge badge-warning">{{$user->trades()->remember(20)->where('user_id', auth()->user()->id )->count()}}</span></td>
				</tr>
				@endif
					</tbody>
				
			</table>
		</div>
		@include('articles.menu')
	</div>
</div>
