<?php if(@$size_data[0]->id!=''){ foreach($size_data as $size){?>
	<li>
	<input type="checkbox" onclick="SearchData();" name="checkbox[]" id="size<?php echo $size->id;?>" class="label-checkbox100">
	<a href="#"><?php echo ucwords($size->name);?></a>
	</li>			
<?php }}else{ ?>
	<li>
		No Size Found.....
	</li>
<?php } ?>