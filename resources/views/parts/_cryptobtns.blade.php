<div class="row row-cards row-deck">
	@foreach( $dashBoardTokens as $token )
	@break($loop->iteration == 5)
	<div class="col-sm-6 col-lg-3">
		<div class="card p-3">
			<div class="d-flex align-items-center"> <span style="font-size:36; padding-top:3" class=" stamp stamp-md bg-blue mr-3"> <i class="logo logo-{{strtolower($token->symbol)}}"></i> </span>
				<div>
					<h4 class="m-0"><a href="{{route('ads.display', strtoupper($token->symbol))}}">{{strtoupper($token->symbol)}} <small>{{__('app.trades')}}</small></a></h4>
					<small class="text-muted">{{ucfirst($token->name)}}</small></div>
			</div>
		</div>
	</div>
	@endforeach
</div>