<?php
namespace App\Exports;
use App\Products;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class ProductExport implements FromView
{
		private $str;

public function __construct($str)
{
	$this->str = $str;
}
  public function view(): View
    {
		
		$Products= Products::where('isdeleted', 0);
		
		if($this->str!=''){
				  $Products=$Products
						->where('products.name','LIKE',$this->str.'%');
				} 
		$products=$Products->orderBy('id', 'DESC')->get();
        return view('admin.exports.products', [
            'Products' => $products
        ]);
    }
}