<?php
namespace App\Exports;
use App\Orders;
use App\OrdersDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Auth;
class OrdervExport implements FromView
{
	
	private $str='';
	private $type='';
	private $daterange='';
	private $category_id='';
	private $brands='';

public function __construct($type,$str,$daterange,$category,$brands)
{
    $this->type = $type;
	$this->str = $str;
	$this->daterange = $daterange;
	$this->category_id = $category;
	$this->brands = $brands;
}
  public function view(): View
    {
        $userId = Auth::id();       
        $type = $this->type;
        $str =  $this->str;
        $daterange =  $this->daterange;
        $category_id = $this->category_id ;
        $brands	 =  $this->brands;
        
		$Orders=OrdersDetail::select(
                            'orders.order_no',
                            'orders.coupon_code',
                            'orders.order_date',
                                'orders.payment_mode as pmode',
                            'order_details.*',
                            'order_details.product_price as grand_total',
                           
                'order_details.product_price as details_price',
                'order_details.product_qty as  details_qty',
                'order_details.order_shipping_charges as  details_shipping_charges',
                'order_details.order_cod_charges as  details_cod_charges',
                'order_details.order_coupon_amount as  details_cpn_amt',
                'order_details.order_wallet_amount as  details_wlt_amt',

        'order_details.id as order_details_id',
        'order_details.suborder_no as suborder_no',
        'order_details.product_qty as qty',
         'products.default_image',
                'customers.name as cust_name',
           
                'orders_shipping.order_shipping_name as customer_name',
                'orders_shipping.order_shipping_address as customer_add',
                'orders_shipping.order_shipping_address1 as customer_add1',
                'orders_shipping.order_shipping_address2 as customer_add2',
                'orders_shipping.order_shipping_city as customer_city',
                'orders_shipping.order_shipping_state as customer_state',
                'orders_shipping.order_shipping_country as customer_country',
                'orders_shipping.order_shipping_zip as customer_zip',
                'orders_shipping.order_shipping_phone as customer_phone',
                'orders_shipping.order_shipping_email as customer_email'
               
						)
                ->join('orders', 'orders.id', '=', 'order_details.order_id')
                 ->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id')
                ->join('products', 'products.id', '=', 'order_details.product_id')
                ->join('product_categories','product_categories.product_id','=','products.id')
                ->join('categories','categories.id','=','product_categories.cat_id')
                  ->join('customers','orders.customer_id','=','customers.id')
                ->where('products.vendor_id',$userId);
						
				
				if($str!='All' && $str!=''){
				  $Orders=$Orders
						->where('orders.order_no','LIKE',$str.'%')
						->orWhere('order_details.suborder_no','LIKE',$str.'%');
				}
				
				if($brands!='All' &&  $brands!=''){
		    	$selcted_brand=explode(",",$brands);
		    
				   $Orders=$Orders->whereIn('products.product_brand',$selcted_brand);
		} 
		
			if($category_id!='All' && $category_id!=''){
		  	$Orders =$Orders->where('product_categories.cat_id',$category_id);
		} 
				 if($daterange!='All' && $daterange!=''){
                    	$daterange_array=explode('.',$daterange);
                    	$from= date("Y-m-d", strtotime($daterange_array[0]));
                    	$to=date("Y-m-d", strtotime($daterange_array[1]));
                   $Orders=$Orders
				 			 ->whereBetween('orders.order_date',[$from,$to]);
                }
		$Orders=$Orders->orderBy('order_details.id','desc')->groupBy('order_details.id')->where('order_details.order_status',$type)->get();
	
		
        return view('admin.exports.vorders', [
            'Orders' => $Orders
        ]);
    }
}