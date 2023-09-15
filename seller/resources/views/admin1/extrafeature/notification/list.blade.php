@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
	
	

	
<div class="">
	<div class="allbutntbl">
		<a href="{{ route('addNotification') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Notification</a>
	</div>
</div>

	
	<form role="form" class="form-element multi_delete_form mt15" action="javascipt:void(0)" method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
                <th>SN</th>
                <th>Title</th>
               
                <th>Action</th>
							
						</tr>
					</thead>
					<tbody>
			
					  @foreach($notifications as $notification)

						<tr>
					<td>{{ $no++ }}</td>
					<td>{{$notification->title}} </td>
					
												
    <td>
    
    <a href="{{route('delete_notification', base64_encode($notification->id))}}"
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
				{{ $notifications->links() }}
				
			
				
@endsection
