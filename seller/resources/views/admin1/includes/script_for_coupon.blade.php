	<!-- popper -->
	<script src=" {{ asset('public/assets/vendor_components/popper/dist/popper.min.js') }}"></script>
	
	<!-- Bootstrap 4.0-->
	<script src="{{ asset('public/assets/vendor_components/bootstrap/dist/js/bootstrap.js') }}"></script>


<!-- Fab Admin App -->
<script src=" {{ asset('public/js/template.js') }}"></script>
<script src=" {{ asset('public/js/horizontal-layout.js') }}"></script>

<script src="{{ asset('public/assets/vendor_components/ckeditor/ckeditor.js') }}"></script>

<!-- Fab Admin for editor -->
<script src=" {{ asset('public/js/pages/editor.js') }}"></script>



<script>
    $(document).on('change','.couponType', function () {
	
		    
        switch(this.value) {
            case '0':
                    $(".coupon_cart").hide(); 
                    $(".coupon_dt").hide(); 
                    $(".coupon_number").hide();
                    $(".noOfUserCoupon").show(); 
            break;
            
            case '1':
                    $(".coupon_cart").show(); 
                    $(".coupon_dt").hide(); 
                    $(".coupon_number").hide(); 
                    $(".noOfUserCoupon").show(); 
            break;
            
            case '2':
                    $(".coupon_cart").hide(); 
                    $(".coupon_dt").show(); 
                    $(".coupon_number").hide(); 
                    $(".noOfUserCoupon").show(); 
            break;
            
            case '3':
                    $(".coupon_cart").show(); 
                    $(".coupon_dt").show(); 
                    $(".coupon_number").hide(); 
                    $(".noOfUserCoupon").show(); 
            break;
            
             case '4':
                    $(".coupon_cart").hide(); 
                    $(".coupon_dt").hide(); 
                    $(".coupon_number").show(); 
                    $(".noOfUserCoupon").hide(); 
            break;
            
            case '5':
                    $(".coupon_cart").show(); 
                    $(".coupon_dt").hide(); 
                    $(".coupon_number").show();
                    $(".noOfUserCoupon").hide(); 
            break;
            
            case '6':
                    $(".coupon_cart").hide(); 
                    $(".coupon_dt").show(); 
                    $(".coupon_number").show();
                    $(".noOfUserCoupon").hide(); 
            break;
            
            case '7':
                    $(".coupon_cart").show(); 
                    $(".coupon_dt").show(); 
                    $(".coupon_number").show();
                    $(".noOfUserCoupon").hide(); 
            break;
        }
        
    
  
   
});

function image_preview(event){
		

		var reader = new FileReader();
         reader.onload = function()
         {
			var nextSib = document.getElementById(event.target.id+'_preview').nextElementSibling;
			var ref = document.getElementById(event.target.id+'_preview');
			
			if(nextSib != '')
			{
				$('#'+event.target.id+'_preview').next('img').remove();
				$('#'+event.target.id+'_preview').next('i').remove();
			}
			//-- Creating Image tag --//
			var output = document.createElement('img'); 
			//--- Creating close button ---//
			var close_btn = document.createElement('i');      
			close_btn.className ="fa fa-remove text-danger";
			$(output).insertAfter(ref);	
			$(close_btn).insertAfter(output);	
			
			close_btn.addEventListener("click", function(){
				document.getElementById(event.target.id).value = "";
				$('#'+event.target.id+'_preview').next('img').remove();
				close_btn.remove();
			});

					
			output.src = reader.result;
			output.style.height = "100px";
			output.style.width = "100px";

         }
		
         reader.readAsDataURL(event.target.files[0]);
		}
</script>