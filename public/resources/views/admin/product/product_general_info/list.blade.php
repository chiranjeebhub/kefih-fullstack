@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
 @section('backButtonFromPage')
    <a href="{{ $page_details['back_route']}}" class="btn btn-default">Go Back</a>
    @endsection
<div class="">
        <div class="allbutntbl">
        <a href="{{ route('add_general',(base64_encode($prd_id))) }}"
        class="btn btn-success"
        
        ><i class="fa fa-plus" aria-hidden="true"
        
        ></i> Add general</a>
        </div>
        </div>
<div class="col-sm-12">
    
<form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
			   @csrf
		    <div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
                            
                            <th>Title</th>
                            <th>Description</th>
                            <th>Action</th>
						</tr>
					</thead>
					<tbody>
					
					  @foreach($description_data as $description)

						<tr>
						 
							<td>{{$description->product_general_descrip_title}}</td>
							<td>{{$description->product_general_descrip_content}}</td>
							<td>
            <a href="{{route('edit_general', [base64_encode($prd_id),base64_encode($description->id)])}}"
            onclick = "if (! confirm('Do you want to edit ?')) { return false; }"
            >
                	<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
            </a>&nbsp;|&nbsp;
            <a href="{{route('delete_general', base64_encode($description->id))}}"
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

