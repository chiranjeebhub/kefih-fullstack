@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
 @section('backButtonFromPage')
    <a href="javascript:void(0)" class="btn btn-default goBack">Go Back</a>
    @endsection
@section('content')
<?php 
	$parameters = Request::segment(2);
	$str = Request::segment(3);
	if($str=='all')
	{
		$str='';
	}
?>
<style>
	/*.allbutntbl{ top: auto; }*/
</style>	
<div class="">
	
	<div class="col-sm-12">
		
		<div class="row">
			<div class="col-sm-2">
				<label style="display:block;">&nbsp; </label>
				<button type="submit" class="btn btn-default reset" >Reset</button>
			</div>
			<div class="col-sm-8">
				<div class="row">
					<div class="col-md-2 col-xs-12">
					<label>Select Status</label>

						<select class="form-control status" name="status">
							<option value="all">Select</option>
							<option value="1" <?php echo ($status=='1')?'selected':'';?>>Active</option>
							<option value="0" <?php echo ($status=='0')?'selected':'';?>>De-active</option>
						</select>
					</div>
					<div class="col-md-5">
						<label style="display:block;">&nbsp; </label>
						<div class="searchmain">
							<input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="{{$str}}">
							<button type="submit" class="btn btn-primary searchButton" disabled >Search</button>
						</div>
					</div>
				</div>
				
			</div>
			<div class="allbutntbl">
				<a href="{{ route('add_vendor_code') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Pincode</a>
			</div>
			
		</div>
	</div>

</div>
		
		<!--<table>
		<tr>
		<td><a href="{{ route('add_vendor_code') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"> Add Pincode</i></a></td>

		</tr>
		</table>-->


				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped mt15">
					<thead>
						<tr>
						   
							<th>Vendor</th>
							<th>Pincode</th>
							<th>Action</th>
							
						</tr>
					</thead>
					<tbody>
					
					  @foreach($pincodes as $row)

						<tr>
						      
								<td>{{$row->name}}</td>
								<td>{{$row->pincode}}</td>
								
								<td>
									<a href="{{route('vendor_pincode_sts',[base64_encode($row->id),base64_encode($row->status)] )}}"
									onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
									>
										@if($row->status ==0)         
										<i class="fa fa-close text-red" aria-hidden="true"></i> 
										@else
										<i class="fa fa-check text-green" aria-hidden="true"></i>  
										@endif
									</a>
									&nbsp;|&nbsp;
									
							<a href="{{route('edit_pin', base64_encode($row->id))}}">
								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
								</a>&nbsp;|&nbsp;
							<a href="{{route('delete_pin', base64_encode($row->id))}}"
							onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
							>
							<i class="fa fa-trash text-red" aria-hidden="true"></i></a>
							</td>
								
								
							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
				</div>
				
				
				
				
		@include('admin.includes.Common_search_and_delete')		
				
@endsection
