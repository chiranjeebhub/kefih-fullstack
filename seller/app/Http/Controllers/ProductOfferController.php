<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Validator;
use DB;
use Config;
use Auth;
use App\Products;
use App\ProductSlider;
use App\Category;
use App\Colors;
use App\Sizes;
use App\Brands;
use App\ProductCategories;


use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response;
use App\Http\Requests;
use Cookie;
use App\Http\Controllers\Controller;

class ProductOfferController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     
    /* public function index()
    {
        return view('home');
    }
    */
    
	/*public function offerzone(Request $request)
	{
	    //$cat_id=base64_decode($request->cat_id);
		//$brand_id=base64_decode($request->brand_id);
	    
		$products=Products::select("products.*",\DB::raw("round(((products.price - products.spcl_price )*100) / products.price )") )
			->where('products.spcl_price','!=',null)
			->where('products.spcl_price','!=',0)
			->where(\DB::raw("round(((products.price - products.spcl_price )*100) / products.price )"),">","1")
			->paginate(12);
	
        return view('fronted.mod_product_offer.product-offer-listing',['products'=>$products]);
	} 
	
	public function offerzone_category_product(){
	 $cats=Category::getcats(1);
	 $html='';
     foreach($cats as $cat){
        
         	$products=ProductCategories::select("products.*",\DB::raw("round(((products.price - products.spcl_price )*100) / products.price )") )
			->join('products', 'product_categories.product_id', '=', 'products.id')
			->where('product_categories.cat_id',$cat['id'])
			->where('products.isexisting',0)
			->where('products.isdeleted',0)
			->where('products.status','=',1)
			->where('products.spcl_price','!=',null)
			->where('products.spcl_price','!=',0)
			->where(\DB::raw("round(((products.price - products.spcl_price )*100) / products.price )"),">","1")
			->paginate(12);
			
    		if(sizeof($products)>0){
    		    $cat_name = preg_replace('/\s+/', '-', strtolower($cat['name']));
				$url=route('cat_offer_wise', [$cat_name,base64_encode($cat['id'])]);
						
    			$html.= view('fronted.mod_product_offer.category-product-offer-listing',
    			[
                        'products'=>$products,
                        'cat_id'=>$cat['id'] ,
                        'cat_name'=>$cat['name'] ,
                        'slider_type'=>'cat'.$cat['id'] ,
                        "url"=>$url
    			    ])->render();
    		}
    	
     }
     return view('fronted.mod_product_offer.product-offer-listing',['offerzone_html'=>$html]);
 
	}*/
	
	public function cat_offer_wise(Request $request)
	{
		$dat=explode('~~~~~',base64_decode($request->id));
		
		$id=$dat[0];
		$type=$dat[1];
		
	
		$offer_discount=$dat[2];
		$cat_name=$dat[3];
		if($dat[4]==0){	
			$below_above="<=";
		}else if($dat[4]==1){	
			$below_above=">=";
		}
		
		$input='';
		
	   	$max_min = Products::select("products.id",\DB::raw("min(products.spcl_price) as min_price"),\DB::raw("max(products.spcl_price) as max_price"));
	   	if($type==0){	
                $max_min=$max_min->join('product_categories', 'products.id', '=', 'product_categories.product_id');
                $max_min=	$max_min->where('product_categories.cat_id','=',$id);
		}else{	
            $max_min=$max_min->join('brands', 'brands.id', '=', 'products.product_brand');
            $max_min=	$max_min->where('brands.id','=',$id);
		}
	   	    
	  $max_min= $max_min->where('products.isexisting',0)
	    ->where('products.isdeleted',0)
	   ->where('products.status','=',1)->get()->first();
	   
	   $min_price=!empty($max_min->min_price)?$max_min->min_price:'1';
	   $max_price= !empty($max_min->max_price)?$max_min->max_price:'1';
	   
	   $data = Products::select("products.*",\DB::raw("round(((products.price - products.spcl_price )*100) / products.price )"));
	   	if($type==0){	
                $data=$data->join('product_categories', 'products.id', '=', 'product_categories.product_id');
                $data=	$data->where('product_categories.cat_id','=',$id);
		}else{	
            $data=$data->join('brands', 'brands.id', '=', 'products.product_brand');
            $data=	$data->where('brands.id','=',$id);
		}
	
	
			$data=	$data->where('products.isexisting',0)
	    ->where('products.isdeleted',0)
		->where('products.status','=',1)
		->where(\DB::raw("round(((products.price - products.spcl_price )*100) / products.price )"),$below_above,$offer_discount)
	    ->where(function($query) use ($min_price,$max_price){
                 $query->whereBetween('products.spcl_price', array($min_price,$max_price));
             })
		->orderBy("products.spcl_price")->paginate(12);
	   
	    if ($request->ajax()) {
			return view('fronted.mod_product.product-filter-listing',["products"=>$data,'cat_id'=>$id,'cat_name'=>'test'])->render();
		}
	  
		 //return view('fronted.mod_product.product-listing',["products"=>$data,'cat_id'=>$id,'min_price'=>$min_price,'max_price'=>$max_price]);
		 
		 return view('fronted.mod_product_offer.category-product-offer-list',["products"=>$data,'cat_id'=>$id,'cat_name'=>$cat_name,'min_price'=>$min_price,'max_price'=>$max_price]);
	}
	
	
	

 
}
