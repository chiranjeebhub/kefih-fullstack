@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')


@if ($page_details['Form_data']['Form_field']['for_category_or_brand_or_product']['selected']==1)
<style>
   .categoryBox{
        display:block;
    } 
     .brandSelection{
        display:none;
    }
	.productSelection{
        display:none;
    }
</style>
@elseif ($page_details['Form_data']['Form_field']['for_category_or_brand_or_product']['selected']==2)
<style>
   .categoryBox{
        display:none;
    } 
     .brandSelection{
        display:block;
    }
	.productSelection{
        display:none;
    }
</style>
@else
<style>
   .categoryBox{
        display:none;
    } 
     .brandSelection{
        display:none;
    }
	.productSelection{
        display:block;
    }
</style>
@endif

<form role="form" class="form-element multi_delete_form" action="{{$page_details['Action_route']}}" method="post">
  @csrf
                <div class="pad">
                <!--<h4 class="skin-purple-light">General</h4>-->
                <div class="fields">
                	<div class="row">
                		
                	
                		
                		<div class="col-sm-3 typeSelection">
                			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['for_category_or_brand_or_product']); !!}
                		</div>
                		
                	</div>
                         
                </div>
                {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
                </div>
</form>
			
				
				 
@endsection
