@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
<?php 
				$parameters = Request::segment(3);
			
				$parameters_level = base64_decode($parameters);
		
				
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
</
</style>
<section class="product-nav-details">
    <div class="container">
        <div class="row">
        
                <div class="col-md-9">
                <div class="box-body">
                <!-- Tab panes -->
							
                        <div class="tab-content">
						
                            
							
							
							
                            <div class="tab-pane active show " id="images" role="tabpanel" aria-expanded="false">
								<form role="form" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
									@csrf                                
								<div class="pad">
								   <h4 class="skin-purple-light">Images</h4>
								<div class="mb15">
								<input type="file" accept="image/*" multiple name="images[]">
                                <span class="text-warning">(Size: <?php echo Config::get('constants.size.product_img_min'); ?>kb – <?php echo Config::get('constants.size.product_img_max'); ?>kb <?php echo Config::get('constants.size.product_img_dimensions'); ?> )</span>

								</div>
								</div>
								{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
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
@include('admin.includes.product_script') 

@endsection
