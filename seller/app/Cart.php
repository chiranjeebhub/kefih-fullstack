<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
use DB;
use App\ProductCategories;
class Cart extends Model
{
	
	
    protected $table = 'cart';
  
    protected $dates = ['added_at', 'updated_at'];
}
