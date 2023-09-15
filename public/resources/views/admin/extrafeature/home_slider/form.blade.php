@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('backButtonFromPage')
<span class="btn btn-light pull-right"><a href="{{ $page_details['back_url']}}">Back</a></span>
@endsection
@section('content')


<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
    <div class="col-sm-12 brandmain">
			   @csrf
		     
   
		 <div class="row">
		     
		     	<div class="col-sm-6" style="display:none;">
		     	    <label for="city_ids">Choose Cities <?php /*[{{count($cities)}}]*/ ?></label>
		    	     <select class="form-control" name="city_ids[]" multiple >
		    	         <option value="">Select Cities</option>
		    	         @foreach($cities as $row)
		    	         <option value="{{$row->id}}" {{(in_array($row->id,$selected_cities))?'selected':''}}>{{$row->name}}</option>
		    	         @endforeach
		    	     </select>
		    	</div>
		    	<div class="col-sm-6">
		    	     {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['text_field']); !!}
		    	</div>
		    	
		    	<div class="col-sm-6">
		    	     {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['url_field']); !!}
		    	</div>
			
			<div class="col-sm-6">
				<div class="form-group">
					{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['banner_file_field']); !!}

					@if($page_details['Method']=='2')
					{!! App\Helpers\CustomFormHelper::support_image('uploads/slider',$page_details['Form_data']['Form_field']['images']['banner_image']); !!}
					@endif
				</div>				
				<span class="text-warning">(Size: <?php echo Config::get('constants.size.banner_min'); ?>kb – <?php echo Config::get('constants.size.banner_max'); ?>kb <?php echo Config::get('constants.size.banner_dimensions'); ?> )</span>

			</div>
			<div class="col-sm-6">
				<div class="form-group">
					{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['banner_description_field']); !!}

				</div>
			</div>
		</div>
		
	 
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
		
	
		</div>				  
 </form>
@endsection


