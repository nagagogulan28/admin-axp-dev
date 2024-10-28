@extends('layouts.employeecontent')
@section('employeecontent')
<div class="settlements-pages">
    <p class="title-common">Service Fund List</p>

    <div class="row row-new">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <div class="mb-3">
                {{ Form::select('business_type_id', $businessNames, null, ['class' => 'form-control bg-white', 'id' => 'businesstypeselect']) }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <div class="mb-3">
                {{ Form::select('business_category_id', $status, null, ['class' => 'form-control bg-white', 'id' => 'categorynameselect']) }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <div class="mb-3 datetimes-width position-relative w-auto">
                <input type="text" name="datetimes" class="form-control" style="background-color: #fff !important;" />
            </div>
        </div>
    </div>
    <div class="tab-seaction">
        <div class="tab-content">
            <div id="OpenedSettlement" class="tab-pane active">
                <div>
                    <div class="table-data">
                        <table id="listofServiceFund" class="table dataTable">
                            <thead>
                                <tr>
                                    <th>Sno</th>
                                    <th>Merchant Nmae </th>
                                    <th>Deposit Bank Details</th>
                                    <th>Refrence Details</th>
                                    <th>Amount</th>
                                    <th>Remark</th>
                                    <th>Payment Status</th>
                                    <td>Action</td>
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


<!-- Add Request -->
<div class="modal" id="add-show-request">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <p class="title-common">Service Request Validation</p>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <form id="addBankAccountForm" action="" method="POST">
                @csrf <!-- CSRF Protection -->
                <div class="modal-body row row-new">
                    <div class="col-xl-6 col-sm-12 mb-4">

                        {{ Form::hidden('rowTopUp', '', ['id' => 'rowTopUpInput']) }}

                        {{ Form::label('deposit_bank', 'Deposit Bank', ['class' => 'control-label']) }}
                        {{ Form::text('deposit_bank', 'AXIS', ['class' => 'form-control', 'placeholder' => '', 'id' =>'deposit_bank', 'readonly' => 'readonly']) }}
                    </div>
                    <div class="col-xl-6 col-sm-12 mb-4">
                        {{ Form::label('request_amount', 'Request Amount', ['class' => 'control-label']) }}
                        {{ Form::text('request_amount', null, ['class' => 'form-control', 'placeholder' => '', 'id' =>'request_amount', 'readonly' => 'readonly']) }}
                    </div>
                    <div class="col-xl-6 col-sm-12 mb-4">
                        {{ Form::label('payment_mode', 'Payment Mode', ['class' => 'control-label']) }}
                        {{ Form::text('payment_mode', 'IMPS', ['class' => 'form-control', 'placeholder' => '', 'id' =>'payment_mode', 'readonly' => 'readonly']) }}
                    </div>
                    <div class="col-xl-6 col-sm-12 mb-4">
                        {{ Form::label('refrence_no', 'Refrence No.', ['class' => 'control-label']) }}
                        {{ Form::text('refrence_no', null, ['class' => 'form-control', 'placeholder' => '', 'readonly' => 'readonly']) }}
                    </div>
                    <div class="col-xl-12 col-sm-12 mb-4">
                        {{ Form::label('req_slip', 'Pay Slip ', ['class' => 'control-label']) }}
                        <a id="uploadedReceipt" href="https://appxpaydev.s3.ap-south-1.amazonaws.com/onboarding/appxpay_43/1EUeSXOkY38dmTqJUjdDqK0sCYe6JNDUyJ4BTKUU.png" class="view-align" target="_blank">View</a>
                    </div>
                    <div class="col-xl-12 col-sm-12 mb-4">
                        {{ Form::label('req_remark', 'Remark', ['class' => 'control-label']) }}
                        {{ Form::textarea('req_remark', null, ['class' => 'form-control', 'placeholder' => '','id' => 'req_remark' ,'readonly' => 'readonly', 'rows' => 2]) }}
                    </div>
                    <div class="col-xl-12 col-sm-12 mb-4">
                        {{ Form::label('req_remark', 'Appxpay Remark', ['class' => 'control-label']) }}
                        {{ Form::textarea('validate_remark', null, ['class' => 'form-control','id' => 'validate_remark', 'placeholder' => '', 'rows' => 4]) }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary reqclose" id="close-button">Close</button>
                    <button type="button" class="btn btn-primary mr-2 mr-2 approveDecline" data-type="decline">Decline</button>
                    <button type="button" class="btn btn-primary mr-2 mr-2 approveDecline" data-type="approve">Approve</button>
                </div>
            </form>

      

    </div>
  </div>
</div>
<!-- Add Request -->

<script>
    $(document).ready(function() {
        function validateField(field) {
            if (field.val().trim() === '' || field.val() === '0') {
                field.addClass('is-invalid');
                field.siblings('.invalid-feedback').remove();
                field.after('<div class="invalid-feedback">This field is required.</div>');
                return false;
            } else {
                field.removeClass('is-invalid');
                field.siblings('.invalid-feedback').remove();
                return true;
            }
        }
        $('#validate_remark').on('input', function() {
            validateField($(this));
        });
        $("#listofServiceFund").on('click', '.add-amt-request', function() {
            console.log("Last");
            var rowId = $(this).data('id');
            $.ajax({
                url: '/merchant/service/request/' + rowId,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    console.log("data", data.data);
                    if (data.status == 'success') {
                        $('#request_amount').val(data.data.amount);
                        $('#refrence_no').val(data.data.rrn_no);
                        $('#req_remark').val(data.data.remark);
                        $('#rowTopUpInput').val(data.data.id);
                        $('#uploadedReceipt').attr('href', data.data.receive_amt_receipt.base_url + '/' + data.data.receive_amt_receipt.document_path);
                        console.log("Log -->", data.data);
                        $("#add-show-request").show();
                    } else {
                        $('#request_amount').val('');
                        $('#refrence_no').val('');
                        $('#req_remark').val('');
                        $('#rowTopUpInput').val('');
                        $("#add-show-request").hide();
                    }

                },
                error: function(xhr, status, error) {
                    console.error('Error fetching merchant details:', xhr.responseText);
                }
            });
        });

        // Hide the modal when the "Close" button is clicked
        $(".reqclose").click(function() {
            $("#add-show-request").hide();
        });
        // Initialize date range picker
        $('input[name="datetimes"]').daterangepicker({
            opens: 'left',
            autoUpdateInput: true,
            timePicker: true,
            timePicker24Hour: true,
            locale: {
                format: 'MM/DD/YYYY HH:mm:ss',
                cancelLabel: 'Clear'
            }
        });

        $('input[name="datetimes"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY HH:mm:ss') + ' - ' + picker.endDate.format('MM/DD/YYYY HH:mm:ss'));
            listofServiceFund.ajax.reload();
        });

        $('input[name="datetimes"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            listofServiceFund.ajax.reload();
        });

        // Initialize DataTable
        var listofServiceFund = $('#listofServiceFund').DataTable({
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
            destroy: true,
            ajax: {
                url: "{{ route('add.servicefound.list') }}",
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function(d) {
                    d.merchant_id = $('#businesstypeselect').val();
                    d.status = $('#categorynameselect').val();
                    d.datetimes = $('input[name="datetimes"]').val();
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
                    data: null,
                    title: 'Company Name',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return row.business_detail.company_name;
                    }
                },
                {
                    data: null, // Replace with actual data source for this column
                    title: 'Deposit Bank Details',
                    render: function(data, type, row) {
                        return 'Bank Name : ' + row.apx_bank.bank_name.name + ' <br> Account No : ' + row.apx_bank.account_no + '<br> Branch : ' + row.apx_bank.branch;
                    }
                },
                {
                    data: null, // Replace with actual data source for this column
                    title: 'Refrence Details',
                    render: function(data, type, row) {
                        return 'Ref No : ' + row.rrn_no + ' <br> Paydate :' + row.created_at + '<br> Paymode : ' + row.mode_txn.name;
                    }
                },
                {
                    data: 'amount', // Replace with actual data source for this column
                    title: 'Amount'
                },
                {
                    data: 'remark', // Replace with actual data source for this column
                    title: 'Remark'
                },
                {
                    data: null, // Replace with actual data source for this column
                    title: 'Payment Status',
                    render: function(data, type, row) {
                        // Check the status and return the desired result
                        if (row.status == 1) {
                            return '<span class="text-success"><strong>Approved</strong></span>';
                        } else if (row.status == 0) {
                            return '<span class="text-warning"><strong>Pending</strong></span>';
                        } else if (row.status == 2) {
                            return '<span class="text-danger"><strong>Declined</strong></span>';
                        } else {
                            return row.status;
                        }

                    }
                },
                {
                    data: 'action', // Replace with actual data source for this column
                    title: 'Action'
                },

                // Add more columns as needed
            ]
        });

        function updateServiceFund(type) {
            var alertMsg = '';
            if (type == "decline") {
                alertMsg = 'Decline It!!';
                $txt = 'You have declined this action.';
            } else {
                alertMsg = 'Approve It!!';
                $txt = 'You have approved this action!';
            }
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
                title: "Are you sure?",
                text: $txt,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: alertMsg,
                cancelButtonText: "Cancel!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    var checkId = $('#rowTopUpInput').val();
                    if (checkId && $.isNumeric(checkId)) {
                        $.ajax({
                            url: '/merchant/topup/update/' + checkId,
                            method: 'GET',
                            data: {
                                requestType: type,
                                adminRemark: $('#validate_remark').val()
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                if (data.status == 'success') {
                                    toastr.success(data.message);
                                    listofServiceFund.ajax.reload();
                                    $("#add-show-request").hide();
                                } else {
                                    toastr.error(data.message);
                                    $("#add-show-request").show();
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error fetching merchant details:', xhr.responseText);
                            }
                        });
                    } else {
                        toastr.error('Recourd ID is Not Available!');
                    }
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire({
                        title: "Cancelled",
                        text: "Everything is safe :)",
                        icon: "error"
                    });
                }
            });
        }

        $(".approveDecline").on('click', function() {
            var type = $(this).data('type');
            var isValid = true;
            var f1 = validateField($('#validate_remark'));
            if (!f1) {
                isValid = false;
            }
            if (isValid) {
                updateServiceFund(type);
            }
        });

        // Attach receipt
        $('body').on('click', '.attach_receipt', function() {
            var settlementId = $(this).data('id');
            $('.upload_receipt[data-id="' + settlementId + '"]').click();
        });

        $('body').on('change', '.upload_receipt', function() {
            var file = $(this)[0].files[0];
            var settlementId = $(this).data('id');

            // Validate file type
            var validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if ($.inArray(file.type, validTypes) === -1) {
                toastr.error('Invalid file type. Only JPG, JPEG,  and PNG are allowed.');
                $(this).val(''); // Clear the input
                return;
            }


            // Validate file size (2MB = 2097152 bytes)
            var maxSize = 2 * 1024 * 1024;
            if (file.size > maxSize) {
                toastr.error('File size exceeds 2MB.');
                $(this).val(''); // Clear the input
                return;
            }
            var formData = new FormData();
            formData.append('receipt', file);
            formData.append('id', settlementId);

            $.ajax({
                url: '/settlement/attachReceipt',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        listofServiceFund.ajax.reload();
                        toastr.success(response.message);
                        $(`#view_attach_${response.settle_record_id}`).show();
                        $('a.mark_as_paid[data-id="' + settlementId + '"]').prop('disabled', false); // Enable the "Mark as Paid" button                   
                        $('a.view_attachment[data-id="' + settlementId + '"]').data('url', response.document_path); // Update the view attachment URL

                    } else {
                        console.log(response.message);
                        toastr.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Error occurred: ' + xhr.responseText);
                }
            });
        });


        // View attachment
        $('body').on('click', '.view_attachment', function() {
            var doc_id = $(this).data('docid');

            if (doc_id != "" && doc_id != null) {
                $.ajax({
                    url: '/settlement/getattachreceipt/' + doc_id,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success == false) {
                            listofServiceFund.ajax.reload();
                            toastr.error(response.message);

                        } else {

                            console.log('respone : ', response)
                            res_arr = JSON.parse(response);
                            console.log('res_arr : ', res_arr)
                            $('#viewAttachmentModal').modal('show');
                            $('#receipt_src').attr('src', res_arr.img_link);
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Error occurred: ' + xhr.responseText);
                    }
                });
            } else {
                toastr.error("No attachment found. Please attach the receipt and try again.");
            }
        });


        // Mark as paid
        var settlementIdToMarkAsPaid;
        $('body').on('click', '.mark_as_paid', function(e) {
            e.preventDefault();
            settlementIdToMarkAsPaid = $(this).data('id');
            $('#confirmModal').modal('show');
        });

        $('#confirmButton').click(function() {
            $('#confirmModal').modal('hide');
            $.ajax({
                url: '/settlement/markAsPaid',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: settlementIdToMarkAsPaid
                },
                success: function(response) {
                    if (response.success == false) {
                        listofServiceFund.ajax.reload();
                        toastr.error(response.message);
                    } else {
                        toastr.success(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Error occurred: ' + xhr.responseText);
                }
            });
        });


        // Reload table when filters change
        $('#businesstypeselect, #categorynameselect, input[name="datetimes"]').change(function() {
            listofServiceFund.ajax.reload();
        });

        $('.dataTable').wrap('<div class="dataTables_scrollss"></div>');
    });
</script>
@endsection