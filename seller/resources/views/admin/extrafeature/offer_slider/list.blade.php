@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])

@section('content')
	
		<?php 
				$parameters_level = base64_decode(Request::segment(3));
				
?>
	
<div class="col-sm-12">
<nav class="mt15">
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
      
	<a class="nav-item nav-link  <?php echo ($parameters_level==0)?'active':''; ?>"  href="{{ route('offer_slider',base64_encode(0)) }}">Deals Of the day </a>
	<a class="nav-item nav-link <?php echo ($parameters_level==1)?'active':''; ?>"  href="{{ route('offer_slider',base64_encode(1)) }}">Best Selling</a>
	<a class="nav-item nav-link <?php echo ($parameters_level==2)?'active':''; ?>"  href="{{ route('offer_slider',base64_encode(2)) }}">Also Bought</a>
	<a class="nav-item nav-link <?php echo ($parameters_level==4)?'active':''; ?>"  href="{{ route('offer_slider',base64_encode(4)) }}">Offer going on</a>
	
  </div>
</nav>
</div>
	
<div class="tab-content" id="new_order">
  <div class="tab-pane fade show active" id="new_order" role="tabpanel" aria-labelledby="new_order">
  
  			
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
					
							<th>Sr. No</th>
							<th>Product Image</th>
							<th>Product name</th>
							<th>Price</th>
							<th>Special Price</th>
							<th>SKU</th>
							<th>Reviews</th>
						   <th>Action</th>

						</tr>
					</thead>
					<tbody>
					<?php $i=1;?>
					  @foreach($products as $product)

						<tr>
					
	<td><?php echo $i++;?></td>
	<td> <img src="{{$product->default_image}}" width="50" height="50"></td>
	<td>{{$product->name}}</td>
	<td>{{$product->price}}</td>
	<td>{{!empty($product->spcl_price)?$product->spcl_price:''}}</td>
	<td>{{$product->sku}}</td>
	<td>
	@if(App\Products::productReviews($product->id)>0)
	 <span class="badge badge-warning"> <a href="{{route('preview',base64_encode($product->id))}}">{{App\Products::productReviews($product->id)}}</a></span>
   
	@else
	<span class="badge badge-warning">0</span>
	@endif
	</td>
	
	<td><a href="{{route('edit_product', [base64_encode(0),base64_encode($product->id)])}}"
							onclick = "if (! confirm('Do you want to edit ?')) { return false; }"
							>
								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
								</a>&nbsp;|&nbsp;
								<a href="{{route('delete_offer_product', [base64_encode($parameters_level),base64_encode($product->id)])}}"
							onclick = "if (! confirm('Do you want to edit ?')) { return false; }"
							>
								<i class="fa fa-trash text-red" aria-hidden="true"></i>
								</a>
								</td>
								

							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
				
				</div>
				</div>
				</div>
				
			
				
				 
@endsection
