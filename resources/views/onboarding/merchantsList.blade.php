@extends('layouts.employeecontent')
@section('employeecontent')

@if(session()->has('status'))
@if(session('status') === 'success')
<div class="alert alert-success" role="alert">{{ session('message') }}</div>
@else
<div class="alert alert-danger" role="alert">{{ session('message') }}</div>
@endif
@endif

@if($errors->any())
<div class="alert alert-danger" role="alert">
    {{ $errors->first('message') }}
</div>
@endif

<div class="merchantslist-pages">
    @if(isset($vendorUser))
    <p class="title-common">List of Merchants reffered by {{ $vendorUser->first_name }} {{$vendorUser->last_name }}</p>
    @else
    <p class="title-common">Merchant List</p>
    @endif
    <div class="common-box">

        @if(!isset($vendorUser))
        <div class="text-right mb-3">
            <a href="{{ route('onboarding.one') }}">
                <button type="button" class="bg-btn" id="add-roles-model">
                    <img src="{{asset('new/img/add.svg')}}" alt="add" class="mr-1" />
                    Add Merchants
                </button>
            </a>
        </div>
        @else
        <div class="text-right mb-3">
            <a href="/vendor/list/index">
                <button type="button" class="bg-btn" id="add-roles-model">
                    <!-- <img src="{{asset('new/img/add.svg')}}" alt="add" class="mr-1" /> -->
                    <i class="fa fa-angle-left mr-2" style="font-size:16px;color:#fff"></i>
                    Back to Vendor List
                </button>
            </a>
        </div>
        @endif


        <div class="table-data">
            <table id="listofnewmerchants" class="table dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Merchant ID</th>
                        <th>Name</th>
                        <th>Recharged Amount</th>
                        <th>Current Balance</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>User Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>

{{-- verify view code --}}
<!-- Merchant Details Modal -->
<div class="modal fade" id="viewWebAppModal" tabindex="-1" role="dialog" aria-labelledby="merchantDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="merchantDetailsModalLabel">Merchant Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div id="merchantDetailsContent">

                </div>
                <div class="d-flex justify-content-center mt-4">
                    <button type="button" id="verify_merchant" class="outline-btn bgg-btn" data-id="">Verify Merchant</button>
                </div>
            </div>
        </div>
    </div>
</div>




<script>
    $(document).ready(function() {

        var reqdata = {
            url: "{{ route('merchants.verify.list') }}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }

        @if(isset($vendorUser))
        reqdata.data = {
            vendorId: <?php echo $vendorUser->id ?>
        };
        @endif

        var merchantListDatatable = new $('#listofnewmerchants').DataTable({
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
            ajax: reqdata,
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
                    data: 'user_label_id',
                    name: 'user_label_id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'Recharged Amount',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        $blnc = row.service_wallet_balance != null ? row.service_wallet_balance.total_service_fund : 0.00;
                        return '<span style="color: green;font-weight: 600;">₹ '+$blnc+'</span>';
                    }
                },
                {
                    data: 'email',
                    name: 'Current Balance',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        $blnc = row.service_wallet_balance != null ? row.service_wallet_balance.current_balance : 0.00;
                        return '<span style="color: green;font-weight: 600;">₹ '+$blnc+'</span>';
                    }
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
                    data: 'action',
                    name: 'action'
                },
            ]
        });

        $('.dataTable').wrap('<div class="dataTables_scrollss"></div>');

        // Verify ajax Code
        $(document).on('click', '.view-webapp', function() {
            var merchantId = $(this).data('id');
            $.ajax({
                url: '/merchant/details/' + merchantId,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    console.log('AJAX Request Successful');
                    console.log('Data Received:', data);
                    var getDoc = '';
                    if (data.merchant.user_documents.length > 0) {
                        data.merchant.user_documents.forEach(function(document) {
                            getDoc = getDoc + '<div class="col-12 col-sm-6 col-md-6 col-lg-6">' + '<div class="col-12 col-sm-12 col-md-12 col-lg-12">' + '<p class="leftside-p">' + document.document_type
                                .document_name + '</p></div>' + '<div class="col-12 col-sm-12 col-md-12 col-lg-12"><a href="' + document.base_url +
                                '/' + document.document_path +
                                '" class="view-image" target="_blank" style="color: white; background-color: #007bff; padding: 3px 11px; border-radius: 5px">View</a></div></div>';


                        });
                    }
                    console.log("test", getDoc);
                    var detailsHtml = `
                <div>
                    <p class="merch-step-title">Personal Details</p>
                    <div class="row box-merch">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p class="leftside-p">First Name</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p>${data.merchant.first_name}</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p class="leftside-p">Email</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p>${data.merchant.personal_email}</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p class="leftside-p">User Name</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p>${data.merchant.user_name}</p></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p class="leftside-p">Last Name</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p>${data.merchant.last_name}</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p class="leftside-p">Mobile</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p>${data.merchant.mobile_no}</p></div>
                            </div>
                        </div>
                    </div>
                    
                    <p class="merch-step-title">Company info</p>
                    <div class="row box-merch">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p class="leftside-p">Business Type</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p>${data.businesstypes.type_name}</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p class="leftside-p">Business Sub Category</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p>${data.businessSubcategory.sub_category_name}</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p class="leftside-p">Bank Acc No</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p>${data.merchant.business_detail.bank_account_number}</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p class="leftside-p">Branch Name</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p>${data.merchant.business_detail.branch_name}</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p class="leftside-p">Monthly Expenditure</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p>${data.merchant.business_detail.monthly_expenditure.option_value}</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p class="leftside-p">Company Address</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p>${data.merchant.business_detail.company_address}</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p class="leftside-p">City</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p>${data.merchant.business_detail.city}</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p class="leftside-p">Country</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p>${data.merchant.business_detail.country}</p></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p class="leftside-p">Business Category</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p>${data.businessCategory.category_name}</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p class="leftside-p">Bank Name</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p>${data.merchant.business_detail.bank_name}</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p class="leftside-p">Bank IFSC Code</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p>${data.merchant.business_detail.bank_ifsc_code}</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p class="leftside-p">Account Holder Name</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p>${data.merchant.business_detail.account_holder_name}</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p class="leftside-p">Company Name</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p>${data.merchant.business_detail.company_name}</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p class="leftside-p">Pincode</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p>${data.merchant.business_detail.company_pincode}</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p class="leftside-p">State</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p>${data.listofstate.state_name}</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p class="leftside-p">Percentage Fees</p></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12"><p>${data.merchant.business_detail.percentage_fees}</p></div>
                            </div>
                        </div>
                    </div>
                    <p class="merch-step-title">Company info</p>
                    <div class="row box-merch">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-6"><p class="leftside-p">Pay-In</p></div>
                                     <div class="col-12 col-sm-12 col-md-12 col-lg-6"><p>${data.merchant.business_detail.pay_in === 1 ? 'Yes' : 'No'}</p></div>
                            
                                <div class="col-12 col-sm-12 col-md-12 col-lg-6"><p class="leftside-p">Pay-Out</p></div>
                                     <div class="col-12 col-sm-12 col-md-12 col-lg-6"><p>${data.merchant.business_detail.pay_out === 1 ? 'Yes' : 'No'}</p></div>
                                 </div>
                            </div>
                        </div>
                    </div>
                    <p class="merch-step-title">Business Info</p>
                </div>
                `;

                    detailsHtml = detailsHtml + '<div class="row">' + getDoc + '</div>';


                    $('#merchantDetailsContent').html(detailsHtml);
                    $('#merchantDetailsModal').modal('show');
                    $('#verify_merchant').attr('data-id', merchantId);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching merchant details:', xhr.responseText);
                }
            });
        });

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

                                merchantListDatatable.ajax.reload(null, false);
                                
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



    // });
</script>

@endsection