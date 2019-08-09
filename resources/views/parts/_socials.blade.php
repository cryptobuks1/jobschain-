<div class="row">
	@if(env('ENABLE_FACEBOOK'))
    <div class="col-sm-6 mt-2">
        {!! HTML::icon_link(route('social.redirect',['provider' => 'facebook']), 'mr-2 fe fe-facebook', 'Facebook', array('class' => 'btn btn-facebook btn-block icon-left')) !!}
	</div>
	@endif
	@if(env('ENABLE_GOOGLE'))
    <div class="col-sm-6 mt-2">
        {!! HTML::icon_link(route('social.redirect',['provider' => 'google']), 'mr-2 fe fe-user-plus', 'Google +', array('class' => 'btn btn-block icon-left btn-google')) !!}
    </div>
	@endif
	@if(env('ENABLE_TWITTER'))
    <div class="col-sm-6 mt-2">
        {!! HTML::icon_link(route('social.redirect',['provider' => 'twitter']), 'mr-2 fe fe-twitter', 'Twitter', array('class' => 'btn btn-block icon-left btn-twitter')) !!}
    </div>
	@endif
	@if(env('ENABLE_GITHUB'))
    <div class="col-sm-6 mt-2">
        {!! HTML::icon_link(route('social.redirect',['provider' => 'github']), 'mr-2 fe fe-github mr', 'Github', array('class' => 'btn btn-block icon-left btn-github')) !!}
    </div>
	@endif
	@if(env('ENABLE_YOUTUBE'))
    <div class="col-sm-6 mt-2">
        {!! HTML::icon_link(route('social.redirect',['provider' => 'youtube']), 'mr-2 fe fe-tv', 'Youtube', array('class' => 'btn btn-block icon-left btn-youtube')) !!}
    </div>
	@endif
	@if(env('ENABLE_INSTAGRAM'))
    <div class="col-sm-6 mt-2">
        {!! HTML::icon_link(route('social.redirect',['provider' => 'instagram']), 'mr-2 fe fe-instagram', 'Instagram', array('class' => 'btn btn-block icon-left btn-instagram')) !!}
    </div>
	@endif
	
</div>