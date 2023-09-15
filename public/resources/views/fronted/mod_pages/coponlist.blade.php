@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 

@section('pageTitle', 'Coupon List')
@section('metaTitle', 'Coupon List')
@section('metaKeywords', 'Coupon List')
@section('metadescription', 'Coupon List')

<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Coupon List</a>
@endsection       
<section class="wrap mt0sectn">
<div class="container">
    <div class="row couponcontainer">
     @foreach($coupons as $row)
     <div class="col col-md-6 col-sm-6 col-xs-12">
         <div class="coupon-items">
             <div class="couponIcon"><i class="fa fa-tag"></i></div>
          <!-- <p>ID :  {{$row->id}} </p>
           <p>coupon_used :  {{$row->coupon_used}} </p> -->
          <div class="couponInBox"><span>Coupon Code </span> : <span>{{$row->coupon_code}}</span></div>
          <div class="couponInBox"><span>Discount</span> : <span>{{$row->discount_value}} % </span></div>
          <div class="couponInBox"><span>Max Discount Amount</span> : <span><i class="fa fa-inr"></i> {{$row->max_discount}} </span> </div>
          @if($row->description)
          <div class="couponInBox"><span>Description</span> : <span>{!!$row->description!!}</span> </div>
          @endif
        </div>
     </div>
     @endforeach
    </div>
    <div class="row text-center">
 {{$coupons->links()}}
</div>
</div>       
</section>      

   
@endsection
