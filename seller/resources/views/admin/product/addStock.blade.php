@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
	
<form role="form" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
							  @csrf
                                <div class="pad">
                                    <div class="fields">
										<div class="row">
										    @if($Product->product_type==1)
										    <div class="col-sm-6">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_qty_field']); !!}  
											</div>
										    @else
										
										
										<div class="fields mb15">
					                     <div class="atr_group">
						
								@foreach ($page_details["return_data"]["attr"] as $attr)
							
								{!! App\Helpers\CustomFormHelper::getBarcode($attr['barcode']); !!}
								<div class="row">
								    <div class="col-md-2">
									 {!! App\Helpers\CustomFormHelper::getColorHtml($page_details["Form_data"]["Form_field"]["product_color_field"],$attr['color_id']); !!}
								</div>
								<div class="col-md-2">
										 {!! App\Helpers\CustomFormHelper::getSizeHtml($page_details["Form_data"]["Form_field"]["product_size_field"],$attr['size_id']); !!}
								</div>
								
								<div class="col-md-2">
									 {!! App\Helpers\CustomFormHelper::getQtyHtml($page_details["Form_data"]["Form_field"]["product_atr_qty_field"],$attr['qty']); !!}
								</div>
									<div class="col-md-2">
									 {!! App\Helpers\CustomFormHelper::getPriceHtml($page_details["Form_data"]["Form_field"]["product_atr_price_field"],$attr['price']); !!}
								</div>
								
								<div class="col-md-2">
									 {!! App\Helpers\CustomFormHelper::getQtyHtml($page_details["Form_data"]["Form_field"]["product_barcode_field"],$attr['barcode']); !!}
								</div>
									
									<div class="col-md-2">
										<div class="form-group">
										<span class="remove_atr pointer">
										<i class="fa fa-trash text-red"></i></span>
										</div>
									</div>
								</div>
								@endforeach
					
						</div>
						<div class=" ">
							<span class="btn btn-success pointer add_atr"><i class="fa fa-plus"></i> Add Color & Size</span>
						</div>
                                     
                                    </div>
										    @endif
											
											
										</div>
						                     
                                    </div>
									{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
                                </div>
								
								
							</form>
				@include('admin.includes.product_script') 
@endsection
