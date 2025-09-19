@extends('layout.admin')
@section('title', 'Edit State')

@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Edit State</h2>
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
                                        {{ Form::open(array('url' => array('/admins/edit-state',Crypt::encrypt($state->id)),'id' => 'pageForm', 'method' => 'post', 'files' => true)) }}
                                            @csrf                                            
                                            <div class="form-group form-float">
                                            	<label class="form-label">Country</label>
                                                <div class="form-line">
                                                    <select name="country_id" id="country_id" class="form-control">
                                                    	<option value="">Select Country</option>
                                                        	@foreach($country_list as $key => $country){
                                                            	<option @php if($key == $state->country_id) echo 'selected'; @endphp value="{{ $key }}">{{ $country }}</option>
                                                            @endforeach
                                                    </select>
                                                </div>
                                                @error('country_id')
                                                <label id="country_id-error" class="error" for="country_id">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">State</label>
                                                <div class="form-line">
                                                    <input type="text" name="state" id="state" value="{{ $state->state }}" class="form-control">                                                </div>
                                                @error('state')
                                                <label id="state-error" class="error" for="state">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Abbreviation</label>
                                                <div class="form-line">
                                                    <input type="text" name="abbreviation" id="abbreviation" value="{{ $state->abbreviation }}" class="form-control">
                                                </div>
                                                @error('abbreviation')
                                                <label id="abbreviation-error" class="error" for="abbreviation">{{ $message }}</label>
                                                @enderror
                                            </div>
											<label class="form-label">Status</label>
                                            <div class="form-group">
                                                <input type="checkbox" id="status" value="1" {{$state->status == 1 ? "checked" : "" }} name="status" class="filled-in" />
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
				'country_id': {
					required: true,
				},
				'state': {
					required: true,
				}
			},
			messages: {
				'country_id': {
					required: "Please select country.",
				},
				'state': {
					required: "Please enter state name.",
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