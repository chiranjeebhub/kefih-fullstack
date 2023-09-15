<?php
namespace App\Exports;
use App\Orders;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class OrderExport implements FromView
{
	
	private $str;
	private $type;

public function __construct($str,$type)
{
    $this->type = $type;
	$this->str = $str;
}
  public function view(): View
    {
		
		 $Orders =Orders::select(
						'orders.order_no',
						'orders.tax_percent',
						'orders.discount_amount',
						    'orders.payment_mode as pmode',
						'orders.coupon_code',
						'orders.coupon_percent',
						'orders_shipping.order_shipping_zip'
						)
					->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id');
					
					if($this->str!=''){
				  $Orders=$Orders
						->where('orders.order_no','LIKE',$this->str.'%')
						->orWhere('orders.order_date','LIKE',$this->str.'%')
						->orWhere('orders_shipping.customer_name','LIKE',$this->str.'%')
						->orWhere('orders_shipping.customer_phone','LIKE',$this->str.'%')
						->orWhere('orders_shipping.customer_email','LIKE',$this->str.'%');
				} 
					$Orders=$Orders->where('orders.order_status',$this->type)->get();
        return view('admin.exports.orders', [
            'Orders' => $Orders
        ]);
    }
}