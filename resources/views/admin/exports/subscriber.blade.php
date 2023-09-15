<table>
    <thead>
			<tr>
             <th>SN</th>
            <th>Email</th>
			</tr>
    </thead>
    <tbody>
 <?php $i=1;?>
					  @foreach($subscribers as $customer)

						<tr>
						       
                            <td>{{$i}}</td>
                            <td>{{$customer->email}}</td>
						
						</tr>
						<?php $i++;?>
					    @endforeach
    </tbody>
</table>
