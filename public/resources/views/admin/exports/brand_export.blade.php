<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
						 
							<th>Brand Id</th>
							<th>Name</th>
							

						</tr>
					</thead>
					<tbody>
					
					  @foreach($Brands as $Brand)

						<tr>
					
								<td>{{$Brand->id}}</td>
								<td>{{$Brand->name}}</td>
								
							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>