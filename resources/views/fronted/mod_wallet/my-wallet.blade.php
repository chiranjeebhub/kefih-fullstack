@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Wallet</a>
@endsection  

<section class="wrap wallet-wrap main_section">
	<div class="container">
        <h4>Wallet</h4>
        <div class="add-amount">
            <div class="amountBox1">
                <div class="media">
                <div class="media-left">
                  <div class="wallet-icon">
                    <img src="{{ asset('public/fronted/images/wallet.png') }}">
                  </div>
                </div>
                <div class="media-body">
                    <h6>Avaliable amount</h6>
                    <h3><i class="fa fa-rupee"></i> <?php echo $cust_info->total_reward_points;?></h3>
                    <?php 
                        $earned=$spend=0;						
                        foreach($current_wallet_history as $row){

                            if($row->fld_reward_points!=0){
                                $earned+=$row->fld_reward_points;
                            }
                            // else if($row->fld_reward_deduct_points!=0){
                            //     $spend+=$row->fld_reward_deduct_points;
                            // }
                        }

                        $spend = $TotalSpendAmount;
                    ?>
                </div>
              </div>
            </div>
            <div class="amountBox2">
                <form class="form-inline" method="POST" action="{{route('wallet-recharge')}}">
                  @csrf
                    <div class="form-group">
                        <div class="popup_info profile_form">
                            <select id="mounth" name="currency"> 
                                <option value="INR" >INR</option>
                            </select>
                        </div>
                      <div class="input-group">
                        <input type="number" class="form-control" placeholder="Enter Amount" name="amount" value="" aria-label="Enter Amount" aria-describedby="button-addon2" required>

                        <button class="btn btn-primary" type="submit" id="button-addon2">Proceed</button>
                      </div>
                    </div>
                </form>

            </div>
        </div>
        <div class="account_dashboard white-box wallet-table">
                 
        <!--<h4>Transaction History</h4>-->
            
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
							<ul class="nav nav-tabs" id="nav-tab" role="tablist">
							  
							  <li><a href="#totalearning" role="tab" data-bs-toggle="tab" aria-expanded="true"  class="active">
								   Earning
								  </a>
							  </li>
							  <li>
								  <a href="#earninghistory" role="tab" data-bs-toggle="tab" aria-expanded="false">
									  Earning history
								  </a>
							  </li>
							  <li>
								  <a href="#creditshistory" role="tab" data-bs-toggle="tab" aria-expanded="false">
									  Spends credits history
								  </a>
							  </li>
							</ul>
							<!-- Tab panes -->
							<div class="tab-content" id="nav-tabContent">
							  <div class="tab-pane fade active show" id="totalearning" role="tabpanel">
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

                                                    @case(5)
                                                     Wallet Recharge
                                                    @break
                                            
                                           @endswitch
                                            
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
							  <div class="tab-pane fade" id="earninghistory" role="tabpanel">
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

                                                    @case(5)
                                                     Wallet Recharge
                                                    @break
                                            
                                           @endswitch
                                            
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
							  <div class="tab-pane fade" id="creditshistory" role="tabpanel">
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
            
        <!--<div class="orders_table_main">
          <div class="table-responsive">
            <div class="table" id="results">
              <div class="theader">
                <div class="table_header">Sr. No.</div>
                <div class="table_header">Transaction Id</div>
                <div class="table_header">Date &amp; Time</div>
                <div class="table_header">Amount</div>
                <div class="table_header">Action</div>
              </div>
              <div class="table_row">
                <div class="table_small">
                  <div class="table_cell">Sr. No.</div>
                  <div class="table_cell">1</div>
                </div>
                <div class="table_small">
                  <div class="table_cell">Transaction Id</div>
                  <div class="table_cell"><span title="Purchased">pi_3MSGClSBYmcOkkJ500P4VH87</span> </div>
                </div>
                <div class="table_small">
                  <div class="table_cell">Date &amp; Time</div>
                  <div class="table_cell"> 20 Jan 2023, 08:45 AM</div>
                </div>

                <div class="table_small">
                  <div class="table_cell">Amount</div>
                  <div class="table_cell">

                                           
                      <span class="text-success">+ $100</span>                   
                  </div>
                </div>
                <div class="table_small">
                  <div class="table_cell">Action</div>
                  <div class="table_cell">
                    <a href="#" class="text-warning">Order details</a>
                  </div>
                </div>
              </div>
              <div class="table_row">
                <div class="table_small">
                  <div class="table_cell">Sr. No.</div>
                  <div class="table_cell">2</div>
                </div>
                <div class="table_small">
                  <div class="table_cell">Transaction Id</div>
                  <div class="table_cell"><span title="Purchased">pi_3MSGClSBYmcOkkJ500P4VH87</span> </div>
                </div>
                <div class="table_small">
                  <div class="table_cell">Date &amp; Time</div>
                  <div class="table_cell"> 20 Jan 2023, 08:45 AM</div>
                </div>

                <div class="table_small">
                  <div class="table_cell">Amount</div>
                  <div class="table_cell">

                                           
                      <span class="text-success">+ $100</span>                   
                  </div>
                </div>
                <div class="table_small">
                  <div class="table_cell">Action</div>
                  <div class="table_cell">
                    <a href="#" class="text-warning">Order details</a>
                  </div>
                </div>
              </div> 
              <div class="table_row">
                <div class="table_small">
                  <div class="table_cell">Sr. No.</div>
                  <div class="table_cell">3</div>
                </div>
                <div class="table_small">
                  <div class="table_cell">Transaction Id</div>
                  <div class="table_cell"><span title="Purchased">pi_3MSGClSBYmcOkkJ500P4VH87</span> </div>
                </div>
                <div class="table_small">
                  <div class="table_cell">Date &amp; Time</div>
                  <div class="table_cell"> 20 Jan 2023, 08:45 AM</div>
                </div>

                <div class="table_small">
                  <div class="table_cell">Amount</div>
                  <div class="table_cell">

                                           
                      <span class="text-danger">- $100</span>                   
                  </div>
                </div>
                <div class="table_small">
                  <div class="table_cell">Action</div>
                  <div class="table_cell">
                    <a href="#" class="text-warning">Order details</a>
                  </div>
                </div>
              </div> 
                <div class="table_row">
                <div class="table_small">
                  <div class="table_cell">Sr. No.</div>
                  <div class="table_cell">4</div>
                </div>
                <div class="table_small">
                  <div class="table_cell">Transaction Id</div>
                  <div class="table_cell"><span title="Purchased">pi_3MSGClSBYmcOkkJ500P4VH87</span> </div>
                </div>
                <div class="table_small">
                  <div class="table_cell">Date &amp; Time</div>
                  <div class="table_cell"> 20 Jan 2023, 08:45 AM</div>
                </div>

                <div class="table_small">
                  <div class="table_cell">Amount</div>
                  <div class="table_cell">

                                           
                      <span class="text-danger">- $100</span>                   
                  </div>
                </div>
                <div class="table_small">
                  <div class="table_cell">Action</div>
                  <div class="table_cell">
                    <a href="#" class="text-warning">Order details</a>
                  </div>
                </div>
              </div> 
                <div class="table_row">
                <div class="table_small">
                  <div class="table_cell">Sr. No.</div>
                  <div class="table_cell">5</div>
                </div>
                <div class="table_small">
                  <div class="table_cell">Transaction Id</div>
                  <div class="table_cell"><span title="Purchased">pi_3MSGClSBYmcOkkJ500P4VH87</span> </div>
                </div>
                <div class="table_small">
                  <div class="table_cell">Date &amp; Time</div>
                  <div class="table_cell"> 20 Jan 2023, 08:45 AM</div>
                </div>

                <div class="table_small">
                  <div class="table_cell">Amount</div>
                  <div class="table_cell">

                                           
                      <span class="text-danger">- $100</span>                   
                  </div>
                </div>
                <div class="table_small">
                  <div class="table_cell">Action</div>
                  <div class="table_cell">
                    <a href="#" class="text-warning">Order details</a>
                  </div>
                </div>
              </div> 
            </div>
          </div>
          <nav aria-label="Page navigation example">
            
          </nav>
        </div>-->
          </div>
	</div>
</section>

@endsection
    

  
  

    
