<table>
    <thead>
			<tr>
			<th>Name</th>
			<th>SKU</th>
			<th>Weight</th>
			<th>Price</th>
			<th>Visibility</th>
			</tr>
    </thead>
    <tbody>
   @foreach($Products as $Product)

						<tr>
								<td>{{$Product->name}}</td>
								<td>{{$Product->sku }}</td>
								<td>{{$Product->weight}}</td>
								<td>{{$Product->price}}</td>
								<td>
										@switch($Product->visibility)
										@case(1)
										<span>Not visible individual </span>
										@break

										@case(2)
										<span>Catelog</span>
										@break
										
										@case(3)
										<span>Search</span>
										@break
										
										@case(4)
										<span>Catelog,Search</span>
										@break
									

										@default
										<span>Not setted</span>
										@endswitch
								
								</td>
						</tr>
					    @endforeach
    </tbody>
</table>
