<?php
namespace App\Exports;
use App\Colors;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class ColorExport implements FromView
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
	$Colors= Colors::where('isdeleted', 0);
		
	if($parameters!=''){
		  $Colors=$Colors
				->where('colors.name','LIKE',$parameters.'%');
		} 
		
		if($status!=''){
		  $Colors=$Colors
				->where('colors.status','=',$status);
		} 
		
        $Colors =$Colors->orderBy('id', 'DESC')->get();
        return view('admin.exports.color_export', [
            'Colors' => $Colors
        ]);
    }
}