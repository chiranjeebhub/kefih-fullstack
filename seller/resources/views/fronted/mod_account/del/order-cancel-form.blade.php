@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
@include('fronted.includes.breadcrum')
@endsection   
<section class="order-cancel-form-section" style="background-image: {{ asset('public/fronted/images/inner-section-bg.png') }}; background-position: center;
background-size: cover; background-repeat: no-repeat;">
<div class="container-fluid">
<div class="row">    
<div class="col-md-12">
<div class="order-cancel-form">
<h6 class="mb40"> Cancel<span> Order</span></h6>
    
    <form action="">
    <div class="form-group">
    <select name="country" class="form-control custom-select" id="country">
    <option value="" selected="selected">---Select Reason For Cancellation---</option>
    <option value="+1">Shipping Address Undeliverable</option>
    <option value="+1">Customer Exchange</option>
    <option value="+1">Buyer Cancelled</option>
    <option value="+1">Buyer Has Not Paid</option>  
    <option value="+1">General Adjustment</option>  
    <option value="+1">Carrier Credit Decision</option>      
    <option value="+1">Risk assessment Information Not Valid</option>      
    <option value="+1">Customer Return</option>
    </select>
    </div>

    <div class="form-group">
   <p>Refund status</p>
    </div>    

    <div class="form-group">
    <textarea class="form-control" name="comment" data-required-error="Please fill the field" placeholder="Comment"></textarea>
    </div>
    <button type="submit" class="registrbtn"> Submit</button>  
    </form>  
    
</div>    
</div>
    
</div>    
</div>    
    
</section>    
@endsection   
    
    

  
  

    
