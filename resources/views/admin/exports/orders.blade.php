<table>
    <thead>
			<tr>
			<th>Order No</th>
			<th>Tax Percent</th>
			<th>Discount</th>
			<th>Coupon</th>
			<th>Percent</th>
			</tr>
    </thead>
    <tbody>
   @foreach($Orders as $Order)

						<tr>
								<td>{{$Order->order_no}}</td>
								<td>{{$Order->tax_percent }}</td>
								<td>{{$Order->discount_amount}}</td>
								<td>{{$Order->coupon_code}}</td>
								<td>{{$Order->coupon_percent}}</td>
								
						</tr>
					    @endforeach
    </tbody>
</table>
