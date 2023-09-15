@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">My Orders</a>
@endsection  
        <?php 
          $level=Request()->level;
        $parameters = Request::segment(2);
        $parameters_level = base64_decode($parameters);
        
        ?>
<section class="dashbord-section">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<div class="dashbordlinks">
					<h6 class="fs18 fw600 mb20">My Account <span id="account-btn">
            <i class="fa fa-navicon"></i></span></h6>    
	               <ul  id="mobile-show">
						@include('fronted.mod_account.dashboard-menu')
					</ul>    
				</div>    
			</div>  
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<div class="dashbordtxt">
					<h6 class="fs18 fw600 mb20">My Orders</h6> 
				   	<div class="db-2-main-com db-2-main-com-table">
						<div class="orderstabs">
							<ul class="nav nav-tabs" role="tablist">
							  <!--<li class="active">
								  <a href="#orders" role="tab" data-toggle="tab">
									  <icon class="fa fa-shopping-cart"></icon> My Orders
								  </a>
							  </li>-->
							  
							  
	                          <li class="<?php echo ($parameters_level==0)?'active':''; ?>"><a href="{{route('myorder',base64_encode(0))}}">
								  Upcoming Orders
								  </a>
							  </li>
							  <li class="<?php echo ($parameters_level==3)?'active':''; ?>">
								  <a href="{{route('myorder',base64_encode(3))}}" >
									  Completed Orders
								  </a>
							  </li>
							  <li class="<?php echo ($parameters_level==4)?'active':''; ?>">
								  <a href="{{route('myorder',base64_encode(4))}}" >
									  Cancelled Orders
								  </a>
							  </li>
							  <li class="<?php echo ($parameters_level==5)?'active':''; ?>">
								  <a href="{{route('myorder',base64_encode(5))}}" >
									  <i class="fa fa-remove"></i> Return/Refund
								  </a>
							  </li>
							</ul>
							<!-- Tab panes -->
						
							<div class="tab-content">
							  
                                    <div class="tab-pane fade active in" id="upcoming-orders">
                                    
                                   
                                    <div class="table ordertabl" id="results">
										<div class="theader">
											<div class="table_header" style="width:5%;">No.</div>
											<div class="table_header">Date</div>
											<div class="table_header">Sub Order Id</div>
										    <div class="table_header dashboradtitle">
												
												@if($parameters_level==4)
												Cancel Date
												@elseif($parameters_level==5)
												Return / Refund Date
												@else 
												Delivery Date
												@endif
											</div>
											
											<div class="table_header">Total Amount</div>
                                            <div class="table_header" style="width:15%;">
											@if($parameters_level==5)
											Status
											@else
											Action
											@endif 
										
										</div>
                                            
											
											@if($parameters_level==4 || $parameters_level==5)
											<div class="table_header">&nbsp;</div>
											@endif
											<!--<div class="table_header">Transaction Id</div>-->
											<!--<div class="table_header">Transaction ID</div>-->
											@if($parameters_level==0)
        										<!--<div class="table_header text-center">Track order</div>
        										<div class="table_header">&nbsp;</div>-->
											@endif
											@if($parameters_level==3)
											<div class="table_header">
												<div class="sortingfilter" style="display:inline-block;">
													<select class="input-sm filterOrder" >
                    <option value="{{base64_encode(0)}}" <?php  echo ($level=='')?"selected":""?>>All Orders</option>
                    <option value="{{base64_encode(1)}}" <?php  echo ($level==base64_encode(1))?"selected":""?>>Last 15 Orders</option>
                    <option value="{{base64_encode(2)}}" <?php  echo ($level==base64_encode(2))?"selected":""?>>Last 1 Months</option>
                    <option value="{{base64_encode(3)}}" <?php  echo ($level==base64_encode(3))?"selected":""?>>Last 6 Months</option>
                    <option value="{{base64_encode(4)}}" <?php  echo ($level==base64_encode(4))?"selected":""?>>Last Year</option>
													</select>
												</div>
											</div>
											@endif
										</div>
										 <?php $i=1;
										 $check_order_no='';
										 $total =0;
										
										 foreach($orders as $order){
                                    
											$docket_info= App\OrdersShipping::orderDocket($order->id) ; 
											
										?>
										<div class="table_row">
											<div class="table_small">
												<div class="table_cell">No.</div>
												<div class="table_cell">{{$i}}</div>
											  
											</div>
											<div class="table_small">
											  <div class="table_cell">Date</div>
											  <div class="table_cell">
                                            <?php 
                                            $old_date_timestamp = strtotime($order->order_details_date);
                                            echo date('d/m/Y', $old_date_timestamp); 
                                            ?>
											  
											  </div>
											</div>
											<div class="table_small">
											  <div class="table_cell">Sub Order Id</div>
											  <div class="table_cell"><a href="javascript:void(0)">{{$order->suborder_no}}</a></div>
											</div>
								            <div class="table_small">
											  <div class="table_cell">Sub Order Id</div>
											  <div class="table_cell"><a href="javascript:void(0)">
											  @if($parameters_level==3)
											  {{date('d/m/Y',strtotime(@$order->order_detail_delivered_date))}} 
											  @endif 

											  @if($parameters_level==4)
											  {{date('d/m/Y',strtotime(@$order->order_detail_cancel_date))}} 
											  @endif 

											  @if($parameters_level==5 && !empty($order->order_detail_return_date))
											
											  {{date('d/m/Y',strtotime(@$order->order_detail_return_date))}} 
											
											  @endif 
											  
											  

													<?php //echo "<strong>Time</strong>: ". @$order->delivery_time ?>
						   
						   <?php 
						 
						//    $va= explode("_",@$order->delivery_date); 
						   //echo "<br><strong>Day</strong> : ". @$va[0];
						//    echo "<br><strong>Date</strong>: ". @$va[1];
						   ?>      </a></div>
											</div>
										
                               
                                
											<!--<div class="table_small">-->
											<!--  <div class="table_cell">Wallet Amount</div>-->
											<!--  <div class="table_cell">-->
											<!--  <?php if($order->deduct_reward_points!=0){?>-->
											<!--  <?php if($check_order_no!=$order->order_no){?>-->
											<!--  <i class="fa fa-inr"></i> {{$order->details_wlt_amt}}-->
											<!--  <?php } } ?>-->
											<!--  </div>-->
											<!--</div>-->
											
											<div class="table_small">
											  <div class="table_cell">Amount</div>
											  <div class="table_cell">
											     
											      <i class="fa fa-rupee"></i>
                                                    <?php 
                                                    
                                                    $product_price=(intval(@$order->details_qty)*intval(@$order->details_price))+intval(@$order->details_shipping_charges)+intval(@$order->details_cod_charges)-intval(@$order->details_cpn_amt)-intval(@$order->details_wlt_amt)+intval(@$order->slot_price); 
                                                    
													/*
														echo $order->details_qty.'*'.$order->details_price.'+'.$order->details_price.''.$order->details_cod_charges.'-'.$order->details_cpn_amt.'-'.$order->details_wlt_amt.'+'.$order->slot_price;
                                                      */                  
                                                        $total+=$product_price;
                                                   ?>
											  
											 <!--{{$order->grand_total*$order->qty}}-->
											 {{$product_price}}  
											 @if($order->payment_mode==0)
                                                    (COD) @else
                                                    (Paid)@endif
                                                    </div>
											</div>
											
											<!--<div class="table_small">-->
											<!--  <div class="table_cell">Transaction Id</div>-->
											<!--  <div class="table_cell">{{$order->order_no}}</div>-->
											<!--</div>-->
											<?php $check_order_no=$order->order_no;?>
											 <?php if($parameters_level==0){ ?>
                                                        <div class="table_small">
                                                        <div class="table_cell text-center">Action</div>
                                                        <div class="table_cell">
															
															     <a href="{{ route('myorder-detail',[base64_encode($order->id),base64_encode($order->order_details_id)]) }}">
							      <button type="submit" class="btn btn-warning btn-sm"> View <!--<i class="fa fa-eye" title="View Order"></i>--></button>  </a>
							      
															<a href="{{ route('track_order',[base64_encode($order->id),base64_encode($order->order_details_id)]) }}">
                                                    <button type="submit" class="btn btn-danger btn-sm"> Track<!--<i class="fa fa-truck" title="Track Order"></i>--></button>  </a>
															
                                                            @if($docket_info)
                                                             <a href="{{ $docket_info->logistic_link}}" target="_blank">
																{{ $docket_info->courier_name}}
															<br>{{ $docket_info->logistic_link}}
															<br>{{ $docket_info->docket_no}}
															<br>{{ $docket_info->remarks}}
														</a>
                                                            @endif
                                                            
                                                         <?php if($parameters_level==0 || $parameters_level==3 || $parameters_level==5 || $parameters_level==6){ ?>
                                                      
											      <?php } else{?>
											  
											       <a href="{{ route('cancel-myorder-detail',base64_encode($order->master_id)) }}">
											      <button type="submit" class="btn btn-deafult btn-black btn-sm"> Cancel<!--<i class="fa fa-times" title="Cancel Order"></i>--></button> </a>
											      <?php }?>
											      
											     <?php if($parameters_level==5){ ?>
													
										

                                                      
                                                  <?php }?>
											     
												 
											      @if($order->isInvoiceGenerate>0)
											      <a title="Invoice Download" href="{{ route('invoice-download',base64_encode($order->id)) }} ">
											      <button type="submit" class="btn btn-deafult btn-black btn-sm"> Download<!--<i class="fa fa-download"></i>--></button> </a>
											      
											      @endif   
                                                           
														</div>
                                                        </div>
											      <?php } else{?>
											      <div class="table_small">
                                                        <div class="table_cell text-center">Action</div>
                                                        <div class="table_cell">
															
						     
								  @if($parameters_level==5)
								 <br>
								 <?php 
                             
							 $isReplaced=DB::table('replace_order_details')
							 ->where('sub_order_id',$order->order_details_id)
							  ->where('product_id',$order->product_id)
							  ->first();?>
							  @if($isReplaced) 		
											<!--if refunded -->
										   @if($isReplaced->replceOrder_sts==0)
											   <!--if check return type -->
											   @if($isReplaced->return_type==0)
											   @else
											   @endif
											   
											   <!--check order staus-->
												
											   @switch($isReplaced->order_status)
													   @case(0)
														   <br>
														   Waiting for pickup
													   @break
													   
													   @case(1)
														<br>
															Waiting for package recieve 
													   @break
													   
													   @case(2)
														<br>
														   @if($isReplaced->return_type==0)
															   Wating For Confirm Refund 
														   @else
															   Waiting For Confirm Replaced
														   @endif
													   @break
											   @endswitch 
											   <!--check order staus-->
											   
											   <!--if check return type -->
										   @else
											<br>
												   @if($isReplaced->return_type==0)
												   <span>Refunded</span>
												   @else
												   Replaced
												   @endif
										   @endif
										<!--if refunded -->
									   
									   
								   <!--if return -->
							  @endif
							   <!--return action tab-->
					 


								  @else 
								  <a href="{{ route('myorder-detail',[base64_encode($order->id),base64_encode($order->order_details_id)]) }}">
							      <button type="submit" class="btn btn-warning btn-sm"> View<!--<i class="fa fa-eye" title="View Order"></i>--></button>  </a>
							      
										
								  @endif 


											         
                                                           
														</div>
                                                        </div>
											      <?php }?>
											      
							       
											      
	                            </div>
									  <?php $i++;}?>
									  
								  </div>
								  {{$orders->links()}}
                                  
                                    </div>
							
							</div>
						</div>
						
						
					</div>

				</div>    
				<!--
			<div class="no-order text-center">
			<h6 class="fs20 fw600 mb20">Sadly, you haven't placed any orders till now.</h6>    
			<img src="images/no-order.png" alt="">  
			<button type="submit" class="norderbtn" value="submit">Continue Shopping</button>    
			</div>   -->  
			</div>     
		</div>    
	</div>    
    
</section>  
@endsection

            

    
