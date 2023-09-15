@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
 @section('backButtonFromPage')
    <a href="javascript:void(0)" class="btn btn-default goBack">Go Back</a>
    @endsection
@section('content')


        <div class="">
                <div class="allbutntbl">
                    
                    <a href="javascript:void(0)"
                    	class="btn btn-success"
                    data-toggle="modal"
                    data-target="#FilterValueModal0"
                    >Add Values
                    </a>
                   
                </div>
        
        </div>	

	

				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
						
							<th>Name</th>
							<th>Action</th>

						</tr>
					</thead>
					<tbody>
					<?php 
					$i=1;
					?>
			  @foreach($page_details['Data']['filter_values'] as $Filter)

						<tr>
                        <td>
                        {{$Filter->filter_value}}
                        </td>
					
						    <td>
<a href="javascript:void(0)"
data-toggle="modal" data-target="#FilterValueModal{{$i}}"
>
								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
								</a>&nbsp; | &nbsp;
							<a href="{{route('deletefilterValue', base64_encode($Filter->id))}}"
							onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
							>
							<i class="fa fa-trash text-red" aria-hidden="true"></i></a>
							</td>
						    
<div class="modal" id="FilterValueModal{{$i}}">
<div class="modal-dialog">
<div class="modal-content">

<!-- Modal Header -->
<div class="modal-header">
<h4 class="modal-title">Filter Value Modal</h4>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<!-- Modal body -->
<div class="modal-body">
    <form role="form" action="{{ route('filterValue') }}" method="post" enctype="multipart/form-data">
    @csrf
<div class="row">
<div class="col-sm-12">
<div class="form-group"><label for="exampleInputEmail1">Filter Value *</label> <input type="text" name="filter_value" value="{{$Filter->filter_value}}" class="form-control" placeholder="Value" required>
<input type="hidden" name="filter_value_id" value="{{$Filter->id}}" class="form-control" placeholder="Value">
<input type="hidden" name="mode" value="1" class="form-control" placeholder="Value">
</div>  
</div>


<div class="col-sm-12">
<div class="form-group">
   <input type="submit" class="btn btn-danger" value="Update">
</div>  
</div>

</div>
    </form>
</div>



</div>
</div>
</div>
							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
				  
				  <div class="modal" id="FilterValueModal0">
<div class="modal-dialog">
<div class="modal-content">

<!-- Modal Header -->
<div class="modal-header">
<h4 class="modal-title">Filter Value Modal</h4>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<!-- Modal body -->
<div class="modal-body">
    <form role="form" action="{{ route('filterValue') }}" method="post" enctype="multipart/form-data">
    @csrf
<div class="row">
<div class="col-sm-12">
<div class="form-group"><label for="exampleInputEmail1">Filter Value *</label> <input type="text" name="filter_value" value="" class="form-control" placeholder="Value" required>
<input type="hidden" name="filter_value_id" value="{{$page_details['Data']['filter_data']->id}}" class="form-control" placeholder="Value">
<input type="hidden" name="mode" value="0" class="form-control" placeholder="Value">
</div>  
</div>


<div class="col-sm-12">
<div class="form-group">
   <input type="submit" class="btn btn-danger" value="Update">
</div>  
</div>

</div>
    </form>
</div>



</div>
</div>
</div>
				</div>
				
				
				
				
@endsection
