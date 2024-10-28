@extends('layouts.employeecontent')
@section('employeecontent')
<div class="settlements-pages">

    <div class="row row-new justify-content-end mb-5">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="mb-3 datetimes-width position-relative">
                <input type="text" name="datetimes" class="form-control" style="background-color: #fff !important;" />
            </div>
        </div>
    </div>

    <div class="row row-new justify-content-between">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-5">
            <div class="mb-3">
                <p class="title-common">Vendor List</p>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-7">
            <div class="align-items-center d-flex justify-content-end">
                <div class="mb-3 mr-3">
                    <a href="#">
                        <button type="button" class="bg-btn bg-white text-dark">
                            <img src="{{ asset('new/img/filter.svg')}}" alt="filter" class="mr-1 w-25">
                            Filter
                        </button>
                    </a>
                </div>
                <div class="mb-3">
                    <a href="#">
                        <button type="button" class="bg-btn" id="add-vendor" data-toggle="modal" data-target="#myModal">
                            <img src="{{ asset('new/img/add.svg')}}" alt="add" class="mr-1">
                            Add Vendor
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-seaction">
        <div class="tab-content">
            <div id="OpenedSettlement" class="tab-pane active">
                <div>
                    <div class="table-data">
                        <table class="table dataTable">
                            <thead>
                                <tr>
                                    <td>S.No</td>
                                    <td>Vendor Name</td>
                                    <td>Mobile Number</td>
                                    <td>Email ID</td>
                                    <td>No. of Merchants</td>
                                    <td>Actions</td>                                    
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Add Vendor</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="mb-4">
                    {{Form::label('name', 'Vendor Name', array('class' => 'control-label'))}}
                    {{ Form::text('name', $name = null, array('class' => 'form-control', 'placeholder' => '')) }}
                </div>
                <div class="mb-4">
                    {{Form::label('name', 'Mobile No.', array('class' => 'control-label'))}}
                    {{ Form::text('name', $name = null, array('class' => 'form-control', 'placeholder' => '')) }}
                </div>
                <div class="mb-4">
                    {{Form::label('name', 'Email ID', array('class' => 'control-label'))}}
                    {{ Form::text('name', $name = null, array('class' => 'form-control', 'placeholder' => '')) }}
                </div>   
                <button type="button" class="bg-btn mt-3 mb-3 w-100">                    
                    Add Vendor
                </button>            
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    var listofsettilements = new $('#listofsettilements').DataTable({
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
            },
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: "/settlement/getoverallsettlementList",
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        },
        columns: [{
                data: 'merchant_name',
                name: 'merchant_name'
            },
            {
                data: 'settlement_id',
                name: 'settlement_id'
            },
            {
                data: 'settlement_report',
                name: 'settlement_report'
            },
            {
                data: 'report_time',
                name: 'report_time'
            },
            {
                data: 'success_txn_count',
                name: 'success_txn_count'
            },
            {
                data: 'total_transaction_amount',
                name: 'total_transaction_amount'
            },
            {
                data: 'merchant_fee',
                name: 'merchant_fee'
            },
            {
                data: 'gst_amount',
                name: 'gst_amount'
            },
            {
                data: 'settlement_amount',
                name: 'settlement_amount'
            },
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
    });

    $('.dataTable').wrap('<div class="dataTables_scrollss"></div>');

});
</script>
@endsection