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
  
    <title>Forgot Password</title> 
	<link rel="icon" href="{{ asset('images/favicon.png') }}">
	<link rel="stylesheet" href="{{ asset('assets/vendor_components/bootstrap/dist/css/bootstrap.css') }}">
	
	<!-- Bootstrap extend-->
	<link rel="stylesheet" href="{{ asset('css/bootstrap-extend.css') }}">
	
	<!-- theme style -->
	<link rel="stylesheet" href="{{ asset('css/master_style.css') }}">
	
	<!-- horizontal menu style -->
	<link rel="stylesheet" href="{{ asset('css/horizontal_menu_style.css') }}">
	
	<!-- Fab Admin skins -->
	<link rel="stylesheet" href=" {{ asset('css/skins/_all-skins.css') }}">
	
	<!-- Morris charts -->
	<link rel="stylesheet" href="{{ asset('assets/vendor_components/morris.js/morris.css') }}">
  
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
        <div class="col-md-8">
            <div class="card">
						@if($errors->any())
						<h4 class="alert alert-danger">{{$errors->first()}}</h4>

						@endif
                <div class="card-header">Forgot password</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('vendor_forgot_password') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
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
                                        <button type="submit" class="btn btn-danger">
                                          Proceed
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
   
	   &copy; 2022 <a href="">Jaldi Kharido </a>. All Rights Reserved.
  </footer> 

  

	
	</div>
	
</body>
</html>
