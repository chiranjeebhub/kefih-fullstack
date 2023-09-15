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
<section class="main_section pt-4 pb-4">
	<div class="container">
        <div class="row">
            <div class="col-lg-6 col-sm-6 col-md-6 col-12">
                <h2 class="heading">My Orders</h2>
                <!--<div class="search_box">
                    <a href="#"><i class="fa fa-search"></i> </a>
                    <input type="text" class="form-control" placeholder="Search a product name or ID">
                </div>-->
            </div>  
        </div>
		<div class="row">
            <!--<div class="col-lg-3 col-sm-4 col-md-4 col-12">
                <div class="order_status orderTabs">
                     <ul  class="nav nav-tabs" id="nav-tab" role="tablist">
                      <li class="<?php //echo ($parameters_level==0)?'active':''; ?>"><a href="{{route('myorder',base64_encode(0))}}">
                          <i class="fa fa-circle-o"></i> Upcoming Orders
                          </a>
                      </li>
                      <li class="<?php //echo ($parameters_level==3)?'active':''; ?>">
                          <a href="{{route('myorder',base64_encode(3))}}" >
                              <i class="fa fa-circle-o"></i> Completed Orders
                          </a>
                      </li>
                      <li class="<?php //echo ($parameters_level==4)?'active':''; ?>">
                          <a href="{{route('myorder',base64_encode(4))}}" >
                              <i class="fa fa-circle-o"></i> Cancelled Orders
                          </a>
                      </li>
                      <li class="<?php //echo ($parameters_level==5)?'active':''; ?>">
                          <a href="{{route('myorder',base64_encode(5))}}" >
                              <i class="fa fa-circle-o"></i> Return/Refund
                          </a>
                      </li>
                    </ul>
                </div>
            </div>-->
			<div class="col-lg-12 col-sm-12 col-md-12 col-12">
                <div class="orderstabs">
                     <ul  class="nav nav-tabs" id="nav-tab" role="tablist">
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
                              Return/Refund
                          </a>
                      </li>
                    </ul>
                </div>
				<div class="order_list mt-4">
				    <div class="tab-content" id="nav-tabContent">
							  
                                    <div class="tab-pane fade active show" id="upcoming-orders" role="tabpanel">
                                   
                                    <div class="table ordertabl">
										<div class="theader">
											<div class="table_header" style="width:5%;">No.</div>
											<div class="table_header">Order Date</div>
											<div class="table_header">Master Order Id</div>
											<div class="table_header">Sub Order Id</div>
										    <div class="table_header dashboradtitle">
												@if($parameters_level==0)
												Expected Delivery Date
												@elseif($parameters_level==4)
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
											<div class="table_header orderdayfilter">
												<div class="sortingfilter" style="display:inline-block;">
                                                    <div class="popup_info profile_form">
								
														
														<form method="GET" action="{{route('myorder',base64_encode(3))}}">
															<div class="editionaldropdn">
																<select class="form-control filterOrder" name="filter_by" onchange="filterMyOrder();">
																	<option value="{{base64_encode(0)}}" <?php  echo ($level=='')?"selected":""?>>All Orders</option>
																	<option value="{{base64_encode(1)}}" <?php  echo ($level==base64_encode(1))?"selected":""?>>Last 15 Orders</option>
																	<option value="{{base64_encode(2)}}" <?php  echo ($level==base64_encode(2))?"selected":""?>>Last 1 Months</option>
																	<option value="{{base64_encode(3)}}" <?php  echo ($level==base64_encode(3))?"selected":""?>>Last 6 Months</option>
																	<option value="{{base64_encode(4)}}" <?php  echo ($level==base64_encode(4))?"selected":""?>>Last Year</option>
																</select>
															</div>
														</form>

                                                    </div>													
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
											  <div class="table_cell">Order Date</div>
											  <div class="table_cell">
                                            <?php 
                                            $old_date_timestamp = strtotime($order->order_details_date);
                                            echo date('d/m/Y', $old_date_timestamp); 
                                            ?>
											  
											  </div>
											</div>
											<div class="table_small">
											  <div class="table_cell">Master Order Id</div>
											  <div class="table_cell"><a href="javascript:void(0)">{{$order->order_no}}</a></div>
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
											  @if($parameters_level==0)
												@php 
														$prd_detail=App\Products::select('products.vendor_id','products.delivery_days')
													->where('products.id','=',$order->product_id)
													->first();

														$expected_date = '';
														if($prd_detail->delivery_days!=0)
														{
															$t=strtotime("$order->order_date +$prd_detail->delivery_days days");
															$expected_date=date('Y-m-d',$t);
														}
														
												@endphp 

											   	@if(!empty($expected_date))
													{{date('d/m/Y',strtotime($expected_date))}}
												@endif  


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
                                                    
                                                    $product_price=(intval(@$order->details_qty)*intval(@$order->details_price))+intval(@$order->details_shipping_charges)+intval(@$order->details_cod_charges)-intval(@$order->details_cpn_amt)+intval(@$order->slot_price); 
                                                    
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
							      
															<!--<a href="{{ route('track_order',[base64_encode($order->id),base64_encode($order->order_details_id)]) }}">
                                                    <button type="submit" class="btn btn-danger btn-sm"> Track</button>  </a>-->
															
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
											      <a title="Invoice Download" href="{{ route('invoice-download',base64_encode($order->order_details_id)) }} ">
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
							      @if($order->isInvoiceGenerate>0)
									<a title="Invoice Download" href="{{ route('invoice-download',base64_encode($order->order_details_id)) }} ">
									<button type="submit" class="btn btn-deafult btn-black btn-sm"> Download<!--<i class="fa fa-download"></i>--></button> </a>
									
									@endif   
									
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

            

    
