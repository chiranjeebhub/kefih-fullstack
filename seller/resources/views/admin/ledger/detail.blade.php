@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
 @section('backButtonFromPage')
    <a href="{{route('vledger') }}" class="btn btn-default goBack">Go Back</a>
    @endsection
@section('content')
<?php $total=0; ?>
<div class="row">
                    <?php if(!Auth::guard('vendor')->check()){ ?>
                    <div class="col-sm-12 text-right">
                    <a href="{{ $page_details['export']}}" class="btn btn-warning">Export TO CSV</a> &nbsp;
                    </div>
	<div class="allbutntbl">
		<a href="{{route('vledger') }}" class="btn btn-warning"><i class="fa fa-undo"></i> Back</a>
	</div>
	<?php } ?>
			<!-- /.col -->
			<div class="col-lg-12 col-12">
				<?php if(!Auth::guard('vendor')->check()){ ?>
				<div class="row">
					<div class="col-sm-2">
						<button type="submit" class="btn btn-default reset" >Reset</button>
					</div>
					<div class="col-sm-10">
						<div class="row">
							<div class="col-md-4">
						<input type="text" name="daterange" class="form-control daterange" placeholder="Select Dates"
						value="<?php echo ($daterange!='' && $daterange!='All')?
						str_replace('--','/',$daterange):"";?>">

							</div>
							<div class="col-md-1" style="padding:0px;" >
								<div class="searchmain">
									<input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="" style="display:none;">
									<button type="submit" class="btn btn-primary ledgerDetails" >Search</button>
								</div>
							</div>
						</div>

					</div>

				</div>
				<?php } ?>
			  <div class="box bg-transparent no-border no-shadow">

				<!-- /.box-header -->
				<div class="box-body b-1 mt15">

				  <!--<div class="mailbox-read-info">
					<h3>Ledger </h3>
				  </div>-->
				  <div class="mailbox-read-info ledgerBox clearfix">
					<div class="left-float margin-r-5">

					</div>
					  <div class="vendorinfobox">
					  	<h5 class="no-margin"> Vendor Info</h5><br>
						 <p><i class="fa fa-user"></i> {{$vendor_info->public_name}}</p>
						 <p><i class="fa fa-envelope"></i> {{$vendor_info->email}}</p>
						 <p><i class="fa fa-phone"></i> {{$vendor_info->phone}}</p>
					  </div>

				  </div>

				  <div class="mailbox-read-message">
					  <div class="row">
                          <table id="example1" class="table table-bordered table-striped">
                              <thead>
                              <tr>
                                  <th>Sr.No</th>
                                  <th>Master Order ID</th>
                                  <th>Sub Order ID</th>
                                  <th>Order Date</th>
                                  <th>SKU</th>
                                  <th>Product Name</th>
                                  <th>Qty</th>
                                  <th>Price</th>
                                  <th>Delivery Charge</th>
                                  <th>Reverse Shipping Charge</th>
                                  <th>Total</th>
                                  <th>Jaldi Kharido Fee</th>
                                  <th>TCS Amt</th>
                                  <th>TDS Amt</th>
                                  <th>IGST Amt</th>
                                  <th>CGST Amt</th>
                                  <th>SGST Amt</th>
                              </tr>
                              </thead>
                              <tbody>
                              <?php $i=1; $total_commission=0; ?>
                              @foreach($ledger_details as $row)
                                  <?php
                                  $igst=$cgst_sgst=0;
                                  $tax_type=$row['order_detail_invoice_type'];
                                  if($tax_type==1) //IGST
                                  {
                                      $igst=$row['order_detail_tax_amt'];
                                  }
                                  if($tax_type==2) //CGST/SGST
                                  {
                                      $cgst_sgst=$row['order_detail_tax_amt'];
                                  }

                                  /*$tcs_amt=DB::table('tbl_settings')->select('tcs_tax_percentage')->where('id',1)->first();

                                  $tcs_amt=number_format(((($row['product_qty']*$row['product_price'])*$tcs_amt->tcs_tax_percentage)/100),2);*/
                                  $tcs_amt=$row['tcs_amt'];
                                  $tds_amt=$row['tds_amt'];
                                  ?>
                                  <tr>
                                      <td>{{$i++}}</td>
                                      <td>{{$row['masterID']}}</td>
                                      <td>{{$row['subID']}}</td>
                                      <td>{{date('d-m-Y',strtotime($row['order_date']))}}</td>
                                      <td>{{$row['sku']}}</td>
                                      <td>{{$row['product_name']}}
                                          <br>{{ isset($row['size'])?'Size: '.$row['size']:''}}
                                          <br>{{isset($row['color'])?'Color: '.$row['color']:''}}
                                      </td>
                                      <td>{{$row['product_qty']}}</td>
                                      <td>{{$row['product_price']}}</td>
                                      <td>{{$row['order_vendor_shipping_charges']}}</td>
                                      <td>@if($row['order_status'] == 6  || $row['order_status'] == 5) {{$row['reverse_order_shipping_charge']}} @else {{0}} @endif</td>
                                      <td>{{$row['product_qty']*$row['product_price']}}</td>
                                      <td>{{$commission_amt=number_format(((($row['product_qty']*$row['product_price'])*$row['order_commission_rate'])/100),2)}}</td>
                                      <td>{{$tcs_amt}}</td>
                                      <td>{{$tds_amt}}</td>
                                      <td><?php echo number_format($igst,2);?></td>
                                      <td><?php echo number_format(($cgst_sgst/2),2);?></td>
                                      <td><?php echo number_format(($cgst_sgst/2),2);?></td>
                                  </tr>
                                  <?php $total+=$row['product_qty']*$row['product_price'];
                                  $total_commission+=$commission_amt;
                                  ?>
                              @endforeach
                              </tbody>

                          </table>
					  </div>
				   <div class="box-footer ledgerBox">
				   <div class="row">
					<div class="col-md-10">
						<h5> Total : &nbsp; <span><strong>Rs. ({{$total}})</strong></span> &nbsp; &nbsp; <span></h5>
						<h5> Jaldi Kharido Fee : &nbsp;<strong>Rs. ({{$total_commission}})</strong></span></h5>
						<h5> Net Total : &nbsp;<span><strong>Rs. ({{$total-$total_commission}})</strong></span></h5>
					</div>
					<div class="col-md-2"> &nbsp; </div>
				   </div>

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

