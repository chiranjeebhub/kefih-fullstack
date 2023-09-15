@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
 @section('backButtonFromPage')
    <a href="javascript:void(0)" class="btn btn-default goBack">Go Back</a>
    @endsection
@section('content')
	
<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
    <div class="col-sm-12 blogmain">
			   @csrf
		     
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['text_field']); !!}

		<!--<div class="boxinputfile">
			<input type="file" name="file-5[]" id="file-5" class="inputfile inputfile-4" data-multiple-caption="{count} files selected" multiple />
			<label for="file-5"><figure><i class="fa fa-upload"></i></figure> <span>Choose a file&hellip;</span></label>
		</div>-->
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group brandmain">
					
					{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['whatsmore_file_field']); !!}

					@if($page_details['Method']=='2')
					{!! App\Helpers\CustomFormHelper::support_image('uploads/blog/banner',$page_details['Form_data']['Form_field']['images']['whatsmore_image']); !!}
					@endif
					<span class="text-warning">(Size: <?php echo Config::get('constants.size.whats_more_image_min'); ?>kb – <?php echo Config::get('constants.size.whats_more_image_max'); ?>kb <?php echo Config::get('constants.size.whats_more_image_dimensions'); ?> )</span>

				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['whatsmore_description_field']); !!}
				

				</div>
			</div>
		</div>
		
	 
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
		
	
		</div>				  
 </form>
@endsection


