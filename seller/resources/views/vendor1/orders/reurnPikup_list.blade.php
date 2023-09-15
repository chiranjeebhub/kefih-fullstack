@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
<?php $total=0; ?>
<div class="row">

	<div class="allbutntbl">
		<a href="#" class="btn btn-warning"><i class="fa fa-undo"></i> Back</a>
	</div>
			<!-- /.col -->
			<div class="col-lg-12 col-12">
			  <div class="box bg-transparent no-border no-shadow">
			
				<!-- /.box-header -->
				<div class="box-body b-1">
				
				 
				  <div class="mailbox-read-info clearfix">
					
				
				  <div class="mailbox-read-message">
				 
					  <div class="row">
					  <?php if(sizeof($pikup_list)!=''){ ?>
					  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr.No</th>
							
							<th>Shipment Id</th>
							<th>Items </th>
							<th>Invoice Value</th>
							
							<th>Action </th>
						</tr>
					</thead>
					<tbody>
					<?php $i=1;  ?>
					  
					  @foreach($pikup_list as $row)

						<tr>
								<td>{{$i++}}</td>
								<td>{{$row['shipment_id']}}</td>
								<td>{{$row['no_of_items']}}</td>
								<td>{{$row['invoice_value']}}</td>
								
								<td>
		<form action="{{route('after_return_vendor_order_shipping_couirer_orders')}}" method="POST" >
								       @csrf
		<input type="hidden" name="order_detail_id" value="{{$order_detail_id}}" >
		<input type="hidden" name="shipment_id" value="{{$row['shipment_id']}}" >
		<input type="hidden" name="select_check" value="{{$multiple_order_detail_id}}" >		
		<input class="btn btn-success" onclick="return confirm('Are you sure?');" type='submit' value="Submit">
								   </form>
								</td>
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
					  <?php } else { echo "<p style='color:red;font-weight:bold;'>Do not have sufficient balance in your account</p>";} ?>
					  </div>
				  </div>
				
				  <!-- /.mailbox-read-message -->
				</div>
				<!-- /.box-body -->
		
				
			  </div>
			  <!-- /. box -->
			</div>
			<!-- /.col -->
		  </div>
@endsection

