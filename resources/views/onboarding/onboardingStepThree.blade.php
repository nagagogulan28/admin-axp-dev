@extends('layouts.employeecontent')
@section('employeecontent')

<style>
    #payout_percentage_fees:disabled {
        background-color: #e9ecef !important;
    }

    #percentage_fees:disabled {
        background-color: #e9ecef !important;
    }
</style>

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
$currentLevel = 3;
@endphp
<p class="title-common">Add Merchant</p>
<div>
    <div class="common-box px-0">
        {!! Form::open(['url' => '/onboarding/webhook', 'method' => 'post', 'autocomplete' => 'off', 'id' => 'stepthreeform']) !!}
        {{ Form::hidden('user_id', $user->id) }}
        <div class="container-indicator">
            <section class="step-indicator">
                <div class="step step1 active in-complete">
                    @if(isset($user))
                    <a href="/onboarding/eqyroksfig/{{$user->id}}">
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
                    <a href="/onboarding/aftxjcqenf/{{$user->id}}">
                        <div class="step-icon">02</div>
                    </a>
                    @else
                    <div class="step-icon">02</div>
                    @endif
                    <p>Company Info</p>
                </div>

                @php
                $get_level_three_1 = $currentLevel == 3 || $user->process_level >= 3 ? 'active' : '';
                $get_level_three_2 = $currentLevel == 3 || $user->process_level >= 3 ? 'in-complete' : '';
                @endphp
                <div class="indicator-line  {{$get_level_three_1}}"></div>
                <div class="step step3 {{$get_level_three_1}} {{$get_level_three_2}}">
                    @if(isset($user) && ($currentLevel == 3 || $user->process_level >= 3))
                    <a href="/onboarding/cokxgpauql/{{$user->id}}">
                        <div class="step-icon">03</div>
                    </a>
                    @else
                    <div class="step-icon">03</div>
                    @endif
                    <p>Pay-in settings</p>
                </div>
                @php
                $get_level_four_1 = $currentLevel == 4 || $user->process_level == 4 ? 'active' : '';
                $get_level_four_2 = $currentLevel == 4 || $user->process_level == 4 ? 'in-complete' : '';
                @endphp
                <div class="indicator-line {{$get_level_four_1}}"></div>
                <div class="step step4 {{$get_level_four_1}} {{$get_level_four_2}}">
                    @if(isset($user) && ($currentLevel == 4 || $user->process_level >= 3))
                    <a href="/onboarding/xyhsrpzfip/{{$user->id}}">
                        <div class="step-icon">04</div>
                    </a>
                    @else
                    <div class="step-icon">04</div>
                    @endif
                    <p>Business Info</p>
                </div>
            </section>
        </div>
        <div class="merchant-sec" style="margin-top: 6rem !important;">
            <div class="three-step" style="background: #F9FBFC;">
                <div class="row justify-content-between">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                        <div class="mb-3">
                            {{Form::label('name', 'Name', array('class' => 'control-label'))}}
                            <div>{{$user->first_name}} {{$user->last_name}}</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                        <div class="mb-3">
                            {{Form::label('name', 'User ID', array('class' => 'control-label'))}}
                            <div>{{$user->user_label_id}}</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                        <div class="mb-3">
                            {{Form::label('name', 'Email', array('class' => 'control-label'))}}
                            <div>{{ $user->personal_email }}</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                        <div class="mb-3">
                            {{Form::label('name', 'Contact No', array('class' => 'control-label'))}}
                            <div>{{ $user->mobile_no }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="three-step">
                <div class="row pt-2">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                        <div class="mb-3">
                            {{Form::label('pay_in', 'Pay-In', array('class' => 'control-label'))}}
                            @if($user->businessDetail->pay_in)
                            @php
                            $payIn = 'active';
                            $payIncheck = 'true';
                            @endphp
                            @else
                            @php
                            $payIn = '';
                            $payIncheck = 'false';
                            @endphp
                            @endif
                            <div style="height: 45px;display: flex;align-items: center;">
                                <button type="button" class="btn btn-toggle {{$payIn}}" data-toggle="button" aria-pressed="{{$payIncheck}}" autocomplete="off" id="pay_in_button">
                                    <div class="handle"></div>
                                </button>
                                {{ Form::hidden('pay_in',$user->businessDetail->pay_in, ['id' => 'pay_in_input']) }} <!-- Hidden field to capture Pay-In value -->
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                        <div class="mb-3">
                            {{Form::label('percentage_fees', 'Payin Percentage Fees', array('class' => 'control-label'))}} <span style="color: red;font-size: 1.5rem;">*</span>
                            {{ Form::text('percentage_fees', $user->businessDetail->percentage_fees > 0 ? $user->businessDetail->percentage_fees : null ,
                                [
                                    'class' => 'form-control',
                                    'id' => 'percentage_fees',
                                    'placeholder' => 'Percentage Fees',
                                    'disabled' => $payIncheck == 'false' ? 'disabled' : null
                                ]) }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                        <div class="mb-3">
                            {{ Form::label('payin_receive_bank', 'Payin Receiving Bank', ['class' => 'control-label']) }} <span style="color: red;font-size: 1.5rem;">*</span>
                            {{ Form::select(
                                'payin_receive_bank',
                                $payinList->bankDetails->pluck('bankName.name', 'id')->prepend('Select a Bank', '0'),
                                isset($user->businessDetail) && $user->businessDetail->payin_receive_bank != '' ? $user->businessDetail->payin_receive_bank : null,
                                [
                                    'class' => 'form-control',
                                    'id' => 'serviceAccountSelect',
                                    'disabled' => $payIncheck == 'false' ? 'disabled' : null
                                ]
                            ) }}
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                        <div class="mb-3">
                            {{Form::label('pay_out', 'Pay-Out', array('class' => 'control-label'))}}
                            <div style="height: 45px;display: flex;align-items: center;">
                                @if($user->businessDetail->pay_out)
                                @php
                                $payOut = 'active';
                                $payOutcheck = 'true';
                                @endphp
                                @else
                                @php
                                $payOut = '';
                                $payOutcheck = 'false';
                                @endphp
                                @endif
                                <button type="button" class="btn btn-toggle {{$payOut}}" data-toggle="button" aria-pressed="{{$payOutcheck}}" autocomplete="off" id="pay_out_button">
                                    <div class="handle"></div>
                                </button>
                                {{ Form::hidden('pay_out', $user->businessDetail->pay_out , ['id' => 'pay_out_input']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                        <div class="mb-3">
                            {{Form::label('payout_percentage_fees', 'Payout Percentage Fees', array('class' => 'control-label'))}} <span style="color: red;font-size: 1.5rem;">*</span>
                            {{ Form::text('payout_percentage_fees', $user->businessDetail->payout_percentage_fees > 0 ? $user->businessDetail->payout_percentage_fees : null , 
                                [
                                    'class' => 'form-control',
                                    'id' => 'payout_percentage_fees',
                                    'placeholder' => 'Percentage Fees',
                                    'disabled' => $payOutcheck == 'false' ? 'disabled' : null
                                ]) }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                        <div class="mb-3">
                            {{ Form::label('payout_receive_bank', 'Payout Disbursement Bank', ['class' => 'control-label']) }} <span style="color: red;font-size: 1.5rem;">*</span>
                            {{ Form::select(
                                'payout_receive_bank',
                                $payoutList->bankDetails->pluck('bankName.name', 'id')->prepend('Select a Bank', '0'),
                                isset($user->businessDetail) && $user->businessDetail->payout_receive_bank != '' ? $user->businessDetail->payout_receive_bank : null,
                                [
                                    'class' => 'form-control',
                                    'id' => 'reciveFundAccountSelect',
                                    'disabled' => $payOutcheck == 'false' ? 'disabled' : null
                                ]
                            ) }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                        <div class="mb-3">
                            {{Form::label('refferedBy', 'Referred By', array('class' => 'control-label'))}}
                            {{ Form::select('refferedBy',
                            $vendorList->pluck('first_name','id')->prepend('Super Admin', '0')->prepend('Select a Vendor', ''),
                            isset($user) && $user->refferedBy != 0 ? $user->refferedBy : null,
                            ['class' => 'form-control', 'id' => 'businesstypeselect' ])
                        }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center">
            {!! Form::submit('Draft', ['class' => 'outline-btn']) !!}
            {!! Form::submit('Next', ['class' => 'outline-btn']) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>


<div class="common-box mt-3">
    <div class="search-btn px-2 justify-content-end">
        <div>
            <button class="bg-btn" id="add-web-apps-manual" data-toggle="modal" data-target="#addwebapps" style="display: flex;align-items: center;"><img src="{{ asset('new/img/add.svg')}}" alt="print" style="margin-right:5px">Add Web Apps</button>
        </div>
    </div>
    <div class="table-data">
        <table id="webApps_datatable" class="table" style="width:100%;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Terminal ID</th>
                    <th>App Name</th>
                    <th>App URL</th>
                    <th>Order Prefix</th>
                    <th>Webhook URL</th>
                    <th>Ip whitelist</th>
                    <th>Respective Type</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    </div>
</div>

<!-- Add Webhook -->

<div class="modal fade" id="addwebapps" tabindex="-1" role="dialog" aria-labelledby="addwebhookmodel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addwebhookmodel">Add Web Application</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{ Form::hidden('update_id_webapp', null ,  array('id' => 'update_id_webapp')) }}
            <form id="addwebapplications">
                {{ Form::hidden('user_id_webapp', $user->id ,  array('id' => 'user_id_webapp')) }}
                <div class="modal-body">
                    <div class="form-group">
                        {{Form::label('app_name', 'App Name', array('class' => 'col-form-label'))}}
                        {{ Form::text('app_name', $name = null, array('class' => 'form-control', 'id' => 'app_name' ,'placeholder' => 'App Name')) }}
                    </div>
                    <div class="form-group">
                        {{Form::label('app_url', 'App URL', array('class' => 'col-form-label'))}}
                        {{ Form::text('app_url', $name = null, array('class' => 'form-control', 'id' => 'app_url' ,'placeholder' => 'App URL')) }}
                    </div>
                    <div class="form-group">
                        {{Form::label('order_prefix', 'Order Prefix', array('class' => 'col-form-label'))}}
                        {{ Form::text('order_prefix', $name = null, array('class' => 'form-control', 'id' => 'order_prefix' ,'placeholder' => 'Order Prefix')) }}
                    </div>
                    <div class="form-group">
                        {{Form::label('webhook_url', 'Webhook URL', array('class' => 'col-form-label'))}}
                        {{ Form::text('webhook_url', $name = null, array('class' => 'form-control', 'id' => 'webhook_url' ,'placeholder' => 'Webhook URL')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('paytype', 'Type', array('class' => 'col-form-label')) }}
                        {{ Form::select('paytype', [
                                '' => 'Select type',
                                '1' => 'PayIn',
                                '2' => 'Payout',
                            ], null, ['class' => 'form-control', 'id' => 'paytype']) }}
                    </div>

                    <div class="form-group">
                        {{Form::label('assignedPartnerPayin', 'Assigned Partner', array('class' => 'control-label'))}}
                        {{ Form::select('assignedPartnerPayin',
                            $aggreGator->pluck('name','id')->prepend('Partner', ''),
                            isset($user) && $user->businessDetail->assignedPartnerPayin != '' ? $user->businessDetail->assignedPartnerPayin : null,
                            ['class' => 'form-control', 'id' => 'partnerlsit' ])
                    }}
                    </div>
                    <div class="form-group">

                        {{ Form::label('ip_whitelist', 'IP Whitelist', ['class' => 'col-form-label']) }}
                        {{ Form::text('ip_whitelist', null, ['class' => 'form-control', 'id' => 'ip_whitelist', 'placeholder' => 'Enter IP Whitelist', 'pattern' => '^(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])$', 'title' => 'Enter a valid IPv4 address']) }}


                        <div class="form-group">
                            {{Form::label('webapp_status_toggle', 'Web App Status', array('class' => 'control-label'))}}
                            <div style="height: 45px;display: flex;align-items: center;">
                                <button type="button" class="btn btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off" id="webapp_status_toggle">
                                    <div class="handle"></div>
                                </button>
                                {{ Form::hidden('status', '0' , ['id' => 'webapp_status_input']) }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'outline-btn out-btn', "id" => 'closepopupwebapplicationsbutton']) !!}
                        {!! Form::button('Add Application', ['class' => 'outline-btn bgg-btn', "id" => 'addwebapplicationsbutton']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
        </div>
    </div>


    <!-- Add Webhook -->

    <script>
        $(document).ready(function() {

            function validateFieldBefore(fieldTest) {
                if (fieldTest.val().trim() === '' || fieldTest.val().trim() === '0') {
                    fieldTest.addClass('is-invalid');
                    fieldTest.siblings('.invalid-feedback').remove();
                    fieldTest.after('<div class="invalid-feedback">This field is required.</div>');
                    return false;
                } else {
                    fieldTest.removeClass('is-invalid');
                    fieldTest.siblings('.invalid-feedback').remove();
                    return true;
                }
            }

            function isValidPercentage(field) {
                let value = field.val().trim();
                let regex = /^(\d{1,2}(\.\d{1,2})?|100(\.00?)?)$/;
                if (!regex.test(value)) {
                    field.addClass('is-invalid');
                    field.siblings('.invalid-feedback').remove();
                    field.after('<div class="invalid-feedback">Please enter a valid percentage (0-100) with up to 2 decimal places.</div>');
                    return false;
                } else {
                    field.removeClass('is-invalid');
                    field.siblings('.invalid-feedback').remove();
                    return true;
                }
            }

            function toggleButton(button, hiddenInput) {
                if (button.attr('aria-pressed') === 'false') {
                    hiddenInput.val('1');
                } else {
                    hiddenInput.val('0');
                }
            }

            // Event listeners for validation
            $('#percentage_fees').on('input', function() {
                console.log("11111");

                validateFieldBefore($(this));
                isValidPercentage($(this));
            });

            $('#payout_percentage_fees').on('input', function() {
                validateFieldBefore($(this));
                isValidPercentage($(this));
            });

            $('#serviceAccountSelect').change(function() {
                validateFieldBefore($(this));
            });

            $('#reciveFundAccountSelect').change(function() {
                validateFieldBefore($(this));
            });

            $('#webapp_status_toggle').click(function() {
                let hiddenInput = $('#webapp_status_input');
                toggleButton($(this), hiddenInput);
            });

            $('#pay_out_button').click(function() {
                let hiddenInput = $('#pay_out_input');
                toggleButton($(this), hiddenInput);
                if ($(this).attr('aria-pressed') === 'true') {
                    $('#reciveFundAccountSelect').siblings('.invalid-feedback').remove();
                    $('#payout_percentage_fees').siblings('.invalid-feedback').remove();
                    $('#payout_percentage_fees').removeClass('is-invalid');
                    $('#reciveFundAccountSelect').removeClass('is-invalid');
                    $('#payout_percentage_fees').prop('disabled', true);
                    $('#reciveFundAccountSelect').prop('disabled', true);
                } else {
                    $('#payout_percentage_fees').prop('disabled', false);
                    $('#reciveFundAccountSelect').prop('disabled', false);
                }
            });

            $('#pay_in_button').click(function() {
                let hiddenInput = $('#pay_in_input');
                toggleButton($(this), hiddenInput);
                if ($(this).attr('aria-pressed') === 'true') {
                    $('#serviceAccountSelect').siblings('.invalid-feedback').remove();
                    $('#percentage_fees').siblings('.invalid-feedback').remove();
                    $('#serviceAccountSelect').removeClass('is-invalid');
                    $('#percentage_fees').removeClass('is-invalid');
                    $('#percentage_fees').prop('disabled', true);
                    $('#serviceAccountSelect').prop('disabled', true);
                } else {
                    $('#percentage_fees').prop('disabled', false);
                    $('#serviceAccountSelect').prop('disabled', false);
                }
            });

            $('#partnerlsit').change(function() {
                validateFieldBefore($(this));
            });
            // Before submit validation
            $('#stepthreeform').submit(function(event) {
                var isValid = true;

                if ($('#pay_out_button').attr('aria-pressed') === 'true') {
                    var f2 = validateFieldBefore($('#payout_percentage_fees'));
                    var f4 = validateFieldBefore($('#reciveFundAccountSelect'));
                    if (!f2 || !f4) {
                        isValid = false
                    }
                    if (f2) {
                        f2 = isValidPercentage($('#payout_percentage_fees'));
                    }
                    if (!f2) {
                        isValid = false;
                    }
                }

                if ($('#pay_in_button').attr('aria-pressed') === 'true') {
                    var f1 = validateFieldBefore($('#percentage_fees'));
                    var f3 = validateFieldBefore($('#serviceAccountSelect'));
                    if (!f1 || !f3) {
                        isValid = false
                    }
                    if (f1) {
                        f1 = isValidPercentage($('#percentage_fees'));
                    }
                    if (!f1) {
                        isValid = false;
                    }
                }
                if (!isValid) {
                    event.preventDefault();
                }
            });

            function validateURL(url) {

                var urlPattern = /^(ftp|http|https):\/\/[^ "]+$/;

                return urlPattern.test(url);
            }


            function validateIPv4(ip) {

                var ipPattern = /^(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])$/;

                return ipPattern.test(ip);
            }

            function ip_address(base, ipaddress) {
                if (validateIPv4(ipaddress)) {
                    base.removeClass('is-invalid');
                    base.siblings('.invalid-feedback').remove();
                    return true;
                } else {
                    base.addClass('is-invalid');
                    base.siblings('.invalid-feedback').remove();
                    base.after('<div class="invalid-feedback">Please enter a valid IP Address.</div>');
                    return false;
                }
            }

            $('#ip_whitelist').on('input', function() {
                var ipaddres = $(this).val();
                ip_address($(this), ipaddres)
            });



            function addbusinessapps(field) {
                $.ajax({
                    url: '{{ route("business.apps.store") }}',
                    type: 'POST',
                    data: field,
                    success: function(response) {
                        if (response.status == 'success') {
                            $("div#divLoading").removeClass('show');
                            $("div#divLoading").addClass('hide');
                            $('#addwebapps').modal('hide');
                            roleListDatatable.ajax.reload();
                            toastr.success(response.message);
                        } else {
                            toastr.success(response.message);
                        }
                    }
                });
            }




            function urlValidate(base, urlInput) {
                if (validateURL(urlInput)) {
                    base.removeClass('is-invalid');
                    base.siblings('.invalid-feedback').remove();
                    return true;
                } else {
                    base.addClass('is-invalid');
                    base.siblings('.invalid-feedback').remove();
                    base.after('<div class="invalid-feedback">Please enter a valid URL.</div>');
                    return false;
                }
            }

            $('#app_url , #webhook_url').on('input', function() {
                var urlInput = $(this).val();
                urlValidate($(this), urlInput)
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

            // Select Fields
            $('#order_prefix').on('input', function() {
                console.log('order_prefix');
                var value = $(this).val().toUpperCase().replace(/[^A-Z]/g, '');
                if (value.length > 4) {
                    value = value.substring(0, 4);
                }
                $(this).val(value);
                validateField($(this));
            });

            $('#app_name').on('input', function() {
                console.log('app_name');
                validateField($(this));
            });

            $('#paytype').change(function() {
                validateField($(this));
            });

            $('#partnerlsit').change(function() {
                validateField($(this));
            });

            $('#add-web-apps-manual').on('click', function() {
                $("#addwebapplicationsbutton").attr('action-pressed', 'false');
                $("#addwebapplicationsbutton").removeAttr('data-id');
                $("#app_name").val('');
                $("#app_url").val('');
                $("#order_prefix").val('');
                $("#webhook_url").val('');
                $("#ip_whitelist").val('');
                $("#paytype").val('');
                $("#partnerlsit").val('');
                $('#webapp_status_toggle').attr('aria-pressed', false);
                $('#webapp_status_input').val(0);
                $('#webapp_status_toggle').removeClass('active');
                $('#addwebapps input').removeClass('is-invalid');
                $('#addwebapps input').siblings('.invalid-feedback').remove();
            });

            $('#closepopupwebapplicationsbutton').on('click', function() {
                $('#addwebapps').modal('hide');
            });

            $('#webApps_datatable').on('click', '.update-webapp', function() {

                var id = $(this).data('id');
                $("#addwebapplicationsbutton").attr('action-pressed', 'true');
                $("#order_prefix").attr('readonly', 'true');

                $.ajax({
                    url: '/business/apps/' + id,
                    type: 'GET',
                    success: function(response) {
                        if (response.status == 'success') {
                            $("#update_id_webapp").val(response.data.id);
                            $('#addwebapps input').removeClass('is-invalid');
                            $('#addwebapps input').siblings('.invalid-feedback').remove();
                            $('#addwebapps').modal('show');
                            $('#app_name').val(response.data.app_name);
                            $('#app_url').val(response.data.app_url);
                            $('#order_prefix').val(response.data.order_prefix);
                            $('#webhook_url').val(response.data.webhook_url);
                            $('#ip_whitelist').val(response.data.ip_whitelist);
                            $('#paytype').val(response.data.type);
                            $('#partnerlsit').val(response.data.aggregators_id);
                            var newStatus = (response.data.is_active == 0) ? 'false' : 'true';
                            $('#webapp_status_toggle').attr('aria-pressed', newStatus);
                            $('#webapp_status_input').val(response.data.is_active);
                            if (response.data.is_active) {
                                $('#webapp_status_toggle').addClass('active');
                            } else {
                                $('#webapp_status_toggle').removeClass('active');
                            }
                        } else {
                            toastr.success(response.message);
                        }
                    }
                });
            })


            $('#addwebapplicationsbutton').on('click', function(e) {
                let isValid = true;

                const url = $('#app_url').val();
                if (!urlValidate($('#app_url'), url)) {
                    isValid = false;
                }

                const webhookurl = $('#webhook_url').val();
                if (!urlValidate($('#webhook_url'), webhookurl)) {
                    isValid = false;
                }
                const ipaddress = $('#ip_whitelist').val();
                if (!ip_address($('#ip_whitelist'), ipaddress)) {
                    isValid = false;
                }

                var f1 = validateField($('#order_prefix'));
                var f2 = validateField($('#app_name'));
                var f3 = validateField($('#paytype'));
                var f4 = validateField($('#partnerlsit'));

                if (!f1 || !f2 || !f3 || !f4) {
                    isValid = false
                }
                console.log('isValid', isValid);
                if (!isValid) {
                    e.preventDefault();
                } else {
                    var reqData = {
                        _token: '{{ csrf_token() }}',
                        order_prefix: $('#order_prefix').val(),
                        webhook_url: $('#webhook_url').val(),
                        ip_whitelist: $('#ip_whitelist').val(),
                        user_id: $('#user_id_webapp').val(),
                        app_name: $('#app_name').val(),
                        app_url: $('#app_url').val(),
                        status: $('#webapp_status_input').val(),
                        type: $('#paytype').val(),
                        assignedPartnerPayin: $('#partnerlsit').val()
                    };
                    if ($(this).attr('action-pressed') === 'true') {
                        reqData.webappsid = $("#update_id_webapp").val();
                        $("#update_id_webapp").val('0');
                    }
                    $("div#divLoading").removeClass('hide');
                    $("div#divLoading").addClass('show');

                    addbusinessapps(reqData);
                }
            });
            var roleListDatatable = jQuery('#webApps_datatable').DataTable({

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
                    url: "{{ route('web.apps.list') }}",
                    type: 'GET',
                    data: function(d) {
                        d.user_id = $('#user_id_webapp').val();
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
                        data: 'terminal_id',
                        name: 'terminal_id'
                    },
                    {
                        data: 'app_name',
                        name: 'app_name'
                    },
                    {
                        data: 'app_url',
                        name: 'app_url'
                    },
                    {
                        data: 'order_prefix',
                        name: 'order_prefix'
                    },
                    {
                        data: 'webhook_url',
                        name: 'webhook_url'
                    },
                    {
                        data: 'ip_whitelist',
                        name: 'ip_whitelist'
                    },
                    {
                        data: 'type',
                        title: 'Respective Type',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            var status = data == 1 ? 'Pay-In' : 'Pay-Out';
                            return '<span class="badge badge-success">' + status + '</span>';
                        }
                    },
                    {
                        data: 'is_active',
                        title: 'Is Active',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            console.log('row', row);
                            var status = data == 1 ? 'Yes' : 'No';
                            return '<span class="badge badge-info">' + status + '</span>';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });



        });
    </script>

    @endsection