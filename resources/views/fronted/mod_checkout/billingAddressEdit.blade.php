@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrumb')
<li class="breadcrumb-item" ><a href="{{ route('index')}}">Home</a></li>
<li class="breadcrumb-item" ><a href="{{ route('checkout')}}">Checkout</a></li>
<li class="breadcrumb-item active" ><a href="javascript:void(0)">Update Billing Address</a></li>
    @section('breadcrumb_title', 'Update Billing Address')
@endsection

	<section class="wrap main_section">
		<div class="container">

			

				<div class="row">
                    <div class="col-lg-12 col-sm-12 col-md-12 col-12 pt-4 pb-4">
                        <div class="account_dashboard profile_dashboard">
                        <div class="page-header">
                            <h2 class="heading mb-30">Edit billing address</h2>
                        </div>
						<!--<p>Be sure to click "Deliver to this address" when you've finished.</p>-->


                            <div class="profile_form ">
						<form class="row mt30" action="{{route('editBillingAddress',base64_encode($id))}}" method="post">
						      @csrf
									

									<div class="col-12 col-sm-6 col-md-4 col-lg-4">
										<div class="form-group">
                                            <label>Email ID</label>
										<input type="email" class="form-control" name="shipping_email" value="{{$shipping_data->shipping_email}}" placeholder="Email Id">
										@if ($errors->has('shipping_email'))
												<span class="text-danger">{{ $errors->first('shipping_email') }}</span>
										@endif
									</div>
									
									</div>

									<div class="col-12 col-sm-6 col-md-4 col-lg-4">
										<div class="form-group">
                                            <label>First Name</label>
											<input type="text" class="form-control" name="shipping_name" value="{{$shipping_data->shipping_name}}" placeholder="First Name">
											@if ($errors->has('shipping_name'))
												<span class="text-danger">{{ $errors->first('shipping_name') }}</span>
											@endif
										</div>
									</div>

									<div class="col-12 col-sm-6 col-md-4 col-lg-4">
										<div class="form-group">
                                            <label>Last Name</label>
											<input type="text" class="form-control" name="shipping_last_name" value="{{$shipping_data->shipping_last_name}}" placeholder="Last Name">
											@if ($errors->has('shipping_last_name'))
												<span class="text-danger">{{ $errors->first('shipping_last_name') }}</span>
											@endif
										</div>
									</div>


						<div class="col-12 col-sm-6 col-md-4 col-lg-4">
							<div class="form-group">
                                <label>Mobile Number</label>
								<input type="text" class="form-ctrl form-control" name="shipping_mobile" value="{{$shipping_data->shipping_mobile}}" id=" " placeholder="Mobile Number" onkeypress="javascript:return onlyPincodeDigit(event,this.value,9)">
								@if ($errors->has('shipping_mobile'))
				                <span class="text-danger">{{ $errors->first('shipping_mobile') }}</span>
								@endif
							</div>
						</div>

						<div class="col-12 col-sm-6 col-md-4 col-lg-4">
							<div class="form-group">
                                <label>Pincode</label>
								<input type="text" class="form-ctrl form-control" name="shipping_pincode" value="{{$shipping_data->shipping_pincode}}" id=" " placeholder="Pincode" onkeypress="javascript:return onlyPincodeDigit(event,this.value,5)">
								@if ($errors->has('shipping_pincode'))
												<span class="text-danger">{{ $errors->first('shipping_pincode') }}</span>
											@endif
							</div>
						</div>

						<div class="col-12 col-sm-6 col-md-4 col-lg-4">
							<div class="form-group">
                                <label>Address</label>
								<input type="text" class="form-ctrl form-control" name="shipping_address" id=" " value="{{$shipping_data->shipping_address}}"  placeholder="Address">
								@if ($errors->has('shipping_address'))
									<span class="text-danger">{{ $errors->first('shipping_address') }}</span>
								@endif
							</div>
						</div>

						{{--
							<div class="col-12 col-sm-6 col-md-4 col-lg-4">
								<div class="form-group">
									<input type="text" class="form-ctrl form-control" name="shipping_address1" id=" " value="{{$shipping_data->shipping_address1}}" placeholder="Area, Colony, Street, Sector, Village">
								</div>
							</div>
							<div class="col-12 col-sm-6 col-md-4 col-lg-4">
								<div class="form-group">
									<input type="text" class="form-ctrl form-control" name="shipping_address2" value="{{$shipping_data->shipping_address2}}" id=" " placeholder="Landmark e.g. near apollo hospital">
								</div>
							</div>
						--}}


							<div class="col-12 col-sm-6 col-md-4 col-lg-4">
							<div class="form-group editionaldropdn">
                                <label>State</label>
							<select class="form-ctrl form-control"  name="shipping_state" id="selectState" >
							<option value="">Select State  </option>
							@foreach($states as $state)
							<option value="{{$state->id}}"
							<?php echo ($shipping_data->shipping_state==$state->name)?"selected":""?>
								>{{$state->name}}</option>
							@endforeach
							</select>

							@if ($errors->has('shipping_state'))
								<span class="text-danger">{{ $errors->first('shipping_state') }}</span>
							@endif

							</div>
						</div>

						<div class="col-12 col-sm-6 col-md-4 col-lg-4">
							<div class="form-group editionaldropdn">
                                <label>City</label>
                                <select class="form-control" name="shipping_city" id="selectcity">
                                <option value="">Select City</option>
                                @foreach($cities as $city)
                                <option value="{{$city->name}}"
                                <?php echo ($shipping_data->shipping_city==$city->name)?"selected":""?>
                                    >{{$city->name}}</option>
                                @endforeach
                                </select>

                                @if ($errors->has('shipping_city'))
                                    <span class="text-danger">{{ $errors->first('shipping_city') }}</span>
                                @endif

							</div>
						</div>

						<div class="col-12 col-sm-6 col-md-4 col-lg-4">
							<div class="form-group">
                                <label>Address Type</label>
								<select name="shipping_address_type" class="form-ctrl form-control" id="sel1">
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

								@if ($errors->has('shipping_address_type'))
									<span class="text-danger">{{ $errors->first('shipping_address_type') }}</span>
								@endif
								
							</div>
						</div>
                        <?php //if(sizeof($shipping_listing)>0){?>
                        	<div class="col-12 col-sm-6 col-md-4 col-lg-4">
							<div class="form-group switch_box">
                                <div class="form-check form-switch">
                                    <input type="checkbox" id="default_address" class="form-check-input" name="shipping_address_default"
                                    <?php echo ($shipping_data->shipping_address_default==1)?'checked':'';?>

                                />
                                    <label class="form-check-label" for="default_address"> Set default address</label>
                                </div>
								
							</div>

						</div>
                        <?php //} else{?>
                       <!--<input type="hidden" name="shipping_address_default" value="1"/>-->
                        <?php //}?>

						<div class="col-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<button type="submit" value="submit" class="btn btn-warning">Update</button>
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






