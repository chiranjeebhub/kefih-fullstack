<section class="product-slider-section"> <div class="container-fluid"> 
<div class="slide-heading"> <h2>{{$slider_name}}</h2> <div class="txt-rgt view">
<a href="{{route('all_products',['type' => $slider_name, 'id' => $slider_type])}}">View All </a>
                </div></div> 
 <div class="owl-carousel owl-carousel1"> 
    @foreach($products as $product)
    <?php 
     if(@$product->color_id!='' && @$product->color_id!=0)
     {
         //$color_image=App\Products::getcolorNameAndCode('Colors',@$colors_img[0]['color_id']);
        //  $color_image=App\ProductImages::getConfiguredImages($product->id,$product->color_id);
        //  if(sizeof($color_image)>0){
        //          $prd_img=@$color_image[0]->image;
        //          $prd_img=@$color_image[0]['image'];
        //          $prd_img=App\Products::getproductImageUrl(1,$prd_img);
        //  } else{
        //         $prd_img=App\Products::getproductImageUrl(1,$product->default_image);
        //  }
        
         $prd_img=App\Products::getproductImageUrl(1,$product->default_image);
         $flag=1;
     }else{
 $prd_img=App\Products::getproductImageUrl(1,$product->default_image);
     
         $flag=2;
     }
    ?>
<div class="item">
     <div class="product-grid8"> <div class="product-image8"> 
     <a href="{{$product::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}"> 
     <div <?php  if(@$product->zoom_image){?> class="simpleimg" <?php } ?>>
               <img src="{{$prd_img}}" loading="lazy">
               </div> </a> </div>
                @if ($product->price!='' && $product->price!=0)
                    <div class="sale-box"> 
                     <span>{{App\Products::offerPercentage($product->price,$product->spcl_price)}}%<em>off</em></span>
                    </div>
                @endif()
               <ul class="social"> 
               <input type="hidden" name="qty" min="1" max="5" value="1" id="prd_qty_{{$product->id}}_s_{{$slider_type}}"> 
               <span id="back_response_msg_{{$product->id}}_s_{{$slider_type}}"></span> 
                <!-- <li><a href="javascript:void(0)" data-tip="Wishlist" title="Wishlist">
                       <i class="fa fa-heart-o wishList" prd_id="{{$product->id}}"></i>
                
                    
                    </a></li> -->
                       
                       </ul> <div class="product-content"> <h3 class="title"> <a href="{{App\Products::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}">{{$product->name}}</a></h3>
                       {!!App\Products::productRatingCounter($product->id)!!} @if ($product->spcl_price!='') <div class="price"> <span><i class="fa fa-rupee"></i> {{$product->price}}</span> 
                       <i class="fa fa-rupee"></i> <em id="prd_price_{{$product->id}}_s_{{$slider_type}}">{{$product->spcl_price}}</em></div> 
                       @else <div class="price"><i class="fa fa-rupee"></i> <em id="prd_price_{{$product->id}}_s_{{$slider_type}}">{{$product->price}}</em></div> @endif 
                       @if($product->qty!=0)
         
         
                        <div class="addtocart-btm-btn">
                            <div class="twobtns">
                                <a href="javascript:void(0)" data-tip="Wishlist" title="Wishlist" class="btn btn-light btn-block">
                                    <!-- <i class="fa fa-heart-o wishList" prd_id="{{$product->id}}"></i> -->
                                    @php 
                                     $wishlisticon = App\Helpers\CommonHelper::productHeartCheck($product->id);
                                    @endphp 
                                    {!! $wishlisticon !!}
                                
                                </a>
                                
                                <button type="submit" value="submit" class="btn btn-danger btn-block hideCartButtton addTocart" prd_page='0' class="" url="{{App\Products::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}" prd_index='{{$product->id}}_s_{{$slider_type}}'  prd_id='{{$product->id}}' size_require="{!!App\Products::Issize_requires($product->id)!!}"  prd_type="{{$product->product_type}}" color_require="{!!App\Products::Iscolorrequires($product->id)!!}" size_id="{!!App\Products::getFirstattrId('Sizes',$product->id)!!}" color_id="{!!App\Products::getFirstattrId('Colors',$product->id)!!}" data-tip="Add to Cart" title="Add to Cart" ><span>+</span> Add to cart</button>
                            </div>
                        </div>
                       
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
                       </div> </div> </div>@endforeach</div></div></section>

