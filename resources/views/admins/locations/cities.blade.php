@extends('layout.admin')
@section('title', 'Cities')

@section('content')
@extends('element.admin.jQuery')
<section class="content">
  <div class="container-fluid">
    <div class="block-header">
      <h2>Cities</h2>
    </div>
    <!-- Basic Examples -->
    <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
          <div class="header">
            <div class="row"> {{ Form::open(array('url' => array('/admins/cities'),'id' => 'pageForm', 'method' => 'post')) }}
              <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <input type="text" name="city" class="form-control" placeholder="Search By Name">
                &nbsp; </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <select name="country_id" class="form-control">
                  	<option value="">Select Country</option>
                    @foreach($country_list as $key => $country){
                        <option value="{{ $key }}">{{ $country }}</option>
                    @endforeach
                </select>
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
              {{ Form::close() }} </div>
            <ul class="header-dropdown">
              <li class="dropdown"> <a href="{{url('admins/add-city')}}">
                <button type="button" class="btn btn-warning btn-lg waves-effect">Add City</button>
                </a> </li>
            </ul>
          </div>
          <div class="body">
            <div class="table-responsive">
              <div class="dataTables_wrapper form-inline dt-bootstrap">
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>City</th>
                      <th>Country</th>
                      <th>State</th>
                      <th class="text-center">Status</th>
                      <th>Created</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>#</th>
                      <th>City</th>
                      <th>Country</th>
                      <th>State</th>
                      <th class="text-center">Status</th>
                      <th>Created</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                  <tbody>
                  
                  @if(isset($pages) && $pages->count() > 0)
                  @foreach($pages as $key => $city)
                  @php
                  $isExists = 0;
                  @endphp
                  <tr>
                    <td>{{ $key+1; }}</td>
                    <td>{{ $city->city; }}</td>
                    <td>{{ Helper::getCountryById($city->country_id,'country_name'); }}</td>
                    <td>{{ Helper::getStateById($city->state_id,'state') }}</td>
                    <td class="text-center"> @if($city->status == 1)
                      <button onclick="changeStatus('cities','{{ Crypt::encrypt($city->id) }}','{{ $city->status }}','{{ $city->id }}');" type="button" class="btn btn-success btn-circle waves-effect waves-circle waves-float"> <i class="material-icons" id="material_icons_{{$city->id}}">check</i> </button>
                      @else
                      <button onclick="changeStatus('cities','{{ Crypt::encrypt($city->id) }}','{{ $city->status }}','{{ $city->id }}');" type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float"> <i class="material-icons" id="material_icons_{{$city->id}}">clear</i> </button>
                      @endif
                      <input type="hidden" id="current_status{{ $city->id }}" value="{{ $city->status }}"/></td>
                    <td>{{ date("F jS, Y h:i A",strtotime($city->created_at)); }}</td>
                    <td><a href="{{ url('admins/edit-city',Crypt::encrypt($city->id)) }}">
                      <button type="button" title="Edit" class="btn bg-blue waves-effect"> <i class="material-icons">edit</i> </button>
                      </a>
                      <!--<button onclick="deleteRecord('cities','{{ Crypt::encrypt($city->id) }}',{{ $isExists; }});" type="button" title="Delete" class="btn bg-red waves-effect"> <i class="material-icons">delete</i> </button>-->
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
            {!! $pages->appends(request()->except('page','_token'))->links('pagination.custom') !!}   </div>
        </div>
      </div>
    </div>
    <!-- #END# Basic Examples --> 
  </div>
</section>
<script type="text/javascript">
	function searchData(){
		window.location.href="{{url('admins/cities')}}";	
	}
</script> 
@endsection