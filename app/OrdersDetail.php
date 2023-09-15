<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
class OrdersDetail extends Model
{
	
	
    protected $table = 'order_details';		 protected $dates = ['created_at', 'updated_at'];
	 public function orders()
    {
        return $this->belongsTo(Orders::class);
    }
    
}
