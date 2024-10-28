@extends('layouts.employeecontent')
@section('employeecontent')
<style>
    .card {
        box-shadow: 0px 2.76px 13.8px 0px #0000001A;
        border: 0px;
        border-radius: 20px;
    }
</style>

<div>
    <p class="title-common">Dashboard</p>
    <div>
        <div class="row row-new" style="margin-bottom: 4rem !important;">
            <div class="col-12 col-sm-6 col-lg-4 mb-4">
                <label class="lable-title" for="time-input">Duration</label>
                <div style="position: relative;">
                    <input type="text" name="dashboarddatetimes" id="datetimes" class="form-control common-date-picker" style="background-color: #fff !important;" />
                    <img class="calendar-icon" src="{{asset('new/img/calendar.svg')}}" alt="calendar" id="calendar-icon"/>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4 mb-4">
    <label class="lable-title" for="merchantId">Company Name</label>
    <select name="merchantId" id="merchantId" class="form-control" style="background-color: #fff !important;">
        <option value="" selected>Select a Company</option>
        @foreach ($merchantsUsers as $merchant)
            <option value="{{ $merchant->id }}">{{ $merchant->businessDetail->company_name }}</option>
        @endforeach
    </select>
</div>
<div class="col-12 col-sm-6 col-lg-4 mb-4">
    <label class="lable-title" for="terminalId">Website</label>
    <select name="terminalId" id="terminalId" class="form-control" style="background-color: #fff !important;" disabled>
    <option value="0" selected>Select a Website</option>
</select>

</div>


            {{--
                <div class="col-6 col-lg-2">
                    <button type="submit" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; margin-top: 30px; text-align: center;">Submit</button>
                </div>
                --}}
            <!-- <div class="col-12 col-sm-12 col-lg-3 mb-4 d-flex align-items-end">
                    <form id="transaction-download-form" action="{{route('download-transactiondata')}}" method="POST" role="form">
                    <input type="hidden" name="selected_mode" id="selected_mode" value="live">
                    <input type="hidden" name="selected_status" id="selected_status" value="">
                    <input type="hidden" name="selected_merchant" id="selected_merchant" value="">
                    <input type="hidden" name="selected_date_ranges" id="selected_date_ranges" value="">
                    {{csrf_field()}}
                    <button type="submit" id="excel_btn" class="btn btn-primary blue-btn">Download Excel</button>
                    </form>
                </div> -->
        </div>


        <div class="row row-new dash-width">

            <div class="col-12 col-sm-6  co-md-6 col-lg-4 mb-4">
                <div class="app-card h-100" style="background: linear-gradient(278.85deg, #FBFCBC -14.38%, #AAB903 61.57%);box-shadow: 0px 17.28px 29.63px 0px #AAB90366;">
                    <div class="app-inner">
                        <div style="padding-right: 15px;">
                            <img src="{{ asset('new/img/TotalAmountCollected.svg')}}" alt="Group1">
                        </div>
                        <div class="">
                            <div class="fw-600 f-30 text-white">₹ <span id="total_collection_amount">0</span></div>

                            <h4 class="f-16 fw-600 text-white mb-0 mt-0">Collection Amount</h4>
                        </div>
                    </div>
                    <a class="app-card-link-mask" href="#"></a>
                </div>
            </div>

            <div class="col-12 col-sm-6  co-md-6 col-lg-4 mb-4">
                <div class="app-card h-100" style="background: linear-gradient(278.77deg, #D6BDFC 0%, #AA7AF4 100%);box-shadow: 0px 17.28px 29.63px 0px #AA7AF466;">
                    <div class="app-inner">
                        <div style="padding-right: 15px;">
                            <img src="{{ asset('new/img/TotalTransaction.svg')}}" alt="Group1">
                        </div>
                        <div class="">
                            <div class="fw-600 f-30 text-white" id="totalTransaction">0</div>
                            <h4 class="f-16 fw-600 text-white mb-0 mt-0">Overall Transaction's</h4>
                        </div>
                    </div>
                    <a class="app-card-link-mask" href="merchant/transactions/appxpay-Ma42px1Z"></a>
                </div>
            </div>
            <div class="col-12 col-sm-6  co-md-6 col-lg-4 mb-4">
                <div class="app-card h-100" style="background: linear-gradient(98.77deg, #10E8B4 0%, #C0F3E7 100%);box-shadow: 0px 17.28px 29.63px 0px #10E8B466;">
                    <div class="app-inner">
                        <div style="padding-right: 15px;">
                            <img src="{{ asset('new/img/Totalsuccessfultransactions.svg')}}" alt="Group1">
                        </div>
                        <div class="">
                            <div class="fw-600 f-30 text-white" id="successfulTransaction">0</div>
                            <h4 class="f-16 fw-600 text-white mb-0 mt-0">Success</h4>
                        </div>
                    </div>
                    <a class="app-card-link-mask" href="merchant/transactions/appxpay-Ma42px1Z?status=success"></a>
                </div>
            </div>

            <div class="col-12 col-sm-6  co-md-6 col-lg-4 mb-4">
                <div class="app-card h-100" style="background: linear-gradient(279.06deg, #EAFB81 -23.25%, #FF8383 100%);box-shadow: 0px 17.28px 29.63px 0px #FF838366;">
                    <div class="app-inner">
                        <div style="padding-right: 15px;">
                            <img src="{{ asset('new/img/TotalUsers.svg')}}" alt="Group1">
                        </div>
                        <div class="">
                            <div class="fw-600 f-30 text-white" id="cancelledTransaction"></div>
                            <h4 class="f-16 fw-600 text-white mb-0 mt-0">Cancelled </h4>
                        </div>
                    </div>
                    <a class="app-card-link-mask" href="#"></a>
                </div>
            </div>
            <div class="col-12 col-sm-6  co-md-6 col-lg-4 mb-4">
                <div class="app-card h-100" style="background: linear-gradient(100.13deg, #6C70FA 29.76%, #D7D8FF 115.07%);box-shadow: 0px 17.28px 29.63px 0px #6C70FA99;">
                    <div class="app-inner">
                        <div style="padding-right: 15px;">
                            <img src="{{ asset('new/img/TotalGTV.svg')}}" alt="Group1">
                        </div>
                        <div class="">
                            <div class="fw-600 f-30 text-white" id="ht_failed_transaction">0</div>
                            <h4 class="f-16 fw-600 text-white mb-0 mt-0">Failed</h4>
                        </div>
                    </div>
                    <a class="app-card-link-mask" href="#"></a>
                </div>
            </div>
            <div class="col-12 col-sm-6  co-md-6 col-lg-4 mb-4">
                <div class="app-card h-100" style="background: linear-gradient(278.34deg, #E5FCE5 -14.83%, #02C04E 100%);box-shadow: 0px 17.28px 29.63px 0px #02C04E66;">
                    <div class="app-inner">
                        <div style="padding-right: 15px;">
                            <img src="{{ asset('new/img/ChargebackAmount.svg')}}" alt="Group1">
                        </div>
                        <div class="">
                            <div class="fw-600 f-30 text-white" id="ht_pending_transaction">0</div>
                            <h4 class="f-16 fw-600 text-white mb-0 mt-0">Pending</h4>
                        </div>
                    </div>
                    <a class="app-card-link-mask" href="#"></a>
                </div>
            </div>
            <div class="col-12 col-sm-6  co-md-6 col-lg-4 mb-4">
                <div class="app-card h-100" style="background: linear-gradient(98.77deg, #D545C6 0%, #F3C2E5 100%);box-shadow: 0px 17.28px 29.63px 0px #D445C666;">
                    <div class="app-inner">
                        <div style="padding-right: 15px;">
                            <img src="{{ asset('new/img/TotalAmountRefunded.svg')}}" alt="Group1">
                        </div>
                        <div class="">
                            <div class="fw-600 f-30 text-white" id="refund">₹ 0</div>
                            <h4 class="f-16 fw-600 text-white mb-0 mt-0">Refunded</h4>
                        </div>
                    </div>
                    <a class="app-card-link-mask" href="#"></a>
                </div>
            </div>
            <div class="col-12 col-sm-6  co-md-6 col-lg-4 mb-4">
                <div class="app-card h-100" style="background: linear-gradient(278.34deg, #E5FCE5 -14.83%, #f32f9af7 80%);box-shadow: 0px 17.28px 29.63px 0px #f32f9a94 ;">
                    <div class="app-inner">
                        <div style="padding-right: 15px;">
                            <img src="{{ asset('new/img/ChargebackAmount.svg')}}" alt="Group1">
                        </div>
                        <div class="">
                            <div class="fw-600 f-30 text-white" id="chargeback">₹ 0</div>
                            <h4 class="f-16 fw-600 text-white mb-0 mt-0">Chargeback</h4>
                        </div>
                    </div>
                    <a class="app-card-link-mask" href="#"></a>
                </div>
            </div>
        </div>

        <p class="title-common" style="margin-top: 4rem;">Gross Transaction Value VS Transaction Count</p>
        <div class="row g-4 mb-4 row-new d-md-flex" style="align-items: center;">
            <div class="col-12 col-sm-6 col-lg-6 col-xl-3 mb-3">
                <label class="lable-title" for="merchantFilter">Merchant Name</label>
                <form method="get" action="{{ route('appxpay-dashboard') }}">
                    <select id="merchantFilter" name="merchant_id" class="form-control" onchange="this.form.submit()">
                        <option value="">All Merchants</option>
                        @foreach ($merchantsUsers as $merchant)
                        <option value="{{ $merchant->id }}" {{ $merchant->id == $selectedMerchantId ? 'selected' : '' }}>
                            {{ $merchant->businessDetail->company_name }}
                        </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="col-12 col-sm-12 col-lg-6 col-xl-9 mb-3 mb-md-0">
                <div id="excelButton" title="Download Excel" style="float: right;font-size: 20px;font-weight: 500;cursor: pointer;">
                    Print Success Ratio <img src="{{ asset('new/img/print.svg')}}" alt="print" class="margin-left-sm">
                </div>
            </div>
        </div>


        <!-- <div class="row row-new">
            <div class="col-lg-12 col-md-12 mbb-4">
                <div class="card ">
                    <div class="card-body">
                        <canvas id="successRatioChart" width="800" height="350"></canvas>
                    </div>
                </div>
            </div>
        </div> -->

        <p class="title-common" style="margin-top: 4rem;">Login Activities</p>
        <div class="row row-new">
            <div class="col-lg-12 col-md-12 mbb-4">
                <div class="card ">
                    <div class="card-body">
                        
                        <table class="table" id="loginActivitiesTable" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Login Ip address</th>
                            <th>Login Device</th>
                            <th>Login Os</th>
                            <th>Login Time</th>
                        </tr>
                    </thead>
                </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>


<script src="https://unpkg.com/boxicons@2.1.2/dist/boxicons.js"></script>

<script src="//cdn.amcharts.com/lib/4/core.js"></script>
<script src="//cdn.amcharts.com/lib/4/charts.js"></script>
<script src="//cdn.amcharts.com/lib/4/themes/animated.js"></script>
<script src="//cdn.amcharts.com/lib/4/themes/kelly.js"></script>

<script>
$(document).ready(function() {

// Function to pad numbers with a leading zero if needed
function padZero(number) {
    return number < 10 ? '0' + number : number;
}

// Function to get today's formatted date
function getFormattedDate() {
    const now = new Date();
    const year = now.getFullYear();
    const month = padZero(now.getMonth() + 1); // Months are zero-based
    const day = padZero(now.getDate());

    const startOfDay = `${month}/${day}/${year}`;
    const endOfDay = `${month}/${day}/${year}`;

    return {
        startOfDay,
        endOfDay
    };
}

const today = getFormattedDate();
var date = today.startOfDay + ' - ' + today.endOfDay;

// Default: Call API to get all data on page load
callingapis(date, null, null);

function getSelectedValues() {
    var selectDate = $('input[name="dashboarddatetimes"]').val();
    var merchantId = $('#merchantId').val();
    var terminalId = $('#terminalId').val();
    console.log(merchantId, terminalId, selectDate);
    callingapis(selectDate, merchantId, terminalId);
}

$('input[name="dashboarddatetimes"]').on('apply.daterangepicker', function(ev, picker) {
    getSelectedValues();
});

$('#merchantId').on('change', function() {
    var merchantId = $(this).val();
    
    // Enable/disable terminal dropdown based on merchant selection
    $('#terminalId').prop('disabled', !merchantId);

    // Fetch terminals if merchant is selected
    if (merchantId) {
        $.ajax({
            url: '/appxpaydashboard/get-terminals', // URL to fetch terminals
            type: 'GET',
            data: { merchantId: merchantId },
            success: function(terminals) {
                console.log(terminals)
                var terminalDropdown = $('#terminalId');
                terminalDropdown.empty(); // Clear existing options
                terminalDropdown.append('<option value="0" selected>Select a Terminal</option>');
                
                // Populate terminal dropdown with fetched terminals
                terminals.forEach(function(terminal) {
                    terminalDropdown.append('<option value="' + terminal.terminal_id + '">' + terminal.terminal_id
                    + '</option>'); // Assuming terminal has 'name'
                });
            }
        });
    } else {
        // If no merchant is selected, reset the terminal dropdown and show all data
        $('#terminalId').empty().append('<option value="0" selected>Select a Website</option>').prop('disabled', true);
        callingapis(date, null, 0); // Fetch all data
    }

    // Call API to update transaction stats for the selected merchant (and default terminal if any)
    getSelectedValues();
});

$('#terminalId').on('change', function() {
    getSelectedValues();
});

function callingapis(date, merchantId, terminalId) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        dataType: "json",
        data: {
            'date': date,
            'merchantId': merchantId,
            'terminalId': terminalId == null ? 0 : terminalId // Include terminalId
        },
        url: '/appxpaydashboard/ajax',
        success: function(data) {
            var statisticsData = data.transactionStats;

            // Update statistics on the dashboard
            $('#total_collection_amount').html(statisticsData.total_amount_of_successfull_transaction || 0);
            $('#totalTransaction').html(statisticsData.total_transactions_count || 0);
            $('#successfulTransaction').html(statisticsData.total_successfull_transactions || 0);
            $('#ht_failed_transaction').html(statisticsData.failed_transactions || 0);
            $('#ht_pending_transaction').html(statisticsData.pending_transactions || 0);
            $('#cancelledTransaction').html(statisticsData.cancelled_transactions || 0);
            $('#gtv').html(statisticsData.gtv);
            $('#refund').html(statisticsData.refund);
            $('#chargeback').html(statisticsData.chargeback);
        }
    });
}

// Initialize the login activities table
$('#loginActivitiesTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: "{{ route('login.activities') }}", // Fetch data from the route
        type: 'GET'
    },
    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false }, // Auto-increment index
        { data: 'username', name: 'username' }, // Matching 'log_employee' username
        { data: 'log_ipaddress', name: 'log_ipaddress' }, // Matching 'log_ipaddress'
        { data: 'log_device', name: 'log_device' },
        { data: 'log_os', name: 'log_os' },
        { data: 'log_time', name: 'log_time' } // Matching 'log_time'
    ],
    order: [[5, 'desc']] // Sort by log_time by default
});

});


</script>
@endsection