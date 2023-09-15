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
  
    <title>@yield('pageTitle')</title> 
		<link rel="icon" href="{{ asset('public/images/logo.png') }}">
	<link rel="stylesheet" href="{{ asset('public/assets/vendor_components/bootstrap/dist/css/bootstrap.css') }}">
	
	<!-- Bootstrap extend-->
	<link rel="stylesheet" href="{{ asset('public/css/bootstrap-extend.css') }}">
	
	<!-- theme style -->
	<link rel="stylesheet" href="{{ asset('public/css/master_style.css') }}">
	
	<!-- horizontal menu style -->
	<link rel="stylesheet" href="{{ asset('public/css/horizontal_menu_style.css') }}">
	
	<!-- Fab Admin skins
	<link rel="stylesheet" href=" {{ asset('public/css/skins/_all-skins.css') }}"> -->
	
	<!-- Morris charts -->
	<link rel="stylesheet" href="{{ asset('public/assets/vendor_components/morris.js/morris.css') }}">
	<link rel="stylesheet" href="{{ asset('public/assets/vendor_components/nestable/nestable.css') }}">
	<link rel="stylesheet" href="{{ asset('public/css/vrp.css') }}">
		<style>
		@import url({{ asset('public/assets/vendor_components/font-awesome/css/font-awesome.css') }});
		@import url({{ asset('public/assets/vendor_components/Ionicons/css/ionicons.css') }});
		@import url({{ asset('public/assets/vendor_components/themify-icons/themify-icons.css') }});
		@import url({{ asset('public/assets/vendor_components/linea-icons/linea.css') }});
		@import url({{ asset('public/assets/vendor_components/glyphicons/glyphicon.css') }});
		@import url({{ asset('public/assets/vendor_components/flag-icon/css/flag-icon.css') }});
		@import url({{ asset('public/assets/vendor_components/material-design-iconic-font/css/materialdesignicons.css') }});
		@import url({{ asset('public/assets/vendor_components/simple-line-icons-master/css/simple-line-icons.css') }});
		</style>
</head>
<body class="hold-transition skin-purple-light layout-top-nav">
<div class="wrapper">
	
	@include('admin.includes.script') 		
	<div class="">
	
	    <!-- Main content -->
      <section class="content">
     
       <div class="row">
        <!-- left column -->
        <div class="col-12">
          <!-- general form elements -->
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title">@yield('boxTitle')</h4>
            </div>
			  @include('admin.includes.session_message') 
					
            <!-- /.box-header -->
         
            <!-- .box-body -->
              <div class="box-body">
			  @include('admin.includes.form_error') 
					<!-- form start -->
					@yield('content')
					<!-- /form  end -->
              </div>
			  
              <!-- /.box-body -->
					</div>
          <!-- /.box -->


        </div>
        <!--/.col (left) -->
        <!-- right column -->
       
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
      
    </section>
    <!-- /.content -->
	
	</div>
	@include('admin.includes.footer') 
	
	</div>

</body>
</html>
