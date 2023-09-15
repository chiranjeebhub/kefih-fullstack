@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('backButtonFromPage')
<a href="{{ $page_details['back_url']}}" class="btn btn-light pull-right">Back</a>
@endsection
@section('content')
<div class="col-sm-12">
	<ul class="adrtsmntDmsn">
		<li>Row 1: Dimensions(1139px * 370px)</li>
		<li>Row 2 Left : Dimensions(557px * 374px)</li>
		<li>Row 2 Right : Dimensions(557px * 374px)</li>
		<li>Row 3 Left : Dimensions(555px * 374px)</li>
		<li>Row 3 Right : Dimensions(555px * 374px)</li>
		<li>Row 4 :  Dimensions(1141px * 400px)</li>
		<li>Row 5 Left  : Dimensions(556px * 372px)</li>
		<li>Row 5 Right : Dimensions(556px * 372px)</li>
		<li>Row 6 Left  : Dimensions(556px * 372px)</li>
		<li>Row 6 Right : Dimensions(556px * 372px)</li>
		<li>Row 7 : Dimensions(1140px * 447px)</li>
		<li>Offer Top : Dimensions(1366px * 463px)</li>
	</ul>

</div>
@if(!empty($page_details['Form_data']['Form_field']['images']['banner_image']))
<img src="{{URL::to('/uploads/advertise')}}/{{$page_details['Form_data']['Form_field']['images']['banner_image']}}" style="display:block !important; width:500px;">
					
				
@endif
<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
    <div class="col-sm-12 brandmain">
			   @csrf
		     
   
		 <div class="row">
		    	<div class="col-sm-6">
		    	     {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['text_field']); !!}
		    	</div>
		    	
		    	<div class="col-sm-6">
		    	     {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['url_field']); !!}
		    	</div>
			
			<div class="col-sm-6">
				<div class="form-group">
					{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['banner_file_field']); !!}

				</div>
					@if($page_details['Method']=='2')
						
				
					@endif

			</div>
			<div class="col-sm-12">
				<div class="form-group">
					{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['banner_description_field']); !!}

				</div>
			</div>
		</div>
		
	 
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
		
	
		</div>				  
 </form>
@endsection


