@extends('layouts.employeecontent')
@section('employeecontent')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    #terminalId:disabled {
        background-color: #e9ecef !important;
    }

    #payment_status_dropdown,
    #merchantId,
    #terminalId {
        background-color: #fff !important;
    }
</style>

<div class="Transaction-pages">
    <p class="title-common">Payout Transaction List</p>

    <form action='{{ route("merchant.txnExport") }}' id="export_txn" method="POST">
        @csrf
        <div class="row row-new">
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3 datetimes-width position-relative w-auto">
                    <input type="text" id="datetimes" name="datetimes" class="form-control" style="background-color: #fff !important;" />
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3">
                <div class="mb-3">
                    {{ Form::select('payment_status_dropdown',
                        $status->prepend('Select a status', '0'),
                        null,
                        ['class' => 'form-control', 'id' => 'payment_status_dropdown'])
                    }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    {{ Form::select('merchant_companyname_dropdown',
                        collect($merchantsUsers)->pluck('company_name', 'id')->prepend('Select a company', ''), 
                        null,
                        ['class' => 'form-control', 'id' => 'merchantId'])
                    }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    <select name="terminalId" id="terminalId" class="form-control" disabled>
                        <option value="0" selected>Select a Terminal</option>
                    </select>
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
                        <table id="transactionlistpayout" class="table dataTable">


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

            var paymenttablelist = $("#transactionlistpayout").DataTable({

                sDom: '<"top"f>rt<"bottom"lp><"clear">',
                pageLength: 10,
                language: {
                    search: '',
                    searchPlaceholder: 'Search',
                    sLengthMenu: "Page _MENU_",
                    paginate: {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Prev"
                    }
                },
                order: [],
                paging: true,
                searching: true,
                info: true,
                ajax: {
                    url: '{{ route("payout.admintranslist") }}',
                    type: "GET",
                    cache: true,
                    data: function(d) {
                        var dateRange = $('input[name="datetimes"]').val();
                        if (dateRange) {
                            var dates = dateRange.split(' - ');
                            var startDate = moment(dates[0].trim(), 'MM/DD/YYYY').startOf('day').format('MM/DD/YYYY 00:00:00');
                            var endDate = moment(dates[1].trim(), 'MM/DD/YYYY').endOf('day').format('MM/DD/YYYY 23:59:59');
                            d.start_date = startDate;
                            d.end_date = endDate;
                        } else {
                            console.log("today");
                            var today = moment().format('MM/DD/YYYY');
                            d.start_date = today + ' 00:00:00';
                            d.end_date = today + ' 23:59:59';
                        }
                        d.id = $('#merchantId').val();
                        d.terminalId = $('#terminalId').val();
                        d.status = $('select[name="payment_status_dropdown"]').val();
                    },
                    error: function(xhr, error, code) {
                        console.log("Error fetching data from server: ", error);
                    }
                },
                columns: [{
                        data: 'S.No',
                        title: 'S.No'
                    },
                    {
                        data: 'order_id',
                        title: 'OrderId'
                    },
                    {
                        data: 'utr',
                        title: 'Utr'
                    },
                    {
                        data: 'bene_id',
                        title: 'Beneficiary Id'
                    },
                    {
                        data: 'amount',
                        title: 'Amount'
                    },
                    {
                        data: 'service_fee',
                        title: 'Service Fee'
                    },
                    {
                        data: 'fee_details',
                        title: 'Fee Details'
                    },
                    {
                        data: 'is_recredited',
                        title: 'Claim Status'
                    },
                    {
                        data: 'ben_name',
                        title: 'Name'
                    },
                    {
                        data: 'transfer_mode',
                        title: 'Mode'
                    },
                    {
                        data: 'status',
                        title: 'Status',
                        render: function(data, type, row) {
                            console.log('status' + row);
                            return data;
                        }
                    },
                    {
                        data: 'ben_phone',
                        title: 'Phone'
                    },
                    {
                        data: 'created_at',
                        title: 'Created At',
                        render: function(data, type, row) {
                            return moment(data).format('DD-MM-YYYY');
                        }
                    },
                    {
                        data: 'updated_at',
                        title: 'Updated At',
                        render: function(data, type, row) {
                            return moment(data).format('DD-MM-YYYY');
                        }
                    }

                ]
            });

            $('select[name="merchant_companyname_dropdown"]').on('change', function() {
                var merchantId = $(this).val();

                $('#terminalId').prop('disabled', !merchantId);

                if (merchantId) {
                    $.ajax({
                        url: '/appxpaydashboard/get-terminals', // URL to fetch terminals
                        type: 'GET',
                        data: {
                            merchantId: merchantId
                        },
                        success: function(terminals) {
                            console.log(terminals)
                            var terminalDropdown = $('#terminalId');
                            terminalDropdown.empty(); // Clear existing options
                            terminalDropdown.append('<option value="0" selected>Select a Terminal</option>');
                            terminals.forEach(function(terminal) {
                                terminalDropdown.append('<option value="' + terminal.sid + '">' + terminal.terminal_id +
                                    '</option>');
                            });
                        }
                    });
                } else {
                    // If no merchant is selected, reset the terminal dropdown and show all data
                    $('#terminalId').empty().append('<option value="0" selected>Select a Website</option>').prop('disabled', true);
                    callingapis(date, null, 0); // Fetch all data
                }
                paymenttablelist.ajax.reload();

            });

            $('.dataTable').wrap('<div class="dataTables_scroll"></div>');

            $('input[name="datetimes"]').on('apply.daterangepicker', function(ev, picker) {
                paymenttablelist.ajax.reload();
            });

            $('select[name="terminalId"]').on('change', function() {
                paymenttablelist.ajax.reload();
            });

            $('select[name="payment_status_dropdown"]').on('change', function() {
                paymenttablelist.ajax.reload();
            });

        });
    </script>
    @endsection