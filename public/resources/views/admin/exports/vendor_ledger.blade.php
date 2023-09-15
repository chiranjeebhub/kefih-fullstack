<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Vendor ID</th>
							<th>Vendor Name</th>
							<th>GST No.</th>
							<th>Sale Amt</th>
							<th>Commission Fee	</th>
							<th>Delivery Charge</th>
							<th>Reverse Shipping Charge	</th>
							<th>Commission Fee	</th>
							<th>Net Amt</th>
							<th>Paid Amt</th>
							<th>Balance Amt</th>
							<th>TCS Amt</th>
							<th>TDS Tax</th>
							<th>IGST Amt</th>
							<th>CGST Amt</th>
							<th>SGST Amt</th>
						
						</tr>
					</thead>
					<tbody>
					<?php $i=1;  ?>
					  @foreach($ledgers as $row)
					  <?php
							$igst=App\Http\Controllers\sws_Admin\VendorLedgerController::tax_type($row->id,1,'','');
							$cgst_sgst=App\Http\Controllers\sws_Admin\VendorLedgerController::tax_type($row->id,2,'',''); 
							$paid_amount=DB::table('tbl_vendor_payment')->select(
							                                DB::raw('SUM(vendor_payment_amt) AS total_paid_amt')
							                            )->where('vendor_id',$row->id)->first();
							                            
							/*$tcs_amt=DB::table('tbl_settings')->select('tcs_tax_percentage')->where('id',1)->first();
							
							$tcs_amt=number_format((($row->total_product_amt*$tcs_amt->tcs_tax_percentage)/100),2);*/
							$tcs_amt=$row->tcs_amt;
							$tds_amt=$row->tds_amt;
					  $check_info=DB::table('vendor_company_info')->select('name as company_name')->where('vendor_id',$row->id)->first();
                            $reverseShippingAmount = App\Helpers\CommonHelper::calculateVendorReverseShippingCharge($row->id);
					  
					  ?>
					 	<tr>

							<td>{{$i++}}</td>
							<td>{{$row->id}}</td>
							<td>{{$check_info->company_name}}</td>
							<td>{{$row->gst_no}}</td>
							<td>{{$row->total_product_amt}}</td>
							<td>{{number_format($row->total_commission_amt,2)}}</td>
							<td>{{$row->total_order_shipping_charges}}</td>
							<td>{{ $reverseShippingAmount}}</td>
							<td></td>
							<td>{{number_format(($row->total_product_amt-$row->total_commission_amt),2)}}</td>
							<td>{{$paid_amount->total_paid_amt}}</td>
							<td>{{($row->total_product_amt-$row->total_commission_amt)-$paid_amount->total_paid_amt}}</td>
							<td>{{$tcs_amt}}</td>
							<td>{{$tds_amt}}</td>
							<td><?php echo number_format((@$igst->order_detail_tax_amt),2);?></td>
							<td><?php echo number_format((@$cgst_sgst->order_detail_tax_amt/2),2);?></td>
							<td><?php echo number_format((@$cgst_sgst->order_detail_tax_amt/2),2);?></td>
							
						</tr>
					    @endforeach
					</tbody>
				  </table>