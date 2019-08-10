@extends('layouts.app') 
@section('title')  Coin Wallets
@endsection 
@section('content')
	<div id="app" class="container">
		
	</div>
    <!-- .container -->
    
      
  <div class="site-wrap">

    <div class="site-mobile-menu">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close mt-3">
          <span class="icon-close2 js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body"></div>
    </div> <!-- .site-mobile-menu -->
    
    <div class="site-navbar-wrap js-site-navbar bg-white">    
      <div class="container">
        <div class="site-navbar bg-light">
          <div class="py-1">
            <div class="row align-items-center">
              <div class="col-2">
                <h2 class="mb-0 site-logo"><a href="index.html">Job<strong class="font-weight-bold">Finder</strong> </a></h2>
              </div>
              <div class="col-10">
                <nav class="site-navigation text-right" role="navigation">
                  <div class="container">
                    <div class="d-inline-block d-lg-none ml-md-0 mr-auto py-3"><a href="#" class="site-menu-toggle js-menu-toggle text-black"><span class="icon-menu h3"></span></a></div>

                    <ul class="site-menu js-clone-nav d-none d-lg-block">
                      <li><a href="categories.html">For Candidates</a></li>
                      <li class="has-children">
                        <a href="category.html">For Employees</a>
                        <ul class="dropdown arrow-top">
                          <li><a href="category.html">Category</a></li>
                          <li><a href="#">Browse Candidates</a></li>
                          <li><a href="{{'/create'}}">Post a Job</a></li>
                          <li><a href="#">Employeer Profile</a></li>
                          <li class="has-children">
                            <a href="#">More Links</a>
                            <ul class="dropdown">
                              <li><a href="{{'/create'}}">Browse Candidates</a></li>
                              <li><a href="{{'/create'}}">Post a Job</a></li>
                              <li><a href="#">Employeer Profile</a></li>
                            </ul>
                          </li>

                        </ul>
                      </li>
                      <li><a href="contact.html">Contact</a></li>
                      <li><a href="new-post.html"><span class="bg-primary text-white py-3 px-4 rounded"><span class="icon-plus mr-3"></span>Post New Job</span></a></li>
                    </ul>
                  </div>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  
    <div style="height: 113px;"></div>
    <div class="unit-5 overlay" style="background-image: url('/assets/images/hero_1.jpg');">
      <div class="container text-center">
        <h2 class="mb-0"><a href="#">Curriculum<strong class="font-weight-bold"> vitae</strong> </a></h2>  
      </div>
    </div>
    <div class="site-section bg-light">
      <div class="container">
        <div class="row">  
          <div class="col-md-12 col-lg-8 mb-5">  
           {{--  <form action="{{'/'}}" class="p-5 bg-white">  --}}
                <form method="POST" action="{{ route('cvs.store') }}" accept-charset="UTF-8" class="p-5 bg-white ajax_form" enctype="multipart/form-data">            
               @csrf
                    <div class="row form-group">                     
              </div>
              {{--name  --}}
              <div class="row form-group">
                <div class="col-md-12 mb-3 mb-md-0">
                  <label class="font-weight-bold" for="salary">Salary</label>
                  <input type="text" id="salary" name="salary" class="form-control" placeholder="$200">
                </div>
              </div>
               {{--name  --}}
{{-- 
              <div class="row form-group">
                <div class="col-md-12 mb-3 mb-md-0">
                  <label class="font-weight-bold" for="fullname">Job Title</label>
                  <input type="text" id="fullname" name=" class="form-control" placeholder="eg. Full Stack Frontend">
                </div>
              </div>
 --}}

<div class="row form-group mb-5">
    <div class="col-md-12 mb-3 mb-md-0">
      <label class="font-weight-bold" for="fullname">Qualifications</label>
    
      <input type="file" id="myFile" name="description" class="form-control">

 
    </div>
  </div>

{{-- 
              <div class="row form-group mb-5">
                <div class="col-md-12 mb-3 mb-md-0">
                  <label class="font-weight-bold" for="fullname">Company</label>
                  <input type="text" id="fullname" class="form-control" placeholder="eg. Facebook, Inc.">
                </div>
              </div> --}}

              {{-- <div class="row form-group">
                <div class="col-md-12"><h3>Job Type</h3></div>
                <div class="col-md-12 mb-3 mb-md-0">
                  <label for="option-job-type-1">
                    <input type="radio" id="option-job-type-1" name="job-type"> Full Time
                  </label>
                </div>
                <div class="col-md-12 mb-3 mb-md-0">
                  <label for="option-job-type-2">
                    <input type="radio" id="option-job-type-2" name="job-type"> Part Time
                  </label>
                </div>

                <div class="col-md-12 mb-3 mb-md-0">
                  <label for="option-job-type-3">
                    <input type="radio" id="option-job-type-3" name="job-type"> Freelance
                </div>
                <div class="col-md-12 mb-3 mb-md-0">
                  <label for="option-job-type-4">
                    <input type="radio" id="option-job-type-4" name="job-type"> Internship
                  </label>
                </div>
                <div class="col-md-12 mb-3 mb-md-0">
                  <label for="option-job-type-4">
                    <input type="radio" id="option-job-type-4" name="job-type"> Termporary
                  </label>
                </div>

              </div> --}}

              <div class="row form-group mb-4">
                <div class="col-md-12"><h3>Address</h3></div>
                <div class="col-md-12 mb-3 mb-md-0">
                  <input type="text" class="form-control" name ="address" placeholder="New York City">
                </div>
              </div>

              <div class="row form-group">
                <div class="col-md-12"><h3>Job Description</h3></div>
                <div class="col-md-12 mb-3 mb-md-0">
                  <textarea name="" class="form-control" id="" cols="30" rows="5"></textarea>
                </div>
              </div>

              <div class="row form-group">
                <div class="col-md-12">
                  <input type="submit" value="Post CV" class="btn btn-primary  py-2 px-5">
                </div>
              </div>
            </form>
          </div>

          <div class="col-lg-4">
            <div class="p-4 mb-3 bg-white">
              <h3 class="h5 text-black mb-3">Wallet balance</h3>
              <p class="mb-0 font-weight-bold">Confirmed</p>
              <p class="h3 mb-4 text-primary">34.09765 JBT</p>
              <p class="mb-0 font-weight-bold">Unconfirmed</p>
              <p class="mb-4"><a href="#">90.8756JBT</a></p>
            </div>
            
            <div class="p-4 mb-3 bg-white">
              <h3 class="h5 text-black mb-3">Send JBT</h3>
              <div class="row form-group mb-2">
                <div class="col-md-12 mb-3 mb-md-0">
                  <input type="text" id="address" class="form-control" placeholder="Jbt Address">
                </div>
              </div>
				
			  <div class="row form-group mb-2">
                <div class="col-md-12 mb-3 mb-md-0">               
                  <input type="text" id="amount" class="form-control" placeholder="Jbt Amount">
                </div>
              </div>
			<div class="row form-group mb-5">
                <div class="col-md-12 mb-3 mb-md-0">         
                  <input type="text" id="password" class="form-control" placeholder="Password">
                </div>
              </div>
              <p><a href="#" class="btn btn-primary btn-block  py-2 px-4">Send JBT</a></p>
            </div>
			<div class="p-4 mb-3 bg-white">
              <h3 class="h5 text-black mb-3">Receive JBT</h3>
              <a href="#" class="mb-0 font-weight-bold">1K5ZuGCoi8u1tkh6yC7RHSw21HhRAP4ZAJ</a>
              <p class="text-info">Copied!</p>
			<div class="row d-flex justify-content-center my-4">
             <img src="/assets/images/qrcode.png"/>
			</div>
              <p class="mb-4"><a href="#">Get New Address <i class="fa fa-refresh fa-spin"></i></a></p>

            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="site-section">
      <div class="container">
        <div class="row justify-content-center text-center mb-5">
          <div class="col-md-6" data-aos="fade" >
            <h2>Frequently Ask Questions</h2>
          </div>
        </div>

        <div class="row justify-content-center" data-aos="fade" data-aos-delay="100">
          <div class="col-md-8">
            <div class="accordion unit-8" id="accordion">
            <div class="accordion-item">
              <h3 class="mb-0 heading">
                <a class="btn-block" data-toggle="collapse" href="#collapseOne" role="button" aria-expanded="true" aria-controls="collapseOne">What is the name of your company<span class="icon"></span></a>
              </h3>
              <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="body-text">
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur quae cumque perspiciatis aperiam accusantium facilis provident aspernatur nisi optio debitis dolorum, est eum eligendi vero aut ad necessitatibus nulla sit labore doloremque magnam! Ex molestiae, dolor tempora, ad fuga minima enim mollitia consequuntur, necessitatibus praesentium eligendi officia recusandae culpa tempore eaque quasi ullam magnam modi quidem in amet. Quod debitis error placeat, tempore quasi aliquid eaque vel facilis culpa voluptate.</p>
                </div>
              </div>
            </div>

             <!-- .accordion-item -->          
            <div class="accordion-item">
              <h3 class="mb-0 heading">
                <a class="btn-block" data-toggle="collapse" href="#collapseTwo" role="button" aria-expanded="false" aria-controls="collapseTwo">How much pay for 3  months?<span class="icon"></span></a>
              </h3>
              <div id="collapseTwo" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="body-text">
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vel ad laborum expedita. Nostrum iure atque enim quisquam minima distinctio omnis, consequatur aliquam suscipit, quidem, esse aspernatur! Libero, excepturi animi repellendus porro impedit nihil in doloremque a quaerat enim voluptatum, perspiciatis, quas dignissimos maxime ut cum reiciendis eius dolorum voluptatem aliquam!</p>
                </div>
              </div>
            </div> <!-- .accordion-item -->

            <div class="accordion-item">
              <h3 class="mb-0 heading">
                <a class="btn-block" data-toggle="collapse" href="#collapseThree" role="button" aria-expanded="false" aria-controls="collapseThree">Do I need to register?  <span class="icon"></span></a>
              </h3>
              <div id="collapseThree" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="body-text">
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vel ad laborum expedita. Nostrum iure atque enim quisquam minima distinctio omnis, consequatur aliquam suscipit, quidem, esse aspernatur! Libero, excepturi animi repellendus porro impedit nihil in doloremque a quaerat enim voluptatum, perspiciatis, quas dignissimos maxime ut cum reiciendis eius dolorum voluptatem aliquam!</p>
                </div>
              </div>
            </div> <!-- .accordion-item -->

            <div class="accordion-item">
              <h3 class="mb-0 heading">
                <a class="btn-block" data-toggle="collapse" href="#collapseFour" role="button" aria-expanded="false" aria-controls="collapseFour">Who should I contact in case of support.<span class="icon"></span></a>
              </h3>
              <div id="collapseFour" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="body-text">
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vel ad laborum expedita. Nostrum iure atque enim quisquam minima distinctio omnis, consequatur aliquam suscipit, quidem, esse aspernatur! Libero, excepturi animi repellendus porro impedit nihil in doloremque a quaerat enim voluptatum, perspiciatis, quas dignissimos maxime ut cum reiciendis eius dolorum voluptatem aliquam!</p>
                </div>
              </div>
            </div> <!-- .accordion-item -->

          </div>
          </div>
        </div>
      
      </div>
    </div>

@endsection

@push('appjs')
<script src="{{ asset('assets/js/user.js')}}"></script>
@endpush