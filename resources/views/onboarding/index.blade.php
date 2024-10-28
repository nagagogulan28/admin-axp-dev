@extends('layouts.employeecontent')
@section('employeecontent')
<p class="title-common">Add Merchant</p>
<!-- step 1 start -->
<div class="common-box">    
    <div class="container-indicator">
        <section class="step-indicator">
            <div class="step step1 active in-complete">
                <div class="step-icon">01</div>
                <p>Personal Details</p>
            </div>
            <div class="indicator-line"></div>
            <div class="step step2">
                <div class="step-icon">02</div>
                <p>Company Info</p>
            </div>
            <div class="indicator-line"></div>
            <div class="step step3">
                <div class="step-icon">03</div>
                <p>Pay-in settings</p>
            </div>
            <div class="indicator-line"></div>
            <div class="step step4">
                <div class="step-icon">04</div>
                <p>Business Info</p>
            </div>
        </section>
    </div>
    <div style="margin-top: 6rem !important;">
        {!! Form::open(['url' => 'foo/bar']) !!}
        <div class="row">       
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    {{Form::label('name', 'Name', array('class' => 'control-label'))}} 
                    {{ Form::text('name', $name = null, array('class' => 'form-control', 'placeholder' => 'Name')) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    {{Form::label('Email', 'Email', array('class' => 'control-label'))}} 
                    {{ Form::text('Email', $Email = null, array('class' => 'form-control', 'placeholder' => 'Email')) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    {{Form::label('Mobile', 'Mobile', array('class' => 'control-label'))}} 
                    {{ Form::text('Mobile', $Mobile = null, array('class' => 'form-control', 'placeholder' => 'Mobile')) }}
                </div>
            </div>
        </div>  
        <div class="row"> 
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    {{Form::label('Password', 'Password', array('class' => 'control-label'))}} 
                    {{ Form::text('Password', $Password = null, array('class' => 'form-control', 'placeholder' => 'Password')) }}
                </div>
            </div>  
        </div>   
        <div class="text-center">        
            {!! Form::submit('Draft', ['class' => 'outline-btn']) !!}
            {!! Form::submit('Next', ['class' => 'outline-btn']) !!}        
        </div>           
        {!! Form::close() !!}
    </div>  
</div>
<!-- step 1 end -->
<!-- step 2 start -->
<div class="common-box">    
    <div class="container-indicator">
        <section class="step-indicator">
            <div class="step step1 active in-complete">
                <div class="step-icon">01</div>
                <p>Personal Details</p>
            </div>
            <div class="indicator-line active"></div>
            <div class="step step2 active in-complete">
                <div class="step-icon">02</div>
                <p>Company Info</p>
            </div>
            <div class="indicator-line"></div>
            <div class="step step3">
                <div class="step-icon">03</div>
                <p>Pay-in settings</p>
            </div>
            <div class="indicator-line"></div>
            <div class="step step4">
                <div class="step-icon">04</div>
                <p>Business Info</p>
            </div>
        </section>
    </div>
    <div style="margin-top: 6rem !important;">
        {!! Form::open(['url' => 'foo/bar']) !!}
        <div class="row">       
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    {{Form::label('name', 'Business Type', array('class' => 'control-label'))}}              
                    {{ Form::select('floor', 
                        [
                            '' => 'Select a Business type',
                            'Basement' => 'Basement',
                            '1st Floor' => '1st Floor',
                            '2nd Floor' => '2nd Floor',
                            '3rd Floor' => '3rd Floor',
                            '4th Floor' => '4th Floor',
                        ], 
                        null, 
                        ['class' => 'form-control','required' => 'required']) 
                    }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    {{Form::label('name', 'Business Category', array('class' => 'control-label'))}}     
                    {{ Form::select('floor', 
                        [
                            '' => 'Select a Business Category',
                            'Basement' => 'Basement',
                            '1st Floor' => '1st Floor',
                            '2nd Floor' => '2nd Floor',
                            '3rd Floor' => '3rd Floor',
                            '4th Floor' => '4th Floor',
                        ], 
                        null, 
                        ['class' => 'form-control','required' => 'required']) 
                    }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    {{Form::label('name', 'Business Sub Category ', array('class' => 'control-label'))}}     
                    {{ Form::select('floor', 
                        [
                            '' => 'Select a Business Sub Category ',
                            'Basement' => 'Basement',
                            '1st Floor' => '1st Floor',
                            '2nd Floor' => '2nd Floor',
                            '3rd Floor' => '3rd Floor',
                            '4th Floor' => '4th Floor',
                        ], 
                        null, 
                        ['class' => 'form-control','required' => 'required']) 
                    }}
                </div>
            </div>
        </div>  
        <div class="row">       
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    {{Form::label('name', 'WebApp/Url', array('class' => 'control-label'))}} 
                    {{ Form::text('name', $name = null, array('class' => 'form-control', 'placeholder' => 'WebApp/Url')) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    {{Form::label('name', 'Bank Name', array('class' => 'control-label'))}}     
                    {{ Form::select('floor', 
                        [
                            '' => 'Select a Bank Name',
                            'Basement' => 'Basement',
                            '1st Floor' => '1st Floor',
                            '2nd Floor' => '2nd Floor',
                            '3rd Floor' => '3rd Floor',
                            '4th Floor' => '4th Floor',
                        ], 
                        null, 
                        ['class' => 'form-control','required' => 'required']) 
                    }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    {{Form::label('name', 'Bank Acc No', array('class' => 'control-label'))}} 
                    {{ Form::text('name', $name = null, array('class' => 'form-control', 'placeholder' => 'Bank Acc No')) }}
                </div>
            </div>
        </div> 
        <div class="row">       
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    {{Form::label('name', 'Bank IFSC Code', array('class' => 'control-label'))}} 
                    {{ Form::text('name', $name = null, array('class' => 'form-control', 'placeholder' => 'Bank IFSC Code')) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    {{Form::label('Email', 'Branch Name', array('class' => 'control-label'))}} 
                    {{ Form::text('Email', $Email = null, array('class' => 'form-control', 'placeholder' => 'Branch Name')) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    {{Form::label('Mobile', 'Account Holder Name', array('class' => 'control-label'))}} 
                    {{ Form::text('Mobile', $Mobile = null, array('class' => 'form-control', 'placeholder' => 'Account Holder Name')) }}
                </div>
            </div>
        </div> 
        <div class="row">       
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    {{Form::label('name', 'Monthly Expenditure', array('class' => 'control-label'))}}              
                    {{ Form::select('floor', 
                        [
                            '' => 'Select a Monthly Expenditure',
                            'Basement' => 'Basement',
                            '1st Floor' => '1st Floor',
                            '2nd Floor' => '2nd Floor',
                            '3rd Floor' => '3rd Floor',
                            '4th Floor' => '4th Floor',
                        ], 
                        null, 
                        ['class' => 'form-control','required' => 'required']) 
                    }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    {{Form::label('Email', 'Company Name', array('class' => 'control-label'))}} 
                    {{ Form::text('Email', $Email = null, array('class' => 'form-control', 'placeholder' => 'Company Name')) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    {{Form::label('Mobile', 'Company Address', array('class' => 'control-label'))}} 
                    {{ Form::text('Mobile', $Mobile = null, array('class' => 'form-control', 'placeholder' => 'Company Address')) }}
                </div>
            </div>
        </div> 
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    {{Form::label('Email', 'Pincode', array('class' => 'control-label'))}} 
                    {{ Form::text('Email', $Email = null, array('class' => 'form-control', 'placeholder' => 'Pincode')) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    {{Form::label('Mobile', 'City', array('class' => 'control-label'))}} 
                    {{ Form::text('Mobile', $Mobile = null, array('class' => 'form-control', 'placeholder' => 'City')) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    {{Form::label('name', 'State', array('class' => 'control-label'))}}              
                    {{ Form::select('floor', 
                        [
                            '' => 'Select a State',
                            'Basement' => 'Basement',
                            '1st Floor' => '1st Floor',
                            '2nd Floor' => '2nd Floor',
                            '3rd Floor' => '3rd Floor',
                            '4th Floor' => '4th Floor',
                        ], 
                        null, 
                        ['class' => 'form-control','required' => 'required']) 
                    }}
                </div>
            </div>
        </div> 
        <div class="row">            
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    {{Form::label('name', 'Country', array('class' => 'control-label'))}}              
                    {{ Form::select('floor', 
                        [
                            '' => 'Select a Country',
                            'Basement' => 'Basement',
                            '1st Floor' => '1st Floor',
                            '2nd Floor' => '2nd Floor',
                            '3rd Floor' => '3rd Floor',
                            '4th Floor' => '4th Floor',
                        ], 
                        null, 
                        ['class' => 'form-control','required' => 'required']) 
                    }}
                </div>
            </div>
        </div>         
        <div class="text-center">        
            {!! Form::submit('Draft', ['class' => 'outline-btn']) !!}
            {!! Form::submit('Next', ['class' => 'outline-btn']) !!}        
        </div>           
        {!! Form::close() !!}
    </div>  
</div>
<!-- step 2 end -->
<!-- step 3 start -->
<div>
    <div class="common-box px-0">    
        <div class="container-indicator">
            <section class="step-indicator">
                <div class="step step1 active in-complete">
                    <div class="step-icon">01</div>
                    <p>Personal Details</p>
                </div>
                <div class="indicator-line active"></div>
                <div class="step step2 active in-complete">
                    <div class="step-icon">02</div>
                    <p>Company Info</p>
                </div>
                <div class="indicator-line active"></div>
                <div class="step step3 active in-complete">
                    <div class="step-icon">03</div>
                    <p>Pay-in settings</p>
                </div>
                <div class="indicator-line"></div>
                <div class="step step4">
                    <div class="step-icon">04</div>
                    <p>Business Info</p>
                </div>
            </section>
        </div>
        <div class="merchant-sec" style="margin-top: 6rem !important;">        
            <div class="three-step" style="background: #F9FBFC;">
                <div class="row justify-content-between">       
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <div class="mb-3">
                            {{Form::label('name', 'Merchant Name', array('class' => 'control-label'))}}              
                            <div>Twilight</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <div class="mb-3">
                            {{Form::label('name', 'Merchant Email', array('class' => 'control-label'))}}              
                            <div>demouser@yopmail.com</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <div class="mb-3">
                            {{Form::label('name', 'Merchant Contact', array('class' => 'control-label'))}}              
                            <div>+91 9452365214</div>
                        </div>
                    </div>
                </div>            
            </div>
            <div class="three-step">
                <div class="row pt-2 justify-content-between">       
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <div class="mb-3">
                            {{Form::label('name', 'Merchant Websites', array('class' => 'control-label MerchantWebsites'))}}              
                            {{ Form::text('name', $name = null, array('class' => 'form-control', 'placeholder' => 'Merchant Websites')) }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <div class="mb-3">
                            {{Form::label('name', 'Pay-In', array('class' => 'control-label'))}}  
                            <div style="height: 45px;display: flex;align-items: center;">
                                <button type="button" class="btn btn-toggle" data-toggle="button" aria-pressed="false" autocomplete="off">
                                    <div class="handle"></div>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <div class="mb-3">
                            {{Form::label('name', 'Pay-Out', array('class' => 'control-label'))}}  
                            <div style="height: 45px;display: flex;align-items: center;">
                                <button type="button" class="btn btn-toggle active" data-toggle="button" aria-pressed="true" autocomplete="off">
                                    <div class="handle"></div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div> 
            </div> 
        </div>  
    </div>
    <div class="common-box mt-3 px-0"> 
        <div class="search-btn">
            <div>  
                {{ Form::text('name', $name = null, array('class' => 'form-control search-field', 'placeholder' => '')) }}               
            </div>
            <div>  
               <button class="bg-btn" style="display: flex;align-items: center;"><img src="{{ asset('new/img/add.svg')}}" alt="print" style="margin-right:5px">Slab Settings</button>             
            </div>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Age</th>
                    <th>City</th>
                    <th>Country</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>Anna</td>
                    <td>Pitt</td>
                    <td>35</td>
                    <td>New York</td>
                    <td>USA</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Anna</td>
                    <td>Pitt</td>
                    <td>35</td>
                    <td>New York</td>
                    <td>USA</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- step 3 end -->
<!-- step 4 start -->
<div class="common-box">    
    <div class="container-indicator">
        <section class="step-indicator">
            <div class="step step1 active in-complete">
                <div class="step-icon">01</div>
                <p>Personal Details</p>
            </div>
            <div class="indicator-line active"></div>
            <div class="step step2 active in-complete">
                <div class="step-icon">02</div>
                <p>Company Info</p>
            </div>
            <div class="indicator-line active"></div>
            <div class="step step3 active in-complete">
                <div class="step-icon">03</div>
                <p>Pay-in settings</p>
            </div>
            <div class="indicator-line active"></div>
            <div class="step step4 active in-complete">
                <div class="step-icon">04</div>
                <p>Business Info</p>
            </div>
        </section>
    </div>
    <div style="margin-top: 6rem !important;">
        <p class="f-20 fw-500 textgray mb-3 px-15">Upload Documents <span class="f-15 textred">(Note PDF & Images are only allowed up to 5MB in size)</span></p>
        {!! Form::open(['url' => 'foo/bar']) !!}
        <div class="row">       
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-3">
                    <div class="star-label">
                        {{Form::label('name', 'Company PanCard', array('class' => 'control-label'))}}<span style="color: red;font-size: 16px;">*</span> 
                    </div>                    
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
               
            </div>
        </div> 
        <div class="text-center">        
            {!! Form::submit('Draft', ['class' => 'outline-btn']) !!}
            {!! Form::submit('Next', ['class' => 'outline-btn']) !!}        
        </div>           
        {!! Form::close() !!}
    </div>  
</div>
<!-- step 4 end -->
@endsection