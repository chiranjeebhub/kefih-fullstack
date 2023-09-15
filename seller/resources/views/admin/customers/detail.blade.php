@extends('admin.layouts.app_new')
@section('pageTitle', 'Customer Detail')
 @section('backButtonFromPage')
    <a href="javascript:void(0)" class="btn btn-default goBack">Go Back</a>
    @endsection
@section('content')

    <!-- Main content -->
    <section class="content">
	  <div class="row">
        
       <div class="col-md-3 col-xs-12">
                        
           <div class="addressadmin">
		   		
				<p><span>Name </span>: &nbsp; {{$customer->name}} </p>
				<p><span>Phone </span>: &nbsp; {{$customer->phone}}</p>
				<p><span>Email </span>: &nbsp; {{$customer->email}}</p>
				<p><span>Dob </span>: &nbsp; {{$customer->dob}}</p>
				<p><span>Gender </span>: &nbsp; 
						@if($customer->gender!='')

								@switch($customer->gender)
								@case(1)
								Male
								@break

								@case(2)
								Female
								@break
								@endswitch

						@else
						NA</p>
						@endif
				
                @if($customer->profile_pic!='')
			   <p><span>Profile </span>: &nbsp; 
					{!! App\Helpers\CustomFormHelper::support_image('uploads/customers/profile_pic',$customer->profile_pic); !!}</p>
                @endif
           </div>
       </div>
        <div class="col-md-3 col-xs-12">
            <div class="adresbox permanent">
				<h2>Permanent Address</h2>
				{{ $customer->address }}
				{{ $customer->city }} 
				{{ $customer->state }}
				{{ $customer->pincode }}
			</div>
        </div>
		<?php foreach($shipping_listing as  $address){?>
			<div class="col-md-3 col-xs-12">
				<div class="adresbox">

					<h2><?php echo ucwords($address['shipping_name']);?></h2>
					<p>	<?php echo $address['shipping_address'];?>,<br>
						<?php echo $address['shipping_address1'];?>
						<?php echo $address['shipping_address2'];?>
						<?php echo $address['shipping_city'];?>
						<?php echo $address['shipping_state'];?> : <?php echo $address['shipping_pincode'];?></p>


					<?php  if($address['shipping_address_default']){?>
					<div class="defaultAddress">
						<span class="ribbon6">Default Address</span>
					</div>

					<?php }?>
				</div>
			</div>
			<?php } ?>
		  
		  
      </div>
    
      <div class="row">
					
					
				</div>
      
	
      <!-- /.row -->	      
	</section>
    <!-- /.content -->
@endsection
