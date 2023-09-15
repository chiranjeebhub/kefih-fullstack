@extends('admin.layouts.app_new')
@section('pageTitle', 'Product Rating')
@section('content')
<style>
    .reviewRow{
      box-shadow: 12px 7px 7px -8px #888888;
      margin-top:8px;
    }
    .red{
        color: red; 
    }
    .green{
        color: green; 
    }
</style>
	<?php 
	$page_details=array(
	     "reset_route"=>"",
	    "search_route"=>""
	    );
                
        $prd_id =base64_decode(Request()->id);
		
	?>
	
	<div class="">

	<div class="col-sm-12">
		<div class="row">
                   
                   
            <div class="col-sm-2">
            <form role="form" action="{{ route('selectedReview')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="mode" value="1">
             <input type="hidden" value="" name="product_ids" class="product_id">
            <button type="submit" class="btn btn-danger commonClassDisableButton" disabled>Approve Selected</button>
            </form>
            </div>
            
		</div>
	</div>
</div>
  <h4 class="fw600">
    <a href="{{route('edit_product', [base64_encode(0),base64_encode($prd_id)])}}" target="_blank">
    {{ ucwords($prd_detail->name) }}'s
    </a>
  
  reviews and rating.</h4>
    <!-- Main content -->
    <section class="content">
       Select All
	<input type="checkbox" class="check_all ">
	  <div class="row">
       

    <?php foreach($ratings as $rating_row){ ?>
	<div class="col-md-12 col-xs-12 reviewRow">
    	<div class="reviewstxt">
			<div class="row">
			    @if(!$rating_row->isActive==1)
                           <input type="checkbox" class="checkbox multiple_select_checkBox checkedProduct" name="review_id[]" value="{{$rating_row->id}}">
                        @endif
                        
			   
				<div class="col-md-9 col-xs-12">
					<h6 class="fw600 fs16 mb20">{{strtoupper($rating_row->user_name)}}</h6>
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
					<div class="col-md-6">
					     @if($rating_row->is_in_snap_book==1)
                            <span class="">It is in Snapbook</span>
                        @endif
                        
                        @if($rating_row->isActive==1)
                            <span class="green">Activated</span>
                        @else
                            <span class="red">Deactivated</span>
                        @endif
                         <a href="{{route('edit_rating_review', [base64_encode($prd_id),base64_encode($rating_row->id)])}}">
                                Edit
                                </a>
					   </div>
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
	
    {{$ratings->links()}}

		  
      </div>	
	
      <!-- /.row -->	      
	</section>
    <!-- /.content -->
    	@include('admin.includes.Common_search_and_delete') 
@endsection
