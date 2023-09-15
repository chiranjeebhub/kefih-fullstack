<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Id</th>
							<th>name</th>
							
						
						</tr>
					</thead>
					<tbody>
					<?php $i=1;  ?>
					  @foreach($ledgers as $row)
					  
					
					 	<tr>
						 <tr>

							<td>{{$i++}}</td>
							<td>{{$row->id}}</td>
							<td>{{$row->name}}</td>
							
						</tr>
					    @endforeach
						
					</tbody>
				  </table>