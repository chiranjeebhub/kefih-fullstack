@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
@include('fronted.includes.breadcrum')
@endsection    
<section class="dashbord-section">
<div class="container">
<div class="row"> 
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="dashbordtxt">
<h6 class="fs18 fw600 mb20">My Order</h6> 
<div class="db-2-main-com db-2-main-com-table packagetbl">
						
							<div class="table" id="results">
							  <div class="theader">
								
								<div class="table_header">No.</div>
								<div class="table_header">Order Id</div>
								<div class="table_header">Order Date</div>
								<div class="table_header">Total Amount</div>
								<div class="table_header">Status</div>
								<div class="table_header">Transaction Id</div>
								<div class="table_header">Review</div>
							  </div>
								
							  <div class="table_row">
								<div class="table_small">
								  <div class="table_cell">No.</div>
								  <div class="table_cell">1</div>
								</div>
								<div class="table_small">
								  <div class="table_cell">Order Id</div>
								  <div class="table_cell"> 200</div>
								</div>
								<div class="table_small">
								  <div class="table_cell">Order Date</div>
								  <div class="table_cell">07/17/2019</div>
								</div>
								<div class="table_small">
								  <div class="table_cell">Total Amount</div>
								  <div class="table_cell"><i class="fa fa-inr"></i> 1200.00</div>
								</div>
								<div class="table_small">
								  <div class="table_cell">Status</div>
								  <div class="table_cell">Pending</div>
								</div>
								<div class="table_small">
								  <div class="table_cell">Transaction Id</div>
								  <div class="table_cell">&nbsp;</div>
								</div>
								<div class="table_small">
								  <div class="table_cell">Review</div>
								  <div class="table_cell"> <button type="submit" class="orderbtn">Order Detail</button> <button type="submit" class="orderbtn orderbtn-bgblack">Cancel Order</button></div>
								</div>
							  </div>
                                <div class="table_row">
                                <div class="table_small">
                                <div class="table_cell">No.</div>
                                <div class="table_cell">1</div>
                                </div>
                                    
                                <div class="table_small">
                                <div class="table_cell">Order Id</div>
                                <div class="table_cell"> 200</div>
                                </div>
                                    
                                <div class="table_small">
                                <div class="table_cell">Order Date</div>
                                <div class="table_cell">07/17/2019</div>
                                </div>
                                    
                                <div class="table_small">
                                <div class="table_cell">Total Amount</div>
                                <div class="table_cell"><i class="fa fa-inr"></i> 1200.00</div>
                                </div>
                                    
                                <div class="table_small">
                                <div class="table_cell">Status</div>
                                <div class="table_cell">Pending</div>
                                </div>
                                    
                                <div class="table_small">
                                <div class="table_cell">Transaction Id</div>
                                <div class="table_cell">&nbsp;</div>
                                </div>
                                    
                                <div class="table_small">
                                <div class="table_cell">Review</div>
                                <div class="table_cell"> <button type="submit" class="orderbtn">Order Detail</button> <button type="submit" class="orderbtn orderbtn-bgblack">Cancel Order</button></div>
                                </div>
                                </div>

                                </div>

                                </div>
    <!-- <h6 class="fs18 fw600 text-center">No Orders Found.....</h6> -->
    
 
</div>    
</div>     
</div>    
</div>    
    
</section>  
    
<section class="track-order-section">
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="track-orderbox">
<h6 class="fs18 fw600 mb20">Track Order</h6>     
    <ol class="progtrckr" data-progtrckr-steps="5">
    <li class="progtrckr-done"> Approved</li>    
    <li class="progtrckr-done"> Packed</li>
    <li class="progtrckr-done"> Shipped</li>
    <li class="progtrckr-todo">In Transit</li>
    <li class="progtrckr-todo">Out for deliver</li>
  <li class="progtrckr-todo"> Delivered</li>
    </ol>     
</div>   
</div>    
</div>    
</div>        
</section>    
@endsection