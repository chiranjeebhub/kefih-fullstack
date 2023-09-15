@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
 @section('backButtonFromPage')
    <a href="javascript:void(0)" class="btn btn-default goBack">Go Back</a>
    @endsection
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
