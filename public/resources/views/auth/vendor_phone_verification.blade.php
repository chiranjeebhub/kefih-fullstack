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
                                                        <span class="pointDescription">Access to 10 Cr+ registered customers </span>
                                                    </span>
                                                    <div class="clearfix"></div>
                                                </div>

                                            </div>
                                            <div class="pointsDiv col-sm-4" id="point2">
                                                <div class="pointTitle">
                                                    <div class="point"><span class="pointNumber">2</span></div>
                                                    <span class="pointName"> Set
                                                        <span class="pointDescription">Pan-India business with minimal investment</span>
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
                                                        <img src="http://b2csoftwares.com/vendor/assets/images/gsticon.png">
                                                        <span class="requirementTitle">GST Identification Number</span>
                                                    </div>

                                                </div>
                                                <div class="requirements col-sm-6">
                                                    <div class="requirementLogo">
                                                        <img src="http://b2csoftwares.com/vendor/assets/images/bankicon.png">
                                                        <span class="requirementTitle">Bank Account Details</span>
                                                    </div>

                                                </div>

                                                <div class="requirements col-sm-6">
                                                    <div class="requirementLogo">
                                                        <img src="http://b2csoftwares.com/vendor/assets/images/pancardicon.png">
                                                        <span class="requirementTitle">PAN Card</span>
                                                    </div>

                                                </div>
                                                <div class="requirements col-sm-6">
                                                    <div class="requirementLogo">
                                                        <img src="http://b2csoftwares.com/vendor/assets/images/producticon.png">
                                                        <span class="requirementTitle">Unique Products to Sell</span>
                                                    </div>

                                                </div>
                                                <div class="brline col-sm-12"></div>
                                                <strong>How will this information be used?</strong>
                                                <p>You can use your email address or mobile number as 'Username' to login to your RedlipsSeller Account.</p>
                                                <p>Please note, the 'Username' and 'Password' used here are only to access your RedlipsSeller Account and can’t be used on Flipkart.com shopping destination.</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 col-sm-6 box-shadow">
                                <div class="register_form">
                                    <h2>Create your Seller Account</h2>
									<form role="form" class="lg-frm" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
									@csrf
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['seller_name_field']); !!}
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['email_field']); !!}
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['phone_field']); !!}
										
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['otp_field']); !!}
										
										@include('fronted.includes.otp_resend')
										
										
											{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['password_field']); !!}
                                       

                                        <div class="form-group form-selector">
                                            <span>If you have read and agree to the <a href="#">Terms and Conditions</a>, please continue</span>
                                        </div>
										{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
                                       
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

@include('admin.includes.Ajax.common') 
@endsection
