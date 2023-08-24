@extends('admin.layouts.app')

@push('breadcrumb')
    {!! Breadcrumbs::render('users_create') !!}
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
        <form id="frmAddPlan" method="POST" action="{{ route('admin.plans.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                {{-- First Name --}}
                <div class="form-group">
                    <label for="name">{!!$mend_sign!!}Name:</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Enter name" autocomplete="name" spellcheck="false" autocapitalize="sentences" tabindex="0" autofocus />
                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong class="form-text">{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>

                {{-- Description --}}
                <div class="form-group">
                    <label for="description">{!!$mend_sign!!}Plan description:</label>
                    <textarea type="text" class="form-control" placeholder="Enter plan description" name="description" id="description" required></textarea>
                    @if ($errors->has('description'))
                        <span class="help-block">
                            <strong class="form-text">{{ $errors->first('description') }}</strong>
                        </span>
                    @endif
                </div>


                {{-- Month --}}
                <div class="form-group">
                    <label for="months">Months</label>
                    <input type="text" class="form-control @error('months') is-invalid @enderror" id="months" name="months" value="{{ old('months') }}" placeholder="Enter monthss" autocomplete="months" spellcheck="false" tabindex="0" />
                    @if ($errors->has('months'))
                        <span class="text-danger">
                            <strong class="form-text">{{ $errors->first('months') }}</strong>
                        </span>
                    @endif
                </div>


                {{-- Month --}}
                <div class="form-group">
                    <label for="special_offer_months">Special Offer Months</label>
                    <input type="text" class="form-control @error('special_offer_months') is-invalid @enderror" id="special_offer_months" name="special_offer_months" value="{{ old('special_offer_months') }}" placeholder="Enter special offer months" autocomplete="special_offer_months" spellcheck="false" tabindex="0" />
                    @if ($errors->has('special_offer_months'))
                        <span class="text-danger">
                            <strong class="form-text">{{ $errors->first('special_offer_months') }}</strong>
                        </span>
                    @endif
                </div>

                {{-- Active now --}}
                <div class="form-group">
                    <label for="is_active">Active now?{!!$mend_sign!!}</label>
                   <select id="is_active" class="form-control" name="is_active">

                        <option value="y"> Yes</option>
                        <option value="n"> No</option>
                    </select>
                    @if ($errors->has('is_active'))
                        <span class="help-block">
                            <strong class="form-text">{{ $errors->first('is_active') }}</strong>
                        </span>
                    @endif
                </div>

                {{-- Project --}}
                <div class="form-group">
                    <label for="project_id">Select Project{!!$mend_sign!!}</label>
                   <select id="project_id" class="form-control" name="project_id">
                        <option></option>
                        @foreach($projects as $project)
                            <option value={{$project->custom_id}}> {{ $project->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('project_id'))
                        <span class="help-block">
                            <strong class="form-text">{{ $errors->first('project_id') }}</strong>
                        </span>
                    @endif
                </div>

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary mr-2 text-uppercase"> Add {{ $custom_title }}</button>
                <a href="{{ route('admin.project.index') }}" class="btn btn-secondary text-uppercase">Cancel</a>
            </div>
        </form>
        <!--end::Form-->
    </div>
</div>
@endsection

@push('extra-js-scripts')
<script>
$(document).ready(function () {
    $("#frmAddPlan").validate({
        rules: {
            name: {
                required: true,
                not_empty: true,
                minlength: 3,
            },
            description: {
                required: true,
                not_empty: true,
                minlength: 3,
            },
            months: {
                required: true,
                digits: true,

            },
            special_offer_months: {
                required: true,
                digits: true,

            },
            is_active :{
                required: true,
            },
            project_id: {
                required: true,
            }
            // image:{
            //     extension: "jpg|jpeg|png",
            // },
        },
        messages: {
            name: {
                required: "@lang('validation.required',['attribute'=>'Name'])",
                not_empty: "@lang('validation.not_empty',['attribute'=>'Name'])",
                minlength:"@lang('validation.min.string',['attribute'=>'Name','min'=>3])",
            },
            description: {
                required: "@lang('validation.required',['attribute'=>'Description'])",
                not_empty: "@lang('validation.not_empty',['attribute'=>'Description'])",
                minlength:"@lang('validation.min.string',['attribute'=>'Description','min'=>3])",
            },
            months: {
                required: "@lang('validation.required',['attribute'=>'months'])",
                digits: "@lang('validation.digits',['attribute'=>'months'])",
                minlength:"@lang('validation.min.string',['attribute'=>'months','min'=>3])",
            },
            special_offer_months: {
                required: "@lang('validation.required',['attribute'=>'special offer months'])",
                digits: "@lang('validation.digits',['attribute'=>'special offer months'])",
                minlength:"@lang('validation.min.string',['attribute'=>'special offer months','min'=>3])",
            },
            is_active: {
                required: "@lang('validation.required',['attribute'=>'Active now'])",
            },
            project_id: {
                required: "@lang('validation.required',['attribute'=>'Project'])",
            }
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
