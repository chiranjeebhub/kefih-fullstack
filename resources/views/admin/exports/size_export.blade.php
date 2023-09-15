<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
					
							<th>Size Id</th>
							<th>Size</th>
						
							
							
						</tr>
					</thead>
					<tbody>
					
					  @foreach($Sizes as $size)

						<tr>
							
							<td>{{$size->id}}</td>
							<td>{{$size->name}}</td>
						
							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>