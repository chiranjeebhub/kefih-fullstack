@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Referals</a>
@endsection  

<section class="dashbord-section">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<div class="dashbordlinks">
					<h6 class="fs18 fw600 mb20">My Account</h6>
					<ul> @include('fronted.mod_account.dashboard-menu') </ul>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
			    
			    <div class="dashbordtxt">
					<h6 class="fs18 fw600 mb20">My Referals (Total earned Rs.  {{auth()->guard('customer')->user()->r_amount}} ) ,
					    <span class="badge badge-danger">
    			        Your referral code: {{auth()->guard('customer')->user()->r_code}}
    			    </span>
					    </h6> 
				
				   	<div class="db-2-main-com db-2-main-com-table packagetbl">
						<div class="orderstabs">
							<ul class="nav nav-tabs" role="tablist">
							  
							  <li class="active"><a href="#totalearning" role="tab" data-toggle="tab" aria-expanded="true">
								   Referals
								  </a>
							  </li>
							  <li class="">
								  <a href="#earninghistory" role="tab" data-toggle="tab" aria-expanded="false">
									 Earning
								  </a>
							  </li>
							  
							</ul>
							<!-- Tab panes -->
							<div class="tab-content">
							  <div class="tab-pane fade active in" id="totalearning">
							     <!--refersl record starts-->
                               
							<div class="table wishlistlst" id="results">
							  <div class="theader">
                                    <div class="table_header">No.</div>
                                    <div class="table_header">User name</div>
                                    <div class="table_header">User Email</div>
                                    <div class="table_header">Refer date</div>
        
							  </div>
							
							<?php $i=1; foreach($referals as $referal){?>
							  <div class="table_row" id="">
								<div class="table_small">
								  <div class="table_cell">No</div>
								  <div class="table_cell"><?php echo $i++;?> </div>
								</div>
                                 
                                
                                      	<div class="table_small">
								  <div class="table_cell">User name</div>
								  <div class="table_cell">{{$referal->name}} {{$referal->last_name}} </div>
								</div>
								
									<div class="table_small">
								  <div class="table_cell">User Email</div>
								  <div class="table_cell"> {{$referal->email}}</div>
								</div>
								
									<div class="table_small">
								  <div class="table_cell">Refer date</div>
								  <div class="table_cell"> <?php 
                                            $old_date_timestamp = strtotime($referal->created_at);
                                            echo date('d M ,Y g:i A', $old_date_timestamp); 
                                            ?> </div>
								</div>
					   
						
							 
							</div>
							<?php }?>
					
</div> 

<!--refersl record ends-->
                                </div>
							  <div class="tab-pane fade" id="earninghistory">
								 
                                  <!--earning history  starts-->
                                 
						<div class="table wishlistlst" id="results">
							  <div class="theader">
                                    <div class="table_header">No.</div>
                                    <div class="table_header">Earn From</div>
                                    <div class="table_header">Earn For</div>
                                     <div class="table_header">Earned</div>
                                    <div class="table_header">Earn date</div>
        
							  </div>
							
							<?php $i=1; foreach($earnings as $earning){?>
							  <div class="table_row" id="">
								<div class="table_small">
								  <div class="table_cell">No</div>
								  <div class="table_cell"><?php echo $i++;?> </div>
								</div>
                                 
                                
                                      	<div class="table_small">
								  <div class="table_cell">Earn From</div>
								  <div class="table_cell">{{$earning->name}} {{$earning->last_name}}
                                        @if($earning->mode==0)
                                        <span>(Referer)</span>
                                        @endif
								  </div>
								</div>
								
									<div class="table_small">
								  <div class="table_cell">Earn For</div>
								  <div class="table_cell">
								      
								      @if($earning->mode==0)
								     
								      <span>Registeration</span>
								      @else
								        <span>Order Placed</span>
								      @endif
								      
								  </div>
								</div>
								
									<div class="table_small">
								  <div class="table_cell">Earned</div>
								  <div class="table_cell">
								      
								     {{$earning->amount}}
								      
								  </div>
								</div>
								
									<div class="table_small">
								  <div class="table_cell">Earn date</div>
								  <div class="table_cell"> <?php 
                                            $old_date_timestamp = strtotime($earning->created_at);
                                            echo date('d M ,Y g:i A', $old_date_timestamp); 
                                            ?> </div>
								</div>
					   
						
							 
							</div>
							<?php }?>
					
</div> 
<!--earning history ends-->
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
    

  
  

    
