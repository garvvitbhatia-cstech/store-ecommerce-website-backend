@extends('layout.admin')
@section('title', 'Edit Brand')
@section('content')
<section class="content">
   <div class="container-fluid">
      <div class="block-header">
         <h2>Edit Brand</h2>
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
                              {{ Form::open(array('url' => array('/admins/edit-brand',Crypt::encrypt($brand->id)),'id' => 'pageForm', 'method' => 'post', 'files' => true)) }}
                              @csrf
                              <input type="hidden" name="edit_token" value="{{ Crypt::encrypt($brand->id); }}">
                              <input type="hidden" name="old_banner" value="{{ $brand->banner; }}">
                              <div class="form-group form-float">
                              	<label class="form-label">Title</label>
                                 <div class="form-line">
                                    <input type="text" id="title" name="title" value="{{ $brand->title; }}" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                 </div>
                                 @error('title')
                                 <label id="title-error" class="error" for="title">{{ $message }}</label>
                                 @enderror 
                              </div>
                              <div class="form-group form-float">
                              	<label class="form-label">Description</label>
                                 <div class="form-line">
                                 <textarea id="description" rows="6" name="description" onkeyup="checkError(this.id);" confirmation="false" class="form-control">{!! $brand->description; !!}</textarea>
                                 </div>
                                 @error('description')
                                 <label id="description-error" class="error" for="description">{{ $message }}</label>
                                 @enderror 
                              </div>
                              @if($brand->banner != '')
                              <img width="150px;" alt="" title="" src="{{ URL::asset('public/assets/images/admin/gallery/') }}/{!! $brand->banner !!}" />
                              @endif
                              <div class="form-group form-float">
                              	<label class="form-label">Banner</label>
                                 <div class="form-line">
                                    <input type="file" id="banner" name="banner" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                 </div>
                                 @error('banner')
                                 <label id="banner-error" class="error" for="banner">{{ $message }}</label>
                                 @enderror
                              </div>
                              <label class="form-label">Status</label>
                              <div class="form-group">
                                 <input type="checkbox" id="status" value="1" {{$brand->status == 1 ? "checked" : "" }} name="status" class="filled-in" />
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
   				'title': {
   					required: true,
   				},
   			},
   			messages: {
   				'title': {
   					required: "Please enter title.",
   				},
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