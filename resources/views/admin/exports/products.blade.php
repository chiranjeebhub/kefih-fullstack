<?php
use App\ProductCategories;
use App\Products;
use App\Vendor;
?>
<table>
    <thead>
			<tr>
			<th>Sr. No.</th>
			<th>Product ID</th>
			<th>HSN Code</th>
			<th>Product Code</th>
			<th>SKU</th>
			<th>Weight</th>
			<th>MRP</th>
			<th>Selling Price</th>
			<th>Category ID</th>
			<th>Vendor name</th>
			<th>Short Description</th>
		
			</tr>
    </thead>
    <tbody>
		
   @foreach($Products as $key => $Product)

          <?php
         $getCate= ProductCategories::where('product_id',$Product->id)->pluck('cat_id')->toArray();
         $category_id=@implode("#",$getCate);

         $vendors_details=Vendor::select('username as uname')->where('id','=',$Product->vendor_id)->first();
		if($vendors_details){
			$uname = $vendors_details->uname;
		}else{
			$uname = '';
		}
         // dd($getCate,$category_id);
          ?>

						<tr>
								<td>{{++$key}}</td>
								<td>{{$Product->id}}</td>
								
								<td>{{$Product->hsn_code}}</td>
								<td>{{$Product->name}}</td>
								<td>{{$Product->sku }}</td>
								<td>{{$Product->weight}}</td>
								<td>{{$Product->price}}</td>
								<td>{{$Product->spcl_price}}</td>
								<td>{{$category_id}}</td>
								<td>{{$uname}}</td>
								<td>{!!$Product->short_description!!}</td>
							
						</tr>
					    @endforeach
    </tbody>
</table>
