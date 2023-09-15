@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Blog</a>
@endsection 


<section class="blog-section">
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-8 col-md-9">
    
    
     <?php foreach($blog_data as $row){?>
<div class="blog-item">
<div class="blog-img">
<img src="{{ asset('uploads/blog/banner/'.$row->banner_image) }}" alt="">    
</div>
<div class="blog-text">
<h3 class="fw600">  <?php echo ucwords($row->name);?></h3>   
<ul>
    <li><em>By Phaukat :</em> <i class="fa fa-calendar-check-o"></i> <?php echo date('d M, Y',strtotime($row->created_at));?></li>
</ul>
<p>
<?php echo substr(strip_tags($row->description),0,150);?>...
<a class="readmore" href="{{ route('blog-detail',base64_encode($row->id)) }}">read more </a> 
</div>  
</div>
<?php } ?>


    

    
            <div class="paginatoin-area text-center mt-18">
            {{$blog_data->links()}}
            </div>    
</div> 
    
  
</div>
</div>     
</section>

@endsection  
    

  
  

    
