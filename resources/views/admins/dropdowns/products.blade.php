@extends('layout.admin')
@section('title', 'Products')
 
@section('content')
@extends('element.admin.jQuery')
<section class="content">
  <div class="container-fluid">
    <div class="block-header">
      <h2>Products</h2>
    </div>
    <!-- Basic Examples -->
    <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        
          <div class="header">
            <div class="row">
            	{{ Form::open(array('url' => array('/admins/products'),'id' => 'pageForm', 'method' => 'post')) }}
              <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <input type="text" name="product_name" class="form-control" placeholder="Search By Product Name">
                &nbsp; </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <select name="status" class="form-control">
                	<option value="">Status</option>
                    <option value="1">Active</option>
                    <option value="2">In-Active</option>
                </select>
                &nbsp; </div>
              <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <button type="submit" class="btn btn-default waves-effect">Search</button>
                <button onclick="searchData()" class="btn btn-default waves-effect">Reset</button>
              </div>
              {{ Form::close() }}
            </div>
            <ul class="header-dropdown">
              <li class="dropdown">
                <a href="{{url('admins/add-product')}}"><button type="button" class="btn btn-success btn-lg waves-effect">Add Product</button></a>
              </li>
              <li class="dropdown">
                <a class="btn btn-primary btn-lg waves-effect" target="_blank" href="https://api.anupamstores.com/storage/sample-csv/products.csv">Sample CSV</a>
              </li>
              <li class="dropdown">
                <a class="btn btn-warning btn-lg waves-effect" href="{{url('admins/import-product')}}">Import</a>
              </li>
              <li class="dropdown">
                <a class="btn btn-primary btn-lg waves-effect" onclick="exportData();">Export</a>
              </li>
              <li class="dropdown">
                <a class="btn btn-danger btn-lg waves-effect" onclick="deleteAll();">Delete</a>
              </li>
              <li class="dropdown">
                <a class="btn btn-warning btn-lg waves-effect" href="{{url('admins/update-product-bulk')}}">Bulk Update</a>
              </li>
            </ul>
          </div>          
          <div class="body">
            <div class="table-responsive">
            <div class="dataTables_wrapper form-inline dt-bootstrap">
              <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                <thead>
                  <tr>
                    <th width="5%" >#</th>
                    <th width="2%"><input type="checkbox" onchange="selectAllItem();" id="select_all" /></th>
                    <th width="25%">Product Name</th>
                    <th width="5%">Banner</th>
                    <th width="10%">Category</th>
                    <th width="13%">Price</th>
                    <th width="15%" class="text-center">Offer</th>
                    <th width="5%" class="text-center">Status</th>
                    <th width="10%">Action</th>
                  </tr>
                </thead>
                                
                <tbody>               
                @if(isset($pages) && $pages->count() > 0)
                @foreach($pages as $key => $product)
                @php
                $pImage = Helper::GetProductImage($product->id);
                @endphp
                <tr>
                  <td>{{ $product->id; }}</td>   
                  <td class="check_colum"><input type="checkbox" onchange="selectItem();" class="item_check" value="{{ $product->id }}" name="" /></td>               
                  <td>
                  {{ $product->product_name; }}<br />
                  <b>Model No:</b> {{ $product->model_no; }}<br />
                  <b>Show Home:</b> {{ $product->is_featured == 1 ? 'Yes' : 'No'}}<br />
                  <b>Created Date:</b> {{ date("F jS, Y h:i A",strtotime($product->created_at)); }}
                  </td>
                  <td>@if(isset($pImage->image_name))<img width="100px;" alt="" title="" src="{{ URL::asset('public/assets/images/admin/products/') }}/{!! $pImage->image_name !!}" />@endif</td>
                  <td>
                  	@php
                    	$isExists = 0;
                        //$isExists = Helper::isParentExist($product->id);
                        if($product->category_id > 0){
                        	echo Helper::getCategoryName($product->category_id); 
                       	}
                    @endphp
                  </td>
                  
                  <td>
                  <b>MRP:</b> {{ number_format($product->price,2); }}<br />
                  <b>Selling Price:</b> @if($product->saling_price > 0) {{ number_format($product->saling_price,2); }} @endif<br />
                  <b>Discounted:</b> @if($product->discounted_price > 0) {{ number_format($product->discounted_price,2); }} @endif
                  </td>
                  <td align="center">
                  <table>
                  <tr>
                  <td><input type="text" class="form-control" id="offer_vale_{{ $product->id; }}" value="{{ $product->offer_value; }}" style="width:74px" /></td>
                  <td>
                  <select class="form-control" id="offer_type_{{ $product->id; }}"  style="width:98px">
                  <option @if($product->offer_type == 'Amount') selected @endif value="Amount">Amount</option>
                  <option @if($product->offer_type == 'Percent') selected @endif value="Percent">Percent</option>
                  </select>
                  </td>
                  </tr>
                  <tr>
                  <td colspan="2"> <a onclick="applyOffer('{{ $product->id; }}');" class="btn btn-warning btn-lg waves-effect">Apply</a></td>
                  </tr>
                  </table>
                  </td>
                  <td class="text-center"> @if($product->status == 1) 
                  <!--<span style="color:#390">{{ 'Active' }}</span>-->
                  
                  	<button @php if($isExists == 0){ @endphp; onclick="changeStatus('products','{{ Crypt::encrypt($product->id) }}','{{ $product->status }}','{{ $product->id }}');" @php } @endphp; type="button" class="btn btn-success btn-circle waves-effect waves-circle waves-float">
                    	<i class="material-icons" id="material_icons_{{$product->id}}">check</i>
                	</button>
                                 
                  @else 
                  <!--<span style="color:#F00">{{ 'In-Active' }}</span> -->
                  
                  	<button @php if($isExists == 0){ @endphp; onclick="changeStatus('products','{{ Crypt::encrypt($product->id) }}','{{ $product->status }}','{{ $product->id }}');" @php } @endphp; type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float">
                    	<i class="material-icons" id="material_icons_{{$product->id}}">clear</i>
                	</button>
                    
                  @endif 
                  <input type="hidden" id="current_status{{ $product->id }}" value="{{ $product->status }}"/>
                  </td>
                  
                  <td>
                  	<a href="{{ url('admins/edit-product',Crypt::encrypt($product->id)) }}"><button type="button" title="Edit" class="btn bg-blue waves-effect">
                        <i class="material-icons">edit</i>
                    </button></a>
                    <button onclick="deleteRecord('products','{{ Crypt::encrypt($product->id) }}',{{ $isExists; }});" type="button" title="Delete" class="btn bg-red waves-effect">
                        <i class="material-icons">delete</i>
                    </button>
                  </td>
                </tr>
                @endforeach
                
                @else
                <tr>
                  <td align="center" colspan="11">Record not found</td>
                </tr>
                @endif
                </tbody>             	
              </table>
              </div> 
            </div>
            {!! $pages->appends(request()->except('page','_token'))->links('pagination.custom') !!}
          </div>
        </div>
      </div>
    </div>
    <!-- #END# Basic Examples --> 
  </div>
</section>

<script type="text/javascript">
	var searchIDs = [];
	function searchData(){
		window.location.href="{{url('admins/products')}}";	
	}
	function applyOffer(id){
		var offerValue = $('#offer_vale_'+id).val();
		var offerType = $('#offer_type_'+id).val();
		if(id > 0 && offerValue > 0 && offerType != ''){
			$.ajax({
					type: 'POST',
					url: "{{url('admins/apply-product-offer')}}",
					data: {id:id,offerValue:offerValue,offerType:offerType}, 
					headers:{
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					success: function(msg){
						window.location.reload();
					},error: function(ts) { 
						$('#error500').modal('show');
					}
				})
			
		}
	}
	function exportData(){
		if(searchIDs.length > 0){
			$.ajax({
					type: 'POST',
					url: "{{url('admins/export-product')}}",
					data: {rowIds:searchIDs}, 
					headers:{
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					success: function(msg){
						window.open(msg, '_blank');
					},error: function(ts) { 
						$('#error500').modal('show');
					}
				})
		}else{
			swal({
				title: "Select at least one item",
				text: "",
				timer: 2000,
				showConfirmButton: false
			});
		}
	}
	function selectAllItem(){
		var searchIDs = [];
		if($("#select_all").prop('checked') == true){
			$(".item_check").prop('checked', true);
			selectItem();
		}else{
			$(".item_check").prop('checked', false);
			
		}
	}
	function selectItem(){

		$(".check_colum input:checkbox:checked").map(function(){
			searchIDs.push($(this).val());
		});
		
		console.log(searchIDs);
	}
	function deleteAll(){
		if(searchIDs.length > 0){
			swal({
			title: "Do you want to delete this record?",
			text: "",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: '#DD6B55',
			cancelButtonText: "No",
			confirmButtonText: 'Yes',
			closeOnConfirm: false,
			closeOnCancel: false
		},

		function(isConfirm){
			if (isConfirm){			  
			  $.ajax({
					type: 'POST',
					url: "{{url('admins/delete-product-bulk')}}",
					data: {rowIds:searchIDs},
					headers:{
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					success: function(msg){
						window.location.reload();
					},error: function(ts) { 
						$('#error500').modal('show');
					}
				})
			} else {
			  swal("Cancelled", "", "error");
			}
		});
		}else{
			swal({
				title: "Select at least one item",
				text: "",
				timer: 2000,
				showConfirmButton: false
			});
		}
	}
</script>
@endsection