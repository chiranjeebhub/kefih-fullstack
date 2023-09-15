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
		
		$vendors= Vendor::select('f_name','l_name','username','public_name','email','phone','created_at')->where('isdeleted', 0)->where('user_role','!=', 0);
		
		if($this->str!=''){
		    $parameters=$this->str;
						
						 $vendors=$vendors
						->where('vendors.username','LIKE',$parameters.'%')
						->orwhere(function($query) use($parameters){
                        $query->orWhere('vendors.public_name','LIKE',$parameters.'%');
                        $query->orWhere('vendors.email','LIKE',$parameters.'%');
                        $query->orWhere('vendors.phone','LIKE',$parameters.'%');
                        
                        });
				} 
		$vendors=$vendors->where('isdeleted',0)->orderBy('id', 'DESC')->get();
        return view('admin.exports.vendors', [
            'Vendors' => $vendors
        ]);
    }
}