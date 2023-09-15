<?php
namespace App\Exports;
use App\Vendor;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class VendorExport implements FromView
{
	private $str;

public function __construct($str)
{
	$this->str = $str;
}
  public function view(): View
    {
		
		$vendors= Vendor::select('f_name','l_name','username','public_name','email')->where('isdeleted', 0)->where('user_role','!=', 0);
		
		if($this->str!=''){
				  $vendors=$vendors
						->where('vendors.username','LIKE',$this->str.'%')
						->orWhere('vendors.public_name','LIKE',$this->str.'%')
						->orWhere('vendors.email','LIKE',$this->str.'%')
						->orWhere('vendors.phone','LIKE',$this->str.'%');
				} 
		$vendors=$vendors->orderBy('id', 'DESC')->get();
        return view('admin.exports.vendors', [
            'Vendors' => $vendors
        ]);
    }
}