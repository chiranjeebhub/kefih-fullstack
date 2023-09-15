@extends('admin.layouts.app_new')
@section('pageTitle', 'Home')
@section('content')
    <!-- Main content -->
	<style>
		.flexbox h5 a{color:#fff;}
	</style>
    <section class="content">
	  <div class="row">
        <div class="col-xl-3 col-md-6 col-12 ">
          	<div class="box bg-info">
              <div class="box-body">
                <div class="flexbox">
                  <h5><a href="{{route('vendor_product')}}">Total Products</a></h5>
                  
                </div>

                <div class="text-center my-2">
                  <div class="font-size-60 text-white">	  {!! App\Vendor::productCount(auth()->guard('vendor')->user()->id); !!}</div>
                
                </div>
              </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 col-12 ">
          	<div class="box bg-warning">
              <div class="box-body">
                <div class="flexbox">
                  <h5><a href="{{ URL::to('admin/vendor_filters_products/'.auth()->guard('vendor')->user()->id.'/0/All/All/All/All/All') }}">Total Inactive Products</a></h5>
                  
                </div>

                <div class="text-center my-2">
                  <div class="font-size-60 text-white">	  {!! App\Vendor::productInactiveCount(auth()->guard('vendor')->user()->id); !!}</div>
                
                </div>
              </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 col-12 ">
          	<div class="box bg-primary">
              <div class="box-body">
                <div class="flexbox">
                  <h5><a href="{{ URL::to('admin/vendor_filters_products/'.auth()->guard('vendor')->user()->id.'/1/All/All/All/All/All') }}">Total Active Products</a></h5>
                  
                </div>

                <div class="text-center my-2">
                  <div class="font-size-60 text-white">	  {!! App\Vendor::productActiveCount(auth()->guard('vendor')->user()->id); !!}</div>
                
                </div>
              </div>
            </div>
        </div>
        <!-- /.col -->
         <div class="col-xl-3 col-md-6 col-12 ">
          	<div class="box bg-danger">
              <div class="box-body">
                <div class="flexbox">
                  
                    <h5><a href="{{ route('vendor_orders',base64_encode(0)) }}">Total  Orders</a></h5>
                </div>

                <div class="text-center my-2">
                  <div class="font-size-60 text-white">	 

                   {!! App\Vendor::orderCount(auth()->guard('vendor')->user()->id); !!}</div>
                
                </div>
              </div>
            </div>
        </div>
        
         <div class="col-xl-3 col-md-6 col-12 ">
          	<div class="box bg-primary">
              <div class="box-body">
                <div class="flexbox">
                  <h5><a href="{{ route('vendor_orders',base64_encode(0)) }}">New Orders</a></h5>
                  
                </div>

                <div class="text-center my-2">
                  <div class="font-size-60 text-white">	  {!! App\Vendor::new_order_Count(auth()->guard('vendor')->user()->id); !!}</div>
                
                </div>
              </div>
            </div>
        </div>
        
        
         <div class="col-xl-3 col-md-6 col-12 ">
          	<div class="box bg-success">
              <div class="box-body">
                <div class="flexbox">
                  <h5><a href="{{ route('vendor_orders',base64_encode(1)) }}">Invoices Orders</a></h5>
                  
                </div>

                <div class="text-center my-2">
                  <div class="font-size-60 text-white">	  {!! App\Vendor::invoice_order_Count(auth()->guard('vendor')->user()->id); !!}</div>
                
                </div>
              </div>
            </div>
        </div>
        
        
         <div class="col-xl-3 col-md-6 col-12 ">
          	<div class="box bg-info">
              <div class="box-body">
                <div class="flexbox">
                  <h5><a href="{{ route('vendor_orders',base64_encode(2)) }}">Shipped Orders</a></h5>
                  
                </div>

                <div class="text-center my-2">
                  <div class="font-size-60 text-white">	  {!! App\Vendor::shipped_order_Count(auth()->guard('vendor')->user()->id); !!}</div>
                
                </div>
              </div>
            </div>
        </div>
        
        
        
         <div class="col-xl-3 col-md-6 col-12 ">
          	<div class="box bg-success">
              <div class="box-body">
                <div class="flexbox">
                  <h5><a href="{{ route('vendor_orders',base64_encode(3)) }}">Delivered Orders</a></h5>
                  
                </div>

                <div class="text-center my-2">
                  <div class="font-size-60 text-white">	  {!! App\Vendor::deliver_order_Count(auth()->guard('vendor')->user()->id); !!}</div>
                
                </div>
              </div>
            </div>
        </div>
        
        
         <div class="col-xl-3 col-md-6 col-12 ">
          	<div class="box bg-info">
              <div class="box-body">
                <div class="flexbox">
                  <h5><a href="{{ route('vendor_orders',base64_encode(5)) }}">Return Orders</a></h5>
                  
                </div>

                <div class="text-center my-2">
                  <div class="font-size-60 text-white">	  {!! App\Vendor::return_order_Count(auth()->guard('vendor')->user()->id); !!}</div>
                
                </div>
              </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-12 ">
          	<div class="box bg-info">
              <div class="box-body">
                <div class="flexbox">
                  <h5><a href="{{ route('vendor_orders',base64_encode(5)) }}">Total Returned Products</a></h5>
                  
                </div>

                <div class="text-center my-2">
                  <div class="font-size-60 text-white">	  {!! App\Vendor::return_product_Count(auth()->guard('vendor')->user()->id); !!}</div>
                
                </div>
              </div>
            </div>
        </div>

        
        <div class="col-xl-3 col-md-6 col-12 ">
          	<div class="box bg-success">
              <div class="box-body">
                <div class="flexbox">
                  <h5><a href="{{ route('vendor_orders',base64_encode(4)) }}">Cancelled Orders</a></h5>
                  
                </div>

                <div class="text-center my-2">
                  <div class="font-size-60 text-white">	  {!! App\Vendor::cancel_order_Count(auth()->guard('vendor')->user()->id); !!}</div>
                
                </div>
              </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 col-12 ">
          	<div class="box bg-info">
              <div class="box-body">
                <div class="flexbox">
                 
                   <h5><a href="{{route('vledger')}}">Total Sales</a></h5>
                 
                </div>

                <div class="text-center my-2">
                  <div class="font-size-60 text-white">{!! App\Vendor::total_sell_Count(auth()->guard('vendor')->user()->id); !!}</div>
                
                </div>
              </div>
            </div>
        </div>


        <div class="col-xl-3 col-md-6 col-12 ">
          	<div class="box bg-warning">
              <div class="box-body">
                <div class="flexbox">
                 
                   <h5><a href="{{ route('vendor_orders',base64_encode(5)) }}">Total returned orders amount</a></h5>
                 
                </div>

                <div class="text-center my-2">
                  <div class="font-size-60 text-white">{!! App\Vendor::returnOrderAmount(auth()->guard('vendor')->user()->id); !!}</div>
                
                </div>
              </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-12 ">
          	<div class="box bg-danger">
              <div class="box-body">
                <div class="flexbox">
                 
                   <h5><a href="{{ route('vendor_orders',base64_encode(4)) }}">Total cancelled orders amount </a></h5>
                 
                </div>

                <div class="text-center my-2">
                  <div class="font-size-60 text-white">{!! App\Vendor::cancelOrderAmount(auth()->guard('vendor')->user()->id); !!}</div>
                
                </div>
              </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-12 ">
          	<div class="box bg-primary">
              <div class="box-body">
                <div class="flexbox">
                 
                   <h5><a href="{{route('vledger')}}">Total Reverse Shipping Amount</a></h5>
                 
                </div>

                <div class="text-center my-2">
                  <div class="font-size-60 text-white">{!! App\Vendor::calculateReverseShippingCharge(auth()->guard('vendor')->user()->id); !!}</div>
                
                </div>
              </div>
            </div>
        </div>

        

        <!-- /.col -->

    
        <!-- /.col -->
    
        <!-- /.col -->
		  
      </div>	
	
      <!-- /.row -->	      
	</section>
    <!-- /.content -->
@endsection
