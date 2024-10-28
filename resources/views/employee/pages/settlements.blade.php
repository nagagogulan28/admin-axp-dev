@extends('layouts.employeecontent')
@section('employeecontent')
    <div class="settlements-pages">
        <p class="title-common">Settlements</p>
        
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
                            <table id="listofsettelements" class="table dataTable">
                                <thead>
                                    <tr>
                                        <td>S.no</td>
                                        <td>Customer Name</td>
                                        <td>Settlement ID</td>
                                        <td>Export Report</td>
                                        <td>Time</td>
                                        <td>Trnx_count</td>
                                        <td>Amount</td>
                                        <td>Fee</td>
                                        <td>GST</td>
                                        <td>Net_settle_amount</td>
                                        <td>Created Time</td>
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

    <!-- Custom Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirm Action</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to mark this as paid?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmButton">OK</button>
                </div>
            </div>
        </div>
    </div>
     <!-- Modal for Viewing Attachment -->
<div class="modal fade" id="viewAttachmentModal" tabindex="-1" role="dialog" aria-labelledby="viewAttachmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAttachmentModalLabel">View Attachment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe id="receipt_src" src="" width="100%" height="500px"></iframe>
            </div>
        </div>
    </div>
</div>

    <script>
        $(document).ready(function() {
            // Initialize date range picker
            $('input[name="datetimes"]').daterangepicker({
                opens: 'left',
                autoUpdateInput: false,
                timePicker: true,
                timePicker24Hour: true,
                locale: {
                    format: 'MM/DD/YYYY HH:mm:ss',
                    cancelLabel: 'Clear'
                }
            });

            $('input[name="datetimes"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY HH:mm:ss') + ' - ' + picker.endDate.format('MM/DD/YYYY HH:mm:ss'));
                listofsettelements.ajax.reload();
            });

            $('input[name="datetimes"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                listofsettelements.ajax.reload();
            });

            // Initialize DataTable
            var listofsettelements = $('#listofsettelements').DataTable({
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
        url: '/settlement/getoverallsettlementList',
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: function (d) {
            d.merchant_id = $('#businesstypeselect').val();
            d.status = $('#categorynameselect').val();
            d.datetimes = $('input[name="datetimes"]').val();
        }
    },
    columns: [
        {
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            orderable: false,
            searchable: false
        },
        {
            data: 'merchant_name',
            name: 'merchant_name',
        },
        {
            data: 'settlement_id',
            name: 'settlement_id'
        },
        { data: 'export_report', name: 'export_report', render: function(data, type, row) {
                        return '<a href="/settlement/downloadExcel/' + row.id + '" class="btn btn-primary">Download Excel</a>';
                    }},
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
            searchable: false,
            
        }
    ]
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
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                listofsettelements.ajax.reload();
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
   
    if(doc_id !="" && doc_id != null){
    $.ajax({
        url: '/settlement/getattachreceipt/'+doc_id,
        type: 'GET',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            if (response.success==false) {
                listofsettelements.ajax.reload();
                toastr.error(response.message);
                
            } else {
                
                console.log('respone : ',response)
                res_arr = JSON.parse(response);
                console.log('res_arr : ',res_arr)
                $('#viewAttachmentModal').modal('show');
                $('#receipt_src').attr('src',res_arr.img_link);
            }
        },
        error: function(xhr, status, error) {
            toastr.error('Error occurred: ' + xhr.responseText);
        }
    });
}else {
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
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: { id: settlementIdToMarkAsPaid },
        success: function(response) {
            if (response.success==false) {
                listofsettelements.ajax.reload();
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
                listofsettelements.ajax.reload();
            });

            $('.dataTable').wrap('<div class="dataTables_scrollss"></div>');
        });
    </script>
@endsection
