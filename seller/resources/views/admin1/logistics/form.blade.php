@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
	
<div class="col-md-12 col-xs-12">
	<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
  		    @csrf
		<div class="row">
			<div class="col-md-6">
				{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['name_field']); !!}

				{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['logistic_link_field']); !!}
				
				{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
			</div>
		</div>

	</form>
</div>
@endsection

