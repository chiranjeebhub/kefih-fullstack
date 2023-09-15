@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
<?php $total=0; ?>
<div class="row">

	<div class="allbutntbl">
		<a href="{{route('ledger') }}" class="btn btn-warning"><i class="fa fa-undo"></i> Back</a>
	</div>
			<!-- /.col -->
			<div class="col-lg-12 col-12">
				<div class="row">
					<div class="col-sm-2">
						<button type="submit" class="btn btn-default reset" >Reset</button>
					</div>
					<div class="col-sm-10">
						<div class="row">
							<div class="col-md-3 offset-md-4">
								<input type="text" name="daterange" class="form-control daterange" placeholder="19/10/2019 - 25/10/2019" value="<?php echo str_replace('--','/',$daterange);?>">
							</div>
							<div class="col-md-5">
								<div class="searchmain">
									<input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="" style="display:none;">
									<button type="submit" class="btn btn-primary searchButton" disabled >Search</button>
								</div>
							</div>
						</div>
						
					</div>
					
				</div>
			  <div class="box bg-transparent no-border no-shadow">
			
				<!-- /.box-header -->
				<div class="box-body b-1">
				
				  <div class="mailbox-read-info">
					<h3>Ledger </h3>
				  </div>
				  <div class="mailbox-read-info clearfix">
					<div class="left-float margin-r-5">
				
					</div>
					  <h5 class="no-margin"> Vendor Info</h5><br>
						 <small>{{$ledger_details[0]['public_name']}}</small><br>
						 <small>{{$ledger_details[0]['email']}}</small><br>
						 <small>{{$ledger_details[0]['phone']}}</small>
						 
				  </div>
				
				  <div class="mailbox-read-message">
					  <div class="row">
					 	<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr.No</th>
							<th>Order Date</th>
							<th>Status</th>
							<th>Product Name</th>
							<th>Qty</th>
							<th>Price</th>
							<th>Total</th>
							<th>Commission Amt</th>
						</tr>
					</thead>
					<tbody>
					<?php $i=1; $total_commission=0; ?>
					  @foreach($ledger_details as $row)
						<tr>
							<td>{{$i++}}</td>
							<td>{{date('d-m-Y',strtotime($row['order_date']))}}</td>
							<td>&nbsp;</td>
							<td>{{$row['product_name']}}
							<br>{{ isset($row['size'])?'Size: '.$row['size']:''}}
							<br>{{isset($row['color'])?'Color: '.$row['color']:''}}
							</td>
							<td>{{$row['product_qty']}}</td>
							<td>{{$row['product_price']}}</td>
							<td>{{$row['product_qty']*$row['product_price']}}</td>
							<td>{{$commission_amt=round((($row['product_qty']*$row['product_price'])*$row['order_commission_rate'])/100)}}</td>
						</tr>
						<?php $total+=$row['product_qty']*$row['product_price'];
								$total_commission+=$commission_amt;
						?>
					    @endforeach
					</tbody>
					
				  </table>
					  </div>
				   <div class="box-footer" style="float: right;">
					<h5> Total <span>Rs. ({{$total}})</span> <span>Rs. ({{$total_commission}})</span></h5>
					<br>
					<h5> Net Total <span>Rs. ({{$total-$total_commission}})</span></h5>
					</div>
					
				<br>
				
				  </div>
				  
				  
				  <!-- /.mailbox-read-message -->
				</div>
				<!-- /.box-body -->
		
				
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

