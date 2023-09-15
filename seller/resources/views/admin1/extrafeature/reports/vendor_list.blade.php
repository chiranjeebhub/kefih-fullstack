@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')

<div class="row">
<?php 
$usid = Request::segment(3);
 ?>
	<div class="allbutntbl">
		<a href="{{route('customers') }}" class="btn btn-warning"><i class="fa fa-undo"></i> Back</a>
	</div>
	<div class="mailbox-read-info">
	<h3>
	@if(base64_decode($usid)==0) Highest selling product @endif
	@if(base64_decode($usid)==1) Location wise products selling @endif
	@if(base64_decode($usid)==2) Refund or replaced orders @endif
	@if(base64_decode($usid)==3) Best Vendors @endif
	</h3>
	</div>
			<!-- /.col -->
			<!--<div class="col-sm-12">
			 
<nav class="mt15 new_ordertabs">
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
	<a class="nav-item nav-link active" data-toggle="tab" href="#newaorder">New Orders </a>
	<a class="nav-item nav-link" data-toggle="tab" href="#newaorder">Invoice</a>
	<a class="nav-item nav-link" data-toggle="tab" href="#newaorder">Shipping</a>
	<a class="nav-item nav-link" data-toggle="tab" href="#Delivered">Delivered</a>
	<a class="nav-item nav-link" data-toggle="tab" href="#cancell">Cancelled</a>
	<a class="nav-item nav-link" data-toggle="tab" href="#return">Returned & Refund</a>
	<a class="nav-item nav-link" data-toggle="tab" href="#failed">Failed</a>
	
  </div>
</nav>
</div>-->

			<div class="col-lg-12 col-12">
			  <div class="box bg-transparent no-border no-shadow">
			 
			 <div class="tab-content">
<div id="newaorder" class="container-fluid tab-pane active">
 @if(count($Order))
 <div class="col-sm-12 text-right">
<button class="btn btn-warning">Export TO Excel</button>
</div>
				<!-- /.box-header -->
				<div class="box-body b-1">
				
				
				  <div class="mailbox-read-message">
				  
					  <div class="row">
					
					 	  <table id="table2excel" class="table table-bordered table-striped noExl">
					<thead>
						<tr>
							<th>Sr.No</th>
							<th>Vendor Name</th>
							<th>Total sales</th>
							<th>Total</th>
							
						</tr>
					</thead>
					<tbody>
					<?php $total=0;?>
					
					  @foreach($Order as $row)
					 
						<tr>
							<td>{{$loop->iteration}}</td>
							<td>
								{{$row['username']}}
							</td>
							<td>{{$row['total_sales']}}</td>
							<td>Rs. {{$row['total_price'] + $row['order_shipping_charges']}}</td>
						</tr>
						<?php $total+=$row['total_price'] + $row['order_shipping_charges'];?>
						  
					    @endforeach
					  
					</tbody>
				  </table>
				    
					  </div>
				  {{$Order->links()}}
				 <div class="box-footer reportsTotal" style="float: right;">
					<h5> Grand Total <span>Rs. {{round($total)}}</span></h5>
				</div>
				
				  </div>
				  
				  
				  <!-- /.mailbox-read-message -->
				</div>
				<!-- /.box-body -->
		@endif		
		 </div>

		  </div>
				
			  </div>
			  <!-- /. box -->
			</div>
			<!-- /.col -->
		  </div>
		  @include('admin.includes.exportscript') 
 @endsection

