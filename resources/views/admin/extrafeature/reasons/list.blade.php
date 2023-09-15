@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
<div class="">
	<div class="allbutntbl">
		<a href="{{ route('add_reason')}}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Reason</a>
	</div>
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-2">
				<!--<button type="button" class="btn btn-danger btnSubmitTrigger commonClassDisableButton" disabled>Bulk Delete</button>-->
			</div>
			<div class="col-sm-10 col-xs-12">
				<div class="row">
					<div class="col-md-6 hidden-xs"></div>
					<div class="col-md-3 col-xs-12">
						<select class="form-control oncheck status" name="status">
							<option value="all" <?php echo ($status=='all')?'selected':'';?>>Select</option>
							<option value="1" <?php echo ($status=='1')?'selected':'';?>>Active</option>
							<option value="0" <?php echo ($status=='0')?'selected':'';?>>De-active</option>
						</select>
					</div>
					<div class="col-md-3 col-xs-12">
						<select class="form-control oncheck reason_type" name="reason_type">
							<option value="all" <?php echo ($reason_type=='all')?'selected':'';?>>Select</option>
							<option value="0" <?php echo ($reason_type=='0')?'selected':'';?>>Cancel</option>
							<option value="1" <?php echo ($reason_type=='1')?'selected':'';?>>Return</option>
						</select>
					</div>
					
				</div>
				
			</div>
		</div>
	</div>
</div>

	
<form role="form" class="form-element multi_delete_form mt15" action="" method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Info</th>
							<th>Reason For</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
                            @foreach($reasons as $reason)
                            <tr>
                            <td>{{$reason->reason}}</td>
                            <td>
                            @if($reason->reason_type==0)
                          	Cancel
                            @elseif($reason->reason_type==1)
                               Return
                            @else
                            Refund
                            @endif
                            
                            </td>
                           
                            <td>
                                <a href="{{route('reasons_sts',[base64_encode($reason->id),base64_encode($reason->status)] )}}"
								onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
								>
									@if($reason->status ==0)         
									<i class="fa fa-close text-red" aria-hidden="true"></i> 
									@else
									<i class="fa fa-check text-green" aria-hidden="true"></i>  
									@endif
								</a>
								&nbsp;|&nbsp;
								<a href="{{route('edit_reason', base64_encode($reason->id))}}"
							onclick = "if (! confirm('Do you want to edit ?')) { return false; }"
							>
							<i class="fa fa-pencil" aria-hidden="true"></i></a>
							|
                            <a href="{{route('delete_reason', base64_encode($reason->id))}}"
							onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
							>
							<i class="fa fa-trash text-red" aria-hidden="true"></i></a>
                            </td>
                            </tr>
                            @endforeach
					        
					
					
					</tbody>
					
				  </table>
				   {{ $reasons->links() }}
				</div>
				 </form>
			
<script>
$(document).on('change','.oncheck', function () {
	    
	     var ReasonType=$('.reason_type').val();
		 var ReasonStatus=$('.status').val();
        
         filterReason(ReasonType,ReasonStatus);
	});
	
function filterReason(ReasonType,ReasonStatus){
	//alert(ReasonStatus);
	   // var url='https://www.redliips.com/admin/reasons';
	    var url='https://www.b2cdomain.in/kefih/admin/reasons';
	    url=url+"/"+ReasonType+"/"+ReasonStatus;
	   
	    window.location.href=url;
	}
</script>				
				 
@endsection


