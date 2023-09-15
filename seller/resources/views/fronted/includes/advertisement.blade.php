<?php 
$featured_cat=DB::table('categories')
->where('featured',1)
->where('status',1)
->where('isdeleted',0)
->limit(10)
->get();
if(sizeof($featured_cat)>0){
?>
<section class="add-section"><div class="container"><div class="slide-heading text-center"><h2><span>featured</span> Categories</h2></div>
        <ul class="list-inline featured-list"> <?php foreach($featured_cat as $cat){ $cat_name = preg_replace('/\s+/', '-',strtolower ($cat->name)); $url=route('cat_wise', [$cat_name,base64_encode($cat->id)]); ?> <li><div class="add-image zoom1"><a href="{{$url}}"> <img src="{{URL::to('/uploads/category/logo')}}/{{$cat->logo}}" alt=""></a></div><div class="text-box"><h4>{{$cat->name}}</h4></div></li>
           <?php }?> </ul></div></section>
<?php 
  }
?>

        @if($advertise_position1)
        <section class="add-section"><div class="container"><div class="slide-heading text-center"><h2>T-Shirts</h2></div><div class="add-image zoom1"><a href="{{$advertise_position1->url}}"> <img src="{{ asset('uploads/advertise/'.$advertise_position1->image) }}" alt=""></a></div></div></section>
        @endif
 @if($advertise_position2 || $advertise_position3)
<section class="add-section"><div class="container"><div class="slide-heading text-center"><h2>Graphic <span>T-Shirts</span></h2></div><div class="row">
        @if($advertise_position2)
        <div class="col-sm-6"><div class="add-image zoom1"><a href="{{$advertise_position2->url}}"> <img src="{{ asset('uploads/advertise/'.$advertise_position2->image) }}" alt=""></a></div></div>
         @endif
          @if($advertise_position3)
        <div class="col-sm-6"><div class="add-image zoom1"><a href="{{$advertise_position3->url}}"> <img src="{{ asset('uploads/advertise/'.$advertise_position3->image) }}" alt=""></a></div></div>
             @endif</div></div></section>
  @endif
  
 @if($advertise_position4 || $advertise_position5)
<section class="add-section"><div class="container"><div class="slide-heading text-center"><h2>Recommended <span>Collections</span></h2></div>                <div class="row">
            @if($advertise_position4)
            <div class="col-xs-12 col-sm-6"><div class="add-image zoom1"><a href="{{$advertise_position4->url}}"> <img src="{{ asset('uploads/advertise/'.$advertise_position4->image) }}" alt=""></a></div></div>
              @endif
               @if($advertise_position5)
            <div class="col-xs-12 col-sm-6"><div class="add-image zoom1"><a href="{{$advertise_position5->url}}"> <img src="{{ asset('uploads/advertise/'.$advertise_position5->image) }}" alt=""></a></div></div>@endif</div></div></section>
 @endif
  @if($advertise_position6)
<section class="add-section"><div class="container"><div class="add-image zoom1"><a href="{{$advertise_position6->url}}"> <img src="{{ asset('uploads/advertise/'.$advertise_position6->image) }}" alt=""></a></div></div></section>
 @endif
  @if($advertise_position7 || $advertise_position8 || $advertise_position9 || $advertise_position10)
<section class="add-section"><div class="container"><div class="slide-heading text-center"><h2>Hot <span>Right Now</span> </h2></div> 
          @if($advertise_position7 || $advertise_position8)<div class="row">
            @if($advertise_position7)<div class="col-xs-12 col-sm-6"><div class="add-image zoom1"><a href="{{$advertise_position7->url}}"> <img src="{{ asset('uploads/advertise/'.$advertise_position7->image) }}" alt=""></a></div></div>
             @endif
             
             @if($advertise_position7)
            <div class="col-xs-12 col-sm-6"><div class="add-image zoom1"><a href="{{$advertise_position8->url}}"> <img src="{{ asset('uploads/advertise/'.$advertise_position8->image) }}" alt=""></a></div></div>@endif</div>
          @endif
           @if($advertise_position9 || $advertise_position10)
        <div class="row">
         @if($advertise_position9)
            <div class="col-xs-12 col-sm-6"><div class="add-image zoom1"><a href="{{$advertise_position9->url}}"> <img src="{{ asset('uploads/advertise/'.$advertise_position9->image) }}" alt=""></a></div></div>
             @endif
            @if($advertise_position10)
            <div class="col-xs-12 col-sm-6"><div class="add-image zoom1"><a href="{{$advertise_position10->url}}"> <img src="{{ asset('uploads/advertise/'.$advertise_position10->image) }}" alt=""></a></div></div>@endif</div>@endif</div></section>
 @endif
 
   @if($advertise_position11)
<section class="add-section"><div class="container"><div class="add-image zoom1"><a href="{{$advertise_position11->url}}"> <img src="{{ asset('uploads/advertise/'.$advertise_position11->image) }}" alt=""></a></div></div></section>
 @endif
