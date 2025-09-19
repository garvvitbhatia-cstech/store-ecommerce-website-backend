@extends('layout.admin')
@section('title', 'Edit Country')

@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Edit Country</h2>
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
                                        {{ Form::open(array('url' => array('/admins/edit-country',Crypt::encrypt($country->id)),'id' => 'pageForm', 'method' => 'post', 'files' => true)) }}
                                            @csrf
                                            <input type="hidden" name="edit_token" id="edit_token" value="{{ Crypt::encrypt($country->id); }}">                            				<input type="hidden" name="old_image" id="old_image" value="{{ $country->flag_image }}">
                                            <div class="form-group form-float">
                                            	<label class="form-label">Country Name</label>
                                                <div class="form-line">
                                                    <input type="text" name="country_name" id="country_name" value="{{ $country->country_name }}" class="form-control">
                                                </div>
                                                @error('country_name')
                                                <label id="country_name-error" class="error" for="country_name">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Country Code</label>
                                                <div class="form-line">
                                                    <input type="text" name="country_code" id="country_code" value="{{ $country->country_code }}" class="form-control">
                                                </div>
                                                @error('country_code')
                                                <label id="country_code-error" class="error" for="country_code">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Phone Code</label>
                                                <div class="form-line">
                                                    <input type="text" name="phonecode" id="phonecode" maxlength="5" value="{{ $country->phonecode }}" class="form-control">                                     
                                                </div>
                                                @error('phonecode')
                                                <label id="phonecode-error" class="error" for="phonecode">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Phone Number Format</label>
                                                <div class="form-line">
                                                    <input type="text" maxlength="20" name="phone_no_format" value="{{ $country->phone_no_format }}" id="phone_no_format" class="form-control">
                                                </div>
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
                                            </div>
                                            </div>
                                            
                                            @php
												$count = 0;											
                                            if(!empty($country->zipcode_format)){ 
                                                    $explode = explode(',',$country->zipcode_format);
                                                    foreach($explode as $key => $zipformat):
                                             @endphp
                                                <div class="row appendRowDiv">
                                                	<div class="form-group form-float">
                                                        <div class="col-md-6 col-lg-6">
                                                        <div class="form-line">
                                                            <input type="text" name="zipcode_format[]" maxlength="10" value="{{ $zipformat }}" id="zipcode_format{{ $key+mt_rand() }}" class="form-control">
                                                            <label class="form-label">Zipcode Format</label>
                                                        </div>
                                                        </div>
                                                        <div class="col-md-6 col-lg-6">
                                                            <button type="button" class="btn btn-danger" onclick="removeZipCode('{{ $zipformat }}','{{ Crypt::encrypt($country->id) }}')">Remove</button>
                                                        </div>                                             
                                                    </div>                                          
                                                </div>
                                                @endforeach

                                            @php } @endphp
                                            
                                            
                                            
                                            <div id="appendZipCode"></div>
                                            
                                            <div class="form-group">
                                            @php
											if($country->flag_image != ""){
                                            @endphp
                                            <img width="100px;" alt="" title="" src="{{ URL::asset('public/assets/images/admin/countries/') }}/{!! $country->flag_image !!}" />
                                            @php } @endphp
                                            </div>
                                            <div class="form-group form-float">
                                                <div class="">
                                                	<label class="form-label">Flag Image</label>
                                                    <input type="file" name="flag_image" id="flag_image" class="form-control">                                                </div>
                                                @error('flag_image')
                                                <label id="flag_image-error" class="error" for="flag_image">{{ $message }}</label>
                                                @enderror
                                            </div>
											<label class="form-label">Status</label>
                                            <div class="form-group">
                                                <input type="checkbox" id="status" value="1" {{$country->status == 1 ? "checked" : "" }} name="status" class="filled-in" />
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

<script>
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
					required: true
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
	function removeZipCode(row,editId){
		if(editId != '' && row != ''){			
			$('#zipcodeDeleteId').attr('onclick','deleteZipCode("'+row+'","'+editId+'")');
			$('#removeZipCodePopup').modal('show');
		}
	}
	
	function deleteZipCode(row,editId){
		if(editId != '' && row != ''){
			var removeZipcodeUrl = "{{url('admins/removeZipcode')}}";
			$.ajax({
				type: 'POST',
				url: removeZipcodeUrl,
				headers:{
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},				
				data: {value:row,editId:editId},
				success: function(msg){
					window.location.reload(true);
				}
			});
		}else{
			$('#errMsgId').html('Something went wrong.');
			$('#errorMsgPopup').modal('show');	
		}
		return false;
	}
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

<div class="modal fade fancyPopup" id="removeZipCodePopup" role="dialog" data-keyboard="false" data-backdrop="static">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h1 class="modal-title text-center">Alert!</h1>
    </div>
    <div class="modal-body" style="padding:15px 30px !important;">
     <div class="rowField_col">
        <div class="row">
            <div class="col-sm-12">
                <div class="help_from">
                <p>Are you sure want to delete this zipcode?</p>
                </div>
           </div>
        </div>
     </div>         
     <div class="row twoButton text-center">
        <div class="col-sm-12">
        	<button type="button" id="zipcodeDeleteId" data-dismiss="modal" onclick="" class="btn btn-outline btn-success">Yes</button>
         	<button type="button" data-dismiss="modal" class="btn btn-outline btn-warning">No</button>
        </div>
     </div>         
     </div>
  </div>
   </div>
</div>

@endsection