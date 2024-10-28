@extends('layouts.employeecontent')

@section('employeecontent')
<div class="settlements-pages">
    <p class="title-common">Payment Aggregators List</p>

    <div class="common-box">
        <div class="text-right mb-3">
            <button type="button" class="bg-btn" id="addPartnerButton">
                <img src="{{asset('new/img/add.svg')}}" alt="add" class="mr-1" />
                Add
            </button>
        </div>

        <div class="table-data">
            <table id="listofpaymentaggregators" class="table dataTable">
                <thead>
                    <tr>
                        <th>S.no</th>
                        <th>Code</th>
                        <th>Order Prefix</th>
                        <th>Name</th>
                        <th>Payin Service Fee (%)</th>
                        <th>Payout service Fee(%)</th>
                        <th>PayOut Status</th>
                        <th>PayIn Status</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Adding Payment Aggregator -->
<div class="modal fade" id="addPartnerModal" tabindex="-1" role="dialog" aria-labelledby="addPartnerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPartnerModalLabel">Add Payment Aggregator</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addPartnerForm">
                    @csrf
                    <div class="form-group">
                        <label for="partnerName">Aggregator Name</label>
                        <input type="text" class="form-control" id="partnerName" name="partnerName">
                    </div>
                    <div class="form-group">
                        <label for="order_prefix">Order Prefix</label>
                        <input type="text" class="form-control" id="order_prefix" name="order_prefix">
                    </div>
                    <div class="form-group">
                        <label for="service_fee">Payin Status</label>
                        <button type="button" class="btn btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off" id="pay_in_button">
                            <div class="handle"></div>
                        </button>
                        <input id="pay_in_input" name="pay_in" type="hidden" value="0">
                    </div>
                    <div class="form-group">
                        <label for="service_fee">Payout Status</label>
                        <button type="button" class="btn btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off" id="pay_out_button">
                            <div class="handle"></div>
                        </button>
                        <input id="pay_out_input" name="pay_out" type="hidden" value="0">
                    </div>
                    <div class="form-group">
                        <label for="service_fee">Payin Service Fee</label>
                        <input type="text" class="form-control" id="service_fee" name="service_fee">
                    </div>
                    <div class="form-group">
                        <label for="payout_service_fee">Payout Service Fee</label>
                        <input type="text" class="form-control" id="payout_service_fee" name="payout_service_fee">
                    </div>
                    <div class="form-group">
                        {{Form::label('uat_url', 'UAT End-Point', array('class' => 'col-form-label'))}}
                        {{ Form::text('uat_url', null, array('class' => 'form-control', 'id' => 'uat_url')) }}
                    </div>
                    <div class="form-group">
                        {{Form::label('prod_url', 'Prod End-Point', array('class' => 'col-form-label'))}}
                        {{ Form::text('prod_url', null , array('class' => 'form-control', 'id' => 'prod_url')) }}
                    </div>
                    <div class="text-center">
                        <button type="submit" class="bg-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var listofpaymentaggregators = $('#listofpaymentaggregators').DataTable({
            sDom: '<"top"f>rt<"bottom"lp><"clear">',
            pageLength: 10,
            processing: true,
            serverSide: true,
            ajax: '{{ route("partners.list") }}', // Adjusted route name if needed
            type: 'GET',
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'code',
                    name: 'code'
                },
                {
                    data: 'order_prefix',
                    name: 'order_prefix'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'total_service_fee',
                    name: 'total_service_fee'
                },
                {
                    data: 'payout_service_fee',
                    name: 'payout_service_fee'
                },
                {
                    data: 'payout_status',
                    name: 'payout_status',
                    render: function(data, type, row) {
                        return data == 1 ?
                            '<span class="badge badge-success">Active</span>' :
                            '<span class="badge badge-danger">Inactive</span>';
                    }
                },
                {
                    data: 'payin_status',
                    name: 'payin_status',
                    render: function(data, type, row) {
                        return data == 1 ?
                            '<span class="badge badge-success">Active</span>' :
                            '<span class="badge badge-danger">Inactive</span>';
                    }
                },
                {
                    data: 'active',
                    name: 'active',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },

            ],
            language: {
                search: '',
                searchPlaceholder: 'Search',
                sLengthMenu: 'Page _MENU_',
                paginate: {
                    first: 'First',
                    last: 'Last',
                    next: 'Next',
                    previous: 'Prev'
                },
            },
        });

        // Show the "Add Payment Aggregator" modal when the "ADD" button is clicked
        $('#addPartnerButton').click(function() {
            $('#addPartnerModal').modal('show');
        });


        // Handle form submission
        $('#addPartnerForm').submit(function(event) {
            event.preventDefault();

            var partnerName = $('#partnerName').val();
            var order_prefix = $('#order_prefix').val();
            var serviceFee = $('#service_fee').val();
            var pay_in_input = $('#pay_in_input').val();
            var pay_out_input = $('#pay_out_input').val();
            var payout_service_fee = $('#payout_service_fee').val();
            var uat_end_point = $('#uat_url').val();
            var prod_end_point = $('#prod_url').val();
            // Perform AJAX request to submit the form
            $.ajax({
                url: '{{ route("partner.add") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    partnerName: partnerName,
                    order_prefix: order_prefix,
                    serviceFee: serviceFee,
                    pay_in_input: pay_in_input,
                    pay_out_input: pay_out_input,
                    payout_service_fee: payout_service_fee,
                    uat_end_point: uat_end_point,
                    prod_end_point: prod_end_point
                },
                success: function(response) {
                    if (response.success) {
                        $('#addPartnerModal').modal('hide');
                        toastr.success(response.message);
                        listofpaymentaggregators.ajax.reload();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Error occurred: ' + xhr.responseText);
                }
            });
        });

        // Handle the click event for status change buttons
        $('#listofpaymentaggregators').on('click', '.change-status', function() {
            var id = $(this).data('id');
            var status = $(this).data('status');

            $.ajax({
                url: '{{ route("partner.changeStatus") }}', // Create this route in web.php and controller
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: id,
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        listofpaymentaggregators.ajax.reload();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Error occurred: ' + xhr.responseText);
                }
            });
        });

        function toggleButton(button, hiddenInput) {
            console.log("Testrrrrrr");
            if (button.attr('aria-pressed') === 'false') {
                hiddenInput.val('1');
            } else {
                hiddenInput.val('0');
            }
        }
        $('#pay_in_button').click(function() {
            let hiddenInput = $('#pay_in_input');
            toggleButton($(this), hiddenInput);
        });

        $('#pay_out_button').click(function() {
            let hiddenInput = $('#pay_out_input');
            toggleButton($(this), hiddenInput);
        });

    });

    function handleButtonClick(id) {
        $('#addPartnerModal').modal('show');
        var url = '{{ route("partner.update.ajax", ":id") }}';
        url = url.replace(':id', id);
        if (id != '' && id != undefined && id != null) {
            //alert("sadfasdfasdf");
            //$("#divLoading").addClass("show");
            $.ajax({
                url: url,
                type: 'GET',
                processData: false,
                contentType: false,
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },
                success: function(response) {
                    if (response.status == 'success') {
                        console.log(response.data);
                        // $("#divLoading").removeClass("show");
                        // $(".modal-backdrop").remove();
                        $('input[name="partnerName"]').val(response.data.name);
                        $('input[name="order_prefix"]').val(response.data.order_prefix);
                        $('input[name="service_fee"]').val(response.data.total_service_fee);
                        $('input[name="payout_service_fee"]').val(response.data.payout_service_fee);
                        $('input[name="uat_url"]').val(response.data.uat_end_point);
                        $('input[name="prod_url"]').val(response.data.prod_end_point);
                        $('input[name="record_id"]').val(response.data.id);
                        var payinStatus = response.data.payin_status;
                        var payoutStatus = response.data.payout_status;
                        console.log(payoutStatus);
                        // Update the hidden input
                        $('#pay_in_input').val(payinStatus);
                        $('#pay_out_input').val(payoutStatus);
                        // // Update the button state
                        if (payinStatus == 1) {
                            $('#pay_in_button').addClass('active').attr('aria-pressed', 'true');
                        } else {
                            $('#pay_in_button').removeClass('active').attr('aria-pressed', 'false');
                        }
                        if (payoutStatus == 1) {
                            $('#pay_out_button').addClass('active').attr('aria-pressed', 'true');
                        } else {
                            $('#pay_out_button').removeClass('active').attr('aria-pressed', 'false');
                        }
                    } else {
                        toastr.error(response.message, 'Error');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status === 500 && jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                        // Loop through the validation errors and display each one
                        $.each(jqXHR.responseJSON.errors, function(field, errors) {
                            errors.forEach(function(error) {
                                toastr.error(error);
                            });
                        });
                    } else {
                        toastr.error('An error occurred while submitting the request.');
                    }
                }
            });
        } else {
            toastr.error('Please fill out all required fields correctly.');
        }

    }
</script>
@endsection