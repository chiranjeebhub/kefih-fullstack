<div class="top-bar">
	<div class="container header-container">
		<div class="row">
			<div class="col-sm-5 col-md-6 col-xs-12">
                <p>FREE RETURNS. Standard shipping orders <i class="fa fa-rupee"></i>99+</p>
				<!--<ul class="list-inline top-menu text-left">
					<li>
						<a href="{{route('offers')}}">Offers </a>
					</li>
                    <li>
						<a href="{{ route('myorder',(base64_encode(0))) }}">Track Order </a>
					</li>
					<li>
						<a class="pr0" href="{{route('snapbook')}}">Snapbook</a>
					</li>
				</ul>-->
			</div>
			<div class="col-sm-7 col-md-6 col-xs-12">
				<ul class="list-inline top-menu text-right">
                <li>
						<a href="#"> <img src="{{ asset('public/fronted/images/mobile-icon.png') }}" alt="">  Get App</a>
					</li>
					<li>
						<a href="{{ route('contact')}}"><img src="{{ asset('public/fronted/images/support-iocn.png') }}" alt="">  Support</a>
					</li>
					<li class="sell">
                  <a href="{{URL::to('/').'/seller/sellerLogin'}}"> Sell with us</a>
                  </li>
				</ul>
			</div>
			     
			    <!--<?php 
			      //$sitecities = DB::table('cities')->join('states', 'cities.state_id', '=', 'states.id')->select('cities.id','cities.name')->where('states.country_id','=','101')->orderBy('cities.name','ASC')->where(['cities.isdeleted' => 0, 'cities.status' => 1])->get();
                   //?>
            <div class="col-sm-2 col-md-2 col-xs-6">
                <div class="custom_select">
                    <select class="dropdown cityselectbox form-control sitecityheader" id="">
                    <option value="0">Select City</option>
                    @if(isset($sitecities))
                        @foreach($sitecities as $row)
                        <option value="{{$row->id}}" {{(@$_COOKIE['sitecity'] == $row->id)?'selected':''}}>{{$row->name}}</option>
                        @endforeach
                    @endif
                    
                </select>
                </div>-->
            </div>
		</div>
</div>
<div class="container header-container">
	<div class="row">
		<div class="col-sm-2 col-md-2  hidden-xs">
			<div class="logo">
				<a href=" {{ route('index') }} ">
					<img src="{{ asset('public/fronted/images/logo.png') }}" alt="">
					</a>
				</div>
			</div>
        <div class="col-sm-6 col-md-6 col-lg-7">
        <div class="hdr-rt search-center">
              <ul class="header-btns-list">
                  <li>
                      <div class="mobilesrch btn-search searchbox-icon">
                         <img id="headsearchbtn" src="{{ asset('public/fronted/images/search.png') }}" />
                          </div>
                      <div class="smart-search search-form4">
              <form class="smart-search-form searchbox" method="GET" action="{{route('SearchProduct')}}"><input class="searchbox-input" type="text" name="search" id="searchterm" placeholder="Search  Products..." autocomplete="off" required value="<?php echo @$searchterm;?>" onkeyup="buttonUp();"><button class="submits searchbox-submit" id="submit" type="submit"><i class="fa fa-search"></i></button></form>
              <div id="display"></div>
          </div>
                  </li>
                  
              </ul>
          </div>
        </div>
        <div class="col-sm-4 col-md-4 col-lg-3">
        <div class="hdr-rt">
              <ul class="header-btns-list">
                  <!--<li class="sell">
                  <a href="{{URL::to('/').'/seller/sellerLogin'}}">Sell with us</a>
                  </li>-->
                  @if (Auth::guard('customer')->check())
                  <li>
                      <div class="btn-search"><a href="{{ route('mydashboard') }}" class="f__char-user">
                              @if(auth()->guard('customer')->user()->profile_pic!='')
                              <a href="{{ route('mydashboard') }}">
                              <img class="navProfile" src="{{URL::to('/uploads/customers/profile_pic')}}/{{auth()->guard('customer')->user()->profile_pic}}" alt="{{auth()->guard('customer')->user()->name}}" width="40" height="40">
                              <div class="btn-search"><span>{{auth()->guard('customer')->user()->name}}</span></div>
                              </a>

                             
                              @else
                              <?php
                              /*
                              {{ substr(auth()->guard('customer')->user()->name, 0, 1) }}
                              */
                              ?>
                              <a href=""><img src="{{ asset('public/fronted/images/profile.png') }}" /><span>{{auth()->guard('customer')->user()->name}}</span> </a>

                              @endif</a></div>
                      <ul class="profile-menu list-unstyled">
                          <div class="usernameafterlogin"><a data-toggle="modal" href="" style="text-align:left;">{{auth()->guard('customer')->user()->name }} </a></div>
                          <li><a href="{{ route('mydashboard') }}">My Account  Info</a></li>
                          <li><a href="{{ route('myorder',(base64_encode(0))) }}">My Orders</a></li>
                          <li><a href="{{ route('wishlist') }}">My Wishlist</a></li>
                          <!--<li><a href="{{ route('wallet') }}">Coupons</a></li>
                          <li><a href="{{ route('wallet') }}">My Wallet</a></li>-->
                          <li><a href="{{ route('customer.logout') }}">Logout</a></li>
                      </ul>
                  </li>
                  <li>
                      <ul class="profile-menu list-unstyled">
                          <div class="btn-search"><a data-toggle="modal" href="{{ route('mydashboard') }}">
                                  @auth('customer')
                                  {{ substr(auth()->guard('customer')->user()->name, 0, 1) }}
                                  @endauth </a></div>
                          <li><a href="{{ route('mydashboard') }}">My Account Info</a></li>
                          <li><a href="{{ route('myorder',(base64_encode(0))) }}">My Orders</a></li>
                          <li><a href="{{ route('wishlist') }}">My Wishlist</a></li>
                          <!--<li><a href="{{ route('wallet') }}">Coupons</a></li>
                          <li><a href="{{ route('wallet') }}">My Wallet</a></li>-->
                          <li><a href="{{ route('customer.logout') }}">Logout</a></li>
                      </ul>
                  </li>
                  
                  
                  @else
                  <li>
                      <div class="btn-search"><a href="{{ route('login') }}"><img src="{{ asset('public/fronted/images/profile.png') }}" /><span>Login</span> </a></div>
                  </li>
                  @endif
                  @if (Auth::guard('customer')->check())
                  <li>
                      <div class="btn-search"><a href="{{ route('wishlist') }}" id="wishlist"><img src="{{ asset('public/fronted/images/wishlist.png') }}" /> <span>Wishlist</span></a></div>
                  </li>
                  @else
                  <li>
                      <div class="btn-search"><a href="{{ route('wishlist') }}" id="wishlist"><img src="{{ asset('public/fronted/images/wishlist.png') }}" /> <span>Wishlist</span></a></div>
                  </li>
                  @endif
                  @include('fronted.includes.cart')
              </ul>
          </div>
          
        </div>
		</div>
	</div>

	
    @include('fronted.includes.nav')
