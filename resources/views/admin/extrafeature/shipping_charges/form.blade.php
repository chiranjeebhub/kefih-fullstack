@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')


@if($page_details['method']==1)
<style>
    .typeSelection{
        display:none; 
    }
</style>
@if($page_details['Form_data']['Form_field']['for_category_or_brand']['selected']==0)
<style>
    .brandSelection{
        display:none;
    }
    
</style>
@else
<style>
    .categorySelection{
        display:none;
    }
    
</style>
@endif

@else
<style>
    .brandSelection{
        display:none;
    }
    
</style>
@endif
<form role="form" class="form-element multi_delete_form" action="{{$page_details['Action_route']}}" method="post">
  @csrf
                <div class="pad">
                <!--<h4 class="skin-purple-light">General</h4>-->
                <div class="fields">
                	<div class="row">
                		<div class="col-sm-12">
{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['offer_name']); !!}  
{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['shipping_charges']); !!}
<!-- {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['cod_charges']); !!} -->
                		</div>
                		
                	
                	</div>
                         
                </div>
                {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
                </div>
</form>
			
				
				 
@endsection
