@extends('layouts.employeecontent')
@section('employeecontent')

@if(session()->has('status'))
@if(session('status') === 'success')
<div class="alert alert-success" role="alert">{{ session('message') }}</div>
@else
<div class="alert alert-danger" role="alert">{{ session('message') }}</div>
@endif
@endif
<div class="row">
    <div class="col-sm-12 padding-20">
        <p class="title-common">Permission Settings</p>
        <div class="row row-new">
            <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="mb-3">                   
                    <select name="roleId" id="roleId" class="form-control bg-white">
                        @if(count($getRoles) > 0)
                        @foreach($getRoles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
        <div class="common-box">
        <div class="table-data">
        <table id="roles_permissions_datatable" class="table dataTable">
        
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Modules</th>
                                            <!-- <th>Status</th> -->
                                            <th>All</th>
                                            <th>Create</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                            <th>View</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                                </div>
                                </div>
        <!-- <div class="panel panel-default" style="margin-top: 22px;">
            <div class="panel-body">
                <div class="tab-content">
                    <div id="apikeyssection" class="tab-pane fade in active">
                        <div class="row">
                            <div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.js"></script>


<script>
    jQuery.noConflict();
    jQuery(document).ready(function($) {

        var roleListDatatable = new jQuery('#roles_permissions_datatable').DataTable({
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
                type: 'POST',
                data: function(d) {
                    d.referPermission_id = $('#roleId').val();
                },
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
                        return meta.row + 1;
                    }
                },
                {
                    data: 'name',
                    name: 'name'
                },
                // {
                //     data: 'status',
                //     name: 'status'
                // },
                {
                    data: 'id',
                    title: 'All',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        var check = '';
                        if (row.module_create == 1 && row.module_view == 1 && row.module_edit == 1 && row.module_delete) {
                            check = 'checked';
                        }
                        return '<input class="check_update_all" data-prid="' + row.id + '" data-id="' + row.moduledata.id + '" type="checkbox" value="" ' + check + '>';
                    }
                },
                {
                    data: 'id',
                    title: 'Create',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        var check = '';
                        if (row.module_create == 1) {
                            check = 'checked';
                        }
                        return '<input data-prid="' + row.id + '" class="single_check_box check_create_' + row.moduledata.id + ' update_all_' + row.moduledata.id + '" type="checkbox" value="module_create" ' + check + '>';
                    }
                },
                {
                    data: 'id',
                    title: 'Update',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        var check = '';
                        if (row.module_edit == 1) {
                            check = 'checked';
                        }
                        return '<input data-prid="' + row.id + '" class="single_check_box check_update_' + row.moduledata.id + ' update_all_' + row.moduledata.id + '" type="checkbox" value="module_edit" ' + check + '>';
                    }
                },

                {
                    data: 'id',
                    title: 'Delete',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        var check = '';
                        if (row.module_delete == 1) {
                            check = 'checked';
                        }
                        return '<input data-prid="' + row.id + '" class="single_check_box check_delete_' + row.moduledata.id + ' update_all_' + row.moduledata.id + '" type="checkbox" value="module_delete" ' + check + '>';
                    }
                },

                {
                    data: 'id',
                    title: 'View',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        var check = '';
                        if (row.module_view == 1) {
                            check = 'checked';
                        }
                        return '<input data-prid="' + row.id + '" class="single_check_box check_view_' + row.moduledata.id + ' update_all_' + row.moduledata.id + '" type="checkbox" value="module_view" ' + check + '>';
                    }
                },
            ]
        });

        $('#roleId').change(function() {
            roleListDatatable.ajax.reload(null, false); // Reload DataTable data without resetting pagination
        });

        $('#roles_permissions_datatable').on('input', '.check_update_all', function() {
            var rowId = $(this).data('id');
            var updateRowId = $(this).data('prid');
            var isChecked = $(this).prop('checked');
            var updateValue = 0;

            if (isChecked) {
                $('#roles_permissions_datatable .update_all_' + rowId).prop('checked', isChecked);
                updateValue = 1;
            } else {
                $('#roles_permissions_datatable .update_all_' + rowId).prop('checked', false);
            }
            var requestData = {};
            var data = {}; 
            data['module_create'] = updateValue;
            data['module_edit'] = updateValue;
            data['module_view'] = updateValue;
            data['module_delete'] = updateValue;
            requestData['rowId'] = updateRowId;
            requestData['updateData'] = data;
            updateAjaxCall(requestData);
        });

        var defArr = ['check_view_', 'check_delete_', 'check_update_', 'check_create_'];

        $('#roles_permissions_datatable').on('input', '.single_check_box', function() {
            var rowId = $(this).data('prid');
            var clickValue = $(this).val();
            var isChecked = $(this).prop('checked');
            var updateValue = 0;
            if (isChecked) {
                updateValue = 1;
            }
            var requestData = {};
            var data = {}; data[clickValue] = updateValue;
            requestData['rowId'] = rowId;
            requestData['updateData'] = data;
            updateAjaxCall(requestData);
        });

        var defArr = ['check_view_', 'check_delete_', 'check_update_', 'check_create_'];

        function updateAjaxCall(requestData) {
            $.ajax({
                type: "POST",
                url: "{{ route('roles.permission.update') }}",
                data: requestData,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                        console.log('response',response);
                    if (typeof response != undefined && response.status == 'success') {
                        toastr.success(response.message);
                    }else{
                        toastr.error(response.message);
                    }
                    roleListDatatable.ajax.reload(null, false);
                }
            });

        }

        // $('#roles_permissions_datatable').on('input', '.check_update_all', function() {
        //     var rowId = $(this).data('id');
        //     var isChecked = $(this).prop('checked');

        //     if (isChecked) {
        //         $('#roles_permissions_datatable .update_all_'+rowId).prop('checked', isChecked);
        //     } else {
        //         $('#roles_permissions_datatable .update_all_' + rowId).prop('checked', false);
        //     }                   
        // });

        // $('.check_create_01, .check_update_01, .check_delete_01, .check_view_01').on('change', function() {
        //     // Get the data-id of the clicked checkbox
        //     var id = $(this).data('id');

        //     // Get the checked state of the clicked checkbox
        //     var isChecked = $(this).prop('checked');

        //     // Update all corresponding checkboxes with the same data-id
        //     $('.update_all_' + id).prop('checked', isChecked);
        // });

    });
</script>
@endsection