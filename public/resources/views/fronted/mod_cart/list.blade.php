@extends('fronted.layouts.app_new')
@section('pageTitle','Cart')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Cart</a>
@endsection   

<section class="shopping-carts wrap inr-wrap-tp">
<div class="container">
        <div class="row cart_table_list">
        
        </div>  
</div>
</section>    

@endsection   
 
  
  

    
