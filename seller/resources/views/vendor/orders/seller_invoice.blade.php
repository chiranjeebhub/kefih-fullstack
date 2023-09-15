<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Seller Invoice</title>
	<link href="https://fonts.googleapis.com/css?family=Be+Vietnam:400,500,700&display=swap" rel="stylesheet"> 
</head>

<body style="padding: 0px; margin: 0px;">

   <div style="width:700px; margin-left:auto; margin-right:auto;">	
		<div style="width:100%; margin-left:auto; margin-right:auto; margin-top: 10px; margin-bottom: 10px;">
			
			<div class="allbutntbl">
				<a href="{{route('vendor_orders',base64_encode(0)) }}" class="btn btn-warning"><i class="fa fa-undo"></i> Back</a>
			</div>

            <style>
                    table tr th{ text-align: left; }
                </style>

			@php 
			 $kefih_state = 'Kerala';
			 $billingStateData = [];

			 $kefihStateData = DB::table('states')->select('state_code')->where('name', $kefih_state)->first(); 						 
			 $shippingStateData = DB::table('states')->select('state_code')->where('name', $sellerData['company_state'])->first(); 

			@endphp 
                
			<table cellpadding="4px" cellspacing="0" style=" border: solid 1px #000; width:100%; font-family: 'Be Vietnam', sans-serif; padding:10px; background: #fff; font-size: 13px; line-height:18px;">
                <tr>
                    <td colspan="2"><h1 style="margin:0;">Kefih </h1></td>
                </tr>
				<tr>
					<td style=" padding-bottom: 20px; vertical-align: top;">
					<br><strong>Kefih E-Commerce Private Limited</strong>
					<br>Mobile : 9916356373
					<br>GSTIN :32AAJCK0331L1ZR			
					<br>State/UT Code : {{$kefihStateData->state_code}}

				    </td>
					<td style="text-align: right; vertical-align: top;">
					<br><strong>TAX INVOICE</strong>
					<br>Invoice No. {{$Order->seller_invoice_num}}
					<br>Invoice Date {{date('d/m/Y',strtotime($Order->seller_invoice_date))}}
					<br> Suborder ID  {{$Order->suborder_no}}
					</td>
				</tr>

				<tr bgcolor="#ebecec">
                    <td colspan="2"><strong style="margin:0;">BILL TO </strong></td>
                </tr>
				@if(!empty($sellerData))
				<tr>
                    <td colspan="2">
					
					
					<p>
					 Sold By : <strong>{{$sellerData['company_name']}}</strong>
					 <br>
					 <strong> {{$sellerData['f_name'].' '.$sellerData['l_name']}} </strong>
					 <br>	 {{$sellerData['company_address']}} 
						@if(!empty($sellerData['company_state']))
						,{{$sellerData['company_state']}}
						@endif 
						@if(!empty($sellerData['company_city']))
						,{{$sellerData['company_city']}}
						@endif 
						@if(!empty($sellerData['company_pincode']))
						,{{$sellerData['company_pincode']}}
						@endif 

						<br>Mobile :{{$sellerData['phone']}} 
						<br>GSTIN :{{$sellerData['gst_no']}} 
						<br>State/UT Code : {{$shippingStateData->state_code}}
						<br>Place of Supply :	@if(!empty($sellerData['company_state']))
													{{$sellerData['company_state']}}
												@endif 
					</td>
                </tr>
				@endif 

				

				<tr>
                    <td colspan="2">

					<table cellpadding="2" cellspacing="0" style="width:100%; text-align:center; border-bottom: solid 1px #000;" border="1">
                    <thead>
						<tr bgcolor="#ebecec">
							<th>S.No.</th>
							<th>SERVICES</th>
							<th>QTY</th>
							<th>RATE</th>
							<th>TAX</th>
							<th>AMOUNT</th>
						</tr>
                    </thead>
                    <tbody>

						@php 
					
						$gstTax = $Order->logistics_tax;
						$TCS = $Order->seller_invoice_tcs;


						 $totalTaxAmount = $totalAmount = $totalTaxableAmount = 0;
						 $srno = 1;
						 $paymentGateWayTax = $Order->payment_gateway_tax;
						 $paymentAmount = $Order->product_price * $Order->product_qty;
						 $paymentTaxAmount = ($paymentAmount * $paymentGateWayTax)/100;				

						 $productTcsTaxAmount = ($paymentAmount * $TCS)/100;					

						 $paymentGSTtaxAmt = ($paymentTaxAmount * $gstTax)/100;
						 $paymentGSTtaxFreeAmt =  $paymentTaxAmount -  $paymentGSTtaxAmt;

						@endphp


						@if($Order->payment_mode == 1)
						@php 
						 $totalTaxAmount+=$paymentGSTtaxAmt;
						 $totalAmount+=$paymentGSTtaxAmt+$paymentTaxAmount;

						 $totalTaxableAmount+=$paymentTaxAmount;
						@endphp 
							<tr>
								<td>{{$srno++}}</td>
								<td>CARD EXPENSE</td>
								<td>1</td>
								<td>{{number_format($paymentTaxAmount,2)}}</td>
								<td>{{number_format($paymentGSTtaxAmt,2)}}
									<br>({{$gstTax}}%)
								</td>
								<td>{{number_format($paymentTaxAmount+$paymentGSTtaxAmt,2)}}</td>
							</tr>
						@endif 
					                       
						@php 
						
						 $logisticsAmount = $Order->courier_charges;
						 $logisticsTaxAmount = ($logisticsAmount * $gstTax)/100;
						 $logisticsTaxFreeAmount = $logisticsAmount - $logisticsTaxAmount;

						 $totalTaxAmount+=$logisticsTaxAmount;
						 $totalAmount+=$logisticsAmount+$logisticsTaxAmount;

						 $totalTaxableAmount+=$logisticsAmount;

						@endphp 

						@if(!empty($Order->logistics_tax))
							<tr>
								<td>{{$srno++}}</td>
								<td>LOGISTICS</td>
								<td>1</td>
								<td>{{number_format($logisticsAmount,2)}}</td>
								<td>{{number_format($logisticsTaxAmount,2)}}
									<br>({{$gstTax}}%)
								</td>
								<td>{{number_format($logisticsAmount+$logisticsTaxAmount,2)}}</td>
							</tr>
						@endif 
						
						<tr bgcolor="#ebecec">
							<td colspan="4">SUBTOTAL</td>
							<td>{{number_format($totalTaxAmount,2)}}</td>
							<td>{{number_format($totalAmount,2)}}</td>
						</tr>


						<tr>
							<td colspan="3"></td>
							<td colspan="3">
								@php 
									$TAXAMOUNT = ($totalTaxableAmount*$gstTax)/100;
									$totalInvoiceAmount = $productTcsTaxAmount + $totalTaxableAmount + $TAXAMOUNT;
								@endphp 

							<p style="text-align:right;">
								<br>TCS@ {{$Order->seller_invoice_tcs}} %  : <strong>Rs. {{number_format($productTcsTaxAmount,2)}}</strong>
								<br>TAXABLE AMOUNT  :    <strong>Rs. {{number_format($totalTaxableAmount,2)}}</strong>
								@if($kefih_state == $sellerData['company_state'])   
								<br>CGST @ {{$gstTax/2}}%  :   <strong> Rs. {{number_format($TAXAMOUNT/2,2)}}</strong>
								<br>SGST @ {{$gstTax/2}}%  :   <strong> Rs. {{number_format($TAXAMOUNT/2,2)}}</strong>
								@else 
								<br>IGST @ {{$gstTax}}%  :   <strong> Rs. {{number_format($TAXAMOUNT,2)}}</strong>
								@endif 

								<br>TOTAL AMOUNT :    <strong> Rs. {{number_format($totalInvoiceAmount,2)}}</strong>
								
								<br>Total Amount (in words) : 	<strong>{!! App\Helpers\CommonHelper::convert_number_to_words(round($totalInvoiceAmount)); !!} 
								Rupee Only</strong></p>
								<br>
								<br>
								Authorised Signature for Kefih E-Commerce Private Limited
							</p>
							</td>
						</tr>

                    </tbody>
                </table>

					</td>
                </tr>



            </table>

        </div>
    </div>
</body>
</html>
			