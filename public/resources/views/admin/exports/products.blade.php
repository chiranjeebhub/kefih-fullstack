<table>
    <thead>
			<tr>
			<th>Sr. No.</th>
			<th>Product ID</th>
			<th>HSN Code</th>
			<th>Name</th>
			<th>SKU</th>
			<th>Weight</th>
			<th>MRP</th>
			<th>Listing Price</th>
		
			</tr>
    </thead>
    <tbody>
		
   @foreach($Products as $key => $Product)

						<tr>
								<td>{{++$key}}</td>
								<td>{{$Product->id}}</td>
								<td>{{$Product->hsn_code}}</td>
								<td>{{$Product->name}}</td>
								<td>{{$Product->sku }}</td>
								<td>{{$Product->weight}}</td>
								<td>{{$Product->price}}</td>
								<td>{{$Product->spcl_price}}</td>
							
						</tr>
					    @endforeach
    </tbody>
</table>
