
@extends('adminlte::page')
@section('title')
 {!! trans("admin.cvs")  !!} 
@endsection
@push('css')
 <link href="{{asset('/assets/admin/mycustom.css')}}" rel="stylesheet">
 <link href="{{asset('/assets/admin/datatables.min.css')}}" rel="stylesheet">
@endpush

@section('content_header')
     <h1>
        {!! trans("admin.cvs")  !!} <!-- changed from app. to admin. -->
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {!! trans("admin.dashboard")  !!}</a></li>
        <li class="active">{!! trans("admin.cvs")  !!}</li> <!-- changed from app. to admin. -->
    </ol>
@endsection

@section("content")

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">

            <div class="box">
                <div class="box-body">
					<a href="{{ route('admin.cvs.create') }}" class="btn btn-success btn-sm" title="@lang('admin.Add') @lang('admin.New') Cv">
						<i class="fa fa-plus" aria-hidden="true"></i> Add New
					</a>
					
					<a data-table="Cv"  href="{{ route('admin.cvs.masstoggle') }}" data-ids="[]" class="btn btn-info btn-sm mass ajax_link refresh confirm" data-confirm="{{__('app.confirm_enable_selected')}}" data-status="1" title="{{__('admin.Enable')}} Cvs">
                            {{__('admin.Enable')}} : <span class="count">0</span> {{__('admin.selected')}} 
                        </a>
						<a data-table="Cv" href="{{ route('admin.cvs.masstoggle') }}" data-ids="[]" class="btn btn-info btn-sm mass ajax_link refresh confirm" data-confirm="{{__('app.confirm_disable_selected')}}"  data-status="0" title=" {{__('admin.Disable')}} Cvs">
                            {{__('admin.Disable')}} : <span class="count">0</span> {{__('admin.selected')}} 
                        </a>
						<a data-table="Cv"   href="{{ route('admin.cvs.massdelete') }}" data-ids="[]" class="btn btn-danger btn-sm ajax_link mass refresh confirm" data-confirm="{{__('app.confirm_delete_selected')}}" title="{{__('admin.Delete')}} Cvs">
                            {{__('admin.Delete')}} : <span class="count">0</span> {{__('admin.selected')}} 
                        </a>
						<a   href="javascript:;" class="btn btn-default btn-sm clear" title="Clear Selection Cvs">
                            {{__('admin.reset')}} : <span class="count">0</span> {{__('admin.selected')}} 
                        </a>
						
				    <hr>
                    <table id="Cv" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th >#</th>
							<th>{{ __('cvs.user_id') }}</th>
							<!--th>{{ __('cvs.address') }}</th-->
							<th>{{ __('cvs.qualifications') }}</th>
							<th>{{ __('cvs.country') }}</th>
							<th>{{ __('cvs.salary') }}</th>
							<th>{{ __('cvs.expirience') }}</th>
							<th>{{ __('cvs.type') }}</th>
							<th>{!! trans("admin.actions")  !!}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
	@include('modals.modal-delete')
</section><!-- /.content -->


@endsection

@push('js')
<script src="{{asset('/assets/admin/FileSaver.min.js')}}"></script>
<script src="{{asset('/assets/admin/sweetalert2.all.js')}}"></script>
<script src="{{asset('/assets/admin/jquery.blockUI.min.js')}}"></script>
<script src="{{asset('/assets/admin/datatables.min.js')}}"></script>
<script src="{{asset('/assets/admin/init.js')}}"></script>

@endpush

@section('js')

    <script>
        $(function() {
			@include('scripts.delete-modal-script')
			var selected = [];
          	window.Cv = $('#Cv').dataTable({
                "order": [[ 5, 'asc' ]],
                "language": {
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
                },
                processing: true,
                serverSide: true,
                "autoWidth": false,
				responsive: true,						   
				"rowCallback": function( row, data ) {
					if ( $.inArray(data.DT_RowId, selected) !== -1 ) {
						$(row).addClass('info');
						$(row).find('input.chkbx').prop('checked', true);
					}
				},
			    "ajax": {
					"url": "{{route('admin.cvs.table')}}",
					"type": "POST",
					'headers': { 'X-CSRF-TOKEN': '{{ csrf_token() }}'}
				},
				type:'POST',
                columns: [
					{data: 'id', name:  'id', orderable: true},
                    {data: 'user_id', name:  'user_id', orderable: true},
                    //{data: 'address', name:  'address', orderable: true},
                    {data: 'qualifications', name:  'qualifications', orderable: true},
                    {data: 'country', name:  'country', orderable: true},
                    {data: 'salary', name:  'salary', orderable: true},
                    {data: 'expirience', name:  'expirience', orderable: true},
                    {data: 'type', name:  'type', orderable: true},
					{data: 'actions', name: 'actions'},
                ]
            });
			$('#Cv tbody').on('click', 'input', function () {
				var tr = $(this).parents('tr')
				var id = tr.attr('id');
				
				var index = $.inArray(id, selected);
				if ( index === -1 ) {
					selected.push( id );
				} else {
					selected.splice( index, 1 );
				}
				
				$(tr).toggleClass('info');
				$('a.btn.mass').data('ids',selected);
				$('.count').text(selected.length);
			} );
			
			$('a.btn.clear').click(function(e) {
				selected =[];
				$('.count').text(0);
				$('a.btn.mass').data('ids',[]);
				$('.chkbx').prop('checked', false);
				$('tr.info','#Cv tbody').removeClass('info');
			});



        });
    </script>
@endsection
