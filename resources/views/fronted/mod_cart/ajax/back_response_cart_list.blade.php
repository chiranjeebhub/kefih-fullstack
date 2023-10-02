
@if (count($cart)>0)

    <div class="productCardWrapper">
        <form>
            <?php
            $out_of_stock=0;
            $grand_total=0;
            $tax=0;
            $sub_total=0;

            $discount=0;
            ?>
            @foreach($cart as $product)
                <?php
                $current_out_stck=0;


                $stock = App\ProductAttributes::select('qty')
                    ->where('size_id','=',$product->size_id)
                    ->where('color_id','=',$product->color_id)
                    ->where('product_id','=',$product->prd_id)
                    ->first();
                $quantity = $stock ? $stock->qty : 0;

                if($quantity < $product->qty) {
                    $current_out_stck=1;
                    $out_of_stock++;
                }
                ?>
                <div class="productCard" id="cart_item_row_{{$product->prd_id.'-'.$product->size_id.'-'.$product->color_id.'-'.$product->qty}}">
                    <div class="productCardImage">
                        <?php

                        if( $product->color_id!=0){
                            $colorImage=DB::table('product_configuration_images')
                                ->where('product_id',$product->prd_id)
                                ->where('color_id',$product->color_id)
                                ->first();

                            $productid=DB::table('products')->where('id',$product->prd_id)->first();
                            $productskuid=DB::table('product_attributes')->where('product_id',$product->prd_id)->first();
                            $imagespathfolder='uploads/products/'.$productid->vendor_id.'/'.$productskuid->sku;


                            if($colorImage){
                                $url=$imagespathfolder.'/'.$colorImage->product_config_image;
                            } else{
                                $url=$imagespathfolder.'/'.$product->default_image;
                            }

                        }
                        else{

                            $productid=DB::table('products')->where('id',$product->prd_id)->first();

                            $productimages=DB::table('product_images')->where('product_id',$product->prd_id)->first();
                            $imagespathfolder='uploads/products/'.$productid->vendor_id.'/'.$productid->sku;

                            $url=$imagespathfolder.'/'.$productimages->image;
                        }

                        $prd_url= App\Products::getProductDetailUrl($product->name,$product->prd_id,$product->size_id,$product->color_id);
                        ?>

                        <a href="{{$prd_url}}" target="_blank">
                            <img src="{{$url }}" alt="cart" class="img-thumbnail">
                        </a>

                    </div>

                    <?php

                    if( $product->color_id!=0){
                        $colorImage=DB::table('product_configuration_images')
                            ->where('product_id',$product->prd_id)
                            ->where('color_id',$product->color_id)
                            ->first();
                        if($colorImage){
                            $url=Config::get('constants.Url.public_url').Config::get('constants.uploads.product_images').'/'.$colorImage->product_config_image;
                        } else{
                            $url=Config::get('constants.Url.public_url').Config::get('constants.uploads.product_images').'/'.$product->default_image;
                        }

                    }
                    else{
                        $url=Config::get('constants.Url.public_url').Config::get('constants.uploads.product_images').'/'.$product->default_image;
                    }

                    $prd_url= App\Products::getProductDetailUrl($product->name,$product->prd_id,$product->size_id,$product->color_id);
                    ?>


                    <div class="productCardDetails">
                        <div>
                            <div class="productCardTitle">{{$product->name}}</div>

                            <div class="productCardSubTitle">

                                @if ($product->color_id!=0)
                                    <label class="colrboxcart color1 me-2"  style="background-color:<?php echo  App\Products::getcolorCode('Colors',$product->color_id);?> !important">
                                    </label>
                                @endif
                                @if ($product->w_size_id!=0)
                                    <span>Men Size : {!!App\Products::getAttrName('Sizes',$product->size_id)!!}</span>
                                    <br>
                                    <span>Women Size : {!!App\Products::getAttrName('Sizes',$product->w_size_id)!!}</span>
                                @else
                                    @if ($product->size_id!=0)

                                        <label class="size1 sizeboxcart">{!!App\Products::getAttrName('Sizes',$product->size_id)!!}</label>
                                    @endif
                                @endif
                            </div>
                            <div class="bottomRow">
                                <div class="productCardPrice">
                                    <div class="sellingPrice">




                                        @if ($product->master_spcl_price!='' && $product->master_spcl_price!=0)


                                            <?php

                                            $old_prc=$product->master_price;
                                            if ($product->master_spcl_price!='' && $product->master_spcl_price!=0){
                                                $prc=$product->master_spcl_price;
                                            }else{
                                                $prc=$product->master_price;
                                            }
                                            if($product->color_id==0 && $product->size_id!=0){

                                                $attr_data=DB::table('product_attributes')
                                                    ->where('product_id',$product->prd_id)
                                                    ->where('size_id',$product->size_id)
                                                    ->first();
                                                $prc+=$attr_data->price;
                                                $old_prc+=$attr_data->price;
                                            }
                                            if($product->color_id!=0 && $product->size_id==0){
                                                $attr_data=DB::table('product_attributes')
                                                    ->where('product_id',$product->prd_id)
                                                    ->where('color_id',$product->color_id)
                                                    ->first();
                                                $prc+=$attr_data->price;
                                                $old_prc+=$attr_data->price;
                                            }
                                            if($product->color_id!=0 && $product->size_id!==0){
                                                $attr_data=DB::table('product_attributes')
                                                    ->where('product_id',$product->prd_id)
                                                    ->where('color_id',$product->color_id)
                                                    ->where('size_id',$product->size_id)
                                                    ->first();
                                                $prc+=$attr_data->price;
                                                $old_prc+=$attr_data->price;
                                            }
                                            ?>



                                        @else


                                            <?php

                                            $old_prc=$product->master_price;
                                            if ($product->master_spcl_price!='')
                                            {
                                                $prc=$product->master_spcl_price;
                                            }else{
                                                $prc=$product->master_price;
                                            }
                                            if($product->color_id==0 && $product->size_id!=0){

                                                $attr_data=DB::table('product_attributes')
                                                    ->where('product_id',$product->prd_id)
                                                    ->where('size_id',$product->size_id)
                                                    ->first();
                                                $prc+=$attr_data->price;
                                                $old_prc+=$attr_data->price;
                                            }
                                            if($product->color_id!=0 && $product->size_id==0){
                                                $attr_data=DB::table('product_attributes')
                                                    ->where('product_id',$product->prd_id)
                                                    ->where('color_id',$product->color_id)
                                                    ->first();
                                                $prc+=$attr_data->price;
                                                $old_prc+=$attr_data->price;
                                            }
                                            if($product->color_id!=0 && $product->size_id!==0){
                                                $attr_data=DB::table('product_attributes')
                                                    ->where('product_id',$product->prd_id)
                                                    ->where('color_id',$product->color_id)
                                                    ->where('size_id',$product->size_id)
                                                    ->first();
                                                $prc+=$attr_data->price;
                                                $old_prc+=$attr_data->price;
                                            }
                                            ?>

                                        @endif


                                        <?php

                                       $total=$prc*$product->qty;
                                        $sub_total+=$total;
                                        echo '₹'.$total?>


                                    </div>
                                    <div class="mrp">
                                        @php
                                            $mrp = $old_prc*$product->qty;
                                        @endphp
                                        @if($total < $mrp)
                                            ₹{{ $mrp}}
                                        @endif
                                    </div>
                                </div>
                                <div  class="countWrapper" id="field1">
                                    <button type="button" class="minusBtn sign minus changeQtyOfCartProduct sub" method="2" row="{{$product->prd_id.'-'.$product->size_id.'-'.$product->color_id.'-'.$product->qty}}" prd_id="{{$product->prd_id}}" size="{{$product->size_id}}" color="{{$product->color_id}}" w_size="{{$product->w_size_id}}"><i class="fa fa-minus"></i></button>
                                    <input type="text" value="{{$product->qty}}" title="Qty" class="quantity qty-input qty text" size="1" disabled id="qty_field_{{$product->prd_id.'-'.$product->size_id.'-'.$product->color_id.'-'.$product->qty}}" pattern="[0-9]" >
                                    <button type="button" class="plusBtn sign plus changeQtyOfCartProduct add" method="1" row="{{$product->prd_id.'-'.$product->size_id.'-'.$product->color_id.'-'.$product->qty}}" prd_id="{{$product->prd_id}}" size="{{$product->size_id}}" color="{{$product->color_id}}" w_size="{{$product->w_size_id}}"><i class="fa fa-plus"></i></button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="productCardRemove deleteCartItem" prd_id="{{$product->prd_id.'-'.$product->size_id.'-'.$product->color_id.'-'.$product->qty}}">
                        <img src="{{asset('public/fronted/images/deleteIcon.svg')}}" alt="close">
                    </div>
                </div>

            @endforeach
            @if (count($cart) ==0)
                <div class="table_row">
                    Cart is empty
                </div>
            @endif

        </form>
    </div>



<!--
    <div class="col-sm-6 col-md-6 col-lg-4 productIncart" >
        <div class="summary">
            <h3>Order Summary</h3>

            <div class="Price-Details">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Total Products (Tax Incl.) <span><i class="fa fa-rupee"></i><strong>{{$sub_total}}</strong></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Shipping Charges <span><i class="fa fa-rupee"></i><strong class="shipping_charge_response"></strong></span>
                    </li>
                    &lt;!&ndash;<li class="list-group-item d-flex justify-content-between align-items-center">
                       COD Charge :<span><i class="fa fa-inr"></i><strong id="cod_charge_html"></strong> </span>
                     </li>&ndash;&gt;
                </ul>
                <ul class="list-group totalpay">
                    <?php $total=$sub_total;?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Total Amount
                        <span><i class="fa fa-inr"></i> {{$total+$shipping_charge}} </span>
                    </li>
                    @if($how_many_you_save>0)
                        <p class="savetextline">You will save <i class="fa fa-rupee"></i>  {{$how_many_you_save}} on this order</p>
                    @endif()
                </ul>

                &lt;!&ndash;<div class="cartshipingaddbox">
                   <h4>Shipping Address:</h4>
                   <p>Sr no 48/4 Washington DC, United States of America, USA - 1223421</p>
                </div>
                <div class="cartshipingaddbox">
                   <h4>Billing Address:</h4>
                   <p>Sr no 48/4 Washington DC, United States of America, USA - 1223421</p>
                </div>&ndash;&gt;

            </div>

            <div class="row mob_fixed">
                <div class="col-md-12 col-xs-12">
                    @php
                        $isLoggedIn = Auth::guard('customer')->check();
                        $loginPopUpClass = ($isLoggedIn)?'':'head_user_login';
                    @endphp

                    @if($out_of_stock==0)
                        @if(empty($loginPopUpClass))
                            <a href="{{route('review_order')}}" class="btn btn-warning btn-block btn-lg"> Checkout</a>
                        @else
                            <a href="javascript:void(0);" class="btn btn-warning btn-block btn-lg {{$loginPopUpClass}}"> Checkout</a>

                        @endif

                    @else
                        <a href="javascript:void(0)" class="btn btn-warning btn-block btn-lg"> <span>Product Out of Stock</span></a>
                    @endif()

                </div>
            </div>

            <div class="checkout-element-content">
                &lt;!&ndash;<p class="order-left">Total Products (Tax Incl.) <span><i class="fa fa-rupee"></i><strong>{{$sub_total}}</strong></span></p>&ndash;&gt;
                &lt;!&ndash;<p class="order-left">Shipping Charges <span><i class="fa fa-rupee"></i><strong class="shipping_charge_response"></strong></span></p>&ndash;&gt;


                &lt;!&ndash; <p class="order-left" id="cod_charge_html">COD Charge :<span><i class="fa fa-inr"></i> &ndash;&gt;
                &lt;!&ndash;<i id="">25</i></span></p>&ndash;&gt;
            </div>
            &lt;!&ndash;<div class="totalmainbox">
                            <?php //$total=$sub_total;?>
                <p class="order-left">Total Amount <span><i class="fa fa-rupee"></i><strong>{{$total+$shipping_charge}}</strong></span></p>
                            @if($how_many_you_save>0)
                <p class="savetextline">You will save <i class="fa fa-rupee"></i>  {{$how_many_you_save}} on this order</p>
                            @endif()

            </div>&ndash;&gt;

        </div>
    </div>
-->


    <div class="subTotalSection">
        <div class="subTotalTitle">Subtotal</div>
        <div class="subTotalCard">
            <div class="subItemWrapper">

                <div class="subItem">
                    <div>  Total Products (Tax Incl.)</div>
                    <div>₹{{$sub_total}}</div>
                </div>
                <div class="subItem">
                    <div>Shipping Charges</div>
                    <div>₹00.00</div>
                </div>
            </div>
            <div class="divider">&nbsp;</div>


            <?php $total=$sub_total;?>
            <div class="subTotal">
                <div>Subtotal</div>
                <span class="toatlValue"><i class="fa fa-inr"></i> {{$total+$shipping_charge}} </span>
            </div>
            @if($how_many_you_save>0)
                <p class="savetextline">You will save <i class="fa fa-rupee"></i>  {{$how_many_you_save}} on this order</p>
            @endif()

        </div>
        <div class="paymentBtn">Proceed To Pay</div>
    </div>



@else
    <div class="col-md-12">
        <h3 class="px-4">No Product Added to Cart</h3>
    </div>
@endif


<script type="text/javascript">
    $(document).ready(function(){
        $("#test1").click(function () {
            if ($(this).is(":checked")) {
                $("#dvPassport").show();

            } else {
                $("#dvPassport").hide();

            }
        });
        if ($('#test1').is(":checked")) {
            $("#dvPassport").show();

        } else {
            $("#dvPassport").hide();

        }
    });
</script>
