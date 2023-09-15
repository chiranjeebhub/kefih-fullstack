<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Order Date</th>
							<th>Order ID</th>
							<th>Product Name</th>
                            <th>Product Qty</th>
                            <th>Product Price </th>
                              <th>Payment Mode</th>
                             <th>Order Status</th>
						</tr>
					</thead>
                        <tbody>
                            <?php 
                            $i=1;
                            foreach($orders as $order){?>
                            <tr>
                            <td>{{$i}}</td>
							<td><?php echo date("d M Y ", strtotime($order->order_date)) ?></td>
							<td>{{$order->suborder_no}}</td>
							<td>{{$order->product_name}}
                                        @if($order->size!='')
                                        <br>
                                        Size {{$order->size}}
                                        @endif
                                        
                                        @if($order->color!='')
                                        <br>
                                        Color {{$order->color}}
                                        @endif
							</td>
							<td>{{$order->product_qty}}</td>
							<td>{{$order->product_price}}</td>
                                        <td>
                                        @if($order->payment_mode==0)
                                        'COD'
                                        @else
                                        'Already Paid'
                                        @endif()
                                        </td>
							
						<td>
                                @switch($type)
                                @case(0)
                                 New order
                                @break
                                
                                @case(1)
                               Invoice generated
                                @break
                                
                                    @case(2)
                                    Shipped
                                    @break
                                    
                                    @case(3)
                                    Delivered
                                    @break
                                    
                                    @case(4)
                                    Canceled
                                    @break
                                    
                                    
                                     @case(5)
                                    Returned
                                    @break
                                    
                                @endswitch
						</td>
                            </tr>
                            <?php $i++;}?>
                        
                        </tbody>
					    </table>