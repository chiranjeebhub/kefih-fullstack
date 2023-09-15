<?php
namespace App\Exports;
use App\Materials;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class MaterialExport implements FromView
{

public function __construct()
{

}
  public function view(): View
    {
	$Materials= Materials::where('isdeleted', 0);
		
// 		if($parameters=='all')
// 		{
// 			$parameters='';
// 		}
// 		if($status=='all')
// 		{
// 			$status='';
// 		}
		
// 		if($parameters!=''){
// 		  $Materials=$Materials
// 				->where('materials.name','LIKE',$parameters.'%');
// 		} 
// 		if($status!=''){
// 		  $Materials=$Materials
// 				->where('materials.status','=',$status);
// 		} 
		
	//	$Materials=$Materials->orderBy('id', 'DESC')->paginate(100);
		$Materials=$Materials->orderBy('id', 'DESC')->get();
        return view('admin.exports.materail_export', [
            'Materials' => $Materials
        ]);
    }
}