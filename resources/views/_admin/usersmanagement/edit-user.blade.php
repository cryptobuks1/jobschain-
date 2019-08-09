
@extends('adminlte::page')
@section('title')
Edit {{ $user->name }}
@endsection
@push('css')
 <link href="{{asset('/assets/admin/mycustom.css')}}" rel="stylesheet">
 <style type="text/css">
    .btn-save,
    .pw-change-container {
      display: none;
    }
  </style>
@endpush


@section('content_header')
     <h1>
        Editing User {{ $user->name }}
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {!! trans("admin.dashboard")  !!}</a></li>
        <li class="active">{!! trans("admin.Users")  !!}</li>
    </ol>
@endsection


@section('content')



<section class="content">

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="{{route('image',$user->profile->avatar?$user->profile->avatar.'@200x200':'avatar.jpg@200x200')}}" alt="User profile picture">

              <h3 class="profile-username text-center">{{$user->first_name}} {{$user->last_name}}</h3>

              <p class="text-muted text-center">{{$user->email}}</p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>@lang('admin.coins')</b> <a class="pull-right">{{$user->tokens->count()}}</a>
                </li>
                <li class="list-group-item">
                  <b>@lang('admin.trades')</b> <a class="pull-right">{{$user->trades->count()}}</a>
                </li>
                <li class="list-group-item">
                  <b>@lang('ads.ads')</b> <a class="pull-right">{{$user->ads->count()}}</a>
                </li>
              </ul>

              <a href="route('admin.users.delete',$user->id)" class="btn btn-primary btn-danger"><b>Delete</b></a>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">@lang('admin.about')</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-book margin-r-5"></i>@lang('admin.information')</strong>

              <p class="text-muted">
                {{$user->profile_info}}
				<br>
				@lang('admin.dob')
				<br>
				 {{$user->dob}}
              </p>

              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i> @lang('admin.business')</strong>

              <p class="text-muted"> {{$user->business_info}}</p>

              <hr>

              <strong><i class="fa fa-pencil margin-r-5"></i>@lang('admin.roles')</strong>
              <p>
			  @foreach($user->getRoles() as $role)
                <span class="label label-info">{{$role->name}}</span>
              @endforeach
              </p>
			 </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#edit-user" data-toggle="tab">@lang('admin.edit_user')</a></li>
              <li><a href="#permissions" data-toggle="tab">@lang('admin.permissions')</a></li>
			  <li><a href="#verification" data-toggle="tab">@lang('admin.verification')</a></li>
			  <a   href="{{URL::to('/admin/users/'.$user->id)}}" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em; margin-top: 5px;">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
             Back  <span class="hidden-xs">to User</span>
            </a>

            <a href="{{URL::to('/admin/users/')}}" style="margin-top: 5px; margin-right: 5px;" class="btn btn-info btn-xs pull-right">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
              <span class="hidden-xs">Back to </span>Users
            </a>
            </ul>
			 
            <div style="min-height:500px" class="tab-content">
			 <div class="tab-pane" id="verification">
				<div class="card-body">
				@if($user->kyc()->count()> 0)
				
 				@if ($errors->any())
					<ul class="alert alert-danger">
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				@endif
					<form method="POST" action="{{route('admin.kycs.update', $user->kyc->id ) }}" accept-charset="UTF-8" class="form-horizontal ajax_form edit" data-edit="true" enctype="multipart/form-data">
						
						{{ csrf_field() }}
	
						@include ('_admin.kycs.form', ['submitButtonText' => 'Update'])
					</form>
				@else
				
				<strong>User has not submitted KYC information !!!</strong>
				@endif
					
				
				
				
				
				</div>
			  </div>
              <div class="active tab-pane" id="edit-user">
               
			    {!! Form::model($user, array('action' => array('Admin\UsersManagementController@update', $user->id), 'method' => 'PUT')) !!}

					{!! csrf_field() !!}
		
					<div class="panel-body">
		
					  <div class="form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
						{!! Form::label('name', 'Username' , array('class' => 'col-md-3 control-label')); !!}
						<div class="col-md-9">
						  <div class="input-group">
							{!! Form::text('name', old('name'), array('id' => 'name', 'class' => 'form-control', 'placeholder' => trans('forms.ph-username'))) !!}
							<label class="input-group-addon" for="name"><i class="fa fa-fw fa-user }}" aria-hidden="true"></i></label>
						  </div>
						</div>
					  </div>
		
					  <div class="form-group has-feedback row {{ $errors->has('email') ? ' has-error ' : '' }}">
						{!! Form::label('email', 'E-mail' , array('class' => 'col-md-3 control-label')); !!}
						<div class="col-md-9">
						  <div class="input-group">
							{!! Form::text('email', old('email'), array('id' => 'email', 'class' => 'form-control', 'placeholder' => trans('forms.ph-useremail'))) !!}
							<label class="input-group-addon" for="email"><i class="fa fa-fw fa-envelope " aria-hidden="true"></i></label>
						  </div>
						</div>
					  </div>
		
		
					  <div class="form-group has-feedback row {{ $errors->has('first_name') ? ' has-error ' : '' }}">
						{!! Form::label('first_name', trans('forms.create_user_label_firstname'), array('class' => 'col-md-3 control-label')); !!}
						<div class="col-md-9">
						  <div class="input-group">
							{!! Form::text('first_name', NULL, array('id' => 'first_name', 'class' => 'form-control', 'placeholder' => trans('forms.create_user_ph_firstname'))) !!}
							<label class="input-group-addon" for="first_name"><i class="fa fa-fw {{ trans('forms.create_user_icon_firstname') }}" aria-hidden="true"></i></label>
						  </div>
						  @if ($errors->has('first_name'))
							<span class="help-block">
								<strong>{{ $errors->first('first_name') }}</strong>
							</span>
						  @endif
						</div>
					  </div>
		
					  <div class="form-group has-feedback row {{ $errors->has('last_name') ? ' has-error ' : '' }}">
						{!! Form::label('last_name', trans('forms.create_user_label_lastname'), array('class' => 'col-md-3 control-label')); !!}
						<div class="col-md-9">
						  <div class="input-group">
							{!! Form::text('last_name', NULL, array('id' => 'last_name', 'class' => 'form-control', 'placeholder' => trans('forms.create_user_ph_lastname'))) !!}
							<label class="input-group-addon" for="last_name"><i class="fa fa-fw {{ trans('forms.create_user_icon_lastname') }}" aria-hidden="true"></i></label>
						  </div>
						  @if ($errors->has('last_name'))
							<span class="help-block">
								<strong>{{ $errors->first('last_name') }}</strong>
							</span>
						  @endif
						</div>
					  </div>
		
					  <div class="form-group has-feedback row {{ $errors->has('role') ? ' has-error ' : '' }}">
						{!! Form::label('role', trans('forms.create_user_label_role'), array('class' => 'col-md-3 control-label')); !!}
						<div class="col-md-9">
						  <div class="input-group">
							<select class="form-control" name="role" id="role">
							  <option value="">{{ trans('forms.create_user_ph_role') }}</option>
							  @if ($roles->count())
								@foreach($roles as $role)
								  <option value="{{ $role->id }}" {{ $currentRole->id == $role->id ? 'selected="selected"' : '' }}>{{ $role->name }}</option>
								@endforeach
							  @endif
							</select>
							<label class="input-group-addon" for="role"><i class="fa fa-fw {{ trans('forms.create_user_icon_role') }}" aria-hidden="true"></i></label>
						  </div>
						  @if ($errors->has('role'))
							<span class="help-block">
								<strong>{{ $errors->first('role') }}</strong>
							</span>
						  @endif
						</div>
					  </div>
		
					  <div class="pw-change-container">
						<div class="form-group has-feedback row">
						  {!! Form::label('password', trans('forms.create_user_label_password'), array('class' => 'col-md-3 control-label')); !!}
						  <div class="col-md-9">
							<div class="input-group">
							  {!! Form::password('password', array('id' => 'password', 'class' => 'form-control ', 'placeholder' => trans('forms.create_user_ph_password'))) !!}
							  <label class="input-group-addon" for="password"><i class="fa fa-fw {{ trans('forms.create_user_icon_password') }}" aria-hidden="true"></i></label>
							</div>
						  </div>
						</div>
		
						<div class="form-group has-feedback row">
						  {!! Form::label('password_confirmation', trans('forms.create_user_label_pw_confirmation'), array('class' => 'col-md-3 control-label')); !!}
						  <div class="col-md-9">
							<div class="input-group">
							  {!! Form::password('password_confirmation', array('id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => trans('forms.create_user_ph_pw_confirmation'))) !!}
							  <label class="input-group-addon" for="password_confirmation"><i class="fa fa-fw {{ trans('forms.create_user_icon_pw_confirmation') }}" aria-hidden="true"></i></label>
							</div>
						  </div>
						</div>
					  </div>
		
					</div>
					<div class="panel-footer">
		
					  <div class="row">
		
						<div class="col-xs-6">
						  <a href="#" class="btn btn-default btn-block margin-bottom-1 btn-change-pw" title="Change Password">
							<i class="fa fa-fw fa-lock" aria-hidden="true"></i>
							<span></span> Change Password
						  </a>
						</div>
						<div class="col-xs-6">
						  {!! Form::button('<i class="fa fa-fw fa-save" aria-hidden="true"></i> Save Changes', array('class' => 'btn btn-success btn-block margin-bottom-1 btn-save','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmSave', 'data-title' => trans('modals.edit_user__modal_text_confirm_title'), 'data-message' => trans('modals.edit_user__modal_text_confirm_message'))) !!}
						</div>
					  </div>
					</div>
		
				  {!! Form::close() !!}

			   
              </div>
              <!-- /.tab-pane -->

              <div class="tab-pane" id="permissions">
                <form method="post" data-edit="true" action="{{route('admin.roles.edit_user_permission')}}" class="form-horizontal ajax_form edit">
				{!! csrf_field() !!}
				<input type="hidden" name="user_id" value="{{$user->id}}">
                 @foreach(\jeremykenedy\LaravelRoles\Models\Permission::all() as $perm )
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <div class="checkbox">
                        <label>
                          <input name="permissions[]" @if($user->hasPermission($perm->id)) checked @endif value="{{$perm->id}}" type="checkbox"> {{$perm->name}}
                        </label>
                      </div>
                    </div>
                  </div>
				 @endforeach
				   <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <div class="checkbox">
                        <label>
                          <input name="can_withdraw" @if($user->can_withdraw)) checked @endif value="1" type="checkbox"> Can Withdraw
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>





  

  @include('modals.modal-save')
  @include('modals.modal-delete')

@endsection

@section('js')
	<script>
		jQuery.fn.toggleText = function (value1, value2) {
    return this.each(function () {
        var $this = $(this),
            text = $this.text();

        if (text.indexOf(value1) > -1)
            $this.text(text.replace(value1, value2));
        else
            $this.text(text.replace(value2, value1));
    });
};
	</script>
  @include('scripts.delete-modal-script')
  @include('scripts.save-modal-script')
  @include('scripts.check-changed')

@endsection

@push('js')
<script src="{{asset('/assets/admin/FileSaver.min.js')}}"></script>
<script src="{{asset('/assets/admin/sweetalert2.all.js')}}"></script>
<script src="{{asset('/assets/admin/jquery.blockUI.min.js')}}"></script>
<script src="{{asset('/assets/admin/init.js')}}"></script>
@endpush