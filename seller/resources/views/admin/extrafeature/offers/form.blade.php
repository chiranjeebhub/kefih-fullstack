@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
 @section('backButtonFromPage')
    <a href="javascript:void(0)" class="btn btn-default goBack">Go Back</a>
    @endsection


@if($page_details['method']==1)
<style>
    .typeSelection{
        display:none; 
    }
</style>
@if($page_details['Form_data']['Form_field']['for_category_or_brand']['selected']==0)
<style>
    .brandSelection{
        display:none;
    }
    
</style>
@else
<style>
    .categorySelection{
        display:none;
    }
    
</style>
@endif

@else
<style>
    .brandSelection{
        display:none;
    }
    
</style>
@endif
<form role="form" class="form-element multi_delete_form" action="{{$page_details['Action_route']}}" method="post"
enctype="multipart/form-data"
>
  @csrf
                <div class="pad">
                <!--<h4 class="skin-purple-light">General</h4>-->
                <div class="fields">
                	<div class="row">
                		<div class="col-sm-12">
                			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['offer_name']); !!}  
                		</div>
                		<div class="col-sm-3">
                			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['discount']); !!}
                		</div>
                		<div class="col-sm-3">
                			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['discount_below_or_above']); !!}
                		</div>
                		<div class="col-sm-3">
                			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['for_category_or_brand']); !!}
                		</div>
                		<div class="col-sm-3 categorySelection">
                			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['category_field']); !!}
                		</div>
                		<div class="col-sm-3 brandSelection">
                			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['brand_field']); !!}
                		</div>
						<div class="col-sm-6">
							<div class="form-group">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['banner_file_field']); !!}

								@if($page_details['method']=='2')
								{!! App\Helpers\CustomFormHelper::support_image('uploads/advertise',$page_details['Form_data']['Form_field']['images']['banner_image']); !!}
								@endif
								<span class="text-warning">(Size: <?php echo Config::get('constants.size.adimage_min'); ?>kb – <?php echo Config::get('constants.size.adimage_max'); ?>kb )</span>

							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['mobile_file_field']); !!}

								@if($page_details['method']=='2')
								{!! App\Helpers\CustomFormHelper::support_image('uploads/advertise',$page_details['Form_data']['Form_field']['images']['mobile_image']); !!}
								@endif
								<span class="text-warning">(Size: <?php echo Config::get('constants.size.adimage_min'); ?>kb – <?php echo Config::get('constants.size.adimage_max'); ?>kb )</span>

							</div>
						</div>
                	</div>
                         
                </div>
                {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
                </div>
</form> 
			
				
				 
@endsection
