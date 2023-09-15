@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')

<div class="col-sm-12">
<form role="form" class="form-element" action="{{route('updateAttributes',[ base64_encode($page_details['cat_id']), base64_encode($page_details['attributesType'])  ]) }}" method="post" enctype="multipart/form-data">
			   @csrf
				<div class="row">
					<div class="col-md-6">
					Added {{$modal_header}}
						<table id="example1" class="table table-bordered table-striped">
							<thead>
								<th>{{$modal_header}}</th>
								<th>Remove</th>
							</thead>
						<tbody class="tableListItem">
							<?php 
							$i=1;
							foreach($data as $row1){	
							?>
							<tr  id="table_row_{{$i}}">
							<input type="hidden" name="arrt_id[]" value="{{$row1->id}}">
							<td>{{$row1->name}}</td>
							<td><i class="fa fa-trash text-red removeAttributes"  attr_id="{{$i}}" attr_name="{{$row1->name}}" aria-hidden="true"></i></td>
							</tr>
							<?php $i++;} ?>
						</tbody>
						</table>
						
						<div class="form-group">
						<label for="exampleInputEmail1"></label>
						<input type="submit" value="Update" class="btn btn-danger"></div>
					</div>
					
					<div class="col-md-6">
					Available {{$modal_header}}
					<table id="example1" class="table table-bordered table-striped">
						<thead>
							<tr>
							<th>{{$modal_header}}</th>
							<th>Add</th>
							</tr>
						</thead>
						<tbody class="tableListItem2">
							@foreach($attr_list as $row)
								<tr id="table_row_2{{$row->id}}">
								<td>{{$row->name}}</td>
								<td><i class="fa fa-plus text-red addTolist"  attr_id="{{$row->id}}" attr_name="{{$row->name}}" aria-hidden="true"></i></td>
								</tr>
							@endforeach
						</tbody>

					</table>
					</div>
				</div>
		    


		
 </form>
</div>
@endsection
		