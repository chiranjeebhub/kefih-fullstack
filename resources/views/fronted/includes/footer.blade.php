<!--<footer class="footer-section">
    <div class="container">
    <?php
    $store_info = DB::table('store_info')->first();
    ?>
   <div class="row">
            <div class="col-xs-12 col-sm-3 col-md-4">
            <div class="widget">
            <div class="fotr-logo">
                    <img src="{{ asset('public/fronted/images/logo.png') }}" class="img-responsive">
                </div>
                        <div class="footer-links">
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's.</p>
</div>
</div>
                        </div>
                    <div class="col-xs-6 col-sm-2 col-md-2">
                        <div class="widget">
                            <h6 class="mb20">Quick Links</h6>
                        <div class="footer-links">
                            <ul><li><a href="{{ route('index') }}">Home</a> </li>
                            <li><a href="{{ route('page_url', ['about_us']) }}">About Us</a> </li>
                                <li><a href="{{ route('contact') }}">Contact Us</a> </li>
                                <li><a href="{{ route('mydashboard') }}">My Account</a> </li>

                                <li><a href="{{ URL::to('/') . '/seller/sellerLogin' }}">Become a Seller</a> </li>
                        </ul></div>
                    </div>
                </div>
                    <div class="col-xs-6 col-sm-3 col-md-3">
                        <div class="widget">
                        <h6 class="mb20">Customised Services</h6>
                        <div class="footer-links">
                            <ul><li><a href="{{ route('page_url', ['privacy-policy']) }}">Privacy policy</a> </li>

                        <li><a href="{{ route('page_url', ['return_policy']) }}"  >Return policy</a> </li>
                        <li><a href="{{ route('page_url', ['delivery_policy']) }}">Shipping policy </a> </li>
                        <li><a href="{{ route('page_url', ['delivery_policy']) }}">Payment policy </a> </li>
                        <li><a href="{{ route('page_url', ['terms']) }}"> Terms & Conditions </a> </li>
                        <li><a href="{{ route('refer_and_earn') }}">Refer & Earn</a> </li>

                        </ul></div></div></div>
                <div class="col-xs-12 col-sm-4 col-md-3"><div class="widget"><h6 class="mb20">Social Accounts</h6>
                    <div class="footer-social"><ul><li><a href="{{ $store_info->facebook_url }}" target="_blank"><i class="fa fa-facebook"></i> </a> </li>
                        <li><a href="{{ $store_info->twiter_url }}" target="_blank"><i class="fa fa-twitter"></i> </a> </li>
                        <li><a href="{{ $store_info->linkedin_url }}" target="_blank"><i class="fa fa-linkedin"></i> </a> </li>
                        <li><a href="#" target="_blank"><i class="fa fa-youtube"></i> </a> </li>

                        </ul>
                    </div>
                    <h6>pay using</h6><div class="onlineicon"><img src="{{ asset('public/fronted/images/paypal.png') }}" alt=""> <img src="{{ asset('public/fronted/images/maestro.png') }}" alt=""> <img src="{{ asset('public/fronted/images/discover.png') }}" alt=""> <img src="{{ asset('public/fronted/images/visa.png') }}" alt=""> </div>
                    </div></div>

                    </div>
                    <div class="services-box">
            <div class="row">
                <div class="col-xs-6 col-sm-3 col-md-3">
                    <div class="srvs-item">
                        <div class="media">
                            <div class="media-left">
                                <div class="srvs-icon"><img src="{{ asset('public/fronted/images/delavery-icon.png') }}"></div>
                            </div>
                            <div class="media-body">
                                <h5>Free Delivery</h5>
                                <p>When ordering form ₹500.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3 col-md-3">
                    <div class="srvs-item">
                        <div class="media">
                            <div class="media-left">
                                <div class="srvs-icon"><img src="{{ asset('public/fronted/images/delavery-icon0.png') }}"></div>
                            </div>
                            <div class="media-body">
                                <h5>90 Days Return </h5>
                                <p>If goods have problems</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3 col-md-3">
                    <div class="srvs-item">
                        <div class="media">
                            <div class="media-left">
                                <div class="srvs-icon"><img src="{{ asset('public/fronted/images/delavery-icon1.png') }}"></div>
                            </div>
                            <div class="media-body">
                                <h5>Secure Payment</h5>
                                <p>100% secure payment</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3 col-md-3">
                    <div class="srvs-item">
                        <div class="media">
                            <div class="media-left">
                                <div class="srvs-icon"><img src="{{ asset('public/fronted/images/delavery-icon2.png') }}"></div>
                            </div>
                            <div class="media-body">
                                <h5>24/7 Support</h5>
                                <p>Dedicated support </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
                        <div class="widget">
                            <div class="row">
                                <div class="col-sm-7 col-md-7 col-lg-7">
                                <h6 class="mb10">Subscribe to Newsletter</h6>
                   <p>Subscribe to our newsletter and get
10% off your first purchase</p>
                                </div>
                                <div class="col-sm-5 col-md-5 col-lg-5">
                                <form  method="Post" action="{{ route('subscribe') }}">
                                  @csrf
                            @if ($errors->any())
@foreach ($errors->all() as $error)
<span class=" "><p style="color:red">{{ $error }}</p></span>
@endforeach
@endif
                                    <div class="subscribe-main-box">
                                <input type="text" name="email" placeholder="Your Email Address">
                                <button type="submit" class="subscribe">Subscribe </button></div></form>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
  </footer>-->


<footer>
    <div class="container">
        <?php
        $store_info = DB::table('store_info')->first();
        ?>
        <div class="row">
            <div class="col-12 col-sm-6 col-md-7 col-lg-7">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-5 col-lg-5">
                        <div class="fotr-logo">
                            <img src="{{ asset('public/fronted/images/logo.png') }}">
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-7 col-lg-7">
                        <div class="fotr-media-item">
                            <h3>Subscribe for Newsletter</h3>
                            <div class="foter-search">
                                <form class="navbar-form" id="news_letter_send" method="post">
                                    <!-- @csrf -->

                                    <!--   @error('subscribe_email')
    <p style="color:red">{{ $message }}</p>
@enderror -->

                                    <span id="get_message" class="display_news_data" style="display: none;"></span>
                                    <div class="form-group">
                                        <input type="text" class="form-control get_email_data" value=""
                                            name="subscribe_email" placeholder="E-mail">
                                        <button type="button" id="get_email_data" class="btn"><i
                                                class="fa fa-location-arrow"></i></button>
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-5 col-lg-5">
                <div class="row">
                    <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="fotr-media-item">
                            <h3>CONTACT US</h3>
                            <div class="fotr-media">
                                <!--<p><a href="tel:+420 777 666 888"><i class="fa fa-phone"></i> +420 777 666 888</a>  </p>-->
                                <p><a href="mailto:{{ $store_info->email }}"><i class="fa fa-envelope"></i>
                                        {{ $store_info->email }}</a> </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="fotr-media-item">
                            <h3>&nbsp;</h3>
                            <div class="fotr-media">
                                {{-- =
                     <a class="btn btn-warning btn-block btn-lg" href="#saleModalLabel" data-bs-toggle="modal">SELL WITH US</a>
                            --}}
                                <a class="btn btn-warning btn-block btn-lg"
                                    href="{{ URL::to('seller/vendor_register/' . base64_encode(0)) }}">SELL WITH
                                    US</a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-12 col-sm-5 col-md-5 col-lg-5">
                <div class="row">

                    {!! App\Category::getFootNavLinks() !!}


                </div>
            </div>
            <div class="col-12 col-sm-7 col-md-7 col-lg-7">
                <div class="row">

                    <div class="col-9 col-sm-8 col-md-9 col-lg-9">
                        <div class="fotr-media-item">

                            <div class="row">
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                                    <h4>Brands</h4>
                                    <ul class="list-unstyled fotr-menu">
                                        @php
                                            $vendrosactive = DB::table('vendors')
                                                ->select('vendors.id', 'vendor_company_info.name')
                                                ->leftJoin('vendor_company_info', 'vendors.id', '=', 'vendor_company_info.vendor_id')
                                                ->where(['vendors.featuremart' => '1', 'vendors.status' => 1, 'vendors.isdeleted' => 0])
                                                ->where('vendor_company_info.name', '!=', '')
                                                ->groupBy('vendors.id')
                                                ->orderBy('vendors.id', 'desc')
                                                ->limit(10)
                                                ->get();
                                        @endphp
                                        @foreach ($vendrosactive as $row)
                                            <li><a
                                                    href="{{ route('brandproducts', base64_encode($row->id)) }}">{{ $row->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                                    <div class="fotr-media-item">
                                        <h4>Policies</h4>
                                        <ul class="list-unstyled fotr-menu">
                                            <li><a href="{{ route('page_url', ['privacy-policy']) }}">Privacy
                                                    policy</a> </li>
                                            <li><a href="{{ route('page_url', ['terms']) }}"> Terms & Conditions </a>
                                            </li>
                                            <li><a href="{{ route('page_url', ['delivery_policy']) }}">Shipping policy
                                                </a> </li>
                                            <li><a href="{{ route('page_url', ['return_policy']) }}">Return & Refund
                                                    Policy</a> </li>
                                            <li><a href="{{ route('page_url', ['cancellation-policy']) }}">Cancellation
                                                    Policy</a> </li>

                                            <li><a href="{{ route('contact') }}">Contact Us</a> </li>

                                        </ul>
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="col-3 col-sm-4 col-md-3 col-lg-3">
                        <div class="fotr-media-item">
                            <h4>Follow Us:</h4>
                            <div class="list-inline hdr-social-link">
                                <ul class="list-inline">
                                    <li><a href="{{ $store_info->facebook_url }}"><i
                                                class="fa fa-facebook-square"></i> </a> </li>
                                    <li><a href="{{ $store_info->instagram_url }}"><i class="fa fa-instagram"></i>
                                        </a> </li>
                                    <li><a href="{{ $store_info->linkedin_url }}"><i class="fa fa-linkedin"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--<section class="cpyrgt-wrap">
        <div class="container-fluid">
            <div class="row text-center">
                <div class="col-sm-12 col-md-12"><p>© 2023, <b>Kefih</b>  | All rights reserved. Powered By <a href="https://www.b2cinfosolutions.com/" target="_blank"> B2C Info Solutions</a></p> </div>

                </div>
        </div>
        </section>-->
</footer>
<!--<div class="whatsap">
    <a href="https://api.whatsapp.com/send?phone=91" class="social-icon whatsapp" target="_blank">
           <i class="fa fa-whatsapp"></i>
</a>
        </div>-->




<!-- MODEL ORDER CANCEL AND RETURN -->
<div class="order_cancel main_section">
    <div class="modal fade" id="myModal2">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Order Return </h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><img
                            src="images/remove.png" alt=""> </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-md-12 col-12">
                            <div class="popup_info profile_form">
                                <div class="form-group">
                                    <label>Select a reason</label>
                                    <select id="mounth">
                                        <option value="january">Select</option>
                                        <option value="february">Select a reason</option>
                                        <option value="march">Select a reason</option>
                                        <option value="april">Select a reason</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-12 col-md-12 col-12">
                            <div class="popup_info">
                                <div class="form-group">
                                    <label>Write your concern </label>
                                    <textarea class="form-control" placeholder=""></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-12 col-md-12 col-12">
                            <div class="checkbox profile_form">
                                <div class="form-group">
                                    <button type="button" class="btn bg-black text-white"> Proceed</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- The Modal -->



<div class="modal fade" id="saleModalLabel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <h5 class="modal-title" id="saleModalLabel">Sale With Us</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-box cardNewbox">
                    <form class="main_section">
                        <div class="form-group">
                            <label>Brand Name:</label>
                            <input type="text" placeholder="Nike" class="form-control form-ctrl" />
                        </div>
                        <div class="form-group">
                            <label>Product category:</label>
                            <input type="text" placeholder="Shoes" class="form-control form-ctrl" />
                        </div>
                        <div class="form-group">
                            <label>Contact person name</label>
                            <input type="text" placeholder="Emma" class="form-control form-ctrl" />
                        </div>
                        <div class="form-group">
                            <label>Contact Number</label>
                            <input type="text" placeholder="+1 124-645-8123" class="form-control form-ctrl" />
                        </div>
                        <div class="form-group">
                            <label>Email ID:</label>
                            <input type="text" placeholder="emma56@gmail.com" class="form-control form-ctrl" />
                        </div>
                        <div class="form-group">
                            <label>GST Number</label>
                            <input type="text" placeholder="07AAGFF2194N1Z1" class="form-control form-ctrl" />
                        </div>
                        <div class="popup_info profile_form">
                            <div class="form-group">
                                <label>Location:</label>
                                <select id="mounth">
                                    <option value="">New Delhi</option>
                                    <option value="">Noida</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4"><a href="#" class="btn btn-warning btn-block btn-lg">Continue</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- modal end -->



<div class="order_cancel main_section">
    <div class="modal fade" id="myModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Log in / Sign up </h4>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal"></button>
                </div>
                @php
                    $countryCodes = App\Country::GetCountryCodeList();
                @endphp
                <!-- Modal body -->
                <div class="modal-body">
                    <div id="loginerror" class="row"></div>
                    <div class="row">

                        <div class="col-lg-4 col-sm-4 col-md-4 col-12">

                            <div class="popup_info profile_form">
                                <div class="form-group">
                                    <select id="login_country_code" name="login_country_code">
                                        @foreach ($countryCodes as $row)
                                            <option value="{{ $row->phonecode }}">{{ $row->sortname }}
                                                (+{{ $row->phonecode }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-sm-8 col-md-8 col-12">
                            <div class="popup_info profile_form">
                                <div class="form-group">
                                    <!--<input type="number" id="login_phone" name="phone" class="form-control" placeholder="000 000 0000" autocomplete="off">-->
                                    <input type="number" id="login_phone" min="0" inputmode="numeric"
                                        pattern="[0-9]*" maxlength="1"
                                        oninput="this.value=this.value.replace(/[^0-9]/g,'');" name="phone"
                                        class="form-control" placeholder="000 000 0000" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-12 col-md-12 col-12">
                            <div class="checkbox profile_form">
                                <div class="form-check-inline">
                                    <label class="form-check-label">



                                        <input type="checkbox" id="term_accepted" class="form-check-input"
                                            name="term_accepted" value="1" required>By continuing, I agree to the
                                        <a href="{{ route('page_url', ['terms']) }}">Terms of Use & Privacy Policy</a>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn userLogin"> Continue</button>

                                    <!-- <button type="button" id="userLogin" class="btn" data-bs-target="#myModal1" data-bs-toggle="modal" data-bs-dismiss="modal"> Continue</button> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-12 col-md-12 col-12">
                            <div class="popup_info">
                                <h3>or</h3>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-12 col-md-12 col-12">
                            <div class="other_login">
                                <!-- <a href="#"><img src="{{ asset('public/fronted/images/facebook.png') }}"
                                        alt=""></a>
                                <a href="#"> <img src="{{ asset('public/fronted/images/google.png') }}"
                                        alt=""></a> -->
                                        <a href="{{route('redirect_fb')}}"><img  src="{{ asset('public/fronted/images/facebook.png') }}" alt=""></a>
                                <a href="{{route('redirect_gp')}}"> <img  src="{{ asset('public/fronted/images/google.png') }}" alt=""></a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- Model OTP Verification -->
<div class="order_cancel main_section">
    <div class="modal" id="myModal1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">OTP Verification </h4>
                    <button type="button" class="close" data-dismiss="modal"><img
                            src="{{ asset('public/fronted/images/remove-white.png') }}" alt=""> </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body otp_popup">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-md-12 col-12">
                            <div class="popup_info profile_form">
                                <p id="otp_response"></p>
                                <p>We have sent the OTP to</p>
                                <h2><span id="otp_phone_no"></span><a href="javascript:void(0);"
                                        id="changeOTP_phone">Change</a> </h2>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-12 col-md-12 col-12">
                            <div class="popup_info profile_form">
                                <div class="form-group">
                                    <input type="text" id="login_otp_1" min="0" inputmode="numeric"
                                        pattern="[0-9]*" maxlength="1"
                                        oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                                        class="form-control login_otp_fld" placeholder="">
                                    <input type="text" id="login_otp_2" min="0" inputmode="numeric"
                                        pattern="[0-9]*" maxlength="1"
                                        oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                                        class="form-control login_otp_fld" placeholder="">
                                    <input type="text" id="login_otp_3" min="0" inputmode="numeric"
                                        pattern="[0-9]*" maxlength="1"
                                        oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                                        class="form-control login_otp_fld" placeholder="">
                                    <input type="text" id="login_otp_4" min="0" inputmode="numeric"
                                        pattern="[0-9]*" maxlength="1"
                                        oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                                        class="form-control login_otp_fld" placeholder="">

                                </div>
                            </div>
                            <div class="resend">
                                <a href="javascript:void(0);" id="login_resent_otp_btn">Resend OTP</a>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-12 col-md-12 col-12">
                            <div class="checkbox profile_form">
                                <div class="form-group">
                                    <!--<button type="button" class="btn"> Continue</button>-->
                                    <a href="javascript:void(0);" id="verifyOTPbtn"
                                        class="btn btn-warning btn-lg btn-block"> Continue</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $popupAds = App\Advertise::getPopupAds();
@endphp
@if (!empty($popupAds))
    <div class="fixoffer-rgtbar">
        <div class="Summer-banner">
            <div class="offerBannerArrow"></div>
            <span>{{ $popupAds->short_text }}</span>
            <img src="{{ asset('uploads/advertise/' . $popupAds->image) }}" />
            @if (!Auth::guard('customer')->check())
                <a class="btn btn-outline-light" href="#myModal" data-bs-toggle="modal" data-bs-dismiss="modal">
                    Register</a>
            @endif
        </div>
    </div>
@endif
