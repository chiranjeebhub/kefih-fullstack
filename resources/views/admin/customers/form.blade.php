@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')

<div class="col-sm-12">
<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
			   @csrf
		     
	<div class="row">
		<div class="col-sm-6">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['name_field']); !!}
		</div>
		
		<div class="col-sm-6">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['email_field']); !!}
		</div>
		
		<div class="col-sm-6">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['phone_field']); !!}
		</div>
		
		<div class="col-sm-6">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['password_field']); !!}
		</div>
		
	
		
		<div class="col-sm-12">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
		</div>
	</div>
						  
 </form>
</div>

@endsection

