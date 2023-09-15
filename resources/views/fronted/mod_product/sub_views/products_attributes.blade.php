	<?php 
	 $prd_data=App\Products::productDetails($prd_detail->id);
        
       $colors=App\Products::getProductsAttributes2('Colors',0,$prd_detail->id);
       file_put_contents('color.txt',json_encode($colors));
	 $sizes=App\Products::getProductsAttributes2('Sizes',0,$prd_detail->id);
	 
	?>
         <!--configurable product starts from here-->
       @if($prd_data->product_type==3)
       
         @if (count($colors)>0)
         <?php 
                    $first_data=App\Products::getcolorNameAndCode('Colors',$colors[0]['color_id']);
         ?>
				<div class="size">
					<div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <p class="sizes">Color 
                                <?php /* : <span id="color_name"><?php echo $first_data->name;?></span> */ ?>
                        </p>
					
					 <div id="colors_html">
				
                     <div class="colormainattribute">
                     <?php /* 
                            <div class="colorleftattribute"
                            id="color_box"
                            style="background-color:<?php echo $first_data->color_code;?>"
                            >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </div>
                            */ ?>

                        <div class="colorrightattribute">
                            <div class="coloroptionbox">
                                <ul>
                                    	 @foreach ($colors as $key=>$color)
                                            <?php 
                                            
                                            $color_data=App\Products::getcolorNameAndCode('Colors',$color['color_id']);
                                            
                                            $colorwise_images=DB::table('product_configuration_images')
                                            ->where('product_id',$prd_detail->id)
                                            ->where('color_id',$color['color_id'])
                                            ->first();
                                            ?>
                                    <li>
                                    <label class="color1 {{(count($colors)>1)?'colorClass':''}}"
                                        style="background-color:<?php echo $color_data->color_code;?>"
                                        color_id="{{$color['color_id']}}"
                                        color_name="{{$color_data['name']}}"
                                        color_code="{{$color_data['color_code']}}"
                                        prd_id='{{$prd_detail->id}}'
                                        prd_type='{{$prd_data->product_type}}'
                                        title="{{$color_data->name}}"
                                    >
                                    @if($key==0)
                                    <img src="{{ asset('public/fronted/images/checkicon.png') }}" >
                                    @endif 
                                    </label>
                                    </li>
                                  
                                    	@endforeach	
                                   
                                </ul>
                            </div>
                        </div>
                     </div>
				
					</div>
					</div>
                        </div>
				</div>
		@endif
       
       @if (count($sizes)>0)
				<div class="size sizeboxes">
					<div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <p class="sizes">Sizes </p>
                         
                         <div class="colormainattribute">
                                <?php 
                                $first_size_data=App\Products::getSizeName($sizes[0]['size_id']);
                                ?>
                    <?php /*
					<div class="colorleftattribute sizeClass" title=" ">
                        <a href="javascript:void(0)" class="" id="size_name">{{$first_size_data}}</a>                        
                        <!--@foreach ($sizes as $size)
						<a href="javascript:void(0)" class="badge badge-danger  sizeClass"  >{!!App\Products::getAttrName('Sizes',$size['size_id'])!!}</a>
                        @endforeach	-->
					</div>
                    */ ?>
                             
                        <div class="colorrightattribute">
                            
                            <div class="coloroptionbox sizeoptionbox" >
                                <ul id="sizes_html">
                                    @foreach ($sizes as $size)
                                     <?php $size_data=App\Products::getSizeName($size['size_id']);?>
                                        <li>
                                        <label class="size1 sizeClass" size_id="{{$size['size_id']}}" prd_id='{{$prd_detail->id}}' size_name="{{$size_data}}">
                                        {{$size_data}}</label>
                                        </li>
                                  @endforeach
                                </ul>
                            </div>
                        </div>
                             
                         </div>
					
					</div>
                    </div>
				</div>
				<?php 
				
			
	    if($prd_detail->product_size_chart){?>	
	       <!--<div class="sizechart"><a type="button" value="button"  class="showSizechart" prd_id="{{$prd_detail->id}}"  href="javascript:void(0)">Size Chart
	       <img src="{{ asset('public/fronted/images/tape-red.png') }}" alt="sizeChart"/> </a></div>-->
	    <?php } ?>
        @endif
        
       
       
       
       @endif
        <!--configurable product ends here-->
        
        
        
        
      
		
		 
		
			
		

			