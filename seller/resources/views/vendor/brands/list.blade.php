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
                    @if (Auth::guard('vendor')->check()) 
                    <a href="{{ route('vendor_addbrand') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Brand</a>
                    @else
                    <a href="{{ route('addbrand') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Brand</a>
                    @endif()
		
	</div>
	<div class="col-sm-12">
		<div class="row">
		     @if (Auth::guard('vendor')->check()) 
                   
                    @else
                        <div class="col-sm-2">
                        <button type="button" class="btn btn-danger btnSubmitTrigger commonClassDisableButton" disabled>Bulk Delete</button>
                        </div>
                    @endif()
			
			<div class="col-sm-9">
				<div class="row">
					<div class="col-md-5 hidden-xs"></div>
					<div class="col-md-2 col-xs-12">
						<label>Select Status </label>
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
			<div class="col-sm-1">
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
						 <th><input type="checkbox" class="check_all"></th>
							<th>Brand Id</th>
							<th>Name</th>
							<th>Logo</th>
							<th>Banner</th>
							<th>Action</th>

						</tr>
					</thead>
					<tbody>
					
					  @foreach($Brands as $Brand)

						<tr>
						 <td><input type="checkbox" class="checkbox multiple_select_checkBox" name="brand_id[]" value="{{$Brand->id}}"></td>
								<td>{{$Brand->id}}</td>
								<td>{{$Brand->name}}
								
								  @if (Auth::guard('vendor')->check()) 
								  @if($Brand->vendor_id!=0 && !empty($Brand->brand_noc))
                                    : <a href="{{URL::to('/uploads/brand/banner').'/'.$Brand->brand_noc}}" target="_blank">NOC</a>
                                    @endif
								  @else
                                    @if($Brand->vendor_id!=0 && !empty($Brand->brand_noc) )
                                    Belongs to :({!! App\Vendor::getVendorName($Brand->vendor_id); !!}) : 
                                    <a href="{{URL::to('/uploads/brand/banner').'/'.$Brand->brand_noc}}" target="_blank">NOC</a>
                                    @endif
								  @endif
						
								
								</td>
								<td>{!! App\Helpers\CustomFormHelper::support_image('uploads/brand/logo',$Brand->logo); !!}</td>
								<td>{!! App\Helpers\CustomFormHelper::support_image('uploads/brand/banner',$Brand->banner_image); !!}</td>
								
							<td>
							    
                                    @if (Auth::guard('vendor')->check()) 
                                            @if($Brand->status ==0)      
                                            Not Activated yet <br>
                                            <a href="{{route('vendor_editbrand', base64_encode($Brand->id))}}">
                                            <i class="fa fa-pencil text-blue" aria-hidden="true"></i>
                                            </a>&nbsp; | &nbsp;
                                            <a href="{{route('vendor_deletebrand', base64_encode($Brand->id))}}"
                                            onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
                                            >
                                            <i class="fa fa-trash text-red" aria-hidden="true"></i></a>
                                            @else
                                            Activated  
                                            @endif
                                    
                                    @else
                                    	<a href="{{route('brands_sts',[base64_encode($Brand->id),base64_encode($Brand->status)] )}}"
									onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
									>
										@if($Brand->status ==0)         
										<i class="fa fa-close text-red" aria-hidden="true"></i> 
										@else
										<i class="fa fa-check text-green" aria-hidden="true"></i>  
										@endif
									</a>
									&nbsp;|&nbsp;
							<a href="{{route('editbrand', base64_encode($Brand->id))}}">
								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
								</a>&nbsp; | &nbsp;
							<a href="{{route('deletebrand', base64_encode($Brand->id))}}"
							onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
							>
							<i class="fa fa-trash text-red" aria-hidden="true"></i></a>
                                    @endif
						
							</td>
							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
				</div>
				 </form>
				{{ $Brands->links() }}
				
				@include('admin.includes.Common_search_and_delete') 
@endsection
