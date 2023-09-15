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
<div class="thankyou-txt">
<i class="fa fa-check-circle"></i>
<h6 class="mb20 fs30 fw600">thank you</h6>
<p>Order is placed successfully. Track your order with 
Order  ID:{{$order_id}}</p>  
<br>
    	<a href="{{route('index')}}" class="continue mt10">Continue Shopping</a>
</div>    
</div>    
</div>
</div>     
</section>    
@endsection