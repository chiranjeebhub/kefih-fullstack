<?php
namespace App\Exports;
use App\Customer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class Customers_export implements FromView
{
		private $search;
			private $otp;
				private $sts;

public function __construct($search,$otp,$sts)
{
	$this->search = $search;
	$this->otp = $otp;
	$this->sts = $sts;
}
  public function view(): View
    {
		
		$Customers= Customer::where('isdeleted', 0);
		

            if($this->otp!='' && $this->otp!='all'){
            $Customers=$Customers
            	->where('customers.isOtpVerified','=',$this->otp);
            }
            
            if($this->sts!='' && $this->sts!='all'){
            $Customers=$Customers
            	->where('customers.status','=',$this->sts);
            }
            
            if($this->search!='' && $this->search!='all'){
            $Customers=$Customers
				->where('customers.name','LIKE',$this->search.'%')
				->orWhere('customers.email','LIKE',$this->search.'%')
				->orWhere('customers.phone','LIKE',$this->search.'%');
		
            }
		$Customers=$Customers->orderBy('id', 'DESC')->get();
        return view('admin.exports.customers', [
            'Customers' => $Customers
        ]);
    }
}