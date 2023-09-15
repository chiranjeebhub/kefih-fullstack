@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')

  <?php 
  if(@$page_details['Title']=="Delivery Boy"){ 
  
  ?> 
   <div class="allbutntbl">
				<a href="{{route('add')}}"
				class="btn btn-success"
					
				><i class="fa fa-plus" aria-hidden="true"
			
				></i> Add New</a>
			</div>
			<?php if(@$list){  ?>
<table id="example1" class="table table-bordered table-striped">
				  <thead>
					<tr>
                      <th>Id</th>
                      <th>Image</th>
                      <th>City</th>
                      <th>Name</th>
                      <th>Phone</th>
                      <th>Adhaar No.</th>
                      <th>Pan No.</th>
                
                      <th>Pincode</th>
                     <!-- <th>Address</th>-->
                      <th>Action</th>
                    </tr>
				  </thead>
				  <tbody>
					<?php $i=1;
					
						foreach ($list as $row){
					//echo base64_encode($row->id),base64_encode($row->status);die;
					
					$totalassign =DB::table('order_details')->where(['deliveryID'=>$row->id,'order_status'=>'1'])->get();
						$totalcom =DB::table('order_details')->where(['deliveryID'=>$row->id,'order_status'=>'3'])->get();
							?>
					  <tr>
					  
					  <td><?php echo $i++;?></td>
					  <td>
					      {!! App\Helpers\CustomFormHelper::support_image('public/uploads/profile',@$row->image); !!} 
					 </td>
					  <td><?php echo @$row->city;?></td>
                      <td><?php echo $row->name;?></td>
                      <td><?php echo $row->phone;?></td>
                      <td><?php echo $row->addar_no;?></td>
                      <td><?php echo $row->pan_no;?></td>
                      <td><?php echo $row->pincode;?></td>
                     <!-- <td><?php echo $row->address;?></td>-->
                     
                  		
							<td>
							<?php   echo '<span class="btn btn-xs btn-info text-white">'.sizeof(@$totalassign).' Assign Order </span>';  ?>  
							<?php   echo '<span class="btn btn-xs btn-dark text-white">'.sizeof(@$totalcom).' Complete Order </span>';  ?>  
							<a href="{{URL::to('admin/deliveryboy_sts/'.base64_encode($row->id)."/".base64_encode($row->status))}}"
									onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
									>
										@if($row->status ==0)         
										<i class="fa fa-close text-red" aria-hidden="true"></i> 
										@else
										<i class="fa fa-check text-green" aria-hidden="true"></i>  
										@endif
									</a>
									&nbsp;|&nbsp;
									<a href="{{route('edit_deliveryboy', [$row->id])}}"
							onclick = "if (! confirm('Do you want to edit ?')) { return false; }"
							>
								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
								</a>&nbsp;|&nbsp;
								
								
							
									
						&nbsp;|&nbsp;
							<a href="{{route('delete_deliveryboy', base64_encode($row->id))}}"
							onclick = "if (! confirm('Do you want to delete ?')) { return false; }"
							>
							<i class="fa fa-trash text-red" aria-hidden="true"></i></a>
							
       
						
							</td>
                    </tr>
                  <?php }?>
				  </tbody>
				</table>
     
  <?php } }?>
  
           <?php  if(@$page_details['Title']!="Delivery Boy"){ ?>
		  <form role="form" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
									 @csrf
                                    <section>
                                        <div class="row">
                                            <div class="col-sm-6">
            <div class="form-group">
                <select class="form-control form_ctrl _select_arrow custom-select" data-live-search="true"  data-title="Location" name="state" id="selectState">
                    <option value="">Select State</option>
                    
                    @foreach($states as $state)
                    <option value="{{$state->name}}" data-id='{{$state->id}}' {{(@$row->state == $state->name)?'selected':''}}>{{$state->name}}</option>
                    @endforeach
                </select>
                   
                
                 @if($errors->has('state'))
                <span  class="spanErrror">{{ $errors->first('state') }}</span>
                @endif
        </div>
            </div>
             <div class="col-sm-6">
            <div class="form-group">
        <select class="form-control form_ctrl _select_arrow" name="city" id="selectcity">
                  <option value="">Select City</option>
                  @foreach(@$cities as $city)
    								<option value="{{$city->name}}"  {{(@$row->city == $city->name)?'selected':''}}>{{$city->name}}</option>
    								@endforeach
                </select>
                    
              
              @if($errors->has('city'))
                <span  class="spanErrror">{{ $errors->first('city') }}</span>
                @endif  
        </div>
            </div>
                                       <!-- <div class="col-md-4">
                                           <div class="form-group">
    <label for="institution_name">Zone : <span class="danger">*</span> </label>
<select name="zone" class="form-control required">
<option value="">Select Zone </option>
<?php foreach($zonelist as $rowz){ ?>
<option value="<?php echo $rowz->id; ?>" <?php echo(@$row->zone==$rowz->id)?'selected':''; ?>><?php echo ucwords($rowz->name); ?></option>
<?php } ?>
</select> <h6 style="color: red;"></h6></div>
                                           </div>-->
                                           <div class="col-md-6">
                                                <div class="form-group">
                                <label for="institution_name">Name : <span class="danger">*</span> </label>
    <input type="text" class="form-control required" value="<?php echo (@$row->name)?@$row->name:old('name'); ?>" name="name" id="name" placeholder="Name"> </div> 
    <h6 style="color: red;"></h6>
      
                                            </div>
  <div class="col-md-6">
                                                <div class="form-group">
                                <label for="institution_name">Email : <span class="danger">*</span> </label>
    <input type="email"  class="form-control required" value="<?php echo (@$row->email)?@$row->email:old('email'); ?>" name="email" placeholder="Enter your email."> </div>
    <h6 style="color: red;"></h6>
                                                
                                            </div>
                                           <div class="col-md-6">
                                                <div class="form-group">
                                <label for="institution_name">Phone : <span class="danger">*</span> </label>
    <input type="text" minlength="10" maxlength="10" class="form-control required" value="<?php echo (@$row->phone)?@$row->phone:old('phone'); ?>" name="phone" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" placeholder="Enter 10 digit phone no."> </div>
    <h6 style="color: red;"></h6>
                                                
                                            </div>
                                            
                                             <div class="col-md-6">
                                                <div class="form-group">
                                <label for="institution_name">Password : <span class="danger">*</span> </label>
    <input type="password" maxlength="12" class="form-control required" value="" name="password" placeholder="Password"> </div>
    <h6 style="color: red;"></h6>
                                                
                                            </div>
                                             
                                         <div class="col-md-6">
                                                <div class="form-group">
                                <label for="institution_name">Aadhaar Number  : <span class="danger">*</span> </label>
    <input type="text" class="form-control required" value="<?php echo (@$row->addar_no)?@$row->addar_no:old('addar_no'); ?>" name="addar_no" data-type="adhaar-number" maxLength="14" placeholder="Aadhaar No."> </div>
    <h6 style="color: red;"></h6>
                                                
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                <label for="institution_name">Pan Card Number  : <span class="danger">*</span> </label>
    <input type="text" class="form-control required" value="<?php echo (@$row->pan_no)?@$row->pan_no:old('pan_no'); ?>" name="pan_no" placeholder="PAN No." maxlength="10" pattern="[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}" title="Please enter valid PAN number. E.g. AAAAA9999A"> </div>
    <h6 style="color: red;"></h6>
                                                
                                            </div>
                                       <div class="col-md-6">
                                                <div class="form-group">
                                <label for="institution_name">Pincode  : <span class="danger">*</span> </label>
    <input type="text" class="form-control required" value="<?php echo (@$row->pincode)?@$row->pincode:old('pincode'); ?>" name="pincode" placeholder="Pincode" maxlength="6" pattern="[0-9]{6}" title="Please enter valid pincode"> </div>
    <h6 style="color: red;"></h6>
                                                
                                            </div>
                              <div class="col-md-6">
                                                <div class="form-group">
                                <label for="institution_name">Image : <!--<span class="danger">*</span> --> </label>
                                
                    <?php if(@$row->image){ ?> 
                {!! App\Helpers\CustomFormHelper::support_image('public/uploads/profile',@$row->image); !!} 
                                        <?php } ?>
    <input type="file" class="form-control required" name="image"> </div> 
    <h6 style="color: red;"></h6>
                                            </div> 
                                        <div class="col-md-12">
                                                <div class="form-group">
                                <label for="institution_name">Address : <span class="danger">*</span> </label>
    <textarea class="form-control" name="address"><?php echo @$row->address;?></textarea> 
    <h6 style="color: red;"></h6>                          
    </div>
    
                                                
                                            </div>
                                    </section>
                                   
                                        <div class="form-group">
                        <input type="submit" class="btn btn-success" value="Save"> 
                                        </div>
                                         </div>
                            </div>
                             </section>
                  </form>
                  <?php } ?>
                  <script>
$('[data-type="adhaar-number"]').keyup(function() {
  var value = $(this).val();
  value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join("-");
  $(this).val(value);
});

</script>
@endsection
