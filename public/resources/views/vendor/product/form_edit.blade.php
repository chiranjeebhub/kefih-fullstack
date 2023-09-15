@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
<?php 
				$parameters = Request::segment(3);
			
				$prd = Request::segment(4);
				$parameters_level = base64_decode($parameters);
			
				$id = base64_decode($prd);
				
	
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
</style>
<section class="product-nav-details">
    <div class="container">
        <div class="row">
            
                <div class="col-md-3">
				

                
                <div class=" ">
                    <!-- Nav tabs -->
                    <div class="vtabs customvtab">
					
                        <ul class="nav nav-tabs tabs-vertical" role="tablist">
						
						
	<li class="nav-item"> <a class="nav-link  <?php echo ($parameters_level==0)?'active show':''; ?>" data-toggle="tab" href="#general" role="tab" aria-expanded="true" aria-selected="false">General</a> </li>
	<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==1)?'active show':''; ?>" data-toggle="tab"  href="#price" role="tab" aria-expanded="false" aria-selected="true">Prices</a></li>
	<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==2)?'active show':''; ?>"  data-toggle="tab" href="#categories" role="tab" aria-expanded="false" aria-selected="true">Categories</a> </li>
	<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==3)?'active show':''; ?>" data-toggle="tab"  href="#attr" role="tab" aria-expanded="false" aria-selected="false">Attributes</a></li>
	<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==4)?'active show':''; ?>"  data-toggle="tab" href="#images" role="tab" aria-expanded="false" aria-selected="true">Images</a> </li>
	<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==5)?'active show':''; ?>"  data-toggle="tab" href="#inventory" role="tab" aria-expanded="false" aria-selected="true">Inventory</a> </li>
	<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==6)?'active show':''; ?>" data-toggle="tab"  href="#extraF" role="tab" aria-expanded="false" aria-selected="false">Extra Fields</a></li>
	<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==7)?'active show':''; ?>"  data-toggle="tab" href="#metaInfo" role="tab" aria-expanded="false" aria-selected="true">Meta Information</a> </li>
	<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==8)?'active show':''; ?>"  data-toggle="tab" href="#related_product" role="tab" aria-expanded="false" aria-selected="true">Related Product</a> </li>
	<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==9)?'active show':''; ?>"  data-toggle="tab" href="#up_sell" role="tab" aria-expanded="false" aria-selected="true">UP Sells</a> </li>
	<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==10)?'active show':''; ?>"  data-toggle="tab" href="#cross_sell" role="tab" aria-expanded="false" aria-selected="true">Cross Sell</a> </li>

			</ul>
                        
                    </div>
                </div>                

            </div>
                <div class="col-md-9">
                <div class="box-body">
                <!-- Tab panes -->
							
                        <div class="tab-content">
						
                            <div class="tab-pane <?php echo ($parameters_level==0)?'active show':''; ?> " id="general" role="tabpanel" aria-expanded="true">
							<form role="form" action="{{route('edit_product_vdr', [base64_encode(0),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
							  @csrf
                                <div class="pad">
                                    <h4 class="skin-purple-light">General</h4>
                                    <div class="fields">
										<div class="row">
											<div class="col-md-12 col-sm-12">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_name_field']); !!}   
											</div>
											<div class="col-md-12 col-sm-12">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_short_description_field']); !!}
											</div>
											<div class="col-md-12 col-sm-12">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_long_description_field']); !!}
											</div>
											<div class="col-md-3 col-sm-12">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_sku_field']); !!}
											</div>
											<div class="col-md-3 col-sm-12">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_code_field']); !!}
											</div>
											<div class="col-md-3 col-sm-12">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_weight_field']); !!}
											</div>
											<div class="col-md-3 col-sm-12">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_status_field']); !!}
											</div>
											<div class="col-md-3 col-sm-12">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_visibility_field']); !!}
											</div>
											
												<div class="col-md-3 col-sm-12">
											 {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_image_field']); !!} 
											</div>
											
												<div class="col-md-3 col-sm-12">
											{!! App\Helpers\CustomFormHelper::support_image('uploads/products',$page_details['Form_data']['Form_field']['product_image_field']['value']); !!} 
											</div>

  

										</div>
						                         
                                    </div>
									{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
                                </div>
								
								
							</form>
                            </div>

                            <div class="tab-pane <?php echo ($parameters_level==1)?'active show':''; ?> " id="price" role="tabpanel" aria-expanded="false">
                    <form role="form" action="{{route('edit_product_vdr', [base64_encode(1),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
							  @csrf                               
							   <div class="pad">
                                    <h4 class="skin-purple-light">Prices</h4>
                                    <div class="fields">
                                    <div class="row">
										<div class="col-md-4 col-sm-12">
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_price_field']); !!} 
										</div>
										<div class="col-md-4 col-sm-12">
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_spcl_price_field']); !!}
										</div>
										<div class="col-md-4 col-sm-12">
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_spcl_from_date_field']); !!}
										</div>
										<div class="col-md-4 col-sm-12">
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_spcl_to_date_field']); !!}
										</div>
										<div class="col-md-4 col-sm-12">
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_tax_field']); !!}
										</div>
									</div>
				  
                                    </div>
								   {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
                                </div>
								
							</form>
                            </div>
                            
							<div class="tab-pane <?php echo ($parameters_level==2)?'active show':''; ?> " id="categories" role="tabpanel" aria-expanded="false">
						
									<form role="form" action="{{route('edit_product_vdr', [base64_encode(2),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
									@csrf
										<div class="pad">
										   <h4 class="skin-purple-light">Categories</h4>
										<div class="simpleListContainer clearfix">
											<div class="row">
												<ul id="simple_list">
												{!! App\Helpers\CommonHelper::getChildsTreeView(1,$page_details["return_data"]["product_category"]); !!}
												</ul>
											</div>
										</div>
										</div>
									
									{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
									</form>
                            </div>
							
							<div class="tab-pane <?php echo ($parameters_level==3)?'active show':''; ?> " id="attr" role="tabpanel" aria-expanded="true">
							<form role="form" action="{{route('edit_product_vdr', [base64_encode(3),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
							  @csrf
                                <div class="pad">
                                    <h4 class="skin-purple-light">Attibutes</h4>
                                    <div class="fields mb15">
					                     <div class="atr_group">
						
								@foreach ($page_details["return_data"]["attr"] as $attr)
								<div class="row">
								<div class="col-md-4">
										 {!! App\Helpers\CustomFormHelper::getSizeHtml($page_details["Form_data"]["Form_field"]["product_size_field"],$attr['size_id']); !!}
								</div>
								<div class="col-md-4">
									 {!! App\Helpers\CustomFormHelper::getColorHtml($page_details["Form_data"]["Form_field"]["product_color_field"],$attr['color_id']); !!}
								</div>
								<div class="col-md-3">
									 {!! App\Helpers\CustomFormHelper::getQtyHtml($page_details["Form_data"]["Form_field"]["product_atr_qty_field"],$attr['qty']); !!}
								</div>
									<div class="col-md-1">
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
									{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
                                </div>
								
							</form>
                            </div>
							
                            <div class="tab-pane <?php echo ($parameters_level==4)?'active show':''; ?> " id="images" role="tabpanel" aria-expanded="false">
								
								<form role="form" action="{{route('edit_product_vdr', [base64_encode(4),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
									@csrf   
											<?php $i=0; ?>
										@foreach ($page_details["return_data"]["product_images"] as $img)
											<input type="hidden" value="{{$img['image']}}" multiple name="product_images[]" id="prd_image_id_<?php echo $i;?>">
											<?php $i++; ?>
										@endforeach									
								<div class="pad">
								 <h4 class="skin-purple-light">Images</h4>
								<div class=" mb15">
								<input type="file" accept="image/*" multiple name="images[]">
								</div>
									{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
								</div>
								
								</form>
                            </div>
							
                            <div class="tab-pane <?php echo ($parameters_level==5)?'active show':''; ?> " id="inventory" role="tabpanel" aria-expanded="false">
                                <form role="form" action="{{route('edit_product_vdr', [base64_encode(5),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
									@csrf
								<div class="pad">
                                    <h4 class="skin-purple-light">Inventory</h4>
                                    <div class="fields">
										<div class="row">
											<div class="col-md-6 col-sm-12">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_manage_stock_field']); !!}
											</div>
											<div class="col-md-6 col-sm-12">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_qty_field']); !!}
											</div>
											<div class="col-md-6 col-sm-12">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_qty_for_out_stock_field']); !!}
											</div>
											<div class="col-md-6 col-sm-12">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_stock_availability_field']); !!}
											</div>
										</div>
									    
                                    </div>
									{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
                                </div>
								</form>
                            </div>
							
							
							<div class="tab-pane <?php echo ($parameters_level==6)?'active show':''; ?> " id="extraF" role="tabpanel" aria-expanded="false">
									<form role="form" action="{{route('edit_product_vdr', [base64_encode(6),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
									@csrf
									<div class="pad">
									<h4 class="skin-purple-light">Extra Fields</h4>
									<div class="fields">
									<div class="row">
										<div class="col-md-4 col-sm-12">
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_hsn_field']); !!}
										</div>
										<div class="col-md-4 col-sm-12">
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_brands_field']); !!}
										</div>
										<div class="col-md-4 col-sm-12">
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_material_field']); !!}
										</div>
										<div class="col-md-4 col-sm-12">
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_deleivery_days_field']); !!}
										</div>
										
										
										<div class="col-md-4 col-sm-12">
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_shipping_charge_stock_field']); !!}
										</div>
									</div>
					
									</div>
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
									</div>
									</form>
									</div>
									
							<div class="tab-pane <?php echo ($parameters_level==7)?'active show':''; ?> " id="metaInfo" role="tabpanel" aria-expanded="false">                               
									<form role="form" action="{{route('edit_product_vdr', [base64_encode(7),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
											@csrf                                  
										<div class="pad">
										<h4 class="skin-purple-light">Meta Information</h4>
										<div class="fields">
											<div class="row">
												<div class="col-md-12 col-sm-12">
													{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_meta_title_field']); !!}
												</div>
												<div class="col-md-6 col-sm-12">
													<span>&nbsp;</span>
													{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_meta_description_field']); !!}	
												</div>
												<div class="col-md-6 col-sm-12">
													<span>Maximum 255 chars</span>	
								        			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_meta_keyword_field']); !!}	
												</div>
											</div>
										
										</div>
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
										</div>
									</form>
                            </div>
							
							
							<div class="tab-pane <?php echo ($parameters_level==8)?'active show':''; ?> " id="related_product" role="tabpanel" aria-expanded="false">                               
									<form role="form" action="{{route('edit_product_vdr', [base64_encode(8),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
											@csrf                                  
										<div class="pad">
										<h4 class="skin-purple-light">Related Product</h4>
										<div class="fields">
										<div class="table-responsive">
										
										
							<table>
							<tr>
							<td>
							<select id='related_product_searchByVisibillity' class="form-control">
							<option value=''>Select Visibillity</option>
							<option value='1'>Not visible individualy</option>
							<option value='2'>Catalog</option>
							<option value='3'>Search</option>
							<option value='4'>Catalog,Search</option>
							</select>
							</td>
							<td>
							<select id='related_product_searchByStatus' class="form-control">
							<option value=''>Select Status</option>
							<option value='1'>Enabled</option>
							<option value='0'>Disabled</option>
							</select>
							</td>
							
							<td>
							<input type="text" id="related_product_searchByname" placeholder="Search By Name" class="form-control">
							</td>
							<td>
							 {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_related_is_shown_field']);!!}
							</td>
							</tr>
							</table>
							
											
				  <table id="productTable" class="table table-bordered table-striped">
					<thead>
						<tr>
						       <th><input type="checkbox" name="product_checkbox" value="0" class="product_checkbox"></th>
								<th>Name</th>
								<th>Status</th>
								<th>Visibility</th>
								

							
						</tr>
					</thead>
					
					<tbody>
					
					
					</tbody>
					
				  </table>
				</div>
				
										</div>
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
										</div>
									</form>
                            </div>
							
							
							<div class="tab-pane <?php echo ($parameters_level==9)?'active show':''; ?> " id="up_sell" role="tabpanel" aria-expanded="false">                               
									<form role="form" action="{{route('edit_product_vdr', [base64_encode(9),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
											@csrf                                  
										<div class="pad">
										<h4 class="skin-purple-light">Up Sell</h4>
										<div class="fields">
										<div class="table-responsive">
										
										
											<table>
											<tr>
											<td>
											<select id='up_sell_product_searchByVisibillity' class="form-control">
											<option value=''>Select Visibillity</option>
											<option value='1'>Not visible individualy</option>
											<option value='2'>Catalog</option>
											<option value='3'>Search</option>
											<option value='4'>Catalog,Search</option>
											</select>
											</td>
											<td>
											<select id='up_sell_product_searchByStatus' class="form-control">
											<option value=''>Select Status</option>
											<option value='1'>Enabled</option>
											<option value='0'>Disabled</option>
											</select>
											</td>

											<td>
											<input type="text" id="up_sell_product_searchByname" placeholder="Search By Name" class="form-control">
											</td>
											<td>
											 {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_up_sell_is_shown_field']);!!}
											</td>
											</tr>
											</table>

											  <table id="up_sell_productTable" class="table table-bordered table-striped">
												<thead>
													<tr>
														   <th><input type="checkbox" name="product_checkbox" value="0" class="product_checkbox"></th>
															<th>Name</th>
															<th>Status</th>
															<th>Visibility</th>



													</tr>
												</thead>

												<tbody>


												</tbody>

											  </table>
										</div>
				
										</div>
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
										</div>
									</form>
                            </div>
							
							<div class="tab-pane <?php echo ($parameters_level==10)?'active show':''; ?> " id="cross_sell" role="tabpanel" aria-expanded="false">                               
									<form role="form" action="{{route('edit_product_vdr', [base64_encode(10),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
											@csrf                                  
										<div class="pad">
										<h4 class="skin-purple-light">Cross Sell</h4>
										<div class="fields">
										<div class="table-responsive">
										
										
							<table>
							<tr>
							<td>
							<select id='cross_sell_product_searchByVisibillity' class="form-control">
							<option value=''>Select Visibillity</option>
							<option value='1'>Not visible individualy</option>
							<option value='2'>Catalog</option>
							<option value='3'>Search</option>
							<option value='4'>Catalog,Search</option>
							</select>
							</td>
							<td>
							<select id='cross_sell_product_searchByStatus' class="form-control">
							<option value=''>Select Status</option>
							<option value='1'>Enabled</option>
							<option value='0'>Disabled</option>
							</select>
							</td>
							<td>
							<input type="text" id="cross_sell_product_searchByname" placeholder="Search By Name" class="form-control">
							</td>
							<td>
							 {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_cross_sell_is_shown_field']);!!}
							</td>
							</tr>
							</table>
							
				  <table id="cross_sell_productTable" class="table table-bordered table-striped">
					<thead>
						<tr>
						       <th><input type="checkbox" name="product_checkbox" value="0" class="product_checkbox"></th>
								<th>Name</th>
								<th>Status</th>
								<th>Visibility</th>
								

							
						</tr>
					</thead>
					
					<tbody>
					
					
					</tbody>
					
				  </table>
				</div>
				
										</div>
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
										</div>
									</form>
                            </div>
							
                                                     
                        </div>
						
                </div>
            </div>
            
        </div>
    </div>
</section>

<link rel="stylesheet" href=" {{ asset('public/css/imageuploadify.min.css') }}">

<script type="text/javascript" src="{{ asset('public/js/imageuploadify.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/js/jsLists.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('input[type="file"]').imageuploadify();
    });
	JSLists.applyToList('simple_list', 'ALL');
</script>	

@include('admin.includes.product_script') 

@endsection
