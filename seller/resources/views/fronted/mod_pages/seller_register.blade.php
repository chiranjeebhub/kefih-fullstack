@extends('fronted.layouts.vendor_layout')
@section('content')
<section class="register-section" style="background-image: url(images/inner-section-bg.png); background-position: center;
background-size: cover; background-repeat: no-repeat;">
<div class="container-fluid">
<div class="row">    
<div class="col-md-12">
<div class="sellerform">
<h6 class="mb40">Create your <span>Seller Account</span></h6>
    
    <form action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data" id="myform">
           @csrf
           	@if($errors->any())
						<h4>{{$errors->first()}}</h4>
			@endif
    <div class="form-group">
    <input type="email" class="form-control"  name="email" id="email" placeholder="Enter Your Email Address" value="{{old('email')}}">
    </div>
        
    <div class="form-group">
    <input type="text" class="form-control" id="mobile" name="phone" placeholder="Enter Your Mobile Number" value="{{old('phone')}}">
    </div>  
    
     <div class="form-group">
    <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="">
    <span class="eyIcon" >
       <i class="fa fa-eye" id="eye" ></i>
    </span> 
</div>

     <div class="form-group">
        <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Confirm Password" value="">
    <span class="eyIcon" >
       <i class="fa fa-eye" id="confEye" ></i>
    </span> 
    </div>   
     
    <div class="form-group privacy_policy">
       <p>
           <input type="checkbox"  name="privacy_policy" id="privacy_policy">  I have read the <a href="{{url('page/vendor-terms-and-conditions')}}" target="_blank" style="color: blue!important;">Terms of Sell</a> & <a href="{{url('page/vendor-privacy-policy')}}" target="_blank" style="color: blue!important;"> privacy policy </a>  and accept them.
       </p>
       <!-- <label for="privacy_policy"></label> -->
    </div>

    <button type="submit" class="registrbtn"><i class="fa fa-user"></i> Sign Up</button>
    <p>Already a Seller? <a href="{{ route('vendor_login') }}">Login Here</a> </p>    
    </form>  
    
</div>    
</div>
    
</div>    
</div>    
    
</section> 

   <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- <script src="{{ asset('public/js/validateform.js') }}"></script>  -->
<style>
    .eyIcon {
    float: right;
    margin-right: 13px;
    margin-top: -32px;
}
.error{
    float:left;
    color:red;
} 

.privacy_policy {
    clear: right;
    float: left;
}
label#privacy_policy-error {
    position: absolute;
    margin-top: 21px;
}

.privacy_policy p{
font-size:12px;
}

</style>
<script>
    $(document).ready(function () {
    $("#eye").click(function () {
        if ($("#password").attr("type") === "password") {
            $("#password").attr("type", "text");
            $(this).toggleClass("fa-eye fa-eye-slash");
        } else {
            $("#password").attr("type", "password");
            $(this).toggleClass("fa-eye fa-eye-slash");
        }
    });
    $("#confEye").click(function () {
        if ($("#cpassword").attr("type") === "password") {
            $("#cpassword").attr("type", "text");
            $(this).toggleClass("fa-eye fa-eye-slash");
        } else {
            $("#cpassword").attr("type", "password");
            $(this).toggleClass("fa-eye fa-eye-slash");
        }
    });
});


$('#myform').validate({ // initialize the plugin
    rules: {
        email: {
            required: true,
            email: true
        },
        phone: {
            required: true,
            digits: true,
            maxlength: 10,
            minlength: 10,
            
           
        },
        password: {
            required: true,
           
        },
        cpassword: {
            required: true,
            equalTo : "#password"
           
        },
        privacy_policy:{
            required: true,
        }
    }, 
     messages: { 
            name: "Email is required",
            phone: { 
                required: "Phone number is required", 
              
            },
            password: { 
                required: "Password is required", 
            },
            cpassword: { 
                required: "Confirm password is required", 
                equalTo: "Enter the same password as above" 
            }, 
            email: { 
                required: "Please enter a valid email address", 
                email: "Please enter a valid email address", 
            },   
            privacy_policy:{
                required: "Please accept our terms and conditions", 
        }
            
        },
});


</script>
@endsection
