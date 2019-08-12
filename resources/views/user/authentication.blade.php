@extends( 'layouts.app' )
@section( 'title','Edit Profile' )
@section( 'content' )
<div style="height: 113px;"></div>
<div class="unit-5 overlay" style="background-image: url('/assets/images/hero_1.jpg');">
	<div class="container text-center">
		<h2 class="mb-0">Edit Profile</h2>
		<p class="mb-0 unit-6"><a href="index.html">Home</a> <span class="sep">></span> <span>Manage Authentication</span>
		</p>
	</div>
</div>
<div class="site-section bg-light">
	<div id="app" class="container">
		<auth></auth>
	</div>
</div>
@endsection
<!-- .container -->