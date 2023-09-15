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
	
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-2">
				<button type="button" class="btn btn-danger btnSubmitTrigger commonClassDisableButton" disabled>Bulk Delete</button>
			</div>
			<div class="col-sm-9">
				<!--<div class="row">
					<div class="col-md-5 hidden-xs"></div>
					<div class="col-md-2 col-xs-12">
						<label>Select Status</label>
						<select class="form-control status" name="status">
							<option value="">Select</option>
							<option value="1" <?php echo ($status=='1')?'selected':'';?>>Active</option>
							<option value="0" <?php echo ($status=='0')?'selected':'';?>>De-active</option>
						</select>
					</div>
					<div class="col-md-5 col-xs-12">
						<div class="searchmain">
							<input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="">
							<button type="submit" class="btn btn-primary searchButton" disabled >Search</button>
						</div>
					</div>
				</div>
				
				
			</div>
			<div class="col-sm-1">
				<button type="submit" class="btn btn-default reset" >Reset</button>
			</div>-->
		</div>
	</div>
</div>
		
	
		
<form role="form" class="form-element multi_delete_form mt15" action="{{route('multi_vendor_delete_cat')}}?vendor_id={{base64_encode($vendorsid)}}" method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
						  <th><input type="checkbox" class="check_all"></th>
							<th>Category Id</th>
							<th>Name</th>
							<th>Action</th>
							
						</tr>
					</thead>
					<tbody>
					
					  @foreach($Categories as $Category)

						<tr>
						 <td><input type="checkbox" class="checkbox multiple_select_checkBox" name="cat_id[]" value="{{$Category->id}}"></td>
								<td>{{$Category->id}}</td>
								<td>{{$Category->name}}</td>
							<td>
								
							<?php 
								$loginuser=$vendorsid;
							 $categorysize=DB::table('sizechart')->where('vendor_id',$loginuser)->where('category_id',$Category->id)->get()->count();
							if($categorysize>0)
							{
							?>
							<a href="{{ route('adminadd_sizechart',[base64_encode($Category->id),base64_encode($loginuser)]) }}" class="btn btn-success">Update size chart</a>
							<?php }else{
								?>
								<a href="{{ route('adminadd_sizechart',[base64_encode($Category->id),base64_encode($loginuser)]) }}" class="btn btn-success">Add size chart</a>
								<?php } ?>
							<!--<a href="{{route('cat_sts',[base64_encode($Category->id),base64_encode($Category->status)] )}}"
									onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
									>
										@if($Category->status ==0)         
										<i class="fa fa-close text-red" aria-hidden="true"></i> 
										@else
										<i class="fa fa-check text-green" aria-hidden="true"></i>  
										@endif
									</a>
									&nbsp;|&nbsp;
									<a href="{{route('edit_category', base64_encode($Category->id))}}">
								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
								</a>&nbsp;|&nbsp;
							<a href="{{route('delete_category', base64_encode($Category->id))}}"
							onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
							>
							<i class="fa fa-trash text-red" aria-hidden="true"></i></a>-->
							</td>
							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
				</div>
				 </form>
				{{ $Categories->links() }}
				
				@include('admin.includes.Common_search_and_delete') 
				
				<div id="attributesModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content attributesResponseData">
     
     
    </div>

  </div>
</div>
@endsection
