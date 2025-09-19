@extends('layout.admin')
@section('title', 'Add Product')

@section('content')

<section class="content"> 
    <div class="container-fluid">
        <div class="block-header">
            <h2>Add Product</h2>
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
                                        {{ Form::open(array('url' => '/admins/add-product/','id' => 'pageForm', 'method' => 'post', 'files' => true)) }}
                                            @csrf
                                            <div class="form-group">
                                            	<label class="form-label">Root Category</label>
                                                <div class="form-line">
                                                    <select id="parent_id" name="parent_id[]" multiple="multiple" onchange="getSubCategory(this.value)" class="form-control show-tick">
                                                        @php
                                                        	//echo Helper::getSubCategory($categoryList);
                                                        @endphp
                                                        @if(isset($categoryList) && $categoryList->count() > 0)
                                                        	@foreach($categoryList as $key => $value)
                                                            	<option value="{{ $value->id }}">{{ $value->title }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>   
                                                         
                                               	</div>
                                                @error('parent_id')
                                                <label id="parent_id-error" class="error" for="parent_id">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                            	<label class="form-label">Sub Category</label>
                                                <div class="form-line">
                                                    <select id="sub_category_id" name="sub_category_id" onchange="getSubSubCategory(this.value)" class="form-control show-tick">
                                                    </select>
                                               	</div>
                                                @error('sub_category_id')
                                                <label id="sub_category_id-error" class="error" for="sub_category_id">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            
                                            <div class="form-group">
                                            	<label class="form-label">Sub Sub Category</label>
                                                <div class="form-line">
                                                    <select id="sub_sub_category_id" name="sub_sub_category_id" class="form-control"></select>
                                               	</div>
                                            </div>
                                            <div class="form-group">
                                            	<label class="form-label">Brand</label>
                                                <div class="form-line">
                                                    <select id="brand_id" name="brand_id" class="form-control">
                                                       <option value="">Select Brand</option>
                                                       @foreach($brands as $key => $brand)
                                                       <option value="{{$brand->id}}">{{$brand->title}}</option>
                                                       @endforeach;
                                                    </select>
                                               	</div>
                                            </div>
                                            
                                            <div class="form-group form-float">
                                            	<label class="form-label">Product Name</label>
                                                <div class="form-line">
                                                    <input type="text" name="product_name" id="product_name" class="form-control">
                                                </div>
                                                @error('product_name')
                                                <label id="product_name-error" class="error" for="product_name">{{ $message }}</label>
                                                @enderror
                                            </div>
                                             <div class="form-group form-float">
                                            	<label class="form-label">Model No</label>
                                                <div class="form-line">
                                                    <input type="text" name="model_no" id="model_no" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Color</label>
                                                <div class="form-line">
                                                    <input type="text" name="color" id="color" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">HSN Code</label>
                                                <div class="form-line">
                                                    <input type="text" name="hsn" id="hsn" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Capacity</label>
                                                <div class="form-line">
                                                    <input type="text" name="capacity" id="capacity" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Material</label>
                                                <div class="form-line">
                                                    <input type="text" name="material" id="material" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Type</label>
                                                <div class="form-line">
                                                    <input type="text" name="type" id="type" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Pack Of</label>
                                                <div class="form-line">
                                                    <input type="text" name="pack_of" id="pack_of" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Warranty</label>
                                                <div class="form-line">
                                                    <input type="text" name="warranty" id="warranty" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Country Of Origin</label>
                                                <div class="form-line">
                                                    <input type="text" name="country_origin" id="country_origin" class="form-control">
                                                </div>
                                            </div>
                                             <div class="form-group form-float">
                                            	<label class="form-label">Product Care</label>
                                                <div class="form-line">
                                                    <input type="text" name="product_care" id="product_care" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">YouTube Video Url</label>
                                                <div class="form-line">
                                                    <input type="text" name="video_url" id="video_url" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Packing & Sizing</label>
                                                <div class="form-line">
                                                    <input type="text" name="packing_sizing" id="packing_sizing" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <label class="form-label">Height</label>
                                                <div class="form-line">
                                                    <input type="text" name="height" id="height" class="form-control">
                                                </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <label class="form-label">Width</label>
                                                <div class="form-line">
                                                    <input type="text" name="width" id="width" class="form-control">
                                                </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <label class="form-label">Length</label>
                                                <div class="form-line">
                                                    <input type="text" name="length" id="length" class="form-control">
                                                </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <label class="form-label">Breadth</label>
                                                <div class="form-line">
                                                    <input type="text" name="breadth" id="breadth" class="form-control">
                                                </div>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Product Price</label>
                                                <div class="form-line">
                                                    <input type="text" name="price" id="price" class="form-control">
                                                </div>
                                                @error('price')
                                                <label id="price-error" class="error" for="price">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Selling Price</label>
                                                <div class="form-line">
                                                    <input type="text" name="saling_price" id="saling_price" class="form-control">
                                                </div>
                                                @error('saling_price')
                                                <label id="discounted_price-error" class="error" for="saling_price">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Product Quantity</label>
                                                <div class="form-line">
                                                    <input type="text" name="quantity" id="quantity" class="form-control">
                                                </div>
                                                @error('quantity')
                                                <label id="quantity-error" class="error" for="quantity">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Minimum Order Quantity</label>
                                                <div class="form-line">
                                                    <input type="text" name="minimum_order_qty" maxlength="4" value="0" id="minimum_order_qty" class="form-control">
                                                </div>
                                                @error('minimum_order_qty')
                                                <label id="minimum_order_qty-error" class="error" for="minimum_order_qty">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">is Wholesale</label>
                                                <div class="form-group">
                                                    <input type="checkbox" id="is_wholesale" value="1" name="is_wholesale" class="filled-in" />
                                                    <label for="is_wholesale">Yes</label>
                                                </div>
                                            </div>
                                            
                                             <div class="form-group form-float">
                                            	<label class="form-label">Product Quantity</label>
                                                <div class="form-line">
                                                    <input type="text" name="quantity" id="quantity" class="form-control">
                                                </div>
                                                @error('quantity')
                                                <label id="quantity-error" class="error" for="quantity">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Minimum Quantity</label>
                                                <div class="form-line">
                                                    <input type="text" name="min_order_qty" id="min_order_qty" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Product Description</label>
                                                <div class="form-line">
                                                	<textarea name="description" rows="6" id="description" class="form-control"></textarea>
                                                </div>
                                                @error('description')
                                                <label id="description-error" class="error" for="description">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Product Features</label>
                                                <div class="form-line">
                                                	<textarea name="features" rows="6" id="features" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Manufracture Details</label>
                                                <div class="form-line">
                                                	<textarea name="manufractur_details" id="manufractur_details" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Packer Details</label>
                                                <div class="form-line">
                                                	<textarea name="packer_details" id="packer_details" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Sales Package</label>
                                                <div class="form-line">
                                                	<textarea name="sales_package" id="sales_package" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Search Keywords</label>
                                                <div class="form-line">
                                                	<textarea name="search_keywords" id="search_keywords" class="form-control"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Product Keywords</label>
                                                <div class="form-line">
                                                	<textarea name="keywords" rows="6" id="keywords" class="form-control"></textarea>
                                                </div>
                                                @error('keywords')
                                                <label id="keywords-error" class="error" for="keywords">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">SEO Title</label>
                                                <div class="form-line">
                                                    <input type="text" name="seo_title" id="seo_title" class="form-control">
                                                </div>
                                                @error('seo_title')
                                                <label id="seo_title-error" class="error" for="seo_title">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">SEO Keywords</label>
                                                <div class="form-line">
                                                	<textarea name="seo_keywords" rows="6" id="seo_keywords" class="form-control"></textarea>
                                                </div>
                                                @error('seo_keywords')
                                                <label id="seo_keywords-error" class="error" for="seo_keywords">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">SEO Description</label>
                                                <div class="form-line">
                                                	<textarea name="seo_description" rows="6" id="seo_description" class="form-control"></textarea>
                                                </div>
                                                @error('seo_description')
                                                <label id="seo_description-error" class="error" for="seo_description">{{ $message }}</label>
                                                @enderror
                                            </div>
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
                                                 @enderror 
                                              </div>
											<label class="form-label">Featured</label>
                                            <div class="form-group">
                                                <input type="checkbox" id="is_featured" checked="checked" value="1" name="is_featured" class="filled-in" />
                                                <label for="is_featured">Active</label>
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
function getSubSubCategory(cat_id){
	if(cat_id != ''){
		$.ajax({
			type: 'POST',
			url: "{{url('admins/get-sub-sub-categories')}}",
			headers:{
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: {cat_id: cat_id},
			success: function(msg){
				$('#sub_sub_category_id').html(msg);
				$('#sub_sub_category_id').selectpicker('refresh');
			},
			error: function(ts){
				$('#errorMsgPopUp').html('Something went wrong');
				$('#Error500').modal('show');
			}
		});
		return false;
	}
}
function getSubCategory(cat_id){
	if(cat_id != ''){
		var arr = [];
		$("#parent_id").each(function(){
			arr.push($(this).val());
		});
		if(arr != ''){
			$.ajax({
				type: 'POST',
				url: "{{url('admins/get-sub-categories')}}",
				headers:{
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				data: {cat_id: arr},
				success: function(msg){
					$('#sub_category_id').html(msg);
					$('#sub_category_id').selectpicker('refresh');
				},
				error: function(ts){
					$('#errorMsgPopUp').html('Something went wrong');
					$('#Error500').modal('show');
				}
			});
			return false;
		}
	}
}
$(document).ready(function(e){
	$('#price').filter_input({regex:'[0-9.]'});
	$('#quantity').filter_input({regex:'[0-9]'});
	$('#minimum_order_qty').filter_input({regex:'[0-9]'});
	
	CKEDITOR.replace('description');
	CKEDITOR.replace('seo_description');

	$(document).on('click', '#submitBtn',function(){

		$("#pageForm").validate({
			errorElement: "label",
			errorPlacement: function (error, element) {
				$(element).parents('.form-group').append(error);
			},
			rules: {
				'product_name': {
					required: true,
				},
				'price': {
					required: true,
				},
				'quantity': {
					required: true,
				},
				'keywords': {
					required: true,
				},
				'minimum_order_qty': {
					required: true	
				}
			},
			messages: {
				'product_name': {
					required: "Please enter product name.",
				} ,
				'price': {
					required: "Please enter product price.",
				},
				'quantity': {
					required: "Please enter product quantity.",
				} ,
				'keywords': {
					required: "Please enter product keywords.",
				},
				'minimum_order_qty': {
					required: "Please enter minimum order quantity.",
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