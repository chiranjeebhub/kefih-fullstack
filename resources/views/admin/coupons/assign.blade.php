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
    .SellerSelection{
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
    .SellerSelection{
        display:none;
    }
</style>
@elseif ($page_details['Form_data']['Form_field']['for_category_or_brand_or_product']['selected']==4)
<style>
   .categoryBox{
        display:none;
    } 
     .brandSelection{
        display:none;
    }
	.productSelection{
        display:none;
    }

    .SellerSelection{
        display:block;
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
    .SellerSelection{
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
                		
                	
                		
                		<div class="col-sm-3 typeSelection">
                			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['for_category_or_brand_or_product']); !!}
                		</div>
                		<div class="col-sm-3 categoryBox">
                			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['category_field']); !!}
                		</div>
                		<div class="col-sm-3 brandSelection">
                			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['brand_field']); !!}
                		</div>
                			<div class="col-sm-4 productSelection">
                		    <?php if($selectProd){
                		        echo "Selected product ::".$selectProd->name." (".$selectProd->city.")";
                		    }?>
                		</div>
						<div class="col-sm-12 productSelection">
                			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_field']); !!}
                		</div>

                        <div class="col-sm-12 SellerSelection">
                			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['seller_field']); !!}
                		</div>

                	</div>
                         
                </div>
                {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
                </div>
</form>
			
				
				 
@endsection
