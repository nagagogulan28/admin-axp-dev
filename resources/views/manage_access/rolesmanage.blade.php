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
    <p class="title-common">List of Roles</p>
    <div class="common-box">
        <div id="apikeyssection" class="">

           
               
            <div class="text-right mb-3">                   
                <button type="button" class="bg-btn" id="add-roles-model" data-toggle="modal" data-target="#add-new-role">
                    <img src="{{asset('new/img/add.svg')}}" alt="add" class="mr-1" />
                    Add Role
                </button>
            </div>
            

            <div class="table-data">
            <table id="roles_datatable" class="table dataTable">
                    
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        
    </div>
</div>




<!-- App Name Modal -->
<div class="modal" id="add-new-role" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">                
                <h4 class="modal-title">Add Role</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="addRoleForm" action="{{ route('roles.store') }}" method="POST">
                @csrf <!-- CSRF Protection -->
                <div class="modal-body">
                    <div class="form-group">
                        <label for="clientId">Role Name</label>
                        <input type="text" name="role_name" class="form-control" id="rlenameUniqe">
                        <div id="roleNameError" class="text-danger"></div>
                    </div>
                    <div class="form-group">
                        <label for="role_status">Status</label>
                        @php
                        // Define two static values
                        $virtualaccounts = [
                        (object)['id' => 1, '0' => 'Static1', 'name' => 'Active'],
                        (object)['id' => 2, '1' => 'Static2', 'name' => 'Inactive']
                        ];
                        @endphp
                        <select class="select" name="status" id="role_status" required>
                            @foreach ($virtualaccounts as $virtual)
                            <option value="{{ $virtual->id }}">{{ auth()->id() }} {{ $virtual->name }}</option>
                            @endforeach
                        </select>
                        <div id="statusError" class="text-danger"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close-button" data-dismiss="modal">Close</button>
                    <button type="button" class="bg-btn" id="generateRole">Add Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.js"></script>



<script>
    jQuery.noConflict();
    jQuery(document).ready(function($) {

        jQuery('#close-button').click(function($) {
            // Hide the modal
            jQuery('#add-new-role').hide();
        });

        // Open modal when button is clicked
        $("#add-roles-model").click(function() {
            $("#add-new-role").show();
        });

        $('#generateRole').click(function(event) {
            console.log("test");
            event.preventDefault(); // Prevent default form submission
            // Get the values of the input fields
            var roleName = $('#rlenameUniqe').val();
            var status = $('#role_status').val();

            // Validation
            var errors = false;
            if (roleName.trim() === '') {
                $('#roleNameError').text('Role Name is required');
                errors = true;
            } else {
                $('#roleNameError').text('');
            }

            if (status.trim() === '') {
                $('#statusError').text('Status is required');
                errors = true;
            } else {
                $('#statusError').text('');
            }

            // If no errors, submit the form
            if (!errors) {
                $('#addRoleForm').submit();
            }
        });

        // Close modal when close button is clicked
        $(".close").click(function() {
            $("#myModal").hide();
        });

        var roleListDatatable = jQuery('#roles_datatable').DataTable({
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
                url: "{{ route('roles.permission.list') }}",
                type: 'GET', // or 'POST' depending on your server configuration
                data: function(d) {
                    d.referPermission_id = 'nbnbnbnb';
                }
            },
            ajax: "{{ route('roles.roleList') }}",
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
                    data: 'status',
                    title: 'status',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        console.log('row', row);
                        var status = data == 1 ? 'Active' : 'Deactive';
                        return '<span class="badge badge-info">' + status + '</span>';
                    }

                },
                {
                    data: 'created_by',
                    title: 'created_by',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return row.userdata.first_name + ' ' + row.userdata.last_name
                    }

                },
                {
                    data: 'action',
                    name: 'action'
                }
            ]
        });

    });
</script>

<!-- App Name Modal -->
<!-- <script>
    $(document).ready(function() {
    var dataTable = $('#api_key_datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "",
            dom: 'Bfrtip', // Add the buttons to the DOM
            buttons: [{
                    extend: 'excelHtml5',
                    text: 'Excel',
                    filename: function() {
                        var d = new Date();
                        return 'PayoutApiKey_' + d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate();
                    },
                    title: 'PayoutApiKey'
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    filename: function() {
                        var d = new Date();
                        return 'PayoutApiKey_' + d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate();
                    },
                    title: 'PayoutApiKey'
                },
                {
                    extend: 'print',
                    text: 'Print',
                    title: 'PayoutApiKey',
                    customize: function(win) {
                        $(win.document.body).find('h1').css('text-align', 'center').css('font-size', '20px');
                    }
                }
            ],
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
                    data: 'id',
                    name: 'id',
                    render: function(data, type, full, meta) {
                        return '<input type="checkbox" name="id[]" value="' + data + '">';
                    }
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'virtual_account',
                    name: 'virtual_account'
                },
                {
                    data: 'client',
                    name: 'client',
                    render: function(data, type, full, meta) {
                        var cln = full.client_id
                        return cln.slice(0, 5) + `***********************`;
                    }
                },
                {
                    data: 'secret',
                    name: 'secret',
                    render: function(data, type, full, meta) {
                        var secret_key = full.secret_key
                        return secret_key.slice(0, 5) + `***********************************`;
                    }
                },
                {
                    data: 'hash',
                    name: 'hash',
                    render: function(data, type, full, meta) {
                        var hash_key = full.hash_key
                        return hash_key.slice(0, 5) + `******`;
                    }
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ]
        });
        $('.dataTable').wrap('<div class="dataTables_scroll"></div>');
    });
</script> -->
@endsection