@extends('layouts.app') 
@section('title')  Coin Wallets
@endsection 
@section('content')

<div id="app" class="container">
 </div>
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
                                  <li><a href="{{'/'}}">Browse Candidates</a></li>
                                  <li><a href="{{'/l.gngcreate'}}">Post a Job</a></li>
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
        </div>Recent
      
        <div style="height: 113px;"></div>
        <div class="unit-5 overlay" style="background-image: url('/assets/images/hero_1.jpg');">
          <div class="container text-center">
            <h2 class="mb-0"><a href="#">Curriculum vitae<strong class="font-weight-bold"> Transactions</strong> </a></h2>  
          </div>
        </div>


<span id="app">
        <div class="site-section bg-light py-4">
            <div class="container mnh-500px">
                <div class="row">
                    <div class="col-md-12 mb-5 mb-md-0">
                        <h2 class="mb-5 h3">{{__('app.recent_cv_transactions')}}</h2>
                        <div class="rounded border jobs-wrap"> 
                            @foreach($cvs as $cv)
                            <div class="job-item d-block d-md-flex align-items-center  border-bottom fulltime">
                            <div class="company-logo blank-logo text-center text-md-left pl-3"> 
                                <span class="btn btn-icon btn-soft-secondary rounded-circle"><span class="btn-icon__inner text-dark">Cv</span>
                            </div>
                            <div class="job-details h-100">
                                <div class="p-3 align-self-center">
                                    <h3 class="d-none d-lg-block">{{$cv->txid}}</h3>
                                    <h3 class="d-lg-none">{{$cv->txid_short}}</h3>
                                    <div class="d-block d-lg-flex">qualifications
                                        <div class="mr-3"><span class="icon-suitcase mr-1"></span> {{$cv->qualification}}</div>
                                        <div class="mr-3"><span class="icon-room mr-1"></span> {{$cv->location}}</div>
                                        <div class="mr-3"><span class="icon-clock-o mr-1"></span> {{$cv->expirience}}</div>
                                        <div><span class="icon-dollar mr-1"></span>{{$cv->salary}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="job-category align-self-center">
                        <div class="p-3">
                            <a href="#viewDetails{{$cv->id}}Modal" class="btn btn-outline btn-outline-primary" data-toggle="modal">
                              View Details 
                            </a> 
                            {{-- <a href="#apply{{$cv->id}}Modal" class="btn btn-primary" data-toggle="modal">
                               Apply
                            </a> --}}
                        </div>                              
                            </div>
                          </div> 

                          <!---View Details Modal-->
                    <div class="modal fade" id="viewDetails{{$cv->id}}Modal" tabindex="-1" >
                      <div class="modal-dialog modal-dialog-md modal-dialog-centered">
                          <div class="modal-content">
                              <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti fa fa-times"></em></a>
                              <div class="dialog-body">
                                  <form action="#">
                                      <div class="row">
                                      <div class="col-md-8">
                                          <div class="form-group">
                                              <label for="address" class="form-label">address </label>
                                              <input class="form-control" type="text" value="{{$cv->address}}" readonly>                
                                          </div><!-- .form -->
                                          <div class="form-group">
                                              <label for="address" class="form-label">Qualifications </label>
                                              <input class="form-control" type="text" value="{{$cv->qualifications}}" readonly>
                                          </div><!-- .form -->
                                          </div><!-- .col -->
                                              <div class="col-md-4">
                                              <div class="form-group">
                                                  <label for="amt" class="form-label">Salary </label>
                                                  <input class="form-control" type="text" value="{{$cv->salary}}" readonly>
                                                </div>   
                                          </div><!-- .col -->
                                      </div><!-- .row -->      
                                      {{-- <div class="row">
                                          <div class="col-md-6">
                                              <div class="form-group">
                                                  <label for="address" class="form-label">Category </label>
                                                  <input class="form-control form-control-sm" type="text" value="{{$cv->expirience}}" readonly>
                                              </div>
                                          </div><!-- .col -->
                                           <div class="col-md-6">
                                              <div class="form-group">
                                                  <label for="password" class="form-label">Expiry Date </label>
                                                  <input class="form-control" type="text" value="{{$cv->location}}" readonly>                          
                                              </div>
                                          </div><!-- .col -->
                                      </div><!-- .row --> --}}
                                      <div class="row form-group">
                                          <div class="col-md-12"> <label for="address" class="form-label">Job Description</label></div>
                                          <div class="col-md-12 mb-3 mb-md-0">
                                            <textarea name="description" class="form-control" id="" cols="30" rows="5" readonly>{{$cv->description}}</textarea>
                                          </div>
                                      </div>
                                      <div class="my-2"></div> 
                                  </form><!-- form -->
                              </div>
                          </div><!-- .modal-content -->
                      </div><!-- .modal-dialog -->
                  </div>
                  <!-- End View Details Modal-->
                  
                   <!---apply Modal
                  <div class="modal fade" id="apply{{$cv->id}}Modal" tabindex="-1" >
                      <div class="modal-dialog modal-dialog-md modal-dialog-centered">
                          <div class="modal-content">
                              <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti fa fa-times"></em></a>
                              <div class="dialog-body">
                                  <h4 class="dialog-title">Apply for Job: </h4>
                                  <form action="#" method="post">
                                      <div class="form-group">
                                          <label for="address" class="form-label">Subject </label>
                                          <input class=form-control type="text" name="subject">    
                                      </div>
                                       <div class="form-group">
                                          <label for="address" class="form-label">Message </label>
                                          <textarea name="message" class="form-control" id="" cols="30" rows="4"></textarea>
                                      </div>
                                     <div class="row form-group">
                                          <div class="col-md-12">     
                                            <input type="submit" value="Send Message" class="btn btn-primary  py-2 px-5">
                                          </div>
                                      </div>
                                      <div class="my-2"></div>
                                  </form>
                              </div>
                          </div>
                      </div>
                  </div>
                  End apply Modal-->

                            @endforeach
                        </div>  
                        <div class="col-md-12 text-center mt-5 ">
                           {{$cvs->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>    
</span>
 </div>
@endsection
