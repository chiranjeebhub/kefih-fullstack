@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="{{ route('checkout')}}">Checkout</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Update Shipping Address</a>
@endsection   

	<section class="wrap pt-4 pb-4 main_section checkout-section">
		<div class="container-fluid">
            <h2 class="heading mb-30">Edit Address</h2>  
            <div class="row">
				<div class="col-md-12 col-xs-12">
                    <div class="profile_form ">
			         <form class="row" action="{{route('editShippingAddress',base64_encode($id))}}" method="post">
						      @csrf

						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
                                <label>Full Name</label>
								<input type="text" class="form-control" name="shipping_name" value="{{$shipping_data->shipping_name}}" placeholder="Full name">
							</div>
						</div>

						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
                                <label>Mobile Number</label>
								<input type="text" class="form-control" name="shipping_mobile" value="{{$shipping_data->shipping_mobile}}" id=" " placeholder="Mobile Number" onkeypress="javascript:return onlyPincodeDigit(event,this.value,9)">
							</div>
						</div>

						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
                                <label>Pincode</label>
								<input type="text" class="form-control" name="shipping_pincode" value="{{$shipping_data->shipping_pincode}}" id=" " placeholder="Pincode" onkeypress="javascript:return onlyPincodeDigit(event,this.value,5)">
							</div>
						</div>

						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
                                <label>Address Line 1</label>
								<input type="text" class="form-control" name="shipping_address" id=" " value="{{$shipping_data->shipping_address}}"  placeholder="Flat, House no., Building, Company, Apartment">
							</div>
						</div>

						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
                                <label>Address Line 2</label>
								<input type="text" class="form-control" name="shipping_address1" id=" " value="{{$shipping_data->shipping_address1}}" placeholder="Area, Colony, Street, Sector, Village">
							</div>
						</div>
						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
                                <label>Address Line 3</label>
								<input type="text" class="form-control" name="shipping_address2" value="{{$shipping_data->shipping_address2}}" id=" " placeholder="Landmark e.g. near apollo hospital">
							</div>
						</div>
							<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group">
							    <label>State</label>
                                <select class="form-control custom-select"  name="shipping_state" id="selectState" >
                                <option value="">Select State  </option>
                                @foreach($states as $state)
                                <option value="{{$state->id}}"
                                <?php echo ($shipping_data->shipping_state==$state->name)?"selected":""?>
                                    >{{$state->name}}</option>
                                @endforeach
                                </select>
								
							</div>
						</div>
						
						<div class="col-md-12 col-md-12 col-md-4 col-lg-4">
							<div class="form-group  editionaldropdn">
                                <label>City</label>
                                <select class="form-control" name="shipping_city" id="selectcity">
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
                                <label>Address Type</label>
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
                        <?php if(sizeof($shipping_listing)>0){?>
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
							
						</div>
                        <?php } else{?>
                       <input type="hidden" name="shipping_address_default" value="1"/>
                        <?php }?>
					
						<div class="col-md-12 col-xs-12">
							<div class="form-group">
								<button type="submit" value="submit" class="btn btn-warning">Update</button>
							</div>
						</div>
					</form>
                    </div>
                </div>
            </div>
		</div>
	</section>



@endsection
    

  
  

    
