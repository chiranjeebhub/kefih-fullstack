<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>@yield('pageTitle')</title>
	<meta name="p:domain_verify" content="2486b52a3cfb0675dd1238560b57e36b"/>
	<meta name="title" content="@yield('metaTitle')"/>
	<meta name="Keywords" content="@yield('metaKeywords')"/>
		<meta name="Description" content="@yield('metadescription')"/>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="shortcut icon" href="{{ asset('public/fronted/images/favicon.png') }}" /><link rel="stylesheet" href="{{ asset('public/fronted/css/bootstrap.min.css') }}" /><link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" /><link rel="stylesheet" type="text/css" href="{{ asset('public/fronted/css/owl.carousel.css') }}" /><link rel="stylesheet" href="{{ asset('public/fronted/css/custom.css') }}" /><link rel="stylesheet" href="{{ asset('public/fronted/megamenu/styles.css') }}" type="text/css" media="all" /><link rel="stylesheet" href="{{ asset('public/fronted/css/animate.css') }}"><link rel="stylesheet" href="{{ asset('public/fronted/css/responsive.css') }}" /><link rel="stylesheet" href="{{ asset('public/fronted/css/rateit.css') }}"><link rel="stylesheet" href="{{ asset('public/fronted/css/jquery.fancybox.min.css') }}"><link rel="stylesheet" href="{{ asset('public/fronted/css/bootstrap-select.min.css') }}" /><link rel="stylesheet" href="{{ asset('public/fronted/css/selectize.bootstrap3.min.css') }}" />


	<script type="text/javascript">if (window.location.hash && window.location.hash == '#_=_') {window.location.hash = '';}</script>

<!-- Google Tag Manager -->
<script>
(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WM3N2LW');


</script>
<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"Product",
  "productID":"facebook_tshirt_001",
  "name":"Facebook T-Shirt",
  "description":"Unisex Facebook T-shirt, Small",
  "url":"https://example.org/facebook",
  "image":"https://example.org/facebook.jpg",
  "brand":"facebook",
  "offers": [
    {
      "@type": "Offer",
      "price": "7.99",
      "priceCurrency": "USD",
      "itemCondition": "https://schema.org/NewCondition",
      "availability": "https://schema.org/InStock"
    }
  ],
  "additionalProperty": [{
    "@type": "PropertyValue",
    "propertyID": "item_group_id",
    "value": "fb_tshirts"
  }]
}
</script>
<!-- End Google Tag Manager -->
<!-- Google Tag Manager (noscript) -->

<!-- End Google Tag Manager (noscript) -->
<!-- Facebook Pixel Code -->

<!-- End Facebook Pixel Code -->


</head>
<body style="touch-action: manipulation;">
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WM3N2LW"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!--<div id="loader"><img src="{{ asset('public/fronted/images/loader.gif') }}" width="150px"></div>-->
<div class="bd-example slider-main"><header class="" data-spy="affix" data-offset-top="50"> @include('fronted.includes.header') @include('fronted.mod_cart.cart')</header>
@yield('slider')
</div>
<div class="add-section1"></div>
<?php
$crt=url()->current();
$proper_url_array=explode('/',$crt);
$str=end($proper_url_array);
?>
@if($str!='index' && $str!='awaam' )
<section class="inner-section"><div class="container"><div class="row"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><div class="inner-banner"><div class="breadcrumb">@yield('breadcrum')</div></div></div></div></div></section>
@endif()



  @include('admin.includes.session_message')

	@yield('content')
	@include('fronted.includes.footer')
	@include('fronted.includes.foot')
	@include('fronted.includes.addtocartscript')
	@include('fronted.includes.script')
	@yield('scripts')
	<script>

	$("#BookingBtn").click(function(e){

	     /*var radioValue = $("input[name='delivery_time']:checked").val();
	    // alert(radioValue);
            if(radioValue==undefined){
               	//alert("choose delivery preferences");

           $(".wishlistModalResponse").html("choose delivery slot");
                $('#wishlistModal').modal('show');
                setTimeout(function() {
                    $('#wishlistModal').modal('hide');
                    $(".wishlistModalResponse").html("");
                }, 2000);
      	localStorage.setItem('slotprice', 0);
        return false;
            }
             else{
      	quotation.submit();
        return true;
      }*/
	  quotation.submit();
	  return true;

    /*
var deliveryday  = $(".deliveryday").val();
var delivery_time  = $(".delivery_time").val();

var delivery_time= $('input[name="delivery_time"]:checked').val();
if(delivery_time==''){
	 	alert("choose delivery preferences");
      	localStorage.setItem('slotprice', 0);
        return false;
      }*/


	});
	$(document).ready(function(){
 /*$("form").submit(function(e){
        e.preventDefault();
    });*/


      localStorage.removeItem('shipping_address_id');

		  $('ul.timeslotcheckout li').click(function(){

			$('li').removeClass("active");

			$(this).addClass("active");

		});

	});
	function timeSelected(valu,price){

  	$("#timeslot").text(valu);

    $('#myModalDeliveryslot').modal('hide');

    var dateval = $('.event-date').text();

    var data1=dateval.split('-');

    var year = "<?php echo date('Y'); ?>";

  	//alert(data1[0]);alert(data1[1]);

		$("#deliveryweekday").text(data1[0]);

		$(".datetxt").text(data1[1]);

        $("#time").val(valu);

       // $(".deliveryday").val(data1);

        $('#selected_Date').show();

        $('#select_Date').hide();



    localStorage.setItem('slotprice', price);

    update_cart();



  }
  $(document).ready(function() {

localStorage.setItem('slotprice', 0);

//createCookie('checkouttype',0,3000);

});
	 function changeDelivery(id){





		$.ajax({



          type: "POST",





         url:"{{route('timeslotdata')}}",

                   data:{ "date":id},

          cache: false,

          success: function(data){

			   var response = JSON.parse(data);

                     $('.timeslotoptn').empty();

                      if(response.status==1){
								$(".deliveryday").val();
							}else{
								$(".deliveryday").val(id);
							}


                          if((response.size)>0){


                            $('.timeslotoptn').append(response.timeslot);

                          }



          }



      });





		}
	</script>
<div class="quickview-model"><div class="modal fade fadeInUp animated" id="quickVieweModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button><div class="modal-body quickViewResponse"></div></div></div></div></div>
<div class="quickview-model"><div class="modal fade fadeInUp animated" id="phuakatCoinsModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button><div class="modal-body phuakatCoinsResponse"></div></div></div></div></div>
<div class="cartpopup">

<div class="modal fade fadeInUp animated" id="otherSellerModal" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Other Offers</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body otherSellerBodyResponse">
        Modal body..
      </div>



    </div>
  </div>
</div>

    <div class="modal fade fadeInUp animated" id="wishlistModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-body wishlistModalResponse"></div></div></div></div></div>
<div class="sizechart-model"><div class="modal fade fadeInUp animated" id="showsizechart" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button><div class="modal-body"><div class="row"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 showsizechartResponse"></div></div></div></div></div></div></div>

<script>
$(document).ready(function(){
  $("#see-all").click(function(){
    $("#see-all-hide").hide();
  });
  $("#see-all").click(function(){
    $("#sellers-table").show();
  });
});
    //new
    $(document).ready(function(){
  $("#see-all2").click(function(){
    $("#see-all-hide2").hide();
  });
  $("#see-all2").click(function(){
    $("#sellers-table2").show();
  });
});
    //new
    $(document).ready(function(){
  $("#see-all3").click(function(){
    $("#see-all-hide3").hide();
    $("#sellers-table3").show();
    $("#sellers-table1").hide();
  });

});
    //new
    $(document).ready(function(){
  $("#see-all4").click(function(){
    $("#see-all-hide4").hide();
  });
  $("#see-all4").click(function(){
    $("#sellers-table4").show();
  });
});


const copyToClipboard = str => {

  const el = document.createElement('textarea');

  el.value = str;

  document.body.appendChild(el);

  el.select();

  document.execCommand('copy');

  document.body.removeChild(el);

};

document.getElementById('myItem').addEventListener('click', function(e){

  let myUrl =  e.target.dataset.page_id;

   //var myUrl = $(this).data("page_id");

  copyToClipboard( myUrl );
  $('#wishlistModal').modal('show');
                    $(".wishlistModalResponse").html('copied');
                    setTimeout(function() {
                        $('#wishlistModal').modal('hide');
                    }, 2000);
  //alert('copied!')

});

</script>
</body></html>
