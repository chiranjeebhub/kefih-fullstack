@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
<style>
.logistics_container{
	display:'<?php echo ($page_details['Form_data']['Form_field']['Logicticsfield']['selected']==0)?'none':'block'; ?>'
}
</style>

<div class="col-sm-12">
	<div class="row">
	
	
	<div class="col-md-6">
      <label for="exampleInputEmail1">
	  <?php 
	  if($page_details['Method']==2){
	  } else{
		  echo "Default Upload";
	  }
	  
	  ?>
	 
	  
	  </label>
	<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
			   @csrf
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['select_field']); !!}
			<div class="radiodsign">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_code_with_logistics_field']); !!}</div>
			<div class="logistics_container">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['Logicticsfield']); !!}
			</div>
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['text_field']); !!}


		
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}

	
		
						  
 </form>
	</div>
	<?php 
	if($page_details['Method']==1){
	?>
	<div class="col-md-6 sideDiv">
	 <h5><i class="fa fa-upload bluecolor"></i> &nbsp; <label for="exampleInputEmail1">Bulk Upload</label>
		 <a href="{{ asset('public/pincode.csv') }}" download>Sample CSV</a></h5>
	 <form role="form" class="form-element" action="{{ route('vendor_pincode_assign_csv') }}" method="post" enctype="multipart/form-data">
			   @csrf
			   
		    
		<div class="form-group">
						
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['csv_select_field']); !!}
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}

						  
 </form>
	</div>
	<?php }?>
	
	
	</div>
</div>

@endsection
