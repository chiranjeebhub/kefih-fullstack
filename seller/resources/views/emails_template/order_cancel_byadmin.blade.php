<!DOCTYPE html>
<html>    
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Order Cancelled</title>
    <meta name="description" content=" ">
	
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
	
</head>
<body style="margin: 0; background: #e0e3de;">


    <table style=" width: 100%; margin: 40px auto; font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif;">
        
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" style="padding: 15px; background: #fff; width:100%; margin: -5px auto;">
                    <tr bgcolor="#231f20">
						<td style="text-align: center; padding: 5px 0;">

							<a href="{{url('/')}}"><img width="200px;" alt="Redlips" src="{{ asset('public/fronted/images/logo.jpg') }}"></a>
						</td>
					</tr>
                    
                    <tr>
                        <td>
                            <table cellpadding="0" cellspacing="0" style="width: 100%;">
                               
                                <tr>
                                    <td style="padding: 5px 0px; ">
                                        <table cellpadding="0" cellspacing="0" style="width: 100%; font-size:17px; padding: 20px;">
                                            <tr>
												<td>
													<h1 style="margin: 10px 0 10px; font-size: 24px; letter-spacing: 1px; word-spacing: 2px; text-align: center; text-transform:uppercase;">Cancel Order</h1>
												</td>
											</tr>
											<tr>
												<td>
													<p style="margin: 10px 0;">Hi <?php echo ucfirst($data['customer_fname'].' '.$data['customer_lname']); ?>,</p>
													<p style="margin: 10px 0;">We have cancelled your order. For more info contact to our support connect@redliips.com</p>
													<p style="margin: 15px 0 0;">Thank you,</p>
													<p style="margin: 10px 0 40px;"><strong>Redliips</strong>.Com</p>
												</td>
											</tr>
                                            <tr>
                                                <td colspan="2" style="font-size: 16px; text-align:left; border-top: solid 2px #ff000085;"><h1 style="margin: 10px 0 5px; font-size: 20px;">Your Order Details</h1>
												<p style="margin:0 0 20px;"><span style="color:#6a6969;">Order no.:</span><?php echo $data['suborder_no']; ?></p></td>
                                            </tr>
											<tr>
												<td style="padding-bottom: 20px; ">
													<table cellpadding="0" cellspacing="0" style="width: 100%; border: solid 1px #e6e6e6;">
														<tr bgcolor="#fff">
															<td>
																<table cellpadding="0" cellspacing="0" style="width: 100%;">
																	<tr>
																		<td><img style="float: left;" width="150px;" src="https://www.redliips.com/uploads/products/240-180/<?php echo $data['product_details']['product_image']; ?>" />
																		</td>
																		<td style="font-size: 16px; padding: 7px 10px; vertical-align: top;">
																			<div style="text-align: left; width: 75%; float: left; ">
																				<h2 style="font-size: 16px; margin:0; "><?php echo $data['product_details']['product_name']; ?></h2> <br/><br/>
																				<p style="margin:0 0 10px;">Price</p>
																				<!--<p style="margin:0 0 10px;">Discount</p>-->
																				<p style="margin:0 0 10px;"><strong>Total</strong></p>
																				<!--<p style="margin: 5px 0; font-size:14px; color:#a8a6a6; "><i>Sold by: Flashstar Commerce</i></p>-->
																			</div>
																			<div style="display:inline-block; float:right; text-align:right;"><p style="color: #6a6969; margin:0 0 10px;"> <?php echo ($data['product_details']['product_size'])?'Size :'.$data['product_details']['product_size']:''; ?> <br/>Qty : <?php echo $data['product_details']['product_qty']; ?></p><br/><br/>
																			<p style="margin:0 0 10px;"><i class="fa fa-inr"></i><?php echo $data['product_details']['product_price']; ?></p>
																			<!--<p style="margin:0 0 10px;"><i class="fa fa-inr"></i> 00.00</p>-->
																			<p style="margin:0 0 10px;"><strong><i class="fa fa-inr"></i><?php echo $data['product_details']['product_price']*$data['product_details']['product_qty']; ?></strong></p>
																			</div>
																		</td>
																	</tr>
																	
																</table>
															</td>
															
														</tr>
														
													</table>
												</td>
											</tr>
											
											<tr>
												<td>
													<table cellpadding="0" cellspacing="0" style="width: 100%; border-top: solid 2px #ff000085; border-bottom: solid 2px #ff000085; padding:20px 0; margin-bottom: 20px;">
														
														<tr>
															<td width="50%" style="padding: 0px 20px 0px 0; border-right:solid 1px #ccc; vertical-align: top; "><p style="margin: 0 0 10px;"><strong>Delivery Address</strong></p>
															<p style="margin: 0; font-size:16px; color:#6a6969; line-height:24px; "><?php echo $data['shipping_data']['shipping_address'].' '.$data['shipping_data']['shipping_address1'].' '.$data['shipping_data']['shipping_address2'];?><br/> <?php echo $data['shipping_data']['shipping_city'].' '.$data['shipping_data']['shipping_state'].', '.$data['shipping_data']['shipping_zip'];?>,<br/></p>
															</td>
															<td style="padding: 0px 0px 0px 20px; vertical-align: top;"><p style="margin: 0 0 10px;"><strong>Purchase Reference</strong></p>
															<p style="margin: 0 0 10px; font-size:16px; color:#6a6969;">Package Value<span style="text-align: right; float: right;"><i class="fa fa-inr"></i> <?php echo $data['product_details']['product_price']*$data['product_details']['product_qty']; ?> </span></p>
															<!--<p style="margin: 0 0 10px; font-size:16px; color:#6a6969;">Shipping Charge<span style="text-align: right; float: right;"> --> <!--<i class="fa fa-inr"></i> 1500.00 /--> <!--<span style="color: #1aa4bb;">Free</span> </span></p>-->
																
															<p style="margin: 15px 0 10px; font-size:16px; color:#000; font-weight:bold; border-top: solid 1px #ccc; padding: 10px 0 0px;">Total<span style="text-align: right; float: right;"><i class="fa fa-inr"></i> <?php echo $data['product_details']['product_price']*$data['product_details']['product_qty']; ?> </span></p>
															<p style="margin: 0 0 10px; font-size:16px; color:#000;">Mode of Payment<span style="text-align: right; float: right;"><!--<i class="fa fa-inr"></i> 1500.00 /--> <span><?php echo $data['mode']; ?></span> </span></p>
															</td>
														</tr>
														
													</table>
												</td>
											</tr>
											<tr>
												<td>
													<!--<h2 style="margin:0; font-size:20px;">What Happens Next?</h2>
													<p style="margin:10 0px 15px; font-size:16px; color: #6a6969;">We wills send you a confirmation once your bag of joy is out for delivery.<br/> If you want to reach us, please <a href="#" style="color: #000;">Contact Us</a> here.</p>-->
													
													
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
                            <!--<li align="left" style="color: #d01f27; font-size: 18px; float: left; margin-right:38px;"><a style="font-size: 20px; color: #fff; text-decoration: none;" href="https://www.redliips.com/refer_and_earn" target="_blank">REFER AND EARN</a></li>-->
                            <li align="left" style="color: #d01f27; font-size: 18px; float: left;  margin-right:38px;"><a style="font-size: 20px; color: #fff; text-decoration: none;" href="https://www.redliips.com/page/about_us" target="_blank">ABOUT US</a></li>
                            <li align="left" style="color: #d01f27; font-size: 18px; float: left; "><a style="font-size: 20px; color: #fff; text-decoration: none;" href="https://www.redliips.com/page/terms" target="_blank">TERM & CONDITIONS</a></li>							
                        </ul>							
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="font-size:18px; line-height:24px;"> <br/>E-mail us: <a style="color:#000;" href="mailto:connect@redliips.com">connect@redliips.com</a></p>
                        <p style="text-align:center; font-size:18px; line-height:24px; margin: 0;">Follow us: <span style="vertical-align: middle; display: inline-block;
                        margin-left: 10px;">
                        <a href="https://twitter.com/redlipsofficial" target="_blank"><img title="Twitter" width="30px;" src="https://www.redlips.com/public/fronted/images/twitter-icon.png"></a> 
                        <a href="https://www.snapchat.com/add/redlipsofficial" target="_blank"><img title="Snapchat" width="30px;" src="https://www.redlips.com/public/fronted/images/snapchat-icon.png"></a> 
                        <a href="https://www.facebook.com/redlipsofficial/" target="_blank"><img title="Facebook" width="30px;" src="https://www.redlips.com/public/fronted/images/facebook-icon.png"></a> 
                        <a href="https://www.instagram.com/redlipsofficial/?hl=en" target="_blank"><img title="Instagram" width="30px;" src="https://www.redlips.com/public/fronted/images/instagram-icon.png"></a></span></p>
                    </td>
                </tr>
					</tr>
					
                </table>
                
            </td>               
        </tr>
             
    </table>
        
</body>
</html>

