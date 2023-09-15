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
	<div class="col-sm-12 text-right">
		<a href="{{ $page_details['export']}}" class="btn btn-warning">Export TO CSV</a>
	</div>
	<div class="col-sm-12 mt15">
		<div class="row">
			<!--<div class="col-sm-2">
				<button type="button" class="btn btn-danger btnSubmitTrigger" disabled>Bulk Delete</button>
			</div>-->
			<div class="col-sm-11">
				<div class="row">
					<!--<div class="col-md-2 hidden-xs"></div>-->
					<div class="col-md-3 col-xs-12">
						<label>Select No. of Rows</label>
						<select class="form-control customers_row" name="customers_row">
							<option value="" <?php echo ($row=='All' || $row=='')?'selected':'';?>>Select Rows</option>
							<option value="1" <?php echo ($row==1)?'selected':'';?>>50</option>
							<option value="2" <?php echo ($row==2)?'selected':'';?>>100</option>
							<option value="3" <?php echo ($row==3)?'selected':'';?>>200</option>
							<option value="4" <?php echo ($row==4)?'selected':'';?>>300</option>
						</select>
					</div>
					<div class="col-md-3 col-xs-12">
						<label>Select Phone Status </label>
						<select class="form-control isOtpVerified" name="isOtpVerified">
							<option value="">Phone Verify</option>
							<option value="1" <?php echo ($isOtpVerified=='1')?'selected':'';?>>Active</option>
							<option value="0" <?php echo ($isOtpVerified=='0')?'selected':'';?>>De-Active</option>
						</select>
					</div>
					<div class="col-md-3 col-xs-12">
						<label>Select Account Status </label>
						<select class="form-control status" name="status">
							<option value="">Select</option>
							<option value="1" <?php echo ($status=='1')?'selected':'';?>>Blocked</option>
							<option value="0" <?php echo ($status=='0')?'selected':'';?>>Un-Blocked</option>
						</select>
					</div>
					<div class="col-md-3 col-xs-12">
						<label>&nbsp; </label>
						<div class="searchmain">
							<input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="{{$parameters}}">
							<button type="submit" class="btn btn-primary searchButton" disabled >Search</button>
						</div>
					</div>
				</div>

			</div>
			<div class="col-sm-1">
				<label>&nbsp; </label>
				<button type="submit" class="btn btn-default reset" >Reset</button>
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
                            <th>Wallet</th>
							<th>Is Phone verify</th>
                            <th>View</th>
							

							
						</tr>
					</thead>
					<tbody>
					
					  @foreach($customers as $customer)

						<tr>
						       
                            <td>{{$customer->name}}</td>
                            <td>{{$customer->email}}</td>
                            <td>{{$customer->phone}}</td>
							<td>{{!empty($customer->total_reward_points)?$customer->total_reward_points:''}}</td>
                            <td>
                                @switch($customer->isOtpVerified)
                                    @case(0)
                                    <a href="{{route('verify_customer_phone', [base64_encode($customer->id),base64_encode($customer->isOtpVerified) ])}}"
                                    onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
                                    >
                                     <i class="fa fa-close text-red verify_red" aria-hidden="true"></i> 
                                    </a>
                                    @break
                                
                                    @case(1)
                                    
                                    <a href="{{route('verify_customer_phone', [base64_encode($customer->id),base64_encode($customer->isOtpVerified) ])}}"
                                    onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
                                    >
                                     <i class="fa fa-check text-green verify_green" aria-hidden="true"></i> 
                                    </a>
                                    
                                    @break
                                
                                @endswitch
                            </td>
                            <td>
                                <a href="{{route('customer_sts',[base64_encode($customer->id),base64_encode($customer->status)] )}}"
									onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
									>
										@if($customer->status ==0)         
										<i class="fa fa-close text-red" aria-hidden="true"></i> 
										@else
										<i class="fa fa-check text-green" aria-hidden="true"></i>  
										@endif
									</a>
									&nbsp;|&nbsp;
								<!--	<a href="{{route('editcustomer', base64_encode($customer->id))}}">-->
								<!--<i class="fa fa-pencil text-blue" aria-hidden="true"></i>-->
								<!--</a>-->
                            <a href="{{route('deletecustomer', base64_encode($customer->id))}}"
                            onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
                            >
                            <i class="fa fa-trash text-red" aria-hidden="true"></i></a>	&nbsp; | &nbsp;
							
								<a title="Customer Orders" href="{{route('customer_orders', [base64_encode($customer->id), base64_encode(0)])}}">
									<img style="width: 30px; max-width: inherit;" src="{{ asset('public/images/custmr-o.png') }}" />
									
                                <!--<i class="fa fa-eye text-blue"></i>-->
                                </a>
								&nbsp; | &nbsp;
								<a title="Customer Details" href="{{route('customer_detail', [base64_encode($customer->id) ])}}">
									<img style="width: 30px; max-width: inherit;" src="{{ asset('public/images/custmr-d.png') }}" />
									
                                <!--<i class="fa fa-eye text-orange"></i>-->
                                </a>
								&nbsp; | &nbsp;
								<a title="Wallet History" href="{{route('customer_wallet', [base64_encode($customer->id) ])}}">
                                <i class="fa fa-google-wallet text-primary"></i>
                                </a>
                             </td>
						
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
