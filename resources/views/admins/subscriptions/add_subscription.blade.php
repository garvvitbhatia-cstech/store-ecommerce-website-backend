@extends('layout.admin')
@section('title', 'Add Subscription')

@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Add Subscription</h2>
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
                                        {{ Form::open(array('url' => '/admins/add-subscription/','id' => 'pageForm', 'method' => 'post', 'files' => true)) }}
                                            @csrf                                                      
                                            <div class="form-group form-float">
                                            	<label class="form-label">Title</label>
                                                <div class="form-line">
                                                    <input type="text" name="title" id="title" class="form-control">
                                                </div>
                                                @error('title')
                                                <label id="title-error" class="error" for="title">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Price</label>
                                                <div class="form-line">
                                                    <input type="text" name="price" maxlength="8" id="price" class="form-control">
                                                </div>
                                                @error('price')
                                                <label id="price-error" class="error" for="price">{{ $message }}</label>
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

<script type="text/javascript">
$(document).ready(function(e){
	$('#price').filter_input({regex:'[0-9]'});
	$('#title').filter_input({regex:'[a-z- A-Z]'});
		
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
				'price': {
					required: true,
					number: true
				}
			},
			messages: {
				'title': {
					required: "Please enter title.",
				},
				'price': {
					required: "Please enter price.",
					number: "Please enter numeric value only.",
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