<a href="{{$back_url}}">Back</a>

<?php 
      
      if($msg != 'successful'){
          echo '<div style="color:red; text-align:center;">'.$msg.'</div>';
?>



          
 <?php     }else{

?>         
<button onclick="PrintDiv();">Print</button>   
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>18 UP</title>
	<link href="https://fonts.googleapis.com/css?family=Be+Vietnam:400,500,700&display=swap" rel="stylesheet"> 
</head>

<body style="width:1100px; padding: 0px; margin: 0px; background: #f9f9f9;">
<div id = "divToPrint" style="">
  <page size="A4">
	<table style="width: 100%;">
		<tr>
			<td style="vertical-align: top;">
				<div style="width:100%;">
    
		<table cellpadding="0" cellspacing="0" style="width:100%; font-family: 'Be Vietnam', sans-serif; padding:0px; background: #fff; ">
    
		<tr>
			<td style="text-align: left; background: #fff">
				<table cellpadding="10px" cellspacing="0" style="width: 100%; border: solid 1px #737373; font-size: 12px; border-bottom: none;">
					<tr>
						<td style="font-size: 16px; width: 33%; border-bottom: solid 1px #737373; padding-left: 20px;">{{$invoice_details['OrderType']}} <?php echo ($invoice_details['OrderType'] == 'COD')?'Rs.'.$invoice_details['CollectibleAmount']:''; ?></td>
					  	<td style="font-size: 16px; width: 24%; border-bottom: solid 1px #737373; text-align: center;">{{$invoice_details['courier_name']}}</td>
					  	<td style="font-size: 16px; width: 42%; border-bottom: solid 1px #737373; text-align: right; padding-right: 20px;">order date {{ date("d-m-Y", strtotime($invoice_details['order_date']))}}</td>
					</tr>
					<tr><td colspan="3" style="padding: 10px;"> </td></tr>
					
				</table>
				<table cellpadding="10px" cellspacing="0" style="width: 100%; border: solid 1px #737373; height: 150px;">
					<tr>
						<td width="50%" style="border-right: solid 1px #737373; padding-left: 20px; vertical-align: top;">
						   Barcode <br>
						   <img src="{{asset('public/images/bar-sample.png')}}" height="100" width="200" />
						    {{$invoice_details['AirWayBillNO']}}
						 </td>
						<td style=" padding-left: 20px; vertical-align: top;">
						  
						 Return address
						    <br>
						                            
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
						      Shipping / customer address<br>
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
						    <img src="{{asset('public/images/qr-sample.png')}}" height="200" width="200" />
						    {{$invoice_details['AirWayBillNO']}}
						
						
						</td>
					</tr>
				</table>
				<table cellpadding="7px" cellspacing="0" style="width: 100%; border: solid 1px #737373; border-top:none; font-size: 13px;">
					<tr>
					  	<td style="border-right: solid 1px #737373; border-bottom: solid 1px #737373; font-size: 16px; padding-left: 5px;">Seller Name</td>
						<td style="border-right: solid 1px #737373; border-bottom: solid 1px #737373; font-size: 16px; padding-left: 5px;">GSTIN</td>
						<td style="border-right: solid 1px #737373; border-bottom: solid 1px #737373; font-size: 16px; padding-left: 5px;">Invoice Number</td>
						<td style="font-size: 16px; padding-left: 5px; border-bottom: solid 1px #737373;">Date</td>
					</tr>
					<tr>
						<td style="border-right: solid 1px #737373; padding-left: 5px; vertical-align: top; height: 80px;">{{$invoice_details['VendorName']}}</td>
					  	<td style="border-right: solid 1px #737373; padding-left: 5px; vertical-align: top; height: 80px;">{{$invoice_details['vendor_gst']}}</td>
						<td style="border-right: solid 1px #737373; padding-left: 5px; vertical-align: top; height: 80px;">{{$invoice_details['invoice_num']}}</td>
					  	<td style="padding-left: 5px; vertical-align: top; height: 80px;">{{ date("d-m-Y", strtotime($invoice_details['invoice_date']))}}</td>
					</tr>
					<tr>
						<td style="border-right: solid 1px #737373; border-top: solid 1px #737373; padding-left: 20px; vertical-align: top;"> </td>
					  	<td colspan="3" style="border-top: solid 1px #737373; padding-left: 20px; vertical-align: top;">the goods sold are intended for end user consumption <br><strong>not for resale</strong></td>
					</tr>
					<tr>
					  	<td colspan="4" style="border-top: solid 1px #737373; padding-left: 20px; vertical-align: top;">Ordered Through &nbsp; <img src="{{asset('public/images/icon.png')}}" style="vertical-align: top;" /> &nbsp; 18up.in</td>
					</tr>
					<tr>
					  	<td colspan="4" style="border-top: solid 1px #737373; padding-left: 20px; vertical-align: top;">&nbsp;<br/><br/></td>
					</tr>
					
				</table>
				
			</td>	
		</tr>
	   
	</table>
    </div>
			</td>
			<td style="width: 5%; height: 100%; background-image: url('{{asset('public/images/papercutimg.png')}}'); background-repeat: repeat-y; background-position: center;"> </td>
			<td style="vertical-align: top; ">
				<div style="width:100%;">
    
		<table cellpadding="0" cellspacing="0" style="font-size: 13px; width:100%; font-family: 'Be Vietnam', sans-serif; padding: 20px;  border:1px solid #737373; background: #fff; ">
    
		<tr>
			<td style="text-align: left; background: #fff;">
				
				<table cellpadding="10px" cellspacing="0" style="width: 100%; height: 107px;">
					<tr>
						<td colspan="2" width="50%" style="padding-left: 20px; vertical-align: top; width: 70%">
							<img src="{{asset('public/images/bar-sample.png')}}" height="100" width="200" /><br>{{$invoice_details['AirWayBillNO']}} <br>
							Shipping / customer address<br>
						{{$invoice_details['CustomerName']}},<br>
                            {{$invoice_details['CustomerCity']}},
                            {{$invoice_details['CustomerState']}},
                            
                            {{$invoice_details['customer_address']}},
                            {{$invoice_details['customer_address1']}},
                            {{$invoice_details['customer_mobile']}},
                            {{$invoice_details['ZipCode']}}</td>
						<td style=" padding-left: 20px; vertical-align: top; width: 30%">
							<table cellpadding="12px" cellspacing="0" style="width: 100%; text-align: center; border: solid 1px #737373; font-size: 13px;">
								<tr>
									<td style="border-bottom:1px solid #737373;">Weight<br>{{$invoice_details['vol_weight']}}</td>
								</tr>
								<tr>
									<td style="border-bottom:1px solid #737373;">Date <br>{{ date("d-m-Y", strtotime($invoice_details['order_date']))}}</td>
								</tr>
								<tr>
									<td>Logistics name <br>{{$invoice_details['courier_name']}}</td>
								</tr>
							</table>
							
						</td>
					</tr>
					
					<tr>
						<td width="15%" style="padding-left: 20px; vertical-align: top; height: auto;">
							
						</td>
						<td width="35%" style="padding-left: 20px; vertical-align: top; height: auto;"> </td>
						<td style="vertical-align: top; width: 30%">
							
							<table cellpadding="5px" cellspacing="0" style="width: 100%; text-align: center; border: solid 1px #737373; font-size: 13px;">
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
						<td width="15%" style="padding-left: 20px; vertical-align: top; height: 100px;">
							<img src="{{asset('public/images/qr-sample.png')}}" 
height="100" width="100" /><br/>
							{{$invoice_details['AirWayBillNO']}}
						</td>
						<td colspan="2" width="35%" style="padding-left: 20px; vertical-align: top; height: 100px;">Shipped by : {{$invoice_details['courier_name']}}<br/>
                            Return address : {{$invoice_details['VendorName']}}, {{$invoice_details['PickVendorAddress']}}, {{$invoice_details['PickVendorState']}}, {{$invoice_details['PickVendorCity']}}, {{$invoice_details['PickVendorPinCode']}}</td>
						
					</tr>
					<tr>
						<td colspan="3" style="text-align: center;">The goods sold are intended for end user consumption <strong>not for resale</strong></td>
					</tr>
				</table>
				
				
				<table cellpadding="7px" cellspacing="0" style="width: 100%; border: solid 1px #737373; font-size: 12px;">
					<tr>
					  	<td style="border-right: solid 1px #737373; border-bottom: solid 1px #737373; font-size: 16px; padding-left: 5px;">Seller Name</td>
						<td style="border-right: solid 1px #737373; border-bottom: solid 1px #737373; font-size: 16px; padding-left: 5px;">GSTIN</td>
						<td style="border-right: solid 1px #737373; border-bottom: solid 1px #737373; font-size: 16px; padding-left: 5px;">Invoice Number</td>
						<td style="font-size: 16px; padding-left: 5px; border-bottom: solid 1px #737373;">Date</td>
					</tr>
					<tr>
						<td style="border-right: solid 1px #737373; padding-left: 5px; vertical-align: top; height: 50px;">{{$invoice_details['VendorName']}}</td>
					  	<td style="border-right: solid 1px #737373; padding-left: 5px; vertical-align: top; height: 50px;">{{$invoice_details['vendor_gst']}}</td>
						<td style="border-right: solid 1px #737373; padding-left: 5px; vertical-align: top; height: 50px;">{{$invoice_details['invoice_num']}}</td>
					  	<td style="padding-left: 5px; vertical-align: top; height: 50px;">{{ date("d-m-Y", strtotime($invoice_details['invoice_date']))}}</td>
					  
					  		
					</tr>
					
					
				</table>
				
				<table cellpadding="10px" cellspacing="0" style="width: 100%; height: auto;">
					<tr>
						<td style="float: right; text-align: center;"><h2 style="font-weight: 400; font-size: 13px; margin-bottom: 5px;">Ordered Through</h2><img src="{{asset('public/images/icon.png')}}"><br/>
			<span style="font-size: 12px; font-weight: normal;">18up.in</span></td>
					</tr>
				</table>
				
			</td>	
		</tr>
	   
	</table>
    </div>
			</td>
		</tr>
	</table>
	 </page>
    
    
</div>
</body>
</html>
          
          
<?php           
      }
      
?>

<script>
	function PrintDiv() {    
		var divToPrint = document.getElementById('divToPrint');
		var its_url=window.location.href.split('admin');
		its_url=its_url[0];
		var popupWin = window.open('', '_blank', 'width=300,height=300');
		popupWin.document.open();
		popupWin.document.write('<body style="margin-top: 0px; font-size: 13px; height:445px; clear:both;" onload="window.print()">' + divToPrint.innerHTML + '</html>');
		popupWin.document.close();
	}
</script>
