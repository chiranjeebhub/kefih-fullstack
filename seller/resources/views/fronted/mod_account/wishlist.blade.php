@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">My Wishlist</a>
@endsection  
 
<section class="dashbord-section wishlstdv">
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
<div class="dashbordlinks">
<h6 class="fs18 fw600 mb20">My Account</h6>    
	               <ul>
						@include('fronted.mod_account.dashboard-menu')
					</ul>    
</div>    
</div>  
<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
<div class="dashbordtxt">
<h6 class="fs18 fw600 mb20">My Wishlist</h6> 
    
  <div class="db-2-main-com db-2-main-com-table">
							
							
							<div class="table wishlistlst" id="results">
							  <div class="theader">
								
								<div class="table_header">No.</div>
								<div class="table_header" style="width:50%;">Product Detail</div>
								<div class="table_header">Price</div>
                                <div class="table_header" style="width:17%">Cart</div>
								<div class="table_header">Remove</div>
							  </div>
							
							<?php $i=1;foreach($wishlist_data as $row){
								
							$attr_obj=new App\ProductAttributes(); 
							$attr_data=$attr_obj->getProductAttributes($row->id);
								
							?>
							  <div class="table_row" id="tableRow_{{$row->fld_wishlist_id}}">
								<div class="table_small">
								  <div class="table_cell">No</div>
								  <div class="table_cell"><?php echo $i++;?> </div>
								</div>
								<div class="table_small">
								  <div class="table_cell">Product Detail</div>
								  <div class="table_cell"><a href="{{App\Products::getProductDetailUrl($row->name,$row->id)}}"><img class="img-responsive" src="{{URL::to('/uploads/products')}}/{{$row->default_image}}" alt=""> <?php echo ucwords($row->name);?></a>
								  <br>
								 <!-- @if (@$attr_data[0]['size_id']!=0)-->
									<!--  <span>Size : -->
									<!--  <?php foreach($attr_data as $attr_data_row){?>-->
									<!--  {!!App\Products::getAttrName('Sizes',@$attr_data_row['size_id'])!!}-->
									<!--  <?php }?>-->
									<!--  </span>-->
								 <!--  <br>-->
									<!--@endif-->
									
									<!--@if (@$attr_data[0]['color_id']!=0)-->
									<!--  <span>Color : -->
									<!--  <?php foreach($attr_data as $attr_data_row){?>-->
									<!--  {!!App\Products::getAttrName('Colors',@$attr_data_row['color_id'])!!}-->
									<!--  <?php }?>-->
									<!--  </span>-->
									<!--@endif-->
									
									<br>
									    In Stock : 
                                        @if($row->qty>0)
                                        Available
                                        @else
                                        Not Available
                                        @endif
									
									
								  </div>
								</div>
								<div class="table_small">
								  <div class="table_cell">Price</div>
								  <div class="table_cell"> @if ($row->spcl_price!='')
										 <i class="fa fa-rupee"></i> <span id="prd_price_0">{{$row->spcl_price}}</span>
									<!--<del><i class="fa fa-rupee"></i>{{$row->price}}</del>
									<span class="offer_txt">{{App\Products::offerPercentage($row->price,$row->spcl_price)}}% <span>off</span></span>-->
										@else
										<i class="fa fa-rupee"></i> <span id="prd_price_0">{{$row->price}}</span>
										@endif
									</div>
								</div>
                                   <div class="table_small">
								  <div class="table_cell">Cart</div>
								  <div class="table_cell">
								  <!--<span class="hideCartButtton" >{{$row->price}}</span>-->
								  <input type="hidden" name="qty" min="1" max="5" value="1" id="prd_qty_{{$row->id}}">
								  <button type="submit" value="submit" class="orderbtn addTocart " 
									prd_page='0'
									url="{{App\Products::getProductDetailUrl($row->name,$row->id)}}"
									prd_index='{{$row->id}}' 
									prd_id='{{$row->id}}'
									size_require="{!!App\Products::Issize_requires($row->id)!!}"
									color_require="{!!App\Products::Iscolorrequires($row->id)!!}"
									size_id="{!!App\Products::getFirstattrId('Sizes',$row->id)!!}"
									color_id="{!!App\Products::getFirstattrId('Colors',$row->id)!!}"
									> <i class="fa fa-shopping-cart"></i> Add to cart</button> </div>
								</div>
								<div class="table_small">
								  <div class="table_cell">Remove</div>
								  <div class="table_cell">
                                    <!--<button class="buttonnone"><span> <i class="fa fa-refresh" aria-hidden="true"></i></span></button> -->
    <button class="buttonnone"><span> <i class="fa fa-trash-o delete deleteItemFromWishList"  	prd_id='{{$row->fld_wishlist_id}}' aria-hidden="true"></i></span></button>
                                    </div>
								</div>
							  </div>
							<?php } ?>
							 
							 
							</div>
							
						</div>  
     
    <!-- <h6 class="fs18 fw600 text-center">No Item in Your Wishlist......</h6> -->
</div>    
</div>     
</div>    
</div>    
    
</section>  
@endsection

            
@include('fronted.includes.addtocartscript')
    
