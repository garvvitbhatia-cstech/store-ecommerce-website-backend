@extends('layout.admin')
@section('title', 'Edit Testimonial')
@section('content')
<section class="content">
   <div class="container-fluid">
      <div class="block-header">
         <h2>Edit Testimonial</h2>
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
                              {{ Form::open(array('url' => array('/admins/edit-testimonial',Crypt::encrypt($testimonial->id)),'id' => 'pageForm', 'method' => 'post', 'files' => true)) }}
                              @csrf
                              <input type="hidden" name="edit_token" value="{{ Crypt::encrypt($testimonial->id); }}">
                              <input type="hidden" name="old_banner" value="{{ $testimonial->profile; }}">
                              <div class="form-group form-float">
                              	<label class="form-label">Username</label>
                                 <div class="form-line">
                                    <input type="text" id="username" name="username" value="{{ $testimonial->username; }}" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                 </div>
                                 @error('username')
                                 <label id="username-error" class="error" for="username">{{ $message }}</label>
                                 @enderror 
                              </div>
                              <div class="form-group form-float">
                              	<label class="form-label">Testimonial</label>
                                 <div class="form-line">
                                 <textarea id="testimonial" rows="6" name="testimonial" onkeyup="checkError(this.id);" confirmation="false" class="form-control">{{ $testimonial->testimonial; }}</textarea>
                                 </div>
                                 @error('testimonial')
                                 <label id="testimonial-error" class="error" for="testimonial">{{ $message }}</label>
                                 @enderror 
                              </div>
                              @if($testimonial->profile != '')
                              <img width="100px;" alt="" title="" src="{{ URL::asset('public/assets/images/admin/gallery/') }}/{!! $testimonial->profile !!}" />
                              @endif
                              <div class="form-group form-float">
                              	<label class="form-label">Profile</label>
                                 <div class="form-line">
                                    <input type="file" id="profile" name="profile" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                 </div>
                                 @error('profile')
                                 <label id="profile-error" class="error" for="profile">{{ $message }}</label>
                                 @enderror
                              </div>
                              <label class="form-label">Status</label>
                              <div class="form-group">
                                 <input type="checkbox" id="status" value="1" {{$testimonial->status == 1 ? "checked" : "" }} name="status" class="filled-in" />
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
   	
	$(document).on('click', '#submitBtn',function(){
   
   		$("#pageForm").validate({
   			errorElement: "label",
   			errorPlacement: function (error, element){
   				$(element).parents('.form-group').append(error);
   			},
   			rules: {
   				'username': {
   					required: true,
   				},
				'testimonial': {
   					required: true,
   				}
   			},
   			messages: {
   				'username': {
   					required: "Please enter username.",
   				},
				'testimonial': {
   					required: "Please enter testimonial.",
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