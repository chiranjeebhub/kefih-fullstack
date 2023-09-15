@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="javascript:void(0)"></a>
@endsection 
    <section class="wrap wrap-40 faqsSec">
<div class="container">
    <div class="title">
        <h4>FAQ’s</h4> 
        <!--<div class="row">
            <div class="col-md-5">
                <form method="GET" class="searchproperty" action="#">
                     <div class="form-group"><input class="searchbox-input form-control" type="text" name="search" placeholder="Search Property" autocomplete="off" required="" value="">
                     <button class="searchbox-submit btn" id="submit" type="submit"><i class="fa fa-search"></i></button>
                     </div>
                </form>
            </div>
        </div>-->
    </div>
<div class="row">

    
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">  
   <div class="faq-panel inrpage-faq">
       <div class="accordion" id="accordionExample1">
            <div class="row">
                <?php $i=0;foreach($page_data as $row){?>
                <div class="col-md-6 col-12 pdr50">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?php echo $i;?>">
                          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $i;?>" aria-expanded="true" aria-controls="collapse<?php echo $i;?>">
                            <?php echo $row->fld_faq_question;?>
                          </button>
                        </h2>
                        <div id="collapse<?php echo $i;?>" class="accordion-collapse collapse <?php echo ($i==0)?'show':'';?>" aria-labelledby="heading<?php echo $i;?>" data-bs-parent="#accordionExample1">
                          <div class="accordion-body">
                            <p><?php echo $row->fld_faq_answer;?> </p>
                          </div>
                        </div>
                    </div>
                </div>
                <?php $i++; } ?>
            </div>
       </div>
    </div>
                        
    
</div>    
</div>
</div>     
</section>

    @endsection  
