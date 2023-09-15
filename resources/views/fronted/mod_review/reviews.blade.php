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
<li><a href="orders.html"><i class="fa fa-first-order"></i>  My Order</a> </li>    
<li><a href="wishlist.html"><i class="fa fa-heart"></i> My Wishlist</a> </li>  
<li class="active"><a href="reviews.html"><i class="fa fa-star"></i>  My Reviews</a> </li>  
<li><a href="returns-and-refund.html"><i class="fa fa-refresh"></i> Returns & Refunds</a> </li>  
<li><a href="order-cancellation.html"><i class="fa fa-sign-out"></i> Order Cancellation</a> </li> 
<li><a href="account-info.html"><i class="fa fa-info-circle"></i> Account information</a></li>
<li><a href="change-password.html"><i class="fa fa-lock"></i> Change password</a></li>
<li><a href="my-querys.html"><i class="fa fa-question-circle"></i> My queries</a></li>
<li><a href="login.html"><i class="fa fa-lock"></i> Logout</a></li>
</ul>    
</div>    
</div>  
<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
<div class="dashbordtxt">
<h6 class="fs18 fw600 mb20"> My Reviews </h6>
                    <div class="db-2-main-com db-2-main-com-table packagetbl">


                    <div class="table" id="results">
                    <div class="theader">

                    <div class="table_header">No.</div>
                    <div class="table_header">Product</div>
                    <div class="table_header">Rating</div>
                    <div class="table_header">Comment</div>
                    <div class="table_header">Date</div>
                    </div>

                    <div class="table_row">
                    <div class="table_small">
                    <div class="table_cell">No</div>
                    <div class="table_cell"># </div>
                    </div>
                        
                    <div class="table_small">
                    <div class="table_cell">Product</div>
                    <div class="table_cell"> T-Shirts</div>
                    </div>
                        
                    <div class="table_small">
                    <div class="table_cell">Rating</div>
                    <div class="table_cell"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>  </div>
                    </div>
                        
                    <div class="table_small">
                    <div class="table_cell">Comment</div>
                    <div class="table_cell"> No comment </div>
                    </div>
                        
                    <div class="table_small">
                    <div class="table_cell">Date</div>
                    <div class="table_cell">29-7-2019</div>
                    </div>
                        
                    </div>

                    </div>

                    </div>      
    <!-- <h6 class="fs18 fw600 text-center">No Reviews Found.....</h6> -->
</div>    
</div>     
</div>    
</div>       
</section>  
@endsection
