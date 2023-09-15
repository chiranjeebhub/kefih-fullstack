<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
						    <!--<th><input type="checkbox" class="check_all"></th>-->
                            <th>Master Order ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Transaction Id</th>
							<th>Amount</th>
							<th>Status</th>
                            <th>Payment Date</th>
							<!-- <th>Order No</th> -->
							<th>Payment Mode</th>
							
						</tr>
					</thead>
					<tbody>
					
					  @foreach($customers as $customer)

						<tr>
						    <td>{{$customer->order_no}}</td>
						    <td>{{$customer->name}}</td>
                            <td>{{$customer->email}}</td>
                            <td>{{$customer->phone}}</td>
							<td>{{$customer->txn_id}}</td>
							<td>{{$customer->grand_total}}</td>
							<td>{{$customer->txn_status}}</td>
							<td>{{date('d-m-Y',strtotime($customer->order_date))}}</td>
							@if($customer->payment_mode==1)
							<td>Online</td>
							@else
							<td>COD</td>
							@endif
							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>