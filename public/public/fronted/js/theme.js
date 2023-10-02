(function($){
"use strict"; // Start of use strict

/*
$(function() {
	//Product Inner Zoom
	if($('.inner-zoom').length>0){
		$('.inner-zoom').on('mouseover',function(){
			$(this).find('img').elevateZoom({
				zoomType: "inner",
				lensShape: "square",
				lensSize: 100,
				borderSize:1,
				containLensZoom:true,
				cursor: "crosshair",
				responsive:true,
				easing:true,
				zoomWindowFadeIn: 500,
				zoomWindowFadeOut: 500,
				lensFadeIn: 500,
				lensFadeOut: 500
			});
		})
	}
	
});
*/

//Detail Gallery
function detail_gallery(){
	if($('.detail-gallery').length>0){
		$('.detail-gallery').each(function(){
			var data=$(this).find(".carousel").data();
			$(this).find(".carousel").jCarouselLite({
				btnNext: $(this).find(".gallery-control .next"),
				btnPrev: $(this).find(".gallery-control .prev"),
				speed: 800,
				visible:data.visible,
				vertical:data.vertical,
			});
			//Elevate Zoom
			$(this).find('.mid img').elevateZoom({
				zoomType: "inner",
				lensShape: "square",
				lensSize: 100,
				borderSize:1,
				cursor: "crosshair",
				containLensZoom:true
			});
			$(this).find(".carousel a").on('click',function(event) {
				event.preventDefault();
				$(this).parents('.detail-gallery').find(".carousel a").removeClass('active');
				$(this).addClass('active');
				var z_url =  $(this).find('img').attr("src");
				
				$(this).parents('.detail-gallery').find(".mid img").attr("src", z_url);

				$('.zoomLens').css('background-image','url("'+z_url+'")');
			});
		});
	}
}

//Banner info fixed

//Document Ready
jQuery(document).ready(function(){
	detail_gallery();
});

})(jQuery); // End of use strict