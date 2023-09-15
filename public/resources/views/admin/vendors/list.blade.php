@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
<?php 
		
		$parameters = @Request::segment(3);
		if(@$parameters == 'all')
		{
			$parameters='';
		}
	
?>

<div class="">
	<div class="allbutntbl">
		<a href="{{ route('add_vendor',(base64_encode(0))) }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Vendor</a>
	</div>
	<div class="col-sm-12 text-right">
		<a href="{{ $page_details['export']}}" class="btn btn-warning">Export TO CSV / Download Vendor Database</a>
	</div>
	<div class="col-sm-12 mt15">
		<div class="row">
			<div class="col-sm-2">
				<button type="button" class="btn btn-danger btnSubmitTrigger">Bulk Delete</button>
			</div>
			<div class="col-sm-9">
				<div class="row">
					<div class="col-md-5 hidden-xs"></div>
					<div class="col-md-2 col-xs-12">
						<select class="form-control status" name="status">
							<option value="all">Select</option>
							<option value="1" <?php echo ($status=='1')?'selected':'';?>>Active</option>
							<option value="0" <?php echo ($status=='0')?'selected':'';?>>De-active</option>
						</select>
					</div>
					<div class="col-md-5 col-xs-12">
						<div class="searchmain">
							<input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="{{$parameters}}">
							<button type="submit" class="btn btn-primary searchButton" disabled >Search</button>
						</div>
					</div>
				</div>

			</div>
			<div class="col-sm-1">
				<button type="submit" class="btn btn-default reset" >Reset</button>
			</div>
		</div>
	</div>
</div>
		
		<!--<table>
		<tr>
		<td><a href="{{ route('add_vendor',(base64_encode(0))) }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"> Add Vendor</i></a></td>
		<td><a href="{{ $page_details['export']}}" class="btn btn-warning">Export TO CSV</a></td>
		<td><button type="button" class="btn btn-danger btnSubmitTrigger" disabled>Bulk Delete</button></td>
		<td><input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="{{$parameters}}"></td>
		<td><button type="submit" class="btn btn-primary searchButton" disabled >Search</button></td>
		<td><button type="submit" class="btn btn-default reset" >Reset</button></td>

		</tr>
		</table>-->
		

<form role="form" class="form-element multi_delete_form mt15" action="{{route('multi_delete_vendor')}}" method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
						    <th><input type="checkbox" class="check_all"></th>
						    	<th>Sr No</th>
							<th>Vendor ID</th>
							<th>Vendor Name</th>
							<th>Username</th>
							<th>Email</th>
							<th>Phone</th>
							<th>Update Address</th>
							<th>Is Verify</th>
							<th>Action</th>

							
						</tr>
					</thead>
					<tbody>
					@if(!empty($vendors))
					  @foreach($vendors as $vendor)
					  
					  <?php
					  
					  $check_info=DB::table('vendor_company_info')->select('name as company_name')->where('vendor_id',$vendor->id)->first();
                            
					  
					  ?>

						<tr>
						        <td><input type="checkbox" class="checkbox multiple_select_checkBox" name="vendor_id[]" value="{{$vendor->id}}"></td>
						        <td>{{$loop->iteration }}</td>
						        <td>{{$vendor->id }}</td>
								<td>{{@$check_info->company_name}}</td>
								<td>{{$vendor->username}}
								<br>
								{!!App\Vendor::getVendorRating($vendor->id)!!}
								</td>
								<td>{{$vendor->email}}</td>
								<td>{{$vendor->phone}}</td>
								
								<td><a href="{{route('vendor_address', [base64_encode($vendor->id)])}}"
								>
								<i class="fa fa-map"></i>
								</a></td>
								
								<td>
								<a href="{{route('vdr_verify', [base64_encode($vendor->id),base64_encode('email'),base64_encode($vendor->is_email_verify) ])}}"
								onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
								>
								<i class="fa fa-envelope  <?php echo ($vendor->is_email_verify==1)?'text-lightgreen':'text-maroon'?>"></i>
								</a>
								&nbsp;|&nbsp;
								<a href="{{route('vdr_verify', [base64_encode($vendor->id),base64_encode('phone'),base64_encode($vendor->is_phone_verify) ])}}"
								onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
								>
								<i class="fa fa-phone-square  <?php echo ($vendor->is_phone_verify==1)?'text-lightgreen':'text-maroon'?>"  aria-hidden="true"></i>
								</a>
								
								</td>
								<td>
									<a href="{{route('vdr_sts',[base64_encode($vendor->id),base64_encode($vendor->status)] )}}"
									onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
									>
									@if($vendor->status ==0)         
									<i class="fa fa-close text-red <?php echo ($vendor->status==0)?'verify_red':'green'?>" aria-hidden="true"></i> 
									@else
									<i class="fa fa-check text-green <?php echo ($vendor->status==0)?'verify_red':'verify_green'?>" aria-hidden="true"></i>  
									@endif
									</a>&nbsp;|&nbsp;
								<a title="Vendor Orders" href="{{route('vendors_order', [base64_encode($vendor->id), base64_encode(0)])}}">
                                <i class="fa fa-eye text-blue"></i>
                                </a>
								&nbsp; | &nbsp;	
							<a href="{{route('edit_vendor', [base64_encode(0),base64_encode($vendor->id)])}}">
								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
								</a>&nbsp;|&nbsp;
							<a href="{{route('delete_vdr', base64_encode($vendor->id))}}"
							onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
							>
							<i class="fa fa-trash text-red" aria-hidden="true"></i></a>
							</td>
								
							
						</tr>
					    @endforeach
						@endif
					</tbody>
					
				  </table>
				</div>
				
				 </form>
				 @if(!empty($vendors))
				{{ $vendors->links() }}
				@endif
				
				@include('admin.includes.Common_search_and_delete') 
@endsection
