@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Job {{ $job->id }}</div>
                    <div class="card-body">

                        <a href="{{ route('admin.jobs.index') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ route('admin.jobs.edit', $job->id ) }}" title="Edit Job"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                        <form method="POST" action="{{route('admin.jobs.delete', $job->id ) }}" accept-charset="UTF-8" style="display:inline">
                           	@method('DELETE')
							@csrf 
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete Job" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $job->id }}</td>
                                    </tr>
                                    <tr><th> {{ __('jobs.user_id') }} </th><td> {{ $job->user_id }} </td></tr><tr><th> {{ __('jobs.country') }} </th><td> {{ $job->country }} </td></tr><tr><th> {{ __('jobs.address') }} </th><td> {{ $job->address }} </td></tr><tr><th> {{ __('jobs.publickey') }} </th><td> {{ $job->publickey }} </td></tr><tr><th> {{ __('jobs.company_name') }} </th><td> {{ $job->company_name }} </td></tr><tr><th> {{ __('jobs.title') }} </th><td> {{ $job->title }} </td></tr><tr><th> {{ __('jobs.qualifications') }} </th><td> {{ $job->qualifications }} </td></tr><tr><th> {{ __('jobs.description') }} </th><td> {{ $job->description }} </td></tr><tr><th> {{ __('jobs.expirience') }} </th><td> {{ $job->expirience }} </td></tr><tr><th> {{ __('jobs.count') }} </th><td> {{ $job->count }} </td></tr><tr><th> {{ __('jobs.status') }} </th><td> {{ $job->status }} </td></tr><tr><th> {{ __('jobs.active') }} </th><td> {{ $job->active }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
