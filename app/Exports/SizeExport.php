<?php
namespace App\Exports;
use App\Sizes;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class SizeExport implements FromView
{
	private $str;
		private $sts;

public function __construct($str,$sts)
{
$this->str=$str;
$this->sts=$sts;
}
  public function view(): View
    {
			$parameters=$this->str;
			$status=$this->sts;
	$Sizes= Sizes::where('isdeleted', 0);
		
	if($parameters!=''){
		  $Sizes=$Sizes
				->where('sizes.name','LIKE',$parameters.'%');
		} 
		
		if($status!=''){
		  $Sizes=$Sizes
				->where('sizes.status','=',$status);
		} 
		
		
        $Sizes =$Sizes->orderBy('id', 'DESC')->get();
        return view('admin.exports.size_export', [
            'Sizes' => $Sizes
        ]);
    }
}