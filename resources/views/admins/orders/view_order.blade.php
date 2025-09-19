@extends('layout.admin')
@section('title', 'View Order')
@section('content')
<section class="content">
   <div class="container-fluid">
      <div class="block-header">
         <h2>View Order</h2>
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
                              
                              @csrf
                              <label>Customer Details</label><br /><br />
                              <div class="form-group form-float">
                              	<label class="form-label">Name</label>
                                 <div class="form-line">
                                    {{ ($order->customer_name); }}
                                 </div>
                              </div>
                              <div class="form-group form-float">
                              	<label class="form-label">Name</label>
                                 <div class="form-line">
                                    {{ ($order->customer_name); }}
                                 </div>
                              </div>
                              <div class="form-group form-float">
                              	<label class="form-label">Email</label>
                                 <div class="form-line">
                                    {{ ($order->customer_email); }}
                                 </div>
                              </div>
                              <div class="form-group form-float">
                              	<label class="form-label">Address</label>
                                 <div class="form-line">
                                    {{ ($order->customer_address); }}
                                 </div>
                              </div>
                              <div class="form-group form-float">
                              	<label class="form-label">Pincode</label>
                                 <div class="form-line">
                                    {{ ($order->customer_pincode); }}
                                 </div>
                              </div>
                              <div class="form-group form-float">
                              	<label class="form-label">Latitude</label>
                                 <div class="form-line">
                                    {{ ($order->customer_latitude); }}
                                 </div>
                              </div>
                              <div class="form-group form-float">
                              	<label class="form-label">Longitude</label>
                                 <div class="form-line">
                                    {{ ($order->customer_longitude); }}
                                 </div>
                              </div>
                              <div class="form-group form-float">
                              	<label class="form-label">TXN ID</label>
                                 <div class="form-line">
                                    {{ ($order->online_transaction_id); }}
                                 </div>
                              </div>
                              <div class="form-group form-float">
                              	<label class="form-label">Payment Status</label>
                                 <div class="form-line">
                                    {{ ($order->payment_status); }}
                                 </div>
                              </div>
                              
                                                            
                              <label>Order Details</label><br /><br />
                              <div class="form-group form-float">
                              	<label class="form-label">Type</label>
                                 <div class="form-line">
                                    {{ ($order->order_type); }}
                                 </div>
                              </div>
                              <div class="form-group form-float">
                              	<label class="form-label">Invoice ID</label>
                                 <div class="form-line">
                                    {{ ($order->invoice_id); }}
                                 </div>
                              </div>
                              <div class="form-group form-float">
                              	<label class="form-label">Price</label>
                                 <div class="form-line">
                                    {{ number_format($order->price+$order->gst+$order->shipping,2); }}
                                 </div>
                              </div>
                              <div class="form-group form-float">
                              	<label class="form-label">TXN ID</label>
                                 <div class="form-line">
                                    {{ ($order->online_transaction_id); }}
                                 </div>
                              </div>
                              <div class="form-group form-float">
                              	<label class="form-label">Payment Method</label>
                                 <div class="form-line">
                                    {{ ($order->payment_method); }}
                                 </div>
                              </div>
                              
                              <a href="{{ url('admins/orders') }}" class="btn btn-primary m-t-15 waves-effect">Back</a>
                               
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
@endsection