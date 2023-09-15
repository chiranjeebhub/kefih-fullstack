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
use App\Sizes;
use App\ProductRating;
use App\ProductCategories;
use App\ProductImages;
use App\Category;
use Config;


class WishlistController extends Controller 
{
	public $successStatus = 200;
		public $site_base_path='http://aptechbangalore.com/test/';
		
		public function __construct()
    {
        $this->site_base_path=Config::get('constants.Url.public_url');
        //$this->middleware('auth');
    }
	/** 
     * Wishist Listing api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function wishlist_listing(Request $request) {
				    
		$input = json_decode(file_get_contents('php://input'), true);
				    
		$record = DB::table('tbl_wishlist')
						->select(
								'products.id as fld_product_id',
								'products.name as fld_product_name',
								'product_attributes.color_id as fld_color_id',
								'product_attributes.size_id as fld_size_id',
								'products.price as fld_product_price',
								'products.spcl_price as fld_spcl_price',
								DB::raw("CONCAT('".$this->site_base_path."uploads/products/',products.default_image) AS default_image"),
								DB::raw("(SELECT COUNT(id)   FROM `product_rating` WHERE `product_id` = products.id) as fld_rating_count"),
								DB::raw("(SELECT COUNT(id)   FROM `product_rating` WHERE `product_id` = products.id and review!='') as fld_review_count")
								)
						->join('products','tbl_wishlist.fld_product_id','products.id')
						->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
						->where('tbl_wishlist.fld_user_id',$input['fld_user_id'])
						->groupBy('products.id')
						->get()
						->toarray();
		$appnededRecord=array();
		foreach($record as $row){
			
				$color_image=ProductImages::getConfiguredImagesAPI($row->fld_product_id,$row->fld_color_id);   
			
				if(sizeof($color_image)>0){
				    $prd_img=@$color_image[0]->image;
				} else{
				   	$prd_img=Products::getproductImageUrlAPI(1,$row->default_image);
				}
				 
				$extrs_price=0;
				if($row->fld_color_id!='' && $row->fld_color_id!=0)
				{
					$color_price=DB::table('product_attributes')
										->where('product_id',$row->fld_product_id)
										->where('color_id',$row->fld_color_id)
										->where('qty','>',0)->first();

					$extrs_price=$color_price->price;
				}
				
				$row->default_image=$prd_img;
				$row->price=$row->fld_product_price+$extrs_price;
				$row->spcl_price=$row->fld_spcl_price+$extrs_price;
			
		    $row->fld_total_rating=Products::productRatings($row->fld_product_id);
		    array_push($appnededRecord,$row);
		}
		if($record){
			$statusCode=201;
			$message="Wishist Listing";
			$wishlist_data=$appnededRecord;
		}else{
			$statusCode=404;
			$message="No Wishist Found";
			$wishlist_data=array();
		}
		
		$res=array(
					"status"=>true,
					"statusCode"=>$statusCode,
					"message"=>$message,
					"wishlist_data"=>$wishlist_data
				);
		
		echo json_encode($res);
	}
	
	/** 
     * Wishlist Add/Delete api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function wishlist_add_update(Request $request) {
				    
		$input = json_decode(file_get_contents('php://input'), true);
			
		$action_type=$input['fld_action_type'];
		
		$data=array(
						'fld_product_id'=>$input['fld_product_id'],
						'fld_user_id'=>$input['fld_user_id'],
					);
		
		if($action_type==1)	
		{
			$record=DB::table('tbl_wishlist')
						->where('fld_product_id',$input['fld_product_id'])
						->where('fld_user_id',$input['fld_user_id'])
						->delete();
					
			$msg="product removed from Wishist";
		}
		else if($action_type==2)	
		{
			DB::table('tbl_wishlist')
						->where('fld_user_id',$input['fld_user_id'])
						->delete();
						$record=null;
					
			$msg="Wishist Deleted";
		}else{
		    $isexist=DB::table('tbl_wishlist')
						->where('fld_product_id',$input['fld_product_id'])
						->where('fld_user_id',$input['fld_user_id'])
						->first();
						if($isexist){
						    	$msg="product is already in Wishist ";
						    	$record=$isexist;
						} else{
						  	$record=DB::table('tbl_wishlist')
						->insert([$data]);  
							$msg="product Inserted to Wishist ";
						}
						
		
						
		
		}
		
		if($record){
			$statusCode=201;
			$message=$msg;
			$wishlist_data=$record;
		}else{
			$statusCode=404;
			$message="No Wishist Found";
			$wishlist_data=null;
		}
		
		$res=array(
					"status"=>true,
					"statusCode"=>$statusCode,
					"message"=>$message,
					"wishlist_add_update_data"=>$wishlist_data
				);
		
		echo json_encode($res);
	}
	
	


}