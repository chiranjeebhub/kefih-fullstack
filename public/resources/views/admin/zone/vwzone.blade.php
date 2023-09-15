@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Title'])
@section('content')
<?php 
$parameters = Request::segment(3);
$parameters_level = base64_decode($parameters);
if(@$row){
$prd = Request::segment(4);
$parameters_level = $parameters;
$id = $prd;	
}
?>
<div class="col-md-12">
<?php if($page_details['Title']=='Zone List'){?>

 <div class="allbutntbl">
				<a href="{{route('add_zone')}}"
				class="btn btn-success"
					
				><i class="fa fa-plus" aria-hidden="true"
			
				></i> Add New</a>
			</div>
<form role="form" class="multi_delete_form mt15" action="{{$page_details['multipe_delete']}}" method="post">
  @csrf
    <div class="row">
        <div class="col-md-3">
            <select name="status" class="form-control">
             <option value="">Select</option>
             <option value="1">Active</option>
             <option value="2">Inactive</option>
             <option value="3">Delete</option>    
             </select>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger btnSubmitTrigger commonClassDisableButton" disabled>Submit</button>
        </div>
    </div>
   
  
  <div class="row mt15">
				<div class="table-responsive">
				
                    
				 <table id="example" class="table table-bordered table-striped dataTable no-footer js-exportable">
					<thead>
						<tr>
						  <th><input type="checkbox" class="check_all"></th>
                            <th>Id</th>
                            
							<th>Name</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
					
					  @foreach($list as $row)

						<tr>
						 <td><input type="checkbox" class="checkbox multiple_select_checkBox checkedrow" name="id[]" value="{{$row->id}}"></td>
						 <td style="width:4%">{{$loop->iteration}}</td>
						 <td>{{$row->name}}</td>
						
							<td>
							    <a href="{{route('zone_sts',[base64_encode($row->id),base64_encode($row->status)] )}}"
									onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
									>
										@if($row->status ==0)         
										<i class="fa fa-close text-red" aria-hidden="true"></i> 
										@else
										<i class="fa fa-check text-green" aria-hidden="true"></i>  
										@endif
									</a>
									&nbsp;|&nbsp;
									<a href="{{route('edit_zone', [$row->id])}}"
							onclick = "if (! confirm('Do you want to edit ?')) { return false; }"
							>
								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
								</a>&nbsp;|&nbsp;
							
							<a href="{{route('delete_zone', base64_encode($row->id))}}"
							onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
							>
							<i class="fa fa-trash text-red" aria-hidden="true"></i></a>
							
       
						
							</td>
							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
				</div>
    </div>
				 </form>
				{{ $list->links() }}
				@include('admin.includes.Common_search_and_delete') 
				<?php } else { ?>
    
     <div class=" ">
                
<form role="form" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
@csrf
 <section>

 <div class="row mt15">
							  <div class="col-sm-6">
            <div class="form-group">
                <select class="form-control form_ctrl _select_arrow custom-select" data-live-search="true"  data-title="Location" name="shipping_state" id="selectState">
                    <option value="">Select State</option>
                    
                    @foreach($states as $state)
                    <option value="{{$state->name}}" data-id='{{$state->id}}'>{{$state->name}}</option>
                    @endforeach
                </select>
                   
                
                 @if($errors->has('shipping_state'))
                <span  class="spanErrror">{{ $errors->first('shipping_state') }}</span>
                @endif
        </div>
            </div>
             <div class="col-sm-6">
            <div class="form-group">
        <select class="form-control form_ctrl _select_arrow" name="city_id" id="selectcity">
                  <option value="">Select City</option>
                </select>
                    
              
              @if($errors->has('shipping_city'))
                <span  class="spanErrror">{{ $errors->first('shipping_city') }}</span>
                @endif  
        </div>
            </div>
								   <div class="col-sm-6">
                                     <div class="form-group"><label for="exampleInputEmail1"> Area</label>
                                     <select name="area_id[]" id="selectarea" class="form-control hidCus" required="required" multiple >
            <option value="0">----- Select Area -----</option>
            
         
						<option>
					
         </select>
							</div>						
   								</div>		
											
                                           <div class="col-md-6">
                                                <div class="form-zone">
                                <label for="institution_name">zone : <span class="danger">*</span> </label>
<input type="text" class="form-control required" name="name" value="{{(@$row->name)?@$row->name:old('name')}}"> </div> 
    <h6 style="color: red;margin-left: 20px;">{{$errors->first('name')}}</h6>
                                     
                                            </div>
                                            
                                          
                                         
                                            
                                          </div>
                                    </section>
                                   
                                        <div class="form-zone">
                        <input type="submit" class="btn  btn-success" value="Save"> 
                                        </div>

</form>           
                           

</div></div>
<?php } ?>
<link rel="stylesheet" href=" {{ asset('public/css/imageuploadify.min.css') }}">
<script src="{{ asset('public/js/validateform.js') }}"></script> 
<script type="text/javascript" src="{{ asset('public/js/imageuploadify.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/js/jsLists.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('input[type="file"]').imageuploadify();
    });
	JSLists.applyToList('simple_list', 'ALL');
	
	

</script>	


@endsection
