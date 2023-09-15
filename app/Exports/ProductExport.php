<?php
namespace App\Exports;
use App\Products;
use App\ProductCategories;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class ProductExport implements FromView
{
            private $str;
            private $sts;
            private $vendor;
            private $type;
            private $category_id;
            private $brands;
            private $blocked;


        
public function __construct($str,$sts,$vendor,$type,$category_id,$brands,$blocked)
{
            $this->str = $str;
            $this->sts = $sts;
            $this->vendor = $vendor;
            $this->type = $type;
            $this->brands = $str;
            $this->category_id = $category_id;
            $this->blocked = $blocked;
}
  public function view(): View
    {
		
		$Products= Products::where('products.isdeleted', 0);
		$parameters= $this->str;

				
				if( ( $this->category_id!='All' &&  $this->category_id!='')  || ($parameters!='All' && $parameters!='')){
		          $Products =$Products
						->join('product_categories','product_categories.product_id','=','products.id')
						->join('categories','categories.id','=','product_categories.cat_id');
		} 
		
		if($this->brands!='All' &&  $this->brands!=''){
		    	$selcted_brand=explode(",",$this->brands);
		    
				   $Products=$Products->whereIn('products.product_brand',$selcted_brand);
		} 
	    
	    
        if($this->vendor!='All' && $this->vendor!=''){
            $selcted_vendor=explode(",",$this->vendor);
            $Products=$Products->whereIn('products.vendor_id',$selcted_vendor);
        }
        
		if( $this->sts!='' && $this->sts!='All'){
				$Products=$Products->where('products.status','=',$this->sts);
		} 
	
	if( $this->blocked!='' && $this->blocked!='All'){
				$Products=$Products->where('products.isblocked','=',$this->blocked);
		} 
		
		if($this->type!='All' && $this->type!=''){
		     $Products=$Products->where('products.isexisting','=',$this->type);
		}
		
		
		if($parameters!='All' && $parameters!=''){
		       $Products =$Products
						   ->Where(function($query) use ($parameters){
							 $query->orWhere('products.name','LIKE', '%' . $parameters . '%');
							 //$query->orWhere('products.sku','LIKE', '%' . $parameters . '%');
							 $query->orWhere('categories.name','LIKE', '%' . $parameters . '%');
						 });
		} 
		if($this->category_id!='All' && $this->category_id!=''){
		  	$Products =$Products->where('product_categories.cat_id',$this->category_id);
		} 
		$products=$Products->orderBy('products.id', 'DESC')->get();
		// ->with('productCategoriesData')
		// dd($products);
        return view('admin.exports.products', [
            'Products' => $products
        ]);
    }
}