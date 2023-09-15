<?php

namespace App\Helpers;
use Illuminate\Http\Request;
use Config;
use App\Firebasemessage;
use App\Orders;
use DB;
use App\Customer;
class FirebaseHelper
{
    public static function registrationDone($user_data){
       

            $c_name=$user_data->name;
            $c_device_token=$user_data->device_token;
           
            
       
         $reg_message=Firebasemessage::select('message')->where('id',1)->first();
         $final_message=str_replace('$name',$c_name,$reg_message->message);
        $data=array(
             "data"=>array(
                        "action_type"=>"registration",
                        "action_url"=>"homescrren",
                        "title"=>'Registration',
                        'is_background'=>true,
                        "body"=>$final_message,
                        "message"=>$final_message,
                        "image"=>"https://www.phaukat.com/public/fronted/images/logo.jpg",
                        "timestamp"=>time()
                    
                   )
            );
      
        //self::sendNotification(1,$data,$tokens);
        self::sendNotification(0,$data,$c_device_token);
        
    }
    
    public static function orderPlaced($user_data,$order_data){
            $c_name=$user_data->name;
            $c_device_token=$user_data->device_token;
       
         $reg_message=Firebasemessage::select('message')->where('id',2)->first();
         $final_message=str_replace('$name',$c_name,$reg_message->message);
           $final_message=str_replace('$orderID',$order_data->order_no,$final_message);
        
        $data=array(
             "data"=>array(
                        "action_type"=>"order",
                        "action_url"=>"orderlist",
                        "image"=>"https://www.phaukat.com/public/fronted/images/logo.jpg",
                        "title"=>'Order Placed',
                        "body"=>$final_message,
                        "message"=>$final_message,
                        "timestamp"=>time()
                   )
            );
      
        //self::sendNotification(1,$data,$tokens);
        self::sendNotification(0,$data,$c_device_token);
        
    }
    
    public static function Coupons($user_data,$order_data){
            $c_name=$user_data->name;
            $c_device_token=$user_data->device_token;
       
         $reg_message=Firebasemessage::select('message')->where('id',2)->first();
         $final_message=str_replace('$name',$c_name,$reg_message->message);
           $final_message=str_replace('$orderID',$order_data->order_no,$final_message);
        
        $data=array(
             "data"=>array(
                    "action_type"=>"coupon",
                    "action_url"=>"couponlist",
                    "title"=>'Coupon Added',
                    "body"=>"If you use this site regularly and would like to help keep the site on the Internet, please consider donating a small sum to help pay for the hosting and bandwidth bill ",
                    "message"=>'If you use this site regularly and would like to help keep the site on the Internet, please consider donating a small sum to help pay for the hosting and bandwidth bill ',
                    "image"=>"https://www.phaukat.com/public/fronted/images/logo.jpg",
                    "timestamp"=>time()
                   )
            );
      
        //self::sendNotification(1,$data,$tokens);
        self::sendNotification(0,$data,$c_device_token);
        
    }
    
    
    public static function CustomNotification($notification_data){
              
	      $data=array(
             "data"=>array(
                    "action_type"=>$notification_data['payload_type'],
                    "action_url"=>$notification_data['payload_url'],
                    "title"=>$notification_data['title'],
                    "body"=>$notification_data['body'],
                    "message"=>$notification_data['body'],
                    "image"=>$notification_data['image'],
                    "timestamp"=>time()
                   )
            );
            
            $tokens=array(
                
            );
            $customers_data=Customer::select('fcm_token')->where('fcm_token','!=','')->get();
            foreach($customers_data as $customer){
              array_push($tokens,$customer->fcm_token);
            }
            if(sizeof($tokens)>0){
                 self::sendNotification(1,$data,$tokens);              
            }
            
                
           
        
    }
    
    public static function sendNotification($method,$notification_data,$tokens){
        
                    $notification_data['data']['image']=$notification_data['data']['image'];
                    $notification_data['notification']['vibrate']=7;
                    $notification_data['notification']['sound']=0;
                    $notification_data['notification']['title']=$notification_data['data']['title'];
                    $notification_data['notification']['body']=$notification_data['data']['body'];
                    $notification_data['notification']['image']=$notification_data['data']['image'];
        switch($method){
            case 0:  /// target single device
                    $notification_data["to"]=$tokens;
                       self::runCurl($notification_data);
            break;
           
           case 1:  /// target mulitple device
           
                     $notification_data["registration_ids"]=$tokens;
                      self::runCurl($notification_data);
           break;
                
        }
    }
    
     public static function runCurl($payload){
       
    
         try {
                $developer_configuration=DB::table('tbl_developer_config')->where('id',1)->first();
                 if($developer_configuration){
                    $ch = curl_init();
                    curl_setopt( $ch,CURLOPT_URL, $developer_configuration->firebase_url);     
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type:application/json',"authorization:key=".$developer_configuration->firebase_auth_key));
                    curl_setopt( $ch,CURLOPT_POST, true );
                    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                    curl_setopt( $ch,CURLOPT_POSTFIELDS, 
                    json_encode($payload));
                    $result = curl_exec($ch );
                    file_put_contents("FCMnotificationStatus.txt", json_encode($result));
                    return $result; 
                 }
          }
          
        catch(Exception $e) {
            return json_encode(array("error"=>"Something went wrong"));
        }

    }
    
  
   
	
	
}
