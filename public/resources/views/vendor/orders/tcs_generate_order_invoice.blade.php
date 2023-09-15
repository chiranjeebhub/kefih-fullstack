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
		<!--<div class="row">
		
			<div class="col-sm-1">
				<button type="submit" class="btn btn-default reset" >Reset</button>
			</div>
			 
			
                        
			<div class="col-sm-3 col-md-3">
							<input type="text" name="daterange" class="form-control daterange" placeholder="Select Date" value="">
						</div>
					
			<div class="col-sm-3 col-md-3">
							<div class="searchmain">
							<input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="">
								<button type="submit" class="btn btn-primary searchBtn sOrdersearch"  >Search</button>
							</div>
						</div>
			
		</div>-->
	</div>
</div>


 
<div class="tab-content" id="new_order">
  <div class="tab-pane fade show active" id="new_order" role="tabpanel" aria-labelledby="new_order">
  
  				<div class="table-responsive">
				  <form method="post" action="{{route('vendor_order_tcs_generate_invoice_update',base64_encode($vdr_data['id']))}}">
				      @csrf
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Order Date</th>
							<th>Master ID</th>
							<th>Suborder ID</th>
							<th>Total Amt</th>
                            <th><input type="submit" value="Submit"/></th>
							
						</tr>
					</thead>
                        <tbody>
                            <?php 
                            $i=1;
                            foreach($orders as $order){?>
                            <tr>
                                <td>{{$i}}</td>
    							<td><?php echo date("d M Y ", strtotime($order->order_date)) ?></td>
    							<td> {{$order->order_no}}</td>
    							<td>{{$order->suborder_no}}</td>
							    <td>{{$order->order_amt}}</td>
                                <td> 
                                    <input type="checkbox" name="order_detail_id[]" value="{{$order->order_detail_id}}"/>
                                </td>
							</tr>
                            <?php $i++;}?>
                        
                        </tbody>
					    </table>
					    </form>
				</div>
				{{ $orders->links() }}
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

 
@endsection
