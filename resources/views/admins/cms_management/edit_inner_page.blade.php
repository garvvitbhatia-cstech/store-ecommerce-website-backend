@extends('layout.admin')
@section('title', 'Edit Inner Page')
@section('content')
<section class="content">
   <div class="container-fluid">
      <div class="block-header">
         <h2>Edit Inner Page</h2>
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
                              {{ Form::open(array('url' => array('/admins/edit-inner-page',Crypt::encrypt($innerpage->id)),'id' => 'pageForm', 'method' => 'post', 'files' => true)) }}
                              @csrf
                              <input type="hidden" name="edit_token" id="edit_token" value="{{ Crypt::encrypt($innerpage->id); }}">
                              <input type="hidden" name="old_banner" id="old_banner" value="{{ $innerpage->banner }}">
                              <div class="form-group form-float">
                              	<label class="form-label">Title</label>
                                 <div class="form-line">
                                    <input type="text" id="title" name="title" value="{{ $innerpage->title }}" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                 </div>
                                 @error('title')
                                 <label id="title-error" class="error" for="title">{{ $message }}</label>
                                 @enderror 
                              </div>
                              <div class="form-group form-float">
                              	<label class="form-label">Description</label>
                                 <div class="form-line">
                                    <textarea id="description" name="description" onkeyup="checkError(this.id);" rows="5" confirmation="false" class="form-control">{{ $innerpage->description }}</textarea>                                    
                                 </div>
                                 @error('description')
                                 <label id="description-error" class="error" for="description">{{ $message }}</label>
                                 @enderror 
                              </div>
                              <div class="form-group form-float">
                              	<label class="form-label">Heading</label>
                                 <div class="form-line">
                                    <input type="text" id="heading" name="heading" value="{{ $innerpage->heading }}" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                 </div>
                                 @error('heading')
                                 <label id="heading-error" class="error" for="heading">{{ $message }}</label>
                                 @enderror 
                              </div>
                              <div class="form-group form-float">
                              	<label class="form-label">Sub Heading</label>
                                 <div class="form-line">
                                    <input type="text" id="sub_heading" name="sub_heading" value="{{ $innerpage->sub_heading }}" onkeyup="checkError(this.id);" confirmation="false" class="form-control">                                    
                                 </div>
                                 @error('sub_heading')
                                 <label id="sub_heading-error" class="error" for="sub_heading">{{ $message }}</label>
                                 @enderror 
                              </div>
                              
                              <label class="form-label">Banner Icon</label>
                              <div class="form-group form-float">
                                 <img width="100px;" alt="" title="" src="{{ URL::asset('public/assets/images/admin/banners/') }}/{!! $innerpage->banner !!}" />
                                 <div class="form-line">
                                    <input type="file" name="banner" id="banner" accept="image/jpeg, image/png" class="form-control">
                                 </div>
                                 @error('banner')
                                 <label id="banner-error" class="error" for="banner">{{ $message }}</label>
                                 @enderror 
                              </div>
                              <label class="form-label">Banner Status</label>
                              <div class="form-group">
                                 <input type="checkbox" id="banner_status" value="1" {{$innerpage->banner_status == 1 ? "checked" : "" }} name="banner_status" class="filled-in" />
                                 <label for="banner_status">Active</label>
                              </div>
                              <div class="form-group form-float">
                              	<label class="form-label">SEO Title</label>
                                 <div class="form-line">
                                    <input type="text" id="seo_title" name="seo_title" value="{{ $innerpage->seo_title }}" onkeyup="checkError(this.id);" confirmation="false" class="form-control">                                    
                                 </div>
                                 @error('seo_title')
                                 <label id="seo_title-error" class="error" for="seo_title">{{ $message }}</label>
                                 @enderror 
                              </div>
                              <div class="form-group form-float">
                              	<label class="form-label">SEO Description</label>
                                 <div class="form-line">
                                    <textarea id="seo_description" name="seo_description" onkeyup="checkError(this.id);" rows="5" confirmation="false" class="form-control">{{ $innerpage->seo_description }}</textarea>                                    
                                 </div>
                                 @error('seo_description')
                                 <label id="seo_description-error" class="error" for="seo_description">{{ $message }}</label>
                                 @enderror 
                              </div>
                              <div class="form-group form-float">
                              	<label class="form-label">SEO Keyword</label>
                                 <div class="form-line">
                                    <textarea id="seo_keyword" name="seo_keyword" onkeyup="checkError(this.id);" rows="5" confirmation="false" class="form-control">{{ $innerpage->seo_keyword }}</textarea>                                    
                                 </div>
                                 @error('seo_keyword')
                                 <label id="seo_keyword-error" class="error" for="seo_keyword">{{ $message }}</label>
                                 @enderror 
                              </div>
                              <div class="form-group form-float">
                              	<label class="form-label">SEO Robots</label>
                                 <div class="form-line">
                                    <select id="robot_tags" name="robot_tags" value="{{ $innerpage->robot_tags }}" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                    <option {{$innerpage->robot_tags == 'index,follow' ? "selected" : "" }} value="index,follow">index,follow</option>
                                    <option {{$innerpage->robot_tags == 'index,nofollow' ? "selected" : "" }} value="index,nofollow">index,nofollow</option>
                                    <option {{$innerpage->robot_tags == 'noindex,follow' ? "selected" : "" }} value="noindex,follow">noindex,follow</option>
                                    <option {{$innerpage->robot_tags == 'noindex,nofollow' ? "selected" : "" }} value="noindex,nofollow">noindex,nofollow</option>
                                    </select>                                    
                                 </div>
                                 @error('robot_tags')
                                 <label id="robot_tags-error" class="robot_tags" for="robot_tags">{{ $message }}</label>
                                 @enderror 
                              </div>
                              <label class="form-label">Status</label>
                              <div class="form-group">
                                 <input type="checkbox" id="status" value="1" {{$innerpage->status == 1 ? "checked" : "" }} name="status" class="filled-in" />
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
	CKEDITOR.replace('description');
	
   	$('#title').filter_input({regex:'[a-z- &A-Z]'});
   		
   	$(document).on('click', '#submitBtn',function(){
   
   		$("#pageForm").validate({
   			errorElement: "label",
   			errorPlacement: function (error, element){
   				$(element).parents('.form-group').append(error);
   			},
   			rules: {
   				'title': {
   					required: true,
   				}
   			},
   			messages: {
   				'title': {
   					required: "Please enter title.",
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