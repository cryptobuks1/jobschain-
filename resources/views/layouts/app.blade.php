<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta http-equiv="Content-Language" content="en"/>
	<meta name="msapplication-TileColor" content="#2d89ef">
	<meta name="theme-color" content="#4188c9">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<link rel="icon" href="./favicon.ico" type="image/x-icon"/>
	<link rel="shortcut icon" type="image/x-icon" href="./favicon.ico"/>
	<title>@yield('title', config('app.name'))</title>
	<link href="https://fonts.googleapis.com/css?family=Amatic+SC:400,700|Work+Sans:300,400,700" rel="stylesheet">
	<link rel="stylesheet" href="/assets/fonts/icomoon/style.css">
	<link rel="stylesheet" href ="/assets/css/bs4.css">
	<link rel="stylesheet" href ="/assets/css/jquery-ui.css">
	<link rel="stylesheet" href ="/assets/css/bootstrap-datepicker.css">
	<link rel="stylesheet" href ="/assets/css/animate.css">
	<link rel="stylesheet" href="/assets/fonts/flaticon/font/flaticon.css">
	<link rel="stylesheet" href ="/assets/css/aos.css">
	<link rel="stylesheet" href ="/assets/css/style.css"> 
	<link rel="stylesheet" href ="/assets/css/utilities.css"> 
	<link rel="stylesheet" href ="/assets/css/custom.css">
	<link rel="stylesheet" href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome-font-awesome.min.css">
	@stack('css') {!!config('tracking.header_code')!!}
	<style>
		@stack('styles')
	</style>
</head>
<body>
	<div class="site-wrap">
		<div class="site-mobile-menu">
			<div class="site-mobile-menu-header">
				<div class="site-mobile-menu-close mt-3">
					<span class="icon-close2 js-menu-toggle"></span>
				</div>
			</div>
			<div class="site-mobile-menu-body"></div>
		</div>
		<!-- .site-mobile-menu -->
		<div class="site-navbar-wrap js-site-navbar bg-white">
			<div class="container">
				<div class="site-navbar bg-light">
					<div class="py-1">
						<div class="row align-items-center">
							<div class="col-2">
								<h2 class="mb-0 site-logo"><a href="/"><img class="img-h-40" src="/assets/images/Jobit.png"/></a></h2>
							</div>
							<div class="col-10">
								<nav class="site-navigation text-right" role="navigation">
									<div class="container">
										<div class="d-inline-block d-lg-none ml-md-0 mr-auto py-3"><a href="#" class="site-menu-toggle js-menu-toggle text-black"><span class="icon-menu h3"></span></a>
										</div>
										<ul class="site-menu js-clone-nav d-none d-lg-block">
											<li class="has-children">
												<a href="category.html">{{__('app.for_candidates')}}</a>
												<ul class="dropdown arrow-top">
													<li><a href="{{route('jobs.index')}}">{{__('app.browse_jobs')}}</a>
													</li>
													<li><a href="new-post.html">{{__('app.post_a_cv')}}</a>
													</li>
													<li><a href="#">{{__('app.buy_coin')}}</a>
													</li>
													<li><a href="#">{{__('app.view_offers')}}</a>
													</li>
												</ul>
											</li>
											<li class="has-children">
												<a href="category.html">{{__('app.for_employers')}}</a>
												<ul class="dropdown arrow-top">
													<li><a href="#">{{__('app.browse_candidates')}}</a>
													</li>
													<li><a href="{{route('jobs.create')}}">{{__('app.post_a_job')}}</a>
													</li>
													<li><a href="#">{{__('app.start_mining')}}</a>
													</li>
													<li><a href="#">{{__('app.buy_coin')}}</a>
													</li>
												</ul>
											</li>
											<li><a href="contact.html">{{__('app.contact')}}</a>
											</li>
											<li><a href="new-post.html"><span class="bg-primary text-white py-3 px-4 rounded"><span class="icon-plus mr-3"></span>{{__('app.post_a_new_job')}}</span></a>
											</li>
										</ul>
									</div>
								</nav>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		@yield('content')
		<footer class="site-footer">
			<div class="container">
				<div class="row">
					<div class="col-md-4">
						<h3 class="footer-heading mb-4 text-white">{{__('app.about')}}</h3>
						<p>{{__('app.about_desc')}}</p>
						<p><a href="#" class="btn btn-primary pill text-white px-4">{{__('app.get_started')}}</a>
						</p>
					</div>
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-6">
								<h3 class="footer-heading mb-4 text-white">{{__('app.quick_menu')}}</h3>
								<ul class="list-unstyled">
									<li><a href="#">{{__('app.white_paper')}}</a>
									</li>
									<li><a href="#">{{__('app.android',['symbol'=>config('coin.symbol')])}}</a>
									</li>
									<li><a href="#">{{__('app.ios',['symbol'=>config('coin.symbol')])}}</a>
									</li>
									<li><a href="#">{{__('app.careers')}}</a>
									</li>
								</ul>
							</div>
							<div class="col-md-6">
								<h3 class="footer-heading mb-4 text-white">{{__('app.menu')}}</h3>
								<ul class="list-unstyled">
									<li><a href="{{config('coin.explorer')}}">{{__('app.view_explorer')}}</a>
									</li>
									<li><a href="#">{{__('app.start_mining')}}</a>
									</li>
									<li><a href="route('purchase')">{{__('app.buy_coin',['symbol'=>config('coin.symbol')])}}</a>
									</li>
									<li><a href="route('home')">{{__('app.my_account')}}</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="col-md-12">
							<h3 class="footer-heading mb-4 text-white">Social Icons</h3>
						</div>
						<div class="col-md-12">
							<p>
								<a href="#" class="pb-2 pr-2 pl-0"><span class="icon-facebook"></span></a>
								<a href="#" class="p-2"><span class="icon-twitter"></span></a>
								<a href="#" class="p-2"><span class="icon-instagram"></span></a>
								<a href="#" class="p-2"><span class="icon-vimeo"></span></a>

							</p>
						</div>
					</div>
				</div>

			</div>
		</footer>
	</div>

	<script src="/assets/js/jquery-3.3.1.min.js"></script>
	<script src="/assets/js/jquery-migrate-3.0.1.min.js"></script>
	<script src="/assets/js/jquery-ui.js"></script>
	<script src="/assets/js/popper.min.js"></script>
	<script src="/assets/js/bootstrap.min.js"></script>
	<script src="/assets/js/jquery.stellar.min.js"></script>
	<script src="/assets/js/jquery.countdown.min.js"></script>
	<script src="/assets/js/bootstrap-datepicker.min.js"></script>
	<script src="/assets/js/aos.js"></script>
	<script src="/assets/js/main.js"></script>
</body>
</html>