<script>

  $(document).ready(function(){
        $(".opt_resend_button").click(function(){
          	$.ajax({
               type:'POST',
			   async:false,
                url:"{{ route('resend_otp') }}",
               data:{ },
               success:function(data) {
                         response = JSON.parse(data);
					   $('.opt_return_message').append(response.MSG);
					   setTimeout(function(){ $('.opt_return_message').html('') }, 3000);
               }
            });
        });
    });

</script>