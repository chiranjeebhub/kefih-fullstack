@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
 @section('backButtonFromPage')
    <a href="javascript:void(0)" class="btn btn-default goBack">Go Back</a>
    @endsection
@section('content')

<div class="col-sm-12 permissionspage">
<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
			   @csrf
		     
		@if($page_details['Method']=='1')
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['text_field']); !!}
		@endif
		
		@if($page_details['Method']=='2')
		  	User Role : {!! $page_details['Form_data']['Form_field']['set_value']['User_role_name']; !!}
		@endif

	
	
	{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['checkbox_field']); !!}
	
		@if($page_details['Method']=='2')
				{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['set_value']['check_box_data']); !!}
		@endif
						
	{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
						  
 </form>
</div>
@endsection

