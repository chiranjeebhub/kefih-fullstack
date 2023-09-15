@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)">Frequently Asked Questions</a>
@endsection       
    
    
    <section class="dashbord-section">
<div class="container">
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
<div class="dashbordlinks">
   
<ul id="mobile-show">
    <?php 
    
      $faq_categories=DB::table('faq_category')
                            ->select('faq_category.id as id','faq_category.name as name')
                            ->get();
     foreach($faq_categories as $faq_category){    
    ?>
    <li class="<?php echo ($faq_category->id==$category_id)?"active":"";?>"><a href="{{route('faq',[base64_encode($faq_category->id)])}}"><i class="fa fa-angle-right"></i>{{$faq_category->name}}</a> </li>
    <?php }?>
</ul>    
</div>    
</div>
    
<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">  
<div class="dashbordtxt">
    <section class="helpfaq-section">
						<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
						<?php $i=0;foreach($page_data as $row){?>
						<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="heading<?php echo $i;?>">
								<h4 class="panel-title">
									<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i;?>" aria-expanded="true" aria-controls="collapse<?php echo $i;?>">
										<?php echo $row->fld_faq_question;?>
									</a>
								</h4>
							</div>
							<div id="collapse<?php echo $i;?>" class="panel-collapse collapse <?php echo ($i==0)?'in':'';?>" role="tabpanel" aria-labelledby="heading<?php echo $i;?>">
								<div class="panel-body">
									<p><?php echo $row->fld_faq_answer;?> </p>
								</div>
							</div>
						</div>
						<?php $i++; } ?>
						
					</div>		   
</section> 
</div>    
</div>    
</div>
</div>     
</section>


@endsection
