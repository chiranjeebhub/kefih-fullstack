@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
 @section('backButtonFromPage')
    <a href="javascript:void(0)" class="btn btn-default goBack">Go Back</a>
    @endsection
@section('content')
		


				<div class="table-responsive">
					<p><span>Name </span>: &nbsp; {{$customer->name}} </p>
					<p><span>Phone </span>: &nbsp; {{$customer->phone}}</p>
					<p><span>Email </span>: &nbsp; {{$customer->email}}</p>
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
						    <!--<th><input type="checkbox" class="check_all"></th>-->
                            <th>Order No</th>
							<th>Date</th>
                            <th>Points</th>
							<th>Narration</th>
                            
						</tr>
					</thead>
					<tbody>
					<?php if(@$wallet_listing[0]['id']!=''){
						$credit=$debit=0;
					?>
					  @foreach($wallet_listing as $row)

						<tr>
						    <td><a href="{{ route('order_detail',base64_encode($row->fld_order_id)) }}" class="btn btn-success btn-small">{{$row->fld_order_id}}</a></td>
							<td>{{date('d M Y',strtotime($row->fld_wallet_date))}}</td>
                            <td>
								{{($row->fld_reward_points!=0)?'+'.$row->fld_reward_points:''}}
								{{($row->fld_reward_deduct_points!=0)?'-'.$row->fld_reward_deduct_points:''}}
							</td>
							<td>{{$row->fld_reward_narration}}</td>
                        </tr>
						<?php 
							$credit+=$row->fld_reward_points;
							$debit+=$row->fld_reward_deduct_points;
						?>
					    @endforeach
						<tr>
						    <td colspan="2" align="right">Total</td>
							<td><strong>{{$credit-$debit}}</strong></td>
                        </tr>
					<?php } else{?>
						<tr>
						    <td colspan="4">No Records Found.....</td>
                        </tr>
					<?php } ?>
					</tbody>
					
				  </table>
				</div>
				
			
				<?php if(@$wallet_listing[0]['id']!=''){?>
				{{ $wallet_listing->links() }}
				<?php } ?>
				
@endsection
