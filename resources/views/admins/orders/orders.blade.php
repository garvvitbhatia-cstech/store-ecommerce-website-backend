@extends('layout.admin')
@section('title', 'Orders')

@section('content')
@extends('element.admin.jQuery')

<section class="content">
  <div class="container-fluid">
    <div class="block-header">
      <h2>Orders</h2>
    </div>
    <!-- Basic Examples -->
    <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      	<div class="card">
        	<div class="header">
            <div class="row"> {{ Form::open(array('url' => array('/admins/orders'),'id' => 'pageForm', 'method' => 'post')) }}              
			<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <input type="text" name="invoice_id" id="invoice_id" class="form-control" placeholder="Search By Invoice ID">
                &nbsp; </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <input type="text" name="customer_name" class="form-control" placeholder="Search By Customer Name">
                &nbsp; </div>
              <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <input type="text" name="customer_email" class="form-control" placeholder="Search By Customer Email">
                &nbsp; </div>
              <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <input type="text" name="customer_contact" maxlength="10" id="customer_contact" class="form-control" placeholder="Search By Customer Contact">
                &nbsp; </div>
              <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <input type="text" name="online_transaction_id" class="form-control" placeholder="Search By Transaction ID">
                &nbsp; </div>
              <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <select name="item_status" class="form-control">
                  <option value="">Item Status</option>
                  <option value="Pending">Pending</option>
                  <option value="Delivered">Delivered</option>
                  <option value="Cancelled">Cancelled</option>
                </select>
                &nbsp; </div>
              <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <select name="payment_status" class="form-control">
                  <option value="">Payment Status</option>
                  <option value="Pending">Pending</option>
                  <option value="Received">Received</option>
                  <option value="Completed">Completed</option>
                </select>
                &nbsp; </div>
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button type="submit" class="btn btn-default waves-effect">Search</button>
                <button type="submit" class="btn btn-default waves-effect">Reset</button>
              </div>
              {{ Form::close() }} 
              </div>  
          </div>
        </div>
        <div class="card">
          
          <div class="body">
            <div class="table-responsive">
              <div class="dataTables_wrapper form-inline dt-bootstrap">
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                  <thead>
                    <tr>
                      	<th>#</th>
                      	<th>Customer Details</th>
                        <th>Order Details</th>
                      	<th>Created</th>
                      	<th>Action</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Customer Details</th>
                        <th>Order Details</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                  </tfoot>
                  <tbody>
                  
                  @if(isset($pages) && $pages->count() > 0)
                  @foreach($pages as $key => $order)
                  @php
                  $isExists = 0;
                  @endphp
                  <tr>
                    <td>{{ $key+1; }}</td>
                    <td>
                    	<b>Name:</b> {{ ($order->customer_name); }}<br>
                        <b>Email:</b> {{ ($order->customer_email); }}<br>
                        <b>Contact:</b> {{ ($order->customer_contact); }}<br>
                        <b>Txn ID:</b> {{ ($order->online_transaction_id); }}<br>
                        <b>Payment Status:</b> {{ ($order->payment_status); }}
                    </td>
                    <td>
                    	<b>Type:</b> {{ ($order->order_type); }}<br>
                        <b>Invoice ID:</b> {{ ($order->invoice_id); }}<br>
                        @php $total = ($order->price+$order->gst+$order->shipping); @endphp
                        <b>Price:</b> <span class="fa fa-rupee"></span> {{ (number_format($total,2)); }}<br>
                        <b>Payment Method:</b> {{ ($order->payment_method); }}
                    </td>
                    <td>{{ date("F jS, Y h:i A",strtotime($order->created_at)); }}</td>
                    <td><a href="{{ url('admins/view-order',Crypt::encrypt($order->id)) }}" title="View" class="btn bg-blue waves-effect"><i class="material-icons">visibility</i></a>
                      <button onclick="deleteRecord('orders','{{ Crypt::encrypt($order->id) }}',{{ $isExists; }});" type="button" title="Delete" class="btn bg-red waves-effect"> <i class="material-icons">delete</i> </button></td>
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
            {!! $pages->appends(request()->except('page','_token'))->links('pagination.custom') !!}    </div>
        </div>
      </div>
    </div>
    <!-- #END# Basic Examples --> 
  </div>
</section>
<script type="text/javascript">
	$(document).ready(function(){
		$('#customer_contact').filter_input({regex:'[0-9]'});
		$('#invoice_id').filter_input({regex:'[0-9]'});
	});

	function searchData(){
		window.location.href="{{url('admins/orders')}}";	
	}
</script> 
@endsection