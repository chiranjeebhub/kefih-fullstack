@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['page'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')
<?php if(@$page_details['page']=="Timeslot"){?>




	<div class="allbutntbl">
	  <?php if(@$page_details['url']!=''):?>
	 
	  <span class="btn btn-success btn-gradient btn-sm pull-right ml_20"><a class="clr_white" href="{{$page_details['url']}}">Add </a></span>
	  
	  <?php endif; 
	 if(@$page_details['backurl']!=''):?>
	 
	<span class="btn btn-dark btn-gradient btn-sm pull-right ml_20"><a class="clr_white" href="{{$page_details['backurl']}}">Go Back</a></span>  
	
					
	<?php endif;?>
	
  </div>

<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="headingbox">
	  @if(Session::has('success'))
    <div class="alert alert-success"> {{ Session::get('success') }}</div>
     @endif
     
     @if(Session::has('danger'))
    <div class="alert alert-danger"> {{ Session::get('danger') }}</div>
     @endif
		
	</div>
	
</div>		
</div>
 <div class="table-responsive">



                            <table id="example" class="table table-bordered table-striped">



                                <thead>



                                    <tr class="">



                                      
                                        <th>Sno.</th>
<!--<th>Date</th>-->
                                        <th>Time Slot</th>
<th>Price</th>
                                        <th>Action</th>
                                    </tr>

                                </thead>



                                <tbody>



                                    <?php $i=1; foreach($list as $rows){?>

                                    <tr>



    <td><?php echo $i++; ?></td>



                                       

<!--<td><?php echo $rows->date ?></td>-->

                                        <td><?php echo ucwords($rows->name) ?></td>
 <td><?php echo $rows->price ?></td>

                                        <td>

<a href="{{route('timeslot_sts',[base64_encode($rows->id),base64_encode($rows->status)] )}}"
									onclick = "if (! confirm('Do you want to change status ?')) { return false; }"
									>
										@if($rows->status ==0)         
										<i class="fa fa-close text-red" aria-hidden="true"></i> 
										@else
										<i class="fa fa-check text-green" aria-hidden="true"></i>  
										@endif
									</a>
									&nbsp;|&nbsp;

<a href="{{route('timeslotEdit', [$rows->id])}}"
							onclick = "if (! confirm('Do you want to edit ?')) { return false; }"
							>
								<i class="fa fa-pencil text-blue" aria-hidden="true"></i>
								</a>

&nbsp; | &nbsp;

                                    <a href="{{route('timeslotDelete',[$rows->id])}}"

                                    onclick = "if (! confirm('Do you want to delete ?')) { return false; }"

                                    >

							<i class="fa fa-trash text-red" aria-hidden="true"></i></a>


								</td>



							 </tr>	





                                    <?php } ?>



                                </tbody>



                              



                            </table>



                       </div>
<?php } else {?>
<div class="col-sm-12">
<form role="form" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
									 @csrf
                      	



                                    <section>



                                        <div class="row">

<!--<div class="col-sm-4">
			<div class="form-group"><label for="exampleInputEmail1">Expiry Date</label> <input type="text" name="date" value="<?php echo @$row->date;?>" class="datepicker form-control" id="expire_date_field" placeholder=""></div>
		</div>-->
                                        <div class="col-md-4">



                                                <div class="form-group">



                                <label for="institution_name">Time Slot : <span class="danger">*</span> </label>



    <input type="text" class="form-control required" value="<?php echo @$row->name;?>" name="name"> </div> 



    <h6 style="color: red;margin-left: 20px;"><?php //echo form_error('coupon_code', '<div class="error">', '</div>'); ?></h6>



                                                



                                            </div>
 <div class="col-md-4">



                                                <div class="form-group">



                                <label for="institution_name">Price : <span class="danger">*</span> </label>



    <input type="text" class="form-control required" value="<?php echo @$row->price;?>" name="price" onchange="validateFloatKeyPress(this);"> </div> 



    <h6 style="color: red;margin-left: 20px;"><?php //echo form_error('coupon_code', '<div class="error">', '</div>'); ?></h6>



                                                



                                            </div>
                       </div> 



                       <div class="form-group">



                        <input type="submit" class="btn  btn-success" value="Save"> 



                                        </div>



                               

                                     



                                    </section>
</form>
</div>
<?php } ?>
@endsection







