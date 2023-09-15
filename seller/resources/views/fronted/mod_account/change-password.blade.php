@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Change Password</a>
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
   
	
	
	 @if ($errors->any())
     @foreach ($errors->all() as $error)
         <span class="help-block">
			<p style="color:red">{{$error}}</p>
		</span>
     @endforeach
 @endif
    <form role="form" class="form-element" action="{{  route('changepass')}}" method="post" enctype="multipart/form-data">
			   @csrf
<h6 class="fs18 fw600 mb20">Change Password </h6> 
    
    <div class="row">
    
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        
    <div class="form-group">
    <input type="password" class="form-control" id="password" name="current_password" placeholder="Current Password">
    </div>   
    
    </div>   

    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
    <div class="form-group">
    <input type="password" class="form-control" id="password" name="new_password"  placeholder="New Password">
    </div>    
    </div> 
        
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
    <div class="form-group">
    <input type="password" class="form-control" id="password" name="confirm_password" placeholder="Confirm Password">
    </div> 
    </div>
        
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <button type="submit" class="saveaddress" value="submit">Update</button>    
    </div>    
    </div>
    </form>
</div>    
</div>     
</div>    
</div>        
</section>  
@endsection
            

    
