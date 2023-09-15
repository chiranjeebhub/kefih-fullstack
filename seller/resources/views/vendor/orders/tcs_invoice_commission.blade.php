<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Jaldi Kharido</title>
</head>
<?php error_reporting(0);?>
<body style="padding: 0px; margin: 0px; "><!--background: #f9f9f9;-->
    
    <style>
        body{font-family: Arial, sans-serif;}
        h2{ font-size: 18px; font-weight: normal; margin: 0; }
        h1{font-size: 24px; font-weight: 600; margin-bottom:10px; margin-top:10px; color:#333; }
        h3{ font-size: 20px; margin:0; color:#333; text-align: center; }
        h4{ font-size: 16px; font-weight: 600; font-size: 20px; }
        table{border-collapse: collapse;}
        td, th {
          border: 1px solid #000;
          padding: 7px;
            font-size: 14px;
        }
        p{ margin: 0;}
        
    </style>
    <div style="max-width:1100px; margin:20px auto 0;">

        <div style=" margin-top: 20px; margin-bottom: 30px;clear:both;">

                <div class="maindiv" style="padding: 0x; background: #fff; ">
                    
                    <table cellpadding="0" cellspacing="0" style="width: 100%;">
                        <!--<tr>
                            <td style="text-align: center;">
                                <img class="logo" style="width:150px;" src="logo.png" />
                            </td>
                        </tr>-->
                        <tr>
                            <td colspan="6">
                                <h3>Invoice</h3>
                            </td>
                            
                        </tr>
                        
                        <tr><td colspan="2"><h1>BTA Prime Private  Limited</h1></td><td rowspan="4" colspan="4" style="text-align: center;"><img class="logo" style="width:250px;" src="{{ asset('public/images/logo.png') }}" /></td></tr>
                        <tr><td colspan="2"><p>testing address, 000000,</p></td></tr>
                        <tr><td colspan="2"><p>Testing state - 000000</p></td></tr>
                        <tr><td colspan="2"><p><strong>GSTIN:  GST909898342345</strong></p></td></tr>
                            
                            
                        <tr><td><h2>Bill to</h2></td><td style="width: 100px;">&nbsp;</td><td colspan="3"><h2 style="font-weight: 700;">Settlement Voucher</h2></td><td><h2>{{$Order[0]['tcs_invoice_no']}}</h2></td></tr>
                        <tr>
                            <td><h2 style="font-weight: 700;">{{$vdr_data['company_name']}}</h2></td>
                            <td rowspan="2" colspan="2"><h2 style="font-weight: 700;">GSTIN : {{$vdr_data['gst_no']}}</h2></td>
                            <td rowspan="2"><h2 style="font-weight: 700;">Date</h2></td>
                            <td rowspan="2" colspan="3"><h2 style="font-weight: 700;">{{date('d-m-Y',strtotime($Order[0]['tcs_invoice_date']))}}</h2></td>
                        </tr>
                        <tr><td><p>{{$vdr_data['company_address']}},  {{$vdr_data['company_state']}} {{$vdr_data['company_city']}} {{$vdr_data['company_pincode']}}</p></td></tr>
                    </table>
                    <?php
                        $check_tax=DB::table('tbl_settings')->select('service_charge_gst_percentage')->where('id',1)->first();
                            
                        if($check_tax->service_charge_gst_percentage!='')
                        {
                            $service_tax=$check_tax->service_charge_gst_percentage;
                        }
                    ?>
                    <table cellpadding="0" cellspacing="0" style="width: 100%;">
                        <tr>
                            <th style="border-top:none;">Sub Order id</th>
                            <th style="border-top:none;">Order Date</th>
                            <th style="border-top:none;">Bill Amount</th>
                            <th style="border-top:none;">Jaldi Kharido Fee Slab %</th>
                            <th style="border-top:none;">Jaldi Kharido Fee Amount</th>
                            <th style="border-top:none;">Gst Slab</th>
                            <th style="border-top:none;">GST {{$service_tax}}% on Jaldi Kharido fee</th>
                            <th style="border-top:none;">Amount Payable After Jaldi Kharido fee</th>
                             
                        </tr>
                        <?php
                            $total_order_amt=$total_comission_amt=$total_comission_amt_with_tax=$total_final_amt=$total_tcs_amt=$tcs_percentage=$total_tds_amt=$tds_percentage=0;
                        
                        ?>
                        @foreach($Order as $row)
                        <?php
                            $total_order_amt+=$row['order_amt'];
                            
                            $comission_amt=((($row['order_amt']*$row['order_commission_rate'])/100));
                            
                            $total_comission_amt+=$comission_amt;
                            
                            $comission_amt_with_tax=0;
                            if(!empty($row['order_commission_rate'])){
                                $comission_amt_with_tax=$comission_amt+(((($comission_amt)*$service_tax)/100));
                                
                                $total_comission_amt_with_tax+=$comission_amt_with_tax;
                            }
                            
                            $final_amt=$row['order_amt']-$comission_amt_with_tax;
                            $total_final_amt+=$final_amt;
                            
                            $total_tcs_amt+=@$row['tcs_order_amt'];
                            $tcs_percentage=@$row['tcs_percentage'];
                            
                            $total_tds_amt+=@$row['tds_order_amt'];
                            $tds_percentage=@$row['tds_percentage'];
                        ?>
                        <tr>
                            <td>{{@$row['suborder_no']}}</td>
                            <td>{{@date('d-m-Y',strtotime($row['order_date']))}}</td>
                            <td>{{$row['order_amt']}}</td>
                            <td>{{$row['order_commission_rate']}}</td>
                            <td>{{number_format($comission_amt,2)}}</td>
                            <td>{{$service_tax}}%</td>
                            <td>{{number_format($comission_amt_with_tax,2)}}</td>
                            <td>{{number_format(($final_amt),2)}}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td><strong>Total</strong></td>
                            <td>&nbsp;</td>
                            <td>{{number_format($total_order_amt,2)}}</td>
                            <td></td>
                            <td><strong>{{number_format($total_comission_amt,2)}}</strong></td>
                            <td>&nbsp;</td>
                            <td><strong>{{number_format($total_comission_amt_with_tax,2)}}</strong></td>
                            <td><strong>{{number_format($total_final_amt,2)}}</strong></td>
                        </tr>
                        <tr>
                            <td style="background: #ffff00;">TCS (-{{$tcs_percentage}}%)</td>
                            <td style="background: #ffff00;">&nbsp;</td>
                            <td style="background: #ffff00;">{{number_format($total_tcs_amt,2)}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><strong>TCS -</strong></td>
                            <td><strong>{{number_format($total_tcs_amt,2)}}</strong></td>
                        </tr>
                        <tr>
                            <td style="background: #ffff00;">TDS (-{{$tds_percentage}}%)</td>
                            <td style="background: #ffff00;">&nbsp;</td>
                            <td style="background: #ffff00;">{{number_format($total_tds_amt,2)}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><strong>TDS -</strong></td>
                            <td><strong>{{number_format($total_tds_amt,2)}}</strong></td>
                        </tr>

                        <tr bgcolor="#000">
                            <td colspan="3" style="color:#fff;"><strong>Amount Payable (In words)</strong></td>
                            <td colspan="4" style="color:#fff;"><strong>Amount Payable</strong></td>
                            <td style="color:#fff;"><strong>{{number_format(($total_final_amt-($total_tcs_amt+$total_tds_amt)),2)}}</strong></td>
                        </tr>
                        <tr><td colspan="5"><strong>	
                        {!! App\Helpers\CommonHelper::convert_number_to_words(round(($total_final_amt-($total_tcs_amt+$total_tds_amt)))); !!} Rupee Only</strong></td><td colspan="3"><strong>For BTA Prime Private  Limited</strong>	</td></tr>
                        <tr><td colspan="5">This a Settlement Voucher not an Invoice, incase of any issue do let us know on 000000000</td><td colspan="3"></td></tr>
                    </table>
                  
                </div>

        </div>

    </div>
</body>
</html>

