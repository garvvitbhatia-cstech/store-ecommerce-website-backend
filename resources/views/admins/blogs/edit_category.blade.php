@extends('layout.admin')
@section('title', 'Edit Blog Category') 

@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Edit Blog Category</h2>
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
                                        {{ Form::open(array('url' => array('/admins/edit-blog-category',Crypt::encrypt($category->id)),'id' => 'pageForm', 'method' => 'post', 'files' => true)) }}
                                            @csrf                                            
                                            <div class="form-group form-float">
                                            	<label class="form-label">Category Name</label>
                                                <div class="form-line">
                                                    <input type="text" name="title" id="title" value="{{ $category->title }}" class="form-control">
                                                </div>
                                                @error('title')
                                                <label id="title-error" class="error" for="title">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Description</label>
                                                <div class="form-line">
                                                    <textarea name="description" id="description" class="form-control">{{ $category->description }}</textarea>
                                                </div>
                                                @error('description')
                                                <label id="description-error" class="error" for="description">{{ $message }}</label>
                                                @enderror
                                            </div>
											<label class="form-label">Status</label>
                                            <div class="form-group">
                                                <input type="checkbox" id="status" value="1" {{$category->status == 1 ? "checked" : "" }} name="status" class="filled-in" />
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
	$('#description').redactor(); 
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