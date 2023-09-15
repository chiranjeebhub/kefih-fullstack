@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')

    <?php 
      $vendor_id =Request()->vendor;
     
    $parameters_level=0;
    ?>
<style>
.nav-tabs>li>a {
    /* margin-right: 2px; */
    line-height: 1.42857143;
    /* border: 1px solid transparent; */
    border-radius: 4px 40px 8px 0 !important;
}.searchBtn{
    right: 3px;
    top: 2px;
    padding: 4px 15px;
position: absolute;
	line-height: 22px;
/*background:#0c6cd5 !important;*/
opacity: 1 !important;

}
</style>

<div class="">
	<div class="allbutntbl">
		<!--<a href="{{ $page_details['export']}}" class="btn btn-warning">Export TO CSV</a>-->
	</div>
	<div class="col-sm-12">
	@if(Session::has('flash_success'))
<p class="alert alert-info">{{ Session::get('flash_success') }}</p>
@endif
		<div class="row">
	
			<div class="col-sm-2">
			    <form method="get" action="">
				<button type="submit" class="btn btn-default reset" >Reset</button>
				</form>
			</div>
			<div class="col-sm-8">
			<form method="get" action="" class="row">
                        
			<div class="col-sm-3 col-md-4">
							<input type="text" name="daterange" class="form-control daterange" placeholder="Select Date" value="<?php echo ($daterange!='')?$daterange:'';?>">
						</div>
					
			<div class="col-sm-6 col-md-6">
							<div class="searchmain">
							<input type="text" name="search_string" class="form-control search_string" placeholder="Search Invoice No." value="<?php echo ($searchterm!='')?$searchterm:'';?>">
								<button type="submit" class="btn btn-primary searchBtn sOrdersearch"  >Search</button>
							</div>
						</div>
			</form>
		</div>
		</div>
	</div>
</div>


 <div class="col-sm-12 mt15">
<div class="tab-content" id="new_order">
  <div class="tab-pane fade show active" id="new_order" role="tabpanel" aria-labelledby="new_order">
  
  				<div class="table-responsive">
				   
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Vendor Name</th>
							<th>Address</th>
							<th>Invoice Date</th>
							<th>Invoice No.</th>
							<th></th>
							
						</tr>
					</thead>
                        <tbody>
                            <?php 
                            $i=1; 
                            foreach($orders as $order){?>
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$vdr_data['company_name']}}</td>
                                <td>{{$vdr_data['company_address']}},  {{$vdr_data['company_state']}} {{$vdr_data['company_city']}} {{$vdr_data['company_pincode']}}</td>
    							<td><?php echo date("d M Y ", strtotime($order->tcs_invoice_date)) ?></td>
    							<td> {{$order->tcs_invoice_no}}</td>
    						    <td> 
                                    <a href="{{ route('vendor_order_tcs_print_invoice',[base64_encode($order->vendor_id),base64_encode($order->tcs_invoice_no)]) }}" class="btn btn-success btn-small">Print TCS/TDS Settlement Invoice </a>
									<a href="{{ route('vendor_order_tcs_print_vendor_invoice',[base64_encode($order->vendor_id),base64_encode($order->tcs_invoice_no),'true']) }}" class="btn btn-success btn-small">Seller Commission Invoice </a>

								</td>
							</tr>
                            <?php $i++;}?>
                        
                        </tbody>
					    </table>
					     
				</div>
				{{ $orders->links() }}
  </div>
  
 
</div>
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
      $(this).val(picker.startDate.format('DD-MM-YYYY') + '.' + picker.endDate.format('DD-MM-YYYY'));
	  $('.daterange').trigger('change');
  });

  $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
  });

});
</script>

 
@endsection
