@extends('layouts.employeecontent')
@section('employeecontent')


@if(session()->has('status'))
@if(session('status') === 'success')
<div class="alert alert-success" role="alert">{{ session('message') }}</div>
@else
<div class="alert alert-danger" role="alert">{{ session('message') }}</div>
@endif
@endif
@if(isset($user))
@php
$get_level = $user->process_level;
@endphp
@endif
@php
$currentLevel = 2;
@endphp
<p class="title-common">Add Vendor</p>
<div class="common-box onboarding-height-two">
    <div class="container-indicator">
        <section class="step-indicator">
            <div class="step step1 active in-complete">
                @if(isset($user))
                <a href="/onboarding/jnylavzkrs/{{$user->id}}">
                    <div class="step-icon">01</div>
                </a>
                @else
                <div class="step-icon">01</div>
                @endif
                <p>Personal Details</p>
            </div>
            @php
            $get_level_two_1 = $currentLevel == 2 || $user->process_level >= 2 ? 'active' : '';
            $get_level_two_2 = $currentLevel == 2 || $user->process_level >= 2 ? 'in-complete' : '';
            @endphp
            <div class="indicator-line {{$get_level_two_1}}"></div>
            <div class="step step2 {{$get_level_two_1}} {{$get_level_two_2}}">
                @if(isset($user) && ($currentLevel == 2 || $user->process_level >= 2))
                <a href="/onboarding/mqkpdfhzkt/{{$user->id}}">
                    <div class="step-icon">02</div>
                </a>
                @else
                <div class="step-icon">02</div>
                @endif
                <p>Business Info</p>
            </div>
        </section>
    </div>
    <div style="margin-top: 6rem !important;">
        {!! Form::open(['url' => '/vendor/onboarding/complete', 'method' => 'post' , 'autocomplete' => 'off' , 'id' => 'steptwoform']) !!}
        {{ Form::hidden('user_id', $user->id) }}
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <div class="mb-5">
                    {{Form::label('name', 'Business Type', array('class' => 'control-label'))}}
                    {{ Form::select('business_type_id',
                        $businesstype->pluck('type_name', 'id')->prepend('Select a Business Category', ''), 
                        isset($user->businessDetail) && $user->businessDetail->business_type_id != '' ? $user->businessDetail->business_type_id : null,
                        ['class' => 'form-control', 'id' => 'businesstypeselect' ])
                    }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <div class="mb-5">
                    {{Form::label('name', 'Business Category', array('class' => 'control-label'))}}
                    {{ Form::select('business_category_id', 
                        $businesscategory->pluck('category_name', 'id')->prepend('Select a Business Category', ''), 
                        isset($user->businessDetail) && $user->businessDetail->business_category_id != '' ? $user->businessDetail->business_category_id : null,
                        ['class' => 'form-control', 'id' => 'categorynameselect'])
                    }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <div class="mb-5">
                    {{Form::label('name', 'Business Sub Category ', array('class' => 'control-label'))}}
                    {{ Form::select('business_sub_category_id', 
                        $businesssubcategory->pluck('sub_category_name', 'id')->prepend('Select a Business Sub Category', ''), 
                        isset($user->businessDetail) && $user->businessDetail->business_sub_category_id != '' ? $user->businessDetail->business_sub_category_id : null,
                        ['class' => 'form-control', 'id' => 'subcategorynameselect'])
                    }}
                </div>
            </div>
        
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <div class="mb-5">
                    {{Form::label('bank_name', 'Bank Name', array('class' => 'control-label'))}}
                    {{ Form::text('bank_name', isset($user->businessDetail) && $user->businessDetail->bank_name != '' ? $user->businessDetail->bank_name : old('bank_name'), array('class' => 'form-control', 'id' => 'banknameinput', 'placeholder' => 'Bank Name')) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <div class="mb-5">
                    {{Form::label('bank_account_number', 'Bank Acc No', array('class' => 'control-label'))}}
                    {{ Form::text('bank_account_number',  isset($user->businessDetail) && $user->businessDetail->bank_account_number != '' ? $user->businessDetail->bank_account_number : old('bank_account_number') , array('class' => 'form-control', 'id' => 'bankaccnoinput', 'placeholder' => 'Bank Acc No')) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <div class="mb-5">
                    {{Form::label('bank_ifsc_code', 'Bank IFSC Code', array('class' => 'control-label'))}}
                    {{ Form::text('bank_ifsc_code', isset($user->businessDetail) && $user->businessDetail->bank_ifsc_code != '' ? $user->businessDetail->bank_ifsc_code : old('bank_ifsc_code') , array('class' => 'form-control', 'id' => 'bankifscnoinput', 'placeholder' => 'Bank IFSC Code')) }}
                </div>
            </div>
       
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <div class="mb-5">
                    {{Form::label('branch_name', 'Branch Name', array('class' => 'control-label'))}}
                    {{ Form::text('branch_name', isset($user->businessDetail) && $user->businessDetail->branch_name != '' ? $user->businessDetail->branch_name : old('branch_name'), array('id' => 'branchnameinput', 'class' => 'form-control', 'placeholder' => 'Branch Name','pattern' => '[A-Za-z\s]+')) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <div class="mb-5">
                    {{Form::label('account_holder_name', 'Account Holder Name', array('class' => 'control-label'))}}
                    {{ Form::text('account_holder_name',isset($user->businessDetail) && $user->businessDetail->account_holder_name != '' ? $user->businessDetail->account_holder_name : old('account_holder_name') , array( 'id' => 'accountholdernameinput' , 'class' => 'form-control', 'placeholder' => 'Account Holder Name')) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <div class="mb-5">
                    {{Form::label('company_name', 'Company Name', array('class' => 'control-label'))}}
                    {{ Form::text('company_name',isset($user->businessDetail) && $user->businessDetail->company_name != '' ? $user->businessDetail->company_name : old('company_name'), array('id' => 'companynameinput', 'class' => 'form-control', 'placeholder' => 'Company Name')) }}
                </div>
            </div>
        
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <div class="mb-5">
                    {{Form::label('company_address', 'Company Address', array('class' => 'control-label'))}}
                    {{ Form::text('company_address',isset($user->businessDetail) && $user->businessDetail->company_address != '' ? $user->businessDetail->company_address : old('company_address'), array('id' => 'companyaddressinput', 'class' => 'form-control', 'placeholder' => 'Company Address')) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <div class="mb-5">
                    {{Form::label('company_pincode', 'Pincode', array('class' => 'control-label'))}}
                    {{ Form::text('company_pincode',isset($user->businessDetail) && $user->businessDetail->company_pincode != '' ? $user->businessDetail->company_pincode : old('company_pincode'), array('id' => 'companypincodeinput', 'class' => 'form-control', 'placeholder' => 'Pincode'  ,'maxlength' => '6')) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <div class="mb-5">
                    {{Form::label('city', 'City', array('class' => 'control-label'))}}
                    {{ Form::text('city',isset($user->businessDetail) && $user->businessDetail->city != '' ? $user->businessDetail->city : old('city'), array( 'id' => 'cityinput', 'class' => 'form-control', 'placeholder' => 'City')) }}
                </div>
            </div>
        
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <div class="mb-5">
                    {{Form::label('state_id', 'State', array('class' => 'control-label'))}}
                    {{ Form::select('state_id', 
                        $state->pluck('state_name', 'id')->prepend('Select a State', ''),
                        isset($user->businessDetail) && $user->businessDetail->state_id != '' ? $user->businessDetail->state_id : null,
                        ['class' => 'form-control', 'id' => 'stateselect'])
                    }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <div class="mb-5">
                    {{Form::label('name', 'Country', array('class' => 'control-label'))}}
                    {{ Form::text('country', 'India', array('class' => 'form-control', 'placeholder' => 'Country', 'readonly' => 'readonly')) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <div class="mb-5">
                    {{Form::label('vendorAgreement', 'Vendor Agreement', array('class' => 'control-label'))}}
                    <div class="d-flex align-items-center">
                        <input type="file" name="vendorAgreement" class="inputfile form-control inputfile-2 file-upload" id="vendorAgreement" accept=".pdf, image/*" onchange="checkFileExtension(10,'vendorAgreement')">
                        @if(isset($user->userDocuments) && $user->userDocuments->count() > 0)
                        @foreach($user->userDocuments as $document)
                        @if($document['document_id'] == 9)
                        <a href="{{$document['base_url']}}/{{$document['document_path']}}" class="view-align" target="_blank">View</a>
                        @break
                        @endif
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center">
            {!! Form::submit('Complete', ['class' => 'outline-btn bgg-btn']) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>
<script>
    function checkFileExtension(document_id, classField) {
        var fileInput = $("#" + classField)[0];

        if (fileInput.files.length === 0) {
            toastr.error("Please select a file to upload.");
            return false;
        }

        var file = fileInput.files[0];

        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;

        if (!allowedExtensions.exec(file.name)) {
            toastr.error("Please upload a file with a valid extension (jpg, jpeg, png, pdf).");
            $("#" + classField).val('');
            return false;
        }

        var userId = <?php echo $user->id; ?>;

        var formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('docfile', file);
        formData.append('document_id', document_id);
        formData.append('user_id', userId);
        $("div#divLoading").removeClass('hide');
        $("div#divLoading").addClass('show');
        $.ajax({
            url: '{{ route("doc.file.upload") }}',
            type: 'POST',
            data: formData,
            processData: false, // Prevent jQuery from transforming the data
            contentType: false, // Ensure the content type is set correctly
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            },
            success: function(response) {
                if (response.status == 'success') {

                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
                $("div#divLoading").removeClass('show');
                $("div#divLoading").addClass('hide');
            }
        });
    }
    $(document).ready(function() {

        function validateField(field) {
            if (field.val().trim() === '') {
                field.addClass('is-invalid');
                field.siblings('.invalid-feedback').remove();
                field.after('<div class="invalid-feedback">This field is required.</div>');
                return false;
            } else {
                field.removeClass('is-invalid');
                field.siblings('.invalid-feedback').remove();
                return true;
            }
        }

        function isValid_IFSC_Code(field) {

            let regex = new RegExp(/^[A-Z]{4}0[A-Z0-9]{6}$/);

            if (field.val().trim() != '' && regex.test(field.val()) == true) {
                field.removeClass('is-invalid');
                field.siblings('.invalid-feedback').remove();
                return true;
            } else {
                field.addClass('is-invalid');
                field.siblings('.invalid-feedback').remove();
                field.after('<div class="invalid-feedback">This IFSC Code Invalid.</div>');
                return false;
            }
        }

        const minLength = 9;
        const maxLength = 18;

        function validateBankAccountNumber(field) {
            const value = field.val();
            if (value.length < minLength || value.length > maxLength) {
                field.addClass('is-invalid');
                field.siblings('.invalid-feedback').remove();
                field.after(`<div class="invalid-feedback">Bank account number must be between ${minLength} and ${maxLength} digits long.</div>`);
                return false;
            } else {
                field.removeClass('is-invalid');
                field.siblings('.invalid-feedback').remove();
                return true;
            }
        }
        const pinMaxLength = 6;

        function validatePincodeNumber(field) {
            const value = field.val();
            if (value.length > pinMaxLength) {
                field.addClass('is-invalid');
                field.siblings('.invalid-feedback').remove();
                field.after(`<div class="invalid-feedback">Pincode must have ${pinMaxLength} digits long.</div>`);
                return false;
            } else {
                field.removeClass('is-invalid');
                field.siblings('.invalid-feedback').remove();
                return true;
            }
        }

        // Select Fields
        $('#businesstypeselect').change(function() {
            validateField($(this));
        });
        $('#categorynameselect').change(function() {
            validateField($(this));
        });
        $('#subcategorynameselect').change(function() {
            validateField($(this));
        });
        // $('#monthlyexpenditureselect').change(function() {
        //     validateField($(this));
        // });
        $('#stateselect').change(function() {
            validateField($(this));
        });

        // Input Fileds 
        $('#banknameinput').on('input', function() {
            validateField($(this));
        });

        $('#bankaccnoinput').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            validateField($(this));
            validateBankAccountNumber($(this))
        });

        $('#bankifscnoinput').on('input', function() {
            validateField($(this));
        });
        $('#branchnameinput').on('input', function() {
            validateField($(this));
        });
        $('#accountholdernameinput').on('input', function() {
            validateField($(this));
        });
        $('#companynameinput').on('input', function() {
            validateField($(this));
        });
        $('#companyaddressinput').on('input', function() {
            validateField($(this));
        });
        $('#companypincodeinput').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            validateField($(this));
            validatePincodeNumber($(this));
        });
        $('#cityinput').on('input', function() {
            validateField($(this));
        });


        // Before submit validation
        $('#steptwoform').submit(function(event) {
            var isValid = true;
            var f1 = validateField($('#businesstypeselect'));
            var f2 = validateField($('#categorynameselect'));
            var f3 = validateField($('#subcategorynameselect'));
            // var f4 = validateField($('#monthlyexpenditureselect'));
            var f5 = validateField($('#stateselect'));
            var f6 = validateField($('#banknameinput'));
            var f7 = validateField($('#bankaccnoinput'));
            var f8 = validateField($('#bankifscnoinput'));
            var f9 = validateField($('#branchnameinput'));
            var f10 = validateField($('#accountholdernameinput'));
            var f11 = validateField($('#companynameinput'));
            var f12 = validateField($('#companyaddressinput'));
            var f13 = validateField($('#companypincodeinput'));
            var f14 = validateField($('#cityinput'));

            if (f8) {
                f8 = isValid_IFSC_Code($('#bankifscnoinput'));
            }

            if (f7) {
                f7 = validateBankAccountNumber($('#bankaccnoinput'));
            }

            if (!f1 || !f2 || !f3 || !f5 || !f6 || !f7 || !f8 || !f9 || !f10 || !f11 || !f12 || !f13 || !f14) {
                isValid = false
            }

            if (!isValid) {
                console.log("False");
                event.preventDefault();
            }
        });

        $('input[name="branch_name"]').on('input', function() {
            var sanitizedValue = $(this).val().replace(/[^A-Za-z\s]/g, '');
            $(this).val(sanitizedValue);
        });
    });
</script>
@endsection