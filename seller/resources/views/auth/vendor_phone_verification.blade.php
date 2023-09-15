@extends('admin.layouts.app')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="panel" style="background:none;">
                <div class="demo">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-7  col-sm-6 box-shadow">
                                <div class="panel-heading">
                                    <div class="register2">
                                        <h2>Sell to crores of customers on Our Portal, right from your doorstep!</h2>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12 left-col">
                                    <div class="left-container">
                                        <div class="pointsForSeller row">
                                            <div class="pointsDiv col-sm-4" id="point1">
                                                <div class="pointTitle">
                                                    <div class="point"><span class="pointNumber">1</span></div>
                                                    <span class="pointName"> Get
                                                        <span class="pointDescription">Access to 1 Cr+ registered customers </span>
                                                    </span>
                                                    <div class="clearfix"></div>
                                                </div>

                                            </div>
                                            <div class="pointsDiv col-sm-4" id="point2">
                                                <div class="pointTitle">
                                                    <div class="point"><span class="pointNumber">2</span></div>
                                                    <span class="pointName"> Set
                                                        <span class="pointDescription">World Wide business with minimal investment</span>
                                                    </span>
                                                    <div class="clearfix"></div>
                                                </div>

                                            </div>
                                            <div class="pointsDiv col-sm-4" id="point3">
                                                <div class="pointTitle">
                                                    <div class="point"><span class="pointNumber">3</span></div>
                                                    <span class="pointName"> Sell
                                                        <span class="pointDescription">Online 24 X 7 without any hassle </span>
                                                    </span>
                                                    <div class="clearfix"></div>
                                                </div>

                                            </div>

                                            <div class="brline col-sm-12"></div>
                                        </div>

                                        <div class="requirementContainer col-sm-12">
                                            <div id="requirementHeader">All you need to sell on our Portal is: </div>
                                            <div class="requirementSection row">
                                                <div class="requirements col-sm-6">
                                                    <div class="requirementLogo">
                                                        <img src="{{ asset('public/images/gsticon.png') }}">
                                                        <span class="requirementTitle">GST Identification Number</span>
                                                    </div>

                                                </div>
                                                <div class="requirements col-sm-6">
                                                    <div class="requirementLogo">
                                                        <img src="{{ asset('public/images/bankicon.png') }}">
                                                        <span class="requirementTitle">Pincode</span>
                                                    </div>

                                                </div>

                                                <div class="requirements col-sm-6">
                                                    <div class="requirementLogo">
                                                        <img src="{{ asset('public/images/pancardicon.png') }}">
                                                        <span class="requirementTitle">PAN Card</span>
                                                    </div>

                                                </div>
                                                <div class="requirements col-sm-6">
                                                    <div class="requirementLogo">
                                                        
                                                        <img src="{{ asset('public/images/producticon.png') }}">
                                                        <span class="requirementTitle">Unique Products to Sell</span>
                                                    </div>

                                                </div>
                                                <div class="brline col-sm-12"></div>
                                                <!--<strong>How will this information be used?</strong>
                                                <p>You can use your email address or mobile number as 'Username' to login to your Seller Account.</p>
                                                <p>Please note, the 'Username' and 'Password' used here are only to access your Seller Account and can’t be used on Flipkart.com shopping destination.</p>-->
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 col-sm-6 box-shadow">
                                <div class="register_form">
                                    <h2>Create your Seller Account</h2>
									<form role="form" class="lg-frm" id="vendorForm" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
									@csrf
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['seller_name_field']); !!}
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['email_field']); !!}
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['phone_field']); !!}
										
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['otp_field']); !!}
									
                    
                     <div class="form-group">	
                                    <span class="vendor_phone_resend_button">
                                    <i class="fa fa-exchange vendor_phone_resend_button2" aria-hidden="true" ></i> Resend OTP</span>
                                    </div>
                                    <span class="vendor_phone_return_message"></span>
                                    
                                    <div class="form-group">
                    <input type="text" name="company_name" value="" class=" form-control" id="company_name" placeholder="Company Name">
                    </div>
                    
                     <div class="form-group">
                        <input type="text" name="gst_no" value="" class="form-control" id="gst_no" placeholder="GST NO.">
                    </div>
                    
                     <div class="form-group">
                    <input type="text" name="pincode" value="" class=" form-control" id="pincode" placeholder="Pincode">
                    </div>
					 <p>
                    <button type="submit" class="registrbtn btn btn-danger"><i class="fa fa-user"></i> Proceed</button>
                    <p>Already a Seller? <a href="{{ route('vendor_login') }}">Login Here</a> </p>    
                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>





            </div>
        </div>
    </div>
</div>

@include('vendor.includes.script')
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="{{ asset('public/js/validateform.js') }}"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>



$('#vendorForm').validate({ // initialize the plugin
    rules: {
        gst_no: {
            // required: true,
            gst: true
        },
     

    }, 
     messages: { 
        gst_no: { 
                required: "GST number is required", 
                gst: "Please enter a valid gst number"
            },
        },
});

$.validator.addMethod("gst", function(value3, element3) {
    var gst_value = value3.toUpperCase();
    var reg = /^([0-9]{2}[a-zA-Z]{4}([a-zA-Z]{1}|[0-9]{1})[0-9]{4}[a-zA-Z]{1}([a-zA-Z]|[0-9]){3}){0,15}$/;
    if (this.optional(element3)) {
      return true;
    }
    if (gst_value.match(reg)) {
      return true;
    } else {
      return false;
    }

  }, "Please specify a valid GSTTIN Number");

</script>  

@endsection
