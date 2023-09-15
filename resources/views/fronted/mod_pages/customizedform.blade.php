@extends('fronted.layouts.app_new')
@section('content')
@section('breadcrum') 
<a href="{{ route('index')}}">Home</a><i class="fa fa-long-arrow-right"></i>
<a href="{{ route('customized')}}">Customized</a>
<a href="javascript:void(0)">Form</a>
@endsection
<section class="about-section">
<div class="container-fluid">
<div class="row">
	<div class="col-md-10 col-md-offset-1 col-xs-12">
		<div class="customizeprdt">
			<div class="slide-heading text-center"> 
				<h6 class="fs24 fw700">Get your <span>customised</span> {{$product_info->name}}</h6> 
			</div>
			<div class="customizedlogoboxMain"><span>AT</span> <div class="customizedlogobox"><img src="{{ asset('public/fronted/images/customized-logo.png') }}"></div></div>
			<div class="row">

			<div class="col-xs-12 col-sm-6 col-md-6">
				<div class="contactinfo text-center">

					<div class="mainbox media">
						<h2>Choose Form</h2> 
						<div class="customisedSize">
							<ul>
								<li><a href="#" class="sizeClass">XS</a></li>
								<li><a href="#" class="sizeClass">S</a></li>
								<li><a href="#" class="sizeClass">M</a></li>
								<li><a href="#" class="sizeClass">L</a></li>
								<li><a href="#" class="sizeClass">XL</a></li>
								<li><a href="#" class="sizeClass">XXL</a></li>
								<li><a href="#" class="sizeClass">XXXL</a></li>
							</ul>
						</div>    
					</div>   
					<div class="mainbox media">
						<h2>Get it Designed</h2> 
						<div class="customisedSize">
							<img src="{{ asset('public/fronted/images/degined-icon.png') }}">
						</div>    
					</div>   
					<div class="mainbox media">
						
						<div class="customisedSize">
							<h1>Your Customized <br/><span>{{$product_info->name}}</span><br/> Are raedy</h1>
						</div>    
					</div> 

				</div>    
			</div>
			<div class="col-xs-12 col-sm-6 col-md-5 col-md-offset-1">
				<div class="contactform">
				
  
				
					<form role="form" class="form-element" action="{{ route('customerQyeryforCustomized',$product_id)}}" method="post" enctype="multipart/form-data">
					      @csrf
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label>Your Name <span>*</span></label>
									
                                          
									<input type="text" class="form-control" id="name" placeholder="Name" name="name">
                                    @if($errors->has('name'))
                                    <span class="help-block">
                                    <p style="color:red">{{ $errors->first('name') }}</p>
                                    </span>
                                    @endif
								</div>
							</div>
						
						<div class="col-sm-12">
							<div class="form-group">
								<label>Your E-mail <span>*</span></label>
								<input type="email" class="form-control" id="email" placeholder="E-mail" name="email">
                                    @if($errors->has('email'))
                                    <span class="help-block">
                                    <p style="color:red">{{ $errors->first('email') }}</p>
                                    </span>
                                    @endif
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label>Your Mobile <span>*</span></label>
								<input type="text" class="form-control" id=" " placeholder="Mobile No." name="mobile">
                                     @if($errors->has('mobile'))
                                        <span style="color:red"> {{ $errors->first('mobile') }}</span>
                                        @endif
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label>Attach Reference</label>
								<input type="file" id=" "  name="image">
                                        @if($errors->has('image'))
                                        <span style="color:red"> {{ $errors->first('image') }}</span>
                                        @endif
							</div>	
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label>Message</label>
								<textarea class="form-control" name="message" data-required-error="Please fill the field" placeholder="Message"></textarea>
                                        @if($errors->has('message'))
                                        <span style="color:red"> {{ $errors->first('message') }}</span>
                                        @endif
							</div>
						</div>
						</div>
						<div class="row">
							<div class="col-sm-12 col-sm-pull-0 col-md-12 col-md-pull-0"> 
								<input type="submit" name="subscription" value="Send" class="registrbtn">
							</div>
						</div>


					</form>      
				</div>    
			</div>    

		</div>
		</div>
	</div>
</div>
</div>     
</section>  



 @endsection   
 