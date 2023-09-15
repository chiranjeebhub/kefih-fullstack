@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
 @section('backButtonFromPage')
    <a href="javascript:void(0)" class="btn btn-default goBack">Go Back </a> 
    @endsection
@section('content')
	
<form role="form" id="formcoupon" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
	<div class="col-sm-12 brandmain">		  
	@csrf
		<div class="row">
		    	<div class="col-sm-4">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['coupon_type_field']); !!}
		</div>
            <div class="col-sm-4">
            {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['coupon_name_field']); !!}
            </div>
            
		<div class="col-sm-4 coupon_dt">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['issue_date_field']); !!}
		</div>
		<div class="col-sm-4 coupon_dt">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['expire_date_field']); !!}
		</div>
	
		<div class="col-sm-4 coupon_number">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['number_coupon_feild']); !!}
		</div>
	
	    
		<div class="col-sm-4">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['discount_field']); !!}
		</div>
			<div class="col-sm-4">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['banner_file_field']); !!}
			<span class="text-warning">(size: <?php echo Config::get('constants.size.banner_min'); ?>kb – <?php echo Config::get('constants.size.banner_max'); ?>kb <?php echo Config::get('constants.size.coupon_banner_dimensions'); ?> )</span>

		</div>
			<div class="col-sm-4 coupon_cart">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['cart_amount_feild']); !!}
		</div>
			<div class="col-sm-4 coupon_cart">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['cart_max_amount_feild']); !!}
		</div>

		
		<div class="col-sm-12">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['short_description_field']); !!}
		</div>
		    
	<div class="col-sm-12">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
		</div>
	
		
	
	</div>	   
	
	</div>				  
 </form>
 

<!--<script src="https://code.jquery.com/jquery-1.10.2.js"></script>-->
<script>
 var coupon_type="{{ $page_details['Form_data']['Form_field']['coupon_type_field']['selected']}}";
 if(coupon_type==1){
     $(".coupon_cd").show(); 
     $(".coupon_dt").hide(); 
  } else{
     $(".coupon_cd").hide();
      $(".coupon_dt").show(); 
  }
    
$( document ).ready(function() {
    $('select[name="couponType"] option[value="0"]').attr('selected', 'selected').trigger('change');
});

//disabling past date from datepicker
var nowDate = new Date();
var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);

$('.datepicker').datepicker({
	//format:'mm-dd-yyyy', 
	startDate: today 
});

</script>
 
<script src="{{ asset('public/js/validateform.js') }}"></script>
  
@endsection
