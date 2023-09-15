@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title']) 
@section('content')

 
<div class="">
    
	<div class="allbutntbl">
		<a href="{{ route('addcity') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add City</a>
	
	</div>
	<div class="col-sm-12">
		<div class="row searchmain-row">
		
			<div class="col-sm-9  col-md-4">
				<form action="{{ route('city') }}" method="GET">
				<div class="row">
				
					<div class="col-md-12 col-xs-12">
						<div class="searchmain">
							<input type="text" name="str" class="form-control search_string" placeholder="Search By Name" value="{{($parameters != '')?$parameters:''}}">
							<button type="submit" class="btn btn-primary searchButton" disabled >Search</button>
							
						</div>
					</div>
				</div>
				</form>

			</div>
			<div class="col-sm-1  col-md-1">
				<button type="submit" class="btn btn-default reset" >Reset</button>
			</div>
		</div>
	</div>
</div>
<div class="col-sm-12">
<form role="form" class="form-element multi_delete_form mt15" action="{{route('multi_delete_city')}}" method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr.No.</th>
							<th>Name</th>
						    <th>Action</th>

						</tr>
					</thead>
					<tbody>
					<?php $a = 1; ?>
					  @foreach($City as $C)

						<tr>
								<td>{{$a++}}</td>
								<td>{{$C->name}}</td>
								<td><a href="{{route('city_sts',[base64_encode($C->id),base64_encode($C->status)] )}}"
    									onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
    									>
    										@if($C->status ==0)         
    										<i class="fa fa-close text-red" aria-hidden="true"></i> 
    										@else
    										<i class="fa fa-check text-green" aria-hidden="true"></i>  
    										@endif
    									</a>
    							&nbsp; | &nbsp;
    							<a href="{{route('editcity', base64_encode($C->id))}}">
    								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
    								</a>&nbsp; | &nbsp;
    							<a href="{{route('deletecity', base64_encode($C->id))}}"
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
				{{ $City->links() }}
				
				@include('admin.includes.Common_search_and_delete') 
</div>
@endsection
