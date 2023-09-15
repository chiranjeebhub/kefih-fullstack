      <style>.navProfile{border-radius: 50%;}</style>
        <nav class="navbar navbar-inverse"><div class="navbar-header"><button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".js-navbar-collapse"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a class="navbar-brand hidden-md hidden-lg" href=" {{ route('index') }} "><img src="{{ asset('public/fronted/images/logo.jpg') }}"/></a></div><div class="hdr-rt"><ul class="header-btns-list"><li class="mobilesrch"><div class="btn-search searchbox-icon"><img src="{{ asset('public/fronted/images/search.png') }}"/></div></li>				
              @if (Auth::guard('customer')->check())
               <li ><div class="btn-search"><a  href="{{ route('mydashboard') }}" class="f__char-user">                                               
            	@if(auth()->guard('customer')->user()->profile_pic!='')<img class="navProfile" src="{{URL::to('/uploads/customers/profile_pic')}}/{{auth()->guard('customer')->user()->profile_pic}}" alt="{{auth()->guard('customer')->user()->name}}" width="40" height="40">
            	@else
            	 {{ substr(auth()->guard('customer')->user()->name, 0, 1) }} 
            	@endif</a></div><ul class="profile-menu list-unstyled"><div class="btn-search"><a data-toggle="modal" href="" style="text-align:left;">{{auth()->guard('customer')->user()->name }} </a></div><li><a href="{{ route('mydashboard') }}">My Dashboard</a></li><li><a href="{{ route('myorder',(base64_encode(0))) }}">My Orders</a></li><li><a href="{{ route('wishlist') }}">My Wishlist</a></li><!--<li><a href="{{ route('wallet') }}">Coupons</a></li>--><li><a href="{{ route('wallet') }}">My Wallet</a></li><li><a href="{{ route('customer.logout') }}">Logout</a></li></ul></li><li><ul class="profile-menu list-unstyled"><div class="btn-search"><a data-toggle="modal" href="{{ route('mydashboard') }}">
                      @auth('customer')
                      {{ substr(auth()->guard('customer')->user()->name, 0, 1) }} 
                      @endauth </a></div><li><a href="{{ route('mydashboard') }}">My Dashboard</a></li><li><a href="{{ route('myorder',(base64_encode(0))) }}">My Orders</a></li><li><a href="{{ route('wishlist') }}">My Wishlist</a></li><!--<li><a href="{{ route('wallet') }}">Coupons</a></li>--><li><a href="{{ route('wallet') }}">My Wallet</a></li><li><a href="{{ route('customer.logout') }}">Logout</a></li></ul></li>
              @else
              <li ><div class="btn-search"><a  href="{{ route('login') }}"><img src="{{ asset('public/fronted/images/profile.png') }}"/><span>Login</span> </a></div></li>
              @endif                                             
              @if (Auth::guard('customer')->check())
              <li><div class="btn-search"><a href="{{ route('wishlist') }}" id="wishlist"><img src="{{ asset('public/fronted/images/wishlist.png') }}"/>  <span>Wishlist</span></a></div></li>
              @else
              <li><div class="btn-search"><a href="{{ route('wishlist') }}" id="wishlist"><img src="{{ asset('public/fronted/images/wishlist.png') }}"/>  <span>Wishlist</span></a></div></li>
              @endif
@include('fronted.includes.cart')</ul></div><div class="smart-search search-form4"><form class="smart-search-form searchbox" method="GET" action="{{route('SearchProduct')}}"><input class="searchbox-input" type="text" name="search" id="searchterm" placeholder="Search  Products..." autocomplete="off" required value="<?php echo @$searchterm;?>" onkeyup="buttonUp();" ><button class="submits searchbox-submit" id="submit" type="submit"><i class="fa fa-search"></i></button></form><div id="display"></div></div><div class="collapse navbar-collapse js-navbar-collapse">
    <ul class="nav navbar-nav">{!! App\Category::getNavLinks()!!}
        <!--<li class="dropdown mega-dropdown"><a href="{{ route('customized') }}" class="rootCat">Customized</a></li>-->
       
    </ul>
    </div>
    <!-- /.nav-collapse --></nav>
