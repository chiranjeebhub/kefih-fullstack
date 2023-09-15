<?php error_reporting(0); $i=1; $total=0;foreach($sub_orders as $order){?>
<?php
 

$prd_detail=App\Products::select('products.vendor_id','products.delivery_days')
->where('products.id','=',$order->product_id)
->first();


$order_shipping=DB::table('orders_courier')
            ->where('orders_courier.order_detail_id',$order->order_id)->first();
        

$cat_tax_percent=DB::table('product_categories')->select('categories.tax_rate')
            ->join('categories','product_categories.cat_id','categories.id')
            ->where('product_categories.product_id',$order->product_id)->first();


if ($prd_detail->vendor_id!=0)
{
	$vendor_info=$prd_detail->getProductsVendorInfo();
	
	if($vendor_info->state!=$shipping_info->order_shipping_state)
	//if($vendor_info->state!=$billing_info->state)	
	{
		$tax_column='1'; //IGST 
	}else{
		$tax_column='2'; //CGST/SGST
	}
}

?>

<?php 
	if($vendor_info->invoice_address==1){
		//echo'vendor address print';
	}else{
		//echo'website address print';
	}
	
	if($vendor_info->invoice_logo==1){
		//echo'vendor logo print';
	}else{
		//echo'website logo print';
	}
	 
?>
													
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Jaldi Kharido</title>
	<link rel="stylesheet" href="http://aptechbangalore.com/test/public/fronted/css/bootstrap.min.css" />
	<link href="https://fonts.googleapis.com/css?family=Be+Vietnam:400,500,700&display=swap" rel="stylesheet"> 
	<style>
		.headertbl{ padding: 15px; }
		.container{ margin-top: 30px; }
		.sellerInvc{ padding: 15px; }
		body table tr td, body table tr th{ padding: 1px 5px;}
		@media screen and (min-device-width:320px) and (max-device-width:480px) {
			body{ font-size: 10px !important; }
			body table tr td{ font-size: 10px !important; }
			.headertbl table tr td{ display: block; width: 100% !important; text-align: left !important; }
			.headertbl{ padding: 5px; }
			.container{ margin: 0 !important; padding: 0 !important;}
			.sellerInvc{ padding: 5px !important; }
			body table tr th{ padding:1px 5px; font-size: 12px !important; }
			body{ overflow-x: hidden !important; }
		}

		.page-break {
			page-break-after: always;
		}
	</style>
</head>

<body style="padding: 0px; margin: 0px;">
<div class="container">
	<div class="row">     	
		<div class="col-md-12 col-xs-12">   
			<table cellpadding="0" cellspacing="0" style="width:100%; font-family: 'Be Vietnam', sans-serif; padding:0px; background: #fff; ">    
				<tr bgcolor="#ebecec">
					<td class="headertbl" style="vertical-align: top;">			
						<table cellpadding="0" cellspacing="0" style="width: 100%;">
							<tr>
								<td style="font-family: 'Be Vietnam', sans-serif;">
									<h1 style="font-size: 26px;font-family: 'Be Vietnam', sans-serif; font-weight: 700; margin: 0; line-height: 24px; ">Tax invoice</h1>
								</td>
							</tr>
						</table>
						<table cellpadding="0" cellspacing="0" style="width: 100%;">
							<tr>
								<td><span>Invoice number : {{$order->order_detail_invoice_num}} </span> <span style="float:right;">Order id : {{$master_order->order_no}} <!--& Item Id : {{$order->id}}--></span></td>
							</tr>
						</table>				
					</td>
				</tr>

				<tr>
					<td class="sellerInvc" style="text-align: left; background: #fff">
						<table cellpadding="7px" cellspacing="0" style="width: 100%; border: solid 1px #737373; font-size: 12px;">
							<tr bgcolor="#ebecec">
								<th style="border-right: solid 1px #737373; font-size: 18px; width: 50%; padding-left: 5px;">Sold by</th>
								<th>{{$vendor_info->company_name}}</th>
							</tr>
							<tr>
								<td style="border-right: solid 1px #737373; padding-left: 5px;">Seller name : 
								{{$vendor_info->public_name}}
								</td>
								<td style=" padding-left: 5px;">Invoice Date : {{date('d-m-Y',strtotime($order->order_detail_invoice_date))}}</td>
							</tr>
							<tr>
								<td style="border-right: solid 1px #737373; padding-left: 5px;">Address : {{$vendor_info->address}}</td>
								<td style=" padding-left: 5px;">Order Date : {{date('d-m-Y',strtotime($master_order->order_date))}}
								<br></br>
								Place Of Supply : {{$vendor_info->company_city}}
								</td>
							</tr>
							<tr>
								<td style="border-right: solid 1px #737373; padding-left: 5px;">Pin : {{$vendor_info->pincode}}</td>
								<td style="padding-left: 5px;">&nbsp;</td>
							</tr>
							<tr>
								<td style="border-right: solid 1px #737373; padding-left: 5px;">GSTIN : {{$vendor_info->gst_no}}</td>
								<td style="padding-left: 5px;">&nbsp;</td>
							</tr>
							<tr>
								<td style="border-right: solid 1px #737373; padding-left: 5px;">PAN : {{$vendor_info->pan_no}}</td>
								<td style="padding-left: 5px;">&nbsp;</td>
							</tr>
						</table>
						<table cellpadding="7px" cellspacing="0" style="width: 100%; border: solid 1px #737373; border-top:none; font-size: 12px;">
							<tr bgcolor="#ebecec">
								<th style="border-right: solid 1px #737373; font-size: 16px; width: 50%; padding-left: 5px;">Shipping Address</th>
								<th style="font-size: 16px; padding-left: 5px;">Billing Address</th>
							</tr>
							<tr>
								<td style="border-right: solid 1px #737373; padding-left: 5px;">Buyer Name : <?php echo ucwords($shipping_info->order_shipping_name);?></td>
								<td style=" padding-left: 5px;">Buyer Name: <?php //echo ucwords($billing_info->name);?> <?php echo ucwords($shipping_info->order_shipping_name);?></td>
							</tr>
							<tr>
								<td style="border-right: solid 1px #737373; padding-left: 5px;">Address : <?php echo $shipping_info->order_shipping_address;?> <?php echo $shipping_info->order_shipping_address1;?> <?php echo $shipping_info->order_shipping_address2;?> <?php echo $shipping_info->order_shipping_city;?> <?php echo $shipping_info->order_shipping_state;?> <?php echo $shipping_info->order_shipping_country;?></td>
								<td style=" padding-left: 5px;">Address: <?php //echo $billing_info->address;?> <?php //echo $billing_info->city;?> <?php //echo $billing_info->state;?> <?php //echo $billing_info->country;?> <?php echo $shipping_info->order_shipping_address;?> <?php echo $shipping_info->order_shipping_address1;?> <?php echo $shipping_info->order_shipping_address2;?> <?php echo $shipping_info->order_shipping_city;?> <?php echo $shipping_info->order_shipping_state;?> <?php echo $shipping_info->order_shipping_country;?></td>
							</tr>
							<tr>
								<td style="border-right: solid 1px #737373; padding-left: 5px;">Pin : <?php echo $shipping_info->order_shipping_zip;?></td>
								<td style=" padding-left: 5px;">Pin : <?php //echo $billing_info->pincode;?> <?php echo $shipping_info->order_shipping_zip;?></td>
							</tr>
							<tr>
								<td style="border-right: solid 1px #737373; padding-left: 5px;">Ph. : <?php echo $shipping_info->order_shipping_phone;?></td>
								<td style=" padding-left: 5px;">Ph. : <?php //echo $billing_info->phone;?> <?php echo $shipping_info->order_shipping_phone;?></td>
							</tr>
							<!--<tr bgcolor="#ebecec">
								<th style="border-right: solid 1px #737373; font-size: 12px; padding-left: 5px;">Dispatched Via. <?php
								if($order_shipping){
								  echo ucwords($order_shipping->courier_name);  
								}
								?></th>
								<th style="font-size: 14px; padding-left: 5px;">Dispatched Doc. No. (AWB) 
								<?php 
								if($order_shipping){
									echo $order_shipping->docket_no;
								}
							  ?></th>
							</tr>-->
						</table>
						<br/>
						<table cellpadding="7px" cellspacing="0" style="width: 100%; border: solid 1px #737373; font-size: 12px;">
							<tr bgcolor="#ebecec">
								<th style="border-right: solid 1px #737373; font-size: 10px; width: 25%;">Item</th>
								<th style="border-right: solid 1px #737373; font-size: 10px;">Qty.</th>
								<th style="border-right: solid 1px #000333; font-size: 10px;">MRP Price($)</th>
								<th style="border-right: solid 1px #000333; font-size: 10px;">Selling Price($)</th>
								<th style="border-right: solid 1px #737373; font-size: 10px;">Discount</th>
								<th style="border-right: solid 1px #737373; font-size: 10px;">Unit Price($)</th>
								<th style="border-right: solid 1px #737373; font-size: 10px;">Taxable Value</th>
								<?php if($tax_column==1){?>
								<th style="border-right: solid 1px #737373; font-size: 10px;">IGST</th>
								<?php } if($tax_column==2){?>
								<th style="border-right: solid 1px #737373; font-size: 10px;">CGST</th>
								<th style="border-right: solid 1px #737373; font-size: 10px;">SGST</th>
								<?php } ?>
								<th style="font-size: 10px; padding-left: 5px;">Total</th>
							</tr>
							<tr>
								<td style="border-right: solid 1px #737373; padding-left: 5px;">{{ucwords($order->product_name)}}
									@if($order->size!='')
									 <br>Size : {{$order->size}}
									@endif
									
									@if($order->color!='')
									<br>Color: {{$order->color}}
									@endif
								</td>
								<?php
									$tax_amt=($order->product_price*$cat_tax_percent->tax_rate)/100;
									$tax_free_amt=$order->product_price-$tax_amt;
									
									/*$product_tax=$order->product_tax;
									//$pp=$order->product_price-$order->order_coupon_amount;
									$pp=$order->product_price; 
									$tax_amt=($pp*$product_tax)/(100+$product_tax);
									$tax_free_amt=$pp-$tax_amt;*/
								?>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">{{$order->product_qty}}</td>
								<td style=" padding-left: 20px; border-right: solid 1px #000333;">{{($order->product_price_old)?number_format($order->product_price_old,2):''}}</td>
								<td style=" padding-left: 20px; border-right: solid 1px #000333;">{{number_format($order->product_price,2)}}</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">{{($order->product_price_old)?$order->product_price_old-$order->product_price:''}}</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">{{number_format($tax_free_amt,2)}}</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;"><?php echo $cat_tax_percent->tax_rate;?>%</td>
								<?php if($tax_column==1){?>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">{{number_format($tax_amt,2)}}</td>
								<?php } if($tax_column==2){?>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">{{number_format(($tax_amt/2),2)}}</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">{{number_format(($tax_amt/2),2)}}</td>
								<?php }?>
								<td style=" padding-left: 5px;">{{$order->product_price}}</td>
							</tr>
							<tr>
								<td style="border-right: solid 1px #737373; padding-left: 5px;">
								<?php if($order->hsn_code!=''){?>
								HSN Code
								{{$order->hsn_code}}
								<?php } ?>
								</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<?php if($tax_column==1){?>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<?php } if($tax_column==2){?>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<?php }?>
								<td style=" padding-left: 5px;">&nbsp;</td>
							</tr>
												
							<tr>
								<td style="border-right: solid 1px #737373; padding-left: 5px;"><br/></td>
								<td style="border-right: solid 1px #737373; padding-left: 5px;"><br/></td>
								<td style="border-right: solid 1px #737373; padding-left: 5px;"><br/></td>
								<td style="border-right: solid 1px #737373; padding-left: 5px;"><br/></td>
								<td style="border-right: solid 1px #737373; padding-left: 5px;"><br/></td>
								<td style="border-right: solid 1px #737373; padding-left: 5px;"><br/></td>
								<td style="border-right: solid 1px #737373; padding-left: 5px;"><br/></td>
								<?php if($tax_column==1){?>
								<td style="border-right: solid 1px #737373; padding-left: 5px;"><br/></td>
								<?php } if($tax_column==2){?>
								<td style="border-right: solid 1px #737373; padding-left: 5px;"><br/></td>
								<td style="border-right: solid 1px #737373; padding-left: 5px;"><br/></td>
								<?php }?>
								<td style="padding-left: 5px;"><br/></td>
							</tr>
							<?php if($order->order_shipping_charges!=0){?>
							<tr>
								<td style="border-right: solid 1px #737373; padding-left: 5px;">Shipping Charges</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<?php if($tax_column==1){?>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<?php } if($tax_column==2){?>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<?php }?>
								<td style=" padding-left: 5px;">{{$order->order_shipping_charges}}</td>
							</tr>
							<?php } ?>
								<?php if($order->order_cod_charges!=0){?>
							<tr>
								<td style="border-right: solid 1px #737373; padding-left: 5px;">COD Charges</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<?php if($tax_column==1){?>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<?php } if($tax_column==2){?>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<td style=" padding-left: 5px; border-right: solid 1px #737373;">&nbsp;</td>
								<?php }?>
								<td style=" padding-left: 5px;">{{$order->order_cod_charges}}</td>
							</tr>
							<?php } ?>
							
							<?php
								//$total+=$order->product_qty*$order->product_price;
								//$i++; 						
							 ?>
							 <?php $total+=$order->product_qty*$order->product_price+$order->order_cod_charges;
								  $ship_total+=$order->order_shipping_charges;
								  $wallet_total+=$order->order_deduct_reward_points;
								  $coupon_total+=$order->order_coupon_amount;
								  $slot_price+= $order->slot_price;
							?>
							<tr>
								<td style="border-right: solid 1px #737373; border-top: solid 1px #737373; font-size: 12px; padding-left: 5px; font-weight: bold;">Sub Total</td>
								<td style="font-size: 12px; padding-left: 5px; border-top: solid 1px #737373; border-right: solid 1px #737373;">&nbsp;</td>
								<td style="font-size: 12px; padding-left: 5px; border-top: solid 1px #737373; border-right: solid 1px #737373;">&nbsp;</td>
								<td style="font-size: 12px; padding-left: 5px; border-top: solid 1px #737373; border-right: solid 1px #737373;">&nbsp;</td>
								<td style="font-size: 12px; padding-left: 5px; border-top: solid 1px #737373; border-right: solid 1px #737373;">&nbsp;</td>
								<td style="font-size: 12px; padding-left: 5px; border-top: solid 1px #737373; border-right: solid 1px #737373;">&nbsp;</td>
								<td style="font-size: 12px; padding-left: 5px; border-top: solid 1px #737373; border-right: solid 1px #737373;">&nbsp;</td>
								<?php if($tax_column==1){?>
								<td style="font-size: 12px; padding-left: 5px; border-top: solid 1px #737373; border-right: solid 1px #737373;">&nbsp;</td>
								<?php } if($tax_column==2){?>
								<td style="font-size: 12px; padding-left: 5px; border-top: solid 1px #737373; border-right: solid 1px #737373;">&nbsp;</td>
								<td style="font-size: 12px; padding-left: 5px; border-top: solid 1px #737373; border-right: solid 1px #737373;">&nbsp;</td>
								<?php }?>
								<td style="font-size: 12px; padding-left: 5px; border-top: solid 1px #737373;">
									{{(number_format(($order->product_qty*$order->product_price),2))}}
								</td>
							</tr>
							<?php $cod_charges=0; if($master_order->cod_charges!=''){ $cod_charges=$master_order->cod_charges;?>
							<tr>
								<td style="border-right: solid 1px #000333; border-top: solid 1px #000333; font-size: 14px; padding-left: 5px; font-weight: bold;">COD Charges</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<?php if($tax_column==1){?>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<?php } if($tax_column==2){?>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<?php }?>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333;">{{number_format(($cod_charges),2)}}</td>
							</tr>
							<?php }if($wallet_total!=0){?>
							<tr>
								<td style="border-right: solid 1px #000333; border-top: solid 1px #000333; font-size: 14px; padding-left: 5px; font-weight: bold;">Wallet Deduct Amt</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<?php if($tax_column==1){?>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<?php } if($tax_column==2){?>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<?php }?>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333;">{{number_format(($wallet_total),2)}}</td>
							</tr>
							<?php } if($coupon_total!=0){?>
							<tr>
								<td style="border-right: solid 1px #000333; border-top: solid 1px #000333; font-size: 14px; padding-left: 5px; font-weight: bold;">Coupon Discount</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 50px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<?php if($tax_column==1){?>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<?php } if($tax_column==2){?>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<?php }?>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333;">{{number_format(($coupon_total),2)}}</td>
							</tr>
							<?php } ?>
								<?php if(@$order->slot_price!=''){?>
					<tr>
					  	<td style="border-right: solid 1px #000333; border-top: solid 1px #000333; font-size: 14px; font-weight: bold;">Slot Charges</td>
					  	<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<?php if($tax_column1==1){?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<?php } if($tax_column1==2){?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<?php }?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
							<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; border-top: solid 1px #000333;">{{number_format(@$order->slot_price,2)}}</td>
					</tr>
					<?php }?>
							<tr>
								<td style="border-right: solid 1px #000333; border-top: solid 1px #000333; font-size: 14px; padding-left: 5px; font-weight: bold;">Grand Total</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<?php if($tax_column==1){?>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<?php } if($tax_column==2){?>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<?php }?>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
								<td style="font-size: 14px; padding-left: 5px; border-top: solid 1px #000333;">{{number_format(round(($total+$ship_total-$wallet_total-$coupon_total+$slot_price)),2)}}</td>
							</tr>
						</table>
					</td>	
				</tr>
				<tr>
					<td bgcolor="#ebecec" style="font-size: 16px; font-weight: bold; padding: 5px 20px;">
						Total amount In words : 
						{!! App\Helpers\CommonHelper::convert_number_to_words(($order->product_qty*$order->product_price)+$ship_total+$cod_charges-$wallet_total-$coupon_total+$slot_price); !!}
						Rupee
					</td>	
				</tr>
				<tr>
					<td style="padding: 5px 15px;">
						<table cellpadding="0" cellspacing="0" style="width: 100%; border-bottom: solid 1px #ddd; padding-bottom: 10px;">
							<tr>
								<td style="width: 50%; vertical-align: top;">
									<h2 style="font-size: 14px; margin-bottom: 0; margin-top:5px;">Declaration</h2>
									<p style="font-size: 12px; margin-top: 5px;">We declarer that this invoice shows the actual price of the goods<br/>described above and that all particulars are true and correct. The<br/>goods sold are intended for end user consumption and not for resale.</p>
								</td>
								<td style="font-size: 12px; text-align: center; width: 50%; line-height: 28px;">For {{$vendor_info->company_name}} <br><span style="width: 200px; height: 50px; display: block; border: solid 1px #333; margin: 0 auto;">&nbsp;</span> Authorized Signatory</td>
							</tr>
						</table>
					</td>			
				</tr>
				<tr>
					<td style="padding: 0px 10px;">
						<h2 style="font-size: 14px; margin-bottom: 0; margin-top:5px;">Customer Acknowledgement</h2>
						<p style="font-size: 12px; margin-top: 5px;">I <strong><?php echo ucwords($shipping_info->order_shipping_name);?></strong>, confirm that the said products are being purchased for my internal/personal consumption and not for re-sale.</p>
					</td>	
				</tr>
				<tr>
					<td style="padding: 0px">
						<p style="font-size: 14px; border: solid 1px #737373; padding: 5px; margin: 0;">Beware of fake calls/SMS/Emails offering any cash/price under any fraud scheme/lottery/lucky draw. Do not share any information or pay any amount.</p>
					</td>	
				</tr>
				<tr>
					<td style="float: right; padding: 15px 5px 15px; text-align: right;">
						<img src="{{ asset('public/images/logo.png') }}" style="width:85px; height:40px;" />
						<h2 style="font-weight: 600; font-size: 16px; margin-bottom: 5px;">Thank You!</h2>
						<span style="font-size: 12px; font-weight: normal;">For Shopping With Us</span>
					</td>
				</tr>
				<tr>
					<td style="padding: 5px 15px;">
						<p style="font-size: 12px; margin-top: 5px; font-style: italic; line-height: 16px;"><strong style="font-style: normal;">Return Policy</strong> : At Jaldi kharido we try to deliver perfectly each and every time. But in the off-chance that you need to return the item, please do so with the original Brand box/price tag, original packing and invoice without which it will be really difficult for us to act on your request. Please help us in helping you. Terms and conditions apply. The goods sold as are intended for end user consumption and not for re-sale.<br/></p>
					</td>	
				</tr>                
			</table>
		</div>
	</div>   
</div>
</body>
</html>
<?php }?>