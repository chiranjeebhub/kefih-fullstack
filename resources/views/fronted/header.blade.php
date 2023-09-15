 <div class="top-bar">
    <div class="searcharea">
     <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Search product by keyword</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
       <form class="example" action="action_page.php">
      <input type="text" placeholder="Search.." name="search">
      <button type="submit"><i class="fa fa-search"></i></button>
      </form>
      </div>
    </div>
    </div>
   </div>  
    </div>
    <div class="container">
      <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
          <div class="logo"><a href="{{ route('index') }}"><img src="{{ asset('public/fronted/images/logo.png') }}" alt=""/></a></div>         
           <!--  @include('fronted.includes.search')-->
			<div class="smart-search search-form4">
					<form class="smart-search-form" method="GET" action="{{route('SearchProduct')}}">
					<input type="text" name="search" id="searchItems" placeholder="Search  Products..." autocomplete="off">
					<button class="submits" id="submit" type="submit" name="submit">Search</button>
				</form>
				<div id="display"></div>
			</div>	  
     <div class="hdr-rt">
            <ul class="header-btns-list">
            <li><div class="btn-search"><i class="fa fa-search" data-toggle="modal" data-target="#exampleModal"></i></div></li>
             <li><div class="btn-search"><i class="fa fa-heart"></i><sup>0</sup> </div></li>
             <li><div class="btn-search"><i class="fa fa-user"></i></div></li>  
            <li>
			   <div class="btn-search">
				<a href="#" id="cart"><i class="fa fa-shopping-cart"></i><sup>0</sup> </a>
			   </div> 
           <!--end shopping-cart -->
            </li>     
        <ul class="nav navbar-nav navbar-right">
    <!-- Authentication Links -->
			@if (Auth::guest())
		    
		    <li id="myDIV_login"><a href="{{ route('customer_login') }}">Login</a></li>
			<li id="myDIV_register"><a href="{{ route('customer_register') }}">Register</a></li>

	      <li class="dropdown" id="myDIV_logout">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
					@auth('customer')
					{{ auth()->guard('customer')->user()->name }} 
					@endauth
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu" role="menu">
					<li>
						<a href="{{ route('customer.logout') }}">Logout</a>
					</li>
				</ul>
			</li>
		    @endif 
			
          <script>
           document.getElementById("myDIV_logout").style.display = "none";
		   @auth('customer')
		    document.getElementById("myDIV_logout").style.display = "block";
		    document.getElementById("myDIV_login").style.display = "none";
		    document.getElementById("myDIV_register").style.display = "none";
		   @endauth
         </script>
		 
		 
        </ul>   
            </ul>          
          </div>
        </div>
      </div>
     </div>  
    </div>
   <div class="hdr-brdr"></div>
  <div class="logo-sec"><a href="{!! url('/index'); !!}"><img src="{{ asset('public/fronted/images/logo.png') }}" alt=""/></a></div>
    
