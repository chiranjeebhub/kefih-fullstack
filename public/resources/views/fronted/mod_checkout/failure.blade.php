@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
@include('fronted.includes.breadcrum')
@endsection   
<section class="thankyou-section">
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="thankyou-txt">
<i class="fa fa-check-circle"></i>
<h6 class="mb20 fs30 fw600">Order Failed</h6>
<br>
    	<a href="{{route('index')}}" class="continue mt10">Continue Shopping</a>
</div>    
</div>    
</div>
</div>     
</section>    
@endsection