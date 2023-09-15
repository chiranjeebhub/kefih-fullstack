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
use App\ProductCategories;
use App\Category;
use App\CheckoutBillingAddress;
use App\CouponDetails;
use App\Helpers\MsgHelper;
use App\Helpers\CommonHelper;
use Session;
use Illuminate\Support\Str;
use App\Helpers\FbConversionHelper;


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
        	$this->middleware('auth:customer')->except('review_order');
    }


    public function verifysecurityCode(Request $request){
        $code = $request->code; 
        $response = array(); 

        $ExibutionData = DB::table('Exibition')->where(['status' => 1, "exibition_code"=> $code])->first();
        if(!empty($ExibutionData)){
             $startDateTime = $ExibutionData->startdate.' '.$ExibutionData->starttime;
             $endDateTime = $ExibutionData->enddate.' '.$ExibutionData->endtime; 
             $currentDateTime = date("Y-m-d H:i");

             if($currentDateTime >= $startDateTime && $currentDateTime <= $endDateTime){

                Session::put('ExibutionData',['status'=>'1', "id"=>$ExibutionData->id,"name"=>$ExibutionData->exibition_name,"code"=>$code]);

                $response = array(                
                    'message' => "Security code is valid",
                    'status' =>true
                 ); 
             }else{
                $response = array(                  
                    'message' => "Security code expired",
                    'status' =>false
                 ); 
             }
            

        }else{

            $response = array(
                'message' => "Invalid Security Code",
                'status' =>false
            );         
        }

        return response()->json($response, 200);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     public function  toVendormailOnOrderplace($order_id,$customer_data,$order_details,$vdr_details,$mode,$shipping_data,$city_data,$state_data){
      	$email_msg='<tr>
            	<td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
                	<p>Hi '.$customer_data->name.' '.$customer_data->last_name.'</p>
                    <p>Thank you for your order</p>
                    <p>We have received your  cgvgg order. We will send you an Email and SMS the moment your order items are dispatched to your address</p>
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
                       
                        <th>Item Name</th>
						<th>Quantity</th>
                        <th>Price</th>
						<th>Amt</th>
                      </tr>';
                      
                    
                        
                            $email_msg.='<tr>
                           
                            <td style="border-bottom:dashed 1px #ccc;">'.$order_details->product_name.'</td>
                            <td style="border-bottom:dashed 1px #ccc;">'.$order_details->product_qty.'</td>
                            <td style="border-bottom:dashed 1px #ccc;">'.$order_details->product_price.'</td>
                            <td style="border-bottom:dashed 1px #ccc;">'.$order_details->product_qty*$order_details->product_price.'</td></tr>';
                            
                      
					
						
						
 
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
                        <td><strong>Total Amount Rs.:'.($order_details->product_price*$order_details->product_qty+$order_details->order_cod_charges-$order_details->order_deduct_reward_points).' </strong></td>
                      </tr>
					  
					
					  
                    </table>

                </td>
            </tr>';
            
            
         $msg='Hi '.$vdr_details->username." You have a new order . Your order id is ".$order_details->id." .";
	           $email_data = [
                            'to'=>$vdr_details->email,
                            'subject'=>'New Order',
                            "body"=>view("emails_template.to_vendor_order_confirmation",
                            array(
                            'message'=>$email_msg,
                            'customer_info'=>$customer_data,
                            'vdr'=>$vdr_details,
                            'shipping_info'=>$shipping_data,
                            'extra_info'=>$master_order,
                            'products'=>$order_details,
                            'city_info'=>$city_data,
                            'state_info'=>$state_data,
                            'payment'=>$mode
                            ) )->render(),
                            'phone'=>$vdr_details->phone,
                            'phone_msg'=>$msg
                         ];
                         
                  
               CommonHelper::SendMsg($email_data);  
             CommonHelper::SendmailCustom($email_data);
         
     }
     public function toAdminMailOnOrderPlace($order_id,$cust_id){
            $master_order=Orders::where('id',$order_id)->first();
            $master_orders=OrdersDetail::where('order_id',$order_id)->get();
                // $mode= ($master_order->payment_mode==0)?"COD":"Paid";
                $mode= '';
                if($master_order->payment_mode==0){
                    $mode="COD";
                }elseif($master_order->payment_mode==1){
                    $mode="Online";
                }elseif($master_order->payment_mode==2){
                    $mode="Exhibition($master_order->exhibition_payment_mode)";
                }
				elseif($master_order->payment_mode==3){
                    $mode="Wallet";
                }
              
				 


                
                $customer_data=Customer::where('id',$cust_id)->first();
                $shipping_data=OrdersShipping::where('order_id',$order_id)->first();
                $city_data=DB::table('cities')->where('id',$shipping_data->order_shipping_city)->first();
                $state_data=DB::table('states')->where('id',$shipping_data->order_shipping_state)->first();
			

            foreach($master_orders as $order_details){
                
                
                $vdr_details=DB::table('products')
                                   ->select('vendors.username','vendors.email','vendors.phone')
                                 ->join('vendors','vendors.id','products.vendor_id')
                                 ->where('products.id',$order_details->product_id)
                                 ->first();
        //    $this->toVendormailOnOrderplace($order_id,$customer_data,$order_details,$vdr_details,$mode,$shipping_data,$city_data,$state_data);             
        
                
            }
          	if($master_order->payment_mode==0) {
					
					$msg=view("message_template.cod_order_placedMessage",
										array(
									'data'=>array(
										'name'=>ucfirst($customer_data->name),
										'order_no'=>$master_order->order_no,
										'order_date'=>$master_order->order_date
										)
										) )->render();					
								
					}
				else {
					$msg=view("message_template.online_order_placedMessage",
										array(
									'data'=>array(
                                        'name'=>$customer_data->name,
                                        'order_no'=>$master_order->order_no,
                                        'order_date'=>$master_order->order_date
										)
										) )->render();
				
				}
			 
          
										    
            	$email_msg='<tr>
            	<td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
                	<p>Hi '.$customer_data->name.' '.$customer_data->last_name.'</p>
                    <p>Thank you for your order</p>
                    <p>We have received your  cgvgg order. We will send you an Email and SMS the moment your order items are dispatched to your address</p>
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
            
            // echo view("emails_template.to_admin_order_confirmation",
            // array(
            // 'message'=>$email_msg,
            // 'customer_info'=>$customer_data,
            // 'shipping_info'=>$shipping_data,
            // 'extra_info'=>$master_order,
            // 'product_info'=>$master_orders,
            // 'city_info'=>$city_data,
            // 'state_info'=>$state_data,
            // 'payment'=>$mode
            // ) )->render(); 
            // die; 
            
	           $email_data = [
                            'to'=>Config::get('constants.email.admin_to'),
                            'subject'=>'New Order',
                            "body"=>view("emails_template.to_admin_order_confirmation",
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
                    
            //CommonHelper::SendMsg($email_data);  
            CommonHelper::SendmailCustom($email_data);
         
     }
      public function index2(Request $request)
    {
           
        
    }
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
// setcookie('pincode_error', 1, time() + (86400 * 30), "/");
setcookie('pincode_error', 0, time() + (86400 * 30), "/");                      
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
           'shipping_address' => 'required|max:255',
            'shipping_address1' => 'max:255',
            'shipping_address2' => 'max:255',
            'shipping_city' => 'required|max:50', 
            'shipping_state' => 'required|max:50',
            'shipping_pincode' => 'required|max:6',
            'shipping_address_type' => 'required'
            ],[
'shipping_name.required' =>'Name is required',
'shipping_name.max' =>'Name can not exceed to 50 characters',
'shipping_address.regex' =>'Shipping Address Must have atleast one digit and one alphabet',
'shipping_mobile.required' =>'Mobile is required',
'shipping_mobile.max' =>'Mobile can not exceed to 10 characters',
'shipping_address.required' =>'Address is required',
'shipping_address.max' =>'Address can not exceed to 255 characters',
'shipping_pincode.required' =>'Pincode is required',
'shipping_pincode.max' =>'Pincode can not exceed to 50 characters',
'shipping_state.required' =>'State is required',
'shipping_state.max' =>'State can not exceed to 50 characters',
'shipping_city.required' =>'City is required',
'shipping_city.max' =>'City can not exceed to 50 characters',
'shipping_address1.max' =>'Area can not exceed to 255 characters',
'shipping_address2.max' =>'Landmark can not exceed to 255 characters',
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
    			CheckoutShipping::where('customer_id',$cust_id)->where('id','!=',$id)->update($input_default_set);
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
	 
	 	$ship_address_list = CheckoutShipping::getshippingAddress($id);
	 	
			return view('fronted.mod_checkout.checkoutEdit',[
			    'shipping_data'=>$data,
			    'id'=>$id,
			    'ship_address_list'=>$ship_address_list,
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
             'shipping_address' => 'required|max:255',
            'shipping_address1' => 'max:255',
            'shipping_address2' => 'max:255',
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
'shipping_address.max' =>'Address can not exceed to 255 characters',
'shipping_pincode.required' =>'Pincode is required',
'shipping_address.regex' =>'Shipping Address Must have atleast one digit and one alphabet',
'shipping_pincode.max' =>'Pincode can not exceed to 6 characters',
'shipping_state.required' =>'State is required',
'shipping_state.max' =>'State can not exceed to 50 characters',
'shipping_city.required' =>'City is required',
'shipping_city.max' =>'City can not exceed to 50 characters',
'shipping_address1.max' =>'Area can not exceed to 255 characters',
'shipping_address2.max' =>'Landmark can not exceed to 255 characters',
'shipping_address_type.required' =>'Shipping Address Type is required',
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
		      $address_id=$CheckoutShipping->id;
		      if(isset($input['shipping_address_default'])){
		         	$input_default_set=array('shipping_address_default' => 0);
    			$CheckoutShipping::where('customer_id',$cust_id)->where('id','!=',$address_id)->update($input_default_set);
		      }
                setcookie('shipping_address_id', $address_id, time() + (86400 * 30), "/"); 
                return Redirect::route('review_order');
			 // MsgHelper::save_session_message('success',Config::get('messages.common_msg.shippingAddressAdded'),$request);
			 //  return Redirect::back();
		  } else{
			   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			    return Redirect::back();
		  }
		 
		}
    
    return view('admin.mod_checkout.checkout');
   }
    
    
    public function review_order(Request $request)
    {
      

            if(Auth::guard('customer')->check()){
                    $cust_id=auth()->guard('customer')->user()->id;
             }else{
            //      $value = Session::get('checout');
            //   echo $value;
                        Session::put('checout', true);
                        return Redirect::route('customer_login');
             }
         
          Session::forget('ExibutionData'); //removing ExibutionData
        $minutes=(86400000 * 30);
         
        $cust_id=auth()->guard('customer')->user()->id ;
		$cart_data=app(\App\Http\Controllers\cart\CartController::class)->getCart_item($request->ip(),$cust_id);
        if(sizeof($cart_data)==0){
            return Redirect::route('index');
        }
              
        $shipping_adddress=$billing_adddress='';

         if(isset($_COOKIE['shipping_address_id'])){
           
	      $shipping_adddress=CheckoutShipping::getshippingAddressOfCustomer($_COOKIE['shipping_address_id'],$cust_id);
	      if(!$shipping_adddress){
	          
	        return Redirect::route('checkout');   
	      }

          
        if (isset($_COOKIE['billing_address_id'])) {
            $billing_adddress = CheckoutBillingAddress::getshippingAddressOfCustomer($_COOKIE['billing_address_id'], $cust_id);
        } else {
            $billing_adddress = CheckoutBillingAddress::getDeafultAddress($cust_id);
        }

      


                $inputs=array(
                    "pincode"=>$shipping_adddress->shipping_pincode,                  
                    );
          $back_response=CommonHelper::checkDelivery_new($inputs);
                  
				  /*
          if ($back_response)
          {                     
              setcookie('pincode', $shipping_adddress->shipping_pincode, time() + (86400 * 30), "/");
              setcookie('pincode_error', 0, time() + (86400 * 30), "/");
          } else{
              setcookie('pincode', $shipping_adddress->shipping_pincode, time() + (86400 * 30), "/");
              setcookie('pincode_error', 1, time() + (86400 * 30), "/");
              return Redirect::route('checkout')->withErrors(['Delivery not available in your port code']);         
          
         }
         */


	    } else{
	         return Redirect::route('checkout');
	    }
	 
        
//         if($request->session()->get('shipping_address_id')==0  $_COOKIE["shipping_address_id"]){
// 			return Redirect::route('checkout');
//         }

                // if(@$_COOKIE["shipping_address_id"]){
                
                //     } else{
                //       return Redirect::route('checkout');
                //     }
        
        
        // $address_id=$request->session()->get('shipping_address_id');
//         $address_id= $_COOKIE["shipping_address_id"];
       
    
//       $shipping_adddress=CheckoutShipping::getshippingAddressOfCustomer($address_id,$cust_id);
    
//     $cust_info=Customer::where('id',$cust_id)->first();
  
//             unset($_COOKIE['ship_details']); 
         
//                 $inputs=array(
//                     "pincode"=>$shipping_adddress->shipping_pincode,
//                     "price"=>200,
//                     "product_name"=>"apple",
//                     "qty"=>1,
//                     "weight"=>2,
//                     "height"=>2,
//                     "length"=>2,
//                     "width"=>2
//                     );
                    
//                     $back_response=CommonHelper::checkDelivery($inputs);
//                      $output = (array)json_decode($back_response);
                   
//                      if (array_key_exists("delivery_details",$output))
//                     {
                      
// setcookie('pincode', $shipping_adddress->shipping_pincode, time() + (86400 * 30), "/");
// setcookie('pincode_error', 0, time() + (86400 * 30), "/");

//                      } else{
// setcookie('pincode', $shipping_adddress->shipping_pincode, time() + (86400 * 30), "/");
// setcookie('pincode_error', 1, time() + (86400 * 30), "/");
//                          return Redirect::route('checkout')->withErrors(['Delivery not available in your area']);


            
                        
//                     }
            
$is_review = ($request->ba == 'true' || $request->ba == 1)?$request->ba:false;
        return view('fronted.mod_checkout.checkoutReview',array(
        	    "shipping_address"=>$shipping_adddress,
                'billing_adddress' => $billing_adddress,
				"cust_info"=>$cust_info,
        	    "cart_data"=>$cart_data,
                'states' => CommonHelper::getState('101'),
                "is_review" => $is_review
        	    ));
    }
	
	
public function isIt_myFirst_order(){
	/*
// 	  if my first order
	    $myfistr_order=DB::table('user_referrals')
	    ->where('c_id',auth()->guard('customer')->user()->id)
	    ->where('p_id',auth()->guard('customer')->user()->r_by)
	    ->where('first_order_placed',0)
	    ->first(); 
	    
	    if($myfistr_order){
	       // 	  get define price
	    $refer_price=DB::table('store_info')
	        ->select('parent_amount','child_amount')
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
	        
	        // 	transfer to child
               
	    DB::table('tbl_refer_earn')
	    ->insert(array(
                'user_id'=>$cust->id,
                'rel_id'=>$cust->r_by,
                'amount'=>$refer_price->child_amount,
                'mode'=>0
	        ));
                
                // update parents refer amount
                Customer::
                where('id',$cust->id)
                ->increment('r_amount',$refer_price->child_amount);
                
                //update wallet amount of customer
                 Customer::
                where('id',$cust->id)
                ->increment('total_reward_points',$refer_price->child_amount);
                
                 // create wallet history
	    DB::table('tbl_wallet_history')
	    ->insert(array(
                'fld_customer_id'=>$cust->id,
                'fld_order_id'=>0,
                'fld_order_detail_id'=>0,
                'fld_reward_points'=>$refer_price->child_amount,
                'fld_reward_narration'=>'Earned',
                'mode'=>2
	        ));
                 
	    }
	    */
	    
                
	}
	public function submit_order(Request $request)
    {

        /**
         * Exibution code setup is applied
         */
         $ExibutionData = Session::get('ExibutionData');
        $ExibutionCode = '';
        $ExibutionName ='';
        $ExibutionId ='';       
       
       if(!empty($ExibutionData)){
            if($ExibutionData['status'] == 1){
                $ExibutionCode = $ExibutionData['code']; 
                $ExibutionName = $ExibutionData['name']; 
                $ExibutionId = $ExibutionData['id']; 
            }
       }
       
     
       $paymentDetails=$request->session()->get('paymentDetails');
    


       //if(isset($_COOKIE['sitecity']) && isset($_COOKIE['shipping_address_id'])){
		   if(isset($_COOKIE['shipping_address_id'])){
            //$sitecityname= CommonHelper::selectedCityName($_COOKIE['sitecity']);
            $shipping_adddress=CheckoutShipping::where('id',$_COOKIE["shipping_address_id"])->first();
            if($shipping_adddress){
                /*if($shipping_adddress->shipping_city===$sitecityname){
                } else{
                  
                    MsgHelper::save_session_message('danger','Products not available in your city',$request);
                    return redirect()->route('review_order');  
                }*/
            } else{
                 
            //      	MsgHelper::save_session_message('danger','Products not available in your city1',$request);
            //   return redirect()->route('review_order');  
            }
           
	    } else{
	       
	        	MsgHelper::save_session_message('danger','Please select an address',$request);
	         return redirect()->route('review_order');
	    }
      
         $shipping_charges_details=CommonHelper::getShippingDetails();
         
        $input=$request->all();
		$cust_id=auth()->guard('customer')->user()->id ;
	
		
		$cart_data=app(\App\Http\Controllers\cart\CartController::class)->getCart_item($request->ip(),$cust_id);

       

		 if(count($cart_data)==0){
		    	MsgHelper::save_session_message('danger','Something went wrong',$request);
              return redirect()->route('review_order'); 
		}
		
$shipping_adddress=CheckoutShipping::where('id',$_COOKIE["shipping_address_id"])->first();

        /**
         * Billing Address setup 
         */

        $billing_address = array(); 
        if($request->is_ba == 'true' && !empty($_COOKIE["billing_address_id"])){
            $billing_address = CheckoutBillingAddress::where('id', $_COOKIE["billing_address_id"])->first();
        }
       
        $order_no='KFH'.date('YmdHis');
        
            $finalAddressCity=$shipping_adddress->shipping_city;
            $invalidProducts=0;
            $validProducts=0;
            
            for($i=0;$i<count($cart_data);$i++)
		 {
		     $productInSelectedLocation = DB::table('products')
                				->select(
                							'products.id'
                						)
                                    ->join('vendors','products.vendor_id','vendors.id')
                                    ->join('vendor_company_info','vendor_company_info.vendor_id','vendors.id')
                                    /*->where('vendor_company_info.city',$finalAddressCity)*/
                                    ->where('products.id',$cart_data[$i]->prd_id)
                		            ->first();
                if($productInSelectedLocation){
                $validProducts++;
                }else{
                 $invalidProducts++;
                }
		    
		 }
		 
		   if($invalidProducts>0){
                MsgHelper::save_session_message('danger','Products not available in your city',$request);
                return redirect()->route('review_order');
                die();
         }
            
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


        
        /**
         * Billing address manage 
         */
        $billing_id = 0;
        if(!empty($billing_address)){

            $order_billing_addresses = array(
                'order_id' => '',
                'order_shipping_name' => $billing_address->shipping_name.' '.$billing_address->shipping_last_name,
                'order_shipping_address' => $billing_address->shipping_address,
                'order_shipping_address1' => $billing_address->shipping_address1,
                'order_shipping_address2' => $billing_address->shipping_address2,
                'order_shipping_city' => $billing_address->shipping_city,
                'order_shipping_state' => $billing_address->shipping_state,
                'order_shipping_zip' => $billing_address->shipping_pincode,
                'order_shipping_phone' => $billing_address->shipping_mobile,
                'order_shipping_email' => $billing_address->shipping_email,
            );  
            DB::table('orders_billing_addresses')->insert($order_billing_addresses);
            $billing_id = DB::getPdo()->lastInsertId();
        }
				
		$tcs_info=DB::table('tbl_settings')->select('tcs_tax_percentage','tds_tax_percentage')->where('id',1)->first();
							
		$tcs_amt=number_format(((($paymentDetails['grandTotal'])*$tcs_info->tcs_tax_percentage)/100),2);
				
        $tds_amt=number_format(((($paymentDetails['grandTotal'])*$tcs_info->tds_tax_percentage)/100),2);

        $orderPaymentMode = $input['payment_mode'];
        $exhibition_payment_mode = ''; 
        if(in_array($input['payment_mode'], [2,3,4,6])){
            $orderPaymentMode = 2; 
        }

        if($input['payment_mode'] == 2){
            $exhibition_payment_mode ='PAYTM';
        }elseif($input['payment_mode'] == 3) {
            $exhibition_payment_mode ='GPAY';
        }elseif($input['payment_mode'] == 4) {
            $exhibition_payment_mode ='PHONEPAY';
        }elseif($input['payment_mode'] == 6) {
            $exhibition_payment_mode ='CASH';
        }

        if($input['payment_mode'] == 5){
            $orderPaymentMode = 3; 
        }


		$order=array(
                'customer_id'=>$cust_id,
                'shipping_id'=>$shipping_id,
                'billing_addresss_id' => $billing_id,
                'exhibitions'=>$ExibutionId,
                'exhibition_name'=>$ExibutionName,
                'exhibition_code'=>$ExibutionCode,
                'exhibition_payment_mode'=>$exhibition_payment_mode,
                'order_no'=>$order_no,
                'grand_total'=>$paymentDetails['grandTotal'],
                'subTotal'=>$paymentDetails['subTotal'],
                'cod_charges'=>($input['payment_mode']==0 && $paymentDetails['subTotal'] < 900)?$shipping_charges_details->cod_charges:0,
                'coupon_code'=>$paymentDetails['coupon_code'],
                'coupon_percent'=>$input['coupon_percent'],
                'coupon_amount'=>$paymentDetails['discount'],
				'deduct_reward_points'=>$paymentDetails['usePoints'],
                'total_shipping_charges'=>$paymentDetails['shippingCharges'],
                'service_charge' => $paymentDetails['serviceCharge'],
                'payment_mode'=>$orderPaymentMode,
                'tax_percent'=>$paymentDetails['tax'],
                'tcs_percentage'=>$tcs_info->tcs_tax_percentage,
                'tcs_amt'=>$tcs_amt,
                'tds_percentage'=>$tcs_info->tds_tax_percentage,
                'tds_amt'=>$tds_amt,
                'delivery_time' => $input['delivery_time'],
				'slot_price' => @$input['slot_price'],
				'delivery_date'	=> $input['delivery_date'],
				'order_date'	=> date("Y-m-d H:i:s"),
                'order_status'=>($input['payment_mode'] == 1)?9:0,
                 'order_from'=>'WEB'
			);
			
				$coupon_code=$paymentDetails['coupon_code'];
	            $discountAmount = $paymentDetails['discount'];

         	DB::table('orders')->insert($order);
         	$order_id=DB::getPdo()->lastInsertId();
         	

             Session::forget('ExibutionData'); //removing ExibutionData
				
				DB::table('orders')->where('id',$order_id)->update(
		 	    array(
		 	        'order_no'=>'KFH'.$order_id,
                     'service_invoice_num'=>'KEF'.$order_id,
                     'service_invoice_date'=>date('Y-m-d')
		 	        )
		 	    );
         	
         		DB::table('orders_shipping')->where('id',$shipping_id)
         		->update(
         		    array(
         		        'order_id'=>$order_id
         		    ));

                if($orderPaymentMode == 3){
                    DB::table('orders')->where('id',$order_id)->update(
                        array(
                                'txn_id'=> Str::uuid(),                   
                            )
                        );
                }


            /**
             * Updating billing address order ID
             */
            DB::table('orders_billing_addresses')->where('id', $billing_id)
            ->update(
                array(
                    'order_id' => $order_id
                ));


         	
		 $grand_total=$total_reward_points=0;
		 
		 $product_commission_master=0;
		 for($i=0;$i<count($cart_data);$i++)
		 {

            $vendor_delivery_charge=0;
            $Gst_Product_Tax=0;
            $productCategoryData = ProductCategories::getProductLatestCategory($cart_data[$i]->prd_id);     
            if(!empty($productCategoryData)){
                $categoryData = Category::select('delivery_charge','tax_rate')->where('id',$productCategoryData->cat_id)->first();
                if(!empty($categoryData)){
                    $vendor_delivery_charge = $categoryData->delivery_charge;
                    $Gst_Product_Tax = $categoryData->tax_rate;
                }                
            } 
            

			 $prd_points=DB::table('product_reward_points')->where('product_id',$cart_data[$i]->prd_id)->first();
                        $prc=$cart_data[$i]->master_spcl_price;
                        $old_price=0;
			  
			 $reward_points=$prd_points->reward_points;
                    if ($cart_data[$i]->master_price!='')
                    {
                    $old_price=$cart_data[$i]->master_price;
                    
                    }
			  
        if($cart_data[$i]->color_id==0 && $cart_data[$i]->size_id!=0){
        
            $attr_data=DB::table('product_attributes')
            ->where('product_id',$cart_data[$i]->prd_id)
            ->where('size_id',$cart_data[$i]->size_id)
            ->first();
            if($old_price!=0){
                 $old_price+=$attr_data->price;
            }
           
            $prc+=$attr_data->price;
            
        }
        if($cart_data[$i]->color_id!=0 && $cart_data[$i]->size_id==0){
            $attr_data=DB::table('product_attributes')
            ->where('product_id',$cart_data[$i]->prd_id)
            ->where('color_id',$cart_data[$i]->color_id)
            ->first();
             if($old_price!=0){
                 $old_price+=$attr_data->price;
            }
            $prc+=$attr_data->price;
        }
        if($cart_data[$i]->color_id!=0 && $cart_data[$i]->size_id!=0){
            $attr_data=DB::table('product_attributes')
            ->where('product_id',$cart_data[$i]->prd_id)
            ->where('color_id',$cart_data[$i]->color_id)
            ->where('size_id',$cart_data[$i]->size_id)
            ->first();
          if($old_price!=0){
                 $old_price+=$attr_data->price;
            }
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
                //$order_deduct_reward_points=round((($prc/$paymentDetails['grandTotal'])*$paymentDetails['usePoints']),2);
         
                if($paymentDetails['usePoints'] >= $paymentDetails['subTotal']){
                    $order_deduct_reward_points=round((($prc/$paymentDetails['subTotal'])*$paymentDetails['subTotal']),2);
                }else{
                    $order_deduct_reward_points=round((($prc/$paymentDetails['subTotal'])*$paymentDetails['usePoints']),2);
                }  
			}
			  $product_coupon_amt=0;
              /*
              //this code is not working because $cart_data[$i]->fld_cart_id id not found. 
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
            */

            /***
             * exhibiton discount amount split 
             */
			
            if(!empty($ExibutionData) && $paymentDetails['exhibition_discount'] > 0){
                $product_coupon_amt = (($prc * $cart_data[$i]->qty)/$paymentDetails['subTotal'])*$paymentDetails['exhibition_discount'];
            }



			 $cod_charges=0;
        if($input['payment_mode']==0 && $paymentDetails['subTotal'] < 900){
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
        
         $slot_prices=0;
        if($paymentDetails['slotprice']>0){
            $total_products=sizeof($cart_data);
            $slot_prices=($paymentDetails['slotprice'])/$total_products;        
            $slot_prices = number_format($slot_prices,2);
        }

        $ProductBasePrice = $prc - number_format(($prc*$Gst_Product_Tax)/100,2);
        $tcs_info_amt=number_format(((($cart_data[$i]->qty*$ProductBasePrice)*$tcs_info->tcs_tax_percentage)/100),2);
        $tds_info_amt=number_format(((($cart_data[$i]->qty*$ProductBasePrice)*$tcs_info->tds_tax_percentage)/100),2);


        /**
         * Seller invoice Data setup start
         */
           $storeInfoData = DB::table('store_info')
                            ->select('Tcs','paymentgateway','logistics_tax')
                            ->first();         

           $CourierCharges = $sellerInvoiceTCS = $paymentgatewayTax = $logistics_tax =  0;
           $ProductTotalWeight = $cart_data[$i]->qty * $ship_data->weight;
           $CourierChargesData = DB::table('businesssettings')
           ->select('prices')
           ->where('from','<=', $ProductTotalWeight)
           ->where('c_to','>=', $ProductTotalWeight)
           ->first();

            if(!empty($storeInfoData)){
                $sellerInvoiceTCS = $storeInfoData->Tcs;
                if($input['payment_mode'] == 1){
                    $paymentgatewayTax = $storeInfoData->paymentgateway;
                }
                $logistics_tax = $storeInfoData->logistics_tax;
            }
            if(!empty($CourierChargesData))
            {
                $CourierCharges = $CourierChargesData->prices;
            }

            

         /**
         * Seller invoice Data setup end
         */

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
                // 'order_coupon_amount'=>$dis,
                 'order_coupon_amount'=>$product_coupon_amt,
                'order_shipping_charges'=>$product_shipping_charges,
                'tcs_percentage'=>$tcs_info->tcs_tax_percentage,
                'tcs_amt'=>$tcs_info_amt,
                'tds_percentage'=>$tcs_info->tds_tax_percentage,
                'tds_amt'=>$tds_info_amt,
                'order_commission_rate'=>(Int)$product_commission_rate,
                'return_days'=>$ship_data->return_days,
                'weight'=>$ship_data->weight,
                'slot_price' => @$slot_prices,
                'order_status'=>($input['payment_mode'] == 1)?9:0,
                'order_vendor_shipping_charges' => $vendor_delivery_charge,
                "courier_charges" => $CourierCharges,
                "seller_invoice_tcs" =>  $sellerInvoiceTCS,
                "payment_gateway_tax" => $paymentgatewayTax,
                "logistics_tax" => $logistics_tax
				);
				
				
				DB::table('order_details')->insert($order_detail);
				$order_detail_id=DB::getPdo()->lastInsertId();
				
				DB::table('order_details')->where('id',$order_detail_id)->update(
		 	    array(
		 	        'suborder_no'=>'KFHS'.$order_detail_id
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



          /**
             * Code here for Split coupon amount
             */
            if(!empty($coupon_code)){
                Coupon::couponSplitAmountCheck($coupon_code, $cust_id, $order_id, $discountAmount, $grand_total);
            }


            /*
            DB::table('coupon_details')->where('coupon_code', $input['coupon_code'])
                ->update(
                    array(
                        'coupon_used' => 1
                    ));
                    */



		 	DB::table('orders')->where('id',$order_id)->update(
		 	    array(
		 	        'total_commission_rate'=>$product_commission_master
		 	        )
		 	    );
         
        /**
         * In case of COD , Exhibution , WALLET payment Instant wallet amount minus on order place
         */
        if(in_array($input['payment_mode'], [0,2,3,4,5,6])){
		 
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
        }

		if($input['payment_mode']==1){ //online
            $product_shipping_charges = 0;
            if($paymentDetails['shippingCharges']>0){
                $product_shipping_charges=$paymentDetails['shippingCharges'];
           }

			$review_order=url("/review_order");
			$return_url=url("/success/$order_id/$order_id");
			$merchant_order_id=$order_id; //product_id
			$txnid=$order_id;
			$productinfo='Test Product';
			$surl=url("/success/$order_id/$order_id");
			$furl=url("/failed/$order_id/$order_id");
			$total=round($paymentDetails['grandTotal']+$product_shipping_charges)*100;
			$amount=round($paymentDetails['grandTotal']+$product_shipping_charges)*100;
			$card_holder_name=$order_shipping['order_shipping_name'];

            $key_id=Config::get('constants.Razorpay.key'); 
            $key_secret = Config::get('constants.Razorpay.secret');


            $order_data = array (
                'amount' => $amount,
                'currency' => 'INR',
            );
            $orderUrl = 'https://api.razorpay.com/v1/orders';
             $order_params = http_build_query($order_data);
                $ch_order = curl_init();
                curl_setopt($ch_order, CURLOPT_URL, $orderUrl);
                curl_setopt($ch_order, CURLOPT_USERPWD, $key_id . ':' . $key_secret);
                curl_setopt($ch_order, CURLOPT_TIMEOUT, 60);
                curl_setopt($ch_order, CURLOPT_POST, 1);
                curl_setopt($ch_order, CURLOPT_POSTFIELDS, $order_params);
                curl_setopt($ch_order, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch_order, CURLOPT_SSL_VERIFYPEER, true);
                $result_order = curl_exec($ch_order);
                $data_order_response = json_decode($result_order);
                $http_status_order = curl_getinfo($ch_order, CURLINFO_HTTP_CODE);
                curl_close($ch);
                Orders::where('id', $order_id)
                ->update([
                    'razorpay_order_id' => $data_order_response->id,
                ]);


            if(!empty($data_order_response->id)){
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
                        var key_id = "'.$key_id.'";
                        var store_name = "KFH";
                        var store_description = "Payment";
                        var store_logo = "http://b2cdomain.in/kefih/public/fronted/images/logo.jpg";
                        var email = "'.$order_shipping['order_shipping_email'].'";
                        var phone = "'.$order_shipping['order_shipping_phone'].'";
    
					  var razorpay_options = {
						key: "'.$key_id.'",
						amount: "'.$total.'",
                        order_id: "'.$data_order_response->id.'",
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
         }else{
                MsgHelper::save_session_message('danger','Something Went Wrong! Try Later.',$request);
                return Redirect::back();
            }
			
		}elseif(in_array($input['payment_mode'], [0,2,3,4,5,6])){  //cod
			self::removeProductFromwishlist($request->ip(),$cust_id);
			    //   $this->generateMailforOrder($order_id,$cust_id);
			 
                if(auth()->guard('customer')->user()->r_by>0){
                // $this->isIt_myFirst_order();
                }
		     
		      
				$oder_data=DB::table('orders')->where('id',$order_id)->first();
				 $this->CouponUsed($oder_data->coupon_code);
// 			$request->session()->forget('cart_coupon');
			self::decreaseAllProductQtyByOrder($request->ip(),$cust_id);
            FbConversionHelper::fbCoversion($order_id, $request);
			return view('fronted.mod_checkout.thank-you',array('order_id'=>$oder_data->order_no,'real_order_id'=>$order_id));
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
            $key_id = Config::get('constants.Razorpay.key');
            $key_secret = Config::get('constants.Razorpay.secret');
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
                app(\App\Http\Controllers\CookieController::class)->remove_cokkie_cart(); 

                  DB::table('orders')->where('id',$merchant_order_id)->update(
		 	    array(
		 	        'order_status'=>0,
                     'txn_id' => $razorpay_payment_id,
                     'txn_status' => 'success'
		 	        )
		 	    );
		 	     DB::table('order_details')->where('order_id',$merchant_order_id)->update(
		 	    array(
		 	        'order_status'=>0
		 	        )
		 	    );


                /**
                 * Deduct wallet amount on payment success
                 */
                $MasterOrderData = DB::table('orders')->select('customer_id','deduct_reward_points')->where('id',$merchant_order_id)->first(); 

                if($MasterOrderData->deduct_reward_points>0){

                    if($MasterOrderData->deduct_reward_points!=0 && $MasterOrderData->deduct_reward_points!=''){
                        $wallet=array(
                                   'fld_customer_id'=>$MasterOrderData->customer_id,
                                   'fld_order_id'=>$merchant_order_id,
                                   'fld_order_detail_id'=>0,
                                   'fld_reward_narration'=>'Deducted',
                                   'fld_reward_deduct_points'=>$MasterOrderData->deduct_reward_points
                               );
                       

                       $isCreated = DB::table('tbl_wallet_history')->insert($wallet);

                    }                    
                                       
                   $cust_points=DB::table('customers')->where('id',$MasterOrderData->customer_id)->first();
                   $deduct_amt=$cust_points->total_reward_points-$MasterOrderData->deduct_reward_points;
                   
                   DB::table('customers')->where('id',$MasterOrderData->customer_id)
                            ->update(
                                array(
                                    'total_reward_points'=>($deduct_amt)
                                ));
                }




		 	    
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
                 $this->toAdminMailOnOrderPlace($order_id,$cust_id);
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
            // $mode= ($master_order->payment_mode==0)?"COD":"Paid";
            $mode= '';
            if($master_order->payment_mode==0){
                $mode="COD";
            }elseif($master_order->payment_mode==1){
                $mode="Online";
            }elseif($master_order->payment_mode==2){
                $mode="Exhibition($master_order->exhibition_payment_mode)";
            }
            elseif($master_order->payment_mode==3){
                $mode="Wallet";
            }
            
            $customer_data=Customer::where('id',$cust_id)->first();
            $shipping_data=OrdersShipping::where('order_id',$order_id)->first();
			$city_data=DB::table('cities')->where('id',$shipping_data->order_shipping_city)->first();
			$state_data=DB::table('states')->where('id',$shipping_data->order_shipping_state)->first();
				
				if($master_order->payment_mode==0) {
					
					$msg=view("message_template.cod_order_placedMessage",
										array(
									'data'=>array(
										'name'=>ucfirst($customer_data->name),
										'order_no'=>$master_order->order_no,
										'order_date'=>$master_order->order_date
										)
										) )->render();					
								
					}
				else {
					$msg=view("message_template.online_order_placedMessage",
										array(
									'data'=>array(
                                        'name'=>$customer_data->name,
                                        'order_no'=>$master_order->order_no,
                                        'order_date'=>$master_order->order_date
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

                        //  echo view("emails_template.order_confirmation",
                        //  array(
                        //  'message'=>$email_msg,
                        //  'customer_info'=>$customer_data,
                        //  'shipping_info'=>$shipping_data,
                        //  'extra_info'=>$master_order,
                        //  'product_info'=>$master_orders,
                        //  'city_info'=>$city_data,
                        //  'state_info'=>$state_data,
                        //  'payment'=>$mode
                        //  ) )->render();
                        //  die; 
            CommonHelper::SendMsg($email_data);
            if(!empty($customer_data->email))  {
                CommonHelper::SendmailCustom($email_data);
            }                
        
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
          /*
        	DB::table('orders')
        	->where('id',$request->merchant_order_id)
        	->update(
        	    array(
        	        'txn_id'=>$request->merchant_trans_id,
        	         'txn_status'=>1
        	        )
        	    );

                */
			 $this->generateMailforOrder($order_id,$cust_id);
                    if(auth()->guard('customer')->user()->r_by>0){
                    // $this->isIt_myFirst_order();
                    }
			  $this->CouponUsed($oder_data->coupon_code);
		 self::decreaseAllProductQtyByOrder($request->ip(),$cust_id);
		 	$oder_data=DB::table('orders')->where('id',$order_id)->first();
             FbConversionHelper::fbCoversion($order_id, $request);
		return view('fronted.mod_checkout.thank-you',array('order_id'=>$oder_data->order_no,'total'=>$oder_data->grand_total));
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
   
	


    public function addBillingAddress(Request $request)
    {
      

        if ($request->isMethod('post')) {
            $request->validate([
                'shipping_email'=> 'required|email|max:255',
                'shipping_name' => 'required|max:50',
                'shipping_last_name' => 'required|max:50',
                'shipping_mobile' => 'required|max:10',
                'shipping_address' => 'required|max:255',
                'shipping_address1' => 'max:255',
                'shipping_address2' => 'max:255',
                'shipping_city' => 'required|max:50',
                'shipping_state' => 'required|max:50',
                'shipping_pincode' => 'required|max:6',
                'shipping_address_type' => 'required',
                'shipping_address_default' => 'nullable'
            ], [
                    'shipping_name.required' => 'Name is required',
                    'shipping_name.max' => 'Name can not exceed to 50 characters',
                    'shipping_mobile.required' => 'Mobile is required',
                    'shipping_mobile.max' => 'Mobile can not exceed to 10 characters',
                    'shipping_address.required' => 'Address is required',
                    'shipping_address.max' => 'Address can not exceed to 255 characters',
                    'shipping_pincode.required' => 'Pincode is required',
                    'shipping_address.regex' => 'Shipping Address Must have atleast one digit and one alphabet',
                    'shipping_pincode.max' => 'Pincode can not exceed to 50 characters',
                    'shipping_state.required' => 'State is required',
                    'shipping_state.max' => 'State can not exceed to 50 characters',
                    'shipping_city.required' => 'City is required',
                    'shipping_city.max' => 'City can not exceed to 50 characters',
                    'shipping_address1.max' => 'Area can not exceed to 255 characters',
                    'shipping_address2.max' => 'Landmark can not exceed to 255 characters',
                    'shipping_address_type.required' => 'Shipping Address Type is required',
                    'shipping_address_default.required' => 'Shiping Address Default is required',
                ]
            );


            $input = $request->all();
            $cust_id = auth()->guard('customer')->user()->id;
            $states = DB::table('states')->where('id', $input['shipping_state'])->first();
            $CheckoutBillAddress = new CheckoutBillingAddress;
            $CheckoutBillAddress->customer_id = $cust_id;
            $CheckoutBillAddress->shipping_name = $input['shipping_name'];
            $CheckoutBillAddress->shipping_email = $input['shipping_email'];   
            $CheckoutBillAddress->shipping_last_name = $input['shipping_last_name'];
            $CheckoutBillAddress->shipping_mobile = $input['shipping_mobile'];
            $CheckoutBillAddress->shipping_address = $input['shipping_address'];
            $CheckoutBillAddress->shipping_address1 = $input['shipping_address1'];
            $CheckoutBillAddress->shipping_address2 = $input['shipping_address2'];
            $CheckoutBillAddress->shipping_city = $input['shipping_city'];
            $CheckoutBillAddress->shipping_state = $states->name;
            $CheckoutBillAddress->shipping_pincode = $input['shipping_pincode'];
            $CheckoutBillAddress->shipping_address_type = $input['shipping_address_type'];
            $CheckoutBillAddress->shipping_address_default = isset($input['shipping_address_default']) ? 1 : 0;
         

            /* save the following details */
            if ($CheckoutBillAddress->save()) {
                $address_id = $CheckoutBillAddress->id;
                if (isset($input['shipping_address_default'])) {
                    $input_default_set = array('shipping_address_default' => 0);
                    $CheckoutBillAddress::where('customer_id', $cust_id)->where('id', '!=', $address_id)->update($input_default_set);
                }
                setcookie('billing_address_id', $address_id, time() + (86400 * 30), "/");
                MsgHelper::save_session_message('success','Billing Address Added',$request);
            
                //  return Redirect::back();
                // return Redirect::route('review_order');

                $isreview = false;
                if(!empty($request->cfrom) && $request->cfrom == 'review')
                {
                    $isreview = true;
                    $to = route('review_order').'?ba='.$isreview;
                    return Redirect::to($to);
                }else{
                    return Redirect::route('billingAddresses');
                }

               


            } else {
                MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
                return Redirect::back();
            }

        }

        return view('admin.mod_checkout.checkout');
    }


    public function selectBillingAddress(Request $request)
    {
        $minutes = (8640 * 30);
        $shipping_id = base64_decode($request->shipping_id);
        setcookie('billing_address_id', $shipping_id, time() + (86400 * 30), "/");
    
        $to = route('review_order').'?ba=true';

        return Redirect::to($to);

    }

    public function removeBillingAddress(Request $request)
    {

        CheckoutBillingAddress::where('id', base64_decode($request->shipping_id))
            ->update([
                'isdeleted' => 1
            ]);

        MsgHelper::save_session_message('success', Config::get('messages.common_msg.shippingAddressDeleted'), $request);
        return Redirect::back();
    }


    public function editBillingAddress(Request $request)
    {
        $id = base64_decode($request->shipping_id);


        if ($request->isMethod('post')) {
            $input = $request->all();
            
            $request->validate([
                'shipping_email'=> 'required|email|max:255',
                'shipping_name' => 'required|max:50',
                'shipping_last_name' => 'required|max:50',
                'shipping_mobile' => 'required|min:10|max:10',
                'shipping_address' => 'required|max:255',
                'shipping_address1' => 'max:255',
                'shipping_address2' => 'max:255',
                'shipping_city' => 'required|max:50',
                'shipping_state' => 'required|max:50',
                'shipping_pincode' => 'required|min:6|max:6',
                'shipping_address_type' => 'required'
            ], [
                    'shipping_name.required' => 'Name is required',
                    'shipping_name.max' => 'Name can not exceed to 50 characters',
                    'shipping_address.regex' => 'Shipping Address Must have atleast one digit and one alphabet',
                    'shipping_mobile.required' => 'Mobile is required',
                    'shipping_mobile.max' => 'Mobile can not exceed to 10 characters',
                    'shipping_address.required' => 'Address is required',
                    'shipping_address.max' => 'Address can not exceed to 255 characters',
                    'shipping_pincode.required' => 'Pincode is required',
                    'shipping_pincode.max' => 'Pincode can not exceed to 50 characters',
                    'shipping_state.required' => 'State is required',
                    'shipping_state.max' => 'State can not exceed to 50 characters',
                    'shipping_city.required' => 'City is required',
                    'shipping_city.max' => 'City can not exceed to 50 characters',
                    'shipping_address1.max' => 'Area can not exceed to 255 characters',
                    'shipping_address2.max' => 'Landmark can not exceed to 255 characters',
                    'shipping_address_type.required' => 'Shipping type is required',
                ]
            );


            $states = DB::table('states')->where('id', $input['shipping_state'])->first();

            $input_array =
                array(
                    'shipping_name' => $input['shipping_name'],                
                    'shipping_last_name' => $input['shipping_last_name'],  
                    'shipping_email' => $input['shipping_email'],  
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


            if ($input_array['shipping_address_default'] == 1) {
                $cust_id = auth()->guard('customer')->user()->id;
                $input_default_set = array('shipping_address_default' => 0);
                CheckoutBillingAddress::where('customer_id', $cust_id)->where('id', '!=', $id)->update($input_default_set);
            }

            $rs = CheckoutBillingAddress::where('id', $id)->update($input_array);

            /* save the following details */
            if ($rs) {               
                MsgHelper::save_session_message('success', Config::get('messages.common_msg.shippingAddressUpdated'), $request);
                return Redirect::route('billingAddresses');
            } else {
                MsgHelper::save_session_message('danger', Config::get('messages.common_msg.data_save_error'), $request);
                return Redirect::back();
            }

        }
        $data = CheckoutBillingAddress::select('customer_billing_address.*')->where('id', $id)->first();
        $states = CommonHelper::getState($data->shipping_state);
        $cities = CommonHelper::getCityFromState($data->shipping_state);


        $states1 = DB::table('states')->where('name', $data->shipping_state)->first();
        //print_r($states1->id);die;
        $cities = CommonHelper::getCityFromState($states1->id);

        $ship_address_list = CheckoutBillingAddress::getshippingAddress($id);

        return view('fronted.mod_checkout.billingAddressEdit', [
            'shipping_data' => $data,
            'id' => $id,
            'ship_address_list' => $ship_address_list,
            "states" => $states,
            "cities" => $cities
        ]);


    }


    public function billingAddresses(Request $request)
    {
        $id = auth()->guard('customer')->user()->id;

        $cart_data = app(\App\Http\Controllers\cart\CartController::class)->getCart_item($request->ip(), $id);
        if (sizeof($cart_data) == 0) {
            return Redirect::route('index');
        }
        $states = CommonHelper::getState('101');
        $ship_address_list = CheckoutBillingAddress::getshippingAddress($id);
        return view('fronted.mod_checkout.billingAddresses', [
            'shipping_listing' => $ship_address_list,
            'states' => $states
        ]);
    }
	
 
}
