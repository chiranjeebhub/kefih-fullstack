<table>
    <thead>
			<tr>
			<th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Wallet Amount</th>
            <th>Account Activated</th>
			<th>Registration Date</th>
			</tr>
    </thead>
    <tbody>
   @foreach($Customers as $Customer)

						<tr>
								<td>{{$Customer->id}}</td>
								<td>{{$Customer->name}}</td>
								<td>{{$Customer->email }}</td>
								<td>{{$Customer->phone}}</td>
								<td>Rs.{{$Customer->total_reward_points}}</td>
								<td>
										@switch($Customer->status)
										@case(0)
										<span>NO </span>
										@break

										@case(1)
										<span>Yes</span>
										@break
										
									
										@endswitch
								
								</td>
								<td>{{date('d-m-Y',strtotime($Customer->created_at))}}</td>
						</tr>
					    @endforeach
    </tbody>
</table>
