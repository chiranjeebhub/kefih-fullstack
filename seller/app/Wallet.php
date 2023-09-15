<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
class Wallet extends Model
{
	
	
    protected $table = 'tbl_wallet_history';
  
    public static function wallet_history($customer_id){
		$data = Wallet::select('tbl_wallet_history.*')
					/*->join('order_details','order_details.id','tbl_wallet_history.fld_order_detail_id')*/
					->where('fld_customer_id', $customer_id)
					->orderBy('tbl_wallet_history.id','desc')->paginate(10);
		return $data;
	}

}
