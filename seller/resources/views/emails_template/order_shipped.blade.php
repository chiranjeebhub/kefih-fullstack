<!DOCTYPE html>
<html>    
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Redliips</title>
    <meta name="description" content=" ">
	
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
	
</head>
<body style="margin: 0; background: #e0e3de;">

    <table style=" width: 50%; margin: 40px auto; font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif;">
        
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" style="padding: 15px; background: #fff; width:100%; margin: -5px auto;">
                    <tr bgcolor="#231f20">
						<td style="text-align: center; padding: 5px 0;">

							<a href="{{url('/')}}"><img width="200px;" alt="Redliips" src="{{ asset('public/fronted/images/logo.jpg') }}"></a>
						</td>
					</tr>
                    
                    <tr>
                        <td>
                            <table cellpadding="0" cellspacing="0" style="width: 100%;">
                               
                                <tr>
                                    <td style="padding: 5px 20px; ">
                                        <table cellpadding="0" cellspacing="0" style="width: 100%; font-size:17px; line-height:22px; padding:20px;">
											<!--<tr>
												<td>
													<h1 style="margin: 10px 0 10px; font-size: 24px; letter-spacing: 1px; word-spacing: 2px; text-align: center;">ORDER SHIPPED</h1>
												</td>
											</tr>-->
											<tr>
												<td>
													
													<div><p style="text-align:center;"><img width="120px" src="https://www.redlips.com/public/fronted/images/shipped-icon.png"></p>
														<h2 style="font-size: 18px; text-align: center; margin-top: 0; ">Order Shipped</h2>
														<ul style="text-align:center; padding:0;">
															<li style="list-style:none; display: inline-block;">
																<div>
																	<div style="border: solid 4px #fff; height:20px; width:20px; line-height:20px; background:#07bf13; border-radius:50%; display: inline-block; margin-left: 5px;"></div><div style="height:2px; width:60px; background: #ccc; display: inline-block; vertical-align: text-top; margin-left: 10px; border-top: solid 3px #fff; border-bottom: solid 3px #fff;"></div>
																</div>
																<div style="float: left; margin-left: -20px; font-size: 16px; color: #4f4e4e; margin-top: 10px; line-height: 18px;">Order <br/>Confirmed</div>
															</li>
															<li style="list-style:none; display: inline-block;">
																<div>
																	<div style="border: solid 4px #f46363; height:20px; width:20px; line-height:20px; background:#07bf13; border-radius:50%; display: inline-block; margin-left: 5px;"></div><div style="height:2px; width:60px; background: #ccc; display: inline-block; vertical-align: text-top; margin-left: 10px; border-top: solid 3px #fff; border-bottom: solid 3px #fff;"></div>
																</div>
																<div style="float: left; margin-left: -10px; font-size: 16px; color: #4f4e4e; margin-top: 10px; line-height: 18px;">Order <br/>Shipped</div>
															</li>
															<li style="list-style:none; display: inline-block;">
																<div>
																	<div style="border: solid 4px #fff; height:20px; width:20px; line-height:20px; background:#ccc; border-radius:50%; display: inline-block; margin-left: 5px;"></div><div style="height:2px; width:60px; background: #ccc; display: inline-block; vertical-align: text-top; margin-left: 10px; border-top: solid 3px #fff; border-bottom: solid 3px #fff;"></div>
																</div>
																<div style="float: left; margin-left: -10px; font-size: 16px; color: #4f4e4e; margin-top: 10px; line-height: 18px;">Out for <br/>Delivery</div>
															</li>
															<li style="list-style:none; display: inline-block;">
																<div style="width: 30px;">
																	<div style="border: solid 4px #fff; height:20px; width:20px; line-height:20px; background:#ccc; border-radius:50%; display: inline-block; margin-left: 5px;"></div>
																</div>
																<div style="float: left; margin-left: -10px; font-size: 16px; color: #4f4e4e; margin-top: 10px; line-height: 18px;">Order <br/>Delivered</div>
															</li>
														</ul>
													</div>
													
												</td>
											</tr>
                                            <tr>
												<td>
													<p style="margin: 10px 0;">Hi <?php echo $data['message']['customer_data']->name.' '.$data['message']['customer_data']->last_name;?>,</p>
													<p style="margin: 10px 0;">Your order (<?php echo $data['message']['master_order'][0]->suborder_no;?>) is on its way to you with (<?php echo $data['message']['courier_data']->courier_name;?>.) It will reach you by <?php $date = strtotime("+".$data['message']['courier_data']->max_delivery_days." days"); echo date('M d, Y', $date);?><!--(27) Jun-->. Your Tracking ID-(<?php echo $data['message']['courier_data']->tracking_number;?>). Stay Stylish! </p>
													<p style="margin: 15px 0 0;">Thank you,</p>
													<p style="margin: 10px 0 40px; line-height:24px;"><strong>Redliips</strong>.Com</p>
												</td>
											</tr>
                                            <tr>
                                                <td colspan="2" style="font-size: 16px; text-align:left; border-top: solid 2px #ff000085;">
												<div style="display: inline-block; width: 100%; text-align: center; margin: 20px 0;"><a href="#" style="background:#e80000; color:#fff; text-decoration: none; border-radius:3px; padding:7px 20px;">Track Order</a></div>	
												<h1 style="margin: 10px 0 5px; font-size: 20px;">Your Order Details</h1>
												<p style="margin:0 0 20px;"><span style="color:#6a6969;">Order no.:</span> <?php echo $data['message']['master_order'][0]->suborder_no;?></p></td>
                                            </tr>
											<tr>
												<td style="padding-bottom: 20px; ">
													<table cellpadding="0" cellspacing="0" style="width: 100%; border: solid 1px #e6e6e6;">
														<?php $total=0; foreach($data['message']['master_order'] as $prd){?>
														<?php
																$prd_info=DB::table('products')
																	->select('products.*')
																	->where('products.id',$prd->product_id)->first();
																
																$image=$prd_info->default_image;
												
																$config_images=DB::table('product_configuration_images')
																	->where('product_id',$prd->product_id)
																	->where('color_id',$prd->color_id)->first();
																	
																if(@$config_images->product_config_image!='')
																{
																	$image=$config_images->product_config_image;
																}
																
                                                          $p_price = (intval($prd->product_qty)*intval($prd->product_price))+$prd->order_shipping_charges+$prd->order_cod_charges-$prd->order_coupon_amount-$prd->order_wallet_amount;

														?>
														<tr bgcolor="#fff">
															<td>
																<table cellpadding="0" cellspacing="0" style="width: 100%;">
																	<tr>
																		<td><img style="float: left;" width="150px;" src="<?php echo URL::to('/uploads/products').'/'.$image;?>" />
																		</td>
																		<td style="font-size: 16px; padding: 7px 10px; vertical-align: top;">
																			<div style="text-align: left; width: 75%; float: left; ">
																				<h2 style="font-size: 16px; margin:0; line-height:20px;"><?php echo ucwords($prd->product_name);?></h2> <br/>
																				<p style="margin:0 0 10px;">Price</p>
																				<p style="margin:0 0 10px;">Discount</p>
																				<p style="margin:0 0 10px;"><strong>Total</strong></p>
																				<!--<p style="margin: 5px 0; font-size:14px; color:#a8a6a6; "><i>Sold by: Flashstar Commerce</i></p>-->
																			</div>
																			<div style="display:inline-block; float:right; text-align:right;"><p style="color: #6a6969; margin:0;"><?php echo ($prd->color!='')?'<br/>Color : '.$prd->color.'<br/>':'';?> <?php echo ($prd->size!='')?'Size : '.$prd->size.'<br/>':'';?> <br/>Qty : <?php echo $prd->product_qty;?></p><br/>
																			<p style="margin:0 0 10px;"><i class="fa fa-inr"></i> <?php echo $prd->product_price_old;?></p>
																			<p style="margin:0 0 10px;"><i class="fa fa-inr"></i> <?php echo $prd->product_price_old-$prd->product_price;?></p>
																			<p style="margin:0 0 10px;"><strong><i class="fa fa-inr"></i> <?php echo $prd->product_price;?></strong></p>
																			</div>
																		</td>
																	</tr>
																	
																</table>
															</td>
															<?php 
															$total+= $p_price;
															
												// 			$total+= $prd->product_qty*$prd->product_price;
															
															?>
														</tr>
														<?php } ?>
														
													</table>
												</td>
											</tr>
											
											<tr>
												<td>
													<table cellpadding="0" cellspacing="0" style="width: 100%; border-top: solid 2px #ff000085; border-bottom: solid 2px #ff000085; padding:20px 0; margin-bottom: 20px;">
														
														<tr>
															<td width="50%" style="padding: 0px 20px 0px 0; border-right:solid 1px #ccc; vertical-align: top; "><p style="margin: 0 0 10px;"><strong>Delivery Address</strong></p>
															<p style="margin: 0; font-size:16px; color:#6a6969; line-height:24px; "><?php echo $data['message']['shipping_data']->order_shipping_name; ?>, 
															<?php echo $data['message']['shipping_data']->order_shipping_address; ?> <?php echo $data['message']['shipping_data']->order_shipping_address1; ?> <?php echo $data['message']['shipping_data']->order_shipping_address2; ?>
															<br/> <?php echo $data['message']['shipping_data']->order_shipping_city; ?>,<br/> <?php echo $data['message']['shipping_data']->order_shipping_state; ?> <?php echo $data['message']['shipping_data']->order_shipping_zip; ?></p>
															</td>
															<td style="padding: 0px 0px 0px 20px; vertical-align: top;"><p style="margin: 0 0 10px;"><strong>Purchase Reference</strong></p>
															<p style="margin: 0 0 10px; font-size:16px; color:#6a6969;">Package Value<span style="text-align: right; float: right;"><i class="fa fa-inr"></i> <?php echo $total;?> </span></p>
															<p style="margin: 0 0 10px; font-size:16px; color:#6a6969;">Shipping Charge<span style="text-align: right; float: right;"><!--<i class="fa fa-inr"></i> 1500.00 /--> <span style="color: #000;">Free</span> </span></p>
																
															<p style="margin: 15px 0 10px; font-size:16px; color:#000; font-weight:bold; border-top: solid 1px #ccc; padding: 10px 0 0px;">Total<span style="text-align: right; float: right;"><i class="fa fa-inr"></i> <?php echo $total;?> </span></p>
															<p style="margin: 0 0 10px; font-size:16px; color:#000;">Mode of Payment<span style="text-align: right; float: right;"><!--<i class="fa fa-inr"></i> 1500.00 /--> <span><?php echo $data['message']['payment_mode'];?></span> </span></p>
															</td>
														</tr>
														
													</table>
												</td>
											</tr>
											<tr>
												<td>
													<h2 style="margin:0; font-size:20px;">What Happens Next?</h2>
													<p style="margin:10 0px 15px; font-size:16px; color: #6a6969;">We wills send you a confirmation once your bag of joy is out for delivery.<br/> If you want to reach us, please <a href="#" style="color: #000;">Contact Us</a> here.</p>
													
													
												</td>
											</tr>
											
                                        </table>
                                        
                                    
                                    </td>
                                </tr>
								
                            </table>
                        </td>
                    </tr>
					
					<tr bgcolor="#231f20">
						<td>
							<ul style="display:inline-block; width:100%; ">
								
								<li style="color: #d01f27; font-size: 18px; float: left;  margin-right:38px;"><a style="font-size: 20px; color: #fff; text-decoration: none;" href="#">ABOUT US</a></li>
								<li style="color: #d01f27; font-size: 18px; float: left; "><a style="font-size: 20px; color: #fff; text-decoration: none;" href="#">TERMS & CONDITIONS</a></li>
							
							</ul>
							
						</td>
					</tr>
					<tr>
						<td><p style="font-size:18px; line-height:24px;">E-mail us: <a style="color:#000;" href="mailto:connect@redliips.com">connect@redliips.com</a></p>
						<p style="text-align:center; font-size:18px; line-height:24px; margin: 0;">Follow us: <span style="vertical-align: middle; display: inline-block;
margin-left: 10px;">
							<a href="#"><img title="Twitter" width="30px;" src="https://www.redlips.com/public/fronted/images/twitter-icon.png"></a> 
							<a href="#"><img title="Snapchat" width="30px;" src="https://www.redlips.com/public/fronted/images/snapchat-icon.png"></a> 
							<a href="#"><img title="Facebook" width="30px;" src="https://www.redlips.com/public/fronted/images/facebook-icon.png"></a> 
								<a href="#"><img title="Instagram" width="30px;" src="https://www.redlips.com/public/fronted/images/instagram-icon.png"></a></span></p>
						</td>
					</tr>
					
                </table>
                
            </td>               
        </tr>
             
    </table>
        
</body>
</html>

