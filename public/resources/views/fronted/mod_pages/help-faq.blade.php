@extends('fronted.layouts.app_new')
@section('content')     
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">{{$page_data->title}}</a>
@endsection    
<section class="helpfaq-section">
<div class="container">
			<div class="row">
				<div class="col-md-12">
				    <div class="about-banner">
     @if($page_data->banner!='')
    <img class="img-responsive" src="{{URL::to('/uploads/pages')}}/{{$page_data->banner}}" alt="{{$page_data->title}}">    
    @endif
</div> 
					<div class="section-title text-center wow zoomIn mb40">
						<h6 class="fs30 fw700 ftu mb20">{{$page_data->title}}</h6>
                        
					</div>
				</div>
			</div>
			<div class="row">				
				{!!$page_data->description!!}
			</div><!--- END ROW -->			
		</div>     
</section>        
@endsection
