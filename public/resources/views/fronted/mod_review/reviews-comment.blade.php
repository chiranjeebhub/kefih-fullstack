@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">{{ ucwords($prd_detail->name) }}</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Reviews and rating</a>
@endsection      
<section class="reviewscomment-section">
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> 


<div class="row">
    <div class="col-md-12 mb20"><h4 class="fw600">{{ ucwords($prd_detail->name) }}'s reviews and rating.</h4></div>

    <?php foreach($ratings as $rating_row){ ?>
	<div class="col-md-12 col-xs-12">
    	<div class="reviewstxt">
			<div class="row">
				<div class="col-md-9 col-xs-12">
					<h6 class="fw600 fs16 mb20">
					    
					    {{strtoupper($rating_row->user_name)}}</h6>
					<p>{{$rating_row->review}}</p> 

					{!!App\Products::userRatingOnproduct($rating_row->rating)!!}   
				   <div class="linkbox">
					<div class="row">
					<div class="col-md-6">
					<p><?php 
					$old_date_timestamp = strtotime($rating_row->review_date);
					echo date('d M ,Y g:i A', $old_date_timestamp); 
					?></p>       
					</div>    
					<!--<div class="col-md-6">-->
					<!--<span><a href="#"><i class="fa fa-thumbs-up"></i>50</a> </span> -->
					<!--<span><a href="#"><i class="fa fa-thumbs-down"></i>30</a> </span>     -->
					<!--</div>-->
					</div> 
					</div> 
				</div>
				<div class="col-md-3 col-xs-12">
					<div class="ratingprodct text-right">
						<?php if($rating_row->uploads!=''){ ?>
						<img src="{{URL::to('/uploads/review')}}/{{$rating_row->uploads}}">
						<?php } ?>
					</div>
				</div>
			</div>
          
    </div>
	</div>
    <?php  } ?>
 </div>  
     
    {{$ratings->links()}}
</div>    
</div>
</div>     
</section>    
@endsection   
 