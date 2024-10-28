@extends('layouts.employeecontent')
@section('employeecontent')

@if(session()->has('status'))
@if(session('status') === 'success')
<div class="alert alert-success" role="alert">{{ session('message') }}</div>
@else
<div class="alert alert-danger" role="alert">{{ session('message') }}</div>
@endif
@endif
<style>
    .common-box input[type="file"] {
        display: block !important;
    }

    a.view-align:hover {
        color: #fff;
    }

    .view-align {
        color: white;
        background-color: #007bff;
        padding: 3px 11px;
        border-radius: 5px;
        height: 45px;
        margin-left: 10px;
        display: flex;
        align-items: center;
    }
</style>
@if(isset($user))
@php
$get_level = $user->process_level;
@endphp
@endif
@php
$currentLevel = 1;
@endphp
<p class="title-common">Add Merchant</p>
<div class="common-box">
    <div class="container-indicator">
        <section class="step-indicator">
            <div class="step step1 active in-complete">
                @if(isset($user))
                <a href="/onboarding/eqyroksfig/{{$user->id}}">
                    <div class="step-icon">01</div>
                </a>
                @else
                <div class="step-icon">01</div>
                @endif
                <p>Personal Details</p>
            </div>
            @php
            $get_level_two_1 = $currentLevel == 2 || $user->process_level >= 2 ? 'active' : '';
            $get_level_two_2 = $currentLevel == 2 || $user->process_level >= 2 ? 'in-complete' : '';
            @endphp
            <div class="indicator-line {{$get_level_two_1}}"></div>
            <div class="step step2 {{$get_level_two_1}} {{$get_level_two_2}}">
                @if(isset($user) && ($currentLevel == 2 || $user->process_level >= 2))
                <a href="/onboarding/aftxjcqenf/{{$user->id}}">
                    <div class="step-icon">02</div>
                </a>
                @else
                <div class="step-icon">02</div>
                @endif
                <p>Company Info</p>
            </div>

            @php
            $get_level_three_1 = $currentLevel == 3 || $user->process_level >= 3 ? 'active' : '';
            $get_level_three_2 = $currentLevel == 3 || $user->process_level >= 3 ? 'in-complete' : '';
            @endphp
            <div class="indicator-line  {{$get_level_three_1}}"></div>
            <div class="step step3 {{$get_level_three_1}} {{$get_level_three_2}}">
                @if(isset($user) && ($currentLevel == 3 || $user->process_level >= 3))
                <a href="/onboarding/cokxgpauql/{{$user->id}}">
                    <div class="step-icon">03</div>
                </a>
                @else
                <div class="step-icon">03</div>
                @endif
                <p>Pay-in settings</p>
            </div>
            @php
            $get_level_four_1 = $currentLevel == 4 || $user->process_level == 4 ? 'active' : '';
            $get_level_four_2 = $currentLevel == 4 || $user->process_level == 4 ? 'in-complete' : '';
            @endphp
            <div class="indicator-line {{$get_level_four_1}}"></div>
            <div class="step step4 {{$get_level_four_1}} {{$get_level_four_2}}">
                @if(isset($user) && ($currentLevel == 4 || $user->process_level >= 3))
                <a href="/onboarding/xyhsrpzfip/{{$user->id}}">
                    <div class="step-icon">04</div>
                </a>
                @else
                <div class="step-icon">04</div>
                @endif
                <p>Business Info</p>
            </div>
        </section>
    </div>
    <div style="margin-top: 9rem !important;">
        <p class="f-20 fw-500 textgray mb-3 px-15">Upload Documents <span class="f-15 textred" style="color: red;">(Note PDF & Images are only allowed up to 2MB in size)</span></p>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                <div class="mb-3">
                    {{Form::label('companypan', 'Company PanCard', array('class' => 'control-label'))}}
                    <div class="d-flex align-items-center">

                        <input type="file" name="companypan" class="inputfile form-control inputfile-2 file-upload" id="companypan" accept=".pdf, image/*" onchange="checkFileExtension(1,'companypan')">
                        <div class="viewfile companypan">
                            @if(isset($user->userDocuments) && $user->userDocuments->count() > 0)
                            @foreach($user->userDocuments as $document)
                            @if($document['document_id'] == 1)
                            <a href="{{$document['base_url']}}/{{$document['document_path']}}" class="view-align" target="_blank">View</a>
                            @break
                            @endif
                            @endforeach
                            @endif
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                <div class="mb-3">
                    {{Form::label('companygst', 'Company GST', array('class' => 'control-label'))}}

                    <div class="d-flex align-items-center">
                        <input type="file" name="companygst" class="inputfile form-control inputfile-2 file-upload" id="companygst" accept=".pdf, image/*" onchange="checkFileExtension(2,'companygst')">
                        <div class="viewfile companygst">
                            @if(isset($user->userDocuments) && $user->userDocuments->count() > 0)
                            @foreach($user->userDocuments as $document)
                            @if($document['document_id'] == 2)
                            <a href="{{$document['base_url']}}/{{$document['document_path']}}" class="view-align" target="_blank">View</a>
                            @break
                            @endif
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                <div class="mb-3">
                    {{Form::label('bankstatement', 'Bank Statement', array('class' => 'control-label'))}}
                    <div class="d-flex align-items-center">
                        <input type="file" name="bankstatement" class="inputfile form-control inputfile-2 file-upload" id="bankstatement" accept=".pdf, image/*" onchange="checkFileExtension(3,'bankstatement')">
                        <div class="viewfile bankstatement">
                            @if(isset($user->userDocuments) && $user->userDocuments->count() > 0)
                            @foreach($user->userDocuments as $document)
                            @if($document['document_id'] == 3)
                            <a href="{{$document['base_url']}}/{{$document['document_path']}}" class="view-align" target="_blank">View</a>
                            @break
                            @endif
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                <div class="mb-3">
                    {{Form::label('cancelcheque', 'Cancel Cheque', array('class' => 'control-label'))}}
                    <div class="d-flex align-items-center">
                        <input type="file" name="cancelcheque" class="inputfile form-control inputfile-2 file-upload" id="cancelcheque" accept=".pdf, image/*" onchange="checkFileExtension(4,'cancelcheque')">
                        <div class="viewfile cancelcheque">
                            @if(isset($user->userDocuments) && $user->userDocuments->count() > 0)
                            @foreach($user->userDocuments as $document)
                            @if($document['document_id'] == 4)
                            <a href="{{$document['base_url']}}/{{$document['document_path']}}" class="view-align" target="_blank">View</a>
                            @break
                            @endif
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                <div class="mb-3">
                    {{Form::label('certificationIncorporation', 'Certification of Incorporation', array('class' => 'control-label'))}}
                    <div class="d-flex align-items-center">
                        <input type="file" name="certificationIncorporation" class="inputfile form-control inputfile-2 file-upload" id="certificationIncorporation" accept=".pdf, image/*" onchange="checkFileExtension(5,'certificationIncorporation')">
                        <div class="viewfile certificationIncorporation">
                            @if(isset($user->userDocuments) && $user->userDocuments->count() > 0)
                            @foreach($user->userDocuments as $document)
                            @if($document['document_id'] == 5)
                            <a href="{{$document['base_url']}}/{{$document['document_path']}}" class="view-align" target="_blank">View</a>
                            @break
                            @endif
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                <div class="mb-3">
                    {{Form::label('mom', 'MOA', array('class' => 'control-label'))}}
                    <div class="d-flex align-items-center">
                        <input type="file" name="mom" class="inputfile form-control inputfile-2 file-upload" id="mom" accept=".pdf, image/*" onchange="checkFileExtension(6,'mom')">
                        <div class="viewfile mom">
                            @if(isset($user->userDocuments) && $user->userDocuments->count() > 0)
                            @foreach($user->userDocuments as $document)
                            @if($document['document_id'] == 6)
                            <a href="{{$document['base_url']}}/{{$document['document_path']}}" class="view-align" target="_blank">View</a>
                            @break
                            @endif
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                <div class="mb-3">
                    {{Form::label('aoa', 'AOA', array('class' => 'control-label'))}}
                    <div class="d-flex align-items-center">
                        <input type="file" name="aoa" class="inputfile form-control inputfile-2 file-upload" id="aoa" accept=".pdf, image/*" onchange="checkFileExtension(7,'aoa')">
                        <div class="viewfile aoa">
                            @if(isset($user->userDocuments) && $user->userDocuments->count() > 0)
                            @foreach($user->userDocuments as $document)
                            @if($document['document_id'] == 7)
                            <a href="{{$document['base_url']}}/{{$document['document_path']}}" class="view-align" target="_blank">View</a>
                            @break
                            @endif
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                <div class="mb-3">
                    {{Form::label('authsignatorypancard', 'Auth Signatory Pancard', array('class' => 'control-label'))}}<span style="color: red;font-size: 1.5rem;">*</span>
                    <div class="d-flex align-items-center">
                        <input type="file" name="authsignatorypancard" class="inputfile form-control inputfile-2 file-upload" id="authsignatorypancard" accept=".pdf, image/*" onchange="checkFileExtension(8,'authsignatorypancard')">
                        <div class="viewfile authsignatorypancard">
                            @if(isset($user->userDocuments) && $user->userDocuments->count() > 0)
                            @foreach($user->userDocuments as $document)
                            @if($document['document_id'] == 8)
                            <a href="{{$document['base_url']}}/{{$document['document_path']}}" class="view-align" target="_blank">View</a>
                            @break
                            @endif
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                <div class="mb-3">
                    {{Form::label('authSignatoryAadharcard', 'Auth Signatory Aadhar Card', array('class' => 'control-label'))}}
                    <div class="d-flex align-items-center">
                        <input type="file" name="authSignatoryAadharcard" class="inputfile form-control inputfile-2 file-upload" id="authSignatoryAadharcard" accept=".pdf, image/*" onchange="checkFileExtension(9,'authSignatoryAadharcard')">
                        <div class="viewfile authSignatoryAadharcard">
                            @if(isset($user->userDocuments) && $user->userDocuments->count() > 0)
                            @foreach($user->userDocuments as $document)
                            @if($document['document_id'] == 9)
                            <a href="{{$document['base_url']}}/{{$document['document_path']}}" class="view-align" target="_blank">View</a>
                            @break
                            @endif
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center">
            {!! Form::open(['url' => route('onboarding.complete'), 'method' => 'POST', 'id' => 'completedsubmits']) !!}
            {!! Form::hidden('user_id', $user->id) !!}
            {!! Form::button('Complete', ['type' => 'submit', 'class' => 'outline-btn bgg-btn' , 'id' => 'completesubmitbutton']) !!}
            {!! Form::close() !!}
        </div>
    </div>
</div>
<script>
    function checkFileExtension(document_id, classField) {
        var fileInput = $("#" + classField)[0];

        if (fileInput.files.length === 0) {
            toastr.error("Please select a file to upload.");
            return false;
        }

        var file = fileInput.files[0];
        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf)$/i; // Using lowercase for allowed extensions
        var maxSizeInBytes = 2 * 1024 * 1024; // Set to 2MB

        if (!allowedExtensions.exec(file.name)) {
            toastr.error("Please upload a file with a valid extension (jpg, jpeg, png, pdf).");
            $("#" + classField).val('');
            return false;
        }

        if (file.size > maxSizeInBytes) {
            toastr.error("File size must not exceed 2MB.");
            $("#" + classField).val('');
            return false;
        }

        var userId = <?php echo $user->id; ?>;

        var formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('docfile', file);
        formData.append('document_id', document_id);
        formData.append('user_id', userId);
        $("div#divLoading").removeClass('hide');
        $("div#divLoading").addClass('show');
        $.ajax({
            url: '{{ route("doc.file.upload") }}',
            type: 'POST',
            data: formData,
            processData: false, // Prevent jQuery from transforming the data
            contentType: false, // Ensure the content type is set correctly
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            },
            success: function(response) {
                if (response.status == 'success') {

                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
                $("div#divLoading").removeClass('show');
                $("div#divLoading").addClass('hide');
            }
        });
    }



    $(document).ready(function() {

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });

        $('#completesubmitbutton').click(function(event) {
            console.log("UUUU");
            event.preventDefault();
            swalWithBootstrapButtons.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Complete it!",
                cancelButtonText: "Cancel!",
                reverseButtons: true
            }).then((result) => {
                console.log(result);
                if (result.isConfirmed) {
                    $('#completedsubmits').submit();
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire({
                        title: "Cancelled",
                        text: "Your imaginary file is safe :)",
                        icon: "error"
                    });
                }
            });
        });

    });
</script>

@endsection