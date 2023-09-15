@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')

    <?php 
    /*
    $parameters = Request::segment(3);
    $str = Request::segment(3);
	$daterange = Request::segment(4);
    $parameters_level = base64_decode($parameters);
    */
    ?>
<style>
.nav-tabs>li>a {
    /* margin-right: 2px; */
    line-height: 1.42857143;
    /* border: 1px solid transparent; */
    border-radius: 4px 40px 8px 0 !important;
}
</style>

<?php /*if(!Auth::guard('vendor')->check()){*/ ?>
<div class="">
	<!--<div class="allbutntbl">
		<a href="#" class="btn btn-warning">Export TO CSV</a>
	</div>-->
	<div class="col-sm-12">
		<div class="row">
				<div class="col-sm-12 text-right">
				<a href="{{ $page_details['export']}}" class="btn btn-warning">Export TO CSV</a> &nbsp; 
				</div>
			<div class="col-sm-2">
				<button type="submit" class="btn btn-default reset" >Reset</button>
			</div>
				<form class="col-sm-10" action="{{$page_details['search_route']}}" method="POST">
@csrf
			<div class="col-sm-10">
				<div class="row">
					<div class="col-sm-3">
						<!--<label>Select Date Range</label>-->
						<input type="text" name="daterange" class="form-control daterange" placeholder="Select Dates" 
						value="<?php echo ($daterange!='' && $daterange!='All')?
						str_replace('--','/',$daterange):"";?>">
					</div>
					<div class="col-sm-6">
						<!--<label style="color:#fff;">&nbsp; &nbsp; &nbsp; &nbsp; </label>-->
						<div class="searchmain floatnone">
							<input type="text" name="search_string" class="form-control searchString" placeholder="Search" 
							value="<?php echo ($str!='' && $str!='All')?$str:"";?>">
							<button type="submit" class="btn btn-danger vendorLedger" >Search</button>
						</div>
					</div>
                  
				</div>
				
			</div>
			</form>
			
		</div>
	</div>
	
</div>
<?php /*} */?>

<div class="mt15">
	<div class="tab-content" id="new_order">
  <div class="tab-pane fade show active" id="new_order" role="tabpanel" aria-labelledby="new_order">
  
  				<div class="table-responsive">
				
					<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Vendor ID</th>
							<th>Vendor Name</th>
							<th>GST No.</th>
							<th>Sale Amt</th>
							<th>Commission Fee	</th>
							<th>Delivery Charge</th>
							<!--<th>Reverse Shipping Charge	</th>-->					
							<th>Net Amt</th>
							<th>Paid Amt</th>
							<th>Balance Amt</th>
							<th>TCS Amt</th>
							<th>TDS Tax</th>
							<th>IGST Amt</th>
							<th>CGST Amt</th>
							<th>SGST Amt</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
					<?php $i=1;  ?>
					  @foreach($ledgers as $row)
					  <?php
							$igst=App\Http\Controllers\sws_Admin\VendorLedgerController::tax_type($row->id,1,'','');
							$cgst_sgst=App\Http\Controllers\sws_Admin\VendorLedgerController::tax_type($row->id,2,'',''); 
							$paid_amount=DB::table('tbl_vendor_payment')->select(
							                                DB::raw('SUM(vendor_payment_amt) AS total_paid_amt')
							                            )->where('vendor_id',$row->id)->first();
							                            
							/*$tcs_amt=DB::table('tbl_settings')->select('tcs_tax_percentage')->where('id',1)->first();
							
							$tcs_amt=number_format((($row->total_product_amt*$tcs_amt->tcs_tax_percentage)/100),2);*/
							$tcs_amt=$row->tcs_amt;
							$tds_amt=$row->tds_amt;
					  $check_info=DB::table('vendor_company_info')->select('name as company_name')->where('vendor_id',$row->id)->first();
					  $reverseShippingAmount = App\Helpers\CommonHelper::calculateVendorReverseShippingCharge($row->id);
					  
					  /**
					   * Sale amount = sale amount - (delivery charge + reverse shipping charge + commission).
					   */
					  $totalCommissionAndShippingCharge = $row->total_commission_amt + $row->total_order_shipping_charges + $reverseShippingAmount;
					  $netAmountsale = $row->total_product_amt-$totalCommissionAndShippingCharge;
					  ?>
					 	<tr>
							<td>{{$i++}}</td>
							<td>{{$row->id}}</td>
							<td>{{$check_info->company_name}}</td>
							<td>{{$row->gst_no}}</td>
						    <td>{{$row->total_product_amt}}</td> 					
							<td>{{number_format($row->total_commission_amt,2)}}</td>
							<!--<td>{{number_format($row->total_order_shipping_charges,2)}}</td>-->
							<td>{{ $reverseShippingAmount}}</td>					
							<td>{{number_format($netAmountsale,2)}}</td>
							<td>{{$paid_amount->total_paid_amt}}</td>
							<td>{{ $netAmountsale - $paid_amount->total_paid_amt  }}</td>
							<td>{{number_format($tcs_amt,2)}}</td>
							<td>{{number_format($tds_amt,2)}}</td>
							<td><?php echo number_format((@$igst->order_detail_tax_amt),2);?></td>
							<td><?php echo number_format((@$cgst_sgst->order_detail_tax_amt/2),2);?></td>
							<td><?php echo number_format((@$cgst_sgst->order_detail_tax_amt/2),2);?></td>
							<td>
								<?php if(Auth::guard('vendor')->check()){ ?>
								<a href="{{ route('vledger_detail',base64_encode($row->id)) }}" class="btn btn-success btn-small">View Sale</a>
								<!--<a href="{{ route('vvendor_payment',base64_encode($row->id)) }}" class="btn btn-success btn-small">Payment History </a>-->
								<?php }else{ ?>
								<a href="{{ route('ledger_detail',base64_encode($row->id)) }}" class="btn btn-success btn-small">View Sale </a>
								<!--<a href="{{ route('vendor_pay',base64_encode($row->id)) }}" class="btn btn-success btn-small">Pay </a>
								<a href="{{ route('vendor_payment',base64_encode($row->id)) }}" class="btn btn-success btn-small">Payment History </a>-->
								<?php }?>
								
								<!--<a href="{{ route('vendor_order_tcs_generate_invoice',base64_encode($row->id)) }}" class="btn btn-success btn-small">Generate TCS/TDS Settlement Ledger </a>
								<a href="{{ route('vendor_order_tcs_print_invoice_list',base64_encode($row->id)) }}" class="btn btn-success btn-small">View TCS/TDS Settlement Ledger </a>-->
							</td>
						</tr>
					    @endforeach
					</tbody>
				  </table>
				</div>
				
				{{ $ledgers->links() }}
				
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
