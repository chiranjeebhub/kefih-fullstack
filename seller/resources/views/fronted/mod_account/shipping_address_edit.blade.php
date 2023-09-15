@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="{{ route('shippingDetails')}}">Shipping Details</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Update Shipping Address</a>
@endsection   


<section class="dashbord-section">
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
<div class="dashbordlinks">
<h6 class="fs18 fw600 mb20">Shipping Details</h6>    
	               <ul>
						@include('fronted.mod_account.dashboard-menu')
					</ul>    
</div>    
</div>  
<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
<div class="dashbordtxt">
<h6 class="fs18 fw600 mb20">Update Shipping Details</h6> 
    
 <div class="text-style">
			
					<div class="shippingDetails text-style">
				
				<div class="row">
					<div class="col-md-12 col-xs-12">
						
						
						<form class="row" action="{{route('editShippingDetailsAddress',base64_encode($id))}}" method="post">
						      @csrf
						      	    @if ($errors->any())
     @foreach ($errors->all() as $error)
         <span class="help-block">
			<p style="color:red">{{$error}}</p>
		</span>
     @endforeach
 @endif

						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
								<input type="text" class="form-control" name="shipping_name" value="{{$shipping_data->shipping_name}}" placeholder="Full name">
							</div>
						</div>

						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
								<input type="text" class="form-control" name="shipping_mobile" value="{{$shipping_data->shipping_mobile}}" id=" " placeholder="Mobile Number" onkeypress="javascript:return onlyPincodeDigit(event,this.value,9)">
							</div>
						</div>

						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
								<input type="text" class="form-control" name="shipping_pincode" value="{{$shipping_data->shipping_pincode}}" id=" " placeholder="Pincode" onkeypress="javascript:return onlyPincodeDigit(event,this.value,5)">
							</div>
						</div>

						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
								<input type="text" class="form-control" name="shipping_address" id=" " value="{{$shipping_data->shipping_address}}"  placeholder="Flat, House no., Building, Company, Apartment">
							</div>
						</div>

						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
								<input type="text" class="form-control" name="shipping_address1" id=" " value="{{$shipping_data->shipping_address1}}" placeholder="Area, Colony, Street, Sector, Village">
							</div>
						</div>
						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
								<input type="text" class="form-control" name="shipping_address2" value="{{$shipping_data->shipping_address2}}" id=" " placeholder="Landmark e.g. near apollo hospital">
							</div>
						</div>
						
							<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
<select class="form-control custom-select" data-live-search="true"  data-title="Location" name="shipping_state" id="selectState" >
<option value="">Select State</option>
@foreach($states as $state)
<option value="{{$state->id}}"
<?php echo ($shipping_data->shipping_state==$state->name)?"selected":""?>
    >{{$state->name}}</option>
@endforeach
</select>
								
							</div>
						</div>
						
						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
<select class="form-control " name="shipping_city" id="selectcity">
<option value="">Select City</option>
@foreach($cities as $city)
<option value="{{$city->name}}"
<?php echo ($shipping_data->shipping_city==$city->name)?"selected":""?>
    >{{$city->name}}</option>
@endforeach
</select>
							</div>
						</div>
						
					
						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
								<select name="shipping_address_type" class="form-control custom-select" id="sel1">
									<option value="">---Select Address Type---</option>
									<option value="1" 
        <?php echo ($shipping_data->shipping_address_type==1)?'selected':'';?>
									>Home</option>
									<option value="2"
		<?php echo ($shipping_data->shipping_address_type==2)?'selected':'';?>
									>Office</option>
										<option value="3"
		<?php echo ($shipping_data->shipping_address_type==3)?'selected':'';?>
									>Other</option>
								</select>
							</div>
						</div>
						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
								<div class="paymnetthod">
									<div class="checkbox checkbox-circle">
										<input type="checkbox" id="default_address" name="shipping_address_default" 
			<?php echo ($shipping_data->shipping_address_default==1)?'checked':'';?> 

									/><label for="default_address"> Set default address</label>
									</div>
								</div>
							</div>
							
								<!--<div class="paymnetthod">
									<div class="checkbox checkbox-circle">
										<input id="default_address" name="payment_mode" type="checkbox" checked="" value="0">
										<label for="default_address"> Set default address</label>
									</div>
								</div>-->
							
						</div>
						<div class="col-md-12 col-xs-12">
							<div class="form-group">
							    <button type="submit" value="submit" class="saveaddress">Update Address</button>
								
							</div>
						</div>
					</form>
					</div>
					
				</div>
			</div>
				
			</div>
			  
</div>     
</div>    
</div>    
    
</section>





@endsection
    

  
  

    
