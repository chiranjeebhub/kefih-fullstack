

	
	<script>
	$(document).ready(function() {
		
		related_product_seach();
		up_sell_product_search();
		cross_sell_product_search();
		function related_product_seach(){
			$.ajax({
				method: "get",
				async:false,
					url : '{{URL::to('admin/related_products_search')}}',
				data: {
					SearchByName: $('#related_product_searchByname').val(),
					SearchByStatus: $('#related_product_searchByStatus').val(),
					SearchByVisibility:$('#related_product_searchByVisibillity').val()
				}
			})
			.done(function( res ) {
				     $("#productTable tbody").html('');
					$(".product_checkbox").prop("checked",false);
					var json = jQuery.parseJSON( res );
					$("#productTable tbody").html(json.table_data);
			});
		}
  $('#related_product_searchByVisibillity').change(function(){
	  related_product_seach();
  });
  
  $('#related_product_searchByStatus').change(function(){
     related_product_seach();
  });
  $('#related_product_searchByname').keyup(function(){
	related_product_seach();
  });
  
  
  
		function up_sell_product_search(){
			$.ajax({
				method: "get",
				async:false,
					url : '{{URL::to('admin/up_sell_product_search')}}',
				data: {
					SearchByName: $('#up_sell_product_searchByname').val(),
					SearchByStatus: $('#up_sell_product_searchByStatus').val(),
					SearchByVisibility:$('#up_sell_product_searchByVisibillity').val()
				}
			})
			.done(function( res ) {
				     $("#up_sell_productTable tbody").html('');
					$(".product_checkbox").prop("checked",false);
					var json = jQuery.parseJSON( res );
					$("#up_sell_productTable tbody").html(json.table_data);
			});
		}
  $('#up_sell_product_searchByVisibillity').change(function(){
	  up_sell_product_search();
  });
  
  $('#up_sell_product_searchByStatus').change(function(){
     up_sell_product_search();
  });
  $('#up_sell_product_searchByname').keyup(function(){
	up_sell_product_search();
  });
  
  function cross_sell_product_search(){
			$.ajax({
				method: "get",
				async:false,
					url : '{{URL::to('admin/cross_sell_product_search')}}',
				data: {
					SearchByName: $('#cross_sell_product_searchByname').val(),
					SearchByStatus: $('#cross_sell_product_searchByStatus').val(),
					SearchByVisibility:$('#cross_sell_product_searchByVisibillity').val()
				}
			})
			.done(function( res ) {
				  $("#cross_sell_productTable tbody").html('');
					$(".product_checkbox").prop("checked",false);
					var json = jQuery.parseJSON( res );
					$("#cross_sell_productTable tbody").html(json.table_data);
			});
		}
		
		
		 $('#cross_sell_product_searchByVisibillity').change(function(){
	  cross_sell_product_search();
  });
  
  $('#cross_sell_product_searchByStatus').change(function(){
     cross_sell_product_search();
  });
  $('#cross_sell_product_searchByname').keyup(function(){
	cross_sell_product_search();
  });
    
	$('.imageParentDiv').append('<?php echo $page_details["return_data"]["image_html"];?>');
	
	
	$(document).on('click','.remove_atr', function () {
	$(this).parent().parent().parent().remove();
	  
	});
	
	$(document).on('click','.removeImage', function () {
	 var index=$(this).attr("data");
	 $(this).parent().remove();
	   $('#prd_image_id_'+index).remove();
	});
	
		$(document).on('click','.removeColorImags', function () {
	 var imageId=$(this).attr("imageId");
	 var html='<input type="hidden" name="removed_color_id[]" value="'+imageId+'">';
        $(this).parent().remove();
        $('#removedImages').append(html);
	 
	});
	
	$(document).on('click','.add_atr', function () {
		var count = $(".atr_group").children().length;
	var html='<div class="row">';
	
		html+='<div class="col-md-2">';
	html+='<?php echo App\Helpers\CustomFormHelper::form_builder($page_details["Form_data"]["Form_field"]["product_color_field"]);?>';
	html+='</div>';
	
	html+='<div class="col-md-2">';
	html+='<?php echo App\Helpers\CustomFormHelper::form_builder($page_details["Form_data"]["Form_field"]["product_size_field"]);?>';
	html+='</div>';
	
	

	
		html+='<div class="col-md-2">';
		html+='<?php echo App\Helpers\CustomFormHelper::form_builder($page_details["Form_data"]["Form_field"]["product_atr_qty_field"]);?>';
		html+='</div>';
		
		
		
		
			html+='<div class="col-md-2">';
		html+='<?php echo App\Helpers\CustomFormHelper::form_builder($page_details["Form_data"]["Form_field"]["product_atr_price_field"]);?>';
		html+='</div>';
		
			html+='<div class="col-md-2">';
		html+='<?php echo App\Helpers\CustomFormHelper::form_builder($page_details["Form_data"]["Form_field"]["product_barcode_field"]);?>';
		html+='</div>';
	
	html+='<div class="col-md-2">';
	html+='<div class="form-group">';
	html+='<span class="remove_atr pointer"><i class="fa fa-trash text-red"></i></span>';
	html+='</div>';
	html+='</div>';
	
	html+='</div>';
	$('.atr_group').append(html);
	});
	
	$(document).on('click','.add_sexatr', function () {
		var count = $(".atr_group").children().length;
	var html='<div class="row">';
	
	html+='<div class="col-md-2">';
	html+='<label>Unisex Type</label><select class="form-control" name="unisex_type[]" id="unisex_type"><option value="1">Men</option><option value="2">Women</option></select>';
	html+='</div>';
		html+='<div class="col-md-2">';
	html+='<?php echo App\Helpers\CustomFormHelper::form_builder($page_details["Form_data"]["Form_field"]["product_color_field"]);?>';
	html+='</div>';
	
	
	html+='<div class="col-md-2">';
	html+='<?php echo App\Helpers\CustomFormHelper::form_builder($page_details["Form_data"]["Form_field"]["product_size_field"]);?>';
	html+='</div>';
	
	

	
		html+='<div class="col-md-2">';
		html+='<?php echo App\Helpers\CustomFormHelper::form_builder($page_details["Form_data"]["Form_field"]["product_atr_qty_field"]);?>';
		html+='</div>';
		
		
			html+='<div class="col-md-2" style="display:none;">';
		html+='<?php echo App\Helpers\CustomFormHelper::form_builder($page_details["Form_data"]["Form_field"]["product_atr_price_field"]);?>';
		html+='</div>';
		
		
			html+='<div class="col-md-2">';
		html+='<?php echo App\Helpers\CustomFormHelper::form_builder($page_details["Form_data"]["Form_field"]["product_barcode_field"]);?>';
		html+='</div>';
	
	html+='<div class="col-md-2">';
	html+='<div class="form-group">';
	html+='<span class="remove_atr pointer"><i class="fa fa-trash text-red"></i></span>';
	html+='</div>';
	html+='</div>';
	
	html+='</div>';
	$('.atr_group').append(html);
	});
	
	
	$(".product_checkbox").change(function() {
    var ischecked= $(this).is(':checked');
	$(".product_checkbox_child").prop("checked",ischecked);
  }); 
});

	

	
	</script>