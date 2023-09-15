<?php

namespace App\Helpers;
use Illuminate\Http\Request;
use Redirect;
use Validator;
use Config;
use App\Brands;
use App\ProductSlider;
use App\Products;
use App\ProductCategories;
use App\Category;
use DB;
class HomeProductSliderHelper
{

   
   
   public static function getBrandSlider()
    {
            $Brands= Brands::where('isdeleted', 0);
            $Brands=$Brands->orderBy('id', 'DESC')->paginate(10);

	 return view('fronted.mod_product.brand-slider',['Brands'=>$Brands]); 
		 
    }
    
	public static function getSlider($slider_type)
    {
		$prd_setting = DB::select("SELECT * FROM product_setting");
		
		$products=Products::select('product_home_slider.product_id','product_home_slider.slider_name','product_home_slider.url','products.*',"product_attributes.color_id","product_attributes.price as extra_price")
			->join('product_home_slider', 'product_home_slider.product_id', '=', 'products.id')
			->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
			->where('product_home_slider.slider_type',$slider_type)
            ->where('products.isexisting',0)
            ->where('products.status',1)
            ->where('products.isblocked','=',0)
            ->where('products.isdeleted',0)
            ->orderBy('products.id','desc');
		
		if($prd_setting[0]->product_shows_type==1)
		{
			$products->groupBy("product_attributes.product_id");
		}
		
		if($slider_type==0){
		    $products=$products->limit(8);
		}
		$products=$products->get();
		
		if(sizeof($products)>0){
		    $slider_name='';
		    switch($slider_type){
		        
		        case 0;
		          $slider_name='FEATURED';
		        break;
		        
		        case 1;
		          $slider_name='Best Selling';
		        break;
		        
		        
		        case 2;
		         $slider_name='Also Bought';
		        break;
		        
		        case 5;
		        $slider_name='Offer going on';
		        break;
		        
		       
		    }
		  return view('fronted.mod_product.product_bestdeal_slider',['products'=>$products,'slider_type'=>$slider_type,'slider_name'=>$slider_name]);  
		}
		 
    }
		public static function getSliderCustomized($slider_type)
    {
		$prd_setting = DB::select("SELECT * FROM product_setting");
		
		$products=Products::select('product_home_slider.product_id','product_home_slider.slider_name','product_home_slider.url','products.*',"product_attributes.color_id","product_attributes.price as extra_price")
			->join('product_home_slider', 'product_home_slider.product_id', '=', 'products.id')
			->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
			->where('product_home_slider.slider_type',$slider_type)
			->where('products.status','=',1);
		
		if($prd_setting[0]->product_shows_type==1)
		{
			$products->groupBy("product_attributes.product_id");
		}
		$products=$products->get();
		
		if(sizeof($products)>0){
		    $slider_name='';
		    switch($slider_type){
		        
		        case 0;
		          $slider_name='FEATURED';
		        break;
		        
		        case 1;
		          $slider_name='Best Selling';
		        break;
		        
		        
		        case 2;
		         $slider_name='Also Bought';
		        break;
		        
		      //  case 4;
		      //  $slider_name='Offer going on';
		      //  break;
		      
		       case 5;
		        $slider_name='Offer going on';
		        break;
		        
		       
		    }
		  return view('fronted.mod_product.offer_slider',['products'=>$products,'slider_type'=>$slider_type,'slider_name'=>$slider_name]);  
		}
		 
    }
	
	public static function getSimilarProduct($id){
		
		$prd_setting = DB::select("SELECT * FROM product_setting");
		
	    $obj=new ProductCategories();
	    $cats=$obj->getCategories($id);
	    $products=Products::select('product_categories.product_id','products.*',"product_attributes.color_id","product_attributes.price as extra_price")
								->join('product_categories', 'product_categories.product_id', '=', 'products.id')
								->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
								->groupBy('product_categories.product_id')
								->whereIn('product_categories.cat_id',$cats)
								->where('products.isexisting',0)
								->where('products.status',1)
								->where('products.isblocked','=',0)
								->where('products.isdeleted',0);
			
		if($prd_setting[0]->product_shows_type==1)
		{
			$products->groupBy("product_attributes.product_id");
		}
			
		$products=$products->paginate(10);
		
			if(sizeof($products)>0){
			    return view('fronted.mod_product.similar-product',['products'=>$products,'slider_type'=>$id]);
			}
		
	   
	}
	
	public static function home_page_product(){
		
		$prd_setting = DB::select("SELECT * FROM product_setting");
		
	 $cats=Category::getcats(1);
	 $html='';
     foreach($cats as $cat){
        
         	$products=Products::select('product_categories.product_id','products.*',"product_attributes.color_id","product_attributes.price as extra_price")
    			->join('product_categories', 'product_categories.product_id', '=', 'products.id')
    			->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
    			->groupBy('product_categories.product_id')
				->where('product_categories.cat_id',$cat['id'])
                ->where('products.isexisting',0)
                ->where('products.status',1)
                ->where('products.isblocked','=',0)
                ->where('products.isdeleted',0);
				
			if($prd_setting[0]->product_shows_type==1)
			{
				$products->groupBy("product_attributes.product_id");
			}
    		$products=$products->paginate(10);
			
    		if(sizeof($products)>0){
    		    $cat_name = preg_replace('/\s+/', '-', strtolower($cat['name']));
				$url=route('cat_wise', [$cat_name,base64_encode($cat['id'])]);
						
    			$html.= view('fronted.mod_product.home-category-product',
    			[
                        'products'=>$products,
                        'cat_id'=>$cat['id'] ,
                        'cat_name'=>$cat['name'] ,
                        'slider_type'=>'cat'.$cat['id'] ,
                        "url"=>$url
    			    ])->render();
    		}
    	
     }
     return ($html);
 
	}
	
	
	public static function recentlyViewProduct(){
		
		$prd_setting = DB::select("SELECT * FROM product_setting");
		
	    $ss=app(\App\Http\Controllers\CookieController::class)->getCookie();
		
	    $products_id=json_decode($ss); 
		
		if(count($products_id)==1)
	    {
	       $products=Products::select('products.*',"product_attributes.color_id")
					->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
                    ->where('products.id',$products_id)
                    ->where('products.isexisting',0)
                    ->where('products.status',1)
                    ->where('products.isblocked','=',0)
                    ->where('products.isdeleted',0);
					
			if($prd_setting[0]->product_shows_type==1)
			{
				$products->groupBy("product_attributes.product_id");
			}
                $products=$products->get(9);
	    		//if(sizeof($products)>0){
		            return view('fronted.mod_product.recently-view-product',['products'=>$products,'slider_type'=>'recent']);  
	            //}
		}elseif(count($products_id)>1){
			$products=Products::select('products.*','product_attributes.color_id')
					->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
                    ->where('products.isexisting',0)
                    ->where('products.status',1)
                    ->where('products.isblocked','=',0)
                    ->where('products.isdeleted',0)
                    ->whereIn('products.id',$products_id);
					
			if($prd_setting[0]->product_shows_type==1)
			{
				$products->groupBy("product_attributes.product_id");
			}
                $products=$products->get(9);
	    		
		            return view('fronted.mod_product.recently-view-product',['products'=>$products,'slider_type'=>'recent']);  
	            
		}
			
	    
	}
	
	/*******************************/
	
	
	/*******************************/
	
	
}
