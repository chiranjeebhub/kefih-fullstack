@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Refer And Earn</a>
@endsection       
    
<section class="blog-section">
<div class="container">

<div class="add-image">
<img src="{{ asset('public/images/refer-and-earn.jpg') }}" alt="refer-and-earn">    
</div>
        @if (!Auth::guard('customer')->check())
            <div class="row text-center refer-login">
                <div class="col-sm-4 col-sm-offset-4 col-sm-pull-0">
                    <p>Hey, Please login to share your referral code.</p>
                    <p><a class="registrbtn" href="{{route('customer_login')}}">Log In</a></p>
                </div>
            </div>
        @endif
         
    
      @if (Auth::guard('customer')->check())
        <div class="row text-center">
            <div class="col-sm-6 col-sm-offset-3 col-sm-pull-0">
                <div class="copycut">
                <h4>Your referral code  
                
                
                </h4>
                <div class="referral-code">
                <p>
                    <style>
                .refer_code{
                    outline: none !important;
                    border: none;
                }
                #rfcode{
                    display:none;
                }
                .badge {
    background-color: 'black'; !important;}
                    </style>
<input type="text" readonly value="{{auth()->guard('customer')->user()->r_code}}" id="myInput" class="refer_code">
                <a href="javascript:void(0)" onclick="copycode()">Copy</a></p>
               
                </div>
                 <span class="badge badge-dark" id="rfcode">Referral code copied</span>
                    <p>Share the code above with your friends and earn Rs. 100 worth of Phaukat Cions. 
                    <a href="{{route('page_url',['refer-and-earn'])}}">T&amp;C</a> </p>
                </div>
            </div>
        </div>
        @endif
        
 

</div>     
</section>


@endsection
