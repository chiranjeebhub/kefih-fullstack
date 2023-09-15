@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
	
		<?php 
				$parameters = Request::segment(3);
				
?>
	

<div class="">
	<div class="allbutntbl">
		<a href="{{ route('addpage') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Page</a>
	</div>
	
</div>

	
<form role="form" class="form-element multi_delete_form mt15" action="" method="post">
  @csrf
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
					      	<th>URL</th>
							<th>Title</th>
							<th>Banner</th>
						
							<th>Action</th>

						</tr>
					</thead>
					<tbody>
					
					  @foreach($Pages as $Page)

						<tr>
					         	<td>{{URL::to('/').'/page/'.$Page->url_name}}</td>
								<td>{{$Page->title}}</td>
								
<td>
{!! App\Helpers\CustomFormHelper::support_image('uploads/pages',$Page->banner); !!}</td>
								
							
							<td>
							    
							<a href="{{route('edit_page', base64_encode($Page->id))}}">
								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
								</a>
							
							</td>
							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
				</div>
				 </form>
			
				
				 
@endsection
