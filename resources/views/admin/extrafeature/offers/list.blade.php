@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
<div class="">
	<div class="allbutntbl">
		<a href="{{ route('add_offer')}}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Offer</a>
	</div>
</div>

	
<form role="form" class="form-element multi_delete_form mt15" action="" method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th> Name</th>
						<th>Discount</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
                            @foreach($offers as $offer)
                            <tr>
                            <td>{{$offer->offer_name}}</td>
                            
                           <td>
                            @if($offer->offer_below_above==0)
                            <=
                            @else
                            >=
                            @endif
                            {{$offer->offer_discount}} 

							{{($offer->discount_type == 0)?'%':'Rs.'}}
                            </td>
                            <td><a href="{{route('edit_offer', [base64_encode($offer->id)])}}"
							onclick = "if (! confirm('Do you want to edit ?')) { return false; }"
							>
								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
								</a>&nbsp;|&nbsp; 
                            
                            <a href="{{route('delete_offer', base64_encode($offer->id))}}"
							onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
							>
							<i class="fa fa-trash text-red" aria-hidden="true"></i></a>
                            </td>
                            </tr>
                            @endforeach
					        
					
					
					</tbody>
					
				  </table>
				   {{ $offers->links() }}
				</div>
				 </form>
			
				
				 
@endsection
