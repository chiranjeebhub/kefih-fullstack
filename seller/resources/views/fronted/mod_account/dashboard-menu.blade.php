
<li><h2><span>Hello</span><br>@auth('customer') {{ auth()->guard('customer')->user()->name }} @endauth </h2> </li>
<!--<li class="<?php echo @$mydashboard;?>"><a href="{{ route('mydashboard') }}"><i class="fa fa-user"></i>  </a> </li>-->
<li class="<?php echo @$accountinfo;?>"><a href="{{ route('accountinfo') }}"><i class="fa fa-info-circle"></i> Account information</a></li>
<li class="<?php echo @$changepass;?>"><a href="{{ route('changepass') }}"><i class="fa fa-key"></i> Change password</a></li>
<li class="<?php echo @$myorder;?>"><a href="{{ route('myorder',(base64_encode(0))) }}"><i class="fa fa-first-order"></i> My Order</a> </li> 
<li class="<?php echo @$reviews;?>"><a href="{{ route('myReviews') }}"><i class="fa fa-comments"></i> My Reviews</a> </li>
<li class="<?php echo @$shippingDetails;?>"><a href="{{ route('shippingDetails') }}"><i class="fa fa-truck"></i> Shipping Detail</a> </li>
<li class="<?php echo @$wishlist;?>"><a href="{{ route('wishlist') }}"><i class="fa fa-heart"></i> My Wishlist</a> </li>
<!--<li class="<?php echo @$savelater;?>"><a href="{{ route('savelater') }}"><i class="fa fa-heart"></i> Save Later</a> </li>-->
<li class="<?php echo @$wallet;?>"><a href="{{ route('wallet') }}"><i class="fa fa-google-wallet"></i> My Coins</a> </li>
<li class="<?php echo @$refers;?>"><a href="{{ route('referarls') }}"><i class="fa fa-google-wallet"></i> My Referals</a> </li>
<!--<li><a href="reviews.html"><i class="fa fa-star"></i>  My Reviews</a> </li>
<li><a href="{{ route('accountinfo') }}"><i class="fa fa-truck"></i> Shipping Detail</a></li>   --> 
<!--<li><a href="returns-and-refund.html"><i class="fa fa-refresh"></i> Returns & Refunds</a> </li>--> 
<!--<li><a href="order-cancellation.html"><i class="fa fa-sign-out"></i> Order Cancellation</a> </li>
<li><a href="my-querys.html"><i class="fa fa-question-circle"></i> My queries</a></li>-->
<li><a href="{{ route('customer.logout') }}"><i class="fa fa-lock"></i> Logout</a></li>
					