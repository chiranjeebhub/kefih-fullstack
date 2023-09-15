<?php
namespace App\Exports;
use App\Products;
use App\Vendor;
use App\OrdersDetail;
use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class VendorPaymentExport implements FromView
{
		private $vendor_id;
			private $str;
				private $daterange;

public function __construct($vendor_id,$str,$daterange)
{
			$this->vendor_id = $vendor_id;
			$this->str = $str;
			$this->daterange = $daterange;
		
}
  public function view(): View
    {
                    $str=$this->str;
                    $daterange=$this->daterange;
        	$vendor_id=base64_decode($this->vendor_id);	
		$Payments =DB::table('tbl_vendor_payment')
					->select(
							'vendors.id',
							'vendors.public_name',
							'vendors.email',
							'vendors.phone',
							'tbl_vendor_payment.vendor_payment_mode',
							'tbl_vendor_payment.vendor_payment_amt',
							'tbl_vendor_payment.vendor_payment_date',
							'tbl_vendor_payment.vendor_payment_narration'
						)
					->join('vendors', 'vendors.id', '=', 'tbl_vendor_payment.vendor_id')
					->where('tbl_vendor_payment.vendor_id',$vendor_id);
					
			if($str!='' && $str!='All'){
					  $Payments=$Payments
							->Where(function($query) use ($str){
								 $query->orwhere('tbl_vendor_payment.vendor_payment_mode','LIKE',$str.'%');
								 
							 });
				} 
				if($daterange!='All' && $daterange!=''){
			
                    	$daterange_array=explode('.',$daterange);
                    	$from= date("Y-m-d", strtotime($daterange_array[0]));
                    	$to=date("Y-m-d", strtotime($daterange_array[1]));
                
				 			  	$from=DB::raw('STR_TO_DATE("'.$from.'", "%d-%m-%Y")');
					$to=DB::raw('STR_TO_DATE("'.$to.'", "%d-%m-%Y")');
					
					$Payments=$Payments
							 ->whereBetween('tbl_vendor_payment.vendor_payment_date',[$from,$to])
							 ->whereBetween(DB::raw('STR_TO_DATE(tbl_vendor_payment.vendor_payment_date, "%d-%m-%Y")'),[$from,$to]);
                } 
					
		$Payments=$Payments->orderBy('tbl_vendor_payment.fld_vendor_payment_id','desc')->get();
			$vendor_info=Vendor::where('id',$vendor_id)->first();
        return view('admin.exports.vendor_payment_export', [
            'payment_history' => $Payments,
            'vendor_info'=>$vendor_info
        ]);
    }
}