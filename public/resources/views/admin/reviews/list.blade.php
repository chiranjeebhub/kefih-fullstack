@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
	
		<?php 
				$parameters = Request::segment(3);
				
?>
	
<div class="">

	<div class="col-sm-12">
		<div class="row">
                    <div class="col-sm-2">
                    <form role="form" action="{{ route('selectedReview')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="mode" value="0">
                     <input type="hidden" value="" name="product_ids" class="product_id">
                    <button type="submit" class="btn btn-danger commonClassDisableButton" disabled>Delete Selected</button>
                    </form>
                    </div>
                    
            <div class="col-sm-2">
				<form role="form" action="{{ route('selectedReview')}}" method="post" enctype="multipart/form-data">
				@csrf
				<input type="hidden" name="mode" value="1">
				 <input type="hidden" value="" name="product_ids" class="product_id">
				<button type="submit" class="btn btn-success commonClassDisableButton" disabled>Approve Selected</button>
				</form>
            </div>
            
		</div>
	</div>
</div>

	
		
<form role="form" class="form-element multi_delete_form mt15" action="{{route('multi_delete_brand')}}" method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
						 <th><input type="checkbox" class="check_all "></th>
                        <th>Product</th>
                        <th>User</th>
                        
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Status</th>
                        <th>Action</th>

						</tr>
					</thead>
					<tbody>
					
					  @foreach($reviews as $review)

						<tr>
						 <td><input type="checkbox" class="checkbox multiple_select_checkBox checkedProduct" name="review_id[]" value="{{$review->id}}"></td>
                                <td> 
                                <a 
                                href="{{route('preview',base64_encode($review->product_id))}}"
                                taget="_blank"
                                > {{$review->name}}</a>
                               </td>
                                <td> {{$review->user_name}}</td>
                                <td> 
{!!App\Products::userRatingOnproduct($review->rating)!!}
	
</td>
<td> {{$review->review}}
<?php if($review->uploads!=''){ ?>
						<img src="{{URL::to('/uploads/review')}}/{{$review->uploads}}" width="100" height="100">
						<?php } ?>
</td>
                                <td>
                                @if($review->isActive==0)
                                Deactivated
                                @else
                                  Activated
                                @endif
                                
                                </td>
                                 
							<td>
                                <a href="{{route('edit_rating_review', [base64_encode($review->product_id),base64_encode($review->id)])}}">
                                Edit
                                </a>
							 </td>
							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
				</div>
				{{$reviews->links()}}
				 </form>
			
				
				@include('admin.includes.Common_search_and_delete') 
@endsection
