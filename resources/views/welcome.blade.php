@extends('layouts.app')
@section('content')
<span id="app">
	<div class="site-blocks-cover overlay" style="background-image: url('/assets/images/hero_1.jpg');" >
		<div class="container">
			<div class="row align-items-center">
				<div class="col-12" data-aos="fade">
					<p class="slogan">{{__('app.slogan')}}</p>
					<h1>Search The JobChain</h1>
					<form action="#">
						<div class="row mb-3">
							<div class="col-md-9">
								<div class="row">
									<div class="col-md-6 mb-3 mb-md-0">
										<input type="text" class="mr-3 form-control border-0 px-4" placeholder="job title, keywords or company name ">
									</div>
									<div class="col-md-6 mb-3 mb-md-0">
										<div class="input-wrap"> <span class="icon icon-room"></span>
											<input type="text" class="form-control form-control-block search-input  border-0 px-4" id="autocomplete" placeholder="city, province or region" onFocus="geolocate()">
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<input type="submit" class="btn btn-search btn-primary btn-block" value="Search">
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<ul class="statistics margin-top-45 hide-under-992px">
									<li> <strong class="counter">3,586</strong> <span>{{__('app.jobs_posted')}}</span> </li>
									<li> <strong class="counter">4,543</strong> <span>{{__('app.CVs_Posted')}}</span> </li>
									<li> <strong class="counter">2,232</strong> <span>{{__('app.blocks')}}</span> </li>
								</ul>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="site-section bg-light py-4">
		<div class="container">
			<div class="row">
				<div class="col-md-12 mb-5 mb-md-0" data-aos="fade-up" data-aos-delay="100">
					<h2 class="mb-5 h3">{{__('app.recent_transactions')}}</h2>
					<div class="rounded border jobs-wrap"> 
						@foreach($jobs as $job )
						<a href="{{route('jobs.show',$job->id)}}" class="job-item d-block d-md-flex align-items-center  border-bottom fulltime">
						<div class="company-logo blank-logo text-center text-md-left pl-3"> 	@if(empty($job->company->logo))
							<span class="btn btn-icon btn-soft-secondary rounded-circle"><span class="btn-icon__inner text-dark">Tx</span>
							@else
							<img src="{{$job->company->logo}}" alt="Image" class="img-fluid mx-auto"> 
							@endif
						</div>
							
						<div class="job-details h-100">
							<div class="p-3 align-self-center">
								<h3 class="d-none d-lg-block">{{$job->txid}}</h3>
								<h3 class="d-lg-none">{{$job->txid_short}}</h3>
								<div class="d-block d-lg-flex">
									<div class="mr-3"><span class="icon-suitcase mr-1"></span> {{$job->company_name}}</div>
									<div class="mr-3"><span class="icon-room mr-1"></span> {{$job->title}}</div>
									<div><span class="icon-money mr-1"></span>{{ $job->salary}}</div>
									@if($job->expiry->greaterThan(now()))
									<div  class="mr-3 text-success" ><span class="icon-check mr-1"></span> {{__('app.active')}}</div>
									@else
									<div  class="mr-3 text-danger" ><span class="icon-times mr-1"></span> {{__('app.expired')}}</div>
									@endif
								</div>
							</div>
						</div>
						<div class="job-category align-self-center">
							<div class="p-3"> <span class="text-info p-2 rounded border border-info">{{$job->salary}}</span> </div>
						</div>
						</a> 
						@endforeach
					</div>
					<div class="col-md-12 text-center mt-5"> <a href="{{route('jobs.index')}}" class="btn btn-primary rounded py-3 px-5"><span class="icon-plus-circle"></span> {{__('app.view_explorer')}}</a> </div>
				</div>
			</div>
		</div>
	</div>
	<div class="site-section bg-light py-4">
		<div class="container">
			<div class="row">
				<div class="col-md-12 mb-5 mb-md-0" data-aos="fade-up" data-aos-delay="100">
					<h2 class="mb-5 h3">{{__('app.recent_cv_transactions')}}</h2>
					<div class="rounded border jobs-wrap"> 
						@foreach($cvs as $cv)
						<a href="{{route('cvs.show',$cv->id)}}" class="job-item d-block d-md-flex align-items-center  border-bottom fulltime">
						<div class="company-logo blank-logo text-center text-md-left pl-3"> 
							<span class="btn btn-icon btn-soft-secondary rounded-circle"><span class="btn-icon__inner text-dark">Cv</span>
						</div>
						<div class="job-details h-100">
							<div class="p-3 align-self-center">
								<h3 class="d-none d-lg-block">{{$cv->txid}}</h3>
								<h3 class="d-lg-none">{{$cv->txid_short}}</h3>
								<div class="d-block d-lg-flex">
									<div class="mr-3"><span class="icon-suitcase mr-1"></span> {{$cv->qualification}}</div>
									<div class="mr-3"><span class="icon-room mr-1"></span> {{$cv->location}}</div>
									<div class="mr-3"><span class="icon-clock-o mr-1"></span> {{$cv->expirience}}</div>
									<div><span class="icon-dollar mr-1"></span>{{$cv->salary}}</div>
								</div>
							</div>
						</div>
						<div class="job-category align-self-center">
							<div class="p-3"> <span class="text-info p-2 rounded border border-info">{{$cv->type}}</span> </div>
						</div>
						</a> 
						@endforeach
					</div>
					<div class="col-md-12 text-center mt-5"> <a href="{{route('cvs.index')}}" class="btn btn-primary rounded py-3 px-5"><span class="icon-plus-circle"></span> {{__('app.view_all_cvs')}}</a> </div>
				</div>
			</div>
		</div>
	</div>
	<div class="site-section">
		<div class="container">
			<div class="row">
				<div class="col-md-6 mx-auto text-center mb-5 section-heading">
					<h2 class="mb-5">{{__('app.categories')}}</h2>
				</div>
			</div>
			<div class="row">
				@foreach($categories as $cat)
				<div class="col-sm-6 col-md-4 col-lg-3 mb-3" data-aos="fade-up" data-aos-delay="100"> <a href="#" class="h-100 feature-item"> <span class="d-block icon flaticon-calculator mb-3 text-primary"></span>
					<h2>{{$cat->name}}</h2>
					<span class="counting">{{$cat->count}}</span></a> 
				</div>
				@endforeach
			</div>
		</div>
	</div>
	<div class="site-section site-block-feature bg-light">
		<div class="container">
			<div class="text-center mb-5 section-heading">
				<h2>{{__('app.why_the_blockachain_?')}}</h2>
			</div>
			<div class="d-block d-md-flex row border-bottom">
				<div class="text-center p-4 item border-right col-md-6" data-aos="fade"> 
					<span class="flaticon-worker display-3 mb-3 d-block text-primary"></span>
					<h2 class="h4">{{__('app.why1')}}</h2>
					<p>{{__('app.why1_desc',['chainname'=>config('coin.name')])}}</p>
					<p><a href="{{route('home')}}">{{__('app.get_started')}}<span class="icon-arrow-right small"></span></a></p>
				</div>
				<div class="text-center p-4 item col-md-6" data-aos="fade"> 
					<span class="flaticon-wrench display-3 mb-3 d-block text-primary"></span>
					<h2 class="h4">{{__('app.why2')}}</h2>
					<p>{{__('app.why2_desc')}}</p>
					<p><a href="{{route('home')}}">{{__('app.get_started')}}<span class="icon-arrow-right small"></span></a></p>
				</div>
			</div>
			<div class="d-block d-md-flex row ">
				<div class="text-center p-4 item border-right col-md-6" data-aos="fade"> <span class="flaticon-stethoscope display-3 mb-3 d-block text-primary"></span>
				
				
					<h2 class="h4">{{__('app.why3')}}</h2>
					<p>{{__('app.why3_desc')}}</p>
					<p><a href="{{route('home')}}">{{__('app.get_started')}}<span class="icon-arrow-right small"></span></a></p>
				</div>
				<div class="text-center p-4 item col-md-6" data-aos="fade"> <span class="flaticon-calculator display-3 mb-3 d-block text-primary"></span>
					<h2 class="h4">{{__('app.why4')}}</h2>
					<p>{{__('app.why4_desc')}}</p>
					<p><a href="{{route('home')}}">{{__('app.get_started')}}<span class="icon-arrow-right small"></span></a></p>
				</div>
			</div>
		</div>
	</div>
	</span>
@endsection
