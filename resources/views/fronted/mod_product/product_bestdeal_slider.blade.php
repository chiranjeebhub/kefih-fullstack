
<section class="wrap wrap-top0">
		<div class="container">
        <div class="owl-carousel owl-carousel1 mb-4">
            
            @foreach($products as $product)
    <?php 

$productskuid=DB::table('product_attributes')->where('product_id',$product->id)->first();
$baseimagespathfolder='uploads/products/'.$product->vendor_id.'/'.$product->sku;

$imagespathfolder='uploads/products/'.$product->vendor_id.'/'.$productskuid->sku;
     if(@$product->color_id!='' && @$product->color_id!=0)
     {

        $productimages=DB::table('product_configuration_images')->where('product_id',$product->id)->first();
         //$color_image=App\Products::getcolorNameAndCode('Colors',@$colors_img[0]['color_id']);
        //  $color_image=App\ProductImages::getConfiguredImages($product->id,$product->color_id);
        //  if(sizeof($color_image)>0){
        //          $prd_img=@$color_image[0]->image;
        //          $prd_img=@$color_image[0]['image'];
        //          $prd_img=App\Products::getproductImageUrl(1,$prd_img);
        //  } else{
        //         $prd_img=App\Products::getproductImageUrl(1,$product->default_image);
        //  }
        //$prd_img=$imagespathfolder.'/'.$product->default_image;
        // $prd_img=App\Products::getproductImageUrl(1,$product->default_image);
         $flag=1;
     }else{
       // $prd_img=$imagespathfolder.'/'.$product->default_image;
 //$prd_img=App\Products::getproductImageUrl(1,$product->default_image);
     
         $flag=2;
     }
    

    ?>
            
        <div class="item">
            <div class="item-box">
<div class="addtocart-btm-btn">
    <div class="twobtns">
        
        <input type="hidden" name="qty" min="1" max="5" value="1" id="prd_qty_{{$product->id}}_s_{{$slider_type}}"> 
               <span id="back_response_msg_{{$product->id}}_s_{{$slider_type}}"></span> 
                    <a class="btn wishList  btn-outline-dark btn-block" href="javascript:void(0)" prd_id="{{$product->id}}" data-tip="Wishlist" title="Wishlist">
                       
                        Add To Wishlist <i class="ml-1 fa fa-heart-o" prd_id="{{$product->id}}"></i>
                    
                    </a>
                </div>
    <div class="d-flex justify-content-between mb-3">
    <div class="pro-icon-hover">
			<!--<a href="#QuickviewModal" data-bs-toggle="modal"><i class="fa fa-heart-o"></i></a>-->
                <div class="share-icon"><a href="javascript:void(0);" class="shareproduct" data-url="{{App\Products::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}"><img src="{{ asset('public/fronted/images/share-icon.png') }}"/></a></div>
			</div>
            {{-- 
    <div class="size">
    <a href="#" class="active">XS</a>
    <a href="#">M</a>
    <a href="#">L</a>
    <a href="#">XL</a>
    <a href="#">XXL</a>
    </div>
    --}}
    </div>
    
                    
                </div>
            <div class="thumbnail tmb">
<?php
            if($product->product_type=='3')
                { 
                    $productimages=DB::table('product_configuration_images')->where('product_id',$product->id)->first();
                    if(!empty($productimages->product_config_image))
                        { ?>
                            <a href="{{App\Products::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}">
                             <img class="hoverprdimg" src="{{URL::to($imagespathfolder)}}/{{$productimages->product_config_image}}">
                        </a>
                        <?php }
                }else{
                    $productimages=DB::table('product_images')->where('product_id',$product->id)->first();
                    if(!empty($productimages->image))
                        { ?>
<a href="{{App\Products::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}">
                    <img class="hoverprdimg" src="{{URL::to($baseimagespathfolder)}}/{{$productimages->image}}">
                        </a>
               <?php } 
            }?>
                
				
                </div>
                <div class="item-box-dec">
                <?php 
                     $vendorbradename=DB::table('vendor_company_info')->where('vendor_id',$product->vendor_id)->first();
                     
                    ?>
                <h5><a href="{{App\Products::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}">{{ $vendorbradename->name}}</a></h5>
                    <h6>{!!$product->short_description!!}</h6>
				<div class="row align-items-center">
                    <div class="col-8 col-sm-9 col-md-10 col-lg-10">
                        
                        
                        
                        <p class="price">
                            
                            @if ($product->spcl_price!='') 
                            <i class="fa fa-inr"></i>{{$product->spcl_price}}
                            <del id="prd_price_{{$product->id}}_s_{{$slider_type}}"><i class="fa fa-inr"></i>{{$product->price}}</del> 
                            
                            @else <i class="fa fa-rupee"></i> <del id="prd_price_{{$product->id}}_s_{{$slider_type}}">{{$product->price}}</del>
                            
                            @endif
                            
                            @if ($product->price!='' && $product->price!=0)
                            <span class="offprice">{{App\Products::offerPercentage($product->price,$product->spcl_price)}}% Off</span>@endif()
                        </p>
                         
                        
                        
                       @if($product->qty!=0)
                        
                        @else
						<style>
						    .outOfStcok {
                                display: inline-block!important;
                            }
						</style>
						<button type="submit"
                        class="add-to-cart-btn addTocart ml30 outOfStcok">
                        <!--<i class="fa fa-shopping-cart"></i> -->Out of stock
                        </button>
                        @endif()
                        
                        </div>
                         <div class="col-4 col-sm-3 col-md-2 col-lg-2 pding-lft0 justify-content-end">
                        <p class="share-icon"><a href="#"><img src="{{ asset('public/fronted/images/share-icon.png') }}"/></a></p>
                        </div>
                    </div>
				
                </div>
            </div>
            </div>
            
            @endforeach
            
            </div>
            <!--<div class="btn-width">
            <a href="{{route('all_products',['type' => $slider_name, 'id' => $slider_type])}}" class="btn btn-outline-dark btn-block">SHOW MORE</a>
            </div>-->
			</div>
    </section>