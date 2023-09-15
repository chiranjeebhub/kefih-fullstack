<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
use DB;
class ProductAttributes extends Model
{
	
	
    protected $table = 'product_attributes';
  
	public static function updateSkuAttributes($prd_id,$color,$size,$gtin,$attr_id,$vdr_id){
		if($color!=0&& $size!=0){
			 $prd_data=Products::where('id', '=',$prd_id)->first();
			 $cat_name='';
			  $brand_name='';
			  $vendor_name='';
		  $brand_data=Brands::where('id', '=',$prd_data->product_brand)->first();
		  $Vendor_data=Vendor::where('id', '=',$vdr_id)->first();
		  
			  $res=ProductCategories::select('cat_id')->where('product_id',$prd_id)->get()->toarray();
			  $cat_name='';
		  foreach($res as $row){
			  $cat_data=Category::where('id',	$row['cat_id'])->first();
		  $cat_name.=substr($cat_data->name, 0, 3);
		  }
		 
		 if($brand_data){
		   $brand_name= substr($brand_data->name, 0, 3); 
		 }
		  if($Vendor_data){
			 $vendor_name=substr($Vendor_data->username, 0, 3);
		 }
		 
			  $color_name=substr(Products::getAttrName('Colors',$color), 0, 3);
			  $size_name=substr(Products::getAttrName('Sizes',$size), 0, 3);
			   $master=$prd_id.$vendor_name.$brand_name.$cat_name;
			   $sku=$vendor_name.$brand_name.$cat_name.$master;
			   
				   Products::where('id',$prd_id)->update(array('sku'=>$master));
			  $res=ProductAttributes::where('id', '=',$attr_id)
		  ->update(
		  [
		  'sku' => strtolower($sku),
		  'gtin'=>$gtin
		  ]);
		}
	}
	
  
  function rewardsPoints($id){
      $data=DB::table('product_reward_points')
            ->where('product_id',$id)
            ->first();
      
      return $data;
  }
  
   public static function getProductQty($prd_id,$size_id,$color_id){
      $data=DB::table('product_attributes')
                ->select('qty')
                ->where('product_id',$prd_id)
                ->where('size_id',$size_id)
                ->where('color_id',$color_id)
                ->first();
      
      return $data;
  }
  
   function updateStocks($obj,$input){
        
        DB::table('product_attributes_log')->insert(array(
					'product_id'=>$obj->id,
                    'size_id'=> 0,
                    'color_id'=>0,
                    'qty'=> $input['qty'],
                    'price'=> 0,
				));
        
               ProductAttributes::where('product_id',$obj->id)->delete();
				$res=ProductAttributes::insert(array(
					'product_id'=>$obj->id,
                    'size_id'=> 0,
                    'color_id'=>0,
                    'qty'=> $input['qty'],
                    'price'=> 0,
				));
				Products::where('id',$obj->id)->update(array('qty'=>$input['qty']));
				return $res;
           
  }
  
  
  function getCategoryFilter($cats){
      $data=DB::table('filters_category')
      ->select(
            'filters.id',
            'filters.name',
            'filters.input_type'
          )
      ->join('filters','filters_category.filter_id','filters.id')
      ->whereIn('cat_id',$cats)
      ->groupBy('filters.id')
      ->get();
      
      return $data;
  }
	function updateAttributes($arr,$prd_id){
	    $prd_data=Products::select('sku')->where('id', '=',$prd_id)->first();
	  
	    
		 ProductAttributes::where('product_id',$prd_id)->delete();
			if (array_key_exists("atr_color",$arr))
		{    
			$i=0;
				$data=array();
				$total_qty=0;
			foreach($arr['atr_color'] as $row){
			    
			     $color_name=substr(Products::getAttrName('Colors',$arr['atr_color'][$i]), 0, 3);
                $size_name=substr(Products::getAttrName('Sizes',$arr['atr_size'][$i]), 0, 3);
			    $sku=$prd_data->sku.$color_name.$size_name;
			    
            if(@$arr['unisex_type'][$i]!='')
			{				
				array_push($data,array(
						'product_id'=>$prd_id,
						'unisex_type'=>$arr['unisex_type'][$i],
						'size_id'=> intval($arr['atr_size'][$i]),
						'women_size_id'=> intval($arr['atr_size'][$i]),
						'color_id'=>intval($arr['atr_color'][$i]),
						'barcode'=>$arr['barcode'][$i],
						'qty'=> intval($arr['atr_qty'][$i]),
						'sku'=>$arr['atr_sku'][$i],
						'price'=> 0,
					));
			}else{
				array_push($data,array(
						'product_id'=>$prd_id,
						'unisex_type'=>0,
						'size_id'=> intval($arr['atr_size'][$i]),
						 'women_size_id'=> 0,
						'color_id'=>intval($arr['atr_color'][$i]),
						'barcode'=>$arr['barcode'][$i],
						'qty'=> intval($arr['atr_qty'][$i]),
						'sku'=>$arr['atr_sku'][$i],
						'price'=> intval($arr['atr_price'][$i]),
					));
			}
				$total_qty+=intval($arr['atr_qty'][$i]);
			$i++;
			}
				$res=ProductAttributes::insert($data);
				
				 DB::table('product_attributes_log')->insert($data);
				
					Products::where('id',$prd_id)->update(array('qty'=>$total_qty));
				return $res;
		} else{
			return 1;
		}
	}
	function getProductSize($id){
		   
		   $res = ProductAttributes::selectRaw('size_id')
                      ->groupBy('size_id')
                     ->get()
					 ->toarray();
		   return $res;
	}
	
	function getProductAttributes($id){
		   
		   $res = ProductAttributes::select('unisex_type','size_id','color_id','qty','price','barcode','sku')
		            ->where('product_id',$id)
                     ->get()
					 ->toarray();
		   return $res;
	}
	function getColor_ProductAttributes($id){
		   
		   $res = ProductAttributes::select('color_id','sku')
                        ->where('product_id',$id)
                        ->groupBy('color_id')
                        ->orderBy('id','asc')
                        ->get()
                        ->toarray();
		   return $res;
	}
	
}
