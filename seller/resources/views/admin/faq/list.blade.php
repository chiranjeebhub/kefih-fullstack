@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
 @section('backButtonFromPage')

<style>
	.allbutntbl{ position: relative; float: right; right: 0; top: 0; }
</style>
    <a href="{{ $page_details['back_route']}}" class="btn btn-default">Go Back</a>
    @endsection


<div class="col-md-12">
        <div class="allbutntbl">
        	<a href="{{ $page_details['Action_route']}}"
        class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Faq</a>
        </div>
        </div>
<div class=" ">
    
<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
			   @csrf
		    <div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped mt15">
					<thead>
						<tr>
                            
                            <th>Title</th>
                            <th>Description</th>
                            <th>FOR</th>
                            <th>Action</th>
						</tr>
					</thead>
					<tbody>
					
					  @foreach($description_data as $description)

						<tr>
						 
							<td>{{$description->fld_faq_question}}</td>
							<td>{{$description->fld_faq_answer}}</td>
								<td>
								    @if($description->fld_faq_type==0)
								    <span>Customers</span>
								    @else
								     <span>Vendors</span>
								    @endif()
								   
								    </td>
							<td>
							    	<a href="{{route('sts_faq',[base64_encode($description->id),base64_encode($description->fld_faq_status)] )}}"
									onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
									>
										@if($description->fld_faq_status ==0)         
										<i class="fa fa-close text-red" aria-hidden="true"></i> 
										@else
										<i class="fa fa-check text-green" aria-hidden="true"></i>  
										@endif
									</a>
									&nbsp;|&nbsp;
            <a href="{{route('edit_faq', [base64_encode($description->id)])}}"
            onclick = "if (! confirm('Do you want to edit ?')) { return false; }"
            >
                	<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
            </a>&nbsp;|&nbsp;
            <a href="{{route('delete_faq', base64_encode($description->id))}}"
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
</div>

@endsection

