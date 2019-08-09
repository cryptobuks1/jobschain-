@extends( 'layouts.auth' )
@section( 'title' ) {
	{
		trans( 'laravel2step::laravel-verification.title' )
	}
}
@endsection

@push( 'styles' )
	. invalid - shake {
		-webkit - animation: kf_shake 0.8 s 1 linear; -
		moz - animation: kf_shake 0.8 s 1 linear; -
		o - animation: kf_shake 0.8 s 1 linear;
	}

@ - webkit - keyframes kf_shake {
	0 % {-webkit - transform: translate( 40 px );
	}
	20 % {-webkit - transform: translate( -40 px );
	}
	40 % {-webkit - transform: translate( 20 px );
	}
	60 % {-webkit - transform: translate( -20 px );
	}
	80 % {-webkit - transform: translate( 8 px );
	}
	100 % {-webkit - transform: translate( 0 px );
	}
}
@ - moz - keyframes kf_shake {
	0 % {-moz - transform: translate( 40 px );
	}
	20 % {-moz - transform: translate( -40 px );
	}
	40 % {-moz - transform: translate( 20 px );
	}
	60 % {-moz - transform: translate( -20 px );
	}
	80 % {-moz - transform: translate( 8 px );
	}
	100 % {-moz - transform: translate( 0 px );
	}
}
@ - o - keyframes kf_shake {
		0 % {-o - transform: translate( 40 px );
		}
		20 % {-o - transform: translate( -40 px );
		}
		40 % {-o - transform: translate( 20 px );
		}
		60 % {-o - transform: translate( -20 px );
		}
		80 % {-o - transform: translate( 8 px );
		}
		100 % {-o - origin - transform: translate( 0 px );
		}
	}
	. two - step - verification #failed_login_alert {
display: none;
}
. two - step - verification #failed_login_alert .glyphicon {
font - size: 6e m;
text - align: center;
display: block;
margin: .25e m 0 .75e m;
}
@endpush



@php
$remainingAttempts = 5;
switch ( $remainingAttempts ) {
	case 0:
	case 1:
		$remainingAttemptsClass = 'danger';
		break;

	case 2:
		$remainingAttemptsClass = 'warning';
		break;

	case 3:
		$remainingAttemptsClass = 'info';
		break;

	default:
		$remainingAttemptsClass = 'primary';
		break;
}
@endphp

@section( 'content' )
	<div class="sign-wrapper mg-lg-l-50 mg-xl-l-60">
		<div class="wd-100p">
			<div class="">
				@include('layouts.messages')
				<span class="d-flex justify-content-between">
						<h3 class="tx-color-01 mg-b-5 text-primary">{{__('app.please_verify')}}</h3> 
					<div><img height="30px"  src="/assets/images/Jobit.png"/></div>
				</span>
				<p id="verification_status_title" class="s-18 p-t-b-20 font-weight-lighter">@if(auth()->check() && !is_null(auth()->user()->twofa_secret)) {{ __('auth.entergooglecode') }} @else {{ __('A code was sent to your Email Address') }} @endif @if(env('DEMO'))({{session( 'authcode')}}) @endif</p>
				{!! Form::open(['class'=>'form-horizontal form-signin', "id"=>"verification_form", 'role' => 'form', 'method' => 'POST'] ) !!} @csrf
				<div class="form-group code-inputs">
					<div class="row row-xs">
						<div class="col-sm-2 px-2">
							<input type="text" id="v_input_1" class="form-control text-center required" required name="v_input_1" value="" autofocus maxlength="1" minlength="1" tabindex="1" placeholder="•">
						</div>
						<div class="col-sm-2 px-2">
							<input type="text" id="v_input_2" class="form-control text-center required" required name="v_input_2" value="" maxlength="1" minlength="1" tabindex="2" placeholder="•">

						</div>
						<div class="col-sm-2 px-2">
							<input type="text" id="v_input_3" class="form-control text-center required" required name="v_input_3" value="" maxlength="1" minlength="1" tabindex="3" placeholder="•">
						</div>
						<div class="col-sm-2 px-2">
							<input type="text" id="v_input_4" class="form-control text-center required" required name="v_input_4" value="" maxlength="1" minlength="1" tabindex="4" placeholder="•">
						</div>
						<div class="col-sm-2 px-2">
							<input type="text" id="v_input_5" class="form-control text-center required" required name="v_input_5" value="" maxlength="1" minlength="1" tabindex="5" placeholder="•">
						</div>
						<div class="col-sm-2 px-2">
							<input type="text" id="v_input_6" class="form-control text-center required last-input " required name="v_input_6" value="" maxlength="1" minlength="1" tabindex="6" placeholder="•">
						</div>

					</div>
				</div>
				<!-- form-group -->
				<button type="submit" class="btn btn-lg btn-{{ $remainingAttemptsClass }} btn-block" id="submit_verification" tabindex="7">
			<i class="fa fa-check" aria-hidden="true"></i>
			{{ trans('laravel2step::laravel-verification.verifyButton') }}
		</button>
			
				<p class="text-{{ $remainingAttemptsClass }} text-center h5 mt-2 remaining_attempts">
					<small>
				<span id="remaining_attempts">{{ $remainingAttempts }}</span> {{ trans_choice('laravel2step::laravel-verification.attemptsRemaining', $remainingAttempts) }}
			</small>
				
				</p>
				<div class="col-xs-12 text-center">
					<a class="btn btn-outline btn-primary btn-sm " id="resend_code_trigger" href="{{route('authentication.resend')}}" tabindex="6">
				{{ trans('laravel2step::laravel-verification.missingCode') }}
			</a>
				
				</div>


				<div id="failed_login_alert">
					<span class="glyphicon glyphicon-alert" aria-hidden="true"></span>
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
@stop
<script>
	@push( 'documentReady' )
	@php
	$minutesToExpire = config( 'laravel2step.laravel2stepExceededCountdownMinutes' );
	$hoursToExpire = $minutesToExpire / 60;
	@endphp
	//@include('laravel2step::scripts.input-parsing-auto-stepper');
	$( "input" ).on( "keydown", function ( event ) {
		if ( event.which == 8 ) {
			if ( !$( this ).hasClass( 'first-input' ) && $( this ).val() == "" ) {
				$( this ).parent().prev().find( 'input' ).focus();
			}
		}
	} );
	$( ".v_input" ).on( "input", function () {
		var self = $( this );
		var regexp = /[^a-zA-Z0-9]/g;
		var value = $( this ).val();
		if ( value.match( regexp ) ) {
			value = $( this ).val().replace( regexp, '' );
		}
		if ( value.length == 6 ) {
			$( '#v_input_1' ).val( value[ 0 ] );
			$( '#v_input_2' ).val( value[ 1 ] );
			$( '#v_input_3' ).val( value[ 2 ] );
			$( '#v_input_4' ).val( value[ 3 ] );
			$( '#v_input_5' ).val( value[ 4 ] );
			$( '#v_input_6' ).val( value[ 5 ] );
			$( '#submit_verification' ).focus();
			return;
		}
		if ( value.length > 1 ) {
			self.val( value[ 0 ] );
			if ( self.hasClass( 'last-input' ) ) {
				$( '#submit_verification' ).focus();
			} else {
				self.parent().next().find( 'input' ).focus();
			}
			return;
		}
		return;
	} );

	$( '.code-inputs' ).on( 'webkitAnimationEnd oanimationend msAnimationEnd animationend', function ( e ) {
		$( '.code-inputs' ).delay( 800 ).removeClass( 'invalid-shake' );
	} );

	$( "#submit_verification" ).click( function ( event ) {
		event.preventDefault();

		var formData = $( '#verification_form' ).serialize();

		$.ajax( {
			url: '{{route("authentication.verify")}}',
			type: "post",
			dataType: 'json',
			data: formData,
			success: function ( response, status, data ) {
				window.location.href = data.responseJSON.nextUri;
			},
			error: function ( response, status, error ) {
				if ( response.status === 418 ) {

					var remainingAttempts = response.responseJSON.remainingAttempts;
					var submitTrigger = $( '#submit_verification' );
					var varificationForm = $( '#verification_form' );
					$( '.code-inputs' ).addClass( 'invalid-shake' );
					varificationForm[ 0 ].reset();

					$( '.remaining_attempts' ).removeClass( 'text-success' ).removeClass( 'text-warning' ).removeClass( 'text-info' ).removeClass( 'text-danger' );
					$( '#remaining_attempts' ).text( remainingAttempts );

					switch ( remainingAttempts ) {
						case 0:
							submitTrigger.addClass( 'btn-danger' );
							$( '.remaining_attempts' ).addClass( 'text-danger' );
							$.sweetModal( {
								content: "{{ trans('laravel2step::laravel-verification.verificationLockedMessage') }}",
								icon: $.sweetModal.ICON_ERROR
							} );
							break;

						case 1:
							submitTrigger.addClass( 'btn-danger' );
							$( '.remaining_attempts' ).addClass( 'text-danger' );
							$.sweetModal( {
								content: "{{ trans('laravel2step::laravel-verification.verificationWarningMessage', ['hours' => $hoursToExpire, 'minutes' => $minutesToExpire,]) }}",
								icon: $.sweetModal.ICON_ERROR
							} );
							break;

						case 2:
							submitTrigger.addClass( 'btn-warning' );
							$( '.remaining_attempts' ).addClass( 'text-warning' );
							break;

						case 3:
							submitTrigger.addClass( 'btn-info' );
							$( '.remaining_attempts' ).addClass( 'text-info' );
							break;

						default:
							submitTrigger.addClass( 'btn-success' );
							$( '.remaining_attempts' ).addClass( 'text-success' );
							break;
					}

					if ( remainingAttempts === 0 ) {
						$( '#verification_status_title' ).html( '{{ trans('
							laravel2step::laravel - verification.titleFailed ') }}' );
						varificationForm.fadeOut( 100, function () {
							$( '#vhead' ).addClass( 'text-danger' ).text( 'ERROR !' )
								///$('#failed_login_alert').show();

							setTimeout( function () {
								$( 'body' ).fadeOut( 100, function () {
									location.reload();
								} );
							}, 3000 );
						} );
					};

				};
			}
		} );

	} );
	@endpush
</script>