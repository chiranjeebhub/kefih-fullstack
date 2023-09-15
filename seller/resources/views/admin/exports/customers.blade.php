<table>
    <thead>
			<tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Wallet Ampunt</th>
            <th>Acount Activated</th>
			</tr>
    </thead>
    <tbody>
   @foreach($Customers as $Customer)

						<tr>
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
						</tr>
					    @endforeach
    </tbody>
</table>
