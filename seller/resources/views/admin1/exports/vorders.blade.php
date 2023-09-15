<table>
    <thead>
			<tr>
			<th>Order Date</th>
			<th>Master ID</th>
			<th>Suborder ID</th>
			<th>Product Name</th>
			<th>Product Quantity</th>
            <th>Product Price</th>
              <th>Coupon Code</th>
            <th>Total</th>
            <th>Customer Details </th>
			</tr>
    </thead>
    <tbody>
        <?php 
    //   echo '<pre>';
    //   print_r($Orders);
    //   die; 
      ?>
   @foreach($Orders as $order)

						<tr>
								<td>{{date("d M Y ", strtotime($order->order_date)) }}</td>
								<td> {{$order->order_no}}</td>
								<td>{{$order->suborder_no}}</td>
							<td>{{$order->product_name}}  
							
							
                                       @if($order->w_size!='')
							
                                        @if($order->size!='')
                                
                                        Men Size : {{$order->size}}
                                        @endif
                                    
                                            @if($order->w_size!='')
                                       
                                            Women Size :{{$order->w_size}}
                                            @endif
							@else
                                    @if($order->size!='')
                                    
                                    Size : {{$order->size}}
                                    @endif
							@endif()
                                        
                                        @if($order->color!='')
                                      
                                        Color : {{$order->color}}
                                        @endif
							</td>
							<td>{{$order->product_qty}}</td>
							<td>{{$order->product_price}}</td>
								<td>{{$order->coupon_code}}</td>
                            <td>
                                {{($order->details_qty*$order->details_price)+$order->details_shipping_charges+$order->details_cod_charges-$order->details_cpn_amt-$order->details_wlt_amt}}
                                <!--{{($order->product_price*$order->product_qty)-$order->order_wallet_amount-$order->order_coupon_amount+$order->order_cod_charges}}-->
                            </td>

                            <td>
                                              Account Name : {{$order->cust_name}}
                                              Name : {{$order->customer_name}}
                                              Phone :{{$order->customer_phone}}
                                              Email : {{$order->customer_email}}  
                                              Addreess : {{$order->customer_add}} {{$order->customer_add1}} {{$order->customer_add2}} {{$order->customer_state}} {{$order->customer_city}} -{{$order->customer_zip}}
                                                
                                
                            </td>
								
						</tr>
					    @endforeach
    </tbody>
</table>
