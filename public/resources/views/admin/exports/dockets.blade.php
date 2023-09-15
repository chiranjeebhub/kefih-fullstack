<?php  error_reporting(0); ?>
<table>
    <thead>
			<tr>
			<th>Id</th>
			<th>Docket Batch ID</th>
			<th>Docket Type</th>
		    <th>Docket Number</th>
		    <th>Status</th>
			</tr>
    </thead>
    <tbody>
                        @foreach($Dockets as $docket)

						<tr>
								<td><?php echo $docket->fld_docket_id; ?></td>
								<td><?php echo $docket->docket_batch_id; ?></td>
							    <td><?php echo $docket->docket_type; ?></td>
								<td><?php echo $docket->docket_number; ?></td>
								<td><?php echo ($docket->status == 0)?'Available':'Used'; ?></td>
						</tr>
					    @endforeach
    </tbody>
</table>
