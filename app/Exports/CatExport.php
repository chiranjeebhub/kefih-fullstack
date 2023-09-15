<?php
namespace App\Exports;
use App\Category;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class CatExport implements FromView
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
	$Categories= Category::where('isdeleted', 0)->where('parent_id', '!=',0);
		
	
		
		if($parameters!='' && $parameters!='all'){
		  $Categories=$Categories
				->where('categories.name','LIKE',$parameters.'%');
		} 
		
		if($status!=''){
		  $Categories=$Categories
				->where('categories.status','=',$status);
		} 
		
		//$Categories=$Categories->orderBy('id', 'DESC')->paginate(100);
		$Categories=$Categories->orderBy('id', 'DESC')->get();
        return view('admin.exports.cat_export', [
            'Categories' => $Categories
        ]);
    }
}