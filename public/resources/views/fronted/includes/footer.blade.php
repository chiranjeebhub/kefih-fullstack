<footer class="footer-section"><div class="container">
		     <?php 
    $store_info=DB::table('store_info')->first();
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
                            <ul><li><a href="{{route('index')}}">Home</a> </li>
                            <li><a href="{{route('page_url',['about_us'])}}">About Us</a> </li>
                                <li><a href="{{ route('contact')}}">Contact Us</a> </li>
                                <li><a href="{{ route('mydashboard')}}">My Account</a> </li>
                                
                                <li><a href="{{URL::to('/').'/seller/sellerLogin'}}">Become a Seller</a> </li>
                        </ul></div>
                    </div>
                </div>
                    <div class="col-xs-6 col-sm-3 col-md-3">
                        <div class="widget">
                        <h6 class="mb20">Customised Services</h6>
                        <div class="footer-links">
                            <ul><li><a href="{{route('page_url',['privacy-policy'])}}">Privacy policy</a> </li>
                        <!--<li><a href="{{ route('exchange')}}">Exchange</a> </li>-->
                        <li><a href="{{route('page_url',['return_policy'])}}"  >Return policy</a> </li>
                        <li><a href="{{route('page_url',['delivery_policy'])}}">Shipping policy </a> </li>
                        <!--<li><a href="{{route('page_url',['delivery_policy'])}}">Payment policy </a> </li>-->
                        <li><a href="{{route('page_url',['terms'])}}"> Terms & Conditions </a> </li>
                        <li><a href="{{route('refer_and_earn')}}">Refer & Earn</a> </li>        
                       
                        </ul></div></div></div>
                <div class="col-xs-12 col-sm-4 col-md-3"><div class="widget"><h6 class="mb20">Social Accounts</h6>
                    <div class="footer-social"><ul><li><a href="{{$store_info->facebook_url}}" target="_blank"><i class="fa fa-facebook"></i> </a> </li>
                        <li><a href="{{$store_info->twiter_url}}" target="_blank"><i class="fa fa-twitter"></i> </a> </li>
                        <li><a href="{{$store_info->linkedin_url}}" target="_blank"><i class="fa fa-linkedin"></i> </a> </li>
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
                    <!--<div class="row">-->
                    <!--    <div class="col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">-->
                    <!--    <div class="widget">-->
                    <!--        <div class="row">-->
                    <!--            <div class="col-sm-7 col-md-7 col-lg-7">-->
                    <!--            <h6 class="mb10">Subscribe to Newsletter</h6>-->
                    <!--  <p>Subscribe to our newsletter and get-->
                    <!--            10% off your first purchase</p>-->
                    <!--            </div>-->
                    <!--            <div class="col-sm-5 col-md-5 col-lg-5">-->
                    <!--            <form  method="Post" action="{{route('subscribe')}}">-->
                    <!--              @csrf-->
                    <!--        @if ($errors->any())-->
                    <!--        @foreach ($errors->all() as $error)-->
                    <!--        <span class=" "><p style="color:red">{{$error}}</p></span>-->
                    <!--        @endforeach-->
                    <!--        @endif<div class="subscribe-main-box">-->
                    <!--            <input type="text" name="email" placeholder="Your Email Address">-->
                    <!--            <button type="submit" class="subscribe">Subscribe </button></div></form>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                </div>
		<section class="copyright-section"><div class="container"><div class="row"><div class="col-sm-7 col-md-9"><div class="copyright"><p>© 2022, Jaldi Kharido | All Rights Reserved  </p></div></div>
            <div class="col-sm-5 col-md-3 text-rgt"><div class="copyright"><p>Powered by: <a href="https://www.b2cinfosolutions.com/" target="_blank">B2C Info Solutions</a></p></div></div></div></div></section></footer>

