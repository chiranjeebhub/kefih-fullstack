@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
<div class="allbutntbl">
	<a href="{{route('orders',base64_encode(8)) }}" class="btn btn-warning"><i class="fa fa-undo"></i> Back</a>
</div>
<div class="col-sm-12">
	<strong>Razor Pay Order ID : {{$razorPayOrderID}}</strong> <p id="rzrres"></p>
<form id="rzrform" role="form" class="form-element" action="{{route('move-to-new',$razorPayOrderID)}}" method="post" enctype="multipart/form-data">
			   @csrf
	<div class="row">
		<div class="col-sm-6">
			<input type="text" placeholder="Enter Transaction ID" id="mytxnid" name="transactionID" class="form-control" readonly/>
		</div>
		<div class="col-sm-6">
			<button type="submit"class="btn btn-xs btn-success">Move to new order</button>
		</div>
		
	</div>
						  
 </form>
</div>

<script>

$(document).ready(function() {

		$.ajax({
		type : 'get',
		url : "{{route('razor-pay-order-details',$razorPayOrderID)}}",	
		success:function(data){
			
			if(data.data.count == 0){
				$("#rzrform").hide(); 
				$("#rzrres").html(`<h5 class="text-danger text-center">Unpaid</h5>`);
			}else{
				let html = '';
				for(i=0;i<data.data.items.length; i++){
					html+=`
					<br> <h3>Attempt : ${i+1}</h3>
					<br> <strong> Payment ID : ${data.data.items[i].id}</strong>
					<br><strong> Amount : <i class="fa fa-inr"></i>${data.data.items[i].amount/100}</strong>  <br> <strong> Status : ${data.data.items[i].status}</strong>
					<br> <strong> Payment Method : ${data.data.items[i].method}</strong><hr>`;
					}			
				
				$("#mytxnid").val(data.data.items[data.data.items.length-1].id);
				$("#rzrres").html(html);
			}
		
		
		}
		});
	});

</script>
@endsection
