@extends('layout.admin')
@section('title', 'Add Country')

@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Add Country</h2>
        </div>
        <!-- Input -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="">
                    <div class="body">                            
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="card">
                                	@include('../flash-message')
                                    <div class="body">
                                        {{ Form::open(array('url' => '/admins/add-country/','id' => 'pageForm', 'method' => 'post', 'files' => true)) }}
                                            @csrf                                                      
                                            <div class="form-group form-float">
                                            	<label class="form-label">Country Name</label>
                                                <div class="form-line">
                                                    <input type="text" name="country_name" id="country_name" class="form-control">                                                </div>
                                                @error('country_name')
                                                <label id="country_name-error" class="error" for="country_name">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Country Code</label>
                                                <div class="form-line">
                                                    <input type="text" name="country_code" id="country_code" class="form-control">                                                </div>
                                                @error('country_code')
                                                <label id="country_code-error" class="error" for="country_code">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Phone Code</label>
                                                <div class="form-line">
                                                    <input type="text" name="phonecode" id="phonecode" class="form-control">                                                </div>
                                                @error('phonecode')
                                                <label id="phonecode-error" class="error" for="phonecode">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Phone Number Format</label>
                                                <div class="form-line">
                                                    <input type="text" maxlength="15" name="phone_no_format" id="phone_no_format" class="form-control">                                                </div>
                                                @error('phone_no_format')
                                                <label id="phone_no_format-error" class="error" for="phone_no_format">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="row">
                                            <div class="form-group form-float">
                                            	<div class="col-md-6 col-lg-6">
                                                <label class="form-label">Zipcode Format</label>
                                                <div class="form-line">
                                                    <input type="text" name="zipcode_format[]" maxlength="10" id="zipcode_format0" class="form-control">                                                </div>
                                                </div>
                                              	<div class="col-md-6 col-lg-6">
                                                    <button type="button" class="btn btn-info" onclick="addMoreZipCode()">Add More Zipcode Format</button>
                                                </div>
                                                <div id="appendZipCode"></div>                                               
                                            </div>
                                            </div>
                                            <div class="form-group form-float">
                                                <div class="">
                                                    <label class="form-label">Flag Image</label>
                                                    <input type="file" name="flag_image" id="flag_image" class="form-control">
                                                </div>
                                                @error('flag_image')
                                                <label id="flag_image-error" class="error" for="flag_image">{{ $message }}</label>
                                                @enderror
                                            </div>
											<label class="form-label">Status</label>
                                            <div class="form-group">
                                                <input type="checkbox" id="status" checked="checked" value="1" name="status" class="filled-in" />
                                                <label for="status">Active</label>
											</div>
											
                                            <button type="submit" id="submitBtn" class="btn btn-primary m-t-15 waves-effect">Submit</button>
                                        {{ Form::close() }}
                                    </div>
                                </div>
                            </div>
                        </div>                             
                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Input -->  
    </div>
</section>

<script type="text/javascript">
$(document).ready(function(e){
	$('#phonecode').filter_input({regex:'[0-9]'});
	$('#country_code').filter_input({regex:'[a-zA-Z]'});
	$('#phone_no_format').filter_input({regex:'[# ()]'});
		
	$(document).on('click', '#submitBtn',function(){

		$("#pageForm").validate({
			errorElement: "label",
			errorPlacement: function (error, element){
				$(element).parents('.form-group').append(error);
			},
			rules: {
				'country_name': {
					required: true,
				},
				'country_code': {
					required: true,
				},
				'phonecode': {
					required: true,
				},
				'phone_no_format': {
					required: true,
				}
			},
			messages: {
				'country_name': {
					required: "Please enter country name.",
				},
				'country_code': {
					required: "Please enter country code.",
				},
				'phonecode': {
					required: "Please enter phonecode.",
				},
				'phone_no_format': {
					required: "Please enter phone number format."
				}
			},
			submitHandler: function(form){
				$('#submitBtn').html('Processing...');
				form.submit();				
			}
		});
	
	});
});
	function addMoreZipCode(){
		var x = $('.appendRowDiv').length;
		var max_fields = 4; //maximum input boxes allowed
		var wrapper   	= $("#appendZipCode"); //Fields wrapper
		if(x < max_fields){ //max input box allowed			
			$(wrapper).append('<div class="form-group row form-float appendRowDiv"><div class="col-md-6 col-lg-6"><div class="form-line"><input type="text" name="zipcode_format[]" maxlength="10" id="zipcode_format0" class="form-control"></div></div><div class="col-md-6 col-lg-6"><button type="button" class="btn btn-danger remove_field">Remove</button></div></div>'); //add input box
			$('.zipcode_format').filter_input({regex:'[# ]'});
			x++; //text box increment
		}else{			
			$('#errMsgId').html('You can add maximum 5 zipcode format.');
			$('#errorMsgPopup').modal('show');
		}
	}
	$("#appendZipCode").on("click",".remove_field", function(e){ //user click on remove text
		 e.preventDefault();
		 $(this).closest('.appendRowDiv').remove();
		 x--;
	});
</script>

@endsection