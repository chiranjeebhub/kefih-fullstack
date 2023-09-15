<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Customer; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Validator;
use DB;
use App\Helpers\CommonHelper;
use App\Products;
use App\ProductAttributes;
use App\Colors;
use App\Slider;
use App\Sizes;
use App\ProductCategories;
use App\ProductImages;
use App\Category;


class BannerController extends Controller 
{
	public $successStatus = 200;
	/** 
     * Banner Listing api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function banner_listing(Request $request) {
				    
		$input = json_decode(file_get_contents('php://input'), true);
				    
		$record =Slider::
		select('id as fld_slider_id','short_text as fld_short_description','description as fld_long_description','url as fld_banner_url',
		'product_type as fld_product_type','cat_prd_id as fld_cat_prd_id',
			DB::raw("CONCAT('http://aptechbangalore.com/test/uploads/slider/',image) AS fld_slider_image")
			)
					->where('status','=',1)
					->get()
					->toarray();
						
		if($record){
			$statusCode=201;
			$message="Banner Listing";
			$slider_data=$record;
		}else{
			$statusCode=404;
			$message="No banner found";
			$slider_data=null;
		}
		
		$res=array(
					"status"=>true,
					"statusCode"=>$statusCode,
					"message"=>$message,
					"banner_data"=>$slider_data
				);
		
		echo json_encode($res);
	}
	
	
	
	


}