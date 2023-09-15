@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">{{$page_data->title}}</a>
@endsection     
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<!--    <div class="about-banner">-->
<!--     @if($page_data->banner!='')-->
<!--    <img class="img-responsive" src="{{URL::to('/uploads/pages')}}/{{$page_data->banner}}" alt="{{$page_data->title}}">    -->
<!--    @endif-->
<!--</div> -->
<section class="about-section">
  {!!$page_data->description!!}
</section>   
</div></div></div>
@endsection    
    
 