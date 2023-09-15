<?php
namespace App\Exports;
use App\Orders;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class SubOrderExport implements FromView
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
						'orders.coupon_code',
						'orders.coupon_percent',
						'orders.order_date',
						'orders_shipping.order_shipping_zip',
						'order_details.suborder_no',
						'order_details.order_detail_invoice_num',
						'order_details.order_detail_invoice_date',
						'order_details.product_name',
						'order_details.product_qty',
						'order_details.product_price',
						'order_details.product_price_old',
						'order_details.size',
						'order_details.color'
						)
					->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id')
					->join('order_details', 'orders.id', '=', 'order_details.order_id');
					
					if($this->str!=''){
				  $Orders=$Orders
						->where('orders.order_no','LIKE',$this->str.'%')
						->orWhere('orders.order_date','LIKE',$this->str.'%')
						->orWhere('orders_shipping.order_shipping_name','LIKE',$this->str.'%')
						->orWhere('orders_shipping.order_shipping_phone','LIKE',$this->str.'%')
						->orWhere('orders_shipping.order_shipping_email','LIKE',$this->str.'%');
				} 
					$Orders=$Orders->where('orders.order_status',$this->type)->get();
        return view('admin.exports.suborders', [
            'Orders' => $Orders
        ]);
    }
}