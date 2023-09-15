console.log('Form Validation Loaded Successfully ..... ');

// Restricts input for the set of matched elements to the given inputFilter function.
(function($) {
  $.fn.inputFilter = function(inputFilter) {
    return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
      if (inputFilter(this.value)) {
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      } else {
        this.value = "";
      }
    });
  };
}(jQuery));

$('#radio_invoice_logo0').click(function(){

    $('#radio_invoice_address0').prop('checked', true);
});
$('#radio_invoice_logo1').click(function(){

   $('#radio_invoice_address1').prop('checked', true);
});

$('#radio_invoice_address0').click(function(){
    $('#radio_invoice_logo0').prop('checked', true);
});
$('#radio_invoice_address1').click(function(){
   $('#radio_invoice_logo1').prop('checked', true);
});


//-------------------- Regex For validation Start -------------------------//
var onlynumRGEX= /^\d*$/;
var alphawithspacehiphenRGEX = /^[a-zA-Z\s]*$/;
var alphanumwithspaceRGEX = /^[a-zA-Z0-9\s]*$/;
var pinRGEX = /^[1-9][0-9]{5}$/;
var gstRGEX = /\d{2}[A-Z]{5}\d{4}[A-Z]{1}\d[Z]{1}[A-Z\d]{1}$/;

var discountRGEX = /^[1-9][0-9]$/;

var numRGEX = /^[1-9]{1}[0-9]{9}$/;
//var pinRGEX = /^\d{6}$/;
var panRGEX = /[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
var numAlpha = /^[a-zA-Z0-9]*$/;


//-------------------- Regex For validation End -------------------------//




/*--------------------------------------------------------------
  |             Vendor Login                                   |
  --------------------------------------------------------------*/
  
  $("#vdr_login").submit(function(){
      
    var password = $('#password').val();
    var email = $('#email').val(); 
    var is_valid_email = validateEmail(email);
     $('#email_error').remove();
     $('#password_error').remove();
     
     if(email === ''){
        $('#form_heading').after("<h4 id='email_error'>The email field is required</h4>");
    }
    
    if(password === ''){
        $('#form_heading').after("<h4  id='password_error'>The password field is required.</h4>");
    }
    
    if(email === ''){
        $('#email_error').remove();
        $('#form_heading').after("<h4  id='email_error'>The email field is required</h4>");
        return false;
    }else if(!is_valid_email){
          $('#password_error').remove();
         $('#form_heading').after("<h4  id='email_error'>Invalid Email Address</h4>");
         return false;
    }
    
    if(password === ''){
          $('#password_error').remove();
        $('#form_heading').after("<h4  id='password_error'>The password field is required.</h4>");
        return false;
    }else if(password.length > 20){
          $('#password_error').remove();
         $('#form_heading').after("<h4  id='password_error'> Password can't be more than 20 characters</h4>");
         return false;
    }
   
    return true;
  });

$('#password').on('propertychange input', function (e) {
       var password = $('#password').val();
       if(password.length > 20){
            $('#password_error').remove();
            $('#password').after("<small class='dangererror pull-left' id='password_error'>Password should be less than 20 characters</small>");
            return false;
       }
  });

$('#confirm_password').on('propertychange input', function (e) {
       var confirm_password = $('#confirm_password').val();
       if(confirm_password.length > 20){
            $('#confirm_password_error').remove();
            $('#confirm_password').after("<small class='dangererror pull-left' id='confirm_password_error'>Confirm password should be less than 20 characters</small>");
            return false;
       }
  });
  
/*--------------------------------------------------------------
  |             Vendor Regstration Step 1 Validation Start      |
  --------------------------------------------------------------*/

$('#mobile').inputFilter(function(value){
    var phone = $('#mobile').val();
    if(phone.length > 10){
        $('#mobile_error').remove();
        $('#mobile').after("<small class='dangererror pull-left' id='mobile_error'>Mobile no. can't be more than 10 digit</small>");
     return false;
    }else{
        if(onlynumber(phone) === false)
        {
            $('#mobile_error').remove();
            $('#mobile').after("<small class='dangererror pull-left' id='mobile_error'>Only Number Allowed</small>");
        }
      return onlynumber(phone);
    }
   
});

$(".registrbtn").click(function(){
    
    var phone = $('#mobile').val();
    var email = $('#email').val(); 
    var is_valid_email = validateEmail(email);
     $('#email_error').remove();
     $('#mobile_error').remove();
     
     if(email === ''){
        $('#email').after("<small class='dangererror pull-left' id='email_error'>Email is required</small>");
    }
    
    if(phone === ''){
        $('#mobile').after("<small class='dangererror pull-left' id='mobile_error'>Mobile No. is required</small>");
    }
    
    if(email === ''){
        $('#email_error').remove();
        $('#email').after("<small class='dangererror pull-left' id='email_error'>Email is required</small>");
        return false;
    }else if(!is_valid_email){
         $('#email_error').remove();
         $('#email').after("<small class='dangererror pull-left' id='email_error'>Invalid Email Address</small>");
         return false;
    }else{
         $('#email_error').remove();
    }
    
    if(phone === ''){
        $('#mobile_error').remove();
        $('#mobile').after("<small class='dangererror pull-left' id='mobile_error'>Mobile No. is required</small>");
        return false;
    }else if(phone.length > 10 || phone.length < 10){
         $('#mobile_error').remove();
         $('#mobile').after("<small class='dangererror pull-left' id='mobile_error'> Invalid Mobile No.</small>");
         return false;
    }else{
         $('#mobile_error').remove();
        
    }
   
    return true;
});


/*--------------------------------------------------------------
  |             Vendor Regstration Step 2 Validation Start      |
  --------------------------------------------------------------*/

$('#otp').inputFilter(function(value){
    var otp = $('#otp').val();
    if(otp.length > 4){
        $('#otp_error').remove();
        $('#otp').after("<small class='dangererror pull-left' id='otp_error'>OTP can't be more than 4 digit</small>");
     return false;
    }else{
        if(onlynumber(otp) === false)
        {
            $('#otp_error').remove();
            $('#otp').after("<small class='dangererror pull-left' id='otp_error'>Only Number Allowed</small>");
        }
      return onlynumber(otp);
    }
   
});

$('#pincode').inputFilter(function(value){
    var pincode = $('#pincode').val();
    if(pincode.length > 6){
        $('#pincode_error').remove();
        $('#pincode').after("<small class='dangererror pull-left' id='pincode_error'>Pincode can't be more than 6 digit</small>");
     return false;
    }else{
        if(onlynumber(pincode) === false)
        {
            $('#pincode_error').remove();
            $('#pincode').after("<small class='dangererror pull-left' id='pincode_error'>Only Number allowed</small>");
        }else if(validpincode(pincode)===false){
            $('#pincode_error').remove();
            $('#pincode').after("<small class='dangererror pull-left' id='pincode_error'>Invalid Pincode</small>");
        }else{
			$('#pincode_error').remove();
		}
        
        
      return onlynumber(pincode)|| validpincode(pincode) ;
    }
   
});


/*
$('#gst_no').inputFilter(function(value){
    var gst_no = $('#gst_no').val();
    var status = onlynumAlpha(gst_no);
    if(gst_no.length > 15){
        $('#gst_no_error').remove();
        $('#gst_no').after("<small class='dangererror pull-left' id='gst_no_error'>GST No. can't be more than 15 Characters</small>");
        return false;
    }else{
        if(status === false){
            $('#gst_no_error').remove();
            $('#gst_no').after("<small class='dangererror pull-left' id='gst_no_error'>Only Number and Alphabets allowed</small>");
        }
        return status;
    }
   
   
});
*/

$('#username').inputFilter(function(value){
    var username = $('#username').val();
    var status = alphawithspacehiphen(username);
    
    if(username.length > 50){
        $('#username_error').remove();
        $('#username').after("<small class='dangererror pull-left' id='username_error'>More than 50 characters not allowed</small>");
        return false;
    }else{
           if(status === false){
                $('#username_error').remove();
                $('#username').after("<small class='dangererror pull-left' id='username_error'>Only Alphabet and space allowed</small>");
        }
        return status;
    }
    
    
   
   
});

// $('#company_name').inputFilter(function(value){
//     var company_name = $('#company_name').val();
//     //var status = alphanumwithspace(company_name);
//     if(company_name.length > 50){
//         $('#company_name_error').remove();
//         $('#company_name').after("<small class='dangererror pull-left' id='company_name_error'>Company Name can't be more than 50 characters</small>");
//         return false;
//     }else{
//     	return true;
//         /*if(status === false){
//                 $('#company_name_error').remove();
//                 $('#company_name').after("<small class='dangererror pull-left' id='company_name_error'>Only Alphabet, Number and space allowed</small>");
//         }*/
        
//       //  return status;
//     }
    
// });


$("#vendor_reg_step_2").submit(function(){
    
      var sellername = $('#username').val();
      var otp = $('#otp').val();
      var pincode = $('#pincode').val();
      var gst_no = $('#gst_no').val();
      var password = $('#password').val();
      var confirm_password = $('#confirm_password').val();
      var company_name = $('#company_name').val();
      
       $('#username_error').remove();
       $('#otp_error').remove();
       $('#password_error').remove();
       $('#confirm_password_error').remove();
       $('#company_name_error').remove();
       $('#gst_no_error').remove();
       $('#pincode_error').remove();
       
       if(sellername === ''){
            $('#username').after("<small class='dangererror pull-left' id='username_error'>Seller name is required</small>");
      }
      
      if(otp === ''){
            $('#otp').after("<small class='dangererror pull-left' id='otp_error'>OTP is required</small>");
      }
       if(password === ''){
            $('#password').after("<small class='dangererror pull-left' id='password_error'>Password is required</small>");
      }
      
      if(confirm_password === ''){
            $('#confirm_password').after("<small class='dangererror pull-left' id='confirm_password_error'>Confirm Password is required</small>");
      }
      
      if(company_name === ''){
            $('#company_name').after("<small class='dangererror pull-left' id='company_name_error'>Company name is required</small>");
      }
      
      if(gst_no === ''){
            $('#gst_no').after("<small class='dangererror pull-left' id='gst_no_error'>GST No. is required</small>");
      }
      
      if(pincode === ''){
            $('#pincode').after("<small class='dangererror pull-left' id='pincode_error'>Pincode is required</small>");
      }
      
      
      
      if(sellername === ''){
            $('#username_error').remove();
            $('#username').after("<small class='dangererror pull-left' id='username_error'>Seller name is required</small>");
            return false;
      }else if(sellername.length < 7){
          
            $('#username_error').remove();
            $('#username').after("<small class='dangererror pull-left' id='username_error'>Seller name should be more than 6 characters</small>");
            return false;
            
      }else if(sellername.length > 50){
          
            $('#username_error').remove();
            $('#username').after("<small class='dangererror pull-left' id='username_error'>Seller name can't be more than 50 characters</small>");
            return false;
            
      }else if(!alphawithspacehiphen(sellername)){
          
            $('#username_error').remove();
            $('#username').after("<small class='dangererror pull-left' id='username_error'>Only Alphabet and space allowed</small>");
            return false;
            
      }else{
          $('#username_error').remove();
      }
      
      if(otp === ''){
            $('#otp_error').remove();
            $('#otp').after("<small class='dangererror pull-left' id='otp_error'>OTP is required</small>");
            return false;
      }else if(otp.length != 4){
           $('#otp_error').remove();
           $('#otp').after("<small class='dangererror pull-left' id='otp_error'>Invalid OTP (It should be 4 digit)</small>");
            return false;
      }else{
          $('#otp_error').remove();
      }
      
      
      if(password === ''){
            $('#password_error').remove();
            $('#password').after("<small class='dangererror pull-left' id='password_error'>Password is required</small>");
            return false;
      }else if(password.length > 20){
           $('#password_error').remove();
            $('#password').after("<small class='dangererror pull-left' id='password_error'>Password should be less than 20 characters</small>");
            return false;
      }else{
          $('#password_error').remove();
      }
      
      if(confirm_password === ''){
            $('#confirm_password_error').remove();
            $('#confirm_password').after("<small class='dangererror pull-left' id='confirm_password_error'>Confirm Password is required</small>");
            return false;
      }else if(confirm_password.length > 20){
            $('#confirm_password_error').remove();
            $('#confirm_password').after("<small class='dangererror pull-left' id='confirm_password_error'>Confirm password should be less than 20 characters</small>");
            return false;
      }else if(confirm_password != password){
             $('#confirm_password_error').remove();
            $('#confirm_password').after("<small class='dangererror pull-left' id='confirm_password_error'>Confirm password and password both should be same</small>");
            return false;
          }else{
          $('#confirm_password_error').remove();
      }
      
    //   if(company_name === ''){
           
    //         $('#company_name_error').remove();
    //         $('#company_name').after("<small class='dangererror pull-left' id='company_name_error'>Company name is required</small>");
    //         return false;
            
    //   }else if(company_name.length < 6){
          
    //         $('#company_name_error').remove();
    //         $('#company_name').after("<small class='dangererror pull-left' id='company_name_error'>Company name should be more than 5 characters</small>");
    //         return false;
            
    //   }else if(company_name.length > 50){
          
    //         $('#company_name_error').remove();
    //         $('#company_name').after("<small class='dangererror pull-left' id='company_name_error'>Company name can't be more than 255 characters</small>");
    //         return false;
            
    //   }
      /*else if(!alphanumwithspace(company_name)){
          
            $('#company_name_error').remove();
            $('#company_name').after("<small class='dangererror pull-left' id='company_name_error'>Only Alphabet, Number and space allowed</small>");
            return false;
            
      }*/
    //   else{
    //       $('#company_name_error').remove();
    //   }
      
      
      if(gst_no === ''){
            $('#gst_no_error').remove();
            $('#gst_no').after("<small class='dangererror pull-left' id='gst_no_error'>GST No. is required</small>");
            return false;
      }else if(gst_no.length != 15){
          
            $('#gst_no_error').remove();
            $('#gst_no').after("<small class='dangererror pull-left' id='gst_no_error'>Invalid GST (It should be 15 characters)</small>");
            return false;
            
      }else if(!validGST(gst_no)){
            $('#gst_no_error').remove();
            $('#gst_no').after("<small class='dangererror pull-left' id='gst_no_error'>Invalid GST No.</small>");
            return false;
    }else{
          $('#gst_no_error').remove();
      }
      
      if(pincode === ''){
            $('#pincode_error').remove();
            $('#pincode').after("<small class='dangererror pull-left' id='pincode_error'>Pincode is required</small>");
            return false;
      }else if(pincode.length != 6){
          
            $('#pincode_error').remove();
            $('#pincode').after("<small class='dangererror pull-left' id='pincode_error'>Pincode should be 6 digit</small>");
            return false;
            
      }else if(!validpincode(pincode)){
            $('#pincode_error').remove();
            $('#pincode').after("<small class='dangererror pull-left' id='pincode_error'>Invalid Pincode</small>");
            return false;
    }else{
          $('#pincode_error').remove();
      }
      
      return true;
      
});


/*----------------------------------------
 |          Vendor Panel Add Address
----------------------------------------*/
 
  
 $('#state').inputFilter(function(value){
    var state = $('#state').val();
    var status = alphawithspacehiphen(state);
    if(status === false){
             $('#state_error').remove();
            $('#state').after("<small class='dangererror pull-left' id='state_error'>Only Alphabet and space allowed</small>");
    }
    
    return status;
});
 
 $('#city').inputFilter(function(value){
    var city = $('#city').val();
    var status = alphawithspacehiphen(city);
    if(status === false){
            $('#city_error').remove();
            $('#city').after("<small class='dangererror pull-left' id='city_error'>Only Alphabet and space allowed</small>");
    }
    return status;
});

/*----------------------------------------
 |          Admin  Add Vendor
----------------------------------------*/


$('#f_name').inputFilter(function(value){
    var f_name = $('#f_name').val();
    var status = alphawithspacehiphen(f_name);
    if(status === false){
            $('#f_name_error').remove();
            $('#f_name').after("<small class='dangererror pull-left' id='f_name_error'>Only Alphabet and space allowed</small>");
    }
    return status;
});

$('#l_name').inputFilter(function(value){
    var l_name = $('#l_name').val();
    var status = alphawithspacehiphen(l_name);
    if(status === false){
            $('#l_name_error').remove();
            $('#l_name').after("<small class='dangererror pull-left' id='l_name_error'>Only Alphabet and space allowed</small>");
    }
    return status;
});

$('#public_name').inputFilter(function(value){
    var public_name = $('#public_name').val();
    var status = alphawithspacehiphen(public_name);
    if(status === false){
            $('#public_name_error').remove();
            $('#public_name').after("<small class='dangererror pull-left' id='public_name_error'>Only Alphabet and space allowed</small>");
    }
    return status;
});

$('#phone').inputFilter(function(value){
    var phone = $('#phone').val();
    if(phone.length > 10){
        $('#phone_error').remove();
        $('#phone').after("<small class='dangererror pull-left' id='phone_error'>Phone number can't be more than 10 digit</small>");
     return false;
    }else{
        if(onlynumber(phone) === false)
        {
            $('#phone_error').remove();
            $('#phone').after("<small class='dangererror pull-left' id='phone_error'>Only Number Allowed</small>");
        }
      return onlynumber(phone);
    }
   
});


/*---------------------------------------------------
 |          Admin Add vendor - >  General Info Vendor
------------------------------------------------------*/

$("#add_vendor_general_info").submit(function(){
     
      var f_name = $('#f_name').val();
      var username = $('#username').val();
      var public_name = $('#public_name').val();
      var email = $('#email').val();
      var is_valid_email = validateEmail(email);
      var phone = $('#phone').val();
      var password = $('#password').val();
      var gender = $('select').val();
    
    
      if(f_name === ''){
            $('#f_name_error').remove();
            $('#f_name').after("<small class='dangererror pull-left' id='f_name_error'>First name is required</small>");
            return false;
      }else if(f_name.length < 3){
          
            $('#f_name_error').remove();
            $('#f_name').after("<small class='dangererror pull-left' id='f_name_error'>First name should be more than 2 characters</small>");
            return false;
            
      }else if(f_name.length > 255){
          
            $('#f_name_error').remove();
            $('#f_name').after("<small class='dangererror pull-left' id='f_name_error'>First name can't be more than 255 characters</small>");
            return false;
            
      }else if(!alphawithspacehiphen(f_name)){
          
            $('#f_name_error').remove();
            $('#f_name').after("<small class='dangererror pull-left' id='f_name_error'>Only Alphabet and space allowed</small>");
            return false;
            
      }else{
          $('#f_name_error').remove();
      }
      
      
      if(username === ''){
            $('#username_error').remove();
            $('#username').after("<small class='dangererror pull-left' id='username_error'>User name is required</small>");
            return false;
      }else if(username.length < 3){
          
            $('#username_error').remove();
            $('#username').after("<small class='dangererror pull-left' id='username_error'>User name should be more than 2 characters</small>");
            return false;
            
      }else if(username.length > 50){
          
            $('#username_error').remove();
            $('#username').after("<small class='dangererror pull-left' id='username_error'>User name can't be more than 50 characters</small>");
            return false;
            
      }else if(!alphawithspacehiphen(username)){
          
            $('#username_error').remove();
            $('#username').after("<small class='dangererror pull-left' id='username_error'>Only Alphabet and space allowed</small>");
            return false;
            
      }else{
          $('#username_error').remove();
      }
      
      
      if(public_name === ''){
            
            $('#public_name_error').remove();
            $('#public_name').after("<small class='dangererror pull-left' id='public_name_error'>Public name is required</small>");
            return false;
            
      }else if(public_name.length < 3){
          
            $('#public_name_error').remove();
            $('#public_name').after("<small class='dangererror pull-left' id='public_name_error'>Public name should be more than 2 characters</small>");
            return false;
            
      }else if(public_name.length > 255){
          
            $('#public_name_error').remove();
            $('#public_name').after("<small class='dangererror pull-left' id='public_name_error'>Public name can't be more than 255 characters</small>");
            return false;
            
      }else if(!alphawithspacehiphen(public_name)){
          
            $('#public_name_error').remove();
            $('#public_name').after("<small class='dangererror pull-left' id='public_name_error'>Only Alphabet and space allowed</small>");
            return false;
            
      }else{
          $('#public_name_error').remove();
      }
    
        if(email === ''){
            $('#email_error').remove();
            $('#email').after("<small class='dangererror pull-left' id='email_error'>Email is required</small>");
            return false;
        }else if(!is_valid_email){
             $('#email_error').remove();
             $('#email').after("<small class='dangererror pull-left' id='email_error'>Invalid Email Address</small>");
             return false;
        }else{
             $('#email_error').remove();
        }
      
      
      if(phone === ''){
        $('#phone_error').remove();
        $('#phone').after("<small class='dangererror pull-left' id='phone_error'>Phone Number is required</small>");
        return false;
    }else if(phone.length != 10){
         $('#phone_error').remove();
         $('#phone').after("<small class='dangererror pull-left' id='phone_error'> Invalid Mobile No.</small>");
         return false;
    }else{
         $('#phone_error').remove();
        
    }
    
    if(gender === ''){
			$('#select_error').remove();
            $('select').after("<small class='dangererror pull-left' id='select_error'>Gender is required</small>");
            return false;
      }else{
          $('#select_error').remove();
      }
      
    
    if(password === ''){
            $('#password_error').remove();
            $('#password').after("<small class='dangererror pull-left' id='password_error'>Password is required</small>");
            return false;
      }else if(password.length < 5){
          
            $('#password_error').remove();
            $('#password').after("<small class='dangererror pull-left' id='password_error'>The password must be at least 5 characters.</small>");
            return false;
            
      }else{
          $('#password_error').remove();
      }
      
});



 $('#company_state').inputFilter(function(value){
    var company_state = $('#company_state').val();
    var status = alphawithspacehiphen(company_state);
    if(status === false){
             $('#company_state_error').remove();
            $('#company_state').after("<small class='dangererror pull-left' id='company_state_error'>Only Alphabet and space allowed</small>");
    }
    
    return status;
});

$('#company_city').inputFilter(function(value){
    var company_city = $('#company_city').val();
    var status = alphawithspacehiphen(company_city);
    if(status === false){
             $('#company_city_error').remove();
            $('#company_city').after("<small class='dangererror pull-left' id='company_city_error'>Only Alphabet and space allowed</small>");
    }
    
    return status;
});


$('#company_pincode').inputFilter(function(value){
    var company_pincode = $('#company_pincode').val();
    
    if(company_pincode.length > 6){
        $('#company_pincode_error').remove();
        $('#company_pincode').after("<small class='dangererror pull-left' id='company_pincode_error'>Pincode can't be more than 6 digit</small>");
        return false;
    }else{
        if(onlynumber(company_pincode) === false)
        {
            $('#company_pincode_error').remove();
            $('#company_pincode').after("<small class='dangererror pull-left' id='company_pincode_error'>Only Number Allowed</small>");
        }else if(validpincode(company_pincode)===false){
            $('#company_pincode_error').remove();
            $('#company_pincode').after("<small class='dangererror pull-left' id='company_pincode_error'>Invalid Pincode</small>");
        }else{
			$('#company_pincode_error').remove();
		}
      return onlynumber(company_pincode);
    }
   
});

$('#company_about').inputFilter(function(value){
    var company_about = $('#company_about').val();
    
    if(company_about.length > 360){
        $('#company_about_error').remove();
        $('#company_about').after("<small class='dangererror pull-left' id='company_about_error'>About Us can't be more than 360 characters</small>");
        return false;
    }else{
        return true;
    }
   
});

/*---------------------------------------------------
 |          Admin Add vendor - >  Company Info Vendor
------------------------------------------------------*/
/*
$("#company_info").submit(function(){
     
      var company_name = $('#company_name').val();
      var company_address = $('#company_address').val();
      var company_state = $('#company_state').val();
      var company_city = $('#company_city').val(); 
      var company_pincode = $('#company_pincode').val(); 
      var company_about = $('#company_about').val(); 
       
      if(company_name === ''){
            
            $('#company_name_error').remove();
            $('#company_name').after("<small class='dangererror pull-left' id='company_name_error'>Company name is required</small>");
            return false;
            
      }else if(company_name.length < 3){
          
            $('#company_name_error').remove();
            $('#company_name').after("<small class='dangererror pull-left' id='company_name_error'>Company name should be more than 2 characters</small>");
            return false;
            
      }else if(company_name.length > 255){
          
            $('#company_name_error').remove();
            $('#company_name').after("<small class='dangererror pull-left' id='company_name_error'>Company name can't be more than 255 characters</small>");
            return false;
            
      }else if(!alphanumwithspace(company_name)){
          
            $('#company_name_error').remove();
            $('#company_name').after("<small class='dangererror pull-left' id='company_name_error'>Only Alphabet, Number and space allowed</small>");
            return false;
            
      }else{
          $('#company_name_error').remove();
      }
      
      if(company_address === ''){
            
            $('#company_address_error').remove();
            $('#company_address').after("<small class='dangererror pull-left' id='company_address_error'>Company Address is required</small>");
            return false;
            
      }else if(company_address.length < 5){
          
            $('#company_address_error').remove();
            $('#company_address').after("<small class='dangererror pull-left' id='company_address_error'>Company Address should be more than 5 characters</small>");
            return false;
            
      }else if(company_address.length > 255){
          
            $('#company_address_error').remove();
            $('#company_address').after("<small class='dangererror pull-left' id='company_address_error'>Company Address can't be more than 255 characters</small>");
            return false;
            
      }else{
          $('#company_address_error').remove();
      }
      
      if(company_state === ''){
            
            $('#company_state_error').remove();
            $('#company_state').after("<small class='dangererror pull-left' id='company_state_error'>State is required</small>");
            return false;
            
      }else if(company_state.length < 3){
          
            $('#company_state_error').remove();
            $('#company_state').after("<small class='dangererror pull-left' id='company_state_error'>State should be more than 2 characters</small>");
            return false;
            
      }else if(company_state.length > 255){
          
            $('#company_state_error').remove();
            $('#company_state').after("<small class='dangererror pull-left' id='company_state_error'>State can't be more than 255 characters</small>");
            return false;
            
      }else{
          $('#company_state_error').remove();
      }
      
      
      
      if(company_city === ''){
            
            $('#company_city_error').remove();
            $('#company_city').after("<small class='dangererror pull-left' id='company_city_error'>City is required</small>");
            return false;
            
      }else if(company_city.length < 3){
          
            $('#company_city_error').remove();
            $('#company_city').after("<small class='dangererror pull-left' id='company_city_error'>City should be more than 2 characters</small>");
            return false;
            
      }else if(company_city.length > 255){
          
            $('#company_city_error').remove();
            $('#company_city').after("<small class='dangererror pull-left' id='company_city_error'>City can't be more than 255 characters</small>");
            return false;
            
      }else{
          $('#company_city_error').remove();
      }
      
      if(company_pincode === ''){
            
            $('#company_pincode_error').remove();
            $('#company_pincode').after("<small class='dangererror pull-left' id='company_pincode_error'>Pincode is required</small>");
            return false;
            
      }else if(company_pincode.length != 6){
          
            $('#company_pincode_error').remove();
            $('#company_pincode').after("<small class='dangererror pull-left' id='company_pincode_error'>Pincode should be 6 digit</small>");
            return false;
            
      }else if(!validpincode(company_pincode)){
            $('#company_pincode_error').remove();
            $('#company_pincode').after("<small class='dangererror pull-left' id='company_pincode_error'>Invalid pincode</small>");
            return false;
    }else{
          $('#company_pincode_error').remove();
      }
      
      
      
      if(company_about === ''){
            
            $('#company_about_error').remove();
            $('#company_about').after("<small class='dangererror pull-left' id='company_about_error'>About Company is required</small>");
            return false;
            
      }else if(company_about.length < 10){
          
            $('#company_about_error').remove();
            $('#company_about').after("<small class='dangererror pull-left' id='company_about_error'>About Company should be more than 2 characters</small>");
            return false;
            
      }else if(company_about.length > 360){
          
            $('#company_about_error').remove();
            $('#company_about').after("<small class='dangererror pull-left' id='company_about_error'>About Company can't be more than 360 characters</small>");
            return false;
            
      }else{
          $('#company_about_error').remove();
      }
});
*/
/*---------------------------------------------------
 |          Admin Add vendor - >  Support Info Vendor
------------------------------------------------------*/
$('#phone_suport').inputFilter(function(value){
    var phone_suport = $('#phone_suport').val();
    if(phone_suport.length > 10){
        $('#phone_suport_error').remove();
        $('#phone_suport').after("<small class='dangererror pull-left' id='phone_suport_error'>Phone no. can't be more than 10 digit</small>");
     return false;
    }else{
        if(onlynumber(phone_suport) === false)
        {
            $('#phone_suport_error').remove();
            $('#phone_suport').after("<small class='dangererror pull-left' id='phone_suport_error'>Only Number Allowed</small>");
        }
      return onlynumber(phone_suport);
    }
   
});


$('#ac_no').inputFilter(function(value){
    var ac_no = $('#ac_no').val();
    if(ac_no.length > 50){
        $('#ac_no_error').remove();
        $('#ac_no').after("<small class='dangererror pull-left' id='ac_no_error'>Account no. can't be more than 50 digit</small>");
     return false;
    }else{
        if(onlynumber(ac_no) === false)
        {
            $('#ac_no_error').remove();
            $('#ac_no').after("<small class='dangererror pull-left' id='ac_no_error'>Only Number Allowed</small>");
        }
      return onlynumber(ac_no);
    }
   
});


$('#bank_name').inputFilter(function(value){
    var bank_name = $('#bank_name').val();
    if(bank_name.length > 255){
        $('#bank_name_error').remove();
        $('#bank_name').after("<small class='dangererror pull-left' id='bank_name_error'>Bank Name can't be more than 255 character</small>");
     return false;
    }else{
        if(alphawithspacehiphen(bank_name) === false)
        {
            $('#bank_name_error').remove();
            $('#bank_name').after("<small class='dangererror pull-left' id='bank_name_error'>Only Alphabets and space Allowed</small>");
        }
      return alphawithspacehiphen(bank_name);
    }
   
});



$('#branch_name').inputFilter(function(value){
   var branch_name = $('#branch_name').val();
    if(branch_name.length > 255){
        $('#branch_name_error').remove();
        $('#branch_name').after("<small class='dangererror pull-left' id='branch_name_error'>Branch Name can't be more than 255 character</small>");
     return false;
    }else{
        if(alphanumwithspace(branch_name) === false)
        {
            $('#branch_name_error').remove();
            $('#branch_name').after("<small class='dangererror pull-left' id='branch_name_error'>Only Alphabets, Number and space Allowed</small>");
        }
      return alphanumwithspace(branch_name);
    }
});

$('#bank_city').inputFilter(function(value){
   var bank_city = $('#bank_city').val();
    if(bank_city.length > 255){
        $('#bank_city_error').remove();
        $('#bank_city').after("<small class='dangererror pull-left' id='bank_city_error'>Bank City can't be more than 255 character</small>");
     return false;
    }else{
        if(alphawithspacehiphen(bank_city) === false)
        {
            $('#bank_city_error').remove();
            $('#bank_city').after("<small class='dangererror pull-left' id='bank_city_error'>Only Alphabets and space Allowed</small>");
        }
      return alphawithspacehiphen(bank_city);
    }
});


$('#ifsc_code').inputFilter(function(value){
   var ifsc_code = $('#ifsc_code').val();
    if(ifsc_code.length > 255){
        $('#ifsc_code_error').remove();
        $('#ifsc_code').after("<small class='dangererror pull-left' id='ifsc_code_error'>IFSC code can't be more than 255 character</small>");
     return false;
    }else{
        if(onlynumAlpha(ifsc_code) === false)
        {
            $('#ifsc_code_error').remove();
            $('#ifsc_code').after("<small class='dangererror pull-left' id='ifsc_code_error'>Only Alphabets and Number Allowed</small>");
        }
      return onlynumAlpha(ifsc_code);
    }
});


$('#pan_no').inputFilter(function(value){
   var pan_no = $('#pan_no').val();
    if(pan_no.length > 10){
        $('#pan_no_error').remove();
        $('#pan_no').after("<small class='dangererror pull-left' id='pan_no_error'>Pan No can't be more than 10 character</small>");
     return false;
    }else{
        
        if(onlynumAlpha(pan_no) === false)
        {
            $('#pan_no_error').remove();
            $('#pan_no').after("<small class='dangererror pull-left' id='pan_no_error'>Only Alphabets and Number Allowed</small>");
        }
      return onlynumAlpha(pan_no);
    }
});




$('#vendor_support_info').submit(function(){
   
    var phone_suport = $('#phone_suport').val();
    var email = $('#email_suport').val();
   
    if(phone_suport.length != 10)
    {
        $('#phone_suport_error').remove();
        $('#phone_suport').after("<small class='dangererror pull-left' id='phone_suport_error'>Invalid Phone no.</small>");
        return false;
    }else{
         $('#phone_suport_error').remove();
    }
    
    if(validateEmail(email) === false)
    {
        $('#email_suport_error').remove();
        $('#email_suport').after("<small class='dangererror pull-left' id='email_suport_error'>Invalid Email</small>");
        return false;
    }else{
        $('#email_suport_error').remove();
    }
    
    return true;
    
});



/*---------------------------------------------------
 |          Admin Add Product 
------------------------------------------------------*/
$('#gtin').inputFilter(function(value){
    var gtin = $('#gtin').val();
    
    if(gtin.length > 14){
        $('#gtin_error').remove();
        $('#gtin').after("<small class='dangererror pull-left' id='gtin_error'>GTIN  can't be more than 14 digit</small>");
        return false;
    }else{
        if(onlynumber(gtin) === false)
        {
            $('#gtin_error').remove();
            $('#gtin').after("<small class='dangererror pull-left' id='gtin_error'>Only Number Allowed</small>");
        }
      return onlynumber(gtin);
    }
   
});

$('#product_tax').inputFilter(function(value){
    var product_tax = $('#product_tax').val();
    
    if(product_tax > 99){
        $('#product_tax_error').remove();
        $('#product_tax').after("<small class='dangererror pull-left' id='product_tax_error'>Product Tax should be less than 100</small>");
        return false;
    }else{
        if(onlynumber(product_tax) === false)
        {
            $('#product_tax_error').remove();
            $('#product_tax').after("<small class='dangererror pull-left' id='product_tax_error'>Only Number Allowed</small>");
        }
      return onlynumber(product_tax);
    }
   
});

$('#price').inputFilter(function(value){
    var price = $('#price').val();
   
        if(onlynumber(price) === false)
        {
            $('#price_error').remove();
            $('#price').after("<small class='dangererror pull-left' id='price_error'>Only Number Allowed</small>");
        }
        return onlynumber(price);
});

$('#spcl_price').inputFilter(function(value){
    var spcl_price = $('#spcl_price').val();
   
        if(onlynumber(spcl_price) === false)
        {
            $('#spcl_price_error').remove();
            $('#spcl_price').after("<small class='dangererror pull-left' id='spcl_price_error'>Only Number Allowed</small>");
        }
        return onlynumber(spcl_price);
    
   
});



$(document).on('input','.atr_qty_conf', function (e) {
	      
	      var atr_qty = $(this).val();
          var status = onlynumber(atr_qty);
           $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if(status === false)
            {
                if($(this).next().hasClass('atr_qty_error')){
                     $(this).next().remove();
                }
                $(this).after("<small class='dangererror pull-left atr_qty_error'>Only Number Allowed</small>");
                
            }
           
            return status;
       
});
 

$(document).on('input','.atr_price_conf', function (e) {
	      
	      var atr_price = $(this).val();
          var status = onlynumber(atr_price);
           $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if(status === false)
            {
                if($(this).next().hasClass('atr_price_error')){
                     $(this).next().remove();
                }
                $(this).after("<small class='dangererror pull-left atr_price_error'>Only Number Allowed</small>");
                
            }
           
            return status;
       
});








$('#hsn_code').inputFilter(function(value){
    var hsn_code = $('#hsn_code').val();
    
     if(hsn_code.length > 10){
        $('#hsn_code_error').remove();
        $('#hsn_code').after("<small class='dangererror pull-left' id='hsn_code_error'>HSN code can't be  be more than 10 digit</small>");
        return false;
    }else{
       if(onlynumber(hsn_code) === false)
        {
            $('#hsn_code_error').remove();
            $('#hsn_code').after("<small class='dangererror pull-left' id='hsn_code_error'>Only Number Allowed</small>");
        }
    }
     return onlynumber(hsn_code);
});

$('#delivery_days').inputFilter(function(value){
    var delivery_days = $('#delivery_days').val();
    
     if(delivery_days.length > 2){
        $('#delivery_days_error').remove();
        $('#delivery_days').after("<small class='dangererror pull-left' id='delivery_days_error'>Delivery Days can't be  be more than 2 digit</small>");
        return false;
    }else{
       if(onlynumber(delivery_days) === false)
        {
            $('#delivery_days_error').remove();
            $('#delivery_days').after("<small class='dangererror pull-left' id='delivery_days_error'>Only Number Allowed</small>");
        }
    }
     return onlynumber(delivery_days);
});

$('#return_days').inputFilter(function(value){
    var return_days = $('#return_days').val();
    
     if(return_days.length > 2){
        $('#return_days_error').remove();
        $('#return_days').after("<small class='dangererror pull-left' id='return_days_error'>Return Days can't be  be more than 2 digit</small>");
        return false;
    }else{
       if(onlynumber(return_days) === false)
        {
            $('#return_days_error').remove();
            $('#return_days').after("<small class='dangererror pull-left' id='return_days_error'>Only Number Allowed</small>");
        }
    }
     return onlynumber(return_days);
});

$('#shipping_charge').inputFilter(function(value){
    var shipping_charge = $('#shipping_charge').val();
    
     if(shipping_charge.length > 4){
        $('#shipping_charge_error').remove();
        $('#shipping_charge').after("<small class='dangererror pull-left' id='shipping_charge_error'>Shipping Charge can't be  be more than 4 digit</small>");
        return false;
    }else{
       if(onlynumber(shipping_charge) === false)
        {
            $('#shipping_charge_error').remove();
            $('#shipping_charge').after("<small class='dangererror pull-left' id='shipping_charge_error'>Only Number Allowed</small>");
        }
    }
     return onlynumber(shipping_charge);
});

$('#meta_title').inputFilter(function(value){
    var meta_title = $('#meta_title').val();
    
     if(meta_title.length > 60){
        $('#meta_title_error').remove();
        $('#meta_title').after("<small class='dangererror pull-left' id='meta_title_error'>Meta title can't be  be more than 60 characters</small>");
        return false;
      }
     return true;
});

$('#meta_description').inputFilter(function(value){
    var meta_description = $('#meta_description').val();
    
      if(meta_description.length > 160){
        $('#meta_description_error').remove();
        $('#meta_description').after("<small class='dangererror pull-left' id='meta_description_error'>Meta Description can't be  be more than 160 characters</small>");
        return false;
      }
     return true;
});

$('#meta_keyword').inputFilter(function(value){
    var meta_keyword = $('#meta_keyword').val();
    
      if(meta_keyword.length > 360){
        $('#meta_keyword_error').remove();
        $('#meta_keyword').after("<small class='dangererror pull-left' id='meta_keyword_error'>Meta Keyword can't be  be more than 360 characters</small>");
        return false;
      }
     return true;
});







$('#add_product_simple_general').submit(function(){
    var name = $('#name').val();
    var short_description = $('#short_description').val();
    var gtin = $('#gtin').val();
    var product_tax = $('#product_tax').val();
    var weight = $('#weight').val();
   
    
    if(name === ''){
        $('#name_error').remove();
        $('#name').after("<small class='dangererror pull-left' id='name_error'>Product Name is required</small>");
        return false;
    }else{
       $('#name_error').remove();
    }
    
    // if(short_description === ''){
    //     $('#short_description_error').remove();
    //     $('#short_description').after("<small class='dangererror pull-left' id='short_description_error'>Short Description is required</small>");
    //     return false;
    // }else{
    //   $('#short_description_error').remove();
    // }
    
    if(gtin === ''){
        $('#gtin_error').remove();
        $('#gtin').after("<small class='dangererror pull-left' id='gtin_error'>GTIN is required</small>");
        return false;
    }else{
       $('#gtin_error').remove();
    }
    
     if(product_tax === ''){
        $('#product_tax_error').remove();
        $('#product_tax').after("<small class='dangererror pull-left' id='product_tax_error'>Product Tax is required</small>");
        return false;
    }else{
       $('#product_tax_error').remove();
    }
    
    if(weight === ''){
        $('#weight_error').remove();
        $('#weight').after("<small class='dangererror pull-left' id='weight_error'>Weight is required</small>");
        return false;
    }else{
       $('#weight_error').remove();
    }
    
    
});



$('#add_product_conf_general').submit(function(){
    var name = $('#name').val();
    var short_description = $('#short_description').val();
    var gtin = $('#gtin').val();
    var product_tax = $('#product_tax').val();
    var weight = $('#weight').val();
   
    
    if(name === ''){
        $('#name_error').remove();
        $('#name').after("<small class='dangererror pull-left' id='name_error'>Product Name is required</small>");
        return false;
    }else{
       $('#name_error').remove();
    }
    
    // if(short_description === ''){
    //     $('#short_description_error').remove();
    //     $('#short_description').after("<small class='dangererror pull-left' id='short_description_error'>Short Description is required</small>");
    //     return false;
    // }else{
    //   $('#short_description_error').remove();
    // }
    
    if(gtin === ''){
        $('#gtin_error').remove();
        $('#gtin').after("<small class='dangererror pull-left' id='gtin_error'>GTIN is required</small>");
        return false;
    }else{
       $('#gtin_error').remove();
    }
    
     if(product_tax === ''){
        $('#product_tax_error').remove();
        $('#product_tax').after("<small class='dangererror pull-left' id='product_tax_error'>Product Tax is required</small>");
        return false;
    }else{
       $('#product_tax_error').remove();
    }
    
    if(weight === ''){
        $('#weight_error').remove();
        $('#weight').after("<small class='dangererror pull-left' id='weight_error'>Weight is required</small>");
        return false;
    }else{
       $('#weight_error').remove();
    }
    
    
});

$('#filter_name').inputFilter(function(value){
    var filter_name = $('#filter_name').val();
    if(filter_name.length > 255){
        $('#filter_name_error').remove();
      
        $('#filter_name').after("<small class='text-danger pull-left' id='filter_name_error'>Filter Name can't be more than 255 character<br></small>");
     return false;
    }else{
        if(alphanumwithspace(filter_name) === false)
        {
            $('#filter_name_error').remove();
            $('#filter_name').after("<small class='text-danger pull-left' id='filter_name_error'>Only Alphabets, Number and space Allowed<br></small>");
        }
      return alphanumwithspace(filter_name);
    }
});

$('#filter_description').inputFilter(function(value){
    var filter_description = $('#filter_description').val();
    if(filter_description.length > 255){
        $('#filter_description_error').remove();
      
        $('#filter_description').after("<small class='text-danger pull-left' id='filter_description_error'>Filter Description can't be more than 255 character<br></small>");
     return false;
    }else{
        if(alphanumwithspace(filter_description) === false)
        {
            $('#filter_description_error').remove();
            $('#filter_description').after("<small class='text-danger pull-left' id='filter_description_error'>Only Alphabets, Number and space Allowed<br></small>");
        }
      return alphanumwithspace(filter_description);
    }
});



$('#name').inputFilter(function(value){
    var name = $('#name').val();
    if(name.length > 255){
        $('#name_error').remove();
      
        $('#name').after("<small class='text-danger pull-left' id='name_error'>Branch Name can't be more than 255 character<br></small>");
     return false;
    }else{
        if(alphanumwithspace(name) === false)
        {
            $('#name_error').remove();
            $('#name').after("<small class='text-danger pull-left' id='name_error'>Only Alphabets, Number and space Allowed<br></small>");
        }
      return alphanumwithspace(name);
    }
});



 var predata = '';
$(document).on('input','.onlyalphanumspace', function (e) {
   
    var val = $(this).val();
     $(this).val($(this).val().replace(/[^a-zA-z0-9\s]/g,''));
   
    
    if(val.length > 255){
        
         $(this).val(predata);
        
        if($(this).next().hasClass('onlyalphanumspace_error'))
        {
             $(this).next().remove();
        }
                
        $(this).after("<small class='text-danger pull-left onlyalphanumspace_error'>More than 255 characters not allowed<br></small>");
        return false;
    }else{
        
         predata = val;
          if($(this).next().hasClass('onlyalphanumspace_error'))
            {
                 $(this).next().remove();
            }
        if(alphanumwithspace(val) === false)
        {
           
            $(this).after("<small class='text-danger pull-left onlyalphanumspace_error' >Only Alphabets, Number and space Allowed<br></small>");
        }
        return alphanumwithspace(val);
    }
});

/*Coupon*/

$(document).on('input','#name', function (e) {
	      
	      var name = $(this).val();
          $( this).val($(this).val().replace(/\s+/g,' '));
		  if(name.charAt(0)==' '){
		       $( this).val($(this).val().trim());
				 $('#name_error').remove();
			  $(this).after("<small class='dangererror pull-left' id= 'name_error' >Space not allowed in begin</small>");
		   }else{
			   $('#name_error').remove();
		   }
		
});

$(document).on('input','#discount', function (e) {
	      
	      var discount = $(this).val();
          var status = onlynumber(discount);
           $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if(status === false)
            {
                if($(this).next().hasClass('discount_error')){
                     $(this).next().remove();
                }
                $(this).after("<small class='dangererror pull-left discount_error'>Only Number Allowed</small>");
                
            }else if(parseInt(discount)<=0){
				if($(this).next().hasClass('discount_error')){
                     $(this).next().remove();
                }
				$(this).after("<small class='dangererror pull-left discount_error'>The discount must be at least 1</small>");
			}else if(parseInt(discount.length)>2){
				if($(this).next().hasClass('discount_error')){
                     $(this).next().remove();
                }
				$(this).after("<small class='dangererror pull-left discount_error'>Invalid Discount</small>");
			}else{
				 $(this).next().remove();
			}
           
            return status;
       
});

$(document).on('input','#minimum_amount', function (e) {
	      
	      var minimum_amount = $(this).val();
	       var above_cart_amt = $('#above_cart_amt').val();
	       
          var status = onlynumber(minimum_amount);
           $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if(status === false)
            {
                if($(this).next().hasClass('minimum_amount_error')){
                     $('.minimum_amount_error').remove();
                }
                $(this).after("<small class='dangererror pull-left minimum_amount_error'>Only Number Allowed</small>");
                
            }else if(parseInt(minimum_amount)<=0){
				if($(this).next().hasClass('minimum_amount_error')){
                     $(this).next().remove();
                }
				$(this).after("<small class='dangererror pull-left minimum_amount_error'>The minimum cart must be at least 1</small>");
			}else{
				$(this).next().remove();
			}
			
			if(parseInt(minimum_amount) > parseInt(above_cart_amt)){
			    	$(this).after("<small class='dangererror pull-left minimum_amount_error'>The Minimum cart amount must be less than maximum cart amount</small>");
			    	return false;
			}else{
			    $('.minimum_amount_error').remove();
			    $('.above_cart_amt_error').remove();
			}
           
            return status;
       
});

$(document).on('input','#above_cart_amt', function (e) {
	      
	      var above_cart_amt = $(this).val();
	      var min_cart_amt = $('#minimum_amount').val();
          var status = onlynumber(above_cart_amt);
           $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if(status === false)
            {
                if($(this).next().hasClass('above_cart_amt_error')){
                     $('.above_cart_amt_error').remove();
                }
                $(this).after("<small class='dangererror pull-left above_cart_amt_error'>Only Number Allowed</small>");
                
            }else if(parseInt(above_cart_amt)<=0){
				if($(this).next().hasClass('above_cart_amt_error')){
                     $('.above_cart_amt_error').remove();
                }
				$(this).after("<small class='dangererror pull-left above_cart_amt_error'>The Maximum cart must be at least 1</small>");
			}else{
				$(".above_cart_amt_error").remove();
			}
			
			if(parseInt(min_cart_amt) > (above_cart_amt)){
			    	$(this).after("<small class='dangererror pull-left above_cart_amt_error'>The Maximum cart amount must be greater than minimum cart amount</small>");
			    	return false;
			}else{
			    $('.above_cart_amt_error').remove();
			     $('.minimum_amount_error').remove();
			}
           
            return status;
       
});

$(document).on('input','#couponNumber', function (e) {
	      
	      var couponNumber = $(this).val();
          var status = onlynumber(couponNumber);
           $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if(status === false)
            {
                if($(this).next().hasClass('couponNumber_error')){
                     $(this).next().remove();
                }
                $(this).after("<small class='dangererror pull-left couponNumber_error'>Only Number Allowed</small>");
                
            }else if(parseInt(couponNumber)<=0){
				if($(this).next().hasClass('couponNumber_error')){
                     $(this).next().remove();
                }
				$(this).after("<small class='dangererror pull-left couponNumber_error'>The Total Number Coupon must be at least 1</small>");
			}else{
				$(this).next().remove();
			}
           
            return status;
       
});

$("#formcoupon").submit(function(){
    var c=$( "select[name='couponType']" ).val();
	
	var name=$( "input[name='name']" ).val();
	var discount=$( "input[name='discount']" ).val();
	var banner_image=$( "input[name='banner_image']" ).val();
	
	var bb=$('.brandmain .row');
	$(".submit_error").remove();
	
	$( ".name_error" ).remove();
	$( ".discount_error" ).remove();
	$( ".banner_error" ).remove();
	$( ".minimum_amount_error" ).remove();
	$( ".max_amount_error" ).remove();
	$( ".issue_date_error" ).remove();
	$( ".expiry_date_error" ).remove();
	$( ".totalno_error" ).remove();
	$( ".coupon_type_error" ).remove();
	
		if(c=='')
	{
	  
		$( "select[name='couponType']" ).after("<small class='dangererror pull-left coupon_type_error'>Coupon Type should not be blank </small>");
		return false;
	}else{
	    
		$( ".coupon_type_error" ).remove();
	}
	
	if(name=='')
	{
		$( "input[name='name']" ).after("<small class='dangererror pull-left name_error'>Name should not be blank </small>");
		return false;
	}else{
		$( ".name_error" ).remove();
	}
	
	if(discount<=0)
	{
		$( "input[name='discount']" ).after("<small class='dangererror pull-left discount_error'>Discount should not below 1 </small>");
		return false;
	}else{
		$( ".discount_error" ).remove();
	}
	
	if(banner_image=='')
	{
		$( "input[name='banner_image']" ).before("<small class='dangererror pull-right banner_error'>Banner Image should not be blank </small> ");
		return false;
	}else{
		$( ".banner_error" ).remove();
	}
	
	
	
	if(c==1){
		//alert('Static with Cart');
		
		var minimum_amount=$( "input[name='minimum_amount']" ).val();
		var above_cart_amt=$( "input[name='above_cart_amt']" ).val();
		
		if(minimum_amount<=0)
		{
			$( "input[name='minimum_amount']" ).after("<small class='dangererror pull-left minimum_amount_error'>Minimum cart amount should not below 1 </small>");
			return false;
		}else{
			$( ".minimum_amount_error" ).remove();
		}
		
		if(above_cart_amt<=0)
		{
			$( "input[name='above_cart_amt']" ).after("<small class='dangererror pull-left max_amount_error'>Maximum cart amount should not below 1 </small>");
			return false;
		}else{
			$( ".max_amount_error" ).remove();
		}
		
	}else if(c==2){
		//alert('Static with Periodic');
		var issuedate=$( "input[name='issuedate']" ).val();
		var expire_date_field=$( "input[name='expire_date_field']" ).val();
		
// 		alert('issuedate : '+issuedate+' Expire Date : '+expire_date_field);
		
		if(issuedate > expire_date_field){
			$( "input[name='expire_date_field']" ).after("<small class='dangererror pull-left expiry_date_error'>Invalid Expiry Date.</small>");
			return false;
		}else{
		   $( ".expiry_date_error" ).remove();
		}
		
		if(issuedate=='')
		{
			$( "input[name='issuedate']" ).after("<small class='dangererror pull-left issue_date_error'>Issue date should not be blank </small>");
			return false;
		}else{
			$( ".issue_date_error" ).remove();
		}
		
		if(expire_date_field=='')
		{
			$( "input[name='expire_date_field']" ).after("<small class='dangererror pull-left expiry_date_error'>Expiry date should not be blank </small>");
			return false;
		}else{
			$( ".expiry_date_error" ).remove();
		}
		
	}else if(c==3){
		//alert('Static with Periodic & Cart');
		var issuedate=$( "input[name='issuedate']" ).val();
		var expire_date_field=$( "input[name='expire_date_field']" ).val();
		var minimum_amount=$( "input[name='minimum_amount']" ).val();
		var above_cart_amt=$( "input[name='above_cart_amt']" ).val();
		
		if(issuedate=='')
		{
			$( "input[name='issuedate']" ).after("<small class='dangererror pull-left issue_date_error'>Issue date should not be blank </small>");
			return false;
		}else{
			$( ".issue_date_error" ).remove();
		}
		
		if(expire_date_field=='')
		{
			$( "input[name='expire_date_field']" ).after("<small class='dangererror pull-left expiry_date_error'>Expiry date should not be blank </small>");
			return false;
		}else{
			$( ".expiry_date_error" ).remove();
		}
		
		if(minimum_amount<=0)
		{
			$( "input[name='minimum_amount']" ).after("<small class='dangererror pull-left minimum_amount_error'>Minimum cart amount should not below 1 </small>");
			return false;
		}else{
			$( ".minimum_amount_error" ).remove();
		}
		
		if(above_cart_amt<=0)
		{
			$( "input[name='above_cart_amt']" ).after("<small class='dangererror pull-left max_amount_error'>Maximum cart amount should not below 1 </small>");
			return false;
		}else{
			$( ".max_amount_error" ).remove();
		}
		
	}else if(c==4){
		//alert('Offer');
		var couponNumber=$( "input[name='couponNumber']" ).val();
		
		if(couponNumber<0)
		{
			$( "input[name='couponNumber']" ).after("<small class='dangererror pull-left totalno_error'>Total Coupon Number should not below 1 </small>");
			return false;
		}else{
			$( ".totalno_error" ).remove();
		}
		
	}else if(c==5){
		//alert('Offer with Cart');
		var couponNumber=$( "input[name='couponNumber']" ).val();
		var minimum_amount=$( "input[name='minimum_amount']" ).val();
		var above_cart_amt=$( "input[name='above_cart_amt']" ).val();
		
		if(couponNumber<0)
		{
			$( "input[name='couponNumber']" ).after("<small class='dangererror pull-left totalno_error'>Total Coupon Number should not below 1 </small>");
			return false;
		}else{
			$( ".totalno_error" ).remove();
		}
		if(minimum_amount<=0)
		{
			$( "input[name='minimum_amount']" ).after("<small class='dangererror pull-left minimum_amount_error'>Minimum cart amount should not below 1 </small>");
			return false;
		}else{
			$( ".minimum_amount_error" ).remove();
		}
		
		if(above_cart_amt<=0)
		{
			$( "input[name='above_cart_amt']" ).after("<small class='dangererror pull-left max_amount_error'>Maximum cart amount should not below 1 </small>");
			return false;
		}else{
			$( ".max_amount_error" ).remove();
		}
		
	}else if(c==6){
		//alert('Offer with Periodic');
		var couponNumber=$( "input[name='couponNumber']" ).val();
		var issuedate=$( "input[name='issuedate']" ).val();
		var expire_date_field=$( "input[name='expire_date_field']" ).val();
		
		if(couponNumber<0)
		{
			$( "input[name='couponNumber']" ).after("<small class='dangererror pull-left totalno_error'>Total Coupon Number should not below 1 </small>");
			return false;
		}else{
			$( ".totalno_error" ).remove();
		}
		if(issuedate=='')
		{
			$( "input[name='issuedate']" ).after("<small class='dangererror pull-left issue_date_error'>Issue date should not be blank </small>");
			return false;
		}else{
			$( ".issue_date_error" ).remove();
		}
		
		if(expire_date_field=='')
		{
			$( "input[name='expire_date_field']" ).after("<small class='dangererror pull-left expiry_date_error'>Expiry date should not be blank </small>");
			return false;
		}else{
			$( ".expiry_date_error" ).remove();
		}
		
	}else if(c==7){
		//alert('Offer with Periodic & Cart');
		var couponNumber=$( "input[name='couponNumber']" ).val();
		var issuedate=$( "input[name='issuedate']" ).val();
		var expire_date_field=$( "input[name='expire_date_field']" ).val();
		var minimum_amount=$( "input[name='minimum_amount']" ).val();
		var above_cart_amt=$( "input[name='above_cart_amt']" ).val();
		
		if(couponNumber<0)
		{
			$( "input[name='couponNumber']" ).after("<small class='dangererror pull-left totalno_error'>Total Coupon Number should not below 1 </small>");
			return false;
		}else{
			$( ".totalno_error" ).remove();
		}
		if(issuedate=='')
		{
			$( "input[name='issuedate']" ).after("<small class='dangererror pull-left issue_date_error'>Issue date should not be blank </small>");
			return false;
		}else{
			$( ".issue_date_error" ).remove();
		}
		
		if(expire_date_field=='')
		{
			$( "input[name='expire_date_field']" ).after("<small class='dangererror pull-left expiry_date_error'>Expiry date should not be blank </small>");
			return false;
		}else{
			$( ".expiry_date_error" ).remove();
		}
		if(minimum_amount<=0)
		{
			$( "input[name='minimum_amount']" ).after("<small class='dangererror pull-left minimum_amount_error'>Minimum cart amount should not below 1 </small>");
			return false;
		}else{
			$( ".minimum_amount_error" ).remove();
		}
		
		if(above_cart_amt<=0)
		{
			$( "input[name='above_cart_amt']" ).after("<small class='dangererror pull-left max_amount_error'>Maximum cart amount should not below 1 </small>");
			return false;
		}else{
			$( ".max_amount_error" ).remove();
		}
		
	}else{
		//alert('static');
		
	}
	

	
	return true;
});


function alphawithspacehiphen(value){
    return alphawithspacehiphenRGEX.test(value);
}

function onlynumber(value){
    
    return onlynumRGEX.test(value);
}

function validpincode(value){
    return pinRGEX.test(value);
}

function validGST(value){
    return gstRGEX.test(value);
}

function validateEmail(email){
    
    return emailReg.test(email);
}

function alphanumwithspace(value){
    return alphanumwithspaceRGEX.test(value);
}

function onlynumAlpha(value){
    return numAlpha.test(value);
}



