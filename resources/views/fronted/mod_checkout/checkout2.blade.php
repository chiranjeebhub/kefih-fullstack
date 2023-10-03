@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum')
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Checkoutdd</a>
@endsection

	<section class="main_section checkout-section">
		<div class="container">

            <div class="row">
                <div class="col-lg-12 col-sm-12 col-md-12 col-12 pt-4">
                    <h2 class="heading mb-30">Select a delivery address</h2>
                    <div class="card_main">
                        <div class="row">
                        <?php for($i=0;$i<count($shipping_listing);$i++){?>

                            <div class="col-md-4 col-xs-12">
                                <div class="row">
                                    <div class="col-lg-9 col-sm-9 col-md-10 col-8">
                                        <div class="card_box">

                                            <!--<div class="location_icon">
                                                <i class="fa fa-map-marker"></i>
                                            </div>-->
                                            <div class="card_info">
                                                <h2><?php echo ucwords($shipping_listing[$i]['shipping_name']);?></h2>
                                                <p>	<?php echo $shipping_listing[$i]['shipping_address'];?>,
                                                    <?php echo $shipping_listing[$i]['shipping_address1'];?>
                                                    <?php echo $shipping_listing[$i]['shipping_address2'];?><br>
                                                    <?php echo $shipping_listing[$i]['shipping_city'];?>
                                                    <?php echo $shipping_listing[$i]['shipping_state'];?> : <?php echo $shipping_listing[$i]['shipping_pincode'];?></p>
                                                <a class="btn btn-warning" href="{{route('selectShippingAddress',base64_encode($shipping_listing[$i]['id']))}}" class="btnn">Deliver to this address</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-3 col-md-2 col-4 d-flex">

                                        <div class="remove_card">
                                            <?php  if($shipping_listing[$i]['shipping_address_default']==1){?>
                                            <span class="defaultbox">Default</span>
                                            <?php }?>

                                            <a href="{{route('editShippingAddress',base64_encode($shipping_listing[$i]['id']))}}"
                                    onclick = "if (! confirm('Do you want to edit ?')) { return false; }" class="editbtn pull-left"><i class="fa fa-pencil"></i> </a>
                                            <a href="{{route('removeShippingAddress',base64_encode($shipping_listing[$i]['id']))}}"
                                    onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
                                    class="editbtn pull-right"><i class="fa fa-trash"></i> </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    <?php } ?>
                            </div>

                    </div>
                </div>
                <div class="col-lg-12 col-sm-12 col-md-12 col-12 pt-4 pb-4">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <span class="help-block">
                            <p style="color:red">{{$error}}</p>
                        </span>
                    @endforeach
                @endif

                @php
                $plusminus = ($errors->any())?'minus':'plus';
                $plusminusmethod = ($errors->any())?1:0;
                $isDisplay =  ($errors->any())?'style="display:block"':'style="display:none"';
                @endphp

                    <div class="profile_form ">
                        <h2 class="heading mb-30">Add New Address <i class="fa fa-{{$plusminus}} iconpointer addAddressForm"
						method="{{$plusminusmethod}}"></i></h2>
                        <div id="myAddressForm" {!! $isDisplay !!}>
                            <form class="row" action="{{route('addShippingAddress')}}" method="post">
						      @csrf

						<div class="col-lg-4 col-sm-12 col-md-4 col-12">
							<div class="form-group">
                                <label>Full Name</label>
								<input type="text" class="form-control" name="shipping_name" placeholder="Enter Name">
							</div>
						</div>

						<div class="col-lg-4 col-sm-12 col-md-4 col-12">
							<div class="form-group">
                                <label>Mobile Number</label>
								<input type="text" class="form-control" name="shipping_mobile" id=" " placeholder="9999999999" onkeypress="javascript:return onlyPincodeDigit(event,this.value,9)">
							</div>
						</div>

						<div class="col-lg-4 col-sm-12 col-md-4 col-12">
							<div class="form-group">
                                <label>Pincode</label>
								<input type="text" class="form-control" name="shipping_pincode" id=" " placeholder="000 000" onkeypress="javascript:return onlyPincodeDigit(event,this.value,5)">
							</div>
						</div>

						<div class="col-lg-12 col-sm-12 col-md-12 col-12">
							<div class="form-group">
                                <label>Address Line 1</label>
								<input type="text" class="form-control" name="shipping_address" id=" " placeholder="Flat, House no., Building, Company, Apartment">
							</div>
						</div>

						<div class="col-lg-12 col-sm-12 col-md-12 col-12">
							<div class="form-group">
                                <label>Address Line 2</label>
								<input type="text" class="form-control" name="shipping_address1" id=" " placeholder="Area, Colony, Street, Sector, Village">
							</div>
						</div>
						<div class="col-lg-12 col-sm-12 col-md-12 col-12">
							<div class="form-group">
                                <label>Address Line 3</label>
								<input type="text" class="form-control" name="shipping_address2" id=" " placeholder="Landmark e.g. near apollo hospital">
							</div>
						</div>
				        <div class="col-lg-4 col-sm-12 col-md-4 col-12">
							<div class="form-group">
                                <label>State</label>
								<select class="form-control custom-select" name="shipping_state" id="selectState">
								<option value="">Select State</option>
								@foreach($states as $state)
								<option value="{{$state->id}}">{{$state->name}}</option>
								@endforeach
								</select>

							</div>
						</div>

						<div class="col-lg-4 col-sm-12 col-md-4 col-12">
							<div class="form-group editionaldropdn">
                                <label>City</label>
                                <select class="form-control" name="shipping_city" id="selectcity">
                                    <option value="">Select City</option>
                                </select>
							</div>
						</div>

						<div class="col-lg-4 col-sm-12 col-md-4 col-12">
							<div class="form-group">
                                <label>Address Type</label>
								<select name="shipping_address_type" class="form-control custom-select" id="sel1">
									<option value="">Select Address Type</option>
									<option value="1">Home</option>
									<option value="2">Office</option>
										<option value="3">Others</option>
								</select>
							</div>
						</div>
						<div class="col-lg-12 col-sm-12 col-md-12 col-12">
							<div class="form-group">
									<?php if(sizeof($shipping_listing)>0){?>

                                        <div class="form-group switch_box">
                                            <div class="form-check form-switch">
                                              <input class="form-check-input" type="checkbox" name="payment_mode" id="flexSwitchCheckChecked" value="0">
                                              <label class="form-check-label" for="flexSwitchCheckChecked">Make Default Address</label>

                                            </div>
                                        </div>

                                            <!--<div class="paymnetthod">
                                            <div class="checkbox checkbox-circle">
                                            <input id="default_address" name="payment_mode" type="checkbox" checked="" value="0">
                                            <label for="default_address"> Make Default Card</label>
                                            </div>
                                            </div>-->
                            <?php } else{?>
                            <input type="hidden" name="shipping_address_default" value="1"/>
                            <?php }?>


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






