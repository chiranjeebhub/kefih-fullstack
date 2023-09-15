@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Checkout</a>
@endsection   

	<section class="checkout-section">
		<div class="container">
				    @if ($errors->any())
     @foreach ($errors->all() as $error)
         <span class="help-block">
			<p style="color:red">{{$error}}</p>
		</span>
     @endforeach
 @endif
			<div class="text-style">
				<h2 class="fw600 fs20 mb30">Select a delivery address</h2>
				<div class="row">
					<?php for($i=0;$i<count($shipping_listing);$i++){?>
					<div class="col-md-3 col-xs-12">
						<div class="adresbox">
						    
							<?php echo ucwords($shipping_listing[$i]['shipping_name']);?>
							<p>	<?php echo $shipping_listing[$i]['shipping_address'];?>,<br>
								<?php echo $shipping_listing[$i]['shipping_address1'];?>
								<?php echo $shipping_listing[$i]['shipping_address2'];?>
								<?php echo $shipping_listing[$i]['shipping_city'];?>
								<?php echo $shipping_listing[$i]['shipping_state'];?> : <?php echo $shipping_listing[$i]['shipping_pincode'];?></p>
							
							<div class="text-center"><a href="{{route('selectShippingAddress',base64_encode($shipping_listing[$i]['id']))}}" class="btnn">Deliver to this address</a></div>
							<?php  if($shipping_listing[$i]['shipping_address_default']){?>
							<div class="defaultAddress">
								<span class="ribbon6">Default Address</span>
							</div>
							
							<?php }?>
							<a href="{{route('editShippingAddress',base64_encode($shipping_listing[$i]['id']))}}"
							onclick = "if (! confirm('Do you want to edit ?')) { return false; }" class="editbtn pull-left">Edit</a> 
<a href="{{route('removeShippingAddress',base64_encode($shipping_listing[$i]['id']))}}"
							onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
							class="editbtn pull-right">Delete</a>
						</div>
					</div>
					<?php } ?>
					
				</div>
			</div>
			<div class="checoutform_main text-style mt30">
				
				<div class="row">
					<div class="col-md-12 col-xs-12">
					    
					    @if ($errors->any())
     @foreach ($errors->all() as $error)
         <span class="help-block">
			<p style="color:red">{{$error}}</p>
		</span>
     @endforeach
 @endif
 
						<h2 class="fw600 fs20 mb10">Add a new address
							<i class="fa fa-plus iconpointer addAddressForm"
						method="0"></i></h2>
						<div id="myAddressForm">
						<p>Be sure to click "Deliver to this address" when you've finished.</p>
						
						<form class="row mt30" action="{{route('addShippingAddress')}}" method="post">
						      @csrf

						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
								<input type="text" class="form-control" name="shipping_name" placeholder="Full name">
							</div>
						</div>

						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
								<input type="text" class="form-control" name="shipping_mobile" id=" " placeholder="Mobile Number" onkeypress="javascript:return onlyPincodeDigit(event,this.value,9)">
							</div>
						</div>

						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
								<input type="text" class="form-control" name="shipping_pincode" id=" " placeholder="Pincode" onkeypress="javascript:return onlyPincodeDigit(event,this.value,5)">
							</div>
						</div>

						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
								<input type="text" class="form-control" name="shipping_address" id=" " placeholder="Flat, House no., Building, Company, Apartment">
							</div>
						</div>

						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
								<input type="text" class="form-control" name="shipping_address1" id=" " placeholder="Area, Colony, Street, Sector, Village">
							</div>
						</div>
						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
								<input type="text" class="form-control" name="shipping_address2" id=" " placeholder="Landmark e.g. near apollo hospital">
							</div>
						</div>
							<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
								<select class="form-control custom-select" data-live-search="true"  data-title="Location" name="shipping_state" id="selectState">
								<option value="">Select State</option>
								@foreach($states as $state)
								<option value="{{$state->id}}">{{$state->name}}</option>
								@endforeach
								</select>
								
							</div>
						</div>
						
						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
<select class="form-control" name="shipping_city" id="selectcity">
<option value="">Select City</option>
</select>
							</div>
						</div>
					
						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
								<select name="shipping_address_type" class="form-control custom-select" id="sel1">
									<option value="">---Select Address Type---</option>
									<option value="1">Home</option>
									<option value="2">Office</option>
										<option value="3">Others</option>
								</select>
							</div>
						</div>
						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
								
								<div class="paymnetthod">
									<div class="checkbox checkbox-circle">
										<input id="default_address" name="payment_mode" type="checkbox" checked="" value="0">
										<label for="default_address"> Set default address</label>
									</div>
								</div>
								
								<!--<input type="checkbox" name="shipping_address_default" value="1"/>Set default address-->
							</div>
						</div>
						<div class="col-md-12 col-xs-12">
							<div class="form-group">
								<button type="submit" value="submit" class="continue">Save</button>
							</div>
						</div>
					</form>
					</div>
					</div>
					
				</div>
			</div>
			
		</div>
	</section>



@endsection
    

  
  

    
