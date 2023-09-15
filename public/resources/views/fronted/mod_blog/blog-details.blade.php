@extends('fronted.layouts.app_new')
@section('content')


@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Blog Details</a>
@endsection    
   

<section class="blog-section">
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-8 col-md-9 clearfix">
<div class="blog-details">
<img class="img-responsive" src="{{ asset('uploads/blog/banner/'.$blog_detail->banner_image) }}" alt="">  
    <h3 class="fw600"><?php echo ucwords($blog_detail->name);?></h3>  
<ul>
<li><em>By Phaukat :</em>  <i class="fa fa-calendar-check-o"></i>  <?php echo date('d M, Y',strtotime($blog_detail->created_at));?></li>    
</ul>
  
<p><?php echo $blog_detail->description;?></p>
</div> 
    
    
</div> 
    
    

</div>
</div>     
</section>


@endsection  
    

  
  

    
