@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
@include('fronted.includes.breadcrum')
@endsection    
<section class="dashbord-section">
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
<div class="dashbordlinks">
<h6 class="fs18 fw600 mb20">My Account</h6>    
<ul>
<li><a href="my-dashboard.html"><i class="fa fa-user"></i> My Account</a> </li> 
<li><a href="orders.html"><i class="fa fa-first-order"></i> My Order</a> </li>    
<li><a href="wishlist.html"><i class="fa fa-heart"></i> My Wishlist</a> </li>  
<li><a href="reviews.html"><i class="fa fa-star"></i>  My Reviews</a> </li>  
<li><a href="returns-and-refund.html"><i class="fa fa-refresh"></i> Returns & Refunds</a> </li>  
<li class="active"><a href="order-cancellation.html"><i class="fa fa-sign-out"></i> Order Cancellation</a> </li> 
<li><a href="account-info.html"><i class="fa fa-info-circle"></i> Account information</a></li>
<li><a href="change-password.html"><i class="fa fa-lock"></i> Change password</a></li>
<li><a href="my-querys.html"><i class="fa fa-question-circle"></i> My queries</a></li>
<li><a href="#"><i class="fa fa-lock"></i> Logout</a></li>
</ul>    
</div>    
</div>
    
<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">  
<div class="dashbordtxt">
<h6 class="fs18 fw600 mb20">Order Cancellations</h6> 
    
    <h6 class="mt40 mb5 fs16 fw600">Returns</h6>
 <p> <b>Definition:</b> 'Return' is defined as the action of giving back the item purchased by the Buyer to the Seller 
on the 18UP website. Following situations may arise: </p>
    <ul> 
  <li>   Item was defective</li> 
   <li>   Item was damaged during the Shipping</li> 
   <li>   Products was / were missing</li> 
   <li>    Wrong item was sent.</li> 

        </ul>
    <p>Return could also result in refund of money in most of the cases.</p>
    <h6 class="mt30 mb5 fs16 fw600">Points to be noted:</h6>
    <p>Customers need to raise the refund request within 7 days from the date of order. Once Buyer has raised a return request through customer account panel. If the product being returned is not in sellable condition, customer shall not be entitled to any refund of money.
Shipping cost for returning the product shall be borne and incurred by Customer. </p>
    <h6 class="mt30 mb5 fs16 fw600">Replacement</h6>
    <p> <b>Definition:</b> Replacement is the action or process of replacing something in place of another. A customer can request for replacement whenever he is not happy with the item, reason being</p>
    
    <ul> 
    <li>Damaged in shipping </li> 
    <li>Defective item, Item(s) missing</li>
    <li>The wrong item shipped.</li>
    <li>Mismatch in size.</li>
    </ul>
<p>
Customer needs to raise the replacement request within 7 days from the date of delivery of products from account panel. Once the replacement request has been raised and verified customer we shall initiate the reverse pickup for the product to us and only after return of the product, we shall be obliged to processes the replacement.
In case we don't have the product at all, we can provide a refund to the customer and customer shall be obligated to accept the refund in lieu of replacement. All the product parameters shall be required to be complied with in cases of replacement.</p>
    
    
    
    <h6 class="mt30 mb5 fs16 fw600">Refund</h6>
    <p> Once the product is received and accepted, a refund will be initiated and the customer shall receive the refund amount within 5 - 7 working days.</p>
    
    <h6 class="mt30 mb5 fs18 fw600">Refund Process</h6>
    <p><b>    Prepaid orders:</b> We will take maximum 5-7 working days to refund the whole amount you paid. The amount can be transferred to the same account from which the purchase was made.<br>
    <b>COD orders:</b> If you had paid cash on the time of delivery,
         </p>
    <h6 class="mt30 mb5 fs16 fw600">Please send us following details on</h6>
    <ul>
    <li>care@18up.in, </li>
    <li>call +91-80-61914618 </li>
    <li>Name of Account Owner</li>    
    <li>Bank Account details</li>
    <li>IFSC Code</li>
        <li>Branch name</li>  
    </ul>
    
    <h6 class="mt30 mb5 fs18 fw600">Exchange</h6>
    <p> If you want to exchange your materials, just inform us for the pick-up. Our
        <b> email: care@18up.in, call: +91-80-61-91-46-18 </b>  Place a new order and we will ship it the same day. Or you can wait for the first material to arrive at us, get inspected for no damage and pay for the difference amount (or pay you the difference amount). Please keep the returning materials in the same condition. They need to be in the sale able condition.
    Free shipping for return and exchange only for Bangalore. </p>
</div>    
</div>    
</div>
</div>     
</section>    
@endsection