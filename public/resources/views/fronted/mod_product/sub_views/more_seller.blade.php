<?php 
if($prd_detail->product_code!=''){
$products=DB::table('products')->where('products.product_code',$prd_detail->product_code)->where('products.isexisting',1)->where('products.vendor_id','!=',0)->get();
if(sizeof($products)>0){
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<p>View <a href="{{route('moreSeller',(base64_encode($prd_detail->product_code)))}}">more sellers</a> starting from 
		<span>
		<i class="fa fa-rupee"></i>
		@if ($prd_detail->spcl_price!='')
		{{$prd_detail->spcl_price}}
		@else
		{{$prd_detail->price}}
		@endif
		</span>
	</p>
</div>
<?php }}?>
