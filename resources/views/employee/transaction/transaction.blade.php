@extends('layouts.employeecontent')
@section('employeecontent')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


<div class="Transaction-pages">
    <p class="title-common">Transaction</p>

    <form action='{{ route("merchant.txnExport") }}' id="export_txn" method="POST">
        @csrf
        <div class="row row-new">
            
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                <div class="mb-3">                    
                    <select class="form-control bg-white" id="status-select" name="status">
                        <option value="" selected>Select a status</option>
                        <option value="1">TXN not initiated</option>
                        <option value="2" @if (isset($_GET['status']) && $_GET['status'] == 'success') selected @endif>Success</option>
                        <option value="0">Failed</option>
                        <option value="3">Tampered</option>
                        <option value="4">Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">                    
                    <select name="merchant" class="form-control bg-white" id="merchant-select">
                        <option value="" selected>Select a Company</option>
                        @if(is_array($merchants_list))
                        @foreach ($merchants_list as $merchant_id=>$merchant_name) 
                            <option value="{{ $merchant_id }}">{{ $merchant_name }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">                
                <div class="mb-3 datetimes-width position-relative w-auto">
                    <input type="text" id="datetimes" name="datetimes" class="form-control" style="background-color: #fff !important;" />
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-1">                
                <div class="mb-3 datetimes-width position-relative w-auto">
                    <button class="export-btn" id="export_action" type="submit" value="">
                        <img src="{{ asset('new/img/export.svg') }}" alt="export" style="margin-right: 5px;">
                        Export
                    </button>
                </div>
            </div>
        </div>
    </form>

<div class="tab-seaction">
    <div class="tab-content">
        <div id="OpenedSettlement" class="tab-pane active">
            <div>
                <div class="table-data">
                    <table id="transactionlistpayin" class="table dataTable">
                        {{-- <thead> --}}
                            {{-- <tr>
                                <th>S.No</th>
                                <th>Merchant Name</th>
                                <th>Customer Name</th>
                                <th>Transaction Amount</th>
                                <th>Mobile no</th>
                                <th>Email</th>
                                <th>Order Id</th>
                                <th>Appxpay TxnId</th>
                                <th>Aggregator TxnId</th>
                                <th>UTR No</th>
                                <th>Bank TxnId</th>
                                {{-- <th>Bank TxnId</th> --}}
                                {{-- <th>Transaction Status</th>
                                <th>Transaction Initiated at</th>
                                <th>Transaction Updated at</th> --}}
                            {{-- </tr> --}}
                        </thead>
                        <tbody>
                            <!-- Data will be populated here by DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    button.export-btn {
        background: #0F4A55;
        padding: 8px 25px;
        border-radius: 50px;
        color: #fff;
        font-weight: 600;
        border: 0px;
        /* width: 120px; */
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        height: 38px;
      }
</style>
<script>
    $(document).ready(function() {

        var dataTableTransaction = $('#transactionlistpayin').DataTable({
        columnDefs: [
            { "width": "100%", "targets": 13, "wrap": true } // Set wrap to true for column 1
        ],
        // add start 
        sDom: '<"top"f>rt<"bottom"lp><"clear">',
        pageLength: 10,      
        language: {
        search: '',
        searchPlaceholder: 'Search',
        sLengthMenu: "Page _MENU_",
        paginate: {
        "first":      "First",
        "last":       "Last",
        "next":       "Next",
        "previous":   "Prev"
        },
        },   
        // add end    
      order: [],
                processing: true,
                serverSide: true,                
                ajax: {
                    url: '{!! route('transactions.data.list') !!}',
                    data: function(d) {
                        // Add additional data parameters for filtering
                        d.date_range = $('input[name="datetimes"]').val();
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
                        data: 'company_name',
                        title: 'Company name',
                        name: 'name',
                        render: function(data, type, row) {
                    return data ? data : '---';
                }
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name',
                        title: 'Customer name',
                        render: function(data, type, row) {
                    return data ? data : '---';
                }
                    },
                    {
                        data: 'amount',
                        title: 'Amount',
                        name: 'amount'
                    },
                    {
                        data: 'commision_data',
                        name:'commision_data',
                        title : 'Commision details'
                    },
                    {
                        data: 'mobile',
                        title: 'Mobile',
                        name: 'mobile_no',
                        render: function(data, type, row) {
                       return data ? data : '---';
                   }
                    },
                    {
                        data: 'mail',
                        title: 'Mail',
                        name: 'email',
                        render: function(data, type, row) {
                    return data ? data : '---';
                   }
                    },

                    {
                        data: 'order_id',
                        title: 'Order Id',
                        name: 'order_id'
                    },
                    {
                        data: 'appxpay_txnid',
                        title: 'Appxpay TxnId',
                        name: 'appxpay_txnid'
                    },
                    {
                        data: 'aggregator_txnid',
                        title: 'Partner TxnId',
                        name: 'aggregator_txnid'
                    },
                    {
                        data: 'rrn_no',
                        title: 'UTR No',
                        name: 'rrn_no'
                    },
                    {
                        data: 'acqrbank_txnid',
                        title: 'Bank TxnId',
                        name: 'acqrbank_txnid'
                    },
                    {
                        data: 'status',
                        title: 'Txn Status',
                        name: 'status'
                    },
                    {
                        data: 'created_at',
                        title: 'Txn Initiated at',
                        name: 'created_at'
                    },
                    {
                        data: 'updated_at',
                        title: 'Txn Updated at',
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
            $("#datetimes").change(function() {
                dataTableTransaction.ajax.reload();
            });

        
    });
</script>
@endsection
