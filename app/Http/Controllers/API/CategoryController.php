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
use App\ProductCategories;
use App\ProductImages;
use App\Category;
use URL;
use Config;
class CategoryController extends Controller 
{
	public $successStatus = 200;
	public $site_base_path='https://phaukat.com/';
	
	public function __construct()
    {
        $this->site_base_path=Config::get('constants.Url.public_url');
        //$this->middleware('auth');
    }
	/** 
     * Category Listing api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
     
     public function getsideChilds($id){
          $cats =Category::
				select(
                'categories.id as fld_cat_id',
                'categories.name as fld_cat_name',
                'categories.cat_compare as fld_cat_compare',
                DB::raw("CONCAT('".$this->site_base_path."uploads/category/logo/',logo) AS image"),
                DB::raw("CONCAT('".$this->site_base_path."uploads/category/banner/',banner_image) AS banner_image"),
                 DB::raw("(IF(categories.app_icon!='', 
                    CONCAT('".$this->site_base_path."uploads/category/logo/',categories.app_icon),
                    CONCAT('".$this->site_base_path."uploads/category/logo/','no_icon.png')
                    )) as fld_app_icon")
				)
                    ->where('categories.isdeleted',0)
                    ->where('categories.status',1)
                    	->where('categories.cat_shows_in_mobile_side_nav',1)
                    ->where('categories.parent_id',$id)
                    ->get()
                    ->toarray();
                    return $cats;
     }
     
     public function getChilds($id){
          $cats =Category::
				select(
                'categories.id as fld_cat_id',
                'categories.name as fld_cat_name',
                'categories.cat_compare as fld_cat_compare',
                DB::raw("CONCAT('".$this->site_base_path."uploads/category/logo/',logo) AS image"),
                DB::raw("CONCAT('".$this->site_base_path."uploads/category/banner/',banner_image) AS banner_image"),
                 DB::raw("(IF(categories.app_icon!='', 
                    CONCAT('".$this->site_base_path."uploads/category/logo/',categories.app_icon),
                    CONCAT('".$this->site_base_path."uploads/category/logo/','no_icon.png')
                    )) as fld_app_icon")
				)
                    ->where('categories.isdeleted',0)
                    ->where('categories.status',1)
                    	->where('categories.cat_shows_in_mobile_cat',1)
                    ->where('categories.parent_id',$id)
                    ->get()
                    ->toarray();
                    return $cats;
     }
     public function child_cat(Request $request){
         	$input = json_decode(file_get_contents('php://input'), true);
				    
		$params=@$input['fld_cat_id'];
         
        $subcats=$this->getsideChilds($params);
                    
                    $whole_array=array();
							foreach($subcats as $subcat){
						    $subcat['cats']=$this->getsideChilds($subcat['fld_cat_id']);
						    array_push($whole_array,$subcat);
						}
					
					if(sizeof($whole_array)>0){
                        $res=array(
                        "status"=>true,
                        "statusCode"=>201,
                        "message"=>'Listing found',
                        "category_data"=>$whole_array
                        );
					} else{
                    $res=array(
                    "status"=>true,
                    "statusCode"=>404,
                    "message"=>'No listing found',
                    "category_data"=>$whole_array
                    ); 
					}	
       
		
		echo json_encode($res);
        
     }
public function child_subcat(Request $request){
         $input = json_decode(file_get_contents('php://input'), true);
				    
		$params=@$input['fld_cat_id'];
         
        $subcats=$this->getChilds($params);
                    
                    $whole_array=array();
							foreach($subcats as $subcat){
						    $subcat['cats']=$this->getChilds($subcat['fld_cat_id']);
						    array_push($whole_array,$subcat);
						}
					
					if(sizeof($whole_array)>0){
                        $res=array(
                        "status"=>true,
                        "statusCode"=>201,
                        "message"=>'Listing found',
                        "category_data"=>$whole_array
                        );
					} else{
                    $res=array(
                    "status"=>true,
                    "statusCode"=>404,
                    "message"=>'No listing found',
                    "category_data"=>$whole_array
                    ); 
					}	
       
		
		echo json_encode($res);
        
     }
     public function subcat_list(Request $request){
	 	$input = json_decode(file_get_contents('php://input'), true);
	 	$rootcat_id = @$input['rootcat_id'];
	 	
	 	$newsArrv=DB::table('categories')
		->select(
		DB::raw("(id) as ids")
		)->where('parent_id',$rootcat_id)->get()->toarray();
		
		$nedata = array();$appnededRecord=array();
		foreach ($newsArrv as $nrow){
			//echo $nrow->ids;die;
			
			$record =Category::	select(
                    'categories.id',
                    'categories.name',
                    'categories.cat_compare as fld_cat_compare',
                    DB::raw("(IF(categories.logo!='', 
                    CONCAT('".$this->site_base_path."uploads/category/logo/',categories.logo),
                    CONCAT('".$this->site_base_path."uploads/category/logo/','no_icon.png')
                    )) as image"),
                    DB::raw("(IF(categories.banner_image!='', 
                    CONCAT('".$this->site_base_path."uploads/category/banner/',categories.banner_image),
                    CONCAT('".$this->site_base_path."uploads/category/banner/','no_icon.png')
                    )) as banner_image"),
                    DB::raw("(IF(categories.app_icon!='', 
                    CONCAT('".$this->site_base_path."uploads/category/logo/',categories.app_icon),
                    CONCAT('".$this->site_base_path."uploads/category/logo/','no_icon.png')
                    )) as fld_app_icon")
			)
						->where('categories.parent_id',$nrow->ids)
						//->where('categories.id',$nrow->ids)
						->where('categories.isdeleted',0)
							->where('categories.status',1)
						->where('categories.cat_shows_in_mobile_cat',1)
					    ->orderBy('id','desc')
                        ->limit(9)
                        ->get()
						->toarray();
						
						if($record){
							foreach($record as $row){
				        	array_push($appnededRecord,$row);	
							}
								
						}
					
		}
	 
			
						
             
				
		if($appnededRecord){
			$statusCode=201;
			$message="Category Listing";
			$category_data=$appnededRecord;
			//$return_page=($page+1);
		}
        else{
			$statusCode=404;
			$message="No Category Found";
			$category_data=null;
			//$return_page=$page;
		}
		
		$res=array(
                    "status"=>true,
                    "statusCode"=>$statusCode,
                   // "fld_total_page"=>ceil(sizeof($Allrecord)/5),
					"message"=>$message,
					//"next_page"=>$return_page,
					"subcategory_data"=>$category_data
				);
		
		echo json_encode($res);
	 }
    public function category_listing(Request $request) {
				    
		$input = $request->all();
			 
		$cat_id=@$input['fld_cat_id'];
		$fld_page_no=@$input['fld_page_no'];
		$page=$fld_page_no;
		if($page!=0){
			$fld_page_no=$fld_page_no*5;
		}
			
		$total_page=0;
		if($cat_id!=0){
			$record =Category::
				select(
                'categories.id',
                    'categories.name',
                    'categories.cat_compare as fld_cat_compare',
                  DB::raw("(IF(categories.logo!='', 
                    CONCAT('".$this->site_base_path."uploads/category/logo/',categories.logo),
                    CONCAT('".$this->site_base_path."uploads/category/logo/','no_icon.png')
                    )) as image"),
                    DB::raw("CONCAT('".$this->site_base_path."uploads/category/banner/',banner_image) AS banner_image"),
                    DB::raw("(IF(categories.app_icon!='', 
                    CONCAT('".$this->site_base_path."uploads/category/logo/',categories.app_icon),
                    CONCAT('".$this->site_base_path."uploads/category/logo/','no_icon.png')
                    )) as fld_app_icon")
				)
						->where('categories.isdeleted',0)
						->where('categories.status',1)
						->where('categories.cat_shows_in_mobile_side_nav',1)
						->where('categories.parent_id',$cat_id)
						->orderBy('categories.mobile_app_order','asc')
						
						//->offset($fld_page_no)
						
                        // ->limit(5)
                        ->get()
						->toarray();
						
            $Allrecord =Category::select(
                        'categories.id'
                        )
                ->where('categories.isdeleted',0)
                ->where('categories.status',1)
                ->where('categories.parent_id',$cat_id)
                ->get()
                ->toarray();
		}else{
			$record =Category::	select(
                    'categories.id',
                    'categories.name',
                    'categories.cat_compare as fld_cat_compare',
                   DB::raw("(IF(categories.logo!='', 
                    CONCAT('".$this->site_base_path."uploads/category/logo/',categories.logo),
                    CONCAT('".$this->site_base_path."uploads/category/logo/','no_icon.png')
                    )) as image"),
                    DB::raw("CONCAT('".$this->site_base_path."uploads/category/banner/',banner_image) AS banner_image"),
                    DB::raw("(IF(categories.app_icon!='', 
                    CONCAT('".$this->site_base_path."uploads/category/logo/',categories.app_icon),
                    CONCAT('".$this->site_base_path."uploads/category/logo/','no_icon.png')
                    )) as fld_app_icon")
			)
						->where('parent_id','=',1)
						->where('categories.isdeleted',0)
						->where('categories.status',1)
						->where('categories.cat_shows_in_mobile_side_nav',1)
					    ->orderBy('categories.mobile_app_order','desc')
					    ->offset($fld_page_no)
					    
                         ->limit(10)
                        ->get()
						->toarray();
						
              $Allrecord =Category::	select(
						'categories.id'
						)
					->where('parent_id','=',1)
					->where('categories.isdeleted',0)
					->where('categories.status',1)
					->get()
					->toarray();
		}
						
		if($record){
			$statusCode=201;
			$message="Category Listing";
			$category_data=$record;
			$return_page=($page+1);
		}else{
			$statusCode=404;
			$message="No Category Found";
			$category_data=null;
			$return_page=$page;
		}
		
		$res=array(
                    "status"=>true,
                    "statusCode"=>$statusCode,
                    "fld_total_page"=>ceil(sizeof($Allrecord)/5),
					"message"=>$message,
					"next_page"=>$return_page,
					"category_data"=>$category_data
				);
		
		echo json_encode($res);
	}
	
	
	public function featuredcategory_listing1(Request $request) {
				    
		$input = json_decode(file_get_contents('php://input'), true);
	
	
				    
		$cat_id=@$input['fld_cat_id'];
		$fld_page_no=@$input['fld_page_no'];
		$page=$fld_page_no;
		if($page!=0){
			$fld_page_no=$fld_page_no*5;
		}
			
		$total_page=0;
		
			$record =Category::	select(
                    'categories.id',
                    'categories.name',
                    'categories.cat_compare as fld_cat_compare',
                    DB::raw("CONCAT('".$this->site_base_path."uploads/category/logo/',logo) AS image"),
                    DB::raw("CONCAT('".$this->site_base_path."uploads/category/banner/',banner_image) AS banner_image"),
                    DB::raw("(IF(categories.app_icon!='', 
                    CONCAT('".$this->site_base_path."uploads/category/logo/',categories.app_icon),
                    CONCAT('".$this->site_base_path."uploads/category/logo/','no_icon.png')
                    )) as fld_app_icon")
			)
						->where('parent_id','=',1)
						->where('categories.featured',1)
						->where('categories.isdeleted',0)
							->where('categories.status',1)
						->where('categories.parent_id',$cat_id)
                        ->get()
						->toarray();
					
						
              $Allrecord =Category::	select(
						'categories.id'
						)
					->where('parent_id','=',1)
					->where('categories.isdeleted',0)
					->get()
					->toarray();
						
		if($record){
			$statusCode=201;
			$message="Category Listing";
			$category_data=$record;
			$return_page=($page+1);
		}else{
			$statusCode=404;
			$message="No Category Found";
			$category_data=null;
			$return_page=$page;
		}
		
		$res=array(
                    "status"=>true,
                    "statusCode"=>$statusCode,
                    "fld_total_page"=>ceil(sizeof($Allrecord)/5),
					"message"=>$message,
					"next_page"=>$return_page,
					"category_data"=>$category_data
				);
		
		echo json_encode($res);
	}
	public function featuredcategory_listing(Request $request) {
				    
		$input = json_decode(file_get_contents('php://input'), true);
			file_put_contents('feature.txt',json_encode($input));
        $cats=array();
        $catsss=array();
  
		$levels1 = Category::select(
                    'categories.id',
                    'categories.name',
                     'categories.isdeleted',
                      'categories.status',
                       'categories.featured',
                       'categories.cat_shows_in_mobile',
                    'categories.cat_compare as fld_cat_compare',
                  DB::raw("(IF(categories.logo!='', 
                    CONCAT('".$this->site_base_path."uploads/category/logo/',categories.logo),
                    CONCAT('".$this->site_base_path."uploads/category/logo/','no_icon.png')
                    )) as image"),
                   
                     DB::raw("(IF(categories.banner_image!='', 
                    CONCAT('".$this->site_base_path."uploads/category/banner/',categories.banner_image),
                    CONCAT('".$this->site_base_path."uploads/category/logo/','no_icon.png')
                    )) as banner_image"),
                    DB::raw("(IF(categories.app_icon!='', 
                    CONCAT('".$this->site_base_path."uploads/category/logo/',categories.app_icon),
                    CONCAT('".$this->site_base_path."uploads/category/logo/','no_icon.png')
                    )) as fld_app_icon")
			)
                ->where('parent_id','=',1)
                ->where('categories.featured',1)
                ->where('categories.isdeleted',0)
                ->where('categories.status',1)
                ->get()
                ->toarray();
				
	
        foreach($levels1 as $level1){
           if($level1['isdeleted']==0 && $level1['status']==1 &&  $level1['cat_shows_in_mobile']==1){
               
               array_push($cats,$level1);
           }
           
           
           
           
        //   level two start
        	$levels2 = Category::select(
                    'categories.id',
                    'categories.name',
                     'categories.isdeleted',
                      'categories.status',
                       'categories.featured',
                        'categories.cat_shows_in_mobile',
                    'categories.cat_compare as fld_cat_compare',
                  DB::raw("(IF(categories.logo!='', 
                    CONCAT('".$this->site_base_path."uploads/category/logo/',categories.logo),
                    CONCAT('".$this->site_base_path."uploads/category/logo/','no_icon.png')
                    )) as image"),
                   
                     DB::raw("(IF(categories.banner_image!='', 
                    CONCAT('".$this->site_base_path."uploads/category/banner/',categories.banner_image),
                    CONCAT('".$this->site_base_path."uploads/category/logo/','no_icon.png')
                    )) as banner_image"),
                    DB::raw("(IF(categories.app_icon!='', 
                    CONCAT('".$this->site_base_path."uploads/category/logo/',categories.app_icon),
                    CONCAT('".$this->site_base_path."uploads/category/logo/','no_icon.png')
                    )) as fld_app_icon")
			)
                ->where('categories.parent_id',$level1['id'])
                
                ->where('categories.featured',1)
                ->where('categories.isdeleted',0)
                ->where('categories.status',1)
                ->get()
                ->toarray();
        foreach($levels2 as $level2){
           if($level2['isdeleted']==0 && $level2['status']==1 &&  $level2['cat_shows_in_mobile']==1){
               
               array_push($cats,$level2);
           }
           //   level three start
        	$levels3 = Category::select(
                    'categories.id',
                    'categories.name',
                     'categories.isdeleted',
                      'categories.status',
                       'categories.featured',
                       'categories.cat_shows_in_mobile',
                    'categories.cat_compare as fld_cat_compare',
                  DB::raw("(IF(categories.logo!='', 
                    CONCAT('".$this->site_base_path."uploads/category/logo/',categories.logo),
                    CONCAT('".$this->site_base_path."uploads/category/logo/','no_icon.png')
                    )) as image"),
                   
                     DB::raw("(IF(categories.banner_image!='', 
                    CONCAT('".$this->site_base_path."uploads/category/banner/',categories.banner_image),
                    CONCAT('".$this->site_base_path."uploads/category/logo/','no_icon.png')
                    )) as banner_image"),
                    DB::raw("(IF(categories.app_icon!='', 
                    CONCAT('".$this->site_base_path."uploads/category/logo/',categories.app_icon),
                    CONCAT('".$this->site_base_path."uploads/category/logo/','no_icon.png')
                    )) as fld_app_icon")
			)
                ->where('categories.parent_id',$level2['id'])
                    ->where('parent_id','=',1)
                    ->where('categories.featured',1)
                    ->where('categories.isdeleted',0)
                    ->where('categories.status',1)
                    ->get()
                    ->toarray();
        foreach($levels3 as $level3){
           if($level3['isdeleted']==0 && $level3['status']==1 &&  $level3['cat_shows_in_mobile']==1){
               array_push($cats,$level3);
           }
           
            
        }
          //   level three end
            
        }
          //   level two end
            
        }
      
      
		if(sizeof($cats)>0){
		    	$res=array(
                    "status"=>true,
                    "statusCode"=>201,
                    "fld_total_page"=>0,
					"message"=>"feature category listed",
					"next_page"=>0,
					"category_data"=>$cats
				);
		} else{
		    	$res=array(
                    "status"=>false,
                    "statusCode"=>404,
                    "fld_total_page"=>0,
					"message"=>"",
					"next_page"=>0,
					"category_data"=>array()
				);
		}
	
		
		echo json_encode($res);
	}
	
	public function category_offer_listing(Request $request) {
				    
		$input = json_decode(file_get_contents('php://input'), true);
	
		$cat_id=@$input['fld_cat_id'];
		//$discount=2;//@$input['discount'];
		$this->site_base_path=Config::get('constants.Url.public_url');
		
		$record =DB::table('offer_categories')
						->select(
									'offer_categories.offer_name',
									'offer_categories.mobile_cat_id as categories_id',
									'offer_categories.offer_zone_type',
									'offer_categories.offer_discount',
									'offer_categories.offer_below_above',
									//DB::raw("CONCAT('".$this->site_base_path."uploads/advertise/',mobile_image) AS fld_offer_image")
									DB::raw("IF((offer_categories.mobile_image) IS NULL, '', CONCAT('".$this->site_base_path."uploads/advertise/',mobile_image)) as fld_offer_image")
								) 
				// 		->where('categories_id',$cat_id)
						->get();
		
		$Allrecord =count($record);
		
		$page=0;
	
		
		if($record){
			$statusCode=201;
			$message="Offer Listing";
			$category_offer_data=$record;
			$return_page=($page+1);
		}else{
			$statusCode=404;
			$message="No Offer Found";
			$category_offer_data=null;
			$return_page=$page;
		}
		
		$res=array(
                    "status"=>true,
                    "statusCode"=>$statusCode,
                    "fld_total_page"=>ceil(sizeof($Allrecord)/5),
					"message"=>$message,
					"next_page"=>$return_page,
					"category_offer_data"=>$category_offer_data
				);
		
		echo json_encode($res);
	}
	
	
	


}