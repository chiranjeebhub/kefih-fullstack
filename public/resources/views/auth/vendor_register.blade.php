@extends('admin.layouts.app')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
@section('content')

<section class="v-lgn">
    <div class="container">
        <div class="row">
            <div class="col-md-8  col-sm-6 box-shadow">
                   <img src="{{ asset('public/images/logo.png') }}">
            </div>
            <div class="col-md-4 col-sm-6 box-shadow">
                <div class="register_form">
						@if($errors->any())
						<h4>{{$errors->first()}}</h4>

						@endif
                    <h2>Register</h2>
                <form role="form" class="form-element" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
			   @csrf
                       
			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['email_field']); !!}

			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['phone_field']); !!}

			{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
		
	
                       

                    </form>
                    <div class="form-group text-center">
                        <p class="pd-15">Already Registered? <a href="{{ route('vendor_login') }}">Login Here</a></p>                        
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
