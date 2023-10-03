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
                    <div class="headerSubtitle">Already Bought Something? <span style="font-weight: bold;">Click Here</span>
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

                    <div class="div-9">
                        <div class="a-4">City</div>
                        <input class="inputStyle" value="{{old('shipping_city')}}" name="shipping_city" type="text" id="name">
                    </div>
                    <div class="div-9">
                        <div class="a-4">State</div>
                        <input class="inputStyle" value="{{old('shipping_state')}}" name="shipping_state" type="text" id="name">
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
                    <div class="couponType">General</div>
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

                <button type="submit" class="paymentBtnOfflin2e">
                    Confirm Order
                </button>
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







