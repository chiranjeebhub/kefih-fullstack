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
use App\ProductRating;
use App\ProductCategories;
use App\ProductImages;
use App\Category;
use App\DeliveryBoy;
use Config;
use App\OrdersDetail;
use App\Orders;
use App\Vendor;
use App\OrdersShipping;
class DeliveryboyController extends Controller 
{	
	public function __construct()
    {
        $this->site_base_path=Config::get('constants.Url.public_url');
        //$this->middleware('auth');
    }
    public function ordersCount(Request $request){
      try{
      
          $totalassign =DB::table('order_details')->where(['deliveryID'=>$request->id,'order_status'=>'1'])->get();
          $totalcom =DB::table('order_details')->where(['deliveryID'=>$request->id,'order_status'=>'3'])->get();
          $totalcancel =DB::table('order_details')->where(['deliveryID'=>$request->id,'order_status'=>'4'])->get();
         
             return response()->json([
               "status" => true,
               'message'=>'Success',
               'code' => 200,
               "delivered"=>sizeof(@$totalcom),
               "cancel"=>sizeof(@$totalcancel),
               "pending"=>sizeof(@$totalassign)
     
             ]);
     
           
         }   
         catch (Exception $e) {
     
           return response()->json([
     
             "status" => false,
     
             'message'=>'Something went to wrong',
     
             'code' => 500
     
           ]);
     
         }
     
          }
public function login(Request $request) {
      $inputs=$request->all();
    try{
      $validation = Validator::make($request->all(),[
        'phone' => 'required',
        'password' => 'required',
      ]);
      if($validation->fails()){
        return response()->json(
        [
          'message'=>'Invalid request data',
          'status'=>false,
          'statusCode'=>400,
          "data"=>array()
        ]);
      } else {
      	
      	
        $user = DeliveryBoy::where('phone',$request->phone)->first();
        if(!empty($user)){
          if (!Hash::check($request->password, $user->password)){
            return response()->json([
                'message'=>'Password not macthed',
               // 'isOtpVerified'=>$user->isOtpVerified,
                'status'=>false,
                'statusCode'=>404,
               
            ]);
          } 
          else if($user->status==1){
          	$string = '0123456789';
    $string_shuffled = str_shuffle($string);
    $otp = substr($string_shuffled, 0,4);
  $messageotp=$otp." your one time password for varification";
 $email_data = ['phone'=>$request->phone,'phone_msg'=>$messageotp,'otp'=>''];
           CommonHelper::SendMsg($email_data);
         DeliveryBoy::where('id',$user->id)->update(['otp'=>$otp]); 	
          	
          	return response()->json([
              'message'=>'Verify your account to continue Login',
              'status'=>true,
              'statusCode' => "201",
              'userID'=>$user->id,
              'phone' => @$user->phone,
            ]);
           //if($user->isOtpVerified=='1'){
            //$accessToken = auth()->user()->createToken('authToken')->accessToken;
            //$accessToken = $user->createToken('authToken')->accessToken;
          /*  return response()->json([
              "status" => true,
              "statusCode" => 200,
              'message'=>'Login successfully',
              'isOtpVerified'=>1,
              "data"=>array(
                "user_id"=>$user->id,
                "name"=>$user->name,
                "address"=>$user->address,
                "mobile_no"=>$user->phone,
                "email"=>$user->email,
              )
            ]);*/
          /*} 
       else{
            return response()->json([
              'message'=>'Verify your account to continue Login',
              'status'=>true,
              'statusCode' => "201",
              'userID'=>$user->id,
              'phone' => @$user->phone,
            ]);
          }*/
        } 
        else{
			return response()->json([
              'message'=>'Account is not actived contact to administration!',
              'status'=>fALSE,
              "statusCode" => 404
            ]);  	
			}
			}
        
        else {
          return response()->json([
            "statusCode" => 404,
            'message'=>'User Not exist',
            'status'=>false
          ]);
        }
      }
    }catch(Exception $e){
      return response()->json([
        "statusCode" => 500,
        'message'=>'Something went wrong.',
        'status'=>false,
        "data"=>null
      ], 500);
    }   
  }
public function verifyOtp(Request $request) {
    try{
      $validation = Validator::make($request->all(),[ 
        'otp' => 'required',
        'phone' => 'required'
      ]);
      if($validation->fails()){
        return response()->json([
          'message'=>'Invalid request data',
          'status'=>false,
          "data"=>null
        ]);
      } else {
      	
        $user = DeliveryBoy::where('phone',$request->phone)->first();
        if(!empty($user)){
          if($request->otp == $user->otp){
            $user->isOtpVerified = '1';
            $user->save();
            
            
if($request->flag==1){
	$msg1 ='OTP Verified Successfully Create Your password to continue login';
}
if($request->flag==0){
	$msg1 ='OTP Verified Successfully continue login';
}


               $status = TRUE;
                $code = 201;
               // $message = array('otp' => ' ');
                $msg = array(
                     "status" => $status,
                    'statusCode' => $code,
                    'userID' => @$user->id,
                    'phone' => @$user->phone,
                    'flag'=>$request->flag,
                    "message" => $msg1
                    );
                echo json_encode($msg);
            /*return response()->json([
              'message'=>'OTP Verified Successfully continue login',
              'statusCode'=>201,
              'status'=>true,
              
            ]);*/
          } else {
            return response()->json(
            [
              'message'=>'OTP Incorrect',
              'status'=>false,
              "data"=>null
            ]);
          }
        } else {
          return response()->json(
          [
            'message'=>'Mobile Number Does not Exit',
            'status'=>false,
            'statusCode' => 404,
            "data"=>null
          ]);
        }
      }
    }
    catch(Exception $e){
      return response()->json([
        'message'=>trans('Something went wrong'),
        'status'=>false,
        "data"=>null
      ], 500);
    }
  }	
public function forget(Request $request){
    
    try{
    $validation = Validator::make($request->all(),[ 
        'phone' => 'required'
       ]);
      if($validation->fails()){
        return response()->json([
          'message'=>'Invalid request data',
          'status'=>false,
          "data"=>null
        ]);
      } else {
      $pr = DeliveryBoy::select('id','phone','isOtpVerified')->where('phone',$request->phone)->first();
      if($pr){
      	$string = '0123456789';
    $string_shuffled = str_shuffle($string);
    $otp = substr($string_shuffled, 0,4);
  $messageotp=$otp." your one time password for varification";
 $email_data = ['phone'=>$request->phone,'phone_msg'=>$messageotp,'otp'=>''];
         //  CommonHelper::SendMsg($email_data);
        // DeliveryBoy::where('id',$pr->id)->update(['otp'=>$otp]); 	
      	  $status = TRUE;
                    $code = 201;
                   
                    $datares = array(
                        'status' => $status,
                        'statusCode' => $code,
                        'userID' => @$pr->id,
                        'phone' => @$pr->phone,
                        'message' => 'your register mobile number OTP Please verify mobile number create forget password.',
                            );
                            
                            
	echo json_encode($datares); 
       // if($pr->isOtpVerified=='1'){
         /* return response()->json([
            'message'=>'OTP send to your register mobile',
            'status'=>true,
            'statusCode' => 201,
            'userID' => @$pr->id,
            'phone' => @$pr->phone,
          ]);
        } else {
          return response()->json([
            'message'=>'Your registration is not completed',
            'status'=>false,
            "data"=>null
          ]);
        }*/
      } 
else{
        return response()->json([
        'message'=>'User Not exist',
        'status'=>false,
        "data"=>null
        ]); 
      }
    }
		}
    catch(Exception $e){
      return response()->json([
        'message'=>'Something went wrong',
        'status'=>false,
        "data"=>null
      ], 500);
    }
  }
public function update_password(Request $request){
    try{
      $validation = Validator::make($request->all(),[ 
        //'id' => 'required',
        'phone' => 'required',
        'password' => 'required'
      ]);
      if($validation->fails()){
        return response()->json([
          'message'=>'Invalid request data',
          'status'=>false,
          "data"=>null
        ]);
      } else {     
     
        //$User = DeliveryBoy::findOrFail($request->id);
        $User = DeliveryBoy::where('phone',$request->phone)->first();
         if($User){
        $User->password = bcrypt($request->password);
        if($User->save()){
          //$accessToken = $user->createToken('authToken')->accessToken;
          return response()->json([
            'message'=>'Forget Password Reset Successfully',
            'statusCode' => 201,
            'status'=>true,
          ]);
        } else{
          return response()->json([
            'message'=>'Something went wrong',
            'status'=>false,
            "data"=>null
          ]);
        }
      } else{
        return response()->json([
          'message'=>'Phone not matched',
          'status'=>false,
          "data"=>null
        ]);
      }
    }
		}
    catch (Exception $e) {
      if($request->ajax()) {
        return response()->json([
          'message'=>'Something went wrong',
          'status'=>false,
          "data"=>null
        ], 500);
      }
    }
  }
  public function resend_otp(Request $request) {
    try{
      $validation = Validator::make($request->all(),[ 
        'phone' => 'required'
      ]);
      if($validation->fails()){
        return response()->json([
          'message'=>'Invalid request data',
          'status'=>false,
          'statusCode'=>404
        ]);
      } else {
        $user = DeliveryBoy::where('phone',$request->phone)->first();
        if($user){
        	$string = '0123456789';
    $string_shuffled = str_shuffle($string);
    $otp = substr($string_shuffled, 0,4);
        	$messageotp=$otp." your one time password for varification";
 $email_data = ['phone'=>$request->phone,'phone_msg'=>$messageotp,'otp'=>''];
 
 DeliveryBoy::where('id',$user->id)->update(['otp'=>$otp]);
           CommonHelper::SendMsg($email_data);
        	
          $user->otp = $otp;
          $user->save();
         
          return response()->json([
            'message'=>'Resend OTP send your register mobile number',
            'status'=>true,
            'statusCode'=>201
            
          ]);
        } else {
          return response()->json([
            'message'=>'Somthing went wrong',
            'status'=>false,
            'statusCode'=>401,
            "data"=>null
          ]);
        }
      }
    }
    catch(Exception $e){
      return response()->json([
        'message'=>'Somthing went wrong',
        'status'=>false,
        "data"=>null,
        'statusCode'=>500,
      ], 500);
    }
  }
  public function changepassword(Request $request){
    try{
      $User = DeliveryBoy::find($request->id);

      if(!empty($User)){
        if(Hash::check($request->oldpassword, $User->password))
        {
          $User->password = bcrypt($request->newpassword);
          $User->save();
          if($User) {
            return response()->json([
              'message'=>'Password changed successfully.',
              'statusCode' => 200,
              'status'=>true,
             
            ], 200);
          }else{
            return response()->json([
              'message'=>'Error',
              'statusCode'=> 400,
              'status'=>false,
            ], 400);
          }
        } else {
          return response()->json([
            'message'=>'Old Password do not match',
            'statusCode'=> 404,
            'status'=>false,
            
          ], 500);
        }
      } else {
        return response()->json([
          'message'=>'Error',
          'statusCode'=> 404,
          'status'=>false,
          "data"=>null
        ], 404);
      }
    }
    catch (Exception $e) {
      if($request->ajax()) {
        return response()->json([
          'message'=>'Something went wrong',
          'status'=>false,
          "data"=>null
        ], 500);
      }
    }
  }
  public function profile(Request $request){
    try{

      $user = DeliveryBoy::whereId($request->id)->get(['id',DB::raw("CONCAT('".$this->site_base_path."uploads/profile/',tbl_delivery_boy.image) AS image")
      ,'name','phone','email','addar_no','address','alternative_number','dl_number','dl_expiry','bank_detail','locality'])->first();
     if($user){
	
        return response()->json([
          'message'=>'Profile data returned successfully',
          'status'=>true,
          'code'=>200,
          "data"=>$user
        ]);
      } else {
        return response()->json([
          'message'=>'User Not found',
          'code'=>404,
          'status'=>false,
          "data"=>null
        ]);
      }  
    }
    catch(Exception $e){
      return response()->json([
        'message'=>"Something went wrong",
        'status'=>false,
        "data"=>null
      ], 500);
    }
  }
  public function updateProfile(Request $request){
    try{
    $user = Deliveryboy::find($request->id);
    if($user){
    Deliveryboy::whereId($request->id)->update($request->all());
   
    $folder_path = '/uploads/profile/';
    $image = '';
    if($request->hasFile('image')) {
      
        $photo = $request->file('image');
      if($photo){
        $image = time().'-'.$photo->getClientOriginalName(); 
        $destinationPath = public_path($folder_path);
        $photo->move($destinationPath, $image);
      }
      $data=[
        'image' => $image
        ];
	 
$res=DeliveryBoy::where('id', '=',$request->id)
   ->update(
  $data
   );   
  } 
    return response()->json([
            'message'=>'Profile updated successfully.',
            'status'=>true,
            'code'=>201,
            //"data"=>$this->getProfileData($user->id)
          ]);

        
      }else{
        return response()->json([
          'message'=>'User not exits.',
          'status'=>false,
          'code'=>404,
          //"data"=>$this->getProfileData($user->id)
        ]);
      }
      }
        catch(Exception $e){
          return response()->json([
            'message'=>"Something went wrong",
            'status'=>false,
            "data"=>null
          ], 500);
        }

  }
  
public function orderlist(Request $request){
	
	  $id = $request->id;
	
		$query=OrdersDetail::select(
		     'orders.delivery_time','orders.delivery_day','orders.delivery_date',
                            'orders.order_no',
                             'orders.payment_mode',
                            'orders.order_date as orderdate',
                            'order_details.*',
                            'order_details.product_price as grand_total',
                           
                'order_details.product_price as details_price',
                'order_details.product_qty as  details_qty',
                'order_details.order_shipping_charges as  details_shipping_charges',
                'order_details.order_cod_charges as  details_cod_charges',
                'order_details.order_coupon_amount as  details_cpn_amt',
                'order_details.order_wallet_amount as  details_wlt_amt',

        'order_details.id as order_details_id',
        'order_details.suborder_no as suborder_no',
        'order_details.product_qty as qty',
         'products.default_image',
                'customers.name as cust_name',
           
                'orders_shipping.order_shipping_name as customer_name',
                'orders_shipping.order_shipping_address as customer_add',
                'orders_shipping.order_shipping_address1 as customer_add1',
                'orders_shipping.order_shipping_address2 as customer_add2',
                'orders_shipping.order_shipping_city as customer_city',
                'orders_shipping.order_shipping_state as customer_state',
                'orders_shipping.order_shipping_country as customer_country',
                'orders_shipping.order_shipping_zip as customer_zip',
                'orders_shipping.order_shipping_phone as customer_phone',
                'orders_shipping.order_shipping_email as customer_email'
               
						)
                ->join('orders', 'orders.id', '=', 'order_details.order_id')
                 ->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id')
                ->join('products', 'products.id', '=', 'order_details.product_id')
                ->join('product_categories','product_categories.product_id','=','products.id')
                ->join('categories','categories.id','=','product_categories.cat_id')
                ->join('customers','orders.customer_id','=','customers.id');

                if(@$request->search!=''){
                  $query=$query->where('order_details.order_status','=',$request->search);
                }
               
                //->where('order_details.order_status',0)
                $query=$query->where('order_details.deliveryID',$id)->orderBy('order_details.id','desc')->groupBy('order_details.id')->get();
     if(sizeof($query)){
     	foreach ($query as $row){
     		$str = $row->delivery_date;
     		$str = explode("_",trim($str));
     		
			$queryresult[] = array(
			//"order_time"=>$row->delivery_date,
			"delivery_day"=>@$str[0],
			"delivery_date"=>(@$str[1])?$str[1]:$str[0],
			"order_id"=>$row->suborder_no,
			"delivery_time"=>$row->delivery_time,
			"shipping_name"=>$row->customer_name,
			"shipping_mobile"=>$row->customer_phone,
			"shipping_address"=>$row->customer_add."".$row->customer_add1."".$row->customer_add2."".$row->customer_state."".$row->customer_city."".$row->customer_zip,
			"payment_mode"=>$row->payment_mode,
			"order_status"=>$row->order_status,
			"order_date"=>date('d M,Y H:i:s',strtotime($row->orderdate)),
			"delivered_time"=>($row->order_detail_delivered_date)?date('d M,Y H:i:s',strtotime($row->order_detail_delivered_date)):'',
			//"QRimg"=>$QRimg
			
			);
		}
	 	$status = TRUE;
                    $statusCode = 201;
                     $datares = array(
                        'status' => $status,
                        'statusCode' => $statusCode,
                        'message' => 'Process Order List',
                        'data'=>$queryresult,
                        
                    );
	 }else{
	 	$status = FALSE;
                    $statusCode = 404;
                     $datares = array(
                        'status' => $status,
                        'statusCode' => $statusCode,
                        'data'=>[],
                        'message' => 'No record found',
                    );
	 }
                    
                            
                            
echo json_encode($datares);    
    
}
public function orderDetails(Request $request){
	 
	$total='0';$discount='0';$dlivery='0';
	$cod_charges='0';$order_wallet='0';
	$slot_price='0';
	$id = $request->order_id;
	
	$query =Orders::select(
		     'orders.delivery_time','orders.delivery_day','orders.delivery_date',
						'orders.order_no',
						'orders.order_date',
						'orders.updated_at',
						'orders.id',
						'orders.deduct_reward_points',
						'orders.discount_amount',
						'orders.payment_mode',
						'orders.grand_total',
						'orders.coupon_percent',
                        'orders.coupon_code',
                        'orders.coupon_amount',
                        'orders_shipping.order_shipping_address',
                        'orders_shipping.order_shipping_address1',
                        'orders_shipping.order_shipping_address2',
						'orders_shipping.order_shipping_name',
						'orders_shipping.order_shipping_phone',
						'orders_shipping.order_shipping_email',
						'orders_shipping.order_shipping_zip',
						'order_details.suborder_no',
						'order_details.product_id',
						'order_details.product_name',
						'order_details.product_qty',
						'order_details.product_price',
						'order_details.size',
						'order_details.color',
						'order_details.w_size',
						'order_details.order_coupon_amount',
						'order_details.order_wallet_amount',
						'order_details.order_cod_charges',
						'order_details.order_shipping_charges',
						'order_details.slot_price',
						'order_details.order_status',
						'order_details.order_id'
						
						)
					
					
					
					
					->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id')
					->join('order_details', 'orders.id', '=', 'order_details.order_id')
					->where('order_details.suborder_no',$id)
					->get();
 	foreach($query as $row){
          $total+=$row->product_price*$row->product_qty; 
           $size = ($row->size)?$row->size:'';
            $color = ($row->color)?$row->color:'';
          
          $prd_info=DB::table('products')->where('id',$row->product_id)->first();
          $prdimage=Config::get('constants.Url.public_url');
  
  $prdimage.=Config::get('constants.uploads.product_images').'/'.$prd_info->default_image;
  
          $orderdetails[]= array(
                       'product_image'=>$prdimage,
                       'product_name'=>$row->product_name,
                       'product_size'=>$size,
                       'product_color'=>$color,
                       'product_qty'=>$row->product_qty,
                       'order_price'=>$row->product_price,
                       'order_details_id'=>$row->id
                        );
          if(@$row->coupon_percent){
		$discount = ($total*$row->coupon_percent/100);
	}
 	if(@$row->order_shipping_address1){
		$addres2=", ".$row->order_shipping_address1.", ".$row->order_shipping_address2;
	}
 	$address = $row->order_shipping_address.$addres2;
 	
 	if(@$row->order_shipping_charges){
		$dlivery = $row->order_shipping_charges;
	}
	if(@$row->order_cod_charges){
		$cod_charges = $row->order_cod_charges;
	}
	if(@$row->order_wallet_amount){
		$order_wallet = $row->order_wallet_amount;
	}
	if(@$row->slot_price){
		$slot_price = $row->slot_price;
	}
	
	
	$QRimg='';
	if($row->payment_mode==0){
     			
     			//$se = $this->db->query('select QR_image from tbl_settings')->row();
				//$QRimg = base_url('uploads/'.$se->QR_image);
			}
			
			
           }
 	
 	$orderinfo=DB::table('orders')->where('id',$row->order_id)->first(); 
			
			$grand_total=($total + $dlivery + $cod_charges + $slot_price - $discount - $order_wallet);
	                  $status = TRUE;
                      $statusCode = 201;
                       
                     $datares = array(
                        'status' => $status,
                        'statusCode' => $statusCode,
                        'message' => 'Order Details',
                        'totalitems'=>count($query),
                       // 'QRimg'=>$QRimg,
                        
                        'subtotal_amount'=>$total,
                        'delivery_charges'=>$dlivery,
                        'cod_charges'=>$cod_charges,
                        'coupon_discount'=>$discount,
                        'order_wallet'=>$order_wallet,
                        'slot_charge'=>$slot_price,
                        'grand_total'=>$grand_total,
                        "order_date"=>date('d M,Y H:i:s',strtotime($orderinfo->order_date)),
                        'data'=>$orderdetails,
                        'billinginfo'=>array(
                        "order_no"=>$id,
                        'customer_name'=>(@$row->order_shipping_name)?$row->order_shipping_name:'',
                        'customer_mobile'=>(@$row->order_shipping_phone)?$row->order_shipping_phone:'',
                        'address'=>@$address,
                        'order_status'=>(@$row->order_status)?$row->order_status:'',
                        'payment_mode'=>(@$orderinfo->payment_mode)?$orderinfo->payment_mode:'0',
                        "delivered_time"=>($row->order_detail_delivered_date)?date('d M,Y H:i:s',strtotime($row->order_detail_delivered_date)):'',
                        ),
                       
                    );
                    
	echo json_encode($datares); 
}

public function address(Request $request){
     	try{
     $id = $request->order_id;
    $row=DB::table('order_details')->where('suborder_no',$id)->first();
    $orderinfo=DB::table('orders')->where('id',$row->order_id)->first();
    $prdinfo=DB::table('products')->where('id',$row->product_id)->first();
    $shipinfo=DB::table('orders_shipping')->where('order_id',$orderinfo->id)->first();
    
     $vdr=new Vendor();
    $vdr_data=$vdr->getVendorDetails($prdinfo->vendor_id);
   
 	$address =$vdr_data['company_address']." ".$vdr_data['company_state']." ".$vdr_data['company_city']." ".$vdr_data['company_pincode'];
 	
 	if(@$shipinfo->order_shipping_address1){
		$addres2=", ".$shipinfo->order_shipping_address1.", ".$shipinfo->order_shipping_address2." ".$shipinfo->order_shipping_state." ".$shipinfo->order_shipping_city." ".$shipinfo->order_shipping_zip;
	}
 	$address1 = $shipinfo->order_shipping_address.$addres2;
 	 return response()->json([
              'message'=>'Address List',
              'status'=>true,
              'statusCode' => 201,
              'pickupaddress'=>array(
              'name'=>@$vdr_data['public_name'],
              'mobile'=>@$vdr_data['phone'],
              'address'=>@$address
              ),
             'deliveryaddress'=>array(
        'customer_name'=>(@$shipinfo->order_shipping_name)?$shipinfo->order_shipping_name:'',
        'customer_mobile'=>(@$shipinfo->order_shipping_phone)?$shipinfo->order_shipping_phone:'',
        'address'=>@$address1,
            )
            ]);
            
 	 }catch(Exception $e){
      return response()->json([
        "statusCode" => 500,
        'message'=>'Something went wrong.',
        'status'=>false,
        "data"=>null
      ], 500);
    } 
   
     
 }
 public function updateOrderImageUpload(Request $request){
     	
    $id = $request->order_id;
    $row=DB::table('order_details')->where('suborder_no',$id)->first();
    $orderinfo=DB::table('orders')->where('id',$row->order_id)->first();
 	
 	//print_r($row);die;
   /*if($orderinfo->payment_mode==0){
   	
     $folder_path = '/uploads/recipt/';
                 $image = '';
                 if($request->hasFile('userImage')) {
                   
                     $photo = $request->file('userImage');
                   if($photo){
                     $image = time().'-'.$photo->getClientOriginalName(); 
                     $destinationPath = public_path($folder_path);
                     $photo->move($destinationPath, $image);
                   }
		    
		   $data=[
		'payment_recipt' => $image,
		'order_status'=>3,
		'order_detail_delivered_date'=>date('Y-m-d H:i:s')
		//"package_description"=>$descrption
		];	 
		
		$res=OrdersDetail::where('suborder_no', '=',$id)->update($data);    
		 $status = TRUE;
                    $statusCode = 201;
                     $datares = array(
                        'status' => $status,
                        'statusCode' => $statusCode,
                        'order_id'=>$id,
                        'order_status'=>3,
                        'message' => 'Status Update Successfully',
                    );
	}

    else{
		 $status = FALSE;
                    $statusCode = 404;
                     $datares = array(
                        'status' => $status,
                        'statusCode' => $statusCode,
                        'order_id'=>$id,
                        'order_status'=>0,
                        'message' => 'Payment receipt is required',
                    );
	}	
   }
   
   else{
   	 	    */
    $data=[
		"order_status"=>3,
		"order_detail_delivered_date"=>date('d-m-Y h:i:s')
		//"package_description"=>$descrption
		];	
		//$row=DB::table('order_details')->where('suborder_no',$id)->first(); 
		  //$this->generateMailforCancelAndReturnOrder($row->id,0);
		  CommonHelper::generateMailforOrderSts($row->id,2);  
		$res=OrdersDetail::where('suborder_no', '=',$id)->update($data);    
                    $status = TRUE;
                    $statusCode = 201;
                    $datares = array(
                        'status' => $status,
                        'statusCode' => $statusCode,
                        'order_id'=>$id,
                        'order_status'=>3,
                        'message' => 'Status Update Successfully',
                    );
  // }
				
 	echo json_encode($datares); 
     
 }
 public function updateOrder_status(Request $request){
     $id = $request->order_id;
    $row=DB::table('order_details')->where('suborder_no',$id)->first();
    $orderinfo=DB::table('orders')->where('order_no',$id)->first();
    $canceled_orders=array();
    $single_order=array('sub_order_id'=>$row->id,'reason'=>$request->reason,'type'=>4);
	 DB::table('order_details')->where('id',$row->id)->update(['order_status'=>4]);
			 
			    $this->generateMailforCancelAndReturnOrder($row->id,0);
			  array_push($canceled_orders,$single_order);
				
				$deduct_points=DB::table('order_details')->where('id',$row->id)->first();
				
					CommonHelper::orderDetailsLog($deduct_points,3);
					
					Products::increaseProductQty($deduct_points->product_id,$deduct_points->size_id,$deduct_points->color_id,$deduct_points->product_qty);
				
				$total_deduct_points=$deduct_points->order_reward_points;
				$order_id=$deduct_points->order_id;
			
			if($total_deduct_points!=0){
				$cust_id=$orderinfo->customer_id;
				$wallet=array(
							'fld_customer_id'=>$cust_id,
							'fld_order_id'=>$order_id,
							'fld_order_detail_id'=>0,
							'fld_reward_narration'=>'Cancelled',
							'fld_reward_deduct_points'=>$total_deduct_points
						);
				
				DB::table('tbl_wallet_history')->insert($wallet);
				
				$master_points=DB::table('customers')->where('id',$cust_id)->first();
				$amt=$master_points->total_reward_points-$total_deduct_points;
				
				DB::table('customers')->where('id',$cust_id)->update(['total_reward_points'=>$amt]);
			}
               $res=DB::table('cancel_return_refund_order')->insert($canceled_orders);
               if($res){
                        $total_order=DB::table('order_details')->where('order_id',$order_id)->count();
                        $cancel_order=DB::table('order_details')->where('order_id',$order_id)->where('order_status',4)->count();
                        if($total_order==$cancel_order){
                           DB::table('orders')->where('id',$order_id)->update(['order_status'=>4]); 
                        }
                   
	}
            
                     $status = TRUE;
                     $statusCode = 201;
                     $datares = array(
                        'status' => $status,
                        'statusCode' => $statusCode,
                        'order_id'=>$id,
                        'order_status'=>4,
                        'message' => 'Status Update Successfully',
                        );
   
 	echo json_encode($datares); 
 }
  public function getReason(Request $request) {
        try{
      $User = DB::table('order_cancel_reason')->where('reason_type',0)->get();

      if(!empty($User)){
        return response()->json([
          'message'=>'Reason List',
          'statusCode'=> 201,
          'status'=>false,
          "data"=>$User
        ], 404);
      } else {
        return response()->json([
          'message'=>'No Reason Found',
          'statusCode'=> 404,
          'status'=>false,
          "data"=>null
        ], 404);
      }
    }
    catch (Exception $e) {
      if($request->ajax()) {
        return response()->json([
          'message'=>'Something went wrong',
          'status'=>false,
          "data"=>null
        ], 500);
      }
    }
           
       }
 public function generateMailforCancelAndReturnOrder($order_id,$type=0){
        //   type => 0 for cancel and 1 =>return 
        $opeation_pr='Return';
        if($type==0){
            $opeation_pr='Cancel';
        }
                $master_orders=OrdersDetail::where('id',$order_id)->first();
             
                $master_order=Orders::where('id',$master_orders->order_id)->first();
                $product_data = DB::table('products')->where('id',$master_orders->product_id)->first();
               
                $customer_data=Customer::where('id',$master_order->customer_id)->first();
                $shipping_data=OrdersShipping::where('order_id',$master_orders->order_id)->first();

				if($master_order->payment_mode==0) {
					
					$msg=view("message_template.cod_order_cancelled",
                        array(
                        'data'=>array(
                        'name'=>$customer_data->name,
                        'suborder_no'=>$master_orders->suborder_no
                        )
                        ) )->render();					
					
					}
				else {
					$msg=view("message_template.online_order_cancelled",
                        array(
                        'data'=>array(
                        'name'=>$customer_data->name,
                        'suborder_no'=>$master_orders->suborder_no
                        )
                        ) )->render();					
				}

                //$msg='Dear '.$customer_data->name.' '.$customer_data->last_name.'  your request for '.$opeation_pr.' order('.$master_orders->suborder_no.') is proceesing.';
                
                    
                    $mode= ($master_order->payment_mode==0)?"'COD'":"'Paid'";
                    
                    $email_msg=view("emails_template.order_cancel",
                        array(
                        'data'=>array(
                                        'mode'=>$mode,
                                        'operation_process'=>$opeation_pr,
                                        'customer_fname'=>$customer_data->name,
                                        'customer_lname'=>$customer_data->last_name,
                                        'suborder_no'=>$master_orders->suborder_no,
                                        'order_date'=>$master_order->order_date,
                                        'shipping_data'=>array(
                                                                  'shipping_name' => $shipping_data->order_shipping_name,
                                                                  'shipping_phone' =>  $shipping_data->order_shipping_phone,
                                                                  'shipping_email' => $shipping_data->order_shipping_email,
                                                                  'shipping_address' => $shipping_data->order_shipping_address,
                                                                  'shipping_address1' => $shipping_data->order_shipping_address1,
                                                                  'shipping_address2' => $shipping_data->order_shipping_address2,
                                                                  'shipping_city' =>  $shipping_data->order_shipping_city,
                                                                  'shipping_state' => $shipping_data->order_shipping_state,
                                                                  'shipping_zip' => $shipping_data->order_shipping_zip
                                                                ),
                                        'product_details' => array(
                                                                    'product_name' => $master_orders->product_name,
                                                                    'product_image' => $product_data->default_image,
                                                                    'product_size' => $master_orders->size,
                                                                    'product_color' => $master_orders->color,
                                                                    'product_qty' => $master_orders->product_qty,
                                                                    'product_price'=> $master_orders->product_price,
                                                                    
                                                                    'order_shipping_charges'=> $master_orders->order_shipping_charges,
                                                                    'order_cod_charges'=> $master_orders->order_cod_charges,
                                                                    'order_coupon_amount'=> $master_orders->order_coupon_amount,
                                                                    'order_wallet_amount'=> $master_orders->order_wallet_amount,
                                                                    )
                                     )
                                     
                        ) )->render();
                        
                     
            
            
            $email_data = [
                            'to'=>$customer_data->email,
                            'subject'=>$opeation_pr.' Order',
                            "body"=>$email_msg,
                            'phone'=>$customer_data->phone,
                            'phone_msg'=>$msg
                         ];
            CommonHelper::SendmailCustom($email_data);
           CommonHelper::SendMsg($email_data);
      }
}