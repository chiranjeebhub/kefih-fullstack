@extends('admin.layouts.app_new_layout_for_coupon')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('backButtonFromPage')
<span class="btn btn-light pull-right"><a href="{{ $page_details['back_url']}}">Back</a></span>
@endsection 
@section('content')




<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
	<div class="col-sm-12 brandmain">		  
	@csrf
		<div class="row">
		    	<div class="col-sm-4">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['coupon_type_field']); !!}
		</div>
            <div class="col-sm-4">
            {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['coupon_name_field']); !!}
            </div>
            <div class="col-sm-4">
            {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['coupon_for_field']); !!}
            </div>
                		
            <div class="col-sm-3 noOfUserCoupon">
            {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['number_of_user_field']); !!}
            </div>
            
             <div class="col-sm-3 noOfUserCoupon">
            {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['user_coupon_uses_field']); !!}
            </div>
            
		<!--<div class="col-sm-4 coupon_dt">-->
  <!--          <div class="form-group">-->
  <!--              <div class='input-group date' id='issueDate'>-->
  <!--                  <input type='text' class="form-control" />-->
  <!--                  <span class="input-group-addon">-->
  <!--                      <span class="glyphicon glyphicon-calendar"></span>-->
  <!--                  </span>-->
  <!--              </div>-->
  <!--          </div>		-->
		
		<!--</div>-->
		<!--<div class="col-sm-4 coupon_dt">-->
  <!--          <div class="form-group">-->
  <!--              <div class='input-group date' id='expDate'>-->
  <!--                  <input type='text' class="form-control" />-->
  <!--                  <span class="input-group-addon">-->
  <!--                      <span class="glyphicon glyphicon-calendar"></span>-->
  <!--                  </span>-->
  <!--              </div>-->
  <!--          </div>		-->
				
		<!--</div>-->
	
	
		<div class='col-sm-6 coupon_dt'>
		  {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['issue_date_field']); !!}
        </div>	
        
        <div class='col-sm-6 coupon_dt'>
		 	{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['expire_date_field']); !!}	
        </div>	
		
		<div class="col-sm-4 coupon_number">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['number_coupon_feild']); !!}
		</div>
	
	    
		<div class="col-sm-4">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['discount_field']); !!}
		</div>
			<div class="col-sm-4">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['coupon_show_inapp__field']); !!}
		</div>
			<div class="col-sm-4">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['banner_file_field']); !!}
			<span class="text-warning">(Size: <?php echo Config::get('constants.size.banner_min'); ?>kb – <?php echo Config::get('constants.size.banner_max'); ?>kb <?php echo Config::get('constants.size.coupon_banner_dimensions'); ?> )</span>

		</div>
        	<div class="col-sm-4 coupon_cart">
        	{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['cart_amount_feild']); !!}
        </div>
        
			<div class="col-sm-4 coupon_cart">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['cart_max_amount_feild']); !!}
		</div>
	<div class="col-sm-4">
        	{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['coupon_max_discount_feild']); !!}
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
 <script>
 
 var coupon_type="{{ $page_details['Form_data']['Form_field']['coupon_type_field']['selected']}}";
 if(coupon_type==1){
     $(".coupon_cd").show(); 
     $(".coupon_dt").hide(); 
  } else{
     $(".coupon_cd").hide();
      $(".coupon_dt").show(); 
  }
  
  
  $('body').on('change', 'select[name="coupon_for"]', function(){
      var val =  $(this).val();
      
    //   if(val == 2){
    //       $('.number_of_user_bx').show();
    //       $('select[name="coupon_for"]').closest('.form-group').parent().removeClass('col-sm-4').addClass('col-sm-3');
    //       $('input[name="name"]').closest('.form-group').parent().removeClass('col-sm-4').addClass('col-sm-3');
    //       $('select[name="couponType"]').closest('.form-group').parent().removeClass('col-sm-4').addClass('col-sm-3');
    //   }else{
    //       $('select[name="coupon_for"]').closest('.form-group').parent().removeClass('col-sm-3').addClass('col-sm-4');
    //       $('input[name="name"]').closest('.form-group').parent().removeClass('col-sm-3').addClass('col-sm-4');
    //       $('select[name="couponType"]').closest('.form-group').parent().removeClass('col-sm-3').addClass('col-sm-4');
    //       $('.number_of_user_bx').hide();
    //   }
      
  });
  
 </script>





@endsection
