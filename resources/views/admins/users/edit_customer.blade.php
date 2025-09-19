@extends('layout.admin')
@section('title', 'Edit Customer')

@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Edit Customer</h2>
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
                                        {{ Form::open(array('url' => array('/admins/edit-user',Crypt::encrypt($user->id)),'id' => 'pageForm', 'method' => 'post', 'files' => true)) }}
                                            @csrf                                            
                                            <div class="form-group form-float">
                                            	<label class="form-label">Full Name</label>
                                                <div class="form-line">
                                                    <input type="text" name="full_name" id="full_name" value="{{ $user->full_name }}" class="form-control">
                                                </div>
                                                @error('full_name')
                                                <label id="full_name-error" class="error" for="full_name">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Email</label>
                                                <div class="form-line">
                                                    <input type="text" name="email" id="email" value="{{ $user->email }}" class="form-control">
                                                </div>
                                                @error('email')
                                                <label id="email-error" class="error" for="email">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Password</label>
                                                <div class="form-line">
                                                    <input type="text" name="password" id="password" value="{{ Crypt::decrypt($user->password) }}" class="form-control">
                                                </div>
                                                @error('password')
                                                <label id="email-error" class="error" for="password">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Contact</label>
                                                <div class="form-line">
                                                    <input type="text" name="contact" maxlength="10" id="contact" value="{{ $user->contact }}" class="form-control">
                                                </div>
                                                @error('contact')
                                                <label id="contact-error" class="error" for="contact">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Address</label>
                                                <div class="form-line">
                                                	<textarea name="address" id="address" class="form-control">{{ $user->address }}</textarea>
                                                </div>
                                                @error('address')
                                                <label id="address-error" class="error" for="address">{{ $message }}</label>
                                                @enderror
                                            </div>
											<label class="form-label">Status</label>
                                            <div class="form-group">
                                                <input type="checkbox" id="status" value="1" {{$user->status == 1 ? "checked" : "" }} name="status" class="filled-in" />
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
	$('#contact').filter_input({regex:'[0-9]'});
	$('#full_name').filter_input({regex:'[a-z A-Z]'});
		
	$(document).on('click', '#submitBtn',function(){

		$("#pageForm").validate({
			errorElement: "label",
			errorPlacement: function (error, element){
				$(element).parents('.form-group').append(error);
			},
			rules: {
				'full_name': {
					required: true,
				},
				'contact': {
					required: true,
				},
				'email': {
					required: true,
					email: true
				},
				'password': {
					required: true,
				}
			},
			messages: {
				'full_name': {
					required: "Please enter name.",
				},
				'contact': {
					required: "Please enter contact.",
				},
				'email': {
					required: "Please enter email.",
					email: "Please enter valid email.",
				},
				'password': {
					required: "Please enter password."
				}
			},
			submitHandler: function(form){
				$('#submitBtn').html('Processing...');
				form.submit();				
			}
		});
	
	});
});
</script>

@endsection