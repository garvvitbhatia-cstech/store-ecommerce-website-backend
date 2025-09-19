@extends('layout.admin')
@section('title', 'Edit Blog') 

@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Edit Blog</h2>
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
                                        {{ Form::open(array('url' => array('/admins/edit-blog',Crypt::encrypt($blog->id)),'id' => 'pageForm', 'method' => 'post', 'files' => true)) }}
                                            @csrf                                            
                                            <div class="form-group form-float">
                                            	<label class="form-label">Category</label>
                                                <div class="form-line">
                                                	<select name="category_id" id="category_id" class="form-control">
                                                    	<option value="">Select Category</option>
                                                        @foreach($category as $key => $value)                                                        
                                                        	<option {{$blog->category_id == $key ? "selected" : "" }} value="{{ $key }}">{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('title')
                                                <label id="title-error" class="error" for="title">{{ $message }}</label>
                                                @enderror
                                            </div>                                             
                                            <div class="form-group form-float">
                                            	<label class="form-label">Title</label>
                                                <div class="form-line">
                                                    <input type="text" name="title" id="title" value="{{ $blog->title }}" class="form-control">
                                                </div>
                                                @error('title')
                                                <label id="title-error" class="error" for="title">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Description</label>
                                                <div class="form-line">
                                                    <textarea name="description" rows="6" id="description" class="form-control">{{ $blog->description }}</textarea>
                                                </div>
                                                @error('description')
                                                <label id="description-error" class="error" for="description">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Name</label>
                                                <div class="form-line">
                                                    <input type="text" name="name" id="name" placeholder="Customer Name" value="{{ $blog->name }}" class="form-control">
                                                </div>
                                                @error('name')
                                                <label id="name-error" class="error" for="name">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Tags</label>
                                                <div class="form-line form-group demo-tagsinput-area">
                                                	<!---data-role="tagsinput"---->
                                                    <input type="text" name="tags" id="tags" data-role="tagsinput" value="{{ $blog->tags }}" class="form-control">
                                                </div>
                                                @error('tags')
                                                <label id="tags-error" class="error" for="tags">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                                <label class="form-label">SEO Title</label>
                                                 <div class="form-line">
                                                    <input type="text" id="seo_title" name="seo_title" value="{{ $blog->seo_title }}" onkeyup="checkError(this.id);" confirmation="false" class="form-control">                                    
                                                 </div>
                                                 @error('seo_title')
                                                 <label id="seo_title-error" class="error" for="seo_title">{{ $message }}</label>
                                                 @enderror 
                                              </div>
                                              <div class="form-group form-float">
                                                <label class="form-label">SEO Description</label>
                                                 <div class="form-line">
                                                    <textarea id="seo_description" name="seo_description" onkeyup="checkError(this.id);" rows="5" confirmation="false" class="form-control">{{ $blog->seo_description }}</textarea>                                    
                                                 </div>
                                                 @error('seo_description')
                                                 <label id="seo_description-error" class="error" for="seo_description">{{ $message }}</label>
                                                 @enderror 
                                              </div>
                                              <div class="form-group form-float">
                                                <label class="form-label">SEO Keyword</label>
                                                 <div class="form-line">
                                                    <textarea id="seo_keyword" name="seo_keyword" onkeyup="checkError(this.id);" rows="5" confirmation="false" class="form-control">{{ $blog->seo_keyword }}</textarea>                                    
                                                 </div>
                                                 @error('seo_keyword')
                                                 <label id="seo_keyword-error" class="error" for="seo_keyword">{{ $message }}</label>
                                                 @enderror 
                                              </div>
                                              <div class="form-group form-float">
                                                <label class="form-label">SEO Robots</label>
                                                 <div class="form-line">
                                                    <select id="robot_tags" name="robot_tags" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                                        <option {{$blog->robot_tags == 'index,follow' ? "selected" : "" }} value="index,follow">index,follow</option>
                                                        <option {{$blog->robot_tags == 'index,nofollow' ? "selected" : "" }} value="index,nofollow">index,nofollow</option>
                                                        <option {{$blog->robot_tags == 'noindex,follow' ? "selected" : "" }} value="noindex,follow">noindex,follow</option>
                                                        <option {{$blog->robot_tags == 'noindex,nofollow' ? "selected" : "" }} value="noindex,nofollow">noindex,nofollow</option>
                                                        </select> 
                                                    </select>                                    
                                                 </div>
                                                 @error('robot_tags')
                                                 <label id="robot_tags-error" class="robot_tags" for="robot_tags">{{ $message }}</label>
                                                 @enderror 
                                              </div>
											<label class="form-label">Status</label>
                                            <div class="form-group">
                                                <input type="checkbox" id="status" value="1" {{$blog->status == 1 ? "checked" : "" }} name="status" class="filled-in" />
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
	// Multiple select
  $( "#tags" ).autocomplete({
		 source: function( request, response){					
			 var searchText = (request.term);
			 $.ajax({
				 url: "{{ url('admins/get-tags') }}",
				 type: 'post',
				 dataType: "json",
				 data: { query: searchText },
				 headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				 success: function(data){
					 response(data);
				 }
			 });
		 },
		 select: function(event, ui){		 
			 /*var terms = split( $('#tags').val());				
			 terms.pop();					
			 terms.push(ui.item.label);
			 terms.push("");
			 $('#tags').val(terms.join( ", " ));	
			 // Id
			 
			 terms = split($('#selected_ids').val());					
			 terms.pop();					
			 terms.push(ui.item.value);					
			 terms.push("");
			 $('#selected_ids').val(terms.join( ", " ));*/
			 
			 return false;
		 }
			   
	 });
	 
	
	$('#description').redactor(); 
	
	//$('textarea#description').ckeditor(); 

	$(document).on('click', '#submitBtn',function(){

		$("#pageForm").validate({
			errorElement: "label",
			errorPlacement: function (error, element) {
				$(element).parents('.form-group').append(error);
			},
			rules: {
				'type': {
					required: true,
				},
				'title': {
					required: true,
				}
			},
			messages: {
				'type': {
					required: "Please select type.",
				},
				'title': {
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