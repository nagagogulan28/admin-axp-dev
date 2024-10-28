@php
use App\Http\Controllers\EmployeeController;

$filterlinks = EmployeeController::navigation();

$selectedLinks=['dashboard'=>'/dashboard','myaccount'=>'/my-account'];
$icons = [


"fa fa-handshake-o",
"fa fa-credit-card",
"fa fa-group",
"fa fa-inr",
"fa fa-calculator",
"fa fa-bullhorn",
"fa fa-tags",
"fa fa-shield",
"fa fa-gavel",
"fa fa-usb",
"fa fa-ticket",
"fa fa-wrench",
"fa fa-search",
"fa fa-sign-out"
];

$sublinks_names = [];

$sublink_ids = [];

// Define your desired menu order
$desiredOrder = ['Settlement', 'Payout','Merchant','Finance','Account','Marketing','Sales','Risk & Complaince','Legal','Networking','Support','Technical','HRM'];

// Reorder the $filterlinks array based on $desiredOrder
$reorderedLinks = [];
foreach ($desiredOrder as $linkName) {
foreach ($filterlinks as $link) {
if ($link['link_name'] === $linkName) {
$reorderedLinks[] = $link;
break;
}
}
}


$filterlinks = $reorderedLinks;


@endphp
<!-- 
<div class="row">
    <div class="page-wrapper toggled">        
        <nav id="sidebar" class="sidebar-wrapper">
            <div class="sidebar-content">
                <a href="#" id="toggle-sidebar"><i class="fa fa-bars"></i></a>
                <div class="sidebar-brand">
                    <div class="text-center">
                        <img class="AppXpay-logo" src="" alt="AppXpay"  style="width: 100%;"/>
                    </div>
                </div>
                <div class="text-center" style="background: #e1fefe;padding: 2rem 0rem 1rem;">
                    <div style="width: 116px;height: 116px;border-radius: 50%;margin: auto;margin-bottom: 1rem;">
                        <img class="AppXpay-logo" src="{{asset('new/img/ProfilePicture.svg')}}" alt="ProfilePicture"  style="width: 100%;"/>
                    </div>
                    <p style="font-size: 17px;">Kayalvizhi Maniyan</p>
                    <p style="font-size: 12px;margin: 0rem;">Admin</p>
                </div>
                <div class="sidebar-menu">
                    @if(!empty($filterlinks))
                    <ul>
                        <li class="sidebar-dropdown dash active {{ (Request::path() === '/dashboard')?'active':''}}">                            
                            <div id="box">
                                <div id="top"></div>
                                <a href="{{$selectedLinks['dashboard']}}"><i class="fa fa-dashboard"></i><span>Dashboard</span></a>
                                <div id="bottom"></div>
                            </div>
                        </li>


                        @foreach($filterlinks as $index => $link)
                        <li class="sidebar-dropdown">
                            @if(!empty($filterlinks[$index]["sublinks"]))
                            <div id="box">
                                <div id="top"></div>
                                <a class="sub-list" href="javascript:void(0)"><i class="{{$icons[$index]}}"></i><span></span><span>{{$filterlinks[$index]["link_name"]}}</span></a>
                                <div id="bottom"></div>
                            </div>
                            <div class="sidebar-submenu">
                                <ul>

                                    @foreach($filterlinks[$index]["sublinks"] as $index => $sublink)
                                        @php 
                                            $sublink_array = explode("/",$sublink["hyperlink"]);
                                            $sublink_count = count($sublink_array);
                                            $sublinks_names[$sublink_array[$sublink_count-1]] = $sublink["link_name"];
                                            $sublink_ids[$sublink["id"]] = $sublink["hyperlinkid"];
                                        @endphp

                                        <li id="submenu-{{$sublink['id']}}" class="sidebar-submenulist"><a href="{{$sublink['hyperlink']}}">{{$sublink['link_name']}}</a></li>
                                    @endforeach

                                </ul>
                            </div>
                          
                            @else
                            @php 
                               
                            @endphp
                            <a href="{{$link['hyperlink']}}"><i class="{{$icons[$index]}}"></i><span>{{$link["link_name"]}}</span></a>
                            @endif
                        </li>
                        @endforeach
                        <li class="sidebar-dropdown dash {{ (Request::path() === '/my-account')?'active':''}}">                            
                            <div id="box">
                                <div id="top"></div>
                                <a href="{{$selectedLinks['myaccount']}}"><i class="fa fa-user"></i> <span>My Account</span></a>
                                <div id="bottom"></div>
                            </div>
                        </li>
                        <li>
                            <form id="logout-form" action="{{ route('appxpay.logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                            <a href="{{ route('appxpay.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out"></i> <span>Log Out</span>
                            </a>
                        </li>
                    </ul>
                    @endif
                </div>

            </div>
        </nav>
    </div>
</div> -->
@php
session(['sublinkNames'=>$sublinks_names])
@endphp

<!-- <script>
    document.addEventListener('DOMContentLoaded', function () {

        // Check if there is a stored active submenu in local storage
        var activeSubmenu = localStorage.getItem('activeSubmenu');


        $(`#${activeSubmenu}`).parent().parent().css('display','block')
        $(`#${activeSubmenu}`).parent().parent().parent().addClass("active")
        
       

        // If there is an active submenu, add the 'active' class to it
        if (activeSubmenu) {
            var activeSubmenuElement = document.getElementById(activeSubmenu);
            if (activeSubmenuElement) {
                activeSubmenuElement.classList.add('active');
                // If your submenu has a parent dropdown, you may need to add 'show' class as well
                activeSubmenuElement.closest('.sidebar-dropdown').classList.add('show');
            }
        }

            $('.sidebar-submenulist').click(function () {
                // Remove 'active' class from all submenu items


                // Add 'active' class to the clicked submenu item
                this.classList.add('active');

                // Store the active submenu in local storage
                localStorage.setItem('activeSubmenu', this.getAttribute('id'));
            });

        $('.dash').click(function () {
        
           localStorage.clear();

        });
            
    });
</script> -->


<!-- new side bar -->
<div class="sidebar" id="employee-nav">
    <a href="#" id="toggle-sidebar" style="position: absolute;right: 20px;top: 15px;"><i class="fa fa-bars" style="font-size: 20px;"></i></a>
    <a href="#" id="toggle-sidebar-mobile" style="position: absolute;right: 20px;top: 15px;"><i class="fa fa-bars" style="font-size: 20px;"></i></a>
    <div class="text-center">
        <img class="logo" src="{{asset('new/img/appxpay-admin-logo.svg')}}" alt="AppXpay" />
        <img class="logo-small" src="{{ asset('new/img/Appxpay-logo.svg') }}" alt="Appxpay Logo" />
    </div>
    <div class="text-center" style="padding: 1rem 0rem 1rem;">
        <div class="ProfilePicture" style="width: 116px;height: 116px;border-radius: 50%;margin: auto;margin-bottom: 1rem;">
            <img class="AppXpay-logo" src="{{asset('new/img/profile-log.png')}}" alt="ProfilePicture" style="width: 100%;" />
        </div>
        {{-- <p class="mb-1 Profilename" style="font-size: 17px;">Kayalvizhi Maniyan</p>
        <p class="mb-1 Profilename-short" style="font-size: 17px;">KM</p> --}}
        <p class="Profilename" style="font-size: 1.5rem">Admin</p>
    </div>
    <div class="menu-wrapper">
        <ul class="menu">
            <li>
                <a href="/dashboard">
                    <img class="no-active-img" src="{{ asset('new/img/Dashboard.svg') }}" alt="Dashboard-icon" />
                    <img class="active-img" src="{{ asset('new/img/Dashboard-active.svg') }}" alt="Dashboard-icon" />
                    <span class="link_name">Dashboard</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a href="/dashboard">Dashboard</a></li>
                </ul>
            </li>
            <li class="sub-menu">
                <a class="sub-menu-a">
                    <img src="{{ asset('new/img/Register.svg') }}" alt="Register-icon" /><span class="link_name">Merchant Register</span>
                    <span class='cavet'><img src="{{ asset('new/img/arrow-black.svg') }}" alt="white-arrow-right" style="width: auto;margin: auto;position: relative;left: 6px;" /></span>
                </a>
                <ul class="list-inline events">
                    <li>
                        <a href="/onboarding/eqyroksfig">
                            <img class="circle-gray" src="{{ asset('new/img/circle-gray.svg') }}" alt="circle-gray" />
                            <img class="circle-blue" src="{{ asset('new/img/circle-blue.svg') }}" alt="circle-gray" />
                            Merchant Onboarding
                        </a>
                    </li>
                    <li>
                        <a href="/merchants/list/index">
                            <img class="circle-gray" src="{{ asset('new/img/circle-gray.svg') }}" alt="circle-gray" />
                            <img class="circle-blue" src="{{ asset('new/img/circle-blue.svg') }}" alt="circle-gray" />
                            Merchant List
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="/merchants/transactions">
                    <img class="no-active-img" src="{{ asset('new/img/Transaction.svg') }}" alt="Dashboard-icon" />
                    <img class="active-img" src="{{ asset('new/img/Transaction-active.svg') }}" alt="Dashboard-icon" />
                    <span class="link_name">Transaction</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a href="/merchants/transactions">Transaction</a></li>
                </ul>
            </li>
            <li class="sub-menu">
                <a class="sub-menu-a">
                    <img src="{{ asset('new/img/Vendor.svg') }}" alt="Register-icon" /><span class="link_name">Payout</span>
                    <span class='cavet'><img src="{{ asset('new/img/arrow-black.svg') }}" alt="white-arrow-right" style="width: auto;margin: auto;position: relative;left: 6px;" /></span>
                </a>
                <ul class="list-inline events">
                    <li>
                    <a href="/payout-dashboard">
                            <img class="circle-gray" src="{{ asset('new/img/circle-gray.svg') }}" alt="circle-gray" />
                            <img class="circle-blue" src="{{ asset('new/img/circle-blue.svg') }}" alt="circle-gray" />
                         Payout Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="/payout/addfund">
                            <img class="circle-gray" src="{{ asset('new/img/circle-gray.svg') }}" alt="circle-gray" />
                            <img class="circle-blue" src="{{ asset('new/img/circle-blue.svg') }}" alt="circle-gray" />
                            Payout Wallet Funds
                        </a>
                    </li>
                    <li>
                        <a href="/merchant/service/fee">
                            <img class="circle-gray" src="{{ asset('new/img/circle-gray.svg') }}" alt="circle-gray" />
                            <img class="circle-blue" src="{{ asset('new/img/circle-blue.svg') }}" alt="circle-gray" />
                            Merchant Service Fee
                        </a>
                    </li>
                    <li>
                        <a href="/payout-transactions">
                            <img class="circle-gray" src="{{ asset('new/img/circle-gray.svg') }}" alt="circle-gray" />
                            <img class="circle-blue" src="{{ asset('new/img/circle-blue.svg') }}" alt="circle-gray" />
                            Payout Transaction
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="/merchant/settlements/list">
                    <img class="no-active-img" src="{{ asset('new/img/Settlement.svg') }}" alt="Dashboard-icon" />
                    <img class="active-img" src="{{ asset('new/img/Settlement-active.svg') }}" alt="Dashboard-icon" />
                    <span class="link_name">Settlement</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a href="/merchant/settlements/list">Settlement</a></li>
                </ul>
            </li>
            <!-- <li>
                <a href="">
                    <img class="no-active-img" src="{{ asset('new/img/Vendor.svg') }}" alt="Dashboard-icon" />
                    <img class="active-img"src="{{ asset('new/img/Vendor-active.svg') }}" alt="Dashboard-icon" />  
                    <span class="link_name">Vendor</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a href="">Vendor</a></li>
                </ul>
            </li> -->

            <li class="sub-menu">
                <a class="sub-menu-a">
                    <img src="{{ asset('new/img/Vendor.svg') }}" alt="Vendor-icon" /><span class="link_name">Vendor</span>
                    <span class='cavet'><img src="{{ asset('new/img/arrow-black.svg') }}" alt="white-arrow-right" style="width: auto;margin: auto;position: relative;left: 6px;" /></span>
                </a>
                <ul class="list-inline events">
                    <li>
                        <a href="/onboarding/jnylavzkrs">
                            <img class="circle-gray" src="{{ asset('new/img/circle-gray.svg') }}" alt="circle-gray" />
                            <img class="circle-blue" src="{{ asset('new/img/circle-blue.svg') }}" alt="circle-gray" />
                            Onboarding
                        </a>
                    </li>
                    <li>
                        <a href="/vendor/list/index">
                            <img class="circle-gray" src="{{ asset('new/img/circle-gray.svg') }}" alt="circle-gray" />
                            <img class="circle-blue" src="{{ asset('new/img/circle-blue.svg') }}" alt="circle-gray" />
                            List
                        </a>
                    </li>

                </ul>
            </li>
            <li class="sub-menu">
                <a class="sub-menu-a">
                    <img src="{{ asset('new/img/Roles.svg') }}" alt="Roles-icon" /><span class="link_name">Roles & Permissions</span>
                    <span class='cavet'><img src="{{ asset('new/img/arrow-black.svg') }}" alt="white-arrow-right" style="width: auto;margin: auto;position: relative;left: 6px;" /></span>
                </a>
                <ul class="list-inline events">
                    <li>
                        <a href="/roles/view">
                            <img class="circle-gray" src="{{ asset('new/img/circle-gray.svg') }}" alt="circle-gray" />
                            <img class="circle-blue" src="{{ asset('new/img/circle-blue.svg') }}" alt="circle-gray" />
                            List of Roles
                        </a>
                    </li>
                    <li>
                        <a href="/modules/manage/index">
                            <img class="circle-gray" src="{{ asset('new/img/circle-gray.svg') }}" alt="circle-gray" />
                            <img class="circle-blue" src="{{ asset('new/img/circle-blue.svg') }}" alt="circle-gray" />
                            Manage Modules
                        </a>
                    </li>
                    <li>
                        <a href="/permission/index">
                            <img class="circle-gray" src="{{ asset('new/img/circle-gray.svg') }}" alt="circle-gray" />
                            <img class="circle-blue" src="{{ asset('new/img/circle-blue.svg') }}" alt="circle-gray" />
                            Permissions
                        </a>
                    </li>
                </ul>
            </li>

            <li class="sub-menu">
                <a class="sub-menu-a">
                    <img src="{{ asset('new/img/Settings.svg') }}" alt="Settings-icon" style="width: 25px;" />
                    <span class="link_name">Settings</span>
                    <span class='cavet'>
                        <img src="{{ asset('new/img/arrow-black.svg') }}" alt="white-arrow-right" style="width: auto; margin: auto; position: relative; left: 6px;" />
                    </span>
                </a>
                <ul class="list-inline events">
                    <li>
                        <a href="/service/fund/index">
                            <img class="circle-gray" src="{{ asset('new/img/circle-gray.svg') }}" alt="circle-gray" />
                            <img class="circle-blue" src="{{ asset('new/img/circle-blue.svg') }}" alt="circle-gray" />
                            Service Funds
                        </a>
                    </li>
                    <li>
                        <a href="/merchant/partner/list">
                            <img class="circle-gray" src="{{ asset('new/img/circle-gray.svg') }}" alt="circle-gray" />
                            <img class="circle-blue" src="{{ asset('new/img/circle-blue.svg') }}" alt="circle-gray" />
                            Partner List
                        </a>
                    </li>
                    <li>
                        <a href="/bank/accounts/list">
                            <img class="circle-gray" src="{{ asset('new/img/circle-gray.svg') }}" alt="circle-gray" />
                            <img class="circle-blue" src="{{ asset('new/img/circle-blue.svg') }}" alt="circle-gray" />
                            Received Banks
                        </a>
                    </li>

                </ul>
            </li>
            <li>
                <a href="/employee/pages/myaccount">
                    <img class="no-active-img" src="{{ asset('new/img/MyAccount.svg') }}" alt="Dashboard-icon" />
                    <img class="active-img" src="{{ asset('new/img/MyAccount-active.svg') }}" alt="Dashboard-icon" />
                    <span class="link_name">My Account</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a href="/employee/pages/myaccount">My Account</a></li>
                </ul>
            </li>
            <li>
                <a href="" onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                    <img class="no-active-img" src="{{ asset('new/img/Logout.svg') }}" alt="Dashboard-icon" />
                    <img class="active-img" src="{{ asset('new/img/Logout-active.svg') }}" alt="Dashboard-icon" />
                    <span class="link_name">Logout</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a href="" onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">Logout</a></li>
                </ul>

                <form id="logout-form" action="{{ route('appxpay.logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
        </ul>
    </div>
</div>