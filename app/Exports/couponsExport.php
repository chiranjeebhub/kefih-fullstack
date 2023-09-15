<?php
namespace App\Exports;
use App\Coupon;
use App\OrdersDetail;
use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class couponsExport implements FromView
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
		
			$Vendors =Coupon::get(); 
        return view('admin.exports.coupons_export', [
            'ledgers' => $Vendors
        ]);
    }
}