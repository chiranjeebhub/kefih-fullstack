@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
 @section('backButtonFromPage')
    <a href="javascript:void(0)" class="btn btn-default goBack">Go Back</a>
    @endsection
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
	<div class="col-sm-12 text-right">
		<a href="{{ $page_details['export']}}" class="btn btn-warning">Export TO CSV</a>
	</div>
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-3 col-md-3">
				<label>Select Date Range</label>
				<input type="text" name="daterange" class="form-control daterange" placeholder="Select Date" value="<?php echo ($daterange!='All')? str_replace('.','/',$daterange):"";?>">
			</div>
			<div class="col-sm-2 col-md-2">
				<label style="display:block;"> &nbsp; </label>
				<button type="submit" class="btn btn-primary searchBtn Customer_payment_history">Search</button>
			</div>
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
							<th>Order No</th>
							<th>Amount</th>
							
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
							<td>{{$customer->order_no}}</td>
							<td>{{$customer->grand_total}}</td>
							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
				</div>
				
				 </form>
				<div class="container-fluid">
					{{ $customers->links() }}
				</div>
				<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

<script type="text/javascript">
$(function() {

  $('input[name="daterange"]').daterangepicker({
      autoUpdateInput: false,
      locale: {
          cancelLabel: 'Clear',
		  format: 'DD-MM-YYYY'
      }
  });

  $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD-MM-YYYY') + '/' + picker.endDate.format('DD-MM-YYYY'));
	  $('.daterange').trigger('change');
  });

  $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
  });

});
</script>
				@include('admin.includes.Common_search_and_delete') 
@endsection
