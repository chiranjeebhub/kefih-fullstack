@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
	
		<?php 
				$parameters = Request::segment(2);
				$str = Request::segment(3);
				if($str=='all')
				{
					$str='';
				}
				
?>

<div>		
<div class="allbutntbl">
	<a href="{{ route('addlogistic') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Logistic</a>
</div>
<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-2">
				<button type="submit" class="btn btn-default reset" >Reset</button>
			</div>
			<div class="col-sm-10">
				<div class="row">
					<div class="col-md-2 col-xs-12">
						<select class="form-control status" name="status">
							<option value="all">Select</option>
							<option value="1" <?php echo ($status=='1')?'selected':'';?>>Active</option>
							<option value="0" <?php echo ($status=='0')?'selected':'';?>>De-active</option>
						</select>
					</div>
					<div class="col-md-5">
						<div class="searchmain">
							<input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="{{$str}}">
							<button type="submit" class="btn btn-primary searchButton" disabled >Search</button>
						</div>
					</div>
				</div>
				
			</div>
			
		</div>
	</div>
</div>
		
		
<form role="form" class="form-element multi_delete_form"  method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Link</th>
							
							<th>Action</th>

							
						</tr>
					</thead>
					<tbody>
					
					  @foreach($Logistics as $Logistic)

						<tr>
						<td>{{$Logistic->id}}</td>
								<td>{{$Logistic->name}}</td>
								<td>{{$Logistic->logistic_link}}</td>
								
								
							<td>
							<a href="{{route('logistic_sts',[base64_encode($Logistic->id),base64_encode($Logistic->status)] )}}"
									onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
									>
										@if($Logistic->status ==0)         
										<i class="fa fa-close text-red" aria-hidden="true"></i> 
										@else
										<i class="fa fa-check text-green" aria-hidden="true"></i>  
										@endif
									</a>
									&nbsp;|&nbsp;
							<a href="{{route('edit_logistic', base64_encode($Logistic->id))}}">
								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
								</a>&nbsp; | &nbsp;
							<a href="{{route('delete_loggistics', base64_encode($Logistic->id))}}"
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
				{{ $Logistics->links() }}
				
				@include('admin.includes.Common_search_and_delete') 
@endsection
