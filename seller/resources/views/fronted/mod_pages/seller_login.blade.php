@extends('fronted.layouts.vendor_layout')
@section('content')
    

<section class="seller-banner">
    <img src="{{ asset('public/fronted/images/become-an-seller-banne.jpg') }}"/>
    <div class="seller-slide-overlay">
<div class="container">

    <div class="seller-slider-title">
        
		<div class="row">
<div class="col-sm-4 col-md-4 col-lg-3">
<h3 class="text-center">Sell with us</h3>
     
    
<div class="seller-register">
<div class="register-heading"><h6 class="fw600 fs16 text-blue">Log in </h6>  </div>  
                @if($errors->any())
                <h4>{{$errors->first()}}</h4>
                @endif
				
                     
        
   <form class="pd20" action="{{route('sellerLogin')}}" method="post">
	@if(session()->has('message'))
	<h4 class="disAppear-text" id="disAppear">{{ session()->get('message') }}</h4>
	@endif
 
	{{-- 
	<h4 class="disAppear-text" id="disAppear" style="display: block;">Seller registration successful.  Your account will be activated shortly.</h4>
	--}}


        @csrf
    <div class="form-group">
    <input type="email" class="form-control" id="email" placeholder="Emai Id" name="email" value="{{old('email')}}">
    </div>
        
    <div class="form-group">
    <input type="password" class="form-control" id="password" placeholder="Password" name="password">
    </div>   
    <button type="submit" class="seller-registerbtn"><i class="fa fa-shopping-cart"></i> Login</button>
      
    <a class="mt10" href="{{route('vendor_register', base64_encode(0))}}" target="_blank"><i class="fa fa-lock"></i> Create your Seller Account</a>  
     <a  class="mt10" href="{{route('vendor_forgot_password')}}">Forgot password</a>
    </form>    
</div>    
</div>    
</div>
    
    </div>
</div>
    </div>
</section>
    
    
<section class="get-started-section">
<div class="container">
<div class="title text-center">
<h3>Benefits to sell with us</h3>    
</div> 
<div class="row">
<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
<div class="get-startbox">
<i class="fa fa-line-chart"></i>  
<h6 class="fs16 fw600">Growth</h6>  
    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
</div>    
</div>
    
<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
<div class="get-startbox">
<i class="fa fa-inr"></i>
<h6 class="fs16 fw600">Lowest cost of doing business</h6>    
<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
</div>    
</div> 
<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
<div class="get-startbox">
<i class="fa fa-search"></i>
<h6 class="fs16 fw600">Transparency</h6>    
<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
</div>    
</div>   
</div>    
</div>    
</section>  
    
<section class="helpfaq-section bg-gray">
<div class="container">
<div class="title text-center">
						<h3>Frequently Asked Questions</h3>
					</div>
                    
			<div class="row">				
				<div class="col-md-12">
					<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
						<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="headingOne">
								<h4 class="panel-title">
									<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
										Where's my Order? 
									</a>
								</h4>
							</div>
							<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
								<div class="panel-body">
									<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
								</div>
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="headingTwo">
								<h4 class="panel-title">
									<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
										How do I return/exchange my order?
									</a>
								</h4>
							</div>
							<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
								<div class="panel-body">
									<p>   Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. </p>
								</div>
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="headingThree">
								<h4 class="panel-title">
									<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
										Can I cancel an order after shipping?
									</a>
								</h4>
							</div>
							<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
								<div class="panel-body">
									<p> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
								</div>
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="headingFour">
								<h4 class="panel-title">
									<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
										My payment failed while making a purchase. What should I do?
									</a>
								</h4>
							</div>
							<div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
								<div class="panel-body">
									<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.  </p>
                                    

                            <ul>
                           <li>  It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</li>
                              <li> It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</li>
                              <li> It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</li>         
                            </ul>

                           
								</div>
							</div>
						</div>
                        
						<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="headingFive">
								<h4 class="panel-title">
									<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
										Why can't I apply some coupons on my bag?
									</a>
								</h4>
							</div>
							<div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
								<div class="panel-body">
									<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like). </p>
								</div>
							</div>
						</div>
                        
                        <div class="panel panel-default">
							<div class="panel-heading" role="tab" id="headingSix">
								<h4 class="panel-title">
									<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
									How do I check the refunds status for my return?
									</a>
								</h4>
							</div>
							<div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
								<div class="panel-body">
									<p>   It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
								</div>
							</div>
						</div>
                        
                           <div class="panel panel-default">
							<div class="panel-heading" role="tab" id="headingSeven">
								<h4 class="panel-title">
									<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
									Forgot your password?
									</a>
								</h4>
							</div>
							<div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">
								<div class="panel-body">
                                    <ul>
                                        <li> Lorem Ipsum is simply dummy text of the printing and typesetting industry. </li>
                                <li> Lorem Ipsum is simply dummy text of the printing and typesetting industry.</li>
                               <li> Lorem Ipsum is simply dummy text of the printing and typesetting industry.</li>
                                <li> Lorem Ipsum is simply dummy text of the printing and typesetting industry.</li>
                                    </ul>
								</div>
							</div>
						</div>
                        
                           <div class="panel panel-default">
							<div class="panel-heading" role="tab" id="headingEight">
								<h4 class="panel-title">
									<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
									Can I order a product that is 'Out of Stock' or 'Temporarily Unavailable'?
									</a>
								</h4>
							</div>
							<div id="collapseEight" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEight">
								<div class="panel-body">
									<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).  </p>
								</div>
							</div>
						</div>
                        
                        
					</div>
				</div><!--- END COL -->		
			</div><!--- END ROW -->			
		</div>     
</section>     


<section class="get-started-section">
<div class="container">
<div class="title text-center">
<h3>How we work</h3>    
</div> 
<div class="row">
<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
<div class="get-startbox">
<i class="fa fa-cloud-upload"></i>  
<h6 class="fs16 fw600">Upload Products</h6>  
    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
</div>    
</div>
    
<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
<div class="get-startbox">
<i class="fa fa-bullhorn"></i>
<h6 class="fs16 fw600">Get Orders</h6>    
<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
</div>    
</div> 
<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
<div class="get-startbox">
<i class="fa fa-truck"></i>
<h6 class="fs16 fw600">Fullfill Orders</h6>    
<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
</div>    
</div> 
<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
<div class="get-startbox">
<i class="fa fa-database"></i>
<h6 class="fs16 fw600">Receive Payments</h6>    
<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
</div>    
</div>   
</div>    
</div>    
</section>  
<section class="wrap wrap-bdrtp">
<div class="container">
<div class="row">
<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
<div class="count-item">
                            <h6>How peoples are earning with</h6>
                            <h3>0</h3>
                        </div>    
</div>
    
<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
<div class="count-item">
                            <h6>How businesses are growing with us</h6>
                            <h3>0</h3>
                        </div>   
</div> 
<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
<div class="count-item">
                            <h6>Total sellers with us</h6>
                            <h3>{!! App\Helpers\CommonHelper::validVendorCount(); !!}</h3>
                        </div>    
</div> 
<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
<div class="count-item">
                            <h6>Total buyers with us</h6>
                            <h3>{!! App\Helpers\CommonHelper::validCustomerCount(); !!}</h3>
                        </div>   
</div>   
</div>    
</div>    
</section> 
    
  
       

   
@endsection
