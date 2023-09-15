@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="{{ route('shippingDetails')}}">My Address</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Edit Address</a>
@endsection   


<section class="dashbord-section">
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
<div class="dashbordlinks">
<h6 class="fs18 fw600 mb20">Delivery address <span id="account-btn">
            <i class="fa fa-navicon"></i></span></h6>    
	               <ul  id="mobile-show">
						@include('fronted.mod_account.dashboard-menu')
					</ul>    
</div>    
</div>  
<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
<div class="dashbordtxt">
<h6 class="fs18 fw600 mb20">Update delivery address</h6> 
    
 <div class="text-style">
			
					<div class="shippingDetails text-style">
				
				<div class="row">
					<div class="col-md-12 col-xs-12">
						
						
						<form class="row" action="{{route('editShippingDetailsAddress',base64_encode($id))}}" method="post">
						      @csrf
	<!--					      	    @if ($errors->any())-->
 <!--    @foreach ($errors->all() as $error)-->
 <!--        <span class="help-block">-->
	<!--		<p style="color:red">{{$error}}</p>-->
	<!--	</span>-->
 <!--    @endforeach-->
 <!--@endif-->

						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group">
							<label>Full Name</label>
								<input type="text" class="form-control" name="shipping_name" value="{{$shipping_data->shipping_name}}" placeholder="Full name">
								 @if($errors->has('shipping_name'))
                                    <span style="color:red">  {{ $errors->first('shipping_name') }}</span>


                                @endif
							</div>
						</div>

						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group">
							<label>Mobile No.</label>
								<input type="text" class="form-control" name="shipping_mobile" value="{{$shipping_data->shipping_mobile}}" id=" " placeholder="Mobile Number" onkeypress="javascript:return onlyPincodeDigit(event,this.value,9)">
						  @if($errors->has('shipping_mobile'))
                                    <span style="color:red">  {{ $errors->first('shipping_mobile') }}</span>


                                @endif
							</div>
						</div>

						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group">
							<label>Pin code</label>
								<input type="text" class="form-control" name="shipping_pincode" value="{{$shipping_data->shipping_pincode}}" id=" " placeholder="Pincode" onkeypress="javascript:return onlyPincodeDigit(event,this.value,5)">
							  @if($errors->has('shipping_pincode'))
                                    <span style="color:red">  {{ $errors->first('shipping_pincode') }}</span>


                                @endif
							</div>
						</div>

						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group">
							<label>Flat, House no., Building, Company</label>
								<input type="text" class="form-control" name="shipping_address" id=" " value="{{$shipping_data->shipping_address}}"  placeholder="Flat, House no., Building, Company, Apartment">
							  @if($errors->has('shipping_address'))
                                    <span style="color:red">  {{ $errors->first('shipping_address') }}</span>


                                @endif
							</div>
						</div>

						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group">
							<label>Area, Colony, Street, Sector, Village</label>
								<input type="text" class="form-control" name="shipping_address1" id=" " value="{{$shipping_data->shipping_address1}}" placeholder="Area, Colony, Street, Sector, Village">
								  @if($errors->has('shipping_address1'))
                                    <span style="color:red">  {{ $errors->first('shipping_address1') }}</span>


                                @endif
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group">
							<label>Landmark e.g. near</label>
								<input type="text" class="form-control" name="shipping_address2" value="{{$shipping_data->shipping_address2}}" id=" " placeholder="Landmark e.g. near apollo hospital">
							  @if($errors->has('shipping_address2'))
                                    <span style="color:red">  {{ $errors->first('shipping_address2') }}</span>


                                @endif
							</div>
						</div>
						
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group">
							<label>State</label>
<select class="form-control custom-select"  name="shipping_state" id="selectState" >
<option value="">Select State </option>
@foreach($states as $state)
<option value="{{$state->id}}"
<?php echo ($shipping_data->shipping_state==$state->name)?"selected":""?>
    >{{$state->name}}</option>
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
<select class="form-control " name="shipping_city" id="selectcity">
<option value="">Select City</option>
@foreach($cities as $city)
<option value="{{$city->name}}"
<?php echo ($shipping_data->shipping_city==$city->name)?"selected":""?>
    >{{$city->name}}</option>
@endforeach
</select>
	 @if($errors->has('shipping_city'))
                                    <span style="color:red">  {{ $errors->first('shipping_city') }}</span>


                                @endif
							</div>
						</div>
						
					
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group">
							<label>Address</label>
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
									 @if($errors->has('shipping_address_type'))
                                    <span style="color:red">  {{ $errors->first('shipping_address_type') }}</span>


                                @endif
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
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
    

  
  

    
