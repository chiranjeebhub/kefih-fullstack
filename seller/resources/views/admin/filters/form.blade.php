@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
 @section('backButtonFromPage')
    <a href="javascript:void(0)" class="btn btn-default goBack">Go Back</a>
    @endsection
@section('content')
            <?php 
            $parameters = Request::segment(2);
            ?>
<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
    <div class="col-sm-12 filtermain">
			   @csrf
		     
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['text_field']); !!}

		<!--<div class="boxinputfile">
			<input type="file" name="file-5[]" id="file-5" class="inputfile inputfile-4" data-multiple-caption="{count} files selected" multiple />
			<label for="file-5"><figure><i class="fa fa-upload"></i></figure> <span>Choose a file&hellip;</span></label>
		</div>-->
		@if($parameters!='editfilter')
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['filter_description_field']); !!}

				</div>
			</div>
		</div>
		@endif
	 
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
		
	
		</div>				  
 </form>
 <script src="{{ asset('public/js/validateform.js') }}"></script> 
@endsection


