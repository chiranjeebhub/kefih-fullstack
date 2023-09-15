@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('backButtonFromPage')
<span class="btn btn-light pull-right"><a href="{{ $page_details['back_url']}}">Back</a></span>
@endsection
@section('content')
	
<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
    <div class="col-sm-12 brandmain">
			   @csrf
		     
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['text_field']); !!}

		<!--<div class="boxinputfile">
			<input type="file" name="file-5[]" id="file-5" class="inputfile inputfile-4" data-multiple-caption="{count} files selected" multiple />
			<label for="file-5"><figure><i class="fa fa-upload"></i></figure> <span>Choose a file&hellip;</span></label>
		</div>-->
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['logo_file_field']); !!}

					@if($page_details['Method']=='2')
					{!! App\Helpers\CustomFormHelper::support_image('uploads/brand/logo',$page_details['Form_data']['Form_field']['images']['logo_image']); !!}
					@endif
					<span class="text-warning">(Size: <?php echo Config::get('constants.size.logo_min'); ?>kb – <?php echo Config::get('constants.size.logo_max'); ?>kb <?php echo Config::get('constants.size.brand_logo_dimensions'); ?>)</span>

				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['banner_file_field']); !!}

					@if($page_details['Method']=='2')
					{!! App\Helpers\CustomFormHelper::support_image('uploads/brand/banner',$page_details['Form_data']['Form_field']['images']['banner_image']); !!}
					@endif
					<span class="text-warning">(Size: <?php echo Config::get('constants.size.banner_min'); ?>kb – <?php echo Config::get('constants.size.banner_max'); ?>kb <?php echo Config::get('constants.size.banner_dimensions'); ?> )</span>

				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['brand_description_field']); !!}

				</div>
			</div>
		</div>
		
	 
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
		
	
		</div>				  
 </form>
@endsection


