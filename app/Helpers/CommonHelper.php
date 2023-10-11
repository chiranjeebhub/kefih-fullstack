<?php

namespace App\Helpers;
use Illuminate\Http\Request;
use Redirect;
use Validator;
use DB;
use App\Category;
use App\Brands;
use App\ProductCategories;
use App\Colors;
use App\Products;
use App\ProductImages;
use App\OrdersDetail;
use App\Orders;
use App\OrdersShipping;
use App\Sizes;
use App\Vendor;
use App\Customer;
use Config;
use Mail;
use App\Mail\EmailMessage;
use Carbon\Carbon;
use App\Helpers\PHPMailer;
use \stdClass;
use URL;
class CommonHelper
{


    public static function GetAdminProductDetails($productID,$colorID,$sizeID){
        $productData = Products::where('id',$productID)->first();

        if($productData->product_type == '1')
        {
            $imagespathfolder='uploads/products/'.$productData->vendor_id.'/'.$productData->sku;
        }else{
            $productskuid=DB::table('product_attributes')->where([
                'product_id'=>$productID,
                'color_id'=>$colorID,
                'size_id'=>$sizeID,
                ])->first();
            $imagespathfolder='uploads/products/'.$productData->vendor_id.'/'.$productskuid->sku;
        }


            if($colorID!='' && $productData->product_type==3 )
			{
                $color_image= ProductImages::getConfiguredImages($productID,$colorID,0);
                $prd_img=URL::to($imagespathfolder).'/'.$color_image[0]['image'];

             }else{
                $prd_slider=App\Products::prdImages($product_id);
                $prd_img=URL::to($imagespathfolder).'/'.$prd_slider[0]['image'];
			}

       return ['short_description' => $productData->short_description, 'image' => $prd_img];
    }

    public static function productHeartCheck($productID){

        $productWishlistIcon = '<i class="fa fa-heart-o wishList prdheart'.$productID.'" prd_id="'.$productID.'" ></i>';
        if(auth()->guard('customer')->check()){
            $user_id = auth()->guard('customer')->user()->id;
            $wishlist_data = DB::table('tbl_wishlist')->select('tbl_wishlist.*', 'products.default_image', 'products.name', 'products.id as prd_id')
            ->join('products', 'tbl_wishlist.fld_product_id', '=', 'products.id')
            ->where('tbl_wishlist.fld_user_id', '=', $user_id)
            ->where('products.id', '=', $productID)
            ->get();
            if(count($wishlist_data) > 0){
                $productWishlistIcon = '<i class="fa fa-heart text-danger wishList prdheart'.$productID.'" prd_id="'.$productID.'" ></i>';
            }
        }
        return $productWishlistIcon;
     }

     public static function totalVendorsales($daterange, $cat, $vendor_id){
        $products=Orders::
               select('vendors.id','vendors.username','order_details.order_shipping_charges',
                DB::raw("count(*) AS totalorders, SUM(order_details.product_qty) AS `total_sales`, SUM(order_details.product_price) AS `total_price`")
                )
            ->join('order_details','order_details.order_id','orders.id')
			->join('products','products.id','order_details.product_id')
			->join('vendors','vendors.id','products.vendor_id');

                            if( ($cat!=0)){
                                      $products =$products
                            		->join('product_categories','product_categories.product_id','=','products.id')
                            		->join('categories','categories.id','=','product_categories.cat_id');
                            			$products =$products->where('product_categories.cat_id',$cat);
                            }
            if($daterange!='All' && $daterange!=''){
                    	$daterange_array=explode('.',$daterange);
                    	$from= date("Y-m-d", strtotime($daterange_array[0]));
                    	$to=date("Y-m-d", strtotime($daterange_array[1]));
        $from    = Carbon::parse($from)
             ->startOfDay()
             ->toDateTimeString();

    $to= Carbon::parse($to)
         ->endOfDay()
         ->toDateTimeString();
                   $products=$products
				 			 ->whereBetween('order_details.order_date',[$from,$to]);
                }
            $products=$products->where('vendors.id', $vendor_id)->groupBy('vendors.id')->orderBy('total_sales','desc')->first();
            return ($products)?$products->total_sales:0;

     }


      public static function getVendorCompanyProfile($id){
        return DB::table('vendor_company_info')->where('vendor_id',$id)->first();
      }
      public static function sendMailToVendorQtyLess(){

          //'sizes.*',  $size_row->name

              $size =DB::table('product_attributes')->select('product_attributes.qty','product_attributes.product_id','products.name as product_name','products.qty_out','products.vendor_id','vendors.email')

                     ->join('products','products.id','product_attributes.product_id')
                      ->join('vendors','vendors.id','products.vendor_id')
                      ->orderBy('product_attributes.size_id','ASC')->get();


                foreach($size as $size_row){

	//$row=DB::table('products')->select('id','name','qty_out','vendor_id')->where(['id'=>$size_row->product_id,'status'=>1,'isdeleted'=>0])->first(); connect@redliips.com
	$cust_info=DB::table('vendors')->select('id','email')->where(['id'=>@$size_row->vendor_id,'status'=>1,'isdeleted'=>0])->first();
	$details= base64_encode($size_row->product_id);
	$prdimage=URL::to('admin/addStock/'.$details);


		if($size_row->qty <= $size_row->qty_out){
				$email_data = [
                            'to'=>(@$size_row->email)?$size_row->email:'connect@redliips.com',
                            'subject'=>'Stock Notification',
                           "body"=>view("emails_template.stocktmp",
                                    array(
                                    'stock'=>$size_row->qty,
                                    'product'=>@$size_row->product_name,
                                    'size'=>'',
                                    'url'=>$prdimage
                                    )),
                            'phone'=>'',
                            'phone_msg'=>''
                         ];
                self::SendmailCustom($email_data);
		}
/*	if($size_row->qty ==0){

			        $email_data = [
                                    'to'=>"Jyoti@b2cmarketing.in",
                                    'subject'=>'Stock Notification',
                                    "body"=>view("emails_template.stocktmp",
                                    array(
                                    'stock'=>$size_row->qty,
                                    'product'=>$row->name,
                                    'size'=>$size_row->name,
                                    'url'=>$prdimage
                                    ))->render(),
                                    'phone'=>'',
                                    'phone_msg'=>''
                                  ];


                 self::SendmailCustom($email_data);


			}	*/


			}

    }

    public static function sendMailToVendorQtyLess_yogi(){
                 $array=[
            	"name"=>"Yogendra verma",
            	"email"=>"yogendraverma325@gmail.com"
            	];
            	//file_put_contents(time()."mail.txt",json_encode($_SERVER));

            // 	  $email_data = [
            //             'to'=>'jyoti@b2cmarketing.in',
            //             'subject'=>'Add Stock ',
            //              "body"=>view("emails_template.otp",
            //                  array(
            //         	    'data'=>array(
            //         	        'message'=>$email_msg
            //         	        )
            //         	     ) )->render(),
            // 	     'phone'=>'',
            // 	     'phone_msg'=>''
            //              ];

             $email_data = [
                            'to'=>'jyoti@b2cmarketing.in',
                            'subject'=>'Add Stock jyoti',
                            "body"=>'Please add stock to your listing',
                            'phone'=>'',
                            'phone_msg'=>''
                         ];
                self::SendmailCustom($email_data);


                 $email_data1 = [
                            'to'=>'anurag@b2cmarketing.in',
                            'subject'=>'Add Stock ',
                            "body"=>'Please add stock to your listing',
                            'phone'=>'',
                            'phone_msg'=>''
                         ];
                //self::SendmailCustom($email_data1);


    }
    public static function selectedCityName($city_id){
        $name='';
            $sitecityname = DB::table('cities')->select('name')->where('id',$city_id)->where(['isdeleted'=>0,'status'=>1])->first();
            if($sitecityname){
               $name=$sitecityname->name;
            }
            return $name;
          }
    public static function logisticsAuth($method,$url=''){

        switch($method){

            case 'POST':

                    try{
            $user_id=Config::get('constants.logisctics.user_id');
            $txt = sprintf($url,$user_id);
            $api_key=Config::get('constants.logisctics.api_key');
            $secret_key=Config::get('constants.logisctics.secret_key');

            $string_to_sign="$method\n/$txt";
            $utf_encoded=urlencode($string_to_sign);
            $hash=hash_hmac("sha1", $utf_encoded, $secret_key,FALSE);
            $signature=base64_encode($hash);
            $auth_token="SHIPIT $api_key:$signature";
            return array(
                    'auth_token'=>$auth_token,
                    'url'=>$txt
                );
                    }
                catch(Exception $e) {
                           return array(
                            'auth_token'=>$auth_token,
                            'url'=>$txt
                        );
                  }


        }

    }
    public static function trackOrder($method,$auth,$post_json_request){
         switch($method){

            case 'POST':
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, Config::get('constants.logisctics.base_url').$auth['url']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json',"Authorization:".$auth['auth_token']));
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS,
        json_encode($post_json_request));
        $result = curl_exec($ch );
        return $result;

        }
          switch($method){

            case 'GET':
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, Config::get('constants.logisctics.base_url').$auth['url']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json',"Authorization:".$auth['auth_token']));
        curl_setopt( $ch,CURLOPT_POST, false );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS,
        json_encode($post_json_request));
        $result = curl_exec($ch );
        return $result;

        }
    }

    public static function logisticsResponse($method,$auth,$post_json_request){
        switch($method){

            case 'POST':
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, Config::get('constants.logisctics.base_url').$auth['url']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json',"Authorization:".$auth['auth_token']));
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS,
        json_encode($post_json_request));
        $result = curl_exec($ch );
        return $result;

        }

    }

    public static function logisticsResponse1($method,$auth,$post_json_request){
        switch($method){

            case 'POST':
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, Config::get('constants.logisctics.base_url').$auth['url']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json',"Authorization:".$auth['auth_token']));
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS,
        json_encode($post_json_request));
        $result = curl_exec($ch );
        return $result;

        }

    }

    public static function checkDelivery_new($inputs){

        $data=DB::table('logistic_vendor_pincode')
        ->join('logistic_partner','logistic_vendor_pincode.logistic_partner_id', 'logistic_partner.id')
        ->where('logistic_vendor_pincode.pincode', $inputs['pincode'])
        ->where('logistic_vendor_pincode.isdeleted',0)
		->where('logistic_vendor_pincode.status',1)
        ->where('logistic_partner.status',1)
		->first();

        if(@$data){
            return true;
        }else{
            return false;
        }
}

     public static function checkDelivery($inputs){

                $method='POST';
                $url="users/%u/pincodeservice/rate";
                $auth_response=self::logisticsAuth($method,$url);

                $post_json_request = array(
                "pickup_pincode"=>"641603",
                "delivery_pincode"=>$inputs['pincode'],
                "invoice_value"=>$inputs['price'],
                "payment_mode"=>"cod",
                "insurance"=>false,
                "number_of_package"=>1,
                "product_type"=>"Parcel",
                "delivery_mode"=>"Both",
                "package_details"=>array(
                                    array(
                                    "package_content"=>$inputs['product_name'],
                                    "no_of_items"=>$inputs['qty'],
                                    "invoice_value"=>$inputs['price'],
                                    "package_dimension"=>array(
                                            //"weight"=>$inputs['weight'],
											"weight"=>70,//weight should not be greater than 70Kg
                                            "height"=>$inputs['height'],
                                            "length"=>$inputs['length'],
                                            "width"=>$inputs['width']
                                                     )
                                        )
                              )
                );

        $res=self::logisticsResponse($method,$auth_response,$post_json_request);
        return $res;
    }

    public static function ShipmentCharges($inputs){

        $method='POST';
        $url="users/%u/pincodeservice/rate";
        $auth_response=self::logisticsAuth($method,$url);

        $post_json_request = array(
                "pickup_pincode"=>$inputs['pickup_pincode'], // got
                "delivery_pincode"=>$inputs['delivery_pincode'], // got
                "invoice_value"=>$inputs['price'], // got
                "payment_mode"=>"cod",
                "insurance"=>false,
                "number_of_package"=>1,
                "product_type"=>"Parcel",
                "delivery_mode"=>"Both",
                "package_details"=>array(
                                    array(
                                    "package_content"=>$inputs['product_name'], // got
                                    "no_of_items"=>$inputs['qty'], // got
                                    "invoice_value"=>$inputs['price'], // got
                                    "package_dimension"=>array(
                                            "weight"=>$inputs['weight'],
                                            "height"=>$inputs['height'],
                                            "length"=>$inputs['length'],
                                            "width"=>$inputs['width']
                                                     )
                                        )
                              )
                );

        $res=self::logisticsResponse($method,$auth_response,$post_json_request);
        return $res;
    }

    public static function removeProductFromCustomerWishlistAndSaveForlater($customer_id,$product_id){

        DB::table('tbl_save_later')
        ->where('fld_user_id',$customer_id)
        ->where('fld_product_id',$product_id)
        ->delete();

        DB::table('tbl_wishlist')
        ->where('fld_user_id',$customer_id)
        ->where('fld_product_id',$product_id)
        ->delete();

    }
    public static function PickupRequest($inputs){

        $method='POST';
        $url="users/%u/pickup";
        $auth_response=self::logisticsAuth($method,$url);

        $post_json_request = array(
                "courier_id"=>$inputs['courier_id'],
                "service_id"=>(int) $inputs['service_id'],
                "service_type"=>$inputs['service_type'],
                "invoice_value"=> $inputs['order_total'],
                "payment_mode"=>$inputs['payment_mode'],
                "order_number"=>$inputs['order_number'], //got
                "pickup_pincode"=> (int) $inputs['pickup_pincode'], //got
                "delivery_pincode"=> (int) $inputs['delivery_pincode'], //got

                "pickup_address"=>array(
                                        "contact_name"=>$inputs['pickup_contact_name'], //got
                                        "company_name"=> substr($inputs['pickup_company_name'],0,30),
                                        "address1"=>$inputs['pickup_address1'], //got
                                        "address2"=>$inputs['pickup_address2'],
                                        "landmark"=>$inputs['pickup_landmark'],
                                        "city"=>$inputs['pickup_city'], //got
                                        "state"=>$inputs['pickup_state'], //got
                                        "pincode"=>$inputs['pickup_pincode'], //got
                                        "country"=>"IN",
                                        "contact_no"=>$inputs['pickup_contact'],
                                        "email"=>$inputs['pickup_email']
                                        ),

                "delivery_address"=>array(
                                    "contact_name"=>$inputs['delivery_contact_name'], //got
                                    "company_name"=>substr($inputs['company_name'],0,30),
                                    "address1"=>$inputs['delivery_address1'], //got
                                    "address2"=>$inputs['delivery_address2'], //got
                                    "landmark"=>$inputs['delivery_landmark'],
                                    "city"=>$inputs['delivery_city'], //got
                                    "state"=>$inputs['delivery_state'], //got
                                    "pincode"=>$inputs['delivery_pincode'], //got
                                    "country"=>$inputs['delivery_country'], //got
                                    "contact_no"=>$inputs['delivery_mobile'], //got
                                    "email"=>$inputs['delivery_email'] //got
                                ),
                "number_of_package"=> 1,
                "package_details"=>array(array(
                                        "package_content"=>$inputs['package_content'],
                                        "no_of_items"=> 1,
                                        "invoice_value"=> $inputs['order_total'],
                                        "package_dimension"=>array(
                                                "weight"=>$inputs['weight'],
                                                "height"=>$inputs['height'],
                                                "length"=>$inputs['length'],
                                                "width"=> $inputs['width']
                                            )
                                     )),
                "insurance"=>"false"

                );

        $request_body = json_encode($post_json_request);

//$base_url = "https://apicouriers.getgologistics.com/";
$base_url = "http://api.couriers.getgologistics.com";
$user_id = Config::get('constants.logisctics.user_id');
$key = "547b973ca3bb38f911502e7ce46430b4";
$secret = "73892edc6f8ad6fcd345dfbb1f94a033";

$post_url = $base_url."/users/".$user_id."/pickup";

$StringtoSign = "POST\n/users/".$user_id."/pickup";
$utf8_encode = urlencode($StringtoSign);
$sha1 = hash_hmac("sha1", $utf8_encode, $secret);
$Signature = base64_encode($sha1);

$header = "Authorization: SHIPIT ".$key.":".$Signature;

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $post_url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $request_body,
  CURLOPT_HTTPHEADER => array(
    $header,
    "cache-control: no-cache",
    "content-type: application/json",
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
  return "cURL Error #:" . $err;
} else {
  return $response;
}

    }


    public static function ShipmentOrder($inputs){

        $method='POST';
        $url="orders";
        $auth_response=self::logisticsAuth($method,$url);
        $user_id = Config::get('constants.logisctics.user_id');
        $key=Config::get('constants.logisctics.api_key');
        $secret=Config::get('constants.logisctics.secret_key');
        //$base_url = "https://apicouriers.getgologistics.com/";
        $base_url = "http://api.couriers.getgologistics.com";
        $post_url = $base_url."/orders";
        $StringtoSign = "POST\n/orders";
        $utf8_encode = urlencode($StringtoSign);
        $sha1 = hash_hmac("sha1", $utf8_encode, $secret);
        $Signature = base64_encode($sha1);
        $header ="Authorization: SHIPIT ".$key.":".$Signature;

        $post_json_request = array(
                "shipmentIds"=>[$inputs['shipmentIds']],
                "user_id"=>$user_id,
                "udf1"=>$inputs['udf1'],
                "Udf2"=>$inputs['udf2'],
                "Udf3"=>$inputs['udf3'],
                "client_source"=> "TEST",
                "api_key"=>$key
                );


             $request_body = json_encode($post_json_request);

 //echo '<pre>';print_r($request_body);die;
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $post_url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $request_body,
  CURLOPT_HTTPHEADER => array(
    $header,
    "cache-control: no-cache",
    "content-type: application/json",
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  return "cURL Error #:" . $err;
} else {
  return $response;
}


    }

    public static function CourierOrder($inputs){

        $method='GET';
        $url="orders/{orderCode}?details=true";
        $auth_response=self::logisticsAuth($method,$url);

        //$base_url = "https://apicouriers.getgologistics.com/";
        $base_url = "http://api.couriers.getgologistics.com";
        $user_id = Config::get('constants.logisctics.user_id');
        $key=Config::get('constants.logisctics.api_key');
        $secret=Config::get('constants.logisctics.secret_key');

        $ordercode = $inputs['ordercode'];
$post_url = $base_url."/orders/".$ordercode."?details=true";

$StringtoSign = "GET\n/orders/".$ordercode."?details=true";
$utf8_encode = urlencode($StringtoSign);
$sha1 = hash_hmac("sha1", $utf8_encode, $secret);
$Signature = base64_encode($sha1);

$header = "Authorization: SHIPIT ".$key.":".$Signature;
//echo $header;

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $post_url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    $header,
    "cache-control: no-cache",
    "content-type: application/json",
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  return "cURL Error #:" . $err;
} else {
  return $response;
}

    }

     public static function getCityFromState($id){
 //	$states=DB::table('states')->where('name',$id)->first();
     $cities=DB::table('cities')->where('state_id',$id)->get();
     return  $cities;
    }

    public static function getStateFromCountry($id){
        //	$states=DB::table('states')->where('name',$id)->first();
            $states=DB::table('states')->where('country_id',$id)->get();
            return  $states;
           }


     public static function getState($id='101'){
         $states=DB::table('states')->where('country_id',101)->get();
         return $states;
    }

      public static function getType(){
        $types=DB::table('company_types')->get();
        return $types;
    }

    public static function getCityFromState_old($id){
     $cities=DB::table('cities')->where('state_id',$id)->get();
     return  $cities;
    }
     public static function getState_old($id='101'){
         $states=DB::table('states')->where('country_id',101)->get();
         return $states;
    }
    public static function SendmailCustom($data){

                $email_to = strip_tags ($data['to']);
                            $mail = new PHPMailer();
                    // try{
                            $mail->IsSMTP();
                            // $mail->Host = 'mail.kefih.com';
                            // $mail->SMTPAuth = true;
                            // $mail->SMTPSecure = "tls";
                            // $mail->Port =587;

                            // $mail->Username = 'contact@kefih.com';
                            // $mail->Password = 'Faykhaabu';
                            // $mail->From = 'contact@kefih.com';

                            // $mail->FromName = 'kefih.com';


                            // $mail->Host = 'mail.b2cdomain.in';
                            // $mail->SMTPAuth = true;
                            // $mail->SMTPSecure = "tls";
                            // $mail->Port = 587;

                            // $mail->Username = 'kefih@b2cdomain.in';
                            // $mail->Password = 'mav3Lf-y[W{&';
                            // $mail->From = 'kefih@b2cdomain.in';



                              $mail->Host = 'mail.kefih.com';
                              $mail->SMTPAuth = true;
                                // $mail->SMTPAutoTLS = true;
                                $mail->SMTPSecure = 'tls';

                                $mail->Port = 587;

                                $mail->Username = 'contact@kefih.com';
                                $mail->Password = 'Faykhaabu';
                                $mail->From = 'contact@kefih.com';

                                $mail->FromName = 'kefih.com';

                                $mail->AddAddress($email_to);
                                $mail->IsHTML(true);
                                $mail->Subject = $data['subject'];
                                $mail->Body = $data['body'];


                           if(!$mail->send()) {
                            //    return $mail->ErrorInfo;
                            echo 'Message could not be sent.';
                            echo 'Mailer Error: ' . $mail->ErrorInfo;
                            die;
                        } else {

                            $msg = 'Mail successfully send';
                            return $msg;
                        }

                        // } catch (phpmailerException $e) {
                        //     echo $e->errorMessage(); //Pretty error messages from PHPMailer
                        //   }

    }



        public static function brandCount(){
        $total=Brands::select('id')->where('isdeleted',0)->get();
        return sizeof($total);
        }


        public static function getShippingDetails(){
          $data= DB::table('store_shipping_charges')->first();
          return $data;
        }

        public static function getServiceCharge(){
            $data= DB::table('store_info')->select('service_charge')->first();
            return $data;
        }

public static function orderDetailsLog($data,$sts){
                $single_array=array(
                "order_id"=>$data->order_id,
                "suborder_no"=>$data->suborder_no,
                "order_detail_invoice_num"=>$data->order_detail_invoice_num,
                "order_detail_invoice_date"=>$data->order_detail_invoice_date,
                "product_id"=>$data->product_id,
                "product_name"=>$data->product_name,
                "product_qty"=>$data->product_qty,
                "product_price"=>$data->product_price,
                "product_price_old"=>$data->product_price_old,
                "size"=>$data->size,
                "color"=>$data->color,
                "size_id"=>$data->size_id,
                "color_id"=>$data->color_id,
                "order_reward_points"=>$data->order_reward_points,
                "order_status"=>$sts,
                "order_date"=>$data->order_date,
                "order_updated"=>$data->order_updated
                );
      DB::table('order_details_log')->insert($single_array);
}
 public static function customerCount($vendor_id){
          $total=Customer::select('id')->where('isdeleted',0)->get();
    return sizeof($total);
}

    public static function vendorCount(){
    $total=Vendor::select('id')->where('isdeleted',0)->get();
    return sizeof($total);
}

public static function productCount(){
   $total=Products::select('id')->where('isdeleted',0)->get();
    return sizeof($total);
}
public static function activeproductCount(){
   $total=Products::select('id')->where('isdeleted',0)->where('status',1)->get();
    return sizeof($total);
}
public static function inactiveproductCount(){
   $total=Products::select('id')->where('isdeleted',0)->where('status',0)->get();
    return sizeof($total);
}
public static function orderCount(){
    //$total=DB::table('orders')->select('id')->get();
    $total=DB::table('order_details')->select('id')->get();
    return sizeof($total);
}
public static function pendingOrderCount(){
    $total=DB::table('order_details')->where('order_status',0)->select('id')->get();
    return sizeof($total);
}
public static function returnOrderCount(){
    $total=DB::table('order_details')->where('order_status',5)->select('id')->get();
    return sizeof($total);
}
public static function cancelOrderCount(){
    $total=DB::table('order_details')->where('order_status',4)->select('id')->get();
    return sizeof($total);
}
public static function deliveredOrderCount(){
    $total=DB::table('order_details')->where('order_status',3)->select('id')->get();
   // dd($total);
    return sizeof($total);
}
public static function shippingOrderCount(){
    $total=DB::table('order_details')->where('order_status',2)->select('id')->get();
   //dd($total);
    return sizeof($total);
}
public static function invoiceOrderCount(){
    $total=DB::table('order_details')->where('order_status',1)->select('id')->get();
   // dd($total);
    return sizeof($total);
}

public static function successOrderAmount(){
    return self::calculateOrderAmount(3);
}

public static function cancelOrderAmount(){

    return self::calculateOrderAmount(4);
}

public static function returnOrderAmount(){

    return self::calculateOrderAmount(5);
}


public static function calculateOrderAmount($type){
    $orderData=$Orders =OrdersDetail::select('order_details.product_qty','order_details.order_coupon_amount','order_details.product_price','order_details.order_shipping_charges')->join('orders', 'orders.id', '=', 'order_details.order_id')
    ->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id')
    ->join('products', 'products.id', '=', 'order_details.product_id')
    ->join('product_categories','product_categories.product_id','=','products.id')
    ->join('categories','categories.id','=','product_categories.cat_id')
    ->join('customers','orders.customer_id','=','customers.id')
    ->where('order_details.order_status',$type)
    ->groupBy('orders.id')->get();
   $totalAmount = 0;
   foreach($orderData as $row){
    $totalAmount+=(($row->product_qty * $row->product_price) + $row->order_shipping_charges) - $row->order_coupon_amount;
   }
   return round($totalAmount);
}


public static function calculateReverseShippingCharge(){
    $orderData=$Orders =OrdersDetail::select('order_details.reverse_order_shipping_charge')
    ->join('orders', 'orders.id', '=', 'order_details.order_id')
    ->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id')
    ->join('products', 'products.id', '=', 'order_details.product_id')
    ->join('product_categories','product_categories.product_id','=','products.id')
    ->join('categories','categories.id','=','product_categories.cat_id')
    ->join('customers','orders.customer_id','=','customers.id')
    ->where('order_details.order_status',5)
    ->where('order_details.reverse_order_shipping_charge','>',0)
    ->groupBy('orders.id')->get();
   $totalAmount = 0;
   foreach($orderData as $row){
    $totalAmount+=$row->reverse_order_shipping_charge;
   }
   return $totalAmount;
}


public static function calculateVendorReverseShippingCharge($vendor_id){
    $orderData=$Orders =OrdersDetail::select('order_details.reverse_order_shipping_charge')
    ->join('orders', 'orders.id', '=', 'order_details.order_id')
    ->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id')
    ->join('products', 'products.id', '=', 'order_details.product_id')
    ->join('product_categories','product_categories.product_id','=','products.id')
    ->join('categories','categories.id','=','product_categories.cat_id')
    ->join('customers','orders.customer_id','=','customers.id')
    ->whereIn('order_details.order_status',['5','6'])
    ->where('products.vendor_id',$vendor_id)
    ->where('order_details.reverse_order_shipping_charge','>',0)
    ->groupBy('orders.id')->get();
   $totalAmount = 0;
   foreach($orderData as $row){
    $totalAmount+=$row->reverse_order_shipping_charge;
   }
   return $totalAmount;
}


//--------------- Function for report count --------------------------------//
public static function reports_count($type){
	 //$products =DB::select("select count(*) AS orders, SUM(od.product_qty) AS `total_sales`, SUM(od.product_price) AS `total_price`, p.id, p.name, p.default_image from orders o, order_details od, products p where od.order_id=o.id and p.id=od.product_id group by p.id order by total_price desc");

     //echo '<pre>'; print_r($products);die;


        $page_details=array(
        "Title"=>"Reports",
        "Box_Title"=>"",
        "search_route"=>'',
        "reset_route"=>''
        );

        if(@$type==0){
		$products=Orders::
               select('products.id','products.name','products.default_image','products.spcl_price','products.sku','order_details.order_shipping_charges',
                DB::raw("count(*) AS totalorders, SUM(order_details.product_qty) AS `total_sales`, SUM(order_details.product_price) AS `total_price`")
                )
            ->join('order_details','order_details.order_id','orders.id')
            ->join('products','products.id','order_details.product_id')
            ->where('order_details.order_status','3')->groupBy('products.id')->orderBy('total_price','desc')->get();

		}

        if(@$type==1){
		$products=Orders::
               select('products.id','products.name','products.default_image','products.spcl_price','products.sku','order_details.order_shipping_charges','orders_shipping.order_shipping_zip','orders_shipping.order_shipping_city',
                DB::raw("count(*) AS totalorders, SUM(order_details.product_qty) AS `total_sales`, SUM(order_details.product_price) AS `total_price`")
                )
            ->join('order_details','order_details.order_id','orders.id')
            ->join('products','products.id','order_details.product_id')
            ->join('orders_shipping','orders_shipping.order_id','order_details.order_id')
            ->where('order_details.order_status','3')->groupBy('orders_shipping.order_shipping_zip')->orderBy('total_price','desc')->get();
		}

        if(@$type==2){
		$products=Orders::
               select('products.id','products.name','products.default_image','products.spcl_price','products.sku','order_details.order_shipping_charges','orders_shipping.order_shipping_zip','orders_shipping.order_shipping_city',
                DB::raw("count(*) AS totalorders, SUM(order_details.product_qty) AS `total_sales`, SUM(order_details.product_price) AS `total_price`")
                )
            ->join('order_details','order_details.order_id','orders.id')
            ->join('products','products.id','order_details.product_id')
            ->join('orders_shipping','orders_shipping.order_id','order_details.order_id')
            ->where('order_details.order_status','5')->groupBy('orders_shipping.order_shipping_zip')->orderBy('total_price','desc')->get();
		}



		if(@$type==3){
		$products=Orders::
               select('vendors.id','vendors.username','order_details.order_shipping_charges',
                DB::raw("count(*) AS totalorders, SUM(order_details.product_qty) AS `total_sales`, SUM(order_details.product_price) AS `total_price`")
                )
            ->join('order_details','order_details.order_id','orders.id')
			->join('products','products.id','order_details.product_id')
			->join('vendors','vendors.id','products.vendor_id')

            ->where('order_details.order_status','3')->groupBy('vendors.id')->get();


		}


		return count($products);

}

public static function getSizes($prd_id){
    $cats=self::getProductCategory($prd_id);
		$Sizes = Sizes::select('sizes.id','sizes.name')
		  ->join('categories_attributes', 'sizes.id', '=', 'categories_attributes.attrinbute_id')
		  ->groupBy('sizes.id')
		->where('sizes.isdeleted', 0)
		->where('categories_attributes.attribute_type',1)
		->whereIn('categories_attributes.category_id',$cats)
		->get();
            // $comments = new stdClass;
            // $comments->id =0;
            // $comments->name ="NA";

            // $Sizes->prepend($comments);
		return $Sizes;
}
public static function getProductCategory($prd)
    {
		$data=array();
			$ProductCategories = ProductCategories::select('cat_id')->where('product_id',$prd)->get()->toarray();
			foreach($ProductCategories as $row){
				array_push($data,$row['cat_id']);
			}
		return $data;
    }
public static function getColors($prd_id){
             $cats=self::getProductCategory($prd_id);
		$Colors = Colors::select('colors.id','colors.name')
                    ->join('categories_attributes', 'colors.id', '=', 'categories_attributes.attrinbute_id')
                    ->groupBy('colors.id')
                    ->where('colors.isdeleted', 0)
                    ->where('categories_attributes.attribute_type',0)
                    ->whereIn('categories_attributes.category_id',$cats)
                    ->get();
    //             $comments = new stdClass;
    //             $comments->id =0;
    //             $comments->name ="NA";


		  //  $Colors->prepend($comments);
		return $Colors;
}
public static function getcats($parent_id){

	$Categories = Category::select('id','name')
					->where('isdeleted', 0)
					->where('status', 1)
					->where('parent_id', $parent_id)
					->get()->toarray();
					return $Categories;
}
public static function getSubChild($parent_id=1,$sub_mark='',$selected){

	$Categories =self::getcats($parent_id);
					$str='';

					foreach ($Categories as $row) {
						$selected_id=($row['id']==$selected)?"selected":"";
				$str.='<option value="'.$row['id'].'" '.$selected_id.'>'.$sub_mark.ucwords($row['name']).'</option>';
                // $str.='<option>'.$selected.'</option>';
					$str.=self::getSubChild($row['id'],'&nbsp;&nbsp;'.$sub_mark.'-',$selected);
			}
			return $str;

}
public static function getChilds($parent_id=1,$selected){
		$Categories =self::getcats($parent_id);
					$str='';
					foreach ($Categories as $row) {
												$selected_id=($row['id']==$selected)?"selected":"";
				$str.='<option value="'.$row['id'].'" '.$selected_id.'>'.ucwords($row['name']).'</option>';
						$str.=self::getSubChild($row['id'],$selected);
			}
			return $str;

}
public static function getAdminChilds($parent_id=1,$sub_mark='',$selected){

		$Categories =self::getcats($parent_id);
					$str='';
					foreach ($Categories as $row) {
												$selected_id=($row['id']==$selected)?"selected":"";
				$str.='<option value="'.$row['id'].'" '.$selected_id.'>'.$sub_mark.ucwords($row['name']).'</option>';
						$str.=self::getSubChild($row['id'],'&nbsp;&nbsp;'.$sub_mark.'-',$selected);
			}
			return $str;

}

public static function getSubChild12($parent_id=1,$sub_mark='',$selected){

    $Categories =self::getcats($parent_id);
                    $str='';

            $new_selected=  explode(',',$selected);

                    foreach ($Categories as $row) {
                        // $selected_id=($row['id']==$selected)?"selected":"";
                         $selected_id=in_array($row['id'],$new_selected)?"selected":"";
                $str.='<option value="'.$row['id'].'" '.$selected_id.'>'.$sub_mark.ucwords($row['name']).'</option>';
                // $str.='<option>'.$selected.'</option>';
                    $str.=self::getSubChild12($row['id'],'&nbsp;&nbsp;'.$sub_mark.'-',$selected);
            }
            return $str;

}

public static function getAdminChilds12($parent_id=1,$sub_mark='',$selected){

        $Categories =self::getcats($parent_id);
        // dd($sub_mark);
        $new_selected=  explode(',',$selected);
        // dd($new_selected);
                    $str='';
                    foreach ($Categories as $row) {
                                                // $selected_id=($row['id']==$selected)?"selected":"";
                                                $selected_id=in_array($row['id'],$new_selected)?"selected":"";
                                                $select_id=0;
                                                // dd($new_selected);
                                                if (in_array($row['id'],$new_selected)) {
                                                    // dd($row['id']);
                                                   $select_id=$row['id'];
                                                }
                $str.='<option value="'.$row['id'].'" '.$selected_id.'>'.$sub_mark.ucwords($row['name']).'</option>';
                // dd($row['id'],$select_id);
                        $str.=self::getSubChild12($row['id'],'&nbsp;&nbsp;'.$sub_mark.'-',$selected);
            }
            return $str;

}


public static function getAdminChildsAdver($parent_id=1,$sub_mark='',$selected){
		$Categories =self::getcats($parent_id);
					$str='';
					foreach ($Categories as $row) {
					$selected_id=($row['id']==$selected)?"selected":"";
			 if($row['id']==215 || $row['id']==216){
				$str.='<option value="'.$row['id'].'" '.$selected_id.'>'.$sub_mark.ucwords($row['name']).'</option>';
			}		//$str.=self::getSubChild($row['id'],'&nbsp;&nbsp;'.$sub_mark.'-',$selected);
			}
			return $str;

}
public static function getSubChildTreeView($parent_id=1,$selected){
	$Categories =self::getcats($parent_id);
					$str='';
					$str.='<ul>';
					foreach ($Categories as $row) {
						$str.='<li>';
					$selected_id='';
									if (in_array($row['id'], $selected))
									{
									$selected_id="checked";
									}
									$str.='<i class="fa fa-folder" aria-hidden="true"></i>';
					$str.='<span><label><input type="checkbox" class="catTreeSeletcted" name="cat[]" value="'.$row['id'].'" '.$selected_id.'>'.ucwords($row['name']).'</label> </span>';
						$str.=self::getSubChildTreeView($row['id'],$selected);
	$str.='</li>';
			}
								$str.='</ul>';
			return $str;

}
public static function getChildsTreeView($parent_id=1,$selected){
		$Categories =self::getcats($parent_id);
					$str='';

					foreach ($Categories as $row) {
						$str.='<li>';
					$selected_id='';
									if (in_array($row['id'], $selected))
									{
									$selected_id="checked";
									}
									$str.='<i class="fa fa-folder" aria-hidden="true"></i>';
					$str.='<span><label><input type="checkbox" class="catTreeSeletcted" name="cat[]" value="'.$row['id'].'" '.$selected_id.'>'.ucwords($row['name']).'</label> </span>';
						$str.=self::getSubChildTreeView($row['id'],$selected);
	$str.='</li>';
			}

			return $str;

}

    public static function email_verification_mail($data)
    {


 $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';

    for ($i = 0; $i < 20; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $code .= $characters[$index];
    }
	    $url=route('email_verify',['email'=>base64_encode($data['to']),'code'=>base64_encode($code)]);

	   $record = DB::table('email_verification')->where('email',$data['to'])->first();

		if($record){
			 DB::table('email_verification')
                ->where('email', $data['to'])
                ->update([
			     'code' =>$code
				]);
		} else{
			DB::table('email_verification')->insert(
			[ 'email'=> $data['to'], 'code' =>$code]
			);
		}

			$email_msg='<tr>
                         <td style="padding:5px 10px;">
                            <strong>CLick the Link to verify</strong>
                         </td>
                         <td style="padding:5px 10px;">
                                  <p><strong></strong><a href="'.$url.'">Click here</a></p>
                         </td>
                     </tr>';

                    $email_data = [
                        'to'=>$data['to'],
                        'subject'=>'Email Verification',
                         "body"=>view("emails_template.otp",
                             array(
                    	    'data'=>array(
                    	        'message'=>$email_msg
                    	        )
                    	     ) )->render(),
                    	     'phone'=>'',
                    	     'phone_msg'=>''
                         ];
                self::SendmailCustom($email_data);
// 		$data['message']=$msg;
		/***self::sendEmail($data);***/
    }
	public static function sendEmail($data)
    {
			Mail::to($data['to'])->send(new EmailMessage($data));
    }

    public static function changedPassword($data){
            $msg=$data['to_name']." , You have changed the password successfullly.";
            $data['message']=$msg;
            self::sendEmail($data);
    }

	public static function generateMailforOrderSts($order_id,$type=0){

	    switch($type){
	            // invoice generated
                case 0:
                $master_orders=OrdersDetail::where('id',$order_id)->first();
                $master_order=Orders::where('id',$master_orders->order_id)->first();

                // print_r($master_order->customer_id);
                // exit;

                $customer_data=Customer::where('id',$master_order->customer_id)->first();
                $shipping_data=OrdersShipping::where('order_id',$master_orders->order_id)->first();
                $msg='Dear '.$customer_data->name.' '.$customer_data->last_name.'  invoice generated for  order('.$master_orders->suborder_no.') .redliips.com';
                $price = ($master_orders->product_qty*$master_orders->product_price)+$master_orders->order_shipping_charges+$master_orders->order_cod_charges-$master_orders->order_coupon_amount-$master_orders->order_wallet_amount;
                    $mode= ($master_order->payment_mode==0)?"'COD'":"'Paid'";
                    $email_msg='<tr>
            	<td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
                	<p>Hi '.$customer_data->name.' '.$customer_data->last_name.'</p>
                    <p>invoice generated for  order('.$master_orders->suborder_no.'). We will send you an Email and SMS for further process</p>
                    <p>
                    Order ID: <span style="color:#00bbe6;">'.$master_orders->suborder_no.'</span><br />
                    Order Date: <span style="color:#00bbe6;">'.$master_order->order_date.'</span><br />
                        Invoice Number: <span style="color:#00bbe6;">'.$master_orders->order_detail_invoice_num.'</span><br />
                        Invoice Date: <span style="color:#00bbe6;">'.$master_orders->order_detail_invoice_date.'</span><br />
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
                         <th>Shipping charges </th>
						<th>Amt</th>
                      </tr>';

                      $i=1;




                    $price = ($master_orders->product_qty*$master_orders->product_price)+$master_orders->order_shipping_charges+$master_orders->order_cod_charges-$master_orders->order_coupon_amount-$master_orders->order_wallet_amount;
                        //$master_orders->product_qty*$master_orders->product_price
                    $email_msg.='<tr>
                    <td style="padding:10px 10px 5px; border-bottom:dashed 1px #ccc;">'.$i.'</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_name.'('.$master_orders->size.' '.$master_orders->color.')</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_qty.'</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_price.'</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->order_shipping_charges.'</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$price.'</td></tr>';

                    $email_msg.='<tr bgcolor="#d1d4d1">
                    <td style="padding:5px 10px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><strong>Total Amount Rs.:'.$price.' </strong></td>
                    </tr>';

                    $email_msg.='</table>

                </td>
            </tr>';

	           $email_data = [
                            'to'=>$customer_data->email,
                            'subject'=>'Invoice generated',
                            "body"=>view("emails_template.order_sts_change",
                            array(
                            'data'=>array(
                            'message'=>$email_msg
                            )
                            ) )->render(),
                            'phone'=>$customer_data->phone,
                            'phone_msg'=>$msg
                         ];
                if($customer_data->email)  {
                    self::SendmailCustom($email_data);
                }
                if($customer_data->phone) {
                    self::SendMsg($email_data);
                }
                break;

                // shipping
                 case 1:
                    // dd($order_id));
                         $master_orders=OrdersDetail::where('id',$order_id)->first();

                            $master_order=Orders::where('id',$master_orders->order_id)->first();

                             $corier_data=DB::table('orders_courier')
                            ->select('couriers.*')
                            ->join('couriers','couriers.id','orders_courier.courier_name')
                            ->where('order_detail_id',$master_orders->order_id)->first();

                        $customer_data=Customer::where('id',$master_order->customer_id)->first();

                        $cr_name='';
                        if($corier_data){
                            $cr_name=$corier_data->name;
                        }

                        $shipping_data=OrdersShipping::where('order_id',$master_orders->order_id)->first();

                        if($master_order->payment_mode==0) {

                            // $msg=view("message_template.order_delivered",
             //                    array(
             //                    'data'=>array(
             //                    'name'=>$customer_data->name,
             //                    'suborder_no'=>$master_orders->suborder_no,
             //                    'cr_name'=>$cr_name
             //                    )
             //                    ) )->render();
                             $msg="Hello Customer, We are happy to inform you that your Kefih order has been dispatched. Thank you. - Kefih E-Commerce Private Limited";

                            }
                        else {
                            $msg=view("message_template.order_delivered",
                                array(
                                'data'=>array(
                                'name'=>$customer_data->name,
                                'suborder_no'=>$master_orders->suborder_no,
                                'cr_name'=>$cr_name
                                )
                                ) )->render();
                        }

                        //$msg='Dear '.$customer_data->name.' '.$customer_data->last_name.'  your  order('.$master_orders->suborder_no.') is  successfully delivered  with('.$cr_name.') .redliips.com';
                        $price = ($master_orders->product_qty*$master_orders->product_price)+$master_orders->order_shipping_charges+$master_orders->order_cod_charges-$master_orders->order_coupon_amount-$master_orders->order_wallet_amount;
                            $mode= ($master_order->payment_mode==0)?"'COD'":"'Paid'";
                            $email_msg='<tr>
                        <td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
                            <p>Hi '.$customer_data->name.' '.$customer_data->last_name.'</p>
                            <p>Your   order('.$master_orders->suborder_no.')is  successfully Shipped.</p>
                            <p>
                            Order ID: <span style="color:#00bbe6;">'.$master_orders->suborder_no.'</span><br />
                            Order Date: <span style="color:#00bbe6;">'.$master_order->order_date.'</span><br />
                                Invoice Number: <span style="color:#00bbe6;">'.$master_orders->order_detail_invoice_num.'</span><br />
                                Invoice Date: <span style="color:#00bbe6;">'.$master_orders->order_detail_invoice_date.'</span><br />
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
                                 <th>Shipping charges </th>
                                <th>Amt</th>
                              </tr>';

                              $i=1;
                            $email_msg.='<tr>
                            <td style="padding:10px 10px 5px; border-bottom:dashed 1px #ccc;">'.$i.'</td>
                            <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_name.'('.$master_orders->size.' '.$master_orders->color.')</td>
                            <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_qty.'</td>
                            <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_price.'</td>
                            <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->order_shipping_charges.'</td>
                            <td style="border-bottom:dashed 1px #ccc;">'.$price.'</td></tr>';


                            $email_msg.='<tr bgcolor="#d1d4d1">
                            <td style="padding:5px 10px;">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td><strong>Total Amount Rs.:'.$price.' </strong></td>
                            </tr>';



                            $email_msg.='</table>

                        </td>
                    </tr>';

                    echo view("emails_template.order_sts_change",
                    array(
                    'data'=>array(
                    'message'=>$email_msg
                    )
                    ) )->render();

                    die;


                    $email_data = [
                            'to'=>$customer_data->email,
                            'subject'=>'Order shipped',
                            "body"=>view("emails_template.order_sts_change",
                            array(
                            'data'=>array(
                            'message'=>$email_msg
                            )
                            ) )->render(),
                            'phone'=>$customer_data->phone,
                            'phone_msg'=>$msg
                         ];

                    if($customer_data->email) {
                        self::SendmailCustom($email_data);
                    }
                    if($customer_data->phone) {
                        self::SendMsg($email_data);
                    }
                break;
                // order delivered
                case 2:
                    $master_orders=OrdersDetail::where('id',$order_id)->first();

                    $master_order=Orders::where('id',$master_orders->order_id)->first();

                     $corier_data=DB::table('orders_courier')
                    ->select('couriers.*')
                    ->join('couriers','couriers.id','orders_courier.courier_name')
                    ->where('order_detail_id',$master_orders->order_id)->first();

                $customer_data=Customer::where('id',$master_order->customer_id)->first();

                $cr_name='';
                if($corier_data){
                    $cr_name=$corier_data->name;
                }

                $shipping_data=OrdersShipping::where('order_id',$master_orders->order_id)->first();

				if($master_order->payment_mode==0) {

					// $msg=view("message_template.order_delivered",
     //                    array(
     //                    'data'=>array(
     //                    'name'=>$customer_data->name,
     //                    'suborder_no'=>$master_orders->suborder_no,
     //                    'cr_name'=>$cr_name
     //                    )
     //                    ) )->render();
                        $msg="Hello Customer, Your Kefih order has been successfully delivered! We thank you for shopping with Kefih! - Kefih E-Commerce Private Limited";

					}
				else {
					$msg=view("message_template.order_delivered",
                        array(
                        'data'=>array(
                        'name'=>$customer_data->name,
                        'suborder_no'=>$master_orders->suborder_no,
                        'cr_name'=>$cr_name
                        )
                        ) )->render();
                }

                //$msg='Dear '.$customer_data->name.' '.$customer_data->last_name.'  your  order('.$master_orders->suborder_no.') is  successfully delivered  with('.$cr_name.') .redliips.com';
                $price = ($master_orders->product_qty*$master_orders->product_price)+$master_orders->order_shipping_charges+$master_orders->order_cod_charges-$master_orders->order_coupon_amount-$master_orders->order_wallet_amount;
                    $mode= ($master_order->payment_mode==0)?"'COD'":"'Paid'";
                    $email_msg='<tr>
            	<td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
                	<p>Hi '.$customer_data->name.' '.$customer_data->last_name.'</p>
                    <p>Your   order('.$master_orders->suborder_no.')is  successfully delivered  with('.$cr_name.').</p>
                    <p>
                    Order ID: <span style="color:#00bbe6;">'.$master_orders->suborder_no.'</span><br />
                    Order Date: <span style="color:#00bbe6;">'.$master_order->order_date.'</span><br />
                        Invoice Number: <span style="color:#00bbe6;">'.$master_orders->order_detail_invoice_num.'</span><br />
                        Invoice Date: <span style="color:#00bbe6;">'.$master_orders->order_detail_invoice_date.'</span><br />
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
                         <th>Shipping charges </th>
						<th>Amt</th>
                      </tr>';

                      $i=1;
                    $email_msg.='<tr>
                    <td style="padding:10px 10px 5px; border-bottom:dashed 1px #ccc;">'.$i.'</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_name.'('.$master_orders->size.' '.$master_orders->color.')</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_qty.'</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_price.'</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->order_shipping_charges.'</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$price.'</td></tr>';


                    $email_msg.='<tr bgcolor="#d1d4d1">
                    <td style="padding:5px 10px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><strong>Total Amount Rs.:'.$price.' </strong></td>
                    </tr>';



                    $email_msg.='</table>

                </td>
            </tr>';


	           $email_data = [
                            'to'=>$customer_data->email,
                            'subject'=>'Order Delivered',
                            "body"=>view("emails_template.order_sts_change",
                            array(
                            'data'=>array(
                            'message'=>$email_msg
                            )
                            ) )->render(),
                            'phone'=>$customer_data->phone,
                            'phone_msg'=>$msg
                         ];

                if($customer_data->email) {
                    self::SendmailCustom($email_data);
                }
                if($customer_data->phone) {
                    self::SendMsg($email_data);
                }
                break;

                // pickup the order
                case 3:
                    $master_orders=OrdersDetail::where('id',$order_id)->first();


                    $master_order=Orders::where('id',$master_orders->order_id)->first();

                     $corier_data=DB::table('orders_courier')
                    ->select('couriers.*')
                    ->join('couriers','couriers.id','orders_courier.courier_name')
                    ->where('order_detail_id',$master_orders->order_id)->first();

                $customer_data=Customer::where('id',$master_order->customer_id)->first();




                $shipping_data=OrdersShipping::where('order_id',$master_orders->order_id)->first();
                $price = ($master_orders->product_qty*$master_orders->product_price)+$master_orders->order_shipping_charges+$master_orders->order_cod_charges-$master_orders->order_coupon_amount-$master_orders->order_wallet_amount;
                $msg='Dear '.$customer_data->name.' '.$customer_data->last_name.'  your return request for order('.$master_orders->suborder_no.')  is accpeted  . Pickup query is generated  .redliips.com';

                    $mode= ($master_order->payment_mode==0)?"'COD'":"'Paid'";
                    $email_msg='<tr>
            	<td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
                	<p>Hi '.$customer_data->name.' '.$customer_data->last_name.'</p>
                    <p>Your return request for  order('.$master_orders->suborder_no.') is accpeted  . Pickup query is generated</p>
                    <p>
                    Order ID: <span style="color:#00bbe6;">'.$master_orders->suborder_no.'</span><br />
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
                	<p><strong>Pickup Address</strong><br />
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
                         <th>Shipping charges </th>
						<th>Amt</th>
                      </tr>';

                      $i=1;
                    $email_msg.='<tr>
                    <td style="padding:10px 10px 5px; border-bottom:dashed 1px #ccc;">'.$i.'</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_name.'('.$master_orders->size.' '.$master_orders->color.')</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_qty.'</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_price.'</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->order_shipping_charges.'</td>
                     <td style="border-bottom:dashed 1px #ccc;">'.$price.'</td>
                    </tr>';


                    $email_msg.='<tr bgcolor="#d1d4d1">
                    <td style="padding:5px 10px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><strong>Total Amount Rs.:'.$price.' </strong></td>
                    </tr>';



                    $email_msg.='</table>

                </td>
            </tr>';


	           $email_data = [
                            'to'=>$customer_data->email,
                            'subject'=>'Order Pickup Confirmation',
                            "body"=>view("emails_template.order_sts_change",
                            array(
                            'data'=>array(
                            'message'=>$email_msg
                            )
                            ) )->render(),
                            'phone'=>$customer_data->phone,
                            'phone_msg'=>$msg
                         ];

                    if($customer_data->email) self::SendmailCustom($email_data);
                    if($customer_data->phone) self::SendMsg($email_data);
                break;

                // order pickuped
                case 4:
                    $master_orders=OrdersDetail::where('id',$order_id)->first();
                    $corier_data=DB::table('orders_courier')
                    ->select('couriers.*')
                    ->join('couriers','couriers.id','orders_courier.courier_name')
                     ->where('order_detail_id',$order_id)->first();
                    $master_order=Orders::where('id',$master_orders->order_id)->first();

                $customer_data=Customer::where('id',$master_order->customer_id)->first();
                $shipping_data=OrdersShipping::where('order_id',$master_orders->order_id)->first();
                $msg='Dear '.$customer_data->name.' '.$customer_data->last_name.'  your return request for order('.$master_orders->suborder_no.')  is successfully pickuped    .redliips.com';
                $price = ($master_orders->product_qty*$master_orders->product_price)+$master_orders->order_shipping_charges+$master_orders->order_cod_charges-$master_orders->order_coupon_amount-$master_orders->order_wallet_amount;
                    $mode= ($master_order->payment_mode==0)?"'COD'":"'Paid'";
                    $email_msg='<tr>
            	<td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
                	<p>Hi '.$customer_data->name.' '.$customer_data->last_name.'</p>
                    <p>Your return request for  order('.$master_orders->suborder_no.') is successfully pickuped</p>
                    <p>
                    Order ID: <span style="color:#00bbe6;">'.$master_orders->suborder_no.'</span><br />
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
                	<p><strong>Pickup Address</strong><br />
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
                         <th>Shipping charges </th>
						<th>Amt</th>
                      </tr>';

                      $i=1;
                    $email_msg.='<tr>
                    <td style="padding:10px 10px 5px; border-bottom:dashed 1px #ccc;">'.$i.'</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_name.'('.$master_orders->size.' '.$master_orders->color.')</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_qty.'</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_price.'</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->order_shipping_charges.'</td>
                     <td style="border-bottom:dashed 1px #ccc;">'.$price.'</td>
                    </tr>';


                    $email_msg.='<tr bgcolor="#d1d4d1">
                    <td style="padding:5px 10px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><strong>Total Amount Rs.:'.$price.' </strong></td>
                    </tr>';



                    $email_msg.='</table>

                </td>
            </tr>';


	           $email_data = [
                            'to'=>$customer_data->email,
                            'subject'=>'Order Pickup Confirmed',
                            "body"=>view("emails_template.order_sts_change",
                            array(
                            'data'=>array(
                            'message'=>$email_msg
                            )
                            ) )->render(),
                            'phone'=>$customer_data->phone,
                            'phone_msg'=>$msg
                         ];

                    if($customer_data->email) self::SendmailCustom($email_data);
                    if($customer_data->phone) self::SendMsg($email_data);
                break;


                // replaced order generated
                case 5:
                    $master_orders=OrdersDetail::where('id',$order_id)->first();
                    $corier_data=DB::table('orders_courier')
                    ->select('couriers.*')
                    ->join('couriers','couriers.id','orders_courier.courier_name')
                    ->where('order_detail_id',$order_id)->first();
                    $master_order=Orders::where('id',$master_orders->order_id)->first();

                $customer_data=Customer::where('id',$master_order->customer_id)->first();
                $shipping_data=OrdersShipping::where('order_id',$master_orders->order_id)->first();
                $msg='Dear '.$customer_data->name.' '.$customer_data->last_name.'  your return request for order('.$master_orders->suborder_no.')  accepted and generated new order    .redliips.com';
                $price = ($master_orders->product_qty*$master_orders->product_price)+$master_orders->order_shipping_charges+$master_orders->order_cod_charges-$master_orders->order_coupon_amount-$master_orders->order_wallet_amount;
                    $mode= ($master_order->payment_mode==0)?"'COD'":"'Paid'";
                    $email_msg='<tr>
            	<td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
                	<p>Hi '.$customer_data->name.' '.$customer_data->last_name.'</p>
                    <p>Your return request for  order('.$master_orders->suborder_no.') is  accepted and generated new order</p>
                    <p>
                    Order ID: <span style="color:#00bbe6;">'.$master_orders->suborder_no.'</span><br />
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
                	<p><strong>Pickup Address</strong><br />
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
                         <th>Shipping charges </th>
						<th>Amt</th>
                      </tr>';

                      $i=1;
                    $email_msg.='<tr>
                    <td style="padding:10px 10px 5px; border-bottom:dashed 1px #ccc;">'.$i.'</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_name.'('.$master_orders->size.' '.$master_orders->color.')</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_qty.'</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_price.'</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->order_shipping_charges.'</td>
                     <td style="border-bottom:dashed 1px #ccc;">'.$price.'</td>
                    </tr>';


                    $email_msg.='<tr bgcolor="#d1d4d1">
                    <td style="padding:5px 10px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><strong>Total Amount Rs.:'.$price.' </strong></td>
                    </tr>';



                    $email_msg.='</table>

                </td>
            </tr>';


	           $email_data = [
                            'to'=>$customer_data->email,
                            'subject'=>'Replaced confirm genetaed new order',
                            "body"=>view("emails_template.order_sts_change",
                            array(
                            'data'=>array(
                            'message'=>$email_msg
                            )
                            ) )->render(),
                            'phone'=>$customer_data->phone,
                            'phone_msg'=>$msg
                         ];

                    if($customer_data->email) self::SendmailCustom($email_data);
                    if($customer_data->phone) self::SendMsg($email_data);
                break;

                // refunded order generated
                case 6:
                    $master_orders=OrdersDetail::where('id',$order_id)->first();
                    $corier_data=DB::table('orders_courier')
                    ->select('couriers.*')
                    ->join('couriers','couriers.id','orders_courier.courier_name')
                     ->where('order_detail_id',$order_id)->first();
                    $master_order=Orders::where('id',$master_orders->order_id)->first();

                $customer_data=Customer::where('id',$master_order->customer_id)->first();
                $shipping_data=OrdersShipping::where('order_id',$master_orders->order_id)->first();
                $msg='Dear '.$customer_data->name.' '.$customer_data->last_name.'  your return request for order('.$master_orders->suborder_no.')  accepted and refunded   .redliips.com';
                $price = ($master_orders->product_qty*$master_orders->product_price)+$master_orders->order_shipping_charges+$master_orders->order_cod_charges-$master_orders->order_coupon_amount-$master_orders->order_wallet_amount;
                    $mode= ($master_order->payment_mode==0)?"'COD'":"'Paid'";
                    $email_msg='<tr>
            	<td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
                	<p>Hi '.$customer_data->name.' '.$customer_data->last_name.'</p>
                    <p>Your return request for  order('.$master_orders->suborder_no.') is  accepted and refunded.</p>
                    <p>
                    Order ID: <span style="color:#00bbe6;">'.$master_orders->suborder_no.'</span><br />
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
                	<p><strong>Pickup Address</strong><br />
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
                         <th>Shipping charges </th>
						<th>Amt</th>
                      </tr>';

                      $i=1;
                    $email_msg.='<tr>
                    <td style="padding:10px 10px 5px; border-bottom:dashed 1px #ccc;">'.$i.'</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_name.'('.$master_orders->size.' '.$master_orders->color.')</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_qty.'</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_price.'</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_qty*$master_orders->product_price.'</td></tr>';


                    $email_msg.='<tr bgcolor="#d1d4d1">
                    <td style="padding:5px 10px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><strong>Total Amount Rs.:'.$master_orders->product_qty*$master_orders->product_price.' </strong></td>
                    </tr>';



                    $email_msg.='</table>

                </td>
            </tr>';


	           $email_data = [
                            'to'=>$customer_data->email,
                            'subject'=>'Return order refunded',
                            "body"=>view("emails_template.order_sts_change",
                            array(
                            'data'=>array(
                            'message'=>$email_msg
                            )
                            ) )->render(),
                            'phone'=>$customer_data->phone,
                            'phone_msg'=>$msg
                         ];

                    if($customer_data->email) self::SendmailCustom($email_data);
                    if($customer_data->phone) self::SendMsg($email_data);
                break;
                //cancel order mail
                case 7:

                $opeation_pr='Cancel';

                $master_orders=OrdersDetail::where('id',$order_id)->first();
                $master_order=Orders::where('id',$master_orders->order_id)->first();
                $product_data = DB::table('products')->where('id',$master_orders->product_id)->first();

                $customer_data=Customer::where('id',$master_order->customer_id)->first();
                $shipping_data=OrdersShipping::where('order_id',$master_orders->order_id)->first();

				if($master_order->payment_mode==0) {

					$msg=view("message_template.cod_order_cancel_byadmin",
                        array(
                        'data'=>array(
                        'name'=>$customer_data->name,
                        'suborder_no'=>$master_orders->suborder_no
                        )
                        ) )->render();

					}
				else {
					$msg=view("message_template.online_order_cancel_byadmin",
                        array(
                        'data'=>array(
                        'name'=>$customer_data->name,
                        'suborder_no'=>$master_orders->suborder_no
                        )
                        ) )->render();
				}


                    $mode= ($master_order->payment_mode==0)?"'COD'":"'Paid'";

                    $email_msg=view("emails_template.order_cancel_byadmin",
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
                                                                    'order_shipping_charges'=>$master_orders->order_shipping_charges,
                                                                    'order_cod_charges'=>$master_orders->order_cod_charges,
                                                                    'order_coupon_amount'=>$master_orders->order_coupon_amount,
                                                                    'order_wallet_amount'=>$master_orders->order_wallet_amount,
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
                         if($customer_data->email) self::SendmailCustom($email_data);
                         if($customer_data->phone) self::SendMsg($email_data);
                    break;
	    }
	}

	public static function generate_otp($data)
    {
		$otp=rand(0,9).rand(0,9).rand(0,9).rand(0,9);
		$msg='Hello ,'.$otp.' this is phone otp . Otp is valid for only 10 min.';
		$data['message']=$msg;
            $customer_data=DB::table('vendors')->where('phone',$mob)->orwhere('email',$mob)->first();

            $email_msg='<tr>
                             <td style="padding:5px 10px;">
                                <strong>OTP</strong>
                             </td>
                             <td style="padding:5px 10px;">
                                      <p><strong></strong>'.$msg.'</p>
                             </td>
                         </tr>';

                        $email_data = [
                            'to'=>$customer_data->email,
                            'subject'=>'OTP',
                             "body"=>view("emails_template.otp",
                                 array(
                        	    'data'=>array(
                        	        'message'=>$email_msg
                        	        )
                        	     ) )->render(),
                        	     'phone'=>'',
                        	     'phone_msg'=>''
                             ];
                             if($customer_data->email) self::SendmailCustom($email_data);

			$ch = curl_init();
                $user="testuser"; //your username
                $password="test@123"; //your password
                $mobilenumbers=	$data['phone']; //enter Mobile numbers comma seperated
                $message = $msg; //enter Your Message
                $senderid="TEST"; //Your senderidCURLOPT_URL =>
                $url="https://api-alerts.kaleyra.com/v4/?method=sms&sender=".$senderid."&to=".$mobilenumbers."&message=".$msg."&api_key=Ad6cf2841bd5f2bdc1d717c23d11dcdfa";

                // curl_setopt( $ch,CURLOPT_URL, $url );

// curl_setopt( $ch,CURLOPT_URL, 'http://sms.b2cmarketing.in/api/swsendSingle.asp?username=t1testb2c&password=48566224&sender=testbc&sendto=91'.$mob.'&message='.$msg );



//                         curl_setopt( $ch,CURLOPT_POST, true );

//                         curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );

//                         curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );

//                         curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );

//                         $result = curl_exec($ch );


$api_key = '45EDF740109A10';
        $contacts = $mobilenumbers;
        $from = 'ALERTS';
        $sms_text = $msg;


        //Submit to server
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, "http://sms.b2chosting.in/app/smsapi/index.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,"key=".$api_key."&routeid=468&type=text&contacts=".$contacts."&senderid=".$from."&msg=".$sms_text);
        $response = curl_exec($ch);
        curl_close($ch);

		$record = DB::table('phone_otp')->where('phone',$data['phone'])->first();

		if($record){
			 DB::table('phone_otp')
                ->where('phone', $data['phone'])
                ->update([
				'otp' =>$otp  ,'expired_on'=>Carbon::now()->addMinutes(10)
				]);
		} else{
			DB::table('phone_otp')->insert(
			[ 'phone'=> $data['phone'], 'otp' =>$otp  ,'expired_on'=>Carbon::now()->addMinutes(10)]
			);
		}

		/**self::sendEmail($data);****/


    }
      public static function generate_profile_otp($data)
    {   $mob=$data['phone'];
        $otp=rand(0,9).rand(0,9).rand(0,9).rand(0,9);



		$customer_data=DB::table('customers')->where('id',$data['userId'])->first();

		$msg='Hello ,'.ucfirst($customer_data->name).' OTP is '.$otp.' for change your profile on redliips.com enter OTP and verify your account.';

		$email_msg='<tr>
                         <td style="padding:5px 10px;">
                            <strong>OTP</strong>
                         </td>
                         <td style="padding:5px 10px;">
                                  <p><strong></strong>'.$msg.'</p>
                         </td>
                     </tr>';

                    $email_data = [
                        'to'=>$data['email'],
                        'subject'=>'OTP',
                         "body"=>view("emails_template.otp",
                             array(
                    	    'data'=>array(
                    	        'message'=>$email_msg
                    	        )
                    	     ) )->render(),
                    	     'phone'=>'',
                    	     'phone_msg'=>''
                         ];
                if($data['email']) self::SendmailCustom($email_data);


        $flag=0;
        if (array_key_exists("flag",$data))
  {
  $flag=$data['flag'];
  }


		$record = DB::table('customer_phone_otp')->where('phone',$mob)->first();

               self::msgCurl($mob,$msg);
		if($record){



			 DB::table('customer_phone_otp')
                ->where('phone',$mob)
               // ->where('flag',$flag)
                ->update([
				'otp' =>$otp  ,'expired_on'=>Carbon::now()->addMinutes(10)
				]);
		} else{
			DB::table('customer_phone_otp')->insert(
			[ 'phone'=> $mob, 'otp' =>$otp  ,'expired_on'=>Carbon::now()->addMinutes(10) ,'user_id'=>$data['userId'] ,'flag'=> $flag  ]
			);
		}

		/**self::sendEmail($data);****/


    }
        public static function SendMsg($data){

        $message=$data['phone_msg'];
        $mobile_number=$data['phone'];
        //   file_put_contents('email.txt',json_encode($data));
        self::msgCurl($mobile_number,$message);


    }

    /*
    public static function msgCurl($mobile,$msg){

               $msg=urlencode($msg);
				$fields=array(

							);
				$curl = curl_init();

				curl_setopt_array($curl, array(
				CURLOPT_URL => "https://api.msg91.com/api/sendhttp.php?authkey=&mobiles=$mobile&country=91&message=$msg&sender=&route=4",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => json_encode( $fields ),
				));

				$response = curl_exec($curl);
				$err = curl_error($curl);
				curl_close($curl);
			return $response;

    }
    */


    public static function msgCurl($mobile,$msg){

                    //https://api.textlocal.in/docs/sendsms
                    // Account details
                    $apiKey = urlencode('NjI2OTU0NTU1MDMwMzc0ZDM4NDg0ZTc4MzU1NzY0NGU=');

                    // Message details
                    $numbers = array($mobile);
                    $sender = urlencode('KEFIHW');
                    $message = rawurlencode($msg);

                    $numbers = implode(',', $numbers);

                    // Prepare data for POST request
                    $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);

                    // Send the POST request with cURL
                    $ch = curl_init('https://api.textlocal.in/send/');
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    $response = curl_exec($ch);
                    curl_close($ch);

                    // Process your response here
                    // echo $response;
                    return $response;
    }



    public static  function generate($data){
         $otp=rand(0,9).rand(0,9).rand(0,9).rand(0,9);

        $mob=9389649459;
        $msg="This is the AJAX CALL OTP ".$otp;
        	  self::msgCurl($mob,$msg);
    }
    public static function generate_user_otpApp($data)
    {


        $mob=$data['phone'];
        $otp=rand(0,9).rand(0,9).rand(0,9).rand(0,9);
        $msg = view("message_template.register_message",
                    array(
                'data'=>array(
                    'otp'=>$otp
                    )
                    ) )->render();
                $msg=$otp.' is your OTP and is valid for 10 minutes';
          self::msgCurl($mob,$msg);


		$customer_data=DB::table('customers')->where('phone',$mob)->orwhere('email',$mob)->first();


		$email_msg='<tr>
                         <td style="padding:5px 10px;">
                            <strong>OTP</strong>
                         </td>
                         <td style="padding:5px 10px;">
                                  <p><strong></strong>'.$msg.'</p>
                         </td>
                     </tr>';

                    $email_data = [
                        'to'=>$customer_data->email,
                        'subject'=>'OTP',
                         "body"=>view("emails_template.otp",
                             array(
                    	    'data'=>array(
                    	        'message'=>$email_msg
                    	        )
                    	     ) )->render(),
                    	     'phone'=>'',
                    	     'phone_msg'=>''
                         ];
                if($customer_data->email) self::SendmailCustom($email_data);

                $record = DB::table('customer_phone_otp')->where('phone',$mob)->first();


		if($record){
		       DB::table('customer_phone_otp')
                ->where('phone',$mob)
                ->update([
				'otp' =>$otp  ,'expired_on'=>Carbon::now()->addMinutes(10)
				]);
		} else{
			DB::table('customer_phone_otp')->insert(
			[ 'phone'=> $mob, 'otp' =>$otp  ,'expired_on'=>Carbon::now()->addMinutes(10) ,'user_id'=>$data['userId']  ]
			);
		}

		/**self::sendEmail($data);****/


    }
    public static function generate_user_otp($data)
    {


        $mob=$data['phone'];
        // $otp=rand(0,9).rand(0,9).rand(0,9).rand(0,9);
        $otp = "1234";
        $msg = view("message_template.register_message",
                    array(
                'data'=>array(
                    'otp'=>$otp
                    )
                    ) )->render();
                $msg=$otp.' is your Jaldi Kharido OTP and is valid for 10 minutes';
          self::msgCurl($mob,$msg);


		$customer_data=DB::table('customers')->where('phone',$mob)->orwhere('email',$mob)->first();





		$email_msg='<tr>
                         <td style="padding:5px 10px;">
                            <strong>OTP</strong>
                         </td>
                         <td style="padding:5px 10px;">
                                  <p><strong></strong>'.$msg.'</p>
                         </td>
                     </tr>';

                    $email_data = [
                        'to'=>$customer_data->email,
                        'subject'=>'OTP',
                         "body"=>view("emails_template.otp",
                             array(
                    	    'data'=>array(
                    	        'message'=>$email_msg
                    	        )
                    	     ) )->render(),
                    	     'phone'=>'',
                    	     'phone_msg'=>''
                         ];
                if($customer_data->email) self::SendmailCustom($email_data);


        $flag=0;
        if (array_key_exists("flag",$data))
  {
  $flag=$data['flag'];
  }


                $record = DB::table('customer_phone_otp')->where('phone',$mob)->where('flag',$flag)->first();





		if($record){
		       DB::table('customer_phone_otp')
                ->where('phone',$mob)
                ->where('flag',$flag)
                ->update([
				'otp' =>$otp  ,'expired_on'=>Carbon::now()->addMinutes(10)
				]);
		} else{
			DB::table('customer_phone_otp')->insert(
			[ 'phone'=> $mob, 'otp' =>$otp  ,'expired_on'=>Carbon::now()->addMinutes(10) ,'user_id'=>$data['userId'] ,'flag'=> $flag  ]
			);
		}

		/**self::sendEmail($data);****/


    }
   public static function generate_customer_login_otp($data)
    { $mob=$data['Phone'];
		$otp=rand(0,9).rand(0,9).rand(0,9).rand(0,9);
		// $otp = 1234;
		$msg = view("message_template.login_OTP_message",
                    array(
                'data'=>array(
                    'otp'=>$otp
                    )
                    ) )->render();


		$record = DB::table('customer_login_otp')
		->where('user_id',$data['User_id'])
		->first();

		 self::msgCurl($mob,$msg);


        $customer_data=DB::table('customers')->where('id',$data['User_id'])->first();

        $msg = view("message_template.register_message",
                    array(
                'data'=>array(
                    'otp'=>$otp
                    )
                    ) )->render();

	    $email_msg='<tr>
                     <td style="padding:5px 10px;">
                        <strong>OTP</strong>
                     </td>
                     <td style="padding:5px 10px;">
                              <p><strong></strong>'.$msg.'</p>
                     </td>
                 </tr>';

                $email_data = [
                    'to'=>$customer_data->email,
                    'subject'=>'OTP',
                     "body"=>view("emails_template.otp",
                         array(
                	    'data'=>array(
                	        'message'=>$email_msg
                	        )
                	     ) )->render(),
                	     'phone'=>'',
                	     'phone_msg'=>''
                     ];
            // self::SendmailCustom($email_data);

		if($record){

			 DB::table('customer_login_otp')
                	->where('user_id',$data['User_id'])
                ->update([
				'otp' =>$otp  ,'expired_on'=>Carbon::now()->addMinutes(1)
				]);
		} else{

			DB::table('customer_login_otp')->insert(
			[ 'otp' =>$otp  ,'expired_on'=>Carbon::now()->addMinutes(1) ,'user_id'=>$data['User_id']  ]
			);
		}

		/**self::sendEmail($data);****/


    }
	/*******************************/
	public static function convert_number_to_words($number) {

        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'Zero',
            1                   => 'One',
            2                   => 'Two',
            3                   => 'Three',
            4                   => 'Four',
            5                   => 'Five',
            6                   => 'Six',
            7                   => 'Seven',
            8                   => 'Eight',
            9                   => 'Nine',
            10                  => 'Ten',
            11                  => 'Eleven',
            12                  => 'Twelve',
            13                  => 'Thirteen',
            14                  => 'Fourteen',
            15                  => 'Fifteen',
            16                  => 'Sixteen',
            17                  => 'Seventeen',
            18                  => 'Eighteen',
            19                  => 'Nineteen',
            20                  => 'Twenty',
            30                  => 'Thirty',
            40                  => 'Fourty',
            50                  => 'Fifty',
            60                  => 'Sixty',
            70                  => 'Seventy',
            80                  => 'Eighty',
            90                  => 'Ninety',
            100                 => 'Hundred',
            1000                => 'Thousand',
            1000000             => 'Million',
            1000000000          => 'Billion',
            1000000000000       => 'Trillion',
            1000000000000000    => 'Quadrillion',
            1000000000000000000 => 'Quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . Self::convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . Self::convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = Self::convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= Self::convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }
return $string;
	}

	public static function vendor_forgot_password($data)
    {
		$otp=rand(0,9).rand(0,9).rand(0,9).rand(0,9);
		$msg='Hello ,'.$otp.' this is phone otp . Otp is valid for only 10 min.';

        $mob=$data['Phone'];

        $record = DB::table('vendor_admin_forgot_password')->where('Phone',$mob)->first();
       // dd($record);
        $fields='';
        $msg=urlencode($msg);

					$ch = curl_init();
                $user="test"; //your username
                $password="test@123"; //your password
                $mobilenumbers=$mob; //enter Mobile numbers comma seperated
                $message = $msg; //enter Your Message
                $senderid="test"; //Your senderidCURLOPT_URL =>

                $url="https://api-alerts.kaleyra.com/v4/?method=sms&sender=".$senderid."&to=".$mobilenumbers."&message=".$message."&api_key=Ad6cf2841bd5f2bdc1d717c23d11dcdfa";
                //dd($url);
                // curl_setopt( $ch,CURLOPT_URL, $url );

// curl_setopt( $ch,CURLOPT_URL, 'http://sms.b2cmarketing.in/api/swsendSingle.asp?username=t1testb2c&password=48566224&sender=testbc&sendto=91'.$mob.'&message='.$msg );



//                         curl_setopt( $ch,CURLOPT_POST, true );

//                         curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );

//                         curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );

//                         curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );

//                         $result = curl_exec($ch );

        $api_key = '45EDF740109A10';
        $contacts = $mobilenumbers;
        $from = 'ALERTS';
        $sms_text =$message;


        //Submit to server
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, "http://sms.b2chosting.in/app/smsapi/index.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,"key=".$api_key."&routeid=468&type=text&contacts=".$contacts."&senderid=".$from."&msg=".$sms_text);
        $response = curl_exec($ch);
        curl_close($ch);

		if($record){



			 DB::table('vendor_admin_forgot_password')
                    ->where('phone',$data['Phone'])
                    ->where('email',$data['Email'])
                    ->where('email_for',$data['Email_for'])
                ->update([
				'otp' =>$otp  ,'expired_on'=>Carbon::now()->addMinutes(10)
				]);
		} else{
			DB::table('vendor_admin_forgot_password')->insert(
			[
                'email'=> $data['Email'],
                'phone' =>$data['Phone'],
                'otp'=>$otp,
                'email_for'=>$data['Email_for'],
                'expired_on'=>Carbon::now()->addMinutes(10)
			    ]
			);
		}

		/**self::sendEmail($data);****/


    }
	/*******************************/

	public static function checkDeliveryCustom($inputs){

		$checkPincode=DB::table('logistic_vendor_pincode')
			->where('pincode',$inputs['pincode'])
			->where('status',1)
			->where('isdeleted',0)
			->first();

		if(@$checkPincode->pincode!=''){
			$res=json_encode(array('success'=>'found'));
		}else{
			$res=json_encode(array('error'=>'not found'));
		}

        return $res;
    }


}
