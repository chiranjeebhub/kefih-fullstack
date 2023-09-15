<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Category Id</th>
							<th>Name</th>
						
							
							
							
						</tr>
					</thead>
					<tbody>
					
					  @foreach($Categories as $Category)

						<tr>
						
                            <td>{{$Category->id}}</td>
                            <td>{{$Category->name}}</td>
                        
							
							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>