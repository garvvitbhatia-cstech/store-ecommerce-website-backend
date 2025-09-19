@extends('layout.admin')
@section('title', 'Edit City')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="block-header">
      <h2>Edit City</h2>
    </div>
    <!-- Input -->
    <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="">
          <div class="body">
            <div class="row clearfix">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card"> @include('../flash-message')
                  <div class="body"> {{ Form::open(array('url' => array('/admins/edit-city',Crypt::encrypt($city->id)),'id' => 'pageForm', 'method' => 'post', 'files' => true)) }}
                    @csrf
                    <div class="form-group form-float">
                    	<label class="form-label">Country</label>
                      <div class="form-line">                      	
                        <select name="country_id" id="country_id" onchange="getStateByCountry(this.value)" class="form-control">
                          <option value="">Select Country</option>
                          	@foreach($country_list as $key => $country){
                          	<option @php if($key == $city->country_id) echo 'selected'; @endphp value="{{ $key }}">{{ $country }}</option>
                          	@endforeach                                                    
                        </select>                        
                      </div>
                      @error('country_id')
                      <label id="country_id-error" class="error" for="country_id">{{ $message }}</label>
                      @enderror 
                    </div>
                    <div class="form-group">
                    	<label class="form-label">State</label>
                      <div class="form-line">
                      	@php $state_list = Helper::getStateListById($city->country_id); @endphp
                        <select name="state_id" id="state_id" class="form-control">
                          <option value="">Select State</option>
                            @foreach($state_list as $key => $state)
                            	<option @php if($city->state_id == $state->id) echo 'selected'; @endphp value="{{ $state->id }}">{{ $state->state }}</option>
                            @endforeach
                        </select>
                      </div>
                      @error('state_id')
                      <label id="state_id-error" class="error" for="state_id">{{ $message }}</label>
                      @enderror
                    </div>
                    <div class="form-group form-float">
                    	<label class="form-label">City</label>
                      <div class="form-line">                      	
                        <input type="text" name="city" id="city" value="{{ $city->city }}" class="form-control">                        
                      </div>
                      @error('city')
                      <label id="city-error" class="error" for="city">{{ $message }}</label>
                      @enderror </div>
                    <div class="form-group form-float">
                    	<label class="form-label">Heading</label>
                      <div class="form-line">                      	
                        <input type="text" name="heading" id="heading" value="{{ $city->heading }}" class="form-control">                        
                      </div>
                      @error('heading')
                      <label id="heading-error" class="error" for="heading">{{ $message }}</label>
                      @enderror </div>
                    <div class="form-group form-float">
                    	<label class="form-label">Description</label>
                      <div class="form-line">                      	
                        <textarea name="description" id="description" rows="6" class="form-control">{{ $city->description }}</textarea>                        
                      </div>
                      @error('description')
                      <label id="description-error" class="error" for="description">{{ $message }}</label>
                      @enderror </div>
                      
                    <div class="form-group form-float">
                    	<label class="form-label">Banner</label>
                      <div class="">                      	
                        <input type="file" name="banner" id="banner" class="form-control">                        
                      </div>
                      @error('banner')
                      <label id="banner-error" class="error" for="banner">{{ $message }}</label>
                      @enderror </div>
                    <div class="form-group form-float">
                    	<label class="form-label">SEO Title</label>
                      <div class="form-line">                      	
                        <input type="text" name="seo_title" id="seo_title" value="{{ $city->seo_title }}" class="form-control">                        
                      </div>
                      @error('seo_title')
                      <label id="seo_title-error" class="error" for="seo_title">{{ $message }}</label>
                      @enderror </div>
                    <div class="form-group form-float">
                    	<label class="form-label">SEO Description</label>
                      <div class="form-line">                      	
                        <textarea name="seo_description" id="seo_description" rows="6" class="form-control">{{ $city->seo_description }}</textarea>                      </div>
                      @error('seo_description')
                      <label id="seo_description-error" class="error" for="seo_description">{{ $message }}</label>
                      @enderror </div>
                    <div class="form-group form-float">
                    	<label class="form-label">SEO Keywords</label>
                      <div class="form-line">                      	
                        <textarea name="seo_keywords" id="seo_keywords" rows="6" class="form-control">{{ $city->seo_keywords }}</textarea>                        
                      </div>
                      @error('seo_keywords')
                      <label id="seo_keywords-error" class="error" for="seo_keywords">{{ $message }}</label>
                      @enderror </div>
                    <label class="form-label">Status</label>
                    <div class="form-group">
                      <input type="checkbox" id="status" value="1" {{$city->status == 1 ? "checked" : "" }} name="status" class="filled-in" />
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
			errorPlacement: function (error, element){
				$(element).parents('.form-group').append(error);
			},
			rules: {
				'country_id': {
					required: true,
				},
				'state_id': {
					required: true,
				},
				'city': {
					required: true,
				}
			},
			messages: {
				'country_id': {
					required: "Please select country.",
				},
				'state_id': {
					required: "Please select state.",
				},
				'city': {
					required: "Please enter city name.",
				}
			},
			submitHandler: function(form){
				$('#submitBtn').html('Processing...');
				form.submit();				
			}
		});
	
	});
});
	function getStateByCountry(countryId){
		if(countryId != '' && $.isNumeric(countryId)){
			$('#state_id').html('');
			$.ajax({
				url: "{{ url('admins/get-state') }}",
				data: {countryId:countryId},
				type: 'POST',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				success:function(response){
					$('#state_id').html(response);
					$('#state_id').selectpicker('refresh');
				}
			});	
			return false;
		}
	}
</script>
@endsection