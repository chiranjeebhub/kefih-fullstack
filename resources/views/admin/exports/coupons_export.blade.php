<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Coupon Name</th>
							<th>Issue Date</th>
							<th>Expire Date</th>
							<th>Discount</th>
							<th>Max Discount</th>
							<th>For</th>
						
							<th>Coupon Assign</th>
						
						</tr>
					</thead>
					<tbody>
					<?php $i=1;  ?>
					  @foreach($ledgers as $coupon)
					  
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