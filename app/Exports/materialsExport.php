<?php
namespace App\Exports;
use App\Category;
use App\Materials;
use App\OrdersDetail;
use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class materialsExport implements FromView
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
		
			$Vendors =Materials::where('status','!=','0')
      ->where('isdeleted',0)->get(); 
        return view('admin.exports.materials_export', [
            'ledgers' => $Vendors
        ]);
    }
}