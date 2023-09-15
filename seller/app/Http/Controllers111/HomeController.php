<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Redirect;
use Validator;
use DB;
use Config;
use Auth;
use App\Products;
use App\ProductCategories;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response;
use App\Http\Requests;
use Cookie;
use URL;
use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     
    /* public function index()
    {
        return view('home');
    }
    */
    
      public function filterCityOnState(Request $request){
        $code=$request->all();
       $cities=CommonHelper::getCityFromState($code['state_id']);
      $city_html="";
        foreach($cities as $city){
             $city_html.="<option value='$city->name'>$city->name</option>";
        }
        echo json_encode(
            array(
                "city"=>$city_html,
                "size"=>sizeof($cities)
                )
            );
     
    }
    
     public function filterCityOnState_old(Request $request){
        $code=$request->all();
       $cities=CommonHelper::getCityFromState($code['state_id']);
      $city_html="";
        foreach($cities as $city){
             $city_html.="<option value='$city->id'>$city->name</option>";
        }
        echo json_encode(
            array(
                "city"=>$city_html,
                "size"=>sizeof($cities)
                )
            );
     
    }
    public function searchPincode(Request $request){
        $code=$request->all();
            $data=DB::table('logistic_vendor_pincode')
            ->where('logistic_vendor_pincode.pincode','LIKE', '%' . $code['searchPincode'] . '%')
            ->where('status',1)
            ->groupBy('pincode')
            ->get();
            $html='';
            foreach($data as $row){
              $html.='<div class="Pincodedisplaydisplay_box">'.$row->pincode.'</div>';  
            }
            
      echo json_encode(
          array(
              "dataSize"=>sizeof($data),
              "html"=>$html
              )
          );
     
    }
      public function get_size_chart(Request $request)
   {
       $input=$request->all();
       
        $cats=Products::select('product_size_chart')
                                   ->where('id',$input['prd_id'])
                                    ->where('product_size_chart','!=','')
                                    ->first();
                                    if($cats){
                                        $url=URL::to('/uploads/sizechart').'/'.$cats->product_size_chart;
                                        $html='<img src="'.$url.'" class="img-responsive" alt="sizechart">';
                                        $res=array(
                                            "Error"=>0,
                                            "data"=>$html
                                            );
                                    } else{
                                        $html='';
                                            $res=array(
                                            "Error"=>1,
                                            "data"=>$html
                                            );
                                    }
                                   
                                   echo json_encode($res);
   }
   
   public function contact_us(Request $request)
   {
       
       $input=$request->all();
       $request->validate([
                'name' => 'required||max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|max:10',
                'message' => 'required|max:255'                
            ]);
            
           
           $msg='   <tr>
                         <td style="padding:5px 10px;">
                            <strong>Name</strong>
                         </td>
                         <td style="padding:5px 10px;">
                                  <p><strong></strong>'.$input['name'].'</p>
                         </td>
                     </tr>
                     <tr>
                         <td style="padding:5px 10px;">
                            <strong>Email</strong>
                         </td>
                         <td style="padding:5px 10px;">
                                  <p><strong></strong>'.$input['email'].'</p>
                         </td>
                     </tr>
                     <tr>
                         <td style="padding:5px 10px;">
                            <strong>Phone</strong>
                         </td>
                         <td style="padding:5px 10px;">
                                  <p><strong></strong>'.$input['phone'].'</p>
                         </td>
                     </tr>
                   
                      
                     <tr>
                         <td style="padding:5px 10px;">
                            <strong>Message</strong>
                         </td>
                         <td style="padding:5px 10px;">
                                  <p><strong></strong>'.$input['message'].'</p>
                         </td>
                     </tr>

               ';
            
            $data = [
                'to'=>Config::get('constants.email.admin_to'),
                'subject'=>'Query',
                 "body"=>view("emails_template.contact_us",
                     array(
				    'data'=>array(
				        'message'=>$msg
				        )
				     ) )->render(),
				     'phone'=>$input['phone'],
				     'phone_msg'=>'Thank you ('.$input['name'].') for contacting us (phaukat.com)'
                 ];
                CommonHelper::SendmailCustom($data);
                CommonHelper::SendMsg($data);
			return Redirect::back()->withErrors(['Thanks for contacting us.']);
	  
	   
   }
     public function subscribe(Request $request)
   {
       $input=$request->all();
       $request->validate([
				'email' => 'required|email|max:255'
            ]);
            
            	
    $subscribed=DB::table('subscription')
    ->where('subscription.email',$input['email'])
    ->first();
if($subscribed){
     return Redirect::back()->withErrors(['You have already subscribed']);
											     
                                                       
											} else{
					
					$msg='
                     <tr>
                         <td style="padding:5px 10px;">
                            <strong>Email</strong>
                         </td>
                         <td style="padding:5px 10px;">
                                  <p><strong></strong>'.$input['email'].'</p>
                         </td>
                     </tr>';
            
                    $data = [
                        'to'=>Config::get('constants.email.admin_to'),
                        'subject'=>'Subscription',
                         "body"=>view("emails_template.contact_us",
                             array(
                    	    'data'=>array(
                    	        'message'=>$msg
                    	        )
                    	     ) )->render(),
                    	     'phone'=>'',
                    	     'phone_msg'=>''
                         ];
                CommonHelper::SendmailCustom($data);
                
											     DB::table('subscription')->insert(
                                                        ['email' => $input['email']]
                                                        );
											     return Redirect::back()->withErrors(['You have subsribe successfully']);
											}
    
      
      
   }
   
   public function check_pinCode(Request $request)
   {
       
        $minutes=(86400 * 30);
            $code=$request->all();
            $data_inputs=$code;
            try{
                $back_response=CommonHelper::checkDelivery($data_inputs);

                $output = (array)json_decode($back_response);
               
                   if (array_key_exists("couriers",$output))
                    { 
        $days=$output['couriers'][0]->service_types[0]->expected_delivery_days;
                         $date=date('d M Y');
                         $dt=date('d M Y', strtotime($date. ' + '.$days.' days'));
                         
                       $res=array(
                        "Error"=>0,
                        "Msg"=>"Expected Delivery Date: ".$dt."<br>
								Cash/Card on Delivery Available<br>
								Easy 7 Days Return Available",
                        "pincode"=>$code['pincode']
                    );
                     $json = json_encode($res); 
                    setcookie('Seachpincode', $json, time() + ($minutes));
                    }
                    else
                    {
                             $res=array(
                            "Error"=>1,
                            "Msg"=>"Delivery not available in this area",
                            "pincode"=>$code['pincode']
                        );
                        
                        $json = json_encode($res); 
                        setcookie('Seachpincode', $json, time() + ($minutes));
                    }
                    
                 echo json_encode($res);  
            }
            
        catch(Exception $e) {
            $res=array(
                    "Error"=>1,
                    "Msg"=>"Delivery not available in this area m",
                    "pincode"=>$code['pincode']
                );
                
                $json = json_encode($res); 
                setcookie('Seachpincode', $json, time() + ($minutes));
                echo json_encode($res);
        }
         
   }
     public function moreSeller(Request $request)
   {
       $code=base64_decode($request->product_code);
       
       $product_details=DB::table('products')->where('products.product_code',$code)->first();
       
       $all_vendors=DB::table('products')
						->select('products.*','vendors.public_name')
						->join('vendors', 'products.vendor_id', '=', 'vendors.id')
						->where('products.product_code',$code)
						->where('products.status','=',1)
						->where('products.isblocked','=',0)
						->where('products.isexisting',1)
						->where('products.vendor_id','!=',0)
						->get();
       return view('fronted.mod_product.seller-product',
       array(
            'product_details'=>$product_details,
            'all_vendors'=>$all_vendors
           ));
   }
    public function index(Request $request)
    {
     
		$position1=DB::table('tbl_advertise')
					->where('tbl_advertise.advertise_position',1)
					->where('tbl_advertise.status',1)->first();
					
		$position2=DB::table('tbl_advertise')
					->where('tbl_advertise.advertise_position',2)
					->where('tbl_advertise.status',1)->first();
		
		$position3=DB::table('tbl_advertise')
					->where('tbl_advertise.advertise_position',3)
					->where('tbl_advertise.status',1)->first();
		
		$position4=DB::table('tbl_advertise')
					->where('tbl_advertise.advertise_position',4)
					->where('tbl_advertise.status',1)->first();
		
		$position5=DB::table('tbl_advertise')
					->where('tbl_advertise.advertise_position',5)
					->where('tbl_advertise.status',1)->first();
					
		$position6=DB::table('tbl_advertise')
					->where('tbl_advertise.advertise_position',6)
					->where('tbl_advertise.status',1)->first();
		
		$position7=DB::table('tbl_advertise')
					->where('tbl_advertise.advertise_position',7)
					->where('tbl_advertise.status',1)->first();
		
		$position8=DB::table('tbl_advertise')
					->where('tbl_advertise.advertise_position',8)
					->where('tbl_advertise.status',1)->first();
					
		$position9=DB::table('tbl_advertise')
					->where('tbl_advertise.advertise_position',9)
					->where('tbl_advertise.status',1)->first();
		
		$position10=DB::table('tbl_advertise')
					->where('tbl_advertise.advertise_position',10)
					->where('tbl_advertise.status',1)->first();
					
					$position11=DB::table('tbl_advertise')
					->where('tbl_advertise.advertise_position',11)
					->where('tbl_advertise.status',1)->first();
					
		$whatsmore= DB::table('tbl_whatsmore')
								->where('isdeleted', 0)
								->where('status','=',1)
								->orderBy('id', 'DESC')->paginate(100);
					
		
		
		return view('fronted.index',array(
            'advertise_position1'=>$position1,
            'advertise_position2'=>$position2,
            'advertise_position3'=>$position3,
            'advertise_position4'=>$position4,
            'advertise_position5'=>$position5,
            'advertise_position6'=>$position6,
            'advertise_position7'=>$position7,
            'advertise_position8'=>$position8,
            'advertise_position9'=>$position9,
            'advertise_position10'=>$position10,
            'advertise_position11'=>$position11,
			'whatsmore'=>$whatsmore
           ));
    }


	
	
 
}
