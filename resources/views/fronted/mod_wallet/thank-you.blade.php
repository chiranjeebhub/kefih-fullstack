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
         <h2 class="text-center">
            @if($is_success)  
            Success 
            @else
            Failed 
            @endif
         </h2>
    </div>
    <div class="thanks-order-summary">
    <div class="Price-Details">
     <ul class="list-group orderdetail">
      <li class="list-group-item text-center">
        <strong>Transaction ID:</strong> {{$walletData->txn_id}}
      </li>
     </ul>

        </div>
    </div>      
         
        
    <div class="mt-4"><a href="{{route('index')}}" class="btn btn-warning btn-block btn-lg">Back to Home</a></div>

 </div>
   
</div>    
</div>
</div>     
</section>    
@endsection