<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Redirect;
use Validator;
use DB;
use URL;
use App\Cart;
use Config;
use Auth;
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

use App\Helpers\TransactionRequestBean;
use App\Helpers\TransactionResponseBean;

use Session;

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
		return view('fronted.mod_checkout.checkout',['shipping_listing'=>$ship_address_list,'states'=>$states]);
    }
	

		public function checkPinCodeOfAddress(Request $request)
    {
                $input=$request->all();
                $shipping_adddress=CheckoutShipping::where('id',$input['shippingaddressid'])->first();
               
      	 $data=DB::table('logistic_vendor_pincode')->where('pincode',$shipping_adddress->shipping_pincode)
      	 ->where('status',1)->first();
      	 
       if($data){
            $request->session()->put('shipping_address_id',$input['shippingaddressid']);
           $res=array(
               "Error"=>0,
               "pincode"=>$shipping_adddress->shipping_pincode,
               'shippingAddressId'=>$input['shippingaddressid']
               );
           
       } else{
           $res=array(
                "Error"=>1,
                "pincode"=>"",
                'shippingAddressId'=>""
               );
       }
        echo json_encode($res);
    }
    
    	public function selectShippingAddress(Request $request)
    {
      
          $request->session()->put('shipping_address_id',base64_decode($request->shipping_id));
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
'shipping_mobile.required' =>'Mobile is required',
'shipping_mobile.max' =>'Mobile can not exceed to 10 characters',
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
			
			
			$CheckoutShipping = new CheckoutShipping;
			$input_array=
			    	array('shipping_name' => $input['shipping_name'],
			'shipping_mobile' => $input['shipping_mobile'],
			'shipping_address' => $input['shipping_address'],
			'shipping_address1' => $input['shipping_address1'],
			'shipping_address2' => $input['shipping_address2'],
			'shipping_city' => $input['shipping_city'],
			'shipping_state' => $input['shipping_state'],
			'shipping_pincode' => $input['shipping_pincode'],
			'shipping_address_type' => $input['shipping_address_type'],
			'shipping_address_default' => isset($input['shipping_address_default']) ? 1 : 0
			
			
			);
			
			if($input_array['shipping_address_default']==1)
			{
    			$cust_id=auth()->guard('customer')->user()->id ;
    			
    			$input_default_set=array( 'shipping_address_default' => 0);
    			$CheckoutShipping::where('customer_id',$cust_id)->update($input_default_set);
			}
			
		
				
		$rs=$CheckoutShipping::where('id',$id)->update($input_array);
		  
		  /* save the following details */
		  if($rs){
			  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
			  return Redirect::route('checkout');
		  } else{
			   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			   return Redirect::route('checkout');
		  }
		  
			}
		$states=CommonHelper::getState('101');
		$data=CheckoutShipping::select('customer_shipping_address.*')->where('id',$id)->first();
		
		$cities = DB::table('cities')
		              ->select('cities.name as name', 'states.name as state_name')
		              ->join('states', 'states.id', '=', 'cities.state_id')
		              ->where('states.name',$data->shipping_state)->get();
	
			return view('fronted.mod_checkout.checkoutEdit',[
            		    'shipping_data'=>$data,
            		    'id'=>$id,
            		    'states' => $states,
            		    'cities' => $cities
		            ]);
		

    }
	public function delete_shipping_address(Request $request)
    {
		
		CheckoutShipping::where('id', base64_decode($request->shipping_id))
						->update([
							'isdeleted' =>1
						]);
	
		MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
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
'shipping_mobile.max' =>'Mobile can not exceed to 10 characters',
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
			
			$input=$request->all();
              $cust_id=auth()->guard('customer')->user()->id ;
	
			$CheckoutShipping = new CheckoutShipping;
				$CheckoutShipping->customer_id = $cust_id;
			$CheckoutShipping->shipping_name = $input['shipping_name'];
			$CheckoutShipping->shipping_mobile = $input['shipping_mobile'];
			$CheckoutShipping->shipping_address = $input['shipping_address'];
			$CheckoutShipping->shipping_address1 = $input['shipping_address1'];
			$CheckoutShipping->shipping_address2 = $input['shipping_address2'];
			$CheckoutShipping->shipping_city = $input['shipping_city'];
			$CheckoutShipping->shipping_state = $input['shipping_state'];
			$CheckoutShipping->shipping_pincode = $input['shipping_pincode'];
			$CheckoutShipping->shipping_address_type = $input['shipping_address_type'];
			$CheckoutShipping->shipping_address_default = isset($input['shipping_address_default']) ? 1 : 0;
		  
		  /* save the following details */
		  if($CheckoutShipping->save()){
		      if(isset($input['shipping_address_default'])){
		          
    			
    			$input_default_set=array( 'shipping_address_default' => 0);
    			$CheckoutShipping::where('customer_id',$cust_id)->update($input_default_set);
		      }
		     
			  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
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
       
        $cust_id=auth()->guard('customer')->user()->id ;
		$cart_data=app(\App\Http\Controllers\cart\CartController::class)->getCart_item($request->ip(),$cust_id);
        if(sizeof($cart_data)==0){
            return Redirect::route('index');
        }
        
        if($request->session()->get('shipping_address_id')==0){
			return Redirect::route('checkout');
        }
      
      	$shipping_adddress=CheckoutShipping::where('id',$request->session()->get('shipping_address_id'))->first();
		$cust_info=Customer::where('id',$cust_id)->first();
		$wallet_setting = DB::select("SELECT * FROM wallet_setting");
      
      	 $data=DB::table('logistic_vendor_pincode')->where('pincode',$shipping_adddress->shipping_pincode)
      	 ->where('status',1)->first();
       if(!$data){
              
           return Redirect::route('checkout')->withErrors(['Delivery not available in your area']);;;
       } else{
            Session::put('pincode',$shipping_adddress->shipping_pincode);
       }
     
        return view('fronted.mod_checkout.checkoutReview',array(
        	    "shipping_address"=>$shipping_adddress,
				"cust_info"=>$cust_info,
        	    "cart_data"=>$cart_data,
				"wallet_setting"=>$wallet_setting
        	    ));
    }
	
	
	public function submit_order(Request $request)
    {
        
        $shipping_charges_details=CommonHelper::getShippingDetails();
         
        $input=$request->all();
		$cust_id=auth()->guard('customer')->user()->id ;
		
		$coupon_code=$input['coupon_code'];
		
		//$grand_total=$input['grand_total']+$input['wallet_amount']+$input['discount_amount'];
		$grand_order_total=$input['grand_total']+$input['discount_amount'];
		
		$cart_data=app(\App\Http\Controllers\cart\CartController::class)->getCart_item($request->ip(),$cust_id);
		
		if(@sizeof($cart_data)!='0'){
			$shipping_adddress=CheckoutShipping::where('id',$request->session()->get('shipping_address_id'))->first();
			$order_no='18UP'.date('YmdHis');
					
		$check_order_id=app(\App\Http\Controllers\CookieController::class)->getcheckoutOrderId();
		if($check_order_id=='')
		{
			
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
			
			$wallet_consume_percent=0;
			$order_total_check=0;
			if($input['wallet_amount']!=0)
			{
				$wallet_setting=DB::table('wallet_setting')->first();
				
				$wallet_consume_percent=$wallet_setting->wallet_consume_percent;
				//$order_total_check=$grand_order_total+$input['discount_amount']-$input['shipping_charges'];
				$order_total_check=$grand_order_total+$input['discount_amount']+$input['wallet_amount']-$input['shipping_charges'];
				
			}
					
			$order=array(
					'customer_id'=>$cust_id,
					'shipping_id'=>$shipping_id,
					'order_no'=>$order_no,
					'grand_total'=>$grand_order_total,
					'coupon_code'=>$input['coupon_code'],
					'coupon_percent'=>$input['coupon_percent'],
					'coupon_amount'=>$input['discount_amount'],
					'deduct_wallet_percent'=>$wallet_consume_percent,
					'deduct_reward_points'=>$input['wallet_amount'],
					'total_shipping_charges'=>$input['shipping_charges'],
					'payment_mode'=>$input['payment_mode'],
					'tax_percent'=>$input['tax'],
					'order_status'=>7
				);
	
	// 		DB::table('CouponDetails')->where('coupon_code',$input['coupon_code'])
	//          		->update(
	//          		    array(
	//          		        'coupon_used'=>1
	//          		    ));
				DB::table('orders')->insert($order);
				$order_id=DB::getPdo()->lastInsertId();
				
				/////
				/*$order_track=array(
						'fld_order_detail_id'=>$order_id,
						'fld_pending_order'=>1,
						'fld_order_date'=>date('d-m-Y H:i:s'),
						'fld_invoice_order'=>0,
						'fld_invoice_date'=>0,
						'fld_shipping_order'=>0,
						'fld_shipping_date'=>0,
						'fld_order_intransit'=>0,
						'fld_order_outofdelivery'=>0,
						'fld_delivered_order'=>0,
						'fld_delivered_date'=>0
					);
		
				DB::table('order_track')->insert($order_track);*/
				////
				
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
				
				if($input['shipping_charges']>0){
					 $product_shipping_charges=$ship_data->shipping_charges;
				} else{
					$product_shipping_charges=0;  
				}
				  
				$tax=$ship_data->product_tax;
				
				$order_deduct_reward_points=0;
				if($order_total_check!=0)
				{
					$osss=round($prc/$order_total_check);
					$order_deduct_reward_points=round(($osss*$input['wallet_amount']),2);
					
					//$grand_total=$input['grand_total']+$input['wallet_amount']+$input['discount_amount'];
					
					/*$wallet=array(
								'fld_customer_id'=>$cust_id,
								'fld_order_id'=>$order_id,
								'fld_order_detail_id'=>0,
								'fld_reward_narration'=>'Consume Wallet Amount',
								'fld_reward_deduct_points'=>0,
								'fld_order_deduct_reward_points'=>0,
								'fld_order_consume_reward_points'=>$order_deduct_reward_points
							);
					
					DB::table('tbl_wallet_history')->insert($wallet);*/
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
					'product_id'=>$cart_data[$i]->prd_id,
					'product_name'=>$cart_data[$i]->name,
					'product_qty'=>$cart_data[$i]->qty,
					'product_price'=>$prc,
					'product_price_old'=>$old_price,
					'product_tax'=>$tax,
					'size'=> Products::getSizeName($cart_data[$i]->size_id ),
					'color'=> Products::getcolorName($cart_data[$i]->color_id ),
					'size_id'=>$cart_data[$i]->size_id,
					'color_id'=>$cart_data[$i]->color_id,
					'order_reward_points'=>($reward_points)?$reward_points:0,
					'order_deduct_reward_points'=>$order_deduct_reward_points,
					'order_coupon_amount'=>$product_coupon_amt,
					'order_shipping_charges'=>$product_shipping_charges,
					'order_commission_rate'=>$product_commission_rate,
					'return_days'=>$ship_data->return_days,
					'order_status'=>7
					);
					DB::table('order_details')->insert($order_detail);
					$order_detail_id=DB::getPdo()->lastInsertId();
					
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
		
// 			key: "rzp_test_3NdYvXHZmWVVzG", live
// 			key: "rzp_live_adQIlBlrngVRb8", rzp_test_3NdYvXHZmWVVzG test key 
		if($input['payment_mode']==1){ //paynimo
		
			/*$reqType='T';
			$mrctCode='T514481';
			//$mrctTxtID=$order_id;
			$mrctTxtID=rand();
			$currencyType='INR';
			$amount='10.00';
			$itc='NIC~TXN0001~122333~rt14154~8 mar 2014~Payment~forpayment';
			$reqDetail='FIRST_10.00_0.0';
			$txnDate=date('d-m-Y');
			$locatorURL='https://www.tpsl-india.in/PaymentGateway/TransactionDetailsNew.wsdl';
			$key='9348429380NFQSGI';
			$iv='1922855088FYIDMJ';
			$returnURL='http://smmtrad.educationdoctor.in/smmtrad/Payment/techprocess.php';
			
			echo $html='<form method="post" id="myForm" action="http://smmtrad.educationdoctor.in/smmtrad/Payment/techprocess.php">
			        <input type="hidden" name="reqType" value="'.$reqType.'">
			        <input type="hidden" name="mrctCode" value="'.$mrctCode.'">
			        <input type="hidden" name="mrctTxtID" value="'.$mrctTxtID.'">
			        <input type="hidden" name="currencyType" value="'.$currencyType.'">
			        <input type="hidden" name="amount" value="'.$amount.'">
			        <input type="hidden" name="itc" value="'.$itc.'">
			        <input type="hidden" name="reqDetail" value="'.$reqDetail.'">
			        <input type="hidden" name="txnDate" value="'.$txnDate.'">
			        <input type="hidden" name="locatorURL" value="'.$locatorURL.'">
			        <input type="hidden" name="key" value="'.$key.'">
			        <input type="hidden" name="iv" value="'.$iv.'">
			        <input type="hidden" name="returnURL" value="'.$returnURL.'">
			        <input type="submit" id="submitBtn" name="submit" value="Submit" style="display:none;" />
			        </form><script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
			        <script>
			                $(document).ready(function(){
    			                $("#submitBtn").click(function(){        
                                        $("#myForm").submit(); 
                                });
                                $("#submitBtn").trigger("click");
			                });
			        </script>';*/
			
			if($check_order_id!='')
			{
				$order_id=$check_order_id;
				$order_amt=app(\App\Http\Controllers\CookieController::class)->getcheckoutOrderAmt();
			}else{
				
				$order_id=$order_id;
				$order_amt=$input['grand_total'];
				
				app(\App\Http\Controllers\CookieController::class)->setcheckoutOrder($order_id,$order_amt);
			}
			
			ob_start();
			error_reporting(E_ALL);
			$strNo = rand(1,1000000);

			date_default_timezone_set('Asia/Calcutta');

			$strCurDate = date('d-m-Y');
			
				$mrctCode='T514481';
				$key='9348429380NFQSGI';
				$iv='1922855088FYIDMJ';
				$mrctTxtID=$order_id;
				//$custID="12";
				$mobNo="8010490862";
				$custname="Test";
				$amount=number_format($order_amt,2);
				$returnURL=url("/success");
				$locatorURL="https://payments.paynimo.com/PaynimoProxy/services/TransactionLiveDetails?wsdl";
				//$s2SReturnURL="https://tpslvksrv6046/LoginModule/Test.jsp";
				//$tpsl_txn_id='TXN00'.rand(1,10000);
				
				$transactionRequestBean = new TransactionRequestBean();

				//Setting all values here
				//$transactionRequestBean->setMerchantCode($val['mrctCode']);
				$transactionRequestBean->merchantCode = $mrctCode;
				//$transactionRequestBean->accountNo = $val['tpvAccntNo'];
				//$transactionRequestBean->ITC = $val['itc'];
				$transactionRequestBean->mobileNumber = $mobNo;
				$transactionRequestBean->customerName = $custname;
				$transactionRequestBean->requestType = "T";
				$transactionRequestBean->merchantTxnRefNumber = $mrctTxtID;
				$transactionRequestBean->amount = "10.00";
				$transactionRequestBean->currencyCode = "INR";
				$transactionRequestBean->returnURL = $returnURL;
				//$transactionRequestBean->s2SReturnURL = $s2SReturnURL;
				$transactionRequestBean->shoppingCartDetails = "FIRST_10.0_0.0";
				$transactionRequestBean->txnDate = $strCurDate;
				//$transactionRequestBean->bankCode = $val['bankCode'];
				//$transactionRequestBean->TPSLTxnID = $tpsl_txn_id;
				//$transactionRequestBean->custId = $custID;
				//$transactionRequestBean->cardId = $val['cardID'];
				$transactionRequestBean->key = $key;
				$transactionRequestBean->iv = $iv;
				$transactionRequestBean->webServiceLocator = $locatorURL;
				//$transactionRequestBean->MMID = $val['mmid'];
				//$transactionRequestBean->OTP = $val['otp'];
				//$transactionRequestBean->cardName = $val['cardName'];
				//$transactionRequestBean->cardNo = $val['cardNo'];
				//$transactionRequestBean->cardCVV = $val['cardCVV'];
				//$transactionRequestBean->cardExpMM = $val['cardExpMM'];
				//$transactionRequestBean->cardExpYY = $val['cardExpYY'];
				//$transactionRequestBean->timeOut = (!empty($val['timeOut']) ? $val['timeOut'] : 30 );
				$transactionRequestBean->timeOut = 30;

				$url = $transactionRequestBean->getTransactionToken();
 
				$responseDetails = $transactionRequestBean->getTransactionToken();
				
				$responseDetails = (array)$responseDetails;
				
				$response = $responseDetails[0];
				
				/*if(is_string($response) && preg_match('/^msg=/',$response)){
					$outputStr = str_replace('msg=', '', $response);
					$outputArr = explode('&', $outputStr);
					$str = $outputArr[0];

					$transactionResponseBean = new TransactionResponseBean();
					$transactionResponseBean->setResponsePayload($str);
					$transactionResponseBean->setKey($val['key']);
					$transactionResponseBean->setIv($val['iv']);

					$response = $transactionResponseBean->getResponsePayload();
					echo "<pre>2";
					print_r($response);
					exit;
				}elseif(is_string($response) && preg_match('/^txn_status=/',$response)){
					echo "<pre>1";
					print_r($response);
					exit;
				}*/

				echo "<script>window.location = '".$response."'</script>";
				//ob_flush();
			
			
			
		}elseif($input['payment_mode']==3){ //online
			
			$review_order=url("/review_order");
			$return_url=url("/success");
			$merchant_order_id=$order_id; //product_id
			$txnid=$order_id;
			$productinfo='Test Product';
			$surl=url("/success");
			$furl=url("/failed");
			$total=$input['grand_total']*100;
			$amount=$input['grand_total'];
			$card_holder_name=$order_shipping['order_shipping_name'];
			
			$html='<form name="razorpay-form" id="razorpay-form" action="'.$return_url.'" method="POST">
					  <input type="hidden" name="_token" value="'.csrf_token().'"  />
					  <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" />
					  <input type="hidden" name="merchant_order_id" id="merchant_order_id" value="'.$merchant_order_id.'"/>
					  <input type="hidden" name="merchant_trans_id" id="merchant_trans_id" value="'.$txnid.'"/>
					  <input type="hidden" name="merchant_product_info_id" id="merchant_product_info_id" value="'.$productinfo.'"/>
					  <input type="hidden" name="merchant_surl_id" id="merchant_surl_id" value="'.$surl.'"/>
					  <input type="hidden" name="merchant_furl_id" id="merchant_furl_id" value="'.$furl.'"/>
					  <input type="hidden" name="card_holder_name_id" id="card_holder_name_id" value="'.$card_holder_name.'"/>
					  <input type="hidden" name="merchant_total" id="merchant_total" value="'.$total.'"/>
					  <input type="hidden" name="merchant_amount" id="merchant_amount" value="'.$amount.'"/>
					</form>
					<input  id="submit-pay" type="submit" onclick="razorpaySubmit(this);" value="Pay Now" style="display:none;" />
					<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
					<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
					<script>
					  var razorpay_options = {
						key: "rzp_live_adQIlBlrngVRb8",
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
							document.getElementById("razorpay_payment_id").value = transaction.razorpay_payment_id;
							document.getElementById("razorpay-form").submit();
						},
						"modal": {
							"ondismiss": function(){
								location.href="'.$review_order.'"
							}
						}
					  };
					  var razorpay_submit_btn, razorpay_instance;
					 
					  function razorpaySubmit(el){
						if(typeof Razorpay == "undefined"){
						  setTimeout(razorpaySubmit, 200);
						  if(!razorpay_submit_btn && el){
							razorpay_submit_btn = el;
							el.disabled = true;
							el.value = "Please wait...";  
						  }
						} else {
						  if(!razorpay_instance){
							razorpay_instance = new Razorpay(razorpay_options);
							if(razorpay_submit_btn){
							  razorpay_submit_btn.disabled = false;
							  razorpay_submit_btn.value = "Pay Now";
							}
						  }
						  razorpay_instance.open();
						}
					  }  
					  
					  $(document).ready(function(){
						   document.getElementById("submit-pay").click();
					  });
					</script>';
					
			echo $html;
			
		}elseif($input['payment_mode']==0){  //cod
                $this->generateMailforOrder($order_id,$cust_id);
                $oder_data=DB::table('orders')->where('id',$order_id)->first();
                /*self::decreaseAllProductQtyByOrder($request->ip(),$cust_id);*/
				self::decreaseAllProductQtyByOrder1($order_id,$cust_id);
                $this->CouponUsed($oder_data->coupon_code);
			$request->session()->forget('cart_coupon');
			$url=URL::to('/');
			
			//return view('fronted.mod_checkout.thank-you',array('order_id'=>$oder_data->order_no));
			return view('fronted.mod_checkout.thank-you',array('order_id'=>$order_id));
			
		}elseif($input['payment_mode']==2){  //wallet
                $this->generateMailforOrder($order_id,$cust_id);
                $oder_data=DB::table('orders')->where('id',$order_id)->first();
                /*self::decreaseAllProductQtyByOrder($request->ip(),$cust_id);*/
				self::decreaseAllProductQtyByOrder1($order_id,$cust_id);
                $this->CouponUsed($oder_data->coupon_code);
			$request->session()->forget('cart_coupon');
			$url=URL::to('/');
			
			DB::table('orders')->where('id',$order_id)
         		->update(
         		    array(
         		        'payment_mode'=>2
         		    ));
			
			//return view('fronted.mod_checkout.thank-you',array('order_id'=>$oder_data->order_no));
			return view('fronted.mod_checkout.thank-you',array('order_id'=>$order_id));
			
		}
		
		}else{   
        return redirect()->intended('/');
        
    }
	
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
    public function success(Request $request)
    { 	
        
		app(\App\Http\Controllers\CookieController::class)->getcheckoutOrderId();
		app(\App\Http\Controllers\CookieController::class)->getcheckoutOrderAmt();
		app(\App\Http\Controllers\CookieController::class)->setcheckoutOrder();
		
		$key='9348429380NFQSGI';
		$iv='1922855088FYIDMJ';
        
        $response = $request->all();

        if(is_array($response)){
            $str = $response['msg'];
        }else if(is_string($response) && strstr($response, 'msg=')){
            $outputStr = str_replace('msg=', '', $response);
            $outputArr = explode('&', $outputStr);
            $str = $outputArr[0];
        }else {
            $str = $response;
        }
        
        $order_id=$txn_msg=$txn_id='';
        if($str!='')
        {
            $transactionResponseBean = new TransactionResponseBean();
            $transactionResponseBean->setResponsePayload($str);
            $transactionResponseBean->key = $key;
            $transactionResponseBean->iv = $iv;
            $response = $transactionResponseBean->getResponsePayload();
            $response_data=explode('|',$response);
            
			for($i=0; $i<count($response_data);$i++)
            {
                $response_setdata=explode('=',$response_data[$i]);
                
                if($response_setdata[0]=='clnt_txn_ref')
                {
                    $order_id=$response_setdata[1];
                }
                if($response_setdata[0]=='tpsl_txn_id')
                {
                    $txn_id=$response_setdata[1];
                }
                if($response_setdata[0]=='txn_msg')
                {
                    $txn_msg=$response_setdata[1];
                    //txn_err_msg
                }
            }
        
        }
        
        $cust_id=auth()->guard('customer')->user()->id ;
        //$input=$request->all();
        
        if($order_id=='')
        {
            $order_id=$response['merchant_order_id'];
        }
       
        	DB::table('orders')
                	->where('id',$order_id)
                	->update(
                	    array(
                	        'txn_id'=>$txn_id,
                	         'txn_status'=>$txn_msg
                	        )
                	    );
                	    
                	
			 $this->generateMailforOrder($order_id,$cust_id);
		 self::decreaseAllProductQtyByOrder($request->ip(),$cust_id);
		 	$oder_data=DB::table('orders')->where('id',$order_id)->first();
		   $this->CouponUsed($oder_data->coupon_code);
		  
		//return view('fronted.mod_checkout.thank-you',array('order_id'=>$oder_data->order_no));
		return view('fronted.mod_checkout.thank-you',array('order_id'=>$order_id));
    }
	
	public function failed(Request $request)
    {	$cust_id=auth()->guard('customer')->user()->id ;
         $input=$request->all();
        $order_id=$input['merchant_order_id'];
		
			DB::table('orders')
        	->where('id',$input['merchant_order_id'])
        	->update(
        	    array(
        	        'txn_id'=>$input['merchant_trans_id'],
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
        	    
			DB::table('cart')->where('user_ip',$request->ip())->delete();
			DB::table('cart')->where('user_id',$cust_id)->delete();
				$oder_data=DB::table('orders')->where('id',$order_id)->first();
		return view('fronted.mod_checkout.failure',array('order_id'=>$oder_data->order_no));
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
            $mode= ($master_order->payment_mode==0)?"'COD'":"'Paid'";
            
            $product_info=Products::where('id',$master_orders[0]->product_id)->first();
            
            
            Cart::where('user_id',$cust_id)->delete();
          
            $customer_data=Customer::where('id',$cust_id)->first();
            $shipping_data=OrdersShipping::where('order_id',$order_id)->first();

			
			if($master_order->payment_mode==0) {
				//$msg='Dear '.$customer_data->name.' '.$customer_data->last_name.' your order for 18up.in with order id '.$master_order->order_no.' amounting to Rs.xyz pay on delivery. You can expect delivery by xxx.we will send you an update when your order is packed Manage your order here http://18up.in beware of fraudulent calls.';
				$template_file="cod_order_placed_message";
				//$msg='Your order for 18up.in with order id '.$master_order->order_no.' amounting to Rs.xyz pay on delivery. You can expect delivery by xxx.we will send you an update when your order is packed Manage your order here http://18up.in beware of fraudulent calls.';			
				}
			else {
			    $template_file="online_order_placed_message";
				//$msg='Your order for 18up.in with order id '.$master_order->order_no.' amounting to Rs.xyz has been received. You can expect delivery by xxx.we will send you an update when your order is packed Manage your order here http://18up.in beware of fraudulent calls.';
			}
			
			$msg=view("message_template.".$template_file,
								array(
    							'data'=>array(
    								//'order_no'=>$master_order->order_no,
									'order_no'=>$order_id,
    								//'order_amt'=>$master_order->grand_total,
									'order_amt'=>$master_order->grand_total-$master_order->coupon_amount,
    								'expected_date'=>date('M d, Y', strtotime("+".$product_info->delivery_days." days"))
    								)
								) )->render();		
            
			$email_msg=array(
								'payment_mode'=>$mode,
								'master_order'=>$master_order,
								'shipping_data'=>$shipping_data,
								'customer_data'=>$customer_data,
								'master_orders'=>$master_orders,
							);
            					
            
	        $email_data = [
                            'to'=>$customer_data->email,
                            'subject'=>'Your Order '.$master_order->id.' has been successfully placed',
                            //"body"=>view("emails_template.order_confirmation",
							"body"=>view("emails_template.order_confirmation_new",
                            array(
                            'data'=>array(
                            'message'=>$email_msg
                            )
                            ) )->render(),
                            'phone'=>$customer_data->phone,
                            'phone_msg'=>$msg
                         ];
                   
            CommonHelper::SendmailCustom($email_data);
            CommonHelper::SendMsg($email_data);
			
			$admin_email_data = [
                            'to'=>Config::get('constants.email.admin_to'),
                            'subject'=>'You have received Order '.$master_order->id.' ',
                            //"body"=>view("emails_template.order_confirmation",
							"body"=>view("emails_template.order_confirmation_new_admin",
                            array(
                            'data'=>array(
                            'message'=>$email_msg
                            )
                            ) )->render(),
                            'phone'=>$customer_data->phone,
                            'phone_msg'=>$msg
                         ];
                   
            CommonHelper::SendmailAdminCustom($admin_email_data);
            //CommonHelper::SendMsg($admin_email_data);
			
			foreach($master_orders as $prd_info)
			{
				$product_info=Products::where('id',$prd_info->product_id)->first();
				$vendor_info=DB::table('vendors')->where('id',$product_info->vendor_id)->first();
				
				if($vendor_info->email!='')
				{
					$vendor_email_data = [
								'to'=>$vendor_info->email,
								'subject'=>'You have received Order '.$master_order->id.' ',
								//"body"=>view("emails_template.order_confirmation",
								"body"=>view("emails_template.order_confirmation_new_vendor",
								array(
								'data'=>array(
								'message'=>$email_msg
								)
								) )->render(),
								'phone'=>$vendor_info->phone,
								'phone_msg'=>$msg
							 ];
					   
					CommonHelper::SendmailVendorCustom($vendor_email_data);
					//CommonHelper::SendMsg($vendor_email_data);
				}
			}
            
            
			
			
        
    }
    
    public static function decreaseAllProductQtyByOrder($ip,$cust_id){
        
	$cart_data=app(\App\Http\Controllers\cart\CartController::class)->getCart_item($ip,$cust_id);
	for($i=0;$i<count($cart_data);$i++)
		 {
			 $prd_id=$cart_data[$i]->prd_id;
			 $size_id=$cart_data[$i]->size_id;
			 $color_id= $cart_data[$i]->color_id;
			 $qty=$cart_data[$i]->qty; 
			Products::decreaseProductQty($prd_id,$size_id,$color_id,$qty);
		 }
        DB::table('cart')->where('user_ip',$ip)->delete();
        DB::table('cart')->where('user_id',$cust_id)->delete();
    }
	
	public static function decreaseAllProductQtyByOrder1($order_id,$cust_id){
        
	//$cart_data=app(\App\Http\Controllers\cart\CartController::class)->getCart_item1($cust_id);
	$cart_data=DB::table('order_details')->where('order_id',$order_id)->get();
		for($i=0;$i<count($cart_data);$i++)
		{
			 $prd_id=$cart_data[$i]->product_id;
			 $size_id=$cart_data[$i]->size_id;
			 $color_id= $cart_data[$i]->color_id;
			 $qty=$cart_data[$i]->product_qty; 
			Products::decreaseProductQty($prd_id,$size_id,$color_id,$qty);
		}
        /*DB::table('cart')->where('user_ip',$ip)->delete();*/
        DB::table('cart')->where('user_id',$cust_id)->delete();
    }
    
	
	
   
	
	
 
}
