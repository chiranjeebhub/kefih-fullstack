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
		<a href="{{ route('add_vendor',(base64_encode(0))) }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Vendor</a>
	</div>
	<div class="col-sm-12 text-right">
		<a href="{{ $page_details['export']}}" class="btn btn-warning">Export TO CSV</a>
	</div>
	<div class="col-sm-12 mt15">
		<div class="row">
			<div class="col-sm-2">
				<label style="display:block;">&nbsp; </label>
				<button type="button" class="btn btn-danger btnSubmitTrigger commonClassDisableButton" disabled>Bulk Delete</button>
			</div>
			<div class="col-sm-9">
				<div class="row">
					<div class="col-md-4 hidden-xs"></div>
					<div class="col-md-3 col-xs-12">
						<label>Select Status </label>
						<select class="form-control status" name="status">
							<option value="">Select</option>
							<option value="1" <?php echo ($status=='1')?'selected':'';?>>Active</option>
							<option value="0" <?php echo ($status=='0')?'selected':'';?>>De-active</option>
						</select>
					</div>
					<div class="col-md-5 col-xs-12">
						<label style="display:block;">&nbsp; </label>
						<div class="searchmain">
							<input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="{{$parameters}}">
							<button type="submit" class="btn btn-primary searchButton" disabled >Search</button>
						</div>
					</div>
				</div>

			</div>
			<div class="col-sm-1">
				<label style="display:block;">&nbsp; </label>
				<button type="submit" class="btn btn-default reset" >Reset</button>
			</div>
		</div>
	</div>
</div>
		
		<!--<table>
		<tr>
		<td><a href="{{ route('add_vendor',(base64_encode(0))) }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"> Add Vendor</i></a></td>
		<td><a href="{{ $page_details['export']}}" class="btn btn-warning">Export TO CSV</a></td>
		<td><button type="button" class="btn btn-danger btnSubmitTrigger" disabled>Bulk Delete</button></td>
		<td><input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="{{$parameters}}"></td>
		<td><button type="submit" class="btn btn-primary searchButton" disabled >Search</button></td>
		<td><button type="submit" class="btn btn-default reset" >Reset</button></td>

		</tr>
		</table>-->
		

<form role="form" class="form-element multi_delete_form mt15" action="{{route('multi_delete_vendor')}}" method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
						    <th><input type="checkbox" class="check_all"></th>
							<th>Username</th>
							<th>Email</th>
							<th>Phone</th>
							<th>Update Address</th>
							<th>View Profile</th>
							<th>Is Verify</th>
							<th>Term Accepted</th>
							<th>Ratings</th>
							<th>Action</th>

							
						</tr>
					</thead>
					<tbody>
					
					  @foreach($vendors as $vendor)

						<tr>
						        <td><input type="checkbox" class="checkbox multiple_select_checkBox" name="vendor_id[]" value="{{$vendor->id}}"></td>
								<td>{{$vendor->username}}
								<br>
								{!!App\Vendor::getVendorRating($vendor->id)!!}
								</td>
								<td>{{$vendor->email}}</td>
								<td>{{$vendor->phone}}</td>
								
								<td><a href="{{route('vendor_address', [base64_encode($vendor->id)])}}"
								>
								<i class="fa fa-map"></i>
								</a></td>
								
								<td>
								    <?php 
								    $obj= new App\Vendor();
								    $return_data=$obj->getVendorDetails($vendor->id);?>
								    <i class="fa fa-eye" data-toggle="modal" data-target="#vendor_profile{{$vendor->id}}"></i>
                                            <div class="modal fade" id="vendor_profile{{$vendor->id}}" role="dialog">
                                            <div class="modal-dialog">
                                            
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                            <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">{{$return_data['username']}}' Profile</h4>
                                            </div>
                                            <div class="modal-body">
                                           <table>
                            <tr>
                            <th>First name</th>
                            <td>{{$return_data['f_name']}}</td>
                            </tr>
                                              
                        <tr>
                        <th>Last Name</th>
                        <td>{{$return_data['l_name']}}</td>
                        </tr>
                        
                        
                         <tr>
                        <th>Public name</th>
                        <td>{{$return_data['public_name']}}</td>
                        </tr>
                        
                        
                        <tr>
                        <th>Email</th>
                        <td>{{$return_data['email']}}</td>
                        </tr>
                        
                        
                        
                        <tr>
                        <th>Company Name</th>
                        <td>{{$return_data['company_name']}}</td>
                        </tr>
                        
                        <tr>
                        <th>Company  Address</th>
                        <td>{{$return_data['company_address']}}</td>
                        </tr>
                        
                        <tr>
                        <th>Company  State</th>
                        <td>{{$return_data['company_state']}}</td>
                        </tr>
                        
                         <tr>
                        <th>Company  City</th>
                        <td>{{$return_data['company_city']}}</td>
                        </tr>
                        
                         <tr>
                        <th>Company  Pincode</th>
                        <td>{{$return_data['company_pincode']}}</td>
                        </tr>
                        
                        <tr>
                        <th>Company  About</th>
                        <td>{{$return_data['company_about_us']}}</td>
                        </tr>
                        
                        
                        
                         <tr>
                        <th>Support Email</th>
                        <td>{{$return_data['support_email']}}</td>
                        </tr>
                        
                         <tr>
                        <th>Support Facebook id</th>
                        <td>{{$return_data['support_fb_id']}}</td>
                        </tr>
                        
                        <tr>
                        <th>Support twitter id</th>
                        <td>{{$return_data['support_tw_id']}}</td>
                        </tr>
                        
                        
                        <tr>
                        <th>Meta Title</th>
                        <td>{{$return_data['meta_title']}}</td>
                        </tr>
                        
                        <tr>
                        <th>Meta description</th>
                        <td>{{$return_data['meta_description']}}</td>
                        </tr>
                        
                        
                         <tr>
                        <th>Meta Keyword</th>
                        <td>{{$return_data['meta_keyword']}}</td>
                        </tr>
                        
                        
                         <tr>
                        <th>Account No</th>
                        <td>{{$return_data['ac_no']}}</td>
                        </tr>
                        
                         <tr>
                        <th>Bank name</th>
                        <td>{{$return_data['bank_name']}}</td>
                        </tr>
                        
                         <tr>
                        <th>Bank city</th>
                        <td>{{$return_data['bank_city']}}</td>
                        </tr>
                              
                        <tr>
                        <th>Bank Branch</th>
                        <td>{{$return_data['branh_name']}}</td>
                        </tr>  
                        
                        <tr>
                        <th>IFSC</th>
                        <td>{{$return_data['ifsc_code']}}</td>
                        </tr>   
                        
                        
                         <tr>
                        <th>GST </th>
                        <td>{{$return_data['gst_no']}}</td>
                        </tr> 
                        
                        <tr>
                        <th>GST FILE</th>
                        <td> 
                       	{!! App\Helpers\CustomFormHelper::support_docs('uploads/docs/gst',$return_data['gst_file']); !!}
                        </td>
                        </tr>
                        
                         <tr>
                                                    <th>PAN NO</th>
                        <td>{{$return_data['pan_no']}}</td>
                        </tr> 
                        
                         <tr>
                        <th>PAN FILE</th>
                        <td>
                             	{!! App\Helpers\CustomFormHelper::support_docs('uploads/docs/pan',$return_data['pan_file']); !!}
                        </td>
                        </tr> 
                        
                         <tr>
                        <th>Cancel Cheque</th>
                        <td> {!! App\Helpers\CustomFormHelper::support_docs('uploads/docs/cheque',$return_data['cancel_cheque_file']); !!}
                        </td>
                        </tr> 
                        
                         <tr>
                        <th>Signature</th>
                        <td> 
                       	{!! App\Helpers\CustomFormHelper::support_docs('uploads/docs/signature',$return_data['signature_file']); !!}
                        </td>
                        </tr>
                        
                        
                        <tr>
                                                    <th>PAN NO</th>
                        <td>{{$return_data['pan_no']}}</td>
                        </tr> 
                                           </table>
                                            </div>
                                          
                                            </div>
                                            
                                            </div>
                                            </div>
								</td>
								
								<td>
								<a href="{{route('vdr_verify', [base64_encode($vendor->id),base64_encode('email'),base64_encode($vendor->is_email_verify) ])}}"
								onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
								>
								<!--<img title="Email Verify" class=" <?php echo ($vendor->is_email_verify==1)?'text-lightgreen':'text-maroon';?>" style="width: 30px; max-width: inherit;" src="{{ asset('public/images/email-icon.png') }}" />-->
								<i class="fa fa-envelope  <?php echo ($vendor->is_email_verify==1)?'text-lightgreen':'text-maroon'?>"></i>
								</a>
								&nbsp;|&nbsp;
								<a href="{{route('vdr_verify', [base64_encode($vendor->id),base64_encode('phone'),base64_encode($vendor->is_phone_verify) ])}}"
								onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
								>
								<!--<img title="Phone Number Verify" class=" <?php echo ($vendor->is_phone_verify==1)?'text-lightgreen':'text-maroon'?>" style="width: 30px; max-width: inherit;" src="{{ asset('public/images/phonevrfy.png') }}" />-->
								<i class="fa fa-phone-square  <?php echo ($vendor->is_phone_verify==1)?'text-lightgreen':'text-maroon'?>"  aria-hidden="true"></i>
								</a>
								
								</td>
								
								<td>
								<a href="{{route('vdr_term_sts', [base64_encode($vendor->id),base64_encode($vendor->term_accepted) ])}}"
								onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
								>
							<!--<img title="Email Verify" class=" <?php echo ($vendor->term_accepted==1)?'text-lightgreen':'text-maroon'?>" style="width: 30px; max-width: inherit;" src="{{ asset('public/images/email-icon.png') }}" />-->
								<i class="fa fa-optin-monster  <?php echo ($vendor->term_accepted==1)?'text-lightgreen':'text-maroon'?>"></i>
								</a>
								</td>
								
								<td>
							<a href="{{route('vendor_rating', [base64_encode($vendor->id) ])}}"
								>
								<i class="fa fa-star text-warning"  aria-hidden="true"></i>
								</a>
								
								</td>
								<td>
									<a href="{{route('vdr_sts',[base64_encode($vendor->id),base64_encode($vendor->status)] )}}"
									onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
									>
									@if($vendor->status ==0)         
									<i class="fa fa-close text-red <?php echo ($vendor->status==0)?'verify_red':'green'?>" aria-hidden="true"></i> 
									@else
									<i class="fa fa-check text-green <?php echo ($vendor->status==0)?'verify_red':'verify_green'?>" aria-hidden="true"></i>  
									@endif
									</a>&nbsp;|&nbsp;
								<a title="Vendor Orders" href="{{route('vendors_order', [base64_encode($vendor->id), base64_encode(0)])}}">
                                <img title="Vendor Orders" class=" <?php echo ($vendor->is_phone_verify==1)?'text-lightgreen':'text-maroon'?>" style="width: 30px; max-width: inherit;" src="{{ asset('public/images/vndrordr.png') }}" />
                                </a>
								&nbsp; | &nbsp;	
							<a href="{{route('edit_vendor', [base64_encode(0),base64_encode($vendor->id)])}}">
								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
								</a>&nbsp;|&nbsp;
							<a href="{{route('delete_vdr', base64_encode($vendor->id))}}"
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
				
				{{ $vendors->links() }}
				
				@include('admin.includes.Common_search_and_delete') 
@endsection
