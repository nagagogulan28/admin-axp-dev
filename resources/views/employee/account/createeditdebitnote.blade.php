@php
use App\appxpaySupplier;
use App\CharOfAccount;
$suppliers = appxpaySupplier::get_sup_opts();
$amount_codes = CharOfAccount::get_code_options();
@endphp
@extends('layouts.employeecontent')
@section('employeecontent')
<style>
.save-generate {
    display: flex;
    justify-content: center;
}
.save-generate div {
    width: 123px;
    margin: 10px;
}
@media (max-width:600px){
    .save-generate {
        display: block;
    }
    .save-generate div {
        margin: 10px auto;
    }
}
</style>
<div class="row">
    <div class="col-sm-12 padding-20">
        <div class="panel panel-default">
            <div class="panel-heading">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" class="show-cursor" data-target="credit-debit-note">Debit Note</a></li> 
                </ul>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="row">
                        <div class="col-sm-12 p-0 mb-4">
                            <a href="{{route('account-payable','appxpay-XYFGXwnY')}}" class="btn btn-primary pull-right btn-sm">Go Back</a>
                        </div>
                    </div>
                    <div id="credit-debit-note" class="tab-pane fade in active">                        
                        @if($form == "create")
                        <div class="row">
                            <div class="col-sm-12 p-0">
                                 <form method="POST" class="form-horizontal" role="form" id="supplier-note-form">
     
                                     <div class="row row-new">
                                         <div class="col-sm-6">
                                             <div class="form-group">
                                                 <label for="input" class="col-lg-4 control-label">Supplier</label>
                                                 <div class="col-lg-6">
                                                     <select name="supplier_id" id="supplier_id" class="form-control">
                                                         <option value="">--Select--</option>
                                                         @foreach($suppliers as $supplier)
                                                             <option value="{{$supplier->id}}">{{$supplier->supplier_name}}</option>
                                                         @endforeach
                                                     </select>
                                                     <div id="supplier_id_error"></div>
                                                 </div>
                                             </div>
                                             <div class="form-group">
                                                 <label for="input" class="col-lg-4 control-label">Due Date:</label>
                                                 <div class="col-lg-6">
                                                     <div class="input-group date">
                                                         <input type="text" name="note_due" id="note_due" class="form-control" value="" placeholder="YYYY-MM-DD">
                                                         <span class="input-group-addon"><span class="fa fa-calendar show-pointer" onclick="($('#note_due')).focus();"></span></span>
                                                     </div>
                                                 </div>
                                             </div>
                                             <div class="form-group">
                                                 <label for="input" class="col-lg-4 control-label">Pay Term:</label>
                                                 <div class="col-lg-6">
                                                     <input type="text" name="note_payterms" id="note_payterms" class="form-control" value="">
                                                 </div>
                                             </div>   
                                             <div class="form-group">
                                                 <label for="textarea" class="col-lg-4 control-label">Remarks:</label>
                                                 <div class="col-lg-6">
                                                     <textarea name="note_remarks" id="note_remarks" class="form-control" rows="3" cols="25"></textarea>
                                                 </div>
                                             </div> 

                                         </div>
                                         <div class="col-sm-6">
     
                                             <div class="form-group">
                                                 <label for="input" class="col-lg-4 control-label">Note Date:</label>
                                                 <div class="col-lg-6">
                                                     <div class="input-group date">
                                                         <input type="text" name="note_date" id="note_date" class="form-control" value="" placeholder="YYYY-MM-DD">
                                                         <span class="input-group-addon"><span class="fa fa-calendar show-pointer" onclick="($('#note_date')).focus();"></span></span>
                                                     </div>
                                                 </div>
                                             </div>
                                             <div class="form-group">
                                                 <label for="input" class="col-lg-4 control-label">Note No:</label>
                                                 <div class="col-lg-6">
                                                     <input type="text" name="note_number" id="note_number" class="form-control" value="">
                                                 </div>
                                             </div>
                                             <div class="form-group">
                                                <label for="input" class="col-lg-4 control-label">Expense Code:</label>
                                                <div class="col-lg-6">
                                                    <select name="note_expense_code" id="note_expense_code" class="form-control">
                                                        <option value="">--Select--</option>                                                                            
                                                        @if(count($amount_codes) > 0)
                                                            @foreach($amount_codes as $amount_code)
                                                                <option value="{{$amount_code->id}}">{{$amount_code->account_code}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <div id="note_expense_code_error"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="input" class="col-lg-4 control-label">Note Amount:</label>
                                                <div class="col-lg-6">
                                                    <input type="text" name="note_amount" id="note_amount" class="form-control" value="">
                                                </div>
                                            </div>
                                         </div>
                                     </div>                                     
                                     {{csrf_field()}}
                                     <div class="row">
                                         <div class="form-group save-generate">
                                             <div>
                                                 <input type="submit" class="btn btn-primary btn-block" value="Save"/>
                                             </div>
                                         </div>
                                     </div>
                                 </form>
                            </div>
                        </div>
                        @else
                        <div class="row">
                            <div class="col-sm-12">
                                 <form method="POST" class="form-horizontal" role="form" id="edit-supplier-note-form">
                                     <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="input" class="col-lg-4 control-label">Supplier</label>
                                                <div class="col-lg-6">
                                                    <select name="supplier_id" id="supplier_id" class="form-control">
                                                        <option value="">--Select--</option>
                                                        @foreach($suppliers as $supplier)
                                                            @if($edit_data->supplier_id == $supplier->id)
                                                                <option value="{{$supplier->id}}" selected>{{$supplier->supplier_name}}</option>
                                                            @else
                                                                <option value="{{$supplier->id}}">{{$supplier->supplier_name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <div id="supplier_id_error"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="input" class="col-lg-4 control-label">Due Date:</label>
                                                <div class="col-lg-6">
                                                    <div class="input-group date">
                                                        <input type="text" name="note_due" id="note_due" class="form-control" value="{{$edit_data->note_due}}" placeholder="YYYY-MM-DD">
                                                        <span class="input-group-addon"><span class="fa fa-calendar show-pointer" onclick="($('#note_due')).focus();"></span></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="input" class="col-lg-4 control-label">Pay Term:</label>
                                                <div class="col-lg-6">
                                                    <input type="text" name="note_payterms" id="note_payterms" class="form-control" value="{{$edit_data->note_payterms}}">
                                                </div>
                                            </div>    
                                            <div class="form-group">
                                                <label for="textarea" class="col-lg-4 control-label">Remarks:</label>
                                                <div class="col-lg-6">
                                                    <textarea name="note_remarks" id="note_remarks" class="form-control" rows="3" cols="25">{{$edit_data->note_remarks}}</textarea>
                                                </div>
                                            </div> 

                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="input" class="col-lg-4 control-label">Note Date:</label>
                                                <div class="col-lg-6">
                                                    <div class="input-group date">
                                                        <input type="text" name="note_date" id="note_date" class="form-control" value="{{$edit_data->note_date}}" placeholder="YYYY-MM-DD">
                                                        <span class="input-group-addon"><span class="fa fa-calendar show-pointer" onclick="($('#note_date')).focus();"></span></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="input" class="col-lg-4 control-label">Note No:</label>
                                                <div class="col-lg-6">
                                                    <input type="text" name="note_number" id="note_number" class="form-control" value="{{$edit_data->note_number}}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                               <label for="input" class="col-lg-4 control-label">Expense Code:</label>
                                               <div class="col-lg-6">
                                                   <select name="note_expense_code" id="note_expense_code" class="form-control">
                                                       <option value="">--Select--</option>                                                                            
                                                       @if(count($amount_codes) > 0)
                                                           @foreach($amount_codes as $amount_code)
                                                                @if($amount_code->id == $edit_data->note_expense_code)
                                                                <option value="{{$amount_code->id}}" selected>{{$amount_code->account_code}}</option>
                                                               @else
                                                                <option value="{{$amount_code->id}}">{{$amount_code->account_code}}</option>
                                                               @endif
                                                           @endforeach
                                                       @endif
                                                   </select>
                                                   <div id="note_expense_code_error"></div>
                                               </div>
                                           </div>
                                           <div class="form-group">
                                               <label for="input" class="col-lg-4 control-label">Note Amount:</label>
                                               <div class="col-lg-6">
                                                   <input type="text" name="note_amount" id="note_amount" class="form-control" value="{{$edit_data->note_amount}}">
                                               </div>
                                           </div>
                                        </div>
                                    </div>                                   
                                     {{csrf_field()}}
                                     <input type="hidden" name="id" id="id" value="{{$edit_data->id}}">
                                     <div class="row">
                                         <div class="form-group">
                                             <div class="col-sm-2 col-sm-offset-5">
                                                 <input type="submit" class="btn btn-primary btn-block" value="Update"/>
                                             </div>
                                         </div>
                                     </div>
                                 </form>
                            </div>
                        </div>
                        @endif
                        <!-- Porder created modal starts-->
                        <div id="supnote-add-response-message-modal" class="modal fade">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <strong id="supnote-add-response"></strong>
                                    </div>
                                    <div class="modal-footer">
                                        <form>
                                            <input type="submit" class="btn btn-primary btn-sm" value="OK" onlick="location.reload();"/>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Porder created modal ends-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection