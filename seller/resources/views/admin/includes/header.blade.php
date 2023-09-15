<header class="main-header">
	<div class="inside-header">
		<!-- Logo -->
            @if (Auth::guard('vendor')->check())
                <a href="{{ route('v_home') }}" class="logo">
            @else
              
            @endif
		  <!-- mini logo for sidebar mini 50x50 pixels -->
		  <b class="logo-mini">
			  <span class="light-logo"><img src=" {{ asset('public/images/logo.png') }}" alt="logo"></span>
			  <!--<span class="dark-logo"><img src="{{ asset('public/images/logo.png') }}" alt="logo"></span>-->
		  </b>
		  <!-- logo for regular state and mobile devices -->
		  <span class="logo-lg">
			   <!--<img src="{{ asset('public/images/logo.png') }}" alt="logo" class="light-logo">
			 <img src="{{ asset('public/images/logo.png') }}" alt="logo" class="dark-logo">-->
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
{{-- <strong style="margin-right:10px;">Seller Care : +91 1111111111</strong> --}}
		  <div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
			    
			     
					@if (Auth::guard('vendor')->check())
                       
				     @else
				     @if(auth()->user()->user_role==0)
			     <li class="dropdown notifications-menu">
					<?php 
						if(Auth::guard('vendor')->check()){
							$vendor_id=auth()->guard('vendor')->user()->id;
							$admin_id='';
						}else{
							$admin_id='99999999999';
							$vendor_id='';
						}
						
						$msg_count= App\Messages::adminNotifyMessages($admin_id,$vendor_id);
					?>
				<a href="{{ URL::to('/admin/chat/') }}" class="dropdown-toggle" data-toggle="dropdown">
				  <i class="mdi mdi-bell"></i>
				</a>
				<ul class="dropdown-menu scale-up">
				  <li class="header">
					  <a href="{{ URL::to('/admin/chat/') }}">
					  You have <?php echo ($msg_count!='')?$msg_count:'No';?> notifications
					  </a>
				  </li>
				 
				</ul>
			  </li>
			     @else
			      <?php 
			  $records=DB::table('permissions')->select('module_id')->where('user_role_id',auth()->user()->user_role)->get();
			  $permitted=array();
			  foreach($records as $record){
			      array_push($permitted,$record->module_id);
			  }
			 if(sizeof($permitted)>0){
			  ?>
                <?php
                if (in_array(28, $permitted)){
                ?>	
                <li class="dropdown notifications-menu">
					<?php 
						if(Auth::guard('vendor')->check()){
							$vendor_id=auth()->guard('vendor')->user()->id;
							$admin_id='';
						}else{
							$admin_id='99999999999';
							$vendor_id='';
						}
						
						$msg_count= App\Messages::adminNotifyMessages($admin_id,$vendor_id);
					?>
				<a href="{{ URL::to('/admin/chat/') }}" class="dropdown-toggle" data-toggle="dropdown">
				  <i class="mdi mdi-bell"></i>
				</a>
				<ul class="dropdown-menu scale-up">
				  <li class="header">
					  <a href="{{ URL::to('/admin/chat/') }}">
					  You have <?php echo ($msg_count!='')?$msg_count:'No';?> notifications
					  </a>
				  </li>
				 
				</ul>
			  </li>
                <?php }?>
			  <?php }?>
			     @endif
				    
				     @endif
			
			  <!-- User Account -->
			  <li class="dropdown user user-menu">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				     @if (Auth::guard('vendor')->check())
				     @if(auth()->guard('vendor')->user()->profile_pic)
				      <img src="<?php echo Config::get('constants.Url.public_url'); ?>uploads/vendor/profile_pic/{{auth()->guard('vendor')->user()->profile_pic}}" class="user-image rounded-circle" alt="User Image">
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
                        <img src="<?php echo Config::get('constants.Url.public_url'); ?>uploads/vendor/profile_pic/{{auth()->guard('vendor')->user()->profile_pic}}" class="user-image rounded-circle" alt="User Image">
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
					
					  <div class="col-12 text-left">
					      
					      @if (Auth::guard('vendor')->check())
					      
							Hello ,' {{auth()->guard('vendor')->user()->username}}
							<a href="{{route('update_vdr_profile', [base64_encode(0),base64_encode(auth()->guard('vendor')->user()->id)])}}"><i class="fa fa-user-circle mr-15"></i>Profile
							@endif
							
							  @if (Auth::guard('vendor')->check())
							  <a href="{{ route('vendor_logout') }}"><i class="fa fa-power-off"></i> Logout</a>
							  @else
							  <a href="{{ route('admin_logout') }}"><i class="fa fa-power-off"></i> Logout</a>
							  @endif
							
						
                        <a href="{{ route('changePassword') }}" ><i class="fa fa-power-off"></i> Change Password</a>
						
						
							<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
											@csrf
							</form>
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
