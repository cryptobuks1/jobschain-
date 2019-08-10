@extends('layouts.app') @section('title') Coin Wallets @endsection @section('content')


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

    </div>
</div>
</div>

<!---View Details Modal-->
<div class="modal fade" id="viewDetails{{$cv->id}}Modal" tabindex="-1">
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
                            </div>
                            <!-- .form -->
                            <div class="form-group">
                                <label for="address" class="form-label">Qualifications </label>
                                <input class="form-control" type="text" value="{{$cv->qualifications}}" readonly>
                            </div>
                            <!-- .form -->
                        </div>
                        <!-- .col -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="amt" class="form-label">Salary </label>
                                <input class="form-control" type="text" value="{{$cv->salary}}" readonly>
                            </div>
                        </div>
                        <!-- .col -->
                    </div>
                    <!-- .row -->

                    <div class="row form-group">
                        <div class="col-md-12"> <label for="address" class="form-label">Job Description</label></div>
                        <div class="col-md-12 mb-3 mb-md-0">
                            <textarea name="description" class="form-control" id="" cols="30" rows="5" readonly>{{$cv->description}}</textarea>
                        </div>
                    </div>
                    <div class="my-2"></div>
                </form>
                <!-- form -->
            </div>
        </div>
        <!-- .modal-content -->
    </div>
    <!-- .modal-dialog -->
</div>
<!-- End View Details Modal-->



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