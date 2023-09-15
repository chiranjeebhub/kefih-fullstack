@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
<?php 
				$parameters = Request::segment(3);
			
				$parameters_level = base64_decode($parameters);
			
				
				?>
<style>
.hideElement1{
	display:none;
}.hideElement{
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
</
</style>
<section class="product-nav-details">
    <div class="container">
        <div class="row">
            
                <div class="col-md-3">
				
               
                
                
                <div class=" ">
                    <!-- Nav tabs -->
                    <div class="vtabs customvtab">
					
						<ul class="nav nav-tabs tabs-vertical" role="tablist">
							<li class="nav-item"> <a class="nav-link  <?php echo ($parameters_level==0)?'active show':'disabled'; ?> " data-toggle="<?php echo ($parameters_level==0)?'tab':''; ?>" href="<?php echo ($parameters_level==0)?'#general':'JavaScript:void(0);'; ?>" role="tab" aria-expanded="true" aria-selected="false">General Info</a></li>
							<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==1)?'active show':'disabled'; ?>"  data-toggle="<?php echo ($parameters_level==1)?'tab':''; ?>" href="<?php echo ($parameters_level==1)?'#categories':'JavaScript:void(0);'; ?>" role="tab" aria-expanded="false" aria-selected="true">Categories Selection</a> </li>
							<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==2)?'active show':'disabled'; ?>" data-toggle="<?php echo ($parameters_level==2)?'tab':''; ?>"  href="<?php echo ($parameters_level==2)?'#company_info':'JavaScript:void(0);'; ?>" role="tab" aria-expanded="false" aria-selected="false">Company Info</a></li>
							<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==3)?'active show':'disabled'; ?>"  data-toggle="<?php echo ($parameters_level==3)?'tab':''; ?>" href="<?php echo ($parameters_level==3)?'#support_info':'JavaScript:void(0);'; ?>" role="tab" aria-expanded="false" aria-selected="true">Support Info</a> </li>
							<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==4)?'active show':'disabled'; ?>"  data-toggle="<?php echo ($parameters_level==4)?'tab':''; ?>" href="<?php echo ($parameters_level==4)?'#seo_info':'JavaScript:void(0);'; ?>" role="tab" aria-expanded="false" aria-selected="true">SEO Info</a> </li>
							 <li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==5)?'active show':'disabled'; ?>"  data-toggle="<?php echo ($parameters_level==5)?'tab':''; ?>" href="<?php echo ($parameters_level==5)?'#bank_info':'JavaScript:void(0);'; ?>" role="tab" aria-expanded="false" aria-selected="true">BANK Info</a> </li>
							<!--<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==6)?'active show':'disabled'; ?>"  data-toggle="<?php echo ($parameters_level==6)?'tab':''; ?>" href="<?php echo ($parameters_level==6)?'#tax_info':'JavaScript:void(0);'; ?>" role="tab" aria-expanded="false" aria-selected="true">Tax Info</a> </li>
							<li class="nav-item"> <a class="nav-link <?php echo ($parameters_level==7)?'active show':'disabled'; ?>"  data-toggle="<?php echo ($parameters_level==7)?'tab':''; ?>" href="<?php echo ($parameters_level==7)?'#invoice_info':'JavaScript:void(0);'; ?>" role="tab" aria-expanded="false" aria-selected="true">Invoice Info</a> </li>-->
						</ul>
                        
                    </div>
                </div>                

            </div>
                <div class="col-md-9">
                <div class="box-body">
                <!-- Tab panes -->
							
                        <div class="tab-content">
						
                            <div class="tab-pane <?php echo ($parameters_level==0)?'active show':''; ?> " id="general" role="tabpanel" aria-expanded="true">
							<form role="form" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
							  @csrf
                                <div class="pad">
                                    <h4 class="skin-purple-light">General Info</h4>
                                    <div class="fields">
										<div class="row">
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
											<div class="col-sm-6">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_profile_pic_field']); !!} 
											</div>
											<div class="col-sm-6">
												{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_signature_pic_field']); !!} 
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
					<form role="form" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
					@csrf
					
					<div class="pad">
					   <h4 class="skin-purple-light">Categories</h4>
						<div class="simpleListContainer clearfix">
							<div class="row">
								<ul id="simple_list">
								{!! App\Helpers\CommonHelper::getChildsTreeView(1,array()); !!}
								</ul>
							</div>
						</div>
						</div>
					{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
					</form>
			</div>
					

			<div class="tab-pane <?php echo ($parameters_level==2)?'active show':''; ?> " id="company_info" role="tabpanel" aria-expanded="false">                               
					<form role="form" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data" id="addCompany">
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
										<div class="col-sm-12">
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_company_about_field']); !!}
									</div>
									<div class="col-sm-4 hideElement1">
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_tax_type_field']); !!} 
									</div>
									
									<div class="col-sm-4 hideElement1">
										<div class="hidefields">
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_tax_rate_field']); !!} 
										</div>
									</div>
									<div class="col-sm-4">
									{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_company_type_field']); !!}
									</div>
									<div class="col-sm-4">
									{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_pannumber_field']); !!}
									</div>
									<div class="col-sm-4">
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_pancard_field']); !!}
									
									</div>
									<div class="col-sm-4">
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_adharcard_field']); !!}
									
									</div>
									<div class="col-sm-4">
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_address_proof_field']); !!}
								
									</div>
									<div class="col-sm-4">
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_certificate_field']); !!}
								
									</div>
									<div class="col-sm-4">
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_other_field']); !!}
								
									</div>
									<div class="col-sm-12">
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
									</div>
									
									</div>
							</div>
						</div>
					
					</form>
				</div>	

				
		<div class="tab-pane <?php echo ($parameters_level==3)?'active show':''; ?> " id="support_info" role="tabpanel" aria-expanded="false">                               
			<form role="form" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
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
							</div>
							<div class="col-sm-6">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_support_tw_field']); !!}
							</div>
							<div class="col-sm-12">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
							</div>
							
						</div>
					</div>
				</div>
			
			</form>
		</div>
		
		
		<div class="tab-pane <?php echo ($parameters_level==4)?'active show':''; ?> " id="metaInfo" role="tabpanel" aria-expanded="false">                               
			<form role="form" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
			@csrf                                  
				<div class="pad">
				<h4 class="skin-purple-light">SEO  Info</h4>
					<div class="fields">
						<div class="row">
							<div class="col-sm-12">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_seo_meta_title_field']); !!}
							</div>
							<div class="col-sm-6">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_seo_meta_description_field']); !!}
							</div>
							<div class="col-sm-6">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_seo_meta_keyword_field']); !!}
							</div>
							<div class="col-sm-12">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
							</div>
					
						</div>
					</div>
				</div>
			
			</form>
		</div>
		
			<div class="tab-pane <?php echo ($parameters_level==5)?'active show':''; ?> " id="bank_info" role="tabpanel" aria-expanded="false">                               
			<form role="form" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
			@csrf                                  
				<div class="pad">
				<h4 class="skin-purple-light">Bank  Info</h4>
					<div class="fields">
						<div class="row">
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
							  </div>
							<div class="col-sm-12">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
							</div>
						</div>
							
					</div>
				</div>
			
			</form>
		</div>
		
		<div class="tab-pane <?php echo ($parameters_level==6)?'active show':''; ?> " id="tax_info" role="tabpanel" aria-expanded="false">                               
			<form role="form" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
			@csrf                                  
				<div class="pad">
				<h4 class="skin-purple-light">Tax  Info</h4>
					<div class="fields">
						<div class="row">
							<div class="col-sm-6">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_gst_field']); !!}
							</div>
							<div class="col-sm-6">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_pan_field']); !!}
							</div>
							<div class="col-sm-6">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_gst_file_field']); !!}
							</div>
							<div class="col-sm-6">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_pan_file_field']); !!}
							</div>
							
							<div class="col-sm-6">
							 {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['vendor_signature_file_field']); !!}
							</div>
							<div class="col-sm-12">
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
							</div>
				
						</div>
					</div>
				</div>
			
			</form>
		</div>
		
		<div class="tab-pane <?php echo ($parameters_level==7)?'active show':''; ?> " id="invoice_info" role="tabpanel" aria-expanded="false">                               
			<form role="form" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
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
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
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
$('#addCompany').validate({ // initialize the plugin
	rules: {
            "pannumber": {pan: true},
        }, 
 
});
</script>
@endsection
