<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Kefih Service Invoice</title>
	<link href="https://fonts.googleapis.com/css?family=Be+Vietnam:400,500,700&display=swap" rel="stylesheet"> 
</head>

<body style="padding: 0px; margin: 0px;">

   <div style="width:700px; margin-left:auto; margin-right:auto;">	
		<div style="width:100%; margin-left:auto; margin-right:auto; margin-top: 5px; margin-bottom: 5px;">
			@if(@$is_front != 1)
			<div class="allbutntbl">
				<a href="{{route('orders',base64_encode(0)) }}" class="btn btn-warning"><i class="fa fa-undo"></i> Back</a>
			</div>
			@endif 

			@php 
			 $kefih_state = 'Kerala';
			 $billingStateData = [];

			 $kefihStateData = DB::table('states')->select('state_code')->where('name', $kefih_state)->first(); 
			 if(!empty($billingAddress)){
				$billingStateData = DB::table('states')->select('state_code')->where('name', $billingAddress->order_shipping_state)->first(); 
			 }
			 
			 $shippingStateData = DB::table('states')->select('state_code')->where('name', $Order->order_shipping_state)->first(); 

			@endphp 
                
                <style>
                    table tr th{ text-align: left; }
                </style>
                
			<table cellpadding="4px" cellspacing="0" style=" border: solid 1px #000; width:100%; font-family: 'Be Vietnam', sans-serif; padding:10px; background: #fff; font-size: 13px; line-height:18px; page-break-inside: avoid;">
                <tr>
                    <td colspan="2"><h1 style="margin:0;">Kefih </h1></td>
                </tr>
				<tr>
					<td style=" padding-bottom: 0; vertical-align: top;">						
					Customer support: +91 9037538402			
					<br><strong>Kefih E-Commerce Private Limited</strong>
					<br>7/1133, Venice 21, Zilla Court Ward, Alappuzha, Kerala, India - 688013
					<br>Email : Contact@Kefih.com
					<br>GSTIN :32AAJCK0331L1ZR
					<br>PAN :AAJCK0331L
					<br>State/UT Code : {{$kefihStateData->state_code}}

				    </td>
					<td style="text-align: right; vertical-align: top;">
					Invoice No:	{{$Order->service_invoice_num}}
					<br>Invoice Date:	{{date('d-M-Y', strtotime($Order->service_invoice_date))}}
					<br>Order No:	{{$Order->order_no}}
					<br>Order Date: {{date('d-M-Y', strtotime($Order->order_date))}}
					<br>Mode of Payment: {{($Order->payment_mode == 0)?'COD':'ONLINE'}}
					</td>
				</tr>
				<tr  bgcolor="#ebecec">
					<th style="width: 50%;">Billing Address</th>
					<th style="width: 50%; border-left:solid 1px #000;">Shipping Address</th>

				</tr>
				<tr>
					<td>

						@if(!empty($billingAddress))
						    Name:	<strong>{{$billingAddress->order_shipping_name}}</strong>
							<br>Billing address:	<strong>{{$billingAddress->order_shipping_address}}</strong>
							<br>Phone number:	<strong>{{$billingAddress->order_shipping_phone}}</strong>
							<br>Email: <strong>{{$billingAddress->order_shipping_email}}	</strong>
						@else 
							Name:	<strong>{{$Order->order_shipping_name}}</strong>
							<br>Billing address:	<strong>{{$Order->order_shipping_address}}</strong>
							<br>Phone number:	<strong>{{$Order->order_shipping_phone}}</strong>
							<br>Email: <strong>{{$Order->order_shipping_email}}	</strong>
						@endif 

					</td>
					<td style=" border-left:solid 1px #000;">
							Buyer name:	<strong>{{$Order->order_shipping_name}}</strong>
							<br>Buyer address:	<strong>{{$Order->order_shipping_address}}</strong>
							<br>Phone number:	<strong>{{$Order->order_shipping_phone}}</strong>
							<br>Email: <strong>{{$Order->order_shipping_email}}	</strong>	
							<br>State/UT Code : {{$shippingStateData->state_code}}					
							<br>Place of Supply : 	<strong>{{$kefih_state}}</strong>
							<br>Place of Delivery :	<strong>{{$Order->order_shipping_state}}</strong>								
					</td>
				</tr>
                <tr>
                    <td colspan="2">
                        <table cellpadding="5px" cellspacing="0" style="width:100%;">
                            <tr  bgcolor="#ebecec">				     
					<th style="width: 5%;">S.NO.</th>
					<th style="width: 30%;  ">Description</th>
					<th style="width: 10%;">QTY</th>
					<th style="width: 25%;">Net price</th>
					<th style="width: 10%;">Taxes</th>
					<th style="width: 30%;  ">Total</th>
				</tr>	
				@php 
				$tax = $serviceChargeTax->service_charge_tax;
				$serviceCharge = $Order->service_charge;

				/*
				$tax_amount = ($serviceCharge * $tax)/100;
				$taxFreeAmount = $serviceCharge - $tax_amount; 
				*/
				
				$taxFreeAmount=$serviceCharge/(1+$tax/100);
				$tax_amount=$taxFreeAmount*$tax/100;
				
				@endphp 
				<tr>				     
					<td>1</td>
					<td>Service Charge</td>
					<td>1</td>
					<td>{{number_format($taxFreeAmount,2)}}</td>
					<td>{{number_format($tax_amount,2)}}					
				    </td>
					<td>{{number_format($tax_amount+$taxFreeAmount,2)}}</td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>
				</tr>
				<tr  bgcolor="#ebecec">				     
					<td colspan="2" style=""><strong>Total</strong></td>
					<td><strong>1</strong></td>
					<td><strong>{{number_format($taxFreeAmount,2)}}</strong></td>
					<td><strong>{{number_format($tax_amount,2)}}</strong></td>
					<td><strong>{{number_format($tax_amount+$taxFreeAmount,2)}}</strong></td>
				</tr>
                <tr>
					<td colspan="6"><h1 style="margin-bottom: 0; font-size: 16px; margin-top: 5px;">Tax Summary</h1></td>
				</tr>	
				<tr>
					<td colspan="6">
				<table cellpadding="2" cellspacing="0"  style="width:100%; text-align:center; border-bottom: solid 1px #000;" border="1">
                    <thead>
                        <tr>       
                          @if($kefih_state == $Order->order_shipping_state)   
                          <th colspan="2" style="text-align:center;">CGST</th> 
                          <th colspan="2" style="text-align:center;">SGST</th> 
                          @else
                          <th colspan="2" style="text-align:center;">IGST</th>
                          @endif 
                          <th rowspan="2" style="text-align:center;">Total Tax Value</th>

                        </tr>
                        <tr>
                        @if($kefih_state == $Order->order_shipping_state)   
                            <th style="text-align:center;">Rate %</th>
                            <th style="text-align:center;">Amount</th>
                            <th style="text-align:center;">Rate %</th>
                            <th style="text-align:center;">Amount</th>
                        @else
                            <th style="text-align:center;">Rate %</th>
                            <th style="text-align:center;">Amount</th>
                            <th>&nbsp;</th>
                        @endif 
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        @if($kefih_state == $Order->order_shipping_state)  
                           @php 
                            $taxpercent = $tax/2;
                            $taxAmount = $tax_amount/2;
                           @endphp 

                            <td style="border-bottom: solid 1px #000;">{{$taxpercent}}</td>
                            <td style="border-bottom: solid 1px #000;">{{number_format($taxAmount,2)}}</td>

                           <td style="border-bottom: solid 1px #000;">{{$taxpercent}}</td>
                            <td style="border-bottom: solid 1px #000;">{{number_format($taxAmount,2)}}</td>
                        @else       
                           <td style="border-bottom: solid 1px #000;">{{$tax}}%</td>
                           <td style="border-bottom: solid 1px #000;">{{number_format($tax_amount,2)}}</td>
                        @endif 
                        <td style="border-bottom: solid 1px #000;">{{number_format($tax_amount,2)}}</td>
                        </tr>
                    </tbody>
                </table>
                </td>

                        </table>
                    </td>
                </tr>

				<tr>
					
					<td style="vertical-align: top;">
                        <p style="margin: 0;">Total invoice Value:<strong>{{number_format($tax_amount+$taxFreeAmount,2)}} </strong></p>
                        <p style="margin: 0;">Total in words: 
						<strong>{!! App\Helpers\CommonHelper::convert_number_to_words(round($tax_amount+$taxFreeAmount)); !!} 
                            Rupee Only</strong></p>
					</td>
					<td style="text-align:right;">
						<p style="position:relative; height: 50px;"><img src="{{ asset('public/images/kefihSign.jpeg') }}" style="height: 50px; position: absolute; top: 0; right: 0;"></p>

                        <p style="margin: 0;"> Kefih Signature</p>
                        <p style="position:relative; height: 50px;"><img src="{{ asset('public/images/signn.jpeg') }}" style="height: 50px; position: absolute; top: 0; right: 0;"></p>
                        <p style="margin: 0;">Authorised Signatory</p>
                    </td>
				</tr>
				<!--<tr>					
					<td colspan="2" style="width: 10%;">					
					
					</td>
				</tr>		
				<tr>
					
				</tr>
				<tr>
					<td colspan="2" style="text-align:right;">
						<img src="{{ asset("public/images/signn.jpeg") }}" style="width:80px">
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:right;">Authorised Signatory</td>
				</tr>-->
				</table>
		
		</div>
	</div>
</body>
</html>
