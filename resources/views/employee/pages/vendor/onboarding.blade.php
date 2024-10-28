@extends('layouts.employeecontent')
@section('employeecontent')
<p class="title-common">Vendor Onboarding</p>
<!-- step 1 start -->
<div class="common-box">
    <div class="container-indicator">
        <section class="step-indicator">
            <div class="step step1 active in-complete">
                <div class="step-icon">01</div>
                <p>Step 1</p>
            </div>
            <div class="indicator-line"></div>
            <div class="step step2">
                <div class="step-icon">02</div>
                <p>Step 2</p>
            </div>
        </section>
    </div>
    <div style="margin-top: 6rem !important;">

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-4">
                    {{Form::label('name', 'Name', array('class' => 'control-label'))}}
                    {{ Form::text('name', $name = null, array('class' => 'form-control', 'placeholder' => 'Name')) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-4">
                    {{Form::label('Email', 'Email', array('class' => 'control-label'))}}
                    {{ Form::text('Email', $Email = null, array('class' => 'form-control', 'placeholder' => 'Email')) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-4">
                    {{Form::label('Mobile', 'Mobile', array('class' => 'control-label'))}}
                    {{ Form::text('Mobile', $Mobile = null, array('class' => 'form-control', 'placeholder' => 'Mobile')) }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-4">
                    {{Form::label('Password', 'Password', array('class' => 'control-label'))}}
                    {{ Form::text('Password', $Password = null, array('class' => 'form-control', 'placeholder' => 'Password')) }}
                </div>
            </div>
        </div>
        <div class="text-center">
            {!! Form::submit('Draft', ['class' => 'outline-btn']) !!}
            {!! Form::submit('Next', ['class' => 'outline-btn']) !!}
        </div>

    </div>
</div>
<!-- step1 end  -->
<!-- step 2 start -->
<div class="common-box">
    <div class="container-indicator">
        <section class="step-indicator">
            <div class="step step1 active in-complete">
                <div class="step-icon">01</div>
                <p>Step 1</p>
            </div>
            <div class="indicator-line active"></div>
            <div class="step step2 active in-complete">
                <div class="step-icon">02</div>
                <p>Step 2</p>
            </div>            
        </section>
    </div>
    <div style="margin-top: 6rem !important;">       
        <div class="row">            
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-4">
                    {{Form::label('name', 'Business Name', array('class' => 'control-label'))}}
                    {{ Form::select('floor', 
                        [
                            '' => 'Select a Business Name',
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
                <div class="mb-4">
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
                <div class="mb-4">
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
        </div>
        
        <div class="row">            
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-4">
                    {{Form::label('Mobile', 'Account Holder Name', array('class' => 'control-label'))}}
                    {{ Form::text('Mobile', $Mobile = null, array('class' => 'form-control', 'placeholder' => 'Account Holder Name')) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-4">
                    {{Form::label('Mobile', 'Account Number', array('class' => 'control-label'))}}
                    {{ Form::text('Mobile', $Mobile = null, array('class' => 'form-control', 'placeholder' => 'Account Number')) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-4">
                    {{Form::label('Mobile', 'Branch', array('class' => 'control-label'))}}
                    {{ Form::text('Mobile', $Mobile = null, array('class' => 'form-control', 'placeholder' => 'Branch')) }}
                </div>
            </div>
        </div>

        <div class="row">            
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-4">
                    {{Form::label('Mobile', 'IFSC Code', array('class' => 'control-label'))}}
                    {{ Form::text('Mobile', $Mobile = null, array('class' => 'form-control', 'placeholder' => 'IFSC Code')) }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="mb-4">
                    {{Form::label('Mobile', 'Proof Documents', array('class' => 'control-label'))}}                   
                </div>
            </div>            
        </div>
       
       
        <div class="text-center">
            {!! Form::submit('Discard', ['class' => 'outline-btn out-btn']) !!}
            {!! Form::submit('Save', ['class' => 'outline-btn bgg-btn']) !!}
        </div>        
    </div>
</div>
<!-- step 2 end -->

@endsection