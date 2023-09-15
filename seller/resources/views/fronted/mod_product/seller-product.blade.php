@extends('fronted.layouts.app_new')
@section('pageTitle','All Seller')
@section('content')   
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href=" {{App\Products::getProductDetailUrl($product_details->name,$product_details->id)}}">{{$product_details->name}}</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">More Seller</a>
@endsection   
<section class="seller-listing-section border-bottom">
    <div class="container">
    <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="seller-header">
    <h6 class="fw600 fs20">All Sellers</h6>    
    </div>    
    </div> 
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="seller-header text-right">
    <h6 class="fw600 fs16">{{$product_details->name}}</h6>
    <!--<p><span>4.5</span> (319)</p>    -->
    </div>    
    </div>     
    </div>    
    </div>    
    </section>
    
    
    <section class="seller-details-section">
    <div class="container">
    <div class="row">
       <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="seller-table">
   <div class="db-2-main-com db-2-main-com-table packagetbl sellertbl">
							
							
							<div class="table" id="results">
							  <div class="theader">
								
								<div class="table_header">Seller</div>
								<div class="table_header">Price</div>
								
								<div class="table_header">&nbsp;</div>
							  </div>
								
							
                                <?php 
                                
                                foreach($all_vendors as $row){
                                ?>
                                <div class="table_row">
								<div class="table_small">
								  <div class="table_cell">Seller</div>
								  <div class="table_cell">{{$row->public_name}} <span>4.2 <i class="fa fa-star"></i></span></div>
								</div>
								<div class="table_small">
								  <div class="table_cell">Price</div>
								   <div class="table_cell">
								       ₹
                                        @if ($row->spcl_price!='')
                                        {{$row->spcl_price}}
                                        <b id="prd_price_{{$row->id}}">{{$row->spcl_price}}</b>
                                        <del>{{$product->price}}</del>{!! App\Products::ofr_per($row->price,$row->spcl_price); !!}% off
                                        @else
                                        <b id="prd_price_{{$row->id}}">{{$row->price}}</b>
                                        @endif
								       
                                           
								        
								        
								        </div>
								</div>
								
								<div class="table_small">
								  <div class="table_cell">&nbsp;</div>
								  <div class="table_cell">
								      <input type="hidden" name="qty" min="1" max="5" value="1" id="prd_qty_{{$row->id}}">
								      <button type="submit" class="selleraddcart addTocart"
                                        prd_page='0'
                                        url="{{App\Products::getProductDetailUrl($row->name,$row->id)}}"
                                        prd_index='{{$row->id}}' 
                                        prd_id='{{$row->id}}'
                                        size_require="{!!App\Products::Issize_requires($row->id)!!}"
                                        color_require="{!!App\Products::Iscolorrequires($row->id)!!}"
                                        size_id="{!!App\Products::getFirstattrId('Sizes',$row->id)!!}"
                                        color_id="{!!App\Products::getFirstattrId('Colors',$row->id)!!}">
								      <i class="fa fa-shopping-cart "></i> 
								      Add to cart </button> <button type="submit" class="sellernuynow buyNow"
								       prd_page='0'
                                        url="{{App\Products::getProductDetailUrl($row->name,$row->id)}}"
                                        prd_index='{{$row->id}}' 
                                        prd_id='{{$row->id}}'
                                        size_require="{!!App\Products::Issize_requires($row->id)!!}"
                                        color_require="{!!App\Products::Iscolorrequires($row->id)!!}"
                                        size_id="{!!App\Products::getFirstattrId('Sizes',$row->id)!!}"
                                        color_id="{!!App\Products::getFirstattrId('Colors',$row->id)!!}">
								      <i class="fa fa-cart-plus"></i> Buy Now </button> </div>
								</div>
							  </div>
                                <?php }?>
                                
                                

                                </div>

                                </div>
    </div>
           
           

    </div>    
    </div>    
    </div>
    
    </section>

@endsection

@include('fronted.includes.addtocartscript')
