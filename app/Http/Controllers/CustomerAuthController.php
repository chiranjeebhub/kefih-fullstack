<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;
use App\Http\Controllers\Vendors;
use App\Helpers\CustomFormHelper;
use App\Helpers\FIleUploadingHelper;
use App\Helpers\MsgHelper;
use App\Brands;
use App\Vendor;
use App\Orders;
use App\Products;
use App\Customer;
use App\Country;
use App\Wallet;
use App\ProductAttributes;
use App\OrdersDetail;
use App\ProductRating;
use App\ProductCategories;
use App\CheckoutShipping;
use App\OrdersShipping;
use Illuminate\Support\Facades\Hash;
use Redirect;
use Validator;
use DB;
use Config;
use Auth;
use Session;
use View;
use App\Helpers\CommonHelper;
use Carbon\Carbon;
use PDF;
use App;
use URL;
class CustomerAuthController extends Controller
{
   
   public function __construct()
    {
        $this->middleware('auth:customer');
    }

	  	public function myDashboard()
    {
        //return view('fronted.mod_account.my-dashboard',['mydashboard'=>'active']);
		return Redirect::route('accountinfo');
    }
    
    public function productVariations(Request $request){
         
         $input=$request->all();
                $ord=$input['suborder_id'];
                $prd=$input['product_id'];
         $order_details=OrdersDetail::where('id',$input['suborder_id'])->first();
         $prd_data=Products::productDetails($input['product_id']);
        $colors=Products::getProductsAttributes2('Colors',0,$input['product_id']);
        $sizes=Products::getProductsAttributes2('Sizes',0,$input['product_id']);
         $w_sizes=Products::getProductsAttributesWsize(0,$input['product_id']);
        $size_html='';
        $color_html='';
         $w_size_html='';

         $imagespathfolder='uploads/products/'.$prd_data->vendor_id.'/';

        foreach($sizes as $size){
            $attr_name=Products::getAttrName('Sizes',$size['size_id']);
            $class=($order_details->size_id==$size['size_id'])?'active':'';
            $size_html.='<span title="small">
						<a href="javascript:void(0)" style="color:black;" class="badge badge-danger  ordersizeClass '.$class.'"  size_id="'.$size['size_id'].'" prd_id="'.$input['product_id'].'">'.$attr_name.'</a>  
					</span>';
        }
        
         foreach($w_sizes as $size){
            $attr_name=Products::getAttrName('Sizes',$size['women_size_id']);
            $class=($order_details->w_size_id==$size['women_size_id'])?'active':'';
            $w_size_html.='<span title="small">
						<a href="javascript:void(0)" class="badge badge-danger  orderwsizeClass '.$class.'"  w_size_id="'.$size['women_size_id'].'" prd_id="'.$input['product_id'].'">'.$attr_name.'</a>  
					</span>';
        }
        
        foreach($colors as $color){
             
            $color_data=Products::getcolorNameAndCode('Colors',$color['color_id']);
                $colorwise_images=DB::table('product_configuration_images')
                ->where('product_id',$input['product_id'])
                ->where('color_id',$color['color_id'])
                ->first();

                $productskuid=DB::table('product_attributes')->where(['product_id'=>$input['product_id'],'color_id'=>$color['color_id']])->first();
                $imagespathfolder='uploads/products/'.$prd_data->vendor_id.'/'.$productskuid->sku;
                $url = URL::to($imagespathfolder).'/'.$colorwise_images->product_config_image;
                /*
                if($colorwise_images){
                    $url=URL::to('/uploads/products/240-180').'/'.$colorwise_images->product_config_image;
                } else{
                    $url=URL::to('/uploads/color').'/'.$color_data->color_image; 
                }
                */
            $clr_class=($order_details->color_id==$color['color_id'])?'active':'';
            $color_html.='<span title="small">
						<a href="javascript:void(0)" class="ordercolorClass '.$clr_class.'"  color_id="'.$color['color_id'].'" prd_id="'.$input['product_id'].'" prd_type="'.$prd_data->product_type.'" title="'.$color_data->name.'">
						<img src="'.$url.'" height="40" width="40">
						</a>  
					</span>';
        }
            $input='<input type="hidden" value="'.$order_details->color_id.'" name="color_id" id="color_id">';
            $input.='<input type="hidden" value="'.$order_details->size_id.'" name="size_id" id="size_id">';
            $input.='<input type="hidden" value="'.$order_details->w_size_id.'" name="w_size_id" id="w_size_id">';
            $input.='<input type="hidden" value="'.$prd_data->product_type.'" name="product_type" id="product_type">';
            $input.='<input type="hidden" value="'.$ord.'" name="order_id" id="order_id">';
            $input.='<input type="hidden" value="'.$prd.'" name="product_id" id="product_id">';

         $res=array(
            "product_name"=>'Replace '.$order_details->product_name,
            "sizes_html"=>$size_html,
            "color_html"=>$color_html,
            "w_size_html"=>$w_size_html,
            "colors"=>sizeof($colors),
            "sizes"=>sizeof($sizes),
            "w_sizes"=>sizeof($w_sizes),
            "inputs"=>$input,
            "prd_type"=>$prd_data->product_type
         );
         echo json_encode($res);
     }
      public function productVariationSize(Request $request){
		$input=$request->all();
		
		$obj=new Products();
		$prd_data=Products::productDetails($input['prd_id']);
		$data=$obj->getProductsAttributes($input['attr_name'],$input['size_id'],$input['prd_id']);
		$html='';
		foreach($data as $row){
			if($input['attr_name']=='Sizes'){
				$appendto='sizes_html';
				$name=$obj->getAttrName('Sizes',$row['size_id']);
				$html.='<span title="small"><a href="javascript:void(0)" class="badge badge-primary sizeClass" prd_id='.$input['prd_id'].'>'.$name.'</a></span>';
			} else{
                $colorwise_images=DB::table('product_configuration_images')
                ->where('product_id',$input['prd_id'])
                ->where('color_id',$row['color_id'])
                ->first();

                $productskuid=DB::table('product_attributes')->where(['product_id'=>$input['prd_id'],'color_id'=>$row['color_id']])->first();
                $imagespathfolder='uploads/products/'.$prd_data->vendor_id.'/'.$productskuid->sku;
                $url = URL::to($imagespathfolder).'/'.$colorwise_images->product_config_image;


				$appendto='colors_html';
				$color_data=Products::getcolorNameAndCode('Colors',$row['color_id']);
                /*
				  if($colorwise_images){
				      	$url=URL::to("/uploads/products/240-180").'/'.$colorwise_images->product_config_image;
				  } else{
				      	$url=URL::to("/uploads/color").'/'.$color_data->color_image;
				  }*/
			
				$name=$color_data->name;
				$html.='<span title="small"><a class="ordercolorClass" href="javascript:void(0)"
				color_id="'.$row['color_id'].'" 
				prd_id='.$input['prd_id'].' 
				prd_type='.$prd_data->product_type.' 
				title="'.$color_data->name.'"><img src="'.$url.'" height="40" width="40" alt="'.$color_data->name.'"></a></span>';
				//$html.='<span title="small"><a href="javascript:void(0)" class="badge badge-primary colorClass" prd_id='.$input['prd_id'].' color_id='.$row['color_id'].'>'.$name.'</a></span>';
			}
		}
	
		$color_id=(sizeof($data)==1)?$data[0]['color_id']:0;
			    $appendto='replace_model_body_attr_color';
			if($input['attr_name']=='Sizes'){
			    $appendto='sizes_html';
			} 
		$response=array(
				"html"=>$html,
				"color_id"=>$color_id,
				"print_to"=>$appendto
				);
				
				echo json_encode($response);
	}
     public function shippingDetails(Request $request){
         $id=auth()->guard('customer')->user()->id ;
         $ship_address_list = CheckoutShipping::getshippingAddress($id);
          $states=CommonHelper::getState('101');
		return view('fronted.mod_account.shipping_details',['shipping_listing'=>$ship_address_list,'shippingDetails'=>'active','states'=>$states]);
   
    }
    
    	public function editShippingDetailsAddress(Request $request)
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
            'shipping_pincode' => 'required|max:6|min:6',
            'shipping_address_type' => 'required'
            ],[
'shipping_name.required' =>'Name is required',
'shipping_name.max' =>'Name can not exceed to 50 characters',
'shipping_mobile.required' =>'Mobile is required',
'shipping_mobile.max' =>'Mobile can not exceed to 50 characters',
'shipping_address.required' =>'Address is required',
'shipping_address.max' =>'Address can not exceed to 50 characters',
'shipping_address.regex' =>'Shipping Address Must have atleast one digit and one alphabet',
'shipping_pincode.required' =>'Pincode is required',
'shipping_pincode.max' =>'Pincode can not exceed to 50 characters',
'shipping_pincode.min' =>'Pincode field required 6 charecters',
'shipping_state.required' =>'State is required',
'shipping_state.max' =>'State can not exceed to 50 characters',
'shipping_city.required' =>'City is required',
'shipping_city.max' =>'City can not exceed to 50 characters',
'shipping_address1.max' =>'Area can not exceed to 50 characters',
'shipping_address2.max' =>'Landmark can not exceed to 50 characters',
'shipping_address_type.required' =>'Shipping type is required',
             ]
			);
			$states=DB::table('states')->where('id',$input['shipping_state'])->first();
			
			
			$CheckoutShipping = new CheckoutShipping;
			$input_array=
			    	array('shipping_name' => $input['shipping_name'],
			'shipping_mobile' => $input['shipping_mobile'],
			'shipping_address' => $input['shipping_address'],
			'shipping_address1' => $input['shipping_address1'],
			'shipping_address2' => $input['shipping_address2'],
			'shipping_city' => $input['shipping_city'],
			'shipping_state' => @$states->name,
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
			   return Redirect::route('shippingDetails');
		  } else{
			   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			   return Redirect::back();
		  }
		  
			}
		$data=CheckoutShipping::select('customer_shipping_address.*')->where('id',$id)->first();
	 $states=CommonHelper::getState('101');
	 $cities=CommonHelper::getCityFromState($data->shipping_state);
	 
	 $states1=DB::table('states')->where('name',$data->shipping_state)->first();
		//print_r($states1->id);die;
        $cities=CommonHelper::getCityFromState($states1->id);
        
			return view('fronted.mod_account.shipping_address_edit',[
			    'shipping_data'=>$data,
			    'id'=>$id,
			    "states"=>$states,
			     "cities"=>$cities
			    ]);
		

    }
    public function myReviews(Request $request){
         $id=auth()->guard('customer')->user()->id ;
        $ratings=ProductRating::
            select('product_rating.*','products.name')
            ->join('products','products.id','product_rating.product_id')
            ->where('user_id',$id)->paginate(9);
            
      return view('fronted.mod_account.myReview',['reviews'=>'active','ratings'=>$ratings]);
    }
    public function sellerRating(Request $request){
         $input=$request->all();
        
         
         $request->validate([
				'rating' => 'required'
            ]);
          $id=auth()->guard('customer')->user()->id ;
           $res=DB::table('product_rating')
           ->where('user_id',$id)
           ->where('product_id',$input['prd_id'])
           ->first();
         if(!$res){
             $dt=Products::productDetails($input['prd_id']);
            
           $data=array(
                    'vendor_id'=>$dt->vendor_id,
                    'user_name'=>auth()->guard('customer')->user()->name ,
                    'rating'=>$input['rating'],
                    'user_id'=>$id
                    );
            DB::table('vendor_rating')->insert($data);
            return Redirect::back()->withErrors(['Seller rating saved']);
         } else{
             return Redirect::back()->withErrors(['Seller Rating already given']);
         }
        
        
    }
    public function addReview(Request $request){
         $input=$request->all();
         
         $request->validate([
                'review' => 'max:255',
				'rating' => 'required'
            ]);
          $id=auth()->guard('customer')->user()->id ;
           $res=DB::table('product_rating')
           ->where('user_id',$id)
           ->where('product_id',$input['prd_id'])
           ->first();
         if(!$res){
             $file='';
             if ($request->hasFile('file')) {
		
				$logo_image = $request->file('file');
				$destinationPath =Config::get('constants.uploads.review_file');
				$file_name=$logo_image->getClientOriginalName();
 $file_name= FIleUploadingHelper::UploadImage($destinationPath,$logo_image,$file_name);
         if($file_name==''){
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
		    return Redirect::back()->withErrors(['File not uploaded']);;
      }
		
       $file=$file_name;
  }
           $data=array(
                    'product_id'=>$input['prd_id'],
                    'user_name'=>auth()->guard('customer')->user()->name ,
                    'rating'=>$input['rating'],
                    'user_id'=>$id,
                    'uploads'=>$file,
                    'review'=>$input['review']
                    );
            DB::table('product_rating')->insert($data);
             return Redirect::back()->withErrors(['Rating saved']);
         } else{
              return Redirect::back()->withErrors(['Rating already  given']);
         }
       
        
    }
	public function accountInfo(Request $request)
    {
        if ($request->isMethod('post')) {
            $input=$request->all();
            // dd($input);
             $gndr='';
                if (array_key_exists("gender",$input))
                {
              	$request->validate([
        'address' => 'max:110',
        //'city' => 'max:25',
        //'state' => 'max:25',
        'pincode' => 'max:6',
        'gender' => '',
        'dob' => '',
        'name'=>'max:255'
            ]
        );
         $gndr=$input['gender'];
                } else{
                    	$request->validate([
        'address' => 'max:110',
        //'city' => 'max:25',
       // 'state' => 'max:25',
        'pincode' => 'max:6',
        'dob' => '',
        'name'=>'max:255'
            ]
        );
                }

            
        
        $file_name='';
          if ($request->hasFile('profile_pic')) {
		
				$logo_image = $request->file('profile_pic');
				$destinationPath =Config::get('constants.uploads.customer_profile_pic');
				$file_name=$logo_image->getClientOriginalName();
 $file_name= FIleUploadingHelper::UploadImage($destinationPath,$logo_image,$file_name);
         if($file_name==''){
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
		    return Redirect::back();
      }
		

  }
$id=auth()->guard('customer')->user()->id ;
               $states1=DB::table('states')->where('id',@$input['state'])->first();
                
                 if($file_name!=''){
                     $data=array(
                        'address'=>@$input['address'],
                        'city'=>@$input['city'],
                        'state'=>@$states1->name,
                        'pincode'=>@$input['pincode'],
                        'gender'=>$gndr,
                        'dob'=>@$input['dob'],
                        'profile_pic'=>$file_name
                    );
                 } else{
                    $data=array(
                        'address'=>@$input['address'],
                        'country'=>@$input['country'],
                        'city'=>@$input['customer_city'],
                        'state'=>@$input['state'],
                        'pincode'=>@$input['pincode'],
                        'gender'=>$gndr,
                        'dob'=>@$input['dob'],
                        'name'=>@$input['name'],
                        'email'=>@$input['email'],
                        'profile_type'=>@$input['profile_type'],
                        

                    ); 
                 }
                
                    
            Customer::where('id',$id)->update($data);

            /**
             * Execute during mobile number chage.
             */
            if($request->phone != auth()->guard('customer')->user()->phone){
                Session::put('changetoMobileNumber', $request->phone);

                Session::put('customer_details', ['Phone'=>auth()->guard('customer')->user()->phone,'User_id' => $id]); 

                $custmor_array=array(
                    "flag" => 3,
                    "phone"=>auth()->guard('customer')->user()->phone,
                    "userId"=>auth()->guard('customer')->user()->id
                    );
               CommonHelper::generate_user_otp($custmor_array);

                return Redirect::route('change-phone')->with('success','OTP sent to '.$request->phone);
            }




  return Redirect::route('accountinfo')->withErrors(['Profile Updated']);;
        }
        $states=CommonHelper::getState('101');
         $states1=DB::table('states')->where('name',auth()->guard('customer')->user()->state)->first();
		//print_r($states1->id);die;
        $cities=CommonHelper::getCityFromState(@$states1->id);
        $countries=Country::get();
        return view('fronted.mod_account.account-info',['accountinfo'=>'active','states'=>$states,'cities'=>$cities,'countries'=>$countries]);
    }

    public function changePhoneVerify(Request $request){
        $Newphone = '';

        $id=auth()->guard('customer')->user()->id ;
        if ($request->session()->has('changetoMobileNumber')) {
            $Newphone =   Session::get('changetoMobileNumber');           
        }

        if ($request->isMethod('post') && !empty($Newphone)) {
            $input=$request->all();
            $request->validate([               
                'OTP'=>'required'
                    ]
                );

                /**
                 * Verify correct OTP 
                 */

               $flag=3;         // For change mobilen no. otp

               $record = DB::table('customer_phone_otp')->where('user_id',$id)->where('phone',auth()->guard('customer')->user()->phone)->where('flag',$flag)->first();


               if($record->otp == $request->OTP){
                $isUpdated = Customer::where(['id' => $id])->update(['phone' => $Newphone]);
                    if($isUpdated){

                        Session::forget('changetoMobileNumber');
                        Session::forget('customer_details');

                        return Redirect::route('accountinfo')->withErrors(['Mobile Number Changed Successfully']);
                    }else{
                        return Redirect::route('change-phone')->withErrors(['Something Went Wrong, Try Later.']);
                    }
               }else{
                return Redirect::route('change-phone')->withErrors(['Invalid OTP']);
            }
        }


        return view('fronted.mod_customer.change-phone-otp',compact('Newphone'));

    }
	
	public function changepassword(Request $request)
    {
        
         $mail_data = [
		'message' => 'This is a test!',
		'to' => 'yogendra@b2cmarketing.in',
		'phone' => '7017734526',
		'from' => 'vendor@mailinator.com',
		'cc' => 'vendor@mailinator.com',
		'bcc' => 'vendor@mailinator.com',
		'replyTo' => 'vendor@mailinator.com',
		'subject'=>'Email Otp',
		'from_name'=>'yogendra verma',
		'to_name'=>'yogendra verma',
		'method'=>'changePassword'
		];

	
			//CommonHelper::changedPassword($mail_data);
         if ($request->isMethod('post')) {
             	$input=$request->all();
             	
             	$request->validate([
        'current_password' => 'required|max:25',
        'new_password' => 'required|max:25|min:6',
        'confirm_password' => 'required|max:25|min:6',
        'confirm_password' => 'required_with:new_password|same:new_password'
            ],[
                'current_password.required' => 'Current password is required',
                'current_password.max' => 'Current password can not exceed to 25 character',
                'new_password.required' => 'New password is required',
                'new_password.min' => 'The new password must be at least of 6 characters.',
                'new_password.max' => 'New password can not exceed to 25 character',
                'confirm_password.required_with' => 'Confirm password is required',
                'confirm_password.min' => 'The Confirm password must be at least of 6 characters.',
                'confirm_password.max' => 'Confirm password can not exceed to 25 character',
                'confirm_password.same' => 'Confirm Password does not match with the new password.',
            ]
        );

        if($input['current_password'] == $input['new_password']){
            return Redirect::route('changepass')->withErrors(['Current password and new password can not be same. Choose any other strong password.']);
        }

        


		 	$id=auth()->guard('customer')->user()->id ;
		 $data=Customer::select('password')->where('id',$id)->first();
	
            if ($data && Hash::check($input['current_password'], $data->password)) {
                 Customer::where('id',$id)->update(array('password'=>Hash::make($input['new_password'])));
                 $customer_data=Customer::where('id',$id)->first();
                 
                 $msg='Dear '.$customer_data->name.' '.$customer_data->last_name.'  you have succuessfully change your password';
                    
                    $email_msg='<tr>
                    <td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
                    <p>Hi '.$customer_data->name.' '.$customer_data->last_name.'</p>
                    <p>succuessfully change your passowrd</p>
                    </td>
                    </tr>';
            
            
	           $email_data = [
                            'to'=>$customer_data->email,
                            'subject'=>'Change password',
                            "body"=>view("emails_template.forget_password",
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
             
               return Redirect::route('changepass')->withErrors(['password is changed']);
            } else{
            return Redirect::route('changepass')->withErrors(['Current password does not match']);
            }

         }
        return view('fronted.mod_account.change-password',['changepass'=>'active']);
    }
	
	public function orderlist(Request $request)
    {	 $id=auth()->guard('customer')->user()->id ;
         $type=base64_decode($request->type);
        
         switch($type){
                case 0:
                    $orders=Orders::select('orders.*',
                           'order_details.product_price as grand_total',
                           'order_details.product_id',
'order_details.product_price as details_price',
'order_details.product_qty as  details_qty',
'order_details.order_shipping_charges as  details_shipping_charges',
'order_details.order_cod_charges as  details_cod_charges',
'order_details.order_coupon_amount as  details_cpn_amt',
'order_details.order_wallet_amount as  details_wlt_amt',
 'order_details.order_date as order_details_date',
 'order_details.order_detail_return_date',

                           'order_details.id as order_details_id',
                              'order_details.suborder_no as suborder_no',
						   'order_details.product_qty as qty',
							DB::raw("( SELECT COUNT(*) 
							FROM order_details where order_id=orders.id and order_status=1) as  isInvoiceGenerate ")
                    )
                    ->join('order_details','orders.id','order_details.order_id')
                 
                       ->where('orders.customer_id',$id)
                        ->where(function($query){
                        $query->where('order_details.order_status',0);
                        $query->orwhere('order_details.order_status',1);
                        $query->orwhere('order_details.order_status',2);
                        
                        })
					->orderBy('order_details.id','desc')->paginate(10);
                break;
                
                 case 3:
                      $orders=Orders::select('orders.*',
                           'order_details.product_price as grand_total',
						   'order_details.product_qty as qty',
                            'order_details.product_price as details_price',
                            'order_details.product_qty as  details_qty',
                            'order_details.order_shipping_charges as  details_shipping_charges',
                            'order_details.order_cod_charges as  details_cod_charges',
                            'order_details.order_coupon_amount as  details_cpn_amt',
                            'order_details.order_wallet_amount as  details_wlt_amt',
						    'order_details.id as order_details_id',
						     'order_details.order_date as order_details_date',
						     'order_details.suborder_no as suborder_no',
                             'order_details.order_detail_delivered_date',
							DB::raw("( SELECT COUNT(*) 
							FROM order_details where order_id=orders.id and order_status=3) as  isInvoiceGenerate ")
                    )
                    ->join('order_details','orders.id','order_details.order_id')
                    ->where('order_details.order_status',3)
                    
                    ->where('orders.customer_id',$id)
						->orderBy('order_details.id','desc')->paginate(10);
                break;
                case 5:
                      $orders=Orders::select('orders.*',
                           'order_details.product_price as grand_total',
						   'order_details.product_qty as qty',
                        'order_details.product_price as details_price',
                        'order_details.product_qty as  details_qty',
                        'order_details.order_shipping_charges as  details_shipping_charges',
                        'order_details.order_cod_charges as  details_cod_charges',
                        'order_details.order_coupon_amount as  details_cpn_amt',
                        'order_details.order_wallet_amount as  details_wlt_amt',
                        'order_details.order_detail_return_date',
                        'order_details.product_id',
						    'order_details.id as order_details_id',
						     'order_details.suborder_no as suborder_no',
						      'order_details.order_date as order_details_date',
							DB::raw("( SELECT COUNT(*) 
							FROM order_details where order_id=orders.id and order_status=1) as  isInvoiceGenerate ")
                    )
                    ->join('order_details','orders.id','order_details.order_id')
                    ->where('order_details.order_status',5)
                    
                    ->where('orders.customer_id',$id)
					->orderBy('order_details.id','desc')->paginate(10);
                break;
                
                case 4:
                    $orders=Orders::select(
                        'order_details.suborder_no as order_no',
                        'orders.id',
                         'order_details.id as order_details_id',
                          'order_details.suborder_no as suborder_no',
                        'order_details.product_price as grand_total',
						'order_details.product_qty as qty',
						 'order_details.order_date as order_details_date',
                        'orders.payment_mode',
                        'orders.order_date',
                        'orders.id as master_id',
                        'order_details.order_detail_cancel_date',

                        'order_details.product_price as details_price',
'order_details.product_qty as  details_qty',
'order_details.order_shipping_charges as  details_shipping_charges',
'order_details.order_cod_charges as  details_cod_charges',
'order_details.order_coupon_amount as  details_cpn_amt',
'order_details.order_wallet_amount as  details_wlt_amt',
                    )
                    ->join('order_details','orders.id','order_details.order_id')
                    ->where('order_details.order_status',$type)->where('orders.customer_id',$id)
					->orderBy('orders.id','desc')->paginate(10);

                  
                break;
         }
       
   
        return view('fronted.mod_account.orders',['myorder'=>'active','orders'=>$orders]);
    }
    
    public function order_filter(Request $request)
    {	 $id=auth()->guard('customer')->user()->id ;
         $type=base64_decode($request->type);
         $level=base64_decode($request->level);
      
 
         switch($type){
                case 0:
                    $orders=Orders::select('orders.*',
                           'order_details.product_price as grand_total',
                           
'order_details.product_price as details_price',
'order_details.product_qty as  details_qty',
'order_details.order_shipping_charges as  details_shipping_charges',
'order_details.order_cod_charges as  details_cod_charges',
'order_details.order_coupon_amount as  details_cpn_amt',
'order_details.order_wallet_amount as  details_wlt_amt',
 'order_details.order_date as order_details_date',
                           'order_details.id as order_details_id',
                              'order_details.suborder_no as suborder_no',
						   'order_details.product_qty as qty',
							DB::raw("( SELECT COUNT(*) 
							FROM order_details where order_id=orders.id and order_status=1) as  isInvoiceGenerate ")
                    )
                    ->join('order_details','orders.id','order_details.order_id')
                 
                       ->where('orders.customer_id',$id)
                        ->where(function($query){
                        $query->where('order_details.order_status',0);
                        $query->orwhere('order_details.order_status',1);
                        $query->orwhere('order_details.order_status',2);
                        
                        });
                        //  level 1=> last 15 order , 2=> last 1 month , 3=> 6 months , 4=> last year
                          switch($level){
                              case 1:
                                    $orders= $orders->offset(0)
                                    ->limit(15);
                                break;
                                
                                case 2:
                                  
                                    $orders= $orders->whereMonth('orders.order_date','=', Carbon::now()->subMonth()->month);  
                                break;
                                
                                case 3:
                                       $orders= $orders->whereMonth('orders.order_date', '=',(new \Carbon\Carbon)->submonths(5)); 
                                break;
                                
                                case 4:
                                      $orders= $orders->whereDate('orders.order_date','=', Carbon::now()->subDays(365));  
                                break;
                                
                               
                         }
					  $orders=$orders->orderBy('order_details.id','desc')->paginate(10);
                break;
                
                 case 3:
                      $orders=Orders::select('orders.*',
                           'order_details.product_price as grand_total',
						   'order_details.product_qty as qty',
                            'order_details.order_detail_delivered_date',
                            'order_details.product_price as details_price',
                            'order_details.product_qty as  details_qty',
                            'order_details.order_shipping_charges as  details_shipping_charges',
                            'order_details.order_cod_charges as  details_cod_charges',
                            'order_details.order_coupon_amount as  details_cpn_amt',
                            'order_details.order_wallet_amount as  details_wlt_amt',
						    'order_details.id as order_details_id',
						    'order_details.order_date as order_details_date',
						     'order_details.suborder_no as suborder_no',
							DB::raw("( SELECT COUNT(*) 
							FROM order_details where order_id=orders.id and order_status=1) as  isInvoiceGenerate ")
                    )
                    ->join('order_details','orders.id','order_details.order_id')
                    ->where('order_details.order_status',3)
                    
                    ->where('orders.customer_id',$id);
                   
                      switch($level){
                              case 1:
                                    $orders= $orders->offset(0)
                                    ->limit(15);
                                break;
                                
                                case 2:
                                 
                                    $orders= $orders->whereDate('order_details.order_date','>', Carbon::now()->subDays(30));  
                                break;
                                
                                case 3:
                                       $orders= $orders->whereDate('order_details.order_date','>', Carbon::now()->subDays(180)); 
                                break;
                                
                                case 4:
                                      $orders= $orders->whereDate('order_details.order_date','>', Carbon::now()->subDays(365));  
                                break;
                                
                               
                         }
					  $orders=$orders->orderBy('order_details.id','desc')->paginate(10);
                break;
                case 5:
                      $orders=Orders::select('orders.*',
                           'order_details.product_price as grand_total',
						   'order_details.product_qty as qty',
                        'order_details.product_price as details_price',
                        'order_details.product_qty as  details_qty',
                        'order_details.order_shipping_charges as  details_shipping_charges',
                        'order_details.order_cod_charges as  details_cod_charges',
                        'order_details.order_coupon_amount as  details_cpn_amt',
                        'order_details.order_wallet_amount as  details_wlt_amt',
						    'order_details.id as order_details_id',
						     'order_details.order_date as order_details_date',
						     'order_details.suborder_no as suborder_no',
							DB::raw("( SELECT COUNT(*) 
							FROM order_details where order_id=orders.id and order_status=1) as  isInvoiceGenerate ")
                    )
                    ->join('order_details','orders.id','order_details.order_id')
                    ->where('order_details.order_status',5)
                    
                    ->where('orders.customer_id',$id);
                    switch($level){
                              case 1:
                                    $orders= $orders->offset(0)
                                    ->limit(15);
                                break;
                                
                                case 2:
                                    $orders= $orders->whereMonth('orders.order_date','=', Carbon::now()->subMonth()->month);  
                                break;
                                
                                case 3:
                                       $orders= $orders->whereMonth('orders.order_date', '=',(new \Carbon\Carbon)->submonths(5)); 
                                break;
                                
                                case 4:
                                      $orders= $orders->whereDate('orders.order_date','=', Carbon::now()->subDays(365));  
                                break;
                                
                               
                         }
					  $orders=$orders->orderBy('order_details.id','desc')->paginate(10);
                break;
                
                case 4:
                    $orders=Orders::select(
                        'order_details.suborder_no as order_no',
                        'order_details.id',
                         'order_details.id as order_details_id',
                          'order_details.suborder_no as suborder_no',
                        'order_details.product_price as grand_total',
						'order_details.product_qty as qty',
						 'order_details.order_date as order_details_date',
                        'orders.payment_mode',
                        'orders.order_date',
                        'orders.id as master_id'
                    )
                    ->join('order_details','orders.id','order_details.order_id')
                    ->where('order_details.order_status',$type)
                       ->where('orders.customer_id',$id);
				switch($level){
                              case 1:
                                    $orders= $orders->offset(0)
                                    ->limit(15);
                                break;
                                
                                case 2:
                                    $orders= $orders->whereMonth('orders.order_date','=', Carbon::now()->subMonth()->month);  
                                break;
                                
                                case 3:
                                       $orders= $orders->whereMonth('orders.order_date', '=',(new \Carbon\Carbon)->submonths(5)); 
                                break;
                                
                                case 4:
                                      $orders= $orders->whereDate('orders.order_date','=', Carbon::now()->subDays(365));  
                                break;
                                
                               
                         }
					  $orders=$orders->orderBy('order_details.id','desc')->paginate(10);
                break;
         }
       
   
        return view('fronted.mod_account.orders',['myorder'=>'active','orders'=>$orders]);
    }
	
	public function oldorderdetail(Request $request){
	    
        $order_id=base64_decode($request->order_id);
              $suborder=OrdersDetail::select('order_details.*','products.default_image')
              ->join('orders','orders.id','order_details.order_id')
              ->join('products','order_details.product_id','products.id')
              ->where('order_details.id',$order_id)
              ->get();
              
            //   echo "<pre>";
            //   print_r($suborder);
            //   die();
              
            $master_order=Orders::select('orders.*')->where('id',$suborder[0]->order_id)->first();
            
            // $sub_orders=DB::table('order_details')->select('order_details.*','products.default_image')
            // ->join('products','order_details.product_id','products.id')
            // ->where('order_id',$order_id)
            // ->groupBy('order_details.id')
            // ->get();
            
        return view('fronted.mod_account.my-orders-details',[
            'myorder'=>'active',
            'master_order'=>$master_order,
            'sub_orders'=>$suborder
            ]);
    }
    public function orderdetail(Request $request){
	    
       $order_id=base64_decode($request->order_id);
         $order_details_id=base64_decode($request->order_details_id);
       

            $master_order=Orders::select('orders.*')->where('id',$order_id)->first();
            
            $sub_orders=DB::table('order_details')->select('order_details.*','products.default_image')
            ->join('products','order_details.product_id','products.id')
             ->where('order_id',$order_id)
            ->where('order_details.id','!=',$order_details_id)
            ->groupBy('order_details.id')
            ->get();
            
            
             $sub_main_order=DB::table('order_details')->select('order_details.*','products.default_image')
            ->join('products','order_details.product_id','products.id')
            ->where('order_details.id',$order_details_id)
            ->groupBy('order_details.id')
            ->get();
            
        return view('fronted.mod_account.my-orders-details',[
            'myorder'=>'active',
            'master_order'=>$master_order,
            'sub_orders'=>$sub_orders,
             'sub_main_order'=>$sub_main_order
            ]);
    }


    public function downloadServiceInvoice(Request $request){
        $id = base64_decode($request->id);
        $type = $request->type;
	    $page_details=array(
            "Title"=>"Service Invoice",
            "Box_Title"=>"Service Invoice"
        );
       $serviceChargeTax =  DB::table('store_info')->select('service_charge_tax')->where('id',1)->first(); 
       $Order =Orders::select(
                         'orders.order_no',
                         'orders.order_date',
                         'orders.updated_at',
                         'orders.id',
                         'orders.billing_addresss_id',
                         'orders.service_charge',
                         'orders.service_invoice_num',
                         'orders.service_invoice_date',
                         'orders.deduct_reward_points',
                         'orders.discount_amount',
                         'orders.grand_total',
                         'orders.coupon_percent',
                         'orders.coupon_code',
                         'orders.coupon_amount',
                         'orders.invoice_num',
                         'orders.payment_mode',
                         'orders_shipping.order_shipping_address',
                         'orders_shipping.order_shipping_name',
                         'orders_shipping.order_shipping_phone',
                         'orders_shipping.order_shipping_email',
                         'orders_shipping.order_shipping_zip',
                         'orders_shipping.order_shipping_state',
                         'orders_shipping.order_shipping_city',	
                         'order_details.product_name',
                         'order_details.product_qty',
                         'order_details.product_price',
                         'order_details.size',
                         'order_details.color'
                         )
                     
                     ->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id')
                     ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                     ->where('orders.id',$id)
                     ->first();

                     $billingAddress = [];
                     if(!empty($Order)){
                        $billingAddress = DB::table('orders_billing_addresses')
                        ->where('order_id', $id)
                        ->first();
                     }
                   

                     $pdfData = ['billingAddress'=>$billingAddress,'serviceChargeTax'=>$serviceChargeTax,'Order'=>$Order,'page_details'=>$page_details, 'is_front'=>1];
 
                     if($type == 1){
                        $pdf=PDF::loadView('admin.orders.service_invoice',$pdfData);
                        return $pdf->download($Order->order_no.'_service_invoice.pdf'); 
                     }else{
                        return view('admin.orders.service_invoice',['billingAddress'=>$billingAddress,'serviceChargeTax'=>$serviceChargeTax,'Order'=>$Order,'page_details'=>$page_details,'is_front'=>1]);	
                     }                 
     
    }


    public function oldtrack_order(Request $request){
	    
	     $order_id=base64_decode($request->order_id);
              $suborder=OrdersDetail::select('order_details.*','products.default_image')
              ->join('orders','orders.id','order_details.order_id')
              ->join('products','order_details.product_id','products.id')
              ->where('order_details.id',$order_id)
              ->first();

        return view('fronted.mod_account.track_order',[
             'sub_order_details'=>$suborder,
    
            ]);
    }
    public function track_order(Request $request){
	    
        $order_id=base64_decode($request->order_id);
         $order_details_id=base64_decode($request->order_details_id);
            $master_order=Orders::select('orders.*')->where('id',$order_id)->first();
            
            $sub_orders=DB::table('order_details')->select('order_details.*','products.default_image')
            ->join('products','order_details.product_id','products.id')
            ->where('order_id',$order_id)
            ->where('order_details.id','!=',$order_details_id)
            ->groupBy('order_details.id')
            ->get();
            
             $sub_main_order=DB::table('order_details')->select('order_details.*','products.default_image')
            ->join('products','order_details.product_id','products.id')
            ->where('order_details.id',$order_details_id)
            ->groupBy('order_details.id')
            ->get();
            
          
            
         return view('fronted.mod_account.track_order',[
             'suborders'=>$sub_orders,
             'sub_main_order'=>$sub_main_order,
            ]);
    }
	public function cancelorderdetail(Request $request){
	    
        $order_id=base64_decode($request->order_id);
            $master_order=Orders::select('orders.*')->where('id',$order_id)->first();
            
            $sub_orders=DB::table('order_details')->select('order_details.*','products.default_image')
            ->join('products','order_details.product_id','products.id')
            ->where('order_id',$order_id)
			->where('order_status',4)->get();
            
        return view('fronted.mod_account.my-orders-details',[
            'myorder'=>'active',
            'master_order'=>$master_order,
            'sub_orders'=>$sub_orders
            ]);
    }


    public function order_invoice(Request $request){
        $page_details=array(
            "Title"=>"Invoice",
            "Box_Title"=>"Invoice"
          );
       
       $id= base64_decode($request->order_detail_id);
       
       $Order =Orders::select(
                                 'orders.order_no',
                                 'orders.order_date',
                                 'orders.updated_at',
                                 'orders.id',
                                 'orders.payment_mode',
                                 'orders.customer_id',
                                 'orders.shipping_id',
                                 'orders.deduct_reward_points',
                                 'orders.discount_amount',
                                 'orders.grand_total',
                                 'orders.coupon_percent',
                                 'orders.coupon_code',
                                 'orders.coupon_amount',
                                 'orders.invoice_num',
                                 'orders.cod_charges',
                                 'orders_shipping.order_shipping_address',
                                 'orders_shipping.order_shipping_address1',
                                 'orders_shipping.order_shipping_address2',
                                 'orders_shipping.order_shipping_city',
                                 'orders_shipping.order_shipping_state',
                                 'orders_shipping.order_shipping_name',
                                 'orders_shipping.order_shipping_phone',
                                 'orders_shipping.order_shipping_email',
                                 'orders_shipping.order_shipping_zip',
                                 'order_details.id as order_detail_id',
                                 'order_details.product_id',
                                 'order_details.order_detail_invoice_num',
                                 'order_details.order_detail_invoice_date',
                                 'order_details.product_name',
                                 'order_details.suborder_no',
                                 'order_details.product_qty',
                                 'order_details.product_price',
                                 'order_details.product_price_old',
                                 'order_details.order_shipping_charges',
                                     'order_details.order_cod_charges',
                                 'order_details.product_tax',
                                 'order_details.order_deduct_reward_points',
                                 'order_details.order_coupon_amount',
                                     'order_details.order_wallet_amount',
                                 'order_details.size',
                                 'order_details.color',
                                 'order_details.w_size',	'order_details.slot_price'
                                 )
                             
                             ->join('orders_shipping', 'orders.id', '=', 'orders_shipping.order_id')
                             ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                             ->where('order_details.id',$id)
                             ->get()->toarray();
                             
                             $vdr=new Vendor();
                             $vdr_id=$vdr->vendor_id($id,1);
                             
                             $vdr_data=$vdr->getVendorDetails($vdr_id);
                             
 
                             $billingAddress = [];
                             if(!empty($Order)){
                             $billingAddress = DB::table('orders_billing_addresses')
                             ->where('order_id', $Order[0]['id'])
                             ->first();
                             }
                         
                            $pdfData = ['billingAddress'=>$billingAddress,'Order'=>$Order,'page_details'=>$page_details,'vdr_data'=>$vdr_data,'is_frontend'=>1];
                        
                            $pdf=PDF::loadView('vendor.orders.invoice_test',$pdfData);
                            return $pdf->download($Order[0]['suborder_no'].'_product_invoice.pdf'); 
                            
    }

    /*
	public function order_invoice(Request $request){
	    
         $order_id=base64_decode($request->order_id);
         $master_order=Orders::select('orders.*')->where('id',$order_id)->first();
            
		 $cust_info=DB::table('customers')->select('customers.*')
										->where('id',$master_order->customer_id)->first();
		 $ship_info=DB::table('orders_shipping')->select('orders_shipping.*')
										->where('order_id',$order_id)->first();
			
         $sub_orders=DB::table('order_details')->select('order_details.*','products.hsn_code','products.shipping_charges','products.default_image')
            ->join('products','order_details.product_id','products.id')
            ->where('order_id',$order_id)
             ->where('order_details.order_status',1)
            ->get();
            
            $pp=[
					//'myorder'=>'active',
					'master_order'=>$master_order,
					'billing_info'=>$cust_info,
					'shipping_info'=>$ship_info,
					'sub_orders'=>$sub_orders
					];
					
			//if($request->has('download')){
				$pdf=PDF::loadView('fronted.mod_template.order_invoice', $pp);
				return $pdf->download($order_id.'_invoice.pdf'); 
			//}
			
			
        return view('fronted.mod_template.order_invoice',[
            'myorder'=>'active',
            'master_order'=>$master_order,
			'billing_info'=>$cust_info,
			'shipping_info'=>$ship_info,
            'sub_orders'=>$sub_orders
            ]);
            
      
    }
    */
    
     public function return_refund_order(Request $request){
            	
        if ($request->isMethod('post')) {
            	$input=$request->all();             	
              $order_detail_id=$input['master_order_id'];

             	if($input['payment_mode']==0 && $input['return_or_refund']==0){
             	    $request->validate([
                    'order_id.*' => 'required',
					'condition_accepted' => 'required',
                    'reason' => 'required',
                    'return_or_refund' => 'required',
                    'account_holder_name' => 'required_if:return_or_refund,0|required_if:payment_mode,0|max:255',
                    'account_no' => 'required_if:return_or_refund,0|required_if:payment_mode,0|max:255',
                    'bank_name' => 'required_if:return_or_refund,0|required_if:payment_mode,0|max:255',
                    'ifsc_code' => 'required_if:return_or_refund,0|required_if:payment_mode,0|max:255',
                    'branch' => 'required_if:return_or_refund,0|required_if:payment_mode,0|max:255',
                     'remarks' => 'required_if:return_or_refund,0',
            ],[
                    'order_id.*.required'=>'Select product to cancel',
					'reason.required'=>'Reason is required to return order',
                    
                    'condition_accepted.required'=>'Please select Term and condition',
                    'return_or_refund.required'=>'Please select what to want return or refund',
                    
                'account_holder_name.required_if'=>'Need to Fill Account Holder name',
                'account_no.required_if'=>'Need to Fill Account No',
                'bank_name.required_if'=>'Need to Fill Bank name',
                'ifsc_code.required_if'=>'Need to Fill IFSC Code',
                'branch.required_if'=>'Need to Fill Branch',
                'remarks.required_if'=>'Need to give remark'
            ]
			);
             	} else{$request->validate([
                    'order_id.*' => 'required',
					'condition_accepted' => 'required',
                    'reason' => 'required',
                    'return_or_refund' => 'required',
                     'remarks' => 'required_if:return_or_refund,0',
            ],[
                    'order_id.*.required'=>'Select product to cancel',
					'reason.required'=>'Reason is required to return order',
                    
                    'condition_accepted.required'=>'Please select Term and condition',
                    'return_or_refund.required'=>'Please select what to want return or refund',
                    
                'account_holder_name.required_if'=>'Need to Fill Account Holder name',
                'account_no.required_if'=>'Need to Fill Account No',
                'bank_name.required_if'=>'Need to Fill Bank name',
                'ifsc_code.required_if'=>'Need to Fill IFSC Code',
                'branch.required_if'=>'Need to Fill Branch',
                'remarks.required_if'=>'Need to give remark'
            ]
			);
             	    
             	}
             	 
				
			$canceled_orders=array();
			$total_deduct_points=0;
			$order_id='';
			foreach($input['order_id'] as $row){
			  $single_order=array('sub_order_id'=>$row,'reason'=>$input['reason'] ,
			  'type'=>5,
			  'return_type'=>$input['return_or_refund']
			  );

             
                $checkorderShippingCharge =  DB::table('order_details')->select('order_shipping_charges','reverse_order_shipping_charge','order_vendor_shipping_charges')->where('id',$row)->first(); 

                // $reverseCharge = $checkorderShippingCharge->reverse_order_shipping_charge+$checkorderShippingCharge->order_shipping_charges;
   
                $reverseCharge = $checkorderShippingCharge->order_vendor_shipping_charges * 2;

                 DB::table('order_details')->where('id',$row)->update([
                       'order_status'=>5,
                       'return_what'=>$input['return_or_refund'],
                       'order_detail_return_date'=>date('Y-m-d H:i:s'),
                       'reverse_order_shipping_charge' => $reverseCharge 
                     ]);
            
            
			  array_push($canceled_orders,$single_order);
				
				$deduct_points=DB::table('order_details')->where('id',$row)->first();
				 $this->generateMailforCancelAndReturnOrder($row,1);
				
				
				CommonHelper::orderDetailsLog($deduct_points,5);
				
				
					Products::increaseProductQty($deduct_points->product_id,$deduct_points->size_id,$deduct_points->color_id,$deduct_points->product_qty);
				
				$total_deduct_points+=$deduct_points->order_reward_points;
				$order_id=$deduct_points->order_id;
			}
			
			if($total_deduct_points!=0){
				$cust_id=auth()->guard('customer')->user()->id ;
				$wallet=array(
							'fld_customer_id'=>$cust_id,
							'fld_order_id'=>$order_id,
							'fld_order_detail_id'=>0,
							'fld_reward_narration'=>'Returned',
							'fld_reward_deduct_points'=>$total_deduct_points
						);
				
				DB::table('tbl_wallet_history')->insert($wallet);
				
				$master_points=DB::table('customers')->where('id',$cust_id)->first();
				$amt=$master_points->total_reward_points-$total_deduct_points;
				
				DB::table('customers')->where('id',$cust_id)->update(['total_reward_points'=>$amt]);
			}
               $res=DB::table('cancel_return_refund_order')->insert($canceled_orders);
               if($res){
                   if($input['return_or_refund']==1 || $input['return_or_refund']==0){
                       
                       
                                    $order_previous_data=OrdersDetail::where('id',$input['master_order_id'])->first();
                                    $order_previous_msater=Orders::where('id',$order_previous_data->order_id)->first();
                                    $old_price=0;
                                    $prc=0;
                                    
                                    $prd_data=Products::productDetails($order_previous_data->product_id);
                                    
                                        $old_price=$prd_data->price;
                                        $prc=$prd_data->spcl_price;
                                        
                                 if($input['color_id']==0 && $input['size_id']!=0){
                                
                                    $attr_data=DB::table('product_attributes')
                                    ->where('product_id',$order_previous_data->product_id)
                                    ->where('size_id',$input['size_id'])
                                    ->first();
                                    
                                    if($attr_data){
                                       $old_price+=$attr_data->price;
                                    $prc+=$attr_data->price; 
                                    }
                                }
                                if($input['color_id']!=0 && $input['size_id']==0){
                                        $attr_data=DB::table('product_attributes')
                                       ->where('product_id',$order_previous_data->product_id)
                                        ->where('color_id',$input['color_id'])
                                        ->first();
                                       if($attr_data){
                                       $old_price+=$attr_data->price;
                                    $prc+=$attr_data->price; 
                                    }
                                }
                                if($input['color_id']!=0 && $input['size_id']!=0){
                                    $attr_data=DB::table('product_attributes')
                                    ->where('product_id',$order_previous_data->product_id)
                                    ->where('color_id',$input['color_id'])
                                    ->where('size_id',$input['size_id'])
                                    ->first();
                                    if($attr_data){
                                       $old_price+=$attr_data->price;
                                    $prc+=$attr_data->price; 
                                    }
                                    
                                }
                                    
                            $order_detail_for_replace=array(
                            'suborder_no'=>$order_previous_msater->order_no.'_item_'.$order_previous_data->product_id,
                            'order_id'=>$order_previous_data->order_id,
                            'sub_order_id'=>$input['master_order_id'],
                            'product_id'=>$order_previous_data->product_id,
                            'product_name'=>$order_previous_data->product_name,
                            'product_qty'=>$order_previous_data->product_qty,
                            'product_price'=>$prc,
                            'product_price_old'=>$old_price,
                            'size'=> Products::getSizeName($input['size_id']),
                            'color'=> Products::getcolorName($input['color_id']),
                            'size_id'=>$input['size_id'],
                            'w_size_id'=>$input['w_size_id'],
                            'w_size'=>Products::getSizeName($input['w_size_id']),
                            'color_id'=>$input['color_id'],
                            'order_reward_points'=>$order_previous_data->order_reward_points,
                            'order_shipping_charges'=>$order_previous_data->order_vendor_shipping_charges,
                            'order_commission_rate'=>$order_previous_data->order_commission_rate,
                            'return_days'=>$order_previous_data->return_days,
                            
                                'account_holder_name'=>$input['account_holder_name'],
                                'account_no'=>$input['account_no'],
                                'bank_name'=>$input['bank_name'],
                                'ifsc_code'=>$input['ifsc_code'],
                                'branch'=>$input['branch'],
                             
                            'remarks'=>$input['remarks'],
                             'return_type'=>$input['return_or_refund']
                            );
				
			         	$res=DB::table('replace_order_details')->insert($order_detail_for_replace);
                            
                        }
                        $total_order=DB::table('order_details')->where('order_id',$order_id)->count();
                        $cancel_order=DB::table('order_details')->where('order_id',$order_id)->where('order_status',5)->count();
                        if($total_order==$cancel_order){
                           //DB::table('orders')->where('id',$order_id)->update(['order_status'=>5]); 
                        }
                            
                   
                   return Redirect::back()->withErrors(['Your selected order is processing for further operation.']);
               } else{
                    return Redirect::back()->withErrors(['Something went wrong, Please try again after sometime.']);
               }
              
        }
         $order_detail_id=base64_decode($request->order_id);
         
             $sub_orders=DB::table('order_details')->select('order_details.*','products.default_image')
            ->join('products','order_details.product_id','products.id')
            ->where('order_details.id',$order_detail_id)
            ->get();
			
			$master_order=Orders::select('orders.*','orders_shipping.*')
             ->join('orders_shipping','orders.id','orders_shipping.order_id')
            ->where('orders.id',$sub_orders[0]->order_id)->first();
            
        return view('fronted.mod_account.return_refund',[
            'myorder'=>'active',
            'master_order'=>$master_order,
            'sub_orders'=>$sub_orders,
            'master_id'=>$order_detail_id
            ]);
    }
	
    public function cancel_order(Request $request){
      
     
        if ($request->isMethod('post')) {
            	$input=$request->all();
            	
              $order_detail_id=$input['master_order_id'];
             
             	
             	 $request->validate([
					'order_id.*' => 'required',
                    'condition_accepted' => 'required',
                    'reason' => 'required'
            ],[
                    'order_id.*.required'=>'Select product to cancel',
					'reason.required'=>'Reason is required to cancel order',
                    'condition_accepted.required'=>'Please select Term and condition'
            ]
			);
				
			$canceled_orders=array();
			$total_deduct_points=0;
			$order_id='';
			foreach($input['order_id'] as $row){
			    
			  $single_order=array('sub_order_id'=>$row,'reason'=>$input['reason'] ,'type'=>4);
			  DB::table('order_details')->where('id',$row)->update(['order_detail_cancel_date'=>date('Y-m-d H:i:s'),'order_status'=>4]);
			  
			    $this->generateMailforCancelAndReturnOrder($row,0);
			  array_push($canceled_orders,$single_order);
				
				$deduct_points=DB::table('order_details')->where('id',$row)->first();
				
					CommonHelper::orderDetailsLog($deduct_points,3);
					
					Products::increaseProductQty($deduct_points->product_id,$deduct_points->size_id,$deduct_points->color_id,$deduct_points->product_qty);
				
				$total_deduct_points+=$deduct_points->order_reward_points;
				$order_id=$deduct_points->order_id;
			}
			
			if($total_deduct_points!=0){
				$cust_id=auth()->guard('customer')->user()->id ;
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
                   
                   return Redirect::back()->withErrors(['Your selected order is processing for further operation.']);
               } else{
                    return Redirect::back()->withErrors(['Something went wrong, Please try again after sometime.']);
               }
              
        }
        
         $order_detail_id=base64_decode($request->order_id);
             $sub_orders=DB::table('order_details')->select('order_details.*','products.default_image')
            ->join('products','order_details.product_id','products.id')
            ->where('order_details.id',$order_detail_id)
            ->get();
			
			$master_order=Orders::select('orders.*','orders_shipping.*')
             ->join('orders_shipping','orders.id','orders_shipping.order_id')
            ->where('orders.id',$sub_orders[0]->order_id)->first();
            
        return view('fronted.mod_account.cancel-order',[
            'myorder'=>'active',
            'master_order'=>$master_order,
            'sub_orders'=>$sub_orders,
            'master_id'=>$sub_orders[0]->order_id
            ]);

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
                        
                        // echo $email_msg;die;
                        
    //                 $email_msg='<tr>
    //             	<td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
    //             	<p>Hi '.$customer_data->name.' '.$customer_data->last_name.'</p>
    //                 <p>We have received your query for '.$opeation_pr.' order. We will send you an Email and SMS for further process</p>
    //                 <p>
    //                 Order ID: <span style="color:#00bbe6;">'.$master_orders->suborder_no.'</span><br />
    //                 Order Date: <span style="color:#00bbe6;">'.$master_order->order_date.'</span><br />
    //                 Payment Mode: <span style="color:#00bbe6;">'.$mode.'</span>
    //                 </p>
    //             </td>
    //         </tr>
    //         <tr>
    //             <td style="border-bottom:solid 1px #999; border-right:solid 1px #999; padding:0px 10px; width:50%;">
    //             	<p><strong>Billing Info</strong><br />
    //                         '.$shipping_data->order_shipping_name.'<br />
    //                         '.$shipping_data->order_shipping_phone.'<br />
    //                         '.$shipping_data->order_shipping_email.'<br />
    //                         '.$shipping_data->order_shipping_address.'<br />
    //                         '.$shipping_data->order_shipping_address1.'<br />
    //                         '.$shipping_data->order_shipping_address2.'<br />
    //                         '.$shipping_data->order_shipping_city.'<br />
    //                         '.$shipping_data->order_shipping_state.'<br />
    //                         '.$shipping_data->order_shipping_zip.'<br />
    //                 </p
    //             </td>
    //             <td style="border-bottom:solid 1px #999; padding:0px 10px; width:50%;">
    //             	<p><strong>Pick up Info</strong><br />
    //                 '.$shipping_data->order_shipping_name.'<br />
    //                 '.$shipping_data->order_shipping_phone.'<br />
    //                 '.$shipping_data->order_shipping_email.'<br />
    //                 '.$shipping_data->order_shipping_address.'<br />
    //                 '.$shipping_data->order_shipping_address1.'<br />
    //                 '.$shipping_data->order_shipping_address2.'<br />
    //                 '.$shipping_data->order_shipping_city.'<br />
    //                 '.$shipping_data->order_shipping_state.'<br />
    //                 '.$shipping_data->order_shipping_zip.'<br />
                        
    //                 </p>
    //             </td>
    //         </tr> 
    //         <tr>
    //         	<td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
    //             	<p>Order Summary</p>
    //             </td>
    //         </tr>';
            
    //         $email_msg.='<tr>
    //         	<td colspan="2">
    //             	<table cellpadding="0" cellspacing="0" style="width:100%; text-align:left; padding:5px 10px;">
    //                   <tr>
    //                     <th style="padding:5px 0px;">S.no.</th>
    //                     <th>Item Name</th>
				// 		<th>Quantity</th>
    //                     <th>Price</th>
				// 		<th>Amt</th>
    //                   </tr>';
                      
    //                   $i=1;
    //                 $email_msg.='<tr>
    //                 <td style="padding:10px 10px 5px; border-bottom:dashed 1px #ccc;">'.$i.'</td>
    //                 <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_name.'('.$master_orders->size.' '.$master_orders->color.')</td>
    //                 <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_qty.'</td>
    //                 <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_price.'</td>
    //                 <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_qty*$master_orders->product_price.'</td></tr>';
                            
                        //---------------------------------------------   
                      
						
						
    //                  $email_msg.='<tr>
    //                     <td style="padding:5px 10px;">&nbsp;</td>
    //                     <td>&nbsp;</td>
    //                     <td>&nbsp;</td>
				// 		<td>&nbsp;</td>
    //                     <td>Total Amount</td>
    //                     <td>hyh</td>
    //                   </tr>
                   
    //               if($master_order->coupon_code!=''){
    //                   $email_msg.='
				// 	    <tr>
				// 		<td colspan="5"><p>Discount Applied Code <strong>'.$master_order->coupon_code.'</strong> to get </p></td>
				// 		<td><strong>'.$master_order->discount_amount.'</strong></td>
				// 	 </tr>';
    //               }
    //               if($master_order->total_shipping_charges!=''){
    //                   $email_msg.='
				// 	    <tr>
				// 		<td colspan="5"><p>Shiiping Charge</td>
				// 		<td><strong>'.$master_order->total_shipping_charges.'</strong></td>
				// 	 </tr>';
    //               }
                   //-----------------------------------  
					 
            //         $email_msg.='<tr bgcolor="#d1d4d1">
            //         <td style="padding:5px 10px;">&nbsp;</td>
            //         <td>&nbsp;</td>
            //         <td>&nbsp;</td>
            //         <td>&nbsp;</td>
            //         <td><strong>Total Amount Rs.:'.$master_orders->product_qty*$master_orders->product_price.' </strong></td>
            //         </tr>';
					  
					
					  
            //         $email_msg.='</table>

            //     </td>
            // </tr>';
            
            
	           //$email_data = [
            //                 'to'=>$customer_data->email,
            //                 'subject'=>$opeation_pr.' Order',
            //                 "body"=>view("emails_template.order_sts_change",
            //                 array(
            //                 'data'=>array(
            //                 'message'=>$email_msg
            //                 )
            //                 ) )->render(),
            //                 'phone'=>$customer_data->phone,
            //                 'phone_msg'=>$msg
            //              ];
            
            
            
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
    
    public function reason_and_policy(Request $request){
         $input=$request->all();
         $html='';
         
         if($input['call_method']==0){
             $reasons=DB::table('order_cancel_reason')
               ->join('reason_category','reason_category.reason_id','order_cancel_reason.id')
               ->join('product_categories','reason_category.category_id','product_categories.cat_id')
                ->where('product_categories.product_id',$input['prd_id'])
                    ->where('order_cancel_reason.reason_type',0)
                    ->groupBy('order_cancel_reason.id')
                        ->get();
                        
                        foreach($reasons as $reason){
                            $html.="<option value='$reason->reason_id'>$reason->reason</option>";
                        }
                    
                
               
             $cats=ProductCategories::select('categories.cancel_description')
                                    ->join('categories','categories.id','product_categories.cat_id')
                                    ->where('product_categories.product_id',$input['prd_id'])
                                    ->first();
                $res=array(
                    "target_policy"=>"cancel_policy",
                    "target_reason"=>"cancel_reason",
                    "policy"=>$cats->cancel_description,
                    "reason"=>$html
                    );
         } else{
             $reasons=DB::table('order_cancel_reason')
               ->join('reason_category','reason_category.reason_id','order_cancel_reason.id')
               ->join('product_categories','reason_category.category_id','product_categories.cat_id')
                ->where('product_categories.product_id',$input['prd_id'])
                    ->where('order_cancel_reason.reason_type',1)
                    ->groupBy('order_cancel_reason.id')
                        ->get();
                        
                        foreach($reasons as $reason){
                            $html.="<option value='$reason->reason_id'>$reason->reason</option>";
                        }
              $cats=ProductCategories::select('categories.return_description')
                                    ->join('categories','categories.id','product_categories.cat_id')
                                    ->where('product_categories.product_id',$input['prd_id'])
                                    ->first();
             $res=array(
                    "target_policy"=>"return_policy",
                    "target_reason"=>"return_reason",
                    "policy"=>$cats->return_description,
                    "reason"=>$html
                    );
         }
         echo json_encode($res); 
        
    }
	
	public function savelater()
    {
		$id=auth()->guard('customer')->user()->id ;
		$data = DB::select("SELECT prd.*,savelater.fld_save_later_id FROM products prd inner join tbl_save_later savelater on savelater.fld_product_id=prd.id where savelater.fld_user_id='".$id."'");
		
		return view('fronted.mod_account.savelater',['savelater'=>'active',"savelater_data"=>$data]);
    }
	
	public function removeSavelaterItem(Request $request){
        $input=$request->all();
      $res=DB::table('tbl_save_later')->where('tbl_save_later.fld_save_later_id',$input['prd_id'])->delete();
      if($res){
          echo json_encode(
              array("Error"=>0)
              );
          
      } else{
           echo json_encode(
              array("Error"=>1)
              );
      }
    }
	
	public function wishlist()
    {
		$id=auth()->guard('customer')->user()->id ;
		$data = DB::select("SELECT prd.*,wishlist.fld_wishlist_id FROM products prd inner join tbl_wishlist wishlist on wishlist.fld_product_id=prd.id where wishlist.fld_user_id='".$id."'");
		
		return view('fronted.mod_account.wishlist',['wishlist'=>'active',"wishlist_data"=>$data]);
    }
    
    public function removeWishlistItem(Request $request){
        $input=$request->all();
      $res=DB::table('tbl_wishlist')->where('tbl_wishlist.fld_wishlist_id',$input['prd_id'])->delete();
      if($res){
          echo json_encode(
              array("Error"=>0)
              );
          
      } else{
           echo json_encode(
              array("Error"=>1)
              );
      }
    }
	
	public function referarls()
    {
        $referals=DB::table('user_referrals')
        ->select('user_referrals.*','customers.name','customers.last_name','customers.email')
        ->join('customers','customers.id','user_referrals.c_id')
        ->where('p_id',auth()->guard('customer')->user()->id)->paginate(100);
        
         $earnings=DB::table('tbl_refer_earn')
        ->select('tbl_refer_earn.*','customers.name','customers.last_name','customers.email','tbl_refer_earn.mode as op')
        ->join('customers','customers.id','tbl_refer_earn.rel_id')
        ->where('user_id',auth()->guard('customer')->user()->id)->paginate(100);
        

        return view('fronted.mod_refers.my_refers',[
		    'refers'=>'active',
		    'referals'=>$referals,
		    'earnings'=>$earnings
		    ]);
    }

    /**
     * Wallet recharge method start 
     */

     public function walletRecharge(Request $request){

        $request->validate([
            'currency' => 'required|max:10',
            'amount' => 'required|numeric|min:0',          
        ]);

        $id=auth()->guard('customer')->user()->id ;
        $userDetails=DB::table('customers')->where('id',$id)->first();

        /**
         * Razor Pay Payment Init
         */
            $amount = $request->amount;

            $productinfo='Wallet Recharge';		
			$total=round($amount)*100;
			$amount=round($amount)*100;
			$card_holder_name=$userDetails->name;            
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
                curl_close($ch_order);
             

            if(!empty($data_order_response->id)){

                /**
                 * Create new recharge record with pending status 
                 */
                $wallerRecharge = new Wallet();
                $wallerRecharge->fld_customer_id = $id;
                $wallerRecharge->fld_reward_points = $request->amount;
                $wallerRecharge->fld_reward_narration = 'Wallet Recharge';
                $wallerRecharge->fld_wallet_date = date('Y-m-d H:i:s');
                $wallerRecharge->razorpay_order_id = $data_order_response->id;
                $wallerRecharge->payment_status = 'pending';
                $wallerRecharge->mode = 5;
                
                if($wallerRecharge->save()){

                    $merchant_order_id = $wallerRecharge->id;
                    $surl=url("/wallet-recharge-success/".base64_encode($merchant_order_id));
                    $furl=url("/wallet-recharge-faild/".base64_encode($merchant_order_id));

                    
                    
                    $html=' <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
                            <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                            <script>
                                var total = "'.$total.'";
                                var merchant_order_id = "'.$merchant_order_id.'";
                                var merchant_surl_id = "'.$surl.'";
                                var merchant_furl_id = "'.$furl.'";
                                var card_holder_name_id = "'.$userDetails->name.'";
                                var merchant_total = total;
                                var merchant_amount = "'.$amount.'";
                                var currency_code_id = "INR";
                                var key_id = "'.$key_id.'";
                                var store_name = "Kefih";
                                var store_description = "Wallet Recharge Payment";
                                var store_logo = "'.asset("public/fronted/images/logo.jpg").'";
                                var email = "'.$userDetails->email.'";
                                var phone = "'.$userDetails->phone.'";
            
                            var razorpay_options = {
                                key: "'.$key_id.'",
                                amount: "'.$total.'",
                                order_id: "'.$data_order_response->id.'",
                                name: "'.$card_holder_name.'",
                                description: "Wallet Recharge # '.$merchant_order_id.'",
                                netbanking: true,
                                currency: "INR",
                                prefill: {
                                name:"'.$userDetails->name.'",
                                email: "'.$userDetails->email.'",
                                contact: "'.$userDetails->phone.'"
                                },
                                notes: {
                                soolegal_order_id: "'.$merchant_order_id.'",
                                },
                                handler: function (transaction) {
                                    var token="'.csrf_token().'";
                                    jQuery.ajax({
                                        url:"'.route("wallet_recharge_callback").'",
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
                                        location.href="'.route("wallet").'"
                                    }
                                }
                            };
                            
                                var objrzpv1 = new Razorpay(razorpay_options);
                                objrzpv1.open();
                                
                            </script>';
                                    
                            echo $html;  
                        }


     }
    }

     /**
      * Wallet recharge method ends
      */

    public function wallet_recharge_callback(Request $request){
        if (($request->razorpay_payment_id!='') && ($request->merchant_order_id!='')) {
            $json = array();
            $razorpay_payment_id = $request->razorpay_payment_id;
            $merchant_order_id = $request->merchant_order_id;
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

                $rechargeAmount = $amount/100;
                $id=auth()->guard('customer')->user()->id ;
                $userDetails=DB::table('customers')->where('id',$id)->first();
                $totalAmount = $userDetails->total_reward_points+$rechargeAmount;
                //User account wallet amount update.
                DB::table('customers')->where('id',$id)->update(['total_reward_points' => $totalAmount]);

                Wallet::where('id', $merchant_order_id)->update(
                    ['payment_status' => 'success','txn_id'=>$request->razorpay_payment_id]
                );           

              if (!$order_info['order_status_id']) {
                  $json['redirectURL'] = $request->merchant_surl_id;
              } else {
                  $json['redirectURL'] = $request->merchant_surl_id;
              }

          } else {
                Wallet::where('id', $merchant_order_id)->update(
                    ['payment_status' => 'failed','txn_id'=>$request->razorpay_payment_id]
                );
                  $json['redirectURL'] = $request->merchant_furl_id;
          }
          $json['msg'] = '';

        } else {
            $json['msg'] = 'An error occured. Contact site administrator, please!';
        }

        header('Content-Type: application/json');
        echo json_encode($json);
    }

    public function walletRechargeSuccess(Request $request){
        $walletID = base64_decode($request->id);
        $walletData = Wallet::where('id', $walletID)->first();         
        return view('fronted.mod_wallet.thank-you',array('walletData'=>$walletData, 'is_success' => true));
    }

    public function walletRechargeFaild(Request $request){
        $walletID = base64_decode($request->id);
        $walletData = Wallet::where('id', $walletID)->first(); 
        return view('fronted.mod_wallet.thank-you',array('walletData'=>$walletData, 'is_success' => false));     
    }





	public function wallet()
    {
		$id=auth()->guard('customer')->user()->id ;
		
		$cust_info=DB::table('customers')->where('id',$id)->first();
		
    $earning=DB::table('tbl_wallet_history')->select('tbl_wallet_history.fld_order_id','tbl_wallet_history.mode','tbl_wallet_history.fld_reward_points','tbl_wallet_history.fld_reward_deduct_points','tbl_wallet_history.fld_reward_narration','tbl_wallet_history.fld_wallet_date')
    ->where('fld_customer_id',$id)
    ->where('fld_reward_points','!=',0)
    ->where('payment_status','success') 
    ->orderBy('tbl_wallet_history.id','desc')->get();
 

    $earning_history=DB::table('tbl_wallet_history')->select('tbl_wallet_history.fld_order_id','tbl_wallet_history.mode','tbl_wallet_history.fld_reward_points','tbl_wallet_history.fld_reward_deduct_points','tbl_wallet_history.fld_reward_narration','tbl_wallet_history.fld_wallet_date')
    ->where('fld_customer_id',$id)
    ->where('fld_reward_points','!=',0)
    ->where('payment_status','success') 
    ->orderBy('tbl_wallet_history.id','desc')->get();
    
    $spend_history=DB::table('tbl_wallet_history')
    ->select('tbl_wallet_history.fld_order_id','tbl_wallet_history.mode','tbl_wallet_history.fld_reward_points','tbl_wallet_history.fld_reward_deduct_points','tbl_wallet_history.fld_reward_narration','tbl_wallet_history.fld_wallet_date')
    ->where('fld_customer_id',$id)
    ->where('fld_reward_deduct_points','!=',0)
    ->orderBy('tbl_wallet_history.id','desc')->get();

    $TotalSpendAmount=DB::table('tbl_wallet_history')
    ->where('fld_customer_id',$id)
    ->where('fld_reward_deduct_points','!=',0)
    ->orderBy('tbl_wallet_history.id','desc')->sum('fld_reward_deduct_points');
 
    
    $wallet_history=DB::table('tbl_wallet_history')->select('tbl_wallet_history.fld_order_id','tbl_wallet_history.fld_reward_points','tbl_wallet_history.fld_reward_deduct_points','tbl_wallet_history.fld_reward_narration','tbl_wallet_history.fld_wallet_date')
    ->where('fld_customer_id',$id)->orderBy('tbl_wallet_history.id','desc')->get();
		
		$current_wallet_history=DB::table('tbl_wallet_history')->select('tbl_wallet_history.fld_order_id','tbl_wallet_history.fld_reward_points','tbl_wallet_history.fld_reward_deduct_points','tbl_wallet_history.fld_reward_narration','tbl_wallet_history.fld_wallet_date')
		/*->join('order_details','order_details.id','tbl_wallet_history.fld_order_detail_id')*/
		->where('fld_customer_id',$id)
        ->where('payment_status','success') 
        ->orderBy('tbl_wallet_history.id','desc')->get();
		
		$wallet_history=DB::table('tbl_wallet_history')->select('tbl_wallet_history.fld_order_id','tbl_wallet_history.fld_reward_points','tbl_wallet_history.fld_reward_deduct_points','tbl_wallet_history.fld_reward_narration','tbl_wallet_history.fld_wallet_date')
		/*->join('order_details','order_details.id','tbl_wallet_history.fld_order_detail_id')*/
		->where('fld_customer_id',$id)->orderBy('tbl_wallet_history.id','desc')->get();
		
		
	
		return view('fronted.mod_wallet.my-wallet',[
		    'wallet'=>'active','cust_info'=>$cust_info,
		    'current_wallet_history'=>$current_wallet_history,
		    'wallet_history'=>$wallet_history,
		    'earnings'=>$earning,
            'TotalSpendAmount'=>($TotalSpendAmount)?$TotalSpendAmount:0,
		    'earning_history'=>$earning_history,
		    'spend_history'=>$spend_history
		    ]);
    }



    public function notifications(Request $request){
        $id=auth()->guard('customer')->user()->id;
        $notifications = DB::table('tbl_notification')->orderBy('id','desc')->paginate(20);

        return view('fronted.mod_account.notifications',compact('notifications'));
    }
}
