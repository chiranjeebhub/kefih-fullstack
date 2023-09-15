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
use App\CouponDetails;
use App\CheckoutShipping;

class CheckOutController extends Controller 
{
	public $successStatus = 200;
	
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
		
		$coupondata =CouponDetails::select(
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
	   ->where('coupon_details.coupon_code',$input['coupon_code'])
	   ->where('coupon_details.coupon_used',0)
	   ->first();
	   if($coupondata){
	       if($coupondata->coupon_type==0 || $coupondata->coupon_type==4){
	            $response=$this->msg_sucess_info('Coupon Applied', $coupondata);	
	       } else{
	            $response=$this->validation_coupon($coupondata,$input);
	       }
	   } else{
	       $response=array(
                "status"=>true,
                "statusCode"=>404,
                "message"=>"Coupon Applied ",
                "Msg"=>"Coupon code invalid",
                "Coupon_data"=>null
				);
	   }
	  
	    echo json_encode($response);	
	}
	
	public function save_order(Request $request) {
		
		$input = json_decode(file_get_contents('php://input'), true);
	
	   $cust_id=$input['fld_user_id'];
       $shipping_address_id=$input['fld_shipping_id'];
	   
	   $check_product=$input['fld_purchase_type'];
	   
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
						'products.delivery_days as fld_delivery_days',
						'products.shipping_charges as fld_shipping_charges')
						->where('products.id',$input['fld_product_id'])
						->first();
									
		   $cart_data[]=(object) array(
							'fld_product_id'=>$input['fld_product_id'],
							'fld_product_name'=>$prd_info->fld_product_name,
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
					
		$order=array(
                'customer_id'=>$cust_id,
                'shipping_id'=>$shipping_id,
                'order_no'=>$order_no,
                'grand_total'=>$input['fld_grand_total'],
                'coupon_code'=>$input['fld_coupon_code'],
                'coupon_percent'=>$input['fld_coupon_percent'],
                'coupon_amount'=>$input['fld_discount_amount'],
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
         	
		 $grand_total=$total_reward_points=0;
		 
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
			  
			 $order_detail=array(
				'suborder_no'=>$order_no.'_item_'.$i,
				'order_id'=>$order_id,
				'product_id'=>$cart_data[$i]->fld_product_id,
				'product_name'=>$cart_data[$i]->fld_product_name,
				'product_qty'=>$cart_data[$i]->fld_product_qty,
                'product_price'=>$prc,
                'product_price_old'=>$old_price,
			    'size'=> Products::getSizeName($cart_data[$i]->fld_product_size ),
                'color'=> Products::getcolorName($cart_data[$i]->fld_product_color ),
                'size_id'=>$cart_data[$i]->fld_product_size,
                'color_id'=>$cart_data[$i]->fld_product_color,
				'order_reward_points'=>($reward_points==null || $reward_points=='')?0:$reward_points,
				'order_status'=>$order_sts
				);
				DB::table('order_details')->insert($order_detail);
				$order_detail_id=DB::getPdo()->lastInsertId();
				
				$grand_total+=$cart_data[$i]->fld_product_qty*$prc;
				$total_reward_points+=($reward_points==null || $reward_points=='')?0:$reward_points;
				
		 }
		 
		 
		  if($input['fld_payment_mode']==1){
        if($input['fld_txn_status']=='true'){
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
		if($input['fld_payment_mode']==0){ //cod
			
			
			$response=array(
				"status"=>true,
				"statusCode"=>201,
				"message"=>"Your Order is placed succesfully",
				"order_id"=>$order_id
				);
		}else{
		    
		      if($input['fld_txn_status']=='true'){
		          	$response=array(
				"status"=>true,
				"statusCode"=>201,
				"message"=>"Your Order is placed succesfully",
				"order_id"=>$order_id
				);
		      } else{
		          	$response=array(
				"status"=>false,
				"statusCode"=>404,
				"message"=>"Placed order failed",
				"order_id"=>''
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