@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
<?php 
				$parameters = Request::segment(3);
				
				if($parameters=='all')
				{
					$parameters='';
				}
				
?>

<div class="">
 <!--   <div class="allbutntbl">-->
	<!--	<a href="{{route('orders',base64_encode(0)) }}" class="btn btn-warning"><i class="fa fa-undo"></i> Back</a>-->
	<!--</div>-->
	<!--<div class="allbutntbl">-->
	<!--	<a href="{{ route('addCustomer') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Customer</a>-->
	<!--</div>-->
<!--	<div class="col-sm-12 text-right">-->
<!--		<a href="{{ $page_details['export']}}" class="btn btn-warning">Export TO CSV</a>-->
<!--	</div>-->
	<div class="col-sm-12 mt15">
		<div class="row">
			<!--<div class="col-sm-2">
				<button type="button" class="btn btn-danger btnSubmitTrigger" disabled>Bulk Delete</button>
			</div>-->
		
		</div>
	</div>
</div>
		
	
		

<form role="form" class="form-element multi_delete_form mt15" action="" method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
						    <!--<th><input type="checkbox" class="check_all"></th>-->
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Transaction Id</th>
							<th>Status</th>
                            <th>Payment Date</th>
							
						</tr>
					</thead>
					<tbody>
					
					  @foreach($customers as $customer)

						<tr>
						    <td>{{$customer->name}}</td>
                            <td>{{$customer->email}}</td>
                            <td>{{$customer->phone}}</td>
							<td>{{$customer->txn_id}}</td>
							<td>{{$customer->txn_status}}</td>
							<td>{{date('d-m-Y',strtotime($customer->order_date))}}</td>
							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
				</div>
				
				 </form>
				<div class="container-fluid">
					{{ $customers->links() }}
				</div>
				@include('admin.includes.Common_search_and_delete') 
@endsection
