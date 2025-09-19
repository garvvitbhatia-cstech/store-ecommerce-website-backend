<!DOCTYPE html>
<html>
<head> 
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Sign In</title>
    <!-- Favicon-->
    <link rel="icon" href="{{ URL::asset('public/favicon.ico') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{{ URL::asset('public/assets/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{{ URL::asset('public/assets/plugins/node-waves/waves.css') }}" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="{{ URL::asset('public/assets/css/admin/style.css') }}" rel="stylesheet">
</head>

<body class="login-page">
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);">Admin Panel</a>
            <small>Admin Panel</small>
        </div>
        <div class="card">
            <div class="body">
                <form action="{{url('admins/postLogin')}}" id="pageForm" method="post">
    				{!! csrf_field() !!}
                    <div class="">
                     @if ($alert = Session::get('success'))   
                        <p class="text-center alert alert-success alert-block" style="padding: 15px; margin-bottom: 20px;">{{ $alert }}</p>
                    @endif
                    @if ($alert = Session::get('error'))   
                        <p class="text-center alert alert-danger alert-block" style="padding: 15px; margin-bottom: 20px;">{{ $alert }}</p>
                    @endif
                    </div>
                    
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="email" class="form-control" autocomplete="off" name="email" placeholder="Email" required autofocus>
                        </div>
                      	@if($errors->has('email'))
                            <span class="invalid-feedback">
                                <label class="error">{{ $errors->first('email') }}</label>
                            </span>
                        @endif
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" autocomplete="off" name="password" placeholder="Password" required>
                        </div>
                        @if ($errors->has('password'))
                        <span class="help-block font-red-mint">
                            <label class="error">{{ $errors->first('password') }}</label>
                        </span>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-xs-8 p-t-5">
                            <input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-pink">
                            <label for="rememberme">Remember Me</label>
                        </div>
                        <div class="col-xs-4">
                            <button id="submitBtn" class="btn btn-block bg-pink waves-effect" type="button">SIGN IN</button>
                        </div>
                    </div>
                    <div class="row m-t-15 m-b--20">
                        <div class="col-xs-6">
                        
                        </div>
                        <div class="col-xs-6 align-right">
                            <a href="#">Forgot Password?</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Jquery Core Js -->
    <script src="{{ URL::asset('public/assets/plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap Core Js -->
    <script src="{{ URL::asset('public/assets/plugins/bootstrap/js/bootstrap.js') }}"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{{ URL::asset('public/assets/plugins/node-waves/waves.js') }}"></script>

    <!-- Custom Js -->
    <script src="{{ URL::asset('public/assets/js/admin/admin.js') }}"></script>
    
    <script>
    	$(document).on('click','#submitBtn',function(){
			$('#submitBtn').html('Processing');
			$('#pageForm').submit();
		});
    </script>
</body>
</html>