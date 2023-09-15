<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Products;
use App\ProductImages;
use App\ProductCategories;
use App\ProductRelation;
use App\Brands;
use App\Colors;
use App\Sizes;
use App\ProductAttributes;
use Redirect;
use Validator;
use DB;
use Config;
use App\Helpers\CommonHelper;
use App\Helpers\MsgHelper;
use App\Helpers\FIleUploadingHelper;
use App\Helpers\CustomFormHelper;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExport;
use URL;
class CommonController extends Controller 
{
   	 public function up_sell_product_search(Request $request)
    {
			$inputs=$request->all();
			$res=$this->productSearchCommon($request->session()->get('product_id'),$inputs,1);
			echo json_encode($res);
			
			
    }
    
     public function related_products_search(Request $request)
    {
		$inputs=$request->all();
		$res=$this->productSearchCommon($request->session()->get('product_id'),$inputs,0);
		echo json_encode($res);
    }

	
	 public function cross_sell_product_search(Request $request)
    {
		$inputs=$request->all();
		$res=$this->productSearchCommon($request->session()->get('product_id'),$inputs,2);
		echo json_encode($res);
    }
    
    	public function  productSearchCommon($prd_id,$inputs,$type){
		$ProductCategories = new ProductCategories;
					$cats=$ProductCategories->getCategories($prd_id);
					$ProductRelation = new ProductRelation;
					$relative_product=$ProductRelation->getRelativeProduct($prd_id,$type,$inputs);
				
				
			 $q=$inputs['SearchByName'];
			 $res=Products::select('product_id','products.name')
								->join('product_categories', 'products.id', '=', 'product_categories.product_id')
								->where('products.name','LIKE',$q.'%');
								if($inputs['SearchByVisibility']!='' && $inputs['SearchByVisibility']!=null){
										$res=$res->where('products.visibility',$inputs['SearchByVisibility']);
								}
								if($inputs['SearchByStatus']!='' && $inputs['SearchByStatus']!=null){
										$res=$res->where('products.prd_sts',$inputs['SearchByStatus']);
								}
								 $res=$res->where('products.isdeleted',0)
								->whereIn('product_categories.cat_id',$cats)
								->where('product_id','!=',$prd_id)
								->whereNotIn('product_id',$relative_product)
								->orderBy('products.id', 'DESC')
								 ->groupBy('product_categories.product_id','products.name')
								->paginate(50);
								
									
								$total=ProductCategories::select('product_id','products.name')
								->join('products', 'products.id', '=', 'product_categories.product_id')
								->where('products.name','LIKE',$q.'%');
								if($inputs['SearchByVisibility']!=''){
										$total=$total->where('products.visibility',$inputs['SearchByVisibility']);
								}
								if($inputs['SearchByStatus']!=''){
										$total=$total->where('products.prd_sts',$inputs['SearchByStatus']);
								}
								$total=$total->where('isdeleted',0)
								 ->whereIn('product_categories.cat_id',$cats)
								 ->whereNotIn('product_id',$relative_product)
								 ->where('product_id','!=',$prd_id)
								->orderBy('products.id', 'DESC')
								 ->groupBy('product_categories.product_id','products.name')
								->get()
								->toarray();
							
				
				$data = array();
					
					$table_data=array();
					
					
					foreach ($relative_product as $rel_product) {
	
	$product = Products::select('id','name','prd_sts','visibility')
				->where('id',$rel_product)
				->first();
				
				$selected_id='';
			if (in_array($rel_product, $relative_product))
			{
			$selected_id="checked";
			}
				
	$checkbox='<input type="checkbox" name="related_product_id[]" value="'.$product->id.'" class="product_checkbox_child" '.$selected_id.'>';
	$sts='';
	switch($product->prd_sts){
			case(0);
		$sts.='<span>Disabled</span>';
		break;
		
		case(1);
		$sts.='<span>Enabled</span>';
		break;
	}
	
	$vis='';
	switch($product->visibility){
		case('');
		$vis.='<span>Not Selected</span>';
		break;
		case(1);
		$vis.='<span>Not visible individualy</span>';
		break;
		
		case(2);
		$vis.='<span>Catalog</span>';
		break;
		
		case(3);
		$vis.='<span>Search</span>';
		break;
		
		case(4);
		$vis.='<span>Catalog,Search</span>';
		break;
	}
 
		$html='<tr>';
		$html.='<td>'.$checkbox.'</td>';
		$html.='<td>'.$product->name.'</td>';
		$html.='<td>'.$sts.'</td>';
		$html.='<td>'.$vis.'</td>';
		$html.='</tr>';
		array_push($table_data,$html);
}
					
foreach ($res as $row) {
	
	$product = Products::select('id','name','prd_sts','visibility')
				->where('id',$row->product_id)
				->first();
				
				$selected_id='';
			if (in_array($row->product_id, $relative_product))
			{
			$selected_id="checked";
			}
				
	$checkbox='<input type="checkbox" name="related_product_id[]" value="'.$product->id.'" class="product_checkbox_child" '.$selected_id.'>';
	$sts='';
	switch($product->prd_sts){
			case(0);
		$sts.='<span>Disabled</span>';
		break;
		
		case(1);
		$sts.='<span>Enabled</span>';
		break;
	}
	
	$vis='';
	switch($product->visibility){
		case('');
		$vis.='<span>Not Selected</span>';
		break;
		case(1);
		$vis.='<span>Not visible individualy</span>';
		break;
		
		case(2);
		$vis.='<span>Catalog</span>';
		break;
		
		case(3);
		$vis.='<span>Search</span>';
		break;
		
		case(4);
		$vis.='<span>Catalog,Search</span>';
		break;
	}
 
		$html='<tr>';
		$html.='<td>'.$checkbox.'</td>';
		$html.='<td>'.$product->name.'</td>';
		$html.='<td>'.$sts.'</td>';
		$html.='<td>'.$vis.'</td>';
		$html.='</tr>';
		array_push($table_data,$html);
}


$json_data = array(
		"draw"            => 3,  
		"recordsTotal"    => sizeof($total),  
		"recordsFiltered" => sizeof($total),
		"table_data"     => (sizeof($table_data)>0?$table_data:'<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No data available in table</td></tr>')   // total data array
		);
		return $json_data;
	}
	
}
