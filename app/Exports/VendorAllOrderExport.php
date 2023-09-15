<?php
namespace App\Exports;
use App\Orders;
use App\OrdersDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class VendorAllOrderExport implements FromView
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
              
		
		$Orders=OrdersDetail::select(
                            'orders.order_no',
                            'orders.order_date',
                            'orders.payment_mode',
                            'order_details.*',
                            'products.default_image'
						)
                ->join('orders', 'orders.id', '=', 'order_details.order_id')
                ->join('products', 'products.id', '=', 'order_details.product_id')
                ->join('product_categories','product_categories.product_id','=','products.id')
                ->join('categories','categories.id','=','product_categories.cat_id')
                ->where('products.vendor_id',auth()->guard('vendor')->user()->id)
                ;
						
				
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
        //             	echo $from." ".$to;
                   $Orders=$Orders
				 			 ->whereBetween('orders.order_date',[$from,$to]);
                }
			$Orders=$Orders->orderBy('order_details.id','desc')->groupBy('order_details.id')->get();
        return view('admin.exports.all_vendor_order', [
            'orders' => $Orders
        ]);
    }
}