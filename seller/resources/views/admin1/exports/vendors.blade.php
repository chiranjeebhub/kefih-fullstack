<table>
    <thead>
			<tr>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Username</th>
			<th>Public name</th>
			<th>Email</th>
			</tr>
    </thead>
    <tbody>
   @foreach($Vendors as $Vendor)

						<tr>
								<td>{{$Vendor->f_name}}</td>
								<td>{{$Vendor->l_name }}</td>
								<td>{{$Vendor->username}}</td>
								<td>{{$Vendor->public_name}}</td>
								<td>{{$Vendor->email}}</td>
								
						</tr>
					    @endforeach
    </tbody>
</table>
