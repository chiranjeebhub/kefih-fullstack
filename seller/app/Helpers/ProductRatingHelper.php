<?php

namespace App\Helpers;
use Illuminate\Http\Request;
use Redirect;
use Validator;
use Config;
use App\ProductSlider;
use App\Products;
use App\ProductCategories;
use App\Category;
use DB;

class ProductRatingHelper
{

   public static function getProductRating($product_id)
   {
		$products=Products::select('products.id',DB::raw('avg(product_rating.rating) as product_rating'))
			->join('product_rating', 'products.id', '=', 'product_rating.product_id')
			->where('product_rating.product_id',$product_id)->first();
		 
		 return view('fronted.mod_product.product_rating.index',['product_rating'=>$products]);
   }
	
	
	
	
}
