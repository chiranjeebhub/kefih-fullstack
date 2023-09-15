<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
use DB;
use App\Products;
class ProductExtraGeneralDescription extends Model
{
	
	
    protected $table = 'product_extra_general';
  
	public static function getProductExtraGeneralDescription($prd_id){
      
		 $res = DB::table('product_extra_general')
						->select(
								"product_extra_general.product_general_descrip_title",
								"product_extra_general.product_general_descrip_content"
							)
							
						->where('product_id',$prd_id)
						->get();
		return $res;
	}
}
