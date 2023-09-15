
<!DOCTYPE html>
<html>    
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Jaldi Kharido</title>
    <meta name="description" content=" ">
	
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
	
</head>
<body style="margin: 0; background: #e0e3de;">

    <table style=" width: 75%; margin: 40px auto; font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif;">
        
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" style="padding: 15px; background: #fff; width:100%; margin: -5px auto;">
                    <tr bgcolor="#231f20">
						<td style="text-align: center; padding: 5px 0;">

							<a href="{{url('/')}}"><img width="200px;" alt="Jaldi Kharido" src="{{ asset('public/fronted/images/logo.png') }}"></a>
						</td>
					</tr>
                    
                    <tr>
                        <td>
                            <table cellpadding="0" cellspacing="0" style="width: 100%;">
                               
                                <tr>
                                    <td style="padding: 5px 0px; ">
                                        <table cellpadding="0" cellspacing="0" style="width: 100%; font-size:17px; line-height:22px; padding:20px; ">
											
											<tr>
												<td>
													
								<div><p style="text-align:center; "><img width="120px" src="https://www.redliips.com/public/fronted/images/confirmed-icon.png"></p>
														<h2 style="font-size: 18px; text-align: center; margin-top: 0; ">Order Confirmed</h2>
														<ul style="text-align:center; padding:0;">
														<?php if($extra_info->order_status==0){ ?>
															<li style="list-style:none; display: inline-block;">
																<div>
																	<div style="border: solid 4px #f46363; height:20px; width:20px; line-height:20px; background:#07bf13; border-radius:50%; display: inline-block; margin-left: 5px;"></div><div style="height:2px; width:60px; background: #ccc; display: inline-block; vertical-align: text-top; margin-left: 10px; border-top: solid 3px #fff; border-bottom: solid 3px #fff;"></div>
																</div>
																<div style="float: left; margin-left: -20px; font-size: 16px; color: #4f4e4e; margin-top: 10px; line-height: 18px;">Order <br/>Confirmed</div>
															</li>
															<?php } ?>
															<li style="list-style:none; display: inline-block;">
																<div>
																	<div style="border: solid 4px #fff; height:20px; width:20px; line-height:20px; background:#ccc; border-radius:50%; display: inline-block; margin-left: 5px;"></div><div style="height:2px; width:60px; background: #ccc; display: inline-block; vertical-align: text-top; margin-left: 10px; border-top: solid 3px #fff; border-bottom: solid 3px #fff;"></div>
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
													<p style="margin: 10px 0;">Hi, {{$vdr->username}}</p>
													<p style="margin: 10px 0;">You have a new order from   {{ucfirst($customer_info->name)}}.</p>
											       <p style="margin: 15px 0 0;">Thank you,</p>
													<p style="margin: 0;"><strong><a href="www.jaldikharido.com ">www.jaldikharido.com </a></strong></p>
												</td>
											</tr>
                                            <tr>
                                                <td colspan="2" style="font-size: 16px; text-align:left; border-top: solid 2px #ff000085;">
											
												<h1 style="margin: 10px 0 5px; font-size: 20px;">Your Order Details</h1>
												<p style="margin:0 0 20px;"><span style="color:#6a6969;">Order no.:</span> {{$extra_info->order_no}}</p></td>
                                            </tr>
                                            <?php $subtotal=0; ?>
                                             <?php 
                               $prd_info=DB::table('products')->where('id',$products->product_id)->first();              
                                if($products->color_id!=0){
                            $colorwise_images=DB::table('product_configuration_images')
                            ->where('product_id',$products->product_id)
                            ->where('color_id',$products->color_id)
                            ->first();

                           // $prdimage=Config::get('constants.Url.public_url');
                            if($colorwise_images){
$url=URL::to('/uploads/products/240-180').'/'.$colorwise_images->product_config_image;
        //$url.=Config::get('constants.uploads.product_images').'/'.$image1; 
        if(@$colorwise_images->product_config_image){
			$url="https://redliips.com/uploads/products/240-180/".$colorwise_images->product_config_image; 
		} else{
		$url="https://redliips.com/uploads/products/".$prd_info->default_image; 	
		}
                               
                            } 
                           else{
                                 //$url=URL::to('/uploads/products').'/'.$products->default_image; 
                    $url="https://redliips.com/uploads/products/".$prd_info->default_image;
                            }
                                } else{
                  //  $url=URL::to('/uploads/products').'/'.$products->default_image; 
                  
  $url="https://redliips.com/uploads/products/".$prd_info->default_image;                
                                                   }
                       
                                                   
                                //   $subtotal +=intval($products->product_qty)*intval($products->product_price);   
                                     $p_price = (intval($products->product_qty)*intval($products->product_price))+$products->order_shipping_charges+$products->order_cod_charges-$products->order_coupon_amount-$products->order_wallet_amount;
                                     $subtotal +=$p_price;

                                                   ?>
											<tr>
												<td style="padding-bottom: 20px;">
													<table cellpadding="0" cellspacing="0" style="width: 100%; border: solid 1px #e6e6e6; ">
														<tr>
															<td><img style="float: left;" width="150px;" src="{{$url}}" />
															</td>
															<td style="font-size: 16px; padding: 7px 10px; vertical-align: top;">
																<div style="text-align: left; width: 75%; float: left; ">
																	<h2 style="font-size: 16px; margin:0; line-height:20px;">{{$products->product_name}}</h2> <br/>
																	<p style="margin:0 0 10px;">Price</p>
																	<!--<p style="margin:0 0 10px;">Discount</p>-->
																	<p style="margin:0 0 10px;"><strong>Total</strong></p>
																
																</div>
																<div style="display:inline-block; float:right; text-align:right;"><p style="color: #6a6969; margin:0;"><?php if($products->size){ ?>Size : {{$products->size}} <br/><?php } ?> <?php if($products->color){ ?>Color : {{$products->color}} <br/> <?php } ?>Qty : {{$products->product_qty}}</p><br/>
																<p style="margin:0 0 10px;">Rs. {{$products->product_price}}</p>
																<!--<p style="margin:0 0 10px;"><i class="fa fa-inr"></i> 50.00</p>-->
															
																	<p style="margin:0 0 10px;"><strong>Rs. {{$products->product_price}}</strong></p>
																<?php /*<p style="margin:0 0 10px;"><strong>Rs. {{@$products->product_qty*@$products->product_price}}</strong></p> */ ?>
																</div>
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
															
										<?php echo '<p style="margin: 0; font-size:16px; color:#6a6969; line-height:24px; ">
                    '.$shipping_info->order_shipping_name.'<br />
                    '.$shipping_info->order_shipping_phone.'<br />
                    '.$shipping_info->order_shipping_email.'<br />
                    '.$shipping_info->order_shipping_address.'<br />
                    '.$shipping_info->order_shipping_address1.'<br />
                    '.$shipping_info->order_shipping_address2.'<br />
                    '.$shipping_info->order_shipping_state.'<br />
                    '.$shipping_info->order_shipping_city.'<br />
                    
                    '.$shipping_info->order_shipping_zip.'<br />
                        
                    </p>'; ?>					
															
														
															</td>
															<td style="padding: 0px 0px 0px 20px; vertical-align: top;"><p style="margin: 0 0 10px;"><strong>Purchase Reference</strong></p>
															<p style="margin: 0 0 10px; font-size:16px; color:#6a6969;">Package Value<span style="text-align: right; float: right;">Rs. {{$subtotal}}</span></p>
															
					<?php  if(@$extra_info->coupon_code!=''){ ?>											
					<p style="margin: 0 0 10px; font-size:16px; color:#6a6969;">Coupon Discount ({{@$extra_info->coupon_percent}}) %<span style="text-align: right; float: right;"> 
						
				<span style="color: #1aa4bb;">Rs. {{@$extra_info->coupon_amount}}</span>
						
						 </span></p>										
					<?php } ?>										
															<p style="margin: 0 0 10px; font-size:16px; color:#6a6969;">Shipping Charge<span style="text-align: right; float: right;"> 
						<?php  if(@$extra_info->total_shipping_charges!=''){ ?>	
				<span style="color: #1aa4bb;">Rs. {{@$extra_info->total_shipping_charges}}</span>
						<?php } else { ?>
						<span style="color: #1aa4bb;">Free</span>
						<?php } ?>
						 </span></p>
						 
						 <?php if(@$payment=="COD"){ ?>
					<p style="margin: 0 0 10px; font-size:16px; color:#6a6969;">COD Charge<span style="text-align: right; float: right;"> 
						
				<span style="color: #1aa4bb;">Rs. {{$products->order_cod_charges}}</span>
						
						 </span></p>	
						 
						 
						  
						 
							<?php } ?>	
							
			 <?php if(@$extra_info->deduct_reward_points!=''){ ?>
					<p style="margin: 0 0 10px; font-size:16px; color:#6a6969;">Jaldi Kharido coins<span style="text-align: right; float: right;"> 
						
				<span style="color: #1aa4bb;">Rs. {{@$extra_info->deduct_reward_points}}</span>
						
						 </span></p>	
						 
						 
						  
						 
							<?php } ?>				
							
															
															<p style="margin: 15px 0 10px; font-size:16px; color:#000; font-weight:bold; border-top: solid 1px #ccc; padding: 10px 0 0px;">Total<span style="text-align: right; float: right;">Rs. {{($products->product_price*$products->product_qty+$products->order_cod_charges-$products->order_deduct_reward_points)}} </span></p>
															<p style="margin: 0 0 10px; font-size:16px; color:#000;">Mode of Payment<span style="text-align: right; float: right;"> <span>{{$payment}}</span> </span></p>
															</td>
														</tr>
														
													</table>
												</td>
											</tr>
											<tr>
												<td>
													<h2 style="margin:0; font-size:20px;">What Happens Next?</h2>
													<p style="margin:10 0px 15px; font-size:16px; color: #6a6969;">We wills send you a confirmation once your bag of joy is out for delivery.<br/> If you want to reach us, please <a href="{{route('page_url',['contact'])}}" target="_blank" style="color: #000;">Contact Us</a> here.</p>
													
													
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
								<!--<li align="left" style="color: #d01f27; font-size: 18px; float: left; margin-right:38px;"><a style="font-size: 20px; color: #fff; text-decoration: none;" href="{{route('refer_and_earn')}}" target="_blank">REFER AND EARN</a></li>-->
								<li align="left" style="color: #d01f27; font-size: 18px; float: left;  margin-right:38px;"><a style="font-size: 20px; color: #fff; text-decoration: none;" href="{{route('page_url',['about_us'])}}" target="_blank">ABOUT US</a></li>
								<li align="left" style="color: #d01f27; font-size: 18px; float: left; "><a style="font-size: 20px; color: #fff; text-decoration: none;" href="{{route('page_url',['terms'])}}" target="_blank">TERMS & CONDITIONS</a></li>				
							
							</ul>
							
						</td>
					</tr>
					<tr>
						<td><p style="font-size:18px; line-height:24px;">E-mail us: <a style="color:#000;" href="mailto:anupsharma6580@gmail.com">anupsharma6580@gmail.com</a></p>
						<p style="text-align:center; font-size:18px; line-height:24px; margin: 0;">Follow us: <span style="vertical-align: middle; display: inline-block;
margin-left: 10px;">
							<a href="#" target="_blank"><img title="Twitter" width="30px;" src="{{ asset('public/fronted/images/twitter-icon.png') }}"></a> 
							<a href="#" target="_blank"><img title="Snapchat" width="30px;" src="{{ asset('public/fronted/images/snapchat-icon.png') }}"></a> 
							<a href="#" target="_blank"><img title="Facebook" width="30px;" src="{{ asset('public/fronted/images/facebook-icon.png') }}"></a> 
                            <a href="#" target="_blank"><img title="Instagram" width="30px;" src="{{ asset('public/fronted/images/instagram-icon.png') }}"></a>
                            </span></p>
						</td>
					</tr>
					
                </table>
                
            </td>               
        </tr>
             
    </table>
        
</body>
</html>

