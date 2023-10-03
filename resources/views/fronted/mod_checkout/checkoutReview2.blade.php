@extends('fronted.layouts.app_new')
@section('content')
    @section('breadcrum')
        <a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
        <a href="javascript:void(0)">Review Order</a>
    @endsection
    <?php
    $timeslot=DB::table('tbl_timeslot')->where('status',1)->get();

    ?>


    <div class="navigation">
        <div class="navLeft">
            <img src="assets/images/logo.svg" alt="logo">
            <div class="toggleWrapper">
                <div class="toggleText">Exhibition</div>
                <div class="toggle-switch">
                    <input type="checkbox" id="toggle" class="toggle-input" checked>
                    <label for="toggle" class="toggle-label"></label>
                </div>
                <div class="toggleText">Online</div>
            </div>
        </div>
        <div class="navRight">
            <span>Cart</span>
            <span style="padding: 0px 5px;">&rsaquo;</span>
            <span style="font-weight: bold;">Information</span>
            <span style="padding: 0px 5px;">&rsaquo;</span>
            <span>Payment</span>
        </div>

    </div>
    <div class="checkoutContainer">
        <div class="leftSection">
            <div class="headerTitleContainer">
                <div class="headerTitle">Contact Information</div>
                <div class="headerSubtitle">Already Bought Something? <span style="font-weight: bold;">Click Here</span>
                </div>
            </div>
            <div class="div-7">
                <div class="div-8">
                    <div class="a-2">Phone Number</div>
                    <div class="a-3" style="display: flex; align-items: center;">
                        <div style="padding-right: 10px; margin-top: 2px;">+91</div>
                        <div>
                            <input class="inputStyle" type="text" id="name">
                        </div>
                    </div>
                </div>
                <div class="div-9">
                    <div class="a-4">Email (Optional)</div>
                    <input class="inputStyle" type="text" id="name">
                </div>
            </div>
            <div class="headerTitleContainer">
                <div class="headerTitle">Shipping Address</div>
            </div>
            <div class="div-7">
                <div class="div-9">
                    <div class="a-4">Country</div>
                    <input class="inputStyle" type="text" id="name" disabled placeholder="India">
                </div>
            </div>
            <div class="div-7">

                <div class="div-9">
                    <div class="a-4">First Name</div>
                    <input class="inputStyle" type="text" id="name">
                </div>
                <div class="div-9">
                    <div class="a-4">Last Name</div>
                    <input class="inputStyle" type="text" id="name">
                </div>
            </div>
            <div class="div-7">
                <div class="div-9">
                    <div class="a-4">Address</div>
                    <input class="inputStyle" type="text" id="name">
                </div>
            </div>
            <div class="div-7">
                <div class="div-9">
                    <div class="a-4">Apartment, suite, etc. (optional)</div>
                    <input class="inputStyle" type="text" id="name">
                </div>
            </div>
            <div class="div-7">

                <div class="div-9">
                    <div class="a-4">City</div>
                    <input class="inputStyle" type="text" id="name">
                </div>
                <div class="div-9">
                    <div class="a-4">State</div>
                    <input class="inputStyle" type="text" id="name">
                </div>
                <div class="div-9">
                    <div class="a-4">Pin Code</div>
                    <input class="inputStyle" type="text" id="name">
                </div>
            </div>
            <div class="checkboxWrapper">

                <input type="checkbox" size="large" id="checkbox" class="checkbox-input" checked="true">
                <div style="padding-left: 10px;">
                    Billing Address Same As Shipping Address
                </div>
            </div>
            <!-- <div class="inputWrapper">
                <div class="inputBoxHalf">
                    <div class="inputLable">Phone Number</div>
                    <div style="display: flex; align-items: center;">
                        <div>+91</div>
                        <input class="inputStyle" type="text" id="name" placeholder="Enter your name">
                    </div>
                </div>
                <div class="inputBoxHalf">
                    <div class="inputLable">Phone Number</div>
                    <input class="inputStyle" type="text" id="name" placeholder="Enter your name">
                </div>
            </div> -->
        </div>
        <div class="rightSection">
            <div class="couponTypeWarpepr">
                <div class="couponType">Personalized</div>
                <div class="couponType">General</div>
            </div>
            <div class="couponInputWrapper">
                <input class="inputStyle" type="text" id="name" placeholder="Enter Coupon Code">
                <button class="applyBtn">Redeem</button>
            </div>
            <h2 class="cartHeader" style="font-size: 18px;">Items</h2>
            <div class="productCardWrapper1">
                <div class="productCard">
                    <div class="productCardImage">
                        <img src="assets/images/1.png" alt="product">
                    </div>
                    <div class="productCardDetails">
                        <div>
                            <div class="productCardTitle">15 Buttons</div>
                            <p class="productCardSubTitle">Blue Pinstripe Co-ord #15Bb81</p>
                        </div>
                        <div class="bottomRow">
                            <div class="productCardPrice">
                                <div class="sellingPrice">₹205</div>
                                <div class="mrp">₹215</div>
                            </div>
                            <div class="countWrapper">
                                <button class="minusBtn">-</button>
                                <div class="quantity">1</div>
                                <button class="plusBtn">+</button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="productCard">
                    <div class="productCardImage">
                        <img src="assets/images/2.png" alt="product">
                    </div>
                    <div class="productCardDetails">
                        <div>
                            <div class="productCardTitle">Agati</div>
                            <p class="productCardSubTitle">Arca Peach and brown yarn weaved top and skirt </p>
                        </div>
                        <div class="bottomRow">
                            <div class="productCardPrice">
                                <div class="sellingPrice">₹195</div>
                                <div class="mrp">₹215</div>
                            </div>
                            <div class="countWrapper">
                                <button class="minusBtn">-</button>
                                <div class="quantity">1</div>
                                <button class="plusBtn">+</button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="productCard">
                    <div class="productCardImage">
                        <img src="assets/images/2.png" alt="product">
                    </div>
                    <div class="productCardDetails">
                        <div>
                            <div class="productCardTitle">Agati</div>
                            <p class="productCardSubTitle">Arca Peach and brown yarn weaved top and skirt </p>
                        </div>
                        <div class="bottomRow">
                            <div class="productCardPrice">
                                <div class="sellingPrice">₹195</div>
                                <div class="mrp">₹215</div>
                            </div>
                            <div class="countWrapper">
                                <button class="minusBtn">-</button>
                                <div class="quantity">1</div>
                                <button class="plusBtn">+</button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="productCard">
                    <div class="productCardImage">
                        <img src="assets/images/2.png" alt="product">
                    </div>
                    <div class="productCardDetails">
                        <div>
                            <div class="productCardTitle">Agati</div>
                            <p class="productCardSubTitle">Arca Peach and brown yarn weaved top and skirt </p>
                        </div>
                        <div class="bottomRow">
                            <div class="productCardPrice">
                                <div class="sellingPrice">₹195</div>
                                <div class="mrp">₹215</div>
                            </div>
                            <div class="countWrapper">
                                <button class="minusBtn">-</button>
                                <div class="quantity">1</div>
                                <button class="plusBtn">+</button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="productCard">
                    <div class="productCardImage">
                        <img src="assets/images/2.png" alt="product">
                    </div>
                    <div class="productCardDetails">
                        <div>
                            <div class="productCardTitle">Agati</div>
                            <p class="productCardSubTitle">Arca Peach and brown yarn weaved top and skirt </p>
                        </div>
                        <div class="bottomRow">
                            <div class="productCardPrice">
                                <div class="sellingPrice">₹195</div>
                                <div class="mrp">₹215</div>
                            </div>
                            <div class="countWrapper">
                                <button class="minusBtn">-</button>
                                <div class="quantity">1</div>
                                <button class="plusBtn">+</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div id="subTotalSection" class="subTotalSection" style="padding-top: 20px;">
                <div class="subTotalTitle">Order Summary</div>
                <div class="subTotalCard">
                    <div class="subItemWrapper1">

                        <div class="subItem">
                            <div>15 Buttons (1)</div>
                            <div>₹205</div>
                        </div>
                        <div class="subItem">
                            <div>Agati (1)</div>
                            <div>₹195</div>
                        </div>
                        <div class="subItem">
                            <div>Agati (1)</div>
                            <div>₹195</div>
                        </div>
                        <div class="subItem">
                            <div>Agati (1)</div>
                            <div>₹195</div>
                        </div>
                        <div class="subItem">
                            <div>Agati (1)</div>
                            <div>₹195</div>
                        </div>
                    </div>
                    <div class="divider">&nbsp;</div>
                    <div class="subTotal">
                        <div>Subtotal</div>
                        <div class="toatlValue">₹985</div>
                    </div>
                </div>
                <div class="paymentBtn">Proceed To Pay</div>
            </div>
            <div id="subTotalSection1" class="subTotalSection1">
                <div class="subTotalTitle">Order Summary</div>
                <div class="couponInputWrapper1">
                    <input class="inputStyle" type="text" id="name" placeholder="Enter Amount">
                </div>

                <div class="buttonGrp">
                    <div class="paymentMode" onclick="selectPaymentMode(this)">Cash</div>
                    <div class="paymentMode" onclick="selectPaymentMode(this)">UPI</div>
                    <div class="paymentMode" onclick="selectPaymentMode(this)">Other</div>
                </div>
                <div class="paymentBtnOffline">Confirm Order</div>
            </div>
        </div>
    </div>


    <section class="wrap inr-wrap-tp cartwrap main_section pt-4 pb-4">
        <div class="container-fluid">
            <!--@include('admin.includes.session_message') -->
            <div class="checoutform_main text-style">

                <div class="row">
                    <div class="col-md-12 noProductIncart" style="display:none;">
                        <h3>No Product Added to Cart</h3>
                        <a href="{{route('index')}}" class="btn btn-danger btn-lg btn-lg-14 mt10">Continue Shopping</a>
                    </div>


                    <div class="col-sm-12 col-md-12 col-lg-8 productIncart">
                        <div class="form-box checkout cartTable">
                            <div class="paymentrightcheckout">
                                <!--<h3>Payment</h3>-->
                                <div class="AddnewAddres">
                                    <div class="form-group">
                                        <div class="check-box-shadow">
                                            <div class="form-check form-check-right" data-bs-toggle="collapse" data-bs-target="#Exhibition">
                                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
                                                <label class="form-check-label" for="flexRadioDefault2">
                                                    Exhibition
                                                </label>
                                            </div>
                                            <div class="collapse" id="Exhibition">
                                                <div class="form-group security_code">
                                                    <label>Enter your security Code</label>
                                                    <div class="d-flex" id="exbcontainer">
                                                        <input type="text" class="form-ctrl form-control" id="ExhibitionCode" placeholder="Security Code">
                                                        <span class="verifiedcheck" style="display:none"><i class="fa fa-check"></i></span>

                                                        <div onclick="javascript:void(0);" class="btn btn-warning" id="ExhibitionVerifyBtn"><a style="color:#000;"  href="javascript:void(0);">Verify</a></div>
                                                    </div>
                                                    <div class="" id="exhibtn_res"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="review_ordr_box">
                                @if($shipping_address)
                                    <div class="review_ordr_left">
                                        <h4 class="title-shopping-cart">Deliver to</h4>
                                        <div class="addinfo">
                                            <p class="nameline"><?php echo ucwords($shipping_address->shipping_name);?></p>
                                            <p><?php echo $shipping_address->shipping_address1;?>, <?php echo $shipping_address->shipping_address2;?>, <?php echo $shipping_address->shipping_city;?>, <?php echo $shipping_address->shipping_state;?> : <?php echo $shipping_address->shipping_pincode;?></p>
                                        </div>
                                    </div>
                                @endif()





                                <div class="review_ordr_center hidden-xs"></div>
                                <div class="review_ordr_right">
                                    <div class=" ">
                                        <a href="{{route('checkout')}}">
                                            <img src="{{ asset('public/fronted/images/plus-checkout.png') }}" />
                                            <span class="changeaddtext">
                               @if($shipping_address)
                                                    Change Address
                                                @else
                                                    Add Address
                                                @endif()
                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>


                            <!-- @if(!empty($billing_adddress))
                                <div class="review_ordr_box">
                               <div class="review_ordr_left" style="{{($is_review == true)?'display: block':'display: none'}}">
            <h4 class="title-shopping-cart">Billing Address</h4>
             <div class="addinfo">
                 <h6>{{ucwords($billing_adddress->shipping_name)}}</h6>
                   <p>
                    {{$billing_adddress->shipping_address}}
                                ,{{$billing_adddress->shipping_city}}
                                ,{{$billing_adddress->shipping_state}}
                                -{{$billing_adddress->shipping_pincode}}</p>
                    {{--
                    <div class="purchase-detail-form-btn">
                        <a href="{{route('editBillingAddress',base64_encode($billing_adddress->id))}}"
                        class="btn btn-light">Edit</a>
                        <a href="{{route('billingAddresses')}}" class="btn btn-light">Change address</a>
                    </div>
                    --}}
                                </div>
                          </div>

                              <div class="review_ordr_center hidden-xs" style="{{($is_review == true)?'display: block':'display: none'}}"></div>
                <div class="review_ordr_right" style="{{($is_review == true)?'display: block':'display: none'}}">
                    <div class=" ">
                        <a href="{{route('billingAddresses')}}">
                            <img src="{{ asset('public/fronted/images/plus-checkout.png') }}" />
                            <span class="changeaddtext">
                                @if($billing_adddress)
                                    Change Billing Address
@else
                                    Add Billing Address
@endif()
                                </span>
                        </a>
                    </div>
                </div>

        </div>
        @endif  -->



                            <div class="AddnewAddres">
                                <div class="form-group">
                                    <div class="check-box-shadow">
                                        <div class="form-check form-check-right">
                                            <input class="form-check-input addressType" type="radio" name="billingaddresstype" id="flexRadiobillingaddressameasabove" value="default" checked>
                                            <label class="form-check-label" for="flexRadiobillingaddressameasabove">
                                                Billing Address is same as above
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="AddnewAddres">
                                <div class="form-group">
                                    <div class="check-box-shadow">
                                        <div class="form-check form-check-right" data-bs-toggle="collapse" data-bs-target="#newbillingaddress">
                                            <input class="form-check-input addressType" type="radio" name="billingaddresstype" value="billing" id="flexRadionewbillingaddress" {{($is_review == true)?'checked':''}}>
                                            <label class="form-check-label" for="flexRadionewbillingaddress">
                                                New Billing Address
                                            </label>
                                        </div>
                                        <div class="{{($is_review == true)?'collapse show':'collapse'}}" id="newbillingaddress">

                                            @if(!empty($billing_adddress))
                                                <div class="review_ordr_box">
                                                    <div class="">
                                                        <div class="addinfo">
                                                            <h6>{{ucwords($billing_adddress->shipping_name)}} {{ucwords($billing_adddress->shipping_last_name)}}</h6>
                                                            <p>
                                                                {{$billing_adddress->shipping_address}}
                                                                ,{{$billing_adddress->shipping_city}}
                                                                ,{{$billing_adddress->shipping_state}}
                                                                -{{$billing_adddress->shipping_pincode}}</p>
                                                            <div class="purchase-detail-form-btn">
                                                                <a href="{{route('editBillingAddress',base64_encode($billing_adddress->id))}}"
                                                                   class="btn btn-light">Edit</a>
                                                                <a href="{{route('billingAddresses')}}" class="btn btn-light">Change address</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif


                                            <!-- Billing Address Form Start -->
                                            <div class="AddnewAddres">
                                                <form action="{{route('addBillingAddress')}}" method="post">
                                                    <input type="hidden" name="cfrom" value="review">
                                                    <div class="card card-body profile_form">

                                                        <div class="row">
                                                            <div class="col-sm-6 col-md-6">
                                                                <div class="form-group">
                                                                    <label>First Name</label>
                                                                    <input type="text" class="form-control"
                                                                           placeholder="First Name" name="shipping_name" id="shipping_name">
                                                                    <div id="shipping_name_error" class="text-danger"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 col-md-6">
                                                                <div class="form-group">
                                                                    <label>Last Name</label>
                                                                    <input type="text" class="form-control"
                                                                           placeholder="Last Name" name="shipping_last_name" id="shipping_last_name">
                                                                    <div id="shipping_last_name_error" class="text-danger"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 col-md-6">
                                                                <div class="form-group">
                                                                    <label>Email ID</label>
                                                                    <input type="text" class="form-control" placeholder="Email Id"
                                                                           name="shipping_email" id="shipping_email">
                                                                    <div id="shipping_email_error" class="text-danger"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 col-md-6">
                                                                <div class="form-group">
                                                                    <label>Mobile Number</label>
                                                                    <input type="number" class="form-control" name="shipping_mobile" id="shipping_mobile"
                                                                           placeholder="Mobile Number"
                                                                           onkeypress="javascript:return onlyPincodeDigit(event,this.value,9)">
                                                                    <div id="shipping_mobile_error" class="text-danger"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Address</label>
                                                            <input type="text" class="form-control" placeholder="Address"
                                                                   name="shipping_address" id="shipping_address">
                                                            <div id="shipping_address_error" class="text-danger"></div>
                                                        </div>


                                                        <div class="row">
                                                            <div class="col-sm-6 col-md-4">
                                                                <div class="form-group editionaldropdn">
                                                                    <!--custom-select-->
                                                                    <label>State</label>
                                                                    <select class="form-control mySelect2" name="shipping_state"
                                                                            id="selectState">
                                                                        <option value="">Select State</option>
                                                                        @foreach($states as $state)
                                                                            <option value="{{$state->id}}">{{$state->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div id="selectState_error" class="text-danger"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 col-md-4">

                                                                <div class="form-group editionaldropdn">
                                                                    <label>City</label>
                                                                    <select class="form-control" name="shipping_city"
                                                                            id="selectcity">
                                                                        <option value="">Select City</option>
                                                                    </select>
                                                                    <div id="selectcity_error" class="text-danger"></div>
                                                                </div>

                                                            </div>
                                                            <div class="col-sm-6 col-md-4">
                                                                <div class="form-group">
                                                                    <label>Pincode</label>
                                                                    <input type="number" class="form-control" name="shipping_pincode"
                                                                           id="shipping_pincode"
                                                                           placeholder="Pincode"
                                                                           onkeypress="javascript:return onlyPincodeDigit(event,this.value,5)">
                                                                    <div id="shipping_pincode_error" class="text-danger"></div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Address Type</label>
                                                            <select name="shipping_address_type" class="form-control custom-select"
                                                                    id="shipping_address_type">
                                                                <option value="">---Select Address Type---</option>
                                                                <option value="1">Home</option>
                                                                <option value="2">Office</option>
                                                                <option value="3">Others</option>
                                                            </select>
                                                            <div id="shipping_address_type_error" class="text-danger"></div>

                                                        </div>

                                                        <div class="form-group switch_box">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" name="shipping_address_default" type="checkbox" value="" id="invalidCheck1" value="1">
                                                                <label class="form-check-label label-trms" for="invalidCheck1">
                                                                    Save  as default address
                                                                </label>
                                                                <div id="invalidCheck1_error" class="text-danger"></div>

                                                            </div>
                                                        </div>
                                                        @csrf
                                                        <button class="btn btn-warning getDataforCheck">Save & Use Address</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- Billing Address Form End -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-xs-12 reviewOrderBackmain mt-3">
                                <div class="checkoutdatalistbox">
                                    <div class="db-2-main-com-table ">
                                        <div class="reviewOrderBackResponse">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <a href="{{route('index')}}" class="btn btn-warning btn-lg mb20 mt-4">Continue Shopping</a>
                        </div>

                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-4 productIncart">
                        <div class="summary"><!--ordersummery-->
                            <form role="form" class="form-element" action="{{ route('thankyou')}}" method="post" enctype="multipart/form-data" name="quotation">
                                @csrf
                                <div class="form-coupan">
                                    <div class="coupanleftBox">
                                        <div class="promotitle">
                                            <label>Promo Code</label> <span><a href="{{route('couponlist')}}" title="View all vailable coupon codes" >View promo code</a></span>
                                        </div>
                                        <input type="text" class="form-control form-ctrl couponcode_back"  id="Coupon_code" placeholder="Enter here" value="">
                                        <span class="couponerrormsg" id="CouponMsg_code"></span>

                                    </div>
                                    <button type="submit" id="cartToalReview" class="btn blackbtn couponApply" index="code" cart_total="">Apply</button>
                                    <button class="btn btn-remove" style="display:none;" type="button" id="removeCopuonapply">Remove Coupon</button>

                                </div>

                                <!--<div class="checkout-element-content couponbox">

        <ul>
            <li><label class="inline">Enter your coupon code/Promo code | <a href="{{route('couponlist')}}" class="badge" title="View all vailable coupon codes" target="_blank">View</a></label></li>
            <li><input type="text" class="form-control couponcode_back"  id="Coupon_code" placeholder="Coupon / Promo code" value=""></li>
            <span id="CouponMsg_code"></span>
        </ul>
        <button type="submit" id="cartToalReview" class="btn blackbtn couponApply" index="code" cart_total="">Apply</button>

        <button style="display:none;" type="button" id="removeCopuonapply">Remove Coupon</button>


        </div>-->

                                <h3>Order Summary</h3>

                                <div class="Price-Details">
                                    <ul class="list-group">
                                        <li class="list-group-item align-items-center">Amount (<i class="cart_count">2</i> items) Tax Incl. <span class="pull-right"><i class="fa fa-rupee"></i><strong class="grand_total_with_out_tax_response">0</strong></span></li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">Coupon Discount <span><i class="fa fa-rupee"></i><strong class="discount_reponse">0</strong></span>
                                        </li>
                                        <!--<li class="list-group-item d-flex justify-content-between align-items-center slothide">Slot Price <span><i class="fa fa-rupee"></i><strong class="slodedisplay"></strong></span></li>-->
                                        <li class="list-group-item d-flex justify-content-between align-items-center">Shipping Charges <span><i class="fa fa-rupee"></i><strong class="shipping_charge_response">0</strong></span></li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center" style="display:none !important;">COD Charge <span><i class="fa fa-rupee"></i><strong id="cod_charge"></strong></span></li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">Service Charge <span><i class="fa fa-rupee"></i><strong id="service_charge">0</strong></span></li>

                                        <li class="list-group-item d-flex justify-content-between align-items-center switch_box coinsmain">
                                            <div class="checkbox form-check form-switch">

                                                <label class="form-check-label" for="checkboxwallet1">Wallet {{--<i class="fa fa-info-circle   coinsInfo"></i>--}} </label> <input id="checkboxwallet1" type="checkbox" name="wallet" class="form-check-input wallet_button" value="1">
                                            </div>
                                            <span><i class="fa fa-rupee"></i><strong class="reward_points_reponse">0</strong></span>


                                        </li>
                                    </ul>

                                    <ul class="list-group totalpay">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Total Amount
                                            <span><i class="fa fa-inr"></i> <strong class="grand_total_with_tax_response">0</strong> </span>
                                        </li>
                                    </ul>


                                    <ul class="list-group" id="exhibition_final_amount_section" style="display:none;">

                                    </ul>


                                    @if($shipping_address)
                                        <div class="cartshipingaddbox">
                                            <h4>Shipping Address:</h4>
                                            <p class="nameline"><strong><?php echo ucwords($shipping_address->shipping_name);?></strong></p>
                                            <p><?php echo $shipping_address->shipping_address1;?>, <?php echo $shipping_address->shipping_address2;?>, <?php echo $shipping_address->shipping_city;?>, <?php echo $shipping_address->shipping_state;?> : <?php echo $shipping_address->shipping_pincode;?></p>

                                            <a href="{{route('checkout')}}">

                    <span class="changeaddtext">
                       @if($shipping_address)
                            Change Address
                        @else
                            Add Address
                        @endif()
                    </span>
                                            </a>

                                        </div>
                                    @endif()
                                    <!--<div class="cartshipingaddbox">
                <h4>Billing Address:</h4>
                <p>Sr no 48/4 Washington DC, United States of America, USA - 1223421</p>
             </div>-->

                                </div>
                                <div class="mode-payment">
                                    <h4>Payment Method</h4>
                                    <input id="is_ba" name="is_ba" type="hidden" value="{{$is_review}}">
                                    <input id="grand_total" name="grand_total" type="hidden"  value="0">
                                    <input id="tax" name="tax" type="hidden"  value="0">
                                    <input id="shipping_charges" name="shipping_charges" type="hidden"  value="0">
                                    <input id="coupon_percent" name="coupon_percent" type="hidden"  value="0">
                                    <input id="coupon_code" name="coupon_code" type="hidden" class="couponcode_back" value="0">
                                    <input id="discount_amount" name="discount_amount" type="hidden"  value="0">
                                    <input id="wallet_amount" name="wallet_amount" type="hidden"  value="0">
                                    <input  name="slot_price" type="hidden" class="inputslotprice" value="0" readonly="">
                                    <div class="paymnetthod">
                                        <div id="_paymethods1">
                                            <div class="paymnetthodBox" id="codpayment">
                                                <div class="checkbox checkbox-circle">

                                                    <input class="form-check-input" id="radio1" name="payment_mode" checked="" type="radio"  value="0" onclick="payment_method(0)">
                                                    <label class="form-check-label" for="radio1">COD (Cash on delivery)</label> <img align=“absmiddle” src="{{ asset('public/fronted/images/cash-on-delivery.png') }}" class="pull-right" />
                                                </div>

                                            </div>
                                            <div class="paymnetthodBox">
                                                <div class="checkbox checkbox-circle">
                                                    <input class="form-check-input" id="radio2" name="payment_mode"  type="radio" value="1" onclick="payment_method(1)">
                                                    <label class="form-check-label" for="radio2">Online Payment</label> <img src="{{ asset('public/fronted/images/online-payment.png') }}" class="pull-right" />
                                                </div>
                                            </div>
                                        </div>

                                        <div id="_paymethods2" style="display:none">
                                            <div class="paymnetthodBox">
                                                <div class="checkbox checkbox-circle">
                                                    <input class="form-check-input" id="radio3" name="payment_mode"  type="radio" value="2" onclick="payment_method(2)">
                                                    <label class="form-check-label" for="radio3">Paytm</label> <img src="{{ asset('public/fronted/images/paytm.png') }}" class="pull-right" />
                                                </div>
                                            </div>

                                            <div class="paymnetthodBox">
                                                <div class="checkbox checkbox-circle">
                                                    <input class="form-check-input" id="radio4" name="payment_mode"  type="radio" value="3" onclick="payment_method(3)">
                                                    <label class="form-check-label" for="radio4">Gpay</label> <img src="{{ asset('public/fronted/images/gpay.png') }}" class="pull-right" />
                                                </div>
                                            </div>

                                            <div class="paymnetthodBox">
                                                <div class="checkbox checkbox-circle">
                                                    <input class="form-check-input" id="radio5" name="payment_mode"  type="radio" value="4" onclick="payment_method(4)">
                                                    <label class="form-check-label" for="radio5">Phone Pay</label> <img src="{{ asset('public/fronted/images/phonepay.png') }}" class="pull-right" />
                                                </div>
                                            </div>

                                            <div class="paymnetthodBox">
                                                <div class="checkbox checkbox-circle">
                                                    <input class="form-check-input" id="radio6" name="payment_mode"  type="radio" value="6" onclick="payment_method(6)">
                                                    <label class="form-check-label" for="radio6">Cash</label> <img src="{{ asset('public/fronted/images/cash.png') }}" class="pull-right" />
                                                </div>
                                            </div>

                                        </div>

                                        <div id="_paymethods3" style="display:none">
                                            <div class="paymnetthodBox">
                                                <div class="checkbox checkbox-circle">
                                                    <input class="form-check-input" id="radio6" name="payment_mode"  type="radio" value="5" onclick="payment_method(5)">
                                                    <label class="form-check-label" for="radio6">Wallet</label>
                                                </div>
                                            </div>
                                        </div>



                                        <!--<div class="paymnetthodBox">-->
                                        <!--    <div class="checkbox checkbox-circle">-->
                                        <!--        <input id="checkbox1" type="checkbox" checked name="wallet" class="wallet_button" value="1">-->
                                        <!--        <label for="checkbox1">Use Coins <i class="fa fa-inr"></i> <?php echo $cust_info->total_reward_points;?></label> <i class="fa fa-google-wallet pull-right" aria-hidden="true"></i>-->

                                        <!--    </div>-->

                                        <!--</div>-->
                                    </div>
                                    <!--<ul class="list-inline paymnetthodBox-icon">
                                        <li><a href="#"><img src="images/paytm.png"></a></li>
                                        <li><a href="#"><img src="images/payment2.png"></a></li>
                                        <li><a href="#"><img src="images/phonepay.png"></a></li>
                                    </ul>-->
                                </div>
                                <!--<input type="submit" value="Place Order" class="continue mt10">-->
                                <div class="places_ord mt-4">
                                    <button type="submit" id="BookingBtn" class="btn btn-warning btn-block btn-lg inStock" >Pay  <i class="fa fa-inr"></i> <span class="grand_total_with_tax_response"></span></button>
                                    <input type="button" value="Product is Out of stock" class="btn btn-danger btn-block btn-lg mt10 outStock" style="display:none">
                                    {{-- <input type="button" value="Product can not be  delivered in your port code" class="btn btn-danger btn-block btn-lg mt10 outofdelivery">									 --}}
                                </div>
                                <!-- <div class="checkout-element-content">
                                 <p class="order-left">Amount (<i class="cart_count">2</i> items) Tax Incl. <span><i class="fa fa-rupee"></i><strong class="grand_total_with_out_tax_response">0</strong></span></p>

                                  <p class="order-left slothide">Slot Price <span><i class="fa fa-rupee"></i><strong class="slodedisplay"></strong></span></p>
                                 <p class="order-left">Less Discount <span><i class="fa fa-rupee"></i><strong class="discount_reponse">0</strong></span></p>
                                 <p class="order-left">Shipping Charges <span><i class="fa fa-rupee"></i><strong class="shipping_charge_response">0</strong></span></p>

                                   <p class="order-left" id="cod_charge_html">COD Charge <span><i class="fa fa-rupee"></i><strong id="cod_charge"></strong></span></p>




                                     </div>-->
                                <!--<div class="totalmainbox totalmainboxcheckout">
                                    <p class="order-left">You Pay <span><i class="fa fa-rupee"></i><strong class="grand_total_with_tax_response">0</strong></span></p>
                                    <p class="savetextline">You will save <i class="fa fa-rupee"></i> <span class="total_save"></span> on this order</p>
                                </div>-->




                        </div>

                    </div>
                </div>
                </form>
            </div>

        </div>
    </section>

@endsection


@section('scripts')
    <script src="{{asset('public/fronted/js/checkout.js')}}"></script>
@endsection







