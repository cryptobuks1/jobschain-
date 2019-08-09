@push('css')
<style>
label.myLabel input[type="file"] {
    position:absolute;
    top: -1000px;
}
</style>
@endpush
<div class="col-md-3">
	
    <div>
		@if(auth()->check())
        <div class="card card-profile mt-8">
            <div class="card-body text-center"> <img class="card-profile-img" src="{{route('image',empty(auth()->user()->profile->avatar)?'avatar.jpg':auth()->user()->profile->avatar)}}@80x80">
                <form id="xavatar" action="{{route('avatar.update')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <label class="myLabel btn btn-outline-primary btn-sm">
                        <input type="file" name="avatar" class="avatar" required />
                        <span class="fe fe-edit"></span> {{__('app.update')}} </label>
                </form>
                <h3 class="mt-4"><a href="{{route('edit.username')}}" class="ajax_link" data-onroute="{{request()->route()->getName()}}" inputs="username|Change Your Username|{{auth()->user()->name}}">{{auth()->user()->name}}</a></h3>
            </div>
        </div>
        
        <div class="list-group list-group-transparent mb-0 d-none d-lg-block">
            <a href="{{route('ads.index')}}" class="list-group-item list-group-item-action d-flex align-items-center {{request()->route()->getName()=='ads.index'?'active':''}}"> <span class="icon mr-3"><i class="fe fe-file"></i></span>{{__('app.ads')}} <span class="ml-auto badge badge-primary">{{auth()->user()->ads()->count()}}</span> </a>
			
            <a href="{{route('trades.index')}}" class="list-group-item list-group-item-action d-flex align-items-center {{request()->route()->getName()=='trades.index'?'active':''}}"> <span class="icon mr-3"><i class="fe fe-alert-circle"></i></span>@lang('app.open_orders') <span class="ml-auto badge badge-secondary">{{auth()->user()->trades()->count()}}</span> </a>
			
            <a href="{{route('profile.edit',Auth::user()->name)}}" class="list-group-item list-group-item-action d-flex align-items-center {{request()->route()->getName()=='profile.edit'?'active':''}}"> <span class="icon mr-3"><i class="fe fe-star"></i></span>@lang('app.edit_profile') </a>
			
            <a href="{{route('verify_phone')}}" class="list-group-item list-group-item-action d-flex align-items-center ajax_link verify_phone"> <span class="icon mr-3"><i class="fe fe-phone"></i></span>{{!empty(auth()->user()->phone_number)? auth()->user()->phone_number : trans('app.add_phone')}} </a>
			
          
			<a class="list-group-item list-group-item-action d-flex align-items-center @if(request()->route()->getName()=='authentication.edit') active @endif"  href="{{route('authentication.edit')}}"><span class="icon mr-3"><i class="fe fe-tag "></i> </span>@lang('app.authentication')  </a>
			
			<a href="{{route('verification')}}" class="list-group-item list-group-item-action d-flex align-items-center {{request()->is('verification')?'active':''}}"> <span class="icon mr-3"><i class="fe fe-user-plus"></i></span>@lang('app.verification') </a> 
			
			<a class="list-group-item list-group-item-action d-flex align-items-center @if(request()->route()->getName()=='users.security') active @endif"  href="{{route('users.security')}}"><span class="icon mr-3"><i class="fe fe-database "></i> </span>@lang('app.security')  </a>
			
			<a class="list-group-item list-group-item-action d-flex align-items-center @if(request()->route()->getName()=='users.sessions') active @endif"  href="{{route('users.sessions')}}"><span class="icon mr-3"><i class="fe fe-unlock "></i> </span>@lang('app.sessions')  </a>
			
		</div>
		<hr>
		@endif
			@include('articles.menu')
    </div>
</div>

<script>
@push('documentReady')

$('form#xavatar').on('change','input.avatar',function(e) {
	var form = $('form#xavatar');
	var url = form.attr('action');
	var formdata = new FormData(document.getElementById('xavatar'));
	console.log(form, formdata);
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	var txt = $('.ashow').text();
	$('.ashow').text('Uploading....');
	$.ajax({
		url: url,
		type: "POST",
		data: formdata,
		mimeTypes:"multipart/form-data",
		contentType: false,
		cache: false,
		processData: false,
		success: function(data){
			$('.ashow').text(txt);
			if(data.status =='ERROR'){
				return $.sweetModal({
					content:data.message,
					icon:$.sweetModal.ICON_ERROR,
					
					theme: $.sweetModal.THEME_LIGHT,
				});;
				return false;
			}
			else if(data.status =='OK'||data.status =='SUCCESS'){
				$('.card-profile-img').attr('src', data.update);
				return $.sweetModal({
					content:data.message,
					icon:$.sweetModal.ICON_SUCCESS,
					
					theme: $.sweetModal.THEME_LIGHT,
				});
			}
		},
		error: function(xhr, status, error) {
			$('.ashow').text(txt);
			var data = $.parseJSON(xhr.responseText);
			if(typeof data.message !=='undefined'){
				var option = { 
					content: data.message,
					icon:$.sweetModal.ICON_ERROR,
					
					theme: $.sweetModal.THEME_LIGHT,
				};
				return $.sweetModal(option);
			}else{
				return  $.sweetModal({
					content:'Indeterminate Error. Internet connection?',
					icon:$.sweetModal.ICON_ERROR,
					
					theme: $.sweetModal.THEME_LIGHT,
				});
			}
		}
	});
	
});
@endpush
</script>