<table>
    <thead>
			<tr>
			<th>Order No</th>
			<!--<th>Tax Percent</th>
			<th>Discount</th>
			<th>Coupon</th>
			<th>Percent</th>-->
			
			<th>SubOrder No</th>
			<th>Invoice No</th>
			<th>Invoice Date</th>
			<th>Product Name</th>
			<th>Qty</th>
			<th>Price</th>
			<th>Offer Price</th>
			<th>Size</th>
			<th>Color</th>
			<th>Order Date</th>
			
			</tr>
    </thead>
    <tbody>
			@foreach($Orders as $Order)
			<tr>
					<td>{{$Order->order_no}}</td>
					<!--<td>{{$Order->tax_percent }}</td>
					<td>{{$Order->discount_amount}}</td>
					<td>{{$Order->coupon_code}}</td>
					<td>{{$Order->coupon_percent}}</td>-->
					
					<td>{{$Order->suborder_no}}</td>
					<td>{{$Order->order_detail_invoice_num}}</td>
					<td>{{date('d-m-Y', strtotime($Order->order_detail_invoice_date))}}</td>
					<td>{{$Order->product_name}}</td>
					<td>{{$Order->product_qty}}</td>
					<td>{{$Order->product_price_old}}</td>
					<td>{{$Order->product_price}}</td>
					<td>{{$Order->size}}</td>
					<td>{{$Order->color}}</td>
					<td>{{date('d-m-Y', strtotime($Order->order_date))}}</td>
					
			</tr>
			@endforeach
    </tbody>
</table>
