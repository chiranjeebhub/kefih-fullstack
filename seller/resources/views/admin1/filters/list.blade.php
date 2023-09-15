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
	<div class="allbutntbl">
		<a href="{{ route('addfilter') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Filter</a>
	</div>
	<div class="col-sm-12">
	<div class="row searchmain-row">
			<div class="col-sm-2  col-md-5">
				<button type="button" class="btn btn-danger btnSubmitTrigger commonClassDisableButton" disabled>Bulk Delete</button>
			</div>
			<div class="col-sm-9  col-md-6">
				<div class="row">
					<div class="col-md-2 hidden-xs"></div>
					<div class="col-md-3 col-xs-12">
						<select class="form-control status" name="status">
							<option value="">Select</option>
							<option value="1" <?php echo ($status=='1')?'selected':'';?>>Active</option>
							<option value="0" <?php echo ($status=='0')?'selected':'';?>>De-active</option>
						</select>
					</div>
					<div class="col-md-6 col-xs-12">
						<div class="searchmain">
							<input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="{{$parameters}}">
							<button type="submit" class="btn btn-primary searchButton" disabled >Search</button>
							
						</div>
					</div>
				</div>
				

			</div>
			<div class="col-sm-1  col-md-1">
				<button type="submit" class="btn btn-default reset" >Reset</button>
			</div>
		</div>
	</div>
</div>

		<!--<table class="allbutntbl">
		<tr>
		<td><a href="{{ route('addfilter') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Filter</a></td>
		<td><button type="button" class="btn btn-danger btnSubmitTrigger" disabled>Bulk Delete</button></td>
		<td><input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="{{$parameters}}"></td>
		<td></td>
		<td><button type="submit" class="btn btn-default reset" >Reset</button></td>

		</tr>
		</table>-->
		
<form role="form" class="form-element multi_delete_form mt15" action="{{route('multi_delete_filter')}}" method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
						 <th><input type="checkbox" class="check_all"></th>
							<th>Filters Id</th>
							<th>Name</th>
							<th>Update</th>
							<th>Action</th>

						</tr>
					</thead>
					<tbody>
					
					  @foreach($Filters as $Filter)

						<tr>
						 <td><input type="checkbox" class="checkbox multiple_select_checkBox" name="filter_id[]" value="{{$Filter->id}}"></td>
						 	<td>{{$Filter->id}}</td>
							<td>{{$Filter->name}}</td>
							
								<td>
<a href="{{route('update_filter_value', base64_encode($Filter->id))}}">
Update Filter Values
</a>
&nbsp; | &nbsp;
<a href="{{route('assign_cat_to_filter', base64_encode($Filter->id))}}">
Assign Category
</a>
								</td>
								
								
							<td>
							<a href="{{route('filters_sts',[base64_encode($Filter->id),base64_encode($Filter->status)] )}}"
									onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
									>
										@if($Filter->status ==0)         
										<i class="fa fa-close text-red" aria-hidden="true"></i> 
										@else
										<i class="fa fa-check text-green" aria-hidden="true"></i>  
										@endif
									</a>
									&nbsp;|&nbsp;
							<a href="{{route('editfilter', base64_encode($Filter->id))}}">
								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
								</a>&nbsp; | &nbsp;
							<a href="{{route('deletefilter', base64_encode($Filter->id))}}"
							onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
							>
							<i class="fa fa-trash text-red" aria-hidden="true"></i></a>
							</td>
							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
				</div>
				 </form>
				{{ $Filters->links() }}
				
				@include('admin.includes.Common_search_and_delete') 
@endsection
