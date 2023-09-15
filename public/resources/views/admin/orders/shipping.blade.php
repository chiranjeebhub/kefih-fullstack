@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
<div class="allbutntbl">
	<a href="{{route('orders',base64_encode(1)) }}" class="btn btn-warning"><i class="fa fa-undo"></i> Back</a>
</div>
<div class="col-sm-12">
<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
			   @csrf
	<div class="row">
		<div class="col-sm-6">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['docket_no']); !!}
		</div>
		<div class="col-sm-6">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['courier']); !!}
		</div>
		<div class="col-sm-12">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['remarks']); !!}
		</div>
		<div class="col-sm-12">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
		</div>
	</div>
						  
 </form>
</div>
@endsection
