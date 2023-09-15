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
use App\products;
use App\Customer;
use App\CheckoutShipping;
use App\ProductAttributes;
use Illuminate\Support\Facades\Hash;
use Redirect;
use Validator;
use DB;
use Config;
use Auth;
use Session;
use View;
use App\Helpers\CommonHelper;
use App\Helpers\XpressbeesHelper;
use App\Docket;
use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite;
use App\Services;
class CustomerController extends Controller
{
   
    public function dashboard(){
        $user = auth()->guard('customer')->user();
		
		return view('customerDashboard');
		//$user = auth()->guard('customer')->user()->id;
       // dd($user);
    }
	 public function redirect_fb()
    {
        return Socialite::driver('facebook')->redirect();
    }

   public function callback_fb()
    {
        $user = Socialite::driver('facebook')->user();
        $existUser = Customer::where('email',$user->getEmail())->count();
        $validate_user= Customer::select(
						'id',
						'name',
						'email',
						'phone',
						'isOtpVerified',
						'password'
			)->where('email',$user->getEmail())->first();
		if(!empty($validate_user) && $existUser>0){
		    
		    if (Auth::guard('customer')->attempt([ 'phone' => $validate_user->phone,'password' => '12345678']))
    		{
    		    Session::put('shipping_address_id','0');
    		    $res= CheckoutShipping::where('shipping_address_default',1)->where('customer_id',$validate_user->id)->first();
    		   if($res){
    		        dd('4');
    		        Session::put('shipping_address_id','0');
    		   }
    		   return redirect()->to('/'); 	 
                //return redirect()->intended('/');
            }
		}
		else{
            $Customer = new Customer;
        	$Customer->name     = $user->getName();
        	$Customer->facebook_id = $user->id;
        	//$Customer->phone    = '1111111111';
        	$Customer->email    =  $user->getEmail();
        	$Customer->isOtpVerified    =  1;
        	$Customer->password = Hash::make(trim(12345678));
        if($Customer->save()){

		if (Auth::guard('customer')->attempt([ 'email' => $user->getEmail(),'password' => '12345678']))
		{
		return redirect()->to('/'); 	 
		//return redirect()->intended('/');
		}
        	   
        	   //Session::put('Phone','1111111111');
        	   Session::put('User_id',$Customer->id);
        	   Session::put('Flag','0');
        	}
        	
        	
        	
            return redirect()->to('/');
        }
        
    }
    
    public function redirect_gp()
    {
        return Socialite::driver('google')->redirect();
    }
     public function callback_gp()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }
        // only allow people with @company.com to login
       /* if(explode("@", $user->email)[1] !== 'bazzarplus.com'){
            return redirect()->to('/');
        }*/
        // check if they're an existing user
        $existingUser = Customer::where('email', $user->email)->first();
        if($existingUser){
            // log them in
            Auth::guard('customer')->login($existingUser, true);
        } else {
            // create a new user
            $newUser                  = new Customer;
            $newUser->name            = $user->name;
            $newUser->email           = $user->email;
            $newUser->google_id       = $user->id;
            //$newUser->avatar          = $user->avatar;
            //$newUser->avatar_original = $user->avatar_original;
            $newUser->save();
            Auth::guard('customer')->login($newUser, true);
            
            
            if (Auth::guard('customer')->attempt([ 'email' =>  $newUser->email,'password' => '12345678']))
    		{
    		    Session::put('shipping_address_id','0');
    		    $res= CheckoutShipping::where('shipping_address_default',1)->where('customer_id',$validate_user->id)->first();
    		    
    		   if($res){
    		   	dd('4');
    		        Session::put('shipping_address_id','0');
    		      //  Session::put('shipping_address_id','0');
    		   }
    		  return redirect()->to('/');  	 
               // return redirect()->intended('/');
            }
            
            
        }
        return redirect()->to('/');
    }
	
    public function logout(Request $request)
    {
     //dd(auth()->guard('admin')->user());
		Auth::guard('customer')->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        //return redirect()->intended('/');
		return redirect()->intended('/login');
    }
	    public function index()
    {
        return view('fronted.index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

  public function verifiy(Request $request){
		
		$user_data=$request->session()->get('customer_details');
	
		
		 $page_details=array(
       "Title"=>"Customer phone verification",
       "Box_Title"=>"",
       "Action_route"=>route('verifiy'),
       "Form_data"=>array(
         "Form_field"=>array(
           "otp_field"=>array(
			  'label'=>'',
			  'type'=>'text',
			  'name'=>'otp',
			  'id'=>'otp',
			  'classes'=>'form-control',
			  'placeholder'=>'OTP',
			  'value'=>'',
			  'disabled'=>''
		  ),
	     "submit_button_field"=>array(
			  'label'=>'',
			  'type'=>'submit',
			  'name'=>'submit',
			  'id'=>'submit',
			  'classes'=>'registrbtn',
			  'placeholder'=>'',
			  'value'=>'Verify',
			  'disabled'=>''
		  )
         )
       )
     );
	
		if ($request->isMethod('post')) {
			
			 $input=$request->all();
		
			 $request->validate([
					'otp' => 'required|max:6'
            ]
			);
			$record = DB::table('customer_phone_otp')
						->where('user_id',$user_data['User_id'])
						->where('otp',$input['otp'])
						->where('flag',$user_data['Flag'])
						->first();
						if($record){
						    if($record->expired_on<Carbon::now()){
									       return Redirect::back()->withErrors(['Otp expired']);
										} else{
										    $customer_data=DB::table('customers')->where('id',$user_data['User_id'])->first();
                         
                            $msg = view("message_template.customer_registration",
                            	array(
                            	'data'=>array(
                            	'name'=>ucwords($customer_data->name).' '.ucwords($customer_data->last_name),
                            	)
                            ))->render();
							
            
                            $email_data = [
                            'to'=>$customer_data->email,
                            'subject'=>'Registration',
                            "body"=>view("emails_template.customer_register",array(
                    	    'data'=>array(
                    	        'customer_data'=>$customer_data
                    	        )
                    	     ))->render(),
                            'phone'=>$customer_data->phone,
                            'phone_msg'=>$msg
                            ];
                 
                    
                       $admin_email_data = [
                        // 'to'=>Config::get('constants.email.admin_to'),
                        'subject'=>'New User Registration',
                        "body"=>view("emails_template.customer_register_admin_mail",array(
                    	    'data'=>array(
                    	        'customer_data'=>$customer_data
                    	        )
                    	     ))->render(),
                        'phone'=>$customer_data->phone,
                        'phone_msg'=>$msg
                    ];
                    
                 CommonHelper::SendmailCustom($email_data);
                 
                 CommonHelper::SendmailAdminCustom($admin_email_data);
                 
                 CommonHelper::SendMsg($email_data);
		   
													$res=Customer::where('id',$user_data['User_id'])
													->update([
                                                        'isOtpVerified'=>1,
                                                        'status'=>1
													]);
													 MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
													return redirect()->route('customer_login');
										}
									
						} 
						else{
							return Redirect::back()->withErrors(['No record found']);
						}
			
		
		}		
		
			return view('fronted.auth.customer_otp_verification',['page_details'=>$page_details]);
	}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function customer_register(Request $request)
       {
		
	
	   $page_details=array(
       "Title"=>"Customer Registration",
       "Box_Title"=>"",
       "Action_route"=>route('customer_register'),
       "Form_data"=>array(
         "Form_field"=>array(
           "name_field"=>array(
			  'label'=>'',
			  'type'=>'text',
			  'name'=>'name',
			  'id'=>'name',
			  'classes'=>'form-control',
			  'placeholder'=>'Name',
			  'value'=>old('name'),
			  'disabled'=>''
		  ),
	      "phone_field"=>array(
			  'label'=>'',
			  'type'=>'text',
			  'name'=>'phone',
			  'id'=>'phone',
			  'classes'=>'form-control',
			  'placeholder'=>'Phone',
			  'value'=>old('phone'),
			  'disabled'=>''
			  
		  ),
		 "email_field"=>array(
			  'label'=>'',
			  'type'=>'text',
			  'name'=>'email',
			  'id'=>'email',
			  'classes'=>'form-control',
			  'placeholder'=>'Email',
			  'value'=>old('email'),
			  'disabled'=>''
		  ),
		 "password_field"=>array(
			  'label'=>'',
			  'type'=>'password',
			  'name'=>'password',
			  'id'=>'password',
			  'classes'=>'form-control',
			  'placeholder'=>'Password',
			  'value'=>'',
			  'disabled'=>''
		  ),
		   "confirm_password_field"=>array(
			  'label'=>'',
			  'type'=>'password',
			  'name'=>'confirm_password',
			  'id'=>'confirm_password',
			  'classes'=>'form-control',
			  'placeholder'=>'Confirm Password',
			  'value'=>'',
			  'disabled'=>''
		  ),
		   "submit_button_field"=>array(
			  'label'=>'',
			  'type'=>'submit',
			  'name'=>'submit',
			  'id'=>'submit',
			  'classes'=>'registrbtn',
			  'placeholder'=>'',
			  'value'=>'Create an account',
			  'disabled'=>''
		  )
         )
       )
     );
	
		if ($request->isMethod('post')) {
			
			 $input=$request->all();
		
			 $request->validate([
					'name' => 'required|max:50',
                    'phone' =>  'required|regex:/[0-9]{10}/|unique:customers,phone,1,isdeleted|max:50',
                    'email' =>  'required|email|unique:customers,email,1,isdeleted|max:50',
                    'password' => 'required|min:6|max:50',
                    'confirm_password' => 'min:6|required_with:password|same:password',
            ],[
                'phone.required'=>'Please Enter valid Mobile Number',
                'phone.regex'=>'Please Enter valid Mobile Number',
                'email.required'=>'Please Enter valid Email ID',
                'email.email'=>'Please Enter valid Email ID'
              ]
			);
			$Customer = new Customer;
				$Customer->name = $input['name'];
				$Customer->phone = $input['phone'];
				$Customer->email =  $input['email'];
				$Customer->password = Hash::make(trim($input['password']));
	
     
      /* save the following details */
      if($Customer->save()){
		  $request->session()->flush();
		  
		   $request->session()->put('customer_details',array(
			"Phone"=>$input['phone'],
			"User_id"=>$Customer->id,
			"Flag"=>0,
		   ));
				$custmor_array=array(
					"phone"=>$input['phone'],
					"userId"=>$Customer->id
					);
		       CommonHelper::generate_user_otp($custmor_array);
			MsgHelper::save_session_message('success',Config::get('messages.common_msg.vendor_register_success'),$request);
			return redirect()->route('verifiy');
      }  else{
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
				return Redirect::back();
	}

		
		}		
		
			return view('fronted.auth.customer_register',['page_details'=>$page_details]);
		
    }
	
	public function customer_login(Request $request)
    {
	$page_details=array(
       "Title"=>"Customer Login",
	   "Method"=>"1",
       "Box_Title"=>"",
       "Action_route"=>route('customer_login'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "email_field"=>array(
              'label'=>'Email ID',
            'type'=>'text',
            'name'=>'email',
            'id'=>'email',
            'classes'=>'form-control',
            'placeholder'=>'Email',
            'value'=>'',
			'disabled'=>''
           ),
		   "password_field"=>array(
              'label'=>'Paswword',
            'type'=>'password',
            'name'=>'password',
            'id'=>'password',
            'classes'=>' form-control',
            'placeholder'=>'*****',
            'value'=>'',
			'disabled'=>''
           ),
		   "submit_button_field"=>array(
			  'label'=>'',
			  'type'=>'submit',
			  'name'=>'submit',
			  'id'=>'submit',
			  'classes'=>'btn btn-login-register btn-md btn-blue',
			  'placeholder'=>'',
			  'value'=>'login'
		)
         )
       )
     );
		if ($request->isMethod('post')) {
		    
		   
			$input=$request->all();
			 $request->validate([
                'phone' => 'required|max:255',
				'password' => 'required|max:255'
            ]);
			
			
			
		$validate_user= Customer::select(
						'id',
						'name',
						'email',
						'phone',
						'isOtpVerified',
						'password',
						'status'
			)
			 ->where('isdeleted',0)
			->where('phone',trim($input['phone']))
			->orwhere('email',trim($input['phone']))
			->first();

			
if ($validate_user && Hash::check(trim($input['password']), $validate_user->password)) {

	if($validate_user->isOtpVerified!=1){
		
		  $request->session()->flush();
		 $request->session()->put('customer_details',array(
			"Phone"=>$validate_user->phone,
			"User_id"=>$validate_user->id,
			"Flag"=>0,
		   ));
		   
		$custmor_array=array(
					"phone"=>$validate_user->phone,
					"userId"=>$validate_user->id
					);
		       CommonHelper::generate_user_otp($custmor_array);
			
			return redirect()->route('verifiy')->withErrors(['Your phone is not verified.']);
	} else{
	    
	    	if($validate_user->status==0){
		   return Redirect::back()->withErrors(['Your account is disabled , plz contact to admin.']);  
		}
		if (filter_var($input['phone'], FILTER_VALIDATE_EMAIL)) {
				    	if (Auth::guard('customer')->attempt([ 'email' => $request->phone,'password' => $request->password ], $request->get('remember')))
		{
		  //  $request->session()->put('shipping_address_id',0);
		  // $res= CheckoutShipping::where('shipping_address_default',1)->where('customer_id',$validate_user->id)->first();
		  // if($res){
		  //      $request->session()->put('shipping_address_id',$res->id);
		  // }
		    	 
            return redirect()->intended('/');
            
        } else{
          return Redirect::back()->withErrors(['Credentials do not match our database .']);  
        }
					} else{
						
						    	if (Auth::guard('customer')->attempt([ 'phone' => $request->phone,'password' => $request->password ], $request->get('remember')))
		{
		    
		   app(\App\Http\Controllers\CookieController::class)->mapProductWebApp(); 
		    $request->session()->put('shipping_address_id',0);
		   $res= CheckoutShipping::where('shipping_address_default',1)->where('customer_id',$validate_user->id)->first();
		   if($res){
		        $request->session()->put('shipping_address_id',$res->id);
		   }
		   
		   
		   
		    	 
            return redirect()->intended('/');
            
            
        } else{
          return Redirect::back()->withErrors(['Credentials do not match our database .']);  
        }	
					}

        
        
        
	}
} else{
	 return Redirect::back()->withErrors(['Credentials do not match our database .']);
}

	
	
         return Redirect::back()->withErrors(['Credentials do not match our database.']);	
		}
		return view('fronted.auth.customer_login',['page_details'=>$page_details]);
    }
	  
	public function send_otp(Request $request){
		  $input=$request->all();
		 
		  $user_data=$request->session()->get('customer_details');
		  $custmor_array=array(
					"phone"=>$user_data['Phone'],
					"userId"=>$user_data['User_id'],
					'flag'=>$input['OTPmethod']
					);
		       CommonHelper::generate_user_otp($custmor_array,1);
		 
		  echo json_encode( array(
				"MSG"=>"'OTP sent to your email as well on mobile"
				));	
	}
	  
	public function forgot_password(Request $request)
	{
		
		if ($request->isMethod('post')) {
			
			 $input=$request->all();
		
		if (filter_var($input['phone'], FILTER_VALIDATE_EMAIL)) {
		
			 $request->validate([
				  'phone' => 'required|max:255',
            ]
			);
			}else{
				 $request->validate([
					'phone' => 'required|regex:/[0-9]{10}/'
            ]
			);
			}
			$phone=$input['phone'];
		$user= Customer::where('isdeleted',0)
		 ->where(function($query) use($phone){
                 $query->where('phone', $phone);
                 $query->orwhere('email',$phone);
             })
		  ->first();
				if($user){
				    	
						if($user->status==0){
		   return Redirect::back()->withErrors(['Your account is disabled , plz contact to admin.']);  
		}
					 $request->session()->flush();
					 
		 $request->session()->put('customer_details',array(
			"Phone"=>$user->phone,
			"User_id"=>$user->id,
			"Flag"=>0,
		   ));
		  
		  
		  $custmor_array=array(
				             "phone"=>$input['phone'],
                            "userId"=>$user->id,
                            "flag"=>1
					);
		       CommonHelper::generate_user_otp($custmor_array,1);
			   
					 return redirect()->route('update_password');
					
				} else{
					return redirect()->back()->withErrors(['Record is not exist in our database']);
				}
		
		
		}	
        return view('fronted.mod_customer.forgetpassword');
		
    }
	
	  public function update_password(Request $request)
    {
		
			 $user_data=$request->session()->get('customer_details');
		
		if ($request->isMethod('post')) {
			
			 $input=$request->all();

	
			 $request->validate([
					'password' => 'required|max:50',
					'OTP' => 'required|max:50'
            ]
			);
			
			 $user_data=$request->session()->get('customer_details');
		
					
			$record = DB::table('customer_phone_otp')
							->where('user_id',$user_data['User_id'])
						->where('otp',$input['OTP'])
						->where('flag',1)
						->first();
						if($record){
						    if($record->expired_on<Carbon::now()){
									       return Redirect::back()->withErrors(['Otp expired']);
										} else{
										    
										    
                				    		$customer_data=DB::table('customers')->where('id',$user_data['User_id'])->first();
                				    		
                				    		$res=Customer::where('id',$user_data['User_id'])
													->update([
													'password'=>Hash::make(	trim($input['password']))
													]);
													
              
                                            if($res == true){
                                            
                                                $email_data = [
                                                'to'=>$customer_data->email,
                                                'subject'=>'Password Changed',
                                                 "body"=>view("emails_template.forget_password",
                                                     array(
                                                    'data'=>array(
                                                        'customer_data'=>$customer_data
                                                        )
                                                     ) )->render(),
                                                        'phone'=>$customer_data->phone,
                                                        'phone_msg'=>view("message_template.update_password_success",
                                                        array(
                                                        'data'=>array(
                                                        'customer_data'=>$customer_data
                                                        )
                                                        ) )->render(),
                                                 ];
                                                CommonHelper::SendmailCustom($email_data);
                                                CommonHelper::SendMsg($email_data);
                                            }
                
                
										    
													
													 MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
													return redirect()->route('customer_login');
										}
									
						} 
						else{
							return Redirect::back()->withErrors(['Invalid OTP']);
						}
			
		
		}	
        return view('fronted.mod_customer.updatepassword');
		
    }
	
	 public function cron_track(Request $request){
	    
        $order_data=DB::table('orders_courier')
						->select('orders_courier.docket_no','orders_courier.order_detail_id','order_details.order_date')
						->join('order_details','order_details.id','orders_courier.order_detail_id')
						->where('order_details.order_status',2)->get();
		
		foreach($order_data as $order_info)
		{
			//$inputs['docket_no']='14147020916346';
			$inputs['docket_no']=$order_info->docket_no;
			
			$ss=XpressbeesHelper::OrderTrack($inputs);
			$data=$ss->GetBulkShipmentStatus[0];
			
			$cust_info=DB::table('orders')
						->select('customers.phone','customers.email','orders.id','order_details.id as fld_order_detail_id','order_details.order_date')
						->join('order_details','order_details.order_id','orders.id')
						->join('customers','customers.id','orders.customer_id')
						->where('order_details.id',$order_info->order_detail_id)->first();
						
		   
		    
			
            
            
			if($data->StatusCode=='IT')
			{
				//$data->Status=='InTransit'
				DB::table('order_track')
						->where('order_detail_id',$order_info->order_detail_id)
						->update([
						'fld_order_intransit'=>1,
						//'fld_intransit_date'=>date('Y-m-d H:i:s')
						]);
					//--------------- Transit Email -------------------//
			        $msg = view("message_template.customer_order_transit",array(
                                                        'data'=>array(
                                                        'suborder_id'=>$cust_info->fld_order_detail_id,
                                                        'masterorder_id'=>$cust_info->id,
                                                        'order_date'=>$cust_info->order_date
                                                        )
                                                        ))->render();
                                                        
			        $email_data = [
                                    'to'=>$cust_info->email,
                                    'subject'=>'In transit ',
                                    "body"=>view("emails_template.customer_order_intransit",array(
                                                        'data'=>array(
                                                        'suborder_id'=>$cust_info->fld_order_detail_id,
                                                        'masterorder_id'=>$cust_info->id,
                                                        'order_date'=>$cust_info->order_date
                                                        )
                                                        ))->render(),
                                    'phone'=>$cust_info->phone,
                                    'phone_msg'=>$msg
                                  ];
                    
                         $admin_email_data = [
                        //'to'=>Config::get('constants.email.admin_to'),
                        'subject'=>'In transit',
                        "body"=>view("emails_template.admin_order_intransit",array(
                                                        'data'=>array(
                                                        'suborder_id'=>$cust_info->fld_order_detail_id,
                                                        'masterorder_id'=>$cust_info->id,
                                                        'order_date'=>$cust_info->order_date,
                                                        )
                                                        ) )->render(),
                        'phone'=>'',
                        'phone_msg'=>''
                         ];
                    
                 CommonHelper::SendmailCustom($email_data);
                 CommonHelper::SendmailAdminCustom($admin_email_data);
                 CommonHelper::SendMsg($email_data);
            //--------------- Transit Email -------------------//
            
			}else if($data->StatusCode=='OFD'){
				//$data->Status=='OutforDelivery'
				DB::table('order_track')
						->where('order_detail_id',$order_info->order_detail_id)
						->update([
						'fld_order_outofdelivery'=>1,
						//'fld_outofdelivery_date'=>date('Y-m-d H:i:s')
						]);
						
				//--------------- OFD Email -------------------//
			        $msg = view("message_template.customer_order_ofd",array(
                                                        'data'=>array(
                                                        'suborder_id'=>$cust_info->fld_order_detail_id,
                                                        'masterorder_id'=>$cust_info->id,
                                                        'order_date'=>$cust_info->order_date
                                                        )
                                                        ))->render();
                                                        
			        $email_data = [
                                    'to'=>$cust_info->email,
                                    'subject'=>'Out for Delivery  ',
                                    "body"=>view("emails_template.customer_order_ofd",array(
                                                        'data'=>array(
                                                        'suborder_id'=>$cust_info->fld_order_detail_id,
                                                        'masterorder_id'=>$cust_info->id,
                                                        'order_date'=>$cust_info->order_date
                                                        )
                                                        ))->render(),
                                    'phone'=>$cust_info->phone,
                                    'phone_msg'=>$msg
                                  ];
                    
                         $admin_email_data = [
                        //'to'=>Config::get('constants.email.admin_to'),
                        'subject'=>'Out for Delivery ',
                        "body"=>view("emails_template.admin_order_ofd",array(
                                                        'data'=>array(
                                                        'suborder_id'=>$cust_info->fld_order_detail_id,
                                                        'masterorder_id'=>$cust_info->id,
                                                        'order_date'=>$cust_info->order_date,
                                                        )
                                                        ) )->render(),
                        'phone'=>'',
                        'phone_msg'=>''
                         ];
                         
                 CommonHelper::SendmailCustom($email_data);
                 CommonHelper::SendmailAdminCustom($admin_email_data);
                 CommonHelper::SendMsg($email_data);
                
                
            //--------------- OFD Email -------------------//		
						
						
						
			}else if($data->StatusCode=='DLVD'){
				//$data->Status=='Delivered'
				DB::table('order_track')
						->where('order_detail_id',$order_info->order_detail_id)
						->update([
						'fld_delivered_order'=>1,
						'fld_delivered_date'=>date('Y-m-d H:i:s')
						]);
        		//--------------- Delivered Email -------------------//
                CommonHelper::generateMailforOrderSts($cust_info->fld_order_detail_id,2);
                //--------------- Delivered Email -------------------//
        
			}	
		}
		echo '1';
    }
	
	public function cron_docket(Request $request){
	    
        $check_cod_docket=DB::table('tbl_docket')
						->where('docket_type','COD')->get();
						
		$check_online_docket=DB::table('tbl_docket')
						->where('docket_type','Online')->get();
		
		$input['text']='';
		if(count($check_cod_docket)<60000) 
		{
			$input['text']='COD';
		}else if(count($check_online_docket)<1000){
			$input['text']='Online';
		}
			
		if($input['text']!='')
		{
			$inputs['delivery_type']=$input['text'];
			$ss=XpressbeesHelper::AWBRequest($inputs);
			$docket_batch=$ss->BatchID;
			$docket_list=$ss->AWBNoSeries;
			
			for($i=0; $i<count($docket_list);$i++)
			{
				$Docket = new Docket;
				$Docket->docket_type = $input['text'];
				$Docket->docket_batch_id =$docket_batch;
				$Docket->docket_number =$docket_list[$i];
				$Docket->save();
			}
		}
	
    }

	  
}
