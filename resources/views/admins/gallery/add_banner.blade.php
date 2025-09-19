@extends('layout.admin')
@section('title', 'Add Banner')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="block-header">
      <h2>Add Banner</h2>
    </div>
    <!-- Input -->
    <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="">
          <div class="body">
            <div class="row clearfix">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card"> @include('../flash-message')
                  <div class="body"> {{ Form::open(array('url' => '/admins/add-banner/','id' => 'pageForm', 'method' => 'post', 'files' => true)) }}
                    @csrf                      
                    <div class="form-group form-float">
                      <label class="form-label">Page</label>
                      <div class="form-line">
                      	<select name="page" id="page" class="form-control">
                        	<option value="Home">Home</option>
                        </select>
                      </div>
                      @error('page')
                      <label id="page-error" class="error" for="page">{{ $message }}</label>
                      @enderror 
                    </div>
                    <div class="form-group form-float">
                      <label class="form-label">Heading</label>
                      <div class="form-line">
                        <input type="text" name="heading" id="heading" class="form-control">
                      </div>
                      @error('heading')
                      <label id="heading-error" class="error" for="heading">{{ $message }}</label>
                      @enderror 
                    </div>
                    <div class="form-group form-float">
                      <label class="form-label">Text</label>
                      <div class="form-line">
                        <input type="text" name="text" id="text" class="form-control">
                      </div>
                      @error('text')
                      <label id="text-error" class="error" for="text">{{ $message }}</label>
                      @enderror 
                    </div>
                    <div class="form-group form-float">
                      <label class="form-label">URL</label>
                      <div class="form-line">
                        <input type="text" name="url" id="url" class="form-control">
                      </div>
                      @error('url')
                      <label id="url-error" class="error" for="url">{{ $message }}</label>
                      @enderror 
                    </div>
                    <label class="form-label">Banner (1920 x 671)</label>
                      <div class="form-group form-float">
                         <div class="form-line">
                            <input type="file" name="banner" id="banner" accept="image/jpeg, image/png" class="form-control">
                         </div>
                         @error('banner')
                         <label id="banner-error" class="error" for="banner">{{ $message }}</label>
                         @enderror 
                      </div>
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
		
	$(document).on('click', '#submitBtn',function(){
		
		$("#pageForm").validate({
			errorElement: "label",
			errorPlacement: function (error, element) {
				$(element).parents('.form-group').append(error);
			},
			rules: {
				'banner': {
					required: true,
				},
			},
			messages: {
				'banner': {
					required: "Please select banner.",
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