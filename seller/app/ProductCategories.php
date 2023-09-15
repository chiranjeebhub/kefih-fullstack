<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
use App\Products;
use DB;
class ProductCategories extends Model
{
	
	
    protected $table = 'product_categories';
  
	function updateCategories($arr,$prd_id){
		
			if (array_key_exists("cat",$arr))
		{    
				$data=array();
			ProductCategories::where('product_id',$prd_id)->delete();
			foreach($arr['cat'] as $row){
				array_push($data,array('product_id'=>$prd_id, 'cat_id'=> $row));
			}
			$res=ProductCategories::insert($data);
			return $res;
		} else{
			ProductCategories::where('product_id',$prd_id)->delete();
			return 1;
		}
		
	}
	
	function getCategories($prd_id){
		
		$res=ProductCategories::select('cat_id')->where('product_id',$prd_id)->get()->toarray();
			$data = array();
		foreach($res as $row){
			$data[]=$row['cat_id'];
		}
		return $data;
	}
	public static function isProductCategoryCompareable($prd_id){
	    $res=ProductCategories::select('cat_id')
	    ->join('categories','product_categories.cat_id','categories.id')
	    ->where('product_categories.product_id',$prd_id)
	     ->where('categories.cat_compare',1)
	    ->count();
	    $html='';
	    if($res>0){
	       
	          $html='<li><a href="javascript:void(0)" 
                data-tip="Compare" 
                title="Compare">
                <i class="material-icons compareProduct" 
                prd_id="'.$prd_id.'"
                >&#xe915;</i></a></li>'; 
	    } else{
               $html="";   
	    }
	    return $html;
	}
	
	public static function getProductcategoryName($prd_id){
		$res=ProductCategories::select('cat_id')->where('product_id',$prd_id)->get()->toarray();
			$data = array();
		foreach($res as $row){
		    $res=DB::table('categories')->select('name')->where('id',$row['cat_id'])->first();
	
			$data[]=@$res->name;
		}
		$string_version = implode(',', $data);
		return $string_version;
	}
	

	
}
