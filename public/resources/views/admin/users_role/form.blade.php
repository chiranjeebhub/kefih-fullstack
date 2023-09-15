@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
	
<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
			   @csrf
		     
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['text_field']); !!}


		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['logo_file_field']); !!}

		@if($page_details['Method']=='2')
		{!! App\Helpers\CustomFormHelper::support_image('uploads/brand/logo',$page_details['Form_data']['Form_field']['images']['logo_image']); !!}
		@endif
	 

		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['banner_file_field']); !!}
		
		@if($page_details['Method']=='2')
		{!! App\Helpers\CustomFormHelper::support_image('uploads/brand/banner',$page_details['Form_data']['Form_field']['images']['banner_image']); !!}
		@endif

		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
		
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['input_hidden_field']); !!}
						  
 </form>
@endsection
