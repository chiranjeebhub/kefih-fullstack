<?php

namespace App\Helpers;
use Illuminate\Http\Request;
use DB;

class FbConversionHelper
{
    

    public static function fbCoversion($orderID,$request){
        $PIXEL_ID = "1207340983414571";
        $ACCESS_TOKEN = "EAALc6cU3Cn4BAPflnKNZCa0kx8AKGYzvmiaCRFwmW5eam4YMaxYRg6XfRnaRQuNCmMw1o0dCPZCXWmaGRNFPty55IMwZByUf3XYjOm8HHmW2UhJxIsvr31MXeedZBatuBBAwc0w7tHGGdrkEdRhwmhZCf1zF37vudLkLnZAU9T4rn1k9QsgZAIcWnhavpdHdS8ZD";
   
        if(!empty($orderID)){
      
        $productdata=DB::table('order_details')
        ->select('order_details.*')
                ->join('products','order_details.product_id','products.id')
                ->where('order_details.order_id',$orderID)->get();
        $orderData=DB::table('orders')->where('id',$orderID)->first();
        
        $OrderedProduct = array();

   
        //data mapping for fb-conversion api 

        foreach($productdata as $row){
            array_push($OrderedProduct, [
                "id"=> $row->product_name,
                "quantity"=> $row->product_qty,
                "delivery_category"=>""
            ]);
        }

        $purchaseEventData = array();
        $purchaseEventData['event_name'] = "Purchase";
        $purchaseEventData['event_time'] = time();
        $purchaseEventData['user_data'] = array(
            "client_ip_address"=> $request->ip(),
           "client_user_agent"=> $request->server('HTTP_USER_AGENT'),
        ); 
        $purchaseEventData['custom_data'] = json_encode(array(
            "currency"=> "INR",
            "value" => $orderData->grand_total,
            "contents" => $OrderedProduct
        )); 
        $purchaseEventData['event_source_url'] = route('thankyou');
        $purchaseEventData['action_source'] = "website";
    
         
        $json = ['data'=>  [json_encode($purchaseEventData)],
            ];
        $url = 'https://graph.facebook.com/v17.0/'.$PIXEL_ID.'/events?access_token='.$ACCESS_TOKEN;
        $ch = curl_init($url);
         
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($json));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            // 'Content-Length: ' . strlen($json)
        ));
         
        $response = curl_exec($ch);
        file_put_contents('fbconversionData.txt',json_encode($purchaseEventData));

        if(curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
            file_put_contents('fbconversionError.txt',json_encode(curl_error($ch)));

        } else {
            file_put_contents('fbconversionRes.txt',json_encode($response));
            // echo $response;
        }
        curl_close($ch);
    }

    }

}