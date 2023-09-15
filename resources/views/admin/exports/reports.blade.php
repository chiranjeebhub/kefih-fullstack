
<html>
    <body>
        
  
<table>
      <thead>
    @if($type==3)
       
        		<tr>
        		<th>Sr.No</th>
        		<th>Vendor Name</th>
        		<th>Total sales</th>
        	
        		</tr>
       
    @else
     
        		<tr>
                <th>Sr.No</th>
                <th>Product Name</th>
                  <th>Category</th>
                @if($type==1)
				<th>City</th>
                                <th>Pincode</th>
                @endif
                <th>Seller</th>
                <th>Product SKU</th>
                <th>Total sales</th>
               
        		</tr>
        
    @endif
   </thead>
    <tbody>
            @if($type==3)
           
					<?php $total=0;?>
					
					  @foreach($Records as $row)
					 
						<tr>
							<td>{{$loop->iteration}}</td>
							<td>
								{{$row['username']}}
							</td>
							<td>{{$row['total_sales']}}</td>
							
						</tr>
						<?php $total+=$row['total_price'] + $row['order_shipping_charges'];?>
						  
					    @endforeach
					   {{ $Records->links() }}
					
            @else
            <?php $total=0;?>
					
					  @foreach($Records as $row)
					 
						<tr>
								<td>{{$loop->iteration}}</td>
								
								<td>
								  {{$row->product_name}}
								{{ isset($row['pr_size'])?'Size: '.$row['pr_size']:''}}
								{{isset($row['pr_color'])?'Color: '.$row['pr_color']:''}}
								</td>
								
								<td>
								 ({{App\ProductCategories::getProductcategoryName($row['id'])}})
								</td>
								
                    @if($type==1)
                    <td>
                    @if($row['order_shipping_zip'])
                    <span class="badge bg-danger">{{$row['order_shipping_city']}}</span>
                    <br>	
                    @else
                    <span  class="badge bg-danger">NAN</span>
                    @endif
                    </td>

					<td>
                    @if($row['order_shipping_zip'])
                    <span class="badge bg-danger">{{$row['order_shipping_zip']}}</span>
                    <br>	
                    @else
                    <span  class="badge bg-danger">NAN</span>
                    @endif
                    </td>

                    @endif
                                
								<td>
								    <?php 
								$seller_name=DB::table('vendors')->where('id',$row['vendor_id'])->first();
								echo $seller_name->public_name;
								    ?>
								    </td>
								<td>{{$row['sku']}}</td>
								<td>{{$row['total_sales']}}</td>
								
							
								
								
							
						</tr>
						<?php $total+=$row['total_price'] + $row['order_shipping_charges'];?>
						  
					    @endforeach
					     {{ $Records->links() }}
            @endif
    </tbody>
</table>
  </body>
</html>
