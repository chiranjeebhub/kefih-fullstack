@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum')
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Thankyou</a>
@endsection
<section class="wrap-40 thanks-section">
<div class="container-fluid">
<div class="row">
<div class="col-md-4 col-12 col-sm-4 offset-sm-4 offset-md-4">
    <div class="inner-thanks">
     <div class="centericonbox">
        <div class="centericonboxin"><i class="bi bi-check2"></i></div>
         <h2 class="text-center">Your order has been <br>successfully placed!</h2>
    </div>
    <div class="thanks-order-summary">
    <div class="Price-Details">
     <ul class="list-group orderdetail">
      <li class="list-group-item text-center">
        <strong>Order ID:</strong> {{$order_id}}
      </li>
     </ul>

        </div>
    </div>

        <input type="hidden" class="form-control" value="{{$total}}" id="grandTotal33">
            <?php

            $productsar=DB::table('order_details')
                         ->select('order_details.*','brands.name as brandName')
                        ->join('products','order_details.product_id','products.id')
                        ->join('brands','brands.id','products.product_brand')
                        ->where('order_details.order_id',$real_order_id)->get();
            $orderData=DB::table('orders')->where('id',$real_order_id)->first();


            $products=array();
            foreach($productsar as $key=>$productData){

                $cat_name=DB::table('categories')
                                    ->join('product_categories','product_categories.cat_id','categories.id')
                                    ->where('product_categories.product_id',@$productData->product_id)
                                    ->first();

                    array_push($products,array(
                        'id' => @$productData->product_id,
                        'name' => @$productData->product_name,
                        'price' =>@$productData->product_price,
                        'brand' =>@$productData->brandName,
                        'category' => @$cat->name,
                        'variant' =>@$productData->color,
                        'dimension1' => 'M',
                        'position' =>@$key,
                        'quantity' =>@$productData->product_qty
                    ));

            }

                 $arr=[
                'event' => 'transaction',
                'ecommerce' => [
                'purchase' => [
                  'actionField' => [
                    'id' => '9d528a3c-a5eb-486d-9dcd-fbdbc9ea4db7',
                    'affiliation' => 'Online Store',
                    'revenue' =>@$orderData->grand_total,
                    'tax' => 5,
                    'shipping' =>@$orderData->total_shipping_charges,
                  ],
                    'products' => @$products,
                ],
                ],
                ];
            ?>
        <script>
        /*window.dataLayer = window.dataLayer || []; */
        var dt =<?php echo json_encode($arr );?>;
        dataLayer.push(dt);
        </script>

    <div class="mt-4"><a href="{{route('index')}}" class="btn btn-warning btn-block btn-lg">Back to Home</a></div>
    @if (auth()->guard('customer')->user()->id)
    <div class="text-center headerSubtitle pt-4">Please <a class="btn btn-warning btn-lg head_user_login" role="button" style="background:transparent; margin:0px; padding:0px; border:none; width:auto;font-size: 13px;">login</a> to track your orders</div>
    @endif

 </div>

</div>
</div>
</div>
</section>
@endsection
