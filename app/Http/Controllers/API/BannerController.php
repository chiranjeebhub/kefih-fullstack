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
use Config;

class BannerController extends Controller 
{
	public $successStatus = 200;
		public $site_base_path='https://phaukat.com/';
		
		public function __construct()
    {
        $this->site_base_path=Config::get('constants.Url.public_url');
        //$this->middleware('auth');
    }
	/** 
     * Banner Listing api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function banner_listing(Request $request) {
    		  	$input = $request->all();  
       file_put_contents('req.txt',json_encode($input));
         $sitecityname = 'NA';
           $sitecityId = '0';
        if(isset($input['city_id'])){
           $sitecityId= $input['city_id'];
        }
    
				    
		$record =Slider::
		select(
            'id as fld_slider_id',
            'short_text as fld_short_description',
            'description as fld_long_description',
            'url as fld_banner_url',
            'product_type as fld_product_type',
            'cat_prd_id as fld_cat_prd_id',
            DB::raw("CONCAT('".$this->site_base_path."uploads/slider/',image) AS fld_slider_image")
			
			)
		           	->whereRaw("find_in_set($sitecityId,city_ids)")
					->where('status','=',1)
					->get()
					->toarray();
						
		if($record){
			$statusCode=201;
			$message="Banner Listing";
			$slider_data=[];
		}else{
			$statusCode=404;
			$message="No banner found";
			$slider_data=[];
		}
		
		$res=array(
					"status"=>true,
					"statusCode"=>$statusCode,
					"message"=>$message,
					"banner_data"=>$slider_data
				);
		file_put_contents('res.txt',json_encode($res));
		echo json_encode($res);
	}
	
	
	
	


}