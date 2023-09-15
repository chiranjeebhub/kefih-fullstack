<?php
namespace App\Http\Controllers\cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;
use App\Http\Controllers\Vendors;
use App\Helpers\CustomFormHelper;
use App\Helpers\FIleUploadingHelper;
use App\Helpers\MsgHelper;
use App\Brands;
use App\Vendor;
use App\Products;
use App\CheckoutShipping;
use App\Category;
use App\Coupon;
use App\CouponDetails;
use App\Customer;
use App\ProductAttributes;
use Illuminate\Support\Facades\Hash;
use Redirect;
use Validator;
use DB;
use Config;
use Auth;
use Session;
use View;
use URL;
use App\Helpers\CommonHelper;
use Carbon\Carbon;
class CartController extends Controller
{
	

	public function localStorage(Request $request){
	      $input=$request->all();
	    if($input['shipping_address_id']!=''){
            $request->session()->put('shipping_address_id',$input['shipping_address_id']);
            $request->session()->put('check_pincode',$input['pincode']);
	    } else{
	         $request->session()->put('check_pincode',$input['pincode']);
	    }
	}
	public function validation_coupon_whencart_changes($obj,$input,$request){
	    
	    switch($obj->coupon_type){
	          
	           case 0:
	           case 4:
	                 $response=array(
				"Error"=>0,
				"Msg"=>"Coupon Applied "
				);
	               break;
                case 3:
                case 7: // check date and cart amount 
	        
            $paymentDate = date('Y-m-d');
            $paymentDate=date('Y-m-d', strtotime($paymentDate));
            $contractDateBegin = date('Y-m-d', strtotime($obj->started_date) );
            $contractDateEnd = date('Y-m-d', strtotime($obj->end_date));

if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){ // check date 
    
 if (
        $input['cart_total'] > $obj->below_cart_amt && 
        $input['cart_total'] < $obj->above_cart_amt
        ) {  // check cart amount
       $response=array(
				"Error"=>0,
				"Msg"=>"Coupon Applied "
				);
   
    
}  else{
    $response=array(
				"Error"=>1,
				"Msg"=>"Not valid for this  total "
				);
} 
    
}else{
    $response=array(
				"Error"=>1,
				"Msg"=>"Coupon code invalid "
				);
				
}
	        
	            break;
	            
	           case 2:
	           case 6:  // check date
	           
	              $paymentDate = date('Y-m-d');
            $paymentDate=date('Y-m-d', strtotime($paymentDate));
            $contractDateBegin = date('Y-m-d', strtotime($obj->started_date) );
            $contractDateEnd = date('Y-m-d', strtotime($obj->end_date));

if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){
  $response=array(
				"Error"=>0,
				"Msg"=>"Coupon code Applied "
				);
				  
}else{
    $response=array(
				"Error"=>1,
				"Msg"=>"Coupon code invalid "
				);
				
}
	            break;
	            
	            case 1 :
	                case 5:  // check  cart amount
	            
	             if (
        $input['cart_total'] > $obj->below_cart_amt && 
        $input['cart_total'] < $obj->above_cart_amt
        ) {  // check cart amount
    $response=array(
				"Error"=>0,
				"Msg"=>"Coupon Applied"
				);
   
    
}  else{
    $response=array(
				"Error"=>1,
					"Msg"=>"Not valid for this  total"
				);
} 
	            break;
	            
	    }
	 return $response;
			
	}
	public function update_wishlist_count(){
	    $wishlist_data=$this->getWishlist_item();
	    $response=array(
				 "size"=>sizeof($wishlist_data)
				);
				
				echo json_encode($response);
	    
	}
	public function getActivateCoupon($ip){
	    $isActivate=DB::table('cart_coupon')
		    ->where('user_ip',$ip)
		    ->first();
		    return $isActivate;
	}
	
	public function verifyAppliedCoupon($code,$request){
	      
	    $cust_id='';
		if(Auth::guard('customer')->check())
		{
			$cust_id=auth()->guard('customer')->user()->id ; 
		}
		
	    $coupondata =CouponDetails::select(
                'coupons.coupon_type',
                'coupons.id',
                'coupons.coupon_type',
                'coupons.max_discount',
                'coupons.below_cart_amt',
                'coupons.above_cart_amt',
                'coupons.started_date',
                'coupons.end_date',
                'coupons.discount_value',
                'coupons.total_coupon',
                 'coupon_details.coupon_code'
	   )
	   ->join('coupons','coupons.id','coupon_details.coupon_id')
	   ->where('coupon_details.coupon_code',$code)
	   ->where('coupon_details.coupon_used',0)
	    ->where('coupons.status',1)
	   ->first();
	   if($coupondata){
	       
	       $obj=DB::table('tbl_coupon_assign')->where('fld_coupon_id',$coupondata->id)->first();
	       
	       if($obj){
	             $ip=$request->ip();
	    switch($obj->fld_coupon_assign_type){
	         
                case 1: /// category wise assign 
                $categoryProductIncarts=DB::table('cart')->select('cart.*')
                         ->join('products','products.id','cart.prd_id')
                         ->join('product_categories','product_categories.product_id','products.id')
                         ->join('categories','product_categories.cat_id','categories.id');
						 
						 if($cust_id!='')
						 {
							  $categoryProductIncarts=$categoryProductIncarts->where('user_id',$cust_id);
						 }
                        //->where('user_ip',$ip)
						
                 $categoryProductIncarts=$categoryProductIncarts->where('categories.id',$obj->fld_assign_type_id)
                        ->get();
                        if(sizeof($categoryProductIncarts)>0){
                            $cart_total=0;
                            foreach($categoryProductIncarts as $categoryProductIncart){
                                 $product_data=Products::select('price','spcl_price')->where('id',$categoryProductIncart->prd_id)->first();
                                $prd_attr=DB::table('product_attributes')
                                ->where('size_id',$categoryProductIncart->size_id)
                                ->where('color_id',$categoryProductIncart->color_id)
                                ->where('product_id',$categoryProductIncart->prd_id)
                                ->first();
                                
                                $total=($product_data->price+$prd_attr->price)*$categoryProductIncart->qty;
                               
                                if($product_data->spcl_price!=0 && $product_data->spcl_price!=''){
                                    $total=($product_data->spcl_price+$prd_attr->price)*$categoryProductIncart->qty;
                                   
                                }
                                $cart_total+=$total;
                                
                            }
                            $input['cart_total']=$cart_total;
                               $response= $this->validation_coupon_whencart_changes($coupondata,$input,$request);
                               
                        } else{
                            $response=array(
                                "Error"=>1,
                                "Msg"=>"Coupon code invalid"
                            );
                        
                        }
                break;
                
                case 2: // brand wise assign
               
               
                $brandProductIncarts=DB::table('cart')->select('cart.*')
                         ->join('products','products.id','cart.prd_id')
                         ->join('brands','products.product_brand','brands.id');
                        //->where('user_ip',$ip)
						
						if($cust_id!='')
						 {
							  $brandProductIncarts=$brandProductIncarts->where('user_id',$cust_id);
						 }
						
                        $brandProductIncarts=$brandProductIncarts->where('brands.id',$obj->fld_assign_type_id)
                        ->get();
                        
                        if(sizeof($brandProductIncarts)>0){
                           $cart_total=0;
                            foreach($brandProductIncarts as $brandProductIncart){
                                 $product_data=Products::select('price','spcl_price')->where('id',$brandProductIncart->prd_id)->first();
                                $prd_attr=DB::table('product_attributes')
                                ->where('size_id',$brandProductIncart->size_id)
                                ->where('color_id',$brandProductIncart->color_id)
                                ->where('product_id',$brandProductIncart->prd_id)
                                ->first();
                                
                                $total=($product_data->price+$prd_attr->price)*$brandProductIncart->qty;
                               
                                if($product_data->spcl_price!=0 && $product_data->spcl_price!=''){
                                    $total=($product_data->spcl_price+$prd_attr->price)*$brandProductIncart->qty;
                                   
                                }
                                $cart_total+=$total;
                                
                            }
                            $input['cart_total']=$cart_total;
                                $response=$this->validation_coupon_whencart_changes($coupondata,$input,$request);
                                
                        } else{
                            $response=array(
                                "Error"=>1,
                                "Msg"=>"Coupon code invalid"
                            );
                          
                        }
                break;
                
                case 3: // product wise assign
                 
                       
                        if(Auth::guard('customer')->check())
                        {
                        $cust_id=auth()->guard('customer')->user()->id ; 
                        }
                      
                       
                        $prdIncart=DB::table('cart');
                        //->where('user_ip',$ip);
						
						if($cust_id!='')
						 {
							  $prdIncart=$prdIncart->where('user_id',$cust_id);
						 }
						
                        $prdIncart=$prdIncart->where('prd_id',$obj->fld_assign_type_id)
                        ->first();
                        if($prdIncart){
                            $product_data=Products::select('price','spcl_price')->where('id',$obj->fld_assign_type_id)->first();
                                $prd_attr=DB::table('product_attributes')
                                ->where('size_id',$prdIncart->size_id)
                                ->where('color_id',$prdIncart->color_id)
                                ->where('product_id',$obj->fld_assign_type_id)
                                ->first();
                                
                                $total=($product_data->price+$prd_attr->price)*$prdIncart->qty;
                               
                                if($product_data->spcl_price!=0 && $product_data->spcl_price!=''){
                                    $total=($product_data->spcl_price+$prd_attr->price)*$prdIncart->qty;
                                   
                                }
                                $input['cart_total']=$total;
                                $response=$this->validation_coupon_whencart_changes($coupondata,$input,$request);
                              
                        } else{
                            $response=array(
                                "Error"=>1,
                                "Msg"=>"Coupon code invalid"
                            );
                            
                        }
                       
                          
                break;
	        
	    }
	       } else{
	          $input['cart_total']=$this->getCartTotal($request);
	          $response=$this->validation_coupon_whencart_changes($coupondata,$input,$request);
	           
	       }
	       
	   } else{
	       $response=array(
				"Error"=>1,
				"Msg"=>"Coupon code invalid"
				);
				
	   }
	  
	    return $response;
	}
	
	public static function getDiscountvalue($type,$id,$ip,$discount){
	    $ip=$ip;
		
		$cust_id='';
		if(Auth::guard('customer')->check())
		{
		$cust_id=auth()->guard('customer')->user()->id ; 
		}
	    switch($type){
	         
                case 1: /// category wise assign 
                $categoryProductIncarts=DB::table('cart')->select('cart.*')
                         ->join('products','products.id','cart.prd_id')
                         ->join('product_categories','product_categories.product_id','products.id')
                         ->join('categories','product_categories.cat_id','categories.id');
                        //->where('user_ip',$ip)
						
						if($cust_id!='')
						{
							$categoryProductIncarts=$categoryProductIncarts->where('user_id',$cust_id);
						}
						
                        $categoryProductIncarts=$categoryProductIncarts->where('categories.id',$id)
                        ->get();
                        if(sizeof($categoryProductIncarts)>0){
                            $cart_total=0;
                            foreach($categoryProductIncarts as $categoryProductIncart){
                                 $product_data=Products::select('price','spcl_price')->where('id',$categoryProductIncart->prd_id)->first();
                                $prd_attr=DB::table('product_attributes')
                                ->where('size_id',$categoryProductIncart->size_id)
                                ->where('color_id',$categoryProductIncart->color_id)
                                ->where('product_id',$categoryProductIncart->prd_id)
                                ->first();
                                
                                $total=($product_data->price+$prd_attr->price)*$categoryProductIncart->qty;
                               
                                if($product_data->spcl_price!=0 && $product_data->spcl_price!=''){
                                    $total=($product_data->spcl_price+$prd_attr->price)*$categoryProductIncart->qty;
                                   
                                }
                                $cart_total+=$total;
                                
                            }
                            $discount= ( $cart_total*$discount)/100;
                                return $discount;
                        }  else{
                            return 0;
                        }
                break;
                
                case 2: // brand wise assign
               
               
                $brandProductIncarts=DB::table('cart')->select('cart.*')
                         ->join('products','products.id','cart.prd_id')
                         ->join('brands','products.product_brand','brands.id');
                        //->where('user_ip',$ip)
						
						if($cust_id!='')
						{
							$brandProductIncarts=$brandProductIncarts->where('user_id',$cust_id);
						}
						
                        $brandProductIncarts=$brandProductIncarts->where('brands.id',$id)
                        ->get();
                        
                        if(sizeof($brandProductIncarts)>0){
                           $cart_total=0;
                            foreach($brandProductIncarts as $brandProductIncart){
                                 $product_data=Products::select('price','spcl_price')->where('id',$brandProductIncart->prd_id)->first();
                                $prd_attr=DB::table('product_attributes')
                                ->where('size_id',$brandProductIncart->size_id)
                                ->where('color_id',$brandProductIncart->color_id)
                                ->where('product_id',$brandProductIncart->prd_id)
                                ->first();
                                
                                $total=($product_data->price+$prd_attr->price)*$brandProductIncart->qty;
                               
                                if($product_data->spcl_price!=0 && $product_data->spcl_price!=''){
                                    $total=($product_data->spcl_price+$prd_attr->price)*$brandProductIncart->qty;
                                   
                                }
                                $cart_total+=$total;
                                
                            }
                            $discount= ( $cart_total*$discount)/100;
                                return $discount;
                            
                        } else{
                            return 0;
                        }
                break;
                
                case 3: // product wise assign
                 
                       
                        if(Auth::guard('customer')->check())
                        {
                        $cust_id=auth()->guard('customer')->user()->id ; 
                        }
                      
                       
                        $prdIncart=DB::table('cart');
                        //->where('user_ip',$ip)
						
						if($cust_id!='')
						{
							$prdIncart=$prdIncart->where('user_id',$cust_id);
						}
						
                        $prdIncart=$prdIncart->where('prd_id',$id)
                        ->first();
                        if($prdIncart){
                            $product_data=Products::select('price','spcl_price')->where('id',$id)->first();
                                $prd_attr=DB::table('product_attributes')
                                ->where('size_id',$prdIncart->size_id)
                                ->where('color_id',$prdIncart->color_id)
                                ->where('product_id',$id)
                                ->first();
                                
                                $total=($product_data->price+$prd_attr->price)*$prdIncart->qty;
                               
                                if($product_data->spcl_price!=0 && $product_data->spcl_price!=''){
                                    $total=($product_data->spcl_price+$prd_attr->price)*$prdIncart->qty;
                                   
                                }
                                $discount= ( $total*$discount)/100;
                                return $discount;
                               
                        }
                        else{
                            DB::table('cart_coupon')
                            ->where('user_ip',$ip)
                            ->delete();
                            return 0;
                        }
                       
                          
                break;
	        
	    }
	}
	public function activateCoupon($obj,$request){
	  
		/*$coupon_array = array(
            'coupon_code'=>$obj->coupon_code,
            'discount_value'=>$obj->discount_value,
        );
		$request->session()->put('cart_coupon', $coupon_array);*/
		
	  $coupondata =CouponDetails::select(
            'tbl_coupon_assign.fld_coupon_assign_type',
            'tbl_coupon_assign.fld_assign_type_id'
	   )
	   ->join('coupons','coupons.id','coupon_details.coupon_id')
	   ->join('tbl_coupon_assign','coupons.id','tbl_coupon_assign.fld_coupon_id')
	   ->where('coupon_details.coupon_code',$obj->coupon_code)
	   ->where('coupon_details.coupon_used',0)
	    ->where('coupons.status',1)
	   ->first();
	   
	  
	   
	   $isAssign=DB::table('tbl_coupon_assign')->where('fld_coupon_id',$obj->id)->first();
	   
	    $type='';
	    $type_id='';
	    if($isAssign){
            $type=$coupondata->fld_coupon_assign_type;
            $type_id=$coupondata->fld_assign_type_id;
	    }
	   
	   
        $ip=$request->ip();
                $isActivate= DB::table('cart_coupon')
		    ->where('user_ip',$ip)
		    ->first(); 
		    if($isActivate){
		        	DB::table('cart_coupon')
			->where('user_ip',$ip)
			->update([
                        'coupon_code'=>$obj->coupon_code,
                        'discount_value'=>$obj->discount_value,
                        'coupon_assign_type'=>$type,
                        'coupon_assign_type_id'=>$type_id
					]);
		        
		    } else{
 
		        DB::table('cart_coupon')
			->insert([
                        'coupon_code'=>$obj->coupon_code,
                        'discount_value'=>$obj->discount_value,
                        'coupon_assign_type'=>$type,
                        'coupon_assign_type_id'=>$type_id,
                        'user_ip'=>$ip
					]);
		    } 
		    

	}
	public function old3activateCoupon($obj,$request){
	    
	   
        $coupon_array = array(
            'coupon_code'=>$obj->coupon_code,
            'discount_value'=>$obj->discount_value,
        );
$request->session()->put('cart_coupon', $coupon_array);

//                 $ip=$request->ip();
//                 $isActivate= $this->getActivateCoupon($ip);
// 		    if($isActivate){
// 		        	DB::table('cart_coupon')
// 			->where('user_ip',$ip)
// 			->update([
//                         'coupon_code'=>$obj->coupon_code,
//                         'discount_value'=>$obj->discount_value
// 					]);
		        
// 		    } else{
// 		        DB::table('cart_coupon')
// 			->insert([
//                         'coupon_code'=>$obj->coupon_code,
//                         'discount_value'=>$obj->discount_value,
//                         'user_ip'=>$ip
// 					]);
// 		    }
		    
	      if(Auth::guard('customer')->check())
		{
// 			$cust_id=auth()->guard('customer')->user()->id ; 
			
			
		}else{
		    
	
			
		}
	}
	
	public function validation_coupon($obj,$input,$request){
	   
	  
	    switch($obj->coupon_type){
	          
	           case 0:
	           case 4:
	                $response=array(
				"Error"=>0,
				"Msg"=>"Coupon Applied "
				);
	               
                    $this->activateCoupon($obj,$request);
	                
	               break;
	        case 3:
	        case 7: // check date and cart amount 
	        
            $paymentDate = date('Y-m-d');
            $paymentDate=date('Y-m-d', strtotime($paymentDate));
            $contractDateBegin = date('Y-m-d', strtotime($obj->started_date) );
            $contractDateEnd = date('Y-m-d', strtotime($obj->end_date));

if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){ // check date 
    
  if (
        $input['cart_total'] > $obj->below_cart_amt && 
        $input['cart_total'] < $obj->above_cart_amt
        ) {  // check cart amount
    
   $this->activateCoupon($obj,$request);
       $response=array(
				"Error"=>0,
				"Msg"=>"Coupon Applied "
				);
   
    
}  else{
    $response=array(
				"Error"=>1,
				"Msg"=>"Not valid for this  total "
				);
} 
    
}else{
    $response=array(
				"Error"=>1,
				"Msg"=>"Coupon code invalid "
				);
				
}
	        
	            break;
	            
            case 2:
            case  6:  // check date
	           
	              $paymentDate = date('Y-m-d');
            $paymentDate=date('Y-m-d', strtotime($paymentDate));
            $contractDateBegin = date('Y-m-d', strtotime($obj->started_date) );
            $contractDateEnd = date('Y-m-d', strtotime($obj->end_date));

if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){
     $this->activateCoupon($obj,$request);
  $response=array(
				"Error"=>0,
				"Msg"=>"Coupon code Applied "
				);
				  
}else{
    $response=array(
				"Error"=>1,
				"Msg"=>"Coupon code invalid "
				);
				
}
	            break;
	            
                case 1:
                case 5:  // check  cart amount
	            
	            if (
        $input['cart_total'] > $obj->below_cart_amt && 
        $input['cart_total'] < $obj->above_cart_amt
        ) {  // check cart amount
	                $this->activateCoupon($obj,$request);
    $response=array(
				"Error"=>0,
				"Msg"=>"Coupon Applied"
				);
   
    
}  else{
    $response=array(
				"Error"=>1,
					"Msg"=>"Not valid for this  total"
				);
} 
	            break;
	            
	    }
	    
	   
	  echo json_encode($response);
			
	}
	
	public function couponAssigned($request,$obj,$coupondata){
            $ip=$request->ip();
            $cust_id=0;
            if(Auth::guard('customer')->check())
                        {
                        $cust_id=auth()->guard('customer')->user()->id ; 
                        }
           
           
	    switch($obj->fld_coupon_assign_type){
	         
                case 1: /// category wise assign 
                $categoryProductIncarts=DB::table('cart')->select('cart.*')
                         ->join('products','products.id','cart.prd_id')
                         ->join('product_categories','product_categories.product_id','products.id')
                         ->join('categories','product_categories.cat_id','categories.id')
                           ->where('cart.user_id',$cust_id)
                        ->where('categories.id',$obj->fld_assign_type_id)
                        ->get();
                        if(sizeof($categoryProductIncarts)>0){
                            $cart_total=0;
                            foreach($categoryProductIncarts as $categoryProductIncart){
                                 $product_data=Products::select('price','spcl_price')->where('id',$categoryProductIncart->prd_id)->first();
                                $prd_attr=DB::table('product_attributes')
                                ->where('size_id',$categoryProductIncart->size_id)
                                ->where('color_id',$categoryProductIncart->color_id)
                                ->where('product_id',$categoryProductIncart->prd_id)
                                ->first();
                                
                                $total=($product_data->price+$prd_attr->price)*$categoryProductIncart->qty;
                               
                                if($product_data->spcl_price!=0 && $product_data->spcl_price!=''){
                                    $total=($product_data->spcl_price+$prd_attr->price)*$categoryProductIncart->qty;
                                   
                                }
                                $cart_total+=$total;
                                
                            }
                            $input['cart_total']=$cart_total;
                                $this->validation_coupon($coupondata,$input,$request);
                                die();
                        } else{
                            $response=array(
                                "Error"=>1,
                                "Msg"=>"Coupon code invalid"
                            );
                            echo json_encode($response);
                            die();
                        }
                break;
                
                case 2: // brand wise assign
               
               
                $brandProductIncarts=DB::table('cart')->select('cart.*')
                         ->join('products','products.id','cart.prd_id')
                         ->join('brands','products.product_brand','brands.id')
                           ->where('cart.user_id',$cust_id)
                        ->where('brands.id',$obj->fld_assign_type_id)
                        ->get();
                        
                        if(sizeof($brandProductIncarts)>0){
                           $cart_total=0;
                            foreach($brandProductIncarts as $brandProductIncart){
                                 $product_data=Products::select('price','spcl_price')->where('id',$brandProductIncart->prd_id)->first();
                                $prd_attr=DB::table('product_attributes')
                                ->where('size_id',$brandProductIncart->size_id)
                                ->where('color_id',$brandProductIncart->color_id)
                                ->where('product_id',$brandProductIncart->prd_id)
                                ->first();
                                
                                $total=($product_data->price+$prd_attr->price)*$brandProductIncart->qty;
                               
                                if($product_data->spcl_price!=0 && $product_data->spcl_price!=''){
                                    $total=($product_data->spcl_price+$prd_attr->price)*$brandProductIncart->qty;
                                   
                                }
                                $cart_total+=$total;
                                
                            }
                            $input['cart_total']=$cart_total;
                                $this->validation_coupon($coupondata,$input,$request);
                                die();
                        } else{
                            $response=array(
                                "Error"=>1,
                                "Msg"=>"Coupon code invalid"
                            );
                            echo json_encode($response);
                            die();
                        }
                break;
                
                case 3: // product wise assign
                 
                        $prdIncart=DB::table('cart')
                          ->where('cart.user_id',$cust_id)
                        ->where('prd_id',$obj->fld_assign_type_id)
                        ->first();
                        if($prdIncart){
                            $product_data=Products::select('price','spcl_price')->where('id',$obj->fld_assign_type_id)->first();
                                $prd_attr=DB::table('product_attributes')
                                ->where('size_id',$prdIncart->size_id)
                                ->where('color_id',$prdIncart->color_id)
                                ->where('product_id',$obj->fld_assign_type_id)
                                ->first();
                                
                                $total=($product_data->price+$prd_attr->price)*$prdIncart->qty;
                               
                                if($product_data->spcl_price!=0 && $product_data->spcl_price!=''){
                                    $total=($product_data->spcl_price+$prd_attr->price)*$prdIncart->qty;
                                   
                                }
                                $input['cart_total']=$total;
                                $this->validation_coupon($coupondata,$input,$request);
                                die();
                        } else{
                            $response=array(
                                "Error"=>1,
                                "Msg"=>"Coupon code invalid"
                            );
                            echo json_encode($response);
                            die();
                        }
                       
                          
                break;
	        
	    }
	}
	public function apply_coupon(Request $request){
	    $input=$request->all();
	    
	   
	    
	    $coupondata =CouponDetails::select(
                'coupons.coupon_type',
                'coupons.id',
                'coupons.coupon_type',
                'coupons.max_discount',
                'coupons.below_cart_amt',
                'coupons.above_cart_amt',
                'coupons.started_date',
                'coupons.end_date',
                'coupons.discount_value',
                'coupons.total_coupon',
                 'coupon_details.coupon_code'
	   )
        ->join('coupons','coupons.id','coupon_details.coupon_id')
        ->where('coupon_details.coupon_code',$input['code'])
        ->where('coupon_details.coupon_used',0)
        ->where('coupons.status',1)
        ->first();
	   if($coupondata){
              
	       
	       $isAssign=DB::table('tbl_coupon_assign')->where('fld_coupon_id',$coupondata->id)->first();
	     
	       
	       if($isAssign){
	             $this->couponAssigned($request,$isAssign,$coupondata);
	            die();
	       } else{
	          $this->validation_coupon($coupondata,$input,$request);
	           die();
	       }
	       
	   } else{
	       $response=array(
				"Error"=>1,
				"Msg"=>"Coupon code invalid"
				);
				
	   }
	  
	    echo json_encode($response);
	}
	 public function add_to_wishlist(Request $request){
            $input=$request->all();
            $user_id=auth()->guard('customer')->user()->id;
	   
		$is_exist=DB::table('tbl_wishlist')
		->select('fld_wishlist_id')
		->where('fld_product_id','=',$input['prd_id'])
		->where('fld_user_id','=',$user_id)
		->first();
		$inputs=array(
			'fld_product_id' =>$input['prd_id'],
			'fld_user_id' =>$user_id
			);
		if($is_exist){
			
			
			$method=2;
			 $res=DB::table('tbl_wishlist')
                    ->where('fld_product_id','=',$input['prd_id'])
                    ->where('fld_user_id','=',$user_id)
                    ->update($inputs);
		} else{
			$method=1;
			$res=DB::table('tbl_wishlist')->insert($inputs);
		}
		
		if($res){
			$response=array(
				"status"=>true,
				"method"=>$method
				);
		} else{
			$response=array(
				"status"=>false,
				"method"=>$method
				);
		}
		echo json_encode($response);
	 }
	 public static function cart_product_delete(){
         
         $minutes=(86400 * 30);
           $cookie_data=app(\App\Http\Controllers\CookieController::class)->getcustomCartCookie(); 
	  
	  if($cookie_data!=''){
	     
	 	$cookie_data=json_decode($cookie_data);
	 	
        foreach($cookie_data as $key=>$products){
            $isProductExist=DB::table('products')->select('id','status','isdeleted','isblocked')->where('id',$products->product_id)->first();
            if($isProductExist){
                
                if($isProductExist->status==0 ||  $isProductExist->isdeleted==1 || $isProductExist->isblocked==1){
                    unset($cookie_data[$key]);
                }
                
                 $isattrProductExist=DB::table('product_attributes')->select('id')
                 ->where('product_id',$products->product_id)
                 ->where('size_id',$products->size_id)
                 ->where('color_id',$products->color_id)
                 ->first();
                 if(!$isattrProductExist){
                     unset($cookie_data[$key]);
                 }
            } else{
                unset($cookie_data[$key]);
            }
	      
        }
         $cookie_data= array_values((array)$cookie_data);
    $json = json_encode($cookie_data);
    
    setcookie('productsInCart', $json, time() + ($minutes));
               
	 }
	 

      
     }
	public static function getCart_item($ip,$customer_id=''){
	      //self::cart_product_delete();
	     
		 if($customer_id=='')
		 {
			 $return_data=app(\App\Http\Controllers\CookieController::class)->getcustomCartCookie(); 
			
			 if($return_data==''){
				 return array();
			 }
				$cookie_data=json_decode($return_data);
				
				$cart_data=array();
				foreach($cookie_data as $cookie){
					 $products_in_cart=Products::select(
						'products.default_image',
						'products.name',
						'products.id as prd_id',
						'products.price as master_price',
						'products.spcl_price as master_spcl_price',
						'products.delivery_days as delivery_days',
						'products.shipping_charges as shipping_charges'
			
			)->where('id',$cookie->product_id)->first();
				$products_in_cart->size_id=$cookie->size_id;
				$products_in_cart->color_id=$cookie->color_id;
				$products_in_cart->qty=$cookie->qty;
				array_push($cart_data,$products_in_cart);
				}
           
		 }else{
			
				$cart_data=Products::select(
											'products.default_image',
											'products.name',
											'products.id as prd_id',
											'products.price as master_price',
											'products.spcl_price as master_spcl_price',
											'products.delivery_days as delivery_days',
											'cart.id as fld_cart_id',
											'cart.qty as qty',
											'cart.size_id as size_id',
											'cart.color_id as color_id',
											'products.shipping_charges as shipping_charges'
								
								)
								->join('cart', 'cart.prd_id', '=', 'products.id')
								->where('cart.user_id','=',$customer_id)->get();
								
				/*$cart_data=DB::table('cart')->select('cart.id as fld_cart_id',
												'products.default_image',
												'products.name',
												'products.id as prd_id',
												'products.price as master_price',
												'products.spcl_price as master_spcl_price',
												'cart.qty as qty',
												'cart.size_id as size_id',
												'cart.color_id as color_id',
												'products.delivery_days as delivery_days',
												'products.shipping_charges as shipping_charges'
											)
						->join('products', 'cart.prd_id', '=', 'products.id');
						//->where('cart.user_ip','=',$ip);
						
				$cart_data=$cart_data->where('cart.user_id','=',$customer_id);
				$cart_data=$cart_data->get();*/
				
				/*$products_in_cart=$cart_data;
				
				$cart_data=array();
				array_push($cart_data,$products_in_cart);*/
		 }		 
        
		return $cart_data;
	}
	
	public static function getCart_item1($customer_id){
	      self::cart_product_delete();
	     $return_data=app(\App\Http\Controllers\CookieController::class)->getcustomCartCookie(); 
	    
	     if($return_data==''){
	         return array();
	     }
	    	$cookie_data=json_decode($return_data);
	    	
	    	$cart_data=array();
	    	foreach($cookie_data as $cookie){
	    	     $products_in_cart=Products::select(
                    'products.default_image',
                    'products.name',
                    'products.id as prd_id',
                    'products.price as master_price',
                    'products.spcl_price as master_spcl_price',
                    'products.delivery_days as delivery_days',
                    'products.shipping_charges as shipping_charges'
		
		)->where('id',$cookie->product_id)->first();
            $products_in_cart->size_id=$cookie->size_id;
            $products_in_cart->color_id=$cookie->color_id;
            $products_in_cart->qty=$cookie->qty;
            array_push($cart_data,$products_in_cart);
	    	}
            
		return $cart_data;
	}
	
	public function getWishlist_item(){
		 $user_id=auth()->guard('customer')->user()->id;
		$wishlist_data=DB::table('tbl_wishlist')->select('tbl_wishlist.*','products.default_image','products.name','products.id as prd_id')
		->join('products', 'tbl_wishlist.fld_product_id', '=', 'products.id')
		->where('tbl_wishlist.fld_user_id','=',$user_id)
		->get();
		
		return $wishlist_data;
	}
	public function index(Request $request){
		

		if(Auth::guard('customer')->check())
		{
			$cust_id=auth()->guard('customer')->user()->id ; 
			$cart_data=self::getCart_item($request->ip(),$cust_id);
		}else{
			$cart_data=self::getCart_item($request->ip());
			
		}
		
		
		
		return view('fronted.mod_cart.list',["cart"=>$cart_data]);
	
	}
public function changeQtyOfCartProduct(Request $request){
		$input=$request->all();
	
	

        
       	$stock = ProductAttributes::select('qty')
        ->where('size_id','=',$input['size'])
         ->where('color_id','=',$input['color'])
        ->where('product_id','=',$input['prd_id'])
        ->first();
       
	 
        

         $quantity = $stock ? $stock->qty : 0;
      
       
       
   	$prd_in_cart=array(
                'product_id'=>$input['prd_id'],
                'size_id'=>$input['size'],
                'color_id'=>$input['color'],
                'qty'=>$input['qty'],
		          
		    );
		    
         if($quantity >= $input['qty']) {
             
	
	$return=app(\App\Http\Controllers\CookieController::class)->increaseQtyOfProduct($prd_in_cart); 
		
			$response=array(
				"error"=>false
				);	
	
		}else{
$response=array(
				"error"=>true
				);	
		}
		echo json_encode($response);
	}
		public function getCartTotal(Request $request){
		    $shipping_charges_details=CommonHelper::getShippingDetails();
	    
                $ip=$request->ip();
                $isActivate= $this->getActivateCoupon($ip);
                $tax=0;
                $discount=0;
                $shipping_charge=0;
                $grand_total=0;
                
                
        $reward_points=0;  
		if(Auth::guard('customer')->check())
		{
			$cust_id=auth()->guard('customer')->user()->id ;
			$cart_data=self::getCart_item($request->ip(),$cust_id);
			
			$cust_info=Customer::where('id',$cust_id)->first();
			$reward_points=$cust_info->total_reward_points;
			
		}else{
			$cart_data=self::getCart_item($request->ip());
		}
		$html='';
		$total=0;
		foreach($cart_data as $row){
			 $old_prc=$row->master_price;
			if ($row->master_spcl_price!='' && $row->master_spcl_price!=0)
			  {
				  $prc=$row->master_spcl_price;
			  }else{
				  $prc=$row->master_price;
			  }
			  
			  if($row->color_id==0 && $row->size_id!=0){
		    
		    $attr_data=DB::table('product_attributes')
		    ->where('product_id',$row->prd_id)
		     ->where('size_id',$row->size_id)
		    ->first();
		     $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
	 if($row->color_id!=0 && $row->size_id==0){
		     $attr_data=DB::table('product_attributes')
		       ->where('product_id',$row->prd_id)
		     ->where('color_id',$row->color_id)
		    ->first();
		    $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
	     if($row->color_id!=0 && $row->size_id!==0){
		     $attr_data=DB::table('product_attributes')
		    ->where('product_id',$row->prd_id)
		     ->where('color_id',$row->color_id)
		     ->where('size_id',$row->size_id)
		    ->first();
		    $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
		  
			$total+=$prc*$row->qty;
           
           
		}
		return $total;
		    
		}
	public function update_cart(Request $request){
	    
	    $user_logged_in=0;
	    $pincode=0;
	    $out_of_delivery=0;
		$shipping_charges_details=CommonHelper::getShippingDetails();
	    
                $ip=$request->ip();
                $isActivate= $this->getActivateCoupon($ip);
                $tax=0;
                $discount=0;
                $shipping_charge=0;
                $grand_total=0;
                	$out_of_stock=0;
                
        $reward_points=0;  
		if(Auth::guard('customer')->check())
		{     $user_logged_in=1;
		        $pincode='';
		        if($request->session()->get('shipping_address_id')!='')
		        {
		            $shipping_adddress=CheckoutShipping::where('id',$request->session()->get('shipping_address_id'))->first();
                    $pincode=$shipping_adddress->shipping_pincode;
		        }
                
			$cust_id=auth()->guard('customer')->user()->id ;
			$cart_data=self::getCart_item($request->ip(),$cust_id);
			
			$cust_info=Customer::where('id',$cust_id)->first();
			$reward_points=$cust_info->total_reward_points;
			
		}else{
		     $user_logged_in=0;
			$cart_data=self::getCart_item($request->ip());
		}
		$html='';
		$total=0;
		foreach($cart_data as $row){
		    
                                        $prd_product=Products::select('stock_availability','qty_out','vendor_id')
                                        ->where('id','=',$row->prd_id)
                                        ->first();
                                        
                                        if($user_logged_in==1){
                                             $product_delivery_available=DB::table('logistic_vendor_pincode')
                                            ->where('vendor_id',$prd_product->vendor_id)
                                            ->where('pincode',$pincode)
                                            ->where('status',1)
                                            ->first();
                                            if(!$product_delivery_available){
                                             $out_of_delivery++; 
                                              
                                            }
                                        }
                                            // if($prd_product->stock_availability==0){
                                            
                                            // 	$out_of_stock++;
                                            // }
                                            
                                            // if($prd_product->qty_out<$row->qty){
                                            
                                            // 		$out_of_stock++;
                                            // }
					        	
                                        $stock = ProductAttributes::select('qty')
                                        ->where('size_id','=',$row->size_id)
                                        ->where('color_id','=',$row->color_id)
                                        ->where('product_id','=',$row->prd_id)
                                        ->first();
                                         $quantity = $stock ? $stock->qty : 0;
     
                                            if($quantity ==0) {
                                              
                                            		$out_of_stock++;
                                            }
			 $old_prc=$row->master_price;
			if ($row->master_spcl_price!='' && $row->master_spcl_price!=0)
			  {
				  $prc=$row->master_spcl_price;
			  }else{
				  $prc=$row->master_price;
			  }
			  
			  if($row->color_id==0 && $row->size_id!=0){
		    
		    $attr_data=DB::table('product_attributes')
		    ->where('product_id',$row->prd_id)
		     ->where('size_id',$row->size_id)
		    ->first();
		     $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
	 if($row->color_id!=0 && $row->size_id==0){
		     $attr_data=DB::table('product_attributes')
		       ->where('product_id',$row->prd_id)
		     ->where('color_id',$row->color_id)
		    ->first();
		    $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
	     if($row->color_id!=0 && $row->size_id!==0){
		     $attr_data=DB::table('product_attributes')
		    ->where('product_id',$row->prd_id)
		     ->where('color_id',$row->color_id)
		     ->where('size_id',$row->size_id)
		    ->first();
		    $prc+=$attr_data->price;
		     $old_prc+=$attr_data->price;
		}
			  
			  $price_html='';
			    if ($row->master_spcl_price!='' && $row->master_spcl_price!=0)
                {
                     $price_html.='<i class="fa fa-rupee"></i>'.$prc;
               $price_html.='<del><i class="fa fa-rupee"></i>'.$old_prc.'</del>';
                $price_html.='<span class="offer_txt">';
               	$percentage=Products::offerPercentage($old_prc,$prc);
               	$price_html.=$percentage.' % <span>off</span></span>';
                }else{
                $price_html.='<i class="fa fa-rupee"></i>'.$prc;
                }
			  
			$total+=$prc*$row->qty;
            $grand_total+= $total;
            $shipping_charge+=$row->shipping_charges*$row->qty;
			
			 if( $row->color_id!=0){
               $colorImage=DB::table('product_configuration_images')
               ->where('product_id',$row->prd_id)
               ->where('color_id',$row->color_id)
               ->first();
               if($colorImage){
                     $url=Config::get('constants.Url.public_url').Config::get('constants.uploads.product_images').'/'.$colorImage->product_config_image;
                    } else{
                  $url=Config::get('constants.Url.public_url').Config::get('constants.uploads.product_images').'/'.$row->default_image;  
               }
               
            }
           else{
             $url=Config::get('constants.Url.public_url').Config::get('constants.uploads.product_images').'/'.$row->default_image;  
           }
           
                    $size_html='';
                    if($row->size_id!=0)
                    {
                    $s=Products::getAttrName('Sizes',$row->size_id);
                    $size_html='Size : '.$s;
                    }
                $color_html='';
                if($row->color_id!=0)
                {
                $s=Products::getAttrName('Colors',$row->color_id);
                $color_html='Color : '.$s;
                }
                
                $prd_new_id=$row->prd_id.'-'.$row->size_id.'-'.$row->color_id.'-'.$row->qty;
			$html.='<li id="cart_item_row_'.$prd_new_id.'">
			<img src="'.$url.'" alt="item1">
			<span class="item-name">'.$row->name.'</span>
			<span class="item-price">'.$price_html.' </span>
			<br>
			<span class="item-quantity">Quantity :  '.$row->qty.'</span>
			<span class="color-sze">
            '.$color_html.'&nbsp; &nbsp;
            '.$size_html.'</span>
			<span class="item-remove"><a href="javascript:void(0)" class="deleteCartItem" prd_id="'.$prd_new_id.'"><i class="fa fa-trash " ></i></a></span>
		  </li>';
		}
		$ship_amount=$shipping_charges_details->cart_total;
		
		if($ship_amount<$total){
		    $shipping_charge=0;
		} 

	$check_wallet=$request->use_wallet;
	
	if($check_wallet==0)
	{
		$reward_points=0;
	}else{
		
		$wallet_consume_setting=DB::table('wallet_setting')->first();
		$wallet_consume_percent=$wallet_consume_setting->wallet_consume_percent;
	
		$reward_consume_points=round(($total*$wallet_consume_percent)/100,2);
		
		if($reward_points<$reward_consume_points)
		{
			//echo "Not Applicable because minimum wallet require amount ".$reward_consume_points; 
			//$reward_points=0;
			$reward_points=$reward_points;
		}else{
			$reward_points=$reward_consume_points;
		}
		
		/*if($reward_points<=$total)
		{
				$grand_total=($total-$discount-$reward_points);
		}else{
			$grand_total=0;
			$reward_points=$total;
			
		}*/
	}
            if(sizeof($cart_data)==0){
            $ip=$request->ip();
             DB::table('cart_coupon')
                            ->where('user_ip',$ip)
                            ->delete();
            }
	 
                $coupon= DB::table('cart_coupon')
                ->where('user_ip',$ip)
                ->first();
               
                
 	$coupon_array=array();
	if($coupon){
	    
	    $res_data=$this->verifyAppliedCoupon($coupon->coupon_code,$request);
	    if($res_data['Error']==0){
	         if($coupon->coupon_assign_type!=''){
                $coupon_array['coupon_code']=$coupon->coupon_code;
                $coupon_array['discount_value']=$coupon->discount_value;
                $discount=self::getDiscountvalue($coupon->coupon_assign_type,$coupon->coupon_assign_type_id,$ip,$coupon->discount_value);;
	    } else{
                $discount= ( $total*$coupon->discount_value)/100; 
                $coupon_array['coupon_code']=$coupon->coupon_code;
           }     $coupon_array['discount_value']=$coupon->discount_value;
	    } else{
	         $ip=$request->ip();
             DB::table('cart_coupon')
                            ->where('user_ip',$ip)
                            ->delete();
	    }
        
	        
	    }
		
		$coupons=DB::table('coupons')->select(
                        'coupons.coupon_name',
                        'coupons.id',
                        'coupons.coupon_type',
                        'coupons.max_discount',
                        'coupons.below_cart_amt',
                        'coupons.above_cart_amt',
                        'coupons.started_date as fld_coupon_validty_start_date',
                        'coupons.end_date as fld_coupon_validty_end_date',
                        'coupons.discount_value as fld_discount_value',
                        'coupons.description',
                        'coupons.id',
                        'coupon_details.coupon_code as fld_coupon_code',
                        DB::raw("CONCAT('".Config::get('constants.Url.public_url')."uploads/coupon_banner/',banner) AS fld_banner_image")
              )
              ->join('coupon_details','coupon_details.coupon_id','coupons.id')
              ->whereIN('coupons.coupon_type',array(0,1,2,3,4,5,6,7))->where('coupons.status',1)->get();
          $coopon_array=array();
          foreach($coupons as $coupon){
              $is_assign=DB::table('tbl_coupon_assign')->where('fld_coupon_id',$coupon->id)->first();
              $special_for=$coupon->fld_discount_value.' % off';
                $cart_below='';
                $cart_above='';
                $cart_info='';
                  $des='';
              if($is_assign){
                  switch($is_assign->fld_coupon_assign_type){
                            // category 
                             case 1:
                                 $dt=Category::where('id',$is_assign->fld_assign_type_id)->first();
                                $special_for.=' on '.$dt->name;
                            break;
                            
                            // brand 
                            case 2:
                                  $dt=Brands::where('id',$is_assign->fld_assign_type_id)->first();
                                $special_for.=' on '.$dt->name;
                            break;
                            
                             // product 
                            case 3:
                                 $dt=Products::where('id',$is_assign->fld_assign_type_id)->first();
                                $special_for.=' on '.$dt->name;
                            break;
                      
                  }
              } 
              
              $couponExpired=0;
              switch($coupon->coupon_type){
                        
                            // static with cart
                    case 2:
                    case 3:
                    case 6:
                    case 7:
                                    $cart_below=$coupon->fld_coupon_validty_start_date;
                                    $cart_above=$coupon->fld_coupon_validty_end_date;
                                    
                                    $today_date=date('Y-m-d');
                                    $expiry_date=$coupon->fld_coupon_validty_end_date;
                                    
                                    $expiry_date_stamp = strtotime($expiry_date); 
                                    $today_date_stamp = strtotime($today_date); 
                                  
                                    if($today_date_stamp>$expiry_date_stamp){
                                     $couponExpired=1;
                                    } 
                             break;
                             
                    case 1:
                    case 3:
                    case 5:
                    case 7:
                                    $cart_info='cart total should be between ('.$coupon->below_cart_amt.' '.$coupon->above_cart_amt.' )';
                             break;
                            
                             
                            
                          
                      
                  }
                  
                  if($couponExpired==0){
                        $des=$coupon->description;;
                        $single_data['fld_coupon_id']=$coupon->id;
                        $single_data['fld_coupon_name']=$coupon->coupon_name;
                        $single_data['fld_coupon_attr_name']=$special_for;
                        $single_data['fld_coupon_cart_below']=$cart_below;
                        $single_data['fld_coupon_cart_above']=$cart_above;
                        $single_data['fld_coupon_cart_info']=$cart_info;
                        $single_data['fld_coupon_validty_start_date']=$coupon->fld_coupon_validty_start_date;
                        $single_data['fld_coupon_validty_end_date']=$coupon->fld_coupon_validty_end_date;
                        $single_data['fld_description']=$des;
                        $single_data['fld_coupon_code']=$coupon->fld_coupon_code;
                        $single_data['fld_coupon_image']=$coupon->fld_banner_image;
                        array_push($coopon_array,$single_data);
                  }
				      
              
          }	

	   
		

		$response=array(
				"html"=>$html,
				 "size"=>sizeof($cart_data),
                    "total"=>round($total),
                    'coupon_code'=>($coupon_array)?$coupon_array['coupon_code']:'',
                    'coupon_percent'=>($coupon_array)?$coupon_array['discount_value']:'',
                      'out_of_stock'=>$out_of_stock,
                      "grand_total_with_tax"=>round($grand_total=($total+$tax+$shipping_charge-$discount-$reward_points)),
                    "tax"=>$tax,
                    "user_logged_in"=>$user_logged_in,
                    "out_of_delivery"=>$out_of_delivery,
                    "shipping_charge"=>round($shipping_charge),
                    "discount"=>round($discount),
					"reward_points"=>($reward_points),
				 "cart_list_view"=>view("fronted.mod_cart.ajax.back_response_cart_list",array(
				     'cart'=>$cart_data,
					 'coupons'=>$coopon_array,
				     'isActivate'=>$coupon_array
				     ) )->render(),
				 "review_order"=>view("fronted.mod_checkout.ajax.back_response_review_order",array(
				     'cart_data'=>$cart_data,
				     
				     ) )->render()
				);
				echo json_encode($response);
	
	}
	
	public function add_to_savelater(Request $request){
            $input=$request->all();
            
            $dt=$input['prd_id'];
            $cart_data=explode('-',$dt);
        
		
            $user_id=auth()->guard('customer')->user()->id;
	   
		$is_exist=DB::table('tbl_save_later')
		->select('fld_save_later_id')
        ->where('fld_product_id','=',$cart_data[0])
          ->where('size_id','=',$cart_data[1])
        ->where('color_id','=',$cart_data[2])
        ->where('fld_user_id','=',$user_id)
		->first();
		$inputs=array(
            'fld_product_id' =>$cart_data[0],
            'color_id' =>$cart_data[1],
            'size_id' =>$cart_data[2],
			'fld_user_id' =>$user_id
			);
		if($is_exist){
			
				app(\App\Http\Controllers\CookieController::class)->deleteCartItem($input); 
			$method=2;
			 $res=DB::table('tbl_save_later')
                    ->where('fld_product_id','=',$cart_data[0])
                    ->where('fld_user_id','=',$user_id)
                    ->update($inputs);
		} else{
			$method=1;
				app(\App\Http\Controllers\CookieController::class)->deleteCartItem($input); 
			$res=DB::table('tbl_save_later')->insert($inputs);
		}
		
		if($res){
		      
			$response=array(
				"status"=>true,
				"method"=>$method
				);
		} else{
			$response=array(
				"status"=>false,
				"method"=>$method
				);
		}
		echo json_encode($response);
	 }
	 
	 public function savelater(Request $request){
		
		
		if(Auth::guard('customer')->check())
		{
			$cust_id=auth()->guard('customer')->user()->id ; 
			$cart_data=self::getCart_item($request->ip(),$cust_id);
		}else{
			$cart_data=self::getCart_item($request->ip());
			
		}
		return view('fronted.mod_cart.list',["cart"=>$cart_data]);
	
	}
    

	  
}
