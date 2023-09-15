@extends('fronted.layouts.vendor_layout')
@section('content')
@section('breadcrum') 
<a href="javascript:void(0);">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)"> {!!$page_data->title!!}</a>
@endsection 
<style>
    .offer-dec h6 {
    font-size: 18px;
    margin-bottom: 12px;
    color: #000;
    font-weight: 600;
}
   .list-points {
    margin-bottom: 30px;
    padding-left: 15px;
} 
.list-points li {
    margin-bottom: 10px;
    line-height: 25px;
    font-size: 14px;
    list-style: disc;
    color: #565656;
}

</style>
<section class="offer-section">
<div class="container">
    <div class="row">
				<div class="col-xs-12">
				    @if($page_data->id == 1 || $page_data->id == 4 ||  $page_data->id == 3 ||  $page_data->id == 14)
				      <h6 class="title-bg mb30 fs20 fw400"> {!!$page_data->title!!}</h6>
						<?php if(!empty($page_data->banner)){ ?>
						  <img src="{{ asset('uploads/pages/'.$page_data->banner) }}" alt="" class="about-img-center img-responsive"/>
						<?php } ?>
				    @elseif($page_data->id == 5)
				     <h6 class="title-bg mb30 fs20 fw400"> {!!$page_data->title!!}</h6>
				    @else
				    <h6 class="title-bg mb30 fs20 fw400"> {!!$page_data->title!!}</h6>
				     @endif
				      
				         
                    <div class="offer-dec">
          {!!$page_data->description!!}
              
          </div>
        </div>
			</div>
</div>     
</section>
    
@endsection    
    
 