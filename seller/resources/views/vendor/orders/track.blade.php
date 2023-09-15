@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')

<style>
/* TRACK ORDER */

.track-order-section{
    padding: 0px 0 50px 0;
}


.track-order-section .track-orderbox ol.progtrckr {
    margin: 0;
    padding: 0;
    list-style-type none;
}

.track-order-section .track-orderbox ol.progtrckr li {
    display: inline-block;
    text-align: center;
    line-height: 3.5em;
}

.track-order-section .track-orderbox ol.progtrckr[data-progtrckr-steps="2"] li { width: 20%; }
.track-order-section .track-orderbox ol.progtrckr[data-progtrckr-steps="3"] li { width: 20%; }
.track-order-section .track-orderbox ol.progtrckr[data-progtrckr-steps="4"] li { width: 20%; }
.track-order-section .track-orderbox ol.progtrckr[data-progtrckr-steps="5"] li { width: 19%; }
.track-order-section .track-orderbox ol.progtrckr[data-progtrckr-steps="6"] li { width: 16%; }
.track-order-section .track-orderbox ol.progtrckr[data-progtrckr-steps="7"] li { width: 14%; }
.track-order-section .track-orderbox ol.progtrckr[data-progtrckr-steps="8"] li { width: 12%; }
.track-order-section .track-orderbox ol.progtrckr[data-progtrckr-steps="9"] li { width: 11%; }

.track-order-section .track-orderbox ol.progtrckr li.progtrckr-done {
    color: black;
    border-bottom: 4px solid #005adb;
    width: 180px;
}
.track-order-section .track-orderbox ol.progtrckr li.progtrckr-todo {
    color: silver; 
    border-bottom: 4px solid silver;
    width: 180px;
}

.track-order-section .track-orderbox ol.progtrckr li:after {
    content: "\00a0\00a0";
}
.track-order-section .track-orderbox ol.progtrckr li:before {
    position: relative;
    bottom: -2.5em;
    float: left;
    left: 50%;
    line-height: 1em;
}
.track-order-section .track-orderbox ol.progtrckr li.progtrckr-done:before {
    content: "\2713";
    color: white;
    background-color: #005adb;
    height: 1.8em;
    width: 1.8em;
    line-height: 1.8em;
    border: none;
    border-radius: 2.2em;
}
.track-order-section .track-orderbox ol.progtrckr li.progtrckr-todo:before {
    content: "\039F";
    color: silver;
    background-color: white;
    font-size: 2.2em;
    bottom: -1.2em;
}
.track-order-section .track-orderbox {
    background: #fbfbfb;
    padding: 50px 20px 80px 20px;
    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.22);
}
</style>
    

<div class="">
	<div class="allbutntbl">
		
	</div>
	<div class="col-sm-12">
		<div class="row">
		</div>
	</div>
</div>

 

<div class="tab-content" id="new_order">
  <div class="tab-pane fade show active" id="new_order" role="tabpanel" aria-labelledby="new_order">
  
  				<div class="table-responsive">
					<div class="db-2-main-com db-2-main-com-table">
						 
						<section class="dashbord-section track-order-section">
	<div class="container">
		<div class="row">
			  
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> 
				<div class="dashbordtxt">
					<h6 class="fs18 fw600 mb20">Track Order | Sub order ID: {{$track_data->fld_order_detail_id}}</h6> 
					 
				   	<div class="db-2-main-com db-2-main-com-table">
						 
						<div class="track-orderbox">
							
							<ol class="progtrckr" data-progtrckr-steps="5">
								<li class="progtrckr-done"> Approved</li>    
								<li class="progtrckr-<?php echo($track_data->fld_invoice_order==1)?'done':'todo';?>"> Packed</li>
								<li class="progtrckr-<?php echo($track_data->fld_shipping_order==1)?'done':'todo';?>"> Shipped</li>
								<li class="progtrckr-<?php echo($track_data->fld_order_intransit==1)?'done':'todo';?>">In Transit</li>
								<li class="progtrckr-<?php echo($track_data->fld_order_outofdelivery==1)?'done':'todo';?>">Out for deliver</li>
								<li class="progtrckr-<?php echo($track_data->fld_delivered_order==1)?'done':'todo';?>"> Delivered</li>
							</ol>     
						</div>
						
					</div>

				</div>    
				<!--
			<div class="no-order text-center">
			<h6 class="fs20 fw600 mb20">Sadly, you haven't placed any orders till now.</h6>    
			<img src="images/no-order.png" alt="">  
			<button type="submit" class="norderbtn" value="submit">Continue Shopping</button>    
			</div>   -->  
			</div>     
		</div>    
	</div>    
    
</section>
						
					</div>
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

	
@endsection
