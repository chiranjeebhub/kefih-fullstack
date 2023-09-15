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
	<!--<div class="allbutntbl">
		<a href="{{ route('addbrand') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Brand</a>
	</div>-->
	<div class="col-sm-12">
		<div class="row">
			<!--<div class="col-sm-2">
				<button type="button" class="btn btn-danger btnSubmitTrigger commonClassDisableButton" disabled>Bulk Delete</button>
			</div>-->
			<div class="col-sm-11">
				<div class="row">
					<!--<div class="col-md-5 hidden-xs"></div>-->
					<div class="col-md-2 col-xs-12">
						<select class="form-control status" name="status">
							<option value="all">Select</option>
							<option value="1" <?php echo ($status=='1')?'selected':'';?>>Active</option>
							<option value="0" <?php echo ($status=='0')?'selected':'';?>>De-active</option>
						</select>
					</div>
					<div class="col-md-5 col-xs-12">
						<div class="searchmain">
							<input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="{{$parameters}}" id="searchBox">
							<button type="submit" class="btn btn-primary searchButton" id="searchButton" >Search</button>
							
						</div>
					</div>
				</div>
				

			</div>
			<div class="col-sm-1">
				<button type="submit" class="btn btn-default reset" >Reset</button>
			</div>
		</div>
	</div>
</div>	

<form role="form" class="form-element multi_delete_form mt15" action="" method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
						    <!--<th><input type="checkbox" class="check_all"></th>-->
                            <th>SN</th>
                            <th>Email</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php $i=1;?>
					  @foreach($subscribers as $customer)

						<tr>
						       
                            <td>{{$i}}</td>
                            <td>{{$customer->email}}</td>
                            <td>
								<a href="{{route('subscriber_sts',[base64_encode($customer->id),base64_encode($customer->status)] )}}"
									onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
									>
										@if($customer->status ==0)         
										<i class="fa fa-close text-red" aria-hidden="true"></i> 
										@else
										<i class="fa fa-check text-green" aria-hidden="true"></i>  
										@endif
									</a>
									&nbsp;|&nbsp;
							<a href="{{route('deletesubscriber', base64_encode($customer->id))}}"
							onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
							>
							<i class="fa fa-trash text-red" aria-hidden="true"></i></a>
							</td>
						
						</tr>
						<?php $i++;?>
					    @endforeach
					</tbody>
					
				  </table>
				</div>
				
				 </form>
				
				{{ $subscribers->links() }}
				
				@include('admin.includes.Common_search_and_delete') 
			
@endsection
