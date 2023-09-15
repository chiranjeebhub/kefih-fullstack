@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
@include('fronted.includes.breadcrum')
@endsection  
<section class="mywishlist-section">
<div class="container">
<div class="row">
    <div class="col-md-12">
        <div class="item-ordered">
            <div class="db-2-main-com db-2-main-com-table packagetbl">
							
							<h6 class="fs20 fw600 ftu mb20">Not Yet Shipped</h6>
							<div class="table" id="results">
							  <div class="theader">
								
								<div class="table_header">item Ordered</div>
								<div class="table_header">Price</div>
								<div class="table_header">Cancel Item</div>
							  </div>
								
							  <div class="table_row">
								<div class="table_small">
								  <div class="table_cell">item Ordered</div>
								  <div class="table_cell">
                                      <p>1 of the waste, prufrock and other poems ( dover thrift ) [ paperback ]</p>
                                    <span>By : T.S. Eliot</span>
                                      <p>Conditon: New</p>
                                      <p>1 Item (s) Gift options: None</p>
                                    </div>  
								</div>
                              
                                  
								<div class="table_small">
								  <div class="table_cell">Price</div>
								  <div class="table_cell"><i class="fa fa-inr"></i> 850.00 </div>
								</div>
                                  
                                   <div class="table_small">
								  <div class="table_cell">Cancel Item</div>
								  <div class="table_cell"> <div class="form-group">    
                                    <form role="form">
                                            <div class="checkbox checkbox-circle">
                                                <input id="checkbox7" type="checkbox">
                                                    <label for="checkbox7"></label>
                                            </div>
                                    </form>
                                </div>
                                </div>
                                </div>

                                </div>

                                </div>

                                </div>  
           
           <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
            <label>Reason For Cancellation </label>
           <div class="form-group">
    <select name="country" class="form-control custom-select" id="country">
    <option value="" selected="selected">--- Select Reason ---</option>
    <option value="+1">Shipping Address Undeliverable</option>
    <option value="+1">Customer Exchange</option>
    <option value="+1">Buyer Cancelled</option>
    </select>
    </div>   
            </div>
              
          </div> 
               <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
               <div class="text-right">
              <button class="cancelbtn" type="submit" value="submit">Cancel Checked Item</button>     
              </div>
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
  
    

  
  

    
