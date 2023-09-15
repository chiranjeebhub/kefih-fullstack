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
<section class="wrap inr-wrap-tp ">
<div class="container-fluid">
    <div class="title">
        <h4>Available Coupon</h4> 
    </div>
    
        <div class="row">
            
                @foreach($coupons as $row)
                    <div class="col-md-6 col-12 col-sm-6">
                        <div class="coupon-box">
                            <div class="media">
                           <div class="media-body">
                           <h5>{{$row->coupon_code}}</h5>
                           <p class="small">Flat {{$row->discount_value}}% off </p>
                           </div>
                           <div class="media-right text-center">
                            <div class="form-check">
                                <p class="big">Copy & apply the code</p>
                                <!--<button class="btn btn-warning">Apply</button>-->
                  <!--<input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" checked>-->
                </div>
                <p class="small"><a href="#">T&amp;C*</a></p>
                           </div>
                        </div>
                        </div>

                 <!--<div class="media">
                     <div class="coupon-items">

                       <p>ID :  {{$row->id}} </p>
                       <p>coupon_used :  {{$row->coupon_used}} </p> 
                      <div class="couponInBox"><span>Coupon Code </span> : <span>{{$row->coupon_code}}</span></div>
                      <div class="couponInBox"><span>Discount</span> : <span>{{$row->discount_value}} % </span></div>
                      <div class="couponInBox"><span>Max Discount Amount</span> : <span><i class="fa fa-inr"></i> {{$row->max_discount}} </span> </div>
                      @if($row->description)
                      <div class="couponInBox"><span>Description</span> : <span>{!!$row->description!!}</span> </div>
                      @endif
                    </div>
                 </div>-->
                </div>
                 @endforeach
            
        </div>
     
    
    <div class="row text-center">
 {{$coupons->links()}}
</div>
</div>       
</section>      

   
@endsection
