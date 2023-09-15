<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
						 
							<th>Material Id</th>
							<th>Name</th>
						
						</tr>
					</thead>
					<tbody>
					
					  @foreach($Materials as $Material)

						<tr>
						 
								<td>{{$Material->id}}</td>
								<td>{{$Material->name}}</td>
								
								
							
							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>