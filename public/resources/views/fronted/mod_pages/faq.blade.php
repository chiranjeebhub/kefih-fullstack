<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>@yield('pageTitle')</title>
	<meta name="p:domain_verify" content="2486b52a3cfb0675dd1238560b57e36b"/>
	<meta name="title" content="@yield('metaTitle')"/>
	<meta name="Keywords" content="@yield('metaKeywords')"/>
		<meta name="Description" content="@yield('metadescription')"/>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="shortcut icon" href="{{ asset('public/fronted/images/favicon.png') }}" /><link rel="stylesheet" href="{{ asset('public/fronted/css/bootstrap.min.css') }}" /><link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" /><link rel="stylesheet" type="text/css" href="{{ asset('public/fronted/css/owl.carousel.css') }}" /><link rel="stylesheet" href="{{ asset('public/fronted/css/custom.css') }}" /><link rel="stylesheet" href="{{ asset('public/fronted/megamenu/styles.css') }}" type="text/css" media="all" /><link rel="stylesheet" href="{{ asset('public/fronted/css/animate.css') }}"><link rel="stylesheet" href="{{ asset('public/fronted/css/responsive.css') }}" /><link rel="stylesheet" href="{{ asset('public/fronted/css/rateit.css') }}"><link rel="stylesheet" href="{{ asset('public/fronted/css/jquery.fancybox.min.css') }}"><link rel="stylesheet" href="{{ asset('public/fronted/css/bootstrap-select.min.css') }}" /><link rel="stylesheet" href="{{ asset('public/fronted/css/selectize.bootstrap3.min.css') }}" />

    
    
    <section class="dashbord-section">
<div class="container">
<div class="row">

    
<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">  
<div class="dashbordtxt">
    <section class="helpfaq-section">
	<h2>Frequently Asked Questions</h2>
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
	@include('fronted.includes.foot')
	@include('fronted.includes.addtocartscript')	
	@include('fronted.includes.script')	
	@yield('scripts')
