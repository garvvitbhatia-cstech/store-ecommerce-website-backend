<!DOCTYPE html>
<html><head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Admin - @yield('title')</title>
<!-- Favicon-->
<link rel="icon" href="{{ URL::asset('public/favicon.png') }}" type="image/x-icon">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

<!-- Bootstrap Core Css -->
<link href="{{ URL::asset('public/assets/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet">

<!-- Waves Effect Css -->
<link href="{{ URL::asset('public/assets/plugins/node-waves/waves.css') }}" rel="stylesheet" />

<!-- Animation Css -->
<link href="{{ URL::asset('public/assets/plugins/animate-css/animate.css') }}" rel="stylesheet" />

<!-- Morris Chart Css-->
<link href="{{ URL::asset('public/assets/plugins/morrisjs/morris.css') }}" rel="stylesheet" />
<!--<link href="{{ URL::asset('assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">-->
<!--<link href="{{ URL::asset('public/assets/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet" />-->

<!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
<link href="{{ URL::asset('public/assets/css/admin/themes/all-themes.css') }}" rel="stylesheet" />

<link href="{{ URL::asset('public/assets/css/admin/jquery-ui.css') }}" rel="stylesheet">

<link href="{{ URL::asset('public/assets/plugins/light-gallery/css/lightgallery.css') }}" rel="stylesheet">

<link href="{{ URL::asset('public/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet">

<link href="{{ URL::asset('public/assets/css/admin/redactor.css') }}" rel="stylesheet">

<!-- Custom Css -->
<link href="{{ URL::asset('public/assets/css/admin/style.css') }}" rel="stylesheet">

<!-- Jquery Core Js -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="{{ URL::asset('public/assets/js/admin/jquery.validate.min.js') }}"></script>

<script src="{{ URL::asset('public/assets/js/admin/jquery-ui.js') }}"></script>

</head>

<body class="theme-red">
<!-- Page Loader -->
<!--<div class="page-loader-wrapper">
  <div class="loader">
    <div class="preloader">
      <div class="spinner-layer pl-red">
        <div class="circle-clipper left">
          <div class="circle"></div>
        </div>
        <div class="circle-clipper right">
          <div class="circle"></div>
        </div>
      </div>
    </div>
    <p>Please wait...</p>
  </div>
</div>-->
<!-- #END# Page Loader --> 
<!-- Overlay For Sidebars -->
<div class="overlay"></div>
<!-- #END# Overlay For Sidebars --> 
<!-- Search Bar -->
<?php /*?><div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div><?php */?>
<!-- #END# Search Bar --> 
<!-- Top Bar -->  

@include('element.admin.header')
@include('element.admin.jQuery');
<!-- #Top Bar -->
<section> 
  <!-- Left Sidebar --> 
  @include('element.admin.sidebar') 
  <!-- #END# Left Sidebar -->
</section>
@yield('content') 

<script src="{{ URL::asset('public/assets/js/admin/validation.js') }}"></script>

<!-- Bootstrap Core Js --> 
<script src="{{ URL::asset('public/assets/plugins/bootstrap/js/bootstrap.js') }}"></script>  

<!-- Select Plugin Js --> 
<!--<script src="{{ URL::asset('public/assets/plugins/bootstrap-select/js/bootstrap-select.js') }}"></script> -->

<!-- Slimscroll Plugin Js --> 
<script src="{{ URL::asset('public/assets/plugins/jquery-slimscroll/jquery.slimscroll.js') }}"></script> 

<!-- Waves Effect Plugin Js --> 
<script src="{{ URL::asset('public/assets/plugins/node-waves/waves.js') }}"></script> 

<script src="{{ URL::asset('public/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>



<!-- Jquery CountTo Plugin Js --> 
<!--<script src="{{ URL::asset('assets/plugins/jquery-countto/jquery.countTo.js') }}"></script>--> 

<!-- Morris Plugin Js --> 
<!--<script src="{{ URL::asset('assets/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/morrisjs/morris.js') }}"></script>--> 

<!-- ChartJs --> 
<!--<script src="{{ URL::asset('assets/plugins/chartjs/Chart.bundle.js') }}"></script>--> 

<!-- Flot Charts Plugin Js --> 
<!--<script src="{{ URL::asset('assets/plugins/flot-charts/jquery.flot.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/flot-charts/jquery.flot.resize.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/flot-charts/jquery.flot.pie.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/flot-charts/jquery.flot.categories.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/flot-charts/jquery.flot.time.js') }}"></script>--> 

<!-- Sparkline Chart Plugin Js --> 
<!--<script src="{{ URL::asset('assets/plugins/jquery-sparkline/jquery.sparkline.js') }}"></script>--> 

<!-- Custom Js --> 
<script src="{{ URL::asset('public/assets/js/admin/admin.js') }}"></script> 
<!--<script src="{{ URL::asset('assets/js/admin/pages/index.js') }}"></script>--> 

<!--<script src="{{ URL::asset('public/assets/js/admin/redactor.min.js') }}"></script>-->

<script src="{{ URL::asset('public/assets/plugins/ckeditor/ckeditor.js') }}"></script>

<script src="{{ URL::asset('public/assets/js/admin/pages/forms/editors.js') }}"></script>

<script src="{{ URL::asset('public/assets/plugins/light-gallery/js/lightgallery-all.js') }}"></script>


<script src="{{ URL::asset('public/assets/js/admin/pages/medias/image-gallery.js') }}"></script>

<!-- Demo Js --> 
<!--<script src="{{ URL::asset('assets/js/admin/demo.js') }}"></script>-->
</body>
</html>