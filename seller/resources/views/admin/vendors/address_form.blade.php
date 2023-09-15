@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
 @section('backButtonFromPage')
    <a href="{{route('vendor_address', [base64_encode(auth()->guard('vendor')->user()->id)])}}" class="btn btn-default goBack">Go Back</a>
    @endsection
@section('content')

<div class="col-sm-12">
<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
			   @csrf
		     
	<div class="row">
		<div class="col-sm-6">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['address_field']); !!}
		</div>
		<div class="col-sm-6">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['state_field']); !!}
		</div>
		<div class="col-sm-6">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['city_field']); !!}
		</div>
		
		<div class="col-sm-6">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['pincode_field']); !!}
		</div>
		
		
		<div class="col-sm-12">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
		</div>
	</div>
						  
 </form>
</div>


<script src="{{ asset('public/js/validateform.js') }}"></script> 

@endsection

