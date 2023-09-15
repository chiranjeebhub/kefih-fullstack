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
?>
	
	<!--
<div class="">
	<div class="allbutntbl">
		<a href="{{ route('addadvertise') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Advertise</a>
	</div>

</div>-->

		<!--<table class="allbutntbl">
		<tr>
		<td><a href="{{ route('addbrand') }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Add Brand</a></td>
		<td><button type="button" class="btn btn-danger btnSubmitTrigger" disabled>Bulk Delete</button></td>
		<td><input type="text" name="search_string" class="form-control search_string" placeholder="Search" value="{{$parameters}}"></td>
		<td></td>
		<td><button type="submit" class="btn btn-default reset" >Reset</button></td>

		</tr>
		</table>-->
		


				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Name</th>
							<th>Description</th>
							<th>Url</th>
							<th>App Advertisement</th>
							<th>Banner</th>
							<th>Assigned Position</th>
							<th>Assign</th>
							<th>Action</th>

						</tr>
					</thead>
					<tbody>
					
					  @foreach($advertises as $banner)

						<tr>
					
								<td>{{$banner->short_text}}</td>
									<td>{!!$banner->description!!}</td>
									<td>{{$banner->url}}</td>
									<td>
									<?php 
									if($banner->categories_id=='215'){
										echo '<b>Category: Women';echo '<br></b>';
									}
									if($banner->categories_id=='216'){
										echo '<b>Category: Men';echo '<br></b>';
									}
									if($banner->mobile_position==1){
										echo 'Featured Category Above Banner<br>';
									}
									if($banner->mobile_position==2){
										echo 'Featured Category Below Banner<br>';
									}
									if($banner->mobile_position==3){
										echo 'Left Banner<br>';
									}
									if($banner->mobile_position==4){
										echo 'Right Banner<br>';
									}
									if($banner->mobile_position==5){
										echo 'Category Store Above Banner<br>';
									}
									if($banner->mobile_position==6){
										echo 'Category Store Below Banner<br>';
									}
									if(@$banner->mobile_image){?>
									
					{!! App\Helpers\CustomFormHelper::support_image('uploads/advertise',$banner->mobile_image); !!}
					<?php } ?>
									</td>
<td>
{!! App\Helpers\CustomFormHelper::support_image('uploads/advertise',$banner->image); !!}</td>
							<td>
								<?php 
									if($banner->advertise_position==1)
										echo'Top Left';
									elseif($banner->advertise_position==2)
										echo'Top Middle';
									elseif($banner->advertise_position==3)
										echo'Top Right';
									elseif($banner->advertise_position==4)
										echo'Middle Left';
									elseif($banner->advertise_position==5)
										echo'Middle Right';
									elseif($banner->advertise_position==6)
										echo'Bottom Left';
									elseif($banner->advertise_position==7)
										echo'Bottom Middle';
									elseif($banner->advertise_position==8)
										echo'Bottom Right';
									elseif($banner->advertise_position==9)
										echo'Footer Left';
									elseif($banner->advertise_position==10)
										echo'Footer Right';
									else
										echo'None';
								?>
							</td>
							<td>
							
							<button type="button" class="btn btn-dark btn-md" data-toggle="modal" 
								data-target="#myModal1<?php echo $banner->id;?>">Mobile Position</button>
								
								<div id="myModal1<?php echo $banner->id;?>" class="modal fade" role="dialog">
								  <div class="modal-dialog">
									<div class="modal-content">
									  <div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">Assign Position for Mobile App&nbsp; {!! App\Helpers\CustomFormHelper::support_image('uploads/advertise',$banner->mobile_image); !!} </h4>
									  </div>
									  <form method="post" action="<?php echo route('position_update_mobile',(base64_encode($banner->id)));?>">
									  @csrf
										  <div class="modal-body">
											 <div class="assignPopup">
		<label><input type="radio" name="advertisepos1" value="0" <?php echo($banner->mobile_position==0)?'checked':''; ?>  /> None </label>
		<label><input type="radio" name="advertisepos1"  value="1" <?php echo($banner->mobile_position==1)?'checked':''; ?>/> Featured Above  </label>
		<label><input type="radio" name="advertisepos1" value="2" <?php echo($banner->mobile_position==2)?'checked':''; ?>/> Featured Below  </label>
		<label><input type="radio" name="advertisepos1" value="3" <?php echo($banner->mobile_position==3)?'checked':''; ?>/> Left Banner </label>
		<label><input type="radio" name="advertisepos1" value="4" <?php echo($banner->mobile_position==4)?'checked':''; ?> /> Right Banner </label>
		<label><input type="radio" name="advertisepos1" value="5" <?php echo($banner->mobile_position==5)?'checked':''; ?> />  Store Above </label>
		<label><input type="radio" name="advertisepos1" value="6" <?php echo($banner->mobile_position==6)?'checked':''; ?> />  Store Below </label>
											

											</div>
											  	<input type="hidden" name="id" value="<?php echo $banner->id;?>"/>
												<input class="btn btn-info" type="submit" value="Save"/>
										  </div>
									  </form> 
									</div>
								  </div>
								</div>
							
							
								<button type="button" class="btn btn-info btn-md" data-toggle="modal" 
								data-target="#myModal<?php echo $banner->id;?>">Assign Position</button>
								
								<div id="myModal<?php echo $banner->id;?>" class="modal fade" role="dialog">
								  <div class="modal-dialog">
									<div class="modal-content">
									  <div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">Assign Position for &nbsp; {!! App\Helpers\CustomFormHelper::support_image('uploads/advertise',$banner->image); !!} </h4>
									  </div>
									  <form method="post" action="<?php echo route('position_update_advertise',(base64_encode($banner->id)));?>">
									  @csrf
										  <div class="modal-body">
											 <div class="assignPopup">
												<label><input type="radio" name="advertisepos" value="0" checked/> None </label>
												<label><input type="radio" name="advertisepos" value="1"/> Top Left </label>
												<label><input type="radio" name="advertisepos" value="2"/> Top Middle </label>
												<label><input type="radio" name="advertisepos" value="3"/> Top Right </label>
												<label><input type="radio" name="advertisepos" value="4"/> Middle Left </label>
												<label><input type="radio" name="advertisepos" value="5"/> Middle Right </label>
												<label><input type="radio" name="advertisepos" value="6"/> Bottom Left </label>
												<label><input type="radio" name="advertisepos" value="7"/> Bottom Middle </label>
												<label><input type="radio" name="advertisepos" value="8"/> Bottom Right </label>
												<label><input type="radio" name="advertisepos" value="9"/> Footer Left </label>
												<label><input type="radio" name="advertisepos" value="10"/> Footer Right  </label>

											</div>
											  	<input type="hidden" name="id" value="<?php echo $banner->id;?>"/>
												<input class="btn btn-info" type="submit" value="Save"/>
										  </div>
									  </form> 
									</div>
								  </div>
								</div>
							</td>
								
							<td>
							    	<a href="{{route('advertise_sts',[base64_encode($banner->id),base64_encode($banner->status)] )}}"
									onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
									>
										@if($banner->status ==0)         
										<i class="fa fa-close text-red" aria-hidden="true"></i> 
										@else
										<i class="fa fa-check text-green" aria-hidden="true"></i>  
										@endif
									</a>&nbsp; | &nbsp;
							<a href="{{route('edit_advertise', base64_encode($banner->id))}}">
								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
								</a>
							<!--&nbsp; | &nbsp;<a href="{{route('delete_advertise', base64_encode($banner->id))}}"
							onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
							>
							<i class="fa fa-trash text-red" aria-hidden="true"></i></a>-->
							</td>
							
						</tr>
					    @endforeach
					</tbody>
					
				  </table>
				</div>
				
			
				
				 
@endsection
