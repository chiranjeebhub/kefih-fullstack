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
use App\Coupon;
use App\Products;
use App\ProductAttributes;
use App\Colors;
use App\Sizes;
use App\ProductCategories;
use App\ProductImages;
use App\Category;
use App\CouponDetails;
use App\CheckoutShipping;
use Config;
class CheckOutController extends Controller 
{
	public $successStatus = 200;
		public $site_base_path='http://aptechbangalore.com/test/';
		
		public function __construct()
    {
        $this->site_base_path=Config::get('constants.Url.public_url');
        //$this->middleware('auth');
    }
	/** 
     * Category Listing api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
     
     	public function validation_coupon($obj,$input){
	    
	    switch($obj->coupon_type){
	          
	        case ($obj->coupon_type==3 || $obj->coupon_type==7): // check date and cart amount 
	        
				$paymentDate = date('Y-m-d');
				$paymentDate=date('Y-m-d', strtotime($paymentDate));
				$contractDateBegin = date('Y-m-d', strtotime($obj->started_date) );
				$contractDateEnd = date('Y-m-d', strtotime($obj->end_date));

				if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){ // check date 
					
					if (($input['cart_total'] >= $obj->below_cart_amt) && ($input['cart_total'] <= $obj->above_cart_amt)){  // check cart amount
							$response=$this->msg_sucess_info('Coupon Applied', $obj);
					}else{
							$response=$this->msg_failed_info('Not valid for this cart total', null);
					} 
					
				}else{
							$response=$this->msg_failed_info('Coupon code invalid', null);
				}
	        
	        break;
				
			case ($obj->coupon_type==2 || $obj->coupon_type==6):  // check date
	           
				$paymentDate = date('Y-m-d');
				$paymentDate=date('Y-m-d', strtotime($paymentDate));
				$contractDateBegin = date('Y-m-d', strtotime($obj->started_date) );
				$contractDateEnd = date('Y-m-d', strtotime($obj->end_date));

				if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){
				   
					$response=$this->msg_sucess_info('Coupon Applied', $obj);			  
				}else{
					$response=$this->msg_failed_info('Coupon code invalid', null);				
				}
	         
			 break;
			    
	         case ($obj->coupon_type==1 || $obj->coupon_type==5):  // check  cart amount
	            
				if (($input['cart_total'] >= $obj->below_cart_amt) && ($input['cart_total'] <= $obj->above_cart_amt)){  // check cart amount
			   
					$response=$this->msg_sucess_info('Coupon Applied', $obj);	
				}  else{
					$response=$this->msg_failed_info('Not valid for this cart total', null);	
				} 
	         break;
				
	    }
	  return   $response;
			
	}
    public function applyCoupon(Request $request) {
		$input = json_decode(file_get_contents('php://input'), true);
	
	
	$output=array(
            "Error"=>1,
            "Msg"=>"",
            //"cart_total"=>"",
            "coupon_code"=>"",
            "discount_percent"=>0,
            "discount_amount"=>0
	    );
		   $res_data=$this->verifyAppliedCoupon($input);
               
		   if($res_data['Error']==0){
		        $coupondata =CouponDetails::select(
										'coupons.discount_value',
										'coupon_details.coupon_code'
									)
								->join('coupons','coupons.id','coupon_details.coupon_id')
								->where('coupon_details.coupon_code',$input['coupon_code'])
								->where('coupon_details.coupon_used',0)
								->where('coupons.status',1)
								->first();
								
                $total=$res_data['cart_total'];
                
                if($total>0){
                    $discount= ( $total*$coupondata->discount_value)/100; 
                    $coupon_array['coupon_code']=$coupondata->coupon_code;
                    $coupon_array['discount_value']=$coupondata->discount_value;
                    
                    
                    $output=array(
                    "Error"=>$res_data['Error'],
                    "Msg"=>$res_data['Msg'],
                    "coupon_code"=>$coupondata->coupon_code,
                    "discount_percent"=>$coupondata->discount_value,
                    "discount_amount"=>round($discount)
                    );
                } else{
                        $output=array(
                        "Error"=>1,
                        "Msg"=>"Cart total invalid",
                        "coupon_code"=>"",
                        "discount_percent"=>0,
                        "discount_amount"=>0
                        );  
                }
               
               
		   }
		   else{
                    $output=array(
                    "Error"=>$res_data['Error'],
                    "Msg"=>$res_data['Msg'],
                    "coupon_code"=>"",
                    "discount_percent"=>0,
                    "discount_amount"=>0
                    );  
		   }
		   
	echo json_encode($output);	
	}
	
	
	public function applyCoupon2(Request $request) {
				    
		$input = json_decode(file_get_contents('php://input'), true);
	
	$output=array(
            "Error"=>"",
            "Msg"=>"",
            "cart_total"=>"",
            "coupon_code"=>"",
            "discount_percent"=>"",
            "discount_amount"=>""
	    );
		   $res_data=$this->verifyAppliedCoupon($input);
               
		   if($res_data['Error']==0){
		        $coupondata =CouponDetails::select(
                    'coupons.discount_value',
                    'coupon_details.coupon_code'
	          )
	   ->join('coupons','coupons.id','coupon_details.coupon_id')
	   ->where('coupon_details.coupon_code',$input['coupon_code'])
	   ->where('coupon_details.coupon_used',0)
	    ->where('coupons.status',1)
	   ->first();
                $total=$res_data['cart_total'];
                $discount= ( $total*$coupondata->discount_value)/100; 
                $coupon_array['coupon_code']=$coupondata->coupon_code;
                $coupon_array['discount_value']=$coupondata->discount_value;
             
                  
                  	$output=array(
            "Error"=>$res_data['Error'],
            "Msg"=>$res_data['Msg'],
            "coupon_code"=>$coupondata->coupon_code,
            "discount_percent"=>$coupondata->discount_value,
            "discount_amount"=>$discount
	    );
               
		   }
		   else{
                    $output=array(
                    "Error"=>$res_data['Error'],
                    "Msg"=>$res_data['Msg'],
                    "coupon_code"=>$coupondata->coupon_code,
                    "discount_percent"=>"",
                    "discount_amount"=>""
                    );  
		   }
		   
	echo json_encode($output);
		  
	}
	

	
		public function verifyAppliedCoupon($input_array){
	      
	    $code=$input_array['coupon_code'];
	     $user_id=$input_array['fld_user_id'];
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
	    switch($obj->fld_coupon_assign_type){
	         
                case 1: /// category wise assign 
                $categoryProductIncarts=DB::table('cart')->select('cart.*')
                         ->join('products','products.id','cart.prd_id')
                         ->join('product_categories','product_categories.product_id','products.id')
                         ->join('categories','product_categories.cat_id','categories.id')
                        ->where('user_ip',$user_id)
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
                               $response= $this->validation_coupon_whencart_changes($coupondata,$input);
                               
                        } else{
                            $response=array(
                                "Error"=>1,
                                "Msg"=>"Coupon code invalid",
                                 "cart_total"=>0
                            );
                        
                        }
                break;
                
                case 2: // brand wise assign
               
               
                $brandProductIncarts=DB::table('cart')->select('cart.*')
                         ->join('products','products.id','cart.prd_id')
                         ->join('brands','products.product_brand','brands.id')
                          ->where('user_ip',$user_id)
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
                                $response=$this->validation_coupon_whencart_changes($coupondata,$input);
                                
                        } else{
                            $response=array(
                                "Error"=>1,
                                "Msg"=>"Coupon code invalid",
                                 "cart_total"=>0
                            );
                          
                        }
                break;
                
                case 3: // product wise assign
                 
                       
                      
                      
                       
                        $prdIncart=DB::table('cart')
                        ->where('user_ip',$user_id)
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
                                $response=$this->validation_coupon_whencart_changes($coupondata,$input);
                              
                        } else{
                            $response=array(
                                "Error"=>1,
                                "Msg"=>"Coupon code invalid",
                                "cart_total"=>0
                            );
                            
                        }
                       
                          
                break;
	        
	    }
                
	       } else{
	           
	          
	           $input['cart_total']=$this->getCartTotal($input_array['fld_user_id']);
	          $response=$this->validation_coupon_whencart_changes($coupondata,$input);
	           
	       }
	       
	   } else{
	       $response=array(
				"Error"=>1,
				"Msg"=>"Coupon code invalid",
				"cart_total"=>0
				);
				
	   }
	  
	    return $response;
	}
	public function getCartTotal($user_id){
            $cart_data=DB::table('cart')
            ->select(
                'cart.prd_id as prd_id',
                'cart.size_id as size_id',
                'cart.color_id as color_id',
                'cart.qty as qty',
                'products.default_image',
                'products.name',
                'products.price as master_price',
                'products.spcl_price as master_spcl_price',
                'products.delivery_days as delivery_days',
                'products.shipping_charges as shipping_charges')
                ->join('products','products.id','cart.prd_id')
            ->where('user_id',$user_id)
            ->get();
           
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
	public function validation_coupon_whencart_changes($obj,$input){
	    switch($obj->coupon_type){
	          
	           case 0:
	           case 4:
	                 $response=array(
				"Error"=>0,
				"Msg"=>"Coupon Applied ",
				 "cart_total"=> $input['cart_total']
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
				"Msg"=>"Coupon Applied ",
				 "cart_total"=> $input['cart_total']
				);
   
    
}  else{
    $response=array(
				"Error"=>1,
				"Msg"=>"Not valid for this  total ",
				 "cart_total"=>0
				);
} 
    
}else{
    $response=array(
				"Error"=>1,
				"Msg"=>"Coupon code invalid ",
				 "cart_total"=>0
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
				"Msg"=>"Coupon code Applied ",
				 "cart_total"=> $input['cart_total']
				);
				  
}else{
    $response=array(
				"Error"=>1,
				"Msg"=>"Coupon code invalid ",
				 "cart_total"=>0
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
				"Msg"=>"Coupon Applied",
				 "cart_total"=> $input['cart_total']
				);
   
    
}  else{
    $response=array(
				"Error"=>1,
					"Msg"=>"Not valid for this  total",
					 "cart_total"=>0
				);
} 
	            break;
	            
	    }
	 return $response;
			
	}
	public function save_order(Request $request) {
		
		$input = json_decode(file_get_contents('php://input'), true);
		
		file_put_contents('save_order.txt',json_encode($input));
	
	   $cust_id=$input['fld_user_id'];
       $shipping_address_id=$input['fld_shipping_id'];
	   $check_product=$input['fld_purchase_type'];
	   
	   $coupon_code=$input['fld_coupon_code'];
	   
		$transaction_id='';
		$transaction_sts='';
		$order_sts='';
      if($input['fld_payment_mode']==1){
		  if($input['fld_txn_status']=='true'){
                $transaction_id=$input['fld_txn_id'];
                $order_sts=0;
                $transaction_sts=$input['fld_txn_status'];
		  } else{
				$transaction_id='';
				$order_sts=7;
				$transaction_sts=$input['fld_txn_status'];
		  }  
          
      } else{
			$transaction_id=$input['fld_txn_id'];
			$order_sts=0;
			$transaction_sts=$input['fld_txn_status'];
      }
      
	   if($check_product==1)
	   {
		   //buy_now
		   $prd_info=DB::table('products')
		                ->select(
						'products.name as fld_product_name',
						'products.product_tax as fld_product_tax',
						'products.delivery_days as fld_delivery_days',
						'products.shipping_charges as fld_shipping_charges')
						->where('products.id',$input['fld_product_id'])
						->first();
									
		   $cart_data[]=(object) array(
							'fld_product_id'=>$input['fld_product_id'],
							'fld_product_name'=>$prd_info->fld_product_name,
							'fld_product_tax'=>$prd_info->fld_product_tax,
							'fld_product_qty'=>1,
							'fld_product_price'=>$input['fld_product_price'],
							'fld_spcl_price'=>$input['fld_spcl_price'],
							'fld_product_color'=>$input['fld_product_color'],
							'fld_product_size'=>$input['fld_product_size'],
							'fld_delivery_days'=>$prd_info->fld_delivery_days,
							'fld_shipping_charges'=>$prd_info->fld_shipping_charges,
						);
					
	   }else if($check_product==2){
		   
		   //addtocart
		   $cart_data = DB::table('cart')
									->select(
												'products.name as fld_product_name',
												'products.product_tax as fld_product_tax',
												'cart.id as fld_cart_id',
												'cart.qty as fld_product_qty',
												'products.price as fld_product_price',
												'products.spcl_price as fld_spcl_price',
												'cart.color_id as fld_product_color',
												'cart.size_id as fld_product_size',
												'cart.prd_id as fld_product_id',
												'products.delivery_days as fld_delivery_days',
												'products.shipping_charges as fld_shipping_charges',
												DB::raw("CONCAT('".$this->site_base_path."uploads/products/',products.default_image) AS default_image")
											)
									->join('products','cart.prd_id','products.id')
									->where('cart.user_id',$cust_id)
									->get()
									->toarray();
	   }
		
		if($shipping_address_id==0){
		  
			$shipping_adddress= Customer::select(
												'name as shipping_name',
												'phone as shipping_mobile',
												'email as shipping_email',
												'address as shipping_address',
												'address1 as shipping_address1',
												'address2 as shipping_address2',
												'city as shipping_city',
												'state as shipping_state',
												'pincode as shipping_pincode'
									)->where('id',$cust_id)->first();
		} else{
		    $shipping_adddress=CheckoutShipping::where('id',$shipping_address_id)->first();
		}
		
        $order_no='18UP'.date('YmdHis');
         
        $order_shipping=array(
				'order_id'=>'',
				'order_shipping_name'=>$shipping_adddress->shipping_name,
				'order_shipping_address'=>$shipping_adddress->shipping_address,
				'order_shipping_address1'=>$shipping_adddress->shipping_address1,
				'order_shipping_address2'=>$shipping_adddress->shipping_address2,
				'order_shipping_city'=>$shipping_adddress->shipping_city,
				'order_shipping_state'=>$shipping_adddress->shipping_state,
				'order_shipping_zip'=>$shipping_adddress->shipping_pincode,
				'order_shipping_phone'=>$shipping_adddress->shipping_mobile,
				'order_shipping_email'=>$shipping_adddress->shipping_email
			);
			
		DB::table('orders_shipping')->insert($order_shipping);
		$shipping_id=DB::getPdo()->lastInsertId();
		
		$wallet_consume_percent=0;
		$order_total_check=0;
		if($input['fld_wallet_amount']!=0)
		{
			$wallet_setting=DB::table('wallet_setting')->first();
			
			$wallet_consume_percent=$wallet_setting->wallet_consume_percent;
			$order_total_check=$input['fld_grand_total']+$input['fld_discount_amount']-$input['fld_shipping_charges'];
		}		
		
		$order=array(
                'customer_id'=>$cust_id,
                'shipping_id'=>$shipping_id,
                'order_no'=>$order_no,
                'grand_total'=>$input['fld_grand_total'],
                'coupon_code'=>$input['fld_coupon_code'],
                'coupon_percent'=>$input['fld_coupon_percent'],
                'coupon_amount'=>$input['fld_discount_amount'],
				'deduct_wallet_percent'=>$wallet_consume_percent,
				'deduct_reward_points'=>$input['fld_wallet_amount'],
                'total_shipping_charges'=>$input['fld_shipping_charges'],
                'payment_mode'=>$input['fld_payment_mode'],
                'tax_percent'=>$input['fld_tax'],
                'txn_id'=>$transaction_id,
                'txn_status'=>$transaction_sts,
                'order_status'=>$order_sts
			);
			
         	DB::table('orders')->insert($order);
         	$order_id=DB::getPdo()->lastInsertId();
         	
			DB::table('orders_shipping')->where('id',$shipping_id)
										->update(
											array(
												'order_id'=>$order_id
											));
         	
		 $grand_total=$total_reward_points=$product_commission_master=0;
		 
		 for($i=0;$i<count($cart_data);$i++)
		 {
			 $prd_points=DB::table('product_reward_points')->where('product_id',$cart_data[$i]->fld_product_id)->first();
			 $prc=0;
			 $old_price=$cart_data[$i]->fld_product_price;;
			 $reward_points=$prd_points->reward_points;
			 
			  if ($cart_data[$i]->fld_spcl_price!='')
			  {
				  $prc=$cart_data[$i]->fld_spcl_price;
				 
			  }else{
				  $prc=$cart_data[$i]->fld_product_price;
			  }
			  
			$product_shipping_charges=0;
			$product_commission_rate=0;
			 
			$ship_data=Products::productDetails($cart_data[$i]->fld_product_id);
			$commission_data=Products::productsFirstCatData($cart_data[$i]->fld_product_id);
			
			$product_commission_rate=$commission_data->commission_rate;
			$product_commission_master+=$product_commission_rate;
			
			if(@$input['fld_shipping_charges']>0){
				 $product_shipping_charges=$ship_data->shipping_charges;
			} else{
				$product_shipping_charges=0;  
			}
			  
			$tax=$ship_data->product_tax;
			
			$order_deduct_reward_points=0;
			if($order_total_check!=0)
			{
				$order_deduct_reward_points=round((($prc/$order_total_check)*$input['fld_wallet_amount']),2);
			}
			
			$product_coupon_amt=0;
			
			if($coupon_code!='')
			{
				$product_removed=array();
				array_push($product_removed,$cart_data[$i]->fld_cart_id);
				
				$ss=Coupon::verifyAppliedCouponProductDiscountAmt($coupon_code,$cust_id,$product_removed);
				
				if(@$ss['ProductCouponCartAmount']!='')
				{
					$product_coupon_amt=round(@$ss['ProductCouponCartAmount']);
				}
			}
			
			$order_detail=array(
								'suborder_no'=>$order_no.'_item_'.$i,
								'order_id'=>$order_id,
								'product_id'=>$cart_data[$i]->fld_product_id,
								'product_name'=>$cart_data[$i]->fld_product_name,
								'product_tax'=>$cart_data[$i]->fld_product_tax,
								'product_qty'=>$cart_data[$i]->fld_product_qty,
								'product_price'=>$prc,
								'product_price_old'=>$old_price,
								'product_tax'=>$tax,
								'size'=> Products::getSizeName($cart_data[$i]->fld_product_size ),
								'color'=> Products::getcolorName($cart_data[$i]->fld_product_color ),
								'size_id'=>$cart_data[$i]->fld_product_size,
								'color_id'=>$cart_data[$i]->fld_product_color,
								'order_reward_points'=>($reward_points==null || $reward_points=='')?0:$reward_points,
								'order_deduct_reward_points'=>$order_deduct_reward_points,
								'order_coupon_amount'=>$product_coupon_amt,
								'order_shipping_charges'=>$product_shipping_charges,
								'order_commission_rate'=>$product_commission_rate,
								'return_days'=>$ship_data->return_days,
								'order_status'=>$order_sts
							);
			DB::table('order_details')->insert($order_detail);
			$order_detail_id=DB::getPdo()->lastInsertId();
			
			$grand_total+=$cart_data[$i]->fld_product_qty*$prc;
			$total_reward_points+=($reward_points==null || $reward_points=='')?0:$reward_points;	
		 }
		 
		if($input['fld_payment_mode']==1){
			if($input['fld_txn_status']=='true'){
					 
					if($order['deduct_reward_points']!=0 && $order['deduct_reward_points']!=''){
						$wallet=array(
							'fld_customer_id'=>$cust_id,
							'fld_order_id'=>$order_id,
							'fld_order_detail_id'=>0,
							'fld_reward_narration'=>'Deducted',
							'fld_reward_deduct_points'=>$order['deduct_reward_points']
						);
				
						DB::table('tbl_wallet_history')->insert($wallet);
					}
			 
					if($total_reward_points!=0 && $total_reward_points!='')
					{
						 $wallet=array(
									'fld_customer_id'=>$cust_id,
									'fld_order_id'=>$order_id,
									'fld_order_detail_id'=>0,
									'fld_reward_narration'=>'Earned',
									'fld_reward_points'=>$total_reward_points
								);
						
						 DB::table('tbl_wallet_history')->insert($wallet);
					}
			
					$cust_points=DB::table('customers')->where('id',$cust_id)->first();
					
					if($cust_points->total_reward_points!=0 && $cust_points->total_reward_points!='')
					{
						$deduct_amt=$cust_points->total_reward_points-$order['deduct_reward_points'];
					}else{
						$deduct_amt=0;
					}
					
			
			DB::table('customers')->where('id',$cust_id)
					->update(
						array(
							'total_reward_points'=>($deduct_amt+$total_reward_points)
						));
		  }  
			  
		  } else{
				 if($order['deduct_reward_points']!=0){
					 $wallet=array(
								'fld_customer_id'=>$cust_id,
								'fld_order_id'=>$order_id,
								'fld_order_detail_id'=>0,
								'fld_reward_narration'=>'Deducted',
								'fld_reward_deduct_points'=>$order['deduct_reward_points']
							);
					
					 DB::table('tbl_wallet_history')->insert($wallet);
				 }
			 
				 $wallet=array(
							'fld_customer_id'=>$cust_id,
							'fld_order_id'=>$order_id,
							'fld_order_detail_id'=>0,
							'fld_reward_narration'=>'Earned',
							'fld_reward_points'=>$total_reward_points
						);
				
				DB::table('tbl_wallet_history')->insert($wallet);
			 
				$cust_points=DB::table('customers')->where('id',$cust_id)->first();
				$deduct_amt=$cust_points->total_reward_points-$order['deduct_reward_points'];
				
				DB::table('customers')->where('id',$cust_id)
										->update(
											array(
												'total_reward_points'=>($deduct_amt+$total_reward_points)
											));      
		  }
	     		    
		DB::table('cart')->where('user_id',$cust_id)->delete();
		
		$stock_data=DB::table('order_details')->where('order_id',$order_id)->get();
		for($i=0;$i<count($stock_data);$i++)
		{
			 $prd_id=$stock_data[$i]->product_id;
			 $size_id=$stock_data[$i]->size_id;
			 $color_id= $stock_data[$i]->color_id;
			 $qty=$stock_data[$i]->product_qty; 
			 Products::decreaseProductQty($prd_id,$size_id,$color_id,$qty);
		}
			
			
		if($input['fld_payment_mode']==0){ //cod
			
			
			$response=array(
				"status"=>true,
				"statusCode"=>201,
				"message"=>"Your Order is placed succesfully",
				"success_order_id"=>$order_no
				);
		}else{
		    
		      if($input['fld_txn_status']=='true'){
		          	$response=array(
				"status"=>true,
				"statusCode"=>201,
				"message"=>"Your Order is placed succesfully",
				"success_order_id"=>$order_no
				);
		      } else{
		          	$response=array(
				"status"=>false,
				"statusCode"=>404,
				"message"=>"Placed order failed",
				"success_order_id"=>''
				);
		      }
		
		}
		
		echo json_encode($response);
		
	}
	
	public function msg_sucess_info($msg, $data)
	{
	     $res=array(
                "status"=>true,
                "statusCode"=>201,
                "message"=>$msg,
                "Coupon_data"=>$data
				);
	
		
		return ($res);
	}
	
	public function msg_failed_info($msg, $data)
	{
	     $res=array(
                "status"=>false,
                "statusCode"=>404,
                "message"=>$msg,
                "Coupon_data"=>$data
				);
	
		
		return ($res);
	}
	
	

}