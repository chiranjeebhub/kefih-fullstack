<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
					
						<th>Color Id</th>
						<th>Name</th>
					
							
						</tr>
					</thead>
					<tbody>
					
					  @foreach($Colors as $Color)

						<tr>
						 
								<td>{{$Color->id}} </td>
								<td>{{$Color->name}} </td>
							
						
							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>