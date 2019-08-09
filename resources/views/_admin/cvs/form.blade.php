<div class="form-group {{ $errors->has('user_id') ? 'has-error' : ''}}">
    <label for="user_id" class="col-md-4 control-label">{{ __('cvs.user_id') }}</label>
    <div class="col-md-6">
        <input class="form-control" name="user_id" type="number" id="user_id" value="{{ $cv->user_id ?? ''}}" >
        {!! $errors->first('user_id', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('address') ? 'has-error' : ''}}">
    <label for="address" class="col-md-4 control-label">{{ __('cvs.address') }}</label>
    <div class="col-md-6">
        <input class="form-control" name="address" type="text" id="address" value="{{ $cv->address ?? ''}}" required>
        {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('publickey') ? 'has-error' : ''}}">
    <label for="publickey" class="col-md-4 control-label">{{ __('cvs.publickey') }}</label>
    <div class="col-md-6">
        <input class="form-control" name="publickey" type="text" id="publickey" value="{{ $cv->publickey ?? ''}}" >
        {!! $errors->first('publickey', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('qualifications') ? 'has-error' : ''}}">
    <label for="qualifications" class="col-md-4 control-label">{{ __('cvs.qualifications') }}</label>
    <div class="col-md-6">
        <input class="form-control" name="qualifications" type="text" id="qualifications" value="{{ $cv->qualifications ?? ''}}" >
        {!! $errors->first('qualifications', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('country') ? 'has-error' : ''}}">
    <label for="country" class="col-md-4 control-label">{{ __('cvs.country') }}</label>
    <div class="col-md-6">
        <input class="form-control" name="country" type="text" id="country" value="{{ $cv->country ?? ''}}" required>
        {!! $errors->first('country', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('location') ? 'has-error' : ''}}">
    <label for="location" class="col-md-4 control-label">{{ __('cvs.location') }}</label>
    <div class="col-md-6">
        <input class="form-control" name="location" type="text" id="location" value="{{ $cv->location ?? ''}}" required>
        {!! $errors->first('location', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
    <label for="description" class="col-md-4 control-label">{{ __('cvs.description') }}</label>
    <div class="col-md-6">
        <input class="form-control" name="description" type="text" id="description" value="{{ $cv->description ?? ''}}" required>
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('salary') ? 'has-error' : ''}}">
    <label for="salary" class="col-md-4 control-label">{{ __('cvs.salary') }}</label>
    <div class="col-md-6">
        <input class="form-control" name="salary" type="text" id="salary" value="{{ $cv->salary ?? ''}}" required>
        {!! $errors->first('salary', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('expirience') ? 'has-error' : ''}}">
    <label for="expirience" class="col-md-4 control-label">{{ __('cvs.expirience') }}</label>
    <div class="col-md-6">
        <input class="form-control" name="expirience" type="number" id="expirience" value="{{ $cv->expirience ?? ''}}" required>
        {!! $errors->first('expirience', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('type') ? 'has-error' : ''}}">
    <label for="type" class="col-md-4 control-label">{{ __('cvs.type') }}</label>
    <div class="col-md-6">
        <select name="type" class="form-control" id="type" required>
    @foreach (json_decode('{"full_time":"Full Time","part_time":"Part Time","freelance":"Freelance"}', true) as $optionKey => $optionValue)
        <option value="{{ $optionKey }}" {{ (isset($cv->type) && $cv->type == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
    @endforeach
</select>
        {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('active') ? 'has-error' : ''}}">
    <label for="active" class="col-md-4 control-label">{{ __('cvs.active') }}</label>
    <div class="col-md-6">
        <div class="radio">
    <label><input name="active" type="radio" value="1" {{ (isset($cv) && 1 == $cv->active) ? 'checked' : '' }}> Yes</label>
</div>
<div class="radio">
    <label><input name="active" type="radio" value="0" @if (isset($cv)) {{ (0 == $cv->active) ? 'checked' : '' }} @else {{ 'checked' }} @endif> No</label>
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
