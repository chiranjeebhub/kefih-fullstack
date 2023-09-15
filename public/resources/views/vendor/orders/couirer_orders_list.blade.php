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
					 
					 <p>Courier Txn Number: {{$ordercode}}</p>
					 <br><br><br><br><br><br><br>
					 
					 <form action="{{route('CourierOrderInfo')}}" method="POST" >
								       @csrf
		<input type="hidden" name="order_detail_id" value="{{$order_detail_id}}" >
		<input type="hidden" name="ordercode" value="{{$ordercode}}" >
		<input type="hidden" name="select_check" value="{{$multiple_order_detail_id}}" >	
		<input class="btn btn-success" onclick="return confirm('Are you sure?');" type='submit' value="Submit">
								   </form>
								   
								   
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

