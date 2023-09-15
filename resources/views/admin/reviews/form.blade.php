@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('backButtonFromPage')
<span class="btn btn-light pull-right"><a href="{{ $page_details['back_url']}}">Back</a></span>
@endsection 
@section('content')

<div class="col-sm-12">
<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
			   @csrf
		     
	<div class="row">
		<div class="col-sm-6">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['review_field']); !!}
		</div>
		
		<div class="col-sm-6">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['isInSnapbook']); !!}
		</div>
		<div class="col-sm-6">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['rating_field']); !!}
		</div>
		
		
			<input type="hidden" name="rating" id="rating_id" value="">
                <div class="col-sm-6">
                    <div class="rating">
                        <div class="stars">
                            <!--<span class="review-no">Select Ratings
                            </span>--> 
                            <div class="rateit" data-rateit-mode="font" id="rateit" aria-valuenow="5">
                            </div>
                        </div>
                    </div>
                </div>
		<div class="col-sm-12">
		<?php if($rating_data->uploads!=''){ ?>
						<img src="{{URL::to('/uploads/review')}}/{{$rating_data->uploads}}">
						<?php } ?>
		</div>
		
		
		<div class="col-sm-12">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
		</div>
		
		
	</div>
						  
 </form>
</div>

@endsection

