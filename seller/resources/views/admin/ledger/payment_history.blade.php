@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
 @section('backButtonFromPage')
    <a href="{{route('vledger') }}" class="btn btn-default goBack">Go Back</a>
    @endsection
@section('content')

    <?php 
    /*$parameters = Request::segment(3);
    $str = Request::segment(3);
	$daterange = Request::segment(4);
    $parameters_level = base64_decode($parameters);*/
    ?>
<style>
.nav-tabs>li>a {
    /* margin-right: 2px; */
    line-height: 1.42857143;
    /* border: 1px solid transparent; */
    border-radius: 4px 40px 8px 0 !important;
}
</style>
<?php if(!Auth::guard('vendor')->check()){ ?>
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
			<div class="col-sm-10">
				<div class="row">
					<div class="col-md-3 offset-md-4">
				<input type="text" name="daterange" class="form-control daterange" placeholder="Select Date" value="<?php echo ($daterange!='All')? str_replace('.','/',$daterange):"";?>">
					
					</div>
					<div class="col-md-5">
						<div class="searchmain">
							<input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="<?php echo ($str!='All')? $str:"";?>">
							<button type="submit" class="btn btn-primary payment_history"  >Search</button>
						</div>
					</div>
				</div>
				
			</div>
			
		</div>
	</div>
</div>
<?php } ?>
<div class="mt15">
	<div class="tab-content" id="new_order">
		{{$vendor_info->public_name}}<br>
		{{$vendor_info->email}}<br>
		{{$vendor_info->phone}}
  <div class="tab-pane fade show active" id="new_order" role="tabpanel" aria-labelledby="new_order">
  
  				<div class="table-responsive">
				
					<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Date</th>
							<th>Transaction ID</th>
							<th>Payment Mode</th>
							<th>Amt</th>
							<th>Narration</th>
						</tr>
					</thead>
					<tbody>
					<?php $i=1;  ?>
					  @foreach($payment_history as $row)
					 	<tr>
							<td>{{$i++}}</td>
							<td>{{$row->vendor_payment_date}}</td>
							<td>{{$row->vendor_payment_receipt}}</td>
							<td>{{$row->vendor_payment_mode}}</td>
							<td>{{$row->vendor_payment_amt}}</td>
							<td>{{$row->vendor_payment_narration}}</td>
						</tr>
					    @endforeach
					</tbody>
				  </table>
				</div>
				
				{{ $payment_history->links() }}
				
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
