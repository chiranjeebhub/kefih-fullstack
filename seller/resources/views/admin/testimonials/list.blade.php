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
		<a href="{{ route('addtestimonial') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Testimonial</a>
	</div>
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-2">
				<label style="display:block;">&nbsp; &nbsp; </label>
				<button type="button" class="btn btn-danger btnSubmitTrigger commonClassDisableButton" disabled>Bulk Delete</button>
			</div>
			<div class="col-sm-9">
				<div class="row">
					<div class="col-md-4 hidden-xs"></div>
					<div class="col-md-3 col-xs-12">
					<label>Select Status</label>
						<select class="form-control status" name="status">
						
							<option value="all">Select</option>
							<option value="1" <?php echo ($status=='1')?'selected':'';?>>Active</option>
							<option value="0" <?php echo ($status=='0')?'selected':'';?>>De-active</option>
						</select>
					</div>
					<div class="col-md-5 col-xs-12">
						<label style="display:block;">&nbsp; &nbsp; </label>
						<div class="searchmain">
							<input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="{{$parameters}}">
							<button type="submit" class="btn btn-primary searchButton" disabled >Search</button>
							
						</div>
					</div>
				</div>

			</div>
			<div class="col-sm-1">
				<label style="display:block;">&nbsp; &nbsp; </label>
				<button type="submit" class="btn btn-default reset" >Reset</button>
			</div>
		</div>
	</div>
</div>

		<!--<table class="allbutntbl">
		<tr>
		<td><a href="{{ route('addbrand') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Brand</a></td>
		<td><button type="button" class="btn btn-danger btnSubmitTrigger" disabled>Bulk Delete</button></td>
		<td><input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="{{$parameters}}"></td>
		<td></td>
		<td><button type="submit" class="btn btn-default reset" >Reset</button></td>

		</tr>
		</table>-->
		
<form role="form" class="form-element multi_delete_form mt15" action="{{route('multi_delete_testimonial')}}" method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
						 <th><input type="checkbox" class="check_all"></th>
							<th>Name</th>
							<th>Image</th>
							<th>Action</th>

						</tr>
					</thead>
					<tbody>
					
					  @foreach($Testimonials as $Testimonial)

						<tr>
						 <td><input type="checkbox" class="checkbox multiple_select_checkBox" name="testimonial_id[]" value="{{$Testimonial->id}}"></td>
								<td>{{$Testimonial->name}}</td>
								<td>{!! App\Helpers\CustomFormHelper::support_image('uploads/testimonial/banner',$Testimonial->banner_image); !!}</td>
								
							<td>
							<a href="{{route('testimonial_sts',[base64_encode($Testimonial->id),base64_encode($Testimonial->status)] )}}"
									onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
									>
										@if($Testimonial->status ==0)         
										<i class="fa fa-close text-red" aria-hidden="true"></i> 
										@else
										<i class="fa fa-check text-green" aria-hidden="true"></i>  
										@endif
									</a>
									&nbsp;|&nbsp;
							<a href="{{route('edittestimonial', base64_encode($Testimonial->id))}}">
								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
								</a>&nbsp; | &nbsp;
							<a href="{{route('deletetestimonial', base64_encode($Testimonial->id))}}"
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
				{{ $Testimonials->links() }}
				
				@include('admin.includes.Common_search_and_delete') 
@endsection
