@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
<form role="form" class="form-element multi_delete_form mt15" action="#" method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
						  
							<th>Sr. No.</th>
							<th>Category Name</th>
							<th>user Name</th>
							<th>Size chart</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$imagespathfolder='uploads/category/size_chart/';
						$i=1;  
						?>
					  @foreach($Sizechartdetails as $sizechart)
						<tr>
							<?php 
							$categoryname=DB::table('categories')->where('id',$sizechart->category_id)->first();
							$vendorsname=DB::table('vendors')->where('id',$sizechart->vendor_id)->first();
							
							?>
						  	<td>{{$i++}}</td>
							<td>{{$categoryname->name}}</td>
							<td>{{$vendorsname->f_name}} {{$vendorsname->l_name}}</td>
							<td>{!! App\Helpers\CustomFormHelper::support_image($imagespathfolder,$sizechart->sizechart); !!}</td>								
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
				</div>
				 </form>
				{{ $Sizechartdetails->links() }}
				
				<div id="attributesModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content attributesResponseData">
     
     
    </div>

  </div>
</div>
<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});
</script>
@endsection
