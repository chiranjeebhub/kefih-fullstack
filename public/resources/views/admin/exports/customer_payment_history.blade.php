<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
						    <!--<th><input type="checkbox" class="check_all"></th>-->
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Transaction Id</th>
							<th>Status</th>
                            <th>Payment Date</th>
							<th>Order No</th>
							<th>Amount</th>
							
						</tr>
					</thead>
					<tbody>
					
					  @foreach($customers as $customer)

						<tr>
						    <td>{{$customer->name}}</td>
                            <td>{{$customer->email}}</td>
                            <td>{{$customer->phone}}</td>
							<td>{{$customer->txn_id}}</td>
							<td>{{$customer->txn_status}}</td>
							<td>{{date('d-m-Y',strtotime($customer->order_date))}}</td>
							<td>{{$customer->order_no}}</td>
							<td>{{$customer->grand_total}}</td>
							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>