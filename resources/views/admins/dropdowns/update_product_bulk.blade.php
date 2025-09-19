@extends('layout.admin')
@section('title', 'Import Product')

@section('content')

<section class="content"> 
    <div class="container-fluid">
        <div class="block-header">
            <h2>Bulk Update Product</h2>
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
                                        {{ Form::open(array('url' => '/admins/update-product-bulk/','id' => 'pageForm', 'method' => 'post', 'files' => true, 'enctype' => 'multipart/form-data')) }}
                                            @csrf
                                            
                                            
                                            
                                            <div class="form-group form-float">
                                            	<label class="form-label">Choose File</label>
                                                <div class="form-line">
                                                    <input type="file" name="products" id="products" >
                                                </div>
                                                
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
		$('#submitBtn').html('Processing...');
	    form.submit();	
	});
}); 
</script>

@endsection