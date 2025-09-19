@extends('layout.admin')
@section('title', 'Contact Us')
 
@section('content')
@extends('element.admin.jQuery')
<section class="content">
  <div class="container-fluid">
    <div class="block-header">
      <h2>Contact Us</h2>
    </div>
    <!-- Basic Examples -->
    <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
        
          <div class="header">
            <div class="row">
            	{{ Form::open(array('url' => array('/admins/contacts'),'id' => 'pageForm', 'method' => 'post')) }}
               
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <select name="read_status" class="form-control">
                	<option value="">Read Status</option>
                    <option value="1">Read</option>
                    <option value="2">Unread</option>
                </select>
                &nbsp; </div>
              <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <button type="submit" class="btn btn-default waves-effect">Search</button>
              </div>
              {{ Form::close() }}
            </div>
             
          </div>          
          <div class="body">
            <div class="table-responsive">
            <div class="dataTables_wrapper form-inline dt-bootstrap">
              <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Subject</th>
                    <th>Created</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Subject</th>
                    <th>Created</th>
                    <th>Action</th>
                  </tr>
                </tfoot>                
                <tbody>               
                @if(isset($pages) && $pages->count() > 0)
                @foreach($pages as $key => $contact)
                @php
                	$weight = ($contact->read_status == 2)?600:0;
                @endphp
                <tr>
                  <td style="font-weight:{{ $weight }}">{{ $key+1; }}</td>   
                  <td style="font-weight:{{ $weight }}">{{ $contact->type }}</td>               
                  <td style="font-weight:{{ $weight }}">{{ $contact->name }}</td>
                  <td style="font-weight:{{ $weight }}">{{ $contact->email }}</td>
                  <td style="font-weight:{{ $weight }}">{{ $contact->phone }}</td>
                  <td style="font-weight:{{ $weight }}">{{ $contact->subject }}</td>
                  <td style="font-weight:{{ $weight }}">{{ date("F jS, Y h:i A",strtotime($contact->created_at)); }}</td>
                  <td style="font-weight:{{ $weight }}">
                  	<a href="{{ url('admins/view-contact',Crypt::encrypt($contact->id)) }}"><button type="button" title="View" class="btn bg-purple waves-effect">
                        <i class="material-icons">visibility</i>
                    </button></a>
                    <button onclick="deleteRecord('contacts','{{ Crypt::encrypt($contact->id) }}','0')" type="button" title="Delete" class="btn bg-red waves-effect">
                        <i class="material-icons">delete</i>
                    </button>
                  </td>
                </tr>
                @endforeach
                
                @else
                <tr>
                  <td align="center" colspan="8">Record not found</td>
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
	function searchData(){
		window.location.href="{{url('admins/contacts')}}";	
	}
</script>
@endsection