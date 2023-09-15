@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
	
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
				<a href="javascript:void(0)"
				class="btn btn-success"
					data-toggle="modal" data-target="#myModal"
				><i class="fa fa-plus" aria-hidden="true"
			
				></i> Add Product</a>
			</div>

			<div class="col-sm-12 text-right">
				<a href="{{ $page_details['export']}}" class="btn btn-warning">Export TO CSV</a> &nbsp; 
				<a href="javacsript:void()"class="btn btn-primary btn-lg resetModalForm" data-toggle="modal" data-target="#importMOdal"> Import Product</a> &nbsp;
				<!--<a class="btn btn-info" href="{{ asset('public/products.csv') }}" download>Sample CSV</a>-->
			</div>

			<div class="col-sm-12 mt15">
				<ul class="list-inline disable-btn-ul">
					<li>
						<button type="button" class="btn btn-danger btnSubmitTrigger commonClassDisableButton" disabled>Bulk Delete</button>
					</li>
						
					
					<li>
						<button type="button" class="btn btn-danger addtooffer commonClassDisableButton" data-toggle="modal" data-target="#offerModal" disabled>Add products To offer</button>
					</li>
					
					<li>
                        <form role="form" action="{{route('bulkActiveProduct')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                        <input type="hidden" value="" name="product_ids" class="product_id">
                        
                        <div class="col-md-6">
                        <button type="submit" class="btn btn-danger bulkActiveButtton commonClassDisableButton" disabled>Bulk Active</button>
                        </div>
                        
                        </div>
                        </form>
                
						
					</li>
                       
				</ul>
				
				<br>
				 <?php #<input type="hidden" value="43" id="ProductVendor"> ?>
					<div class="row searchmain-row">
					    
					    <div class="col-sm-8 col-md-2">
					        <select class="form-control fdProductVendor" id="ProductVendor" multiple>
					            <option value="All" <?php echo ($vendor_id=='All')?"selected":""; ?> >Select Vendor</option>
					            @foreach($vendors as $row)
					                <option value="{{$row->id}}" <?php echo (in_array($row->id, $selectvendor) )?"selected":""; ?>>{{$row->name}}</option>
					            @endforeach
					        </select>
                        </div>
        
     <!--                  <div class="col-sm-8 col-md-3">-->
					<!--	<div class="searchmain">-->
						  
					<!--	</div>-->

					<!--</div>-->
					<div class="col-sm-8 col-md-2">
						<div class="searchmain productLst">
						   
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
					
					
						<div class="col-sm-8 col-md-2">
						<div class="searchmain productLst">
						    <input type="hidden" class="ProductType" value="0">
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
					
					<div class="col-sm-8 col-md-2">
                        <div class="searchmain productLst">
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
						
				
        <div class="col-sm-8 col-md-2">
        {!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['select_field']); !!}
        </div>
					<div class="col-sm-9  col-md-4">
						<div class="searchmain productLst">
<input type="text" class="form-control ProductSearchString" placeholder="Search By ID & Name" id="searchBox" value="<?php echo ($parameters!='All')?$parameters:"";?>">
						<button type="submit" class="btn btn-primary ProductSearch"  id="searchButton" >Search</button>	
						</div>

					</div>
           
                    
                       
					
					<div class="col-sm-3 col-md-1">
						<button type="submit" class="btn btn-default reset"
						>Reset</button>
					</div>
					   </div>
			</div>
		</div>

	
		
<div class="modal fade prdtCSV" id="importMOdal" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="modalLabelLarge">Import From CSV</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        </div>
<div class="row">
        <div class="col-md-12">
        
        <div class="modal-body">
			<h4 class="modal-title" id="modalLabelLarge">Import Product</h4>
<form role="form" class="form-element resetAbleForm" action="{{ route('bulk_upload_product') }}" method="post" enctype="multipart/form-data">
			   @csrf
					
					
					
					<div class="form-group">
					  <select name="vendor" class="form-control" required>
                            <?php 
                            foreach($vendors as $vendor){
                            ?>
                            <option value="{{$vendor->id}}">{{$vendor->name}} {{($vendor->registration_id)?'('.$vendor->registration_id.')':''}}</option>
                            
                            <?php }?>
                        </select>
					</div>
					
                      
                
					<div class="form-group">
						<label for="exampleInputEmail1">Product Type</label>
						<select name="product_type" class="form-control" >
							<option value="1">Simple Product</option>
							<option value="3">Configurable Product</option>
								<!--<option value="2">Unisex Products</option>-->
						</select>
					</div>
		    
					<div class="form-group">
					<label for="exampleInputEmail1">Choose CSV</label>
					<input type="file" accept="csv/*"  name="csv" class="form-control" required>
					</div>
			
	

		
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}

<a href="{{ asset('public/Simple_product.csv') }}">Simple Product Upload CSV</a>&nbsp;|&nbsp;<a href="{{ asset('public/Configurable_product.csv') }}">Configurable Product Upload CSV</a>
						  
 </form>
</div>
        </div>
        
        <!--<div class="col-md-6">
         
         <div class="modal-body">
			 <h4 class="modal-title" id="modalLabelLarge">Import General & Description</h4>
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
<form role="form" class="form-element multi_delete_form mt15" action="{{route('multi_delete_product')}}" method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
						  <th><input type="checkbox" class="check_all"></th>
                            <th>Sr No</th>
                            <th>Id</th>
                            <th>HSN Code</th>
							<th>Name</th>
							<th>Vendor Id</th>
							<th>Vendor Name</th>
						    <th>Category</th>
                            <!--<th>Blocked</th>-->
							<th>Price</th>
							<!--<<th>SKU</th>-->
							<!-- <th>GTIN</th> -->
							<!--<th>QA</th>-->
							<th>Extra Description/General</th>
							<th>Reviews</th>
							<!--<th>Status</th>-->
							<th>Added Date</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
					
					  @foreach($products as $product)
								
						<tr>
						 <td><input type="checkbox" class="checkbox multiple_select_checkBox checkedProduct" name="product_id[]" value="{{$product->id}}"></td>
								<td style="width:20%">{{$loop->iteration}}</td>
								<td style="width:20%">{{$product->id}}</td>
								<td style="width:20%">{{$product->hsn_code}}</td>
								<td style="width:20%">{{$product->name}}</td>
								<td style="width:20%">{{$product->vendor_id}}</td>
								<td style="width:20%">{{$product->uname}}</td>
									<td>
								 ({!!App\ProductCategories::getProductcategoryName($product->id)!!})
								</td>
								<!--<td>
                                    @if($product->isblocked ==0)         
                                    <span class="btn btn-prima needToblockAndUnblock" 
                                    data-toggle="modal" 
                                    blockProductId="{{$product->id}}"
                                    blockedproductMode="1"
                                    data-target="#ProductBlockedReason"
                                    >Block </span>
                                    @else
                                    <span 
                                    class="btn btn-danger needToblockAndUnblock" 
                                    blockProductId="{{$product->id}}"
                                    blockedproductMode="0"
                                    data-toggle="modal" 
                                    data-target="#ProductBlockedReason"
                                    >Unblock</span>
                                    @endif
								</td>-->
								<td>
									Price : {!!$product->price !!}
									Listing Price : {!!$product->spcl_price !!}
								</td>
								<!--<td>
								<?php 
								$multiskus = DB::table('product_attributes')->where('product_id',$product->id)->get();
								?>
								 @foreach($multiskus as $multisku)
										<?php echo $multisku->sku.','; ?>
								@endforeach
								</td>-->
								<!-- <td>{{$product->gtin}}</td> -->
								<!--<td>
								    <a href="{{route('qa',base64_encode($product->id))}}">
								    <span class="badge badge-primary"><i class="fa fa-question-circle" aria-hidden="true"></i></span>
								    </a>
								    </td>-->
								    <td>
								    <a href="{{route('product_description',base64_encode($product->id))}}" title="Extra Description">
								    <span class="badge badge-primary"><i class="fa fa-files-o" aria-hidden="true"></i></span>
								    </a>
									<a href="{{route('product_general',base64_encode($product->id))}}" title="General">
								    <span class="badge badge-primary"><i class="fa fa-file-text" aria-hidden="true"></i></span>
								    </a>
								    </td>
								    
								     
                                <td>
                                @if(App\Products::productReviews($product->id)>0)
                                 <span class="badge badge-warning"> <a href="{{route('preview',base64_encode($product->id))}}">{{App\Products::productReviews($product->id)}}</a></span>
                               
                                @else
                                <span class="badge badge-warning">0</span>
                                @endif
                                </td>
                                
									<!--<td style="width:20%"> @if($product->qty > 0) Stock @else Out Stock @endif</td>-->
									<td style="width:20%">{{ \Carbon\Carbon::parse($product->created_at)->format('d/m/Y')}}</td>
								
							<td>
							    
							    <a href="{{route('addStock', [base64_encode($product->id)])}}">Add Stock</a>
							    	&nbsp;|&nbsp;
							<a href="{{route('prd_sts',[base64_encode($product->id),base64_encode($product->status)] )}}"
									onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
									>
										@if($product->status ==0)         
										<i class="fa fa-close text-red" aria-hidden="true"></i> 
										@else
										<i class="fa fa-check text-green" aria-hidden="true"></i>  
										@endif
									</a>
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
                       <select name="product_vendor" class="form-control" required>
                            <?php 
                            foreach($vendors as $vendor){
                            ?>
                            <option value="{{$vendor->id}}">{{$vendor->name}} {{($vendor->registration_id)?'('.$vendor->registration_id.')':''}}</option>
                            
                            <?php }?>
                </select>
                   
                    </div>
                     <div class="col-md-6">
                      
                    <select name="product_type" class="form-control" required>
                    <option value="1">Simple Product </option>
                    	<!--<option value="2">Unisex Products</option>-->
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
        <div class="modal" id="offerModal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Select Offer Type</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
     <div class="modal-body">
              <form role="form" action="{{route('save_offer_product')}}" method="post" enctype="multipart/form-data">
        @csrf
            <div class="row">
                <input type="hidden" value="" name="product_ids" class="product_id">
                <div class="col-md-6">
                    <select name="product_type" class="form-control">
                    <option value="0">slider 1</option>
                    <option value="1">slider 2</option>
					<option value="2">slider 3</option>
					<option value="3">slider 4</option>
                      <!-- <option value="5">Customized Products </option> -->
                    <!--<option value="2">Also Bought</option>-->
                    <!-- <option value="4">Offer going on</option>-->
                    </select>
                </div>
                <div class="col-md-6">
                     <input type="submit" value="Save" class="btn btn-primary">
                </div>
               
            </div>
        </form>
       
        </div>
      

    </div>
  </div>
</div>



                    <div class="modal fade" id="ProductBlockedReason" role="dialog">
                    <div class="modal-dialog">
                    
                    <!-- Modal content-->
                    <div class="modal-content">
                        <form role="form" action="{{route('bl_nblk_Product')}}" method="post" enctype="multipart/form-data">
                        @csrf
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Reason</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-6">
                        <input type="text" name="reason" class="form-control"  required value="" placeholder="Give a Reason">
                         <input type="hidden" name="product_id"  required value="0" id="blockProductId">
                         <input type="hidden" name="method"  required value="0" id="blockedproductMode">
                        </div>
                    </div>
                    <div class="modal-footer">
                     <input type="submit" value="Save" class="btn btn-primary">
                    </div>
                    </form>
                    </div>
                    
                    </div>
                    </div>


				@include('admin.includes.Common_search_and_delete') 
				
@endsection
