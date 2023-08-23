@extends('admin.layouts.app')

@push('breadcrumb')
    {!! Breadcrumbs::render('plans_update', $plan->id) !!}
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
        <form id="frmEditUser" method="POST" action="{{ route('admin.plans.update', $plan->custom_id) }}" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="card-body">
                <input type="hidden" name="project_id" value="{{ $plan->project_id }}" />
                <input type="hidden" name="is_active" value="{{ $plan->is_active }}" />
                {{--  Name --}}
                <div class="form-group">
                    <label for="name">{!!$mend_sign!!}Name:</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') != null ? old('name') : $plan->name }}" placeholder="Enter first name" autocomplete="name" spellcheck="false" autocapitalize="sentences" tabindex="0" autofocus />
                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong class="form-text">{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>

                {{-- Description --}}
                <div class="form-group">
                    <label for="description">{!!$mend_sign!!}Description:</label>
                    <textarea type="text" class="form-control" placeholder="Enter plan description" name="description" id="description" required>{{ $plan->description }}</textarea>
                    @if ($errors->has('description'))
                        <span class="help-block">
                            <strong class="form-text">{{ $errors->first('description') }}</strong>
                        </span>
                    @endif
                </div>

                {{-- Months --}}
                <div class="form-group">
                    <label for="price">{!!$mend_sign!!}Price:</label>
                    <input type="price" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') != null ? old('price') : $plan->price }}" placeholder="Enter price" autocomplete="price" spellcheck="false" tabindex="0" />
                    @if ($errors->has('price'))
                        <span class="text-danger">
                            <strong class="form-text">{{ $errors->first('price') }}</strong>
                        </span>
                    @endif
                </div>

                {{-- Months --}}
                <div class="form-group">
                    <label for="months">{!!$mend_sign!!}Months:</label>
                    <input type="months" class="form-control @error('months') is-invalid @enderror" id="months" name="months" value="{{ old('months') != null ? old('months') : $plan->months }}" placeholder="Enter months" autocomplete="months" spellcheck="false" tabindex="0" />
                    @if ($errors->has('months'))
                        <span class="text-danger">
                            <strong class="form-text">{{ $errors->first('months') }}</strong>
                        </span>
                    @endif
                </div>

                {{-- Special offer months --}}
                <div class="form-group">
                    <label for="special_offer_months">Special Offer months</label>
                    <input type="special_offer_months" class="form-control @error('special_offer_months') is-invalid @enderror" id="special_offer_months" name="special_offer_months" value="{{ old('special_offer_months') != null ? old('special_offer_months') : $plan->special_offer_months }}" placeholder="Enter contact number" autocomplete="special_offer_months" spellcheck="false" tabindex="0" />
                    @if ($errors->has('special_offer_months'))
                        <span class="text-danger">
                            <strong class="form-text">{{ $errors->first('special_offer_months') }}</strong>
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
            description: {
                required: true,
                not_empty: true,
                minlength: 3,
            },
            months: {
                required: true,
                digits: true,

            },
            price: {
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
            price: {
                required: "@lang('validation.required',['attribute'=>'price'])",
                digits: "@lang('validation.digits',['attribute'=>'price'])",
                minlength:"@lang('validation.min.string',['attribute'=>'price','min'=>3])",
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
