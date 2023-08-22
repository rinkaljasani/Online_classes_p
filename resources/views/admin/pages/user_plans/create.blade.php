@extends('admin.layouts.app')

@push('breadcrumb')
    {!! Breadcrumbs::render('user_plans_create') !!}
@endpush

@section('content')
<div class="container">
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <span class="card-icon">
                    <i class="fas fa-user-plus text-primary"></i>
                </span>
                <h3 class="card-label text-uppercase">ADD {{ $custom_title }}</h3>
            </div>
        </div>

        <!--begin::Form-->
        <form id="frmAddUserPlan" method="POST" action="{{ route('admin.user_plans.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">

                {{-- Project Id --}}
                <div class="form-group">
                    <label for="project_id">Select Project{!!$mend_sign!!}</label>
                   <select id="project_id" class="form-control" name="project_id" data-error-container="#project_id_error_container">
                        <option></option>
                        @foreach($projects as $project)
                            <option value={{$project->id}} > {{ $project->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('project_id'))
                        <span class="help-block">
                            <strong class="form-text">{{ $errors->first('project_id') }}</strong>
                        </span>
                    @endif
                    <span id="project_id_error_container"></span>
                </div>
                {{-- User Id --}}
                <div class="form-group">
                    <label for="user_id">Select User{!!$mend_sign!!}</label>
                   <select id="user_id" class="form-control" name="user_id" data-error-container="#user_id_error_container">
                    <option>Select Project First</option>

                    </select>
                    @if ($errors->has('user_id'))
                        <span class="help-block">
                            <strong class="form-text">{{ $errors->first('user_id') }}</strong>
                        </span>
                    @endif
                    <span id="user_id_error_container"></span>
                </div>
                {{-- Plan Id --}}
                <div class="form-group">
                    <label for="plan_id">Select Plan{!!$mend_sign!!}</label>
                   <select id="plan_id" class="form-control" name="plan_id" data-error-container="#plan_id_error_container">
                        <option>Select Project First</option>

                    </select>
                    @if ($errors->has('plan_id'))
                        <span class="help-block">
                            <strong class="form-text">{{ $errors->first('plan_id') }}</strong>
                        </span>
                    @endif
                    <span id="plan_id_error_container"></span>
                </div>
                {{-- Device ID --}}
                <div class="form-group">
                    <label for="device_id">Device Id{!!$mend_sign!!}</label>
                   <input type="text" name="device_id" placeholder="Enter Device Id" id="device_id" class="form-control">
                   {{-- <select id="device_id" class="form-control" name="device_id" data-error-container="#device_id_error_container">
                    <option>Select User First</option>

                </select> --}}
                    @if ($errors->has('device_id'))
                        <span class="help-block">
                            <strong class="form-text">{{ $errors->first('device_id') }}</strong>
                        </span>
                    @endif
                    <span id="device_id_error_container"></span>
                </div>
                {{-- Device Type --}}
                <div class="form-group">
                    <label for="device_type">Device Type{!!$mend_sign!!}</label>
                   <input type="text" name="device_type" placeholder="Enter Device Type" id="device_type" class="form-control">
                    @if ($errors->has('device_type'))
                        <span class="help-block">
                            <strong class="form-text">{{ $errors->first('device_type') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary mr-2 text-uppercase"> Add {{ $custom_title }}</button>
                <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary text-uppercase">Cancel</a>
            </div>
        </form>
        <!--end::Form-->
    </div>
</div>
@endsection

@push('extra-js-scripts')
<script>
$(document).ready(function () {
    $('#project_id').select2({
        placeholder:"Select Project"
    });
    $('#user_id').select2({
        placeholder:"Select Users"
    });
    $('#plan_id').select2({
        placeholder:"Select Plan"
    });
    // $('#device_id').select2({
    //     placeholder:"Select Device Id"
    // });

    $('#project_id').change(function(){
        console.log('Project');
        var project_id = $(this).val();
        $.ajax({
            url: "{{route('admin.get_projects.users_plans')}}",
            type: 'POST',
            dataType: 'json',
            data: {
                _token: "{{csrf_token()}}",
                project_id
            },
            success:function(response){

                var usersOptions = plansOptions = '<option></option>';

                if( response.users.length > 0){

                    response.users.map(function(user){
                        usersOptions += `<option value="${user.id}">${user.first_name} ${user.last_name}</option>`;
                    });
                    // $('#user_id').select2('refresh');
                }
                if(response.plans.length > 0){
                    response.plans.map(function(plan){
                        plansOptions += `<option value="${plan.id}">${plan.name}</option>`;
                    });
                    // $('#plan_id').select2('refresh');
                }
                $('#plan_id').html(plansOptions);
                $('#user_id').html(usersOptions);


                console.log(response);
            },
        });
    });
    $('#user_id').change(function(){

        var user_id = $(this).val();
        $.ajax({
            url: "{{route('admin.get_active_users_devices')}}",
            type: 'POST',
            dataType: 'json',
            data: {
                _token: "{{csrf_token()}}",
                user_id
            },
            success:function(response){
                console.log(response.device);
                if(response.device !== null){
                    $('#device_id').val(response.device.device_id);
                    $('#device_type').val(response.device.device_type);
                }
            },
        });
    });

    $("#frmAddUserPlan").validate({
        rules: {
            user_id: {
                required: true,
            },
            project_id: {
                required: true,
            },
            plan_id: {
                required: true,
            },
            device_id: {
                required: true,
                not_empty:true,
            },
            device_type: {
                required: true,
                not_empty:true,
            }
        },
        messages: {
            user_id: {
                required: "@lang('validation.required',['attribute'=>'User'])",
            },
            plan_id: {
                required: "@lang('validation.required',['attribute'=>'Plan'])",
            },
            project_id: {
                required: "@lang('validation.required',['attribute'=>'Project'])",
            },
            device_id: {
                required: "@lang('validation.required',['attribute'=>'Device Id'])",
                not_empty: "@lang('validation.not_empty',['attribute'=>'Device Id'])",
            },
            device_type: {
                required: "@lang('validation.required',['attribute'=>'Device Type'])",
                not_empty: "@lang('validation.not_empty',['attribute'=>'Device Id'])",
            },
        },
        errorClass: 'invalid-feedback',
        errorElement: 'span',
        highlight: function (element) {
            $(element).addClass('is-invalid');
            $(element).siblings('label').addClass('text-danger'); // For Label
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
            $(element).siblings('label').removeClass('text-danger'); // For Label
        },
        errorPlacement: function (error, element) {
            if (element.attr("data-error-container")) {
                error.appendTo(element.attr("data-error-container"));
            } else {
                error.insertAfter(element);
            }
        }
    });

});
</script>
@endpush
