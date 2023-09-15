@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('backButtonFromPage')
<span class="btn btn-light pull-right"><a href="{{ $page_details['back_url']}}">Back</a></span>
@endsection 
@section('content')

<div class="col-sm-12 coloraditmaindiv">
<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
			   @csrf
		     
	<div class="row">
		<div class="col-sm-6">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['text_field']); !!}
		</div>
	<div class="col-sm-6">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['description_field']); !!}
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['color_file_field']); !!}

				@if($page_details['Method']=='2')
				{!! App\Helpers\CustomFormHelper::support_image('uploads/color',$page_details['Form_data']['Form_field']['images']['color_image']); !!}
				@endif
			</div>
		</div>
		
		<div class="col-sm-12">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
		</div>
	</div>
						  
 </form>
</div>

@endsection

