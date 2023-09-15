<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Customer; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Validator;
use DB;
use App\Helpers\CommonHelper;
use App\Products;
use App\ProductAttributes;
use App\Colors;
use App\Sizes;
use App\ProductCategories;
use App\ProductImages;
use App\Category;


class CartController extends Controller 
{
	public $successStatus = 200;
	
	/** 
     * Cart Listing api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function cart_listing(Request $request) {
				      $shipping_charges_details=CommonHelper::getShippingDetails();
		$input = json_decode(file_get_contents('php://input'), true);
				    $save_total=0;
				    $shipping_total=0;
				    $cart_total=0;
		$record = DB::table('cart')
		                ->select(
							'products.name as fld_product_name',
							'cart.qty as fld_product_qty',
							'products.price as fld_product_price',
							'products.spcl_price as fld_spcl_price',
							'cart.color_id as fld_product_color',
							'cart.size_id as fld_product_size',
							'cart.prd_id as fld_product_id',
							'products.delivery_days as fld_delivery_days',
							'products.shipping_charges as fld_shipping_charges',
                            DB::raw("CONCAT('http://aptechbangalore.com/test/uploads/products/',products.default_image) AS default_image")
		                )
		                ->join('products','cart.prd_id','products.id')
		                
						->where('user_id',$input['fld_user_id'])
						->get()
						->toarray();
						
		$total_data=$ship_total_data=$save_total_data=0;
		
		if($record){
		    $whole_data=array();
		    foreach($record as $row){
		        $row->fld_delivery_days='Delivery by '.rand(date('d')+1,30).' '.date('M').' '.date('D');
		        
		        $old_prc=$row->fld_product_price;
		        $prc=0;
			if ($row->fld_spcl_price!='' && $row->fld_spcl_price!=0)
			  {
				  $prc=$row->fld_spcl_price;
			  }else{
				  $prc=$row->fld_product_price;
			  }
			  
			   if($row->fld_product_color==0 && $row->fld_product_size!=0){
		    
		    $attr_data=DB::table('product_attributes')
		    ->where('product_id',$row->fld_product_id)
		     ->where('size_id',$row->fld_product_size)
		    ->first();
		     $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
	 if($row->fld_product_color!=0 && $row->fld_product_size==0){
		     $attr_data=DB::table('product_attributes')
		       ->where('product_id',$row->fld_product_id)
		     ->where('color_id',$row->fld_product_color)
		    ->first();
		    $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
	     if($row->fld_product_color!=0 && $row->fld_product_size!==0){
		     $attr_data=DB::table('product_attributes')
		    ->where('product_id',$row->fld_product_id)
		     ->where('color_id',$row->fld_product_color)
		     ->where('size_id',$row->fld_product_size)
		    ->first();
		    $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
				$row->fld_spcl_price=$prc;
				$row->fld_product_price=$old_prc;
		        array_push($whole_data,$row);
				
				$total_data+=$prc*$row->fld_product_qty;
				$ship_total_data+=$row->fld_shipping_charges*$row->fld_product_qty;
				//$save_total_data+=$row->fld_spcl_price*$row->fld_product_qty;
				$save_total_data+=($row->fld_product_price-$row->fld_spcl_price)*$row->fld_product_qty;
		    }
		    
		    if($shipping_charges_details->cart_total<$total_data){
				$ship_total_data=0;
			} 
			
			$statusCode=201;
			$message="Cart Listing";
			$cart_data=$whole_data;
			$save_total=$save_total_data;
			$shipping_total=$ship_total_data;
			$cart_total=$total_data;
		}else{
			$statusCode=404;
			$message="No Cart Found";
			$cart_data=array();
		}
		
		$res=array(
					"status"=>true,
					"statusCode"=>$statusCode,
					"message"=>$message,
					"cart_data"=>$cart_data,
					"save_total"=>$save_total,
					"shipping_total"=>$shipping_total,
					"cart_total"=>$cart_total
				);
		
		echo json_encode($res);
	}
	
	/** 
     * Cart Add/Update/Delete api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function cart_add_update(Request $request) {
				    
		$input = json_decode(file_get_contents('php://input'), true);
	
			
		$action_type=$input['fld_action_type'];
		
	$code='404';
		
		if($action_type==0)	
		{
			$data=array(
                    'prd_id'=>$input['fld_product_id'],
                    'user_id'=>$input['fld_user_id'],
                    'size_id'=>$input['fld_size_id'],
                    'color_id'=>$input['fld_color_id'],
                    'qty'=>$input['fld_product_qty'],
                    'price'=>$input['fld_product_price'],
                    'spcl_price'=>$input['fld_spcl_price']
					);
		    $record=DB::table('cart')
						->where('prd_id',$input['fld_product_id'])
						->where('user_id',$input['fld_user_id'])
						->first();
						
						    
						if($record){
						  
							$msg="product already in cart";
								$code='404';
						
						} else{
						    	$record=DB::table('cart')
						     ->insert($data);
						     	$msg="Product Added to cart";
						     		$code='201';
						}
						
						
		
						
		
		}elseif($action_type==1){
			
			$record=DB::table('cart')
						->where('prd_id',$input['fld_product_id'])
						->where('user_id',$input['fld_user_id'])
						->delete();
					
			$msg="Cart Deleted";
				$code='201';
		}
		
		elseif($action_type==2){
			
				$data=array(
                    'prd_id'=>$input['fld_product_id'],
                    'user_id'=>$input['fld_user_id'],
                    'size_id'=>$input['fld_size_id'],
                    'color_id'=>$input['fld_color_id'],
                    'qty'=>$input['fld_product_qty'],
                    'price'=>$input['fld_product_price'],
                    'spcl_price'=>$input['fld_spcl_price']
					);
			$record=DB::table('cart')
						->where('prd_id',$input['fld_product_id'])
						->where('user_id',$input['fld_user_id'])
						->update($data);
					
			$msg="Cart Updated";
				$code='201';
		}else{
		
						
			$msg="something went wrong";
				$code='404';
		}
		
		if($record){
			$statusCode=$code;
			$message=$msg;
			$cart_data=$record;
		}else{
			$statusCode=404;
			$message="No Cart Found";
			$cart_data=null;
		}
		
		$res=array(
					"status"=>true,
					"statusCode"=>$statusCode,
					"message"=>$message,
					"cart_update_data"=>$cart_data
				);
		
		echo json_encode($res);
	}
	
	


}