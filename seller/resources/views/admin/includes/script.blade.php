<script src=" {{ asset('public/assets/vendor_components/jquery-3.3.1/jquery-3.3.1.js') }}"></script>
	
	<!-- jQuery UI 1.11.4 -->
	<script src="{{ asset('public/assets/vendor_components/jquery-ui/jquery-ui.js') }}"></script>
	
	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>
	  $.widget.bridge('uibutton', $.ui.button);
	</script>
	
	<!-- popper -->
	<script src=" {{ asset('public/assets/vendor_components/popper/dist/popper.min.js') }}"></script>
	
	<!-- Bootstrap 4.0-->
	<script src="{{ asset('public/assets/vendor_components/bootstrap/dist/js/bootstrap.js') }}"></script>
	
	<!-- Slimscroll -->
	<script src="{{ asset('public/assets/vendor_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
	

	<script src="{{ asset('public/assets/vendor_components/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
	
	<!-- FastClick -->
	<script src=" {{ asset('public/assets/vendor_components/fastclick/lib/fastclick.js') }}"></script>
	
	<!-- Morris.js charts -->
	<script src=" {{ asset('public/assets/vendor_components/raphael/raphael.min.js') }}"></script>
	<script src=" {{ asset('public/assets/vendor_components/morris.js/morris.min.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
	
	<!-- Fab Admin App -->
	<script src=" {{ asset('public/js/template.js') }}"></script>
	
	<!-- Fab Admin dashboard demo (This is only for demo purposes) -->
	<script src=" {{ asset('public/js/pages/dashboard.js') }}"></script>
	
	<!-- Fab Admin for demo purposes -->
	<script src=" {{ asset('public/js/demo.js') }}"></"></script>	
	
	<!-- Fab admin horizontal-layout -->
	<script src=" {{ asset('public/js/horizontal-layout.js') }}"></script>
	
	 <script src=" {{ asset('public/assets/vendor_components/datatable/datatables.min.js') }}"></script>
	
	<!-- Fab Admin for Data Table -->
	<script src=" {{ asset('public/js/pages/data-table.js') }}"></script>
	<script src=" {{ asset('public/js/pages/advanced-form-element.js') }}"></script>
	
	<script src="{{ asset('public/assets/vendor_components/ckeditor/ckeditor.js') }}"></script>

	
	<!-- Fab Admin for editor -->
	<script src=" {{ asset('public/js/pages/editor.js') }}"></script>

	<script src="{{ asset('public/js/jquery.rateit.min.js') }}"></script>
	<script>
	console.log("hello");
	$(document).ready(function() {
		
		$('#search').on('keyup',function(){
		$value=$(this).val();
		$.ajax({
		type : 'get',
		url : '{{URL::to('admin/search')}}',
		data:{'search':$value},
		success:function(data){
			var myObj = JSON.parse(data);
			$('.tbody').html(myObj.data);
		
		}
		});
		});
		
     
	 setTimeout(function(){
 $(".alert_message").hide();
  $(".alert").hide();
		 }, 60000);
		 
		 
		$.ajaxSetup({
		headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
		});
		
		
		$(document).on('click','.disableAfterCick', function () {
			
	});

});

$(document).on('keyup','.cat_url', function () {
	
		if (this.value.match(/[^a-zA-Z0-9\-]/g)) {
			var value = this.value.replace(/[^a-zA-Z0-9 \-]/g, '');
			  this.value=value.replace(/\s+/g, '-');
			
			
		}
	});
	
	
	$(document).on('click','.resetModalForm', function () {
	 $(".resetAbleForm").trigger("reset");
	});
	
	$(document).on('click','.showcat', function () {
		$(this).parent().parent().parent().children( ".element" ).show();
		$(this).parent().html('<i class="fa fa-plus pointer hidecat" aria-hidden="true"></i>');
	 
	});
	
	$(document).on('click','.ext_product_button', function () {
					$("#myModal").hide('hide');
	});
	
	$(document).on('click','.disableAfterClick', function () {
		
	 
	});
	
	
	$(document).on('change','.offerType', function () {
		 switch(this.value) {
            case '0':
                $(".brandClass").val('');
            $('.brandSelection').hide();
            $('.categorySelection').show();
            break;
            
            case '1':
                $(".catClass").val('');
                $('.brandSelection').show();
                $('.categorySelection').hide();
            break;
        
        }
  
});

$(document).on('change','.CouponAssignType', function () {

		 switch(this.value) {
            case '1':
                    
                    $('.categoryBox').show();
                    $('.brandSelection').hide();
                    $('.productSelection').hide();
				  break;
            case '2':
                $(".catClass").val('');
				$(".productSelection").val('');
                $('.brandSelection').show();
                $('.categoryBox').hide();
				$('.productSelection').hide();
            break;
			case '3':
                $(".catClass").val('');
				$(".brandClass").val('');
				$('.productSelection').show();
                $('.brandSelection').hide();
                $('.categoryBox').hide();
            break;
        
        }
  
});

		$(document).on('change','.couponType', function () {
	
		    
        switch(this.value) {
            case '0':
                    $(".coupon_cart").hide(); 
                    $(".coupon_dt").hide(); 
                    $(".coupon_number").hide(); 
            break;
            
            case '1':
                    $(".coupon_cart").show(); 
                    $(".coupon_dt").hide(); 
                    $(".coupon_number").hide(); 
            break;
            
            case '2':
                        $(".coupon_cart").hide(); 
                        $(".coupon_dt").show(); 
                        $(".coupon_number").hide(); 
            break;
            
            case '3':
                        $(".coupon_cart").show(); 
                        $(".coupon_dt").show(); 
                        $(".coupon_number").hide(); 
            break;
            
             case '4':
                    $(".coupon_cart").hide(); 
                    $(".coupon_dt").hide(); 
                    $(".coupon_number").show(); 
            break;
            
            case '5':
                    $(".coupon_cart").show(); 
                    $(".coupon_dt").hide(); 
                    $(".coupon_number").show(); 
            break;
            
            case '6':
                    $(".coupon_cart").hide(); 
                    $(".coupon_dt").show(); 
                    $(".coupon_number").show(); 
            break;
            
            case '7':
                    $(".coupon_cart").show(); 
                    $(".coupon_dt").show(); 
                    $(".coupon_number").show(); 
            break;
        }
        
    
  
   
});
	$(document).on('click','.show_hide', function () {
	
	 if($(this).val()==0){
		 $(".logistics_container").hide();
	 } else{
		  $(".logistics_container").show();
	 }
	 
	});
	
	
	
	$(document).on('click','.reset_form', function () {
 $(".form").trigger("reset");
	});
	
	$(document).on('click','.edit', function () {
			
			var role_id=$(this).attr("data-id");
			var role_name=$(this).attr("data-value");

			$('#edit_name').val(role_name);
			$('#user_role_id').val(role_id);
			
	});
	$(document).on('click','.goBack', function () {
			window.history.back();
	});
	
	
	 $(document).on('click','.viewReaplcedOrder', function () {
	     
             var suborder_id=$(this).attr("suborder_id");
               $.ajax({
		type : 'post',
		url : '{{URL::to('admin/getReaplcedOrder')}}',
		data:{'suborder_id':suborder_id},
		success:function(data){
            var myObj = JSON.parse(data);   
		     $("#replaced_dialog").modal('show');
            $('.replaced_model_body').html(myObj.HTML);
		}
		});
            
	 });
       
        $(document).on('click','.replace_order', function () {
            $('.replace_model_body_attr_size').html("");
             $('.replace_model_inputs').html("");
            	$(".size_class").hide();
            		$(".color_class").hide();
        var suborder_id=$(this).attr("suborder_id");
         var product_id=$(this).attr("product_id");
        $("#replace_dialog").modal('show');
     
        $.ajax({
		type : 'post',
		url : '{{URL::to('admin/suborder_product_details')}}',
		data:{'suborder_id':suborder_id,'product_id':product_id},
		success:function(data){
                var myObj = JSON.parse(data);
                    $('.replace_model_header').html(myObj.product_name);
                    $('.replace_model_body_attr_size').html(myObj.sizes_html);
                    $('.replace_model_body_attr_color').html(myObj.color_html);
                    $('.replace_model_inputs').html(myObj.inputs);
                 if(myObj.sizes>0){
                     	$(".size_class").show();
                 } else{
                     	$(".size_class").hide();
                 }
                  if(myObj.colors>0){
                     	$(".color_class").show();
                 } else{
                     	$(".color_class").hide();
                 }
		    
		}
		});
        });
        
        $(document).on('click','.sizeClass', function () {
        var size_id=$(this).attr('size_id');
                        $('#size_id').val(size_id);
                        $('#color_id').val(0);
				var prd_id=$(this).attr('prd_id');
				var token="{{ csrf_token() }}";
				 $(".sizeClass").removeClass("active");
				$(this).addClass('active');
			$.ajax({
               type:'POST',
			   async:true,
                url:"{{ route('admin_get_attr_dependend') }}",
               data:{
						"size_id":size_id,
						"prd_id":prd_id,
						"attr_name":'Colors',
							"_token":token
				   },
               success:function(data) {
            var myObj = JSON.parse(data);
            $("#"+myObj.print_to).html(myObj.html);
						
               }
            });
        });
        
        $(document).on('click','.colorClass', function () {
        $(".colorClass").removeClass("active");
        $(this).addClass('active');
            var color_id=$(this).attr('color_id');
            $('#color_id').val(color_id);
        });
        
         $(document).on('click','.replaceOrderCurrent', function () {
             	 
                var color_id=$('#color_id').val();
                var size_id=$('#size_id').val();
                var product_type=$('#product_type').val();
                var order_id=$('#order_id').val();
                var product_id=$('#product_id').val();
                
                var remarks=$('#remarks').val();
                	var token="{{ csrf_token() }}";
                if(product_type==3){
                    if(color_id==0 || size_id==0){
                        alert("Select color or size to replace");
                        return false;
                    }
                }
               
			$.ajax({
               type:'POST',
			   async:false,
                url:"{{ route('replaceOrder') }}",
               data:{
                        "color_id":color_id,
                        "size_id":size_id,
                        "product_id":product_id,
                         "remarks":remarks,
                        "order_id":order_id,
                        "_token":token
				   },
               success:function(data) {
                     var myObj = JSON.parse(data);
                     if(myObj.ERROR=='NONE'){
                        $('#table_row'+order_id).remove();
                        $("#replace_dialog").modal('hide');
                        alert("Request processing");
                        $(this).hide();
                        setTimeout(function(){  location.reload(); }, 2000);
                    }
                    if(myObj.ERROR=='YES'){
                         alert("something went wrong");
                    }
                   
						
               }
            });
        });
       
        
	
	$(document).on('click','.sell_class', function () {
var prd_id=$(this).attr("data");
$('#product_id').val(prd_id);
	$("#product_form").modal('show');
	$('.atr_group').html('');
	$(".add_atr").hide();
$.ajax({
		type : 'get',
		url : '{{URL::to('admin/getProDetails')}}',
		data:{'prd_id':prd_id},
		success:function(data){
					var myObj = JSON.parse(data);
					$('#price').val(myObj.product_details.price);
					$('#qty').val(myObj.product_details.qty);
					$('#spcl_price').val(myObj.product_details.spcl_price);
					$('#spcl_from_date').val(myObj.product_details.spcl_from_date);
					$('#spcl_to_date').val(myObj.product_details.spcl_to_date);
					$('#manage_stock').val(myObj.product_details.manage_stock);
					$('#qty_out').val(myObj.product_details.qty_out);
					$('#stock_availability').val(myObj.product_details.stock_availability);
					if(myObj.product_attr_length>0){
						$('.atr_group').append(myObj.product_attr);
						$(".add_atr").show();
					} else{
						$('.atr_group').append('');
						$(".add_atr").hide();
					}
					
		}
		});
	});
	
		

	
	$(document).on('submit', 'form.productForm', function() { 
            $("#errors").html("");
			$(".ajaxSubmitButton").attr("disabled", true);
        $.ajax({
            url     : $(this).attr('action'),
            type    : $(this).attr('method'),
            dataType: 'json',
            data    : $(this).serialize(),
            success : function(data) {
				
					if(data.Error=='NO'){
						var id=$('#product_id').val();
						$("#product_row_"+id).hide();
						
                         $(".productForm").trigger("reset");
						 setTimeout(function(){  $("#errors").html(""); $("#product_form").modal('hide'); }, 2000);
						 
					} 
					$("#errors").append(data.Msg);
                 $(".ajaxSubmitButton").attr("disabled", false);					
            },
            error: function(xhr, status, error) 
        {
$(".ajaxSubmitButton").attr("disabled", false);	
          $.each(xhr.responseJSON.errors, function (key, item) 
          {
            $("#errors").append("<li class='alert alert-danger'>"+item+"</li>");
          });

        }
        });    
        return false;
    });
	
	
	$(document).on('click','.hidecat', function () {
		$(this).parent().parent().parent().children( ".element" ).hide();
		$(this).parent().html('<i class="fa fa-minus pointer showcat" aria-hidden="true"></i>');
	  
	});
	
	
	$(document).on('keyup','.onlych', function () {
	
		if (this.value.match(/[^a-zA-Z\-]/g)) {
			var value = this.value.replace(/[^a-zA-Z \-]/g, '');
			  this.value=value.replace(/\s+/g, '-');
			
			
		}
	});
	
	
	$(document).on('keyup','.onlych2', function () {
	
		if (this.value.match(/[^a-zA-Z\-]/g)) {
			var value = this.value.replace(/[^a-zA-Z \-]/g, '');
			  this.value=value.replace(/\s+/g, '');
			
			
		}
	});
	
	$(document).on('keyup','.size', function () {
	
		if (this.value.match(/[^0-9\-]/g)) {
			var value = this.value.replace(/[^0-9 \-]/g, '');
			  this.value=value.replace(/\s+/g, '-');
			
			
		}
	});
	
	$(document).on('keyup','.search_string', function () {
		var str=this.value;
		if(str.length>0){
			$(".searchButton").attr("disabled", false);
		} else{
			$(".searchButton").attr("disabled", true);
		}
	
	});
	$(document).on('keyup','.size2', function () {
	
		if (this.value.match(/[^0-9\-]/g)) {
			var value = this.value.replace(/[^0-9 \-]/g, '');
			  this.value=value.replace(/\s+/g, '');
			
			
		}
	});
		$(document).on('click','.removeAttributes', function () {
		var attr_id=$(this).attr("attr_id");
		var attr_name=$(this).attr("attr_name");
		var html='';
				html+='<tr  id="table_row_2'+attr_id+'">';
				html+='<td>'+attr_name+'</td>';
				html+='<td><i class="fa fa-plus text-red addTolist"  attr_id="'+attr_id+'" attr_name="'+attr_name+'" aria-hidden="true"></i></td>';
				html+='</tr>';
			$('#table_row_'+attr_id).remove();
			$('.tableListItem2').append(html);			
	});
	$(document).on('click','.addTolist', function () {
		var attr_id=$(this).attr("attr_id");
		var attr_name=$(this).attr("attr_name");
		var count = $(".tableListItem").children().length;
		count++;
		var html='';
				html+='<tr  id="table_row_'+count+'">';
				html+='<input type="hidden" name="arrt_id[]" value="'+attr_id+'">';
				html+='<td>'+attr_name+'</td>';
				html+='<td><i class="fa fa-trash text-red removeAttributes"  attr_id="'+count+'" attr_name="'+attr_name+'" aria-hidden="true"></i></td>';
				html+='</tr>';
		$('#table_row_2'+attr_id).remove();	
		$('.tableListItem').append(html);				
	});



function image_preview(event){
		

		const file = event.target.files[0];
		const  fileType = file['type'];
		const validImageTypes = ['image/gif', 'image/jpeg', 'image/jpg', 'image/bmp', 'image/png'];
		if (!validImageTypes.includes(fileType)) {
			// invalid file type code goes here.
			alert("Invalid Image");
			return false;
		}
		
		var a=(file.size);
        //alert(a);
        //4000000 byte  in decimal
        //4194304 byte in binary
        if(a >= 4194304) {
            alert("File Can't be more than 4 MB");
			return false;
        };

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

function image_preview_brandlogo(event){
		

		const file = event.target.files[0];
		const  fileType = file['type'];
		const validImageTypes = ['image/gif', 'image/jpeg', 'image/jpg', 'image/bmp', 'image/png'];
		if (!validImageTypes.includes(fileType)) {
			// invalid file type code goes here.
			alert("Invalid Image");
			return false;
		}
		
		var a=(file.size);
        if(a >= 1048576) {
            alert("File Can't be more than 1024kb");
			return false;
        };

		image_preview_main(event);
}

function image_preview_bannerlogo(event){
		

		const file = event.target.files[0];
		const  fileType = file['type'];
		const validImageTypes = ['image/gif', 'image/jpeg', 'image/jpg', 'image/bmp', 'image/png'];
		if (!validImageTypes.includes(fileType)) {
			// invalid file type code goes here.
			alert("Invalid Image");
			return false;
		}
		
		var a=(file.size);
        if(a >= 2097152) {
            alert("File Can't be more than 2048kb");
			return false;
        };

		image_preview_main(event);
}

function image_preview_bannernoc(event){
		
		const file = event.target.files[0];
		const  fileType = file['type'];
		const validImageTypes = ['application/pdf'];
		if (!validImageTypes.includes(fileType)) {
			// invalid file type code goes here.
			alert("Invalid File");
			return false;
		}
		
		var a=(file.size);
        if(a >= 2097152) {
            alert("File Can't be more than 2048kb");
			return false;
        };

}

function image_preview_main(event){
		

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


$(document).on('change','#selectState', function () {
                
                 $('#selectcity option:not(:first)').remove();
                 //var state_id=$(this).val();
                 var state_id = $(this).find(':selected').data('id');
                 
            if(state_id!=''){
               
                $.ajax({
                   type:'POST',
    			   async:true,
                    url:"{{ route('filterCityOnState') }}",
                   data:{ "state_id":state_id},
                   success:function(data) {
                           
                          var response = JSON.parse(data);
                          console.log(response);
                          
                          if((response.size)>0){
                            $('#selectcity').append(response.city); 
                            $('#selectcity').addClass('custom-select');
                            //$('#selectcity').addClass('selectpicker');custom-select 
                            $('#selectcity').attr('data-live-search', 'true');
                             $('#selectcity').selectize('refresh');
                              
                          }
                         
                   }
                });
            }
            
            
            });

	</script>