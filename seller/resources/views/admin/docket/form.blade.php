@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
        @section('backButtonFromPage')
        <a href="javascript:void(0)" class="btn btn-default goBack">Go Back</a>
        @endsection
@section('content')
	
<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
    <div class="col-sm-12 brandmain">
			   @csrf
		
		 <div class="row">
		    	<div class="col-sm-6">
		    	     {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['text_field']); !!}
		    	</div>
		    
		</div>
		
	 
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
		
	
		</div>				  
 </form>
@endsection


