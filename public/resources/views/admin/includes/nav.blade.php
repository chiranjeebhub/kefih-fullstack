 <div class="main-nav">  	
	  <nav class="navbar navbar-expand-lg">
		  <div class="collapse navbar-collapse" id="navbarNavDropdown">
			<ul class="navbar-nav">
			
			  @if (Auth::guard('vendor')->check())  
				<li class="nav-item">
				<a class="nav-link" href="{{ route('v_home') }}"><i class="fa fa-dashboard mr-5"></i> <span>Dashboard</span></a>
				</li>
					
					<li class="nav-item">
				<a class="nav-link" href="{{route('update_vdr_profile', [base64_encode(0),base64_encode(auth()->guard('vendor')->user()->id)])}}">
				    <i class="fa fa-dashboard mr-5"></i> <span>Profile</span></a>
				</li>
				
				
				<li class="nav-item">
				<a class="nav-link" href="{{route('vendor_address', [base64_encode(auth()->guard('vendor')->user()->id)])}}">
				    <i class="fa fa-dashboard mr-5"></i> <span>Address</span></a>
				</li>
				
			  
			 <!-- <li class="nav-item dropdown">-->
				<!--<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
				<!--  <i class="fa fa-th mr-5"></i> <span>Master   </span>-->
				<!--</a>				  -->
				<!--<ul class="dropdown-menu multilevel scale-up-left">-->
						
				<!--		<li class="nav-item"><a class="nav-link" href="{{route('vendor_product')}}">Products</a></li>-->
						
					
				<!--	</ul>				  -->
			 <!-- </li>	-->
			 <li class="nav-item"><a class="nav-link" href="{{ route('coupons',base64_encode(0)) }}">Coupon Management</a></li>
			  
			   <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  <span>Product Management</span>
				</a>				  
				<ul class="dropdown-menu multilevel scale-up-left">
						<li class="nav-item"><a class="nav-link" href="{{route('vendor_product')}}"><span>Product Listing</span></a></li>
						<li class="nav-item"><a class="nav-link" href="{{ route('imageUploads') }}"><span>Image upload</span></a></li>
						<li class="nav-item"><a class="nav-link" href="{{ route('vendor_brands') }}">Brands</a></li>
									  
				</ul>				  
			  </li>	
			  
			  <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  <span>Order Management</span>
				</a>				  
				<ul class="dropdown-menu multilevel scale-up-left">
						<li class="nav-item"><a class="nav-link" href="{{ route('vendor_orders',base64_encode(0)) }}">Orders</a></li>
					
									  
				</ul>				  
			  </li>	
			
		
			  
		@else
			  
			  @if(auth()->user()->user_role == 0)
			  <li class="nav-item">
				<a class="nav-link" href="{{ route('dashboard') }}"><i class="fa fa-dashboard mr-5"></i> <span>Dashboard</span></a>
			  </li>
			  
			   
			 
			  
			  <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  <i class="fa fa-th mr-5"></i> <span>Master</span>
				</a>				  
				<ul class="dropdown-menu multilevel scale-up-left">
						<li class="nav-item"><a class="nav-link" href="{{ route('home_slider') }}">Banner</a></li>
						<li class="nav-item"><a class="nav-link" href="{{ route('categories') }}">Categories</a></li>
						<li class="nav-item"><a class="nav-link" href="{{ route('brands') }}">Brands</a></li>
						
						<li class="nav-item dropdown"><a class="nav-link dropdown-item dropdown-toggle" href="#">Products</a>
					<ul class="dropdown-menu">
					  <li><a class="dropdown-item nav-link" href="{{ route('products') }}">Products Listing</a></li>
					  <li><a class="dropdown-item nav-link" href="{{ route('imageUploads') }}">Image upload</a></li>
					  <li><a class="dropdown-item nav-link" href="{{ route('offer_slider',base64_encode(0)) }}">Offer</a></li>
					  <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle nav-link" href="#">Attributes</a>
						<ul class="dropdown-menu">
						   <li><a class="dropdown-item nav-link" href="{{ route('colors') }}">Colors</a></li>
						   <li><a class="dropdown-item nav-link" href="{{ route('sizes') }}">Sizes</a></li>
						   <li><a class="dropdown-item nav-link" href="{{ route('materials') }}">Materials</a></li>
						   <li><a class="dropdown-item nav-link" href="{{ route('filters') }}">Filters</a></li>
						     <li><a class="dropdown-item nav-link" href="{{ route('productSetting') }}">Product Setting</a></li>
						</ul>
					  </li>
					  
					  
           			</ul>
				  </li>
				  <li class="nav-item"><a class="nav-link" href="{{ route('coupons',base64_encode(0)) }}">Coupon Management</a></li>
				  <li class="nav-item"><a class="nav-link" href="{{ route('rating_review') }}">Product Rating & Reviews</a></li>
				 <li class="nav-item"><a class="nav-link" href="{{ route('offerszone') }}">Offer Zone Assign Categories</a></li>
				     				  
				</ul>				  
			  </li>
			  <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  <i class="fa fa-th mr-5"></i> <span>Order Master</span>
				</a>				  
				<ul class="dropdown-menu multilevel scale-up-left">
						<!--<li class="nav-item"><a class="nav-link" href="{{ route('orders',base64_encode(0)) }}">Orders</a></li>-->
						<li class="nav-item"><a class="nav-link" href="{{ route('vendor_orders',base64_encode(0)) }}">Orders</a></li>
						<li class="nav-item"><a class="nav-link" href="{{ route('shipping_charges') }}">Shipping Charges</a></li>
						<li class="nav-item"><a class="nav-link" href="{{ route('reasons') }}">Return/Cancel Reasons</a></li>
						
				</ul>				  
			  </li>	
			  
			   <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  <i class="fa fa-th mr-5"></i> <span>Vendor Master</span>
				</a>				  
				<ul class="dropdown-menu multilevel scale-up-left">
						<li class="nav-item"><a class="nav-link" href="{{ route('vendors') }}">Vendors</a></li>
						<li class="nav-item"><a class="nav-link" href="{{ route('ledger') }}">Ledger</a></li>
						<!-- <li class="nav-item"><a class="nav-link" href="{{ route('enquirry') }}">Enquiry</a></li> -->
						
				</ul>				  
			  </li>	
			  
			 
			  
			  
			  <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  <i class="fa fa-th mr-5"></i> <span>Report</span>
				</a>				  
				<ul class="dropdown-menu multilevel scale-up-left">
						
                    <li class="nav-item"><a class="nav-link" href="{{ route('reports',base64_encode(0)) }}">Highest Selling Product </a></li>			
                    <!--<li class="nav-item"><a class="nav-link" href="{{ route('advertise') }}">Best selling combo </a></li>-->
                    <li class="nav-item"><a class="nav-link" href="{{ route('reports',base64_encode(1)) }}">Location wise Products Selling </a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('reports',base64_encode(2)) }}">Refund or Replaced Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('reports',base64_encode(3)) }}">Best Vendors</a></li>
				</ul>				  
			  </li>	
			  
			  <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  <i class="fa fa-th mr-5"></i> <span>Customer Master</span>
				</a>				  
				<ul class="dropdown-menu multilevel scale-up-left">
						<li class="nav-item"><a class="nav-link" href="{{ route('customers') }}">Customers</a></li>
						<li class="nav-item"><a class="nav-link" href="{{ route('subscribers') }}">Subscribers </a></li>
						<li class="nav-item"><a class="nav-link" href="{{ route('customers_pay') }}">Customers Payment</a></li>
						
				</ul>				  
			  </li>	
			  
			  
			  <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  <i class="fa fa-th mr-5"></i> <span>Manage</span>
				</a>				  
				<ul class="dropdown-menu multilevel scale-up-left">
					<!--<li class="nav-item"><a class="nav-link" href="{{ route('blogs') }}">Blog</a></li>-->
					<!--<li class="nav-item"><a class="nav-link" href="{{ route('testimonials') }}">Testimonial</a></li>-->
					<li class="nav-item"><a class="nav-link" href="{{ route('advertise') }}">Advertisement</a></li>
					<li class="nav-item"><a class="nav-link" href="{{ route('my_offers') }}">Offers</a></li>
					
					<li class="nav-item"><a class="nav-link" href="{{ route('logistics') }}">Logistics</a></li>
					<li class="nav-item"><a class="nav-link" href="{{ route('vendor_pincode') }}">Port Code</a></li>

					
					   
           
						
				</ul>				  
			  </li>	
			   
			   {{--
			   <li class="nav-item">
				<a class="nav-link" href="{{ route('timeslot') }}"><i class="fa fa-th mr-5"></i> <span>Timeslot</span></a>
				
				
			  </li>
			  
			  <li class="nav-item">
				<a class="nav-link" href="{{ route('deliveryboy') }}"><i class="fa fa-th mr-5"></i> <span>Delivery Boy</span></a>
				
				
			  </li>
			  --}}
			 <!--  <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  <i class="fa fa-th mr-5"></i> <span>Delivery</span>
				</a>				  
				<ul class="dropdown-menu multilevel scale-up-left">
					<li class="nav-item"><a class="nav-link" href="{{ route('zone') }}">Zone</a></li>
					<li class="nav-item"><a class="nav-link" href="{{ route('area') }}">Area</a></li>
					<li class="nav-item"><a class="nav-link" href="{{ route('deliveryboy') }}">Delivery Boy</a></li>
					
				</ul>				  
			  </li>	-->
			  
			   <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  <span>Utility</span>
				</a>				  
				<ul class="dropdown-menu multilevel scale-up-left">
					
					<li class="nav-item dropdown"><a class="nav-link dropdown-item dropdown-toggle" href="#">Manage Site </a>
					<ul class="dropdown-menu">
					  <li><a class="dropdown-item nav-link" href="{{ route('pages') }}">Static Pages</a></li>
					 {{-- <li><a class="dropdown-item nav-link" href="{{ route('city') }}">Manage City</a></li> --}}
					  <li><a class="dropdown-item nav-link" href="{{ route('store_info') }}">Store info</a></li>
					  <li><a class="dropdown-item nav-link" href="{{ route('faqs') }}">Faqs</a></li>
					   <!--<li><a class="dropdown-item nav-link" href="{{ route('snapbooks') }}">Snapbook</a></li>-->
					    <li><a class="dropdown-item nav-link" href="{{ route('notifications') }}">Notifications</a></li>

						<li><a class="dropdown-item nav-link" href="{{ route('users_role') }}">Sub Admin Roles</a></li>
						<li><a class="dropdown-item nav-link" href="{{ route('permissions') }}">Role Permissions</a></li>

					     <!--<li><a class="dropdown-item nav-link" href="{{ route('customizedProducts') }}">Customized</a></li>-->
					     <!--<li><a class="dropdown-item nav-link" href="{{ route('newCustomization') }}">New Customization</a></li>-->
					   
					  
           			</ul>
				  </li>
                    
				</ul>				  
			  </li>	
			  @else
			    <li class="nav-item">
				<a class="nav-link" href="{{ route('dashboard') }}"><i class="fa fa-dashboard mr-5"></i> <span>Dashboard</span></a>
			  </li>
			  <?php 
			  $records=DB::table('permissions')->select('module_id')->where('user_role_id',auth()->user()->user_role)->get();
			  $permitted=array();
			
			  foreach($records as $record){
			      array_push($permitted,$record->module_id);
			  }
			 if(sizeof($permitted)>0){
			  ?>
			  <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  <i class="fa fa-th mr-5"></i> <span>Master</span>
				</a>				  
				<ul class="dropdown-menu multilevel scale-up-left">
                        <?php
                          if (in_array(1, $permitted)){
                        ?>	
                        <li class="nav-item"><a class="nav-link" href="{{ route('home_slider') }}">Banner</a></li>
                        <?php }?>
                        
                         <?php
                          if (in_array(2, $permitted)){
                        ?>	
                      	<li class="nav-item"><a class="nav-link" href="{{ route('categories') }}">Categories</a></li>
                        <?php }?>
                        
                        <?php
                          if (in_array(3, $permitted)){
                        ?>	
                      	<li class="nav-item"><a class="nav-link" href="{{ route('brands') }}">Brands</a></li>
                        <?php }?>
					
					
					<?php
                          if (in_array(4, $permitted) 
						  || in_array(10, $permitted)
						  || in_array(11, $permitted)
						  || in_array(6, $permitted)
						  || in_array(5, $permitted)
						  || in_array(7, $permitted)
						  || in_array(8, $permitted)
						  || in_array(9, $permitted)
						  ){
                        ?>	
                      		<li class="nav-item dropdown"><a class="nav-link dropdown-item dropdown-toggle" href="#">Products</a>
					<ul class="dropdown-menu">
					    
                            <?php
                            if (in_array(4, $permitted)){
                            ?>	
                            <li><a class="dropdown-item nav-link" href="{{ route('products') }}">Products Listing</a></li>
                            <?php }?>
                            
                            <?php
                            if (in_array(10, $permitted)){
                            ?>	
                             <li><a class="dropdown-item nav-link" href="{{ route('imageUploads') }}">Image upload</a></li>
                            <?php }?>
                            
                            
                                <?php
                                if (in_array(11, $permitted)){
                                ?>	
                                <li><a class="dropdown-item nav-link" href="{{ route('offer_slider',base64_encode(0)) }}">Product Slider</a></li>
                                <?php }?>
					  
					
					 
					  <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle nav-link" href="#">Attributes</a>
						<ul class="dropdown-menu">
                                        <?php
                                        if (in_array(6, $permitted)){
                                        ?>	
                                        <li><a class="dropdown-item nav-link" href="{{ route('colors') }}">Colors</a></li>
                                        <?php }?>
						
                                        <?php
                                        if (in_array(5, $permitted)){
                                        ?>	
                                        <li><a class="dropdown-item nav-link" href="{{ route('sizes') }}">Sizes</a></li>
                                        <?php }?>
                                        
                                        
                                         <?php
                                        if (in_array(7, $permitted)){
                                        ?>	
                                       <li><a class="dropdown-item nav-link" href="{{ route('materials') }}">Materials</a></li>
                                        <?php }?>
                                        
                                         <?php
                                        if (in_array(8, $permitted)){
                                        ?>	
                                       <li><a class="dropdown-item nav-link" href="{{ route('filters') }}">Filters</a></li>
                                        <?php }?>
						
                                <?php
                                if (in_array(9, $permitted)){
                                ?>	
                                <li><a class="dropdown-item nav-link" href="{{ route('productSetting') }}">Product Setting</a></li>
                                <?php }?>
						  
						     
						</ul>
					  </li>
					  
					  
           			</ul>
				  </li>
                        <?php }?>
						
                        <?php
                        if (in_array(12, $permitted)){
                        ?>	
                        <li class="nav-item"><a class="nav-link" href="{{ route('coupons',base64_encode(0)) }}">Coupon Management</a></li>
                        <?php }?>
                        
                        <?php
                        if (in_array(13, $permitted)){
                        ?>	
                         <li class="nav-item"><a class="nav-link" href="{{ route('rating_review') }}">Product Rating & Reviews</a></li>
                        <?php }?>
                            <?php
                            if (in_array(14, $permitted)){
                            ?>	
                            <li class="nav-item"><a class="nav-link" href="{{ route('offers') }}">Offer Zone Assign Categories</a></li>
                            <?php }?>

				  
				     				  
				</ul>				  
			  </li>
                        <?php
                        if (in_array(15, $permitted) ||  in_array(16, $permitted)){
                        ?>	
                        <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-th mr-5"></i> <span>Order Master</span>
                        </a>				  
                        <ul class="dropdown-menu multilevel scale-up-left">
                        <!--<li class="nav-item"><a class="nav-link" href="{{ route('orders',base64_encode(0)) }}">Orders</a></li>-->
                        <li class="nav-item"><a class="nav-link" href="{{ route('sorders',base64_encode(0)) }}">Orders</a></li>
                        
                        <?php
                        if (in_array(16, $permitted)){
                        ?>	
                        <li class="nav-item"><a class="nav-link" href="{{ route('shipping_charges') }}">Shipping Charges</a></li>
                        <?php }?>
                        
                        <?php
                        if (in_array(17, $permitted)){
                        ?>	
                        <li class="nav-item"><a class="nav-link" href="{{ route('reasons') }}">Return/Cancel Reasons</a></li>
                        <?php }?>
                       
                       
                        <!--<li class="nav-item"><a class="nav-link" href="{{ URL::to('/admin/chat/') }}">Chat Support</a></li>-->
                        </ul>				  
                        </li>
                        <?php }?>

						<?php
                        if (in_array(30, $permitted) ||  in_array(31, $permitted)){
                        ?>	
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fa fa-th mr-5"></i> <span>Vendor Master</span>
							</a>				  
							<ul class="dropdown-menu multilevel scale-up-left">
										<?php
										if (in_array(30, $permitted)){
										?>	
										<li class="nav-item"><a class="nav-link" href="{{ route('vendors') }}">Vendors</a></li>
										<?php }?>
										<?php
										if (in_array(31, $permitted)){
										?>	
										<li class="nav-item"><a class="nav-link" href="{{ route('ledger') }}">Ledger</a></li>
										<?php }?>
									<!-- <li class="nav-item"><a class="nav-link" href="{{ route('enquirry') }}">Enquiry</a></li> -->
									
							</ul>				  
					  </li>	
					  <?php }?>

                            
                <?php
                if (in_array(19, $permitted)){
                ?>	
                <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  <i class="fa fa-th mr-5"></i> <span>Report</span>
				</a>				  
				<ul class="dropdown-menu multilevel scale-up-left">
						<li class="nav-item"><a class="nav-link" href="{{ route('reports',base64_encode(0)) }}">Highest Selling Product </a></li>			
					<!--<li class="nav-item"><a class="nav-link" href="{{ route('advertise') }}">Best selling combo </a></li>-->
						<li class="nav-item"><a class="nav-link" href="{{ route('reports',base64_encode(1)) }}">Location wise Products Selling </a></li>
						<li class="nav-item"><a class="nav-link" href="{{ route('reports',base64_encode(2)) }}">Refund or Replaced Orders</a></li>
						<li class="nav-item"><a class="nav-link" href="{{ route('reports',base64_encode(3)) }}">Best Vendors</a></li>
				</ul>				  
			  </li>	
                <?php }?>
			  
			           <?php
                        if (in_array(20, $permitted) || in_array(21, $permitted) || in_array(29, $permitted)){
                        ?>	
                                <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-th mr-5"></i> <span>Customer Master</span>
                            </a>				  
                            <ul class="dropdown-menu multilevel scale-up-left">
							<?php
                                        if (in_array(20, $permitted)){
                                        ?>
                            	<li class="nav-item"><a class="nav-link" href="{{ route('customers') }}">Customers</a></li>
								<?php }?>


                                <?php
                                if (in_array(21, $permitted)){
                                ?>	
                                <li class="nav-item"><a class="nav-link" href="{{ route('subscribers') }}">Subscribers </a></li>
                                <?php }?>
                                        <?php
                                        if (in_array(29, $permitted)){
                                        ?>	
                                        <li class="nav-item"><a class="nav-link" href="{{ route('customers_pay') }}">Customers Payment</a></li>
                                        <?php }?>
                            
                            
                            	
                            </ul>				  
                            </li>
                        <?php }?>
			  
			 	
                        
                                        
			 	  <?php
                                if (in_array(23, $permitted) || in_array(24, $permitted) || in_array(25, $permitted) || in_array(26, $permitted) 
								|| in_array(27, $permitted)
								|| in_array(32, $permitted)
								|| in_array(33, $permitted)
								|| in_array(34, $permitted)
								){
                                ?>	
                        <li class="nav-item dropdown">
                        	<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        	  <i class="fa fa-th mr-5"></i> <span>Manage</span>
                        	</a>				  
                        	<ul class="dropdown-menu multilevel scale-up-left">
                        	    
                        	     <?php
                                        if (in_array(23, $permitted)){
                                        ?>	
                                       	<li class="nav-item"><a class="nav-link" href="{{ route('blogs') }}">Blog</a></li>
                                        <?php }?>
                                        
                                    <?php
                                    if (in_array(24, $permitted)){
                                    ?>	
                                    <li class="nav-item"><a class="nav-link" href="{{ route('testimonials') }}">Testimonial</a></li>
                                    <?php }?>
                                    
                                    <?php
                                    if (in_array(25, $permitted)){
                                    ?>	
                                   	<li class="nav-item"><a class="nav-link" href="{{ route('advertise') }}">Advertisement</a></li>
                                    <?php }?>
                                    
                                    <?php
                                    if (in_array(26, $permitted)){
                                    ?>	
                                   	<li class="nav-item"><a class="nav-link" href="{{ route('whatsmore') }}">What's More</a></li>
                                    <?php }?>


									<?php
                                    if (in_array(32, $permitted)){
                                    ?>	
									<li class="nav-item"><a class="nav-link" href="{{ route('my_offers') }}">Offers</a></li>
									<?php }?>
									<?php
                                    if (in_array(33, $permitted)){
                                    ?>	
					<li class="nav-item"><a class="nav-link" href="{{ route('logistics') }}">Logistics</a></li>
					<?php }?>
					<?php
                                    if (in_array(34, $permitted)){
                                    ?>	
					<li class="nav-item"><a class="nav-link" href="{{ route('vendor_pincode') }}">Port Code</a></li>

					<?php }?>


                        	
                        	 <?php
                                    if (in_array(27, $permitted)){
                                    ?>	
                                   	<li class="nav-item dropdown"><a class="nav-link dropdown-item dropdown-toggle" href="#">Manage Site </a>
                        		<ul class="dropdown-menu">
                        		  <li><a class="dropdown-item nav-link" href="{{ route('pages') }}">Static Pages</a></li>
                        		  <li><a class="dropdown-item nav-link" href="{{ route('store_info') }}">Store info</a></li>
                        		  <li><a class="dropdown-item nav-link" href="{{ route('faqs') }}">Faqs</a></li>
                        		 
                           		</ul>
                        	  </li>
                                    <?php }?>
                        		
                        		
                        			
                        	</ul>				  
                          </li>	

						

						
                                <?php }?>
			 
			   
			  <?php }?>
			  
			  @endif()
			  
			  
			  
			  @endif
			
			  		  
			</ul>
		  </div>
		</nav>	  
  </div>