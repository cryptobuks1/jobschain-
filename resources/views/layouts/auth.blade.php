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
	<link rel="stylesheet" href="/assets/css/bs4.css">
	<link rel="stylesheet" href="/assets/css/jquery-ui.css">
	<link rel="stylesheet" href="/assets/css/bootstrap-datepicker.css">
	<link rel="stylesheet" href="/assets/css/animate.css">
	<link rel="stylesheet" href="/assets/fonts/flaticon/font/flaticon.css">
	<link rel="stylesheet" href="/assets/css/aos.css">
	<link rel="stylesheet" href="/assets/css/style.css">
	<link rel="stylesheet" href="/assets/css/utilities.css"> 
	<link rel="stylesheet" href="/assets/css/custom.css"> 
	@stack('css') {!!config('tracking.header_code')!!}
	<style>
		ul,
		ol {
			list-style: none;
			padding: 0;
			margin: 0;
		}
		
		ul li,
		ol li {
			list-style: none;
		}
		
		
		
	
		@stack('styles')

	</style>
</head>

<body>
	<div class="content content-fixed auth">
		<div class="container mg-t-80">
			<div class="media align-items-stretch justify-content-center ht-100p pos-relative">

				@yield('content')

			</div>
			<!-- media -->
		</div>
		<!-- container -->
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