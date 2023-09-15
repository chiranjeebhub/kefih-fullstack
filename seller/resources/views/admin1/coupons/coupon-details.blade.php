@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')

<div class="container-fluid">
	<h6>{{$coupons->coupon_name}}'s Details :</h6>
</div>
                <div class="table-responsive">
									<table id="example1" class="table table-bordered table-striped dataTable">
					<thead>
						<tr>
							<th class="sorting_disabled">Coupon code</th>
							<th class="sorting_disabled">Coupon used</th>
							<th class="sorting_disabled">Delete</th>
						</tr>
					</thead>
                            <tbody>
                                
                                @foreach ($coupons->CouponDetail as $coupon)
                                    <tr>
                                        <td>{{$coupon->coupon_code}}</td>
                                        <td>
                                            @switch($coupon->coupon_used)
                                                @case(0)
                                                    No
                                                @break
                                            
                                                @case(1)
                                                    Yes
                                                @break
                                            @endswitch
                                        </td>
                                        <td>
											<a href="{{route('coupon_sts',[base64_encode($coupon->id),base64_encode($coupon->status)] )}}"
									onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
									>
										@if($coupon->status ==0)         
										<i class="fa fa-close text-red" aria-hidden="true"></i> 
										@else
										<i class="fa fa-check text-green" aria-hidden="true"></i>  
										@endif
									</a>
									&nbsp;|&nbsp;
                                            <a href="{{route('couponDelete',[base64_encode(1),base64_encode($coupon->id) ]  )}}"
                                            onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
                                            >
                                            <i class="fa fa-trash text-red" aria-hidden="true"></i></a>
                                        </td>
                                  </tr>
                                @endforeach
                            </tbody>
					
				  </table>
										  
				</div>
@endsection
