@extends('layouts.employeecontent')
@section('employeecontent')
<div class="account-pages">
	<p class="title-common">My Account</p>
	<div class="tab-seaction">
		<!-- Nav pills -->
        <div class="scroll-tabs">
            <ul class="nav nav-pills" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="pill" href="#AccountSettings">Account Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#ChangePassword">Change Password</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#Notifications">Notifications</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#Messages">Messages</a>
                </li> -->
            </ul>
        </div>
		<!-- Tab panes -->
		<div class="tab-content">
			<div id="AccountSettings" class="tab-pane active">
				<div class="tab-bg mb-4">
					<div class="row account-seaction1 mb-3 mt-3">
						<div class="col-12 col-md-12 col-lg-4 col-xl-3">
							<div class="text-center mb-4 mb-lg-0">								
								<div class="mb-3 ddd">
									<img style="width:120px" src="{{ asset('new/img/profile-log.png') }}" />
								</div>
								<!-- <div>
									<button class="bg-transparent fw-600 px-3 py-2 rounded-pill f-15"
                                    style="border: 1px solid #194b9f;color: #194b9f;">Choose Image</button>
								</div> -->
							</div>
						</div>
						<div class="col-12 col-md-12 col-lg-8 col-xl-9">
							<div>								
								<div class="row row-new">
									<div class="col-12 col-md-12 col-lg-6 col-xl-6">
                                        <div class="mb-3">
                                            <label for="Name" class="control-label">Name</label>
                                            <input class="form-control" readonly placeholder="Name" name="Name" type="text" autocomplete="off" value="{{ $employee->employee_username }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="Phone" class="control-label">Phone Number</label>
                                            <input class="form-control" readonly placeholder="Phone Number" name="Phone" type="text" autocomplete="off" value="{{$employee->mobile_no  }}">
                                        </div>										
									</div>
									<div class="col-12 col-md-12 col-lg-6 col-xl-6">
                                        <div class="mb-3">
                                            <label for="Email" class="control-label">Email</label>
                                            <input class="form-control" readonly placeholder="Email" name="Email" type="text" autocomplete="off" value="{{ $employee->personal_email }}">
                                        </div>										
									</div>
								</div>
							</div>
						</div>
					</div>
                    <!-- <div class="text-center">
                        <input class="outline-btn cancel" type="submit" value="Cancel" autocomplete="off">
                        <input class="outline-btn save" type="submit" value="Save Changes" autocomplete="off">
                    </div> -->
				</div>
				<div class="tab-bg mb-4">
					<div>
						<p class="fw-600 textgreen mb-4 mt-4 f-20">Support Details</p>
						<div class="row row-new">
							<div class="col-12 col-md-12 col-lg-6 col-xl-4">
                                <div>
                                    <label class="control-label">Email</label>
                                    <p class="right-account">support@appxpay.in</p>									
								</div>
							</div>
							<div class="col-12 col-md-12 col-lg-6 col-xl-4">
								<div>									
                                    <label class="control-label">Contact</label>                                
                                    <p class="right-account">+91 9087711911</p>									
								</div>
							</div>
						</div>
					</div>
				</div>	
                <div class="tab-bg mb-4">
					<div>
						<p class="fw-600 textgreen mb-4 mt-4 f-20">Account Manager Details</p>
						<div class="row row-new">
							<div class="col-12 col-md-12 col-lg-6 col-xl-4">
                                <div>
                                    <label class="control-label">Email</label>
                                    <p class="right-account">support@appxpay.in</p>									
								</div>
							</div>
							<div class="col-12 col-md-12 col-lg-6 col-xl-4">
								<div>									
                                    <label class="control-label">Contact</label>                                
                                    <p class="right-account">+91 9087711911</p>									
								</div>
							</div>
						</div>
					</div>
				</div>			
			</div>
			<div id="ChangePassword" class="tab-pane fade">
				<div class="tab-bg">
                        							
                    <div class="row row-new mb-3 mt-3">
                        <div class="col-12 col-md-12 col-lg-6 col-xl-4">
                            <div class="mb-3">
                                <label for="Current" class="control-label">Current Password</label>
                                <div class="position-relative">
                                    <input class="form-control" placeholder="Current Password" name="Current" type="password" autocomplete="off">
                                    <img src="{{ asset('new/img/eye.svg') }}" class="position-absolute" style="right: 15px;top: 15px;"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-6 col-xl-4">
                            <div class="mb-3">
                                <label for="New" class="control-label">New Password</label>
                                <div class="position-relative">
                                    <input class="form-control" placeholder="New Password" name="New" type="password" autocomplete="off">
                                    <img src="{{ asset('new/img/eye-close.svg') }}" class="position-absolute" style="right: 15px;top: 15px;"/>
                                </div>	
                            </div>										
                        </div>
                        <div class="col-12 col-md-12 col-lg-6 col-xl-4">
                            <div class="mb-3">
                                <label for="Confirm" class="control-label">Confirm Password</label>
                                <div class="position-relative">
                                    <input class="form-control" placeholder="Confirm Password" name="Confirm" type="password" autocomplete="off">
                                    <img src="{{ asset('new/img/eye-close.svg') }}" class="position-absolute" style="right: 15px;top: 15px;"/>
                                </div>	
                            </div>										
                        </div>
                    </div>
							
                    <div class="text-center">
                        <input class="outline-btn cancel" type="submit" value="Cancel" autocomplete="off">
                        <input class="outline-btn save" type="submit" value="Save Changes" autocomplete="off">
                    </div>
				</div>
			</div>
            <!-- <div id="Notifications" class="tab-pane fade">
				<div class="tab-bg">
					test
				</div>
			</div>
            <div id="Messages" class="tab-pane fade">
				<div class="tab-bg">
					test
				</div>
			</div> -->
		</div>
	</div>
</div>
@endsection