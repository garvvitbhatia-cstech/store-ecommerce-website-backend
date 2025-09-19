@extends('layout.admin')
@section('title', 'Change Password')

@section('content')
 
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Update Password</h2>
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
                                        {{ Form::open(array('url' => '/admins/change-password/','id' => 'pageForm', 'method' => 'post')) }}
                                            @csrf
                                            <input type="hidden" id="passToken" value="1">
                                            <div class="form-group form-float">
                                            	<label class="form-label">Old Password</label> 
                                                <div class="form-line">
                                                    <input type="text" name="old_password" id="old_password" class="form-control">                                                </div>
                                                @error('old_password')
                                                <label id="old_password-error" class="error" for="old_password">{{ $message }}</label>
                                                @enderror
                                            </div>
            
                                            <div class="form-group form-float">
                                            	<label class="form-label">New Password</label>
                                                <div class="form-line">
                                                    <input type="text" name="new_password" id="new-password" class="form-control">                                                </div>
                                                @error('new_password')
                                                <label id="new_password-error" class="error" for="new_password">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Confirm Password</label>
                                                <div class="form-line">
                                                    <input type="text" name="confirm_password" id="confirm_password" class="form-control">
                                                </div>
                                                @error('confirm_password')
                                                <label id="confirm_password-error" class="error" for="confirm_password">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        
                                            <button type="submit" id="submitForm" class="btn btn-primary m-t-15 waves-effect">Change Password</button>
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
	$(document).on('click', '#submitForm',function(){

		$("#pageForm").validate({
			errorElement: "label",
			 errorPlacement: function (error, element) {
				$(element).parents('.form-group').append(error);
			},		
			rules: {
				'old_password': {
					required: true,
				},
				'new_password': {
					required: true,
				},
				'confirm_password': {
					required: true,
					equalTo: '#new-password'
				}
			},
			messages: {
				'old_password': {
					required: "Please enter old password.",
				},
				'new_password': {
					required: "Please enter new password.",
				},
				'confirm_password': {
					required: "Please enter confirm password.",
					equalTo: "Confirm password must be equal to new password.",
				}     
			},
			submitHandler: function(form){
				if($('#passToken').val() == 1){
					$('#submitForm').html('Processing...');
					form.submit();
				}else{
					return false;
				}
			}
		});
	
	});
});
</script>

@endsection