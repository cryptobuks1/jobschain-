@extends('layouts.app')
@section('content')
<main id="content" class="bg-light" role="main">
    <div class="container">
        <div class="d-flex justify-content-between pt-5  pb-4">

        </div>
    </div>
    <div class="container mt-5 mb-4">
        <div class="mb-4">
            <div class="card header-bg">
                <div class="card-body px-sm-4 pb-sm-4 ">
                    <form action="/search" method="GET" autocomplete="off" spellcheck="false">
                        <div class="d-none d-sm-flex align-items-baseline">
                            <h1 class="h5 text-white">Search The JobChain</h1>
                        </div>
                        <div class="input-group input-group-main">
                            <input id="search" type="text" value="{{$keyword ?? ''}}" class="form-control py-3 mb-0"
                                placeholder="Enter / Txhash / Block Hash / Address" name="q">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit"> <i
                                        class="fa fa-search d-inline-block d-sm-none"></i><span
                                        class="d-none d-sm-inline-block"><i class="fa fa-search"></i> Search</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <div class="site-section bg-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-5 mb-md-0">
                    <h2 class="mb-5 h3">{{__('app.recent_transactions')}}</h2>
                    <div class="rounded border jobs-wrap">
                        @if (count($search) > 0) @foreach($search as $job )
                        <div class="job-item d-block d-md-flex align-items-center  border-bottom fulltime">
                            <div class="company-logo blank-logo text-center text-md-left pl-3">
                                @if(empty($job->company->logo))
                                <span class="btn btn-icon btn-soft-secondary rounded-circle"><span
                                        class="btn-icon__inner text-dark">Tx</span> @else
                                    <img src="{{$job->company->logo}}" alt="Image" class="img-fluid mx-auto"> @endif
                            </div>

                            <div class="job-details h-100">
                                <div class="p-3 align-self-center">
                                    <h3 class="d-none d-lg-block">{{$job->txid}}</h3>
                                    <h3 class="d-lg-none">{{$job->txid_short}}</h3>
                                    <div class="d-block d-lg-flex">
                                        <div class="mr-3"><span class="icon-suitcase mr-1"></span>
                                            {{$job->company_name}}
                                        </div>
                                        <div class="mr-3"><span class="icon-room mr-1"></span> {{$job->title}}</div>
                                        <div><span class="icon-money mr-1"></span>{{ $job->salary}}</div>
                                        @if($job->expiry->greaterThan(now()))
                                        <div class="mr-3 text-success"><span class="icon-check mr-1"></span>
                                            {{__('app.active')}}
                                        </div>
                                        @else
                                        <div class="mr-3 text-danger"><span class="icon-times mr-1"></span>
                                            {{__('app.expired')}}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="job-category align-self-right d-flex flex-row justify-content-end">
                                <div class="p-3">
                                    <a href="#viewDetails{{$job->id}}Modal" class="btn btn-outline btn-outline-primary"
                                        data-toggle="modal">View Details</a>
                                    <a href="#apply{{$job->id}}Modal" class="btn btn-primary"
                                        data-toggle="modal">Apply</a>
                                </div>
                            </div>
                        </div>

                        <!---View Details Modal-->
                        <div class="modal fade" id="viewDetails{{$job->id}}Modal" tabindex="-1">
                            <div class="modal-dialog modal-dialog-md modal-dialog-centered">
                                <div class="modal-content">
                                    <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em
                                            class="ti fa fa-times"></em></a>
                                    <div class="dialog-body">
                                        <h4 class="dialog-title">{{$job->address}}</h4>
                                        <label for="address" class="form-label">Txid: {{$job->txid}} </label>
                                        <form action="#">
                                            <h4 class="dialog-title text-primary">{{$job->title}}</h4>

                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label for="address" class="form-label">Qualifications </label>
                                                        <input class="form-control" type="text"
                                                            value="{{$job->qualifications}}" readonly>

                                                    </div>
                                                    <!-- .form -->
                                                </div>
                                                <!-- .col -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="amt" class="form-label">Salary </label>
                                                        <input class="form-control" type="text" value="{{$job->salary}}"
                                                            readonly>

                                                    </div>


                                                </div>
                                                <!-- .col -->
                                            </div>
                                            <!-- .row -->

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="address" class="form-label">Category </label>
                                                        <input class="form-control form-control-sm" type="text"
                                                            value="{{$job->category}}" readonly>
                                                    </div>
                                                </div>
                                                <!-- .col -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="password" class="form-label">Expiry Date </label>
                                                        <input class="form-control" type="text" value="{{$job->expiry}}"
                                                            readonly>

                                                    </div>
                                                </div>
                                                <!-- .col -->
                                            </div>
                                            <!-- .row -->



                                            <div class="row form-group">
                                                <div class="col-md-12"> <label for="address"
                                                        class="form-label">Description</label></div>
                                                <div class="col-md-12 mb-3 mb-md-0">
                                                    <textarea name="description" class="form-control" id="" cols="30"
                                                        rows="5" readonly>{{$job->description}}</textarea>
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

                        <!---apply Modal-->
                        <div class="modal fade" id="apply{{$job->id}}Modal" tabindex="-1">
                            <div class="modal-dialog modal-dialog-md modal-dialog-centered">
                                <div class="modal-content">
                                    <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em
                                            class="ti fa fa-times"></em></a>
                                    <div class="dialog-body">
                                        <h4 class="dialog-title">Apply for Job: {{$job->title}}</h4>

                                        <form action="#" method="post">
                                            <div class="form-group">
                                                <label for="address" class="form-label">Subject </label>
                                                <input class=form-control type="text" name="subject">

                                            </div>
                                            <!-- .form -->
                                            <div class="form-group">
                                                <label for="address" class="form-label">Message </label>
                                                <textarea name="message" class="form-control" id="" cols="30"
                                                    rows="4">{{$job->message}}</textarea>

                                            </div>
                                            <!-- .form -->

                                            <div class="row form-group">
                                                <div class="col-md-12">

                                                    <input type="submit" value="Send Message"
                                                        class="btn btn-primary  py-2 px-5">
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
                        <!-- End apply Modal-->
                        @endforeach @else
                        <h3 class="text-danger">No records to show for this search</h3>
                        @endif

                    </div>
                    <div class="col-md-12 text-center mt-5">{{$search->links()}} </div>
                </div>
            </div>
        </div>
    </div>

</main>

@endsection