@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
@include('fronted.includes.breadcrum')
@endsection    
<section class="mywishlist-section">
<div class="container">
<div class="row">
                <div class="col-md-12">
            <div class="my-wishlist-page">			
            <div class="row">                   
            <div class="col-md-12">
            <h6 class="fs20 fw700 mb20">My Wishlist</h6>
            </div>


            <div class="col-md-2"><img src="{{ asset('public/fronted/images/p1.jpg') }}" alt="imga"></div>
            <div class="col-md-7">
            <div class="product-name"><a href="#">Floral Print Buttoned</a></div>
            <div class="rating rateit">
            <i class="fa fa-star rate"></i>
            <i class="fa fa-star rate"></i>
            <i class="fa fa-star rate"></i>
            <i class="fa fa-star rate"></i>
            <i class="fa fa-star non-rate"></i>
            <span class="review">( 06 Reviews )</span>
            </div>

            <div class="price">
            Rs.400.00 <span>Rs.900.00</span>
            </div>

            </div>	
            <div class="col-md-2">
            <a href="#" class="addcart"><i class="fa fa-shopping-cart"></i> Add to cart</a>
            </div>
            <div class="col-md-1 close-btn">
            <a href="#" class=""><span><i class="fa fa-trash-o"></i></span></a>
            </div>

            </div> 
            </div>

                    
       <!--
<div class="no-order text-center">
<h6 class="fs20 fw600 mb20">Your Wishlist is Empty !</h6>    
<img src="images/no-wishlist.png" alt="">  
<button type="submit" class="norderbtn" value="submit">Continue Shopping</button>    
</div>               
         -->           
                    
</div>

</div>        
</div>
</section>        
 @endsection 
    

  
  

    
