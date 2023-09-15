@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')

<div class="allbutntbl">
	<a href="{{ route('addpermissions') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Permissions</a>
</div>
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>User Role</th>
							<th>Module Accessable</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
					
					  @foreach($Rights_list as $Rights)
								<tr>
								<td>{{$Rights->user_role_name}}</td>
								<td>{{$Rights->total}}</td>
								
							<td>
							<a href="{{route('editpermissions', base64_encode($Rights->user_role_id))}}">
								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
								</a>&nbsp;|&nbsp;
							<a href="{{route('deletepermissions', base64_encode($Rights->user_role_id))}}"
							onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
							>
							<i class="fa fa-trash text-red" aria-hidden="true"></i></a>
							</td>
							
						</tr>
					    @endforeach
					</tbody>
					 </table>
				</div>
				
@endsection
