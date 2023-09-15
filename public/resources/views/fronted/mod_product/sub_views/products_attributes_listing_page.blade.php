<div class="sizemainDiv">	
<?php $colors=App\Products::getProductsAttributes2('Colors',0,$product->id);
	$sizes=App\Products::getProductsAttributes2('Sizes',0,$product->id);
	?>
    @if (count($sizes)>0)
        <div class="size-hover">
            <h4>Sizes:
                @foreach ($sizes as $size)
                    <span class="product-sizeInventoryPresent">{!!App\Products::getAttrName('Sizes',$size['size_id'])!!} </span>
                @endforeach	
            </h4>
        </div>
    @endif
    
    @if (count($colors)>0)
        <div class="color-hover">
            <h4>Colors:
                @foreach ($colors as $color)
                <?php $color_data=App\Products::getcolorNameAndCode('Colors',$color['color_id']) ?>
                <span class=""   style="background-color:{{$color_data->color_code}};" title="{{$color_data->name}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <!--<span class="product-sizeInventoryPresent">{!!App\Products::getAttrName('Colors',$color['color_id'])!!} </span>-->
                @endforeach	
            </h4>
        </div>
    @endif
</div>

		
			
		

			