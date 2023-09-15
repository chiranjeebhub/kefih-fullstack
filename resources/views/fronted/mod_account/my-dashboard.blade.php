@extends('fronted.layouts.app_new')
@section('content') 

@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">My Dashboard</a>
@endsection   
 
<section class="dashbord-section">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<div class="dashbordlinks">
					<h6 class="fs18 fw600 mb20">My Account</h6>    
					<ul>
						@include('fronted.mod_account.dashboard-menu')
					</ul>    
				</div>    
			</div>  
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<div class="dashbordtxt">
					<h6 class="fs18 fw600 mb20">My Dashboard</h6> 
					<h2><i class="fa fa-user"></i> Welcome 	
					@auth('customer') {{ auth()->guard('customer')->user()->name }} @endauth					</h2>    
				</div>    
			</div>     
		</div>    
	</div>    
    
</section>  

@endsection
            

    
