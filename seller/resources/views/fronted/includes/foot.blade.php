<button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fa fa-arrow-up"></i></button><!-- Modal login--><div id="loginmodal" class="modal fade" role="dialog"><!-- tab system --><div class="tab-content"><div id="homelogin" class="tab-pane fade in active"><div class="modal-dialog modal-sm"><!-- Modal content--><div class="modal-content"><div class="modal-body"><button type="button" class="close" data-dismiss="modal"><img src="{{ asset('public/fronted/images/cross-out.png') }}"/></button><div class="register clearfix"><div class="registrform login"><h6>Login</h6><p class="login_error"></p><div class="form-group"><input type="email" class="form-control" id="email_log" placeholder="Enter Email Id / Mobile Number"></div><div class="form-group"><input type="password" class="form-control" id="password_log" placeholder="Password"></div><!-- <p>--><!--<a id="otpclick" data-toggle="tab" href="#menuotp">Login with OTP </a>--><!--</p>--><span  class="registrbtn login_sws"><i class="fa fa-lock"></i> Login</span><div class="or"><span>Or</span></div><h1 class="fs16 mb20 mt20"> Login/Sign Up With</h1><div class="login-socialmedia"><a class="fb" href="#"><span><i class="fa fa-facebook"></i></span> facebook</a> <a class="instagram" href="#"><span><i class="fa fa-instagram"></i></span> instagram</a>   <a class="google" href="#"><span><i class="fa fa-google"></i></span> google</a>    </div> <div class="form-group"><span>New User?</span> <a data-toggle="tab" href="#menusignup"> Sign Up</a></div></div></div></div></div></div></div><div id="menuotp" class="tab-pane fade"><div class="modal-dialog modal-sm"><!-- Modal content--><div class="modal-content"><div class="modal-body"><span class="backbtn" data-toggle="tab" data-target="#homelogin"><i class="fa fa-angle-left"></i> </span> <button type="button" class="close" data-dismiss="modal"><img src="{{ asset('public/fronted/images/cross-out.png') }}"/></button><div class="register clearfix"><div class="registrform forgetpassword"><h6>OTP </h6><form action=""><div class="form-group"><input type="text" class="form-control" id="otp" placeholder="OTP"></div><button type="submit" class="registrbtn"> Submit</button></form></div></div></div></div></div></div><div id="menuforgot" class="tab-pane fade"><div class="modal-dialog modal-sm"><!-- Modal content--><div class="modal-content"><div class="modal-body"><span class="backbtn" data-toggle="tab" data-target="#homelogin"><i class="fa fa-angle-left"></i> </span> <button type="button" class="close" data-dismiss="modal"><img src="{{ asset('public/fronted/images/cross-out.png') }}"/></button><div class="register clearfix"><div class="registrform forgetpassword"><h6>Forgot Password</h6><form action=""><div class="form-group"><input type="email" class="form-control" id="email" placeholder="Email id / Mobile Number"></div><button type="submit" class="registrbtn"> Submit</button></form></div></div></div></div></div></div><div id="menusignup" class="tab-pane fade"><div class="modal-dialog"><!-- Modal content--><div class="modal-content"><div class="modal-body"><span class="backbtn" data-toggle="tab" data-target="#homelogin"><i class="fa fa-angle-left"></i> </span> <button type="button" class="close" data-dismiss="modal"><img src="{{ asset('public/fronted/images/cross-out.png') }}"/></button><div class="register clearfix"><div class="registrform"><h6>Create Account</h6><form action=""><div class="row"><div class="col-sm-6"><div class="form-group"><input type="text" class="form-control" id="name" placeholder="Name"><i class="fa fa-user"></i></div></div><div class="col-sm-6"><div class="form-group"><input type="text" class="form-control" id="mobile" placeholder="Mobile Number"><i class="fa fa-phone"></i></div> </div></div><div class="row"><div class="col-sm-6"><div class="form-group"><input type="email" class="form-control" id="email" placeholder="Email"><i class="fa fa-envelope"></i></div></div><div class="col-sm-6"><div class="form-group"><input type="password" class="form-control" id="password" placeholder="Password"><i class="fa fa-lock"></i></div></div></div><div class="form-group"><input type="password" class="form-control" id="password" placeholder="Confirm Password"><i class="fa fa-lock"></i></div> <button type="submit" class="registrbtn">Submit</button></form><div class="or"><span>Or</span></div><h1 class="fs16 mb20 mt20"> Login/Sign Up With</h1><div class="login-socialmedia"><a class="fb" href="#"><span><i class="fa fa-facebook"></i></span> facebook</a> <a class="instagram" href="#"><span><i class="fa fa-instagram"></i></span> instagram</a>   <a class="google" href="#"><span><i class="fa fa-google"></i></span> google</a></div> </div></div></div></div></div></div></div></div><button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fa fa-arrow-up"></i></button><script type="text/javascript" src="{{ asset('public/fronted/js/jquery-2-1-1.min.js') }}"></script><script src="{{ asset('public/fronted/js/owl.carousel.js') }}"></script><script src="{{ asset('public/fronted/js/bootstrap.min.js') }}"></script><script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js" type="text/javascript"></script><script src="{{ asset('public/fronted/js/jquery.jcarousellite.min.js') }}"></script>


<script type="text/javascript" src="{{ asset('public/fronted/js/jquery.elevateZoom.min.js') }}"></script>

 <script  type="text/javascript">
 var $j = jQuery.noConflict(true);

// $(document).ready(function(){ if($('.detail-gallery').length>0){ $('.detail-gallery').each(function(){ var data=$(this).find(".carousel").data(); $(this).find(".carousel").jCarouselLite({ btnNext: ".nextMe", btnPrev: ".prevMe", speed: 400, visible:5, vertical:true, wrap: 'both' }); $j(this).find('#img_zoom').elevateZoom({ zoomType: "inner", cursor: "crosshair", responsive:true, easing:true }); $(this).find(".carousel a").on('click',function(event) { event.preventDefault(); $(this).parents('.detail-gallery').find(".carousel a").removeClass('active'); $(this).addClass('active'); var z_url =  $(this).find('img').attr("src"); $(this).parents('.detail-gallery').find("#img_zoom").attr("src", z_url); $('.zoomWindow').css('background-image','url("'+z_url+'")'); }); }); }  });

</script>
<script src="{{ asset('public/fronted/js/selectize.min.js') }}" ></script><script>$(document).ready(function () { $('.custom-select').selectize({ sortField: 'text' }); });</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script><script type="text/javascript" src="{{ asset('public/fronted/js/jquery-ui-time-range.min.js') }}"></script>
<?php $my_url =  url()->current(); if(strpos($my_url, "cat")!=0 || strpos($my_url, "search")!=0  ){ } else{ $min_price=100; $max_price=200;  $min_price1=100; $max_price1=200; } ?>
<script>

var minPrice="<?php echo $min_price;?>";
var maxPrice="<?php echo $max_price;?>";

var minPrice1="<?php echo $min_price1;?>";
var maxPrice2="<?php echo $max_price1;?>";

if(minPrice1==''){
    minPrice1=minPrice
}

if(maxPrice2==''){
    maxPrice2=maxPrice
}

    $("#slider-range").slider({ 
        range: true,
    min: <?php echo $min_price;?>, 
    max: <?php echo $max_price;?>,
    step: 5,
    values: [minPrice, maxPrice], 
    slide: function (e, ui) { 
    var hours1 = Math.floor(ui.values[0]); 
    
    $('.slider-time').html(hours1); 
    
    var hours2 = Math.floor(ui.values[1]);
    
    $('.slider-time2').html(hours2); }
    
        
    });
    
    </script>
    <script src="{{ asset('public/fronted/js/bootstrap-select.min.js') }}"></script><script> $('ul.nav li.dropdown').hover(function() { $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500); }, function() { $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500); });</script><script src="{{ asset('public/fronted/js/jquery.fancybox.min.js') }}"></script><script>$(".fancybox").fancybox();</script><script src="{{ asset('public/fronted/js/selectordie.js') }}"></script><script src="{{ asset('public/fronted/js/custom.js') }}" ></script><script src="{{ asset('public/js/jquery.rateit.min.js') }}"></script><script> if($(window).width() >= 767){ $('ul.nav li.dropdown').hover(function() {  $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);}, function() {  $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);}); }</script><script> document.querySelector('.hello').scrollIntoView({ behavior: 'smooth' }); let mainNavLinks = document.querySelectorAll("nav ul li a"); mainNavLinks.forEach(link => { link.addEventListener("click", event => { event.preventDefault(); let target = document.querySelector(event.target.hash); target.scrollIntoView({ behavior: "smooth", block: "start" }); }); });</script><script>$(document).ready(function(){ $("#searchterm").keyup(function() { $("#display").empty(); var searchbox = $(this).val(); var token="{{ csrf_token() }}"; var dataString = 'keyword='+ searchbox+'&_token='+token; if(searchbox==''){ $("#display").hide(); }else{ $.ajax({ type: "POST", url: "{{ route('search_filter') }}", data: dataString, cache: false, success: function(html) { $("#display").html(html).show(); } }); } return false; });	});	</script><script> window.onscroll = function() {scrollFunction()}; function scrollFunction() { if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {document.getElementById("myBtn").style.display = "block"; } else { document.getElementById("myBtn").style.display = "none"; } } function topFunction() { document.body.scrollTop = 0; document.documentElement.scrollTop = 0; }</script><script> function openSearch() { document.getElementById("myOverlay").style.display = "block"; } function closeSearch() { document.getElementById("myOverlay").style.display = "none"; }</script><script> var htp=$(location).attr('protocol'); var urlname=$(location).attr('host'); var its_url=htp+'//'+urlname+'/'; var token="{{ csrf_token() }}"; $( document ).ready(function() { $(".login_sws").click(function(){ var email=$("#email_log").val(); var pass=$("#password_log").val(); if(email=='') { $(".login_error").css("color","red").text("email is required"); return false; } if(pass=='') { $(".login_error").css("color","red").text("pass is required"); return false; } var dataString ='phone='+email+'&password='+pass+'&_token='+token;; $.ajax({ type:'POST', data:dataString, url:"{{route('login')}}", cache:false, success:function(data) { response = JSON.parse(data); if(response.ERROR==0) { $(".login_error").css("color","red").text(response.MSG); setTimeout(function(){ $(".login_error").css("color","red").text("");  location.reload(true); }, 3000); } else if(response.ERROR==1){ $(".login_error").css("color","red").text(response.MSG); } else{ $(".login_error").css("color","red").text(response.MSG); } } }); }); });</script><script>(function() { if($(window).width() <= 768) { $("#account-btn").on("click", function() { $("#mobile-show").slideToggle("2000"); $("#mobile-sort").slideUp("2000"); });	$("#sortt-btn").on("click", function() { $("#mobile-sort").slideToggle("2000"); $("#mobile-show").slideUp("2000"); }); } })();</script><script type="text/javascript"> $(document).ready(function(){ $("#test1").click(function () { if ($(this).is(":checked")) { $("#dvPassport").show(); } else { $("#dvPassport").hide(); } }); }); </script><script> jQuery(function($) { $('.box-title>h5, .categoriesaside>h5').on('click', function() { var $el = $(this), textNode = this.lastChild; $el.find('span').toggleClass('fa-angle-down fa-angle-up'); textNode.nodeValue = ' Gimme ' + ($el.hasClass('showFire') ? 'Fire' : 'Water') $el.toggleClass('showFire'); }); });</script><script>$(document).ready(function(){ var submitIcon = $('.searchbox-icon'); var inputBox = $('.searchbox-input'); var searchBox = $('.searchbox'); var isOpen = false; submitIcon.click(function(){ if(isOpen == false){ searchBox.addClass('searchbox-open'); inputBox.focus(); isOpen = true; } else { searchBox.removeClass('searchbox-open'); inputBox.focusout(); isOpen = false; } }); submitIcon.mouseup(function(){ return false; }); searchBox.mouseup(function(){ return false; }); $(document).mouseup(function(){ if(isOpen == true){ $('.searchbox-icon').css('display','block'); submitIcon.click(); } }); }); function buttonUp(){ var inputVal = $('.searchbox-input').val(); inputVal = $.trim(inputVal).length; if( inputVal !== 0){ $('.searchbox-icon').css('display','none'); } else { $('.searchbox-input').val(''); $('.searchbox-icon').css('display','block'); } }</script>


<script>
    if ($(window).width() > 1024){
    $(function(){
      $('.dropdown-toggle').click(
        function(){
          if ($(this).next().is(':visible')) {
            location.href = $(this).attr('href');;
          }
         });
      });
    };
</script>
