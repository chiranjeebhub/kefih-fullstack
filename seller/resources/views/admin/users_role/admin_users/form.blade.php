@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
 @section('backButtonFromPage')
    <a href="javascript:void(0)" class="btn btn-default goBack">Go Back</a>
    @endsection
@section('content')
	
<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
			   @csrf
		     
    {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['name_field']); !!}
    {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['email_field']); !!}
   
    {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['password_field']); !!}
    {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}

	
						  
 </form>
@endsection
