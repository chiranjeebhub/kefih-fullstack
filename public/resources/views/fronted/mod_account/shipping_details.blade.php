@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">My Address</a>
@endsection   

<section class="dashbord-section checkout-section">
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
<div class="dashbordlinks">
<h6 class="fs18 fw600 mb20">My Address <span id="account-btn">
            <i class="fa fa-navicon"></i></span></h6>    
	               <ul  id="mobile-show">
						@include('fronted.mod_account.dashboard-menu')
					</ul>    
</div>    
</div>  
<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
<div class="dashbordtxt">
<h6 class="fs18 fw600 mb20">My Address</h6> 
    
 <div class="text-style">
			
				<div class="row">
					<?php for($i=0;$i<count($shipping_listing);$i++){?>
					<div class="col-md-4 col-sm-6 col-xs-12">
						<div class="adresbox">
						    
							<h6><?php echo ucwords($shipping_listing[$i]['shipping_name']);?></h6>
							<p>	<?php echo $shipping_listing[$i]['shipping_address'];?>,<br>
								<?php echo $shipping_listing[$i]['shipping_address1'];?>
								<?php echo $shipping_listing[$i]['shipping_address2'];?>
								<?php echo $shipping_listing[$i]['shipping_city'];?>
								<?php echo $shipping_listing[$i]['shipping_state'];?> : <?php echo $shipping_listing[$i]['shipping_pincode'];?></p>
							
						
							<?php  if($shipping_listing[$i]['shipping_address_default']){?>
							<div class="defaultAddress">
								<span class="ribbon6">Default Address</span>
							</div>
							
							<?php }?>
							<div class="purchase-detail-form-btn">
							<a class="btn btn-warning" href="{{route('editShippingDetailsAddress',base64_encode($shipping_listing[$i]['id']))}}"
							onclick = "if (! confirm('Do you want to edit ?')) { return false; }" class="editbtn pull-left">Edit</a> 
<a class="btn btn-danger" href="{{route('removeShippingAddress',base64_encode($shipping_listing[$i]['id']))}}"
							onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
							class="editbtn pull-right">Delete</a>
						</div>
					</div>
							</div>
					<?php } ?>
					
				</div>
			</div>
			<div class="shippingDetails text-style">
				
				<div class="row">
					<div class="col-md-12 col-xs-12">
					    
	<!--				    @if ($errors->any())-->
 <!--    @foreach ($errors->all() as $error)-->
 <!--        <span class="help-block">-->
	<!--		<p style="color:red">{{$error}}</p>-->
	<!--	</span>-->
 <!--    @endforeach-->
 <!--@endif-->
 <div class="Addnewaddress">
 <h2 class="fw600 fs20 mb10">Add a new address 
						<i class="fa fa-plus iconpointer addAddressForm"
						method="0"></i></h2>
 </div>
						
						<div id="myAddressForm">
						<p>Be sure to click "Deliver to this address" when you've finished.</p>
						
						<form class="row" action="{{route('addShippingAddress')}}" method="post">
						      @csrf

						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group">
							<label>Full Name</label>
								<input type="text" class="form-control" name="shipping_name" placeholder="Full name">
								@if($errors->has('shipping_name'))
                                    <span style="color:red">  {{ $errors->first('shipping_name') }}</span>


                                @endif
							</div>
						</div>

						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group">
							<label>Mobile No.</label>
								<input type="text" class="form-control" name="shipping_mobile" id=" " placeholder="Mobile Number" onkeypress="javascript:return onlyPincodeDigit(event,this.value,9)">
						@if($errors->has('shipping_mobile'))
                                    <span style="color:red">  {{ $errors->first('shipping_mobile') }}</span>


                                @endif
							</div>
						</div>

						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group">
							<label>Pin code</label>
								<input type="text" class="form-control" name="shipping_pincode" id=" " placeholder="Pincode" onkeypress="javascript:return onlyPincodeDigit(event,this.value,5)">
						@if($errors->has('shipping_pincode'))
                                    <span style="color:red">  {{ $errors->first('shipping_pincode') }}</span>


                                @endif
							</div>
						</div>

						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group">
							<label>Flat, House no., Building, Company</label>
								<input type="text" class="form-control" name="shipping_address" id=" " placeholder="Flat, House no., Building, Company, Apartment">
								@if($errors->has('shipping_address'))
                                    <span style="color:red">  {{ $errors->first('shipping_address') }}</span>


                                @endif
							</div>
						</div>

						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group">
							<label>Area, Colony, Street, Sector, Village</label>
								<input type="text" class="form-control" name="shipping_address1" id=" " placeholder="Area, Colony, Street, Sector, Village">
								@if($errors->has('shipping_address1'))
                                    <span style="color:red">  {{ $errors->first('shipping_address1') }}</span>


                                @endif
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group">
							<label>Landmark e.g. near</label>
								<input type="text" class="form-control" name="shipping_address2" id=" " placeholder="Landmark e.g. near apollo hospital">
						@if($errors->has('shipping_address2'))
                                    <span style="color:red">  {{ $errors->first('shipping_address2') }}</span>


                                @endif
							</div>
						</div>
						<!--customSelect selectpicker-->
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group">
							<label>State</label>
								<select class="form-control custom-select" data-live-search="true"  data-title="Location" name="shipping_state" id="selectState">
								<option value="">Select State</option>
								@foreach($states as $state)
								<option value="{{$state->id}}">{{$state->name}}</option>
								@endforeach
								</select>
									@if($errors->has('shipping_state'))
                                    <span style="color:red">  {{ $errors->first('shipping_state') }}</span>


                                @endif
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group">
							<label>City</label>
								<select class="form-control" name="shipping_city" id="selectcity" >
								<option value="">Select City</option>
								</select>
									@if($errors->has('shipping_city'))
                                    <span style="color:red">  {{ $errors->first('shipping_city') }}</span>


                                @endif
							</div>
						</div>
						
					
						<!--<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group">
							<label>Select Address</label>
								<select name="shipping_address_type" class="form-control custom-select" id="sel1">
									<option value="">---Select Address Type---</option>
									<option value="1">Home</option>
									<option value="2">Office</option>
										<option value="3">Others</option>
								</select>
							</div>
						</div>-->
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<div class="paymnetthod">
									<div class="checkbox checkbox-circle">
										<input id="default_address_home" name="shipping_address_type" type="radio" checked="" value="1">
										<label for="default_address_home"> Home</label>
									</div>
									<div class="checkbox checkbox-circle">
										<input id="default_address_office" name="shipping_address_type" type="radio"  value="2">
										<label for="default_address_office"> Office</label>
									</div>
									<div class="checkbox checkbox-circle">
										<input id="default_address_others" name="shipping_address_type" type="radio" value="3">
										<label for="default_address_others"> Others</label>
									</div>
								</div>
								
								<!--<input type="checkbox" name="shipping_address_default" value="1"/>Set default address-->
							</div>
						</div>
						<div class="col-md-12 col-xs-12">
							<div class="form-group">
								<button type="submit" value="submit" class="saveaddress">Save Address</button>
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
    

  
  

    
