
@extends('adminlte::page')
@section('title')
 Wallets
@endsection
@push('css')
 <link href="{{asset('/assets/admin/mycustom.css')}}" rel="stylesheet">
@endpush

@section('content_header')
     <h1>
        Wallets
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {!! __("admin.dashboard")  !!}</a></li>
        <li class="active">Wallets</li>
    </ol>
@endsection

@section("content")

<!-- Main content -->
<section class="content">
    <div class="row">
        <!-- /.col -->
		 <div class="col-md-9">
            <div class="box">
				
                <div class="box-body">
					
					<div class="table-responsive">
						<table class="table table-hover" id="wallets">
							<thead>
								<tr>
									<th>Name</th>
									<th>Wallet Bal</th>
									<th>Users Bal</th>
									<th>Profit</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
				    </div>
					<div id="add" class="row" style="display:none">
						
                    </div>
					<div class="box-footer clearfix">
					
					  <form data-table="walletsTable" method="post" class="ajax_form authorize" action="{{route('admin.wallets.update')}}">
							{{ csrf_field() }}
							<div class="col-md-8">
								<input class="form-control" placeholder="Amount" name="amount"/>
							
							</div>
							<div class="col-md-4">
								<input type="hidden" name="password" id="password">
								<button type="submit" class="btn pull-left  btn-success">Top Up Wallet</button>
							</div>
						</form>
					</div>
                 </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
		<div class="col-md-3">
            <div class="box">
				<div class="box-header with-border"><h3 class="box-title">Send</h3></div>
                <div class="box-body">
  					
					<form data-table="txTable|walletsTable"  action="{{route('admin.wallets.send')}}" class="form-horizontal form-label-left input_mask ajax_form authorize">
					{{ csrf_field() }}
						<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Amount</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" name="amount" class="form-control" placeholder="Amount">
							</div>
							</div>
						<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Wallet</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
								<select name="token_id" class="token form-control input-md input-medium select2">
								@foreach($user->wallets as $wallet)
								@if(!empty($wallet->token))
									<option @if(empty($wallet->token->contract_address)&&$wallet->token->symbol!='ETH') fee="txfee" @else fee="gas" @endif value="{{$wallet->token->id}}">{{$wallet->token->name}} {{$wallet->token->symbol}}</option>
								@endif
								@endforeach
								</select>
							</div>
							</div>
						<div class="form-group">
								<div class="col-md-12 col-sm-12 col-xs-12">
								<input name="to" type="text" class="form-control" placeholder="To">
								<input name="password" class="password" type="hidden" value="">
							</div>
							</div> 
						<div class="form-group gaslimit">
								<div class="col-md-12 col-sm-12 col-xs-12">
								<input name="gasLimit" type="text" class="form-control" placeholder="Gas Limit (Optional)">
							</div>
							</div>
						<div class="form-group gasprice">
								<div class="col-md-12 col-sm-12 col-xs-12">
								<input name="gasPrice" type="text" class="form-control" placeholder="Gas Price (Optional)">
							</div>
							</div>
						<div class="ln_solid"></div>
						<div class="box-footer clearfix">
						<div class="form-group">
								<div class="col-md-12 col-sm-9 col-xs-12">
								<button class="btn btn-primary" type="reset">Reset</button>
								<button type="submit" class="btn btn-success">Send</button>
							</div>
							</div>
						</div>
					</form>
                 </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div><!-- /.row -->
</section><!-- /.content -->

<div class="row">
        <div class="col-md-12">
          <div class="box box-info">
             <div class="box-header with-border">
              <h3 class="box-title">Transaction History</h3>
             </div>
             <div class="box-body">
				  <div class="table-responsive">
				  
				  	<table id="txTable" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th >{!! trans("home.Symbol_short")  !!}</th>
								<th >{!! trans("home.Type")  !!}</th>
								<th >{!! trans("home.Value")  !!}</th>
								<th >#</th>
								<th >{!! trans("home.created_at")  !!}</th>
							</tr>
							</thead>
						<tbody>
						</tbody>
					</table>
  				  </div>
             </div>
          </div>
          <!-- /.box -->
        </div>
    
      </div>
@endsection




@push('js')
@include('modals.modal-delete')
<script src="{{asset('/assets/admin/FileSaver.min.js')}}"></script>
<script src="{{asset('/assets/admin/sweetalert2.all.js')}}"></script>
<script src="{{asset('/assets/admin/jquery.blockUI.min.js')}}"></script>
<script src="{{asset('/assets/admin/init.js')}}"></script>
@endpush

@section('js')

<script>
var language ={
	
                    "sDecimal":        ",",
                    "sEmptyTable":     "{!! trans("admin.sEmptyTable")  !!}",
                    "sInfo":           "{!! trans("admin.sInfo")  !!}",
                    "sInfoEmpty":      "{!! trans("admin.sInfoEmpty")  !!}",
                    "sInfoFiltered":   "{!! trans("admin.sInfoFiltered")  !!}",
                    "sInfoPostFix":    "",
                    "sInfoThousands":  ".",
                    "sLengthMenu":     "{!! trans("admin.sLengthMenu")  !!}",
                    "sLoadingRecords": "{!! trans("admin.sLoadingRecords")  !!}",
                    "sProcessing":     "{!! trans("admin.sProcessing")  !!}",
                    "sSearch":         "{!! trans("admin.sSearch")  !!}",
                    "sZeroRecords":    "{!! trans("admin.sZeroRecords")  !!}",
                    "oPaginate": {
                        "sFirst":    "{!! trans("admin.sFirst")  !!}",
                        "sLast":     "{!! trans("admin.sLast")  !!}",
                        "sNext":     "{!! trans("admin.sNext")  !!}",
                        "sPrevious": "{!! trans("admin.sPrevious")  !!}"
                    },
                    "oAria": {
                        "sSortAscending":  "{!! trans("admin.sSortAscending")  !!}",
                        "sSortDescending": "{!! trans("admin.sSortDescending")  !!}"
                    }
                
}
$(function() {
	@include('scripts.delete-modal-script')
	window.txTable = $('#txTable').dataTable({
		"order": [[4, 'desc' ]],
		"lengthMenu": [4, 10, 15],
        "pageLength": 10,
		"language": language,
		processing: true,
		serverSide: true,
		initComplete:function(){
			$('.tooltips').tooltip();
		},
		"autoWidth": false,
		"ajax": {
			"url": "{{route('admin.wallets.txtable')}}",
			"type": "POST",
			'headers': { 'X-CSRF-TOKEN': '{{ csrf_token() }}'}
		},
		type:'POST',
		columns: [
			{data: 'symbol', name: 'symbol', orderable: true},
			{data: 'type', name: 'type'},
			{data: 'amount', name: 'amount', orderable: true},
			{data: 'tx_hash', name: 'tx_hash'},
			{data: 'created_at', name: 'created_at'},
		]
	});
	
	
	
	window.walletsTable = $('#wallets').dataTable({
		"language": language,
		processing: true,
		serverSide: true,
		searching: true,
		lengthMenu: [5, 10, 15],
        "pageLength":5,
		"autoWidth": false,
		"ajax": {
			"url": "{{route('admin.wallets.list')}}",
			"type": "POST",
			'headers': { 'X-CSRF-TOKEN': '{{ csrf_token() }}'}
		},
		order: [[ 1, "desc" ]] ,
		"drawCallback": function( ) {
			$('[data-toggle="tooltip"]').tooltip();
		},
		type:'POST',
		columns: [
			{data: 'symbol', name: 'symbol', orderable: false},
			{data: 'balance', name: 'balance', orderable: true},
			{data: 'ubalance', name: 'ubalance', orderable: true},
			{data: 'available', name: 'available', orderable: true},
			{data: 'action', name: 'action',orderable: false}
			
		]
	});


});
</script>
@endsection
