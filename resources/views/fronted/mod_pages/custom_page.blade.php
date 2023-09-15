@extends('fronted.layouts.app_new')
@section('content')
@section('pageTitle',$page_data->title)

<?php   
$dt=DB::table('meta_tags')->where('page_id',1)->first();
if($dt){
?>
@section('pageTitle', @$dt->title)
@section('metaTitle', @$dt->title)
@section('metaKeywords', @$dt->keywords)
@section('metadescription', @$dt->description)
<?php } ?>
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)"> {!!$page_data->title!!}</a>
@endsection 
<style>
    .offer-dec h6 {
    font-size: 18px;
    margin-bottom: 12px;
    color: #000;
    font-weight: 600;
}
   .list-points {
    margin-bottom: 30px;
    padding-left: 15px;
} 
.list-points li {
    margin-bottom: 10px;
    line-height: 25px;
    font-size: 14px;
    list-style: disc;
    color: #565656;
}

</style>
<section class="wrap-40">
<div class="container">
    <div class="row">
				<div class="col-xs-12">
                    <div class="txt_dec cutompageheight">
                        @if($page_data->id == 1 || $page_data->id == 4 ||  $page_data->id == 3 ||  $page_data->id == 14)
                          <h4> {!!$page_data->title!!}</h4>
                            <?php if(!empty($page_data->banner)){ ?>
                              <img src="{{ asset('uploads/pages/'.$page_data->banner) }}" alt="" class="about-img-center img-responsive"/>
                            <?php } ?>
                        @elseif($page_data->id == 5)
                         <h4 class="title-bg mb30 fs20 fw400"> {!!$page_data->title!!}</h4>
                        @else
                        <h6> {!!$page_data->title!!}</h4>
                         @endif


                        <div class="offer-dec">
              {!!$page_data->description!!}

              </div>
                    </div>
        </div>
			</div>
</div>     
</section>
    
@endsection    
    
 