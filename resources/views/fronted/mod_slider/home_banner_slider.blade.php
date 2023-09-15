<?php
$sliderData = array();
if(isset($_COOKIE['sitecity'])){
    $sitecityid = $_COOKIE['sitecity'];
    $sliderData= DB::table('sliders') ->select('sliders.*', 'sliders.image','sliders.description','sliders.url')/*->whereRaw("find_in_set($sitecityid,city_ids)")*/->where('status',1) ->orderby('id', 'desc')->get();     

}else{   
    
    $sliderData= DB::table('sliders') ->select('sliders.*', 'sliders.image','sliders.description','sliders.url')->where('status',1) ->orderby('id', 'desc')->limit(30)->get();     
    
}
 
 /*
$sliderData= DB::table('sliders') ->select('sliders.*', 'sliders.image','sliders.description','sliders.url')->where('status',1) ->orderby('id', 'desc')->get();     
 */                         

?>



<section class="slider-wrap">
	 <div id="service-carousel" class="owl-carousel">
         <?php $i=0; foreach($sliderData as $slider) {
            
            ?>
        <div class="item <?php echo ($i==0)?'active':''?>">
		 <img src="{{url('uploads/slider')}}/{{$slider->image}}"/>
		<div class="slider-overlay"></div>
		 <div class="slide-text">
        <div class="container">
            <div class="slide-text-dec">
                <div class="Cuptext">
                    {!!$slider->description!!}
                    @if($slider->url!='') @endif
                    
                </div>
                <div class="text-center">
                    @if($slider->url!='')
                    <a class="btn btn-outline-light" href="{{$slider->url}}">Shop Now</a>@endif</div>
                
            </div>
            </div>
        </div>
		</div><?php $i++; }?>
         
		</div>
    </section>

    @if(!empty($offers))
<section class="wrap bg-black wrappd20 pdt20">
    <div class="container">
        <div class="row text-center">
            @foreach($offers as $offer)
             <div class="col-6 col-sm-3 col-md-3 col-lg-3">
                    <div class="offer-box">
                        <a href="{{route('offer-zone-products',base64_encode($offer->id))}}">{{$offer->offer_name}}</a>
                    </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif 