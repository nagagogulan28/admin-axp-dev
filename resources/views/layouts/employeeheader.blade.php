<nav class="navbar navbar-expand-xl navbar-dark">
  <div class="align-items-center d-flex justify-content-between w-xl-100">
    <a href="#" id="toggle-sidebar-head" style="padding: 0px;background: transparent !important;margin-right: 1rem;"><i class="fa fa-bars" style="font-size: 20px;"></i></a>
    <ul class="nav navbar-nav navbar-left">
      <li><a style="font-size: 24px;font-weight: 500;text-align: left;color: #211C37;">Welcome, SuperAdmin !<img src="{{ asset('new/img/handsign.svg')}}" alt="print" class="ml-2"></a></li>
    </ul>
    <button class="navbar-toggler bg-secondary" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
    </button>
  </div>
  <div class="collapse navbar-collapse justify-content-end" id="collapsibleNavbar">
    <ul class="nav navbar-nav navbar-right align-items-center">
      <li class="current-time">
        <a href="javascript:">
          <strong>
            <div id="nav-clock"></div>
          </strong>
        </a>
      </li>
      <li class="ip-address"><a href="javascript:"><strong><span style='color:#00a8e9'>Login Ip:</span> {{ Request::ip()}}</strong></a></li>
      <li><a href="javascript:"><img src="{{ asset('new/img/not.svg')}}" alt="not"></a></li>
      @if(!auth()->guard('employee')->check())
      <li><a href="{{ route('appxpay.login') }}">Login</a></li>
      @else
      <!-- <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
        {{ Auth::guard('employee')->user()->first_name." ".Auth::guard('employee')->user()->last_name }} <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" style="left:auto;justify-content: center;text-align: center;">
          <li>
            <a href="{{ route('appxpay.logout') }}" onclick="event.preventDefault();
              document.getElementById('logout-form').submit();">
            Logout
            </a>
            <form id="logout-form" action="{{ route('appxpay.logout') }}" method="POST" style="display: none;">
              {{ csrf_field() }}
            </form>
          </li>
        </ul>
      </li> -->
      <li class="ProfilePicture" style="width: 48px;height: 48px;border-radius: 50%;">
        <img class="AppXpay-logo" src="{{asset('new/img/profile-log.png')}}" alt="ProfilePicture"  style="width: 100%;"/>
      </li>
      @endif
    </ul>
  </div>
</nav>