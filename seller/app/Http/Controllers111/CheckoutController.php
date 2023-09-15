<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Redirect;
use Validator;
use DB;
use Config;
use Auth;
use Cookie;
use App\Coupon;
use App\CheckoutShipping;
use App\Customer;
use App\Colors;
use App\Products;
use App\Sizes;
use App\Orders;
use App\OrdersDetail;
use App\OrdersShipping;
use App\CouponDetails;
use App\Helpers\MsgHelper;
use App\Helpers\CommonHelper;

error_reporting(0);
class CheckoutController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        	$this->middleware('auth:customer');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     
    public function index(Request $request)
    {
        $id=auth()->guard('customer')->user()->id ;
        
        $cart_data=app(\App\Http\Controllers\cart\CartController::class)->getCart_item($request->ip(), $id);
        if(sizeof($cart_data)==0){
              return Redirect::route('index');
        }
          $states=CommonHelper::getState('101');
		$ship_address_list = CheckoutShipping::getshippingAddress($id);
		return view('fronted.mod_checkout.checkout',[
		    'shipping_listing'=>$ship_address_list,
		    'states'=>$states
		    ]);
    }
	
		public function selectShippingAddress(Request $request)
    {
        $minutes=(8640 * 30);
        
            $shipping_id=base64_decode($request->shipping_id);
        
           
setcookie('shipping_address_id', $shipping_id, time() + (86400 * 30), "/"); 
      
        
          $res= CheckoutShipping::
             where('id',$shipping_id)
           ->first();
            if($res){


                
                $inputs=array(
                    "pincode"=>$res->shipping_pincode,
                    "price"=>200,
                    "product_name"=>"apple",
                    "qty"=>1,
                    "weight"=>2,
                    "height"=>2,
                    "length"=>2,
                    "width"=>2
                    );
                    
                    $back_response=CommonHelper::checkDelivery($inputs);
            
                     $output = (array)json_decode($back_response);
                   
                     if (array_key_exists("couriers",$output))
                    {
  
setcookie('pincode', $res->shipping_pincode, time() + (86400 * 30), "/");
setcookie('pincode_error', 0, time() + (86400 * 30), "/");
                     } else{
                        
setcookie('pincode', $res->shipping_pincode, time() + (86400 * 30), "/");
setcookie('pincode_error', 1, time() + (86400 * 30), "/");
                        
                    }
   
                
            }
            return Redirect::route('review_order');
        
    }
		public function editShippingAddress(Request $request)
    {
		$id=base64_decode($request->shipping_id);
		
		
			if ($request->isMethod('post')) {
			    $input=$request->all();
			   
			    $request->validate([
            'shipping_name' => 'required|max:50',
            'shipping_mobile' => 'required|max:10',
           'shipping_address' => 'required|max:110',
            'shipping_address1' => 'max:110',
            'shipping_address2' => 'max:110',
            'shipping_city' => 'required|max:50', 
            'shipping_state' => 'required|max:50',
            'shipping_pincode' => 'required|max:6',
            'shipping_address_type' => 'required'
            ],[
'shipping_name.required' =>'Name is required',
'shipping_name.max' =>'Name can not exceed to 50 characters',
'shipping_address.regex' =>'Shipping Address Must have atleast one digit and one alphabet',
'shipping_mobile.required' =>'Mobile is required',
'shipping_mobile.max' =>'Mobile can not exceed to 50 characters',
'shipping_address.required' =>'Address is required',
'shipping_address.max' =>'Address can not exceed to 50 characters',
'shipping_pincode.required' =>'Pincode is required',
'shipping_pincode.max' =>'Pincode can not exceed to 50 characters',
'shipping_state.required' =>'State is required',
'shipping_state.max' =>'State can not exceed to 50 characters',
'shipping_city.required' =>'City is required',
'shipping_city.max' =>'City can not exceed to 50 characters',
'shipping_address1.max' =>'Area can not exceed to 50 characters',
'shipping_address2.max' =>'Landmark can not exceed to 50 characters',
'shipping_address_type.required' =>'Shipping type is required',
             ]
			);
			
		
			 $states=DB::table('states')->where('id',$input['shipping_state'])->first();
			 
			$input_array=
			    	array('shipping_name' => $input['shipping_name'],
			'shipping_mobile' => $input['shipping_mobile'],
			'shipping_address' => $input['shipping_address'],
			'shipping_address1' => $input['shipping_address1'],
			'shipping_address2' => $input['shipping_address2'],
			'shipping_city' => $input['shipping_city'],
			'shipping_state' => $states->name,
			'shipping_pincode' => $input['shipping_pincode'],
			'shipping_address_type' => $input['shipping_address_type'],
			'shipping_address_default' => isset($input['shipping_address_default']) ? 1 : 0
			
			
			);
			
	
			if($input_array['shipping_address_default']==1)
			{
    			$cust_id=auth()->guard('customer')->user()->id ;
    			
    			$input_default_set=array( 'shipping_address_default' => 0);
    			CheckoutShipping::where('customer_id',$cust_id)->update($input_default_set);
			}
			
		  $rs=CheckoutShipping::where('id',$id)->update($input_array);
		  
		  /* save the following details */
		  if($rs){
		      
		      if($id==@$_COOKIE["shipping_address_id"]){
		          $inputs=array(
                    "pincode"=>$input['shipping_pincode'],
                    "price"=>200,
                    "product_name"=>"apple",
                    "qty"=>1,
                    "weight"=>2,
                    "height"=>2,
                    "length"=>2,
                    "width"=>2
                    );
                     $back_response=CommonHelper::checkDelivery($inputs);
                     $output = (array)json_decode($back_response);
                     if (array_key_exists("delivery_details",$output))
                    {
setcookie('pincode', $res->shipping_pincode, time() + (86400 * 30), "/");
setcookie('pincode_error', 0, time() + (86400 * 30), "/");
                     } else{
       setcookie('pincode', $res->shipping_pincode, time() + (86400 * 30), "/");
       setcookie('pincode_error', 1, time() + (86400 * 30), "/");
             
                    }
		          
		      }
		      MsgHelper::save_session_message('success',Config::get('messages.common_msg.shippingAddressUpdated'),$request);
			   return Redirect::route('checkout');
		      
		   
			 
		  } else{
		     
		     
			   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			   return Redirect::back();
		  }
		  
			}
		$data=CheckoutShipping::select('customer_shipping_address.*')->where('id',$id)->first();
        $states=CommonHelper::getState($data->shipping_state);
        $cities=CommonHelper::getCityFromState($data->shipping_state);
        
        
        $states1=DB::table('states')->where('name',$data->shipping_state)->first();
		//print_r($states1->id);die;
        $cities=CommonHelper::getCityFromState($states1->id);
	 
			return view('fronted.mod_checkout.checkoutEdit',[
			    'shipping_data'=>$data,
			    'id'=>$id,
			    "states"=>$states,
			     "cities"=>$cities
			    ]);
		

    }
	public function delete_shipping_address(Request $request)
    {
		
		CheckoutShipping::where('id', base64_decode($request->shipping_id))
						->update([
							'isdeleted' =>1
						]);
	
		MsgHelper::save_session_message('success',Config::get('messages.common_msg.shippingAddressDeleted'),$request);
		 return Redirect::back();	
    }
	
	public function add(Request $request){

		if ($request->isMethod('post')) {
		    
		    $request->validate([
            'shipping_name' => 'required|max:50',
            'shipping_mobile' => 'required|max:10',
             'shipping_address' => 'required|max:110',
            'shipping_address1' => 'max:110',
            'shipping_address2' => 'max:110',
            'shipping_city' => 'required|max:50', 
            'shipping_state' => 'required|max:50',
            'shipping_pincode' => 'required|max:6',
            'shipping_address_type' => 'required'
            ],[
'shipping_name.required' =>'Name is required',
'shipping_name.max' =>'Name can not exceed to 50 characters',
'shipping_mobile.required' =>'Mobile is required',
'shipping_mobile.max' =>'Mobile can not exceed to 50 characters',
'shipping_address.required' =>'Address is required',
'shipping_address.max' =>'Address can not exceed to 50 characters',
'shipping_pincode.required' =>'Pincode is required',
'shipping_address.regex' =>'Shipping Address Must have atleast one digit and one alphabet',
'shipping_pincode.max' =>'Pincode can not exceed to 50 characters',
'shipping_state.required' =>'State is required',
'shipping_state.max' =>'State can not exceed to 50 characters',
'shipping_city.required' =>'City is required',
'shipping_city.max' =>'City can not exceed to 50 characters',
'shipping_address1.max' =>'Area can not exceed to 50 characters',
'shipping_address2.max' =>'Landmark can not exceed to 50 characters',
'shipping_address_type.required' =>'Shipping type is required',
             ]
			);
			
			$input=$request->all();
              $cust_id=auth()->guard('customer')->user()->id ;
	$states=DB::table('states')->where('id',$input['shipping_state'])->first();
			$CheckoutShipping = new CheckoutShipping;
				$CheckoutShipping->customer_id = $cust_id;
			$CheckoutShipping->shipping_name = $input['shipping_name'];
			$CheckoutShipping->shipping_mobile = $input['shipping_mobile'];
			$CheckoutShipping->shipping_address = $input['shipping_address'];
			$CheckoutShipping->shipping_address1 = $input['shipping_address1'];
			$CheckoutShipping->shipping_address2 = $input['shipping_address2'];
			$CheckoutShipping->shipping_city = $input['shipping_city'];
			$CheckoutShipping->shipping_state = $states->name;
			$CheckoutShipping->shipping_pincode = $input['shipping_pincode'];
			$CheckoutShipping->shipping_address_type = $input['shipping_address_type'];
			$CheckoutShipping->shipping_address_default = isset($input['shipping_address_default']) ? 1 : 0;
		  
		  /* save the following details */
		  if($CheckoutShipping->save()){
		      if(isset($input['shipping_address_default'])){
		          
    			
    			$input_default_set=array( 'shipping_address_default' => 0);
    			$CheckoutShipping::where('customer_id',$cust_id)->update($input_default_set);
		      }
		     
			  MsgHelper::save_session_message('success',Config::get('messages.common_msg.shippingAddressAdded'),$request);
			   return Redirect::back();
		  } else{
			   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			    return Redirect::back();
		  }
		 
		}
    
    return view('admin.mod_checkout.checkout');
   }
    
    
    public function review_order(Request $request)
    {
       
        $minutes=(86400000 * 30);
         
        $cust_id=auth()->guard('customer')->user()->id ;
		$cart_data=app(\App\Http\Controllers\cart\CartController::class)->getCart_item($request->ip(),$cust_id);
        if(sizeof($cart_data)==0){
            return Redirect::route('index');
        }
        
//         if($request->session()->get('shipping_address_id')==0  $_COOKIE["shipping_address_id"]){
// 			return Redirect::route('checkout');
//         }

                if(@$_COOKIE["shipping_address_id"]){
                
                    } else{
                       return Redirect::route('checkout');
                    }
        
        
        // $address_id=$request->session()->get('shipping_address_id');
        $address_id= $_COOKIE["shipping_address_id"];
       
    
      $shipping_adddress=CheckoutShipping::getshippingAddressOfCustomer($address_id,$cust_id);
    
    $cust_info=Customer::where('id',$cust_id)->first();
  
            unset($_COOKIE['ship_details']); 
         
                $inputs=array(
                    "pincode"=>$shipping_adddress->shipping_pincode,
                    "price"=>200,
                    "product_name"=>"apple",
                    "qty"=>1,
                    "weight"=>2,
                    "height"=>2,
                    "length"=>2,
                    "width"=>2
                    );
                    
                    $back_response=CommonHelper::checkDelivery($inputs);
                     $output = (array)json_decode($back_response);
                   
                     if (array_key_exists("delivery_details",$output))
                    {
                      
setcookie('pincode', $shipping_adddress->shipping_pincode, time() + (86400 * 30), "/");
setcookie('pincode_error', 0, time() + (86400 * 30), "/");

                     } else{
setcookie('pincode', $shipping_adddress->shipping_pincode, time() + (86400 * 30), "/");
setcookie('pincode_error', 1, time() + (86400 * 30), "/");
                         return Redirect::route('checkout')->withErrors(['Delivery not available in your area']);


            
                        
                    }
            
     
        return view('fronted.mod_checkout.checkoutReview',array(
        	    "shipping_address"=>$shipping_adddress,
				"cust_info"=>$cust_info,
        	    "cart_data"=>$cart_data
        	    ));
    }
	
	
public function isIt_myFirst_order(){
	
// 	  if my first order
	    $myfistr_order=DB::table('user_referrals')
	    ->where('c_id',auth()->guard('customer')->user()->id)
	    ->where('p_id',auth()->guard('customer')->user()->r_by)
	    ->where('first_order_placed',0)
	    ->first(); 
	    
	    if($myfistr_order){
	       // 	  get define price
	    $refer_price=DB::table('store_info')
	      ->select('parent_amount')
	    ->first();
	   
	    // 	 transfer to parent
	    DB::table('tbl_refer_earn')
	    ->insert(array(
                'user_id'=>auth()->guard('customer')->user()->r_by,
                'rel_id'=>auth()->guard('customer')->user()->id,
                'amount'=>$refer_price->parent_amount,
                'mode'=>1
	        ));
	        
	        
	        // 	 update my order 
	        DB::table('user_referrals')
                ->where('c_id',auth()->guard('customer')->user()->id)
                ->where('p_id',auth()->guard('customer')->user()->r_by)
	    ->update(array(
            'first_order_placed'=>1
	        ));
	        
	         // update parents refer amount
                Customer::
                where('id',auth()->guard('customer')->user()->r_by)
                ->increment('r_amount',$refer_price->parent_amount);
                
                //update wallet amount of customer
                 Customer::
                where('id',auth()->guard('customer')->user()->r_by)
                ->increment('total_reward_points',$refer_price->parent_amount);
                
                
      // create wallet history
	    DB::table('tbl_wallet_history')
	    ->insert(array(
                'fld_customer_id'=>auth()->guard('customer')->user()->r_by,
                'fld_order_id'=>0,
                'fld_order_detail_id'=>0,
                'fld_reward_points'=>$refer_price->parent_amount,
                'fld_reward_narration'=>'Earned',
                'mode'=>2
	        ));
                 
	    }
	    
	    
                
	}
	public function submit_order(Request $request)
    {
      
       $paymentDetails=$request->session()->get('paymentDetails');
       
     
      
         $shipping_charges_details=CommonHelper::getShippingDetails();
         
        $input=$request->all();
		$cust_id=auth()->guard('customer')->user()->id ;
		
	
		
		$cart_data=app(\App\Http\Controllers\cart\CartController::class)->getCart_item($request->ip(),$cust_id);
		
$shipping_adddress=CheckoutShipping::where('id',$_COOKIE["shipping_address_id"])->first();
        $order_no='phaukatM'.date('YmdHis');
         if(@count($cart_data)!='0'){
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
            'order_shipping_email'=>auth()->guard('customer')->user()->email
			);
				DB::table('orders_shipping')->insert($order_shipping);
				$shipping_id=DB::getPdo()->lastInsertId();
				
				
					
		$order=array(
                'customer_id'=>$cust_id,
                'shipping_id'=>$shipping_id,
                'order_no'=>$order_no,
                'grand_total'=>$paymentDetails['grandTotal'],
                'subTotal'=>$paymentDetails['subTotal'],
                'cod_charges'=>($input['payment_mode']==0)?$shipping_charges_details->cod_charges:0,
                'coupon_code'=>$paymentDetails['coupon_code'],
                'coupon_percent'=>$input['coupon_percent'],
                'coupon_amount'=>$paymentDetails['discount'],
				'deduct_reward_points'=>$paymentDetails['usePoints'],
                'total_shipping_charges'=>$paymentDetails['shippingCharges'],
                'payment_mode'=>$input['payment_mode'],
                'tax_percent'=>$paymentDetails['tax'],
                  'order_status'=>7
			);
			
				$coupon_code=$paymentDetails['coupon_code'];
	
// 		DB::table('CouponDetails')->where('coupon_code',$input['coupon_code'])
//          		->update(
//          		    array(
//          		        'coupon_used'=>1
//          		    ));
         	DB::table('orders')->insert($order);
         	$order_id=DB::getPdo()->lastInsertId();
         	

				
				DB::table('orders')->where('id',$order_id)->update(
		 	    array(
		 	        'order_no'=>'Phaukat_'.$order_id
		 	        )
		 	    );
         	
         		DB::table('orders_shipping')->where('id',$shipping_id)
         		->update(
         		    array(
         		        'order_id'=>$order_id
         		    ));
         	
		 $grand_total=$total_reward_points=0;
		 
		 $product_commission_master=0;
		 for($i=0;$i<count($cart_data);$i++)
		 {
			 $prd_points=DB::table('product_reward_points')->where('product_id',$cart_data[$i]->prd_id)->first();
			 $prc=0;
			  $old_price=$cart_data[$i]->master_price;;
			 $reward_points=$prd_points->reward_points;
			 
			 	if ($cart_data[$i]->master_spcl_price!='')
			  {
				  $prc=$cart_data[$i]->master_spcl_price;
				 
			  }else{
				  $prc=$cart_data[$i]->master_price;
			  }
        if($cart_data[$i]->color_id==0 && $cart_data[$i]->size_id!=0){
        
            $attr_data=DB::table('product_attributes')
            ->where('product_id',$cart_data[$i]->prd_id)
            ->where('size_id',$cart_data[$i]->size_id)
            ->first();
            
            $old_price+=$attr_data->price;
            $prc+=$attr_data->price;
        }
        if($cart_data[$i]->color_id!=0 && $cart_data[$i]->size_id==0){
            $attr_data=DB::table('product_attributes')
            ->where('product_id',$cart_data[$i]->prd_id)
            ->where('color_id',$cart_data[$i]->color_id)
            ->first();
            $old_price+=$attr_data->price;
            $prc+=$attr_data->price;
        }
        if($cart_data[$i]->color_id!=0 && $cart_data[$i]->size_id!=0){
            $attr_data=DB::table('product_attributes')
            ->where('product_id',$cart_data[$i]->prd_id)
            ->where('color_id',$cart_data[$i]->color_id)
            ->where('size_id',$cart_data[$i]->size_id)
            ->first();
            $old_price+=$attr_data->price;
            $prc+=$attr_data->price;
        }
        $product_shipping_charges=0;
         $product_commission_rate=0;
        $ship_data=Products::productDetails($cart_data[$i]->prd_id);
        
        $commission_data=Products::productsFirstCatData($cart_data[$i]->prd_id);
       
        
        $product_commission_rate=$commission_data->commission_rate;
        
         $product_commission_master+=$product_commission_rate;
    if($paymentDetails['shippingCharges']>0){
         $product_shipping_charges=$ship_data->shipping_charges;
    } else{
        $product_shipping_charges=0;  
    }
    
    	$order_deduct_reward_points=0;
			if($paymentDetails['grandTotal']!=0)
			{
            $order_deduct_reward_points=round((($prc/$paymentDetails['grandTotal'])*$paymentDetails['usePoints']),2);
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
			
			 $cod_charges=0;
        if($input['payment_mode']==0){
        $total_products=sizeof($cart_data);
        $cod_charges=($shipping_charges_details->cod_charges)/$total_products;
        }
        
        
        $product_shipping_charges=0;
        if($paymentDetails['shippingCharges']>0){
            $total_products=sizeof($cart_data);
        $product_shipping_charges=($paymentDetails['shippingCharges'])/$total_products;
        }
        
        $dis=0;
        if($paymentDetails['discount']>0){
            $total_products=sizeof($cart_data);
        $dis=($paymentDetails['discount'])/$total_products;
        }
        
      
        
			 $order_detail=array(
				'suborder_no'=>$order_no.'_item_'.$i,
                'order_id'=>$order_id,
                'order_cod_charges'=>$cod_charges,
				'product_id'=>$cart_data[$i]->prd_id,
				'product_name'=>$cart_data[$i]->name,
				'product_qty'=>$cart_data[$i]->qty,
                'product_price'=>$prc,
                'product_price_old'=>$old_price,
                'size'=> Products::getSizeName($cart_data[$i]->size_id ),
                'color'=> Products::getcolorName($cart_data[$i]->color_id ),
                'size_id'=>$cart_data[$i]->size_id,
                'w_size_id'=>$cart_data[$i]->w_size_id,
                'w_size'=>Products::getSizeName($cart_data[$i]->w_size_id ),
                'color_id'=>$cart_data[$i]->color_id,
                'order_reward_points'=>($reward_points)?$reward_points:0,
                'order_deduct_reward_points'=>$order_deduct_reward_points,
                'order_wallet_amount'=>$order_deduct_reward_points,
                'order_coupon_amount'=>$dis,
                //  'order_coupon_amount'=>$product_coupon_amt,
                'order_shipping_charges'=>$product_shipping_charges,
                'order_commission_rate'=>(Int)$product_commission_rate,
                 'return_days'=>$ship_data->return_days,
                  'order_status'=>7
				);
				
				
				DB::table('order_details')->insert($order_detail);
				$order_detail_id=DB::getPdo()->lastInsertId();
				
				DB::table('order_details')->where('id',$order_detail_id)->update(
		 	    array(
		 	        'suborder_no'=>'Phaukat'.$order_detail_id
		 	        )
		 	    );
		 	    
				
				/*$wallet=array(
						'fld_customer_id'=>$cust_id,
						'fld_order_id'=>$order_id,
						'fld_order_detail_id'=>$order_detail_id,
						'fld_reward_points'=>$reward_points
					);
			
				DB::table('tbl_wallet_history')->insert($wallet);*/
		
				$grand_total+=$cart_data[$i]->qty*$prc;
				$total_reward_points+=$reward_points;
				
		 }
		 
		 	DB::table('orders')->where('id',$order_id)->update(
		 	    array(
		 	        'total_commission_rate'=>$product_commission_master
		 	        )
		 	    );
         
		 
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
		$deduct_amt=$cust_points->total_reward_points-$order['deduct_reward_points'];
		
		DB::table('customers')->where('id',$cust_id)
         		->update(
         		    array(
         		        'total_reward_points'=>($deduct_amt+$total_reward_points)
         		    ));
		
// 			key: "rzp_test_3NdYvXHZmWVVzG", live
// 			key: "rzp_live_qDoUunqdjpysod", rzp_test_3NdYvXHZmWVVzG test key 
		if($input['payment_mode']==1){ //online
			
			$review_order=url("/review_order");
			$return_url=url("/success/$order_id/$order_id");
			$merchant_order_id=$order_id; //product_id
			$txnid=$order_id;
			$productinfo='Test Product';
			$surl=url("/success/$order_id/$order_id");
			$furl=url("/failed/$order_id/$order_id");
			$total=round($paymentDetails['grandTotal'])*100;
			$amount=round($paymentDetails['grandTotal'])*100;
			$card_holder_name=$order_shipping['order_shipping_name'];
			
			$html=' <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
				    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
					<script>
					    var total = "'.$total.'";
                        var merchant_order_id = "'.$merchant_order_id.'";
                        var merchant_trans_id = "'.$txnid.'";
                        var merchant_surl_id = "'.$surl.'";
                        var merchant_furl_id = "'.$furl.'";
                        var card_holder_name_id = "'.$card_holder_name.'";
                        var merchant_total = total;
                        var merchant_amount = "'.$amount.'";
                        var currency_code_id = "INR";
                        var key_id = "rzp_live_MwQbR4QU2o7h8x";
                        var store_name = "Phaukat";
                        var store_description = "Payment";
                        var store_logo = "https://www.phaukat.com/public/fronted/images/logo.jpg";
                        var email = "'.$order_shipping['order_shipping_email'].'";
                        var phone = "'.$order_shipping['order_shipping_phone'].'";
    
					  var razorpay_options = {
						key: "rzp_live_MwQbR4QU2o7h8x",
						amount: "'.$total.'",
						name: "'.$card_holder_name.'",
						description: "Order # '.$order_id.'",
						netbanking: true,
						currency: "INR",
						prefill: {
						  name:"'.$order_shipping['order_shipping_name'].'",
						  email: "'.$order_shipping['order_shipping_email'].'",
						  contact: "'.$order_shipping['order_shipping_phone'].'"
						},
						notes: {
						  soolegal_order_id: "'.$order_id.'",
						},
						handler: function (transaction) {
						    var token="'.csrf_token().'";
						    jQuery.ajax({
                                url:"'.route("callback").'",
                                type: "post",
                                data: {_token:token,razorpay_payment_id: transaction.razorpay_payment_id, merchant_order_id: merchant_order_id, merchant_surl_id: merchant_surl_id, merchant_furl_id: merchant_furl_id, card_holder_name_id: card_holder_name_id, merchant_total: merchant_total, merchant_amount: merchant_amount, currency_code_id: currency_code_id}, 
                                dataType: "json",
                                success: function (res) {
                                    if(res.msg){
                                        alert(res.msg);
                                        return false;
                                    } 
                                    window.location = res.redirectURL;
                                }
                            });
                             
                    	},
						"modal": {
							"ondismiss": function(){
								location.href="'.$review_order.'"
							}
						}
					  };
					  
					    var objrzpv1 = new Razorpay(razorpay_options);
                        objrzpv1.open();
                        
                    </script>';
					
			echo $html;
			
		}elseif($input['payment_mode']==0){  //cod
			self::removeProductFromwishlist($request->ip(),$cust_id);
			 $this->generateMailforOrder($order_id,$cust_id);
			 
                if(auth()->guard('customer')->user()->r_by>0){
                $this->isIt_myFirst_order();
                }
		     
		      
				$oder_data=DB::table('orders')->where('id',$order_id)->first();
				 $this->CouponUsed($oder_data->coupon_code);
// 			$request->session()->forget('cart_coupon');
			self::decreaseAllProductQtyByOrder($request->ip(),$cust_id);
			return view('fronted.mod_checkout.thank-you',array('order_id'=>$oder_data->order_no));
		}
    }else{   
        return redirect()->intended('/');
        
    }
		
    }
    
    public function callback(Request $request)
    {
        
        if (($request->razorpay_payment_id!='') && ($request->merchant_order_id!='')) {
            $json = array();
            $razorpay_payment_id = $request->razorpay_payment_id;
            $merchant_order_id = $request->merchant_order_id;
            $currency_code = "INR";
            
            $dataFlesh = array(
                'card_holder_name' => $request->card_holder_name_id,
                'merchant_amount' => $request->merchant_amount,
                'merchant_total' => $request->merchant_total,
                'surl' => $request->merchant_surl_id,
                'furl' => $request->merchant_furl_id,
                'currency_code' => $currency_code,
                'order_id' => $request->merchant_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
            );
        
        $paymentInfo = $dataFlesh;
        $order_info = array('order_status_id' => $request->merchant_order_id);
        $amount = $request->merchant_total;
        $currency_code = "INR";
        $data = array(
            'amount' => $amount,
            'currency' => $currency_code,
        );
        $success = false;
        $error = '';
        //try {
            $url = 'https://api.razorpay.com/v1/payments/' . $razorpay_payment_id . '/capture';
            $key_id = "rzp_live_MwQbR4QU2o7h8x";
            $key_secret = "BU9XcTuvguBPEwBmNZvJd1B8";
            $params = http_build_query($data);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERPWD, $key_id . ':' . $key_secret);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            $result = curl_exec($ch);
            $data = json_decode($result);
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
            if ($result === false) {
                $success = false;
                $error = 'Curl error: ' . curl_error($ch);
            } else {
                $response_array = json_decode($result, true);
                if ($http_status === 200 and isset($response_array['error']) === false) {
                    $success = true;
                } else {
                    $success = false;
                    if (!empty($response_array['error']['code'])) {
                        $error = $response_array['error']['code'] . ':' . $response_array['error']['description'];
                    } else {
                        $error = 'Invalid Response <br/>' . $result;
                    }
                }
            }
            curl_close($ch);
        /*} catch (Exception $e) {
            $success = false;
            $error = 'Request to Razorpay Failed';
        }*/
            if ($success === true) {
                  DB::table('orders')->where('id',$merchant_order_id)->update(
		 	    array(
		 	        'order_status'=>0
		 	        )
		 	    );
		 	     DB::table('order_details')->where('order_id',$merchant_order_id)->update(
		 	    array(
		 	        'order_status'=>0
		 	        )
		 	    );
		 	    
                if (!$order_info['order_status_id']) {
                    $json['redirectURL'] = $request->merchant_surl_id;
                } else {
                    $json['redirectURL'] = $request->merchant_surl_id;
                }
            } else {
                    $json['redirectURL'] = $request->merchant_furl_id;
            }
            
            $json['msg'] = '';
        } else {
            $json['msg'] = 'An error occured. Contact site administrator, please!';
        }
        header('Content-Type: application/json');
        echo json_encode($json);
    
    }
    
     public  static function CouponUsed($coupon_code){
	      if($coupon_code!=''){
		         $coupon_details=DB::table('coupon_details')
                      ->select('coupons.coupon_type')
                     ->join('coupons','coupons.id','coupon_details.coupon_id')
                    ->where('coupon_code',$coupon_code)
                    ->first();
                       if($coupon_details){
                            if(
                $coupon_details->coupon_type==4  ||
                $coupon_details->coupon_type==5  ||
                $coupon_details->coupon_type==6  ||
                $coupon_details->coupon_type==7  
            ){
               	DB::table('coupon_details')->where('coupon_code',$coupon_code)->update(
                        array(
                          "coupon_used"=>1  
                        )
               	    );
            } 
                       }
           
		    }
   


	}
    
    public function generateMailforOrder($order_id,$cust_id){
                 app(\App\Http\Controllers\CookieController::class)->remove_cokkie_cart(); 
                 
                 
                  DB::table('orders')->where('id',$order_id)->update(
		 	    array(
		 	        'order_status'=>0
		 	        )
		 	    );
		 	     DB::table('order_details')->where('order_id',$order_id)->update(
		 	    array(
		 	        'order_status'=>0
		 	        )
		 	    );
            $master_order=Orders::where('id',$order_id)->first();
            $master_orders=OrdersDetail::where('order_id',$order_id)->get();
            $mode= ($master_order->payment_mode==0)?"COD":"Paid";
          
            $customer_data=Customer::where('id',$cust_id)->first();
            $shipping_data=OrdersShipping::where('order_id',$order_id)->first();
			$city_data=DB::table('cities')->where('id',$shipping_data->order_shipping_city)->first();
			$state_data=DB::table('states')->where('id',$shipping_data->order_shipping_state)->first();
				
				if($master_order->payment_mode==0) {
					
					$msg=view("message_template.cod_order_placedMessage",
										array(
									'data'=>array(
										'name'=>ucfirst($customer_data->name),
										'order_no'=>$master_order->order_no
										)
										) )->render();					
								
					}
				else {
					$msg=view("message_template.online_order_placedMessage",
										array(
									'data'=>array(
										'name'=>$customer_data->name,
										'order_no'=>$master_order->order_no
										)
										) )->render();
				
				}
			 
          
										    
            	$email_msg='<tr>
            	<td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
                	<p>Hi '.$customer_data->name.' '.$customer_data->last_name.'</p>
                    <p>Thank you for your order</p>
                    <p>We have received your order. We will send you an Email and SMS the moment your order items are dispatched to your address</p>
                    <p>
                       Order ID: <span style="color:#00bbe6;">'.$master_order->order_no.'</span><br />
                       Order Date: <span style="color:#00bbe6;">'.$master_order->order_date.'</span><br />
                       Payment Mode: <span style="color:#00bbe6;">'.$mode.'</span>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="border-bottom:solid 1px #999; border-right:solid 1px #999; padding:0px 10px; width:50%;">
                	<p><strong>Billing Info</strong><br />
                            '.$shipping_data->order_shipping_name.'<br />
                            '.$shipping_data->order_shipping_phone.'<br />
                            '.$shipping_data->order_shipping_email.'<br />
                            '.$shipping_data->order_shipping_address.'<br />
                            '.$shipping_data->order_shipping_address1.'<br />
                            '.$shipping_data->order_shipping_address2.'<br />
                            '.$shipping_data->order_shipping_city.'<br />
                            '.$shipping_data->order_shipping_state.'<br />
                            '.$shipping_data->order_shipping_zip.'<br />
                    </p
                </td>
                <td style="border-bottom:solid 1px #999; padding:0px 10px; width:50%;">
                	<p><strong>Shipping Info</strong><br />
                    '.$shipping_data->order_shipping_name.'<br />
                    '.$shipping_data->order_shipping_phone.'<br />
                    '.$shipping_data->order_shipping_email.'<br />
                    '.$shipping_data->order_shipping_address.'<br />
                    '.$shipping_data->order_shipping_address1.'<br />
                    '.$shipping_data->order_shipping_address2.'<br />
                    '.$shipping_data->order_shipping_city.'<br />
                    '.$shipping_data->order_shipping_state.'<br />
                    '.$shipping_data->order_shipping_zip.'<br />
                        
                    </p>
                </td>
            </tr> 
            <tr>
            	<td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
                	<p>Order Summary</p>
                </td>
            </tr>';
            
            $email_msg.='<tr>
            	<td colspan="2">
                	<table cellpadding="0" cellspacing="0" style="width:100%; text-align:left; padding:5px 10px;">
                      <tr>
                        <th style="padding:5px 0px;">S.no.</th>
                        <th>Item Name</th>
						<th>Quantity</th>
                        <th>Price</th>
						<th>Amt</th>
                      </tr>';
                      
                      $i=1;
                      foreach($master_orders as $products){
                          
                            $email_msg.='<tr>
                            <td style="padding:10px 10px 5px; border-bottom:dashed 1px #ccc;">'.$i.'</td>
                            <td style="border-bottom:dashed 1px #ccc;">'.$products->product_name.'</td>
                            <td style="border-bottom:dashed 1px #ccc;">'.$products->product_qty.'</td>
                            <td style="border-bottom:dashed 1px #ccc;">'.$products->product_price.'</td>
                            <td style="border-bottom:dashed 1px #ccc;">'.$products->product_qty*$products->product_price.'</td></tr>';
                            
                            $i++;
                      }
					
						
						
    //                  $email_msg.='<tr>
    //                     <td style="padding:5px 10px;">&nbsp;</td>
    //                     <td>&nbsp;</td>
    //                     <td>&nbsp;</td>
				// 		<td>&nbsp;</td>
    //                     <td>Total Amount</td>
    //                     <td>hyh</td>
    //                   </tr>
                   
                   if($master_order->coupon_code!=''){
                       $email_msg.='
					    <tr>
						<td colspan="5"><p>Discount Applied Code <strong>'.$master_order->coupon_code.'</strong> to get </p></td>
						<td><strong>'.$master_order->discount_amount.'</strong></td>
					 </tr>';
                   }
                   if($master_order->total_shipping_charges!=''){
                       $email_msg.='
					    <tr>
						<td colspan="5"><p>Shiiping Charge</td>
						<td><strong>'.$master_order->total_shipping_charges.'</strong></td>
					 </tr>';
                   }
                     
					 
                       $email_msg.='<tr bgcolor="#d1d4d1">
                        <td style="padding:5px 10px;">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
						<td>&nbsp;</td>
                        <td><strong>Total Amount Rs.:'.$master_order->grand_total.' </strong></td>
                      </tr>
					  
					
					  
                    </table>

                </td>
            </tr>';
            
            
	           $email_data = [
                            'to'=>$customer_data->email,
                            'subject'=>'Order',
                            "body"=>view("emails_template.order_confirmation",
                            array(
                            'message'=>$email_msg,
                            'customer_info'=>$customer_data,
                            'shipping_info'=>$shipping_data,
                            'extra_info'=>$master_order,
                            'product_info'=>$master_orders,
                            'city_info'=>$city_data,
                            'state_info'=>$state_data,
                            'payment'=>$mode
                            ) )->render(),
                            'phone'=>$customer_data->phone,
                            'phone_msg'=>$msg
                         ];
                   
            CommonHelper::SendmailCustom($email_data);
            
             CommonHelper::SendMsg($email_data);
        
    }
    
public static function removeProductFromwishlist($cust_id){
  $cart_data=app(\App\Http\Controllers\cart\CartController::class)->getCart_item('111',$cust_id);
	
		for($i=0;$i<count($cart_data);$i++)
		 {
			 $prd_id=$cart_data[$i]->prd_id;
			 $size_id=$cart_data[$i]->size_id;
			 $color_id= $cart_data[$i]->color_id;
			 $qty=$cart_data[$i]->qty; 
			Products::decreaseProductQty($prd_id,$size_id,$color_id,$qty);
			CommonHelper::removeProductFromCustomerWishlistAndSaveForlater($cust_id,$prd_id);
		 }  
}
    public static function decreaseAllProductQtyByOrder($ip,$cust_id){
        // transfer amont to parent 
        //DB::table('cart')->where('user_ip',$ip)->delete();
        //DB::table('cart')->where('user_id',$cust_id)->delete();
        //DB::table('cart_coupon')->where('user_id',$cust_id)->delete();
    }
    
	
	public function success(Request $request)
    { 	$cust_id=auth()->guard('customer')->user()->id ;
        self::removeProductFromwishlist($request->ip(),$cust_id);
        $order_id=$request->merchant_order_id;
       
        DB::table('cart')->where('user_id',$cust_id)->delete();
        
        	DB::table('orders')
        	->where('id',$request->merchant_order_id)
        	->update(
        	    array(
        	        'txn_id'=>$request->merchant_trans_id,
        	         'txn_status'=>1
        	        )
        	    );
			 $this->generateMailforOrder($order_id,$cust_id);
                    if(auth()->guard('customer')->user()->r_by>0){
                    $this->isIt_myFirst_order();
                    }
			  $this->CouponUsed($oder_data->coupon_code);
		 self::decreaseAllProductQtyByOrder($request->ip(),$cust_id);
		 	$oder_data=DB::table('orders')->where('id',$order_id)->first();
       
		return view('fronted.mod_checkout.thank-you',array('order_id'=>$oder_data->order_no));
    }
	
	public function failed(Request $request)
    {	$cust_id=auth()->guard('customer')->user()->id ;
         
        $order_id=$request->merchant_order_id;
		
			DB::table('orders')
        	->where('id',$request->merchant_order_id)
        	->update(
        	    array(
        	        'txn_id'=>$request->merchant_trans_id,
        	         'txn_status'=>0,
        	         'order_status'=>7
        	        )
        	    );
        	    
        	    DB::table('order_details')
        	->where('order_id',$input['merchant_order_id'])
        	->update(
        	    array(
        	         'order_status'=>7
        	        )
        	    );
        	    
		
				$oder_data=DB::table('orders')->where('id',$order_id)->first();
		return view('fronted.mod_checkout.failure',array('order_id'=>$oder_data->order_no));
    }
   
	
	
 
}
