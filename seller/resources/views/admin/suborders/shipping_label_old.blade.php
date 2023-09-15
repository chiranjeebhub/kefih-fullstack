<button onclick="PrintDiv();">Print</button>   
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>18 UP</title>
	<link href="https://fonts.googleapis.com/css?family=Be+Vietnam:400,500,700&display=swap" rel="stylesheet"> 
</head>

<body style="padding: 0px; margin: 0px; background: #f9f9f9;">
<div id = "divToPrint" style="width:1100px; margin-left:auto; margin-right:auto;">
	
    <div style="width:65%; margin-left:auto; margin-right:auto; margin-top: 30px; margin-bottom: 30px;">
    <page size="A4">
		<table cellpadding="0" cellspacing="0" style="width:100%; font-family: 'Be Vietnam', sans-serif; padding:0px; background: #fff; ">
    
		<tr>
			<td style="text-align: left; background: #fff">
				<table cellpadding="10px" cellspacing="0" style="width: 100%; border: solid 1px #737373; font-size: 14px; border-bottom: none;">
					<tr>
						<td style="font-size: 16px; width: 33%; border-bottom: solid 1px #737373; padding-left: 20px;">{{$invoice_details['OrderType']}} <?php echo ($invoice_details['OrderType'] == 'COD')?'Rs.'.$invoice_details['CollectibleAmount']:''; ?></td>
					  	<td style="font-size: 16px; width: 33%; border-bottom: solid 1px #737373; text-align: center;">{{$invoice_details['courier_name']}}</td>
					  	<td style="font-size: 16px; width: 33%; border-bottom: solid 1px #737373; text-align: right; padding-right: 20px;">order date {{ date("d-m-Y", strtotime($invoice_details['order_date']))}}</td>
					</tr>
					<tr><td colspan="3" style="padding: 10px;"> </td></tr>
					
				</table>
				<table cellpadding="10px" cellspacing="0" style="width: 100%; border: solid 1px #737373; height: 150px;">
					<tr>
						<td width="50%" style="border-right: solid 1px #737373; padding-left: 20px; vertical-align: top;">
						    Barcode <br>
						     {{$invoice_details['docket_num']}}
						    
						 </td>
						<td style=" padding-left: 20px; vertical-align: top;">
						                            Return address <br>
						                            
						                            {{$invoice_details['VendorName']}},<br>
						                            {{$invoice_details['PickVendorAddress']}},
						                            {{$invoice_details['PickVendorState']}},
						                            
						                            {{$invoice_details['PickVendorCity']}},
						                            {{$invoice_details['PickVendorPinCode']}}
						 </td>
					</tr>
				</table>
				<table cellpadding="10px" cellspacing="0" style="width: 100%; border: solid 1px #737373; height: 150px;">
					<tr>
						<td width="50%" style="border-right: solid 1px #737373; padding-left: 20px; vertical-align: top;">
						    Shipping / customer address <br>
						                            {{$invoice_details['CustomerName']}},<br>
						                            {{$invoice_details['CustomerCity']}},
						                            {{$invoice_details['CustomerState']}},
						                            
						                            {{$invoice_details['customer_address']}},
						                            {{$invoice_details['customer_address1']}},
						                            {{$invoice_details['customer_mobile']}},
						                            {{$invoice_details['ZipCode']}}
						</td>
						<td style=" padding-left: 20px; vertical-align: top;">
						                            
						                      QRCode <br>
						                      {{$invoice_details['docket_num']}}
						 </td>
					</tr>
				</table>
				<table cellpadding="7px" cellspacing="0" style="width: 100%; border: solid 1px #737373; border-top:none; font-size: 14px;">
					<tr>
					  	<td style="border-right: solid 1px #737373; border-bottom: solid 1px #737373; font-size: 16px; padding-left: 20px;">Seller Name</td>
						<td style="border-right: solid 1px #737373; border-bottom: solid 1px #737373; font-size: 16px; padding-left: 20px;">GSTIN</td>
						<td style="border-right: solid 1px #737373; border-bottom: solid 1px #737373; font-size: 16px; padding-left: 20px;">Invoice Number</td>
						<td style="font-size: 16px; padding-left: 20px; border-bottom: solid 1px #737373;">Date</td>
					</tr>
					<tr>
						<td style="border-right: solid 1px #737373; padding-left: 20px; vertical-align: top; height: 150px;">{{$invoice_details['VendorName']}}</td>
					  	<td style="border-right: solid 1px #737373; padding-left: 20px; vertical-align: top; height: 150px;">{{$invoice_details['vendor_gst']}}</td>
						<td style="border-right: solid 1px #737373; padding-left: 20px; vertical-align: top; height: 150px;">{{$invoice_details['invoice_num']}}</td>
					  	<td style="padding-left: 20px; vertical-align: top; height: 150px;">{{ date("d-m-Y", strtotime($invoice_details['invoice_date']))}}</td>
					</tr>
					<tr>
						<td style="border-right: solid 1px #737373; border-top: solid 1px #737373; padding-left: 20px; vertical-align: top;"> </td>
					  	<td colspan="3" style="border-top: solid 1px #737373; padding-left: 20px; vertical-align: top;">the goods sold are intended for end user consumption <br><strong>not for resale</strong></td>
					</tr>
					<tr>
					  	<td colspan="4" style="border-top: solid 1px #737373; padding-left: 20px; vertical-align: top;">Ordered Through &nbsp; <img src="{{asset('public/images/logo.png')}}" style="vertical-align: top;" /> &nbsp; 18up.in</td>
					</tr>
					<tr>
					  	<td colspan="4" style="border-top: solid 1px #737373; padding-left: 20px; vertical-align: top;">&nbsp;<br/><br/></td>
					</tr>
					
				</table>
				
			</td>	
		</tr>
	   
	</table>
	</page>
    </div>
    
</div>
</body>
</html>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>18 UP</title>
	<link href="https://fonts.googleapis.com/css?family=Be+Vietnam:400,500,700&display=swap" rel="stylesheet"> 
</head>

<body style="padding: 0px; margin: 0px; background: #f9f9f9;">
<div style="width:1100px; margin-left:auto; margin-right:auto;">
	
    <div style="width:65%; margin-left:auto; margin-right:auto; margin-top: 30px; margin-bottom: 30px;">
    
		<table cellpadding="0" cellspacing="0" style="font-size: 14px; width:100%; font-family: 'Be Vietnam', sans-serif; padding: 20px;  border:1px solid #737373; background: #fff; ">
    
		<tr>
			<td style="text-align: left; background: #fff;">
				
				<table cellpadding="10px" cellspacing="0" style="width: 100%; height: 150px;">
					<tr>
						<td width="50%" style="padding-left: 20px; vertical-align: top; width: 70%">
						                                Barcode <br>
						                                {{$invoice_details['docket_num']}}
						</td>
						<td style=" padding-left: 20px; vertical-align: top; width: 30%">
							 
							
						</td>
					</tr>
					<tr>
						<td width="50%" style="padding-left: 20px; vertical-align: top; width: 70%">
						                                Shipping / customer address <br>
    						                            {{$invoice_details['CustomerName']}},<br>
    						                            {{$invoice_details['CustomerCity']}},
    						                            {{$invoice_details['CustomerState']}},
    						                            
    						                            {{$invoice_details['customer_address']}},
    						                            {{$invoice_details['customer_address1']}},
    						                            {{$invoice_details['customer_mobile']}},
    						                            {{$invoice_details['ZipCode']}}
						</td>
						<td style=" padding-left: 20px; vertical-align: top; width: 30%">
							<table cellpadding="12px" cellspacing="0" style="width: 100%; text-align: center; border: solid 1px #737373; font-size: 14px;">
								<tr>
									<td style="border-bottom:1px solid #737373;">Weight<br>{{$invoice_details['vol_weight']}}</td>
								</tr>
								<tr>
									<td style="border-bottom:1px solid #737373;">Date<br>{{ date("d-m-Y", strtotime($invoice_details['order_date']))}}</td>
								</tr>
								<tr>
									<td>Logistics name <br>{{$invoice_details['courier_name']}}</td>
								</tr>
							</table>
							
						</td>
					</tr>
					<tr>
						<td width="50%" style="padding-left: 20px; vertical-align: top; width: 70%; height: 100px;">
						    Shipped by : {{$invoice_details['courier_name']}}<br/>
                            Return address : {{$invoice_details['VendorName']}}, {{$invoice_details['PickVendorAddress']}}, {{$invoice_details['PickVendorState']}}, {{$invoice_details['PickVendorCity']}}, {{$invoice_details['PickVendorPinCode']}}
                            </td>
						<td style=" padding-left: 20px; vertical-align: top; width: 30%">
							
							<table cellpadding="5px" cellspacing="0" style="width: 100%; text-align: center; border: solid 1px #737373; font-size: 14px;">
								<tr>
									<td style="font-size: 16px; font-weight: 600; border-bottom:1px solid #737373;">Box 1 of 1</td>
								</tr>
								<tr>
									<td style="font-size: 11px;">Delivery station</td>
								</tr>
								
							</table>
							
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align: center;">The goods sold are intended for end user consumption <strong>not for resale</strong></td>
					</tr>
				</table>
				<table cellpadding="7px" cellspacing="0" style="width: 100%; border: solid 1px #737373; font-size: 14px;">
					<tr>
					  	<td style="border-right: solid 1px #737373; border-bottom: solid 1px #737373; font-size: 16px; padding-left: 20px;">Seller Name</td>
						<td style="border-right: solid 1px #737373; border-bottom: solid 1px #737373; font-size: 16px; padding-left: 20px;">GSTIN</td>
						<td style="border-right: solid 1px #737373; border-bottom: solid 1px #737373; font-size: 16px; padding-left: 20px;">Invoice Number</td>
						<td style="font-size: 16px; padding-left: 20px; border-bottom: solid 1px #737373;">Date</td>
					</tr>
					<tr>
						<td style="border-right: solid 1px #737373; padding-left: 20px; vertical-align: top; height: 60px;">{{$invoice_details['VendorName']}}</td>
					  	<td style="border-right: solid 1px #737373; padding-left: 20px; vertical-align: top; height: 60px;">{{$invoice_details['vendor_gst']}}</td>
						<td style="border-right: solid 1px #737373; padding-left: 20px; vertical-align: top; height: 60px;">{{$invoice_details['invoice_num']}}</td>
					  	<td style="padding-left: 20px; vertical-align: top; height: 60px;">{{ date("d-m-Y", strtotime($invoice_details['invoice_date']))}}</td>
					</tr>
					
					
				</table>
				
				<table cellpadding="10px" cellspacing="0" style="width: 100%; height: 150px;">
					<tr>
						<td style="float: right; padding: 40px 0px 20px; text-align: center;"><h2 style="font-weight: 400; font-size: 14px; margin-bottom: 5px;">Ordered Through</h2><img src="{{asset('public/images/logo.png')}}"><br/>
			<span style="font-size: 12px; font-weight: normal;">18up.in</span></td>
					</tr>
				</table>
				
			</td>	
		</tr>
	   
	</table>
    </div>
    
</div>
</body>
</html>  