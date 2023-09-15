<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
use DB;
use App\Orders;
class OrdersShipping extends Model
{
	
	
    protected $table = 'orders_shipping';
	public static function orderDocket($order_id){
       if($order_id!=''){
           $data=OrdersShipping::select('orders_shipping.*','logistic_partner.logistic_link')
		   ->join('logistic_partner','logistic_partner.name','=','orders_shipping.courier_name')
		   ->where('order_id',$order_id)->first();
           
           return $data;  
       }
	   
	}
	
	public static function orderDetailDocket($order_detail_id){
       if($order_detail_id!=''){
           $data=DB::table('orders_courier')
		   ->select('orders_courier.*','logistic_partner.name as fld_courier_name,logistic_partner.logistic_link')
		   ->join('logistic_partner','logistic_partner.id','=','orders_courier.courier_name')
		   ->where('order_detail_id',$order_detail_id)->first();
           
           return $data;
       }
	   
	}
	
	public static function orderShippingInfo($ship_id){
       if($ship_id!=''){
           $data=OrdersShipping::select('orders_shipping.*')
		   ->where('id',$ship_id)->first();
           
           return $data;
       }
	   
	}
    
}
