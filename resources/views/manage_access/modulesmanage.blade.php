@extends('layouts.employeecontent')
@section('employeecontent')

@if(session()->has('status'))
@if(session('status') === 'success')
<div class="alert alert-success" role="alert">{{ session('message') }}</div>
@else
<div class="alert alert-danger" role="alert">{{ session('message') }}</div>
@endif
@endif

<div class="modulesmanage-pages">
    <p class="title-common">List of Modules</p>
    <div class="common-box">

        <div class="text-right mb-3">
            <button type="button" class="bg-btn" id="add-module-model" data-toggle="modal" data-target="#add-module">
                <img src="{{asset('new/img/add.svg')}}" alt="add" class="mr-1" />
                Add Module
            </button>
        </div>

        <div class="table-data">
            <table id="module_datatable" class="table dataTable">
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

<!-- App Module Modal -->
<div class="modal" id="add-module" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">            
            <div class="modal-header">
                <h4 class="modal-title">Add Module</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="addModuleForm" action="{{ route('module.store') }}" method="POST">
                @csrf <!-- CSRF Protection -->
                <div class="modal-body">
                    <div class="form-group">
                        <label for="clientId">Module Name</label>
                        <input type="text" name="module_name" class="form-control" id="moduleName">
                        <div id="moduleNameError" class="text-danger"></div>
                    </div>
                    <div class="form-group">
                        <label class="container p-0">Main Menu
                            <input type="radio" name="menu_type" value="mainMenu">
                            <span class="checkmark"></span>
                        </label>
                        <label class="container p-0">Sub Menu
                            <input type="radio" name="menu_type" value="subMenu">
                            <span class="checkmark"></span>
                        </label>
                        <div id="menuTypeError" class="text-danger"></div>
                    </div>
                    @if(isset($mainMenuCollection) && count($mainMenuCollection) > 0)
                    <div class="form-group" id="selectMainMenu">
                        <label>Parent Menu</label>
                        <select class="select" name="parentMenuId" id="parent_menu_id" required>
                            @foreach ($mainMenuCollection as $virtual)
                            <option value="{{ $virtual->id }}">{{ $virtual->modules }}</option>
                            @endforeach
                        </select>
                        <div id="statusError" class="text-danger"></div>
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="clientId">Menu Order</label>
                        <input type="number" name="main_menu_order" class="form-control" id="main_menu_order">
                        <div id="mainMenuOrderError" class="text-danger"></div>
                    </div>
                    <div class="form-group">
                        <label for="clientId">Menu icon</label>
                        <input type="text" name="menu_icon" class="form-control" id="menuIcon">
                        <div id="menuIconError" class="text-danger"></div>
                    </div>
                    <div class="form-group">
                        <label for="module_status">Status</label>
                        @php
                        // Define two static values
                        $virtualaccounts = [
                        (object)['id' => 1, '0' => 'Static1', 'name' => 'Active'],
                        (object)['id' => 2, '1' => 'Static2', 'name' => 'Inactive']
                        ];
                        @endphp
                        <select class="select" name="status" id="module_status" required>
                            @foreach ($virtualaccounts as $virtual)
                            <option value="{{ $virtual->id }}">{{ auth()->id() }} {{ $virtual->name }}</option>
                            @endforeach
                        </select>
                        <div id="statusError" class="text-danger"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close-button" data-dismiss="modal">Close</button>
                    <button type="button" class="bg-btn" id="generateNewModule">Add Module</button>
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
            jQuery('#add-module').hide();
        });

        // Open modal when button is clicked
        $("#add-module-model").click(function() {
            $('#moduleNameError').text('');
            $('#mainMenuOrderError').text('');
            $('#subMenuOrderError').text('');
            $('#statusError').text('');
            $('#menuTypeError').text('');
            $("#add-module").show();
            $('#moduleName').val('');
            $('#main_menu_order').val('');
            $('#sub_menu_order').val('');
        });

        $('#generateNewModule').click(function(event) {
            event.preventDefault();

            var moduleName = $('#moduleName').val();
            var menuType = $('input[name="menu_type"]:checked').val();
            var mainMenuOrder = $('#main_menu_order').val();
            var subMenuOrder = $('#sub_menu_order').val();
            var iconVal = $('#menuIcon').val();
            var status = $('#module_status').val();

            var errors = false;

            if (moduleName.trim() === '') {
                $('#moduleNameError').text('Module Name is required');
                errors = true;
            }

            if (!iconVal) {
                $('#menuIconError').text('Menu Icon is required');
                errors = true;
            }

            if (!menuType) {

                $('#menuTypeError').text('Menu Type is required');
                errors = true;
            }

            if (mainMenuOrder.trim() === '') {
                $('#mainMenuOrderError').text('Main Menu Order is required');
                errors = true;
            }

            if (status === '') {
                $('#statusError').text('Status is required');
                errors = true;
            }

            if (!errors) {
                var formData = $('#addModuleForm').serialize();
                $.ajax({
                    url: $('#addModuleForm').attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        if (response.status == 'success') {
                            $('#responseContainer').text('Module added successfully!').show();
                            $('#addModuleForm')[0].reset();
                            toastr.success(response.message);
                            moduleListDatatable.ajax.reload();
                            jQuery('#add-module').hide();
                        } else {
                            toastr.error(response.message, 'Error');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error:', textStatus, errorThrown);
                        $('#responseContainer').text('An error occurred while adding the module. Please try again.').show();
                    }
                });
            }
        });
        
        $(".close").click(function() {
            $("#myModal").hide();
        });

        var moduleListDatatable = jQuery('#module_datatable').DataTable({
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
            ajax: "{{ route('modules.list') }}",
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
                    data: 'modules',
                    name: 'modules'
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

@endsection