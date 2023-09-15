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
	    	<a href="{{ $page_details['export']}}" class="btn btn-warning">Export TO CSV</a> &nbsp;
		<a href="{{ route('addsize') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Size</a>
	</div>
	<div class="col-sm-12">
		<div class="row searchmain-row">
			<div class="col-sm-2  col-md-5">
				<button type="button" class="btn btn-danger btnSubmitTrigger commonClassDisableButton" disabled>Bulk Delete</button>
			</div>
			<div class="col-sm-9  col-md-6">
				<div class="row">
					<div class="col-md-5 hidden-xs"></div>
					<div class="col-md-2 col-xs-12">
						<select class="form-control status" name="status">
							<option value="">Select</option>
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
			<div class="col-sm-1  col-md-1">
				<button type="submit" class="btn btn-default reset" >Reset</button>
			</div>
		</div>
	</div>
</div>

		<!--<table>
		<tr>
		<td><a href="{{ route('addsize') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"> Add Size</i></a></td>
		<td><button type="button" class="btn btn-danger btnSubmitTrigger" disabled>Bulk Delete</button></td>
		<td><input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="{{$parameters}}"></td>
		<td><button type="submit" class="btn btn-primary searchButton" disabled >Search</button></td>
		<td><button type="submit" class="btn btn-default reset" >Reset</button></td>

		</tr>
		</table>-->
		<form role="form" class="form-element multi_delete_form mt15" action="{{route('multi_delete_size')}}" method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
						<th><input type="checkbox" class="check_all"></th>
							<th>Size Id</th>
							<th>Size</th>
							<th>Action</th>
							
							
						</tr>
					</thead>
					<tbody>
					
					  @foreach($Sizes as $size)

						<tr>
							<td><input type="checkbox" class="checkbox multiple_select_checkBox" name="size_id[]" value="{{$size->id}}"></td>
							<td>{{$size->id}}</td>
							<td>{{$size->name}}</td>
							<td>
								<a href="{{route('sizes_sts',[base64_encode($size->id),base64_encode($size->status)] )}}"
									onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
									>
										@if($size->status ==0)         
										<i class="fa fa-close text-red" aria-hidden="true"></i> 
										@else
										<i class="fa fa-check text-green" aria-hidden="true"></i>  
										@endif
									</a>
									&nbsp;|&nbsp;
								<a href="{{route('editsize', base64_encode($size->id))}}">
								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
								</a>&nbsp;|&nbsp;
							<a href="{{route('deletesize', base64_encode($size->id))}}"
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
				{{ $Sizes->links() }}
				@include('admin.includes.Common_search_and_delete') 
@endsection
