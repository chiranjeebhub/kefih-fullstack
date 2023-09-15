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
			   <?php 
			
			 	$loginuser=auth()->guard('vendor')->user()->id;
				$categorysize=DB::table('sizechart')->where('vendor_id',$loginuser)->where('category_id',$categoryid)->first(); 
				
			   ?>
	<div class="row">
		<div class="col-sm-4">
			<div class="form-group">
				{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['size_chart_file_field']); !!}
				
				@if(!empty($categorysize) && $categorysize->sizechart!='')
				<a target="_blank"  href="<?php echo Config::get('constants.Url.public_url'); ?>uploads/category/size_chart/{{$categorysize->sizechart}}">Size Chart</a>
				@endif
				<span class="text-warning">(Size: <?php echo Config::get('constants.size.size_chart_min'); ?>kb – <?php echo Config::get('constants.size.size_chart_max'); ?>kb <?php echo Config::get('constants.size.size_chart_dimensions'); ?>)</span>
			</div>
			<div>
				@if(!empty($categorysize->sizechart))
			<a target="_blank"  href="<?php echo Config::get('constants.Url.public_url'); ?>uploads/category/size_chart/{{$categorysize->sizechart}}"><h3>Views Size Chart</h3></a>
			@endif
			</div>
		</div>
		
		<div class="col-sm-12">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
		</div>
	</div>
	</div>				  
 </form>

@endsection
