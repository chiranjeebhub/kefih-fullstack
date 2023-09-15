<?php
namespace App\Exports;
use App\Products;
use App\OrdersDetail;
use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class LedgerExport implements FromView
{
		private $str;
		private $date_range;

public function __construct($str,$date_range)
{
			$this->str = $str;
			$this->date_range =$date_range;
}
  public function view(): View
    {
		/*
		$Vendors =OrdersDetail::select(
							'vendor_company_info.name as public_name',
							'vendors.id',
							'vendor_tax_info.gst_no',
							DB::raw('SUM(round((order_details.product_price*order_details.product_qty)*order_details.order_commission_rate)/100) AS total_commission_amt'),
							DB::raw('SUM(order_details.product_price*order_details.product_qty) AS total_product_amt'),
							DB::raw('SUM(order_details.order_detail_invoice_type) AS order_detail_invoice_type'),
							DB::raw('SUM(order_details.order_detail_tax_amt) AS order_detail_tax_amt')
						)
					->join('products', 'products.id', '=', 'order_details.product_id')
					//->join('product_categories', 'product_categories.product_id', '=', 'order_details.product_id')
					//->join('categories', 'categories.id', '=', 'product_categories.cat_id')
					->join('vendors', 'vendors.id', '=', 'products.vendor_id')
					->join('vendor_company_info', 'vendors.id', '=', 'vendor_company_info.vendor_id')
					->rightJoin('vendor_tax_info', function($join) {
					  $join->on('products.vendor_id', '=', 'vendor_tax_info.vendor_id');
					})
					->where('order_details.order_status', '=', '1');
			*/
			
			$Vendors =OrdersDetail::select(
				'vendors.public_name',
				'vendors.id',
				'vendor_tax_info.gst_no',
				DB::raw('SUM(round((order_details.product_price*order_details.product_qty)*order_details.order_commission_rate)/100) AS total_commission_amt'),
				DB::raw("SUM(order_details.tcs_amt) AS tcs_amt"),
				DB::raw("SUM(order_details.tds_amt) AS tds_amt"),	
				DB::raw("SUM(order_details.order_vendor_shipping_charges) AS total_order_shipping_charges"),	
				DB::raw('SUM(order_details.product_price*order_details.product_qty) AS total_product_amt'),
				DB::raw('SUM(order_details.order_detail_invoice_type) AS order_detail_invoice_type'),
				DB::raw('SUM(order_details.order_detail_tax_amt) AS order_detail_tax_amt')
			)
			->join('products', 'products.id', '=', 'order_details.product_id')			
			->join('vendors', 'vendors.id', '=', 'products.vendor_id')
			->rightJoin('vendor_tax_info', function($join) {
			$join->on('products.vendor_id', '=', 'vendor_tax_info.vendor_id');
			})
			->where('order_details.order_status', '=', '3');
			
		if($this->str!='' && $this->str!='All'){
			$str=$this->str;
					  $Vendors=$Vendors
							->Where(function($query) use ($str){
								 $query->orwhere('vendors.public_name','LIKE',$str.'%');
								 $query->orWhere('vendors.email','LIKE',$str.'%');
								 $query->orWhere('vendors.phone','LIKE',$str.'%');
								 $query->orWhere('vendors.username','LIKE',$str.'%');
							 });
				} 
				
				if($this->date_range!='All' && $this->date_range!=''){
					$daterange=$this->date_range;
					
					$daterange_array=explode('.',$daterange);
                    	$from= date("Y-m-d", strtotime($daterange_array[0]));
                    	$to=date("Y-m-d", strtotime($daterange_array[1]));
							 
							  $Vendors=$Vendors
							 ->whereBetween('order_details.order_date',[$from,$to]);
                }
					
		//$Vendors=$Vendors->groupBy('products.vendor_id')->orderBy('vendors.id','desc')->paginate(100); 
		$Vendors=$Vendors->groupBy('products.vendor_id')->orderBy('vendors.id','desc')->get(); 
        return view('admin.exports.vendor_ledger', [
            'ledgers' => $Vendors
        ]);
    }
}