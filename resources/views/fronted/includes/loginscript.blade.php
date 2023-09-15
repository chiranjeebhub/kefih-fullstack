<script>


$(document).on('click','.head_user_login', function () {
    $("#myModal").modal("show");  
});

$(document).on('click','#changeOTP_phone', function () {
    $("#myModal").modal("show"); 
    $("#myModal1").modal("hide");
});

$(document).on('click','.userLogin', function () {

    let login_country_code = $("#login_country_code").val(); 
    let login_phone = $("#login_phone").val(); 
    let term_accepted = ($("#term_accepted").is(":checked"))?1:''; 

          $.ajax({
                type: 'POST',
                async: false,
                url: "{{ route('user-login') }}",
                data: {"login_country_code":login_country_code, "login_phone":login_phone, "term_accepted":term_accepted},
                success: function(data) {
                    // let response = JSON.parse(data);
                    if(!data.status){
                       let errorHTML = '';
                       if(data.data.login_country_code){
                        errorHTML+=`<p class="text-danger">${data.data.login_country_code[0]}</p>`;
                       }
                       if(data.data.login_phone){
                        errorHTML+=`<p class="text-danger">${data.data.login_phone[0]}</p>`;
                       }
                       if(data.data.term_accepted){
                        errorHTML+=`<p class="text-danger">${data.data.term_accepted[0]}</p>`;
                       }

                       $("#loginerror").html(errorHTML); 
                    }else{
                        $("#loginerror").empty();
                        $("#myModal").modal("hide"); 
                        $("#myModal1").modal("show");
                        $("#otp_response").html(`<span class="text-success">${data.message}</span>`);
                        $("#otp_phone_no").html(`+${data.data.country_code}${data.data.phone}`);                       

                    }
                    
                }
            });
      

 });



 $(document).on('click','#verifyOTPbtn', function () {
    let otp1 = $("#login_otp_1").val(); 
    let otp2 = $("#login_otp_2").val(); 
    let otp3 = $("#login_otp_3").val(); 
    let otp4 = $("#login_otp_4").val(); 
    let OTP = `${otp1}${otp2}${otp3}${otp4}`;
    $.ajax({
                type: 'POST',
                async: false,
                url: "{{ route('user-login-verifiy') }}",
                data: {"otp":OTP},
                success: function(data) {
                    console.log({data});  
                    if(!data.status){
                        if(data.data.otp){
                            $("#otp_response").html(`<span class="text-danger">${data.data.otp[0]}</span>`);

                        }else{
                            $("#otp_response").html(`<span class="text-danger">${data.message}</span>`);

                        }
                    }else{
                        $("#otp_response").html(`<span class="text-success">${data.message}</span>`);
                       updateFCM(); 
                        window.location.href = data.data;
                    }
                }
            });
 });

 

 
 $(document).on('click','#login_resent_otp_btn', function () {
    $("#otp_response").empty();
    $.ajax({
                type: 'POST',
                async: false,
                url: "{{ route('user-login-otp') }}",
                data: {},
                success: function(data) {
                    console.log({data});  
                    if(!data.status){
                        $("#otp_response").html(`<span class="text-danger">${data.message}</span>`);
                    }else{
                        $("#otp_response").html(`<span class="text-success">${data.message}</span>`);
                       
                    }
                }
            });
 });

 
 $(document).on('keyup','.login_otp_fld', function () {
 
   if($(this).val()){
    if ( $(this).next().hasClass("login_otp_fld") ) {
          $(this).next().focus();
       } 
   }

   
 });


</script>