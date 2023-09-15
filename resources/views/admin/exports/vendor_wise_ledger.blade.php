	<table id="example1" class="table table-bordered table-striped">
                    <tr>
                    <td colspan="2">
                        {{$vendor_info->public_name}}
                        {{$vendor_info->email}}
                        {{$vendor_info->phone}}
                        </td>
                    </tr>
					<thead>
						<tr>
							<th>Sr.No</th>
							<th>Order Date</th>
							<th>SKU</th>
							<th>Product Name</th>
							<th>Qty</th>
							<th>Price</th>
							<th>Total</th>
							<th>Commission Amt</th>
							<th>IGST Amt</th>
							<th>CGST Amt</th>
							<th>SGST Amt</th>
						</tr>
					</thead>
					<tbody>
					<?php $i=1;  $total=0; $total_commission=0; ?>
					  @foreach($ledger_details as $row)
					  <?php
							$igst=$cgst_sgst=0;
							$tax_type=$row['order_detail_invoice_type'];
							if($tax_type==1) //IGST
							{
								$igst=$row['order_detail_tax_amt'];
							}
							if($tax_type==2) //CGST/SGST
							{
								$cgst_sgst=$row['order_detail_tax_amt'];
							}


					  ?>
						<tr>
							<td>{{$i++}}</td>
							<td>{{date('d-m-Y',strtotime($row['order_date']))}}</td>
							<td>{{$row['sku']}}</td>
							<td>{{$row['product_name']}}
							<br>{{ isset($row['size'])?'Size: '.$row['size']:''}}
							<br>{{isset($row['color'])?'Color: '.$row['color']:''}}
							</td>
							<td>{{$row['product_qty']}}</td>
							<td>{{$row['product_price']}}</td>
							<td>{{$row['product_qty']*$row['product_price']}}</td>
							<td>{{$commission_amt=number_format(((($row['product_qty']*$row['product_price'])*$row['order_commission_rate'])/100),2)}}</td>
							<td><?php echo number_format($igst,2);?></td>
							<td><?php echo number_format(($cgst_sgst/2),2);?></td>
							<td><?php echo number_format(($cgst_sgst/2),2);?></td>
						</tr>
						<?php $total+=$row['product_qty']*$row['product_price'];
								$total_commission+=$commission_amt;
						?>
					    @endforeach
					</tbody>
					
				  </table>