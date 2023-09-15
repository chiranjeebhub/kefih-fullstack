@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
 @section('backButtonFromPage')
    <a href="javascript:void(0)" class="btn btn-default goBack">Go Back</a>
    @endsection
@section('content')
	
<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
	<div class="col-sm-12 brandmain">		  
	@csrf
			   
	<div class="row">
		<div class="col-sm-4">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['select_field']); !!}
		</div>
		<div class="col-sm-4">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['cat_name']); !!}
		</div>
		<div class="col-sm-4">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['cat_url']); !!}
		</div>
		<div class="col-sm-4">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['select_compare']); !!}
		</div>
	
	    
		<div class="col-sm-4">
			<div class="form-group">
				{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['logo_file_field']); !!}
				@if($page_details['Method']=='2')
				{!! App\Helpers\CustomFormHelper::support_image('uploads/category/logo',$page_details['Form_data']['Form_field']['images']['logo_image']); !!}
				@endif
				<span class="text-warning">(Size: <?php echo Config::get('constants.size.logo_min'); ?>kb – <?php echo Config::get('constants.size.logo_max'); ?>kb <?php echo Config::get('constants.size.cat_logo_dimensions'); ?> )</span>

			</div>
		</div>
		
		<div class="col-sm-4">
			<div class="form-group">		    
			   
				{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['app_icon_file_field']); !!}
				@if($page_details['Method']=='2')
				{!! App\Helpers\CustomFormHelper::support_image('uploads/category/logo',$page_details['Form_data']['Form_field']['images']['app_icon']); !!}
				@endif

				<span class="text-warning">(Size : <?php echo Config::get('constants.size.app_icon_min'); ?>kb - <?php echo Config::get('constants.size.app_icon_max'); ?>kb Type: .png only)</span>
			</div>
		</div>
		
		<div class="col-sm-4">
			<div class="form-group">
				{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['banner_file_field']); !!}
				@if($page_details['Method']=='2')
				{!! App\Helpers\CustomFormHelper::support_image('uploads/category/banner',$page_details['Form_data']['Form_field']['images']['banner_image']); !!}
				@endif
				<span class="text-warning">(Size: <?php echo Config::get('constants.size.banner_min'); ?>kb – <?php echo Config::get('constants.size.banner_max'); ?>kb <?php echo Config::get('constants.size.banner_dimensions'); ?>)</span>
			</div>
		</div>
		
		<div class="col-sm-4">
			<div class="form-group">
				{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['size_chart_file_field']); !!}
				@if($page_details['Method']=='2' && $page_details['Form_data']['Form_field']['images']['size_chart']!='')
				<a target="_blank"  href="{{URL::to('/uploads/category/size_chart')}}/{{$page_details['Form_data']['Form_field']['images']['size_chart']}}">Size Chart</a>
				@endif
				<span class="text-warning">(Size: <?php echo Config::get('constants.size.size_chart_min'); ?>kb – <?php echo Config::get('constants.size.size_chart_max'); ?>kb <?php echo Config::get('constants.size.size_chart_dimensions'); ?>)</span>
			</div>
		</div>
		
		<div class="col-sm-6">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['tax_rate']); !!}
		</div>
		<div class="col-sm-6">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['commission_rate']); !!}
		</div>
        <div class="col-sm-12">
            <div class="form-group">
            {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['cat_description_field']); !!}
             </div>
        </div>
         <div class="col-sm-12">
            <div class="form-group">
            {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['cat_return_description_field']); !!}
             </div>
        </div>
         <div class="col-sm-12">
            <div class="form-group">
            {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['cat_cancel_description_field']); !!}
             </div>
        </div>
		<div class="col-sm-12">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
		</div>
	</div>
	</div>				  
 </form>
 <script>
 $("#name").keypress(function(e){
      //alert('keypressed'+e.keyCode);
       var k = e.keyCode;
       //return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8   || (k >= 48 && k <= 57)); //removing special characters
       if(k == 45 || k == 47 || k==92){ // preventing for - / \
           e.preventDefault();  
       }
    });
 </script>
@endsection
