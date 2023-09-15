<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
class Payments extends Model
{
	
	
    protected $table = 'tbl_vendor_payment';
  
    protected $dates = ['created_at', 'updated_at'];

   
  
}
