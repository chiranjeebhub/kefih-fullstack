@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')

        @section('backButtonFromPage')
        <a href="{{ $page_details['back_route']}}" class="btn btn-default">Go Back</a>
        @endsection
<div class="col-sm-12">
<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
			   @csrf
		     
	<div class="row">
		<div class="col-sm-6">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['text_field']); !!}
		</div>
			
		
		<div class="col-sm-6">
			<div class="form-group">
				{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['description_file_field']); !!}

				@if($page_details['Method']=='2')
				{!! App\Helpers\CustomFormHelper::support_image('uploads/products',$page_details['Form_data']['Form_field']['images']['description_image']); !!}
				@endif
				<span class="text-warning">(Size: <?php echo Config::get('constants.size.product_desc_img_min'); ?>kb – <?php echo Config::get('constants.size.product_desc_img_max'); ?>kb <?php echo Config::get('constants.size.product_desc_img_dimensions'); ?> )</span>

			</div>
		</div>
		
		<div class="col-sm-12">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
		</div>
	</div>
						  
 </form>
</div>

@endsection

