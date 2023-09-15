@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">My Reviews</a>
@endsection  
 
<section class="dashbord-section reviewsordrdv">
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
<div class="dashbordlinks">
<h6 class="fs18 fw600 mb20">My Reviews</h6>    
	               <ul>
						@include('fronted.mod_account.dashboard-menu')
					</ul>    
</div>    
</div>  
<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
<div class="dashbordtxt">
<h6 class="fs18 fw600 mb20">My Reviews</h6> 
    
  <div class="db-2-main-com db-2-main-com-table">
							
							
							<div class="table wishlistlst" id="results">
							  <div class="theader">
        							<div class="table_header">No.</div>
        								<div class="table_header">Product</div>
        							<div class="table_header">Rating</div>
        						
        								<div class="table_header">Comment</div>
        							<div class="table_header">Date</div>
							  </div>
							
							<?php $i=1;foreach($ratings as $row){ ?>
							  <div class="table_row" id="">
								<div class="table_small">
								  <div class="table_cell">No</div>
								  <div class="table_cell"><?php echo $i++;?> </div>
								</div>
                                 
                                 <div class="table_small">
                                            <div class="table_cell">Product</div>
                                            <div class="table_cell">
                                             <a href="{{App\Products::getProductDetailUrl($row->name,$row->product_id)}}">
                                                               {{$row->name}} 
                                                                </a>
                                            </div>
                                            </div>
                                            
								<div class="table_small">
								  <div class="table_cell">Rating</div>
								   <div class="table_cell"> {!!App\Products::userRatingOnproduct($row->rating)!!}
								   
                    <?php if($row->uploads!=''){ ?>
                    <p class="attachmentText">Attachment</p>
                    <div class="ratingprodct">
                    <img src="{{URL::to('/uploads/review')}}/{{$row->uploads}}">
                    </div>
                    <?php } ?>
								   </div>
								</div>
        						<div class="table_small">
        <div class="table_cell">Review Rating</div>
        <div class="table_cell"> {!!$row->review!!} 
        
        </div>
        </div>
								
							
								<div class="table_small">
								  <div class="table_cell">Review Date</div>
								   <div class="table_cell">
                                            <?php 
                                            $old_date_timestamp = strtotime($row->review_date);
                                            echo date('d M ,Y g:i A', $old_date_timestamp); 
                                            ?>
								   </div>
								</div>
                                  
							
							 
							</div>
								<div class="table_row" id="">
								<div class="table_small">
								  <div class="table_cell">No</div>
								  <div class="table_cell"><?php echo $i++;?> </div>
								</div>
                                 
                                 <div class="table_small">
                                            <div class="table_cell">Product</div>
                                            <div class="table_cell">
                                             <a href="{{App\Products::getProductDetailUrl($row->name,$row->product_id)}}">
                                                               {{$row->name}} 
                                                                </a>
                                            </div>
                                            </div>
                                            
								<div class="table_small">
								  <div class="table_cell">Rating</div>
								   <div class="table_cell"> {!!App\Products::userRatingOnproduct($row->rating)!!}
								   
                    <?php if($row->uploads!=''){ ?>
                    <p class="attachmentText">Attachment</p>
                    <div class="ratingprodct">
                    <img src="{{URL::to('/uploads/review')}}/{{$row->uploads}}">
                    </div>
                    <?php } ?>
								   </div>
								</div>
        						<div class="table_small">
        <div class="table_cell">Review Rating</div>
        <div class="table_cell"> {!!$row->review!!} 
        
        </div>
        </div>
								
							
								<div class="table_small">
								  <div class="table_cell">Review Date</div>
								   <div class="table_cell">
                                            <?php 
                                            $old_date_timestamp = strtotime($row->review_date);
                                            echo date('d M ,Y g:i A', $old_date_timestamp); 
                                            ?>
								   </div>
								</div>
                                  
							
							 
							</div>
								<div class="table_row" id="">
								<div class="table_small">
								  <div class="table_cell">No</div>
								  <div class="table_cell"><?php echo $i++;?> </div>
								</div>
                                 
                                 <div class="table_small">
                                            <div class="table_cell">Product</div>
                                            <div class="table_cell">
                                             <a href="{{App\Products::getProductDetailUrl($row->name,$row->product_id)}}">
                                                               {{$row->name}} 
                                                                </a>
                                            </div>
                                            </div>
                                            
								<div class="table_small">
								  <div class="table_cell">Rating</div>
								   <div class="table_cell"> {!!App\Products::userRatingOnproduct($row->rating)!!}
								   
                    <?php if($row->uploads!=''){ ?>
                    <p class="attachmentText">Attachment</p>
                    <div class="ratingprodct">
                    <img src="{{URL::to('/uploads/review')}}/{{$row->uploads}}">
                    </div>
                    <?php } ?>
								   </div>
								</div>
        						<div class="table_small">
        <div class="table_cell">Review Rating</div>
        <div class="table_cell"> {!!$row->review!!} 
        
        </div>
        </div>
								
							
								<div class="table_small">
								  <div class="table_cell">Review Date</div>
								   <div class="table_cell">
                                            <?php 
                                            $old_date_timestamp = strtotime($row->review_date);
                                            echo date('d M ,Y g:i A', $old_date_timestamp); 
                                            ?>
								   </div>
								</div>
                                  
							
							 
							</div>
							<?php } ?>
							 {{$ratings->links()}}
						</div>  
     
    <!-- <h6 class="fs18 fw600 text-center">No Item in Your Wishlist......</h6> -->
</div>    
</div>     
</div>    
</div>    
    
</section>  
@endsection

            
@include('fronted.includes.addtocartscript')
    
