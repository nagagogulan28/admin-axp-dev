@extends('layouts.employeeapp')

@section('content')
<div id="divLoading">
    <img src="{{asset('new/img/Walk.gif') }}" alt="Appxpay-logo" width="250" class="mb-3" style="opacity: 0;">
</div>
<div class="loginBackground">
    <div class="container">
        <div class="login-content">
            <form method="POST" id="employee-login" autocomplete="off">
                <img src="{{asset('new/img/appxpay-admin-logo.png') }}" alt="Appxpay-logo" width="250" class="mb-3">
                <br>
                <br>
                {{-- <h3 class="title text-left" style="font-size: 24px;font-weight: 500;line-height: 36px;color: #1D4AA3;">Welcome Back!</h3> --}}
                <div class="title-subtext">Please enter the credentials to login</div>
                <br>
                <div id="employee-success-message" class="text-success">
                    @if(session('success'))
                    {{session('success')}}
                    @endif
                </div>
                @if(session('loginstatus') === FALSE)
                <div class="alert alert-danger">
                    {{ session('message') }}
                </div>
                @endif
                <div id="employee-login-error"></div>
                <div class="input-div one ">
                    <div class="div">
                        <label class="label-login">Username</label>
                        <input type="text" class="input" name="employee_username" id="employee_username" placeholder="Username" value="{{ old('employee_username') }}" required autofocus autocomplete="off">
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="div">
                        <label class="label-login" style="width: 100px;">Password</label>
                        <input type="password" maxlength="20" class="input" name="password" id="password" placeholder="Password" required autocomplete="off">
                    </div>
                    <i class="fa fa-eye-slash password-toggle" aria-hidden="true" style="
                    color: #7c7c7c;
                    display: flex;
                    justify-content: flex-end;
                    align-items: center;
                    font-size: 20px;
                    position:absolute;
                    top: 12px;
                    right: 13px;
                " onclick="togglePassword()"></i>
                </div>
                <br>
                <input type="submit" class="btn" value="Login" style="background: #1D4AA3;color: #fff;border-radius: 13px;"><br>
                {{ csrf_field() }}
            </form>
        </div>
    </div>
    <div>
        <div class="modal employee-login" tabindex="-1" role="dialog" id="employee-login-modal">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="padding: 40px 20px;border-radius: 10px;box-shadow: 0px 0px 10px 0px #00000040;border: 0px;">
                    <div id="load-login-form">
                        <div class="modal-header border-0 pb-0 pt-0" style="display: block;">
                            <div class="text-center">
                                <img src="{{asset('new/img/appxpay-admin-logo.png') }}" alt="Appxpay-logo" width="250" class="mb-3 mob-width">
                            </div>
                            <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close" style="position: absolute;top: 15px;right: 15px;">
                                <img src="{{asset('new/img/cross.svg') }}" alt="cross" style="width: 18px;height: 18px;">
                            </button>
                        </div>
                        <br>
                        <div class="modal-body p-0">
                            <h3 class="title text-left" style="font-size: 24px;font-weight: 500;line-height: 36px;color: #1D4AA3;">Login Email Verfication</h3>
                            <div class="row">
                                <div class="col-sm-12">
                                    <span id="success-body" class="title-subtext">An OTP has sent to your official email</span>
                                    <span id="error-body" class="text-sm-center"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <form id="email-otp-form" action="/email-verify-otp" method="POST">
                                        <div class="form-group">
                                            <label for="email-otp" class="col-form-label text-left" style="font-weight: 400;line-height: 36px;color: #555555;font-size: 20px;margin: 1rem 0rem;">Enter the Six-digit OTP</label>
                                            <div class="">
                                                <input type="hidden" class="form-control" id="email-otp" value="" name="email_otp">
                                                <div class="verification-code--inputs d-flex justify-content-between">
                                                    <input value="" class="inputsection1" type="text" maxlength="1" />
                                                    <input value="" class="inputsection2" type="text" maxlength="1" />
                                                    <input value="" class="inputsection3" type="text" maxlength="1" />
                                                    <input value="" class="inputsection4" type="text" maxlength="1" />
                                                    <input value="" class="inputsection5" type="text" maxlength="1" />
                                                    <input value="" class="inputsection6" type="text" maxlength="1" />
                                                </div>
                                                <span id='email_otp_ajax_error'></span>
                                            </div>
                                        </div>
                                        {{csrf_field()}}
                                        <div class="form-group row mt-5 mb-0">
                                            <div class="col-sm-12">
                                                <!-- <input type="submit" class="btn btn-primary" value="Send"> -->
                                                <input type="submit" class="btn btn-primary" id="otp-match-now" value="Send" style="background: #1D4AA3;color: #fff;border-radius: 13px;">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function togglePassword() {
                const passwordInput = document.getElementById('password');
                const togglePasswordIcon = document.querySelector('.password-toggle');

                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);


                if (type === 'password') {
                    togglePasswordIcon.classList.remove('fa-eye');
                    togglePasswordIcon.classList.add('fa-eye-slash');
                } else {
                    togglePasswordIcon.classList.remove('fa-eye-slash');
                    togglePasswordIcon.classList.add('fa-eye');
                }
            }
        </script>
        @endsection