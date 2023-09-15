@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Wallet</a>
@endsection  

<section class="dashbord-section">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        
				<div class="dashbordlinks">
					<h6 class="fs18 fw600 mb20">My Account <span id="account-btn">
            <i class="fa fa-navicon"></i></span></h6>    
	               <ul  id="mobile-show"> @include('fronted.mod_account.dashboard-menu') </ul>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
      <div class="wallet-header">
              <div class="row text-center">
                  <div class="col-xs-12 col-sm-12">
                      <h4>Available amount</h4>
                     <p class="price-btn"><i class="fa fa-rupee"></i> <?php echo $cust_info->total_reward_points;?></p>
                     <?php 
											$earned=$spend=0;						
											foreach($current_wallet_history as $row){
												
												if($row->fld_reward_points!=0){
													$earned+=$row->fld_reward_points;
												}else if($row->fld_reward_deduct_points!=0){
													$spend+=$row->fld_reward_deduct_points;
												}
											}
										?>
                    </div>
                  </div>
                  </div>
			    <div class="dashbordtxt">
					<!--<h6 class="fs18 fw600 mb20">Coins <?php echo $cust_info->total_reward_points;?></h6> -->
					<?php 
											//$earned=$spend=0;						
											//foreach($current_wallet_history as $row){
												
											//	if($row->fld_reward_points!=0){
												//	$earned+=$row->fld_reward_points;
											//	}else if($row->fld_reward_deduct_points!=0){
												//	$spend+=$row->fld_reward_deduct_points;
											//	}
										//	}
										?>
										
										<span>Earned : <span><?php echo $earned;?></span></span>
										<span>Spend : <span><?php echo $spend;?></span></span>
				   	<div class="db-2-main-com db-2-main-com-table packagetbl mt10">
						<div class="orderstabs">
							<ul class="nav nav-tabs" role="tablist">
							  
							  <li class="active"><a href="#totalearning" role="tab" data-toggle="tab" aria-expanded="true">
								   earning
								  </a>
							  </li>
							  <li class="">
								  <a href="#earninghistory" role="tab" data-toggle="tab" aria-expanded="false">
									  Earning history
								  </a>
							  </li>
							  <li class="">
								  <a href="#creditshistory" role="tab" data-toggle="tab" aria-expanded="false">
									  Spends credits history
								  </a>
							  </li>
							</ul>
							<!-- Tab panes -->
							<div class="tab-content">
							  <div class="tab-pane fade active in" id="totalearning">
							      <?php 
																		
                                        $text='';
                                        $icon='';
                                        $point ='';
                                        foreach($earnings as $row){?>
																	
															
							      
								  <div class="row">
                                                    <div class="col-sm-4">
                                                    <div class="redlip-wallet">
                                                    <h4>Coins <span><em class="fa fa-rupee"></em> {{$row->fld_reward_points}}</span></h4>
                                                    </div>
                                                    </div>
                                            
                                            <div class="col-sm-4">
                                            <div class="redlip-wallet">
                                            <h4>FOR </h4>
                                            
                                             @switch($row->mode)
                                                    @case(0)
                                                    Placing order
                                                    @break
                                            
                                                    @case(1)
                                                    You have registered using referral code
                                                    @break
                                                
                                                    @case(2)
                                                     You have earned from referee orders
                                                    @break

                                                    @case(3)
                                                     You have earned from referee
                                                    @break
                                            
                                           @endswitch
                                            
                                            </span>
                                            </div>
                                            </div>
                                                    <div class="col-sm-4">
                                                    <div class="redlip-wallet">
                                                    <p class="date"> Date : <?php echo date('d M Y',strtotime($row->fld_wallet_date));?></p>
                                                    </div>
                                                    </div>
                                      
                                  </div>
                                  <?php }?>
                                  
                                </div>
							  <div class="tab-pane fade" id="earninghistory">
								  <?php 
																		
                                        $text='';
                                        $icon='';
                                        $point ='';
                                        foreach($earning_history as $row){?>
																	
															
							      
								  <div class="row">
								  <div class="col-sm-4">
								  <div class="redlip-wallet">
                                      <h4>Coins <span><em class="fa fa-rupee"></em> {{$row->fld_reward_points}}</span></h4>
                                      </div>
                                      </div>
                                      
                                       <div class="col-sm-4">
                                            <div class="redlip-wallet">
                                            <h4>FOR </h4>
                                            
                                            @switch($row->mode)
                                                    @case(0)
                                                    Placing order
                                                    @break
                                            
                                                    @case(1)
                                                    You have registered from referrals
                                                    @break
                                                
                                                    @case(2)
                                                     You have earned from referee orders
                                                    @break

                                                    @case(3)
                                                     You have earned from referee
                                                    @break
                                            
                                           @endswitch
                                            
                                            </span>
                                            </div>
                                            </div>
                                      <div class="col-sm-4">
                                         
                                          <div class="redlip-wallet">
                                         
                                              <p class="date"> Date : <?php echo date('d M Y',strtotime($row->fld_wallet_date));?></p>
                                          </div>
                                      </div>
                                      
                                  </div>
                                  <?php }?>
                                  
                                </div>
							  <div class="tab-pane fade" id="creditshistory">
                                  <?php 
																		
                                        $text='';
                                        $icon='';
                                        $point ='';
                                        foreach($spend_history as $row){?>
																	
															
							      
								  <div class="row">
								  <div class="col-sm-4">
								  <div class="redlip-wallet">
                                         <h4>Coins <span><em class="fa fa-rupee"></em> {{$row->fld_reward_deduct_points}}</span></h4>
                                      </div>
                                      </div>
                                      
                                            
                                      <div class="col-sm-4">
                                         
                                          <div class="redlip-wallet">
                                         
                                              <p class="date"> Date : <?php echo date('d M Y',strtotime($row->fld_wallet_date));?></p>
                                          </div>
                                      </div>
                                      
                                  </div>
                                  <?php }?>
							  </div>
							</div>
						</div>	
					</div>
				</div>
				
			</div>
		</div>
	</div>
</section>

@endsection
    

  
  

    
