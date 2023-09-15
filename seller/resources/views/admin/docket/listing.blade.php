@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
	
<style>
[type="radio"]:checked, [type="radio"]:not(:checked) {
	position: relative !important;
	left: 0 !important;
	opacity: 4 !important;
}
</style>
<?php 
    $parameters = Request::segment(3);				
    $sts =Request()->sts;
    $dtsts=Request()->dtsts;
     
?>
	
<div class="">
	<div class="allbutntbl">
		<a href="{{ route('adddocket') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Docket</a>
	</div>

</div>

		<!--<table class="allbutntbl">
		<tr>
		<td><a href="{{ route('addbrand') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Brand</a></td>
		<td><button type="button" class="btn btn-danger btnSubmitTrigger" disabled>Bulk Delete</button></td>
		<td><input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="{{$parameters}}"></td>
		<td></td>
		<td><button type="submit" class="btn btn-default reset" >Reset</button></td>

		</tr>
		</table>-->
		<div class="col-sm-12 text-right">
				<a href="{{ $page_details['export']}}" class="btn btn-warning">Export TO CSV</a> &nbsp; 
		</div>
		
		<div class="row searchmain-row">
            	<div class="col-sm-8 col-md-2">
            		<div class="searchmain">
            		<lable>Status</lable>
            			<select id="sts" class="form-control DocketStatus">
            			    <option value="All"
            			     <?php echo ($sts=='All')?"selected":""; ?>
                			    >Status</option>
                			     <option value="0"
                			      <?php echo ($sts=='0')?"selected":""; ?>
                			     >Available</option>
                			      <option value="1"
                			       <?php echo ($sts=='1')?"selected":""; ?>
                			      >Used</option>
            			</select>
            		</div>
            	</div>
            	
            	<div class="col-sm-8 col-md-2">
            		<div class="searchmain">
            		<lable>Docket Type</lable>
            			<select id="dtsts" class="form-control DocketStatus">
            			    <option value="All"
            			     <?php echo ($dtsts=='All')?"selected":""; ?>
                			    >Docket Type</option>
                			     <option value="COD"
                			      <?php echo ($dtsts=='COD')?"selected":""; ?>
                			     >COD</option>
                			      <option value="Online"
                			       <?php echo ($dtsts=='Online')?"selected":""; ?>
                			      >Online</option>
            			</select>
            		</div>
            	</div>
            	
            		<div class="col-sm-8 col-md-2">
                		<div class="searchmain">
                		<lable>Docket No.</lable>
                			<input type="text" id="docket_no" placeholder="Enter Docket No." class="form-control DocketStatus" value="<?php echo  (@$page_details['docket_no']  != 'All' )?@$page_details['docket_no']:'';?>">
                		</div>
            
            	    </div>
            	    
            	    <div class="col-sm-8 col-md-2">
                		<div class="searchmain">
                		    	<lable>&nbsp;</lable>
                			<input type="button"   class="btn btn-primary" onclick="reset_docket_filter();" value="Reset">
                		</div>
            
            	    </div>
           
	
	</div>	


				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Docket Type</th>
							<th>Docket Number</th>
							<th>Status</th>

						</tr>
					</thead>
					<tbody>
					
					  @foreach($dockets as $docket)

						<tr>
				            <td>{{$docket->docket_type}}</td>
							<td>{!!$docket->docket_number!!}</td>
						    <td>
						    	@if($docket->status == 1)         
								<i class="fa fa-close text-red" aria-hidden="true"></i> Used
								@else
								<i class="fa fa-check text-green" aria-hidden="true"></i> Available
								@endif
						    </td>
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
				  {{ $dockets->links() }}
				</div>
				
<script>
    $(document).on('change','.DocketStatus', function () {
		    
        var sts=$('#sts').val();
        var dtsts=$('#dtsts').val();
        var docket_no=$('#docket_no').val();
        
	    if(sts == ''){ 
             sts = 'All';   
            }
            
        if(dtsts == ''){
             dtsts = 'All';   
            }
         if(docket_no == ''){
             docket_no = 'All';   
            }
    	filterDocket(sts,dtsts,docket_no);
	});
	
	function filterDocket(sts,dtsts,docket_no){
	    var url='{{$page_details['search_route']}}';
	    url=url+"/"+sts+"/"+dtsts+"/"+docket_no;
	    window.location.href=url;
	}
	
    function reset_docket_filter(){
	     var url='{{$page_details['base_route']}}';
 	     window.location.href=url;
	}

</script>			
				
				 
@endsection
