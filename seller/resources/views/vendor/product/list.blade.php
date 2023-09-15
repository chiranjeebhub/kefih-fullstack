@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
<style>
.scrollTheDiv {
    max-height: 250px;
    overflow: scroll;
}
</style>
	<?php 
        $parameters =Request()->str;
        $vendor_id =Request()->vendor;
        $sts =Request()->sts;
        $type =Request()->type;
        
          $Brand_ids=Request()->brands;
            $ProductBlocked=Request()->blocked;
            $selectBrands=array();
             $selectvendor=array();
             
    if($Brand_ids!='All' &&  $Brand_ids!=''){
    $selectBrands=explode(",",$Brand_ids);
    } 
		
        if($vendor_id!='All' &&  $vendor_id!=''){
        $selectvendor=explode(",",$vendor_id);
        } 
		
	?>
		<!--{{ route('add_product',(base64_encode(0))) }}-->
		<div class="">
			<div class="allbutntbl">
			<button type="button" class="btn btn-warning btn-lg" data-toggle="modal" data-target="#productTypeModal">+Add Product</button> 
			<button class="btn btn-primary btn-lg resetModalForm" data-toggle="modal" data-target="#importMOdal"> Bulk Upload Product</button> &nbsp;
			</div>
            <div class="col-sm-12">
            <div class="row">
            	<div class="col-sm-2">
            		<button type="button" class="btn btn-danger btnSubmitTrigger commonClassDisableButton" disabled>Bulk Delete</button>
            	</div>
            	     
            </div>
				
				<br>
					<div class="row">
					    
					   
                       <div class="col-sm-3" style="display:none">
						<div class=" ">
							<select class="form-control ProductVendor" id="ProductVendor">
							    <option value="{{auth()->guard('vendor')->user()->id}}">All Vendor</option>
							     
							</select>
						</div>

					</div>
					
					<div class="col-sm-8 col-md-3">
						<div class="">
						   <lable>Choose Status</lable>
							<select class="form-control ProductStatus">
							    <option value="All"
							     <?php echo ($sts=='All')?"selected":""; ?>
							    >Activated/Deactivated</option>
							     <option value="1"
							      <?php echo ($sts=='1')?"selected":""; ?>
							     >Activated</option>
							      <option value="0"
							       <?php echo ($sts=='0')?"selected":""; ?>
							      >Deactivated</option>
							</select>
						</div>

					</div>
				<div class="col-sm-2 col-md-2">
				    
						<div class=" ">
						    	<lable>Choose New/Existing</lable>
							<select class="form-control ProductType">
							    <option value="All"
							      <?php echo ($type=='All')?"selected":""; ?>
							    >New/Existing</option>
							     <option value="0"
							      <?php echo ($type=='0')?"selected":""; ?>
							     >New</option>
							      <option value="1"
							       <?php echo ($type=='1')?"selected":""; ?>
							      >Existing</option>
							</select>
						</div>

					</div>
					
					<div class="col-sm-8 col-md-2">
						<div class=" ">
						    <lable>Blocked/Non blocked</lable>
							<select class="form-control ProductBlocked" id="ProductBlocked">
							    <option value="All"
							      <?php echo ($ProductBlocked=='All')?"selected":""; ?>
							    >Blocked/Non blocked</option>
							     <option value="0"
							      <?php echo ($ProductBlocked=='0')?"selected":""; ?>
							     >Non blocked</option>
							      <option value="1"
							       <?php echo ($ProductBlocked=='1')?"selected":""; ?>
							      >Blocked</option>
							</select>
						</div>

					</div>
					
					
					
					
					<div class="col-sm-6 col-md-2">
					      <lable>Choose category</lable>
        {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['select_field']); !!}
        </div>
       
        
        	<div class="col-sm-8 col-md-2">
                        <div class=" ">
                               <lable>Choose Brands</lable>
                        <select class="selectpicker" id="brands" multiple data-live-search="true">
                            <option value="" selected>Select Brand</option>
							    <?php 
							    foreach($Brands as $Brand){
							    ?>
<option value="{{$Brand->id}}" 
<?php echo (in_array($Brand->id, $selectBrands) )?"selected":""; ?>
>{{$Brand->name}}</option>
							     
							    <?php }?>
                        </select>
                        
                        </div>
                        
                        </div>
				
					<div class="col-sm-3">
						<div class=" ">
<input type="text" class="form-control ProductSearchString" placeholder="Search" value="<?php echo ($parameters!='All')?$parameters:"";?>">
							
						</div>

					</div>
            <div class="col-sm-1">            
            <button type="submit" class="btn btn-primary ProductSearch"  >Search</button>
            </div>
					<div class="col-sm-1">
						<button type="submit" class="btn btn-default reset"
						>Reset</button>
					</div>
					   </div>
			</div>
		</div>
<div class=" ">

  
  
 


<form role="form" class="form-element multi_delete_form mt15" action="{{route('multi_delete_product')}}" method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
						  <th><input type="checkbox" class="check_all"></th>
						  <th>Sr. No.</th>
                            <th>Id</th>
							<th>HSN Code</th>
							<th>Name</th>
							 <th>Category</th>
                            <!--<th>Blocked</th>-->
							<th>Price</th>
							<th>Listing Price</th>
							<!--<th>SKU</th>-->
					      	<!--<th>QA</th>-->
					      	<th>Add Extra Description</th>
					      	<th>Add General</th>
							<th>Action</th>

						</tr>
					</thead>
					<tbody>
				
					  @foreach($products as $key=>$product)

						<tr>
						 <td><input type="checkbox" class="checkbox multiple_select_checkBox checkedProduct" name="product_id[]" value="{{$product->id}}"></td>
						 <td style="width:20%">{{++$key}}</td>		
						 <td style="width:20%">{{$product->id}}</td>
						 <td style="width:20%">{{$product->hsn_code}}</td>
								<td style="width:20%">
								     <!--@if($product->isexisting == 1)-->
								     <!--<span class="badge badge-info">Existing</span><br>-->
								     <!--@endif-->
								     
								    {{$product->name}}
								    
								</td>
									<td>
								 ({!!App\ProductCategories::getProductcategoryName($product->id)!!})
								</td>
								<!--<td>
                                    @if($product->isblocked ==0)         
                                   Unblocked 
                                    @else
                                   Blocked
                                    @endif
                                    
                                    @if($product->isblocked ==1) 
                                    Reason :
                                    <?php $bl_product=DB::table('blocked_product_log')->where('id',$product->id)->first();
                                    if($bl_product){
                                       echo $bl_product->reason; 
                                    }
                                    ?>
                                    @endif
								</td>-->
								<td>{!!$product->price !!}</td>
								<td>{!!$product->spcl_price !!}</td>
								<!--<td><?php 
								$multiskus = DB::table('product_attributes')->where('product_id',$product->id)->get();
								?>
								 @foreach($multiskus as $multisku)
										<?php echo $multisku->sku.','; ?>
								@endforeach</td>-->
                                
									<!--<td>
								    <a href="{{route('qa',base64_encode($product->id))}}">
								<i class="fa fa-question-circle" aria-hidden="true"></i>
								    </a></td>-->
								
								    <td>
								    <a href="{{route('product_description',base64_encode($product->id))}}">
									<i class="badge badge-primary" aria-hidden="true"></i>
								    </a>
								    </td>
								    
								    <td>
								    <a href="{{route('product_general',base64_encode($product->id))}}">
									<i class="fa fa-file-text" aria-hidden="true"></i>
								    </a>
								    </td>
								
							<td>
							    
                                @if($product->status==0)
                                Not activated 
                                @else
                                Activated 
                                @endif
                                &nbsp;|&nbsp;
                                 <a href="{{route('addStock', [base64_encode($product->id)])}}">Add Stock</a>
							    	&nbsp;|&nbsp;
						   
							<a href="{{route('edit_product', [base64_encode(0),base64_encode($product->id)])}}"
							onclick = "if (! confirm('Do you want to edit ?')) { return false; }"
							>
								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
								</a>&nbsp;|&nbsp;
						    
						
							<a href="{{route('delete_prd', base64_encode($product->id))}}"
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
				{{ $products->links() }}
			
			
			  <div class="modal fade" id="productTypeModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Prduct Type</h4>
        </div>
        <div class="modal-body">
		<a class="btn btn-info btn-lg" href="{{ route('existing_product') }}">Existing Product</a>
		<a class="btn btn-info btn-lg" href="javascript:void(0)" 	data-toggle="modal" data-target="#myModal">Add New Product</a>
        </div>
        
      </div>
      
    </div>
  </div>
  
  
  <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
        
        <!-- Modal content-->
        <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Select Product Type</h4>
        </div>
        <div class="modal-body">
              <form role="form" action="{{route('productSelection')}}" method="post" enctype="multipart/form-data">
        @csrf
            <div class="row">
      <div class="col-md-6">
                    <input type="hidden" name="product_vendor" value="{{auth()->guard('vendor')->user()->id}}">
                    <select name="product_type" class="form-control">
                    <option value="1">Simple Product </option>
                    <!--<option value="2">Attributes Product </option>-->
                    <option value="3">Configurable Product</option>
                    </select>
                </div>
                <div class="col-md-6">
                     <input type="submit" value="Next" class="btn btn-primary">
                </div>
               
            </div>
        </form>
       
        </div>
        </div>
        
        </div>
        </div>
        
        <div class="modal fade" id="importMOdal" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="modal-content">

    <div class="modal-header">
    <h4 class="modal-title" id="modalLabelLarge">Import From CSV</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>

<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <form role="form" class="form-element resetAbleForm" action="{{ route('bulk_upload_product') }}" method="post" enctype="multipart/form-data">
			   @csrf
					
					<div class="form-group">
					    <input type="hidden" name="vendor" value="{{auth()->guard('vendor')->user()->id}}">
						<label for="exampleInputEmail1">Product Type</label>
						<select name="product_type" class="form-control" >
							<option value="1">Simple Product</option>
							<option value="3">Configurable Product</option>
						</select>
					</div>
		    
					<div class="form-group">
					<label for="exampleInputEmail1">Choose CSV</label>
					<input type="file" accept="csv/*"  name="csv" class="form-control" required>
					</div>
			
	

		
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}

<a href="<?php echo Config::get('constants.Url.public_url'); ?>public/Simple_product.csv">Simple Product Upload CSV</a>&nbsp;|&nbsp;
<a href="<?php echo Config::get('constants.Url.public_url'); ?>public/Configurable_product.csv">Configurable Product Upload CSV</a>
						  
 </form>
            </div>
            
             <!--<div class="col-md-6">
         <h4 class="modal-title" id="modalLabelLarge">Import General & Description</h4>
         <div class="modal-body">

				<form role="form" class="form-element resetAbleForm" action="{{ route('bulk_upload_product_description_and_general') }}" method="post" enctype="multipart/form-data">
							@csrf
									
									<div class="form-group">
									<label for="exampleInputEmail1">Choose CSV</label>
									<input type="file" accept="csv/*"  name="csv" class="form-control" required>
									</div>
							
					

						
						{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}

				<a href="{{ asset('public/general_and_description.csv') }}">Sample CSV</a>
										
				</form>
				</div>
        </div>-->
            </div>


</div>

</div>
</div>
</div>

</div>
	@include('admin.includes.Common_search_and_delete') 
@endsection