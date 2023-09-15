<?php
namespace App\Exports;
use App\Brands;
use Auth;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class BrandExport implements FromView
{
		private $str;

public function __construct()
{

}
  public function view(): View
    {
		
		 $Brands= Brands::where('isdeleted', 0);
		
// 		if($parameters=='all')
// 		{
// 			$parameters='';
// 		}
		
// 		if($parameters!=''){
// 		  $Categories=$Categories
// 				->where('categories.name','LIKE',$parameters.'%');
// 		} 
		
// 		if($status!=''){
// 		  $Categories=$Categories
// 				->where('categories.status','=',$status);
// 		} 
		 if(Auth::guard('vendor')->check()){
          	$Brands=$Brands->orderBy('id', 'DESC')->where('brands.vendor_id','=',auth()->guard('vendor')->user()->id)->get();
        } else{
           $Brands=$Brands->orderBy('id', 'DESC')->get();
        }
			
        return view('admin.exports.brand_export', [
            'Brands' => $Brands
        ]);
    }
}