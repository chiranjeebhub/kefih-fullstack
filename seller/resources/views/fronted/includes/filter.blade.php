<?php if(sizeof($products)>0){?>
<?php 
	$brand_listing="";
	$parameters= Request::segment(1); 				
	$cat_data = App\Category::select('categories.*')
						->where('categories.parent_id','=',$cat_id)
						->where('categories.status','=',1)
						->where('categories.isdeleted','=',0)
						->get()->toArray();	
	
	$brand_data = App\Products::select('brands.*')
								->join('brands', 'products.product_brand', '=', 'brands.id')
								->join('product_categories', 'products.id', '=', 'product_categories.product_id')
							    ->where('product_categories.cat_id','=',$cat_id)
							    ->where('products.isdeleted','=',0)
                                ->where('products.status','=',1)
								->groupBy('brands.id')
								->orderBy('brands.name')
								->get()->toArray();	
	
	$color_data = App\Products::select('colors.*')
								->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
							    ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
								->join('colors', 'product_attributes.color_id', '=', 'colors.id')
								->where('product_categories.cat_id','=',$cat_id)
								->where('products.isdeleted','=',0)
                ->where('products.status','=',1)
								->groupBy('colors.id')
								->orderBy('colors.name')
								->get()->toArray();	
	
	$size_data = App\Products::select('sizes.*')
								->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
							    ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
								->join('sizes', 'product_attributes.size_id', '=', 'sizes.id')
								->where('product_categories.cat_id','=',$cat_id)
								->where('products.isdeleted','=',0)
                             ->where('products.status','=',1)
								->groupBy('sizes.id')
								->orderBy('sizes.id')
								->get()->toArray();	
								
	$discount_data = App\Products::select(\DB::raw("distinct(round(((products.price - products.spcl_price )*100) / products.price )) as  find_discount "))
								->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
							    ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
								->where('product_categories.cat_id','=',$cat_id)
								->where('products.spcl_price','!=',null)
								->where('products.spcl_price','!=',0)
								->where(\DB::raw("round(((products.price - products.spcl_price )*100) / products.price )"),">","1")
								->orderBy('find_discount','asc')
								->where('products.isdeleted','=',0)
                                ->where('products.status','=',1)
								->get()->toArray();	
								
	$rating_data = App\Products::select(\DB::raw("distinct(product_rating.rating) as  find_rating "))
								->join('product_rating', 'products.id', '=', 'product_rating.product_id')
							    ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
								->where('product_categories.cat_id','=',$cat_id)
								->orderBy('find_rating','desc')
								->where('products.isdeleted','=',0)
                                ->where('products.status','=',1)
								->get()->toArray();

	
                
                
                
        $filters_data =	DB::table('filters')->select(
        \DB::raw("distinct(filters.id) as  filters_id "),
        \DB::raw("group_concat(filter_values.id) as  filters_input_value ")
        )
        ->join('filter_values', 'filter_values.filter_id', '=', 'filters.id')
        ->join('filters_category', 'filters_category.filter_id', '=', 'filters.id')
        ->where('filters_category.cat_id','=',@$cat_id)
        ->where('filters.isdeleted','=',0)
        ->where('filters.status','=',1)
        ->get()->toArray();
        
        $filters_data =	DB::table('filters')->select(
        	    \DB::raw("distinct(filters.id) as  filters_id ")
        	    )
                ->join('filter_values', 'filter_values.filter_id', '=', 'filters.id')
                 ->join('filters_category', 'filters_category.filter_id', '=', 'filters.id')
                ->where('filters_category.cat_id','=',$cat_id)
                ->where('filters.isdeleted','=',0)
                ->where('filters.status','=',1)
                ->get()->toArray();
                
             
                
               
                
                
                
 // echo '<pre>';print_r($filters_data);die;           
								
?>
    
   
	
	
<div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 sidebar__inner">
    <div class="filter">
		<h6 class="col-md-12 col-xs-8"><span id="account-btn">Filter by <i class="fa fa-arrow-down"></i></span>
		<div class="clearall">         
			<a href="javascript:void(0)" id="clear_all" style="display:<?php echo ($type_filter!='')?'block':'none';?>">Clear all</a>                   
		</div> 
		</h6>

		<h6 class="col-xs-4 hidden-md hidden-lg"> <span id="sortt-btn">Sort by <i class="fa fa-arrow-down"></i></span>  
		<!-- <div class="clearall">         
			<a href="javascript:void(0)" id="clear_all1" style="display:none;">Clear all</a>                   
		</div>  -->
		</h6> 		  
	</div>

	<div id="mobile-show">				  				  
		<aside class="sidebar-wrapper bgwhite"> </aside>    
		<div class="bgwhite">
        	<aside class="sidebar-wrapper">            
				<?php if($parameters!='search' && $parameters!='brand'){
				if(count($cat_data)>0){
				?>		
				<div class="sidebar-box">
					<div class="box-title"><h5 data-toggle="collapse" data-target="#rootCat2">Categories <span class="fa fa-angle-down"></span></h5></div>
					<div id="rootCat2" class="box-content last_li_hidden collapse">
						<ul class="category-list filtersize">
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
				<?php }}?>
			
				<?php if(1==0){?>
				<div class="sidebar-box">
					<div class="box-title"><h5 data-toggle="collapse" data-target="#catemenu2">Brand <span class="fa fa-angle-down"></span></h5></div>
					<div id="catemenu2" class="box-content last_li_hidden collapse">
						<ul class="category-list filtersize">
							<?php for($i=0;$i<count($brand_data); $i++){?>
							<li>
								<label for="brand<?php echo $brand_data[$i]['id'];?>"><input type="checkbox" onclick="SearchData();"
								<?php echo (in_array( $brand_data[$i]['id'], $brands))?'checked':'';?>
								name="checkbox[]" id="brand<?php echo $brand_data[$i]['id'];?>" class="label-checkbox100">
								<span><?php echo ucwords($brand_data[$i]['name']);?></span></label>
							</li>			
							<?php } ?>                       
						</ul>
					</div>
				</div> 
				<?php } ?>   
				<?php if(@$size_data[0]['name']!=''){?>		
				<div class="sidebar-box">
					<div class="box-title"><h5 data-toggle="collapse" data-target="#catemenu4">Sizes <span class="fa fa-angle-down"></span></h5></div>
					<div id="catemenu4" class="box-content last_li_hidden collapse">
						<ul class="category-list filtersize">
							<?php for($i=0;$i<count($size_data); $i++){?>
							<li>
								<label for="size<?php echo $size_data[$i]['id'];?>"><input type="checkbox" onclick="SearchData();" 
								<?php echo (in_array( $size_data[$i]['id'], $size_array))?'checked':'';?>
								name="checkbox[]" id="size<?php echo $size_data[$i]['id'];?>" class="label-checkbox100">				
								<span><?php echo ucwords($size_data[$i]['name']);?></span></label>					
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>
				<?php } ?> 
				<?php if(@$color_data[0]['name']!=''){?> 			
				<div class="sidebar-box">
					<div class="box-title"><h5 data-toggle="collapse" data-target="#catemenu3">Color <span class="fa fa-angle-down"></span></h5></div>
					<div id="catemenu3" class="box-content last_li_hidden collapse">
						<ul class="category-list color-filter filtercsum">			
							<?php for($i=0;$i<count($color_data); $i++){?>
							<li>
								<label for="color<?php echo $color_data[$i]['id'];?>"><input type="checkbox" onclick="SearchData();"
									<?php echo (in_array( $color_data[$i]['id'], $color_array))?'checked':'';?>
								name="checkbox[]" id="color<?php echo $color_data[$i]['id'];?>" class="label-checkbox100">
								<span title="<?php echo ucwords($color_data[$i]['name']);?>" style="background-color:<?php echo $color_data[$i]['color_code'];?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></label>
							</li>	
							<?php } ?> 
						</ul>
					</div>
				</div>
				<?php } ?> 
			
				<?php 	
				for($i=0;$i<count(@$filters_data); $i++){			
					$filters_records = App\Filters::select("filters.name","filters.name")
										->where('filters.id','=',$filters_data[$i]->filters_id)
										->first();
					// $filters_records_value=array_unique(array_filter(explode(',',@$filters_data[$i]->filters_input_value)));
					$filters_records_value=DB::table('filter_values')->where('filter_id',$filters_data[$i]->filters_id)->get()->toarray();
				?>
				<div class="sidebar-box">
					<div class="box-title"><h5 data-toggle="collapse" data-target="#catemenu10{{$i}}"><?php echo ucwords($filters_records->name);?> <span class="fa fa-angle-down"></span></h5></div>
					<div id="catemenu10{{$i}}" class="box-content last_li_hidden collapse">
						<ul class="category-list filtersize">  
							@foreach($filters_records_value as $a)
							<li>
							<label for="filtervalue<?php echo @$a->id;?>"><input type="radio" onclick="SearchData();"  
							<?php echo (in_array(@$a->id, $filtervalue_array))?'checked':'';?>
							name="checkbox[]" id="filtervalue<?php echo @$a->id;?>" class="label-checkbox100 sortyBY">
							<span>{{ucwords(@$a->filter_value)}}</span>
							</label>
							</li>
							@endforeach		
						</ul>
					</div>
				</div>
				<?php }  ?>
				<div class="sidebar-box">
					<div class="box-title"><h5>Prices</h5></div>
					<div class="box-content">
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
				<!-- <div class="sidebar-box">
					<div class="box-title"><h5 data-toggle="collapse" data-target="#catemenu8">Price <span class="fa fa-angle-down"></span></h5></div>
						<div id="catemenu8" class="box-content last_li_hidden collapse">
							<div class="box-content last_li_hidden">
								<div class="wrapper mg_top40 mg_bottom40">
									<div class="sliders_step1">
										<div id="slider-range"></div>
									</div>

									<div class="extra-controls form-inline">
										<div id="time-range" class="row">
											<div class="col-md-6 col-xs-6"><span class="slider-time">455</span></div>
											<div class="col-md-6 col-xs-6"><span class="slider-time2">{{$max_price}}</span></div>
										</div>
									</div>

								</div>
							</div>
						</div>
				</div>-->
			
				<div class="sidebar-box hidden-sm hidden-xs">
					<div class="box-title"><h5 data-toggle="collapse" data-target="#catemenu9">Sort By <span class="fa fa-angle-down"></span></h5></div>
					<div id="catemenu9" class="box-content last_li_hidden collapse">
						<ul class="category-list filtersize">            
							<li>
								<label for="radio1"><input type="radio" onclick="SearchData();"
								
									<?php echo (4==$sortby)?'checked':'';?>
								name="sotyBY" value="4" id="radio1" class="label-checkbox100 sortyBY">
								<span>Popular</span></label>
							</li>
							<li>
								<label for="radio2"><input type="radio" onclick="SearchData();"
								<?php echo (3==$sortby)?'checked':'';?>
								name="sotyBY" value="3" id="radio2" class="label-checkbox100 sortyBY">
								<span>New</span></label>
							</li>
							<li>
								<label for="radio3"><input type="radio" onclick="SearchData();"
									<?php echo (2==$sortby)?'checked':'';?>
								name="sotyBY" value="2" id="radio3" class="label-checkbox100 sortyBY">
								<span>Price : High to Low</span></label>
							</li>
							<li>
								<label for="radio4"><input type="radio" onclick="SearchData();" 
									<?php echo (1==$sortby)?'checked':'';?>
								name="sotyBY" value="1" id="radio4" class="label-checkbox100 sortyBY">
								<span>Price : Low to High</span></label>
							</li>
							<!--<li><a href="#">Popular</a></li>        
							<li><a href="#">New</a></li>   
							<li><a href="#">Price : High to Low</a></li>        
							<li><a href="#">Price : Low to High</a></li>--> 					
						</ul>
					</div>
				</div>             
			</aside>    
		</div>
	</div>    
	<div id="mobile-sort" class="mobile-sort hidden-md hidden-lg">
		<div class="bgwhite">
			<aside class="sidebar-wrapper">
				<div class="sidebar-box">
					<div class="box-title"><h5 data-toggle="collapse" data-target="#catemenu10">Sort By <span class="fa fa-angle-down"></span></h5></div>
					<div id="catemenu10" class="box-content last_li_hidden collapse">
						<ul class="category-list filtersize">            
							<li>
								<label for="radio1"><input type="radio" onclick="SearchData();"  name="sotyBY" value="4" id="radio1" class="label-checkbox100 sortyBY">
								<span>Popular</span></label>
							</li>
							<li>
								<label for="radio2"><input type="radio" onclick="SearchData();" name="sotyBY" value="3" id="radio2" class="label-checkbox100 sortyBY">
								<span>New</span></label>
							</li>
							<li>
								<label for="radio3"><input type="radio" onclick="SearchData();" name="sotyBY" value="2" id="radio3" class="label-checkbox100 sortyBY">
								<span>Price : High to Low</span></label>
							</li>
							<li>
								<label for="radio4"><input type="radio" onclick="SearchData();" name="sotyBY" value="1" id="radio4" class="label-checkbox100 sortyBY">
								<span>Price : Low to High</span></label>
							</li>
							<!--<li><a href="#">Popular</a></li>        
							<li><a href="#">New</a></li>   
							<li><a href="#">Price : High to Low</a></li>        
							<li><a href="#">Price : Low to High</a></li>--> 					
						</ul>
					</div>
				</div>
			</aside>
		</div>
	</div> 
  
</div>    
<?php }?>

<script type="text/javascript" src="https://www.phaukat.com/public/fronted/js/jquery-1.12.0.min.js"></script>
<!--<script src="{{ asset('public/fronted/js3/jquery.min.js') }}"></script>-->
<script>

function showClearButton(){
    $("#clear_all").css('display','block');
    $(".clearall").css('display','block'); 
}
var htp=$(location).attr('protocol');
var urlname=$(location).attr('host');
var its_url='{{URL::to('/')}}/';
var token="{{ csrf_token() }}";

$( document ).ready(function() {
	
	var mcat=<?php echo $cat_id;?>;
	
	$(".ui-slider-handle").mouseleave(function(){
		
		SearchData();
	showClearButton();
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
    $(window).scrollTop(50);
   
   
	var facilitiesid = new Array();
	var mcat=<?php echo $cat_id;?>;
	
           
            var srt=4;
            if ($("input[name='sotyBY']:checked").val()) {
            var srt=$("input[name='sotyBY']:checked").val();
            }
	
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
	    	var dataString ='page='+page+'&cat_id='+mcat+'&id='+facilitiesid+'&min_price='+min_price+'&max_price='+max_price+'&type=filter&_token='+token+'&brand='+'<?php echo $brand_listing; ?>'+'&sortby='+srt;
	
	    <?php
	} else{
	    ?>
	 	var dataString ='page='+page+'&cat_id='+mcat+'&id='+facilitiesid+'&min_price='+min_price+'&max_price='+max_price+'&type=filter&_token='+token+'&sortby='+srt;
	   <?php 
	}
	?>
	

	$("#loader").show();

	$.ajax({
		type:'POST',
		data:dataString,
		url:its_url+'listingfilter',
		success:function(data) {
		     $('#loader').css("display","none");
			$(".pagination li").removeClass('active');
			$(".productAfterFilter").empty();
			$(".productAfterFilter").append(data);
			$(".hidepagi").empty();
			$('li').removeClass('active');
            $("[aria-current='page']").addClass('active');
            
			location.hash = page;
		}
	  });
}

</script>
<!--<script type="text/javascript" src="https://b2cinfosolutions.in/html/myfitsub/js/jquery-ui-time-range.min.js"></script> 
<script>
	$("#slider-range").slider({
		range: true,
		min: <?php echo $min_price;?>,
		max: <?php echo $max_price;?>,
		step: 5,
		values: [<?php echo $min_price;?>, <?php echo $max_price;?>],
		slide: function (e, ui) {
			var hours1 = Math.floor(ui.values[0]);
			
			$('.slider-time').html(hours1);

			var hours2 = Math.floor(ui.values[1]);
			
			$('.slider-time2').html(hours2);
		}
	});
</script> --> 
<script>

var htp=$(location).attr('protocol');
var urlname=$(location).attr('host');
var its_url='{{URL::to('/')}}/';
var token="{{ csrf_token() }}";
$( document ).ready(function() {
	var mcat=<?php echo $cat_id;?>;
	
	$("#clear_all").click(function(){
        $("#clear_all").hide();
        $(".clearall").hide();
        resetSlider();
        $( "input[name='checkbox[]']:checked" ).prop("checked", false);
        $( "input[name='sotyBY']:checked" ).prop("checked", false);
        SearchData();
    });
    
    function resetSlider() {
  var $slider = $("#slider-range");
  $slider.slider("values", 0, minPrice);
  $slider.slider("values", 1, maxPrice);
  
$('.slider-time').html(minPrice);
$('.slider-time2').html(maxPrice);
}



	
	/*$(".ui-slider-handle").mouseleave(function(){
		SearchData();
	});*/
	
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
         console.log("second call");
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
{   $(window).scrollTop(50);
 console.log("second");
                 $('#loader').css("display","none");
                $('#loader').css("display","flex");
	var facilitiesid = new Array();
	var mcat=<?php echo $cat_id;?>;
    var srt=4;
                if ($("input[name='sotyBY']:checked").val()) {
                    showClearButton();
                var srt=$("input[name='sotyBY']:checked").val();
                }
           
	
	
	var min_price=$('.slider-time').text();
	var max_price=$('.slider-time2').text();
	
	$( "input[name='checkbox[]']:checked" ).each( function() 
	{    showClearButton();
		facilitiesid.push( $(this).attr('id') );
	});
	
	<?php 	
	
	if($parameters=='brand'){
	    $brand_listing=Request::segment(2);
	    
	    ?>
	    	var dataString ='page='+page+'&cat_id='+mcat+'&id='+facilitiesid+'&min_price='+min_price+'&max_price='+max_price+'&type=filter&_token='+token+'&brand='+'<?php echo $brand_listing; ?>'+'&sortby='+srt;
	
	    <?php
	} else{
	    ?>
	 	var dataString ='page='+page+'&cat_id='+mcat+'&id='+facilitiesid+'&min_price='+min_price+'&max_price='+max_price+'&type=filter&_token='+token+'&sortby='+srt;
	   <?php 
	}
	?>
	

	$("#loader").show();
$.ajax({
		type:'POST',
		data:dataString,
		url:its_url+'listingfilter',
		success:function(data) {
		                $('#loader').css("display","none");
			$(".grid-list-option li").removeClass('active');
			$(".grid-list-option li:first-child").addClass('active');
			$("#filterResponseData").empty();
			$("#filterResponseData").append(data);
			
			$('li').removeClass('active');
            $("[aria-current='page']").addClass('active');
          
          if(page==''){
              page=1;
          }
            <?php 	
            
            if($parameters=='brand'){
            $brand_listing=Request::segment(2);
            
            ?>
	 history.pushState(null,null,"?page="+page+'&id='+facilitiesid+'&min_price='+min_price+'&max_price='+max_price+'&type=filter='+'&brand='+'<?php echo $brand_listing; ?>'+'&sortby='+srt);
        <?php
        } else{
        ?>
        history.pushState(null,null,"?page="+page+'&id='+facilitiesid+'&min_price='+min_price+'&max_price='+max_price+'&type=filter='+'&sortby='+srt);
        <?php 
        }
	?>
           
          
			location.hash = page;
		}
	  });

}

</script>

<!--<script>
$("#slider-range").slider({
		range: true,
		min: <?php echo $min_price;?>,
		max: <?php echo $max_price;?>,
		step: 5,
		values: [<?php echo $min_price;?>, <?php echo $max_price;?>],
		slide: function (e, ui) {
			var hours1 = Math.floor(ui.values[0]);
			
			$('.slider-time').html(hours1);

			var hours2 = Math.floor(ui.values[1]);
			
			$('.slider-time2').html(hours2);
		}
	});
</script> --> 

<script type="text/javascript" src="https://b2cinfosolutions.in/html/myfitsub/js/jquery-ui-time-range.min.js"></script> 
<script>
var minPrice="<?php echo $min_price;?>";
var maxPrice="<?php echo $max_price;?>";

var minPrice1="<?php echo $min_price1;?>";
var maxPrice2="<?php echo $max_price1;?>";

if(minPrice1==''){
    minPrice1=minPrice
}

if(maxPrice2==''){
    maxPrice2=maxPrice
}
maxPrice=4000;
maxPrice=20000;
	$("#slider-range4").slider({
		range: true,
		min: minPrice1,
		max: maxPrice2,
		step: 1,
		values: [<?php echo $min_price;?>, <?php echo $max_price;?>],
		slide: function (e, ui) {
			var hours1 = Math.floor(ui.values[0]);
			
			$('.slider-time').html(hours1);

			var hours2 = Math.floor(ui.values[1]);
			
			$('.slider-time2').html(hours2);
			
			 
			  
		}
	});
</script>  