@extends('admin.layouts.app')

@push('breadcrumb')
    {!! Breadcrumbs::render('projects_update', $project->id) !!}
@endpush

@section('content')
<div class="container">
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <span class="card-icon">
                    <i class="fas fa-user-edit text-primary"></i>
                </span>
                <h3 class="card-label text-uppercase">Edit {{ $custom_title }}</h3>
            </div>
        </div>

        <!--begin::Form-->
        <form id="frmEditUser" method="POST" action="{{ route('admin.projects.update', $project->custom_id) }}" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="card-body">
                <input type="hidden" name="project_id" value="{{ $project->project_id }}" />
                <input type="hidden" name="is_active" value="{{ $project->is_active }}" />
                {{--  Name --}}
                <div class="form-group">
                    <label for="name">{!!$mend_sign!!}Name:</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') != null ? old('name') : $project->name }}" placeholder="Enter first name" autocomplete="name" spellcheck="false" autocapitalize="sentences" tabindex="0" autofocus />
                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong class="form-text">{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary mr-2">Update {{ $custom_title }}</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
        <!--end::Form-->
    </div>
</div>
@endsection

@push('extra-js-scripts')
<script>
$(document).ready(function () {
    $("#frmEditUser").validate({
        rules: {
            name: {
                required: true,
                not_empty: true,
                minlength: 3,
            },
        },
        messages: {
            name: {
                required: "@lang('validation.required',['attribute'=>'first name'])",
                not_empty: "@lang('validation.not_empty',['attribute'=>'first name'])",
                minlength:"@lang('validation.min.string',['attribute'=>'first name','min'=>3])",
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
            $(element).siblings('label').removeClass('text-danger');
        },
        errorPlacement: function (error, element) {
            if (element.attr("data-error-container")) {
                error.appendTo(element.attr("data-error-container"));
            } else {
                error.insertAfter(element);
            }
        }
    });
    $('#frmEditUser').submit(function () {
        if ($(this).valid()) {
            addOverlay();
            $("input[type=submit], input[type=button], button[type=submit]").prop("disabled", "disabled");
            return true;
        } else {
            return false;
        }
    });

    //remove the imaegs
    $(".remove-img").on('click',function(e){
        e.preventDefault();
        $(this).parents(".symbol").remove();
        $('#frmEditUser').append('<input type="hidden" name="remove_profie_photo" id="remove_image" value="removed">');
    });
});
</script>
@endpush
