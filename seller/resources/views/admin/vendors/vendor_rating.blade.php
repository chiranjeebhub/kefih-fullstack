@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
	


	
		
<form role="form" class="form-element multi_delete_form mt15" action="" method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
                            <th>User</th>
                            <th>Rating</th>
                            <th>Status</th>
                       

						</tr>
					</thead>
					<tbody>
					
					  @foreach($ratings as $rating)
					  <tr>
					  <td>{{$rating->name}} {{$rating->last_name}}</td>
					   <td>{!!App\Products::userRatingOnproduct($rating->rating)!!}</td>
					    <td>
                            <a href="{{route('vdr_rt__sts',[base64_encode($rating->id),base64_encode($rating->isActive)] )}}"
                            onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
                            >
                            @if($rating->isActive ==0)         
                            <i class="fa fa-close text-red" aria-hidden="true"></i> 
                            @else
                            <i class="fa fa-check text-green" aria-hidden="true"></i>  
                            @endif
                            </a>
					    </td>
					    </tr>
					    @endforeach
					</tbody>
					
				  </table>
				</div>
				{{$ratings->links()}}
				 </form>
			
			
@endsection
