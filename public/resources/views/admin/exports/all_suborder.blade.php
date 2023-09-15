<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Order Date</th>
							<th>Order ID</th>
							<th>Payment Mode</th>
							<th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                           
                    
						
						</tr>
					</thead>
                        <tbody>
                            <?php 
                             if(@$orders[0]->order_no!=''){
                                 $i=1;
                            foreach($orders as $order){?>
                            <tr id="table_row{{$order->id}}">
                            <td>{{$i}}</td>
							<td><?php echo date("d M Y H:i", strtotime($order->order_date)) ?></td>
							<td>{{$order->order_no}}<br>
								{{$order->suborder_no}}
							</td>
							<td>{{($order->payment_mode==1)?'Online':'COD'}}</td>
							<td>
							 
                                     
							{{$order->product_name}}
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
					
						
                            </tr>
                            <?php
                            $i++;
							}}?>
							
							
                        </tbody>
					    </table>