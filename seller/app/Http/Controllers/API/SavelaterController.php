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


class SavelaterController extends Controller 
{
	public $successStatus = 200;
	
	/** 
     * Savelater Listing api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function savelater_listing(Request $request) {
				    
		$input = json_decode(file_get_contents('php://input'), true);
				    
		$record = DB::table('tbl_save_later')
		 ->select(
        'products.id as fld_product_id',
        'products.name as fld_product_name',
		'product_attributes.color_id as fld_color_id',
		'product_attributes.size_id as fld_size_id',
        'products.price as fld_product_price',
        'products.spcl_price as fld_spcl_price',
       DB::raw("CONCAT('http://aptechbangalore.com/test/uploads/products/',products.default_image) AS default_image"),
        DB::raw("(SELECT COUNT(id)   FROM `product_rating` WHERE `product_id` = products.id) as fld_rating_count"),
            DB::raw("(SELECT COUNT(id)   FROM `product_rating` WHERE `product_id` = products.id and review!='') as fld_review_count")
		                )
            ->join('products','tbl_save_later.fld_product_id','products.id')
			->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
						->where('tbl_save_later.fld_user_id',$input['fld_user_id'])
						->groupBy('products.id')
						->get()
						->toarray();
		$appnededRecord=array();
		foreach($record as $row){
		    $row->fld_total_rating=Products::productRatings($row->fld_product_id);
		    array_push($appnededRecord,$row);
		}
		if($record){
			$statusCode=201;
			$message="Save Later Listing";
			$wishlist_data=$appnededRecord;
		}else{
			$statusCode=404;
			$message="No Save Later Found";
			$wishlist_data=array();
		}
		
		$res=array(
					"status"=>true,
					"statusCode"=>$statusCode,
					"message"=>$message,
					"savelater_data"=>$wishlist_data
				);
		
		echo json_encode($res);
	}
	
	/** 
     * Savelater Add/Delete api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function savelater_add_update(Request $request) {
				    
		$input = json_decode(file_get_contents('php://input'), true);
			
		$action_type=$input['fld_action_type'];
		
		$data=array(
						'fld_product_id'=>$input['fld_product_id'],
						'fld_user_id'=>$input['fld_user_id'],
					);
		
		if($action_type==1)	
		{
			$record=DB::table('tbl_save_later')
						->where('fld_product_id',$input['fld_product_id'])
						->where('fld_user_id',$input['fld_user_id'])
						->delete();
					
			$msg="product removed from Savelater";
		}else if($action_type==2){
			DB::table('tbl_save_later')
						->where('fld_user_id',$input['fld_user_id'])
						->delete();
						$record=null;
					
			$msg="Savelater Deleted";
		}else{
		    $isexist=DB::table('tbl_save_later')
						->where('fld_product_id',$input['fld_product_id'])
						->where('fld_user_id',$input['fld_user_id'])
						->first();
						if($isexist){
						    	$msg="product is already in Savelater ";
						    	$record=$isexist;
						} else{
						  	$record=DB::table('tbl_save_later')
						->insert([$data]);  
							$msg="product Inserted to savelater ";
						}
						
		
						
		
		}
		
		if($record){
			$statusCode=201;
			$message=$msg;
			$savelater_data=$record;
		}else{
			$statusCode=404;
			$message="No Savelater Found";
			$savelater_data=null;
		}
		
		$res=array(
					"status"=>true,
					"statusCode"=>$statusCode,
					"message"=>$message,
					"savelater_add_update_data"=>$savelater_data
				);
		
		echo json_encode($res);
	}
	
	


}