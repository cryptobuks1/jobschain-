@extends('layouts.app') @section('title') Coin Wallets @endsection @section('content')


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
                {{--
                <form action="{{'/'}}" class="p-5 bg-white"> --}}
                    <form method="POST" action="{{ route('cvs.store') }}" accept-charset="UTF-8" class="p-5 bg-white ajax_form" enctype="multipart/form-data">
                        @csrf
                        <div class="row form-group">
                        </div>
                        {{--name --}}
                        <div class="row form-group">
                            <div class="col-md-12 mb-3 mb-md-0">
                                <label class="font-weight-bold" for="salary">Salary</label>
                                <input type="text" id="salary" name="salary" class="form-control" placeholder="$200">
                            </div>
                        </div>

                        <div class="row form-group mb-5">
                            <div class="col-md-12 mb-3 mb-md-0">
                                <label class="font-weight-bold" for="fullname">Qualifications</label>

                                <input type="file" id="myFile" name="description" class="form-control">


                            </div>
                        </div>

                        <div class="row form-group mb-4">
                            <div class="col-md-12">
                                <h3>Address</h3>
                            </div>
                            <div class="col-md-12 mb-3 mb-md-0">
                                <input type="text" class="form-control" name="address" placeholder="New York City">
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <h3>Job Description</h3>
                            </div>
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
                        <img src="/assets/images/qrcode.png" />
                    </div>
                    <p class="mb-4"><a href="#">Get New Address <i class="fa fa-refresh fa-spin"></i></a></p>

                </div>
            </div>
        </div>
    </div>
</div>



@endsection