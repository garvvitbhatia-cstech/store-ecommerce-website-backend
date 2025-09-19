@extends('layout.admin')
@section('title', 'Dashboard')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="block-header">
      <h2>DASHBOARD</h2>
    </div>
    <style>
    	a:link { text-decoration: none; }
    </style>
    <!-- Widgets -->
    <div class="row clearfix">
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-pink hover-expand-effect">
          <div class="icon"> <i class="material-icons">playlist_add_check</i> </div>
          <div class="content">
            <div class="text">NEW TASKS</div>
            <div class="number count-to" data-from="0" data-to="125" data-speed="15" data-fresh-interval="20"></div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-cyan hover-expand-effect">
          <div class="icon"> <i class="material-icons">help</i> </div>
          <div class="content">
            <div class="text">NEW TICKETS</div>
            <div class="number count-to" data-from="0" data-to="257" data-speed="1000" data-fresh-interval="20"></div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-light-green hover-expand-effect">
          <div class="icon"> <i class="material-icons">forum</i> </div>
          <div class="content">
            <div class="text">NEW COMMENTS</div>
            <div class="number count-to" data-from="0" data-to="243" data-speed="1000" data-fresh-interval="20"></div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-orange hover-expand-effect">
          <div class="icon"> <i class="material-icons">person_add</i> </div>
          <div class="content">
            <div class="text">NEW VISITORS</div>
            <div class="number count-to" data-from="0" data-to="1225" data-speed="1000" data-fresh-interval="20"></div>
          </div>
        </div>
      </div>
    </div>
    <!-- #END# Widgets --> 
    <!-- CPU Usage -->
    <div class="row clearfix">
      <div class="col-lg-12">
        <div class="alert alert-info"> <i class="fa fa-folder-open"></i><b>&nbsp; </b>Location Management<b></b> </div>
      </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="{{ url('admins/countries/'); }}"><div class="info-box-4 hover-zoom-effect" style="cursor:pointer">
            <div class="icon">
                <i class="material-icons col-cyan">gps_fixed</i>
            </div>
            <div class="content">
                <div class="text">&nbsp;</div>
                <div class="number">Country</div>
            </div>
        </div></a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{ url('admins/states/'); }}"><div class="info-box-4 hover-zoom-effect" style="cursor:pointer">
                <div class="icon">
                    <i class="material-icons col-cyan">gps_fixed</i>
                </div>
                <div class="content">
                    <div class="text">&nbsp;</div>
                    <div class="number">State</div>
                </div>
            </div></a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{ url('admins/cities/'); }}"><div class="info-box-4 hover-zoom-effect" style="cursor:pointer">
                <div class="icon">
                    <i class="material-icons col-cyan">gps_fixed</i>
                </div>
                <div class="content">
                    <div class="text">&nbsp;</div>
                    <div class="number">City</div>
                </div>
            </div></a>
        </div>
    </div>    
    <div class="row clearfix">
      <div class="col-lg-12">
        <div class="alert alert-info"> <i class="fa fa-folder-open"></i><b>&nbsp; </b>Subscription Management<b></b> </div>
      </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="{{ url('admins/subscriptions/'); }}"><div class="info-box-4 hover-zoom-effect" style="cursor:pointer">
            <div class="icon">
                <i class="material-icons col-cyan">gps_fixed</i>
            </div>
            <div class="content">
                <div class="text">&nbsp;</div>
                <div class="number">Subscription</div>
            </div>
        </div></a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="{{ url('admins/coupon-code/'); }}"><div class="info-box-4 hover-zoom-effect" style="cursor:pointer">
            <div class="icon">
                <i class="material-icons col-cyan">gps_fixed</i>
            </div>
            <div class="content">
                <div class="text">&nbsp;</div>
                <div class="number">Coupon Code</div>
            </div>
        </div></a>
        </div>
    </div>
    <div class="row clearfix">
      <div class="col-lg-12">
        <div class="alert alert-info"> <i class="fa fa-folder-open"></i><b>&nbsp; </b>Order Management<b></b> </div>
      </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="{{ url('admins/orders/'); }}"><div class="info-box-4 hover-zoom-effect" style="cursor:pointer">
            <div class="icon">
                <i class="material-icons col-cyan">gps_fixed</i>
            </div>
            <div class="content">
                <div class="text">&nbsp;</div>
                <div class="number">Orders</div>
            </div>
        </div></a>
        </div>         
    </div>
    <div class="row clearfix">
      <div class="col-lg-12">
        <div class="alert alert-info"> <i class="fa fa-folder-open"></i><b>&nbsp; </b>Master Management<b></b> </div>
      </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="{{ url('admins/colors/'); }}"><div class="info-box-4 hover-zoom-effect" style="cursor:pointer">
            <div class="icon">
                <i class="material-icons col-cyan">gps_fixed</i>
            </div>
            <div class="content">
                <div class="text">&nbsp;</div>
                <div class="number">Colors</div>
            </div>
        </div></a>
        </div> 
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="{{ url('admins/sizes/'); }}"><div class="info-box-4 hover-zoom-effect" style="cursor:pointer">
            <div class="icon">
                <i class="material-icons col-cyan">gps_fixed</i>
            </div>
            <div class="content">
                <div class="text">&nbsp;</div>
                <div class="number">Sizes</div>
            </div>
        </div></a>
        </div> 
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="{{ url('admins/brands/'); }}"><div class="info-box-4 hover-zoom-effect" style="cursor:pointer">
            <div class="icon">
                <i class="material-icons col-cyan">gps_fixed</i>
            </div>
            <div class="content">
                <div class="text">&nbsp;</div>
                <div class="number">Brands</div>
            </div>
        </div></a>
        </div>         
    </div>
    <div class="row clearfix">
      <div class="col-lg-12">
        <div class="alert alert-info"> <i class="fa fa-folder-open"></i><b>&nbsp; </b>Product Management<b></b> </div>
      </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="{{ url('admins/category/'); }}"><div class="info-box-4 hover-zoom-effect" style="cursor:pointer">
            <div class="icon">
                <i class="material-icons col-cyan">gps_fixed</i>
            </div>
            <div class="content">
                <div class="text">&nbsp;</div>
                <div class="number">Categories</div>
            </div>
        </div></a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="{{ url('admins/products/'); }}"><div class="info-box-4 hover-zoom-effect" style="cursor:pointer">
            <div class="icon">
                <i class="material-icons col-cyan">gps_fixed</i>
            </div>
            <div class="content">
                <div class="text">&nbsp;</div>
                <div class="number">Products</div>
            </div>
        </div></a>
        </div>
    </div>
    <div class="row clearfix">
      <div class="col-lg-12">
        <div class="alert alert-info"> <i class="fa fa-folder-open"></i><b>&nbsp; </b>Content Management System<b></b> </div>
      </div>        
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="{{ url('admins/contacts/'); }}"><div class="info-box-4 hover-zoom-effect" style="cursor:pointer">
            <div class="icon">
                <i class="material-icons col-cyan">gps_fixed</i>
            </div>
            <div class="content">
                <div class="text">&nbsp;</div>
                <div class="number">Contact Us</div>
            </div>
        </div></a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="{{ url('admins/inner-pages/'); }}"><div class="info-box-4 hover-zoom-effect" style="cursor:pointer">
            <div class="icon">
                <i class="material-icons col-cyan">gps_fixed</i>
            </div>
            <div class="content">
                <div class="text">&nbsp;</div>
                <div class="number">Inner Pages</div>
            </div>
        </div></a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="{{ url('admins/blog-categories/'); }}"><div class="info-box-4 hover-zoom-effect" style="cursor:pointer">
            <div class="icon">
                <i class="material-icons col-cyan">gps_fixed</i>
            </div>
            <div class="content">
                <div class="text">&nbsp;</div>
                <div class="number">Blog Categories</div>
            </div>
        </div></a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="{{ url('admins/blogs/'); }}"><div class="info-box-4 hover-zoom-effect" style="cursor:pointer">
            <div class="icon">
                <i class="material-icons col-cyan">gps_fixed</i>
            </div>
            <div class="content">
                <div class="text">&nbsp;</div>
                <div class="number">Blogs</div>
            </div>
        </div></a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="{{ url('admins/tags/'); }}"><div class="info-box-4 hover-zoom-effect" style="cursor:pointer">
            <div class="icon">
                <i class="material-icons col-cyan">gps_fixed</i>
            </div>
            <div class="content">
                <div class="text">&nbsp;</div>
                <div class="number">Tags</div>
            </div>
        </div></a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="{{ url('admins/users/'); }}"><div class="info-box-4 hover-zoom-effect" style="cursor:pointer">
            <div class="icon">
                <i class="material-icons col-cyan">gps_fixed</i>
            </div>
            <div class="content">
                <div class="text">&nbsp;</div>
                <div class="number">Customers</div>
            </div>
        </div></a>
        </div>
    </div>
    <div class="row clearfix">
      <div class="col-lg-12">
        <div class="alert alert-info"> <i class="fa fa-folder-open"></i><b>&nbsp; </b>Gallery Management<b></b> </div>
      </div>        
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="{{ url('admins/gallery/'); }}"><div class="info-box-4 hover-zoom-effect" style="cursor:pointer">
            <div class="icon">
                <i class="material-icons col-cyan">gps_fixed</i>
            </div>
            <div class="content">
                <div class="text">&nbsp;</div>
                <div class="number">Gallery</div>
            </div>
        </div></a>
        </div> 
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="{{ url('admins/banners/'); }}"><div class="info-box-4 hover-zoom-effect" style="cursor:pointer">
            <div class="icon">
                <i class="material-icons col-cyan">gps_fixed</i>
            </div>
            <div class="content">
                <div class="text">&nbsp;</div>
                <div class="number">Banners</div>
            </div>
        </div></a>
        </div> 
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="{{ url('admins/testimonials/'); }}"><div class="info-box-4 hover-zoom-effect" style="cursor:pointer">
            <div class="icon">
                <i class="material-icons col-cyan">gps_fixed</i>
            </div>
            <div class="content">
                <div class="text">&nbsp;</div>
                <div class="number">Testimonials</div>
            </div>
        </div></a>
        </div>       
    </div>
  </div>
</section>
@endsection