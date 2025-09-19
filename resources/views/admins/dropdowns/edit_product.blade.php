@extends('layout.admin')
@section('title', 'Edit Product')
@extends('element.admin.jQuery');
@section('content')

<link href="{{ URL::asset('public/assets/css/admin/dropzone.css') }}" rel="stylesheet">
<script src="{{ URL::asset('public/assets/js/admin/dropzone.js') }}"></script>


<link rel="stylesheet" href="{{ asset('public/assets/css/token-input.css') }}">
<script src="{{ asset('public/assets/js/jquery.tokeninput.js') }}" ></script>

<section class="content"> 
    <div class="container-fluid">
        <div class="block-header">
            <h2>Edit Product</h2> <a href="{{url('admins/products')}}">Back</a>
        </div>
        <!-- Input -->
        <div class="row clearfix">
        {{ Form::open(array('url' => array('/admins/edit-product',Crypt::encrypt($product->id)),'id' => 'pageForm', 'method' => 'post', 'files' => true)) }}
         @csrf
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="">
                    <div class="body">                            
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="card">
                                	@include('../flash-message')
                                    <div class="body">
                                        
                                            @csrf
                                            <div class="form-group">
                                            	<label class="form-label">Root Category</label>
                                                <div class="form-line">
                                                    <select id="parent_id" name="parent_id[]" multiple="multiple" onchange="getSubCategory(this.value)" class="form-control show-tick">
                                                        @php
                                                        	//echo Helper::getSubCategory($categoryList,$product->category_id);
                                                        @endphp
                                                        @if(isset($categoryList) && $categoryList->count() > 0)
                                                        	@php
                                                            if($product->category_id != ''){
                                                            	$explode_cat = explode(',',$product->category_id);
                                                           	}
                                                            @endphp
                                                        	@foreach($categoryList as $key => $value)
                                                            	<option {{(in_array($value->id,$explode_cat) ? 'selected' : '')}} value="{{ $value->id }}">{{ $value->title }}</option>
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
                                                    <select id="sub_category_id" name="sub_category_id" onchange="getSubSubCategory(this.value)" class="form-control">
                                                       {!! Helper::getSubCategoryAll($product->category_id,$product->sub_category_id); !!}
                                                    </select>
                                               	</div>
                                                @error('sub_category_id')
                                                <label id="sub_category_id-error" class="error" for="sub_category_id">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            
                                            <div class="form-group">
                                            	<label class="form-label">Sub Sub Category</label>
                                                <div class="form-line">
                                                    <select id="sub_sub_category_id" name="sub_sub_category_id" class="form-control">
                                                       {!! Helper::getSubCategoryAll($product->sub_category_id,$product->sub_sub_category_id); !!}
                                                    </select>
                                               	</div>
                                            </div>
                                            <div class="form-group">
                                            	<label class="form-label">Brand</label>
                                                <div class="form-line">
                                                    <select id="brand_id" name="brand_id" class="form-control">
                                                       <option value="">Select Brand</option>
                                                       @foreach($brands as $key => $brand)
                                                       <option @if($product->brand_id == $brand->id) selected @endif value="{{$brand->id}}">{{$brand->title}}</option>
                                                       @endforeach;
                                                    </select>
                                               	</div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Product Name</label>
                                                <div class="form-line">
                                                    <input type="text" name="product_name" id="product_name" value="{{ $product->product_name }}" class="form-control">
                                                </div>
                                                @error('product_name')
                                                <label id="product_name-error" class="error" for="product_name">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Model No</label>
                                                <div class="form-line">
                                                    <input type="text" name="model_no" id="model_no" value="{{ $product->model_no }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">HSN Code</label>
                                                <div class="form-line">
                                                    <input type="text" name="hsn" id="hsn" value="{{ $product->hsn }}" class="form-control">
                                                </div>
                                            </div>
                                             <div class="form-group form-float">
                                            	<label class="form-label">Color</label>
                                                <div class="form-line">
                                                    <input type="text" name="color" id="color" value="{{ $product->color }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Capacity</label>
                                                <div class="form-line">
                                                    <input type="text" name="capacity" id="capacity" value="{{ $product->capacity }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Material</label>
                                                <div class="form-line">
                                                    <input type="text" name="material" id="material" value="{{ $product->material }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Type</label>
                                                <div class="form-line">
                                                    <input type="text" name="type" id="type" value="{{ $product->type }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Pack Of</label>
                                                <div class="form-line">
                                                    <input type="text" name="pack_of" id="pack_of" value="{{ $product->pack_of }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Warranty</label>
                                                <div class="form-line">
                                                    <input type="text" name="warranty" id="warranty" value="{{ $product->warranty }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Country Of Origin</label>
                                                <div class="form-line">
                                                    <input type="text" name="country_origin" id="country_origin" value="{{ $product->country_origin }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Packing & Sizing</label>
                                                <div class="form-line">
                                                    <input type="text" name="packing_sizing" id="packing_sizing" value="{{ $product->packing_sizing }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Product Care</label>
                                                <div class="form-line">
                                                    <input type="text" name="product_care" id="product_care" value="{{ $product->product_care }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">YouTube Video Url</label>
                                                <div class="form-line">
                                                    <input type="text" name="video_url" id="video_url" value="{{ $product->video_url }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <label class="form-label">Height</label>
                                                <div class="form-line">
                                                    <input type="text" name="height" id="height" value="{{ $product->height }}" class="form-control">
                                                </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <label class="form-label">Width</label>
                                                <div class="form-line">
                                                    <input type="text" name="width" id="width" value="{{ $product->width }}" class="form-control">
                                                </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <label class="form-label">Length</label>
                                                <div class="form-line">
                                                    <input type="text" name="length" id="length" value="{{ $product->length }}" class="form-control">
                                                </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <label class="form-label">Breadth</label>
                                                <div class="form-line">
                                                    <input type="text" name="breadth" id="breadth" value="{{ $product->breadth }}" class="form-control">
                                                </div>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Product Price</label>
                                                <div class="form-line">
                                                    <input type="text" name="price" id="price" value="{{ $product->price }}" class="form-control">
                                                </div>
                                                @error('price')
                                                <label id="price-error" class="error" for="price">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Selling Price</label>
                                                <div class="form-line">
                                                    <input type="text" name="saling_price" id="saling_price" value="{{ $product->saling_price }}" class="form-control">
                                                </div>
                                                @error('saling_price')
                                                <label id="discounted_price-error" class="error" for="saling_price">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Minimum Order Quantity</label>
                                                <div class="form-line">
                                                    <input type="text" name="minimum_order_qty" value="{{ $product->minimum_order_qty }}" maxlength="4" id="minimum_order_qty" class="form-control">
                                                </div>
                                                @error('minimum_order_qty')
                                                <label id="minimum_order_qty-error" class="error" for="minimum_order_qty">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">is Wholesale</label>
                                                <div class="form-group">
                                                    <input type="checkbox" id="is_wholesale" {{$product->is_wholesale == 1 ? "checked" : "" }} value="1" name="is_wholesale" class="filled-in" />
                                                    <label for="is_wholesale">Yes</label>
                                                </div>
                                            </div>                                            
                                            <div class="form-group form-float">
                                            	<label class="form-label">Product Quantity</label>
                                                <div class="form-line">
                                                    <input type="text" name="quantity" id="quantity" value="{{ $product->quantity }}" class="form-control">
                                                </div>
                                                @error('quantity')
                                                <label id="quantity-error" class="error" for="quantity">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Minimum Quantity</label>
                                                <div class="form-line">
                                                    <input type="text" name="min_order_qty" id="min_order_qty" value="{{ $product->min_order_qty }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Product Description</label>
                                                <div class="form-line">
                                                	<textarea name="description" rows="6" id="description" class="form-control">{{ $product->description }}</textarea>
                                                </div>
                                                @error('description')
                                                <label id="description-error" class="error" for="description">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Product Features</label>
                                                <div class="form-line">
                                                	<textarea name="features" id="features" class="form-control">{{ $product->features }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Manufracture Details</label>
                                                <div class="form-line">
                                                	<textarea name="manufractur_details" id="manufractur_details" class="form-control">{{ $product->manufractur_details }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Packer Details</label>
                                                <div class="form-line">
                                                	<textarea name="packer_details" id="packer_details" class="form-control">{{ $product->packer_details }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Sales Package</label>
                                                <div class="form-line">
                                                	<textarea name="sales_package" id="sales_package" class="form-control">{{ $product->sales_package }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Search Keywords</label>
                                                <div class="form-line">
                                                	<textarea name="search_keywords" id="search_keywords" class="form-control">{{ $product->search_keywords }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">Product Keywords</label>
                                                <div class="form-line">
                                                	<textarea name="keywords" rows="6" id="keywords" class="form-control">{{ $product->keywords }}</textarea>
                                                </div>
                                                @error('keywords')
                                                <label id="keywords-error" class="error" for="keywords">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">SEO Title</label>
                                                <div class="form-line">
                                                    <input type="text" name="seo_title" id="seo_title" value="{{ $product->seo_title }}" class="form-control">
                                                </div>
                                                @error('seo_title')
                                                <label id="seo_title-error" class="error" for="seo_title">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">SEO Keywords</label>
                                                <div class="form-line">
                                                	<textarea name="seo_keywords" rows="6" id="seo_keywords" class="form-control">{{ $product->seo_keywords }}</textarea>
                                                </div>
                                                @error('seo_keywords')
                                                <label id="seo_keywords-error" class="error" for="seo_keywords">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                            	<label class="form-label">SEO Description</label>
                                                <div class="form-line">
                                                	<textarea name="seo_description" rows="6" id="seo_description" class="form-control">{{ $product->seo_description }}</textarea>
                                                </div>
                                                @error('seo_description')
                                                <label id="seo_description-error" class="error" for="seo_description">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group form-float">
                                                <label class="form-label">SEO Robots</label>
                                                 <div class="form-line">
                                                    <select id="robot_tags" name="robot_tags" onkeyup="checkError(this.id);" confirmation="false" class="form-control">
                                                    <option {{$product->robot_tags == 'index,follow' ? "checked" : "" }} value="index,follow">index,follow</option>
                                                    <option {{$product->robot_tags == 'index,nofollow' ? "checked" : "" }} value="index,nofollow">index,nofollow</option>
                                                    <option {{$product->robot_tags == 'noindex,follow' ? "checked" : "" }} value="noindex,follow">noindex,follow</option>
                                                    <option {{$product->robot_tags == 'noindex,nofollow' ? "checked" : "" }} value="noindex,nofollow">noindex,nofollow</option>
                                                    </select>                                    
                                                 </div>
                                                 @error('robot_tags')
                                                 <label id="robot_tags-error" class="robot_tags" for="robot_tags">{{ $message }}</label>
                                                 @enderror 
                                            </div>                                            
                                           
                                            <br />
											<label class="form-label">Featured</label>
                                            <div class="form-group">
                                                <input type="checkbox" id="is_featured" {{$product->is_featured == 1 ? "checked" : "" }} value="1" name="is_featured" class="filled-in" />
                                                <label for="is_featured">Active</label>
											</div>
                                            <br />
                                            <label class="form-label">Status</label>
                                            <div class="form-group">
                                                <input type="checkbox" id="status" {{$product->status == 1 ? "checked" : "" }} value="1" name="status" class="filled-in" />
                                                <label for="status">Active</label>
											</div>
											
                                            <button type="submit" id="submitBtn" class="btn btn-primary m-t-15 waves-effect">Submit</button>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>                             
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            
            
                <div class="">
                    <div class="body">                            
                        <div class="row clearfix">
                        
                        
                  
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="card">
                                    <div class="body">
                                                                          
                                            <div class="form-group form-float">
                                                <label class="form-label">Product Images</label>
                                                <div id="my-awesome-dropzone" class="dropzone"></div>
                                            </div>
                                            @if(isset($productImages) && !empty($productImages->count() > 0))
                                            <hr>                                            
                                            	<table style="width:100%" class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                                <thead>
                                                  <tr>
                                                    <th>#</th>
                                                    <th>Image</th>
                                                    <th>Ordering</th>
                                                    <th>Action</th>
                                                  </tr>
                                                </thead>              
                                                <tbody>
                                            	@foreach($productImages as $key => $image)
                                                	@php
                                                        if(!empty($image->image_name)){
                                                    @endphp
                                                    
                                                    	<tr>
                                                        	<td>{{ $key+1 }}</td>
                                                            <td>
                                                            <img width="100px;" alt="" title="" src="{{ URL::asset('public/assets/images/admin/products/') }}/{!! $image->image_name !!}" />
                                                            </td>
                                                            <td width="100px;">
                                                            <input type="text" name="ordering[]" class="form-control ordering" value="{{ $image->ordering }}"/>
                                                            <input type="hidden" name="orderingEditId[]" value="{{ $image->id }}"/>
                                                            </td>
                                                            <td>
                                      						<button onClick="removeProductImage('{{ Crypt::encrypt($image->id) }}')" type="button" title="Delete" class="btn bg-red waves-effect">
                                                                <i class="material-icons">delete</i>
                                                            </button>
                                                            </td>
                                                        </tr>                                                                                                          
                                                    @php
                                                        }
                                                    @endphp  
                                                @endforeach  
                                                </tbody>
                                                </table>                                                                                             
                                            @endif
                                            <button type="submit" id="submitBtn" class="btn btn-primary m-t-15 waves-effect">Submit</button>
											
                                            
                                    </div>
                                </div>
                            </div>
                        </div>                             
                    </div>
                </div>
                
                <div class="">
            <div class="body">                            
            <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            
            <div class="form-group form-float">
                <label class="form-label">Product Varient</label>
                <div class="form-line">
                    <input type="text" class="form-control" placeholder="Enter Model No" name="varient_ids" id="varient_ids">
                </div>
            </div>
            </div>
            </div>
            </div>
            </div>
            </div>
            
            {{ Form::close() }}
        </div>
        <!-- #END# Input -->  
    </div>
</section>
@php
	$ailmentArray = explode(',',$product->varient_ids);
@endphp
<script>
$(function(){
	var myToken = $("#varient_ids").tokenInput("{{url('/admins/search-product-varient')}}",{});
	
	@foreach($ailmentArray as $key => $ailment_id)
	@php
	$ailmentData = Helper::getProductData($ailment_id);
	if(isset($ailmentData->id)){
	@endphp
	myToken.tokenInput("add", {id: '{{$ailment_id}}', name: '{{$ailmentData->model_no}}'});
	@php } @endphp
	@endforeach
		
});
</script>
<script type="text/javascript">
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
	
	CKEDITOR.replace('description');

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

/**************delete banner image*****************/
function removeProductImage(rowId){
	if (rowId != '') {
		swal({
			title: "Do you want to delete this product image?",
			text: "",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: '#DD6B55',
			cancelButtonText: "No",
			confirmButtonText: 'Yes',
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function(isConfirm) {
			if (isConfirm) {
				swal("Deleted!", "", "success");
				$.ajax({
					type: 'POST',
					url: "{{url('admins/delete-product-image')}}",
					headers:{
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					data: {rowId: rowId},
					success: function(msg) {
						window.location.reload(true);
					},
					error: function(ts){
						$('#errorMsgPopUp').html('Something went wrong');
						$('#Error500').modal('show');
					}
				})
			} else {
				swal("Cancelled", "", "error");
			}
		});
	}
}

$('#my-awesome-dropzone').attr('class', 'dropzone');
var myDropzone = new Dropzone('#my-awesome-dropzone', {
	url: "{{url('admins/upload-product-images')}}",
	clickable: true,
	method: 'POST',
	maxFiles: 50,
	parallelUploads: 50,
	maxFilesize: 20,
	addRemoveLinks: false,
	dictRemoveFile: 'Remove',
	dictCancelUpload: 'Cancel',
	dictCancelUploadConfirmation: 'Confirm cancel?',
	dictDefaultMessage: 'Drop files here to upload',
	dictFallbackMessage: 'Your browser does not support drag n drop file uploads',
	dictFallbackText: 'Please use the fallback form below to upload your files like in the olden days',
	paramName: 'file',
	params: {'pid':'{{ $product->id }}'},
	forceFallback: false,
	createImageThumbnails: true,
	maxThumbnailFilesize: 5,
	//acceptedFiles: ".jpeg,.jpg,.webp,.png,.svg",
	acceptedFiles: "image/*",
	autoProcessQueue: true,
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	},
	init: function() {
		this.on('thumbnail', function(file) {
			if (file.width < 100 || file.height < 100) {
				file.rejectDimensions();
			} else {
				file.acceptDimensions();
			}
		});
	},
	accept: function(file, done) {
		file.acceptDimensions = done;
		file.rejectDimensions = function() {
			done('The image must be at least 100 x 100px')
		};
	}
});

myDropzone.on("complete", function(file) {
	var status = file.status;
	if (status == 'success') {

	}
	console.log(file);
});

var count = 1;
myDropzone.on("success", function(file, responseText) {
	var fnamenew = file.name;
	count++;
});

myDropzone.on("removedfile", function(file) {
	var fname = file.name;
	fname2 = fname.trim().replace(/["~!@#$%^&*\(\)_+=`{}\[\]\|\\:;'<>,.\/?"\- \t\r\n]+/g, '_');    
});

myDropzone.on("addedfile", function(file) {

}); 

</script>

@endsection