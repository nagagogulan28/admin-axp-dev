<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- font awesome cdn -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        *{
            font-family: "Montserrat", sans-serif !important;
        }
        body {
            margin: 0px;
            background: #ecf4f7;
        }
        .fa {
            font-family: FontAwesome !important;
        }
        p.SessionTimeout {
            font-size: 40px;
            font-weight: 500;
            margin: 1rem;
            color: #3A3939;
        }
        @media(max-width:500px){
            .timeout-img{
                width: 100%;
            }
            p{
                font-size: 16px !important; 
                padding: 0rem 1rem;
            }
            p.SessionTimeout {
                font-size: 30px !important;               
            }
        }
    </style>
</head>
<!-- mynav -->
 {{--<div class="navbar navbar-default-sess navbar-fixed-top session-timout-page" role="navigation">
    <div class="container" style="margin-top: 2rem;margin-bottom: 2rem;"> 
        <div class="navbar-header bg-transparent">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span> 
            </button>
            <a target="_blank" href="#" class="navbar-brand">
                <img src="/assets/img/appxpay-admin-logo.png"  width="170" alt="">
            </a>
        </div>
        <div class="collapse navbar-collapse">
        
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle drop-sess" data-toggle="dropdown">
                        <span class="fa fas-user"></span> 
                        <strong>{{ Auth::guard("employee")->user()->first_name." ".Auth::guard("employee")->user()->last_name }}</strong>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <div class="navbar-login">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <p class="text-center">
                                            <img src="/assets/img/navbar-user.png" width="80" alt="">
                                        </p>
                                    </div>
                                    <div class="col-lg-8">
                                        <p class="text-left"><strong>{{ Auth::guard("employee")->user()->first_name." ".Auth::guard("employee")->user()->last_name }} </strong></p>
                                        <p class="text-left small">{{ Auth::guard("employee")->user()->official_email}} </p>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="navbar-login navbar-login-session">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <p>
                                            <a href="{{ route('appxpay.logout') }}"
                                            onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();" class="btn btn-danger btn-block">
                                            Logout
                                            </a>

                                            <form id="logout-form" action="{{ route('appxpay.logout') }}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>--}}



<div>
    <div>
        <div class="navbar-login navbar-login-session">
            <p class="text-right">
                <a href="{{ route('appxpay.logout') }}"
                onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();" class="d-block font-weight-bold p-3" style="color: #1D4AA3;font-size: 17px;text-decoration: underline;">
                Logout
                </a>

                <form id="logout-form" action="{{ route('appxpay.logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </p>
        </div>
    </div>
    <form action="{{ route('emp-session-update') }}" method="POST">                    
        @if(!session()->has("account-locked"))
            <div class="text-center">
                <img src="{{asset('new/img/timeout.svg')}}" alt="timeout" class="timeout-img"/> 
                <p class="SessionTimeout">Session Timeout </p>
                <p style="color: #96979B;font-size: 21px;margin-bottom: 2rem;">Oh No ! You're being timed out for inactivity </p>
            </div>
            <div class="p-2 p-md-5" style="background: #F6FAFD;">
                <p class="text-center" style="color: #96979B;font-size: 20px;">Please Re-enter your password to stay Signed In</p>
                <div class="container" style="max-width: 379px;">
                    <div class="{{ $errors->has('password') ? ' has-error' : '' }}">                       
                        <div>
                            <label style="color: #3A3939;font-weight: 500;font-size: 18px;">Password</label>
                            <div class="position-relative">
                                <input type="password" name="password" placeholder="Re-enter Password" class="border px-3 py-2 rounded-lg w-100"> 
                                <div style="position: absolute;right: 15px;top: 8px;"class="                                                                                                                                                                                                                                                            dp show-pointer show-cursor" onclick="visiblePasssword('password',this)"> 
                                    <i class="fa fa-eye fa-lg"></i>
                                </div>
                            </div>
                        </div>
                       
                    </div> 
                    <div class="{{ $errors->has('password') ? ' has-error' : '' }}">   
                        @if($errors->has('password'))
                        <span class="help-block">
                            <strong  class="text-danger mt-1 d-block">{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="{{ session()->has('passwordAttempts') ? ' has-error' : '' }}">
                        @if(session()->has('passwordAttempts'))
                        <div class="col-sm-12">
                            <span class="help-block">
                                <strong class="text-danger mt-1 d-block">{{ session('passwordAttempts') }}</strong>
                            </span>
                        </div>
                        @endif
                    </div>
                    {{@csrf_field()}}
                    <input type="submit" value="Login" class="border-0 font-weight-bold mt-3 p-2 rounded-lg text-white w-100" style="font-size: 18px;background: #1D4AA3;">
                    @else                                                                                                                                                                                                                                                                                                                                                                                                                             
                    <h4>Oh no!</h4>
                    <h2>{{session("account-locked")}}</h2>  
                </div> 
            </div>                  
        @endif
    </form>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
</div>    




<script src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/crudapp.js') }}"></script>
</body>
</html>