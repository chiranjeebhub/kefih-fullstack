@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])

 @section('backButtonFromPage')
 {!! ($vendor_data['status']==1)?'<h4 class="box-title"> | Status :</h4> <h4 class="text-success box-title"> Approved</h4>':'<h4 class="box-title"> | Status : </h4> <h4 class="text-danger box-title"> Pending</h4>' !!}

 		<h4 class="box-title"> | Registered from date :</h4> <h4 class="text-success box-title"> {{date('d-m-Y', strtotime($vendor_data['created_at']))}}</h4>

    <a href="javascript:void(0)" class="btn btn-default goBack pull-right">Go Back</a>
    @endsection
@section('content')
<?php 
					$parameters = Request::segment(3);
				
					$parameters_level = base64_decode($parameters);
				
					$prd = Request::segment(4);
					$id = base64_decode($prd);
				
				?>
<style>
.hideElement{
	display:block;
}.pointer{
	cursor:pointer;
}
span.remove_atr {
    margin-top: 32px;
    position: absolute;
}
table#productTable,table#up_sell_productTable,table#cross_sell_productTable {
    width: 100% !important;
}

</style>
<section class="product-nav-details">
    <div class="container">
        @if(auth()->guard('vendor')->user()->status==1)
            <span style="color: red;
    font-size: 19px;">Only General Info can be updated.</span>
            @endif
        <div class="row">
            
                <div class="col-md-3">
				
                <div class=" ">
                    <!-- Nav tabs -->
                    <div class="vtabs customvtab">
					@if(auth()->guard('vendor')->user()->status==0)
					<ul class="nav nav-tabs tabs-vertical" role="tablist">
							<li class="nav-item"> <a class="nav-link  <?php echo ($parameters_level==0)?'active show':''; ?>" data-toggle="tab" href="#general" role="tab" aria-expanded="true" aria-selected="false">General Info </a></li>
							<li class="nav-item"> <a class="nav-link  <?php echo ($parameters_level==1)?'active show':''; ?> "  data-toggle="tab" href="#categories" role="tab" aria-expanded="false" aria-selected="true">Categories Selection</a> </li>
							<li class="nav-item"> <a class="nav-link  <?php echo ($parameters_level==2)?'active show':''; ?>" data-toggle="tab"  href="#company_info" role="tab" aria-expanded="false" aria-selected="false">Company Info</a></li>
							<li class="nav-item"> <a class="nav-link  <?php echo ($parameters_level==3)?'active show':''; ?>"  data-toggle="tab" href="#support_info" role="tab" aria-expanded="false" aria-selected="true">Support Info</a> </li>
							<li class="nav-item"> <a class="nav-link  <?php echo ($parameters_level==4)?'active show':''; ?>"  data-toggle="tab" href="#seo_info" role="tab" aria-expanded="false" aria-selected="true">SEO Info</a> </li>
							<li class="nav-item"> <a class="nav-link  <?php echo ($parameters_level==5)?'active show':''; ?>"  data-toggle="tab" href="#bank_info" role="tab" aria-expanded="false" aria-selected="true">Bank Info</a> </li>
							<li class="nav-item"> <a class="nav-link  <?php echo ($parameters_level==6)?'active show':''; ?>"  data-toggle="tab" href="#tax_info" role="tab" aria-expanded="false" aria-selected="true">Tax Info</a> </li> 
							<!-- <li class="nav-item"> <a class="nav-link  <?php echo ($parameters_level==7)?'active show':''; ?>"  data-toggle="tab" href="#invoice_info" role="tab" aria-expanded="false" aria-selected="true">Invoice Info</a> </li>-->
						</ul>
					@else
				
					<ul class="nav nav-tabs tabs-vertical" role="tablist">
							<li class="nav-item"> <a class="nav-link  <?php echo ($parameters_level==0)?'active show':''; ?>" data-toggle="tab" href="#general" role="tab" aria-expanded="true" aria-selected="false">General Info </a></li>
							<li class="nav-item"> <a class="nav-link  <?php echo ($parameters_level==1)?'active show':''; ?> "  data-toggle="tab" href="#categories" role="tab" aria-expanded="false" aria-selected="true">Categories Selection</a> </li>
							<li class="nav-item"> <a class="nav-link  <?php echo ($parameters_level==2)?'active show':''; ?>" data-toggle="tab"  href="#company_info" role="tab" aria-expanded="false" aria-selected="false">Company Info</a></li>
							<li class="nav-item"> <a class="nav-link  <?php echo ($parameters_level==3)?'active show':''; ?>"  data-toggle="tab" href="#support_info" role="tab" aria-expanded="false" aria-selected="true">Support Info</a> </li>
							<li class="nav-item"> <a class="nav-link  <?php echo ($parameters_level==4)?'active show':''; ?>"  data-toggle="tab" href="#seo_info" role="tab" aria-expanded="false" aria-selected="true">SEO Info</a> </li>
							<li class="nav-item"> <a class="nav-link  <?php echo ($parameters_level==5)?'active show':''; ?>"  data-toggle="tab" href="#bank_info" role="tab" aria-expanded="false" aria-selected="true">Bank Info</a> </li>
							<li class="nav-item"> <a class="nav-link  <?php echo ($parameters_level==6)?'active show':''; ?>"  data-toggle="tab" href="#tax_info" role="tab" aria-expanded="false" aria-selected="true">Tax Info</a> </li> 
							<!-- <li class="nav-item"> <a class="nav-link  <?php echo ($parameters_level==7)?'active show':''; ?>"  data-toggle="tab" href="#invoice_info" role="tab" aria-expanded="false" aria-selected="true">Invoice Info</a> </li> -->
						</ul>
					@endif
						
                        
                    </div>
                </div>                

            </div>
                <div class="col-md-9">
                <div class="box-body">
                <!-- Tab panes -->
						
                        <div class="tab-content">
						
                            <div class="tab-pane <?php echo ($parameters_level==0)?'active show':''; ?> " id="general" role="tabpanel" aria-expanded="true">
							<form role="form" action="{{route('update_vdr_profile', [base64_encode(0),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
							  @csrf
                                <div class="pad">
                                    <h4 class="skin-purple-light">General Info</h4>
                                    <div class="fields">
										<div class="row">
										    
											<div class="col-sm-4">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_reg_id_field']); !!}
											</div>
											<div class="col-sm-4">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_f_name_field']); !!}
											</div>
											<div class="col-sm-4">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_l_name_field']); !!}
											</div>
											<div class="col-sm-4">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_user_name_field']); !!}
											</div>
											<div class="col-sm-4">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_public_name_field']); !!}
											</div>
											<div class="col-sm-4">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_email_field']); !!}
											</div>
											<div class="col-sm-4">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_phone_field']); !!}
											</div>
											<div class="col-sm-4">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vednor_gender_field']); !!}
											</div>
											
											<div class="col-sm-4">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_password_field']); !!} 
											</div>
											<div class="col-sm-4">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_profile_pic_field']); !!} 
												{!! App\Helpers\CustomFormHelper::support_image('uploads/vendor/profile_pic',$page_details['return_data']['profile_pic']); !!}
												<span class="text-warning">(Size: <?php echo Config::get('constants.size.vendor_profile_image_min'); ?>kb – <?php echo Config::get('constants.size.vendor_profile_image_max'); ?>kb <?php echo Config::get('constants.size.vendor_profile_dimensions'); ?>)</span>

											</div>
											<div class="col-sm-4">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_signature_pic_field']); !!} 
												{!! App\Helpers\CustomFormHelper::support_image('uploads/vendor/signature_pic',$page_details['return_data']['signature_pic']); !!}
												<span class="text-warning">(Size: <?php echo Config::get('constants.size.vendor_profile_image_min'); ?>kb – <?php echo Config::get('constants.size.vendor_profile_image_max'); ?>kb <?php echo Config::get('constants.size.vendor_profile_dimensions'); ?>)</span>

											</div>

									

											<div class="col-sm-12">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
											</div>
											
											
										</div>
			   
                                    </div>
                                </div>
								
								
							</form>
                            </div>

                           
							
			 <div class="tab-pane <?php echo ($parameters_level==1)?'active show':''; ?> " id="categories" role="tabpanel" aria-expanded="false">
					<form role="form" action="{{route('update_vdr_profile', [base64_encode(1),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
					@csrf
					
					<div class="pad">
					   <h4 class="skin-purple-light">Categories</h4>
						<div class="simpleListContainer clearfix">
						<div class="row">
						<ul id="simple_list">
						{!! App\Helpers\CommonHelper::getChildsTreeView(1,$page_details['return_data']['category']); !!}
						</ul>
						</div>
						</div>
						</div>
                                    @if(auth()->guard('vendor')->user()->status==0)
                                    {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
                                    @endif()
						
					
					</form>
			</div>
					

			<div class="tab-pane <?php echo ($parameters_level==2)?'active show':''; ?> " id="company_info" role="tabpanel" aria-expanded="false">                               
					<form role="form" id="editCompany" action="{{route('update_vdr_profile', [base64_encode(2),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
					@csrf                                  
						<div class="pad">
						<h4 class="skin-purple-light">Company Info</h4>
							<div class="fields">
								<div class="row">
									<div class="col-sm-4">
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_company_name_field']); !!}
									</div>
									<div class="col-sm-4">
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_company_logo_field']); !!}
										{!! App\Helpers\CustomFormHelper::support_image('uploads/vendor/company_logo',$page_details['return_data']['company_logo']); !!}
										<span class="text-warning">(Size: <?php echo Config::get('constants.size.vendor_company_logo_min'); ?>kb – <?php echo Config::get('constants.size.vendor_company_logo_max'); ?>kb)</span>

									</div>
									
									<div class="col-sm-4">
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_company_address_field']); !!}
									</div>
									<div class="col-sm-4">
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_company_state_field']); !!}
									</div>
									<div class="col-sm-4">
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_company_city_field']); !!}
									</div>
									<div class="col-sm-4">
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_company_pincode_field']); !!}
									</div>
									<div class="col-sm-4">
									{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_company_type_field']); !!}
									</div>
									<div class="col-sm-4">
									{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_pannumber_field']); !!}
									</div>
									<div class="col-sm-4">
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_pancard_field']); !!}
										{!! App\Helpers\CustomFormHelper::support_image('uploads/vendor/company_logo',$page_details['return_data']['pancard']); !!}
									</div>
									<div class="col-sm-4">
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_adharcard_field']); !!}
										{!! App\Helpers\CustomFormHelper::support_image('uploads/vendor/company_logo',$page_details['return_data']['adharcard']); !!}
									</div>
									<div class="col-sm-4">
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_address_proof_field']); !!}
										{!! App\Helpers\CustomFormHelper::support_image('uploads/vendor/company_logo',$page_details['return_data']['address_proof']); !!}
									</div>
									<div class="col-sm-4">
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_certificate_field']); !!}
										{!! App\Helpers\CustomFormHelper::support_image('uploads/vendor/company_logo',$page_details['return_data']['certificate']); !!}
									</div>
									<div class="col-sm-4">
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_other_field']); !!}
										{!! App\Helpers\CustomFormHelper::support_image('uploads/vendor/company_logo',$page_details['return_data']['other_documents']); !!}
									</div>
									
									<div class="col-sm-12">
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_company_about_field']); !!}
									</div>
									
								<div class="col-sm-4" style="display:none">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_tax_type_field']); !!}
								<div class="col-sm-4">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_tax_rate_field']); !!} 
								</div>  
								</div>
									<div class="col-sm-4">
									<!--{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}-->
									@if(auth()->guard('vendor')->user()->status==0)
                                    {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
                                    @endif()
									</div>
								</div>
				
							</div>
						</div>
					
				



					</form>
				</div>	

				
		<div class="tab-pane <?php echo ($parameters_level==3)?'active show':''; ?> " id="support_info" role="tabpanel" aria-expanded="false">                               
			<form role="form" action="{{route('update_vdr_profile', [base64_encode(3),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
			@csrf                                  
				<div class="pad">
				<h4 class="skin-purple-light">Support  Info</h4>
					<div class="fields">
						<div class="row">
							<div class="col-sm-6">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_support_number_field']); !!}
							</div>
							<div class="col-sm-6">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_support_email_field']); !!}
							</div>
							<div class="col-sm-6">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_support_fb_field']); !!}
								<span class="text-warning" >Ex: https://www.facebook.com/yourpagename/</span>
							</div>
							<div class="col-sm-6">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_support_tw_field']); !!}
								<span class="text-warning" >Ex: 	https://twitter.com/yourpagename</span>
							</div>
							<div class="col-sm-12">
							@if(auth()->guard('vendor')->user()->status==0)
                                    {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
                                    @endif()
							</div>
						</div>
											
					</div>
				</div>
			
			</form>
		</div>
		
		
		<div class="tab-pane <?php echo ($parameters_level==4)?'active show':''; ?> " id="seo_info" role="tabpanel" aria-expanded="false">                               
			<form role="form" action="{{route('update_vdr_profile', [base64_encode(4),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
			@csrf                                  
				<div class="pad">
				<h4 class="skin-purple-light">SEO  Info</h4>
					<div class="fields">
						<div class="row">
							<div class="col-sm-12">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendoe_seo_meta_title_field']); !!}
							</div>
							<div class="col-sm-6">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendoe_seo_meta_description_field']); !!}
							</div>
							<div class="col-sm-6">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendoe_seo_meta_keyword_field']); !!}
							</div>
							<div class="col-sm-12">
							@if(auth()->guard('vendor')->user()->status==0)
                                    {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
                                    @endif()
							</div>
						</div>
												
					</div>
				</div>
			
			</form>
		</div>
		
		<div class="tab-pane <?php echo ($parameters_level==5)?'active show':''; ?> " id="bank_info" role="tabpanel" aria-expanded="false">                               
			<form role="form" action="{{route('update_vdr_profile', [base64_encode(5),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
			@csrf            

			@if(@$bank_ac_details->id !== null)
			<input type="hidden" name="bid" value="{{base64_encode($bank_ac_details->id)}}">    
			@endif

				<div class="pad">
				<h4 class="skin-purple-light">Bank Info</h4>
					<div class="fields">
						<div class="row">
							<div class="col-sm-6">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_bank_ac_holder_name_field']); !!}
							</div>
							<div class="col-sm-6">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_bank_ac_no_field']); !!}
							</div>
							<div class="col-sm-6">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_bank_name_field']); !!}
							</div>
							<div class="col-sm-4">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_bank_branch_name_field']); !!}
							</div>
							<div class="col-sm-4">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_bank_city_field']); !!}	
							</div>
							<div class="col-sm-4">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_bank_ifsc_field']); !!}	
							</div>
							
								<div class="col-sm-6">
							    	{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_cheque_file_field']); !!}
							    {!! App\Helpers\CustomFormHelper::support_docs('uploads/docs/cheque',$page_details['Form_data']['Form_field']['vendor_cheque_file_field']['value']); !!}
								<span class="text-warning">(Size: <?php echo Config::get('constants.size.vendor_cancel_cheque_image_min'); ?>kb – <?php echo Config::get('constants.size.vendor_cancel_cheque_image_max'); ?>kb)</span>

							  </div>
							<div class="col-sm-12">
							    {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
						<!--	@if(auth()->guard('vendor')->user()->status==0)
                                    {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
                                    @endif() -->
							</div>
						</div>
						
					</div>
				</div>
			
			</form>
			<div>
			<table id="example1" class="table table-bordered table-striped dataTable no-footer" role="grid">
				<thead>

					<tr>
						<th>Account Holder Name</th>
						<th>Account Number</th>
						<th>Bank Name</th>
						<th>Branch Name</th>
						<th>IFSC Code</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($page_details['return_data']['data'] as $bankData)
					<tr>
						<td>{{$bankData->ac_holder_name}}</td>
						<td>{{$bankData->account_no}}</td>
						<td>{{$bankData->name}}</td>
						<td>{{$bankData->branch}}</td>
						<td>{{$bankData->ifsc_code}}</td>
						<td><a title="Delete" href="{{route('delete_bank', base64_encode($bankData->id))}}" onclick = "if (! confirm('Do you want to delete ?')) { return false; }">
								<i class="fa fa-trash text-red" aria-hidden="true"></i>
								</a>

								| 

								<a title="Edit Bank Info" href="{{route('update_vdr_profile', [base64_encode(5),base64_encode($id)])}}?bid={{base64_encode($bankData->id)}}">
								<i class="fa fa-pencil text-success" aria-hidden="true"></i>
								</a>
							
							
							</td>
					
					</tr>
					@endforeach
				</tbody>				
						 </table>
					</div>
		</div>
		
		<div class="tab-pane <?php echo ($parameters_level==6)?'active show':''; ?> " id="tax_info" role="tabpanel" aria-expanded="false">                               
			<form role="form" action="{{route('update_vdr_profile', [base64_encode(6),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
			@csrf                                  
				<div class="pad">
				<h4 class="skin-purple-light">Tax  Info</h4>
					<div class="fields">
						<div class="row">
							<div class="col-sm-6">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_gst_field']); !!}
							</div>
							<div class="col-sm-6">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_gst_file_field']); !!}
								{!! App\Helpers\CustomFormHelper::support_docs('uploads/docs/gst',$page_details['Form_data']['Form_field']['vendor_gst_file_field']['value']); !!}
								<span class="text-warning">(Size: <?php echo Config::get('constants.size.vendor_gst_image_min'); ?>kb – <?php echo Config::get('constants.size.vendor_gst_image_max'); ?>kb)</span>

							</div>
							<div class="col-sm-6" style="display:none;">
								
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_pan_field']); !!}
							</div>
							<div class="col-sm-6 hideElement" style="display:none;">
								
								
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_pan_file_field']); !!}
								{!! App\Helpers\CustomFormHelper::support_docs('uploads/docs/pan',$page_details['Form_data']['Form_field']['vendor_pan_file_field']['value']); !!}
								<span class="text-warning">(Size: <?php echo Config::get('constants.size.vendor_pan_image_min'); ?>kb – <?php echo Config::get('constants.size.vendor_pan_image_max'); ?>kb)</span>

								
							</div>
							
							  
							  <div class="col-sm-6">
                                {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_signature_file_field']); !!}
								{!! App\Helpers\CustomFormHelper::support_docs('uploads/docs/signature',$page_details['Form_data']['Form_field']['vendor_signature_file_field']['value']); !!}
								<span class="text-warning">(Size: <?php echo Config::get('constants.size.vendor_sign_image_min'); ?>kb – <?php echo Config::get('constants.size.vendor_sign_image_max'); ?>kb)</span>

                            </div>
							<div class="col-sm-12">
							@if(auth()->guard('vendor')->user()->status==0)
                                    {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
                                    @endif()
							</div>
						</div>
			
					</div>
				</div>
			
			</form>
		</div>
		
		<div class="tab-pane <?php echo ($parameters_level==7)?'active show':''; ?> " id="invoice_info" role="tabpanel" aria-expanded="false">                               
			<form role="form" action="{{route('update_vdr_profile', [base64_encode(7),base64_encode($id)])}}" method="post" enctype="multipart/form-data">
			@csrf                                  
				<div class="pad">
				<h4 class="skin-purple-light">Invoice Details</h4>
					<div class="fields">
						<div class="row">
							<div class="col-sm-6">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_invoice_address_field']); !!}
							</div>
							<div class="col-sm-6">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_invoice_logo_field']); !!}
							</div>
							<div class="col-sm-12">
                                    @if(auth()->guard('vendor')->user()->status==0)
                                    {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
                                    @endif()
							</div>
						</div>
				
					</div>
				</div>
			
			</form>
		</div>
							
						                      
                        </div>
						
                </div>
            </div>
            
        </div>
    </div>
</section>

<link rel="stylesheet" href=" {{ asset('public/css/imageuploadify.min.css') }}">
<script src="{{ asset('public/js/validateform.js') }}"></script> 
<script type="text/javascript" src="{{ asset('public/js/imageuploadify.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('public/js/jsLists.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('input[type="file"]').imageuploadify();
    });
	JSLists.applyToList('simple_list', 'ALL');
</script>	
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
	  $.validator.addMethod("pan", function(value, element)
    {
        return this.optional(element) || /^[A-Z]{5}\d{4}[A-Z]{1}$/.test(value);
    }, "Invalid Pan Number");
$('#editCompany').validate({ // initialize the plugin
	rules: {
            "pannumber": {pan: true},
        }, 
 
});
</script>
@endsection
