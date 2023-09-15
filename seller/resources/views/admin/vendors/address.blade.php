@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
 @section('backButtonFromPage')
    <!--<a href="javascript:void(0)" class="btn btn-default goBack">Go Back</a>-->
    @endsection
@section('content')


<style>
	.box-header a.btn.btn-default.goBack{ margin-top: 0; margin-right: 160px; }
</style>
 
<div class="">
	<div class="allbutntbl">
		<a href="{{ route('add_address',(base64_encode($vendor_id))) }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Address</a>
	</div>
</div>

		

<form role="form" class="form-element multi_delete_form mt15" action="" method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
						   
							<th>Address</th>
							<th>State</th>
							<th>City</th>
							<th>Zip</th>
							<th>Action</th>

							
						</tr>
					</thead>
					<tbody>
					
					  @foreach($addresss as $address)

						<tr>
						       
								<td>{{$address->address}}</td>
								<td>{{$address->state}}</td>
								<td>{{$address->city}}</td>
								<td>{{$address->zip}}</td>
								<td>
							   
							<a href="{{route('edit_address', [base64_encode($vendor_id),base64_encode($address->id)])}}"
							onclick = "if (! confirm('Do you want to edit ?')) { return false; }"
							>
								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
								</a>&nbsp;|&nbsp;
							<a href="{{route('delete_address', [base64_encode($address->id)])}}"
							onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
							>
								<i class="fa fa-trash  text-red" aria-hidden="true"></i>
								</a>
       
							</td>
									
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
				</div>
				
				 </form>
				
		
				
				
@endsection
