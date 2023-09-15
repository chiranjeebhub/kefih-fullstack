<header class="main-header">
	<div class="inside-header">
		<!-- Logo -->
            @if (Auth::guard('vendor')->check())
                <a href="{{ route('v_home') }}" class="logo">
            @else
                <a href="{{ route('dashboard') }}" class="logo">
            @endif
		  <!-- mini logo for sidebar mini 50x50 pixels -->
		  <b class="logo-mini">
			  <span class="light-logo"><img src=" {{ asset('public/images/logo.jpg') }}" alt="logo"></span>
			  <span class="dark-logo"><img src="{{ asset('public/images/logo.jpg') }}" alt="logo"></span>
		  </b>
		  <!-- logo for regular state and mobile devices -->
		  <span class="logo-lg">
			  <img src="{{ asset('public/images/logo.jpg') }}" alt="logo" class="light-logo">
			  <img src="{{ asset('public/images/logo.jpg') }}" alt="logo" class="dark-logo">
		  </span>
		</a>
		<!-- Header Navbar -->
		<nav class="navbar navbar-static-top">
		  <!-- Sidebar toggle button-->
		  <a href="#" class="sidebar-toggle d-block d-lg-none" data-toggle="push-menu" role="button">
			<span class="sr-only">Toggle navigation</span>
		  </a>

		  <ul class="navbar-nav mr-auto mt-md-0">		
			<!-- .Megamenu -->
			
			<!-- /.Megamenu -->
		</ul>	

		  <div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
  
			
			  <!-- User Account -->
			  <li class="dropdown user user-menu">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				     @if (Auth::guard('vendor')->check())
				     @if(auth()->guard('vendor')->user()->profile_pic)
				      <img src=" {{URL::to('/uploads/vendor/profile_pic')}}/{{auth()->guard('vendor')->user()->profile_pic}}" class="user-image rounded-circle" alt="User Image">
				     @else
				      <img src=" {{ asset('public/images/user5-128x128.jpg') }}" class="user-image rounded-circle" alt="User Image">
				     @endif
				    
				     @else
				     <img src=" {{ asset('public/images/user5-128x128.jpg') }}" class="user-image rounded-circle" alt="User Image">
				     @endif
				  
				</a>
				<ul class="dropdown-menu scale-up">
				  <!-- User image -->
				  <li class="user-header">
					@if (Auth::guard('vendor')->check())
                        @if(auth()->guard('vendor')->user()->profile_pic)
                        <img src=" {{URL::to('/uploads/vendor/profile_pic')}}/{{auth()->guard('vendor')->user()->profile_pic}}" class="user-image rounded-circle" alt="User Image">
                        @else
                        <img src=" {{ asset('public/images/user5-128x128.jpg') }}" class="user-image rounded-circle" alt="User Image">
                        @endif
				     @else
				     <img src=" {{ asset('public/images/user5-128x128.jpg') }}" class="user-image rounded-circle" alt="User Image">
				     @endif

					<p>
					   
					  <small class="mb-5"></small>
					   <!-- <a href="#" class="btn btn-danger btn-sm btn-rounded">View Profile</a>-->
					</p>
				  </li>
				  <!-- Menu Body -->
				 
				  <li class="user-body">
				  
				
					
					  <div class="text-left">
					      
					      @if (Auth::guard('vendor')->check())
					      
					    Hello ,' {{auth()->guard('vendor')->user()->username}}
						 <a href="{{route('update_vdr_profile', [base64_encode(0),base64_encode(auth()->guard('vendor')->user()->id)])}}"><i class="fa fa-user-circle mr-15"></i>Profile
							@endif
					
					 @if (Auth::guard('vendor')->check())
							  <a href="{{ route('admin_logout') }}"><i class="fa fa-power-off"></i> Logout</a>
							  @else
							  <a href="{{ route('admin_logout') }}"><i class="fa fa-power-off"></i> Logout</a>
							  @endif
							  
					  </div>				
					</div>
					<!-- /.row -->
				  </li>
				</ul>
			  </li>
			  <!-- Control Sidebar Toggle
			  <li>
				<a href="#" data-toggle="control-sidebar"><i class="fa fa-cog fa-spin"></i></a>
			  </li> Button -->
			</ul>
		  </div>
		</nav>	
	</div>
  </header>
  <style>
  .loader{
	  display:none;
  }
  </style>
