@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
  @section('backButtonFromPage')
        <a href="{{ $page_details['back_route']}}" class="btn btn-default">Go Back</a>
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
                            <th>No</th>
							<th>User Name</th>
                            <th>User Email</th>
							<th>Refer Date</th>
                            
						</tr>
					</thead>
					<tbody>
					<?php 
						$i=1;
					?>
					  @foreach($referals as $referal)
                                    <tr>
                                    <td><?php echo $i++;?></td>
                                     <td>{{$referal->name}} {{$referal->last_name}}</td>
                                      <td>{{$referal->email}}</td>
                                       <td><?php 
                                            $old_date_timestamp = strtotime($referal->created_at);
                                            echo date('d M ,Y g:i A', $old_date_timestamp); 
                                            ?></td>
                                    </tr>
							<?php $i++;?>
					    @endforeach
						
				
					
					</tbody>
					
				  </table>
				</div>
				
			
				
				{{ $referals->links() }}
			
				
@endsection
