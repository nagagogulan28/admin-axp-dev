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
    <p class="title-common">Receive Bank Accounts</p>
    <div class="row row-new">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <div class="mb-3">
                {{ Form::select(
                                'filter_change_action',
                                $typeList, null,
                                [
                                    'class' => 'form-control',
                                    'style' => 'background-color: #fff;',
                                    'id' => 'filter_change_action'
                                ]
                            ) }}
            </div>
        </div>
    </div>
    <div class="common-box">
        <div class="text-right mb-3">
            <button type="button" class="bg-btn" id="add-bank-account" data-toggle="modal" data-target="#add-module">
                <img src="{{asset('new/img/add.svg')}}" alt="add" class="mr-1" />
                Add bank Account
            </button>
        </div>
        <div class="table-data">
            <table id="receive_bank_accounts" class="table dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Account No</th>
                        <th>Branch</th>
                        <th>Created By</th>
                        <th>Action</th>
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
                <h4 class="modal-title">Add Bank</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="addBankAccountForm" action="{{ route('accounts.store') }}" method="POST">
                @csrf <!-- CSRF Protection -->
                <div class="modal-body">
                    <div class="mb-4">
                        {{ Form::label('bank_name', 'Bank Name', ['class' => '']) }}
                        {{ Form::select('bank_name', $banks, null, ['class' => 'form-control']) }}
                    </div>
                    <div class="mb-3">
                        {{ Form::label('account_using_type', 'Payin Receive Bank', ['class' => '']) }}
                        {{ Form::select(
                                'account_using_type',
                                $typeList, null,
                                [
                                    'class' => 'form-control',
                                    'id' => 'account_using_type'
                                ]
                            ) }}
                    </div>
                    <div class="mb-4">
                        {{ Form::label('account_holder_name', 'Account Holder Name', ['class' => '']) }}
                        {{ Form::text('account_holder_name', null, ['class' => 'form-control', 'placeholder' => '']) }}
                    </div>
                    <div class="mb-4">
                        {{ Form::label('account_number', 'Account Number', ['class' => '']) }}
                        {{ Form::text('account_number', null, ['class' => 'form-control', 'placeholder' => '']) }}
                    </div>
                    <div class="mb-4">
                        {{ Form::label('branch', 'Branch', ['class' => '']) }}
                        {{ Form::text('branch', null, ['class' => 'form-control', 'placeholder' => '']) }}
                    </div>
                    <div class="mb-4">
                        {{ Form::label('ifsc_code', 'IFSC Code', ['class' => '']) }}
                        {{ Form::text('ifsc_code', null, ['class' => 'form-control', 'placeholder' => '']) }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="acc-close-button" data-dismiss="modal">Close</button>
                    <button type="button" class="bg-btn" id="generateNewModule">Add Bank</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
    jQuery.noConflict();
    jQuery(document).ready(function($) {

        jQuery('#acc-close-button').click(function($) {
            // Hide the modal
            jQuery('#add-module').hide();
        });

    });
</script>

@endsection