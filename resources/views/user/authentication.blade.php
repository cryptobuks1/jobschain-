@extends('layouts.vuser') 
@section('title')  Coin Wallets
@endsection 
@section('content')
	<div id="app" class="container">
		<auth></auth>
	</div>
	<!-- .container -->
@endsection

@push('appjs')
<script src="{{ asset('assets/js/user.js')}}"></script>
@endpush