
<?php if(@$Brands[0]->name!=''){?>
<section class="product-slider-section">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="whitebox">
						<div class="slide-heading">
        <h6 class="fs20 fw600">Brands
       
        </h6>
						</div>
						
							<ul class="owl-carousel brandSlider owl-theme">
								@foreach($Brands as $brand)
								<li class="item wow fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">

										<a href="{{route('brands_product',(preg_replace('/\s+/', '-', strtolower($brand->name))))}}">
											<div class="brandimg">
												<img class=" " src="{{URL::to('/uploads/brand/logo/')}}/{{$brand->logo}}">
											</div>
										</a>


									<!--<div class="product-content text-left">
										<h3 class="title"><a href="{{route('brands_product',(preg_replace('/\s+/', '-', strtolower($brand->name))))}}">{{strtoupper($brand->name)}}</a></h3>

									</div>-->

								</li>
								@endforeach

							</ul>

					</div>

				</div>

			</div>
		</div>

	</section>
<?php } ?>
	
	