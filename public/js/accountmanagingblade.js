$(document).ready(function () {

    var filterData = {
        RespectiveType: 0,
    };

    var bankListDatatable = jQuery('#receive_bank_accounts').DataTable({
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
            url: "/add/bank/list",
            type: "GET",
            data: function(d) {
                return $.extend({}, d, filterData);
            }
        },
        columns: [{
            data: 'id',
            title: 'S.No',
            orderable: false,
            searchable: false,
            render: function (data, type, row, meta) {
                return meta.row + 1;
            }
        },
        {
            data: 'account_holder_name',
            name: 'account_holder_name'
        },
        {
            data: 'account_no',
            title: 'Account No',
            orderable: false,
            searchable: false,
            render: function (data, type, row, meta) {
                return data;
            }

        },
        {
            data: 'branch',
            name: 'Branch'
        },
        {
            data: 'status',
            title: 'Respective Type',
            orderable: false,
            searchable: false,
            render: function (data, type, row, meta) {
                return '<span class="badge badge-success">'+row.bank_account_type.name+'</span>';
            }

        },
        {
            data: 'created_at',
            title: 'Created At',
            orderable: false,
            searchable: false,
            render: function (data, type, row, meta) {
                return data;
            }

        },
        {
            data: 'action',
            name: 'action'
        }
        ]
    });

    

    $('#filter_change_action').on('change', function () {

        filterData = {
            RespectiveType: $(this).val()
        };

        bankListDatatable.ajax.reload();
        
    });

    $("#receive_bank_accounts").on('click', '.update-row', function () {
        var statusOfRow = $(this).data('status') == '1' ? 'Deactivated!' : 'Actvated!';
        Swal.fire({
            title: "Status Update!",
            text: "It's will be " + statusOfRow,
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            showCloseButton: true,
            icon: "info"
        }).then((result) => {
            if (result.isConfirmed) {
                showLoader();
                var rowId = $(this).data('id');
                $.ajax({
                    url: '/service/bank/update/' + rowId,
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data.status == 'success') {
                            bankListDatatable.ajax.reload();
                            toastr.success(data.message);
                        } else {
                            toastr.error(data.message, 'Error');
                        }
                        hideLoader();
                    },
                    error: function (xhr, status, error) {
                        hideLoader();
                        console.error('Error fetching merchant details:', xhr.responseText);
                    }
                });
            }
        });
    });

    $("#receive_bank_accounts").on('click', '.bank_details', function () {
        showLoader();
        $('#generateNewModule').text('Update Bank');
        var rowId = $(this).data('id');
        $.ajax({
            url: '/service/bank/' + rowId,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data.status == 'success') {
                    // Update select fields
                    $('#bank_name').val(data.data.bank_id).change();
                    $('#account_using_type').val(data.data.account_type).change();

                    // Populate other form fields with data from the response
                    $('#addBankAccountForm').append('<input type="hidden" name="bank_row_id" id="bank_row_id" value="' + data.data.id + '">');
                    $('#account_holder_name').val(data.data.account_holder_name);
                    $('#account_number').val(data.data.account_no);
                    $('#branch').val(data.data.branch);
                    $('#ifsc_code').val(data.data.ifsc_code);
                    $('#add-module').modal('show');
                } else {
                    $('#addBankAccountForm')[0].reset();
                    $('#add-module').modal('hide');
                }
                hideLoader();
            },
            error: function (xhr, status, error) {
                hideLoader();
                console.error('Error fetching merchant details:', xhr.responseText);
            }
        });
    });

    $("#add-bank-account").click(function () {
        $('#addBankAccountForm #bank_row_id').remove();
        $('#generateNewModule').text('Add Bank');
        $('#addBankAccountForm')[0].reset();
        $('#moduleNameError').text('');
        $('#mainMenuOrderError').text('');
        $('#subMenuOrderError').text('');
        $('#statusError').text('');
        $('#menuTypeError').text('');
        $('#moduleName').val('');
        $('#main_menu_order').val('');
        $('#sub_menu_order').val('');
    });

    $(".close").click(function () {
        $('#add-module').modal('hide');
    });

    function validateField(field) {
        if (field.val() && field.val().trim() === '' || field.val() == 0) {
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

    function isValid_IFSC_Code(field) {
        let regex = new RegExp(/^[A-Z]{4}0[A-Z0-9]{6}$/);
        if (field.val().trim() !== '' && regex.test(field.val())) {
            field.removeClass('is-invalid');
            field.siblings('.invalid-feedback').remove();
            return true;
        } else {
            field.addClass('is-invalid');
            field.siblings('.invalid-feedback').remove();
            field.after('<div class="invalid-feedback">This IFSC Code is invalid.</div>');
            return false;
        }
    }

    const minLength = 9;
    const maxLength = 18;

    function validateBankAccountNumber(field) {
        const value = field.val();
        if (value.length < minLength || value.length > maxLength) {
            field.addClass('is-invalid');
            field.siblings('.invalid-feedback').remove();
            field.after(`<div class="invalid-feedback">Bank account number must be between ${minLength} and ${maxLength} digits long.</div>`);
            return false;
        } else {
            field.removeClass('is-invalid');
            field.siblings('.invalid-feedback').remove();
            return true;
        }
    }

    // Input Fields
    $('#bank_name').on('change', function () {
        validateField($(this));
    });

    $('#account_using_type').change(function () {
        validateField($(this));
    });

    $('#account_holder_name').on('input', function () {
        validateField($(this));
        this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
    });

    $('#account_number').on('input', function () {
        validateField($(this));
        this.value = this.value.replace(/[^0-9]/g, '');
        validateBankAccountNumber($(this));
    });

    $('#branch').on('input', function () {
        validateField($(this));
    });

    $('#ifsc_code').on('input', function () {
        validateField($(this));
        isValid_IFSC_Code($(this));
    });

    // Before submit validation and AJAX request
    $('#generateNewModule').on('click', function (event) {
        event.preventDefault();
        var isValid = true;
        var f1 = validateField($('#bank_name'));
        var f2 = validateField($('#account_holder_name'));
        var f3 = validateField($('#account_number'));
        var f4 = validateField($('#branch'));
        var f5 = validateField($('#ifsc_code'));
        var f6 = validateField($('#account_using_type'));

        if (f3) {
            f3 = validateBankAccountNumber($('#account_number'));
        }

        if (f5) {
            f5 = isValid_IFSC_Code($('#ifsc_code'));
        }

        if (!f1 || !f2 || !f3 || !f4 || !f5 || !f6) {
            isValid = false;
        }

        if (isValid) {
            var formData = $('#addBankAccountForm').serialize();
            console.log("formData", formData);
            showLoader();
            $.ajax({
                url: $('#addBankAccountForm').attr('action'),
                type: 'POST',
                data: formData,
                success: function (response) {
                    console.log(response);
                    if (response.status == 'success') {
                        $('#responseContainer').text('Module added successfully!').show();
                        $('#addBankAccountForm')[0].reset();
                        toastr.success(response.message);
                        bankListDatatable.ajax.reload();
                        $('#add-module').modal('hide');
                    } else {
                        $('#add-module').modal('hide');
                        toastr.error(response.message, 'Error');
                    }
                    hideLoader();
                }
            });
        }
    });

    $('#close-button').on('click', function () {
        $('#addBankAccountForm')[0].reset();
        $('.invalid-feedback').remove();
        $('.is-invalid').removeClass('is-invalid');
    });

    function showLoader() {
        $("div#divLoading").removeClass('hide');
        $("div#divLoading").addClass('show');
    }

    function hideLoader() {
        $("div#divLoading").removeClass('show');
        $("div#divLoading").addClass('hide');
    }
});
