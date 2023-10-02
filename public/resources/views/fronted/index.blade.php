@extends('fronted.layouts.app_new')

<?php
$dt=DB::table('meta_tags')->where('page_id',0)->first();
if($dt){
?>
@section('pageTitle', @$dt->title)
@section('metaTitle', ' ')
@section('metaKeywords', ' ')
@section('metadescription', ' ')
<?php } ?>

@section('slider')
    @include('fronted.mod_slider.home_banner_slider')
@endsection
@section('content')
    @include('fronted.includes.advertisement')
    {!! App\Helpers\HomeProductSliderHelper::getSlider(1) !!}
    @include('fronted.includes.addtocartscript')
@endsection
