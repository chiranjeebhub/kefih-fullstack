@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Thankyou</a>
@endsection  
<section class="thankyou-section">
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="inner-thanks text-center">
<div class="thankyou-img">
    <img src="{{ asset('public/fronted/images/thanks-img.png') }}">
</div>
<h6 class="mb20 fs30 fw600">Thank you</h6>
<p>Order is placed successfully. Track your order with 
Order  ID:{{$order_id}}</p> 
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
<br>
    	<a href="{{route('index')}}" class="btn btn-danger mt10">Back to home</a>
</div>    
</div>    
</div>
</div>     
</section>    
@endsection