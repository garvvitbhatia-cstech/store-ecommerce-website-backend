@extends('layout.admin')
@section('title', 'Category')
 
@section('content')
@extends('element.admin.jQuery')
<section class="content">
  <div class="container-fluid">
    <div class="block-header">
      <h2>Categories</h2>
    </div>
    <!-- Basic Examples -->
    <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        
          <div class="header">
            <div class="row">
            	{{ Form::open(array('url' => array('/admins/category'),'id' => 'pageForm', 'method' => 'post')) }}
              <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <input type="text" name="title" class="form-control" placeholder="Search By Title">
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
              </div>
              {{ Form::close() }}
            </div>
            <ul class="header-dropdown">
              <li class="dropdown">
                <a href="{{url('admins/add-category')}}"><button type="button" class="btn btn-warning btn-lg waves-effect">Add Category</button></a>
              </li>
              <li class="dropdown">
                <a class="btn btn-primary btn-lg waves-effect" target="_blank" href="https://api.anupamstores.com/storage/sample-csv/categories.csv">Sample CSV</a>
              </li>
              <li class="dropdown">
                <a class="btn btn-warning btn-lg waves-effect" href="{{url('admins/import-category')}}">Import</a>
              </li>
              <li class="dropdown">
                <a class="btn btn-danger btn-lg waves-effect" onclick="deleteAll();">Delete</a>
              </li>
            </ul>
          </div>          
          <div class="body">
            <div class="table-responsive">
            <div class="dataTables_wrapper form-inline dt-bootstrap">
              <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                <thead>
                  <tr>
                    <th>#</th>
                    <th><input type="checkbox" onchange="selectAllItem();" id="select_all" /></th>
                    <th>Category</th>
                    <th>Banner</th>
                    <th class="text-center">Status</th>
                    <th>Created</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>#</th>
                    <th>#</th>
                    <th>Category</th>
                    <th>Banner</th>
                    <th class="text-center">Status</th>
                    <th>Created</th>
                    <th>Action</th>
                  </tr>
                </tfoot>                
                <tbody>               
                @if(isset($pages) && $pages->count() > 0)
                @foreach($pages as $key => $category)
                <tr>
                  <td>{{ $category->id }}</td>  
                  <td class="check_colum"><input type="checkbox" onchange="selectItem();" class="item_check" value="{{ $category->id }}" name="" /></td>                
                  <td>
                  	@php
                    	$isExists = 0;
                        $isExists = Helper::isParentExist($category->id);
                        if($category->parent_id > 0){
                        	echo Helper::getCategoryName($category->parent_id);
                            echo ' → '; 
                       	}
                        echo $category->title;
                    @endphp
                  </td>
                  <td> @if($category->category_banner) 
                  		<img src="{{ URL::asset('public/assets/images/admin/categories/') }}/{!! $category->category_banner !!}" width="100px;" title="{{ $category->category_banner }}" alt="{{ $category->category_banner }}"/> 
                      @else
                    	Not Available
                    @endif </td>
                  <td class="text-center"> 
                  @if($category->status == 1) 
                  <!--<span style="color:#390">{{ 'Active' }}</span>-->
                  
                  	<button onclick="changeStatus('categories','{{ Crypt::encrypt($category->id) }}','{{ $category->status }}','{{ $category->id }}');" type="button" class="btn btn-success btn-circle waves-effect waves-circle waves-float">
                    	<i class="material-icons" id="material_icons_{{$category->id}}">check</i>
                	</button>
                                 
                  @else 
                  <!--<span style="color:#F00">{{ 'In-Active' }}</span> -->
                  
                  	<button  onclick="changeStatus('categories','{{ Crypt::encrypt($category->id) }}','{{ $category->status }}','{{ $category->id }}');" type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float">
                    	<i class="material-icons" id="material_icons_{{$category->id}}">clear</i>
                	</button>
                    
                  @endif 
                  <input type="hidden" id="current_status{{ $category->id }}" value="{{ $category->status }}"/>
                  </td>
                  <td>{{ date("F jS, Y h:i A",strtotime($category->created_at)); }}</td>
                  <td>
                  	<a href="{{ url('admins/edit-category',Crypt::encrypt($category->id)) }}"><button type="button" title="Edit" class="btn bg-blue waves-effect">
                        <i class="material-icons">edit</i>
                    </button></a>
                    <button onclick="deleteRecord('categories','{{ Crypt::encrypt($category->id) }}',{{ $isExists; }});" type="button" title="Delete" class="btn bg-red waves-effect">
                        <i class="material-icons">delete</i>
                    </button>
                  </td>
                </tr>
                @endforeach
                
                @else
                <tr>
                  <td align="center" colspan="6">Record not found</td>
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
		window.location.href="{{url('admins/category')}}";	
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
					url: "{{url('admins/delete-category-bulk')}}",
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