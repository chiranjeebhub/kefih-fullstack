@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
   
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Account information</a>
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
    
    <div class="dashbordtxt dashbord-account-info acc-info">
        <form role="form" class="form-element" action="{{  route('accountinfo')}}" method="post" enctype="multipart/form-data">
			   @csrf
    @if ($errors->any())
     @foreach ($errors->all() as $error)
         <span class="help-block">
			<p style="color:red">{{$error}}</p>
		</span>
     @endforeach
 @endif
<h6 class="fs18 fw600 mb20">Account information</h6> 
 
	<div class="row">
		<div class="col-xs-9 col-sm-7">
			<div class="form-group">
				<input type="hidden" id="original1" class="form-control" id="name" placeholder="Name" name="name" value="{{ auth()->guard('customer')->user()->name }}">
				<input type="text" id="fake1" class="form-control" id="name" placeholder="Name" disabled="" value="{{ auth()->guard('customer')->user()->name }}">
			</div> 
		</div>
		<div class="col-xs-3 col-sm-5 pding-lft0">
			<div class="edit-delet-btn">
				<a href="javascript:void(0)" class="showButton" row="1" id="show1" prt_type="text">Edit</a>
				<a href="javascript:void(0)" class="hideButton" row="1" id="hide1" prt_type="hidden">Close</a>
			</div>
		</div> 
	</div>
  
        <div class="row">
    <div class="col-xs-9 col-sm-7">
    <div class="form-group">
    <input type="email" class="form-control" id="email" placeholder="Email Address" disabled="" value="{{ auth()->guard('customer')->user()->email }}">
      <input type="hidden" class="form-control" id="email" placeholder="Email Address" disabled="" name="email" value="{{ auth()->guard('customer')->user()->email }}">
    </div> 
    </div> 
        
        </div>
            <div class="row">
    <div class="col-xs-9 col-sm-7">
    <div class="form-group">
    <input type="text" class="form-control" id="mobile" placeholder="Mobile No." disabled="" name="phone" value="{{ auth()->guard('customer')->user()->phone }}" onkeypress="javascript:return onlyPincodeDigit(event,this.value,9)">
     <input type="hidden" class="form-control" id="mobile" placeholder="Mobile No." disabled="" value="{{ auth()->guard('customer')->user()->phone }}" onkeypress="javascript:return onlyPincodeDigit(event,this.value,9)">
    </div> 
    </div>
        
        </div> 
        
        <div class="row">
    <div class="col-xs-9 col-sm-7">
    <div class="form-group">
    <input type="date" class="form-control"   id="fake2" value="{{ auth()->guard('customer')->user()->dob }}" disabled="">
     <input type="hidden" class="form-control"  id="original2" name="dob" value="{{ auth()->guard('customer')->user()->dob }}" >
    </div> 
    </div>
        <div class="col-xs-3 col-sm-5 pding-lft0">
      <div class="edit-delet-btn">
                <a href="javascript:void(0)" class="showButton" row="2" id="show2" prt_type="date">Edit</a>
                <a href="javascript:void(0)" class="hideButton" row="2" id="hide2" prt_type="hidden">Close</a>
      </div>
        </div> 
        </div>
        
        
        <div class="row">
    <div class="col-xs-9 col-sm-7">
    <div class="form-group">
     <input type="text"  class="form-control"  id="fake3" placeholder="Address"  value="{{ auth()->guard('customer')->user()->address }}" disabled="">
    <input type="hidden"  class="form-control"  id="original3" placeholder="Address" name="address" value="{{ auth()->guard('customer')->user()->address }}">
   
    </div> 
    </div>
        <div class="col-xs-3 col-sm-5 pding-lft0">
      <div class="edit-delet-btn">
                <a href="javascript:void(0)" class="showButton" row="3" id="show3" prt_type="text">Edit</a>
                <a href="javascript:void(0)" class="hideButton" row="3" id="hide3" prt_type="hidden">Close</a>
      </div>
        </div> 
        </div>
        
        
        <div class="row">
    <div class="col-xs-9 col-sm-7">
    <div class="form-group">
      <input type="text"  class="form-control" id="fake4" placeholder="State"  value="{{ auth()->guard('customer')->user()->state }}" disabled="">
     <input type="hidden"  class="form-control" id="original4" placeholder="State" name="state" value="{{ auth()->guard('customer')->user()->state }}">
    </div> 
    </div>
        <div class="col-xs-3 col-sm-5 pding-lft0">
      <div class="edit-delet-btn">
                <a href="javascript:void(0)" class="showButton" row="4" id="show4" prt_type="text">Edit</a>
                <a href="javascript:void(0)" class="hideButton" row="4" id="hide4" prt_type="hidden">Close</a>
      </div>
        </div> 
        </div>
        
        <div class="row">
    <div class="col-xs-9 col-sm-7">
    <div class="form-group">
      <input type="text"  class="form-control" id="fake5" placeholder="City"  value="{{ auth()->guard('customer')->user()->city }}" disabled="">
     <input type="hidden"  class="form-control" id="original5" placeholder="City" name="city" value="{{ auth()->guard('customer')->user()->city }}">
    </div> 
    </div>
        <div class="col-xs-3 col-sm-5 pding-lft0">
      <div class="edit-delet-btn">
                <a href="javascript:void(0)" class="showButton" row="5" id="show5" prt_type="text">Edit</a>
                <a href="javascript:void(0)" class="hideButton" row="5" id="hide5" prt_type="hidden">Close</a>
      </div>
        </div> 
        </div>
        
        
        <div class="row">
    <div class="col-xs-9 col-sm-7">
    <div class="form-group">
       <input type="text"  class="form-control" id="fake6" placeholder="Pincode"  value="{{ auth()->guard('customer')->user()->pincode }}" onkeypress="javascript:return onlyPincodeDigit(event,this.value,5)" disabled="">
      <input type="hidden"  class="form-control" id="original6" placeholder="Pincode" name="pincode" value="{{ auth()->guard('customer')->user()->pincode }}" onkeypress="javascript:return onlyPincodeDigit(event,this.value,5)">
    </div> 
    </div>
        <div class="col-xs-3 col-sm-5 pding-lft0">
      <div class="edit-delet-btn">
                <a href="javascript:void(0)" class="showButton" row="6" id="show6" prt_type="text">Edit</a>
                <a href="javascript:void(0)" class="hideButton" row="6" id="hide6" prt_type="hidden">Close</a>
      </div>
        </div> 
        </div>
        <div class="row">
			<div class="col-md-4 col-sm-5 col-xs-12">
     			<h4 class="mb10">Gander</h4>
                <div class="form-group radio-tab">
					<label class="radio-inline genderChanged <?php echo (auth()->guard('customer')->user()->gender==1)?"active":"";?>" row="1">
					<input type="radio" name="gender" value="1" id="gender1" class="radio-menu "  <?php echo (auth()->guard('customer')->user()->gender==1)?"checked":"";?> >Male
					</label>
					<label class="radio-inline  genderChanged <?php echo (auth()->guard('customer')->user()->gender==2)?"active":"";?>" row="2">
					<input type="radio" name="gender" value="2" id="gender2" class="radio-menu " <?php echo (auth()->guard('customer')->user()->gender==2)?"checked":"";?> >Female
					</label>
                </div>  
			</div>
            <div class="col-md-8 col-sm-7 col-xs-12">
                   <label>Profile Image</label>
					<div class="form-group"> 
					<div class="custom-file">

					<div class="box">
						<input type="file" name="profile_pic" id="file-2" class="inputfile inputfile-2" data-multiple-caption="{count} files selected">
						<label for="file-2"><i class="fa fa-file"></i> <span>Choose a file…</span></label>
						@if(auth()->guard('customer')->user()->profile_pic!='')
					 {!! App\Helpers\CustomFormHelper::support_image('uploads/customers/profile_pic',auth()->guard('customer')->user()->profile_pic); !!}
					@endif
					</div>

					</div>
					</div>
            
                
            	</div>
		</div>
        <div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mt10">
				<button type="submit" class="saveaddress" value="submit">Update profile</button>    
			</div>    
		</div>
   </form>
</div>
<!--<div class="dashbordtxt">-->
<!--<h6 class="fs18 fw600 mb20">Account information</h6> -->
    
<!--    <div class="row">-->
<!--        <form role="form" class="form-element" action="{{  route('accountinfo')}}" method="post" enctype="multipart/form-data">-->
<!--			   @csrf-->
<!--    @if ($errors->any())-->
<!--     @foreach ($errors->all() as $error)-->
<!--         <span class="help-block">-->
<!--			<p style="color:red">{{$error}}</p>-->
<!--		</span>-->
<!--     @endforeach-->
<!-- @endif-->
<!--    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">-->
         
<!--    <div class="form-group">-->
<!--    <input type="text" class="form-control" id="name" placeholder="Name" name="name" value="{{ auth()->guard('customer')->user()->name }}">-->
<!--    </div>   -->
     
<!--    </div>   -->
  
<!--    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">-->
<!--    <div class="form-group">-->
<!--    <input type="email"  disabled class="form-control" id="email" placeholder="Email" name="email" value="{{ auth()->guard('customer')->user()->email }}">-->
<!--    </div> -->
<!--    </div> -->
        
<!--    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">-->
<!--    <div class="form-group">-->
<!--    <input type="text" disabled class="form-control" id="mobile" placeholder="Phone" name="phone" value="{{ auth()->guard('customer')->user()->phone }}" onkeypress="javascript:return onlyPincodeDigit(event,this.value,9)">-->
<!--    </div> -->
<!--    </div>-->
    
<!--    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">-->
<!--    <div class="form-group">-->
<!--    <input type="text"  class="form-control" id="address" placeholder="Address" name="address" value="{{ auth()->guard('customer')->user()->address }}">-->
<!--    </div> -->
<!--    </div>-->
    
<!--     <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">-->
<!--    <div class="form-group">-->
<!--    <input type="text"  class="form-control" id="city" placeholder="City" name="city" value="{{ auth()->guard('customer')->user()->city }}">-->
<!--    </div> -->
<!--    </div>-->
    
    
<!--     <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">-->
<!--    <div class="form-group">-->
<!--    <input type="text"  class="form-control" id="state" placeholder="State" name="state" value="{{ auth()->guard('customer')->user()->state }}">-->
<!--    </div> -->
<!--    </div>-->
    
    
<!--     <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">-->
<!--    <div class="form-group">-->
<!--    <input type="text"  class="form-control" id="pincode" placeholder="Pincode" name="pincode" value="{{ auth()->guard('customer')->user()->pincode }}" onkeypress="javascript:return onlyPincodeDigit(event,this.value,5)">-->
<!--    </div> -->
<!--    </div>-->
    
<!--     <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">-->
<!--         <label>Gender</label>-->
<!--    <div class="form-group">-->
<!--	<div class="row checkSc">-->
<!--		<div class="col-md-6 col-xs-6">-->
<!--			<label><input type="radio" name="gender" value="1" <?php echo (auth()->guard('customer')->user()->gender==1)?"checked":"";?>>Male</label>-->
<!--		</div>-->
<!--		<div class="col-md-6 col-xs-6">-->
<!--			<label><input type="radio" name="gender" value="2" <?php echo (auth()->guard('customer')->user()->gender==2)?"checked":""; ?>>Female</label>-->
<!--		</div>-->
<!--	</div>-->
     
   
<!--    </div> -->
<!--    </div>-->
    
<!--     <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">-->
<!--         <label>DOB</label>-->
<!--    <div class="form-group">-->
<!--    <input type="date" class="form-control" name="dob" value="{{ auth()->guard('customer')->user()->dob }}" >-->
<!--    </div> -->
<!--    </div>-->
    
<!--            <div class="col-md-12 col-xs-12 col-sm-12">-->
<!--                   <label>Profile Image</label>-->
<!--            <div class="form-group"> -->
<!--            <div class="custom-file">-->
            
<!--            <div class="box">-->
<!--            	<input type="file" name="profile_pic" id="file-2" class="inputfile inputfile-2" data-multiple-caption="{count} files selected">-->
<!--            	<label for="file-2"><i class="fa fa-file"></i> <span>Choose a file…</span></label>-->
<!--				@if(auth()->guard('customer')->user()->profile_pic!='')-->
<!--             {!! App\Helpers\CustomFormHelper::support_image('uploads/customers/profile_pic',auth()->guard('customer')->user()->profile_pic); !!}-->
<!--            @endif-->
<!--            </div>-->
            
<!--            </div>-->
<!--            </div>-->
            
           
<!--            </div>-->
    
    
    
    
    

<!--    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">-->
        
<!--        <input type="submit" value="Update" class="saveaddress">-->
<!--    </div>-->
<!--     </form>-->
<!--    </div>-->
<!--</div>    -->
</div>     
</div>    
</div>        
</section>  
@endsection
            

    
