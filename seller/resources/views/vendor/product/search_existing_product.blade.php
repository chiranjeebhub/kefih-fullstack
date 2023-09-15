@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
 @section('backButtonFromPage')
    <a href="javascript:void(0)" class="btn btn-default goBack">Go Back</a>
    @endsection
@section('content')
	
<style>
.scrollTheDiv {
    max-height: 250px;
    overflow: scroll;
}
.add_atr{
	display:none
}
</style>
		
			<table>
			<tr>
			<td><input class="form-control" id="search" name="search" type="text" placeholder="Search.."></td>
			</tr>
			</table>
		
				
				<div class="table-responsive">
						<table class="table table-bordered table-striped">
						<thead>
						<tr>
						<th>Product Name</th>
						<th>Price</th>
						<th>Action</th>
						</tr>
						</thead>
						<tbody id="myTablePage" class="tbody">
						</tbody>

						</table>
				</div>
				
				 
				 
	
<div class="modal fade" id="product_form" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Product Details</h4>
        </div>
        <div class="modal-body">
  <div style="display:none" class="alert alert-success" id="susmsg">
  <strong id="success"></strong>
  </div> 
  
	<form class="form-horizontal productForm" action="{{URL::to('admin/addSellProduct')}}"  method="post">
	@csrf
	<!--<input type="hidden" id="vendorId" name="vendor_id">-->
	<input type="hidden" id="product_id" name="product_id">
	<div class="text-danger" style="display:none" id="product_error">
        <strong id="product_error_msg"></strong>
    </div>
	
	
	<div class="row">
	
	<div class="col-md-4">
	{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_price_field']); !!}  
	<div class="text-danger" style="display:none" id="price_error">
        <strong id="price_error_msg"></strong>
    </div>
	</div>
	
		<div class="col-md-4">
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_qty_field']); !!} 
		<div class="text-danger" style="display:none" id="qty_error">
		<strong id="qty_error_msg"></strong>
		</div>
		</div>
		
		<div class="col-md-4">
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_spcl_price_field']); !!} 
		<div class="text-danger" style="display:none" id="qty_error">
		<strong id="qty_error_msg"></strong>
		</div>
		</div>
		
		
		
		<div class="col-md-4">
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_manage_stock_field']); !!} 
		<div class="text-danger" style="display:none" id="qty_error">
		<strong id="qty_error_msg"></strong>
		</div>
		</div>
		
		<div class="col-md-4">
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_qty_for_out_stock_field']); !!} 
		<div class="text-danger" style="display:none" id="qty_error">
		<strong id="qty_error_msg"></strong>
		</div>
		</div>
		
		
		<div class="col-md-4">
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['product_stock_availability_field']); !!} 
		<div class="text-danger" style="display:none" id="qty_error">
		<strong id="qty_error_msg"></strong>
		</div>
		</div>
	
	
	</div>
	
	<!--<div class="fields">-->
	<!--				<div class="atr_group">-->



	<!--				</div>-->
				
	<!--			<span class="pointer add_atr"><i class="fa fa-plus"></i>Add Color & Size</span>                                                      -->
                                     
									 
                                        
 <!--                                   </div>-->
  
	
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
	  <input type="submit" name="submit" value="Submit" class="btn btn-default ajaxSubmitButton">
      </div>
    </div>
	<span id="errors"></span>
	</form>
        </div>
      </div> 
    </div>
  </div>
		@include('admin.includes.product_script') 		
@endsection
