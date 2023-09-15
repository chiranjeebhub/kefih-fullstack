@if (Auth::guard('customer')->check())
    <script>$(document).ready(function() { window.user_login=1; update_wishlist(); var current_url="{{url()->current()}}"; var str1 = "review_order"; var str2 = "thankyou"; syncwebAppProduct(); });</script>
    @else
    <script>$(document).ready(function() { window.user_login=0; }); </script>
    @endif
    <script> function syncwebAppProduct(){ $.ajax({ type:'GET', async:false, url:"{{ route('mapProductWebApp') }}", data:{ }, success:function(data) { } }); } function copycode() { var copyText = document.getElementById("myInput"); copyText.select(); copyText.setSelectionRange(0, 99999); document.execCommand("copy"); $('#rfcode').show(); }
    $(document).on('change','#selectState', function () { $('#selectcity option:not(:first)').remove();             
    var state_id=$(this).val(); if(state_id!=''){ $.ajax({ type:'POST', async:true, url:"{{ route('filterCityOnState') }}", data:{ "state_id":state_id}, success:function(data) { var response = JSON.parse(data); if((response.size)>0){ $('#selectcity').append(response.city); $('#selectcity').addClass('custom-select');
    $('#selectcity').attr('data-live-search', 'true'); $('#selectcity').selectize('refresh'); } } }); } });	
    $(document).on('click','.addAddressForm', function () { var method=$(this).attr('method'); if(method==0){ $(this).removeClass('fa-plus'); $(this).addClass('fa-minus'); $('#myAddressForm').show(); $(this).attr('method',1); } else{ $(this).removeClass('fa-minus'); $(this).addClass('fa-plus'); $('#myAddressForm').hide(); $(this).attr('method',0); } });
	$(document).on('click','.genderChanged', function () {
        $(this).addClass('active').siblings().removeClass('active');
        var method=$(this).attr('row');
        if(method==1){
            $('#gender2').removeAttr('checked')
            $( "#gender1" ).attr( "checked", "true" );
        } else{
            $('#gender1').removeAttr('checked')
            $( "#gender2" ).attr( "checked", "true" );
        }                
	});
	$(document).on('click','.showButton', function () { var method=$(this).attr('row'); var prt_type=$(this).attr('prt_type'); $('#hide'+method).show(); $('#show'+method).hide(); $('#fake'+method).hide(); var val= $('#fake'+method).val(); $("#original"+method).prop("value", val); $("#original"+method).prop("type", prt_type); $('#original'+method).show(); });	
    $(document).on('click','.hideButton', function () { var method=$(this).attr('row'); var prt_type=$(this).attr('prt_type'); $('#hide'+method).hide(); $('#show'+method).show(); $('#fake'+method).show(); $("#original"+method).prop("type", prt_type); $('#original'+method).hide(); });	
	function createCookie(name, value, days) { if (days) { var date = new Date(); date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000)); var expires = "; expires=" + date.toGMTString(); } else var expires = ""; document.cookie = name + "=" + value + expires + "; path=/"; }
    function readCookie(name) { var nameEQ = name + "="; var ca = document.cookie.split(';'); for (var i = 0; i < ca.length; i++) { var c = ca[i]; while (c.charAt(0) == ' ') c = c.substring(1, c.length); if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length); } return null; }	
	$(document).ready(function() { 
	    
	    	if($('.detail-gallery').length>0){ 
	    $('.detail-gallery').each(function(){ 
	        var data=$(this).find(".carousel").data();
	        $(this).find(".carousel").jCarouselLite({ btnNext: ".nextMe", btnPrev: ".prevMe", speed: 400, visible:5, vertical:true, circular: true, start: 0, scroll: 1 });
	        }); 
	    
	}
	    
	    
	    var pincode = $.cookie("pincode"); var shipping_address_id = $.cookie("shipping_address_id"); var pincode_error = $.cookie("pincode_error"); if(pincode){ $('#pincode').val(pincode); if(pincode_error==1){ $('#pincode_msg').html('No delivery available in your area'); $('.noProblem').hide(); $('.outOFdelivery').show(); } else{
    $('#pincode_msg').html('Delivery available in your area'); $('.noProblem').show(); $('.outOFdelivery').hide(); } }	    
    $('#myAddressForm').hide();	     	  	    
    $('.hideButton').hide(); setTimeout(function(){  $("#rateit-reset-3").trigger("click"); $("#rateit-reset-2").trigger("click"); }, 2000); update_cart(); window.color_id=0; window.size_id=0; window.size_color_require=0; setTimeout(function(){ $(".alert_message").hide(); $(".alert").hide(); $(".help-block").hide(); }, 30000);		 		 
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } }); });	
    $(document).ready(function(){ $(".otp_resend_button").click(function(){ var OTPmethod = $('#OTPmethod').val(); $.ajax({ type:'POST', async:false, url:"{{ route('send_otp') }}", data:{ "OTPmethod":OTPmethod}, success:function(data) { response = JSON.parse(data); $('.otp_return_message').append("<br>"+response.MSG); setTimeout(function(){ $('.otp_return_message').html('') }, 3000); } }); });        
    $(".customer_resend_login_otp").click(function(){ $.ajax({ type:'POST', async:false, url:"{{ route('customer_resend_login_otp_message') }}", data:{}, success:function(data) { response = JSON.parse(data); $('.customer_resend_login_otp_message').append(response.MSG); setTimeout(function(){ $('.customer_resend_login_otp_message').html('') }, 3000); } }); });
    $(".vendor_resend_button").click(function(){ $.ajax({ type:'POST', async:false, url:"{{ route('vendor_resend_otp') }}", data:{}, success:function(data) { response = JSON.parse(data); $('.vendor_return_message').append(response.MSG); setTimeout(function(){ $('.vendor_return_message').html('') }, 3000); } }); });
    $(".admin_resend_button").click(function(){ $.ajax({ type:'POST', async:false, url:"{{ route('admin_resend_otp') }}", data:{}, success:function(data) { response = JSON.parse(data); $('.admin_return_message').append(response.MSG); setTimeout(function(){ $('.admin_return_message').html('') }, 3000); } }); }); });    		
	$(document).on('click','.changeQty', function () { var method=$(this).attr('method'); var row=$(this).attr('row'); var qty=$('#prd_qty_'+row).val(); if(method==1){ qty++; } else{ qty==1? qty : qty--; } $('#prd_qty_'+row).val(qty); });	
    $(document).on('click','.cancel_reutrn_radio_button', function () { $('.cancel_reason').children('option:not(:first)').remove(); $('.return_reason').children('option:not(:first)').remove(); var prd_id=$(this).attr('prd_id'); var call_method=$(this).attr('call_method');; $.ajax({ type:'GET', async:false, url:"{{ route('reason_and_policy') }}", data:{ "prd_id":prd_id, "call_method":call_method }, success:function(data) { var myObj = JSON.parse(data); $('.'+myObj.target_policy).html(myObj.policy);  $('.'+myObj.target_reason) .append(myObj.reason); } }); });	
	$(".cancel_reutrn_radio_button").trigger('click');
	function readyReaplceSelection(suborder_id,product_id){ $('#size_id').val(0); $('#color_id').val(0); $('#w_size_id').val(0); $('.replace_model_body_attr_size').html(""); $('.replace_model_body_m_attr_size').html(""); $('.replace_model_body_w_attr_size').html(""); $('.replace_model_body_attr_color').html(""); $('.replace_model_inputs').html(""); $(".size_class").hide(); $(".color_class").hide(); $(".m_size_class").hide(); $(".w_size_class").hide(); $("#replace_dialog").modal('show'); $.ajax({ type : 'post', url : '{{URL::to('productVariations')}}', data:{'suborder_id':suborder_id,'product_id':product_id}, success:function(data){ var myObj = JSON.parse(data); if(myObj.prd_type==2){ $('.replace_model_header').html(myObj.product_name); $('.replace_model_body_m_attr_size').html(myObj.sizes_html); $('.replace_model_body_w_attr_size').html(myObj.w_size_html); $('.replace_model_body_attr_color').html(myObj.color_html); $('.replace_model_inputs').html(myObj.inputs); if(myObj.w_sizes>0){ $(".m_size_class").show(); } else{ $(".m_size_class").hide(); } if(myObj.w_sizes>0){ $(".w_size_class").show(); } else{ $(".w_size_class").hide(); } if(myObj.colors>0){ $(".color_class").show(); } else{ $(".color_class").hide(); } } else{ $('.replace_model_header').html(myObj.product_name); $('.replace_model_body_attr_size').html(myObj.sizes_html); $('.replace_model_body_attr_color').html(myObj.color_html); $('.replace_model_inputs').html(myObj.inputs); if(myObj.sizes>0){ $(".size_class").show(); } else{ $(".size_class").hide(); } if(myObj.colors>0){ $(".color_class").show(); } else{ $(".color_class").hide(); } } } }) ;}
	$(document).on('change','.returnType', function () {
	    var str=this.value;
	    $('#size_id').val(0); 
	    $('#w_size_id').val(0); 
	    $('#color_id').val(0); 
	    var prd_type=$("#productType").val();
	     var returnType=$(".returnType").val();
	    
	     
	     if(returnType==0){
	         $('.refundState').show();
	     } else{
	         $('.refundState').hide();
	     }
	    if( prd_type==3&& str==1 || prd_type==2 && str==1){ 
	        var suborder_id=$(this).attr("suborder_id"); 
	        var product_id=$(this).attr("product_id"); 
	        
	        readyReaplceSelection(suborder_id,product_id);
	        } 
	    
	});		
    $(document).on('click','.ordersizeClass', function () { var size_id=$(this).attr('size_id'); $('#size_id').val(size_id); $('#color_id').val(0); var prd_id=$(this).attr('prd_id'); var token="{{ csrf_token() }}"; $(".ordersizeClass").removeClass("active"); $(this).addClass('active'); $.ajax({ type:'POST', async:true, url:"{{ route('productVariationSize') }}", data:{ "size_id":size_id, "prd_id":prd_id, "attr_name":'Colors', "_token":token }, success:function(data) { var myObj = JSON.parse(data); $(".replace_model_body_attr_color").html(myObj.html); } }); });        
    $(document).on('click','.orderwsizeClass ', function () { var w_size_id=$(this).attr('w_size_id'); $('#w_size_id').val(w_size_id); $('#color_id').val(0); var prd_id=$(this).attr('prd_id'); var token="{{ csrf_token() }}"; $(".orderwsizeClass").removeClass("active"); $(this).addClass('active'); $.ajax({ type:'POST', async:true, url:"{{ route('productVariationSize') }}", data:{ "size_id":size_id, "prd_id":prd_id, "attr_name":'Colors', "_token":token }, success:function(data) { var myObj = JSON.parse(data);
    $(".replace_model_body_attr_color").html(myObj.html); } }); });        
    $(document).on('click','.ordercolorClass', function () { $(".ordercolorClass").removeClass("active"); $(this).addClass('active'); var color_id=$(this).attr('color_id'); $('#color_id').val(color_id); });
    $(document).on('click','#fakeButtonButton', function () { var prd_type=$("#productType").val(); var returnType=$(".returnType").val(); var suborder_id=$('.returnType').attr("suborder_id"); var product_id=$('.returnType').attr("product_id"); var color_id=$("#color_id").val(); var size_id=$("#size_id").val(); var w_size_id=$("#w_size_id").val(); var color_require=$("#color_require").val(); var size_require=$("#size_require").val();			      			      
    if(prd_type==3 && returnType==1){ if(size_require==1 && color_require==1){ if(color_id==0 && size_id==0){ alert("Select size and color"); readyReaplceSelection(suborder_id,product_id); return false; } if(color_id!=0 && size_id==0){ alert("Select size"); readyReaplceSelection(suborder_id,product_id); return false; } if(color_id==0 && size_id!=0){ alert("Select  color"); readyReaplceSelection(suborder_id,product_id); return false; } }
    if(size_require==0 && color_require==1){ if(color_id==0){ alert("Select  color"); readyReaplceSelection(suborder_id,product_id); return false; } }			           
    if(size_require==1 && color_require==0){ if(size_id==0){ alert("Select  size"); readyReaplceSelection(suborder_id,product_id); return false; } } }			       
    if(prd_type==2 && returnType==1){ if(color_id==0 || size_id==0 || w_size_id==0){ alert("Select  Replacement"); readyReaplceSelection(suborder_id,product_id); return false; } } $( "#originalButton" ).trigger( "click" ); });		
    $(document).on('change','.filterOrder', function () { ;
        var str=this.value; var base_url="{{URL::to('/')}}"; var type="{{Request()->type}}";
        var url=base_url+"/order_filter/"+type+"/"+str
        window.location.replace(url);
    });	
    $(document).on('click','.Pincodedisplaydisplay_box', function () { var value = $(this).text(); $("#pincode").val(value); $('#Pincodedisplay').hide(''); $('#Pincodedisplay').html(''); });
    function onlyPincodeDigit(evt,val,length_check) {
        var iKeyCode = (evt.which) ? evt.which : evt.keyCode
        if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57)){
            return false;}            
            var n = val.length;
            if(n>length_check){
	        return false;
	    }            	      
        return true;
    }  	   
    function onlyDigit(evt) {
        var iKeyCode = (evt.which) ? evt.which : evt.keyCode
        if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
        return false;
        return true;
    }
    $("#pincode").keyup(function(){ var searchPincode=$(this).val(); var n = searchPincode.length; var isNumber =!/\D/.test(searchPincode); if(n==0){ $('#Pincodedisplay').hide(''); $('#Pincodedisplay').html(''); return false; }
    var token="{{ csrf_token() }}";
    var dataString ='searchPincode='+searchPincode+'&_token='+token;;
    $.ajax({ type:'POST', data:dataString, url:"{{route('searchPincode')}}", success:function(data) { var myObj = JSON.parse(data); if(myObj.dataSize>0){ $('#Pincodedisplay').show(''); $('#Pincodedisplay').html(myObj.html); } else{ $('#Pincodedisplay').hide(''); $('#Pincodedisplay').html(''); } } }); });
	$(document).on('click','.checkPinCode', function () {var element=$(this).attr('element'); var pincode=$('#'+element).val(); var price = $('#prd_price_0').html(); var product_id=$('#'+element).attr('product_id'); var product_name=$('#'+element).attr('product_name'); var menSizeID=$('#'+element).attr('menSizeID'); var womenSizeID=$('#'+element).attr('womenSizeID'); var colorID=$('#'+element).attr('colorID'); var weight=$('#'+element).attr('weight'); var height=$('#'+element).attr('height'); var length=$('#'+element).attr('length'); var width=$('#'+element).attr('width'); var n = pincode.length; if(n!=6){ $('#pincode_msg').html('Enter valid pincode'); return false; } $.ajax({ type:'POST', async:true, url:"{{ route('check_pinCode') }}", data:{ "pincode":pincode, "product_id":product_id, "product_name":product_name, "price":price, "menSizeID":menSizeID, "womenSizeID":womenSizeID, "colorID":colorID, "weight":weight, "height":height, "length":length, "width":width, "qty":1 }, success:function(data) { var myObj = JSON.parse(data); $('#pincode_msg').html(myObj.Msg); localStorage.setItem("pincodechecked",myObj.Error); localStorage.setItem("pincode",pincode); createCookie('pincode',pincode,3000); createCookie('pincode_error',myObj.Error,3000); if(myObj.Error==1){ $('.noProblem').hide(); $('.outOFdelivery').show(); } else{ $('.noProblem').show(); $('.outOFdelivery').hide(); } } }); });
	$(document).on('click','.changeQtyOfCartProduct', function () { changeQtyOfCartProduct(this); }); $(document).on('click','.goBack', function () { window.history.back(); });	
    $(document).on('click','.couponApply', function () { var index=$(this).attr('index'); var cart_total=$(this).attr('cart_total'); var code=$('#Coupon_'+index).val(); var n = code.length; if(n==0){ $('#CouponMsg_'+index).html('Enter valid Code'); setTimeout(function(){ $('#CouponMsg_'+index).html(''); }, 2000); return false; } $.ajax({ type:'POST', async:false, url:"{{ route('apply_coupon') }}", data:{ "code":code, "cart_total":cart_total }, success:function(data) { var myObj = JSON.parse(data); $('#CouponMsg_'+index).html(myObj.Msg); if(myObj.Error==0){ update_cart(); } setTimeout(function(){ $('#CouponMsg_'+index).html(''); }, 2000); } }); });	
	$(document).on('click','.deleteCartItem', function () { var prd_id=$(this).attr('prd_id'); $.ajax({ type:'POST', async:false, url:"{{ route('remove_cart_item') }}", data:{ "prd_id":prd_id }, success:function(data) { var myObj = JSON.parse(data); update_cart(); if(!myObj.error){ $("#cart_item_row_"+prd_id).remove(); } } }); });
	$(".wallet_button").click(function(){ if($(this).prop("checked") == true) { localStorage.setItem('useWallet', 1); console.log("use"); }else{ localStorage.setItem('useWallet', 0); console.log("dont use"); } update_cart(); });        
    function payment_method(method){ localStorage.setItem('payementMethod', method); update_cart(); }
    function shippinginfo(){ $('#phuakatCoinsModal').modal('show'); var html="<p>Free Shipping Available In Order Total Above <i class='fa fa-rupee'></i> "; $(".phuakatCoinsResponse").html(html); } $(".phaukatCoinsInfo").click(function(){ $('#phuakatCoinsModal').modal('show'); var html=" <p>Not Applicable On already Discounted products .Not Applicable if voucher is applied.</p>"; $(".phuakatCoinsResponse").html(html); });
    function update_cart(wallet=1){ $('.outStock').hide(); $('.outofdelivery').hide(); var useWallet=0; var paymentMethod=0; var Walletkey=localStorage.getItem("useWallet"); var paymentKey=localStorage.getItem("payementMethod"); $('#loader').css("display","flex"); if(Walletkey==null){ $("#checkboxwallet1").attr('checked', false); useWallet=0; } else{ if(Walletkey==1){ $("#checkboxwallet1").attr('checked', true); } else{ $("#checkboxwallet1").attr('checked', false); } useWallet=Walletkey; } if(paymentKey==null){ paymentMethod=0; } else{ paymentMethod=paymentKey; } $.ajax({ type:'GET', url:"{{ route('update_cart') }}", data:{ "wallet":useWallet, "paymentMethod":paymentMethod }, success:function(data) { response = JSON.parse(data); $('#cart_total').html(response.total); $('.cart_count').html(response.size); if(response.size>0){ $('#cart_item').html(response.html); $('.productIncart').show(); $('.noProductIncart').hide(); } else{ $('.noProductIncart').show(); $('.productIncart').hide(); } if(response.out_of_stock>0 && response.pincode_error==0){ $('.outStock').show(); $('.inStock').hide(); $('.outofdelivery').hide(); } else if(response.out_of_stock==0 && response.pincode_error==1){ $('.outStock').hide(); $('.outofdelivery').show(); $('.inStock').hide(); } else if(response.out_of_stock>0 && response.pincode_error==0){ $('.outStock').show(); $('.inStock').hide(); $('.outofdelivery').hide(); } if(response.offerProducts>0){ $("#checkboxwallet1").attr('checked', false); $("#checkboxwallet1").attr("disabled", true); } else{ $("#checkboxwallet1").attr("disabled", false); } $('#loader').css("display","none"); $('.cart_table_list').html(response.cart_list_view); $('.reviewOrderBackResponse').html(response.review_order); $('.couponcode_back').val(response.coupon_code); $('.tax_response').html(response.tax); $('.grand_total_with_out_tax_response').html(response.total); $('.discount_reponse').html(response.discount); $('.reward_points_reponse').html(response.available_points); $('#tax').val(response.tax); $('#discount_amount').val(response.discount); $('#wallet_amount').val(response.reward_points); $('#coupon_percent').val(response.coupon_percent); $('#shipping_charges').val(response.shipping_charge); if(response.shippingamount!=0){ $("#offershipping").html("Free Shipping Available In Order Total Above <i class='fa fa-rupee'></i> "+response.shippingamount); } if(response.cod_charges>0){ $('#cod_charge').html(response.cod_charges); } else{ $('#cod_charge').html(0); } $('.shipping_charge_response').html(response.shipping_charge); $('.grand_total_with_tax_response').html(response.grand_total_with_tax); $('.cartGrandTotal').html(response.grand_total_with_tax); $('#grand_total').val(response.grand_total_with_tax); $('#cartToalReview').attr('cart_total',response.total) } }); }
    function changeQtyOfCartProduct(obj){ var method=$(obj).attr('method'); var row=$(obj).attr('row'); var prd_id=$(obj).attr('prd_id'); var size=$(obj).attr('size'); var w_size=$(obj).attr('w_size'); var color=$(obj).attr('color'); var qty=$('#qty_field_'+row).val(); if(method==1){ qty++; } else{ qty==1? qty : qty--; } $('#qty_field_'+row).val(qty); $.ajax({ type:'POST', async:false, url:"{{ route('changeQtyOfCartProduct') }}", data:{ "qty":qty, "prd_id":prd_id, "size":size, "color":color, "w_size_id":w_size }, success:function(data) { var myObj = JSON.parse(data); if(myObj.error==false){ update_cart(); } else{ $('#wishlistModal').modal('show'); $(".wishlistModalResponse").html("Qty not available in stock"); setTimeout(function(){  update_cart(); 	$('#wishlistModal').modal('hide'); }, 2000); } } }); }
    $(document).on('click','.quickView', function () { var prd_id=$(this).attr('prd_id'); var token="{{ csrf_token() }}"; $.ajax({ type:'POST', async:false, url:"{{ route('quickView') }}", data:{ "prd_id":prd_id, "_token":token }, success:function(data) { var myObj = JSON.parse(data); $("#quickVieweModal").modal('show'); $(".quickViewResponse").html(myObj.product); } }); });	
	$(document).on('click','.wishList', function () { var prd_id=$(this).attr('prd_id'); if(window.user_login==1){ var token="{{ csrf_token() }}"; $.ajax({ type:'POST', async:false, url:"{{ route('add_to_wishlist') }}", data:{ "prd_id":prd_id, "_token":token }, success:function(data) { var myObj = JSON.parse(data); update_wishlist(); if(myObj.method==1){ $('#wishlistModal').modal('show'); $(".wishlistModalResponse").html("Porduct Inserted To Your Wishlist"); } else{ $('#wishlistModal').modal('show'); $(".wishlistModalResponse").html("Porduct Updated To Your Wishlist"); } setTimeout(function(){ $('#wishlistModal').modal('hide'); }, 2000); } }); } else{ var url="{{ route('customer_login') }}"; window.location.href = url; } });
	function  update_wishlist(){ $.ajax({ type:'GET', url:"{{ route('update_wishlist') }}", success:function(data) { response = JSON.parse(data); $('#wishlistCounter').html(response.size); } }); }
    $(document).on('click','.savelater', function () { var prd_id=$(this).attr('prd_id'); if(window.user_login==1){ var token="{{ csrf_token() }}"; $.ajax({ type:'POST', async:false, url:"{{ route('add_to_savelater') }}", data:{ "prd_id":prd_id, "_token":token }, success:function(data) {c
    var myObj = JSON.parse(data); update_wishlist(); if(myObj.method==1){ $('#wishlistModal').modal('show'); $(".wishlistModalResponse").html("Product Inserted To Your Save Later"); } else{ $('#wishlistModal').modal('show'); $(".wishlistModalResponse").html("Product Updated To Your Save Later"); } setTimeout(function(){ $('#wishlistModal').modal('hide'); }, 2000); } }); } else{ var url="{{ route('customer_login') }}"; window.location.href = url; } });
	</script>