@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('backButtonFromPage')
<span class="btn btn-light pull-right"><a href="{{ $page_details['back_url']}}">Back</a></span>
@endsection 
@section('content')
            <?php 
            $parameters = Request::segment(2);
            ?>
<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
    <div class="col-sm-12 filtermain">
		<div class="row">
		<div class="col-sm-6">
			   @csrf
		     
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['text_field']); !!}

		<!--<div class="boxinputfile">
			<input type="file" name="file-5[]" id="file-5" class="inputfile inputfile-4" data-multiple-caption="{count} files selected" multiple />
			<label for="file-5"><figure><i class="fa fa-upload"></i></figure> <span>Choose a file&hellip;</span></label>
		</div>-->
		</div>
		@if($parameters!='editfilter')
		<div class="col-sm-6">
			
				{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['filter_description_field']); !!}

		</div>
		
		@endif
	 </div>
		</div>
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
		
							  
 </form>
@endsection


