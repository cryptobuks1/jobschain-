@if ($errors->any())
<ul>
    @foreach ($errors->all() as $error)
    <li><div class="alert alert-dismissible fade show alert-danger"><a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&nbsp;</a> {!! $error !!}</div></li>
    @endforeach
</ul>
@endif
@if (session('info'))
<div class="alert alert-dismissible fade show alert-info" role="alert">
    <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&nbsp;</a>
    {!! session('info') !!}
</div>
@endif
@if (session('status'))
<div class="alert alert-dismissible fade show alert-success" role="alert">
    <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&nbsp;</a>
    &nbsp;{!! session('status') !!}
</div>
@endif
@if (session('success'))
<div class="alert alert-dismissible fade show alert-success" role="alert">
    <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&nbsp;</a>
    {!! session('success') !!}
</div>
@endif
@if (session('danger'))
<div class="alert alert-dismissible fade show alert-danger" role="alert">
    <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&nbsp;</a>
    {!! session('danger') !!}
</div>
@endif
@if (session('error'))
<div class="alert alert-dismissible fade show alert-danger" role="alert">
    <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&nbsp;</a>
    {!! session('error') !!}
</div>
@endif
@if (session('warning'))
<div class="alert alert-dismissible fade show alert-warning" role="alert">
    <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&nbsp;</a>
    {!! session('warning') !!}
</div>
@endif