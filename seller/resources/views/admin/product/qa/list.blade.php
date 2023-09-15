@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
	
	 @section('backButtonFromPage')
    <a href="{{ $page_details['back_route']}}" class="btn btn-default">Go Back</a>
    @endsection
	
        <div class="">
        <div class="allbutntbl">
        <a href="{{ route('add_qa',(base64_encode($prd_id))) }}"
        class="btn btn-success"
        
        ><i class="fa fa-plus" aria-hidden="true"
        
        ></i> Add QA</a>
        </div>
        </div>




<form role="form" class="form-element multi_delete_form mt15" action="" method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
						
                                <th>Question</th>
                                <th>Answer</th>
                                <th>Action</th>

						</tr>
					</thead>
					<tbody>
					
					  @foreach($qas as $qa)

						<tr>
						 
							<td>{{$qa->product_question}}</td>
							<td>{{$qa->product_answer}}</td>
							<td>
							<a href="{{route('qa_sts',[base64_encode($qa->id),base64_encode($qa->product_question_answer_status)] )}}"
									onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
									>
										@if($qa->product_question_answer_status ==0)         
										<i class="fa fa-close text-red" aria-hidden="true"></i> 
										@else
										<i class="fa fa-check text-green" aria-hidden="true"></i>  
										@endif
									</a>
										&nbsp;|&nbsp;
							<a href="{{route('edit_qa', [base64_encode($qa->id)])}}"
							onclick = "if (! confirm('Do you want to edit ?')) { return false; }"
							>
								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
								</a>&nbsp;|&nbsp;
							<a href="{{route('delete_qa', base64_encode($qa->id))}}"
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
				{{ $qas->links() }}
        

			
				
@endsection
