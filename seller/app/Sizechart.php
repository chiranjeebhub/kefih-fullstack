<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
use DB;
use URL;
class Sizechart extends Model
{
	
	
    protected $table = 'sizechart';
  
    //protected $dates = ['created_at', 'updated_at'];
    protected $fillable = ['vendor_id','category_id','sizechart','updated_at','created_at'];

      
}
