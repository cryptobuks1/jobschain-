@extends('layouts.app') 
@section('title')  Post Job
@endsection 
@section('content')
    
<div style="height: 113px;"></div>
<div class="unit-5 overlay" style="background-image: url('/assets/images/hero_1.jpg');">
    <div class="container text-center">
      <h2 class="mb-0">Post a Job</h2>
      <p class="mb-0 unit-6"><a href="/">Home</a> <span class="sep">&gt;</span> <span>Post a Job</span></p>
    </div>
</div>

<div class="site-section bg-light">
  <div class="container">
    <div class="row">
   
      <div class="col-md-12 col-lg-8 mb-5">
      
        <form class="p-5 bg-white" action="{{ route('jobs.store') }}" method="post">
        @csrf
        
          <div class="row form-group">
            <div class="col-md-12 mb-3 mb-md-0">
              <label class="font-weight-bold" for="Country">Country</label>
              <select name="country" id="country" class="form-control" placeholder="Select Country" required>
                <option value="Uganda">Uganda</option>
              </select>
            </div>
          </div>

          <div class="row form-group mb-5">
            <div class="col-md-12 mb-3 mb-md-0">
              <label class="font-weight-bold" for="Address">Address</label>
              <input name="address" type="text" id="address" class="form-control" placeholder="eg. Kampala" required>
            </div>
          </div>
            
            <div class="row form-group mb-5">
            <div class="col-md-12 mb-3 mb-md-0">
              <label class="font-weight-bold" for="company_name">Company Name</label>
              <input name="company_name" type="text" id="company_name" class="form-control" placeholder="eg. Company or Oganization" required>
            </div>
          </div>
            
         <div class="row form-group mb-5">
            <div class="col-md-12 mb-3 mb-md-0">
              <label class="font-weight-bold" for="title">Title</label>
              <input name="title" type="text" id="title" class="form-control" placeholder="Job Title" required>
            </div>
          </div>
          
        <div class="row form-group mb-5">
            <div class="col-md-12 mb-3 mb-md-0">
              <label class="font-weight-bold" for="salary">Salary</label>
              <input name="salary" type="text" id="salary" class="form-control" placeholder="eg. 2,000" required>
            </div>
          </div>
            
        <div class="row form-group">
            <div class="col-md-12"><h3>Qualifications</h3></div>
            <div class="col-md-12 mb-3 mb-md-0">
              <textarea name="qualifications" class="form-control" id="" cols="30" rows="5" required></textarea>
            </div>
          </div>
            
        <div class="row form-group">
            <div class="col-md-12"><h3>Description</h3></div>
            <div class="col-md-12 mb-3 mb-md-0">
              <textarea name="description" class="form-control" id="" cols="30" rows="5" required></textarea>
            </div>
          </div>
        
        <div class="row form-group">
            <div class="col-md-12 mb-3 mb-md-0">
              <label class="font-weight-bold" for="category">Category</label>
              <select name="category" id="category" class="form-control" placeholder="Select Country" required>
                <option value="Marketing">Marketing</option>
              </select>
            </div>
        </div>

        <div class="row form-group mb-5">
            <div class="col-md-12 mb-3 mb-md-0">
              <label class="font-weight-bold" for="experience">Experience</label>
              <input name="expirience" type="text" id="expirience" class="form-control" placeholder="Experience Required">
            </div>
          </div>
            
        <div class="row form-group mb-5">
            <div class="col-md-12 mb-3 mb-md-0">
              <label class="font-weight-bold" for="salary">Expiry</label>
              <input name="expiry" type="date" id="form_datetime" class="form-control" placeholder="Expiry date" size="16">
                <script type="text/javascript">
                    $("#form_datetime").datetimepicker({format: 'yyyy-mm-dd hh:ii'});
                </script>  
            </div>
          </div>
        
          <div class="row form-group">
            <div class="col-md-12">
              <input type="submit" value="Post a Job" class="btn btn-primary  py-2 px-5">
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
         <img src="images/qrcode.png">
        </div>
          <p class="mb-4"><a href="#">Get New Address <i class="fa fa-refresh fa-spin"></i></a></p>

        </div>
      </div>
    </div>
  </div>
</div>


@endsection
@push('appjs')
<script src="{{ asset('assets/js/user.js')}}"></script>
@endpush