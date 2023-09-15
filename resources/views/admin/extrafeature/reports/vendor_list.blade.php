@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])

@section('content')

<div class="row">
<?php 
$usid = Request::segment(3);
 ?>

<style>
.box-header{height:60px;}
</style>
 <div class="col-12 col-sm-12">
 <div class="row">
		    <div class="col-6 col-sm-6 col-md-7">
	<h4 class="box-title">
	@if (!Auth::guard('vendor')->check()) 
		@if(base64_decode($usid)==0) Highest selling product @endif
		@if(base64_decode($usid)==1) Location wise products selling @endif
		@if(base64_decode($usid)==2) Refund or replaced orders @endif
		@if(base64_decode($usid)==3) Best Vendors @endif
	@endif
	</h4>
	</div>
	  <div class="col-6 col-sm-6 col-md-5">
	<div class="allbutntbl" style="top: 0px;"> 
			<a href="{{$page_details['export_route']}}" class="btn btn-warning">Export</a> &nbsp; 
			<a href="{{route('dashboard')}}" class="btn btn-default goBack">Go Back</a>
	</div>
	</div>
	</div>
		<div class="row mt15">
		    <div class="col-sm-4">
				<input type="hidden" id="category_id" value="">
				<label>Select Date Range</label>
				<input type="text" name="daterange" class="form-control daterange" placeholder="Select Date" value="<?php echo ($daterange!='All')? str_replace('.','/',$daterange):"";?>">
			</div>
			<div class="col-sm-4">
				<label>Select Category</label>
        		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['select_field']); !!}
        
			</div>
			<div class="col-sm-2">
				<label style="display:block;">&nbsp; </label>
                <button type="button" class="btn btn-primary reportFilter"  >Filter</button>
			</div>
			<div class="col-sm-2">
				<label style="display:block;">&nbsp; </label>
				<button type="button" class="btn btn-default reset" >Reset</button>
			</div>
			
		</div>
	
			<!-- /.col -->
			<div class="mt15 row">
			  <div class="box bg-transparent no-border no-shadow">
			 
			 <div class="tab-content">
<div id="newaorder" class="container-fluid tab-pane active">
 @if(count($Order))
        		<!-- /.box-header -->
				<div class="box-body b-1">
				
				
				  <div class="mailbox-read-message">
				  
					  <div class="row">
					
					 	  <table id="table2excel" class="table table-bordered table-striped noExl">
					<thead>
						<tr>
							<th>Sr.No</th>
							<th>Vendor ID</th>
							<th>Vendor Name</th>
							<th>Category</th>
							<th>All sales</th>
							<th>Total sales</th>
							<!--<th>Total</th>-->
							
						</tr>
					</thead>
					<tbody>
					<?php $total=0;?>
					
					  @foreach($Order as $row)
					 
						<tr>
							<td>{{$loop->iteration}}</td>
							<td>
								{{$row['id']}}
							</td>
							<td>
								{{$row['username']}}
							</td>
								<td>
								 ({!!App\ProductCategories::getProductcategoryName($row->id)!!})
							</td>

							<td>
								 {!! App\Helpers\CommonHelper::totalVendorsales($daterange,$cat, $row->id)!!}
							</td>


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
 @endsection

