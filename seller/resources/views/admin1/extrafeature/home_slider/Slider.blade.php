@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
	
		<?php 
				$parameters = Request::segment(3);
				
?>
	
<div class="">
	<div class="allbutntbl">
		<a href="{{ route('addbanner') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Banner</a>
	</div>
	
	<div class="col-sm-12">
		<div class="row searchmain-row">
			<!--<div class="col-sm-3  col-md-5">
				<button type="button" class="btn btn-danger btnSubmitTrigger commonClassDisableButton" disabled>Bulk Delete</button>
			</div>-->
			<div class="col-sm-7  col-md-6">
				<div class="searchmain">
					<form method="post" action="{{ $page_details['search_route']}}">
					 @csrf
					<!--<input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="{{$parameters}}">-->
					<select class="form-control" name="status">
						<option value="">Select</option>
						<option value="1" <?php echo ($status=='1')?'selected':'';?>>Active</option>
						<option value="0" <?php echo ($status=='0')?'selected':'';?>>De-active</option>
					</select>
					<button type="submit" class="btn btn-primary searchButton" >Search</button>
					</form>
				</div>

			</div>
			<div class="col-sm-2  col-md-1">
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
		
<form role="form" class="form-element multi_delete_form mt15" action="{{route('multi_delete_brand')}}" method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
					
							<th>Text</th>
							<th>Description</th>
							<th>Url</th>
							<th>Banner</th>
							<th>Action</th>

						</tr>
					</thead>
					<tbody>
					
					  @foreach($banners as $banner)

						<tr>
					
								<td>{{$banner->short_text}}</td>
									<td>{!!$banner->description!!}</td>
										<td>{{$banner->url}}</td>
<td>
{!! App\Helpers\CustomFormHelper::support_image('uploads/slider',$banner->image); !!}</td>
								
								
							<td>
							    	<a href="{{route('banner_sts',[base64_encode($banner->id),base64_encode($banner->status)] )}}"
									onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
									>
										@if($banner->status ==0)         
										<i class="fa fa-close text-red" aria-hidden="true"></i> 
										@else
										<i class="fa fa-check text-green" aria-hidden="true"></i>  
										@endif
									</a>&nbsp; | &nbsp;
							<a href="{{route('edit_banner', base64_encode($banner->id))}}">
								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
								</a>&nbsp; | &nbsp;
							<a href="{{route('delete_banner', base64_encode($banner->id))}}"
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
			
				
				 
@endsection
