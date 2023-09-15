@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')

        @section('backButtonFromPage')
        <a href="{{ $page_details['back_route']}}" class="btn btn-default">Go Back</a>
        @endsection
<div class="col-sm-12">
<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
			   @csrf
		     
	<div class="row">
		<div class="col-sm-4">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['exibition_name']); !!}
		</div>
				<div class="col-sm-4">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['exibition_code']); !!}
		</div>
		<div class="col-sm-4">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['exibition_startdate']); !!}
		</div>
		<div class="col-sm-4">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['exibition_starttime']); !!}
		</div>
		<div class="col-sm-4">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['exibition_enddate']); !!}
		</div>
		<div class="col-sm-4">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['exibition_endtime']); !!}
		</div>
		
		<div class="col-sm-12">
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
		</div>
	</div>
						  
 </form>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
	$(function(){
    var dtToday = new Date();
    
    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();
    
    var maxDate = year + '-' + month + '-' + day;

    // or instead:
    // var maxDate = dtToday.toISOString().substr(0, 10);

    $('#startdate').attr('min', maxDate);
	$('#enddate').attr('min', maxDate);
	
});
</script>
@endsection

