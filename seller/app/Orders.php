<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
class Orders extends Model
{
	
	
    protected $table = 'orders';

	 public function ordersDetail()
    {
       return $this->hasMany(OrdersDetail::class);
    }
}
