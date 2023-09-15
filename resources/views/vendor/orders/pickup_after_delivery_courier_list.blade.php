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
					 	<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr.No</th>
							<!--<th>Courier Id</th>-->
							<th>Service Type</th>
							<th>Courier Name</th>
							<th>Courier Charges</th>
							<th>Service Mode</th>
							<th>Expected Pickup Date</th>
							<th>Expected Delivey</th>
							<th>Action </th>
						</tr>
					</thead>
					<tbody>
					<?php $i=1;  ?>
					  
					  @foreach($courier_list as $row)

						<tr>
								<td>{{$i++}}</td>
								<!--<td>{{ $row['courier_id']}}</td>-->
								<td>{{$row['service_name']}}</td>
								<td>{{$row['courier_name']}}</td>
								<td>
                                Cod 1 charge -> {{$row['codcharges']}}
                                <br>
                                Toral 1 charge -> {{$row['courier_charges']}}
                                <br>
                                Cod 2 charge -> {{$row['codcharges1']}}
                                <br>
                                Toral 2 charge -> {{$row['courier_charges1']}}

								</td>
								<td>{{$row['display_name']}}</td>
								<td>{{$row['expected_pickup_date']}}</td>
									<td>{{$row['expected_delivery']}}- days</td>
								<td>
								   <form action="{{route('reutrn_vendor_order_shipping_couirer_pickup')}}" method="POST" >
								       @csrf
								       <input type="hidden" name="order_detail_id" value="{{$order_detail_id}}" >
								       <input type="hidden" name="courier_id" value="{{$row['courier_id']}}" >
								       <input type="hidden" name="service_id" value="{{$row['service_id']}}" >
								       <input type="hidden" name="service_name" value="{{$row['service_name']}}" >
								       <input type="hidden" name="courier_charges" value="{{$row['courier_charges']}}" >
								       <input type="hidden" name="courier_name" value="{{$row['courier_name']}}" >
									   
									   <input type="hidden" name="phy_weight" value="{{$shipment_measure['weight']}}" >
									   <input type="hidden" name="ship_length" value="{{$shipment_measure['height']}}" >
									   <input type="hidden" name="ship_width" value="{{$shipment_measure['length']}}" >
									   <input type="hidden" name="ship_height" value="{{$shipment_measure['width']}}" >
									   <input type="hidden" name="select_check" value="{{$multiple_order_detail_id}}" >
									   
									   <input type="hidden" name="package_description" value="{{$package_content}}" >
									   
								       <input class="btn btn-success" onclick="return confirm('Are you sure?');" type='submit' value="Pickup">
								   </form>
								   
								</td>
							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
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

