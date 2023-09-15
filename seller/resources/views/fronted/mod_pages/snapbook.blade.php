@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Snapbook</a>
@endsection
<section class="snapbook-section">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="masonry">
              @foreach($snap_books as $description)
                <div class="item">
                    <div class="add-image">
                    <img src="{{ asset('uploads/review/'.$description->uploads) }}" alt=""> 
                    </div>
                </div>
            @endforeach
		
	</div>
			</div>
		</div>
	</div>
</section>
@endsection    
    
 