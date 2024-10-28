@extends('layouts.employeecontent')
@section('employeecontent')

@if(session()->has('status'))
@if(session('status') === 'success')
<div class="alert alert-success" role="alert">{{ session('message') }}</div>
@else
<div class="alert alert-danger" role="alert">{{ session('message') }}</div>
@endif
@endif

<div class="merchantslist-pages">
    <p class="title-common">Vendor's List</p>
    <div class="common-box">
        <div class="text-right mb-3">
            <a href="{{ route('vendor.onboarding.one') }}">
                <button type="button" class="bg-btn" id="add-roles-model">
                    <img src="{{asset('new/img/add.svg')}}" alt="add" class="mr-1" />
                    Add Vendor
                </button>
            </a>
        </div>


        <div class="table-data">
            <table id="listofnewmvendor" class="table dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Vendor ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>User Name</th>
                        <th>Bank Information</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <!-- <tbody>

                </tbody> -->
            </table>
        </div>
    </div>

</div>

<div class="modal fade" id="myModalbank">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="bank modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <div class="modal-body">
                <div class="mb-4">
                    {{ Form::label('bank_name', 'Bank Name', ['class' => 'control-label']) }}
                    {{ Form::text('bank_name', null, ['class' => 'form-control', 'placeholder' => '', 'readonly' => true]) }}
                </div>
                <div class="mb-4">
                    {{ Form::label('account_holder_name', 'Account Holder Name', ['class' => 'control-label']) }}
                    {{ Form::text('account_holder_name', null, ['class' => 'form-control', 'placeholder' => '', 'readonly' => true]) }}
                </div>
                <div class="mb-4">
                    {{ Form::label('account_number', 'Account Number', ['class' => 'control-label']) }}
                    {{ Form::text('account_number', null, ['class' => 'form-control', 'placeholder' => '', 'readonly' => true]) }}
                </div>
                <div class="mb-4">
                    {{ Form::label('branch', 'Branch', ['class' => 'control-label']) }}
                    {{ Form::text('branch', null, ['class' => 'form-control', 'placeholder' => '', 'readonly' => true]) }}
                </div>
                <div class="mb-4">
                    {{ Form::label('ifsc_code', 'IFSC Code', ['class' => 'control-label']) }}
                    {{ Form::text('ifsc_code', null, ['class' => 'form-control', 'placeholder' => '', 'readonly' => true]) }}
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {

        var roleListDatatable = new $('#listofnewmvendor').DataTable({
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
                url: "{{ route('vendor.complete.list') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [{
                    data: 'id',
                    title: 'S.No',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        console.log("testtts");
                        return meta.row + 1;
                    }
                },
                {
                    data: 'user_label_id',
                    name: 'user_label_id'
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
                    data: 'mobile',
                    name: 'mobile'
                },
                {
                    data: 'user_name',
                    name: 'user_name'
                },
                {
                    data: 'bank_details',
                    name: 'bank_details',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        console.log(data);
                        return data;
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
            ]
        });

        $('.dataTable').wrap('<div class="dataTables_scrollss"></div>');

        document.getElementById('verify_merchant').addEventListener('click', function() {
            Swal.fire({
                title: "Verify Merchant",

                showCancelButton: true,
                cancelButtonText: 'Cancel',
                showCloseButton: true,
                icon: "info"
            }).then((result) => {
                if (result.isConfirmed) {
                    var merchantId = document.getElementById('verify_merchant').dataset.id;
                    $.ajax({
                        url: '/merchant/details/Verify/' + merchantId,
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            $('#viewWebAppModal').modal('hide');

                            if (data.status === 'Success') {
                                Swal.fire({
                                    title: "Merchant Verified!",
                                    icon: "success"
                                });
                            } else {
                                toastr.error('Merchant verification failed!', 'Error');
                            }
                        },
                        error: function(xhr, status, error) {
                            toastr.error('Merchant verification failed!', 'Error');
                            console.error('Error:', xhr.responseText);
                        }
                    });
                }
            });
        });
    });

    

        // Verify ajax Code
        $(document).on('click', '#listofnewmvendor #openpopupbankdetails', function() {
            $('#divLoading').attr('class', 'show');
            var merchantId = $(this).data('id');
            $.ajax({
                url: '/merchant/details/' + merchantId,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    console.log('Data Received:', data);
                    $('.bank.modal-title').html(data.merchant.first_name+' '+data.merchant.last_name+"'s Bank Information");
                    $('#bank_name').val(data.merchant.business_detail.bank_name);
                    $('#account_holder_name').val(data.merchant.business_detail.account_holder_name);
                    $('#account_number').val(data.merchant.business_detail.bank_account_number);
                    $('#branch').val(data.merchant.business_detail.branch_name);
                    $('#ifsc_code').val(data.merchant.business_detail.bank_ifsc_code);
                    $('#myModalbank').modal("show");
                    $('#divLoading').removeAttr('class');
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching merchant details:', xhr.responseText);
                }
            });
        });

    // });
</script>

@endsection