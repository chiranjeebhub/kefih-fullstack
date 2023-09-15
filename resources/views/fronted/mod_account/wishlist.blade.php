@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">My Wishlist</a>
@endsection  
 
<section class="dashbord-section wishlstdv">
    <div class="container">
        <div class="row"> 
            <div class="col-lg-12 col-sm-12 col-md-12 col-12 pt-4 pb-4">
                <h2 class="heading">My Wishlist</h2>
                <div class="db-2-main-com db-2-main-com-table">
                                        <div class="table wishlistlst">
                                          <div class="theader">

                                            <div class="table_header">No.</div>
                                            <div class="table_header" style="width: 18%;">Image</div>
                                            <div class="table_header">Product Detail</div>
                                            <div class="table_header">Price</div>
                                            <div class="table_header" style="width:17%">Cart</div>
                                            <div class="table_header">Remove</div>
                                          </div>

                                        <?php $i=1;foreach($wishlist_data as $row){

                                            $attr_obj=new App\ProductAttributes(); 
                                        $attr_data=$attr_obj->getProductAttributes($row->id);
                                        $size_id = $color_id = 0;
                                        $size_id = $attr_data[0]['size_id'];
                                        $color_id = $attr_data[0]['color_id'];

                                        $imagespathfolder='uploads/products/'.$row->vendor_id.'/';
                                        $pathSKU = $row->sku;
                                        if($row->product_type == 3){
                                          $pathSKU = $attr_data[0]['sku'];
                                        }
                                        $imagespathfolder='uploads/products/'.$row->vendor_id.'/'.$pathSKU;
                                        $prdURL = ($row->product_type == 1)?App\Products::getProductDetailUrl($row->name,$row->id):App\Products::getProductDetailUrl($row->name,$row->id,$size_id ,$color_id);
                                        $prd_img = '';

                                        if($color_id!='' && $row->product_type==3 )
                                        {                              
                                          $color_image=App\ProductImages::getConfiguredImages($row->id,$color_id,0);
                                          $prd_img=URL::to($imagespathfolder).'/'.$color_image[0]['image'];
                                        }else{
                                          $prd_slider=App\Products::prdImages($row->id);
                                          $prd_img=URL::to($imagespathfolder).'/'.$prd_slider[0]['image'];
                                        }

                                        ?>
                                          <div class="table_row" id="tableRow_{{$row->fld_wishlist_id}}">
                                            <div class="table_small orderidmrgn">
                                              <div class="table_cell">No</div>
                                              <div class="table_cell"><?php echo $i++;?> </div>
                                            </div>

                                              <div class="table_small">
                                              <div class="table_cell">Image</div>
                                              <div class="table_cell"><a href="{{$prdURL}}">
                                                <img class="img-responsive" src="{{$prd_img}}" alt="">
                                              </a>
                                              </div>
                                            </div>


                                            <div class="table_small dashboradtitle">
                                              <div class="table_cell">Product Detail</div>
                                              <div class="table_cell"><a href="{{$prdURL}}">
                                                  <div class="prdnamecartpage"><?php echo ucwords($row->name);?></div></a>
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
                                            <div class="table_small orderidmrgn">
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
                                               <div class="table_small orderidmrgn">
                                              <div class="table_cell">Cart</div>
                                              <div class="table_cell">
                                              <!--<span class="hideCartButtton" >{{$row->price}}</span>-->
                                              @php 
                                              $productAttribute = DB::table('product_attributes')
                                                    ->where('product_id',$row->id)
                                                    ->first();
                                              @endphp 
                                              <input type="hidden" name="qty" min="1" max="5" value="1" id="prd_qty_{{$row->id}}">
                                              <button type="submit" value="submit" class="btn btn-warning btn-sm hideCartButtton addTocart" 
                                                prd_page='0'
                                                url="{{App\Products::getProductDetailUrl($row->name,$row->id,$productAttribute->size_id,$productAttribute->color_id)}}"
                                                prd_index='{{$row->id}}' 
                                                prd_id='{{$row->id}}'
                                                     prd_type="{{$row->product_type}}"
                                                size_require="{!!App\Products::Issize_requires($row->id)!!}"
                                                color_require="{!!App\Products::Iscolorrequires($row->id)!!}"
                                                size_id="{!!App\Products::getFirstattrId('Sizes',$productAttribute->size_id)!!}"
                                                color_id="{!!App\Products::getFirstattrId('Colors',$productAttribute->color_id)!!}"
                                                > <i class="fa fa-shopping-cart"></i> Move to cart</button> </div>
                                            </div>
                                            <div class="table_small orderidmrgn">
                                              <div class="table_cell">Remove</div>
                                              <div class="table_cell">
                                                <!--<button class="buttonnone"><span> <i class="fa fa-refresh" aria-hidden="true"></i></span></button> -->
                <button class="btn buttonnone"><span> <i class="fa fa-trash-o delete deleteItemFromWishList"  	prd_id='{{$row->fld_wishlist_id}}' aria-hidden="true"></i></span></button>
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
</section>  
@endsection

            
@include('fronted.includes.addtocartscript')
    
