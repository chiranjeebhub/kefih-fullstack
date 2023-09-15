@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])

@section('content')

<?php 
$usid = Request::segment(3);
 ?>
<style>
.box-header{height:60px;}
</style>
 <div class="">
	<h4 class="box-title" style="top: 30px;position: absolute;">
	@if (!Auth::guard('vendor')->check()) 
		@if(base64_decode($usid)==0) Highest Selling Product @endif
		@if(base64_decode($usid)==1) Location wise Products selling @endif
		@if(base64_decode($usid)==2) Refund or Replaced Orders @endif
	@endif
	</h4>
	<div class="allbutntbl"> 
			<a href="{{$page_details['export_route']}}" class="btn btn-warning">Export</a> &nbsp; 
			<a href="{{route('dashboard')}}" class="btn btn-default goBack">Go Back</a>
	</div>
	<div class="col-sm-12">
		<div class="row">
		    <div class="col-sm-4">
				<label>Select Date Range</label>
        		<input type="text" name="daterange" class="form-control daterange" placeholder="Select Date" value="<?php echo ($daterange!='All')? str_replace('.','/',$daterange):"";?>">
        
			</div>
			<div class="col-sm-1">
				<label style="display:block;">&nbsp; </label>
                <button type="button" class="btn btn-primary reportFilter"  >Filter</button>
			</div>
			<div class="col-sm-1">
				<label style="display:block;">&nbsp; </label>
            	<button type="button" class="btn btn-default reset" >Reset</button>
			</div>
			<div class="col-sm-6">
			</div>
		</div>
	</div>
</div>
     
			<!-- /.col -->
	

			<div class="mt15">
			  <div class="box bg-transparent no-border no-shadow">
			 
			 <div class="tab-content">
<div id="newaorder" class="container-fluid tab-pane active">
 @if(count($Order))
            	<!-- /.box-header -->
				<div class="box-body b-1">
				  <div class="mailbox-read-message">
				  
					  <div class="row table-responsive">
					
					 	  <table id="table2excel" class="table table-bordered table-striped noExl">
					<thead>
						<tr>
                            <th>Sr.No</th>
							<th>Product ID</th>
							<th>Vendor ID</th>
                            <th>Product Name</th>
                             <th>Category</th>
                                @if(base64_decode($usid)==1)
								<th>City</th>
                                <th>Pincode</th>
                                @endif
                            <th>Seller</th>
                            <th>Product SKU</th>
                            <th>Total sales</th>
                            <!--<th>Total</th>-->
							
						</tr>
					</thead>
					<tbody>
					<?php $total=0;?>
					
					  @foreach($Order as $row)
					
						<tr>
								<td>{{$loop->iteration}}</td>
								<td>{{$row->id}}</td>
								<td>{{$row->vendor_id}}</td>
								<td>
								<?php 
								
								$producttype=DB::table('products')->where('id',$row->id)->first();
								if($producttype->product_type=='1')
										{
											$imagespathfolder='uploads/products/'.$row->vendor_id.'/'.$row['sku'];
											$productimages=DB::table('product_images')->where('product_id',$row->id)->first();
											$images=$imagespathfolder.'/'.$productimages->image;

										}else{
											$productskuid=DB::table('product_attributes')->where('product_id',$row->id)->first();
											$imagespathfolder='uploads/products/'.$row->vendor_id.'/'.$productskuid->sku;
											$productimages=DB::table('product_configuration_images')->where('product_id',$row->id)->first();
											$images=$imagespathfolder.'/'.$productimages->product_config_image;
										} ?>
								<img src="{{URL::to($images)}}" style="height:50px; width:50px;">
								
								<br>{{$row['name']}}
								<br>{{ isset($row['size'])?'Size: '.$row['size']:''}}
								<br>{{isset($row['color'])?'Color: '.$row['color']:''}}
								</td>
									<td>
								 ({!!App\ProductCategories::getProductcategoryName($row['id'])!!})
								</td>
								
								 @if(base64_decode($usid)==1)
								 	<td>
                                @if($row['order_shipping_zip'])
                                <span class="badge bg-danger">{{$row['order_shipping_city']}}</span>
                                <br>	
                                @else
                                <span  class="badge bg-danger">NAN</span>
                                @endif
                                </td>
								<td>
                                @if($row['order_shipping_zip'])
                                <span class="badge bg-danger">{{$row['order_shipping_zip']}}</span>
                                <br>	
                                @else
                                <span  class="badge bg-danger">NAN</span>
                                @endif
                                </td>
								
                                @endif
                                
								<td>
								    <?php 
								$seller_name=DB::table('vendors')->where('id',$row['vendor_id'])->first();
								echo $seller_name->public_name;
								    ?>
								    </td>
								<td>{{$row['sku']}}</td>
								<td>{{$row['total_sales']}}</td>
								
								<!--<td>Rs. {{$row['total_price'] + $row['order_shipping_charges']}}</td>-->
								
								
							
						</tr>
						<?php $total+=$row['total_price'] + $row['order_shipping_charges'];?>
						  
					    @endforeach
					  
					</tbody>
					
				  </table>
				    
				  
					  </div>
				  {{$Order->links()}}
				<!-- <div class="box-footer" style="float: right;">-->
				 
					
				<!--	<h5> Grand Total <span>Rs. {{round($total)}}</span></h5>-->
				<!--</div>-->
				
				  </div>
				  
				  
				  <!-- /.mailbox-read-message -->
				</div>
				<!-- /.box-body -->
		@endif		
		 </div>

		  </div>
				
			  </div>
			  <!-- /. box -->
			</div>
			<!-- /.col -->
		  </div>
		  <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

<script type="text/javascript">
$(function() {

  $('input[name="daterange"]').daterangepicker({
      autoUpdateInput: false,
      locale: {
          cancelLabel: 'Clear',
		  format: 'DD-MM-YYYY'
      }
  });

  $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD-MM-YYYY') + '/' + picker.endDate.format('DD-MM-YYYY'));
	  $('.daterange').trigger('change');
  });

  $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
  });

});
</script>
        @include('admin.includes.Common_search_and_delete')
        <!--@include('admin.includes.exportscript') -->
 @endsection

