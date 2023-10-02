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
use App\Sizechart;
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
       public function filterareaOnCity(Request $request){
        $code=$request->all();
       $cities=DB::table('tbl_area')->where(['city_id'=>$code['city_id'],'status'=>1])->orderBy('name','asc')->get();
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


      public function filterCityOnState(Request $request){
        $code=$request->all();
       $cities=CommonHelper::getCityFromState($code['state_id']);
      $city_html="";
        foreach($cities as $city){
             $city_html.="<option value='$city->name'  data-id='$city->id'>$city->name</option>";
        }
        echo json_encode(
            array(
                "city"=>$city_html,
                "size"=>sizeof($cities)
                )
            );

    }

    public function filterCityIdOnState(Request $request){
        $code=$request->all();
        $cities=CommonHelper::getCityFromState($code['state_id']);
       $city_html="";

      foreach($cities as $city){
          $selected = '';
          if(@$code['city_id'] == $city->id){
              $selected = 'selected';
          }
           $city_html.="<option value='$city->id'  data-id='$city->id' $selected>$city->name</option>";
      }
      echo json_encode(
          array(
              "city"=>$city_html,
              "size"=>sizeof($cities)
              )
          );

  }

    public function filterStateOncountry(Request $request){
        $code=$request->all();
        $countries=CommonHelper::getStateFromCountry($code['country_id']);
        $state_html="";
        foreach($countries as $state){
            $select = '';
            if(@$code['state_id'] == $state->id){
                $select = 'selected';
            }

            $state_html.="<option value='$state->id'  data-id='$state->id' $select>$state->name</option>";
        }
        echo json_encode(
            array(
                "state"=>$state_html,
                "size"=>sizeof($countries),
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
       /*
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
                                   */

            $prd_detail = Products::select('products.*','product_categories.cat_id','vendors.username as seller_name','vendor_company_info.name as companyName')
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->join('vendors','vendors.id','products.vendor_id')
            ->join('vendor_company_info','vendors.id','vendor_company_info.vendor_id')
            ->where('products.id',$input['prd_id'])
            ->first();
            if($prd_detail){

                /**
                 * Product Size Chart based on vendors category
                 */
                $AssignedCategory = DB::table('product_categories')->where('product_id', $input['prd_id'])->orderBy('id','DESC')->first();


                $cat_id = $AssignedCategory->cat_id;
			   $vendorID = $prd_detail->vendor_id;

			   $sizeChartData = Sizechart::where(['vendor_id'=>$vendorID, 'category_id'=> $cat_id])->first();

               $sizeChartURL = '';
			   if(!empty($sizeChartData)){
				$sizeChartURL = asset('uploads/category/size_chart/'.$sizeChartData->sizechart);
			   }

                $html='<img src="'.$sizeChartURL.'" class="img-responsive" alt="sizechart">';
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

   public function product_enquiry(Request $request)
   {
        $input=$request->all();

        $request->validate([
                'quote_name' => 'required|max:255',
                'country_code' => 'required',
                'product_id' => 'required',
                'email' => 'required|email|max:255',
                'phone' => 'required|max:10',
                'qty' => 'required',
                'comment' => 'required|max:500'
            ]);

            $prdDetails = DB::table('products')->where('id',$input['product_id'])->first();
            $vendorDetails = array();

            if(!empty($prdDetails)){
                $vendorDetails = DB::table('vendors')->where('id',$prdDetails->vendor_id)->first();
            }
            $countCode = '+'.''.$input['country_code'];

            DB::table('product_enquiry')->insert(
                [
                    'product_id' => $input['product_id'],
                    'name'=> $input['quote_name'],
                    'email' => $input['email'],
                    'country_code'=> $countCode,
                    'phone' => $input['phone'],
                    'qty' => $input['qty'],
                    'message' => $input['comment'],
            ]);

           $msg='
                        <tr>
                            <td style="padding:5px 10px;">
                                <strong>Product</strong>
                            </td>
                            <td style="padding:5px 10px;">
                                        <p><strong></strong>'.(($prdDetails->name)?$prdDetails->name:'').'</p>
                            </td>
                        </tr>

                        <tr>
                        <td style="padding:5px 10px;">
                            <strong>Quantity</strong>
                        </td>
                        <td style="padding:5px 10px;">
                                    <p><strong></strong>'.$input['qty'].'</p>
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
                                  <p><strong></strong>'.$countCode.''.$input['phone'].'</p>
                         </td>
                     </tr>


                     <tr>
                         <td style="padding:5px 10px;">
                            <strong>Message</strong>
                         </td>
                         <td style="padding:5px 10px;">
                                  <p><strong></strong>'.$input['comment'].'</p>
                         </td>
                     </tr>

               ';



            $data = [
                'to'=>Config::get('constants.email.admin_to'),
                'subject'=>'Query',
                 "body"=>view("emails_template.product_enquiry",
                     array(
				    'data'=>array(
				        'message'=>$msg
				        )
				     ) )->render(),
				     'phone'=>$input['phone'],
				     'phone_msg'=>'Thank you for contacting us'
                 ];

             $responseMsg = CommonHelper::SendmailCustom($data);

             $msg = $responseMsg;
                 if(!empty($vendorDetails)){
                   $data = [
                    'to'=>$vendorDetails->email,
                    'subject'=>'Query',
                     "body"=>view("emails_template.product_enquiry",
                         array(
                        'data'=>array(
                            'message'=>$msg
                            )
                         ) )->render(),
                         'phone'=>$input['phone'],
                         'phone_msg'=>'Thank you for contacting us'
                     ];
                    CommonHelper::SendmailCustom($data);
                 }

    	return response()->json(['responseMsg'=>$responseMsg]);
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
				     'phone_msg'=>'Thank you ('.$input['name'].') for contacting us'
                 ];
                CommonHelper::SendmailCustom($data);
                CommonHelper::SendMsg($data);
			return Redirect::back()->withErrors(['Thanks for contacting us.']);


   }
     public function subscribe(Request $request)
   {
       $input=$request->all();
       // dd($input);
       // $request->validate([
	// 			'subscribe_email' => 'required|email|max:255'
       //      ],[
       //          'subscribe_email.required' => 'Email is required',
       //          'subscribe_email.email' => 'Please enter valid email',
       //          'subscribe_email.max' => 'Email should not be more than 255 characters',
       //      ]);

       $rules = array('subscribe_email' => 'required|email|max:255');
        $messages = [
              'subscribe_email.required' => 'Email is required',
                'subscribe_email.email' => 'Please enter valid email',
                'subscribe_email.max' => 'Email should not be more than 255 characters',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // return Response::json(array(
            //     'status' => false,
            //     'errors' => $validator->getMessageBag()->toArray(),
            // ));
             return response()->json([
                // 'message' => 'You have already subscribed.',
                'status' => false,
                'errors' =>$validator->getMessageBag()->toArray()
            ],201);
        }


    $subscribed=DB::table('subscription')
    ->where('subscription.email',$input['subscribe_email'])
    ->first();
if($subscribed){
     return response()->json([
                'message' => 'You have already subscribed.',
                'status' => true,
                // 'data' => $getStuDataCreate,
            ],201);
     // return Redirect::back()->withErrors(['You have already subscribed']);


											} else{

					$msg='
                     <tr>
                         <td style="padding:5px 10px;">
                            <strong>Email</strong>
                         </td>
                         <td style="padding:5px 10px;">
                                  <p><strong></strong>'.$input['subscribe_email'].'</p>
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
                                                        ['email' => $input['subscribe_email']]
                                                        );
											     // return Redirect::back()->withErrors(['You have subscribed successfully']);

                                                  return response()->json([
                'message' => 'You have subscribed successfully.',
                'status' => true,
                // 'data' => $getStuDataCreate,
            ],200);
											}


   }

   public function check_pinCode(Request $request)
   {

        $minutes=(86400 * 30);
            $code=$request->all();
            $data_inputs=$code;
            try{
                $back_response=CommonHelper::checkDeliveryCustom($data_inputs);

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
                    }else if (array_key_exists("success",$output)){
						$res=array(
							"Error"=>0,
							"Msg"=>"Delivery available in this port code",
							"pincode"=>$code['pincode']
						);
						 $json = json_encode($res);
						setcookie('Seachpincode', $json, time() + ($minutes));
                    }else{
                             $res=array(
                            "Error"=>1,
                            "Msg"=>"Delivery not available in this port code",
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
                    "Msg"=>"Delivery not available in this port code",
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
   public function index2(Request $request)
    {
        echo "hiiii";
    }
    public function index(Request $request)
    {



		$position1=DB::table('tbl_advertise')
					->where('tbl_advertise.advertise_position',1)
					->where('tbl_advertise.status',1)->first();

		$position2=DB::table('tbl_advertise')
					->where('tbl_advertise.advertise_position',2)
					->where('tbl_advertise.status',1)->get();

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

	//	$cities = DB::table('cities')->join('states', 'cities.state_id', '=', 'states.id')->select('cities.id','cities.name')->where('states.country_id','=','101')->orderBy('cities.name','ASC')->get();
    $offers=DB::table('offer_categories')
            ->get();



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
			'whatsmore'=>$whatsmore,
            'offers' => $offers
           ));
    }

    /* new function by jyoti ma'am */

    public function timeslot(Request $request){

        $code=$request->all();
        $date= explode("_",$code['date']);
        $j=1;
       $timeslo=DB::table('tbl_timeslot')->where(['status'=>1])->get();
       $city_html=""; $ccdata = date('d-m-Y');$status='0';
        foreach($timeslo as $trow){

        	$pint= explode("-",$trow->name);
        	$price =($trow->price)?"<i class='fa fa-inr'></i>".$trow->price:'';
        	$price1 =($trow->price)?$trow->price:'0';
        	$chk = ($j==1)?'':'';  //checked=""
          $start=$pint[0];
         $end_time= $pint[1];
          $fixed= "4 PM";

          $timewise=(date("ha")!='11AM')?'':'';
		   //echo date("a");die;
           if($ccdata==$date[1]){

          $start = new \DateTime($start);
          $end = new \DateTime($end_time);
          $start_time = $start->format('H:i');
           $end_time = $end->format('H:i');

          $fixed = new \DateTime($fixed);
          $fixtime_time = $fixed->format('H:i');


           if(strtotime(date('H:i')) >= strtotime($fixtime_time)){
               $city_html='';
                $city_html.='<div class="notdeliveryslot"><p>Oops! No slot available for today</p></div>';
           }


      else if(strtotime(date('H:i')) >= strtotime($start_time) && strtotime(date('H:i')) <= strtotime($end_time)){
      // else if(strtotime(date('H:i')) >= strtotime($start_time) || strtotime(date('H:i')) <= strtotime($end_time)){
       	$city_html.='<li><label class="common-customCheckbox vertical-filters-label">
                    	<input type="radio" class="price-input" name="delivery_time" value="'.$trow->name.'" onclick="timeSelected(this.value,'.$price1.')"  '.$chk.'>
                    	'.$trow->name."  ".$price.
                       '<div class="common-checkboxIndicator"></div></label></li>';
                }
           else if(strtotime(date('H:i')) <= strtotime($start_time) && strtotime(date('H:i')) <= strtotime($end_time)){
      //   else if(strtotime(date('H:i')) >= strtotime($start_time) && strtotime(date('H:i')) <= strtotime($end_time)){
       	$city_html.='<li><label class="common-customCheckbox vertical-filters-label">
                    	<input type="radio" class="price-input" name="delivery_time" value="'.$trow->name.'" onclick="timeSelected(this.value,'.$price1.')"  '.$chk.'>
                    	'.$trow->name."  ".$price.
                       '<div class="common-checkboxIndicator"></div></label></li>';
                }

                else{
                // $city_html.='<div class="notdeliveryslot"><p>Oops! No slot available for today</p></div>';
                }
         //if(strtotime($start_time) <= strtotime($end_time)){
        //else if(strtotime(date('H:i')) >= strtotime($start_time) && strtotime(date('H:i')) <= strtotime($end_time)){
       /* else if(strtotime(date('H:i')) <= strtotime($start_time) && strtotime(date('H:i')) <= strtotime($end_time)){
      //   else if(strtotime(date('H:i')) >= strtotime($start_time) && strtotime(date('H:i')) <= strtotime($end_time)){
       	$city_html.='<li><label class="common-customCheckbox vertical-filters-label">
                    	<input type="radio" class="price-input" name="delivery_time" value="'.$trow->name.'" onclick="timeSelected(this.value,'.$price1.')"  '.$chk.'>
                    	'.$trow->name."  ".$price.
                       '<div class="common-checkboxIndicator"></div></label></li>';
                }*/



          }
         /// not same day
         	else{
			$chk = ($j==1)?'':'';//checked=""
         	$city_html.='<li><label class="common-customCheckbox vertical-filters-label"><input type="radio" class="price-input" name="delivery_time" value="'.$trow->name.'" onclick="timeSelected(this.value,'.$price1.')"  '.$chk.'>'.$trow->name."  ".$price. '<div class="common-checkboxIndicator"></div></label></li>';
			}


          $j++;

        }
        echo json_encode(
            array(
                "timeslot"=>$city_html,
                "status"=>$status,
                "size"=>sizeof($timeslo)
                )
            );

    }


    public function getRazorPayOrderDetails(Request $request){
        $razorPayOrderID = $request->razorpay_order_id;
        $key_id=Config::get('constants.Razorpay.key');
        $key_secret = Config::get('constants.Razorpay.secret');
        $orderUrl = "https://api.razorpay.com/v1/orders/".$razorPayOrderID."/payments";
        $ch_order = curl_init();
        curl_setopt($ch_order, CURLOPT_URL, $orderUrl);
        curl_setopt($ch_order, CURLOPT_USERPWD, $key_id . ':' . $key_secret);
        curl_setopt($ch_order, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch_order, CURLOPT_POST, 0);
        curl_setopt($ch_order, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_order, CURLOPT_SSL_VERIFYPEER, true);
        $result_order = curl_exec($ch_order);
        $data_order_response = json_decode($result_order);
        return response()->json(['data' => $data_order_response,'message'=>"Data Loaded", "status" => true]);

    }

}
