@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
	
<div class="allbutntbl">
	<a href="javascript:void(0)"   data-toggle="modal" data-target="#add_form" class="btn btn-success reset_form"><i class="fa fa-plus" aria-hidden="true"></i> Add Role</a>
</div>

		<!--<table>
	<tr>
	<td><a href="javascript:void(0)"   data-toggle="modal" data-target="#add_form" class="btn btn-success reset_form"><i class="fa fa-plus" aria-hidden="true"> Add Role</i></a></td>
	</tr>
		</table>-->

				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
						
							<th>Name</th>
							<th>Users</th>
							<th>Action</th>

						</tr>
					</thead>
					<tbody>
					
					  @foreach($roles as $row)

						<tr>
						 
								<td>{{$row->user_role_name}}</td>
								<td>
								     <a href="{{route('role_users', base64_encode($row->id))}}"
							
							>
							<i class="fa fa-eye" aria-hidden="true"></i></a>
								        
								    </td>
								   
								   <td>
							<a href="javascript:void(0)"   data-toggle="modal" data-target="#edit_form">
								<i class="fa fa-pencil edit" aria-hidden="true" data-id="{{$row->id}}" data-value="{{$row->user_role_name}}" ></i>
								</a> &nbsp;|&nbsp;
							<a href="{{route('deleterole', base64_encode($row->id))}}"
							onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
							>
							<i class="fa fa-trash text-danger" aria-hidden="true"></i></a>
							</td>
						
							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
				</div>
				
				

<!-- Modal -->
<div class="modal fade" id="add_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <form action="{{ route('addrole') }} " method="post" class="form">
     @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Role Add Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		  <div class="row">
		  	<div class="col-md-12 rolenamesec">
        		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['text_field']); !!}
				{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
			  </div>
		  </div>
			
      </div>
     
    </div>
	</form>
  </div>
</div>
	<!-- Modal -->	

<!-- Modal -->
<div class="modal fade" id="edit_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <form action="{{ route('editrole') }} " method="post" class="form">
     @csrf
    <div class="modal-content">
	<span id="erc"></span>
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Role Edit Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
		  <div class="row">
		  	<div class="col-md-12 rolenamesec">
				{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['text2_field']); !!}
				{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
				{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['hidden_button_field']); !!}	  	
		  	</div>
		  </div>
		  
		
		
      </div>
     
    </div>
	</form>
  </div>
</div>
	<!-- Modal -->		
@endsection
