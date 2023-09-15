<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Vendor;
use App\Products;
use App\Category;
use App\Helpers\MsgHelper;
use Illuminate\Support\Facades\Hash;
use Redirect;
use Validator;
use DB;
use Config;
use Auth;
use App\CheckoutShipping;
use App\Customer;
use Carbon\Carbon;
use App\Helpers\CommonHelper;
use App\Helpers\FirebaseHelper;
use App\Helpers\PHPMailer;
use App\Firebasemessage;
use App\Orders;
class TestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function testsms(){
        $mobile = '6306391956';
        $msg = 'Hello, Welcome to KEFIH! Your OTP code is 5456. - Kefih E-Commerce Private Limited';
        CommonHelper::msgCurlTest($mobile,$msg);
    }

public static function getAllParent($arr,$id){
    if($id==1){
        return $arr;
    } else{
        $cats=Category::select('name','parent_id')
		                ->where('id',$id)
		                ->where('isdeleted',0)
		                ->where('status',1)
		                ->first();
		                array_push($arr,$cats->name);
		  self::getAllParent($arr,$cats->parent_id);
    }
}
public function catsss(){
    echo "<pre>";
        $cats=array();
        $catsss=array();
   $categories = Category::
                   with('AllparentCats')
                ->where('isdeleted',0)
                ->where('id', 278)
                ->where('status',1)
        ->first();
        if($categories){
             array_push($catsss,array('id'=>$categories->id,'name'=>$categories->name));
           $cats=Category::list_categories($categories->AllparentCats);
          }

   $cats1=Category::array_flatten($cats,array());

   foreach($cats1 as $key=>$c){
      if( ($key % 2)==0){
          array_push($catsss,array('id'=>$c,'name'=>$cats1[$key+1]));
      }
   }
   
   unset ($catsss[count($catsss)-1]);
   $final_cats=array_reverse($catsss);
   $last_index= count($final_cats)-1;
echo $last_index;
   print_r($final_cats);
}
                public function customer_address(){
                    $minutes=(86400000 * 30);
                   $id=1;
          
$cookie_name = "pincode";
$cookie_value = "201304";
setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");

$cookie_name1 = "pincode_error";
$cookie_value2 = "1";
setcookie($cookie_name1, $cookie_value2, time() + (86400 * 30), "/");

           die();
            
            $res= CheckoutShipping::where('shipping_address_default',1)->where('customer_id',178)->first();
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
                     if (array_key_exists("delivery_details",$output))
                    {
setcookie('pincode',  $inputs['pincode'], time() + ($minutes));
setcookie('pincode_error', 0, time() + ($minutes));
                     } else{
             setcookie('pincode',  $inputs['pincode'], time() + ($minutes));
             setcookie('pincode_error', 1, time() + ($minutes));
                        
                    }
                
            }
             
            setcookie('shipping_address_id', $id, time() + ($minutes));
                }
 public function test(){
            $order_id=838;
           
                $productsar=DB::table('order_details')
                                 ->select('order_details.*','brands.name as brandName')
                                ->join('products','order_details.product_id','products.id')
                                ->join('brands','brands.id','products.product_brand')
                                ->where('order_details.order_id',$order_id)->get();
                $orderData=DB::table('orders')->where('id',$order_id)->first();
             

            $products=array();
            foreach($productsar as $key=>$productData){
                
                $cat_name=DB::table('categories')
                                    ->join('product_categories','product_categories.cat_id','categories.id')
                                    ->where('product_categories.product_id',$productData->product_id)
                                    ->first();
                                    
                    array_push($products,array(
                        'id' => $productData->product_id,
                        'name' => $productData->product_name,
                        'price' =>$productData->product_price,
                        'brand' =>@$productData->brandName,
                        'category' => @$cat->name,
                        'variant' =>$productData->color,
                        'dimension1' => 'M',
                        'position' =>$key,
                        'quantity' =>$productData->product_qty
                    ));
                
            }
     
                 $arr=[
                'event' => 'transaction',
                'ecommerce' => [
                'purchase' => [
                  'actionField' => [
                    'id' => '9d528a3c-a5eb-486d-9dcd-fbdbc9ea4db7',
                    'affiliation' => 'Online Store',
                    'revenue' =>$orderData->grand_total,
                    'tax' => 5,
                    'shipping' =>$orderData->total_shipping_charges,
                  ],
                    'products' => $products,
                ],
                ],
                ];
              
                print_r($arr);
               
                die();
     
//      	$custmor_array=array(
// 					"phone"=>9389649459,
// 					"userId"=>2
// 					);
// 		       CommonHelper::generate($custmor_array);
// echo "hiii";
// die();

                
                   $method='POST';
        $url="users/%u/search/couriers/shipment/track/3342010282586";
        $auth_response=CommonHelper::logisticsAuth($method,$url);
                
        $post_json_request = array(
                "pickup_pincode"=>'281004', // got
                "delivery_pincode"=>'281004', // got
                "invoice_value"=>'248', // got
                "payment_mode"=>"cod", 
                "insurance"=>false,
                "number_of_package"=>1,
                "product_type"=>"Parcel",
                "delivery_mode"=>"Both",
                "package_details"=>array(
                                    array(
                                    "package_content"=>'ffdfdf', // got
                                    "no_of_items"=>1, // got
                                    "invoice_value"=>248, // got
                                    "package_dimension"=>array(
                                            "weight"=>45,
                                            "height"=>23,
                                            "length"=>45,
                                            "width"=>56
                                                     )
                                        )
                              )
                );
              
        $res=CommonHelper::logisticsResponse($method,$auth_response,$post_json_request);
          $output = (array)json_decode($res);
                echo "<pre>";
                print_r($output);
                
                echo "hii";
                die();
     $coupon_code='44a429206502512a14kb';
      if($coupon_code!=''){
		         $coupon_details=DB::table('coupon_details')
                      ->select('coupons.coupon_type')
                     ->join('coupons','coupons.id','coupon_details.coupon_id')
                    ->where('coupon_details.coupon_code',$coupon_code)
                    ->first();
                       if($coupon_details){
                            if(
                $coupon_details->coupon_type==4  ||
                $coupon_details->coupon_type==5  ||
                $coupon_details->coupon_type==6  ||
                $coupon_details->coupon_type==7  
            ){
                echo "<pre>";
                print_r($coupon_details);
            } 
                       }
           
		    }
     echo "hii";
     die();
     
            $data = [
                 'phone'=>'9389649459',
                 'phone_msg'=>'Test msg triggers from Phaukat '.date('Y-m-d H:i:s')
             ];
     
          CommonHelper::SendMsg($data);
         
  
    //  $reg_message=Firebasemessage::select('message')->where('id',1)->first();
    //  echo "<pre>";
    //  print_r($reg_message);
    //  die();
        //   $dt=CommonHelper::registrationDone();
        //  echo $dt;
         
     }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     public function cart_product_delete(){
           $cookie_data=app(\App\Http\Controllers\CookieController::class)->getcustomCartCookie(); 
	  
	  
	  
	  
	  if($cookie_data!=''){
	     
	    
	 	$cookie_data=json_decode($cookie_data);
	 	
	 echo "<pre>";
	 	
	     
            $whole_products=array();
            
            $ex=0;
            $k=0;

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
         print_r($cookie_data);
         die();       
               
	 }
	 

      
     }
     public function test2()
     {
         
         
         $customer_data=Customer::where('id',178)->where('device_token','!=',null)->first();
            if($customer_data){
            FirebaseHelper::registrationDone($customer_data); 
	    }
	
     }
	 public function test3()
     {
         
         
         $customer_data=Customer::where('id',259)->where('device_token','!=',null)->first();
            if($customer_data){
                $order_data=Orders::where('id',1)->first();
           			FirebaseHelper::orderPlaced($customer_data,$order_data); 
	}
     }
      public function test4()
     {
         
         
         $customer_data=Customer::where('id',259)->where('device_token','!=',null)->first();
            if($customer_data){
                $order_data=Orders::where('id',1)->first();
           			FirebaseHelper::Coupons($customer_data,$order_data); 
	}
     }
    
     public function testddd()
    {
        $msg = '<tr>
                             <td style="padding:5px 10px;">
                                	<p>Dear <strong> Yogendra,</strong></p>
                                    <p>You have successfully registered yourself on <a href="">18up.in</a></p>
                    
                             </td>
                        </tr>
                       ';
            $data = [
                'to'=>'yogendra@b2cmarketing.in',
                'subject'=>'Query',
                 "body"=>view("emails.email",
                     array(
				    'data'=>array(
				        'message'=>$msg
				        )
				     ) )->render(),
				     'phone'=>'7017734526',
				     'phone_msg'=>'You have successfully registered yourself on 18UP.in'
                 ];
        CommonHelper::SendmailCustom($data);
          CommonHelper::SendMsg($data);
     
//         $mail = new PHPMailer();

// $mail->IsSMTP();
// $mail->Host = "mail.aptechbangalore.com";  /*SMTP server*/

// $mail->SMTPAuth = true;
// //$mail->SMTPSecure = "ssl";
// $mail->Port = 587;
// $mail->Username = "mailto@aptechbangalore.com";  /*Username*/
// $mail->Password = "47#z2NqYMZX";    /**Password**/

// $mail->From = "mailto@aptechbangalore.com";    /*From address required*/
// $mail->FromName = "Test ";
// $mail->AddAddress("yogendra@b2cmarketing.in");
// //$mail->AddReplyTo("mail@mail.com");

// $mail->IsHTML(true);

// $mail->Subject = "Test message from server fdgrhthtrhythjy";
// $mail->Body = "Test Mail<b>in bold!</b>";
// //$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

// $mail->Send();
    }
   
}
