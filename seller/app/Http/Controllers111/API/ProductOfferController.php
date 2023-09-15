<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Validator;
use DB;
use App\Helpers\CommonHelper;
use App\Products;
use App\Category;
use App\ProductRating;
use App\ProductAttributes;
use App\Colors;
use App\Sizes;
use App\Brands;
use App\ProductCategories;
use App\ProductImages;
use App\Customer;

use URL;
use Config;

class ProductOfferController extends Controller 
{
	public $successStatus = 200;
	
	public $site_base_path='https://phaukat.com/';
	
	public function __construct()
    {
        $this->site_base_path=Config::get('constants.Url.public_url');
        //$this->middleware('auth');
    }
	
	public function cat_offer_wise()
	{
		$input = json_decode(file_get_contents('php://input'), true);
		$cat_id=@$input['fld_cat_offer_id'];
		$offer_zone_type=@$input['offer_zone_type'];
		$offer_discount=@$input['offer_discount'];
		$offer_name=@$input['offer_name'];
		$offer_below_above=@$input['offer_below_above'];
		$user_id=@$input['fld_user_id'];
		
		$fld_page_no=$input['fld_page_no'];
        $page=$fld_page_no;
            	
		if($page!=0){
		 $fld_page_no=$fld_page_no*10;
		}
		
		
		$id=$cat_id;
		$type=$offer_zone_type;
		
		$offer_discount=$offer_discount;
		$cat_name=$offer_name;
		if($offer_below_above==0){	
			$below_above="<=";
		}else if($offer_below_above==1){	
			$below_above=">=";
		}
		
		$input='';
		
	   	$max_min = Products::select("products.id",\DB::raw("min(products.spcl_price) as min_price"),\DB::raw("max(products.spcl_price) as max_price"));
	   	if($type==0){	
			$max_min=$max_min->join('product_categories', 'products.id', '=', 'product_categories.product_id');
			$max_min=$max_min->where('product_categories.cat_id','=',$id);
		}else{	
            $max_min=$max_min->join('brands', 'brands.id', '=', 'products.product_brand');
            $max_min=	$max_min->where('brands.id','=',$id);
		}
	   	    
		$max_min=$max_min->where('products.isexisting',0)
							->where('products.isdeleted',0)
							->where('products.status','=',1)->get()->first();
	   
		$min_price=!empty($max_min->min_price)?$max_min->min_price:'1';
		$max_price= !empty($max_min->max_price)?$max_min->max_price:'1';
	   
		//$data = Products::select("products.*",\DB::raw("round(((products.price - products.spcl_price )*100) / products.price )"));
		$prd_img='';
		$data = Products::select(
									'products.id',
									'product_categories.cat_id',
									'product_attributes.unisex_type',
									'product_attributes.color_id',
									'product_attributes.size_id',
									'product_attributes.price as extra_price',
									'products.name',
									'products.price',
									'products.spcl_price',
									DB::raw("CONCAT('$prd_img',default_image) AS default_image"),
									DB::raw("(".Products::productRatings('products.id').") as fld_total_rating"),
									DB::raw("(SELECT COUNT(id)   FROM `product_rating` WHERE `product_id` = products.id) as fld_rating_count"),
									DB::raw("(SELECT COUNT(id)   FROM `product_rating` WHERE `product_id` = products.id and review!='') as fld_review_count")
								);
								
			$data=$data->join('product_attributes', 'products.id', '=', 'product_attributes.product_id');
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
					->orderBy("products.spcl_price");
		
		$data=$data->offset($fld_page_no)
					->limit(10)
					->get()
					->toarray();
					
					
		$appnededRecord=array();
		foreach($data as $row){
		    $color_image=ProductImages::getConfiguredImagesAPI($row['id'],$row['color_id']);
			
				if(sizeof($color_image)>0){
				    $prd_img=@$color_image[0]->image;
				} else{
				   	$prd_img=Products::getproductImageUrlAPI(1,$row['default_image']);
				}
				
				$extrs_price=0;
				if($row['color_id']!='' && $row['color_id']!=0)
				{
					$color_price=DB::table('product_attributes')
										->where('product_id',$row['id'])
										->where('color_id',$row['color_id'])
										->where('qty','>',0)->first();

					$extrs_price=@$color_price->price;
				}
				
				$row['default_image']=$prd_img;
				$row['price']=100;
				$row['spcl_price']=$row['spcl_price']+$extrs_price;
				
				if($offer_zone_type!=''){
				   	$offer_zone_type=0; 
				}
					if($offer_below_above!=''){
				   	$offer_below_above=0; 
				}
				
				
        $row['offerId']=$cat_id;
        $row['zoneType']=$offer_zone_type;
        $row['offerDiscount']=$offer_discount;
        $row['offerName']=$cat_name;
        $row['offerBelowAbove']=$offer_below_above;
				
			
				$row['fld_total_rating']=Products::productRatings($row['id']);
				$row['isInWishlist']=Customer::productInWishlist($user_id,$row['id']);
				array_push($appnededRecord,$row);
		}
						
	    /*if ($request->ajax()) {
			//return view('fronted.mod_product.product-filter-listing',["products"=>$data,'cat_id'=>$id,'cat_name'=>'test'])->render();
		}
		 //return view('fronted.mod_product.product-listing',["products"=>$data,'cat_id'=>$id,'min_price'=>$min_price,'max_price'=>$max_price]);
		 //return view('fronted.mod_product_offer.category-product-offer-list',["products"=>$data,'cat_id'=>$id,'cat_name'=>$cat_name,'min_price'=>$min_price,'max_price'=>$max_price]);
		 */
		 
		 $message="Offer Zone Product Listing";
		 $api_key='offerzone_view_Product_data';
		 
		 $record=$appnededRecord;
		 $allrecord=count($appnededRecord);
		 $page=0;
		
		echo $this->msg_info($message,$record,$page,$api_key,$allrecord);
	}
	
	public function msg_info(
                    $msg,
                    $data,
                    $page_no,
                    $api_key,
                    $Allrecord=array(),
                    $extra_keys='',
                    $extra_data=array()
	          )
	{
	    if($data){
			$status=true;
			$statusCode=201;
			$message=$msg;
			$data_list=$data;
			$return_page=($page_no+1);
		}else{
			$status=false;
			$statusCode=404;
			$message='No '.$msg.' Found';
			$data_list=null;
			$return_page=$page_no;
		}
		if($extra_keys!=''){
		   	$res=array(
					"status"=>$status,
					"statusCode"=>$statusCode,
					 "fld_total_page"=>ceil(sizeof($Allrecord)/10),
                    $extra_keys=>$extra_data,
					"message"=>$message,
					"next_page"=>$return_page,
					$api_key=>$data_list
				); 
		} else{
		    	$res=array(
					"status"=>$status,
					"statusCode"=>$statusCode,
					 "fld_total_page"=>ceil(sizeof($Allrecord)/10),
					"message"=>$message,
					"next_page"=>$return_page,
					$api_key=>$data_list
				);
		}
	
		
		return json_encode($res);
	}
	
	
	

}