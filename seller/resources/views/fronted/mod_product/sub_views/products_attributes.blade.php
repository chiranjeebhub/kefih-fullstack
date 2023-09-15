	<?php 
	 $prd_data=App\Products::productDetails($prd_detail->id);

       $colors=App\Products::getProductsAttributes2('Colors',0,$prd_detail->id);
	 $sizes=App\Products::getProductsAttributes2('Sizes',0,$prd_detail->id);
	 
	?>
         <!--configurable product starts from here-->
       @if($prd_data->product_type==3)
       
         @if (count($colors)>0)
				<div class="size">
					<div class="row">
					<span class="col-xs-12 col-sm-3 col-md-2 col-lg-2 sizes">Colors </span>
					 <div id="colors_html" class="col-xs-12 col-sm-9 col-md-10 col-lg-10">
					 @foreach ($colors as $color)
					 <?php 

$color_data=App\Products::getcolorNameAndCode('Colors',$color['color_id']);

$colorwise_images=DB::table('product_configuration_images')
->where('product_id',$prd_detail->id)
->where('color_id',$color['color_id'])
->first();
?>
					 <span class="colorClass {{($decodeInput[2]==$color['color_id'])?'active':''}}"
					 color_id="{{$color['color_id']}}"
					 prd_id='{{$prd_detail->id}}'
					 prd_type='{{$prd_data->product_type}}'
					 title="{{$color_data->name}}"
					 style="background-color:<?php echo $color_data->color_code;?>"
					 >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					  </span>
					<!--<span title="small">-->
					<!--	<a href="javascript:void(0)" class="badge badge-primary colorClass" color_id="{{$color['color_id']}}"  prd_id='{{$prd_detail->id}}'>{{$color_data->name}}</a>-->
					<!--</span>-->
					@endforeach	
					</div>
					</div>
				</div>
		@endif
       
       @if (count($sizes)>0)
				<div class="size">
					<div class="row">
					<span class="col-xs-12 col-sm-3 col-md-2 col-lg-2 sizes">Sizes: </span>
					 <div id="sizes_html" class="col-xs-12 col-sm-9 col-md-10 col-lg-10">
					 @foreach ($sizes as $size)
					<span title="small">
						<a href="javascript:void(0)" class="badge badge-danger  sizeClass"  size_id="{{$size['size_id']}}" prd_id='{{$prd_detail->id}}'>{!!App\Products::getAttrName('Sizes',$size['size_id'])!!}</a>  
					</span>
					@endforeach	
					</div>
					</div>
				</div>
				<?php 
				
			
	    if($prd_detail->product_size_chart){?>	
	       <div class="sizechart"><a type="button" value="button"  class="showSizechart" prd_id="{{$prd_detail->id}}"  href="javascript:void(0)">Size Chart
	       <img src="{{ asset('public/fronted/images/tape-red.png') }}" alt="sizeChart"/> </a></div>
	    <?php } ?>
        @endif
        
       
       
       
       @endif
        <!--configurable product ends here-->
        
        
        
        
          <!--unisex products ends here-->
         @if($prd_data->product_type==2)
       
       
        @if (count($colors)>0)
				<div class="size">
					<div class="row">
					<span class="col-xs-12 col-sm-3 col-md-2 col-lg-2 sizes">Colors </span>
					 <div id="colors_html" class="col-xs-12 col-sm-9 col-md-10 col-lg-10">
					 @foreach ($colors as $color)
					 <?php 

$color_data=App\Products::getcolorNameAndCode('Colors',$color['color_id']);

$colorwise_images=DB::table('product_configuration_images')
->where('product_id',$prd_detail->id)
->where('color_id',$color['color_id'])
->first();
?>
					 <span class="colorClass"
					 color_id="{{$color['color_id']}}"
					 prd_id='{{$prd_detail->id}}'
					 prd_type='{{$prd_data->product_type}}'
					 title="{{$color_data->name}}"
					 style="background-color:<?php echo $color_data->color_code;?>"
					 >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					  </span>
					<!--<span title="small">-->
					<!--	<a href="javascript:void(0)" class="badge badge-primary colorClass" color_id="{{$color['color_id']}}"  prd_id='{{$prd_detail->id}}'>{{$color_data->name}}</a>-->
					<!--</span>-->
					@endforeach	
					</div>
					</div>
				</div>
		@endif
		
       @if (count($sizes)>0)
         <!--men sizes start here-->
				<div class="size">
					<div class="row">
					<span class="col-xs-12 col-sm-3 col-md-2 col-lg-2 sizes">Men Sizes : </span>
					 <div id="sizes_html" class="col-xs-12 col-sm-9 col-md-10 col-lg-10">
					 @foreach ($sizes as $size)
					 <?php if($size['unisex_type']==1){?>
					<span title="small">
						<a href="javascript:void(0)" class="badge badge-danger  sizeClass"  size_id="{{$size['size_id']}}" prd_id='{{$prd_detail->id}}'>{!!App\Products::getAttrName('Sizes',$size['size_id'])!!}</a>  
					</span>
					<?php } ?>
					@endforeach	
					</div>
					</div>
				</div>
				  <!--men sizes end here-->
				  
				   <!--women sizes start here-->
				<div class="size">
					<div class="row">
					<span class="col-xs-12 col-sm-3 col-md-2 col-lg-2 sizes">Women Sizes : </span>
					 <div id="wsizes_html" class="col-xs-12 col-sm-9 col-md-10 col-lg-10">
					 @foreach ($sizes as $size)
					 <?php if($size['unisex_type']==2){?>
					<span title="small">
						<a href="javascript:void(0)" class="badge badge-danger  wsizeClass"  w_size_id="{{$size['size_id']}}" prd_id='{{$prd_detail->id}}'>{!!App\Products::getAttrName('Sizes',$size['size_id'])!!}</a>  
					</span>
					<?php } ?>
					@endforeach	
					</div>
					</div>
				</div>
				<?php 
				
			  if($prd_detail->product_size_chart){?>		
	       <!--<a type="button" value="button"  class="showSizechart" prd_id="{{$prd_detail->id}}"  href="javascript:void(0)">Size Chart</a>-->
	  	<div class="sizechart"><a type="button" value="button"  class="showSizechart" prd_id="{{$prd_detail->id}}"  href="javascript:void(0)">Size Chart
	  	<img src="{{ asset('public/fronted/images/tape-red.png') }}" alt="sizechart"/> </a></div>

	    <?php } ?>
	    
				
					
					
				  <!--women sizes end here-->
        @endif
        
        
       
       
       @endif
       <!--unisex products ends here---->
		
		 
		
			
		

			