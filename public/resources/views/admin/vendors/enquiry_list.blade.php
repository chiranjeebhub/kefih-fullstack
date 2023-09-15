@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
		

		

<form role="form" class="form-element multi_delete_form mt15" action="{{route('multi_delete_vendor')}}" method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Sr.No</th>
							<th>Product Name</th>
							<th>Vendor Name</th>>
							<th>Name</th>
							<th>Email</th>
							<th>Mobile number</th>
							<th>Quantity</th>
							<th>Comment</th>
							<th>Action</th>

							
						</tr>
					</thead>
					<tbody>
					
					  @foreach($dataEnquirry as $key => $vendor)

						<tr>
						    <td>{{ ($dataEnquirry->currentpage()-1) * $dataEnquirry->perpage() + $key + 1 }}</td>
								<td>{{$vendor->productName}}</td>
								<td>{{$vendor->f_name.' '.$vendor->l_name}}</td>
								<td>{{$vendor->enq_name}}</td>
								<td>{{$vendor->email}}</td>
								<td>{{$vendor->country_code.'-'.$vendor->phone}}</td>
								<td>{{$vendor->qty}}</td>
								<td>{{$vendor->message}}</td>
								
								<td>
    							<a href="{{route('delete_enquiry', base64_encode($vendor->id))}}"
    							onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
    							>
    							<i class="fa fa-trash text-red" aria-hidden="true"></i></a>
							</td>
								
							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
				</div>
				
				 </form>
				
				{{ $dataEnquirry->links() }}
				
@endsection
