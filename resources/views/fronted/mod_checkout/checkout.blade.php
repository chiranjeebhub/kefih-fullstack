@extends('fronted.layouts.app_new')
@section('content')

    <style>
        .paymentBtn, .paymentBtnOffline{
            display: none;
        }
    </style>
    <div class="navigation">
        <div class="navLeft">
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

    <form action="{{route('addShippingAddress')}}" method="post">
        <div class="checkoutContainer">
            @csrf
            <div class="leftSection">
                @if ($errors->any())
                    <div class="errors mt-2">
                        @foreach ($errors->all() as $error)
                            <span class="help-block">
                            <p style="color:red">{{$error}}</p>
                        </span>
                        @endforeach
                    </div>
                @endif
                    <input type="hidden" name="shipping_address_type" value="Home">

                <div class="headerTitleContainer">
                    <div class="headerTitle">Contact Information</div>
                    <!-- <li class="logoutBtn"><a class="btn btn-warning btn-block btn-lg head_user_login" role="button"> Log in / Sign up</a> </li> -->
                    <div class="headerSubtitle" style="display:flex;">Already Bought Something? 
                    <a class="btn btn-warning btn-block btn-lg head_user_login" role="button" style="background:transparent; margin:0px; padding:0px; border:none; width:auto;">&nbsp;Click Here</a>
                    <!-- <span style="font-weight: bold;">Click Here</span> -->
                    </div>
                </div>
                <div class="div-7">
                    <div class="div-8">
                        <div class="a-2">Phone Number</div>
                        <div class="a-3" style="display: flex; align-items: center;">
                            <div style="padding-right: 10px; margin-top: 2px;">+91</div>
                            <div>
                                <input type="text" class="inputStyle" name="shipping_mobile" value="{{old('shipping_mobile')}}">
                            </div>
                        </div>
                    </div>
                    <div class="div-9">
                        <div class="a-4">Email (Optional)</div>
                        <input class="inputStyle" type="text" id="name" name="shipping_email" value="{{old('shipping_email')}}">
                    </div>
                </div>
                <div class="headerTitleContainer">
                    <div class="headerTitle">Shipping Address</div>
                </div>
                <div class="div-7">
                    <div class="div-9">
                        <div class="a-4">Country</div>
                        <input class="inputStyle" type="text" id="name" readonly value="india" placeholder="India">
                    </div>
                </div>
                <div class="div-7">

                    <div class="div-9">
                        <div class="a-4">First Name</div>
                        <input class="inputStyle" name="shipping_name" value="{{old('shipping_name')}}" type="text" id="name">
                    </div>
                    <div class="div-9">
                        <div class="a-4">Last Name</div>
                        <input class="inputStyle" name="last_name" value="{{old('last_name')}}" type="text" id="name">
                    </div>
                </div>
                <div class="div-7">
                    <div class="div-9">
                        <div class="a-4">Address</div>
                        <input class="inputStyle" value="{{old('shipping_address')}}" name="shipping_address" type="text" id="name">
                    </div>
                </div>
                <div class="div-7">
                    <div class="div-9">
                        <div class="a-4">Apartment, suite, etc. (optional)</div>
                        <input class="inputStyle" name="shipping_address2" value="{{old('shipping_address2')}}" type="text" id="name">
                    </div>
                </div>
                <div class="div-7">

                    <div class="col-lg-4 col-sm-12 col-md-4 col-12 div-9">
                    <div class="form-group editionaldropdn" >
                                <label>State</label>
							<select class="form-ctrl form-control"  name="shipping_state" id="selectState" style="border:none; padding-left:0px;">
							<option value="" style="opacity:0.2;">Select State  </option>
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
                        <!-- <div class="form-group editionaldropdn">
                            <label>State</label>
                            <select class="form-control custom-select" name="shipping_state" id="selectState">
                                <option value="">Select State</option>
                                @foreach($states as $state)
                                    <option value="{{$state->id}}">{{$state->name}}</option>
                                @endforeach
                            </select>

                        </div> -->
                    </div>

                    <div class="col-lg-4 col-sm-12 col-md-4 col-12 div-9">
                        <div class="form-group editionaldropdn">
                            <label>City</label>
                            <select class="form-control" name="shipping_city" id="selectcity" style="border:none; padding-left:0px;">
                                <option value="">Select City</option>
                            </select>
                        </div>
                    </div>



                    <div class="div-9">
                        <div class="a-4">Pin Code</div>
                        <input class="inputStyle" value="{{old('shipping_pincode')}}" name="shipping_pincode" type="text" id="name">
                    </div>
                </div>
                <input type="hidden" name="payment_mode" id="payment_type" value="1">
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
                    <div class="couponType" style="opacity:0.2">General</div>
                </div>
                <div class="couponInputWrapper">
                    <input type="text" class="inputStyle form-ctrl couponcode_back"  id="Coupon_code" placeholder="Enter Coupon Code" value="">

                    <button type="submit" id="cartToalReview" class="applyBtn couponApply" index="code" cart_total="">Redeem</button>
                    <button class="btn btn-remove" style="display:none;" type="button" id="removeCopuonapply">Remove Coupon</button>

                </div>
                <span class="couponerrormsg" style="margin: 10px 30px; display: inline-block" id="CouponMsg_code"></span>
                <h2 class="cartHeader" style="font-size: 18px;">Items</h2>
                <div class="cart_table_list">


                </div>
                <!-- <div style="display:flex; padding-top:20px;padding-left:30px">
                    <div class="toggle-switch">
                        <input type="checkbox" id="togglecod" class="toggle-input">
                        <label for="togglecod" class="toggle-label1"></label>
                    </div>
                    <div class="toggleText">COD (Cash on delivery)</div>
                </div> -->
                <div class="paymnetthod" style="padding-top:20px; padding-left:30px;padding-right:30px;">
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
                <!-- <div>
             
                </div> -->
                <div style="width:100%; padding-right:4rem;">
                    <button type="submit" class="paymentBtnOfflin2e">
                        Confirm Order
                    </button>
                </div>
             
            </div>

        </div>
    </form>

    <br>
    <br>
    <br>

@endsection


@section('scripts')
    <script src="{{asset('public/fronted/js/checkout.js')}}"></script>

    @php
        session()->forget('ExibutionData');

    @endphp

    <script type="text/javascript">
        $(document).ready(function (){
            $('#toggle').on('click', function (){
                if ($('#toggle').is(':checked')) {
                    $(".subTotalSection").css('display', 'block');
                    $(".subTotalSection1").css('display', 'none');
                    $('#payment_type').val(1);
                }else{
                    $(".subTotalSection").css('display', 'none');
                    $(".subTotalSection1").css('display', 'block');

                    $('#payment_type').val(null);
                };
            })
        })
    </script>
@endsection







