@extends('layout.admin')
@section('title', 'Testimonials')

@section('content')
@extends('element.admin.jQuery')
<section class="content">
  <div class="container-fluid">
    <div class="block-header">
      <h2>Testimonials</h2>
    </div>
    <!-- Basic Examples -->
    <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
          <div class="header">
            <div class="row"> {{ Form::open(array('url' => array('/admins/testimonials'),'id' => 'pageForm', 'method' => 'post')) }}
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
                <a href="{{url('admins/add-testimonial')}}"><button type="button" class="btn btn-warning btn-lg waves-effect">Add Testimonial</button></a>
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
                      <th>Username</th>
                      <th class="text-center">Status</th>
                      <th>Created</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>#</th>
                      <th>Username</th>
                      <th class="text-center">Status</th>
                      <th>Created</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                  <tbody>
                  
                  @if(isset($pages) && $pages->count() > 0)
                  @foreach($pages as $key => $testimonial)
                  @php
                  $isExists = 0;
                  @endphp
                  <tr>
                    <td>{{ $key+1; }}</td>
                    <td>{{ $testimonial->username; }}</td>
                    <td class="text-center"> @if($testimonial->status == 1)
                      <button onclick="changeStatus('testimonials','{{ Crypt::encrypt($testimonial->id) }}','{{ $testimonial->status }}','{{ $testimonial->id }}');" type="button" class="btn btn-success btn-circle waves-effect waves-circle waves-float"> <i class="material-icons" id="material_icons_{{$testimonial->id}}">check</i> </button>
                      @else
                      <button onclick="changeStatus('testimonials','{{ Crypt::encrypt($testimonial->id) }}','{{ $testimonial->status }}','{{ $testimonial->id }}');" type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float"> <i class="material-icons" id="material_icons_{{$testimonial->id}}">clear</i> </button>
                      @endif
                      <input type="hidden" id="current_status{{ $testimonial->id }}" value="{{ $testimonial->status }}"/></td>
                    <td>{{ date("F jS, Y h:i A",strtotime($testimonial->created_at)); }}</td>
                    <td><a href="{{ url('admins/edit-testimonial',Crypt::encrypt($testimonial->id)) }}">
                      <button type="button" title="Edit" class="btn bg-blue waves-effect"> <i class="material-icons">edit</i> </button></a>
                      <button onclick="deleteRecord('testimonials','{{ Crypt::encrypt($testimonial->id) }}',{{ $isExists; }});" type="button" title="Delete" class="btn bg-red waves-effect"> <i class="material-icons">delete</i> </button></td>
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
		window.location.href="{{url('admins/testimonials')}}";	
	}
</script> 
@endsection