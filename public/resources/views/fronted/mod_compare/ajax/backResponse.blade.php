 <div class="row">
              <div class="col-md-3">
                <div class="content-txt">
                <h3>Products</h3>
                </div>
            </div>
            <?php 
            foreach($products as $product){
            ?>
                <div class="col-md-3">
                    <div class="box-shadow">
                    <div class="closebtn RemovecompareProduct" prd_id='{{$product->id}}'>✕</div>
                    <div class="compare-box">
                    {!! App\Helpers\CustomFormHelper::support_image('uploads/products',$product->default_image); !!}  
                    </div>
                    <div class="content-txt">
                    <h6><a href="{{$product::getProductDetailUrl($product->name,$product->id)}}">{{$product->name}} </a></h6>
                        <div class="price"> Rs
                        @if ($product->spcl_price!='')
                        <i id="prd_price_{{$product->id}}">{{$product->spcl_price}}</i>
                        
                        <span>{{$product->price}}</span><aside>{{$product::offerPercentage($product->price,$product->spcl_price)}}% off</aside>
                        @else
                        <i id="prd_price_{{$product->id}}">{{$product->price}}</i>
                        @endif
                        </div>   
                    </div>
                    </div>
                </div>
            <?php } ?>
            
            
            <?php 
              if(sizeof($products)<1){
              ?>
              <div class="col-md-3">
                  <div class="box-shadow">
                <div class="compare-blnk">
                    <img src="images/compare-blank.png" alt="">    
                </div>
                <div class="content-txt">                    
                    <h4>Add a product </h4>
                     <div class="row">                         
                        <div class="col-md-12">
                             <div class="form-group">
                                  <select class="form-control custom-select" id="sel1">
                                      <option>Choose Brand</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                  </select>
                            </div> 
                        </div>
                            <div class="col-md-12">
                             <div class="form-group">
                                  <select class="form-control custom-select" id="sel1">
                                    <option>Choose a Product</option>  
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                  </select>
                            </div> 
                        </div>
                    </div> 
                </div>
              </div>
            </div> 
            <?php } ?>
            
            <?php 
              if(sizeof($products)<2){
              ?>
              <div class="col-md-3">
                  <div class="box-shadow">
                <div class="compare-blnk">
                    <img src="images/compare-blank.png" alt="">    
                </div>
                <div class="content-txt">                    
                    <h4>Add a product </h4>
                     <div class="row">                         
                        <div class="col-md-12">
                             <div class="form-group">
                                  <select class="form-control custom-select" id="sel1">
                                      <option>Choose Brand</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                  </select>
                            </div> 
                        </div>
                            <div class="col-md-12">
                             <div class="form-group">
                                  <select class="form-control custom-select" id="sel1">
                                    <option>Choose a Product</option>  
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                  </select>
                            </div> 
                        </div>
                    </div> 
                </div>
              </div>
            </div> 
            <?php } ?>
            
              <?php 
              if(sizeof($products)<3){
              ?>
              <div class="col-md-3">
                  <div class="box-shadow">
                <div class="compare-blnk">
                    <img src="images/compare-blank.png" alt="">    
                </div>
                <div class="content-txt">                    
                    <h4>Add a product </h4>
                     <div class="row">                         
                        <div class="col-md-12">
                             <div class="form-group">
                                  <select class="form-control custom-select" id="sel1">
                                      <option>Choose Brand</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                  </select>
                            </div> 
                        </div>
                            <div class="col-md-12">
                             <div class="form-group">
                                  <select class="form-control custom-select" id="sel1">
                                    <option>Choose a Product</option>  
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                  </select>
                            </div> 
                        </div>
                    </div> 
                </div>
              </div>
            </div> 
            <?php } ?>
        </div>
        
      
            
         <div class="row">
                <div class="col-md-3">
                    <div class="compare-product-details">
                        <h6>Ratings & Reviews</h6>
                    </div>    
                 </div>
              
            <?php 
            foreach($products as $product){
            ?>
            <div class="col-md-3">
            <div class="compare-product-details">
            {!!App\Products::productRatingCounter($product->id)!!}
            <h1>
             @if(App\Products::productRating($product->id)>0)
                     {{App\Products::productRating($product->id)}}
                    @endif
                    Ratings &amp; 
                    
                     @if(App\Products::productReviews($product->id)>0)
                     {{App\Products::productReviews($product->id)}}
                    @endif
                   Reviews
            
            </h1>
            <span><a href="{{route('product_review',base64_encode($product->id))}}">All reviews</a> </span>
            </div>    
            </div>
            <?php 
            }
            ?>
            </div>
           <div class="row">
                <div class="col-md-3">
                    <div class="compare-product-details">
                        <h6>Highlights</h6>
                    </div>    
                 </div>
                  <?php 
            foreach($products as $product){
                $prd_detail=$product;
            ?>
            <div class="col-md-3">
                    <div class="compare-product-details">
                        <p>{!!$prd_detail->short_description!!}</p>
                       
                        
                    </div>  
                      
                 </div>
                  
            <?php  }?>
              
               
            </div>
        <div class="row">
                <div class="col-md-3">
                    <div class="compare-product-details">
                        <h6>Seller</h6>
                    </div>    
                 </div>
                   <?php 
            foreach($products as $product){
                $prd_detail=$product;
            ?>
              <div class="col-md-3">
                    <div class="compare-product-details">
                    	@if ($product->vendor_id!=0)
					
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<span>{!!$product->getProductsVendor()!!}</span>
							 {!!App\Vendor::getVendorRating($product->vendor_id)!!}
							 @if($prd_detail->delivery_days!=0)
						Deliver in {{$prd_detail->delivery_days}} days
						@endif

						@if($prd_detail->shipping_charges!=0)
						<br>Shipping Charges : <i class="fa fa-rupee"></i> {{$prd_detail->shipping_charges}}
						@endif
						</div>
						@endif	
						@include('fronted.mod_product.sub_views.more_seller')
                        
                    </div>    
                 </div>
                 <?php } ?>
               
            </div>
            
             <div class="row">
                <div class="col-md-3">
                    <div class="compare-product-details">
                        <h6>Variants Available</h6>
                    </div>    
                 </div>
                  <?php 
            foreach($products as $product){
            ?>
              <div class="col-md-3">
                    <div class="compare-product-details">
                     @include('fronted.mod_product.sub_views.products_attributes_listing_page')
                    </div>    
                 </div>
                 <?php } ?>
            </div>
            
        <div class="row">
            <div class="col-md-12">
            <div class="generalbox">
              <h6>General Info</h6>  
            </div>
            </div>
        </div>
           <div class="row">
               
                <div class="col-md-3">
                    <div class="generalbox">
                        
                    </div>    
                 </div>
                 
                 
                  <?php 
            foreach($products as $product){
                $prd_detail=$product;
            ?>
              <div class="col-md-3">
                    <div class="generalbox">
                    {!!$prd_detail->short_description!!}
                    </div>    
                 </div>
                  <?php } ?>
              
            </div>
            @include('fronted.includes.addtocartscript')