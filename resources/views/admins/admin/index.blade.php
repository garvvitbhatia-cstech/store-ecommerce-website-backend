@extends('layout.admin')
@section('title', 'My Account')

@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Update Account Information</h2>
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
                                        {{ Form::open(array('url' => '/admins/','id' => 'pageForm', 'method' => 'post', 'files' => true)) }}
                                            @csrf
                           					<input type="hidden" name="old_image" id="old_image" value="{{ $record->profile; }}"/>
                                            <input type="hidden" name="profile_pic" id="profile_pic" value="{{ $record->profile; }}">
                                            <div class="form-group form-float">
                                            	<label class="form-label">Name</label>
                                                <div class="form-line">
                                                    <input type="text" name="name" value="{{ $record->name; }}" class="form-control">                                                </div>
                                                @error('name')
                                                <label id="name-error" class="error" for="name">{{ $message }}</label>
                                                @enderror
                                            </div>            
                                            <div class="form-group form-float">
                                            	<label class="form-label">Contact</label>
                                                <div class="form-line">
                                                    <input type="text" name="contact" id="contact" value="{{ $record->contact; }}" maxlength="10" class="form-control">
                                                </div>
                                                @error('contact')
                                                <label id="name-error" class="error" for="name">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Email</label>
                                                <div class="form-line">
                                                    <input type="email" readonly="readonly" name="email" value="{{ $record->email; }}" class="form-control">
                                                </div>
                                                @error('email')
                                                <label id="name-error" class="error" for="name">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Address</label>
                                                <div class="form-line">
                                                    <textarea rows="4" name="address" class="form-control no-resize">{!! $record->address; !!}</textarea>
                                                </div>
                                                @error('address')
                                                <label id="name-error" class="error" for="name">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                               <div class="panel-body excerpt_img" style="display:none;">
                                                  <div class="progress progress-striped active" role="progressbar">
                                                     <div class="progress-bar progress-bar-primary myprogress2" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 0%">0%</span></div>
                                                  </div>
                                               </div>
                                               <div class="msg2">
                                               	<img width="100px;" alt="" title="" src="{{ URL::asset('public/assets/images/admin/') }}/{!! $record->profile !!}" />
                                                </div>
                                            </div>   
                                            <div class="form-group">
                                               	<label class="form-label">Profile Image</label>
                                                <input type="file" name="profile" onchange="profile_image()" id="UserProfile" class="form-control"/>
                                            </div>
                                            <br>
                                            <button type="submit" id="submitForm" class="btn btn-primary m-t-15 waves-effect">Update</button>
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
	$('#contact').filter_input({regex:'[0-9]'});
	
	$(document).on('click', '#submitForm',function(){

		$("#pageForm").validate({
			errorElement: "label",
			errorPlacement: function (error, element){
				$(element).parents('.form-group').append(error);
			},
			rules: {
				'name': {
					required: true,
				},
				'contact': {
					required: true,
				},
				'email': {
					required: true,
					email: true
				}
			},
			messages: {
				'name': {
					required: "Please enter name.",
				},
				'contact': {
					required: "Please enter contact.",
				},
				'email': {
					required: "Please enter email.",
					email: "Please enter valid email.",
				}
			},
			submitHandler: function(form){
				$('#submitForm').html('Processing...');
				form.submit();				
			}
		});
	
	});
});
	
	function profile_image(){
		var manish = 1;
		$('#errorMsgPopUp').html('Something went wrong. Please try again.');
		$('.myprogress2').css('width', '0');
		$('.msg2').html('');	
		var ext = $('#UserProfile').val().split('.').pop().toLowerCase();
	
		if($.inArray(ext, ['jpeg', 'jpg', 'png']) == -1){
			$('#errorMsgPopUp').html('Only jpg, png files are allowed.');
			$('#Error500').modal('show');
			manish = 0;
			return false;
		}
	
		if(manish == 1){
			$('.excerpt_img').show();
			var CampaignAttachment = $('#UserProfile').val();
			$('#uploadFile').val(CampaignAttachment);
			
			var formData = new FormData();
			formData.append('UserProfile', $('#UserProfile')[0].files[0]);
			formData.append('old_banner', $('#old_image').val());
			$.ajax({
				url: "{{ url('admins/change-profile') }}",
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
						$('#profile_pic').val(response.data.name);
					}
				}
			});
		}
	}

</script>

@endsection