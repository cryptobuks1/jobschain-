@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Cv {{ $cv->id }}</div>
                    <div class="card-body">

                        <a href="{{ route('admin.cvs.index') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ route('admin.cvs.edit', $cv->id ) }}" title="Edit Cv"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                        <form method="POST" action="{{route('admin.cvs.delete', $cv->id ) }}" accept-charset="UTF-8" style="display:inline">
                           	@method('DELETE')
							@csrf 
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete Cv" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $cv->id }}</td>
                                    </tr>
                                    <tr><th> {{ __('cvs.user_id') }} </th><td> {{ $cv->user_id }} </td></tr><tr><th> {{ __('cvs.address') }} </th><td> {{ $cv->address }} </td></tr><tr><th> {{ __('cvs.publickey') }} </th><td> {{ $cv->publickey }} </td></tr><tr><th> {{ __('cvs.qualifications') }} </th><td> {{ $cv->qualifications }} </td></tr><tr><th> {{ __('cvs.country') }} </th><td> {{ $cv->country }} </td></tr><tr><th> {{ __('cvs.location') }} </th><td> {{ $cv->location }} </td></tr><tr><th> {{ __('cvs.description') }} </th><td> {{ $cv->description }} </td></tr><tr><th> {{ __('cvs.salary') }} </th><td> {{ $cv->salary }} </td></tr><tr><th> {{ __('cvs.expirience') }} </th><td> {{ $cv->expirience }} </td></tr><tr><th> {{ __('cvs.type') }} </th><td> {{ $cv->type }} </td></tr><tr><th> {{ __('cvs.active') }} </th><td> {{ $cv->active }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
