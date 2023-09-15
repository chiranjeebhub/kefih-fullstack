<!doctype html>
<html class="no-js" lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>Om Rudraksha Centre</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo HTTP_IMAGES_PATH;?>favicon.png">

	<!-- CSS -->

	<!-- Plugins CSS -->
	<link rel="stylesheet" href="<?php echo HTTP_CSS_PATH;?>plugins.css">

	<!-- Main Style CSS -->
	<link rel="stylesheet" href="<?php echo HTTP_CSS_PATH;?>style.css">

</head>
<style>
.disbclss{
	display: none;
}
</style>
<body>

	<?php 
			$dbv ='';
		$cookie_name = "omrudraksha_curr";
        $random_value = $_COOKIE[$cookie_name];
			if($random_value){
				$dbv ="disbclss";
			}
			?>
<div class="newletter-popup <?php echo $dbv; ?>">
	<div id="boxes" class="newletter-container">
		<div id="dialog" class="window">
			<!--<div id="popup2">
				<span class="b-close"><span>close</span></span>
			</div>-->
			
			<div class="box">
				<div class="newletter-title">
					<h2>Select Country</h2>
				</div>
				<div class="box-content newleter-content">
					<div class="form-froup">
						<select class="form-control" onchange="selectCity(this.value)">
						 
						  <option value="1"  id="1">India</option>
						  <option value="2"  id="2">Other Country</option>   
						</select>
					</div>
				</div>
				<!-- /.box-content -->
			</div>
		</div>

	</div>
	<!-- /.box -->
</div>


<!--Offcanvas menu area start-->
<div class="off_canvars_overlay"></div>
	<div class="Offcanvas_menu">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="canvas_open">
						<a href="javascript:void(0)"><i class="ion-navicon"></i></a>
					</div>
					<div class="Offcanvas_menu_wrapper">
						<div class="canvas_close">
							<a href="javascript:void(0)"><i class="ion-android-close"></i></a>
						</div>						
						<div class="header_configure_area">							
							<div class="mini_cart_wrapper">
								<a href="javascript:void(0)">
									<i class="fa fa-shopping-bag"></i>
									<span id="total_item2" class="cart_count"><?php echo count($this->cart->contents());?></span>
								</a>
								<!--mini cart-->
								<div id="mini_cart_container_mob" class="mini_cart">
									<div id ="mini_cart_inner_mob" class="mini_cart_inner">				
										<?php $cart = $this->cart->contents(); 
												if(!empty($cart)){											
													$total = 0;

													foreach($cart as $row){													

													$total = $total + $row['qty']*$row['price'];
										?>
											<div class="headercartbox">
										    	<div class="cart_item">
												<div class="cart_img">
													<a href="#"><img src="<?php echo base_url().'images/thumbs/'.$row['image']; ?>" alt=""></a>
												</div>
												<div class="cart_info">
													<a href=""><?php echo ucfirst(substr($row['name'],0,50)); ?></a>
													<p>Qty: <?php echo $row['qty']; ?>  X <span> 
														<?php 
															$currency = Currency();
															
															if($currency == '1'){
														?>

														<i class="fa fa-usd"></i>

															<?php }else{ ?>

														<i class="fa fa-inr"></i> 

															<?php } ?>
															
															
														<?php echo $row['price']; ?>
													 </span>
													</p>
												</div>
												<div class="cart_remove">
													<a href="<?php echo base_url().'cart/remove/'.$row['rowid']; ?>"><i class="ion-android-close"></i></a>
												</div>
											</div>	
											</div>

											<?php
											 	} 
											?>
											<div class="mini_cart_table">
												<div class="cart_total mt-10">
													<span>Total:</span>
													<span class="price">
														<?php 
															$currency = Currency();
															
															if($currency == '1'){
														?>
															<i class="fa fa-usd"></i>
														<?php }else{ ?>
															<i class="fa fa-inr"></i>
														<?php } ?>
														<?php echo $total; ?>
                                                    </span>
												</div>
											</div> 

											<div class="mini_cart_footer">
												<div class="cart_button">
													<a href="<?php echo base_url().'cart'; ?>">View cart</a>
												</div>
												<div class="cart_button">
													<a href="<?php echo base_url().'checkout'; ?>">Checkout</a>
												</div>

											</div>
										

										<?php }else{
											echo '<div class="text-center text-warning">Cart Empty</div>';
										}
										?>	
                                    </div>
								</div>
								<!--mini cart end-->
							</div>
							<?php 
								if(!empty($this->session->userdata('is_customer_login'))) {
							
							?>
							<ul class="nav navbar-nav navbar-right login sm-collapsible">
								<!--<li><a href="<?php echo base_url().'dashboard'; ?>"><?php echo $this->session->userdata('username'); ?></a>-->
                                <div class="register-box dropdown open">									                                    
										<a class="current-open" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" href="<?php echo base_url().'dashboard'; ?>"><span><?php echo $this->session->userdata('username'); ?></span></a>
										<ul class="dropdown-menu mega_dropdown" role="menu">
											<li><a href="<?php echo base_url(); ?>dashboard">My Dashboard</a></li>
											<li><a href="<?php echo base_url(); ?>login/logout">Logout</a></li>
										</ul>
																								
						     	</div>					
							</ul>
							<?php 
								}else{
							?>
							<ul class="nav navbar-nav navbar-right login sm-collapsible">
								<li><a href="<?php echo base_url().'login'; ?>">Login</a></li> / 
								<li><a href="<?php echo base_url().'register'; ?>">Register</a></li>
							</ul>
							<?php 
								}
							?>							
						</div>
						<div class="search_container">
							<form action="<?php echo base_url().'category/search'; ?>" type="post">								
								<div class="search_box">
									<input placeholder="Search product..." type="text" name="searchterm" value="<?php echo (!empty($searchterm))?$searchterm:''; ?>" required>
									<button type="submit">Search</button>
								</div>
							</form>
						</div>
						<!-- Loading mobile menu bar -->
						<?php echo load_mobile_menu_bar(); ?>
						<!-- Mobile menu bar end -->
						
						<div class="Offcanvas_footer">
						<?php $contact_data = load_contact_details(); ?>
							<span><a href="#"><i class="fa fa-envelope-o"></i><?php echo $contact_data->email; ?></a></span>
							<ul>
								<li class="facebook"><a href="https://www.facebook.com/omrudrakshacentre/?modal=admin_todo_tour"><i class="fa fa-facebook"></i></a></li>
								<li class="twitter"><a href="https://twitter.com/RudrakshaOm"><i class="fa fa-twitter"></i></a></li>
								<li class="instagram"><a href="https://www.instagram.com/omrudraksha1988/"><i class="fa fa-instagram"></i></a></li>
								<li class="whatsapp"><a  href="https://api.whatsapp.com/send?phone=91<?php echo @$contact_data->mobile; ?>" target="_blank"><i class="fa fa-whatsapp"></i></a></li>
										
								<!--<li class="youtube"><a href="#"><i class="fa fa-youtube"></i></a></li>
								<li class="pinterest"><a href="#"><i class="fa fa-pinterest"></i></a></li>
								<li class="linkedin" ><a href="#"><i class="fa fa-linkedin"></i></a></li>
								<li class="facebook"><a href="#"><i class="fa fa-facebook"></i></a></li>
								<li class="twitter"><a href="#"><i class="fa fa-twitter"></i></a></li>
								<li class="linkedin"><a href="#"><i class="fa fa-linkedin"></i></a></li>-->
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
 

	<!--Offcanvas menu area end-->
	<!--header area start-->
	<header>
		<div class="main_header header_four m-0">
			<!--header middel start-->
			<div class="header_middle header_middle_style4">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-lg-4 col-md-6">
							<div class="logo">
								<a href="<?php echo base_url(); ?>"><img src="<?php echo HTTP_IMAGES_PATH; ?>logo.png" alt=""></a>
							</div>
						</div>
						<div class="col-lg-6 col-md-12">
							<div class="search_container">
								<form action="<?php echo base_url().'category/search'; ?>" method="post">								
									<div class="search_box">
										<input placeholder="Search product..." type="text" name="searchterm" value="<?php echo (!empty($searchterm))?$searchterm:''; ?>" required>
										<button type="submit">Search</button>
									</div>
								</form>
							</div>
						</div>
						<div class="col-lg-2 pd0">
							<div class="header_configure_area header_configure_four">

								<div class="mini_cart_wrapper">
									<a href="<?php echo base_url().'cart'; ?>">
										<i class="fa fa-shopping-bag"></i>
										<span id="total_item" class="cart_count"><?php echo count($this->cart->contents());?></span>
									</a>
									<!--mini cart-->
									<div id="mini_cart_container" class="mini_cart">
										<div id="mini_cart_inner" class="mini_cart_inner">
											<?php $cart = $this->cart->contents(); 
												if(!empty($cart)){	?>
											<div class="headercartbox">
													<?php									
													$total = 0;
													foreach($cart as $row){												
													$total = $total + $row['qty']*$row['price'];
												?>
												<div class="cart_item">
												<div class="cart_img">
													<a href="#"><img src="<?php echo base_url().'images/thumbs/'.$row['image']; ?>" alt=""></a>
												</div>
												<div class="cart_info">
													<a href=""><?php echo ucfirst(substr($row['name'],0,50)); ?></a>
													<p>Qty: <?php echo $row['qty']; ?>  X <span> 
													   <?php 
															$currency = Currency();

															if($currency == '1'){
														?>
															<i class="fa fa-usd"></i>
														<?php }else{ ?>
															<i class="fa fa-inr"></i>
														<?php } ?>

														<?php echo $row['price']; ?> </span></p>
												</div>
												<div class="cart_remove">
													<a href="<?php echo base_url().'cart/remove/'.$row['rowid']; ?>"><i class="ion-android-close"></i></a>
												</div>
											</div>

												<?php } ?>

											</div>

											<div class="mini_cart_table">
												<div class="cart_total mt-10">
													<span>Total:</span>

													<span class="price"> 
														<?php 
															$currency = Currency(); 

															if($currency == '1') { ?>
															<i class="fa fa-usd"></i>
														<?php } else { ?>
															<i class="fa fa-inr"></i>
														<?php } ?>
														<?php echo $total; ?>
													</span>

												</div>
											</div> 

											<div class="mini_cart_footer">
												<div class="cart_button">
													<a href="<?php echo base_url().'cart'; ?>">View cart</a>
												</div>
												<div class="cart_button">
													<a href="<?php echo base_url().'checkout'; ?>">Checkout</a>
												</div>
											</div>

											<?php } else { echo '<div class="text-center">Cart Empty</div>'; } ?>										
										</div>

									</div>
									<!--mini cart end-->
								</div>


								<?php if(!empty($this->session->userdata('is_customer_login'))) { ?>
								<div class="register-box dropdown open">                       
									<a class="current-open" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" href="#"><span><?php echo $this->session->userdata('username'); ?></span></a>
									<ul class="dropdown-menu mega_dropdown" role="menu">
										<li><a href="<?php echo base_url(); ?>dashboard">My Dashboard</a></li>
										<li><a href="<?php echo base_url(); ?>login/logout">Logout</a></li>
									</ul>
								</div>

								<?php } else { ?>
								<ul class="nav navbar-nav navbar-right login sm-collapsible">
									<li><a href="<?php echo base_url().'login'; ?>">Login</a></li> / 
									<li><a href="<?php echo base_url().'register'; ?>">Register</a></li>
								</ul>
								<?php } ?>
								
							</div>
						</div>
					</div>
				</div>
			</div>


			<!--header middel start-->
			
			<!--header middel end-->
			<!--header middel end-->

			<!--header bottom satrt-->
			<?php echo (!empty(load_menu_bar()))?load_menu_bar():''; ?>		
					
			<!--header bottom end-->
		</div>
	</header>
	<span id="currencysign" style="display:none;"><?php echo (Currency() == 1)?"usd":"inr";?></span>
	<!--header area end-->
	
	