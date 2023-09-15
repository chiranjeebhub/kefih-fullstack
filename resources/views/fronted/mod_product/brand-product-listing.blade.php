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


<?php //echo "test";exit;?>

<section class="wrap inr-wrap-tp">
<div class="container">
    <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="title">
                    <h4>Seller Info</h4> 
                </div>
                @if(!empty($vendorsdetail))
                <div class="selleryellowboxMain">
                    <div class="selleryellowbox">
                        <div class="brandLogo">
                        @if(!empty($vendorsdetail->logo))
                        
                        <img src="{{ asset('/uploads/vendor/company_logo/'.$vendorsdetail->logo) }}">

                        @else
                            <img src="{{ asset('public/fronted/images/brands2.png') }}">
                        @endif 

                            <p>{!! $vendorsdetail->about_us !!}</p>
                        </div>
                    </div>
                    
                    <div class="sellerinfoinner">
                        <div class="sellerinfotext">
                            <p>Seller Name</p>
                            <h3>{{$vendorsdetail->name }}</h3>
                        </div>
                        <div class="sellerinfotext">
                            <p>City and State</p>
                            <h3>{{$vendorsdetail->city }}{{($vendorsdetail->state)?' ,'.$vendorsdetail->state:'' }}</h3>
                        </div>
                        <div class="sellerinfotext">
                            <p>Products uploaded</p>
                            <h3>{{$totalProduct}}</h3>
                        </div>
                    </div>
                </div>
                @endif 
                
            </div>
        </div>




    
        <div class="row">
			@include('fronted.includes.brandProductListingFilter')
			
            <div class="col-xs-12 col-sm-{{(count($products) ==0)?'12':'9'}} col-md-{{(count($products) ==0)?'12':'9'}} col-lg-{{(count($products) ==0)?'12':'9'}} pdl0">
               
                <div class="shortby-listing">
                    <div class="row">
                        <div class="col-6 col-sm-7 col-md-9 col-lg-9">
                            <div class="title">
                                <h4>{{ucfirst(urldecode(@$cat_trimmed))}}</h4> 
                            </div>
                        </div>
                        {{-- 
                            <div class="col-6 col-sm-5 col-md-3 col-lg-3">
                                <div class="d-flex align-items-center">

                                <div class="form-group">
                                    <div class="select"><select class="form-ctrl form-control select-hidden">
                                        <option selected="">Sort by</option>
                                        <option>Low to High</option>
                                        <option>High to Low</option>
                                    </select><div class="select-styled">Sort by</div><ul class="select-options" style="display: none;"><li rel="Sort by">Sort by</li><li rel="Low to High">Low to High</li><li rel="High to Low">High to Low</li></ul></div>
                                    <div class="form_icon"><i class="fa fa-angle-down"></i></div>
                                </div>
                                </div>
                            </div>
                        --}}

                    </div>
                </div>
                
                        
                        <div id="filterResponseData">
                        <ul class="list-inline productList row" >
                            
                       
<?php 


$whole_data=array();
$position=1;
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $res=explode('/',$actual_link);
                    
?>
							@foreach($products as $product)
							<?php 
                            
                            /*if(@$product->color_id!='' && @$product->color_id!=0)
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
			}

            */

            if(@$product->color_id!='' && @$product->color_id!=0)
			{
				// $color_image=App\ProductImages::getConfiguredImages($product->id,$product->color_id);
				// if(sizeof($color_image)>0){
				//     	$prd_img=@$color_image[0]->image;
				//     	$prd_img=@$color_image[0]['image'];
				//     	$prd_img=App\Products::getproductImageUrl(1,$prd_img);
				// } else{
				//    	$prd_img=App\Products::getproductImageUrl(1,$product->default_image);
				// }
                $prd_img=App\Products::getproductImageUrl(1,$product->default_image);

				$flag=1;
			}else{
		$prd_img=App\Products::getproductImageUrl(1,$product->default_image);
			
				$flag=2;
			}


			array_push($whole_data,array(
                        "id"=> $product->id,
                        "name"=> $product->name,
                        "price"=> $product->spcl_price,
                        "brand"=> $product->brands_name,
                        "category"=> @$res[4],
                        "position"=> $position,
                        "list"=>  @$res[4]
			    ));
	      	$position++;
			?>
                            
                            
                            <li class="col-6 col-sm-6 col-md-4 col-lg-3">
                 <div class="item-box">
<div class="addtocart-btm-btn">
    <div class="d-flex justify-content-between mb-3">
    <div class="pro-icon-hover">
			<div class="share-icon"><a href="javascript:void(0);" class="shareproduct" data-url="{{App\Products::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}"><img src="{{ asset('public/fronted/images/share-icon.png') }}"/></a></div>
			</div>
            
    {{--
    <div class="size">
    <a href="#">XS</a>
    <a href="#">M</a>
    <a href="#">L</a>
    <a href="#">XL</a>
    <a href="#">XXL</a>
    </div>
    --}}

    </div>
    
    <input type="hidden" name="qty" min="1" max="5" value="1" id="prd_qty_{{$product->id}}">
        <span id="back_response_msg_{{$product->id}}"></span>
        @if ($product->qty!=0)

            <div class="twobtns">
                <!--<a href="javascript:void(0)" data-tip="Wishlist" title="Wishlist" class="btn btn-light btn-block">
                     <i class="fa fa-heart-o wishList" prd_id="{{$product->id}}"></i> 
                    @php 
                        $wishlisticon = App\Helpers\CommonHelper::productHeartCheck($product->id);
                    @endphp 
                    {!! $wishlisticon !!}
                </a>-->
                <button type="submit" value="submit"
                class="btn btn-outline-dark btn-block addTocart hideCartButtton"
                prd_page='0'
                url="{{$product::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}"
                prd_index='{{$product->id}}' 
                prd_id='{{$product->id}}'
                prd_type="{{$product->product_type}}"
                size_require="{!!App\Products::Issize_requires($product->id)!!}"
                color_require="{!!App\Products::Iscolorrequires($product->id)!!}"
                size_id="{!!$product::getFirstattrId('Sizes',$product->id)!!}"
                color_id="{!!$product::getFirstattrId('Colors',$product->id)!!}"
                >Add to cart</button>
            </div>

        @else
                                                                                
        <style>
            .outOfStcok {
                display: inline-block!important;
            }
        </style>
        <div class="twobtns">
        <!--<a class="btn btn-default" href="javascript:void(0)"><i class="fa fa-heart-o"></i></a>-->
        <button type="submit"
        class="btn btn-outline-dark btn-block addTocart outOfStcok">Add To Cart
        </button>
        </div>
        @endif()
    
                    <!--<div class="twobtns">
                         <a href="#" class="btn btn-outline-dark btn-block"> Add To Cart</a>	 
                </div>-->
                </div>
            <div class="thumbnail tmb">
                <a href="{{$product::getProductDetailUrl($product->name,$product->id,$product->size_id,$product->color_id)}}">
                    <div class="thumbnail tmb">


                    <?php 
                    
                    if($product['product_type']=='1')
                    {
                        $imagespathfolder='uploads/products/'.$product->vendor_id.'/'.$product['sku'];
                    }else{

                    $productskuid=DB::table('product_attributes')->where('product_id',$product->id)->first();

                    $imagespathfolder='uploads/products/'.$product->vendor_id.'/'.$productskuid->sku;
                    }
                    if($product->product_type=='3')
                    { 
                        $productimages=DB::table('product_configuration_images')->where('product_id',$product->id)->first();
                        if(!empty($productimages->product_config_image))
                        {
                        ?>
                        <img class="hoverprdimg" src="{{URL::to($imagespathfolder)}}/{{$productimages->product_config_image}}">
                         <?PHP }
                    }else{
                        $productimages=DB::table('product_images')->where('product_id',$product->id)->first();
                        ?>
                        @if(!empty($productimages))
                        <img class="hoverprdimg" src="{{URL::to($imagespathfolder)}}/{{$productimages->image}}">
                        @endif 

                       <?php
                        }
                    ?>
                        <!--<div class="product-image8">
                            <img src="{{$prd_img}}">
                        </div>-->
                    </div></a>
				
                </div>
                <div class="item-box-dec">
                    <?php 
                     $vendorbradename=DB::table('vendor_company_info')->where('vendor_id',$product->vendor_id)->first();
                    ?>
                <h5>{{ $vendorbradename->name}}</h5>
                    <h6><?php echo substr(strip_tags($product->short_description),0,150); ?> {{(!empty($product->short_description))?'...':''}}	</h6>
				<div class="row align-items-center">
                    <div class="col-8 col-sm-9 col-md-10 col-lg-10">
                       
                        @if ($product->price!='' && $product->price!=0)
                       
                            <p class="price">
                                <del><i class="fa fa-inr"></i>{{$product->price}}</del> 
                                <span class="offprice">{{$product::offerPercentage($product->price,$product->spcl_price)}}% Off</span> <br/> 
                                <i class="fa fa-inr"></i>{{$product->spcl_price}} 
                        
                                 @else
                                <i class="fa fa-rupee"></i> <del id="prd_price_{{$product->id}}">{{$product->spcl_price}}</del>
                        </p>
                            
                        @endif
                        </div>
                         <div class="col-4 col-sm-3 col-md-2 col-lg-2 pding-lft0 justify-content-end">
                        <p class="share-icon"><a href="#"><img src="{{ asset('public/fronted/images/share-icon.png') }}"/></a></p>
                        </div>
                    </div>
				
                </div>
            </div>
              </li>
                            
                            
                            
							@endforeach
							@if (count($products) ==0)
							<li><div class="text-center">No Product Found</div></li>
							@endif														
                        </ul>
                        	<?php 
                        
                        	
                        	
                        	echo $products->links();?>
                        	
                        	 <!--data layer-->
                                        <script>
                                        var dt =<?php echo json_encode($whole_data );?>;
                                        dataLayer.push({
                                        "ecommerce": {
                                        "currencyCode": "RS",
                                        "impressions":dt,
                                        "promoView": {
                                        "promotions": [{
                                        "id": "bts",
                                        "name": "Back To School",
                                        "creative": "HOME banner",
                                        "position": "right sidebar"
                                        }]
                                        }
                                        }
                                        });
                                        </script>
                        <!--data layer-->
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
                    {{--
                    <!--<div class="btn-width">
                    <a href="#" class="btn btn-outline-dark btn-block">SHOW MORE</a>
                    </div>-->
                    --}}
			</div>
		
        </div>
    </div>
</section>

@endsection


