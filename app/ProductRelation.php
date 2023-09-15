<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
use App\Products;
class ProductRelation extends Model
{
	
	
    protected $table = 'products_relation';
  
	function updateRelation($arr,$prd_id,$type){
		      ProductRelation::where('product_id',$prd_id)->where('relation_type',$type)->delete();
						if (array_key_exists("is_related_product_shown",$arr))
						{    
							$res=Products::where('id', '=',$prd_id)
								->update(
								[
								'is_related_product_shown' => $arr['is_related_product_shown']
								]);
						}
						
						if (array_key_exists("is_cross_sell_product_shown",$arr))
						{    
							$res=Products::where('id', '=',$prd_id)
								->update(
								[
								'is_cross_sell_product_shown' => $arr['is_cross_sell_product_shown']
								]);
						}
						
						if (array_key_exists("is_up_sell_product_shown",$arr))
						{    
							$res=Products::where('id', '=',$prd_id)
								->update(
								[
								'is_up_sell_product_shown' => $arr['is_up_sell_product_shown']
								]);
						}
			 
		
			if (array_key_exists("related_product_id",$arr))
		{    
				$data=array();
				
			foreach($arr['related_product_id'] as $row){
				array_push($data,array('product_id'=>$prd_id, 'relative_product_id'=> $row,'relation_type'=>$type));
			}
			
			$res=ProductRelation::insert($data);
			return $res;
		} else{
			
			return 1;
		}
		
	}
	
	
	function getRelativeProduct($prd_id,$type,$arr){
		$res=ProductRelation::select('products_relation.relative_product_id')
								->join('products', 'products.id', '=', 'products_relation.relative_product_id')
								->where('products_relation.product_id',$prd_id)
								->where('products_relation.relation_type',$type)
								->where('products.name','LIKE',$arr['SearchByName'].'%');
								if($arr['SearchByVisibility']!=''){
										$res=$res->where('products.visibility',$arr['SearchByVisibility']);
								}
								if($arr['SearchByStatus']!=''){
										$res=$res->where('products.prd_sts',$arr['SearchByStatus']);
								}
								$res=$res->get()
								->toarray();
			$data = array();
		foreach($res as $row){
			$data[]=$row['relative_product_id'];
		}
		return $data;
		
	}
	
}
