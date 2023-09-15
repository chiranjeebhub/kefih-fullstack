@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])

@section('content')
	 @section('backButtonFromPage')
		 @if (Auth::guard('vendor')->check()) 
		 <a href="{{route('vendor_brands')}}" class="btn btn-default">Go Back</a>
		 @else
         <a href="{{route('brands')}}" class="btn btn-default">Go Back</a>
		 @endif()
        @endsection
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
					<span class="text-warning">(Size: <?php echo Config::get('constants.size.logo_min'); ?>kb – <?php echo Config::get('constants.size.logo_max'); ?>kb  Dimensions:560*460 )</span>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['banner_file_field']); !!}

					@if($page_details['Method']=='2')
					{!! App\Helpers\CustomFormHelper::support_image('uploads/brand/banner',$page_details['Form_data']['Form_field']['images']['banner_image']); !!}
					@endif
					<span class="text-warning">(Size: <?php echo Config::get('constants.size.banner_min'); ?>kb – <?php echo Config::get('constants.size.banner_max'); ?>kb Dimensions:1366*468 )</span>
				
				</div>
			</div>
			
			<div class="col-sm-6">
				<div class="form-group">
					{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['noc_file_field']); !!}

					@if($page_details['Method']=='2')
					<?php if($page_details['Form_data']['Form_field']['images']['noc_file']!=''){?>
					<a href="{{URL::to('/uploads/brand/banner').'/'.$page_details['Form_data']['Form_field']['images']['noc_file']}}" target="_blank">NOC</a>
					<?php } ?>
					@endif
					<span class="text-warning">( Size: <?php echo Config::get('constants.size.noc_min'); ?>kb – <?php echo Config::get('constants.size.noc_max'); ?>kb | FIle Type : PDF )</span>
				
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
 <script src="{{ asset('public/js/validateform.js') }}"></script> 
@endsection


