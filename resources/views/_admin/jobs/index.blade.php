
@extends('adminlte::page')
@section('title')
 {!! __("admin.jobs")  !!}
@endsection
@push('css')
 <link href="{{asset('/assets/admin/mycustom.css')}}" rel="stylesheet">
 <link href="{{asset('/assets/admin/datatables.min.css')}}" rel="stylesheet">
@endpush

@section('content_header')
     <h1>
        {!! __("admin.jobs")  !!} <!-- changed from app. to admin. -->
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {!! __("admin.dashboard")  !!}</a></li>
        <li class="active">{!! __("admin.jobs")  !!}</li> <!-- changed from app. to admin. -->
    </ol>
@endsection

@section("content")

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
					<a href="{{ route('admin.jobs.create') }}" class="btn btn-success btn-sm" title="@lang('admin.Add') @lang('admin.New') Job">
						<i class="fa fa-plus" aria-hidden="true"></i> Add New
					</a>
					<a data-table="Job"  href="{{ route('admin.jobs.masstoggle') }}" data-ids="[]" class="btn btn-info btn-sm mass ajax_link refresh confirm" data-confirm="{{__('app.confirm_enable_selected')}}" data-status="1" title="{{__('admin.Enable')}} Jobs">
                            {{__('admin.Enable')}} : <span class="count">0</span> {{__('admin.selected')}} 
                        </a>
						<a data-table="Job" href="{{ route('admin.jobs.masstoggle') }}" data-ids="[]" class="btn btn-info btn-sm mass ajax_link refresh confirm" data-confirm="{{__('app.confirm_disable_selected')}}"  data-status="0" title=" {{__('admin.Disable')}} Jobs">
                            {{__('admin.Disable')}} : <span class="count">0</span> {{__('admin.selected')}} 
                        </a>
						<a data-table="Job"   href="{{ route('admin.jobs.massdelete') }}" data-ids="[]" class="btn btn-danger btn-sm ajax_link mass refresh confirm" data-confirm="{{__('app.confirm_delete_selected')}}" title="{{__('admin.Delete')}} Jobs">
                            {{__('admin.Delete')}} : <span class="count">0</span> {{__('admin.selected')}} 
                        </a>
						<a   href="javascript:;" class="btn btn-default btn-sm clear" title="Clear Selection Jobs">
                            {{__('admin.reset')}} : <span class="count">0</span> {{__('admin.selected')}} 
                        </a>
						
				    <hr>
                    <table id="Job" class="table table-bordered table-hover">
                        <thead>
							<tr>
								<th >#</th>
								<th>{{ __('jobs.user_id') }}</th>
								<th>{{ __('jobs.country') }}</th>
								<th>{{ __('jobs.address') }}</th>
								<th>{{ __('jobs.title') }}</th>
								<th>{{ __('jobs.count') }}</th>
								<th>{{ __('jobs.status') }}</th>
								<th>{!! __("admin.actions")  !!}</th>
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
          	window.Job = $('#Job').dataTable({
                "order": [[ 5, 'asc' ]],
                "language": {
                    "sDecimal":        ",",
                    "sEmptyTable":     "{!! __("admin.sEmptyTable")  !!}",
                    "sInfo":           "{!! __("admin.sInfo")  !!}",
                    "sInfoEmpty":      "{!! __("admin.sInfoEmpty")  !!}",
                    "sInfoFiltered":   "{!! __("admin.sInfoFiltered")  !!}",
                    "sInfoPostFix":    "",
                    "sInfoThousands":  ".",
                    "sLengthMenu":     "{!! __("admin.sLengthMenu")  !!}",
                    "sLoadingRecords": "{!! __("admin.sLoadingRecords")  !!}",
                    "sProcessing":     "{!! __("admin.sProcessing")  !!}",
                    "sSearch":         "{!! __("admin.sSearch")  !!}",
                    "sZeroRecords":    "{!! __("admin.sZeroRecords")  !!}",
                    "oPaginate": {
                        "sFirst":    "{!! __("admin.sFirst")  !!}",
                        "sLast":     "{!! __("admin.sLast")  !!}",
                        "sNext":     "{!! __("admin.sNext")  !!}",
                        "sPrevious": "{!! __("admin.sPrevious")  !!}"
                    },
                    "oAria": {
                        "sSortAscending":  "{!! __("admin.sSortAscending")  !!}",
                        "sSortDescending": "{!! __("admin.sSortDescending")  !!}"
                    }
                },
                processing: true,
                serverSide: true,
                responsive: true,
                "autoWidth": false,
				"rowCallback": function( row, data ) {
					if ( $.inArray(data.DT_RowId, selected) !== -1 ) {
						$(row).addClass('info');
						$(row).find('input.chkbx').prop('checked', true);
					}
				},
			    "ajax": {
					"url": "{{route('admin.jobs.table')}}",
					"type": "POST",
					'headers': { 'X-CSRF-TOKEN': '{{ csrf_token() }}'}
				},
				type:'POST', 
                columns: [
                    {data: 'id', name:  'id', orderable: true},
                    {data: 'user_id', name:  'user_id', orderable: true},
                    {data: 'country', name:  'country', orderable: true},
                    {data: 'address', name:  'address', orderable: true},
                    {data: 'title', name:  'title', orderable: true},
                    {data: 'count', name:  'count', orderable: true},
                    {data: 'status', name:  'status', orderable: true},
					{data: 'actions', name: 'actions'},
					]
            });
			$('#Job tbody').on('click', 'input', function () {
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
				$('tr.info','#Job tbody').removeClass('info');
			});



        });
    </script>
@endsection
