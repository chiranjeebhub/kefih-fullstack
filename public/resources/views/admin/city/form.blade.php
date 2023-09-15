@extends('admin.layouts.app_new')
@section('pageTitle',$page_details['Title'])
@section('boxTitle',$page_details['Box_Title'])
 @section('backButtonFromPage')
    <a href="{{ route('city') }}" class="btn btn-default goBack">Go Back</a>
    @endsection
@section('content')
<div class="text-warning col-md-12">* Mandatory fields</div>	
<!--<hr>-->	
<form role="form" class="form-element mt15" id="createform" action="{{ $page_details['Action_route']}}" method="post" enctype="multipart/form-data">
    <div class="col-sm-12 manufacturemain">
			   @csrf
		<div class="row">
		<div class="col-sm-6">
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['state_field']); !!}
		</div>
		<div class="col-sm-6">
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['text_field']); !!}
		</div>
            <div class="col-sm-12">
		{!! App\Helpers\CustomFormHelper::form_builder($page_details['Form_data']['Form_field']['submit_button_field']); !!}
            </div>
		</div>
		</div>
 </form>
 <script>


// Variables
const createForm = document.getElementById('createform'),
      createBtn = document.getElementsByClassName('createbtn')[0],
      uname = document.getElementsByName('name')[0],
      state = document.getElementsByName('state')[0];
      
 //Event Listeners
eventListeners();
function eventListeners() {
    
     //App Init
     document.addEventListener('DOMContentLoaded', appInit);
     //Validate the forms
        uname.addEventListener('blur', validateField);
        uname.addEventListener('input', validateField);
        state.addEventListener('blur', validateField);
        state.addEventListener('input', validateField);
       
     // User create button
     createForm.addEventListener('submit', createnew);
   
}  

// Functions

// App Initialization
function appInit() {
     // disable the send button on load
     createBtn.disabled = true;
}  

 function createnew(e) {
    //  e.preventDefault();
     createBtn.disabled = true;
}

// Validate the fields
function validateField() {
     let errors;

     // Validate the Length of the field
     validateLength(this);
     
     // Both will return errors, then check if there're any errors
     errors = document.querySelectorAll('.error');
   
    
     
     // Check that the inputs are not empty
     if(uname.value !== '' && state.value !== '') {
          if(errors.length === 0) {
               // the button should be enabled
               createBtn.disabled = false;
          }
     }
}

// Validate the Length of the fields
function validateLength(field) {
     if(field.value.length > 0 ) {
       field.style.backgroundImage = 'linear-gradient(#006103 , #019019), linear-gradient(#006103, #019019)';
       field.classList.remove('error');
     } else {
        field.style.backgroundImage = 'linear-gradient(#f73939 , #f73939), linear-gradient(#f73939, #f73939)';
        field.classList.add('error');
     }
}


 </script>
 <script src="{{ asset('public/js/validateform.js') }}"></script> 
@endsection


