<div class="form-group {{ $errors->has('user_id') ? 'has-error' : ''}}">
    <label for="user_id" class="col-md-4 control-label">{{ __('jobs.user_id') }}</label>
    <div class="col-md-6">
        <input class="form-control" name="user_id" type="number" id="user_id" value="{{ $job->user_id ?? ''}}" >
        {!! $errors->first('user_id', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('country') ? 'has-error' : ''}}">
    <label for="country" class="col-md-4 control-label">{{ __('jobs.country') }}</label>
    <div class="col-md-6">
        <input class="form-control" name="country" type="text" id="country" value="{{ $job->country ?? ''}}" required>
        {!! $errors->first('country', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('address') ? 'has-error' : ''}}">
    <label for="address" class="col-md-4 control-label">{{ __('jobs.address') }}</label>
    <div class="col-md-6">
        <input class="form-control" name="address" type="text" id="address" value="{{ $job->address ?? ''}}" >
        {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('publickey') ? 'has-error' : ''}}">
    <label for="publickey" class="col-md-4 control-label">{{ __('jobs.publickey') }}</label>
    <div class="col-md-6">
        <input class="form-control" name="publickey" type="text" id="publickey" value="{{ $job->publickey ?? ''}}" >
        {!! $errors->first('publickey', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('company_name') ? 'has-error' : ''}}">
    <label for="company_name" class="col-md-4 control-label">{{ __('jobs.company_name') }}</label>
    <div class="col-md-6">
        <input class="form-control" name="company_name" type="text" id="company_name" value="{{ $job->company_name ?? ''}}" required>
        {!! $errors->first('company_name', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    <label for="title" class="col-md-4 control-label">{{ __('jobs.title') }}</label>
    <div class="col-md-6">
        <input class="form-control" name="title" type="text" id="title" value="{{ $job->title ?? ''}}" required>
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('qualifications') ? 'has-error' : ''}}">
    <label for="qualifications" class="col-md-4 control-label">{{ __('jobs.qualifications') }}</label>
    <div class="col-md-6">
        <input class="form-control" name="qualifications" type="text" id="qualifications" value="{{ $job->qualifications ?? ''}}" >
        {!! $errors->first('qualifications', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
    <label for="description" class="col-md-4 control-label">{{ __('jobs.description') }}</label>
    <div class="col-md-6">
        <input class="form-control" name="description" type="text" id="description" value="{{ $job->description ?? ''}}" required>
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('expirience') ? 'has-error' : ''}}">
    <label for="expirience" class="col-md-4 control-label">{{ __('jobs.expirience') }}</label>
    <div class="col-md-6">
        <input class="form-control" name="expirience" type="text" id="expirience" value="{{ $job->expirience ?? ''}}" required>
        {!! $errors->first('expirience', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('count') ? 'has-error' : ''}}">
    <label for="count" class="col-md-4 control-label">{{ __('jobs.count') }}</label>
    <div class="col-md-6">
        <input class="form-control" name="count" type="number" id="count" value="{{ $job->count ?? ''}}" required>
        {!! $errors->first('count', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="col-md-4 control-label">{{ __('jobs.status') }}</label>
    <div class="col-md-6">
        <select name="status" class="form-control" id="status" >
    @foreach (json_decode('{"open":"Open","closed":"Closed","filled":"Filled"}', true) as $optionKey => $optionValue)
        <option value="{{ $optionKey }}" {{ (isset($job->status) && $job->status == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
    @endforeach
</select>
        {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('active') ? 'has-error' : ''}}">
    <label for="active" class="col-md-4 control-label">{{ __('jobs.active') }}</label>
    <div class="col-md-6">
        <div class="radio">
    <label><input name="active" type="radio" value="1" {{ (isset($job) && 1 == $job->active) ? 'checked' : '' }}> Yes</label>
</div>
<div class="radio">
    <label><input name="active" type="radio" value="0" @if (isset($job)) {{ (0 == $job->active) ? 'checked' : '' }} @else {{ 'checked' }} @endif> No</label>
</div>
        {!! $errors->first('active', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input class="btn btn-primary" type="submit" value="{{ $submitButtonText ?? 'Create' }}">
    </div>
</div>

@push('js')
<!-- Laravel Javascript Validation -->
 <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
 {!! $jsvalidator !!}
@endpush
