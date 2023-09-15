<?php if(sizeof($products)>0){?>
<div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 sidebar__inner">
    <div class="">
        <div class="filter mt20">
            <h6><i class="fa fa-filter"></i> Filter by <div class="clearall">         
                    <a href="#">Clear all</a>
            </div> <span id="account-btn"><i class="fa fa-navicon"></i></span></h6>
        </div>
        <div class="bgwhite" id="mobile-show">
			<?php 
				$brand_listing="";
				$parameters= Request::segment(1); 
				/*$cat_data = App\Category::select('categories.*')
									->where('categories.parent_id','!=',0)
									->where('categories.parent_id','=',$cat_id)
									->get()->toArray();	
									
				$brand_data = App\Brands::select('brands.*')
									->where('brands.isdeleted','=',0)
									->get()->toArray();	
									
				$color_data = App\Colors::select('colors.*')
									->where('colors.isdeleted','=',0)
									->get()->toArray();
				$size_data = App\Sizes::select('sizes.*')
									->where('sizes.isdeleted','=',0)
									->get()->toArray();  
				*/	
									
				$cat_data = App\Category::select('categories.*')
									->where('categories.parent_id','=',$cat_id)
									->get()->toArray();	
				
				$brand_data = App\Products::select('brands.*')
											->join('brands', 'products.product_brand', '=', 'brands.id')
											->join('product_categories', 'products.id', '=', 'product_categories.product_id')
											->where('product_categories.cat_id','=',$cat_id)
											->groupBy('brands.id')
											->orderBy('brands.name')
											->get()->toArray();	
				
				$color_data = App\Products::select('colors.*')
											->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
											->join('product_categories', 'products.id', '=', 'product_categories.product_id')
											->join('colors', 'product_attributes.color_id', '=', 'colors.id')
											->where('product_categories.cat_id','=',$cat_id)
											->groupBy('colors.id')
											->orderBy('colors.name')
											->get()->toArray();	
				
				$size_data = App\Products::select('sizes.*')
											->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
											->join('product_categories', 'products.id', '=', 'product_categories.product_id')
											->join('sizes', 'product_attributes.size_id', '=', 'sizes.id')
											->where('product_categories.cat_id','=',$cat_id)
											->groupBy('sizes.id')
											->orderBy('sizes.name')
											->get()->toArray();	
											
				$discount_data = App\Products::select(\DB::raw("distinct(round(((products.price - products.spcl_price )*100) / products.price )) as  find_discount "))
											->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
											->join('product_categories', 'products.id', '=', 'product_categories.product_id')
											->where('product_categories.cat_id','=',$cat_id)
											->where('products.spcl_price','!=',null)
											->where('products.spcl_price','!=',0)
											->where(\DB::raw("round(((products.price - products.spcl_price )*100) / products.price )"),">","1")
											->orderBy('find_discount','asc')
											->get()->toArray();	
											
				$rating_data = App\Products::select(\DB::raw("distinct(product_rating.rating) as  find_rating "))
											->join('product_rating', 'products.id', '=', 'product_rating.product_id')
											->join('product_categories', 'products.id', '=', 'product_categories.product_id')
											->where('product_categories.cat_id','=',$cat_id)
											->orderBy('find_rating','desc')
											->get()->toArray();

				$filters_data = App\Products::select(\DB::raw("distinct(product_filters.filters_id) as  filters_id "),\DB::raw("group_concat(product_filters.filters_input_value) as  filters_input_value "))
											->join('product_filters', 'products.id', '=', 'product_filters.product_id')
											->join('product_categories', 'products.id', '=', 'product_categories.product_id')
											->where('product_categories.cat_id','=',$cat_id)
											->groupBy('product_filters.filters_id')
											->get()->toArray();
											
			?>			
            <aside class="sidebar-wrapper">
				<?php 
				if($parameters!='search' && $parameters!='brand'){
				?>
                <div class="sidebar-box">
                    <div class="box-title">
                        <h5 class="showFire" data-toggle="collapse" data-target="#catemenu1">Categories  <span class="fa fa-angle-up"></span></h5></div>
                    <div id="catemenu1" class="box-content last_li_hidden collapse in">
                        <ul class="category-list">
						<?php for($i=0;$i<count($cat_data); $i++){
						$cat_name = preg_replace('/\s+/', '-', strtolower($cat_data[$i]['name']));
						$url=route('cat_wise', [$cat_name,base64_encode($cat_data[$i]['id'])]);?>			
							<li>
								<a href="{{$url}}"><?php echo ucwords($cat_data[$i]['name']);?></a>
							</li>
						<?php } ?>							                        
                        </ul>
                    </div>
				</div>
				<?php }?>

				<?php if(@$brand_data[0]['name']!=''){?>
                <div class="sidebar-box">
                    <div class="box-title">
                        <h5 data-toggle="collapse" data-target="#catemenu2">Brand <span class="fa fa-angle-down"></span></h5></div>
                    <div id="catemenu2" class="box-content last_li_hidden collapse">
						<!--<div class="form-serchs">
							<form>
								<label><i class="fa fa-search" aria-hidden="true"></i></label>
								<input type="search" id="brand_filter" class="form-control">
							</form>
						</div>	-->					
                        <ul class="category-list filtersize">
                            <?php for($i=0;$i<count($brand_data); $i++){?>
							<li>
							<label for="brand<?php echo $brand_data[$i]['id'];?>"><input type="checkbox" onclick="SearchData();" name="checkbox[]" id="brand<?php echo $brand_data[$i]['id'];?>" class="label-checkbox100">
							<span><?php echo ucwords($brand_data[$i]['name']);?></span></label>
							</li>			
							<?php } ?>
                        </ul>
                    </div>
				</div>
				<?php } if(@$color_data[0]['name']!=''){?>

                <div class="sidebar-box">
                    <div class="box-title">
                        <h5 data-toggle="collapse" data-target="#catemenu3">Color <span class="fa fa-angle-down"></span></h5></div>
                    <div id="catemenu3" class="box-content last_li_hidden collapse">
						<!--<div class="form-serchs">
							<form>
								<label><i class="fa fa-search" aria-hidden="true"></i></label>
								<input type="search" id="color_filter" class="form-control">
							</form>
						</div>-->					
                        <ul class="category-list color-filter filtercsum">
							<?php for($i=0;$i<count($color_data); $i++){?>
							<li>
							<label for="color<?php echo $color_data[$i]['id'];?>"><input type="checkbox" onclick="SearchData();" name="checkbox[]" id="color<?php echo $color_data[$i]['id'];?>" class="label-checkbox100">
							<span title="<?php echo ucwords($color_data[$i]['name']);?>" style="background-color:<?php echo $color_data[$i]['color_code'];?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></label>
							</li>			
							<?php } ?>                            
                        </ul>
                    </div>
				</div>
				
				<?php } if(@$size_data[0]['name']!=''){?>

                <div class="sidebar-box">
                    <div class="box-title">
                        <h5 data-toggle="collapse" data-target="#catemenu4">Sizes <span class="fa fa-angle-down"></span></h5></div>
                    <div id="catemenu4" class="box-content last_li_hidden collapse">
						
                        <ul class="category-list filtersize">
							<?php for($i=0;$i<count($size_data); $i++){?>
							<li>
							<label for="size<?php echo $size_data[$i]['id'];?>"><input type="checkbox" onclick="SearchData();" name="checkbox[]" id="size<?php echo $size_data[$i]['id'];?>" class="label-checkbox100">							
								<span><?php echo ucwords($size_data[$i]['name']);?></span></label>								
							</li>
							<?php } ?>
                        </ul>
                    </div>
				</div>
				
				<?php /* <?php } if(@$discount_data[0]['find_discount']!=''){?> */?>

				<!--<div class="sidebar-box">
                    <div class="box-title">
                        <h5 data-toggle="collapse" data-target="#catemenu5">Discounts <span class="fa fa-angle-down"></span></h5></div>
                    <div id="catemenu5" class="box-content last_li_hidden collapse">
                        <ul class="category-list">
							<?php for($i=0;$i<count($discount_data); $i++){?>
							<li>
							<label><input type="checkbox" onclick="SearchData();" name="checkbox[]" id="discount<?php echo $discount_data[$i]['find_discount'];?>" class="label-checkbox100">
							
								<?php echo $discount_data[$i]['find_discount'];?>% Off or More</label>
								
							</li>
							<?php } ?>
                        </ul>
                    </div>
                </div>-->
				 <?php /* <?php } if(@$rating_data[0]['find_rating']!=''){?> */?>

				<!--<div class="sidebar-box">
                    <div class="box-title">
                        <h5 data-toggle="collapse" data-target="#catemenu6">Rating <span class="fa fa-angle-down"></span></h5></div>
                    <div id="catemenu6" class="box-content last_li_hidden collapse">
                        <ul class="category-list">
							<?php for($i=0;$i<count($rating_data); $i++){?>
							<li>
							<label><input type="checkbox" onclick="SearchData();" name="checkbox[]" id="rating<?php echo $rating_data[$i]['find_rating'];?>" class="label-checkbox100">
							
								<?php echo $rating_data[$i]['find_rating'];?><span class="fa fa-star gray-star"></span> & above</label>
								
							</li>
							<?php } ?>
                        </ul>
                    </div>
                </div>-->
				<?php } if(@$filters_data[0]['filters_id']!=''){?>

				<?php 
	
				for($i=0;$i<count(@$filters_data); $i++){
			
				$filters_records = App\Filters::select("filters.name","filters.name")
									->where('filters.id','=',$filters_data[$i]['filters_id'])
									->first();				
				$filters_records_value=array_unique(array_filter(explode(',',@$filters_data[$i]['filters_input_value'])));
				?>
				<div class="sidebar-box">
                    <div class="box-title">
                        <h5 data-toggle="collapse" data-target="#catemenu7"><?php echo ucwords($filters_records->name);?> <span class="fa fa-angle-down"></span></h5></div>
                    <div id="catemenu7" class="box-content last_li_hidden collapse">
                        <ul class="category-list filtersize">
							<?php for($m=0;$m<count(@$filters_records_value); $m++){?>
							<li>
							<label for="filtervalue<?php echo @$filters_records_value[$m];?>"><input type="checkbox" onclick="SearchData();" name="checkbox[]" id="filtervalue<?php echo @$filters_records_value[$m];?>" class="label-checkbox100">
							<span>
								<?php 
									$filters_records = DB::table('filter_values')->select("filter_values.filter_value")
									->where('filter_values.id','=',@$filters_records_value[$m])
									->first();
								
									echo ucwords(@$filters_records->filter_value);?></span></label>
								
							</li>
							<?php } ?>
                        </ul>
                    </div>
				</div>
				<?php }?>
				<?php }?>


                <div class="sidebar-box">
                    <div class="box-title">
                        <h5 data-toggle="collapse" data-target="#catemenu8">Prices <span class="fa fa-angle-down"></span></h5></div>
                    <div id="catemenu8" class="box-content last_li_hidden collapse">
                        <div class="box-content last_li_hidden">
                            <div class="wrapper mg_top40 mg_bottom40">
                                <div class="sliders_step1">
                                    <div id="slider-range"></div>
                                </div>
                                <div class="extra-controls form-inline">
                                    <div id="time-range" class="row">
                                        <div class="col-md-6 col-xs-6"><span class="slider-time">{{$min_price}}</span></div>
                                        <div class="col-md-6 col-xs-6"><span class="slider-time2">{{$max_price}}</span></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
				</div>

            </aside>
        </div>
    </div>
</div>
<?php }?>

<script src="{{ asset('public/fronted/js/jquery.min.js') }}"></script>

<script type="text/javascript" src="https://b2cinfosolutions.in/html/myfitsub/js/jquery-ui-time-range.min.js"></script> 
    <script>
		$("#slider-range").slider({
			range: true,
			min: 0,
			max: 1440,
			step: 15,
			values: [540, 1020],
			slide: function(e, ui) {
				var hours1 = Math.floor(ui.values[0] / 60);
				var minutes1 = ui.values[0] - (hours1 * 60);

				if (hours1.length == 1) hours1 = '0' + hours1;
				if (minutes1.length == 1) minutes1 = '0' + minutes1;
				if (minutes1 == 0) minutes1 = '00';
				if (hours1 >= 12) {
					if (hours1 == 12) {
						hours1 = hours1;
						minutes1 = minutes1 + " PM";
					} else {
						hours1 = hours1 - 12;
						minutes1 = minutes1 + " PM";
					}
				} else {
					hours1 = hours1;
					minutes1 = minutes1 + " AM";
				}
				if (hours1 == 0) {
					hours1 = 12;
					minutes1 = minutes1;
				}



				$('.slider-time').html(hours1 + ':' + minutes1);

				var hours2 = Math.floor(ui.values[1] / 60);
				var minutes2 = ui.values[1] - (hours2 * 60);

				if (hours2.length == 1) hours2 = '0' + hours2;
				if (minutes2.length == 1) minutes2 = '0' + minutes2;
				if (minutes2 == 0) minutes2 = '00';
				if (hours2 >= 12) {
					if (hours2 == 12) {
						hours2 = hours2;
						minutes2 = minutes2 + " PM";
					} else if (hours2 == 24) {
						hours2 = 11;
						minutes2 = "59 PM";
					} else {
						hours2 = hours2 - 12;
						minutes2 = minutes2 + " PM";
					}
				} else {
					hours2 = hours2;
					minutes2 = minutes2 + " AM";
				}

				$('.slider-time2').html(hours2 + ':' + minutes2);
			}
		});
	</script> 


<script>

var htp=$(location).attr('protocol');
var urlname=$(location).attr('host');
var its_url="{{URL::to('/')}}"+"/";
var token="{{ csrf_token() }}";

$( document ).ready(function() {
	
	var mcat=<?php echo $cat_id;?>;
	
	$(".ui-slider-handle").mouseleave(function(){
		
		SearchData();
	
	});
	
    $("#brand_filter").keyup(function(){
		var id=$(this).val();
		var dataString ='cat_id='+mcat+'&brand_id='+id+'&_token='+token;;
	
		$.ajax({
			type:'POST',
			data:dataString,
			url:its_url+'brandfilter',
			success:function(data) {
				$(".brandlist").empty();
				$(".brandlist").html(data);
			}
		  });
	});
	
	$("#size_filter").keyup(function(){
		var id=$(this).val();
		var dataString ='cat_id='+mcat+'&size_id='+id+'&_token='+token;;
		
		$.ajax({
			type:'POST',
			data:dataString,
			url:its_url+'sizefilter',
			success:function(data) {
				$(".sizelist").empty();
				$(".sizelist").html(data);
			}
		  });
	});
	
	$("#color_filter").keyup(function(){
		var id=$(this).val();
		var token="{{ csrf_token() }}";
		var dataString ='cat_id='+mcat+'&color_id='+id+'&_token='+token;
		
		$.ajax({
			type:'POST',
			data:dataString,
			url:its_url+'colorfilter',
			success:function(data) {
				$(".colorlist").empty();
				$(".colorlist").html(data);
			}
		  });
	});
	
	$(document).on('click', '.pagination a',function(event)
    {
		event.preventDefault();
		$('li').removeClass('active');
		$(this).parent('li').addClass('active');
		var myurl = $(this).attr('href');
		var page=$(this).attr('href').split('page=')[1];
		SearchData(page);
    });
	
});

//Search brand/Size/Color/price Product Code Starts
function SearchData(page='')
{   		 
	var facilitiesid = new Array();
	var mcat=<?php echo $cat_id;?>;
	var sortby=$('#sortby').val();
	
	var min_price=$('.slider-time').text();
	var max_price=$('.slider-time2').text();
	
	$( "input[name='checkbox[]']:checked" ).each( function() 
	{
		facilitiesid.push( $(this).attr('id') );
	});
	
	<?php 	
	
	if($parameters=='brand'){
	    $brand_listing=Request::segment(2);
	    
	    ?>
	    	var dataString ='page='+page+'&cat_id='+mcat+'&id='+facilitiesid+'&min_price='+min_price+'&max_price='+max_price+'&type=filter&_token='+token+'&brand='+'<?php echo $brand_listing; ?>'+'&sortby='+sortby;
	
	    <?php
	} else{
	    ?>
	 	var dataString ='page='+page+'&cat_id='+mcat+'&id='+facilitiesid+'&min_price='+min_price+'&max_price='+max_price+'&type=filter&_token='+token+'&sortby='+sortby;
	   <?php 
	}
	?>
	

	$("#loader").show();

	$.ajax({
		type:'POST',
		data:dataString,
		url:its_url+'listingfilter',
		success:function(data) {
			$(".grid-list-option li").removeClass('active');
			$(".grid-list-option li:first-child").addClass('active');
			$("#filterResponseData").empty();
			$("#filterResponseData").append(data);
			$('html, body').animate({ scrollTop: 0 }, 'slow');
			$('li').removeClass('active');
            $("[aria-current='page']").addClass('active');
			location.hash = page;
		}
	  });
}

</script>
 