<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Kefih</title>
	<link href="https://fonts.googleapis.com/css?family=Be+Vietnam:400,500,700&display=swap" rel="stylesheet"> 
</head>

<body style="padding: 0px; margin: 0px; background: #f9f9f9;">
<div style="width:1100px; margin-left:auto; margin-right:auto;">
	
    <div style="width:88%; margin-left:auto; margin-right:auto; margin-top: 30px; margin-bottom: 30px;">
		@php 
		$sellerStateData = DB::table('states')->select('state_code')->where('name', $vdr_data['company_state'])->first(); 
	    $billingStateData = [];

		if(!empty($billingAddress)){
			$billingStateData = DB::table('states')->select('state_code')->where('name', $billingAddress->order_shipping_state)->first(); 
		}		
		$shippingStateData = DB::table('states')->select('state_code')->where('name', $Order[0]['order_shipping_state'])->first(); 

		@endphp 
		<table cellpadding="0" cellspacing="0" style="width:100%; font-family: 'Be Vietnam', sans-serif; padding:0px; background: #fff; ">
    
        <tr bgcolor="#ebecec">
			<td style="vertical-align: top; padding: 30px;">
				<table cellpadding="0" cellspacing="0" style="width: 100%;">
					<!--<tr>
						<td colspan="2" style="text-align: center;"><img src="logo.png" /></td>
					</tr>-->
					<tr>
						<td colspan="2">
							<h1 style="font-size: 26px; font-weight: 700; margin: 0 0 10px; line-height: 24px; ">Tax invoice</h1>
							@if(@$is_frontend != 1)
								<a href="{{route('vendor_orders',base64_encode(0)) }}" class="btn btn-warning"><i class="fa fa-undo"></i> Back</a>
							@endif 
						</td>
					</tr>
					<tr bgcolor="#ebecec">
						<td>Invoice number : {{ ucfirst($Order[0]['order_detail_invoice_num'])}}</td>
						<td style="text-align: right;">Kefih Order Id : {{ucwords($Order[0]['order_no'])}}
					<br>	Sub Order Id : {{$Order[0]['suborder_no']}}
						</td>
					</tr>
				</table>
			</td>
			
        </tr>
		<tr>
			<td style="padding: 30px; text-align: left; background: #fff">
				<table cellpadding="7px" cellspacing="0" style="width: 100%; border: solid 1px #737373; font-size: 14px;">
					<tr bgcolor="#ebecec">
					  	<th style="border-right: solid 1px #737373; font-size: 18px; width: 50%; padding-left: 20px;">Sold by</th>
					  	<th>&nbsp;</th>
					</tr>
					<tr>
												<td style="border-right: solid 1px #737373; padding-left: 20px;">Sold By : {{$vdr_data['company_name']}}</td>
					  	<td style=" padding-left: 20px;">Invoice Date : @if($Order[0]['order_detail_invoice_date']!='') {{ date('d-m-Y',strtotime($Order[0]['order_detail_invoice_date']))}} @endif
						</td>
					</tr>
					<tr>
					  	<td style="border-right: solid 1px #737373; padding-left: 20px;">Address :
					  	{{$vdr_data['company_address']}}
					  	{{$vdr_data['company_state']}} {{$vdr_data['company_city']}} 
					  	</td>
					  	<td style=" padding-left: 20px;">Order Date : {{ date('d-m-Y H:i:s',strtotime($Order[0]['order_date']))}}
						<br><br>
						Place Of Supply: {{ucwords($vdr_data['company_city'])}}
						<br>Payment Mode: 
						@if($Order[0]['payment_mode']==0)
						 COD
						@elseif($Order[0]['payment_mode']==1)
						 Prepaid
						@elseif($Order[0]['payment_mode']==2)
						 Exhibition
						@elseif($Order[0]['payment_mode']==3)
						 Wallet
						@endif 
						</td>
					</tr>
					<tr>
					  	<td style="border-right: solid 1px #737373; padding-left: 20px;">Pin : {{$vdr_data['company_pincode']}} </td>
					  	
					</tr>
					<?php if($vdr_data['gst_no']!=''){?>
					<tr>
					  	<td style="border-right: solid 1px #737373; padding-left: 20px;">GSTIN :  {{$vdr_data['gst_no']}}</td>
					  
					</tr>
					<?php } ?>
					<tr>
					  	<td style="border-right: solid 1px #737373; padding-left: 20px;">
						PAN : {{$vdr_data['pan_no']}}
						<br>State/UT Code : {{$sellerStateData->state_code}}

					 </td>
					  	
					</tr>
				</table>
				<?php 
				
				
					$user_data=App\Customer::where('id',$Order[0]['customer_id'])->get()->first();
					
					
				?>
				<table cellpadding="7px" cellspacing="0" style="width: 100%; border: solid 1px #737373; border-top:none; font-size: 14px;">
					<tr bgcolor="#ebecec">
					  	<th style="border-right: solid 1px #737373; font-size: 18px; width: 50%; padding-left: 20px;">Shipping Address</th>
					  	<th style="font-size: 18px; padding-left: 20px;">Billing Address</th>
					</tr>
					<tr>
						<td style="border-right: solid 1px #737373; padding-left: 20px;">Buyer Name : {{ ucfirst($Order[0]['order_shipping_name'])}}</td>
					  	<td style=" padding-left: 20px;">
						  @if(!empty($billingAddress))
						  	Buyer Name:	{{$billingAddress->order_shipping_name}}							
							@else 
									Buyer Name : {{ ucfirst($Order[0]['order_shipping_name'])}}
							@endif 

						
					    </td>
					</tr>
					<tr>
					  	<td style="border-right: solid 1px #737373; padding-left: 20px;">Address : {{$Order[0]['order_shipping_address']}} {{$Order[0]['order_shipping_address1']}} {{$Order[0]['order_shipping_address2']}} {{$Order[0]['order_shipping_city']}}    
						 {{$Order[0]['order_shipping_state']}}</td>
					  	<td style=" padding-left: 20px;">
						  @if(!empty($billingAddress))
						  	Billing address :	{{$billingAddress->order_shipping_address}}
							
							@else 
							Address : {{$Order[0]['order_shipping_address']}} {{$Order[0]['order_shipping_address1']}} {{$Order[0]['order_shipping_address2']}} {{$Order[0]['order_shipping_city']}}    
						 {{$Order[0]['order_shipping_state']}}
							@endif 

						
						
						</td>
					</tr>
					<tr>
					  	<td style="border-right: solid 1px #737373; padding-left: 20px;">
						Pin : {{ ucfirst($Order[0]['order_shipping_zip'])}}
						
					</td>
					  	<td style=" padding-left: 20px;">
						  @if(!empty($billingAddress))
						    Pin : 	{{$billingAddress->order_shipping_zip}}
							
							@else 
							Pin : {{ ucfirst($Order[0]['order_shipping_zip'])}}
							@endif

					     	
					    </td>
					</tr>
					<tr>
					  	<td style="border-right: solid 1px #737373; padding-left: 20px;">
						Ph. : {{ $Order[0]['order_shipping_phone']}}
						<br>State/UT Code : {{$shippingStateData->state_code}}					

					</td>
					  	<td style=" padding-left: 20px;">
						   @if(!empty($billingAddress))
						 	 Ph. : {{$billingAddress->order_shipping_phone}}						
							@else 
							Ph. : {{ $Order[0]['order_shipping_phone']}}
							@endif


						
						</td>
					</tr>
					<!--<tr bgcolor="#ebecec">
					  	<th style="border-right: solid 1px #737373; font-size: 14px; padding-left: 20px;">Dispatched Via. XXXXX</th>
					  	<th style="font-size: 14px; padding-left: 20px;">Dispatched Doc. No. (AWB) XXXXXXX</th>
					</tr>-->
				</table>
					<br/>
				
				<table cellpadding="7px" cellspacing="0" style="width: 100%; border: solid 1px #000333; font-size: 14px;">
					<tr bgcolor="#ebecec">
					  	<th style="border-right: solid 1px #000333; font-size: 14px; width: 30%; padding-left: 20px;">Item</th>
					  	<th style="border-right: solid 1px #000333; font-size: 14px;">Qty.</th>
						<!--<th style="border-right: solid 1px #000333; font-size: 14px; padding-left: 20px; font-weight: normal;">MRP Price(Rs.)</th>-->
						<!-- <th style="border-right: solid 1px #000333; font-size: 14px;">Selling Price(Rs.)</th> -->
						<!--<th style="border-right: solid 1px #000333; font-size: 14px; padding-left: 20px; font-weight: normal;">Discount(Rs.)</th>-->
						<?php if($vdr_data['gst_no']!=''){?>
						<th style="border-right: solid 1px #000333; font-size: 14px;">Unit Price(Rs.)</th>
						<!--<th style="border-right: solid 1px #000333; font-size: 14px; padding-left: 20px;">Discount</th>-->
						<th style="border-right: solid 1px #000333; font-size: 14px;">Tax Rate(%)</th>
						
						<?php
							$prd_detail=App\Products::select('products.hsn_code','products.vendor_id','products.delivery_days','products.shipping_charges')
										->where('products.id','=',$Order[0]['product_id'])
										->first();
										
							if ($prd_detail->vendor_id!=0)
							{
								$vendor_info=$prd_detail->getProductsVendorInfo();
								
								if($vendor_info->state!=$Order[0]['order_shipping_state'])
								//if($vendor_info->state!=$user_data->state)	
								{
									$tax_column1='1'; //IGST
								}else{
									$tax_column1='2'; //CGST/SGST
								}
							}
						?>
						<?php if($tax_column1==1){?>
						<th style="border-right: solid 1px #000333; font-size: 14px;">IGST (Rs.)</th>
						<?php } if($tax_column1==2){?>
						<th style="border-right: solid 1px #000333; font-size: 14px;">CGST (Rs.)</th>
						<th style="border-right: solid 1px #000333; font-size: 14px;">SGST (Rs.)</th>
						<?php }?>
						<?php }?>
						<th style="font-size: 14px;">Total (Rs.)</th>
					</tr>
					<?php $total=$cod_total=$ship_total=$wallet_total=$coupon_total=@$slot_price=0;?>
					
					@foreach($Order as $row)
					<?php
						$prd_detail=App\Products::select('products.hsn_code','products.short_description','products.vendor_id','products.delivery_days','products.shipping_charges','tax')
										->where('products.id','=',$row['product_id'])
										->first();
										
						$order_shipping=DB::table('orders_courier')
								->where('orders_courier.order_detail_id',$row['order_detail_id'])->first();

						$cat_tax_percent=DB::table('product_categories')->select('categories.tax_rate')
									->join('categories','product_categories.cat_id','categories.id')
									->where('product_categories.product_id',$row['product_id'])->first();
						
						//$product_tax=$cat_tax_percent->tax_rate;
						$product_tax=$prd_detail->tax;
						//$product_tax=$row['product_tax'];
				
						//$pp=$row['product_price']-$row['order_coupon_amount'];
						$pp=$row['product_price']; 
						// $tax_amt=($pp*$product_tax)/(100+$product_tax);
						
						// $tax_amt=($pp*$product_tax)/100;
					
						// $tax_free_amt=$pp-$tax_amt;

						$tax_free_amt=$pp/(1+$product_tax/100);
						$tax_amt=$tax_free_amt*$product_tax/100;


						if ($prd_detail->vendor_id!=0)
						{
							$vendor_info=$prd_detail->getProductsVendorInfo();
							
							if($vendor_info->state!=$Order[0]['order_shipping_state'])
							//if($vendor_info->state!=$user_data->state)	
							{
								$tax_column='1'; //IGST
							}else{
								$tax_column='2'; //CGST/SGST
							}
							
							DB::table('order_details')->where('order_details.id','=',$row['order_detail_id'])
								->update([
									'order_details.order_detail_invoice_type'=>$tax_column,
									'order_details.order_detail_tax_amt'=>$tax_amt
								]);
						}
					?>
					<tr>
						<td style="border-right: solid 1px #000333; padding-left: 20px;">
							{{$prd_detail->short_description}}

							<br>{{$row['product_name']}}
							<br>{{ isset($row['size'])?'Size: '.$row['size']:''}}
							<br>{{isset($row['color'])?'Color: '.$row['color']:''}}
						</td>
					  	<td style="border-right: solid 1px #000333;">{{$row['product_qty']}}</td>
						<!--<td style=" padding-left: 20px; border-right: solid 1px #000333;">{{($row['product_price_old'])?number_format($row['product_price_old'],2):''}}</td>-->
						<!-- <td style="border-right: solid 1px #000333;">{{number_format($row['product_price'],2)}}</td> -->
						<!--<td style=" padding-left: 20px; border-right: solid 1px #000333;">{{($row['product_price_old'])?number_format(($row['product_price_old']-$row['product_price']),2):''}}</td>-->
						<?php if($vdr_data['gst_no']!=''){?>
						<td style="border-right: solid 1px #000333;">{{number_format($tax_free_amt,2)}}</td>
						<td style="border-right: solid 1px #000333;"><?php echo $product_tax;?></td>
						
						<?php if($tax_column==1){?>
						<td style="border-right: solid 1px #000333;">{{number_format($tax_amt,2)}}</td>
						<?php } else if($tax_column==2){?>
						<td style="border-right: solid 1px #000333;">{{number_format(($tax_amt/2),2)}}</td>
						<td style="border-right: solid 1px #000333;">{{number_format(($tax_amt/2),2)}}</td>
						<?php }?>
						<?php }?>
						<td style="">{{number_format($tax_free_amt+$tax_amt,2)}}</td>
					</tr>
					<tr>
					  	<td style="border-right: solid 1px #000333; padding-left: 20px;">
							<?php if($prd_detail->hsn_code!=''){?>
							HSN Code
							{{$prd_detail->hsn_code}}
							<?php } ?>
						</td>
					  	<td style="border-right: solid 1px #000333;">&nbsp;</td>
						<!--<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>-->
						<!-- <td style="border-right: solid 1px #000333;">&nbsp;</td> -->
						<?php if($vdr_data['gst_no']!=''){?>
						<td style="border-right: solid 1px #000333;">&nbsp;</td>
						<td style="border-right: solid 1px #000333;">&nbsp;</td>
						<?php if($tax_column==1){?>
						<td style="border-right: solid 1px #000333;">&nbsp;</td>
						<?php } if($tax_column==2){?>
						<td style="border-right: solid 1px #000333;">&nbsp;</td>
						<td style="border-right: solid 1px #000333;">&nbsp;</td>
						<?php }?>
						<?php }?>
						<td style="">&nbsp;</td>
					</tr>
					<!--<tr>
					  	<td style="border-right: solid 1px #000333; padding-left: 20px;">warranty</td>
					  	<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<td style=" padding-left: 20px;">&nbsp;</td>
					</tr>
					<tr>
					  	<td style="border-right: solid 1px #000333; padding-left: 20px;">IGST</td>
					  	<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<td style=" padding-left: 20px;">&nbsp;</td>
					</tr>-->
					<tr>
						<td style="border-right: solid 1px #000333; padding-left: 20px;"><br/></td>
						<td style="border-right: solid 1px #000333; padding-left: 20px;"><br/></td>
						<!--<td style="border-right: solid 1px #000333; padding-left: 20px;"><br/></td>
						<td style="border-right: solid 1px #000333; padding-left: 20px;"><br/></td>-->
						<!-- <td style="border-right: solid 1px #000333; padding-left: 20px;"><br/></td> -->
						<?php if($vdr_data['gst_no']!=''){?>
						<td style="border-right: solid 1px #000333; padding-left: 20px;"><br/></td>
						<td style="border-right: solid 1px #000333; padding-left: 20px;"><br/></td>
						<?php if($tax_column==1){?>
						<td style="border-right: solid 1px #000333; padding-left: 20px;"><br/></td>
						<?php } if($tax_column==2){?>
						<td style="border-right: solid 1px #000333; padding-left: 20px;"><br/></td>
						<td style="border-right: solid 1px #000333; padding-left: 20px;"><br/></td>
						<?php }?>
						<?php }?>
						<td style="padding-left: 20px;"><br/></td>
					</tr>
					<tr>
					  	<td style="border-right: solid 1px #000333; padding-left: 20px; font-weight: bold;">Shipping Charges</td>
					  	<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<!--<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>-->
						<!-- <td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td> -->
						<?php if($vdr_data['gst_no']!=''){?>
						<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<?php if($tax_column==1){?>
						<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<?php } if($tax_column==2){?>
						<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<?php }?>
						<?php }?>
						<td style="">{{($row['order_shipping_charges']!=0)?$row['order_shipping_charges']:'Free'}}</td>
					</tr>
					
						<!--<tr>
					  	<td style="border-right: solid 1px #000333; padding-left: 20px;">COD Charges</td>
					  	<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<?php if($tax_column==1){?>
						<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<?php } if($tax_column==2){?>
						<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<td style=" padding-left: 20px; border-right: solid 1px #000333;">&nbsp;</td>
						<?php }?>
						<td style=" padding-left: 20px;">{{($row['order_cod_charges']!=0)?$row['order_cod_charges']:'Free'}}</td>
					</tr>-->
					<?php //$total+=$row['product_qty']*$row['product_price']+$row['order_cod_charges'];
					$total+=$row['product_qty']*$row['product_price'];
						  $ship_total+=$row['order_shipping_charges'];
						  $wallet_total+=$row['order_deduct_reward_points'];
						  $coupon_total+=$row['order_coupon_amount'];
						  $cod_total+=$row['order_cod_charges'];
						   $slot_price+=@$row['slot_price'];
					?>
					@endforeach
					<tr>
					  	<td style="border-right: solid 1px #000333; border-top: solid 1px #000333; font-size: 14px; padding-left: 20px; font-weight: bold;">Sub Total</td>
					  	<!-- <td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td> -->
						<!--<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>-->
						<?php if($vdr_data['gst_no']!=''){?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<?php if($tax_column1==1){?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<?php } if($tax_column1==2){?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<?php }?>
						<?php }?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; border-top: solid 1px #000333;">{{number_format(($total+$ship_total),2)}}</td>
					</tr>
					<?php if($Order[0]['cod_charges']!=''){?>
					<tr>
					  	<td style="border-right: solid 1px #000333; border-top: solid 1px #000333; font-size: 14px; padding-left: 20px; font-weight: bold;">COD Charges</td>
					  	<!-- <td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td> -->
						<!--<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>-->
						<?php if($vdr_data['gst_no']!=''){?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<?php if($tax_column1==1){?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<?php } if($tax_column1==2){?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<?php }?>
						<?php }?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; border-top: solid 1px #000333;">{{number_format($cod_total,2)}}</td>
					</tr>
					<?php }?>
						<?php /* if(@$Order[0]['slot_price']!=''){?>
					<tr>
					  	<td style="border-right: solid 1px #000333; border-top: solid 1px #000333; font-size: 14px; padding-left: 20px; font-weight: bold;">Slot Charges</td>
					  	<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<!--<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>-->
						<?php if($vdr_data['gst_no']!=''){?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<?php if($tax_column1==1){?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<?php } if($tax_column1==2){?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<?php }?>
						<?php }?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; border-top: solid 1px #000333;">{{number_format($slot_price,2)}}</td>
					</tr>
					<?php } */ ?>
					<?php if($wallet_total!=0){?>
					<tr>
					  	<td style="border-right: solid 1px #000333; border-top: solid 1px #000333; font-size: 14px; padding-left: 20px; font-weight: bold;">Wallet Deduction</td>
					  	<!-- <td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td> -->
						<!--<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>-->
						<?php if($vdr_data['gst_no']!=''){?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<?php if($tax_column1==1){?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<?php } if($tax_column1==2){?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<?php }?>
						<?php }?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; border-top: solid 1px #000333;">{{number_format(($wallet_total),2)}}</td>
					</tr>
					<?php } if($coupon_total!=0){?>
					<tr>
					  	<td style="border-right: solid 1px #000333; border-top: solid 1px #000333; font-size: 14px; padding-left: 20px; font-weight: bold;">Coupon Discount</td>
					  	<!-- <td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td> -->
						<!--<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>-->
						<?php if($vdr_data['gst_no']!=''){?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<?php if($tax_column1==1){?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<?php } if($tax_column1==2){?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<?php }?>
						<?php }?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; border-top: solid 1px #000333;">{{number_format(($coupon_total),2)}}</td>
					</tr>
					<?php } ?>
					<tr>
					  	<td style="border-right: solid 1px #000333; border-top: solid 1px #000333; font-size: 14px; padding-left: 20px; font-weight: bold;">Grand Total</td>
					  	<!-- <td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td> -->
						<!--<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>-->
						<?php if($vdr_data['gst_no']!=''){?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<?php if($tax_column1==1){?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<?php } if($tax_column1==2){?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td>
						<?php }?>
						<?php }?>
						<td style="font-size: 14px; padding-left: 20px; border-top: solid 1px #000333; border-right: solid 1px #000333;">&nbsp;</td> 
						<td style="font-size: 14px; border-top: solid 1px #000333;">
						@if($Order[0]['payment_mode']==3)
						{{number_format(round(($total+$ship_total+$cod_total-$coupon_total+$slot_price)),2)}}
						@else
						{{number_format(round(($total+$ship_total+$cod_total-$wallet_total-$coupon_total+$slot_price)),2)}}
						@endif 
					
					    </td>
					</tr>
				</table>
			</td>	
		</tr>
		<tr>
			<td bgcolor="#ebecec" style="font-size: 16px; font-weight: bold; padding: 15px 30px;">
				Total amount In words.
					{!! App\Helpers\CommonHelper::convert_number_to_words(round($total+$ship_total+$cod_total-$wallet_total-$coupon_total+$slot_price)); !!} 
				Rupee Only
			</td>	
		</tr>
		<tr>
			<td style="padding: 15px 30px;">
				<table cellpadding="0" cellspacing="0" style="width: 100%; border-bottom: solid 1px #ddd; padding-bottom: 10px;">
					<tr>
						<td style="width: 80%; vertical-align: top;">
							<p>Whether tax is payable under reverse charge-No</p>
							<h2 style="font-size: 16px; margin-bottom: 0;">Declaration</h2>
							<p style="font-size: 14px; margin-top: 5px;">We Declare that this invoice shows the actual price of the goods<br/>described above and that all particulars are true and correct. The<br/>goods sold are intended for end user consumption and not for resale.</p>
						</td>
						<td style="font-size: 14px; text-align: center; width: 20%; line-height: 28px;">For {{$vdr_data['company_name']}} <span style="width: 200px; height: 60px; display: inline-block; border: solid 1px #333;">{!! App\Helpers\CustomFormHelper::signature_pic('uploads/vendor/signature_pic',$vdr_data['signature_pic']); !!}
&nbsp;</span> Authorized Signatory</td>
					</tr>
				</table>
			</td>
			
		</tr>
		<tr>
			<td style="padding: 0px 30px;">
				<h2 style="font-size: 16px; margin-bottom: 0;">Customer Acknowledgement</h2>
				<p style="font-size: 14px; margin-top: 5px;">I , confirm that the said products are being purchased for my internal/personal consumption and not for re-sale.</p>
			</td>	
		</tr>
		<tr>
			<td style="padding: 0px 30px;">
				<p style="font-size: 16px; border: solid 1px #737373; padding: 10px 15px; margin: 0; text-align: center;">Beware of fake calls/SMS/Emails offering any cash/price under any fraud scheme/lottery/lucky draw. Do not share any information or pay any amount.</p>
			</td>	
		</tr>
		<tr>
			<td style="float: right; padding: 40px 30px 20px; text-align: center;"><img style="width:200px;" src="{{ asset('public/fronted/images/logo.png') }}" /><h2 style="font-weight: 600; font-size: 18px; margin-bottom: 5px;">Thank You!</h2>
<span style="font-size: 12px; font-weight: normal;">For Shopping With Us</span></td>
		</tr>
		<tr>
			<td style="padding: 20px 30px;">
				<p style="font-size: 13px; margin-top: 5px; font-style: italic; line-height: 24px;"><strong style="font-style: normal;">Return Policy</strong> : At kefih.com we try to deliver perfectly each and every time. But in the off-chance that you need to return the item, please do so with the original Brand box/price tag, original packing and invoice without which it will be really difficult for us to act on your request. Please help us in helping you. Terms and conditions apply.<br/>
The goods sold as are intended for end user consumption and not for re-sale.<br/>
Contact kefih.com : help@kefih.com <br>Customer Care: +91-8888888888</p>
			</td>	
		</tr>
		
                
	</table>
    </div>
    
</div>
</body>
</html>
