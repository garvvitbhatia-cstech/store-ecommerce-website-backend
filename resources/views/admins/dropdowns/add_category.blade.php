@extends('layout.admin')
@section('title', 'Add Category')

@section('content')

<section class="content"> 
    <div class="container-fluid">
        <div class="block-header">
            <h2>Add Category</h2>
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
                                        {{ Form::open(array('url' => '/admins/add-category/','id' => 'pageForm', 'method' => 'post', 'files' => true)) }}
                                            @csrf
                                                                                              
                                            <div class="form-group">
                                            	<label class="form-label">Parent Category</label>
                                                <div class="form-line">
                                                    <select id="parent_id" name="parent_id" class="form-control show-tick">
                                                        @php
                                                        	echo Helper::getSubCategory($categoryList);
                                                        @endphp
                                                    </select>        
                                               	</div>
                                                @error('parent_id')
                                                <label id="parent_id-error" class="error" for="parent_id">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Category Name</label>
                                                <div class="form-line">
                                                    <input type="text" name="title" id="title" class="form-control">
                                                </div>
                                                @error('title')
                                                <label id="title-error" class="error" for="title">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            
                                            <label class="form-label">Category Banner</label>
                                            <div class="form-group form-float">
                                                <div class="form-line">                                                    
                                                    <input type="file" name="category_banner" id="category_banner" accept="image/jpeg, image/png" class="form-control">
                                                                                                        
                                                </div>
                                                @error('category_banner')
                                                <label id="category_banner-error" class="error" for="category_banner">{{ $message }}</label>
                                                @enderror
                                            </div> 
                                            
                                            <label class="form-label">Category Icon</label>
                                            <div class="form-group form-float">
                                                <div class="form-line">                                                    
                                                    <input type="file" name="category_icon" id="category_icon" accept="image/jpeg, image/png" class="form-control">
                                                                                                        
                                                </div>
                                                @error('category_icon')
                                                <label id="category_icon-error" class="error" for="category_icon">{{ $message }}</label>
                                                @enderror
                                            </div>

											<label class="form-label">Status</label>
                                            <div class="form-group">
                                                <input type="checkbox" id="status" checked="checked" value="1" name="status" class="filled-in" />
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