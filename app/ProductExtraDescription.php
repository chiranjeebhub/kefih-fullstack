<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
use DB;
use App\Products;
class ProductExtraDescription extends Model
{
	
	
    protected $table = 'product_extra_description';
  
	public static function getProductExtraDescription($prd_id){
      
		 $res = DB::table('product_extra_description')
						->select(
								"product_extra_description.product_descrip_title",
								"product_extra_description.product_descrip_content",
								"product_extra_description.product_descrip_image"
							)
							
						->where('product_id',$prd_id)
						->get();
		return $res;
	}
}
