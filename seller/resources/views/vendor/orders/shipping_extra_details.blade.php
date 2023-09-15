@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
 @section('backButtonFromPage')
    <a href="javascript:void(0)" class="btn btn-default goBack">Go Back</a>
    @endsection
@section('content')
<?php 
$order_details_id = Request::segment(3);
$order_details_id = base64_decode($order_details_id);
?>
<style>
.hideElement{
	display:block;
}.pointer{
	cursor:pointer;
}
span.remove_atr {
    margin-top: 32px;
    position: absolute;
}
table#productTable,table#up_sell_productTable,table#cross_sell_productTable {
    width: 100% !important;
}
.hideElement{display:none;}

</style>
<section class="product-nav-details">
    <div class="container">
        <div class="row">
                <div class="col-md-9">
                <div class="box-body">
                <!-- Tab panes -->
							
                        <div class="tab-content">
                          
							<form role="form" action="{{ route('vendor_order_shipping',base64_encode($order_details_id)) }}" method="post" enctype="multipart/form-data">
							  @csrf
							  <input type="hidden" value="{{$page_details['Grand_Total']}}" name ="grand_total" />
                                <div class="pad">
                                    <h4 class="skin-purple-light">Shipping Info</h4>
                                    <div class="fields">
										<div class="row">
										 
											<div class="col-sm-3">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['phy_weight']); !!}
											</div>
											
											<div class="col-sm-3">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['ship_length']); !!}
											</div>
											
											<div class="col-sm-3">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['ship_width']); !!}
											</div>
											
											<div class="col-sm-3">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['ship_height']); !!}
												<input type="hidden" name="select_check" value="<?php echo $multiple_order_detail_id;?>">
											</div>
											
											<div class="col-sm-6">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['package_description']); !!}
											</div>
											
										</div>
											
										<div class="row">	
											<div class="col-sm-4">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
											</div>
											
											
											
										</div>
			   
                                    </div>
                                </div>
								
								
							</form>
                          
						                      
                        </div>
						
                </div>
            </div>
            
        </div>
    </div>
</section>
@endsection

