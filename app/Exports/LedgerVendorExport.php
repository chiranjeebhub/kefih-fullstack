<?php
namespace App\Exports;
use App\Products;
use App\Vendor;
use App\OrdersDetail;
use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class LedgerVendorExport implements FromView
{
		private $vendor_id;
		private $date_range;

public function __construct($vendor_id,$date_range)
{
			$this->vendor_id = $vendor_id;
			$this->date_range = $date_range;
		
}
  public function view(): View
    {
        $daterange=$this->date_range;
        
		$Vendors =OrdersDetail::select(
							'vendors.id',
							'vendors.public_name',
							'vendors.email',
							'vendors.phone',
							'products.sku',
							'order_details.product_name',
							'order_details.product_qty',
							'order_details.product_price',
							'order_details.size',
							'order_details.color',
							'order_details.order_commission_rate',
							'order_details.order_detail_invoice_type',
							'order_details.order_detail_tax_amt',
							'order_details.order_date'
						)
					->join('products', 'products.id', '=', 'order_details.product_id')
					->join('vendors', 'vendors.id', '=', 'products.vendor_id')
					->where('vendors.id',base64_decode($this->vendor_id))
					->where('order_details.order_status', '=', '3');
					
				if($daterange!='All' && $daterange!=''){
				
                    	$daterange_array=explode('.',$daterange);
                    	$from= date("Y-m-d", strtotime($daterange_array[0]));
                    	$to=date("Y-m-d", strtotime($daterange_array[1]));
                   $Vendors=$Vendors
				 			 ->whereBetween('order_details.order_date',[$from,$to]);
                }
				
					
		/*$Vendors=$Vendors->groupBy('products.vendor_id')->orderBy('vendors.id','desc')->get(); */
		$Vendors=$Vendors->orderBy('order_details.id','desc')->get();
			$vendor_info=Vendor::where('id',base64_decode($this->vendor_id))->first();
		
        return view('admin.exports.vendor_wise_ledger', [
            'ledger_details' => $Vendors,
            'vendor_info'=>$vendor_info
        ]);
    }
}