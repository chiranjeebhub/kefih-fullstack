<?php
/*
$featured_cat=DB::table('categories')
->where('featured',1)
->where('status',1)
->where('isdeleted',0)
->limit(0)
->get();
if(sizeof($featured_cat)>0){
?>
?>
<section class="add-section">
    <div class="container-fluid">
        <div class="featured-list">
            <div class="slide-heading">
                <h2>Featured Categories <span>Choose what you looking for</span></h2>
            </div>
            <div class="owl-carousel owl-carousel-featured text-center"> <?php foreach($featured_cat as $cat){ $cat_name = preg_replace('/\s+/', '-',strtolower ($cat->name)); $url=route('cat_wise', [$cat_name,base64_encode($cat->id)]); ?>
                <div class="item">
                    <div class="add-image zoom1"><a href="{{ $url }}"> <img
                                src="{{ URL::to('/uploads/category/logo') }}/{{ $cat->logo }}" alt=""></a>
                    </div>
                    <div class="text-box">
                        <h4>{{ $cat->name }}</h4>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
    </div>
</section>
<?php
  }
  */

?>


{{--
@if ($advertise_position1)
<section class="add-section mt0sectn"><div class="container-fluid"><!--<div class="slide-heading text-center"><h2>T-Shirts</h2></div>--><div class="add-image zoom1"><a href="{{$advertise_position1->url}}"> <img src="{{ asset('uploads/advertise/'.$advertise_position1->image) }}" alt=""></a></div></div></section>
@endif





 @if ($advertise_position4 || $advertise_position5)
<section class="add-section mt0sectn"><div class="container-fluid"><div class="slide-heading text-center"><h2>cosmetics <span>shop</span></h2></div>                <div class="row">
            @if ($advertise_position4)
            <div class="col-xs-12 col-sm-6"><div class="add-image zoom1"><a href="{{$advertise_position4->url}}"> <img src="{{ asset('uploads/advertise/'.$advertise_position4->image) }}" alt=""></a></div></div>
              @endif
               @if ($advertise_position5)
            <div class="col-xs-12 col-sm-6"><div class="add-image zoom1"><a href="{{$advertise_position5->url}}"> <img src="{{ asset('uploads/advertise/'.$advertise_position5->image) }}" alt=""></a></div></div>@endif</div></div>
</section>
 @endif

 --}}
<!--@if ($advertise_position7 || $advertise_position8 || $advertise_position9 || $advertise_position10)
<section class="add-section mt0sectn">
  <div class="container">
          @if ($advertise_position7 || $advertise_position8)<div class="row">
            @if ($advertise_position7)
<div class="col-xs-12 col-sm-12">
              <div class="add-image zoom1">
                <a href="{{ $advertise_position7->url }}">
                  <img src="{{ asset('uploads/advertise/' . $advertise_position7->image) }}" alt="">
                  <div class="advertise-overlay-txt">
                                    <div class="advertise-dec">
                                    <h2>TOYS <sub>&amp;</sub><br>GAMES</h2>
                                        <h6>Hurry up before offer will end</h6>

                                        <a href="{{ $advertise_position7->url }}" class="btn btn-light">Shop  NOW</a>

                                    </div>
                                    </div>
                </div></div>
@endif-->
<!--
             @if (@$advertise_position7)
<div class="col-xs-12 col-sm-6"><div class="add-image zoom1"><a href="{{ @$advertise_position8->url }}"> <img src="{{ asset('uploads/advertise/' . @$advertise_position8->image) }}" alt=""></a></div></div>
@endif--></div>
@endif
<!--@if (@$advertise_position9 || @$advertise_position10)
        <div class="row">
         @if (@$advertise_position9)
<div class="col-xs-12 col-sm-6"><div class="add-image zoom1"><a href="{{ @$advertise_position9->url }}"> <img src="{{ asset('uploads/advertise/' . @$advertise_position9->image) }}" alt=""></a></div></div>
@endif
            @if ($advertise_position10)
<div class="col-xs-12 col-sm-6"><div class="add-image zoom1"><a href="{{ @$advertise_position10->url }}"> <img src="{{ asset('uploads/advertise/' . @$advertise_position10->image) }}" alt=""></a></div></div>
@endif
</div>
@endif
</div></section>
 @endif-->

<!--   @if (@$advertise_position11)
<section class="add-section"><div class="container"><div class="add-image zoom1"><a href="{{ @$advertise_position11->url }}"> <img src="{{ asset('uploads/advertise/' . @$advertise_position11->image) }}" alt=""></a></div></div></section>
@endif-->


@if ($advertise_position1)
    <section class="wrap Ethnic-wrap">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-5 col-lg-5">
                    <div class="Ethnic-box">
                        <div class="title">
                            <h2>{{ $advertise_position1->short_text }}</h2>
                        </div>
                        <div class="text-center">
                            {!! $advertise_position1->description !!}
                            <a href="{{ $advertise_position1->url }}" class="btn btn-warning btn-block btn-lg">Shop
                                Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-7 col-lg-7">
                    <div class="Ethnic-img">
                        <img src="{{ asset('uploads/advertise/' . $advertise_position1->image) }}">
                    </div>
                </div>

            </div>
        </div>
    </section>
@endif

{!! App\Helpers\HomeProductSliderHelper::getSlider(0) !!}

<section class="bg-black advetiseslider">
    <div>
        <div class="owl-carousel owl-carousel-advertise">

            @if ($advertise_position2)
                @foreach ($advertise_position2 as $ads)
                    <div class="item">
                        <div class="advertise-img">
                            <img src="{{ asset('uploads/advertise/' . $ads->image) }}" alt="" />
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </div>
</section>




@if (!empty($advertise_position3))
    <section class="wrap Ethnic-wrap">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-5 col-lg-5">
                    <div class="Ethnic-box">
                        <div class="title">
                            <h2>{{ $advertise_position3->short_text }}</h2>
                        </div>
                        <div class="text-center">
                            {!! $advertise_position3->description !!}
                            <a href="{{ $advertise_position3->url }}" class="btn btn-warning btn-block btn-lg">Shop
                                Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-7 col-lg-7">
                    <div class="Ethnic-img">
                        <img src="{{ asset('uploads/advertise/' . $advertise_position3->image) }}">
                    </div>
                </div>

            </div>
        </div>
    </section>
@endif

{!! App\Helpers\HomeProductSliderHelper::getSlider(1) !!}

@if (!empty($advertise_position4))
    <section class="wrap Ethnic-wrap">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-5 col-lg-5">
                    <div class="Ethnic-box">
                        <div class="title">
                            <h2>{{ $advertise_position4->short_text }}</h2>
                        </div>
                        <div class="text-center">
                            {!! $advertise_position4->description !!}

                            <a href="{{ $advertise_position4->url }}" class="btn btn-warning btn-block btn-lg">Shop
                                Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-7 col-lg-7">
                    <div class="Ethnic-img">
                        <img src="{{ asset('uploads/advertise/' . $advertise_position4->image) }}">
                    </div>
                </div>

            </div>
        </div>
    </section>
@endif


{!! App\Helpers\HomeProductSliderHelper::getSlider(2) !!}

@if ($advertise_position5)
    <section class="add-section mt0sectn">
        <a href="{{ $advertise_position5->url }}"><img
                src="{{ asset('uploads/advertise/' . $advertise_position5->image) }}" alt=""></a>
    </section>
@endif

@if (!empty($advertise_position6))
    <section class="wrap Ethnic-wrap">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-5 col-lg-5">
                    <div class="Ethnic-box">
                        <div class="title">
                            <h2>{{ $advertise_position6->short_text }}</h2>
                        </div>
                        <div class="text-center">
                            {!! $advertise_position6->description !!}
                            <a href="{{ $advertise_position6->url }}" class="btn btn-warning btn-block btn-lg">Shop
                                Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-7 col-lg-7">
                    <div class="Ethnic-img">
                        <img src="{{ asset('uploads/advertise/' . $advertise_position6->image) }}">
                    </div>
                </div>

            </div>
        </div>
    </section>
@endif
{!! App\Helpers\HomeProductSliderHelper::getSlider(3) !!}
