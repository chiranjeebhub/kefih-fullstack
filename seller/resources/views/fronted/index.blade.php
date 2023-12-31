@extends('fronted.layouts.app_new')

<?php
$dt=DB::table('meta_tags')->where('page_id',0)->first();
if($dt){
?>
@section('pageTitle', @$dt->title)
@section('metaTitle', @$dt->title)
@section('metaKeywords', @$dt->keywords)
@section('metadescription', @$dt->description)
<?php } ?>

@section('slider')
    @include('fronted.mod_slider.home_banner_slider')
@endsection
@section('content')
    {!! App\Helpers\HomeProductSliderHelper::getSlider(0) !!}
    @include('fronted.includes.addtocartscript')
@endsection
