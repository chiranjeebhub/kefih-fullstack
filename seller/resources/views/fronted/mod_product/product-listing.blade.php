@extends('fronted.layouts.app_new')
@section('pageTitle','Products')




@section('content') 
  <?php 
            $cuurent_url = Request::segment(1);
            $parameters = Request::segment(2);
			if(Request::segment(3)!=Request()->id)
			{
				$parameters_catname = Request::segment(3);
			}
			if(Request::segment(4)!=Request()->id)
			{
				$parameters_scatname = Request::segment(4);
			}
			
            $cat_id1 = Request()->cat_id;
            $pag=base64_decode($cat_id1);
	?>
	
		<?php   
$dt=DB::table('meta_tags')->where('page_id',$pag)->first();
if($dt){
?>
@section('pageTitle', @$dt->title)
@section('metaTitle', @$dt->title)
@section('metaKeywords', @$dt->keywords)
@section('metadescription', @$dt->description)
<?php } ?>
	
@section('breadcrum') 
@if($cuurent_url=='cat')
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<?php 
        $cats=array();
        $catsss=array();
   $categories = App\Category::
                   with('AllparentCats')
                ->where('isdeleted',0)
                ->where('id', base64_decode(@$cat_id1))
                ->where('status',1)
        ->first();
        if($categories){
             array_push($catsss,array('id'=>$categories->id,'name'=>$categories->name));
           $cats=App\Category::list_categories($categories->AllparentCats);
          }

   $cats1=App\Category::array_flatten($cats,array());

   foreach($cats1 as $key=>$c){
      if( ($key % 2)==0){
          array_push($catsss,array('id'=>$c,'name'=>$cats1[$key+1]));
      }
   }
   
   unset ($catsss[count($catsss)-1]);
   $final_cats=array_reverse($catsss);
        $last_index= count($final_cats)-1;
       $i=0;
      
    foreach($final_cats as $final){
        if($i!=$last_index){
            $url=route('cat_wise', [$final['name'],base64_encode($final['id'])]); 
        } else{
             $url="javascript:void(0)";
        }
      
?>
@if($i!=$last_index)
<a href="{{$url}}">{{ $final['name'] }}</a><i class="fa fa-long-arrow-right"></i>
@else
<a href="{{$url}}">{{ $final['name'] }}</a>
@endif

<?php $i++;}?>
@endif
@endsection     



<!-- @if($cuurent_url=='cat' || $cuurent_url=='brand')
<section class="productdetails-section">
	<div class="container">
        <div class="row">
        	<div class="about-txt whitebox categText">           
				@if($cuurent_url=='cat')
				<?php //$cat_id1=base64_decode($cat_id1);?>
				<p>{!!App\Products::categoryDescription($cat_id1)!!}</p> 
				@else
				<p>{!!App\Products::brandDescription($parameters,1)!!}</p> 
				@endif
            </div>
    	</div>
     </div>
</section>
@endif -->




<section class="prolisting-section">
    <div class="container-fluid">
        <div class="row">
			@include('fronted.includes.filter')
			
            <div class="col-xs-12 col-sm-{{(count($products) ==0)?'12':'8'}} col-md-{{(count($products) ==0)?'12':'9'}} col-lg-{{(count($products) ==0)?'12':'9'}}">
                <div class="bgwhite clearfix mr-btm-40">
                    <div class="pro-listing">
						
						<div class="slide-heading">
							<h6 class="fs20 fw600">{{ucfirst(urldecode(@$cat_trimmed))}}</h6>   
						</div>
					
                        
                        <div id="filterResponseData">
                        <ul class="row" >
							@foreach($products as $product)
							<?php 	if(@$product->color_id!='' && @$product->color_id!=0)
			{
				//$color_image=App\Products::getcolorNameAndCode('Colors',@$colors_img[0]['color_id']);
				$color_image=App\ProductImages::getConfiguredImages($product->id,$product->color_id);
				//echo @$color_image[0]['image'];die;
				//echo '<pre>';print_r($color_image); die;
				if(sizeof($color_image)>0){
				    	$prd_img=@$color_image[0]->image;
				    	$prd_img=@$color_image[0]['image'];
				    	$prd_img=App\Products::getproductImageUrl(1,$prd_img);
				    	
				} else{
				   	$prd_img=App\Products::getproductImageUrl(1,$product->default_image);
				}
			
				$prd_slider=$color_image;
				$flag=1;
			}else{
		$prd_img=App\Products::getproductImageUrl(1,$product->default_image);
		$prd_slider=$product::prdImages($product->id);
		$flag=2;
			} ?>
		
                            <li class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
                                <div class="product-grid8">
                                	<a href="{{$product::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}">
                                	<div class="product-image8">
									
									
									<?php  if(@$product->zoom_image){?>
									
				<img class="hoverprdimg" src="{{URL::to('/uploads/products')}}/{{$product->zoom_image}}">
									<?php } ?>
									
										<img <?php  if(@$product->zoom_image){?> class="simpleimg" <?php } ?> src="{{URL::to('/uploads/products')}}/{{$product->default_image}}">
									
										<!--<div class="product-image8">
											<img src="{{$prd_img}}">
										</div>-->
									
									</div></a>
                                        @if ($product->spcl_price!='')
                                        <div class="sale-box">
                                       
                                        <span>{{$product::offerPercentage($product->price,$product->spcl_price)}}% <em>off</em></span>
                                        
                                        
                                        </div>
                                        @endif
                                    <ul class="social">
										<li><a href="javascript:void(0)" data-tip="Quick View" title="Quick View" class="quickView" prd_id="{{$product->id}}"><i class="fa fa-eye"></i></a></li>
										<li><a href="javascript:void(0)" data-tip="Wishlist" title="Wishlist"><i class="fa fa-heart-o wishList" prd_id="{{$product->id}}"></i></a></li>
										<li><a href="javascript:void(0)" data-tip="Add to Cart" title="Add to Cart"
										class="addTocart"
											prd_page='0'
											url="{{$product::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}"
											prd_index='{{$product->id}}' 
											prd_id='{{$product->id}}'
											 prd_type="{{$product->product_type}}"
											size_require="{!!$product::Issize_requires($product->id)!!}"
											color_require="{!!$product::Iscolorrequires($product->id)!!}"
											size_id="{!!$product::getFirstattrId('Sizes',$product->id)!!}"
											color_id="{!!$product::getFirstattrId('Colors',$product->id)!!}"
										><i class="fa fa-shopping-cart"></i></a></li>										
                                        <!-- <li><a href="#squarespaceModal" data-toggle="modal" data-tip="Quick View" title="Quick View"><i class="fa fa-eye"></i></a></li>
                                        <li><a href="" data-tip="Wishlist" title="Wishlist"><i class="fa fa-heart-o"></i></a></li>
                                        <li><a href="" data-tip="Add to Cart" title="Add to Cart"><i class="fa fa-shopping-cart"></i></a></li> -->
                                    </ul>
                                    <div class="product-content">
                                        <h3 class="title"><a href="{{$product::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}">{{$product->name}} </a></h3>
										{!!App\Products::productRatingCounter($product->id)!!}									
										
										<div class="price">
                                                @if ($product->spcl_price!='')
                                                	<span><i class="fa fa-inr"></i> {{$product->price}}</span>
											<i class="fa fa-inr"></i>
											 <em id="prd_price_{{$product->id}}">{{$product->spcl_price}}</em>
                                                @else
                                                	<i class="fa fa-inr"></i>
                                                 <i id="prd_price_{{$product->id}}">{{$product->price}}</i>
                                                @endif
										
                                           
										</div>										
										
										<input type="hidden" name="qty" min="1" max="5" value="1" id="prd_qty_{{$product->id}}">
										<span id="back_response_msg_{{$product->id}}"></span>
										<button type="submit" value="submit"
										class="add-to-cart-btn addTocart hideCartButtton"
										prd_page='0'
										url="{{$product::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}"
										prd_index='{{$product->id}}' 
										prd_id='{{$product->id}}'
										prd_type="{{$product->product_type}}"
										size_require="{!!App\Products::Issize_requires($product->id)!!}"
										color_require="{!!App\Products::Iscolorrequires($product->id)!!}"
										size_id="{!!$product::getFirstattrId('Sizes',$product->id)!!}"
										color_id="{!!$product::getFirstattrId('Colors',$product->id)!!}"
										><i class="fa fa-shopping-cart"></i> Add to cart</button>
                                    </div>
                                </div>
							</li>
							@endforeach
							@if (count($products) ==0)
							<li><div class="text-center">No Product Found</div></li>
							@endif														
                        </ul>
                        	<?php echo $products->links();?>
                        	<script>
                	$(document).on('click', '.pagination a',function(event)
            {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var myurl = $(this).attr('href');
            var page=$(this).attr('href').split('page=')[1];
            SearchData(page);
            });
                        	</script>
                        
                        	</div>
                    </div>
                </div>
			</div>
		
        </div>
    </div>
</section>

@endsection


