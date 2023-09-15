@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Offer</a>

<?php   
$dt=DB::table('meta_tags')->where('page_id',2)->first();
if($dt){
?>
@section('pageTitle', @$dt->title)
@section('metaTitle', @$dt->title)
@section('metaKeywords', @$dt->keywords)
@section('metadescription', @$dt->description)
<?php } ?>
@endsection
<style>
    .offer-section .add-image {
    margin-bottom: 20px;
}
.add-image {
    width: 100%;
}
.add-image img {
    width: 100%;
}
</style>
<section class="offer-section">
<div class="container">
   

            @if($advertise_position12)
            
            <div class="add-image">
            <img src="{{ asset('uploads/advertise/'.$advertise_position12->image) }}" alt="">    
            </div> 
            @endif
    <div class="slide-heading text-center">
							<h2><span>Redlips</span> Offers</h2>
        <p>Find the best offers across our platforms on this page.</p>
						</div>
						
					<?php 
					 $offers=App\Whatsmore::where('status', 1)->get();
					?>	
    @if(sizeof($offers)>0)		
    <div class="row">
      
                @foreach($offers as $Blog)
                <div class="col-xs-12 col-sm-6">
                	<div class="add-image">
                		<a href="{{$Blog->name}}"> <img src="{{ asset('uploads/blog/banner/'.$Blog->banner_image) }}" alt="">
                        
                        </a>
                	</div>
                </div>
                @endforeach
				
				
			</div>
			@endif
</div>     
</section>   
@endsection    
    
 