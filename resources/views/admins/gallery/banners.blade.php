@extends('layout.admin')
@section('title', 'Banner')

@section('content')

<section class="content">
  <div class="container-fluid">
    <div class="block-header">
      <h2>Banners</h2>
    </div>
    <!-- Basic Examples -->
    <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        
          <div class="header">
            <div class="row">
            	{{ Form::open(array('url' => array('/admins/banners'),'id' => 'pageForm', 'method' => 'post')) }}
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <select name="page" id="page" class="form-control filter-input">
                	<option value="">Page</option>
                    <option value="Home">Home</option>
                </select>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <select name="status" id="status" class="form-control filter-input">
                	<option value="">Status</option>
                    <option value="1">Active</option>
                    <option value="2">In-Active</option>
                </select>
               </div>
              <div id="searchFilterBtn" class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <button type="submit" class="btn btn-default waves-effect mr-2">Search</button>
                <button type="button" onclick="resetFilter();" class="btn btn-default waves-effect">Reset</button>
              </div> 
              {{ Form::close() }}
            </div>
            <ul class="header-dropdown">
              <li class="dropdown">
                <a href="{{url('admins/add-banner')}}"><button type="button" class="btn btn-warning btn-lg waves-effect">Add Banner</button></a>
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
                    <th>Banner</th>
                    <th>Page</th>
                    <th>Heading</th>
                    <th class="text-center">Status</th>
                    <th>Created</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Banner</th>
                    <th>Page</th>
                    <th>Heading</th>
                    <th class="text-center">Status</th>
                    <th>Created</th>
                    <th>Action</th>
                  </tr>
                </tfoot>                
                <tbody>               
                @if(isset($banners) && $banners->count() > 0)
                @foreach($banners as $key => $banner)
                @php
                	$isExists = 0;
                @endphp
                <tr>
                  <td>{{ $key+1 }}</td>
                  <td>
                        <a href="{{ URL::asset('public/assets/images/admin/banners/') }}/{!! $banner->banner !!}" data-sub-html="Banner">
                            <img width="100px" class="img-responsive" src="{{ URL::asset('public/assets/images/admin/banners/') }}/{!! $banner->banner !!}">
                        </a>
                    </td>
                  <td>{{ $banner->page }}</td>
                  <td>{{ $banner->heading }}</td>
                  <td class="text-center"> @if($banner->status == 1)                   
                  	<button @php if($isExists == 0){ @endphp; onclick="changeStatus('banners','{{ Crypt::encrypt($banner->id) }}','{{ $banner->status }}','{{ $banner->id }}');" @php } @endphp; type="button" class="btn btn-success btn-circle waves-effect waves-circle waves-float">
                    	<i class="material-icons" id="material_icons_{{$banner->id}}">check</i>
                	</button>
                                 
                  @else
                                    
                  	<button @php if($isExists == 0){ @endphp; onclick="changeStatus('banners','{{ Crypt::encrypt($banner->id) }}','{{ $banner->status }}','{{ $banner->id }}');" @php } @endphp; type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float">
                    	<i class="material-icons" id="material_icons_{{$banner->id}}">clear</i>
                	</button>
                    
                  @endif 
                  <input type="hidden" id="current_status{{ $banner->id }}" value="{{ $banner->status }}"/>
                  </td>
                  <td>{{ date("F jS, Y h:i A",strtotime($banner->created_at)); }}</td>
                  <td>
                  	<a href="{{ url('admins/edit-banner',Crypt::encrypt($banner->id)) }}"><button type="button" title="Edit" class="btn bg-blue waves-effect">
                        <i class="material-icons">edit</i>
                    </button></a>
                    <button onclick="deleteRecord('banners','{{ Crypt::encrypt($banner->id) }}',{{ $isExists; }});" type="button" title="Delete" class="btn bg-red waves-effect">
                        <i class="material-icons">delete</i>
                    </button>
                  </td>
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
            {!! $banners->appends(request()->except('page','_token'))->links('pagination.custom') !!}      
          </div>
        </div>
      </div>
    </div>
    <!-- #END# Basic Examples --> 
  </div>
</section>

<script type="text/javascript">
	function searchData(){
		window.location.href="{{url('admins/banners')}}";
	}
</script>
@endsection