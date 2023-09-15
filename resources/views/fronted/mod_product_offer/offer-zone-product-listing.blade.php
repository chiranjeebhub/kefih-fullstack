@extends('fronted.layouts.app_new')
@section('pageTitle','Products')
@section('content') 
<?php   
$dt=DB::table('meta_tags')->where('page_id',1)->first();
if($dt){
?>
@section('pageTitle', @$dt->title)
@section('metaTitle', @$dt->title)
@section('metaKeywords', @$dt->keywords)
@section('metadescription', @$dt->description)
<?php } ?>
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Offer Zone</a>
@endsection 

<section class="wrap inr-wrap-tp">
    <div class="container-fluid">
        <div class="row">	
            @php 
            $cat_data = App\Category::select('categories.*')        
                        ->where('categories.parent_id','=',1)
                        ->where('categories.status','=',1)
                        ->where('categories.isdeleted','=',0)
                        ->get()->toArray();
            @endphp 
            <!-- Filter Code Starting  -->
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                <div class="sidebar__inner">
                    <div class="filter-leftbar">
                        <div class="filter">
                            <h3>Filter by  <span id="account-btn"><i class="fa fa-navicon"></i></span>           
                            </h3>	  
                        </div>

                        <div id="mobile-show">	
                            <aside class="sidebar-wrapper">     
                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                    <?php
                                    if(count($cat_data)>0){
                                    ?>	

                        <div class="accordion-item">
                        <h2 class="accordion-header"><a href="#" class="accordion-button">Categories
                          </a>
                        </h2>
                        <ul class="category-list category-listroot list-unstyled">
                            <?php
                         
                            for($i=0;$i<count($cat_data);++$i){
                               
                            $cat_name = preg_replace('/\s+/', '-', strtolower($cat_data[$i]['name']));
                            $url=route('offer-zone-products', base64_encode($offerID)).'?cat='.base64_encode($cat_data[$i]['id']);?>
                           
                            <li>
                                <a href="{{$url}}"><?php echo ucwords($cat_data[$i]['name']);?> </a>
                               <?php 
                               //2nd level category 
                                $subcats = App\Category::GetSubcategory($cat_data[$i]['id']);                                
                               
                                if(!empty($subcats)){
                                ?>
                                <ul class="category-list1 list-unstyled">
                                <?php for($j=0;$j<count($subcats); $j++){
                                    $scat_name = preg_replace('/\s+/', '-', strtolower($subcats[$j]['name']));
                                    $surl=route('offer-zone-products', base64_encode($offerID)).'?cat='.base64_encode($subcats[$j]['id']);;?>
                                     <li>
                                <a href="{{$surl}}"><?php echo ucwords($subcats[$j]['name']);?></a>
                                <?php 
                                //3rd level category
                                $ssubcats = App\Category::GetSubcategory($subcats[$j]['id']);                                
                                
                                if(!empty($ssubcats)){
                                ?>
                                <ul class="category-list2 list-unstyled">
                                <?php for($k=0;$k<count($ssubcats); $k++){
                                    $sscat_name = preg_replace('/\s+/', '-', strtolower($ssubcats[$k]['name']));
                                    $ssurl=route('offer-zone-products', base64_encode($offerID)).'?cat='.base64_encode($ssubcats[$k]['id']);?>
                                     <li>
                                <a href="{{$ssurl}}"><?php echo ucwords($ssubcats[$k]['name']);?></a>
                                     </li>
                                     <?php } ?> 
                                </ul> 
                                <?php } ?>



                                     </li>
                                     <?php } ?> 
                                </ul> 
                                <?php } ?>

                            </li>		
                            <?php } ?> 
                        </ul>

                      </div>         
                    <?php }?>                                

                                 </div>
                            </aside>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Filter Code End  -->




        <div class="col-xs-12 col-sm-{{(count($products) ==0)?'12':'9'}} col-md-{{(count($products) ==0)?'12':'9'}} col-lg-{{(count($products) ==0)?'12':'9'}} pdl0">
               
                <div class="shortby-listing">
                    <div class="row">
                        <div class="col-6 col-sm-7 col-md-9 col-lg-9">
                            <div class="title">
                                <h4>{{ucfirst(urldecode(@$cat_trimmed))}}</h4> 
                            </div>
                        </div>                 

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
                            
                           

            if(@$product->color_id!='' && @$product->color_id!=0)
			{
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
                    $productskuid=DB::table('product_attributes')->where('product_id',$product->id)->first();
                    $baseimagespathfolder='uploads/products/'.$product->vendor_id.'/'.$product->sku;

                    $imagespathfolder='uploads/products/'.$product->vendor_id.'/'.$productskuid->sku;
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
                        <img class="hoverprdimg" src="{{URL::to($baseimagespathfolder)}}/{{$productimages->image}}">
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
                        
                        	
                        	
                        	echo $products->appends(request()->except('page'))->links();?>
                        	
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
                
                   
                
			</div>
		
        </div>
    </div>
</section>

@endsection


