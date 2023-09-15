<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Redirect;
use Validator;
use DB;
use Config;
use Auth;
use App\Products;
use App\Brands;
use App\Category;
use App\ProductCategories;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response;
use App\Http\Requests;
use Cookie;
use URL;
use Session;
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
            //  $city_html.="<option value='$city->id'>$city->name</option>";
            
            $city_html.="<option value='$city->name'>$city->name</option>";
        }
        echo json_encode(
            array(
                "city"=>$city_html,
                "size"=>sizeof($cities)
                )
            );
     
    }
    
     public function checkPincodeBefore(Request $request){
       $pincode=Session::get('check_pincode');;
        if($pincode!=''){
            $isFound=DB::table('logistic_vendor_pincode')
                ->select('id')
                ->where('status',1)
                ->where('isdeleted',0)
                 ->where('pincode',$pincode)
                 ->first();
            
            if($isFound){
                    $res=array(
                    "Error"=>0
                    );
            } else{
                    $res=array(
                    "Error"=>1
                    );
            }
       } else{
            $res=array(
           "Error"=>1
           );
       }
      
     echo json_encode($res);
    }
    public function searchPincode(Request $request){
        $code=$request->all();
            $data=DB::table('logistic_vendor_pincode')
             ->select('logistic_vendor_pincode.pincode')
            ->where('logistic_vendor_pincode.pincode','LIKE', '%' . $code['searchPincode'] . '%')
            ->where('status',1)
             ->where('isdeleted',0)
            ->groupBY('logistic_vendor_pincode.pincode')
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
        $cats=ProductCategories::select('categories.size_chart')
                                    ->join('categories','categories.id','product_categories.cat_id')
                                    ->where('product_categories.product_id',$input['prd_id'])
                                    ->where('categories.size_chart','!=','')
                                    ->first();
                                    if($cats){
                                        $url=URL::to('/uploads/category/size_chart').'/'.$cats->size_chart;
                                        $html='<img src="'.$url.'" class="img-responsive" alt="">';
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
                'message' => 'required|max:255',
                'reason' => 'required|max:255'
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
                            <strong>Reason</strong>
                         </td>
                         <td style="padding:5px 10px;">
                                  <p><strong></strong>'.$input['reason'].'</p>
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
				     'phone_msg'=>'Thank you ('.$input['name'].') for contacting us (18UP.in)'
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
					
					$msg='<tr>
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
       $code=$request->all();
	   
	   $request->session()->put('check_pincode', $code['pincode']);
	   
       $data=DB::table('logistic_vendor_pincode')
            ->where('pincode',$code['pincode'])
            ->where('status',1)
       ->first();
       if($data){
            $res=array(
                "Error"=>0,
                "Msg"=>"Delivery available in this area"
                );
       } else{
         $res=array(
                "Error"=>1,
                "Msg"=>"Delivery not available in this area"
                );
       }
     echo json_encode($res);
      
   }
     public function moreSeller(Request $request)
   {
       $code=base64_decode($request->product_code);
       
       $product_details=DB::table('products')->where('products.sku',$code)->first();
       
	    if($request->session()->get('check_pincode')==0 && $request->session()->get('check_pincode')==''){
			
			$all_vendors=DB::table('products')
						->select('products.*','vendors.public_name')
						->join('vendors', 'products.vendor_id', '=', 'vendors.id')
						->where('products.sku',$code)
						->where('products.status','=',1)
						->where('products.isblocked','=',0)
						->where('products.isexisting',1)
						->where('products.vendor_id','!=',0)
						->get();
			
		}else{
			
			$pincode=$request->session()->get('check_pincode');
			
			$all_vendors=DB::table('products')
						->select('products.*','vendors.public_name')
						->join('vendors', 'products.vendor_id', '=', 'vendors.id')
						->join('logistic_vendor_pincode', 'logistic_vendor_pincode.vendor_id', '=', 'vendors.id')
						->where('logistic_vendor_pincode.pincode',$pincode)
						->where('products.product_code',$code)
						->where('products.status','=',1)
						->where('products.isblocked','=',0)
						->where('products.isexisting',1)
						->where('products.vendor_id','!=',0)
						->get();
			
		}
       
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
					
		$whatsmore= DB::table('tbl_whatsmore')
								->where('isdeleted', 0)
								->where('status','=',1)
								->orderBy('id', 'DESC')->paginate(100);
								
			 $coupons=DB::table('coupons')->select(
                        'coupons.coupon_name',
                        'coupons.coupon_type',
                        'coupons.max_discount',
                        'coupons.below_cart_amt',
                        'coupons.above_cart_amt',
                        'coupons.started_date as fld_coupon_validty_start_date',
                        'coupons.end_date as fld_coupon_validty_end_date',
                        'coupons.discount_value as fld_discount_value',
                        'coupons.description',
                        'coupons.id',
                        'coupon_details.coupon_code as fld_coupon_code',
                        DB::raw("CONCAT('".Config::get('constants.Url.public_url')."uploads/coupon_banner/',banner) AS fld_banner_image")
              )
              ->join('coupon_details','coupon_details.coupon_id','coupons.id')
              ->whereIN('coupons.coupon_type',array(0,1,2,3))->where('coupons.status',1)->get();
          $coopon_array=array();
          foreach($coupons as $coupon){
              $is_assign=DB::table('tbl_coupon_assign')->where('fld_coupon_id',$coupon->id)->first();
              $special_for=$coupon->fld_discount_value.' % off';
                $cart_below='';
                $cart_above='';
                $cart_info='';
                  $des='';
              if($is_assign){
                  switch($is_assign->fld_coupon_assign_type){
                            // category 
                             case 1:
                                 $dt=Category::where('id',$is_assign->fld_assign_type_id)->first();
                                $special_for.=' on '.$dt->name;
                            break;
                            
                            // brand 
                            case 2:
                                  $dt=Brands::where('id',$is_assign->fld_assign_type_id)->first();
                                $special_for.=' on '.$dt->name;
                            break;
                            
                             // product 
                            case 3:
                                 $dt=Products::where('id',$is_assign->fld_assign_type_id)->first();
                                $special_for.=' on '.$dt->name;
                            break;
                      
                  }
              } 
              switch($coupon->coupon_type){
                        
                            // static with cart
                             case 1:
                                    $cart_below=$coupon->fld_coupon_validty_start_date;
                                    $cart_above=$coupon->fld_coupon_validty_end_date;
                                    $cart_info='cart total_should be between ('.$coupon->fld_coupon_validty_start_date.' '.$coupon->fld_coupon_validty_end_date.' )';
                             break;
                            
                              // static with cart and date
                            case 3:
                                $cart_below=$coupon->fld_coupon_validty_start_date;
                                $cart_above=$coupon->fld_coupon_validty_end_date;
                                $cart_info='cart total_should be between ('.$coupon->fld_coupon_validty_start_date.' '.$coupon->fld_coupon_validty_end_date.' )';
                            break;
                      
                  }
				      $des=$coupon->description;;
					$single_data['fld_coupon_name']=$coupon->coupon_name;
					$single_data['fld_coupon_attr_name']=$special_for;
					$single_data['fld_coupon_cart_below']=$cart_below;
					$single_data['fld_coupon_cart_above']=$cart_above;
					$single_data['fld_coupon_cart_info']=$cart_info;
					
					
					$single_data['fld_description']=$des;
					$single_data['fld_coupon_code']=$coupon->fld_coupon_code;
					$single_data['fld_coupon_image']=$coupon->fld_banner_image;
            array_push($coopon_array,$single_data);
              
          }					
					
	
		
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
                'whatsmore'=>$whatsmore,
                'coupons'=>$coopon_array
           ));
    }

    
	
	
 
}
