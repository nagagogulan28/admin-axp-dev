@extends('layouts.employeecontent')
@section('employeecontent')

@if(session()->has('status'))
@if(session('status') === 'success')
<div class="alert alert-success" role="alert">{{ session('message') }}</div>
@else
<div class="alert alert-danger" role="alert">{{ session('message') }}</div>
@endif
@endif


<p class="title-common">Commission Settings</p>
<div>
    <div class="common-box px-0">
        <div class="merchant-sec">
            <div class="three-step" style="background: #F9FBFC;">
                <div class="row justify-content-between">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                        <div class="mb-3">
                            {{Form::label('name', 'Name', array('class' => 'control-label'))}}
                            <div>{{$user->first_name}} {{$user->last_name}}</div>
                            {{ Form::hidden('actual_merchant_id', $user->id, ['class' => 'form-control', 'id' => 'actual_merchant_id']) }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                        <div class="mb-3">
                            {{Form::label('name', 'Email', array('class' => 'control-label'))}}
                            <div>{{ $user->personal_email }}</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                        <div class="mb-3">
                            {{Form::label('name', 'Contact No', array('class' => 'control-label'))}}
                            <div>{{ $user->mobile_no }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="three-step" style="background: #F9FBFC;">
                <div class="row pt-2 justify-content-between">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                        <div class="mb-3">
                            {{Form::label('name', 'User ID', array('class' => 'control-label'))}}
                            <div>{{$user->user_label_id}}</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                        <div class="mb-3">
                            {{ Form::label('pay_out', 'Pay-Out Status', array('class' => 'control-label')) }}
                            <div style="height: 45px;display: flex;align-items: center;">
                                @if($user->businessDetail && $user->businessDetail->pay_out)
                                @php
                                $payOut = 'Enabled';
                                $payOutCheck = 'badge-success';
                                @endphp
                                @else
                                @php
                                $payOut = 'Disabled';
                                $payOutCheck = 'badge-danger';
                                @endphp
                                @endif
                                <span class="badge {{ $payOutCheck }}">{{ $payOut }}</span>
                                {{ Form::hidden('pay_out', $user->businessDetail->pay_out , ['id' => 'pay_out_input']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                        <div class="mb-3">
                            {{Form::label('payout_percentage_fees', 'Percentage Fees', array('class' => 'control-label'))}}
                            {{ Form::text('payout_percentage_fees', $user->businessDetail->payout_percentage_fees > 0 ? $user->businessDetail->payout_percentage_fees : '0.00' , array('class' => 'form-control','disabled' => 'disabled','id' => 'percentage_fees' ,'placeholder' => 'Percentage Fees')) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="common-box mt-3">
    <div class="search-btn px-2 justify-content-end">
        <div>
            <button class="bg-btn" id="add-slab-settings-btn" data-toggle="modal" data-actualpercentage="{{$user->businessDetail->payout_percentage_fees > 0 ? $user->businessDetail->payout_percentage_fees : '0.00'}}" data-id="{{$user->id}}" data-name="{{$user->first_name }} {{ $user->last_name}}" data-target="#add-slab-settings-model" style="display: flex;align-items: center;"><img src="{{ asset('new/img/add.svg')}}" alt="print" style="margin-right:5px">Add Slab</button>
        </div>
    </div>
    <div class="table-data">
        <table id="slabsettingsList" class="table" style="width:100%;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Min Range</th>
                    <th>Max Range</th>
                    <th>IMPS</th>
                    <th>NEFT</th>
                    <th>RTGS</th>
                    <th>UPI</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="add-slab-settings-model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg .modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Slab Setting Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="priceSettingForm" method="POST" class="form-horizontal">
                {{ csrf_field() }}

                <div class="modal-body">
                    <!-- Section 1 -->
                    <div class="form-group row">
                        <div class="col-sm-6">
                            {{ Form::label('merchant_id', 'Merchant Name', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                            {{ Form::text('merchant_name', null, ['class' => 'form-control', 'id' => 'merchant_name', 'disabled' => 'disabled']) }}
                            {{ Form::hidden('merchant_id', null, ['class' => 'form-control', 'id' => 'merchant_id']) }}
                            <span class="text-danger" id="error-merchant_id"></span>
                        </div>
                        <div class="col-sm-6">
                            {{ Form::label('percentage', 'Percentage Fee (%)', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                            {{ Form::text('percentage', null, ['data-id' => 'error-percentage','class' => 'form-control', 'id' => 'percentage']) }}
                            <span class="text-danger" id="error-percentage"></span>
                        </div>
                    </div>

                    <!-- Section 2 -->
                    <div class="settingssection">
                        <div class="form-group">
                            {{ Form::label('flat_fee_limit', 'Set Flat fee Limit', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                            {{ Form::text('flat_fee_limit[]', '1 ₹ to 2,000 ₹' , ['class' => 'form-control' , 'id' => 'flat_fee_limit', 'disabled' => 'disabled']) }}
                            {{ Form::hidden('flat_fee_limit[]', 1 , ['class' => 'form-control']) }}
                            <span class="text-danger" id="error-flat_fee_limit"></span>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                {{ Form::label('imps', 'IMPS', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                                {{ Form::text('imps[]', null, [ 'id' => 'imps_0' , 'data-id' => 'error-imps_0' , 'class' => 'form-control']) }}
                                <span class="text-danger" id="error-imps_0"></span>
                            </div>
                            <div class="col-sm-3">
                                {{ Form::label('neft', 'NEFT', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                                {{ Form::text('neft[]', null, [ 'id' => 'neft_0' , 'data-id' => 'error-neft_0' , 'class' => 'form-control']) }}
                                <span class="text-danger" id="error-neft_0"></span>
                            </div>
                            <div class="col-sm-3">
                                {{ Form::label('rtgs', 'RTGS', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                                {{ Form::text('rtgs[]', null, [ 'id' => 'rtgs_0' , 'data-id' => 'error-rtgs_0' , 'class' => 'form-control']) }}
                                <span class="text-danger" id="error-rtgs_0"></span>
                            </div>
                            <div class="col-sm-3">
                                {{ Form::label('upi', 'UPI', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                                {{ Form::text('upi[]', null, [ 'id' => 'upi_0' , 'data-id' => 'error-upi_0' , 'class' => 'form-control']) }}
                                <span class="text-danger" id="error-upi_0"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3 -->
                    <div class="settingssection">
                        <div class="form-group">
                            {{ Form::label('flat_fee_limit', 'Set Flat fee Limit', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                            {{ Form::text('flat_fee_limit[]', '2,001 ₹ to 25,000 ₹' , ['class' => 'form-control' , 'id' => 'flat_fee_limit', 'disabled' => 'disabled']) }}
                            {{ Form::hidden('flat_fee_limit[]', 2 , ['class' => 'form-control']) }}
                            <span class="text-danger" id="error-flat_fee_limit"></span>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                {{ Form::label('imps', 'IMPS', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                                {{ Form::text('imps[]', null, [ 'id' => 'imps_1' , 'data-id' => 'error-imps_1' , 'class' => 'form-control']) }}
                                <span class="text-danger" id="error-imps_1"></span>
                            </div>
                            <div class="col-sm-3">
                                {{ Form::label('neft', 'NEFT', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                                {{ Form::text('neft[]', null, [ 'id' => 'neft_1' , 'data-id' => 'error-neft_1' , 'class' => 'form-control']) }}
                                <span class="text-danger" id="error-neft_1"></span>
                            </div>
                            <div class="col-sm-3">
                                {{ Form::label('rtgs', 'RTGS', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                                {{ Form::text('rtgs[]', null, [ 'id' => 'rtgs_1' , 'data-id' => 'error-rtgs_1' , 'class' => 'form-control']) }}
                                <span class="text-danger" id="error-rtgs_1"></span>
                            </div>
                            <div class="col-sm-3">
                                {{ Form::label('upi', 'UPI', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                                {{ Form::text('upi[]', null, [ 'id' => 'upi_1' , 'data-id' => 'error-upi_1' , 'class' => 'form-control']) }}
                                <span class="text-danger" id="error-upi_1"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4 -->
                    <div class="settingssection">
                        <div class="form-group">
                            {{ Form::label('flat_fee_limit', 'Set Flat fee Limit', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                            {{ Form::text('flat_fee_limit[]', '25,001 ₹ to 10,00,000 ₹' , ['class' => 'form-control' , 'id' => 'flat_fee_limit', 'disabled' => 'disabled']) }}
                            {{ Form::hidden('flat_fee_limit[]', 3 , ['class' => 'form-control']) }}
                            <span class="text-danger" id="error-flat_fee_limit"></span>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                {{ Form::label('imps', 'IMPS', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                                {{ Form::text('imps[]', null, [ 'id' => 'imps_2' , 'data-id' => 'error-imps_2' , 'class' => 'form-control']) }}
                                <span class="text-danger" id="error-imps_2"></span>
                            </div>
                            <div class="col-sm-3">
                                {{ Form::label('neft', 'NEFT', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                                {{ Form::text('neft[]', null, [ 'id' => 'neft_2' , 'data-id' => 'error-neft_2' , 'class' => 'form-control']) }}
                                <span class="text-danger" id="error-neft_2"></span>
                            </div>
                            <div class="col-sm-3">
                                {{ Form::label('rtgs', 'RTGS', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                                {{ Form::text('rtgs[]', null, [ 'id' => 'rtgs_2' , 'data-id' => 'error-rtgs_2' , 'class' => 'form-control']) }}
                                <span class="text-danger" id="error-rtgs_2"></span>
                            </div>
                            <div class="col-sm-3">
                                {{ Form::label('upi', 'UPI', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                                {{ Form::text('upi[]', null, [ 'id' => 'upi_2' , 'data-id' => 'error-upi_2' , 'class' => 'form-control']) }}
                                <span class="text-danger" id="error-upi_2"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    {!! Form::button('Close', ['class' => 'btn btn-secondary', "data-dismiss" => 'modal']) !!}
                    {!! Form::button('Save', ['class' => 'btn btn-primary', 'id' => 'submitBtn']) !!}
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Webhook -->

<div class="modal fade bd-example-modal-lg" id="update-slab-settings-model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg .modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Slab Setting Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="updatepriceSettingForm" method="POST" class="form-horizontal">
                {{ csrf_field() }}

                <div class="modal-body">
                    <!-- Section 1 -->
                    <div class="form-group row">
                        <div class="col-sm-6">
                            {{ Form::label('merchant_id', 'Merchant Name', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                            {{ Form::text('merchant_name', null, ['class' => 'form-control', 'id' => 'merchant_name', 'disabled' => 'disabled']) }}
                            {{ Form::hidden('merchant_id', null, ['class' => 'form-control', 'id' => 'update_merchant_id']) }}
                            {{ Form::hidden('commission_settings_id', null, ['class' => 'form-control', 'id' => 'commission_settings_id']) }}
                            <span class="text-danger" id="error-merchant_id"></span>
                        </div>
                        <div class="col-sm-6">
                            {{ Form::label('percentage', 'Percentage Fee (%)', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                            {{ Form::text('percentage', null, ['data-id' => 'error-updatepercentage','class' => 'form-control', 'id' => 'updatepercentage']) }}
                            <span class="text-danger" id="error-updatepercentage"></span>
                        </div>
                    </div>

                    <!-- Section 2 -->
                    <div class="settingssection">
                        <div class="form-group">
                            {{ Form::label('flat_fee_limit', 'Set Flat fee Limit', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                            {{ Form::text('flat_fee_limit[]', null , ['class' => 'form-control' , 'id' => 'flat_fee_limit', 'disabled' => 'disabled']) }}
                            {{ Form::hidden('flat_fee_limit', null , ['id' => 'hidden_flat_fee_limit','class' => 'form-control']) }}
                            <span class="text-danger" id="error-flat_fee_limit"></span>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                {{ Form::label('imps', 'IMPS', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                                {{ Form::text('imps[]', null, [ 'id' => 'imps_0' , 'data-id' => 'error-imps_0' , 'class' => 'form-control']) }}
                                <span class="text-danger" id="error-imps_0"></span>
                            </div>
                            <div class="col-sm-3">
                                {{ Form::label('neft', 'NEFT', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                                {{ Form::text('neft[]', null, [ 'id' => 'neft_0' , 'data-id' => 'error-neft_0' , 'class' => 'form-control']) }}
                                <span class="text-danger" id="error-neft_0"></span>
                            </div>
                            <div class="col-sm-3">
                                {{ Form::label('rtgs', 'RTGS', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                                {{ Form::text('rtgs[]', null, [ 'id' => 'rtgs_0' , 'data-id' => 'error-rtgs_0' , 'class' => 'form-control']) }}
                                <span class="text-danger" id="error-rtgs_0"></span>
                            </div>
                            <div class="col-sm-3">
                                {{ Form::label('upi', 'UPI', ['class' => 'col-form-label']) }}<span class="text-danger">*</span>
                                {{ Form::text('upi[]', null, [ 'id' => 'upi_0' , 'data-id' => 'error-upi_0' , 'class' => 'form-control']) }}
                                <span class="text-danger" id="error-upi_0"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    {!! Form::button('Close', ['class' => 'btn btn-secondary', "data-dismiss" => 'modal']) !!}
                    {!! Form::button('Save', ['class' => 'btn btn-primary', 'id' => 'updateSubmitBtn']) !!}
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Add Webhook -->

<script>
    $(document).ready(function() {

        function roundInputValue(input) {
            let value = parseFloat(input.val());
            if (!isNaN(value)) {
                input.val(Math.round(value * 100) / 100);
            }
        }

        function displayErrors(errors) {
            for (let key in errors) {
                if (errors.hasOwnProperty(key)) {
                    let escapedKey = key.replace(/[\[\]]/g, '\\$&');
                    $('#error-' + escapedKey).text(errors[key][0]);
                }
            }
        }

        function validateFieldSlab(id, actual) {
            let value = actual.val();
            var valActual = value.replace(/[^0-9.]/g, '');
            actual.val(valActual);
            if (valActual != '') {
                roundInputValue(actual);
                $(id + ' #' + actual.data('id')).text('');
                return true;
            } else {
                $(id + ' #' + actual.data('id')).text('This field is Required.');
                return false;
            }
        }

        $('#updatepriceSettingForm input[name="imps[]"], #updatepriceSettingForm input[name="neft[]"], #updatepriceSettingForm input[name="rtgs[]"], #updatepriceSettingForm input[name="upi[]"]').on('input', function() {
            validateFieldSlab('form#updatepriceSettingForm', $(this));
        });

        $('input[name="imps[]"], input[name="neft[]"], input[name="rtgs[]"], input[name="upi[]"]').on('input', function() {
            validateFieldSlab('form#priceSettingForm', $(this));
        });

        $('#percentage').on('input', function() {
            isValidPercentage('#priceSettingForm', $(this));
        });

        function convertNumberToArray(number) {
            var numberString = number.toString();
            var digitsArray = numberString.split('').map(Number);
            return digitsArray;
        }

        function isValidPercentage(id, field) {
            let value = field.val().trim();
            let regex = /^(\d{1,2}(\.\d{1,2})?|100(\.00?)?)$/;
            if (!regex.test(value)) {
                $(id + ' #' + field.data('id')).text('Please enter a valid percentage (0-100) with up to 2 decimal places.');
                return false;
            } else {
                $(id + ' #' + field.data('id')).text('');
                return true;
            }
        }

        $('#updatepercentage').on('input', function() {
            isValidPercentage('#updatepriceSettingForm', $(this));
        });

        $('#add-slab-settings-btn').on('click', function() {
            var dataId = $(this).data('id');
            var dataName = $(this).data('name');
            var percentatge_fee = $(this).data('actualpercentage');
            $("#merchant_id").val(dataId);
            $("#flat_numericInput").val(percentatge_fee);
            $("#merchant_name").val(dataName);
            $("#addwebapplicationsbutton").attr('action-pressed', 'false');
            $("#addwebapplicationsbutton").removeAttr('data-id');
            $("#app_name").val('');
            $("#app_url").val('');
            $("#order_prefix").val('');
            $("#webhook_url").val('');
            $("#ip_whitelist").val('');
            $('#webapp_status_toggle').attr('aria-pressed', false);
            $('#webapp_status_input').val(0);
            $('#webapp_status_toggle').removeClass('active');
            $('#addwebapps input').removeClass('is-invalid');
            $('#addwebapps input').siblings('.invalid-feedback').remove();
        });

        $('#slabsettingsList').on('click', '.update-fee-settings', function() {
            $('#updatepriceSettingForm')[0].reset();
            var collection = $(this).data('collection');
            $('form#updatepriceSettingForm #flat_fee_limit').val(collection.get_range.option_name);
            $('form#updatepriceSettingForm #commission_settings_id').val(collection.id);
            $('form#updatepriceSettingForm #hidden_flat_fee_limit').val(collection.get_range.id);
            $('form#updatepriceSettingForm #merchant_name').val(collection.merchant.business_detail.company_name);
            $('form#updatepriceSettingForm #update_merchant_id').val(collection.merchant.id);
            $('form#updatepriceSettingForm #updatepercentage').val(collection.merchant.business_detail.payout_percentage_fees);
            $('form#updatepriceSettingForm #imps_0').val(collection.IMPS);
            $('form#updatepriceSettingForm #neft_0').val(collection.NEFT);
            $('form#updatepriceSettingForm #rtgs_0').val(collection.RTGS);
            $('form#updatepriceSettingForm #upi_0').val(collection.UPI);
            console.log(collection);
            $('#update-slab-settings-model').modal('show');
        });

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



        $('#updateSubmitBtn').click(function(event) {
            var isValid = true;

            isValid &= validateField($('#update_merchant_id'));
            isValid &= isValidPercentage('#updatepriceSettingForm', $('#updatepercentage'));
            isValid &= validateFieldSlab('form#updatepriceSettingForm', $('form#updatepriceSettingForm #imps_0'));
            isValid &= validateFieldSlab('form#updatepriceSettingForm', $('form#updatepriceSettingForm #neft_0'));
            isValid &= validateFieldSlab('form#updatepriceSettingForm', $('form#updatepriceSettingForm #rtgs_0'));
            isValid &= validateFieldSlab('form#updatepriceSettingForm', $('form#updatepriceSettingForm #upi_0'));

            if (!isValid) {
                event.preventDefault();
                toastr.error('Kindly fill all the required fields!');
                return false;
            }
            
            $.ajax({
                url: `{{ route('slab.update.commission') }}`,
                method: 'POST',
                data: $('#updatepriceSettingForm').serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        $('#updatepriceSettingForm')[0].reset();
                        toastr.success('Slab Updated Successfully!!');
                        $('#update-slab-settings-model').modal('hide');
                        roleListDatatable.ajax.reload();
                    }
                },
                error: function(response) {
                    if (response.status === 500) {
                        var errors = response.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            if (key.includes('.')) {
                                var parts = key.split('.');
                                var fieldName = parts[0];
                                var index = parts[1];
                                $('form#updatepriceSettingForm #error-' + fieldName + '_' + index).text('This field is Required.');
                            } else {
                                $('form#updatepriceSettingForm #error-' + key).text('This field is Required.');
                            }
                        });
                    }
                }
            });
        });

        $('#submitBtn').click(function(event) {

            var isValid = true;

            isValid &= validateField($('#merchant_id'));
            isValid &= isValidPercentage('#priceSettingForm', $('#percentage'));
            isValid &= validateFieldSlab('form#priceSettingForm', $('#imps_0'));
            isValid &= validateFieldSlab('form#priceSettingForm', $('#neft_0'));
            isValid &= validateFieldSlab('form#priceSettingForm', $('#rtgs_0'));
            isValid &= validateFieldSlab('form#priceSettingForm', $('#upi_0'));
            isValid &= validateFieldSlab('form#priceSettingForm', $('#imps_1'));
            isValid &= validateFieldSlab('form#priceSettingForm', $('#neft_1'));
            isValid &= validateFieldSlab('form#priceSettingForm', $('#rtgs_1'));
            isValid &= validateFieldSlab('form#priceSettingForm', $('#upi_1'));
            isValid &= validateFieldSlab('form#priceSettingForm', $('#imps_2'));
            isValid &= validateFieldSlab('form#priceSettingForm', $('#neft_2'));
            isValid &= validateFieldSlab('form#priceSettingForm', $('#rtgs_2'));
            isValid &= validateFieldSlab('form#priceSettingForm', $('#upi_2'));


            if (!isValid) {
                event.preventDefault();
                toastr.error('Kindly fill all the required fields!');
                return false;
            }

            $.ajax({
                url: `{{ route('slab.store.commission') }}`,
                method: 'POST',
                data: $('#priceSettingForm').serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        $('#priceSettingForm')[0].reset();
                        toastr.success('Slabs Created Successfully!!');
                        $('#add-slab-settings-model').modal('hide');
                        roleListDatatable.ajax.reload();
                    }
                },
                error: function(response) {
                    if (response.status === 500) {
                        var errors = response.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            if (key.includes('.')) {
                                var parts = key.split('.');
                                var fieldName = parts[0];
                                var index = parts[1];
                                $('#error-' + fieldName + '_' + index).text('This field is Required.');
                            } else {
                                $('#error-' + key).text('This field is Required.');
                            }
                        });
                    }
                }
            });
        });

        var roleListDatatable = jQuery('#slabsettingsList').DataTable({

            sDom: '<"top"f>rt<"bottom"lp><"clear">',
            pageLength: 10,
            language: {
                search: '',
                searchPlaceholder: 'Search',
                sLengthMenu: "Page _MENU_",
                paginate: {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Prev"
                },
            },
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('slab.listofslabs.commission') }}",
                type: 'GET',
                data: function(d) {
                    d.merchant_id = $('#actual_merchant_id').val();
                }
            },
            columns: [{
                    data: 'id',
                    title: 'S.No',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'minRange',
                    title: 'Min Range',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'maxRange',
                    title: 'Max Range',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'IMPS',
                    title: 'IMPS'
                },
                {
                    data: 'NEFT',
                    title: 'NEFT',
                },
                {
                    data: 'RTGS',
                    title: 'RTGS'
                },
                {
                    data: 'UPI',
                    title: 'UPI',
                },
                {
                    data: 'status',
                    title: 'Is Active',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        if (data == 1) {
                            $('.merchant-sec #percentage_fees').val(row.merchant.business_detail.payout_percentage_fees);
                        }
                        var status = data == 0 ? 'No' : 'Yes';
                        var color = data == 0 ? 'badge-danger' : 'badge-success';
                        return '<span class="badge ' + color + '">' + status + '</span>';
                    }

                },
                {
                    data: 'action',
                    title: 'Action',
                }
            ]
        });



    });
</script>

@endsection