@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
        @section('backButtonFromPage')
        <a href="{{ $back_url }}" class="btn btn-default goBack">Go Back</a>
        @endsection
@section('content')
	<style>
	    .hideelement{
	        display:none;
	    }
	</style>
<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
    <div class="col-sm-12 brandmain">
			   @csrf
			   {{--
		<div class="row">
			<div class="col-sm-12">
				<ul class="adrtsmntDmsn">
					<li>Ad Top Left : Dimensions(369px * 492px)</li>
					<li>Ad Top Center : Dimensions(369px * 492px)</li>
					<li>Ad Top Right : Dimensions(369px * 492px)</li>
					<li>Ad Middle Left : Dimensions(571px * 275px)</li>
					<li>Ad Middle Right : Dimensions(571px * 275px)</li>
					<li>Ad Bottom Left : Dimensions(369px * 274px)</li>
					<li>Ad Bottom Middle : Dimensions(369px * 274px)</li>
					<li>Ad Bottom Right : Dimensions(369px * 274px)</li>
					<li>Ad Footer Left  : Dimensions(571px * 216px)</li>
					<li>Ad Footer Right : Dimensions(571px * 216px)</li>
				</ul>

			</div>
			
		</div>--}}
		 <div class="row">
            <div class="col-sm-4 categorySelection hideelement">
            		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['category_field']); !!}
            	</div>
		    	<div class="col-sm-4">
		    	     {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['text_field']); !!}
		    	</div>
		    		<div class="col-sm-4">
		    	     {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['cat_url']); !!}
		    	</div>
		    	
		    	
		    	<div class="col-sm-4">
		    	     {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['url_field']); !!}
		    	</div>
			
			<div class="col-sm-6">
				<div class="form-group">
					{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['banner_file_field']); !!}

					@if($page_details['Method']=='2')
					{!! App\Helpers\CustomFormHelper::support_image('uploads/advertise',$page_details['Form_data']['Form_field']['images']['banner_image']); !!}
					@endif
					<span class="text-warning">(Size: <?php echo Config::get('constants.size.adimage_min'); ?>kb – <?php echo Config::get('constants.size.adimage_max'); ?>kb )</span>

				</div>
			</div>
			
			<div class="col-sm-6">
				<div class="form-group">
					{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['mobile_file_field']); !!}

					@if($page_details['Method']=='2')
					{!! App\Helpers\CustomFormHelper::support_image('uploads/advertise',$page_details['Form_data']['Form_field']['images']['mobile_image']); !!}
					@endif
					<span class="text-warning">(Size: <?php echo Config::get('constants.size.adimage_min'); ?>kb – <?php echo Config::get('constants.size.adimage_max'); ?>kb )</span>

				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['banner_description_field']); !!}

				</div>
			</div>
		</div>
		
	 
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
		
	
		</div>				  
 </form>
@endsection


