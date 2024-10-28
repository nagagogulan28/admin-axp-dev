@if(isset($first_time_login) && $first_time_login == 'Y')
<div class="modal-header bg-info">
    <h5 class="modal-title text-light">Change Password Request</h5>
    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
      <div class="col-sm-12">
        <h6 class="text-danger text-center">Your password has expired please change your password</h6>
      </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-center pb-3">
            <img src="/assets/img/registration.jpg" width="280" height="130" alt="registration.jpg" class="rounded-circle img-responsive">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 offset-sm-2 pt-3 pb-3">
          <span id="success-body" class="text-sm-center" style="color: green;">An OTP has sent to your mobile no</span>
            <span id="error-body" class="text-sm-center"></span>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <form id="first-time-password-otp">
              <div class="form-group row">
                <label for="password-change" class="col-sm-1 offset-sm-2 col-form-label text-right">OTP:</label>
                <div class="col-sm-8 offset-sm-1">
                  <input type="text" class="form-control" id="mobile" name="firsttimepasswordOTP" value="{{ $firsttimepasswordOTP }}">
                  <span id='firsttimepasswordOTP_ajax_error'></span>
                </div>
              </div>
              {{csrf_field()}}
              <div class="form-group row">
                <div class="col-sm-8 offset-sm-4">
                  <input type="submit" class="btn btn-primary" value="Next">
                </div>
              </div>
            </form>
        </div>
    </div>
</div>
@endif

@if(isset($change_password_form) && $change_password_form == "Y")
<div class="modal-header bg-info">
  <h5 class="modal-title text-light">Change Password</h5>
  <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <div class="row">
      <div class="col-sm-12 text-center pb-3">
          <img src="/assets/img/change-password.png" width="280" height="130" alt="change-password.png" class="rounded-circle img-responsive">
      </div>
  </div>
  <div class="row">
      <div class="col-sm-12 pb-3 text-sm-center">
          <span id="success-body" class="text-sm-center"></span>
          <span id="error-body" class="text-sm-center"></span>
      </div>
  </div>
  <div class="row">
      <div class="col-sm-12 change-password">
          <form id="change-password">
            <div class="form-group row">
                <label for="password-change" class="col-sm-5 col-form-label text-right">Password:</label>
              <div class="col-sm-6 row">
                <input type="password" class="form-control" id="password" name="password">
                <span id='password_ajax_error'></span>
              </div>
              <div class="i show-pointer col-sm-1" onclick="showPopUpPasssword('password',this)"> 
                <i class="fa fa-eye-slash fa-lg"></i>
              </div>
            </div>
            <div class="form-group row">
              <label for="password-change" class="col-sm-5 col-form-label text-right">Confirm Password:</label>
            <div class="col-sm-6 row">
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
              <span id='password_confirmation_ajax_error'></span>
            </div>
            <div class="i show-pointer col-sm-1" onclick="showPopUpPasssword('password_confirmation',this)"> 
              <i class="fa fa-eye-slash fa-lg"></i>
            </div>
          </div>
            {{csrf_field()}}
            <div class="form-group row">
              <div class="col-sm-8 offset-sm-3">
                <input type="submit" class="btn btn-primary" value="Send">
              </div>
            </div>
          </form>
      </div>
  </div>
</div>
@endif

@if(isset($second_factory_auth) && $second_factory_auth == 'Y')
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
                  <input type="text" class="form-control" id="email-otp" value="{{ $appxpay_email_verify  }}" name="email_otp">
                  <!-- <div class="verification-code--inputs d-flex justify-content-between">
                    <input type="text" maxlength="1" />
                    <input type="text" maxlength="1" />
                    <input type="text" maxlength="1" />
                    <input type="text" maxlength="1" />
                    <input type="text" maxlength="1" />
                    <input type="text" maxlength="1" />
                  </div> -->
                  <span id='email_otp_ajax_error' value=""></span>
                </div>
              </div>
              {{csrf_field()}}
              <div class="form-group row mt-5 mb-0">
                <div class="col-sm-12">
                  <!-- <input type="submit" class="btn btn-primary" value="Send"> -->
                  <input type="button" class="btn btn-primary" value="Send" style="background: #1D4AA3;color: #fff;border-radius: 13px;">
                </div>
              </div>
            </form>
        </div>
    </div>
</div>
@endif

@if(isset($third_factory_auth) && $third_factory_auth == 'Y')
<div class="modal-header bg-info">
    <h5 class="modal-title text-light">Login Mobile Verify</h5>
    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-12 text-center pb-3">
            <img src="/assets/img/registration.jpg" width="280" height="130" alt="registration.jpg" class="rounded-circle img-responsive">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 offset-sm-2 pt-3 pb-3">
            <span id="success-body" class="text-sm-center"></span>
            <span id="error-body" class="text-sm-center"></span>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <form id="mobile-otp-form">
              <div class="form-group row">
                <label for="email" class="col-sm-1 offset-sm-2 col-form-label text-right">OTP:</label>
                <div class="col-sm-8 offset-sm-1">
                  <input type="text" class="form-control" id="mobile-otp" name="mobile_otp">
                  <span id='mobile_otp_ajax_error'></span>
                </div>
              </div>
              {{csrf_field()}}
              <div class="form-group row">
                <div class="col-sm-8 offset-sm-4">
                  <input type="submit" class="btn btn-primary" value="Send">
                </div>
              </div>
            </form>
        </div>
    </div>
</div>
@endif

@if(isset($email_auth) &&  $email_auth == 'Y')
<div class="modal-header bg-info">
    <h5 class="modal-title text-light">Email Verification</h5>
    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-12 text-center pb-3">
            <img src="/assets/img/forget-password.png" width="280" height="130" alt="forget-password" class="rounded-circle img-responsive">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 offset-sm-2 pt-3 pb-3">
            <span id="success-body" class="text-sm-center" style="color: green;"> OTP has been auto fetched</span>
            <span id="error-body" class="text-sm-center"></span>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <form id="employee-email-otp-form" method="POST">
              <div class="form-group row">
                <label for="email-otp" class="col-sm-1 offset-sm-2 col-form-label text-right">OTP:</label>
                <div class="col-sm-8 offset-sm-1">
                  <input type="text" class="form-control" id="email-otp" name="email_otp">
                  <span id='email_otp_ajax_error'></span>
                </div>
              </div>
              {{csrf_field()}}
              <div class="form-group row">
                <div class="col-sm-8 offset-sm-4">
                  <input type="submit" class="btn btn-primary" value="Send">
                </div>
              </div>
            </form>
        </div>
    </div>
</div>
@endif

@if(isset($mobile_auth) && $mobile_auth == 'Y')
<div class="modal-header bg-info">
    <h5 class="modal-title text-light">Mobile Verify</h5>
    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-12 text-center pb-3">
            <img src="/assets/img/registration.jpg" width="280" height="130" alt="registration.jpg" class="rounded-circle img-responsive">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 offset-sm-2 pt-3 pb-3">
            <span id="success-body" class="text-sm-center"  style="color: green;">An OTP has sent to your registered mobile no</span>
            <span id="error-body" class="text-sm-center"></span>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <form id="forget-mobile-otp-form">
              <div class="form-group row">
                <label for="email" class="col-sm-1 offset-sm-2 col-form-label text-right">OTP:</label>
                <div class="col-sm-8 offset-sm-1">
                  <input type="text" class="form-control" id="mobile-otp" name="mobile_otp">
                  <span id='mobile_otp_ajax_error'></span>
                </div>
              </div>
              {{csrf_field()}}
              <div class="form-group row">
                <div class="col-sm-8 offset-sm-4">
                  <input type="submit" class="btn btn-primary" value="Send">
                </div>
              </div>
            </form>
        </div>
    </div>
</div>
@endif

@if(isset($reset_password_form) && $reset_password_form == "Y")
<div class="modal-header bg-info">
  <h5 class="modal-title text-light">Reset Password</h5>
  <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <div class="row">
      <div class="col-sm-12 text-center pb-3">
          <img src="/assets/img/change-password.png" width="280" height="130" alt="change-password.png" class="rounded-circle img-responsive">
      </div>
  </div>
  <div class="row">
      <div class="col-sm-12 pb-3 text-sm-center">
          <span id="success-body" class="text-sm-center"></span>
          <span id="error-body" class="text-sm-center"></span>
      </div>
  </div>
  <div class="row">
      <div class="col-sm-12 change-password">
          <form id="reset-password">
            <div class="form-group row">
                <label for="password-change" class="col-sm-5 col-form-label text-right">Password:</label>
              <div class="col-sm-6 row">
                <input type="password" class="form-control" id="password" name="password">
                <span id='password_ajax_error'></span>
              </div>
              <div class="i show-pointer col-sm-1" onclick="showAdminPasssword('password',this)"> 
                <i class="fa fa-eye fa-lg"></i>
              </div>
            </div>
            <div class="form-group row">
              <label for="password-change" class="col-sm-5 col-form-label text-right">Confirm Password:</label>
            <div class="col-sm-6 row">
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
              <span id='password_confirmation_ajax_error'></span>
            </div>
            <div class="i show-pointer col-sm-1" onclick="showAdminPasssword('password_confirmation',this)"> 
              <i class="fa fa-eye fa-lg"></i>
            </div>
          </div>
            {{csrf_field()}}
            <div class="form-group row">
              <div class="col-sm-8 offset-sm-3">
                <input type="submit" class="btn btn-primary" value="Send">
              </div>
            </div>
          </form>
      </div>
  </div>
</div>
@endif