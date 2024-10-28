@extends('layouts.employeecontent')
@section('employeecontent')
<script>
    function togglePasswordVisibility() {
        var passwordField = document.getElementById('password');
        var icon = document.getElementById('password-toggle-icon');
        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
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
$currentLevel = 1;
@endphp
<p class="title-common">Add Vendor</p>
<div class="common-box onboarding-height">
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
            $get_level_two_1 = isset($user) && $currentLevel == 2 || isset($user) && $user->process_level >= 2 ? 'active' : '';
            $get_level_two_2 = isset($user) && $currentLevel == 2 || isset($user) && $user->process_level >= 2 ? 'in-complete' : '';
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
                <p>Bank Info</p>
            </div>
        </section>
    </div>
    <div style="margin-top: 6rem !important;">
        {!! Form::open(['url' => '/onboarding/vendor/initial', 'method' => 'post' , 'autocomplete' => 'off']) !!}

        @if(isset($user) && $user)
        {{ Form::hidden('user_id', $user->id, ['id' => 'user_id']) }}
        @endif

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <div class="mb-5">
                    {{ Form::label('firstname', 'First Name', ['class' => 'control-label']) }}
                    {{ Form::text('firstname', isset($user) ? $user->first_name : old('firstname'), ['id' => 'firstname-input', 'class' => 'form-control', 'placeholder' => 'First Name']) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <div class="mb-5">
                    {{ Form::label('lastname', 'Last Name', ['class' => 'control-label']) }}
                    {{ Form::text('lastname', isset($user) ? $user->last_name : old('lastname'), ['id' => 'lastname-input', 'class' => 'form-control', 'placeholder' => 'Last Name']) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <div class="mb-5">
                    {{ Form::label('userName', 'User Name', ['class' => 'control-label']) }}
                    {{ Form::text('userName', isset($user) ? $user->user_name : old('userName'), ['class' => 'form-control', 'placeholder' => 'User Name', 'autocomplete' => 'off']) }}
                </div>
            </div>
        
            @if(!isset($user))
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <div class="mb-5">
                    <label for="password" class="control-label">Password</label>
                    <div class="input-group eye-section">
                        {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password', 'id' => 'password']) }}
                        <div class="input-group-append">
                            <span class="input-group-text" onclick="togglePasswordVisibility()">
                                <i id="password-toggle-icon" class="fa fa-eye fa-lg" style="color: #1d4aa3;"></i>
                            </span>
                        </div>
                    </div>
                </div>
          
            
   
            
            </div>
            @endif
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <div class="mb-5">
                    {{ Form::label('email', 'Email', ['class' => 'control-label']) }}
                    {{ Form::email('email', isset($user) ? $user->personal_email : old('email'), ['class' => 'form-control', 'placeholder' => 'Email']) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-4">
                <div class="mb-5">
                    {{ Form::label('mobile', 'Mobile', ['class' => 'control-label']) }}
                    {{ Form::text('mobile',isset($user) ? $user->mobile_no : old('mobile'), ['class' => 'form-control', 'placeholder' => 'Mobile' , 'maxlength' => '10']) }}
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

<!-- step 1 end -->
<script>
    $(document).ready(function() {
        <?php if (!isset($user)) : ?>
            setTimeout(function() {
                $('input[name="userName"]').val(''); // Clear username field 
                $('input[name="password"]').val(''); // Clear password field
            }, 1);
        <?php endif; ?>
        $('input').attr('autocomplete', 'off');

        function validateField(field, errorMessage) {
            if (!field.val().trim()) {
                console.log("dddddooooo");
                field.addClass('is-invalid');
                field.next('.invalid-feedback').remove();
                field.after('<div class="invalid-feedback">' + errorMessage + '</div>');
                return false;
            } else {
                console.log("eeeeewwww");
                field.removeClass('is-invalid');
                field.next('.invalid-feedback').remove();
                return true;
            }
        }

        function validateEmailAJAX(field) {
            let reqData = {
                _token: '{{ csrf_token() }}',
                email: field.val().trim()
            };
            var userId = $('#user_id').val();
            if (userId) {
                reqData.user_id = userId;
            }
            $.ajax({
                url: '{{ route("validate.email") }}',
                type: 'POST',
                data: reqData,
                success: function(response) {
                    if (response.exists) {
                        field.addClass('is-invalid');
                        field.next('.invalid-feedback').remove();
                        field.after('<div class="invalid-feedback">Email already exists</div>');
                    } else {
                        field.removeClass('is-invalid');
                        field.next('.invalid-feedback').remove();
                    }
                }
            });
        }

        function validateMobileAJAX(field) {
            let reqData = {
                _token: '{{ csrf_token() }}',
                mobile: field.val().trim()
            };
            var userId = $('#user_id').val();
            if (userId) {
                reqData.user_id = userId;
            }
            $.ajax({
                url: '{{ route("validate.mobile") }}',
                type: 'POST',
                data: reqData,
                success: function(response) {
                    if (response.exists) {
                        field.addClass('is-invalid');
                        field.next('.invalid-feedback').remove();
                        field.after('<div class="invalid-feedback">Mobile number already exists</div>');
                    } else {
                        field.removeClass('is-invalid');
                        field.next('.invalid-feedback').remove();
                    }
                }
            });
        }

        function validateUsernameAJAX(field) {
            let reqData = {
                _token: '{{ csrf_token() }}',
                userName: field.val().trim()
            };
            var userId = $('#user_id').val();
            if (userId) {
                reqData.user_id = userId;
            }
            $.ajax({
                url: '{{ route("validate.username") }}',
                type: 'POST',
                data: reqData,
                success: function(response) {
                    if (response.exists) {
                        field.addClass('is-invalid');
                        field.next('.invalid-feedback').remove();
                        field.after('<div class="invalid-feedback">Username already exists</div>');
                    } else {
                        field.removeClass('is-invalid');
                        field.next('.invalid-feedback').remove();
                    }
                }
            });
        }

        function validateEmail(field) {
            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!emailPattern.test(field.val().trim())) {
                field.addClass('is-invalid');
                field.next('.invalid-feedback').remove();
                field.after('<div class="invalid-feedback">Please enter a valid email address</div>');
                return false;
            } else {
                field.removeClass('is-invalid');
                field.next('.invalid-feedback').remove();

                if (field.val().trim().length >= 3) {
                    validateEmailAJAX(field);
                }

                return true;
            }
        }

        function validatePhoneNumber(field) {
            const phonePattern = /^[0-9]{10}$/;
            if (!phonePattern.test(field.val().trim())) {
                field.addClass('is-invalid');
                field.next('.invalid-feedback').remove();
                field.after('<div class="invalid-feedback">Please enter a valid 10-digit phone number</div>');
                return false;
            } else {
                field.removeClass('is-invalid');
                field.next('.invalid-feedback').remove();

                if (field.val().trim().length >= 3) {
                    validateMobileAJAX(field);
                }

                return true;
            }
        }

        function validateUsername(field) {
            if (!field.val().trim()) {
                field.addClass('is-invalid');
                field.next('.invalid-feedback').remove();
                field.after('<div class="invalid-feedback">This field is required</div>');
                return false;
            } else {
                field.removeClass('is-invalid');
                field.next('.invalid-feedback').remove();

                if (field.val().trim().length >= 3) {
                    validateUsernameAJAX(field);
                }

                return true;
            }
        }

        function restrictNonNumericInput(event) {
            const field = $(this);
            const charCode = event.which ? event.which : event.keyCode;

            if ((charCode < 48 || charCode > 57) || (field.val().length >= 10 && charCode !== 8 && charCode !== 46)) {
                event.preventDefault();
            }
        }

        $('input[name="mobile"]').on('keypress', restrictNonNumericInput);

        $('input').on('input change', function() {
            const field = $(this);
            console.log("ddsdd");
            if (field.val().trim()) {
                if (field.val().trim().length >= 3 && field.attr('name') === 'email') {
                    validateEmail(field);
                } else if (field.val().trim().length >= 3 && field.attr('name') === 'mobile') {
                    validatePhoneNumber(field);
                } else if (field.val().trim().length >= 3 && field.attr('name') === 'userName') {
                    validateUsername(field);
                } else {
                    validateField(field, 'This field is required');
                }
            } else {
                validateField(field, 'This field is required');
            }
        });

        $('form').on('submit', function(e) {
            let isValid = true;

            // Check email field
            const emailField = $('input[name="email"]');
            if (emailField.length && !validateEmail(emailField)) {
                isValid = false;
            }

            // Check mobile field
            const mobileField = $('input[name="mobile"]');
            if (mobileField.length && !validatePhoneNumber(mobileField)) {
                isValid = false;
            }

            // Check userName field
            const userNameField = $('input[name="userName"]');
            if (userNameField.length && !validateUsername(userNameField)) {
                isValid = false;
            }

            // Check userName field
            const firstnameField = $('input[name="firstname"]');
            if (firstnameField.length && !validateField(firstnameField, 'This field is required')) {
                isValid = false;
            }

            // Check userName field
            const lastnameField = $('input[name="lastname"]');
            if (lastnameField.length && !validateField(lastnameField, 'This field is required')) {
                isValid = false;
            }

            // Check userName field
            const passwordField = $('input[name="password"]');
            if (passwordField.length && !validateField(passwordField, 'This field is required')) {
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            } else {
                $("div#divLoading").removeClass('hide');
                $("div#divLoading").addClass('show');
            }
        });
    });
</script>




@endsection