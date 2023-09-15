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
use App\ProductRating;
use App\ProductAttributes;
use App\Colors;
use App\Sizes;
use App\Brands;
use App\ProductCategories;
use App\Filters;
use App\ProductImages;
use App\Category;
use App\Slider;
use App\Vendor;
use URL;
use Config;

class CompareController extends Controller 
{
	public $successStatus = 200;
	public $site_base_path='http://aptechbangalore.com/test/';
	
	public function compareList(Request $request){
	    	$input = json_decode(file_get_contents('php://input'), true);
	    	
	    	$compared_product=array();
            $user_compare_product=DB::table('compare_product')
										->select('product_id')
										->where('user_id','=',$input['fld_user_id'])
										->get();
            $user_id=$input['fld_user_id'];
            foreach($user_compare_product as $prd){
                $product_id=$prd->product_id;
              
                	$record =Products::select(
							'products.id',
							'products.vendor_id',
							'products.name',
							'products.product_type',
							'products.price',
							'products.spcl_price',
							'products.sku',
							'products.short_description',
							'products.long_description',
							'products.tax_class as size_chart',
							'products.qty',
							'products.delivery_days',
							'products.shipping_charges',
							DB::raw("CONCAT('http://aptechbangalore.com/test/uploads/products/',default_image) AS default_image"),
							DB::raw("(SELECT COUNT(id)   FROM `product_rating` WHERE `product_id` = products.id) as fld_rating_count"),
							DB::raw("(SELECT COUNT(id)   FROM `product_rating` WHERE `product_id` = products.id and review!='') as fld_review_count"),
							DB::raw("(SELECT GROUP_CONCAT(product_attributes.size_id)  FROM `product_attributes` WHERE `product_id` = products.id) as size_id"),
							DB::raw("(SELECT GROUP_CONCAT(product_attributes.color_id)   FROM `product_attributes` WHERE `product_id` = products.id) as color_id")
						)
				->where('products.status','1')
				->where('products.id',$product_id)
				
				->first();
	
		if($record){
		    $url='';
		     
		     $cats=ProductCategories::select('categories.size_chart')
                                    ->join('categories','categories.id','product_categories.cat_id')
                                    ->where('product_categories.product_id',$product_id)
                                    ->where('categories.size_chart','!=','')
                                    ->first();
                                    if($cats){
                                        $url=URL::to('/uploads/category/size_chart').'/'.$cats->size_chart;
                                       
                                    }
		    $record->fld_total_rating=Products::productRatings($product_id);
		    $myBagArray = (array)$record; 
		$size_record=array();
	
		    $size_id=explode(',',$record->size_id);
		    $size_record =DB::table('sizes')->select(
                'sizes.id as fld_size_id',
                'sizes.name as fld_size_name'
				)
				->whereIn('sizes.id',$size_id)
				->get()
				->toArray();
	
		
		$color_record=array();
	$image_record=array();
		    $color_id=explode(',',$record->color_id);
		    
		    if($record->product_type==3){
		       if(sizeof($color_id)>0){
		           
            	           	$images_record =DB::table('product_configuration_images')->select(
                        DB::raw("CONCAT('http://aptechbangalore.com/test/uploads/products/',product_config_image) AS image")
            			)
            ->where('product_configuration_images.product_id',$product_id)
            ->where('product_configuration_images.color_id',$color_id[0])
            ->get()
            ->toArray();
            if(sizeof($images_record)>0){
            $record->default_image=$images_record[0]->image;
            }
		         
	
		       }
		        
		    } else{
		        	$images_record =DB::table('product_images')->select(
                DB::raw("CONCAT('http://aptechbangalore.com/test/uploads/products/',image) AS image")
				)
				->where('product_images.product_id',$record->id)
				->get()
				->toArray();
 $single_image=array("image"=>$record->default_image);
array_push($images_record,$single_image);

		        
		    }
		    
		
		$color_record =DB::table('colors')->select(
                'colors.id as fld_color_id',
                'colors.name as fld_color_name',
				'colors.color_code as fld_color_code',
				 DB::raw("CONCAT('http://aptechbangalore.com/test/uploads/color/',color_image) AS fld_color_thumbnail")
				)
				->whereIn('colors.id',$color_id)
				->get()
				->toArray();
	
		
		$attributes=array('sizes'=>$size_record,'colors'=>$color_record);
		$more_sellers=DB::table('vendor_existing_product')
		              ->join('vendors','vendor_existing_product.vendor_id','vendors.id')
                    ->where('vendor_existing_product.master_product_id','=',$product_id)
                    ->get();
                    
                    $vdr_data=Vendor::where('id',$record->vendor_id)->first();
                    
        $fld_estimate_delivery_date=null;
		if($record->delivery_days!='')
		{
			$fld_estimate_delivery_date=date('y-m-d', strtotime("+".$record->delivery_days." days"));
			$fld_estimate_delivery_date=date('d-m-Y',strtotime($fld_estimate_delivery_date));
		}

		
		$record=array(
					"fld_product_id"=>$record->id,
					"fld_product_name"=>$record->name,
					"fld_product_price"=>$record->price,
					"fld_product_spcl_price"=>$record->spcl_price,
					"fld_product_short_description"=>
					route('productDesc', [0,$record->id]),
					"fld_product_long_description"=>	route('productDesc', [1,$record->id]),
					"fld_product_image"=>$record->default_image,
                    "fld_total_rating"=>$record->fld_total_rating,
                    "fld_rating_count"=>$record->fld_rating_count,
                    "fld_review_count"=>$record->fld_review_count,
                    "fld_more_seller"=>$record->vendor_id!=0?true:false,
                    "fld_seller_info"=>$record->vendor_id!=0>0?
                    array(
                            "fld_seller_name"=>$vdr_data?$vdr_data->public_name:'',
                            "fld_seller_rating"=>3,
                            "fld_return_policy_days"=>($record->return_days)?$record->return_days:0
                        )
                    :array(
                                "fld_seller_name"=>'t',
                                "fld_seller_rating"=>0,
                                "fld_return_policy_days"=>0
                        ),
                    "fld_delivery_days"=>"4",
					"fld_estimate_delivery_date"=>"today",
                    "fld_shipping_charges"=>"12",
					
				);
			  array_push($compared_product,$record);
	
		}
            }
          
            if(sizeof($compared_product)==0){
	       $response=array(
                            "status"=>true,
                            "statusCode"=>404,
                            "message"=>"No product in compare list ",
                            "compare_list_data"=>null
                            );
	   } else{
	       $response=array(
                            "status"=>true,
                            "statusCode"=>201,
                            "message"=>"Compare list found",
                            "compare_list_data"=>$compared_product
                            );
	   }
	    echo json_encode($response);
		}
	
	public function addRemoveCompareProduct(Request $request){
	    	$input = json_decode(file_get_contents('php://input'), true);
	   
	   if($input['fld_mode']==0){
	       $arr=explode(",",$input['fld_product_id']);
	       if(sizeof($arr)==0 || $arr[0]=='' ){
	            $response=array(
                                    "status"=>true,
                                    "statusCode"=>404,
                                    "message"=>"Please select product to add"
                                    );
                               
                    	    echo json_encode($response);
                    	    die();
	       }
	       
	      foreach($arr as $ar){
	          $cats=Products::productsFirstCatData($ar);
	       
	       
		$cat_id=0;
		if($cats){
		  $cat_id =$cats->id;
		}
		
		$user_compare_product=DB::table('compare_product')
		->select('id')
		->where('user_id','=',$input['fld_user_id'])
		->first();
		if($user_compare_product){
		    
		    
                        $total_product=DB::table('compare_product')
                        ->select('id')
                        ->where('user_id','=',$input['fld_user_id'])
                        ->get();
                        if(sizeof($total_product)==3){
                                    $response=array(
                                    "status"=>true,
                                    "statusCode"=>404,
                                    "message"=>"Compare list full"
                                    );
                               
                    	    echo json_encode($response);
                    	    die();
                        }
		
		     $same_cat_product=DB::table('compare_product')
            ->select('id')
            ->where('cat_id','=',$cat_id)
           ->where('user_id','=',$input['fld_user_id'])
            ->first();
            
            if($same_cat_product){
                $is_exist=DB::table('compare_product')
                ->select('id')
                ->where('product_id','=',$ar)
                ->where('cat_id','=',$cat_id)
                ->where('user_id','=',$input['fld_user_id'])
                ->first();
        
	
		if($is_exist){
		    
                    $response=array(
                            "status"=>true,
                            "statusCode"=>404,
                            "message"=>"Product already in compare list"
                            );
		    
	
		} else{
		    	$inputs=array(
                'product_id' =>$ar,
                'cat_id' =>$cat_id,
                'user_id' =>$input['fld_user_id']
			);
			    DB::table('compare_product')->insert($inputs);
			    
			    $res=array(
                            "status"=>true,
                            "statusCode"=>201,
                            "message"=>"Product added for compare"
                            );
			    
		}
                
            } else{
                
                			    $response=array(
                            "status"=>true,
                            "statusCode"=>404,
                            "message"=>"Compare Product only  of same category"
                            );
                   
            }
		} else{
           	$inputs=array(
                'product_id' =>$ar,
                'cat_id' =>$cat_id,
                'user_id' =>$input['fld_user_id']
			);
				DB::table('compare_product')->insert($inputs);
				
			
                			    $response=array(
                            "status"=>true,
                            "statusCode"=>201,
                            "message"=>"Product added for compare"
                            );
		}
		
	
	      }
	      	echo json_encode($response);
	   }
	   
	   if($input['fld_mode']==1){
	       $res=DB::table('compare_product')
                        ->where('user_id','=',$input['fld_user_id'])
                        ->where('product_id','=',$input['fld_product_id'])
                        ->delete();
                if($res){
                 $response=array(
                            "status"=>true,
                            "statusCode"=>201,
                            "message"=>"Product remove from compare list"
                            );
                } else{
                     $response=array(
                            "status"=>true,
                            "statusCode"=>404,
                            "message"=>"Something went wrong"
                            );
                }
                echo json_encode($response);
	       
	   }
	   
		
	}
	
	public function brandsList(){
	    $brands=Brands::select('id as fld_brand_id','name as fld_brand_name')->where('isdeleted',0)->where('status',1)->get()->toarray();
	   if(sizeof($brands)==0){
	       $response=array(
                            "status"=>true,
                            "statusCode"=>404,
                            "message"=>"No Brand List found ",
                            "compare_brand_data"=>null
                            );
	   } else{
	       $response=array(
                            "status"=>true,
                            "statusCode"=>201,
                            "message"=>"Brand List found",
                            "compare_brand_data"=>$brands
                            );
	   }
	    echo json_encode($response);
	}
	public function brandsWiseProduct(){
	    $input = json_decode(file_get_contents('php://input'), true);
	     $prd_img=$this->site_base_path.'uploads/products/';
	    	$fld_page_no=@$input['fld_page_no'];
	    		$user_id=@$input['fld_user_id'];
	    	$page=$fld_page_no;
	    if($page!=0){
		 $fld_page_no=$fld_page_no*10;
		}
	    $prd_id=array();
            $user_compare_product=DB::table('compare_product')
            ->select('product_id')
            ->where('user_id','=',$input['fld_user_id'])
            ->get();
            foreach($user_compare_product as $prd){
                array_push($prd_id,$prd->product_id);
            }
            $brand_id=$input['fld_brand_id'];
            
        $prds=DB::table('products')
        ->select('id as fld_product_id','name as fld_product_name')
        ->where('isdeleted',0)
        ->where('status',1)
        ->whereNotIn('products.id',$prd_id)
        ->where('product_brand',$input['fld_brand_id'])
        ->get()->toarray();
        
        
        	$record =Products::select(
									'products.id',
									'product_categories.cat_id',
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
								)
						->join('product_categories','products.id','=','product_categories.product_id')
						->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
						->where('products.isexisting',0)
						->where('products.isdeleted',0)
						->where('products.status',1)
						->whereNotIn('products.id',$prd_id)
						->where('products.product_brand',$brand_id);
                        
						
			$allrecord =Products::select('products.id')
						->join('product_categories','products.id','=','product_categories.product_id')
						->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')
						->where('products.isexisting',0)
						->where('products.isdeleted',0)
						->where('products.status',1)
						->whereNotIn('products.id',$prd_id)
						->where('products.product_brand',$brand_id)
						->get()
						->toarray();
						
						$record=$record ->offset($fld_page_no)
							->limit(10)
							->get()
							->toarray();
                
		$appnededRecord=array();
		foreach($record as $row){
		    $color_image=ProductImages::getConfiguredImagesAPI($row['id'],$row['color_id']);
			
				if(sizeof($color_image)>0){
				    $prd_img=@$color_image[0]->image;
				} else{
				   	$prd_img=Products::getproductImageUrlAPI(1,$row['default_image']);
				}
			$row['default_image']=$prd_img;
			$row['fld_total_rating']=Products::productRatings($row['id']);
		    $row['isInWishlist']=Customer::productInWishlist($user_id,$row['id']);
		    array_push($appnededRecord,$row);
		}
		
            $message="Brand wise product Listing";
            $api_key="brand_wise_product_data";
		
		echo $this->msg_info($message,$appnededRecord,$page,$api_key,$allrecord);
		
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