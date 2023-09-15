<?php
namespace App\Exports;
use App\Orders;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;
class ReportExport implements FromView
{
	
    private $daterange;
    private $cat;
    private $type;

public function __construct($type,$daterange,$cat)
{
        $this->type = $type;
        $this->daterange = $daterange;
        $this->cat = $cat;
}
  public function view(): View
    {
        
       
        if(@$this->type==0){
		$products=Orders::
               select('products.id','products.vendor_id','products.name',
               'order_details.product_name as product_name',
                        'order_details.size as pr_size',
                        'order_details.color as pr_color',
               'products.default_image','products.spcl_price','products.sku','order_details.order_shipping_charges',
                DB::raw("count(*) AS totalorders, SUM(order_details.product_qty) AS `total_sales`, SUM(order_details.product_price) AS `total_price`")
                )
            ->join('order_details','order_details.order_id','orders.id')
            ->join('products','products.id','order_details.product_id');
             if($this->daterange!='All' && $this->daterange!=''){
                    	$daterange_array=explode('.',$this->daterange);
                    	$from= date("Y-m-d", strtotime($daterange_array[0]));
                    	$to=date("Y-m-d", strtotime($daterange_array[1]));
       
                   $products=$products
				 			 ->whereBetween('order_details.order_date',[$from,$to]);
                }
            $products=$products->where('order_details.order_status','3')->groupBy('products.id')->orderBy('total_sales','desc')->paginate(10); 	
		}
       
        if(@$this->type==1){
		$products=Orders::
               select('products.id','products.vendor_id','products.name as pname',
               'order_details.product_name as product_name',
                        'order_details.size as pr_size',
                        'order_details.color as pr_color',
               'products.default_image','products.spcl_price','products.sku','order_details.order_shipping_charges','orders_shipping.order_shipping_zip','orders_shipping.order_shipping_city',
                DB::raw("count(*) AS totalorders, SUM(order_details.product_qty) AS `total_sales`, SUM(order_details.product_price) AS `total_price`")
                )
            ->join('order_details','order_details.order_id','orders.id')
            ->join('products','products.id','order_details.product_id')
            ->join('orders_shipping','orders_shipping.order_id','order_details.order_id');
            if($this->daterange!='All' && $this->daterange!=''){
                    	$daterange_array=explode('.',$this->daterange);
                    	$from= date("Y-m-d", strtotime($daterange_array[0]));
                    	$to=date("Y-m-d", strtotime($daterange_array[1]));
       
                   $products=$products
				 			 ->whereBetween('order_details.order_date',[$from,$to]);
                }
            $products=$products->where('order_details.order_status','3')->groupBy('products.id')->orderBy('total_sales','desc')->paginate(200);  	
		} 
       
        if(@$this->type==2){
		$products=Orders::
               select('products.id','products.vendor_id','products.name as pname',
                        'order_details.product_name as product_name',
                        'order_details.size as pr_size',
                        'order_details.color as pr_color',
               'products.default_image','products.spcl_price','products.sku','order_details.order_shipping_charges','orders_shipping.order_shipping_zip','orders_shipping.order_shipping_city',
                DB::raw("count(*) AS totalorders, SUM(order_details.product_qty) AS `total_sales`, SUM(order_details.product_price) AS `total_price`")
                )
            ->join('order_details','order_details.order_id','orders.id')
            ->join('products','products.id','order_details.product_id')
            ->join('orders_shipping','orders_shipping.order_id','order_details.order_id');
            if($this->daterange!='All' && $this->daterange!=''){
                    	$daterange_array=explode('.',$this->daterange);
                    	$from= date("Y-m-d", strtotime($daterange_array[0]));
                    	$to=date("Y-m-d", strtotime($daterange_array[1]));
       
                   $products=$products
				 			 ->whereBetween('order_details.order_date',[$from,$to]);
                }
            
            $products=$products->where('order_details.order_status','5')->groupBy('orders_shipping.order_shipping_zip')->orderBy('total_price','desc')->paginate(200);  	
		} 
		
		$repo_type = array("0", "1", "2");
		if (in_array($this->type, $repo_type))
		{

            
		 return view('admin.exports.reports', [
                'Records' => $products,
                'type'=>$this->type
        ]);
		}
		
		if(@$this->type==3){
		$products=Orders::
               select('vendors.id','vendors.username','order_details.order_shipping_charges','products.name as pname',
                DB::raw("count(*) AS totalorders, SUM(order_details.product_qty) AS `total_sales`, SUM(order_details.product_price) AS `total_price`")
                )
            ->join('order_details','order_details.order_id','orders.id')
			->join('products','products.id','order_details.product_id')
			->join('vendors','vendors.id','products.vendor_id');
			
                if($this->cat!=0){
                $products =$products
                ->join('product_categories','product_categories.product_id','=','products.id')
                ->join('categories','categories.id','=','product_categories.cat_id');
                $products =$products->where('product_categories.cat_id',$this->cat);
                }
            if($this->daterange!='All' && $this->daterange!=''){
                    	$daterange_array=explode('.',$this->daterange);
                    	$from= date("Y-m-d", strtotime($daterange_array[0]));
                    	$to=date("Y-m-d", strtotime($daterange_array[1]));
       
                   $products=$products
				 			 ->whereBetween('order_details.order_date',[$from,$to]);
                }
            $products=$products->where('order_details.order_status','3')->groupBy('vendors.id')->orderBy('total_sales','desc')->paginate(200); 	
          
	
        return view('admin.exports.reports', [
            'Records' => $products,
            'type'=>$this->type
        ]);
		} 
		
    }
}