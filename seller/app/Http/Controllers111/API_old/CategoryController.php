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
use config;
class CategoryController extends Controller 
{
	public $successStatus = 200;
	public $site_base_path='http://aptechbangalore.com/test/';
	/** 
     * Category Listing api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
     
     public function getChilds($id){
          $cats =Category::
				select(
                'categories.id as fld_cat_id',
                'categories.name as fld_cat_name',
                'categories.cat_compare as fld_cat_compare',
                DB::raw("CONCAT('".$this->site_base_path."uploads/category/logo/',logo) AS image"),
                DB::raw("CONCAT('".$this->site_base_path."uploads/category/banner/',banner_image) AS banner_image")
				)
                    ->where('categories.isdeleted',0)
                    ->where('categories.status',1)
                    ->where('categories.parent_id',$id)
                    ->get()
                    ->toarray();
                    return $cats;
     }
     public function child_cat(Request $request){
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
    public function category_listing(Request $request) {
				    
		$input = json_decode(file_get_contents('php://input'), true);
				    
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
                    DB::raw("CONCAT('".$this->site_base_path."uploads/category/logo/',logo) AS image"),
                    DB::raw("CONCAT('".$this->site_base_path."uploads/category/banner/',banner_image) AS banner_image"),
                    DB::raw("(IF(categories.app_icon!='', 
                    CONCAT('".$this->site_base_path."uploads/logo/',categories.app_icon),
                    CONCAT('".$this->site_base_path."uploads/category/logo/','no_icon.png')
                    )) as fld_app_icon")
				)
						->where('categories.isdeleted',0)
						->where('categories.status',1)
						->where('categories.parent_id',$cat_id)
						->offset($fld_page_no)
                         ->limit(5)
                        ->get()
						->toarray();
						
            $Allrecord =Category::select(
                        'categories.id'
                        )
                ->where('categories.isdeleted',0)
                ->where('categories.parent_id',$cat_id)
                ->get()
                ->toarray();
		}else{
			$record =Category::	select(
                    'categories.id',
                    'categories.name',
                    'categories.cat_compare as fld_cat_compare',
                    DB::raw("CONCAT('".$this->site_base_path."uploads/category/logo/',logo) AS image"),
                    DB::raw("CONCAT('".$this->site_base_path."uploads/category/banner/',banner_image) AS banner_image"),
                    DB::raw("(IF(categories.app_icon!='', 
                    CONCAT('".$this->site_base_path."uploads/logo/',categories.app_icon),
                    CONCAT('".$this->site_base_path."uploads/category/logo/','no_icon.png')
                    )) as fld_app_icon")
			)
						->where('parent_id','=',1)
						->where('categories.isdeleted',0)
					    ->offset($fld_page_no)
                        ->limit(5)
                        ->get()
						->toarray();
						
              $Allrecord =Category::	select(
						'categories.id'
						)
					->where('parent_id','=',1)
					->where('categories.isdeleted',0)
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
	
	
	


}