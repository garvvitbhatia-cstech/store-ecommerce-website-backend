@extends('layout.admin')
@section('title', 'Add Couponcode')
@section('content')

<script src="{{ URL::asset('public/assets/js/admin/jquery.datetimepicker.js') }}"></script>
<link href="{{ URL::asset('public/assets/css/admin/jquery.datetimepicker.css') }}" rel="stylesheet">

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Add Couponcode</h2>
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
                                        {{ Form::open(array('url' => '/admins/add-coupon-code/','id' => 'pageForm', 'method' => 'post', 'files' => true)) }}
                                            @csrf                                                      
                                            <div class="form-group form-float">
                                            	<label class="form-label">Coupon Name</label>
                                                <div class="form-line">
                                                    <input type="text" name="coupon" id="coupon" class="form-control" maxlength="8" style="text-transform: uppercase;" >
                                                </div>
                                                @error('coupon')
                                                <label id="coupon-error" class="error" for="coupon">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Coupon Type</label>
                                                <div class="form-line">
                                                    <select id="type" name="type" confirmation="false" class="form-control">
                                                        <option value="percentage">Percentage</option>
                                                        <option value="amount">Amount</option>
                                                    </select>
                                                </div>
                                                @error('type')
                                                <label id="type-error" class="error" for="type">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Price</label>
                                                <div class="form-line">
                                                    <input type="text" name="value" maxlength="4" onblur="chkvalue(this.value);" id="value" class="form-control">
                                                </div>
                                                @error('value')
                                                <label id="value-error" class="error" for="value">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Start Date</label>
                                                <div class="form-line">
                                                    <input type="text" readonly="readonly" name="start_date" id="start_date" class="form-control">
                                                </div>
                                                @error('value')
                                                <label id="value-error" class="error" for="value">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Expired Date</label>
                                                <div class="form-line">
                                                    <input type="text" readonly="readonly" name="expired" readonly="readonly" id="expired" class="form-control">
                                                </div>
                                                @error('expired')
                                                <label id="expired-error" class="error" for="expired">{{ $message }}</label>
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
function chkvalue(val){
	if($('#type').val() == 'percentage'){
		if($('#value').val() > 100){
			$('#value').val(0);
		}
	}
}
$(document).ready(function(e){
	$('#start_date').datetimepicker();	
	$('#expired').datetimepicker();	
	
	$('#value').filter_input({regex:'[0-9]'});
	$('#coupon').filter_input({regex:'[A-Za-z0-9]'});
		
	$(document).on('click', '#submitBtn',function(){

		$("#pageForm").validate({
			errorElement: "label",
			errorPlacement: function (error, element){
				$(element).parents('.form-group').append(error);
			},
			rules: {
				'coupon': {
					required: true,
				},
				'type': {
					required: true,
				},
				'value': {
					required: true,
					number: true
				},
				'start_date': {
					required: true,
				},
				'expired': {
					required: true,
				},
				
			},
			messages: {
				'coupon': {
					required: "Please enter title.",
				},
				'type': {
					required: "Please select coupon type.",
				},
				'value': {
					required: "Please enter price.",
					number: "Please enter only numeric price.",
				},
				'start_date': {
					required: "Please enter start date.",
				},
				'expired': {
					required: "Please enter expired date.",
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