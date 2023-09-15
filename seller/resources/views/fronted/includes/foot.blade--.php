    
    <button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fa fa-arrow-up"></i></button>
	<script src="{{ asset('public/fronted/js/jquery.min.js') }}"></script>
	<script src="{{ asset('public/fronted/js/bootstrap.min.js') }}"></script>
	<script>
		$('ul.nav li.dropdown').hover(function() {
			$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
		}, function() {
			$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
		});
	</script>
	<!-- <script type="text/javascript" src="{{ asset('public/fronted/js/jquery-1.12.0.min.js') }}"></script> -->

	<!--carousel -->
	<script src="{{ asset('public/fronted/js/owl.carousel.js') }}"></script>
	<script src="{{ asset('public/fronted/js/selectordie.js') }}"></script>
	<script src="{{ asset('public/fronted/js/custom.js') }}" type="text/javascript"></script>

	<script>
		$('.owl-carousel').owlCarousel({
			loop: true,
			margin: 10,
			autoplay: true,
			slideSpeed: 1000,
			responsiveClass: true,
			responsive: {
				320: {
					items: 1,
					nav: true
				},
				480: {
					items: 2,
					nav: true
				},
				600: {
					items: 3,
					nav: false
				},
				1000: {
					items: 4,
					nav: true,
					loop: false
				}
			}
		})

		$('.recent').owlCarousel({
			loop: true,
			margin: 10,
			autoplay: true,
			responsiveClass: true,
			responsive: {
				320: {
					items: 1,
					nav: true
				},
				480: {
					items: 2,
					nav: true
				},
				600: {
					items: 3,
					nav: false
				},
				1000: {
					items: 5,
					nav: true,
					loop: false
				}
			}
		})
	
		// When the user scrolls down 20px from the top of the document, show the button
		window.onscroll = function() {
			scrollFunction()
		};

		function scrollFunction() {
			if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
				document.getElementById("myBtn").style.display = "block";
			} else {
				document.getElementById("myBtn").style.display = "none";
			}
		}

		// When the user clicks on the button, scroll to the top of the document
		function topFunction() {
			document.body.scrollTop = 0;
			document.documentElement.scrollTop = 0;
		}

            $(function($) {
            $('.navbar .dropdown').hover(function() {
            $(this).find('.dropdown-menu').first().stop(true, true).delay(250).slideDown();
            }, function() {
            $(this).find('.dropdown-menu').first().stop(true, true).delay(100).slideUp();
             
            });
            $('.navbar .dropdown > a').click(function(){
              location.href = this.href;
            });
            });
		function openSearch() {
			document.getElementById("myOverlay").style.display = "block";
		}
		function closeSearch() {
			document.getElementById("myOverlay").style.display = "none";
		}
            $(".rootCat").on('click', function(event){
               console.log(this);
            });
	
		document.querySelector('.hello').scrollIntoView({
			behavior: 'smooth'
		});

		let mainNavLinks = document.querySelectorAll("nav ul li a");
		mainNavLinks.forEach(link => {
			link.addEventListener("click", event => {
				event.preventDefault();
				let target = document.querySelector(event.target.hash);
				target.scrollIntoView({
					behavior: "smooth",
					block: "start"
				});
			});
		});
	</script>
    
    <!-- Modal login-->
<div id="loginmodal" class="modal fade" role="dialog">
    <!-- tab system -->
    <div class="tab-content">
  <div id="homelogin" class="tab-pane fade in active">
     <div class="modal-dialog modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">
          <button type="button" class="close" data-dismiss="modal"><img src="{{ asset('public/fronted/images/cross-out.png') }}"/></button>
          <div class="register clearfix">
<div class="registrform login">
<h6>Login</h6>
<p class="login_error"></p>
          
    <div class="form-group">
    <input type="email" class="form-control" id="email_log" placeholder="Enter Email Id / Mobile Number">
    </div>
        
    <div class="form-group">
    <input type="password" class="form-control" id="password_log" placeholder="Password">
    </div>
        <!-- <p>-->
        <!--<a id="otpclick" data-toggle="tab" href="#menuotp">Login with OTP </a>-->
        <!--</p>-->
   <span  class="registrbtn login_sws"><i class="fa fa-lock"></i> Login</span>
    
    
    <div class="or"><span>Or</span></div>
  <h1 class="fs16 mb20 mt20"> Login/Sign Up With</h1> 
<div class="login-socialmedia">
<a class="fb" href="#"><span><i class="fa fa-facebook"></i></span> facebook</a> <a class="instagram" href="#"><span><i class="fa fa-instagram"></i></span> instagram</a>   <a class="google" href="#"><span><i class="fa fa-google"></i></span> google</a>    
</div> 
    <div class="form-group">
        <span>New User?</span> <a data-toggle="tab" href="#menusignup"> Sign Up</a>
    </div>
</div>    
</div>
      </div>
    </div>
  </div>
  </div>
  <div id="menuotp" class="tab-pane fade">
 <div class="modal-dialog modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">
          <span class="backbtn" data-toggle="tab" data-target="#homelogin"><i class="fa fa-angle-left"></i> </span> <button type="button" class="close" data-dismiss="modal"><img src="{{ asset('public/fronted/images/cross-out.png') }}"/></button>
<div class="register clearfix">
<div class="registrform forgetpassword">
<h6>OTP </h6>    
    <form action="">        
    <div class="form-group">
    <input type="text" class="form-control" id="otp" placeholder="OTP">
        
    </div>        
    <button type="submit" class="registrbtn"> Submit</button>
    </form>  
    
</div>    
</div>  
      </div>
    </div>
  </div>
  </div>
        <div id="menuforgot" class="tab-pane fade">
 <div class="modal-dialog modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">
          <span class="backbtn" data-toggle="tab" data-target="#homelogin"><i class="fa fa-angle-left"></i> </span> <button type="button" class="close" data-dismiss="modal"><img src="{{ asset('public/fronted/images/cross-out.png') }}"/></button>
<div class="register clearfix">
<div class="registrform forgetpassword">
<h6>Forgot Password</h6>    
    <form action="">        
    <div class="form-group">
    <input type="email" class="form-control" id="email" placeholder="Email id / Mobile Number">
    </div>        
   
    <button type="submit" class="registrbtn"> Submit</button>
    </form>  
    
</div>    
</div>  
      </div>
    </div>
  </div>
  </div>
  <div id="menusignup" class="tab-pane fade">
   <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">
        <span class="backbtn" data-toggle="tab" data-target="#homelogin"><i class="fa fa-angle-left"></i> </span> <button type="button" class="close" data-dismiss="modal"><img src="{{ asset('public/fronted/images/cross-out.png') }}"/></button>
<div class="register clearfix">
<div class="registrform">
<h6>Create Account</h6>
    <form action="">
        <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
    <input type="text" class="form-control" id="name" placeholder="Name">
        <i class="fa fa-user"></i>
    </div>
            </div>
        <div class="col-sm-6">
            <div class="form-group">
    <input type="text" class="form-control" id="mobile" placeholder="Mobile Number">
        <i class="fa fa-phone"></i>
    </div> 
            </div>
        </div>
         <div class="row">
        <div class="col-sm-6">
             <div class="form-group">
    <input type="email" class="form-control" id="email" placeholder="Email">
        <i class="fa fa-envelope"></i>
    </div>
             </div>
        <div class="col-sm-6">
             <div class="form-group">
    <input type="password" class="form-control" id="password" placeholder="Password">
        <i class="fa fa-lock"></i>
    </div>
             </div>
        </div>
   <div class="form-group">
    <input type="password" class="form-control" id="password" placeholder="Confirm Password">
        <i class="fa fa-lock"></i>
    </div> 
    <button type="submit" class="registrbtn">Submit</button>
    </form>  
    <div class="or"><span>Or</span></div>
  <h1 class="fs16 mb20 mt20"> Login/Sign Up With</h1> 
<div class="login-socialmedia">
<a class="fb" href="#"><span><i class="fa fa-facebook"></i></span> facebook</a> <a class="instagram" href="#"><span><i class="fa fa-instagram"></i></span> instagram</a>   <a class="google" href="#"><span><i class="fa fa-google"></i></span> google</a>    
</div> 
</div>   
</div>  
      </div>
    </div>
  </div>
  </div>
</div>
</div> 
</body>
</html>







<button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fa fa-arrow-up"></i></button>   
<!--<script src="{{ asset('public/fronted/js3/jquery.min.js') }}"></script>--> 
<script type="text/javascript" src="{{ asset('public/fronted/js/jquery-1.12.0.min.js') }}"></script>
<script src="{{ asset('public/fronted/js/bootstrap.min.js') }}"></script>

<script>
if($(window).width() >= 767){
	$('ul.nav li.dropdown').hover(function() {  $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);}, function() {  $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);});
}
</script>
 
<!--<script type="text/javascript" src="{{ asset('public/fronted/js/jquery-1.12.0.min.js') }}"></script>-->
<!--carousel --> 
<script src="{{ asset('public/fronted/js/owl.carousel.js') }}"></script> 
<script src="{{ asset('public/fronted/js/selectordie.js') }}"></script> 
<script src="{{ asset('public/fronted/js/custom.js') }}" type="text/javascript"></script> 
<script src="{{ asset('public/fronted/js/custom-file-input.js') }}"></script>
<script src="{{ asset('public/fronted/js/jquery.smartmenus.js') }}"></script>
<script src="{{ asset('public/js/jquery.rateit.min.js') }}"></script>
<script src="{{ asset('public/fronted/js/jquery.smartmenus.bootstrap.js') }}"></script> 
	
<script>
$(document).ready(function(){
	$("#searchterm").keyup(function()
	{
	  	$("#display").empty();
	  	var searchbox = $(this).val();
		var token="{{ csrf_token() }}";
	  	var dataString = 'keyword='+ searchbox+'&_token='+token;
			if(searchbox==''){ 
				$("#display").hide(); 
			}else{
			   $.ajax({
				   type: "POST",
				   url: "{{ route('search_filter') }}",
				   data: dataString,
				   cache: false,
				   success: function(html)
				   {
					$("#display").html(html).show();
				   }
			   });
			}
		return false;
	});
	
});
	
    $('.productSlider').owlCarousel({
    loop:true,
    margin:10,
    autoplay:true,
    slideSpeed:1000,
    responsiveClass:true,
    responsive:{
         320:{
            items:1,
            nav:true
        },
        480:{
            items:2,
            nav:true
        },
        600:{
            items:3,
            nav:false
        },
        1000:{
            items:4,
            nav:true,
            loop:false
        }
    }
})
	
 $('.brandSlider').owlCarousel({
    loop:true,
    margin:20,
    autoplay:true,
    slideSpeed:1000,
    responsiveClass:true,
    responsive:{
         320:{
            items:2,
            nav:true
        },
        480:{
            items:4,
            nav:true
        },
        600:{
            items:6,
            nav:false
        },
        1000:{
            items:8,
            nav:true,
            loop:false
        }
    }
})
	
</script>
<script>
    $('.recent').owlCarousel({
    loop:true,
    margin:10,
    autoplay:true,
    responsiveClass:true,
    responsive:{
        320:{
            items:1,
            nav:true
        },
        480:{
            items:2,
            nav:true
        },
        600:{
            items:3,
            nav:false
        },
        1000:{
            items:5,
            nav:true,
            loop:false
        }
    }
})
	
$('.thumbnails_carousel').owlCarousel({
    loop:true,
    margin:10,
    autoplay:true,
    slideSpeed:1000,
    responsiveClass:true,
    responsive:{
         320:{
            items:2,
            nav:true
        },
        480:{
            items:3,
            nav:true
        },
        600:{
            items:4,
            nav:false
        },
        1000:{
            items:6,
            nav:true,
            loop:false
        }
    }
})
	
</script>
    
    <script>
// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    document.getElementById("myBtn").style.display = "block";
  } else {
    document.getElementById("myBtn").style.display = "none";
  }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}
</script>
    
    <script>
function openSearch() {
  //document.getElementById("myOverlay").style.display = "block";
}

function closeSearch() {
  //document.getElementById("myOverlay").style.display = "none";
}
</script>
    
    <script>
   

/*let mainNavLinks = document.querySelectorAll("nav ul li a");

mainNavLinks.forEach(link => {
  link.addEventListener("click", event => {
    event.preventDefault();
    let target = document.querySelector(event.target.hash);
    target.scrollIntoView({
      behavior: "smooth",
      block: "start"
    });
  });
});*/
    
    </script>
	
<!--<script>
	(function(){
	  $("#cart").on("click", function() {
		$(".shopping-cart").slideToggle( "2000");
	  });

	})();
</script>-->
    


<div class="quickview-model">

<!-- line modal -->
<div class="modal fade fadeInUp animated" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    
  <div class="modal-dialog">
	<div class="modal-content">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
		          <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 product_img">
                            <img src="{{ asset('public/fronted/images/p1.jpg') }}" class="img-responsive">
                        </div>

                    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <div class="quick-view-content">
                         <h6>Women Spaghetti </h6>
                        <h4>Product Id: <span>51526</span></h4>
                        
                        <div class="rating">
                            <span class="fa fa-star rate"></span>
                            <span class="fa fa-star rate"></span>
                            <span class="fa fa-star rate"></span>
                            <span class="fa fa-star non-rate"></span>
                            <span class="fa fa-star non-rate"></span> (10 reviews)
                        </div>
                        <h4 class="mt10 mb5 fw600">Description</h4>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        </p>
                        
                        <h3 class="cost"><span class="fa fa-inr"></span> 675.00 
                            <del class="pre-cost"><span class="fa fa-inr"></span> 860.00</del></h3>
                  
                        <div class="space-ten"></div>
                        <div class="btn-ground">
                            
                            <button type="button" class="btn btn-primary"><span class="fa fa-shopping-cart"></span> Add To Cart</button>
                            
                            <button type="button" class="btn btn-primary"><span class="fa fa-heart"></span> Add To Wishlist</button>
                        </div>
                    </div>    
                       
                    </div>
                </div>
            </div>
	</div>
  </div>
</div>    
</div>

<!--<script src="http://aptechbangalore.com/phaukat/public/fronted/js/jquery.min.js"></script>-->
<script>

var htp=$(location).attr('protocol');
var urlname=$(location).attr('host');
var its_url=htp+'//'+urlname+'/phaukat/';
var token="{{ csrf_token() }}";

$( document ).ready(function() {
    
    
	
	$(".login_sws").click(function(){
		var email=$("#email_log").val();
		var pass=$("#password_log").val();
		
		if(email=='')
		{
			$(".login_error").css("color","red").text("email is required");
			return false;
		}
		if(pass=='')
		{
			$(".login_error").css("color","red").text("pass is required");
			return false;
		}
		var dataString ='phone='+email+'&password='+pass+'&_token='+token;;
	
		$.ajax({
			type:'POST',
			data:dataString,
			url:"{{route('login')}}",
			cache:false,
			success:function(data) {
			       response = JSON.parse(data);
				if(response.ERROR==0)
				{
				      	$(".login_error").css("color","red").text(response.MSG);
				       setTimeout(function(){ $(".login_error").css("color","red").text("");  location.reload(true); }, 3000);
				}
				else if(response.ERROR==1){
					$(".login_error").css("color","red").text(response.MSG);
				} else{
				    	$(".login_error").css("color","red").text(response.MSG);
				}
			}
		  });
	});
	
});
</script>

<script>
	jQuery(function($) {
	  $('.box-title>h5').on('click', function() {
		var $el = $(this),
		  textNode = this.lastChild;
		$el.find('span').toggleClass('fa-angle-down fa-angle-up');
		textNode.nodeValue = ' Gimme ' + ($el.hasClass('showFire') ? 'Fire' : 'Water')
		$el.toggleClass('showFire');
	  });
	});
</script>