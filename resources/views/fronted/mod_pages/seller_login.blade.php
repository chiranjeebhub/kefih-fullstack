@extends('fronted.layouts.vendor_layout')
@section('content')

<section class="seller-section" style="background: url({{ asset('public/fronted/images/become-an-seller-banne.jpg') }}); background-repeat: no-repeat;">
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="seller-register">
<div class="register-heading"><h6 class="fw600 fs20 text-blue">Log in</h6>  </div>  
                    @if($errors->any())
                    <h4>{{$errors->first()}}</h4>
                    
                    @endif
   <form class="pd30" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
        @csrf
    <div class="form-group">
    <input type="email" class="form-control" id="email" placeholder="Emai Id" name="email" value="{{old('email')}}">
    </div>
        
    <div class="form-group">
    <input type="password" class="form-control" id="password" placeholder="Password" name="password">
    </div>   
    <button type="submit" class="seller-registerbtn"><i class="fa fa-shopping-cart"></i> Start Selling</button>
      
    <a class="text-white mt10" href="{{route('vendor_register', base64_encode(0))}}" target="_blank"><i class="fa fa-lock"></i> Create your Seller Account</a>  
     <a  class="text-white mt10" href="{{route('vendor_forgot_password')}}">Forgot password</a>
    </form>    
</div>    
</div>    
</div>    
</div>    
    
</section>
    
   
    

    <section class="seller-section1" style="background: url({{ asset('public/fronted/images/seller-background.jpg') }});background-repeat: no-repeat;
background-size: cover; background-attachment: fixed;">
    <div class="container">
    <div class="row">
    <div class="col-md-6"></div>    
    <div class="col-md-6">
   <div class="why18up">
     <h6 class="fs40 fw700 mb20 text-white">Why phaukat</h6> 
    <h4 class="fs20 fs600 mb20 text-white">Take Your Business Nationwide</h4>
    <p class="fs16 fw300 text-white">Sell your products to over 10,500 pin codes all over India.</p>       
    </div> 
    </div>    
    </div>  
    </div>

    </section>
    
    
<section class="get-started-section">
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
<h6 class="fs25 fw700 mb40">What do you need to get started?</h6>    
</div> 
    
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
<div class="get-startbox">
<i class="fa fa-credit-card"></i>  
<h6 class="fs25 fw600">PAN</h6>    
<p>PAN number is mandatory for tax purposes and should be in the name of the business. In case of sole proprietorship, the PAN card of the seller needs to be submitted.</p>    
</div>    
</div>
    
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
<div class="get-startbox">
<i class="fa fa-file"></i>
<h6 class="fs25 fw600">GSTIN</h6>    
<p>GSTIN number is mandatory for tax purposes and should be in the name of the business. In case of sole proprietorship, the GSTIN number of the seller needs to be submitted.</p>    
</div>    
</div>    
</div>    
</div>    
</section>    

    
<section class="sell-section">
<div class="container-fluid">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 pdnone">
<div class="sellbox">
<h6 class="fs25 fw600">How We Work</h6>   
<p> Why is this important? Because clients want to know the businesses they depend on for advice, are well managed in their own right PAN number is mandatory for tax purposes and should be in the name of the business. In case of sole proprietorship, the PAN card of the seller needs to be submitted.</p>    
</div>
</div> 
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 pd30">
    <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="sell-icon">
    <i><img src="{{ asset('public/fronted/images/upload-product-icon.png') }}" alt=""> </i>
    <h6 class="fs18 mt20 mb20">Upload Products</h6>    
    </div>    
    </div> 
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="sell-icon">
    <i><img src="{{ asset('public/fronted/images/get-order-icon.png') }}" alt=""> </i>
    <h6 class="fs18 mt20 mb20">Get Orders</h6>    
    </div>    
    </div> 
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="sell-icon">
    <i><img src="{{ asset('public/fronted/images/fullfill-order-icon.png') }}" alt=""> </i>
    <h6 class="fs18 mt20 mb20">Fullfill Orders</h6>    
    </div>    
    </div> 
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="sell-icon">
    <i><img src="{{ asset('public/fronted/images/receive-payments-icon.png') }}" alt=""> </i>
    <h6 class="fs18 mt20 mb20">Receive Payments</h6>    
    </div>    
    </div>      
    </div>
    </div>    
</div>    
</div>    
</section>    
     

  
    
   
<section class="testimonial-section">
		<div class="large-container">
			<div class="sec-title">
				<h6 class="title fs25 fw600 ">Doing business on phaukat is really easy</h6>
				<h2>What Our million client say ?</h2>
			</div>

            <div class="testimonial-carousel owl-carousel owl-theme">
                
            <div class="testimonial-block">
            <div class="inner-box">
            <div class="text">
                <h2 class="fw700 fs25 mb10">Title Name</h2>
            <p>Why is this important? Because clients want to know the businesses they depend on for advice, are well managed in their own right Why is this important? Because clients want to know the businesses they depend on for advice, are well managed in their own right</p></div>
            <div class="info-box">
            <div class="thumb"><img src="http://t.commonsupport.com/adro/images/resource/thumb-1.jpg" alt=""></div>
            <h4 class="name">Mahfuz Riad</h4>
            <span class="designation">Ui Designer & CEO</span>
            </div>
            </div>
            </div>

            <div class="testimonial-block">
            <div class="inner-box">
            <div class="text">
            <h2 class="fw700 fs25 mb10">Title Name</h2>
            <p>Why is this important? Because clients want to know the businesses they depend on for advice, are well managed in their own right Why is this important? Because clients want to know the businesses they depend on for advice, are well managed in their own right.</p></div>
            <div class="info-box">
            <div class="thumb"><img src="http://t.commonsupport.com/adro/images/resource/thumb-1.jpg" alt=""></div>
            <h4 class="name">Mahfuz Riad</h4>
            <span class="designation">Ui Designer & CEO</span>
            </div>
            </div>
            </div>

            <div class="testimonial-block">
            <div class="inner-box">
            <div class="text">
           <h2 class="fw700 fs25 mb10">Title Name</h2>
            <p>Why is this important? Because clients want to know the businesses they depend on for advice, are well managed in their own right Why is this important? Because clients want to know the businesses they depend on for advice, are well managed in their own right</p>
            </div>

            <div class="info-box">
            <div class="thumb"><img src="http://t.commonsupport.com/adro/images/resource/thumb-1.jpg" alt=""></div>
            <h4 class="name">Mahfuz Riad</h4>
            <span class="designation">Ui Designer & CEO</span>
            </div>

            </div>
            </div>
                
               <div class="testimonial-block">
            <div class="inner-box">
            <div class="text">
            <h2 class="fw700 fs25 mb10">Title Name</h2>
            <p>Why is this important? Because clients want to know the businesses they depend on for advice, are well managed in their own right Why is this important? Because clients want to know the businesses they depend on for advice, are well managed in their own right</p>
            </div>
                
            	<div class="info-box">
							<div class="thumb"><img src="http://t.commonsupport.com/adro/images/resource/thumb-1.jpg" alt=""></div>
							<h4 class="name">Mahfuz Riad</h4>
							<span class="designation">Ui Designer & CEO</span>
						</div>
                                
            </div>
            </div>  
                

            
			</div>
		</div>
	</section> 
    
    
  
       

   
@endsection
