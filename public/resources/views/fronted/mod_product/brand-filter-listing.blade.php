<?php if(@$brand_data[0]->id!=''){ foreach($brand_data as $brand){?>
	<li>
	<input type="checkbox" onclick="SearchData();" name="checkbox[]" id="brand<?php echo $brand->id;?>" class="label-checkbox100">
	<a href="#"><?php echo ucwords($brand->name);?></a>
	</li>			
<?php }}else{ ?>
	<li>
		No Brand Found.....
	</li>
<?php } ?>