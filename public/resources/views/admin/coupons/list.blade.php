@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
     

<?php $parameters = base64_decode(Request::segment(3));?>
                
        <div class="">
        <div class="allbutntbl">
        <a href="{{ route('addCoupon') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Coupon</a>
        </div>
        </div>
<div class="coupon-Main">
<div class="row">
<div class="col-sm-12">
<nav class="mt15">
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <a class="nav-item nav-link  <?php echo ($parameters==0)?'active':''?>" href="{{route('coupons',base64_encode(0))}}">Static Coupon </a>
        <a class="nav-item nav-link  <?php echo ($parameters==1)?'active':''?>" href="{{route('coupons',base64_encode(1))}}">Static Cart Coupon </a>
        <a class="nav-item nav-link  <?php echo ($parameters==2)?'active':''?>" href="{{route('coupons',base64_encode(2))}}">Static Periodic Coupon </a>
        <a class="nav-item nav-link  <?php echo ($parameters==3)?'active':''?>" href="{{route('coupons',base64_encode(3))}}">Static Periodic  And Cart Coupon </a>
	
	<a class="nav-item nav-link <?php echo ($parameters==4)?'active':''?>" href="{{route('coupons',base64_encode(4))}}">Offer Coupon</a>
	<a class="nav-item nav-link <?php echo ($parameters==5)?'active':''?>" href="{{route('coupons',base64_encode(5))}}">Offer Cart Coupon</a>
	<a class="nav-item nav-link <?php echo ($parameters==6)?'active':''?>" href="{{route('coupons',base64_encode(6))}}">Offer Periodic Coupon</a>
	<a class="nav-item nav-link <?php echo ($parameters==7)?'active':''?>" href="{{route('coupons',base64_encode(7))}}">Offer Periodic  And Cart Coupon</a>
	
	 </div>
</nav>
</div>
</div>
<div class="tab-content" id="new_order">
  <div class="tab-pane fade show active" id="new_order" role="tabpanel" aria-labelledby="new_order">
  
  				<div class="table-responsive">
									<table id="example1" class="table table-bordered table-striped dataTable">
					<thead>
						<tr>
							<th class="sorting_disabled">Coupon Name</th>
							
						
							<th class="sorting_disabled">Issue  Date</th>
							<th class="sorting_disabled">Expire Date</th>
						
							
							<th class="sorting_disabled">Discount</th>
							<th class="sorting_disabled">Max Discount</th>
                            <th class="sorting_disabled">For</th>
                             <th class="sorting_disabled">Shown In APP</th>
                            <?php if($parameters==0 || $parameters==1 || $parameters==2 || $parameters==3){?>
                            <th class="sorting_disabled">Number of customers can use</th>
                            <th class="sorting_disabled">Number of uses percustomers</th>
                            <?php }?>
                            
							<th class="sorting_disabled">Coupon Assign</th>
							<th class="sorting_disabled">Action</th>
						</tr>
					</thead>
                            <tbody>
                                
                                @foreach ($coupons as $coupon)
                                
                                <?php
                                
                        
                                
                                 ?>
                                    <tr>
                                    <td>{{$coupon->coupon_name}}</td>
                                    
                                    
                                        <td>{{$coupon->started_date}}</td>
                                        <td>{{$coupon->end_date}}</td>
							
                                    
                                    <td>{{$coupon->discount_value}} %</td>
                                     <td>Rs .{{$coupon->max_discount}}</td>
                                       
                                            <td>
                                            @if($coupon->coupon_for==1)
                                            <span>All customers</span>
                                            @else
                                              <span>New customers</span> 
                                            @endif()
                                            </td>
                                            
                                             <td>
                                            @if($coupon->show_in_app==1)
                                            <span>Yes</span>
                                            @else
                                              <span>No</span> 
                                            @endif()
                                            </td>
                                            
                            <?php if($parameters==0 || $parameters==1 || $parameters==2 || $parameters==3){?>
                            <td>{{ $coupon->number_of_user }}</td>    
                            <td>{{ $coupon->uses_per_user }}</td>    
                            <?php }?>
			<td> <a href="{{route('couponaAssign', base64_encode($coupon->id))}}">
		Assign
			</a></td>
                                    <td>
                               
								
                               
                                <a href="{{route('couponDetail', base64_encode($coupon->id))}}">
                                <i class="fa fa-eye text-orange" aria-hidden="true"></i>
                                </a>&nbsp;|
                                &nbsp;
                               
							<!--<a href="{{route('couponEdit', base64_encode($coupon->id))}}">-->
							<!--	<i class="fa fa-pencil text-blue" aria-hidden="true"></i>-->
							<!--	</a>&nbsp; | &nbsp;-->
                                    <a href="{{route('couponDelete',[base64_encode(0),base64_encode($coupon->id) ]  )}}"
                                    onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
                                    >
							<i class="fa fa-trash text-red" aria-hidden="true"></i></a>
							
						
							</td>
                                    </td>
                                   
                                    </tr>
                                @endforeach
                            </tbody>
					
				  </table>
										  
				</div>
				
  </div>
  
 
</div>

</div>

@endsection
