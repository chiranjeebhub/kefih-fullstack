

<header class="fixedHeader">
    <!--<section class="hdr-top">
        
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-4 col-sm-6 col-md-6 col-lg-6">
                    <div class="list-inline hdr-social-link">
						<ul class="list-inline">
							<li><a href="#"><i class="fa fa-facebook"></i> </a> </li>
							<li><a href="#"><i class="fa fa-instagram"></i> </a> </li>
						</ul>
					</div>
                </div>
                <div class="col-8 col-sm-6 col-md-6 col-lg-6 text-end">
                    <nav id="navbar-example2" class="navbar navbar-expand-lg navbar-light bg-light justify-content-end">
						
                    </nav>
                </div>
            </div>
        </div>
        
    </section>-->
<nav id="navbar-example2" class="navbar sticky-top navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid justify-content-center">
        <div class="borderdiv mega_menu_div">
        
            @include('fronted.includes.nav')
            
            
         <ul class="navbar-nav ms-auto logoBox">
            
               <li class="nav-item dropdown">
                   <a class="navbar-brand" href=" {{ route('index') }} ">
                <img src="{{ asset('public/fronted/images/logo.png') }}" alt="">
                </a>
              
			  </li>
          </ul>
        <!--<ul class="navbar-nav ms-auto mobile-nav-search rightMenu">
            
            
        </ul>-->
        <ul class="list-inline hdr-top-cnt mobile-nav-search rightMenu">
            <li class="nav-item">
               <div class="mobilesrch searchbox-icon"><img src="{{ asset('public/fronted/images/search.png') }}"></div>
                
               <form method="GET" class="searchbox navbar-form" action="{{route('SearchProduct')}}">
             <div class="form-group"><input class="searchbox-input form-control" type="text" name="search" id="searchterm" placeholder="Search a product" autocomplete="off" required="" value="<?php echo @$searchterm;?>" onkeyup="buttonUp();">
             <!--<div class="form-icon"><i class="fa fa-search"></i></div>-->
             
             <button class="submits searchbox-submit btn btn-warning" id="submit" type="submit"><img src="{{ asset('public/fronted/images/search.png') }}"></button>
                
                <div id="display"></div>
                 </div>
                </form>
                
               </li>
            <!--<li><a class="profile" data-bs-toggle="modal" data-bs-target="#myModal" href="#"><img src="images/user-pic.png"/> </a> </li>-->
            <!--<li class="nav-item login-drop dropdown">
               <a class="nav-link login dropdown-toggle profile" href="#" role="button" data-bs-toggle="dropdown"><img src="{{ asset('public/fronted/images/user-pic.png') }}"/> </a> 
               <ul class="dropdown-menu dropdown-rgt" aria-labelledby="navbarDropdown" data-bs-popper="none">
                   <li class="welcomeBox">
                       <h2>Welcome, <span>James</span></h2>
                       <p>To access account and manage orders</p>
                   </li>
             <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                   <li><a class="dropdown-item" href="faqs.php">FAQ’s</a></li>
              <li><a class="dropdown-item" href="my-order.php">My Orders</a> </li>
                   <li><a class="dropdown-item" href="aboutus.php">About Us</a> </li>
                   <li><a class="dropdown-item" href="saved-credit-debit-card.php">Saved Cards</a> </li>
                   <li><a class="dropdown-item" href="refund-policy.php">Refund Policy</a> </li>
                   <li><a class="dropdown-item" href="saved-addresses.php">Saved Addresses</a></li>
                   <li><a class="dropdown-item" href="privacy-policy.php">Privacy Policy</a></li>
                   <li><a class="dropdown-item" href="wallet.php">Wallet</a> </li>
                   <li><a class="dropdown-item" href="notification.php"> Notification</a> </li>

                   <li class="logoutBtn"><a class="btn btn-warning btn-block btn-lg" href="index.php"> Logout</a> </li>
              </ul>

            </li>-->
            
            @if (Auth::guard('customer')->check())
                  <li class="nav-item login-drop dropdown"><a class="nav-link login dropdown-toggle profile" href="#" role="button" data-bs-toggle="dropdown">
                              @if(auth()->guard('customer')->user()->profile_pic!='')
                              
                              <img class="navProfile" src="{{URL::to('/uploads/customers/profile_pic')}}/{{auth()->guard('customer')->user()->profile_pic}}" alt="{{auth()->guard('customer')->user()->name}}" width="40" height="40">
                              <div class="btn-search"><span>{{auth()->guard('customer')->user()->name}}</span></div>
                             

                             
                              @else
                              <?php
                              /*
                              {{ substr(auth()->guard('customer')->user()->name, 0, 1) }}
                              */
                              ?>
                              <img src="{{ asset('public/fronted/images/user-pic.png') }}" /><span>{{auth()->guard('customer')->user()->name}}</span>

                              @endif</a>
                      <ul class="dropdown-menu dropdown-rgt" aria-labelledby="navbarDropdown" data-bs-popper="none">
                           <li class="welcomeBox">
                               <h2>Welcome, <span>{{auth()->guard('customer')->user()->name }}</span></h2>
                               <p>To access account and manage orders</p>
                           </li>
                     <li><a class="dropdown-item" href="{{ route('mydashboard') }}">Profile</a></li>
                           <li><a class="dropdown-item" href="{{route('faq')}}">FAQ’s</a></li>
                      <li><a class="dropdown-item" href="{{ route('myorder',(base64_encode(0))) }}">My Orders</a> </li>
                           <li><a class="dropdown-item" href="{{route('about')}}">About Us</a> </li>
                           <!--<li><a class="dropdown-item" href="saved-credit-debit-card.php">Saved Cards</a> </li>-->
                           <li><a class="dropdown-item" href="{{route('page_url',['return_policy'])}}">Refund Policy</a> </li>
                           <li><a class="dropdown-item" href="{{ route('shippingDetails') }}">Saved Addresses</a></li>
                           <li><a class="dropdown-item" href="{{route('page_url',['privacy-policy'])}}">Privacy Policy</a></li>
                           <li><a class="dropdown-item" href="{{ route('wallet') }}">Wallet</a> </li>
                           <li><a class="dropdown-item" href="{{ route('user-notifications') }}"> Notification</a> </li>

                           <li class="logoutBtn"><a class="btn btn-warning btn-block btn-lg" href="{{ route('customer.logout') }}"> Logout</a> </li>
                      </ul>
                      <!--<ul class="profile-menu list-unstyled">
                          <div class="usernameafterlogin"><a data-toggle="modal" href="" style="text-align:left;">{{auth()->guard('customer')->user()->name }} </a></div>
                          <li><a href="{{ route('mydashboard') }}">My Account  Info</a></li>
                          <li><a href="{{ route('myorder',(base64_encode(0))) }}">My Orders</a></li>
                          <li><a href="{{ route('wishlist') }}">My Wishlist</a></li>
                          <li><a href="{{ route('wallet') }}">Coupons</a></li>
                          <li><a href="{{ route('wallet') }}">My Wallet</a></li>
                          <li><a href="{{ route('customer.logout') }}">Logout</a></li>
                      </ul>-->
                  </li>
                  <!--<li>
                      <ul class="profile-menu list-unstyled">
                          <a data-toggle="modal" href="{{ route('mydashboard') }}">
                                  @auth('customer')
                                  {{ substr(auth()->guard('customer')->user()->name, 0, 1) }}
                                  @endauth </a>
                          <li><a href="{{ route('mydashboard') }}">My Account Info</a></li>
                          <li><a href="{{ route('myorder',(base64_encode(0))) }}">My Orders</a></li>
                          <li><a href="{{ route('wishlist') }}">My Wishlist</a></li>
                          <li><a href="{{ route('wallet') }}">Coupons</a></li>
                          <li><a href="{{ route('wallet') }}">My Wallet</a></li>
                          <li><a href="{{ route('customer.logout') }}">Logout</a></li>
                      </ul>
                  </li>-->
                  
                  
                  @else
                  <li class="nav-item login-drop dropdown">
                      <a  class="nav-link login dropdown-toggle profile" role="button" data-bs-toggle="dropdown"><img src="{{ asset('public/fronted/images/user-pic.png') }}" /></a>
                      
                          <ul class="dropdown-menu dropdown-rgt" aria-labelledby="navbarDropdown" data-bs-popper="none">
                               <li class="welcomeBox">
                                   <h2>Welcome, <span>Guest</span></h2>
                                   <p>To access account and manage orders</p>
                               </li>
                               <li class="logoutBtn"><a class="btn btn-warning btn-block btn-lg head_user_login" role="button"> Log in / Sign up</a> </li>
                          </ul>
                      
                  </li>
                  @endif
                  @if (Auth::guard('customer')->check())
              <li>
                  <a href="{{ route('wishlist') }}" id="wishlist"><img src="{{ asset('public/fronted/images/heart.png') }}" /></a>
              </li>
              @else
              <li>
                  <a href="javascript:void(0);" id="wishlist" class="head_user_login"><img src="{{ asset('public/fronted/images/heart.png') }}" /></a>
              </li>
              @endif
            
            @include('fronted.includes.cart')
            <!--<li><a href="cart.php"><img src="{{ asset('public/fronted/images/whistlist.png') }}"/> </a> </li> -->   
        </ul>
        </div>
    </div>
  </nav>

    
    
   
    
    
    

    

    </header>