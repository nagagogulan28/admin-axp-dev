@extends('layouts.employeecontent')
@section('employeecontent')
<style>
    @media (max-width: 1090px){
        .src-new form {
            position: relative;
            z-index: 99;
            left: 0px;
        }
        .row.excel-pad {
            padding-top: 6rem;
        }
    }
</style>
    <div class="row">
        <div class="col-sm-12 padding-20">
            <div class="panel panel-default position-relative">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        @if (count($sublinks) > 0)
                            @foreach ($sublinks as $index => $value)
                                @if ($index == 0)
                                    <li class="active"><a data-toggle="tab" class="show-pointer"
                                            data-target="#{{ str_replace(' ', '-', strtolower($value->link_name)) }}">{{ $value->link_name }}</a>
                                    </li>
                                @else
                                    <li><a data-toggle="tab" class="show-pointer"
                                            data-target="#{{ str_replace(' ', '-', strtolower($value->link_name)) }}">{{ $value->link_name }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @else
                            <li class="active"><a data-toggle="tab" class="show-pointer"
                                    data-target="#{{ str_replace(' ', '-', strtolower($sublink_name)) }}">{{ $sublink_name }}</a>
                            </li>
                            <li><a data-toggle="tab" class="show-pointer" data-target="#NoOfTransactions">No Of
                                    Transactions</a></li>
                            <li><a data-toggle="tab" class="show-pointer" data-target="#transaction-volume">Total
                                    Transactions &#8377; Amount</a></li>
                        @endif
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        @if (count($sublinks) > 0)
                            @foreach ($sublinks as $index => $value)
                                @if ($index == 0)
                                    <div id="{{ str_replace(' ', '-', strtolower($value->link_name)) }}"
                                        class="tab-pane fade in active">

                                    </div>
                                @else
                                    <div id="{{ str_replace(' ', '-', strtolower($value->link_name)) }}"
                                        class="tab-pane fade">

                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="src src-new">
                                <form id="transaction-form">
                                    <input class="form-control" id="mer_trans_date_range" name="mer_trans_date_range"
                                        placeholder="MM/DD/YYYY" type="text" value="">
                                    <input type="hidden" name="trans_from_date" id="trans_from_date_val" value="{{ session()->get('fromdate') }}">
                                    <input type="hidden" name="trans_to_date" id="trans_to_date_val" value="{{ session()->get('todate') }}">
                                    <input type="hidden" name="perpage" value="10">
                                    <input type="hidden" name="transaction_page" value="transactions">
                                    <div class="input-group-append">
                                        <i class="fa fa-calendar" id="datepicker-icon"></i>
                                    </div>

                                    {{ csrf_field() }}
                                </form>
                            </div>
                            <div id="{{ str_replace(' ', '-', strtolower($sublink_name)) }}"
                                class="tab-pane fade active in">
                                <div class="row">
                                    <div class="col-sm-12 p-0">
                                        <div class="row excel-pad margin-bottom-lg">
                                            <div class="col-sm-12 p-0">
                                                <!-- <a href="{{ route('add-new-settlement') }}" class="btn btn-primary btn-sm pull-right margin-right-md">New Settlement</a>
                                                     <a href="javascript:void(0)" class="btn btn-primary btn-sm pull-right margin-right-md" onclick="bulkSettlement();">Bulk Settlement</a> -->

                                                <form id="transaction-download-form"
                                                    action="{{ route('download-transactiondata') }}" method="POST"
                                                    role="form">
                                                    <input type="hidden" name="trans_from_date" id="input_trans_from_date"
                                                        class="form-control" value="{{ session()->get('fromdate') }}">
                                                    <input type="hidden" name="trans_to_date" id="input_trans_to_date"
                                                        class="form-control" value="{{ session()->get('todate') }}">
                                                    <input type="hidden" name="selected_mode" id="selected_mode"
                                                        value="">
                                                    <input type="hidden" class="form-control" name="selected_status" id="selected_status"
                                                        value="">
                                                    <input type="hidden" class="form-control" name="selected_merchant" id="selected_merchant"
                                                        value="">
                                                    <input type="hidden" class="form-control" name="selected_date_ranges"
                                                        id="selected_date_ranges" value="">
                                                    {{ csrf_field() }}                                                    
                                                    <button id="excel_btn" type="submit" class="btn mt-5 mb-5" style="padding: 5px 10px;;font-weight: bolder;background-color: #175c35;color: white; border-radius: 30px;">Download Excel&nbsp;&nbsp;<i class="fa fa-file-excel-o" style="font-size:20px"></i></button>
                                                </form>
                                                <form id="mode-form" class="row row-new" style="margin-top: 2rem !important;">
                                                    {{ csrf_field() }}

                                                    <div class="col-md-3 mb-1" style="display: none">
                                                        <label for="status-select">Mode</label>
                                                        <select class="form-control" id="mode-select" name="mode">
                                                            <option value="live">Live</option>
                                                            <option value="test">Test</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3 mb-1">
                                                        <label for="status-select">Transaction Status</label>
                                                        <select class="form-control" id="status-select" name="status">
                                                            <option value="" selected>Select a status</option>
                                                            <option value="1">TXN not initiated</option>
                                                            <option value="2" <?php if (isset($_GET['status']) && $_GET['status'] == 'success') {
                                                                echo 'selected';
                                                            } ?>>Success</option>
                                                            <option value="0">Failed</option>
                                                            <option value="3">Tampered</option>
                                                            <option value="4">Cancelled</option>
                                                            
                                                        </select>
                                                    </div>                                                    
                                                    <div class="col-md-3 mb-1">
                                                        <label for="merchant-select">Merchant Name</label>
                                                        <select name="merchant_name" class="form-control" id="merchant-select">
                                                            <option value="" selected>Select a Merchant</option>
                                                            @foreach ($merchantname as $merchant)
                                                                <option value="{{ $merchant->id }}">{{ $merchant->company_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                    </div>                                                   

                                                </form>
                                                {{-- <a href="javascript:void(0)" class="btn btn-primary btn-sm pull-right margin-right-md" id="call-adjustment-modal">Bulk Adjustment</a> --}}
                                            </div>
                                        </div>
                                        <div id="paginate_alltransactions">
                                            <table class="table-bordered" id="transactionlistpayin"
                                                style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Sno</th>
                                                        <th>Merchant Name</th>
                                                        <th>Customer Name</th>
                                                        <th>Transaction Amount</th>
                                                        <th>Mobile No</th>
                                                        <th>Email</th>
                                            
                                                        <th>Order Id</th>
                                                        <th>AXP Transaction Id</th>
                                                        <th>Aggregator Txn Id</th>
                                                        {{-- <th>RRN No</th> --}}
                                                        {{-- <th>Bank Transaction Id</th> --}}
                                                        <th>Transaction Status</th>
                                                        <th>Transaction Initiated at</th>
                                                        <th>Transaction Updated at</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="NoOfTransactions" class="tab-pane fade ">
                                <div class="row">
                                    <div class="col-sm-12 p-0">
                                        <form id="notransationmode-form" class="row row-new excel-pad">
                                            {{ csrf_field() }}                                           
                                            <div class="col-md-3 mb-1">
                                                <label for="status-select">Mode</label>
                                                <select class="form-control" id="mode-select" name="mode_select">
                                                    <option value="live">Live</option>
                                                    <option value="test">Test</option>
                                                </select>
                                            </div>
                                        </form>
                                        <div id="paginate_nooftransactions">
                                            <table class="table-bordered" id="nooftransactionlistpayin"
                                                style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Sno</th>
                                                        <th>Merchant Name</th>
                                                        <th>Email</th>
                                                        <th>Mobile No</th>
                                                        <th>No Of Transactions</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="transaction-volume" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-sm-12 p-0">
                                        <form id="notransationamountmode-form" class="row row-new excel-pad">
                                            {{ csrf_field() }}                                           
                                            <div class="col-md-3 mb-1">
                                                <label for="status-select">Mode</label>
                                                <select class="form-control" id="mode-select" name="modeselect">
                                                    <option value="live">Live</option>
                                                    <option value="test">Test</option>
                                                </select>
                                            </div>
                                        </form>
<!-- task  -->

                                        <div id="paginate_transactionamounts">
                                            <table class="table-bordered" id="amounttransactionlistpayin"
                                                style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Sno</th>
                                                        <th>Merchant Name</th>
                                                        <th>Email</th>
                                                        <th>Mobile No</th>
                                                        <th>Transaction Amount</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function(e) {
            e.preventDefault();
            loadMerchantNoOfTransaction();
        });



        document.addEventListener("DOMContentLoaded", function(e) {
            //getAdjustmentDetails();
            getMerchantTransactionsByDate();
        });

        $(document).ready(function() {

            //    // Get the value of the 'status' parameter from the URL
            //     const urlParams = new URLSearchParams(window.location.search);
            //     const statusParam = urlParams.get('status');

            //     // If the 'status' parameter exists and its value is 'success', select the corresponding option in the dropdown
            //     if (statusParam && statusParam.toLowerCase() === 'success') {
            //         $('#status-select').val('2'); // '2' corresponds to 'Success' in the dropdown
            //     } 

            var selectedMode = $("#mode-select").val();
            // var selectedstatus = $("#status-select").val();

            const urlParams = new URLSearchParams(window.location.search);
            const statusParam = urlParams.get('status');
            // console.log('tesssssssss',statusParam);


            $('#mer_trans_date_range').daterangepicker();

            // Show the daterangepicker when the icon is clicked
            $("#datepicker-icon").click(function() {
                $('#mer_trans_date_range').data('daterangepicker').toggle();
            });
            // Call the function with the initially selected value
            // getMerchantTransactionsmode(selectedMode,statusParam);

            // Bind the change event to the select element
            $("#mode-select, #status-select, #merchant-select, #mer_trans_date_range").change(function() {
                // Get the selected value
                // var selectedMode = $(this).val();
                var selectedMode = $("#mode-select").val();
                var selectedStatus = $("#status-select").val();
                var selectedMerchantName = $("#merchant-select").val();
                var selectedDateRange = $("#mer_trans_date_range").val(); // Get selected date range


                // Set the values to the hidden input fields
                $("#selected_mode").val(selectedMode);
                $("#selected_status").val(selectedStatus);
                $("#selected_merchant").val(selectedMerchantName);
                $("#selected_date_range").val(selectedDateRange);
                //console.log("select" + selectedMode + " " + selectedDateRange);
                //    console.log("select"+selectedMode)
                // Call the function with selected value
                // getMerchantTransactionsmode(selectedMode, selectedStatus,selectedMerchantName, selectedDateRange);
            });





            // no of transaction

            var nooftransactionselectedMode = $("#nooftransactionmode-select").val();

            // Call the function with the initially selected value
            // Bind the change event to the select element
            $("#nooftransactionmode-select").change(function() {
                // Get the selected value

                var nooftransactionselectedMode = $(this).val();

                // Call the function with selected value
                getMerchantnoofTransactionsmode(nooftransactionselectedMode);
            });


            var tranamount = $("#nooftransactionamountmodeselect").val();


            $("#nooftransactionamountmodeselect").change(function() {

                var tranamount = $(this).val();

                amountTransactionsmode(tranamount);
            });





        });


        function getMerchantTransactionsmode(selectedMode, selectedStatus, selectedMerchantName, selectedDateRange) {

            var formdata = $("#transaction-form").serializeArray();
            // Add selected mode to formdata
            formdata.push({
                name: "mode",
                value: selectedMode
            });
            formdata.push({
                name: "status",
                value: selectedStatus
            });
            formdata.push({
                name: "merchant_name",
                value: selectedMerchantName
            });
            formdata.push({
                name: "date_range",
                value: selectedDateRange
            });

            $.ajax({
                type: "POST",
                url: "/settlement/get-all-transactions",
                data: getJsonObject(formdata),
                dataType: "html",
                success: function(response) {
                    $("#paginate_alltransaction").html(response);
                }
            });
        }




        function getMerchantnoofTransactionsmode(nooftransactionselectedMode) {
            var formdata = $("#transaction-form").serializeArray();
            // Add selected mode to formdata
            formdata.push({
                name: "mode",
                value: nooftransactionselectedMode
            });
            // console.log(getJsonObject(formdata));
            $.ajax({
                type: "POST",
                url: "/merchant/no-of-transactions",
                data: getJsonObject(formdata),
                dataType: "html",
                success: function(response) {
                    console.log(response);
                    $("#paginate_nooftransaction").html(response);
                },
                error: function(xhr, status, error) {
                    console.log("AJAX Error:", error);
                }
            });

        }

        //amount

        function amountTransactionsmode(tranamount) {
            var formdata = $("#transaction-form").serializeArray();
            // Add selected mode to formdata
            formdata.push({
                name: "mode",
                value: tranamount
            });
            // console.log(getJsonObject(formdata));
            $.ajax({
                type: "POST",
                url: "/appxpay/merchant/transaction-amount",
                data: getJsonObject(formdata),
                dataType: "html",
                success: function(response) {
                    console.log(response);
                    $("#paginate_transactionamount").html(response);
                },
                error: function(xhr, status, error) {
                    console.log("AJAX Error:", error);
                }
            });

        }


        $(function() {
            var dataTableTransaction = $('#transactionlistpayin').DataTable({
                processing: true,
                serverSide: true,                
                ajax: {
                    url: '{!! route('transactions.data.list') !!}',
                    data: function(d) {
                        // Add additional data parameters for filtering
                        d.date_range = $('input[name="mer_trans_date_range"]').val();
                        d.status = $('select[name="status"]').val();
                        d.merchant_name = $('select[name="merchant_name"]').val();
                        d.mode = $('select[name="mode"]').val();

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
                        data: 'name',
                        name: 'name',
                        render: function(data, type, row) {
                    return data ? data : '---';
                }
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name',
                        render: function(data, type, row) {
                    return data ? data : '---';
                }
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'mobile_no',
                        name: 'mobile_no',
                        render: function(data, type, row) {
                    return data ? data : '---';
                }
                    },
                    {
                        data: 'email',
                        name: 'email',
                        render: function(data, type, row) {
                    return data ? data : '---';
                }
                    },

                    {
                        data: 'order_id',
                        name: 'order_id'
                    },
                    {
                        data: 'appxpay_txnid',
                        name: 'appxpay_txnid'
                    },
                    {
                        data: 'aggregator_txnid',
                        name: 'aggregator_txnid'
                    },
                    // {
                    //     data: 'rrn_no',
                    //     name: 'rrn_no'
                    // },
                    // {
                    //     data: 'acqrbank_txnid',
                    //     name: 'acqrbank_txnid'
                    // },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },

                ]
            });
            $('.dataTable').wrap('<div class="dataTables_scroll"></div>');

            // $('input[name="mer_trans_date_range"]').on('apply.daterangepicker', function (ev, picker) {

            //     dataTableTransaction.ajax.reload();
            //    });

            $('select[name="merchant_name"]').on('change', function() {
                // Reload the DataTable with new merchant name filter
                dataTableTransaction.ajax.reload();
            });

            $('select[name="mode"]').on('change', function() {
                // Reload the DataTable with new merchant name filter
                dataTableTransaction.ajax.reload();
            });

            $('select[name="status"]').on('change', function() {
                // Reload the DataTable with new merchant name filter
                dataTableTransaction.ajax.reload();
            });
            $("#mer_trans_date_range").change(function() {
                dataTableTransaction.ajax.reload();
            });


            var dataTablenoofTransaction = $('#nooftransactionlistpayin').DataTable({
                processing: true,
                serverSide: true,                
                ajax: {
                    url: '{!! route('nooftransactions.data.list') !!}',
                    data: function(d) {
                        // Add additional data parameters for filtering
                        d.date_range = $('input[name="mer_trans_date_range"]').val();
                        // d.status = $('select[name="status"]').val();
                        // d.merchant_name = $('select[name="merchant_name"]').val();
                        d.mode = $('select[name="mode_select"]').val();

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
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'mobile_no',
                        name: 'mobile_no'
                    },
                    {
                        data: 'merchant_count',
                        name: 'merchant_count'
                    },


                ]
            });
            $('.dataTable').wrap('<div class="dataTables_scroll"></div>');
            $('select[name="mode_select"]').on('change', function() {
                // Reload the DataTable with new merchant name filter
                dataTablenoofTransaction.ajax.reload();
            });


            var dataTableamount = $('#amounttransactionlistpayin').DataTable({
                processing: true,
                serverSide: true,                
                ajax: {
                    url: '{!! route('transactions.amount.list') !!}',
                    data: function(d) {
                        // Add additional data parameters for filtering
                        d.date_range = $('input[name="mer_trans_date_range"]').val();
                        // d.status = $('select[name="status"]').val();
                        // d.merchant_name = $('select[name="merchant_name"]').val();
                        d.mode = $('select[name="modeselect"]').val();

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
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'mobile_no',
                        name: 'mobile_no'
                    },
                    {
                        data: 'merchant_sum',
                        name: 'merchant_sum'
                    },


                ]
            });
            $('.dataTable').wrap('<div class="dataTables_scroll"></div>');

            $('select[name="modeselect"]').on('change', function() {
                // Reload the DataTable with new merchant name filter
                dataTableamount.ajax.reload();
            });

        });
    </script>

@endsection
