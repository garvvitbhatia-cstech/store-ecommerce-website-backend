@extends('layout.admin')
@section('title', 'Add Blog')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="block-header">
      <h2>Add Blog</h2>
    </div>
    <!-- Input -->
    <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="">
          <div class="body">
            <div class="row clearfix">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card"> @include('../flash-message')
                  <div class="body"> {{ Form::open(array('url' => '/admins/add-blog/','id' => 'pageForm', 'method' => 'post', 'files' => true)) }}
                    @csrf
                    <div class="form-group form-float">
                      <label class="form-label">Category</label>
                      <div class="form-line">
                        <select name="category_id" id="category_id" class="form-control">
                          <option value="">Select Category</option>
                          
                     		@foreach($category as $key => $value)                                                        
                                                        	
                          		<option value="{{ $key }}">{{ $value }}</option>
                          
                        	@endforeach
                                                    
                        </select>
                      </div>
                      @error('title')
                      <label id="title-error" class="error" for="title">{{ $message }}</label>
                      @enderror </div>
                    <div class="form-group form-float">
                      <label class="form-label">Title</label>
                      <div class="form-line">
                        <input type="text" name="title" id="title" class="form-control">
                      </div>
                      @error('title')
                      <label id="title-error" class="error" for="title">{{ $message }}</label>
                      @enderror </div>
                    <div class="form-group form-float">
                      <label class="form-label">Description</label>
                      <div class="form-line">
                        <textarea name="description" rows="6" id="description" class="form-control"></textarea>
                      </div>
                      @error('description')
                      <label id="description-error" class="error" for="description">{{ $message }}</label>
                      @enderror </div>
                    <div class="form-group form-float">
                      <label class="form-label">Name</label>
                      <div class="form-line">
                        <input type="text" name="name" id="name" placeholder="Customer Name" class="form-control">
                      </div>
                      @error('name')
                      <label id="name-error" class="error" for="name">{{ $message }}</label>
                      @enderror </div>
                    <div class="form-group form-float">
                      <label class="form-label">SEO Title</label>
                      <div class="form-line">
                        <input type="text" id="seo_title" name="seo_title" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                      </div>
                      @error('seo_title')
                      <label id="seo_title-error" class="error" for="seo_title">{{ $message }}</label>
                      @enderror </div>
                    <div class="form-group form-float">
                      <label class="form-label">SEO Description</label>
                      <div class="form-line">
                        <textarea id="seo_description" name="seo_description" onkeyup="checkError(this.id);" rows="5" confirmation="false" class="form-control"></textarea>
                      </div>
                      @error('seo_description')
                      <label id="seo_description-error" class="error" for="seo_description">{{ $message }}</label>
                      @enderror </div>
                    <div class="form-group form-float">
                      <label class="form-label">SEO Keyword</label>
                      <div class="form-line">
                        <textarea id="seo_keyword" name="seo_keyword" onkeyup="checkError(this.id);" rows="5" confirmation="false" class="form-control"></textarea>
                      </div>
                      @error('seo_keyword')
                      <label id="seo_keyword-error" class="error" for="seo_keyword">{{ $message }}</label>
                      @enderror </div>
                    <div class="form-group form-float">
                      <label class="form-label">SEO Robots</label>
                      <div class="form-line">
                        <select id="robot_tags" name="robot_tags" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                          <option value="index,follow">index,follow</option>
                          <option value="index,nofollow">index,nofollow</option>
                          <option value="noindex,follow">noindex,follow</option>
                          <option value="noindex,nofollow">noindex,nofollow</option>
                        </select>
                      </div>
                      @error('robot_tags')
                      <label id="robot_tags-error" class="robot_tags" for="robot_tags">{{ $message }}</label>
                      @enderror </div>
                    <label class="form-label">Status</label>
                    <div class="form-group">
                      <input type="checkbox" id="status" checked="checked" value="1" name="status" class="filled-in" />
                      <label for="status">Active</label>
                    </div>
                    <button type="submit" id="submitBtn" class="btn btn-primary m-t-15 waves-effect">Submit</button>
                    {{ Form::close() }} </div>
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
	$('#description').redactor();
	$('#seo_keyword').redactor();
	$('#seo_description').redactor();
	
	$(document).on('click', '#submitBtn',function(){

		$("#pageForm").validate({
			errorElement: "label",
			errorPlacement: function (error, element) {
				$(element).parents('.form-group').append(error);
			},
			rules: {
				'category_id': {
					required: true,
				},
				'title': {
					required: true,
				},
				'description': {
					required: true,
				}
			},
			messages: {
				'category_id': {
					required: "Please enter category name.",
				},
				'title': {
					required: "Please enter category name.",
				},
				'description': {
					required: "Please enter category name.",
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