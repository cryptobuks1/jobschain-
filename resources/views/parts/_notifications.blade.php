<div class="dropdown d-none d-md-flex">
    <a class="nav-link icon" data-toggle="dropdown"> <i class="fe fe-bell"></i> <span class="ncount badge badge-pill badge-danger">{{$user->unreadNotifications()->count()+ $user->notifs()->count()}}</span>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow notifications">
		@foreach($user->unreadNotifications()->limit(10)->get() as $alert )
        <a href="{{$alert->data['message']['url']}}?read={{$alert->id}}" class="dropdown-item d-flex"> <span class="avatar mr-3 align-self-center" style="background-image: url({{$alert->data['message']['image']}})"></span>
		
            <div> <strong>{{$alert->data['message']['heading']}}</strong>{{$alert->data['message']['text']}}
                <div datetime="{{$alert->created_at->toIso8601ZuluString()}}" class="small text-muted timeago">{{$alert->created_at}}</div>
            </div>
        </a>
		@endforeach
		<div class="dropdown-divider"></div>
		@foreach($user->notifs as $alert )
		@continue(is_null($alert->trade))
        <a href="{{route('trades.show',$alert->trade->uuid)}}?nread={{$alert->message->id}}" class="dropdown-item d-flex"> <span class="avatar mr-3 align-self-center" style="background-image: url({{$alert->message->sender->avatar}})"></span>
            <div> <strong>{{$alert->message->sender->name }}: </strong>{{str_limit($alert->message->body, 16)}}
                <div datetime="{{$alert->message->created_at->toIso8601ZuluString()}}" class="small text-muted timeago">{{$alert->message->created_at}}</div>
            </div>
        </a>
		@endforeach
		@if(auth()->user()->unreadNotifications()->count() == 0 && $user->notifs->count()==0)
		<a href="#" class="clearall dropdown-item text-center text-muted-dark">{{__('auth.no_notifications')}}</a> 
		@endif
		<div class="dropdown-divider"></div>
	</div>
</div>
@push('js')
<script>

function addnotification(notification){
	var message = notification.message;
	var id = notification.id;
	var html =' <a href="'+message.url+'?read='+id+'" class="dropdown-item d-flex"> <span class="avatar mr-3 align-self-center" style="background-image: url('+message.image+')"></span><div> <strong>'+message.heading+'</strong>'+message.text+'<div  datetime="'+message.created_at+'" class="small text-muted timeago"></div></div></a>';
	var notifs =  $('.notifications');
	notifs.prepend(html)
	var ncount =  notifs.find('a').length;
	$('.ncount').text(ncount);
	timeago.register('zopay_net', locales);
	var timeagoInstance = timeago();
	var nodes = document.querySelectorAll('.timeago');
	timeagoInstance.render(nodes, 'zopay_net');
}

function remove(notification){
	var notifs =  $('.notifications');
	notifs.remove('a#n'+notification.id)
	var ncount =  notifs.find('a').length;
	$('.ncount').text(ncount);
}

function clear_notification(){
	var notifs =  $('.notifications');
	notifs.empty();
	$('.ncount').text(0);
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.post('{{route('notifications.readall')}}',function(data){},'json');
}
</script>
@endpush
<script>
@push('documentReady')

	timeago.register('zopay_net', locales);
	var timeagoInstance = timeago();
	var nodes = document.querySelectorAll('.timeago');
	timeagoInstance.render(nodes, 'zopay_net');
	$('a.clearall').click(function(e) {
		clear_notification();
	});

@endpush
</script>