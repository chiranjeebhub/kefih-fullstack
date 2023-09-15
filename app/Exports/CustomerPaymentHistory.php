<?php
namespace App\Exports;
use App\Customer;
use URL;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class CustomerPaymentHistory implements FromView
{

	private $dt;
public function __construct($str,$dt_rnge)
{
     $this->dt = $dt_rnge;
     $this->str = $str;
}
  public function view(): View
    {     $daterange=$this->dt;
        $customers=Customer::select(
						'customers.*','orders.order_no','orders.grand_total','orders.txn_id','orders.txn_status','orders.order_date','orders.payment_mode'
						);
		$customers=$customers
				->join('orders','customers.id','=','orders.customer_id')
				->whereRaw('orders.txn_id <> ""')
				->orderBy('orders.id','desc');
		if($daterange!='All' && $daterange!=''){
				     $export=URL::to('admin/customers_pay_export_dt').'/'.$daterange;
                    	$daterange_array=explode('.',$daterange);
                    	$from= date("Y-m-d", strtotime($daterange_array[0]));
                    	$to=date("Y-m-d", strtotime($daterange_array[1]));
                   $customers=$customers
				 			 ->whereBetween('orders.order_date',[$from,$to]);
                }
		$customers=$customers->get();
	
        return view('admin.exports.customer_payment_history', [
            'customers' => $customers
        ]);
    }
}