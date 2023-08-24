@extends('admin.layouts.app')

@push('breadcrumb')
    {!! Breadcrumbs::render('faqs_create') !!}
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
        <form id="frmAddPlan" method="POST" action="{{ route('admin.faqs.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                {{-- First Name --}}
                <div class="form-group">
                    <label for="question">{!!$mend_sign!!}Question:</label>
                    <input type="text" class="form-control @error('question') is-invalid @enderror" id="question" name="question" value="{{ old('question') }}" placeholder="Enter question" autocomplete="question" spellcheck="false" autocapitalize="sentences" tabindex="0" autofocus />
                    @if ($errors->has('question'))
                        <span class="help-block">
                            <strong class="form-text">{{ $errors->first('question') }}</strong>
                        </span>
                    @endif
                </div>

                {{-- Description --}}
                <div class="form-group">
                    <label for="answer">{!!$mend_sign!!}Answer:</label>
                    <textarea type="text" class="form-control" placeholder="Enter answer" name="answer" id="answer" required></textarea>
                    @if ($errors->has('answer'))
                        <span class="help-block">
                            <strong class="form-text">{{ $errors->first('answer') }}</strong>
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
                <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary text-uppercase">Cancel</a>
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
            question: {
                required: true,
                not_empty: true,
                minlength: 3,
            },
            answer: {
                required: true,
                not_empty: true,
                minlength: 3,
            },

            project_id: {
                required: true,
            }
            // image:{
            //     extension: "jpg|jpeg|png",
            // },
        },
        messages: {
            question: {
                required: "@lang('validation.required',['attribute'=>'question'])",
                not_empty: "@lang('validation.not_empty',['attribute'=>'question'])",
                minlength:"@lang('validation.min.string',['attribute'=>'question','min'=>3])",
            },
            answer: {
                required: "@lang('validation.required',['attribute'=>'answer'])",
                not_empty: "@lang('validation.not_empty',['attribute'=>'answer'])",
                minlength:"@lang('validation.min.string',['attribute'=>'answer','min'=>3])",
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
