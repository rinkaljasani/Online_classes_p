@extends('admin.layouts.app')

@push('breadcrumb')
    {!! Breadcrumbs::render('plans_list') !!}
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
                    <a href="{{ route('admin.plans.destroy', 0) }}" name="del_select" id="del_select" class="btn btn-sm btn-light-danger font-weight-bolder text-uppercase mr-2 delete_all_link">
                        <i class="far fa-trash-alt"></i> Delete Selected
                    </a>
                @endif
                @if (in_array('add', $permissions))
                    <a href="{{ route('admin.plans.create') }}" class="btn btn-sm btn-primary font-weight-bolder text-uppercase">
                        <i class="fas fa-plus"></i>
                        Add {{ $custom_title }}
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-10 ">
                <div class="col-lg-4">
                    <label class="">Project</label>
                    <select class="form-control select2" id="project_id" name="project_id">
                        @foreach($projects as $project)
                            <option value="{{ $project->custom_id}}">{{ $project->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            {{--  Datatable Start  --}}
            <table class="table table-bordered table-hover table-checkable" id="plan_table" style="margin-top: 13px !important"></table>
            {{--  Datatable End  --}}
        </div>
    </div>
</div>
@endsection

@push('extra-js-scripts')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script>
    $(document).ready(function () {
        dataTableValue();
        // datatable
        function dataTableValue()
        {
            let project_id = $('#project_id').val();
            oTable = $('#plan_table').DataTable({
            responsive: true,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.plans.listing') }}",
                data: {
                    columnsDef: ['checkbox','name','project' ,'months', 'special_offer_months','price','prorities', 'active', 'action'],
                    project_id: project_id,
                },
            },
            columns: [
                { data: 'checkbox' },
                { data: 'name' },
                { data: 'project' },
                { data: 'months' },
                { data: 'special_offer_months' },
                { data: 'price' },
                { data: 'prorities' },
                { data: 'active' },
                { data: 'action', responsivePriority: -1 },
            ],
            columnDefs: [
                // Specify columns titles here...
                { targets: 0, title: "<center><input type='checkbox' class='all_select'></center>", orderable: false },
                { targets: 1, title: 'Name', orderable: true },
                { targets: 2, title: 'Project', orderable: false },
                { targets: 3, title: 'Month', orderable: true },
                { targets: 4, title: 'Special Offer Month', orderable: true },
                { targets: 5, title: 'Price', orderable: true },
                { targets: 6, title: 'Priority', orderable: true },
                { targets: 7, title: 'Active', orderable: false },

                // Action buttons
                { targets: -1, title: 'Action', orderable: false },
            ],
            order: [
                [1, 'asc']
            ],
            lengthMenu: [
                [10, 20, 50, 100],
                [10, 20, 50, 100]
            ],
            pageLength: 10,
        });
        }
        $('#project_id').on('change',function(){
            oTable.destroy();
            dataTableValue();
        })
    });

     // placeholder for drop down menu
     $('#project_id').select2({
        placeholder: "Select a currancy"
    });
</script>
@endpush
