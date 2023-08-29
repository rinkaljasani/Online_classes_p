@extends('admin.layouts.app')

@push('breadcrumb')
    {!! Breadcrumbs::render('user_plans_list') !!}
@endpush

@push('extra-css-styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" />
@endpush

@section('content')
<div class="container">

    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <span class="card-icon">
                    <i class="fas fa-users text-primary"></i>
                </span>
                <h3 class="card-label">{{ $custom_title }}</h3>
            </div>

            <div class="card-toolbar">
                @if (in_array('delete', $permissions))
                    <a href="{{ route('admin.user_plans.destroy', 0) }}" name="del_select" id="del_select" class="btn btn-sm btn-light-danger font-weight-bolder text-uppercase mr-2 delete_all_link">
                        <i class="far fa-trash-alt"></i> Delete Selected
                    </a>
                @endif
                @if (in_array('add', $permissions))
                    <a href="{{ route('admin.user_plans.create') }}" class="btn btn-sm btn-primary font-weight-bolder text-uppercase">
                        <i class="fas fa-plus"></i>
                        Add {{ $custom_title }}
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            {{-- Date Filter --}}
            <div class="form-group row">
				<label class="col-form-label col-lg-2 col-sm-12">Date Filter:</label>
				<div class="col-lg-4 col-md-9 col-sm-12">
					<div class='input-group' id='date_filter'>
						<input type='text' class="form-control" id="date_filter" readonly name="date_filter"  placeholder="Select date range"/>
                        <input type="hidden" name="start_date" id="start_date">
                        <input type="hidden" name="end_date" id="end_date">
						<div class="input-group-append">
							<span class="input-group-text"><i class="la la-calendar-check-o"></i></span>
						</div>
					</div>
				</div>
			</div>

            {{--  Datatable Start  --}}
            <table class="table table-bordered table-hover table-checkable" id="user_plans_table" style="margin-top: 13px !important"></table>
            {{--  Datatable End  --}}
        </div>
    </div>
</div>
@endsection

@push('extra-js-scripts')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script>
    $(document).ready(function () {

        $('#date_filter').daterangepicker({
            buttonClasses: ' btn',
			applyClass: 'btn-primary',
			cancelClass: 'btn-secondary'
        },
        function(start,end,label){
            $('#date_filter .form-control').val( start.format('YYYY-MM-DD') + ' / ' + end.format('YYYY-MM-DD'));
            $('#start_date').val(start.format('YYYY-MM-DD'));
            $('#end_date').val(end.format('YYYY-MM-DD'));
            oTable.draw();
        });
        dataTableValue();
        // datatable
        function dataTableValue()
        {
            oTable = $('#user_plans_table').DataTable({
            responsive: true,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.user_plans.listing') }}",
                data: {
                    columnsDef: ['checkbox','id','user_id','plan_id','project_name', 'device_id','device_type','purchase_at','expiry_at','active','action'],
                    start_date : function(){
                        return $('#start_date').val();
                    },
                    end_date : function(){
                        return $('#end_date').val();
                    },
                },
            },
            columns: [
                { data: 'checkbox' },
                {data : 'id', visible:false},
                { data: 'user_id' },
                { data: 'plan_id' },
                { data: 'project_id' },
                { data: 'device_id' },
                { data: 'device_type' },
                { data: 'purchase_at' },
                { data: 'expiry_at' },
                { data: 'active' },
                { data: 'action', responsivePriority: -1 },
            ],
            columnDefs: [
                // Specify columns titles here...
                { targets: 0, title: "<center><input type='checkbox' class='all_select'></center>", orderable: false },
                { targets: 1, title: 'id', orderable: false },
                { targets: 2, title: 'User Name', orderable: false },
                { targets: 3, title: 'Plan Name', orderable: false },
                { targets: 4, title: 'Project Name', orderable: false },
                { targets: 5, title: 'Device Id', orderable: false },
                { targets: 6, title: 'Device Name', orderable: false },
                { targets: 7, title: 'Plan Starts From', orderable: false },
                { targets: 8, title: 'Plan Expiry', orderable: false },
                { targets: 9, title: 'Active', orderable: false },

                // Action buttons
                { targets: -1, title: 'Action', orderable: false },
            ],
            order: [
                [1, 'desc']
            ],
            lengthMenu: [
                [10, 20, 50, 100],
                [10, 20, 50, 100]
            ],
            pageLength: 10,
        });
        }
        // $('#project_id').on('change',function(){
        //     oTable.destroy();
        //     dataTableValue();
        // })
    });

     // placeholder for drop down menu
     $('#project_id').select2({
        placeholder: "Select a currancy"
    });
    // $('#date_range').on('change.datepicker',function(){
    //     console.log('hello');
    //     console.log($(this).val());
    // });
</script>
@endpush
