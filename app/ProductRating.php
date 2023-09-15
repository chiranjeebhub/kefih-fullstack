<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
use App\ProductImages;
use DB;
class ProductRating extends Model
{
	
	
    protected $table = 'product_rating';
  public function products()
{
    return $this->belongsTo("App\Products");
}
    

}
