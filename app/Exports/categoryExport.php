<?php
namespace App\Exports;
use App\Category;
use App\OrdersDetail;
use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class categoryExport implements FromView
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
		
			$Vendors =Category::where('status','!=','0')
      ->where('isdeleted',0)
      ->where('parent_id', '!=','0')->get(); 
        return view('admin.exports.category_export', [
            'ledgers' => $Vendors
        ]);
    }
}