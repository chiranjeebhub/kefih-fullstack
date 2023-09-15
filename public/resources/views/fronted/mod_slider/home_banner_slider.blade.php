

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

<div class="slider"><div id="myCarousel" class="carousel slide carousel-fade" data-ride="carousel"><ol class="carousel-indicators">
    
     <?php 
     
     $i=0; foreach($sliderData as $slider) {?><li data-target="#myCarousel" data-slide-to="{{$i}}" class=" <?php echo ($i==0)?'active':''?>"></li><?php $i++; }?> </ol><div class="carousel-inner"> <?php $i=0; foreach($sliderData as $slider) {?><div class="item  <?php echo ($i==0)?'active':''?>">@if($slider->url!='')<a class="" href="{{$slider->url}}">@endif<div class="container"><div class="row"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> <div class="banner-text fadeInRight animated"><h6>{!!$slider->description!!}</h6> @if($slider->url!='') @endif</div></div></div></div><img src="{{url('uploads/slider')}}/{{$slider->image}}" alt="">@if($slider->url!='')</a>@endif</div><?php $i++; }?></div><a class="left carousel-control" href="#myCarousel" data-slide="prev"><span class="fa fa-angle-left"></span><span class="sr-only">Previous</span></a><a class="right carousel-control" href="#myCarousel" data-slide="next"><span class="fa fa-angle-right"></span><span class="sr-only">Next</span></a></div></div> 