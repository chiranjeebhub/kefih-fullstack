<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="description" content="">
    <meta name="author" content="">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
  
    <title>Update Password</title> 
	<link rel="icon" href="{{ asset('public/images/favicon.png') }}">
	<link rel="stylesheet" href="{{ asset('public/assets/vendor_components/bootstrap/dist/css/bootstrap.css') }}">
	
	<!-- Bootstrap extend-->
	<link rel="stylesheet" href="{{ asset('public/css/bootstrap-extend.css') }}">
	
	<!-- theme style -->
	<link rel="stylesheet" href="{{ asset('public/css/master_style.css') }}">
	
	<!-- horizontal menu style -->
	<link rel="stylesheet" href="{{ asset('public/css/horizontal_menu_style.css') }}">
	
	<!-- Fab Admin skins -->
	<link rel="stylesheet" href=" {{ asset('public/css/skins/_all-skins.css') }}">
	
	<!-- Morris charts -->
	<link rel="stylesheet" href="{{ asset('public/assets/vendor_components/morris.js/morris.css') }}">
  
</head>
<style>
.card {
   
    border: 2px solid black;
}
</style>
<body class="hold-transition skin-purple-light layout-top-nav">
<div class="wrapper">
	
	
	<div class="content-wrapper login-wrapper">
	<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-6 col-md-6 col-lg-6">
            <div class="card">
						@if($errors->any())
						<h4 class="alert alert-danger">{{$errors->first()}}</h4>

						@endif
                <div class="card-header">Update password</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('vendor_update_password') }}">
                        @csrf

                        <div class="form-group">
                                 <label for="email" class="col-md-4 col-form-label">Password</label>
                                <input id="email" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                             <div class="form-group">
                                  <label for="email" class="col-md-4 col-form-label">OTP</label>
                                <input id="otp" type="text" class="form-control @error('otp') is-invalid @enderror" name="otp" value="{{ old('otp') }}">
                <span class="vendor_resend_button"><i class="fa fa-exchange vendor_resend_button33" aria-hidden="true" ></i> Resend OTP</span>
                <span class="vendor_return_message"></span>
                                @error('otp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                       

                    

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary">
                                          Update
                                        </button>
                                        <br><br>
                                      
                                    </div>
                                    
                                </div>
                                
								 
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
	</div>
	<footer class="main-footer">
   
	  &copy; 2022 <a href="">Jaldi Kharido</a>. All Rights Reserved.
  </footer> 

  
<script type="text/javascript" src="http://aptechbangalore.com/test/public/fronted/js/jquery-1.12.0.min.js"></script>
	
	</div>
	@include('vendor.includes.script')
</body>
</html>
