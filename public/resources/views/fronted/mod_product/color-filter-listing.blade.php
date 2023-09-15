<?php if(@$color_data[0]->id!=''){ foreach($color_data as $color){?>
	<li>
	<label><input type="checkbox" onclick="SearchData();" name="checkbox[]" id="color<?php echo $color->id;?>" class="label-checkbox100">
	<?php echo ucwords($color->name);?> <span title="<?php echo ucwords($color->name);?>" style="background-color:<?php echo $color->color_code;?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></label>
	</li>			
<?php }}else{ ?>
	<li>
		No Color Found.....
	</li>
<?php } ?>