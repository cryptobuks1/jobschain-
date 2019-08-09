
<div class="footer">
       <div class="container">
          <div class="row">
            <div class="col-lg-8">
              <div class="row">
                <div class="col-6 col-md-3">
                  <ul class="list-unstyled mb-0">
                    <li><a class="{{request()->is('articles/about')?'text-primary':''}}" href="{{route('articles','about')}}">@lang('app.about')</a></li>
					<li><a class="{{request()->is('articles/careers')?'text-primary':''}}" href="{{route('articles','careers')}}">@lang('app.careers')</a></li>
                  </ul>
                </div>
                <div class="col-6 col-md-3">
                  <ul class="list-unstyled mb-0">
                    <li><a class="{{request()->is('articles/fees')?'text-primary':''}}" href="{{route('articles','fees')}}">@lang('app.fees')</a></li>
                    <li><a class="{{request()->is('articles/bounties')?'text-primary':''}}" href="{{route('articles','bounties')}}">@lang('app.bounties')</a></li>
                  </ul>
                </div>
                <div class="col-6 col-md-3">
                  <ul class="list-unstyled mb-0">
                    <li><a class="{{request()->is('articles/tos')?'text-primary':''}}" href="{{route('articles','tos')}}">@lang('app.tos')</a></li>
                    <li><a class="{{request()->is('articles/privacy')?'text-primary':''}}" href="{{route('articles','privacy')}}">@lang('app.privacy')</a></li>
                  </ul>
                </div>
                <div class="col-6 col-md-3">
                  <ul class="list-unstyled mb-0">
                     <li><a class="{{request()->is('articles/support')?'text-primary':''}}" href="{{route('articles','support')}}">@lang('app.support')</a></li>
                    <li><a  class="{{request()->is('articles/marketcap')?'text-primary':''}}" href="{{route('articles','marketcap')}}"> MarketCap </a></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-lg-4 mt-4 mt-lg-0">
              {{setting('footer_message')}}
            </div>
          </div>
        </div>
      </div>
	  
	 <footer class="footer">
        <div class="container">
          <div class="row align-items-center flex-row-reverse">
            <div class="col-auto ml-lg-auto">
              <div class="row align-items-center">
                <div class="col-auto">
                  <ul class="list-inline list-inline-dots mb-0">
				  	<li class="list-inline-item"><a href="{{setting('facebookpage')}}" class="btn btn-sm btn-icon btn-facebook"><i class="fe fe-facebook"></i></a></li>
					<li class="list-inline-item"><a href="{{setting('twitterhandle')}}" class="btn btn-sm btn-icon btn-twitter"><i class="fe fe-twitter"></i></a></li>
					<li class="list-inline-item"><a href="{{setting('googlepluspage')}}" class="btn btn-sm btn-icon btn-google"><i class="ti ti-google"></i></a></li>
					<li class="list-inline-item"><a href="{{setting('youtubepage')}}" class="btn btn-sm btn-icon btn-youtube"><i class="ti ti-youtube"></i></a></li>
					<li class="list-inline-item"><a href="{{setting('instagrampage')}}" class="btn  btn-sm btn-icon btn-instagram"><i class="fe fe-instagram"></i></a></li>
					<li class="list-inline-item"><a href="{{route('documentation','api')}}">@lang('app.api')</a></li>
                    <li class="list-inline-item"><a href="{{route('articles','faq')}}">@lang('app.faq')</a></li>
                  </ul>
                </div>
                <div class="col-auto">
                  <a href="{{route('articles','affiliates')}}" class="btn btn-outline-primary btn-sm">@lang('app.affiliate')</a>
                </div>
              </div>
            </div>
            <div class="col-12 col-lg-auto mt-3 mt-lg-0 text-center">
              Copyright © {{date('Y')}} <a href="{{route('articles','about')}}">{{env('APP_NAME')}}</a>.
            </div>
          </div>
        </div>
      </footer>