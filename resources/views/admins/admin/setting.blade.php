@extends('layout.admin')
@section('title', 'My Account')

@section('content')
 
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>My Account</h2>
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
                                        {{ Form::open(array('url' => '/admins/setting/','id' => 'pageForm', 'method' => 'post', 'files' => true)) }}
                                            @csrf
                                            <input type="hidden" name="old_image" id="old_image" value="{{ $setting->logo; }}"/>
                                            <input type="hidden" name="company_logo" id="company_logo" value="{{ $setting->logo; }}">
                                            <div class="form-group form-float">
                                            	<label class="form-label">Admin Email</label> 
                                                <div class="form-line">
                                                    <input type="text" name="admin_email" value="{{ $setting->admin_email }}" id="admin_email" class="form-control">                                                </div>
                                                @error('admin_email')
                                                <label id="admin_email-error" class="error" for="admin_email">{{ $message }}</label>
                                                @enderror
                                            </div>            
                                            <div class="form-group form-float">
                                            	<label class="form-label">Company Name</label>
                                                <div class="form-line">
                                                    <input type="text" name="company_name" value="{{ $setting->company_name }}" id="company_name" class="form-control">                                                </div>
                                                @error('company_name')
                                                <label id="company_name-error" class="error" for="company_name">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Business Address</label>
                                                <div class="form-line">
                                                	<textarea name="business_address" id="business_address" class="form-control">{{ $setting->business_address }}</textarea>
                                                </div>
                                                @error('business_address')
                                                <label id="business_address-error" class="error" for="business_address">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Business Address 2</label>
                                                <div class="form-line">
                                                	<textarea name="business_address2" id="business_address2" class="form-control">{{ $setting->business_address2 }}</textarea>
                                                </div>
                                                @error('business_address2')
                                                <label id="business_address2-error" class="error" for="business_address2">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Company Contact Number</label>
                                                <div class="form-line">
                                                    <input type="text" maxlength="10" name="mobile" value="{{ $setting->mobile }}" id="mobile" class="form-control">                                                </div>
                                                @error('mobile')
                                                <label id="mobile-error" class="error" for="mobile">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Footer Content</label>
                                               	<div class="form-line">
                                                    <input type="text" name="footer_content" id="footer_content" value="{{ $setting->footer_content }}" class="form-control">
                                                </div>
                                                @error('footer_content')
                                                <label id="footer_content-error" class="error" for="footer_content">{{ $message }}</label>
                                                @enderror
                                            </div>  
                                            <div class="form-group">
                                               <div class="panel-body excerpt_img" style="display:none;">
                                                  <div class="progress progress-striped active" role="progressbar">
                                                     <div class="progress-bar progress-bar-primary myprogress2" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 0%">0%</span></div>
                                                  </div>
                                               </div>
                                               <div class="msg2">
                                               	<img width="100px;" alt="" title="" src="{{ URL::asset('public/assets/images/admin/') }}/{!! $setting->logo !!}" />
                                                </div>
                                            </div>   
                                            <div class="form-group">
                                               	<label class="form-label">Company Logo</label>
                                                <input type="file" name="logo" onchange="company_image()" id="CompanyLogo" class="form-control"/>
                                            </div>                                      
                                            <button type="submit" id="submitForm" class="btn btn-primary m-t-15 waves-effect">Submit</button>
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
	$('#mobile').filter_input({regex:'[0-9]'});
	$(document).on('click', '#submitForm',function(){

		$("#pageForm").validate({
			errorElement: "label",
			 errorPlacement: function (error, element) {
				$(element).parents('.form-group').append(error);
			},		
			rules: {
				'admin_email': {
					required: true,
					email: true,
				},
				'company_name': {
					required: true,
				},
				'mobile': {
					required: true,
				}
			},
			messages: {
				'admin_email': {
					required: "Please enter email.",
					email: "Please enter valid email address.",
				},
				'company_name': {
					required: "Please enter company name.",
				},
				'mobile': {
					required: "Please enter contact.",
				}     
			},
			submitHandler: function(form){
				$('#submitForm').html('Processing...');
				form.submit();
			}
		});
	
	});
});

function company_image(){
	var manish = 1;
	$('#errorMsgPopUp').html('Something went wrong. Please try again.');
	$('.myprogress2').css('width', '0');
	$('.msg2').html('');	
	var ext = $('#CompanyLogo').val().split('.').pop().toLowerCase();

	if($.inArray(ext, ['jpeg', 'jpg', 'png']) == -1){
		$('#errorMsgPopUp').html('Only jpg, png files are allowed.');
		$('#Error500').modal('show');
		manish = 0;
		return false;
	}

	if(manish == 1){
		$('.excerpt_img').show();
		var CampaignAttachment = $('#CompanyLogo').val();
		$('#uploadFile').val(CampaignAttachment);
		
		var formData = new FormData();
		formData.append('CompanyLogo', $('#CompanyLogo')[0].files[0]);
		formData.append('old_banner', $('#old_image').val());
		$.ajax({
			url: "{{ url('admins/change-logo') }}",
			data: formData,
			processData: false,
			contentType: false,
			type: 'POST',
			dataType: 'JSON',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			xhr:function(){
				var xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener("progress",function(evt){
					if (evt.lengthComputable){
						var percentComplete = evt.loaded / evt.total;
						percentComplete = parseInt(percentComplete * 100);
						$('.myprogress2').text(percentComplete + '%');
						$('.myprogress2').css('width', percentComplete + '%');
					}
				}, false);
				return xhr;
			},
			success:function(response){
				console.log(response);
				if(response.data.msg == 'Error'){
					$('.excerpt_img').hide();
					$('#Error500').modal('show');
					return false;
				}else{
					setTimeout(function(){
						$('.excerpt_img').hide();
						$('.msg2').html('<div style="margin-bottom:10px;"><img src="'+response.data.path + '"width="100"/><div class="slider_action" style="width:200px;padding-top:10px; display:block;"><div class="clear"></div></div></div>');
						//$('.msg2').html('success');
					}, 1000);
					$('#company_logo').val(response.data.name);
				}
			}
		});
	}
}
</script>

@endsection