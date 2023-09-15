@extends('fronted.layouts.app_new')
@section('pageTitle', 'Checkout')
@section('content')
    @section('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ route('index')}}">Home</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)">Checkout</a>
        </li>
        @section('breadcrumb_title', 'Checkout')
        @endsection


    <section class="wrap main_section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-sm-12 col-md-12 col-12 pt-4">
                    
           {{-- @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <span class="help-block"><p style="color:red">{{$error}}</p></span>
                @endforeach
            @endif--}}

                    <h2 class="heading mb-30">Choose a billing address</h2>
                <div class="card_main">
                
                
                
                <div class="row">
                    <?php for($i = 0;$i < count($shipping_listing);$i++){?>
                        <div class="col-md-4 col-xs-12">
                            <div class="row">
                                    <div class="col-lg-9 col-sm-9 col-md-10 col-8">
                                        <div class="card_box"> 
                                            <div class="card_info">
                                                <h2><?php echo ucwords($shipping_listing[$i]['shipping_name']);?> {{ucwords($shipping_listing[$i]['last_name'])}}</h2>
                                                <p><?php echo $shipping_listing[$i]['shipping_address'];?>,<br>
                                                    <?php echo $shipping_listing[$i]['shipping_address1'];?>
                                                    <?php echo $shipping_listing[$i]['shipping_address2'];?>
                                                    <?php echo $shipping_listing[$i]['shipping_city'];?>
                                                    <?php echo $shipping_listing[$i]['shipping_state'];?>
                                                    : <?php echo $shipping_listing[$i]['shipping_pincode'];?></p>
                                                <a href="{{route('selectBillingAddress',base64_encode($shipping_listing[$i]['id']))}}" class="btn btn-warning">Choose this address</a>
                                            </div>
                                        </div>
                                </div>
                                <div class="col-lg-3 col-sm-3 col-md-2 col-4 d-flex">

                                        <div class="remove_card">
                                            <?php if($shipping_listing[$i]['shipping_address_default'] == 1){?>
                                                <span class="defaultbox">Default</span>
                                            <?php }?>
                                            
                                            <a href="{{route('editBillingAddress',base64_encode($shipping_listing[$i]['id']))}}"  class="editbtn pull-left"><i class="fa fa-pencil"></i></a>
                                            
                                            <a href="{{route('removeBillingAddress',base64_encode($shipping_listing[$i]['id']))}}" onclick="if (! confirm('Do you want to delete ?')) { return false; }" class="editbtn pull-right"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    <?php } ?>

                </div>
            </div>
                    </div>
                <div class="col-lg-12 col-sm-12 col-md-12 col-12 pt-4 pb-4">
                    
                    @php 
                    $plusminus = ($errors->any())?'minus':'plus';
                    $plusminusmethod = ($errors->any())?1:0;
                    $isDisplay =  ($errors->any())?'style="display:block"':'style="display:none"';
                    @endphp 
                    
                    <div class="profile_form ">
                        <!--<button class="btn btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAddadress" aria-expanded="false" aria-controls="collapseExample">
                            Add a new address <i class="fa fa-plus"></i>
                        </button>-->
                        <h2 class="heading mb-30">Add New Address <i class="fa fa-{{$plusminus}} iconpointer addAddressForm"
						method="{{$plusminusmethod}}"></i></h2>  
                                            
                        <div id="myAddressForm" {!! $isDisplay !!}>
                            
                    {{--@if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <span class="help-block">
                    			<p style="color:red">{{$error}}</p>
		                        </span>
                        @endforeach
                    @endif--}}

                    <form class="row" action="{{route('addBillingAddress')}}" method="post">
                                    @csrf
                                    <div class="row">
                                        

                                        <div class="col-12 col-sm-6 col-md-4 col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label>First Name</label>
                                                <input type="text" class="form-control" name="shipping_name" id="shipping_name" placeholder="First Name">
                                            
                                            @if ($errors->has('shipping_name'))
                                                      <span class="text-danger">{{ $errors->first('shipping_name') }}</span>
                                             @endif
                                            <div id="shipping_name_error" class="text-danger"></div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-4 col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label>Last Name</label>
                                                <input type="text" class="form-control" name="shipping_last_name" id="shipping_last_name" placeholder="Last Name">
                                                 @if ($errors->has('shipping_last_name'))
                                                      <span class="text-danger">{{ $errors->first('shipping_last_name') }}</span>
                                             @endif
                                             <div id="shipping_last_name_error" class="text-danger"></div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-4 col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label>Email Id</label>
                                                <input type="email" class="form-control" name="shipping_email" id="shipping_email" placeholder="Email Id">
                                            
                                            @if ($errors->has('shipping_email'))
                                                      <span class="text-danger">{{ $errors->first('shipping_email') }}</span>
                                             @endif
                                            <div id="shipping_email_error" class="text-danger"></div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-4 col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label>Mobile Number</label>
                                                <input type="text" class="form-control" name="shipping_mobile" id="shipping_mobile" placeholder="Mobile Number" onkeypress="javascript:return onlyPincodeDigit(event,this.value,9)">
                                             @if ($errors->has('shipping_mobile'))
                                                          <span class="text-danger">{{ $errors->first('shipping_mobile') }}</span>
                                                 @endif
                                                 <div id="shipping_mobile_error" class="text-danger"></div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6 col-md-4 col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label>Pincode</label>
                                                <input type="text" class="form-control" name="shipping_pincode" id="shipping_pincode" placeholder="Pincode" onkeypress="javascript:return onlyPincodeDigit(event,this.value,5)">
                                                 @if ($errors->has('shipping_pincode'))
                                                          <span class="text-danger">{{ $errors->first('shipping_pincode') }}</span>
                                                 @endif
                                                 <div id="shipping_pincode_error" class="text-danger"></div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6 col-md-4 col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" class="form-control" name="shipping_address" id="first_address" placeholder="Address">
                                             @if ($errors->has('shipping_address'))
                                                      <span class="text-danger">{{ $errors->first('shipping_address') }}</span>
                                             @endif
                                             <div id="shipping_address_error" class="text-danger"></div>
                                            </div>
                                        </div>

                                        {{--
                                        <div class="col-12 col-sm-6 col-md-4 col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label>First Name</label>
                                                <input type="text" class="form-control" name="shipping_address1" id="shipping_address1" placeholder="Area, Colony, Street, Sector, Village">
                                             @if ($errors->has('shipping_address1'))
                                                      <span class="text-danger">{{ $errors->first('shipping_address1') }}</span>
                                             @endif
                                             <div id="shipping_address1_error" class="text-danger"></div>
                                            </div>
                                        </div>
                                      

                                        <div class="col-12 col-sm-6 col-md-4 col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label>First Name</label>
                                                <input type="text" class="form-control" name="shipping_address2" id="shipping_address2" placeholder="Landmark e.g. near apollo hospital">
                                             @if ($errors->has('shipping_address2'))
                                                      <span class="text-danger">{{ $errors->first('shipping_address2') }}</span>
                                             @endif
                                             <div id="shipping_address2_error" class="text-danger"></div>
                                            </div>
                                        </div>
                                        --}}
                                        <div class="col-12 col-sm-6 col-md-4 col-md-4 col-lg-4">
                                            <div class="form-group editionaldropdn">
                                                <label>State</label>
                                                <select class="form-control" name="shipping_state"
                                                        id="selectState">
                                                    <option value="">Select State</option>
                                                    @foreach($states as $state)
                                                        <option value="{{$state->id}}">{{$state->name}}</option>
                                                    @endforeach
                                                </select>
                                                    @if ($errors->has('shipping_state'))
                                                      <span class="text-danger">{{ $errors->first('shipping_state') }}</span>
                                             @endif
                                             <div id="selectState_error" class="text-danger"></div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6 col-md-4 col-md-4 col-lg-4">
                                            <div class="form-group editionaldropdn">
                                                <label>City</label>
                                                <select class="form-control" name="shipping_city" id="selectcity">
                                                    <option value="">Select City</option>
                                                    
                                                </select>
                                                 @if ($errors->has('shipping_city'))
                                                      <span class="text-danger">{{ $errors->first('shipping_city') }}</span>
                                                 @endif
                                                <div id="selectcity_error" class="text-danger"></div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6 col-md-4 col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label>Address Type</label>
                                                <select name="shipping_address_type" class="form-control"
                                                        id="shipping_address_type">
                                                    <option value="">---Select Address Type---</option>
                                                    <option value="1">Home</option>
                                                    <option value="2">Office</option>
                                                    <option value="3">Others</option>
                                                </select>
                                                 @if ($errors->has('shipping_address_type'))
                                                      <span class="text-danger">{{ $errors->first('shipping_address_type') }}</span>
                                             @endif
                                             <div id="shipping_address_type_error" class="text-danger"></div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-group switch_box">
                                        <div class="form-check form-switch">
                                            <?php if(sizeof($shipping_listing) > 0){?>
                                                <input id="default_address" name="payment_mode" type="checkbox" checked="" value="0" class="form-check-input">
                                                <label class="form-check-label label-trms" for="default_address">  Save as a default address</label>
                                            <?php } else{?>
                                                <input id="default_address" name="payment_mode" type="checkbox" checked="" value="1" class="form-check-input">
                                                <label class="form-check-label label-trms" for="default_address">  Save as a default address</label>
                                            <?php }?>
                                                @if ($errors->has('email'))
                                              <span class="text-danger">{{ $errors->first('email') }}</span>
                                             @endif
                                             <div id="payment_mode_error" class="text-danger"></div>
                                                


                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                            <button type="submit" value="submit" class="btn btn-warning btn-block getDataforCheck">Save</button>
                                        </div>
                                    </div>
                                </form>
                          
                </div>
                    </div>
                </div>
            
            </div>
        </div>
    </section>


@endsection
@push('scripts')
<script>
    $('.getDataforCheck').click(function(e){
        
             var shipping_name = $("#shipping_name").val(); 
             var shipping_last_name = $("#shipping_last_name").val(); 
             var shipping_mobile = $("#shipping_mobile").val(); 
             var shipping_pincode = $("#shipping_pincode").val(); 
             var shipping_address = $("#first_address").val(); 
             var shipping_email = $('#shipping_email').val();

            /* var shipping_address1 = $("#shipping_address1").val(); 
             var shipping_address2 = $("#shipping_address2").val(); */
             var selectState = $("#selectState").val(); 
             var selectcity = $("#selectcity").val();
             var shipping_address_type = $('#shipping_address_type').val();
             let reg_pincode = $("#shipping_pincode").val();
            // alert(shipping_address);

            if(shipping_email==''){
                 $("#shipping_email_error").html('Enter Email');
            }else{
                 $("#shipping_email_error").html('');
            }


            if(shipping_name==''){
                $("#shipping_name_error").html('Enter Name');
            }else{
                $("#shipping_name_error").html('');
            }
            if(shipping_last_name==''){
                $("#shipping_last_name_error").html('Enter Last Name');
            }else{
                $("#shipping_last_name_error").html('');
            }
            if(shipping_mobile==''){
                $("#shipping_mobile_error").html('Enter a Mobile Number');
            }else{
                $("#shipping_mobile_error").html('');
            }
            if(!/^(\d{4}|\d{6})$/.test(reg_pincode)){
                $("#shipping_pincode_error").html('Pincode should be a 6-digit number');
            }else{
                $("#shipping_pincode_error").html('');
            }
            if(shipping_address==''){
                $("#shipping_address_error").html('Enter Address');
            }else{
                $("#shipping_address_error").html('');
            }
           /* if(shipping_address1==''){
                $("#shipping_address1_error").html('Pincode should be a 6-digit number');
            }else{
                $("#shipping_address1_error").html('');
            }
            if(shipping_address2==''){
                $("#shipping_address2_error").html('Pincode should be a 6-digit number');
            }else{
                $("#shipping_address2_error").html('');
            }*/
            if(selectState==''){
                $("#selectState_error").html('Select State');
            }else{
                $("#selectState_error").html('');
            }
            if(selectcity==''){
                $("#selectcity_error").html('Select City');
            }else{
                $("#selectcity_error").html('');
            }
             if(shipping_address_type==''){
                $("#shipping_address_type_error").html('Select Address Type');
            }else{
                $("#shipping_address_type_error").html('');
            }
            if(shipping_name==''||shipping_last_name =='' || shipping_mobile =='' || reg_pincode=='' || shipping_address == '' || selectState=='' ||selectcity=='' || shipping_address_type ==''){
                return false; 
            }else{
               return true; 
            }
            
       
    });
</script>
@endpush






