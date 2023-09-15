<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
use App\ProductImages;
use App\Brands;
use App\Vendor;
use App\ProductAttributes;
use App\ProductCategories;
use App\Category;
use DB;
class Zone extends Model
{
	
	
    protected $table = 'tbl_zone';
  
    protected $dates = ['created_at', 'updated_at'];
    
   
}
