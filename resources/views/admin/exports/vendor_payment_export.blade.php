<table id="example1" class="table table-bordered table-striped">
      
					<thead>
					    <tr> <td colspan="2">	
                        {{$vendor_info->public_name}}<br>
                        {{$vendor_info->email}}<br>
                        {{$vendor_info->phone}}
		</td></tr>
						<tr>
							<th>Sr. No.</th>
							<th>Date</th>
							<th>Payment Mode</th>
							<th>Amt</th>
							<th>Narration</th>
						</tr>
					</thead>
					<tbody>
					<?php $i=1;  ?>
					  @foreach($payment_history as $row)
					 	<tr>
							<td>{{$i++}}</td>
							<td>{{$row->vendor_payment_date}}</td>
							<td>{{$row->vendor_payment_mode}}</td>
							<td>{{$row->vendor_payment_amt}}</td>
							<td>{{$row->vendor_payment_narration}}</td>
						</tr>
					    @endforeach
					</tbody>
				  </table>