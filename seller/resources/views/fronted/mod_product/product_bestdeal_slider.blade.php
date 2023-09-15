<section class="product-slider-section"> <div class="container"> 
<div class="slide-heading text-center"> <h2><span>{{$slider_name}}</span> Products </h2> </div> 
<div class="pro-listing"> <div> <ul class="row"> @foreach($products as $product)
<li class="col-sm-4 col-md-3">
     <div class="product-grid8"> <div class="product-image8"> 
     <a href="{{$product::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}"> 
     <?php  if(@$product->zoom_image){?> <div class="hoverprdimg">
          <img class=" " src="{{URL::to('/uploads/products')}}/{{$product->zoom_image}}" loading="lazy"> 
          </div> <?php } ?> <div <?php  if(@$product->zoom_image){?> class="simpleimg" <?php } ?>>
               <img src="{{URL::to('/uploads/products')}}/{{$product->default_image}}" loading="lazy">
               </div> </a> </div> <div class="sale-box"> 
               <span>{{App\Products::offerPercentage($product->price,$product->spcl_price)}}%<em>off</em></span>
               </div> <ul class="social"> 
               <input type="hidden" name="qty" min="1" max="5" value="1" id="prd_qty_{{$product->id}}_s_{{$slider_type}}"> 
               <span id="back_response_msg_{{$product->id}}_s_{{$slider_type}}"></span> 
               <li><a href="javascript:void(0)" data-tip="Quick View" title="Quick View" class="quickView" prd_id="{{$product->product_id}}">
                   <i class="fa fa-eye"></i></a></li> <li><a href="javascript:void(0)" data-tip="Wishlist" title="Wishlist">
                       <i class="fa fa-heart-o wishList" prd_id="{{$product->id}}"></i></a></li>
                       <li><a href="javascript:void(0)" prd_page='0' class="addTocart" url="{{App\Products::getProductDetailUrl($product->name,$product->id)}}" prd_index='{{$product->id}}_s_{{$slider_type}}'  prd_id='{{$product->id}}' size_require="{!!App\Products::Issize_requires($product->id)!!}" color_require="{!!App\Products::Iscolorrequires($product->id)!!}" size_id="{!!App\Products::getFirstattrId('Sizes',$product->id)!!}" color_id="{!!App\Products::getFirstattrId('Colors',$product->id)!!}" data-tip="Add to Cart" title="Add to Cart"><i class="fa fa-shopping-cart"></i></a></li> 
                       </ul> <div class="product-content"> <h3 class="title"> <a href="{{App\Products::getProductDetailUrl($product->name,$product->id)}}">{{$product->name}}</a></h3>
                       {!!App\Products::productRatingCounter($product->id)!!} @if ($product->spcl_price!='') <div class="price"> <span><i class="fa fa-inr"></i> {{$product->price}}</span> 
                       <i class="fa fa-inr"></i> <em id="prd_price_{{$product->id}}_s_{{$slider_type}}">{{$product->spcl_price}}</em></div> 
                       @else <div class="price"><i class="fa fa-inr"></i> <em id="prd_price_{{$product->id}}_s_{{$slider_type}}">{{$product->price}}</em></div> @endif <button type="submit" value="submit" class="add-to-cart-btn hideCartButtton addTocart" prd_page='0' class="" url="{{App\Products::getProductDetailUrl($product->name,$product->id)}}" prd_index='{{$product->id}}_s_{{$slider_type}}'  prd_id='{{$product->id}}' size_require="{!!App\Products::Issize_requires($product->id)!!}" color_require="{!!App\Products::Iscolorrequires($product->id)!!}" size_id="{!!App\Products::getFirstattrId('Sizes',$product->id)!!}" color_id="{!!App\Products::getFirstattrId('Colors',$product->id)!!}" data-tip="Add to Cart" title="Add to Cart" ><i class="fa fa-shopping-cart"></i> Add to cart</button> </div> </div> </li>@endforeach</ul></div></div></div></section>

