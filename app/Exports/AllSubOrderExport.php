<?php
namespace App\Exports;
use App\Orders;
use App\OrdersDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class AllSubOrderExport implements FromView
{
	
               
                private $str;
                private $daterange;
                private $vendor;
                private $brand;
                private $category;

public function __construct($str,$daterange,$vendor,$brand,$category)
{
 
	$this->str = $str;
	$this->daterange = $daterange;
	$this->vendor = $vendor;
	$this->brand = $brand;
	$this->category = $category;
}
  public function view(): View
    {
                $str=$this->str;
                $brands=$this->str;
                $category_id=$this->str;
                $daterange=$this->str;
                
                $vendor=$this->vendor;
		
		$Orders=OrdersDetail::select(
						'orders.id as fld_master_order_id',
						'orders.order_no',
						'orders.payment_mode',
						'orders.order_date',
						'orders.shipping_id',
						'order_details.*',
						'products.default_image'
						)
						->join('orders', 'orders.id', '=', 'order_details.order_id')
						->join('products', 'products.id', '=', 'order_details.product_id')
                        ->join('product_categories','product_categories.product_id','=','products.id')
                        ->join('categories','categories.id','=','product_categories.cat_id');
						
						
					if($vendor!=0){
				  $Orders=$Orders->where('products.vendor_id',$vendor);
						
				}
				if($brands!='All' &&  $brands!=''){
		    	$selcted_brand=explode(",",$brands);
		    
				   $Orders=$Orders->whereIn('products.product_brand',$selcted_brand);
		} 
		
			if($category_id!='All' && $category_id!=''){
		  	$Orders =$Orders->where('product_categories.cat_id',$category_id);
		} 
		
				if($str!='All' && $str!=''){
				  $Orders=$Orders
						->where('orders.order_no','LIKE',$str.'%')
						->orWhere('order_details.suborder_no','LIKE',$str.'%');
				}

			
				
				 if($daterange!='All' && $daterange!=''){
                    	$daterange_array=explode('.',$daterange);
                    	$from= date("Y-m-d", strtotime($daterange_array[0]));
                    	$to=date("Y-m-d", strtotime($daterange_array[1]));
                   $Orders=$Orders
				 			 ->whereBetween('orders.order_date',[$from,$to]);
                }
                
				$Orders=$Orders->groupBy('order_details.id')->orderBy('order_details.id','desc')->get();
        return view('admin.exports.all_suborder', [
                'orders' => $Orders
        ]);
    }
}