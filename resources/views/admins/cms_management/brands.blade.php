@extends('layout.admin')
@section('title', 'Brands')

@section('content')
@extends('element.admin.jQuery')
<section class="content">
  <div class="container-fluid">
    <div class="block-header">
      <h2>Brands</h2>
    </div>
    <!-- Basic Examples -->
    <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
          <div class="header">
            <div class="row"> 
            {{ Form::open(array('url' => array('/admins/brands'),'id' => 'pageForm', 'method' => 'post')) }}
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
                <a href="{{url('admins/add-brand')}}"><button type="button" class="btn btn-warning btn-lg waves-effect">Add Brand</button></a>
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
                      <th>Title</th>
                      <th>Banner</th>
                      <th class="text-center">Status</th>
                      <th>Created</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>#</th>
                      <th>Title</th>
                      <th>Banner</th>
                      <th class="text-center">Status</th>
                      <th>Created</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                  <tbody>
                  
                  @if(isset($pages) && $pages->count() > 0)
                  @foreach($pages as $key => $brand)
                  @php
                  $isExists = 0;
                  @endphp
                  <tr>
                    <td>{{ $brand->id; }}</td>
                    <td>{{ $brand->title; }}</td>
                    <td>
                    @if($brand->banner != '')
                      <img width="100px;" alt="" title="" src="{{ URL::asset('public/assets/images/admin/gallery/') }}/{!! $brand->banner !!}" />
                      @endif
                    </td>
                    <td class="text-center"> 
                    @if($brand->status == 1)
                      <button onclick="changeStatus('brands','{{ Crypt::encrypt($brand->id) }}','{{ $brand->status }}','{{ $brand->id }}');" type="button" class="btn btn-success btn-circle waves-effect waves-circle waves-float"> <i class="material-icons" id="material_icons_{{$brand->id}}">check</i> </button>
                      @else
                      <button onclick="changeStatus('brands','{{ Crypt::encrypt($brand->id) }}','{{ $brand->status }}','{{ $brand->id }}');" type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float"> <i class="material-icons" id="material_icons_{{$brand->id}}">clear</i> </button>
                      @endif
                      <input type="hidden" id="current_status{{ $brand->id }}" value="{{ $brand->status }}"/>
                    </td>
                    <td>{{ date("F jS, Y h:i A",strtotime($brand->created_at)); }}</td>
                    <td><a href="{{ url('admins/edit-brand',Crypt::encrypt($brand->id)) }}">
                      <button type="button" title="Edit" class="btn bg-blue waves-effect"> <i class="material-icons">edit</i> </button></a>
                      <button onclick="deleteRecord('brands','{{ Crypt::encrypt($brand->id) }}',{{ $isExists; }});" type="button" title="Delete" class="btn bg-red waves-effect"> <i class="material-icons">delete</i> </button></td>
                  </tr>
                  @endforeach
                  
                  @else
                  <tr>
                    <td align="center" colspan="9">Record not found</td>
                  </tr>
                  @endif
                    </tbody>
                  
                </table>
              </div>
            </div>
           {!! $pages->appends(request()->except('page','_token'))->links('pagination.custom') !!}</div>
        </div>
      </div>
    </div>
    <!-- #END# Basic Examples --> 
  </div>
</section>
<script type="text/javascript">
	function searchData(){
		window.location.href="{{url('admins/brands')}}";	
	}
</script> 
@endsection