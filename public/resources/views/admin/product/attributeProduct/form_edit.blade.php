@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
 @section('backButtonFromPage')
        <a href="{{ $page_details['back_route']}}" class="btn btn-default">Go Back</a>
        @endsection
@section('content')
<?php 
				$parameters = Request::segment(3);
			
				$prd = Request::segment(4);
				$parameters_level = base64_decode($parameters);
			
				$id = base64_decode($prd);
				
	
				?>
<style>
.hideElement{
	display:none;
}.pointer{
	cursor:pointer;
}
span.remove_atr {
    margin-top: 32px;
    position: absolute;
}
.colorImagesRow{
    /*height:100px;*/
}
table#productTable,table#up_sell_productTable,table#cross_sell_productTable {
    width: 100% !important;
}
</style>
<section class="product-nav-details">
    <div class="container">
        <div class="row">
            
                <div class="col-md-2">
				
                <div class=" ">
                    <!-- Nav tabs -->
                    <div class="vtabs customvtab">
					
                        <ul class="nav nav-tabs tabs-vertical" role="tablist">
						
						
	<li class="nav-item"> <a class="nav-link  <?php echo ($parameters_level==0)?'active show':''; ?>" data-toggle="tab" href="#general" role="tab" aria-expanded="true" aria-selected="false">General</a> </li>
	<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==1)?'active show':''; ?>" data-toggle="tab"  href="#price" role="tab" aria-expanded="false" aria-selected="true">Prices</a></li>
	<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==2)?'active show':''; ?>"  data-toggle="tab" href="#categories" role="tab" aria-expanded="false" aria-selected="true">Categories</a> </li>
	<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==3)?'active show':''; ?>" data-toggle="tab"  href="#attr" role="tab" aria-expanded="false" aria-selected="false">Attributes</a></li>
	
        @if (count($page_details["return_data"]["attr"])>0)
        <li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==4)?'active show':''; ?>"  data-toggle="tab" href="#images" role="tab" aria-expanded="false" aria-selected="true">Images</a> </li>
        @endif

	
	
	<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==5)?'active show':''; ?>"  data-toggle="tab" href="#inventory" role="tab" aria-expanded="false" aria-selected="true">Inventory</a> </li>
	<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==6)?'active show':''; ?>" data-toggle="tab"  href="#extraF" role="tab" aria-expanded="false" aria-selected="false">Extra Fields</a></li>
	<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==7)?'active show':''; ?>"  data-toggle="tab" href="#metaInfo" role="tab" aria-expanded="false" aria-selected="true">Meta Information</a> </li>

	<!--<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==8)?'active show':''; ?>"  data-toggle="tab" href="#related_product" role="tab" aria-expanded="false" aria-selected="true">Related Product</a> </li>-->
	<!--<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==9)?'active show':''; ?>"  data-toggle="tab" href="#up_sell" role="tab" aria-expanded="false" aria-selected="true">UP Sells</a> </li>-->
	<!--<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==10)?'active show':''; ?>"  data-toggle="tab" href="#cross_sell" role="tab" aria-expanded="false" aria-selected="true">Cross Sell</a> </li>-->

			</ul>
                        
                    </div>
                </div>                

            </div>
                <div class="col-md-10">
                <div class="box-body">
                <!-- Tab panes -->
							
                        <div class="tab-content">
						
                            <div class="tab-pane <?php echo ($parameters_level==0)?'active show':''; ?> " id="general" role="tabpanel" aria-expanded="true">
							<form role="form" action="{{route('edit_product', [base64_encode(0),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
							  @csrf
                                <div class="pad">
                                    <h4 class="skin-purple-light">General</h4>
                                    <div class="fields">
										<div class="row prodctthumprew">
											<div class="col-md-12 col-sm-12">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_name_field']); !!}   
											</div>
											<div class="col-md-12 col-sm-12">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_short_description_field']); !!}
											</div>
											<div class="col-md-12 col-sm-12 hideElement">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_long_description_field']); !!}
											</div>
											<!--<div class="col-sm-4 ">-->
											<!--	{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_gtin_field']); !!}-->
											<!--</div>-->
											<div class="col-md-3 col-sm-12 hideElement">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_sku_field']); !!}
											</div>
												<div class="col-md-3 col-sm-12 hideElement">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_code_field']); !!}
											</div>
											<div class="col-md-3 col-sm-12">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_weight_field']); !!}
											</div>
											<div class="col-sm-4">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_height_field']); !!}
											</div>
											
											<div class="col-sm-4">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_length_field']); !!}
											</div>
											<div class="col-sm-4">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_width_field']); !!}
											</div>
											<div class="col-md-3 col-sm-12">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_status_field']); !!}
											</div>
											<div class="col-md-3 col-sm-12 hideElement">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_visibility_field']); !!}
											</div>
											
											<div class="col-md-6 col-sm-12">
											 {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_image_field']); !!} 
											 <p class="text-warning">(Size: <?php echo Config::get('constants.size.product_img_min'); ?>kb – <?php echo Config::get('constants.size.product_img_max'); ?>kb <?php echo Config::get('constants.size.product_img_dimensions'); ?> )</p>

											{!! App\Helpers\CustomFormHelper::support_image('uploads/products',$page_details['Form_data']['Form_field']['product_image_field']['value']); !!} 

											</div>

  	<div class="col-md-6 col-sm-12">
											 {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_zoomimage_field']); !!} 
											<p class="text-warning">(Size: <?php echo Config::get('constants.size.product_img_min'); ?>kb – <?php echo Config::get('constants.size.product_img_max'); ?>kb <?php echo Config::get('constants.size.product_img_dimensions'); ?> )</p>
											
											{!! App\Helpers\CustomFormHelper::support_image('uploads/products',$page_details['Form_data']['Form_field']['product_zoomimage_field']['value']); !!} 
											

											</div>

										</div>
						                         
                                    </div>
									{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
                                </div>
								
								
							</form>
                            </div>

                            <div class="tab-pane <?php echo ($parameters_level==1)?'active show':''; ?> " id="price" role="tabpanel" aria-expanded="false">
                    <form role="form" action="{{route('edit_product', [base64_encode(1),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
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
										<div class="col-md-4 col-sm-12 hideElement">
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_spcl_from_date_field']); !!}
										</div>
										<div class="col-md-4 col-sm-12 hideElement">
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_spcl_to_date_field']); !!}
										</div>
										<div class="col-md-4 col-sm-12 hideElement">
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_tax_field']); !!}
										</div>
									</div>
				  
                                    </div>
								   {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
                                </div>
								
							</form>
                            </div>
                            
							<div class="tab-pane <?php echo ($parameters_level==2)?'active show':''; ?> " id="categories" role="tabpanel" aria-expanded="false">
						
									<form role="form" action="{{route('edit_product', [base64_encode(2),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
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
							<form role="form" action="{{route('edit_product', [base64_encode(3),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
							  @csrf
                                <div class="pad">
                                    <h4 class="skin-purple-light">Attributes</h4>
                                    <div class="fields mb15">
					                     <div class="atr_group">
				
								@foreach ($page_details["return_data"]["attr"] as $attr)
									{!! App\Helpers\CustomFormHelper::getBarcode($attr['barcode']); !!}
								<div class="row">
								<div class="col-md-2">
									 <label>Unisex Type</label>
									 <select class="form-control" name="unisex_type[]" id="unisex_type">
									     <option value="1" <?php echo ($attr['unisex_type']==1)?'selected':'';?> >Men</option>
									     <option value="2" <?php echo ($attr['unisex_type']==2)?'selected':'';?> >Women</option>
									 </select>
								</div>
								<div class="col-md-2">
									 {!! App\Helpers\CustomFormHelper::getColorHtml($page_details["Form_data"]["Form_field"]["product_color_field"],$attr['color_id']); !!}
								</div>
								<div class="col-md-2">
										 {!! App\Helpers\CustomFormHelper::getSizeHtml($page_details["Form_data"]["Form_field"]["product_size_field"],$attr['size_id']); !!}
								</div>
								
								<div class="col-md-2">
									 {!! App\Helpers\CustomFormHelper::getQtyHtml($page_details["Form_data"]["Form_field"]["product_atr_qty_field"],$attr['qty']); !!}
								</div>
									<div class="col-md-2" style="display:none;">
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
						  
							<span class="btn btn-success pointer add_sexatr"><i class="fa fa-plus"></i> Add Color & Size</span>
						</div>
                                     
                                    </div>
									{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
                                </div>
								
							</form>
                            </div>
							
                            <div class="tab-pane <?php echo ($parameters_level==4)?'active show':''; ?> " id="images" role="tabpanel" aria-expanded="false">
								<!-- <div id="color_img_errors" class="text-center text-danger"></div> -->
								<form role="form" class="color_image_form" action="{{route('edit_product', [base64_encode(4),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
									@csrf   
											<div id="removedImages">
								    
								</div>								
								<div class="pad">
								<h4 class="skin-purple-light">Images</h4>
								<span class="text-warning">(Size: <?php echo Config::get('constants.size.product_img_min'); ?>kb – <?php echo Config::get('constants.size.product_img_max'); ?>kb <?php echo Config::get('constants.size.product_img_dimensions'); ?> )</span>

                                    <div class="fields">
                                        <?php 
                                        $color_index=0;
                                        ?>
                                    @foreach ($page_details["return_data"]["color_attr"] as $attr)
<?php 
if($attr['color_id']!='' && $attr['color_id']!=0){
$color_data=App\Products::getcolorNameAndCode('Colors',$attr['color_id']);

$color_images=DB::table('product_configuration_images')
                        ->where('product_id',$id)
                        ->where('color_id',$attr['color_id'])
                        ->get();
?>
                                    <div class="row colorImagesRow">
                                     
            <input type="hidden" name="color_ids[{{$color_index}}]" value="{{$attr['color_id']}}">
                                       <div class="col-md-2">
                                           <span class="badge badge-primary"
                                       style="background-color:{{$color_data->color_code}};" title="{{$color_data->name}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                       </div>
                                       
                                    <div class="col-md-3">
                                  
								<input type="file" id="{{$attr['color_id']}}" class="input_color_img" multiple name="color_images[{{$color_index}}][]" onchange='multi_image_preview(event);'>
								<span id="{{$attr['color_id']}}_preview"></span>
							
                                    </div>
                                    <div class="col-md-7">
                                        
										 <div id="recipeCarousel{{$attr['color_id']}}" class="recipeCarousel carousel slide" data-ride="carousel" data-type="multi" data-interval="false" >
											<div class="carousel-inner" role="listbox">
												<?php $a = 1; ?>
												@foreach ($color_images as $row)
												<div class="carousel-item <?php echo ($a==1)?'active':''; ?>">
													<div class="col-md-2">
													 <img class="img-fluid" src="{{URL::to('/uploads/products')}}/{{$row->product_config_image}}"><?php if($a != 1){ ?><i class="fa fa-trash text-red removeColorImags" imageId="{{$row->id}}" aria-hidden="true"></i>
														<?php  }$a++;  ?>
													</div>
												</div>
												@endforeach
											</div>
											<a class="carousel-control-prev" href="#recipeCarousel{{$attr['color_id']}}" role="button" data-slide="prev">
												<span class="carousel-control-prev-icon" aria-hidden="true"></span>
												<span class="sr-only">Previous</span>
											</a>
											<a class="carousel-control-next" href="#recipeCarousel{{$attr['color_id']}}" role="button" data-slide="next">
												<span class="carousel-control-next-icon" aria-hidden="true"></span>
												<span class="sr-only">Next</span>
											</a>
										</div>

										<script>
										$('#recipeCarousel{{$attr['color_id']}}').carousel({
										item: 5,
										pause: true,
    									interval: false
										})
										</script>
                                        
                                    </div>
                                       
                                    </div>
                                     <?php 
                                        $color_index++;
                                        ?>
                                          <?php }?>
                                    @endforeach
                                    
                                    </div>
							
									{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
								</div>
								
								</form>
                            </div>
							
                            <div class="tab-pane <?php echo ($parameters_level==5)?'active show':''; ?> " id="inventory" role="tabpanel" aria-expanded="false">
                                <form role="form" action="{{route('edit_product', [base64_encode(5),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
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
									<form role="form" action="{{route('edit_product', [base64_encode(6),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
									@csrf
									<div class="pad">
									<h4 class="skin-purple-light">Extra Fields</h4>
									<div class="fields">
									<div class="row">
										<div class="col-md-4 col-sm-12 ">
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_hsn_field']); !!}
										</div>
										<div class="col-md-4 col-sm-12 hideElement">
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_brands_field']); !!}
										</div>
										<div class="col-md-4 col-sm-12">
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_material_field']); !!}
										</div>
										
										<div class="col-md-4 col-sm-12">
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_deleivery_days_field']); !!}
										</div>
										<div class="col-md-4 col-sm-12">
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_return_days_field']); !!}
										</div>
										
										<div class="col-md-4 col-sm-12">
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_shipping_charge_stock_field']); !!}
										</div>
										
										<div class="col-md-4 col-sm-12">
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_rewards_point_field']); !!}
										</div>
											<div class="col-md-4 col-sm-12 hideElement">
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_bar_code_field']); !!}
										</div>
                        <div class="col-md-4 col-sm-12">
                        {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_sizechart_field']); !!} 
                        <p class="text-warning">(Size: <?php echo Config::get('constants.size.product_img_min'); ?>kb – <?php echo Config::get('constants.size.product_img_max'); ?>kb <?php echo Config::get('constants.size.product_img_dimensions'); ?> )</p>
                        
                        {!! App\Helpers\CustomFormHelper::support_image('uploads/sizechart',$page_details['Form_data']['Form_field']['product_sizechart_field']['value']); !!} 
                        </div>
                    
									</div>
									
									@if(count($filters)>0)
            <div class="filter_available">
                <h4><label for="exampleInputEmail1">Filters</label></h4>
                    <div class="row appended_filter">
            @foreach($filters as $filter)
                <div class="col-md-3">
                    <div class="form-group">
                    <label for="exampleInputEmail1">{{$filter->name}}</label>
                    <select class="form-control" name="filter[{{$filter->id}}]">
                         <option value="">Select </option>
                        <?php 
                       
                        
                        $extra_options=DB::table('filter_values')
                        ->where('filter_id',$filter->id)
                        ->get();
                         foreach($extra_options as $extra_option){
                             
                              $issetted=DB::table('product_filters')
                        ->where('filters_input_value',$extra_option->id)
                        ->where('product_id',$id)
                        ->first();
                       ?>
        <option value="{{$extra_option->id}}"
         <?php  echo ($issetted)?"selected":"" ?>
        >{{$extra_option->filter_value}}</option>
       
                        <?php }?>
                   
                    
            
                    <?php ?>
                    </select>
                    </div>
                </div>
            
            @endforeach
                    </div>
            </div>
								@endif
								
					
									</div>
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
									</div>
									</form>
									</div>
									
							<div class="tab-pane <?php echo ($parameters_level==7)?'active show':''; ?> " id="metaInfo" role="tabpanel" aria-expanded="false">                               
									<form role="form" action="{{route('edit_product', [base64_encode(7),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
											@csrf                                  
										<div class="pad">
										<h4 class="skin-purple-light">Meta Information</h4>
										<div class="fields">
											<div class="row">
												<div class="col-md-12 col-sm-12">
													{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_meta_title_field']); !!}
												</div>
												<div class="col-md-6 col-sm-12">
													
													{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_meta_description_field']); !!}	
												</div>
												<div class="col-md-6 col-sm-12">
												
								        			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_meta_keyword_field']); !!}	
												</div>
											</div>
										
										</div>
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
										</div>
									</form>
                            </div>
							
							
							<div class="tab-pane <?php echo ($parameters_level==8)?'active show':''; ?> " id="related_product" role="tabpanel" aria-expanded="false">                               
									<form role="form" action="{{route('edit_product', [base64_encode(8),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
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
									<form role="form" action="{{route('edit_product', [base64_encode(9),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
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
									<form role="form" action="{{route('edit_product', [base64_encode(10),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
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

<style>
	
	.recipeCarousel .carousel-item .col-md-2{ display: inline-block; width: 15%; padding: 5px; border: solid 1px #f2f1f1; margin: 0 3px; height: 100px; text-align: center; margin-bottom: 10px; }
	.recipeCarousel .carousel-item .col-md-2 img{ height: auto; width: auto; max-height: 100%; max-width: 100%; }
	.recipeCarousel{ display: inline-block; width: 100%; margin-bottom: 5px; }    
	
	.recipeCarousel .carousel-item .col-md-2 .fa-trash{ position: absolute; top: 0; right: 0; }
	.carousel-control-next-icon, .carousel-control-prev-icon {
		font-size: 26px !important;
		width: 25px !important;
		height: 25px !important;
	}
	.carousel-control-prev, .carousel-control-next{ width: 5% !important; min-width: auto !important; padding: 3px; }
	.carousel-control-prev-icon, .carousel-control-next-icon{ background-color: #0c6cd5; padding: 5px; opacity: 1;}
	
</style>

<link rel="stylesheet" href=" {{ asset('public/css/imageuploadify.min.css') }}">

<script type="text/javascript" src="{{ asset('public/js/imageuploadify.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/js/jsLists.min.js') }}"></script>
<script type="text/javascript">

// var img_el = document.getElementsByClassName("input_color_img");
// const arr_el_imgs = Array.from(img_el);

// arr_el_imgs.forEach(CheckImg);

// function CheckImg(item, index) {
// //console.log('Item:'+item+' value:'+index);

// 	item.onchange = function(){		
// 		var x = document.querySelectorAll('.color_image_form .input_color_img');
// 		const imgs = Array.from(x);
// 		imgs.forEach(isEmpty);
// 	};
// }

// function isEmpty(item, index){

// 			var error_el = document.getElementById('color_img_errors');			
// 			if(item.value == ''){		
// 				//console.log("Image is required for all color");	
// 				error_el.innerHTML="Image is required for all color";
// 				document.getElementById("myBtn").disabled = true;
// 			}else{
// 				error_el.innerHTML="";
// 				//console.log(item.value);
// 			}
// 			console.log(error_el);
// }






	

    
	

    $(document).ready(function() {
        $('input[type="file"]').imageuploadify();
    });
	JSLists.applyToList('simple_list', 'ALL');

	


</script>	

@include('admin.includes.product_script') 

<script>	

	/*$('.carousel .carousel-item').each(function(){
		var next = $(this).next();
		if (!next.length) {
		next = $(this).siblings(':first');
		}
		next.children(':first-child').clone().appendTo($(this));

		if (next.next().length>0) {
		next.next().children(':first-child').clone().appendTo($(this));
		}
		if (next.next().next().length>0) {
		next.next().next().children(':first-child').clone().appendTo($(this));
		}
		if (next.next().next().next().length>0) {
		next.next().next().next().children(':first-child').clone().appendTo($(this));
		}
		if (next.next().next().next().next().length>0) {
		next.next().next().next().next().children(':first-child').clone().appendTo($(this));
		}
		else {
		  $(this).siblings(':first').children(':first-child').clone().appendTo($(this));
		}
	});*/
	
	$('.carousel .carousel-item').each(function() {
	  var item = $(this);
	  item.siblings().each(function(index) {
		if (index < 5) {
		  $(this).children(':first-child').clone().appendTo(item);
		}
	  });
	});
	
	$('.carousel-control-next').click(function() {
	  $(this).blur();
	  $(this).parent().find('.carosel-item').first().insertAfter($(this).parent().find('.carosel-item').last());
	});
	$('.carousel-control-prev').click(function() {
	  $(this).blur();
	  $(this).parent().find('.carosel-item').last().insertBefore($(this).parent().find('.carosel-item').first());
	});

</script>

@endsection
