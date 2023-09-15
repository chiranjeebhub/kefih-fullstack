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
use Cookie;
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
use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite;
use App\Services;
class CustomerController extends Controller
{
   
    public function redirect_fb()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function callback_fb()
    {
         try{
           
             $user = Socialite::driver('facebook')->stateless()->user();
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
		    
		    if (Auth::guard('customer')->attempt([ 'email' => $validate_user->email,'password' => '12345678']))
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
		    
		     $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'.date('dmYHis'); 
                        $code = ''; 
                        for ($i = 0; $i < 5; $i++) { 
                        $index = rand(0, strlen($characters) - 1); 
                        $code .= $characters[$index]; 
                        }
                    $code.=$Customer->id;
                    Customer::
                    where('id',$Customer->id)
                    ->update(
                    array('r_code'=>$code)
                    );
                    
		return redirect()->to('/'); 	 
		//return redirect()->intended('/');
		}
        	   
        	   //Session::put('Phone','1111111111');
        	   Session::put('User_id',$Customer->id);
        	   Session::put('Flag','0');
        	}
        	
        	
        	
            return redirect()->to('/');
        }
        } catch (\Exception $e) {
            	return redirect()->route('customer_login')->withErrors(['Something went wrong']);;
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
            	return redirect()->route('customer_login')->withErrors(['Something went wrong']);;
          
        }
       
        // only allow people with @company.com to login
       /* if(explode("@", $user->email)[1] !== 'bazzarplus.com'){
            return redirect()->to('/');
        }*/
        // check if they're an existing user
    
        $b64image = base64_encode(file_get_contents($user->avatar));
         $data = $b64image;
            $data = base64_decode($data);
            $name=time().".png";
            file_put_contents(Config::get('constants.uploads.customer_profile_pic')."/".$name, $data);
    
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
             $newUser->profile_pic       = $name;
            //$newUser->avatar          = $user->avatar;
            //$newUser->avatar_original = $user->avatar_original;
            $newUser->save();
            Auth::guard('customer')->login($newUser, true);
            
            
            if (Auth::guard('customer')->attempt([ 'email' =>  $newUser->email,'password' => '12345678']))
    		{
    		    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'.date('dmYHis'); 
                        $code = ''; 
                        for ($i = 0; $i < 5; $i++) { 
                        $index = rand(0, strlen($characters) - 1); 
                        $code .= $characters[$index]; 
                        }
                    $code.=$newUser->id;
                    Customer::
                    where('id',$newUser->id)
                    ->update(
                    array('r_code'=>$code)
                    );
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
   
   
   
    public function dashboard(){
        $user = auth()->guard('customer')->user();
		
		return view('customerDashboard');
		//$user = auth()->guard('customer')->user()->id;
       // dd($user);
    }
	
	
    public function logout(Request $request)
    {
     //dd(auth()->guard('admin')->user());
		Auth::guard('customer')->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->intended('/');
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
     
      public function login_with_otp(Request $request){
		
        $minutes=(86400000 * 30);
       $shipping_address_id=0;
                    
		$user_data=$request->session()->get('customer_login_details');
	
		 $page_details=array(
       "Title"=>"Otp Verification",
       "Box_Title"=>"",
       "Action_route"=>route('login_with_otp'),
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
			$record = DB::table('customer_login_otp')
						->where('user_id',$user_data['User_id'])
						->where('otp',$input['otp'])
						->first();
						if($record){
						    if($record->expired_on<Carbon::now()){
									       return Redirect::back()->withErrors(['Otp expired']);
										} else{
										    
											$validate_user= Customer::select(
											'id',
											'name',
											'email',
											'phone',
											'isOtpVerified',
											'password'
											)
											->where('id',$user_data['User_id'])
												->where('status',1)
												->where('isdeleted',0)
											->first();
                                                
                                $user = Customer::find($user_data['User_id']);
                                                
                                Auth::guard('customer')->login($user);
            
$res= CheckoutShipping::where('shipping_address_default',1)->where('customer_id',$user_data['User_id'])->first();
            if($res){
                    $ship_details=array(
                    "shipping_address_id"=>$res->id,
                    "pincode"=>'',
                    "pincode_error"=>1
                    );
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
setcookie('pincode', $res->shipping_pincode, time() + (86400 * 30), "/");
setcookie('pincode_error', 0, time() + (86400 * 30), "/");
                     } else{
       setcookie('pincode', $res->shipping_pincode, time() + (86400 * 30), "/");
       setcookie('pincode_error', 1, time() + (86400 * 30), "/");
             
                    }
       setcookie('shipping_address_id', $res->id, time() + (86400 * 30), "/");           
            }
                                        
                          
return redirect()->intended('/');
                                        
                                        
										}
									
						} 
						else{
							return Redirect::back()->withErrors(['Invalid Otp']);
						}
			
		
		}		
		
			return view('fronted.auth.customer_login_otp',['page_details'=>$page_details]);
	}

  public function verifiy(Request $request){
		
		$user_data=$request->session()->get('customer_details');
	
		//print_r($user_data);die;
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
						//->where('flag',$user_data['Flag'])
						->first();
						if($record){
						    if($record->expired_on<Carbon::now()){
									       return Redirect::back()->withErrors(['Otp expired']);
										} else{
										    $customer_data=DB::table('customers')->where('id',$user_data['User_id'])->first();

											 $msg=view("message_template.welcome_message",
												array(
												'data'=>array(
												'name'=>$customer_data->name
												)
											) )->render();	
											 
											 //$msg='Dear '.$customer_data->name.' '.$customer_data->last_name.'  you have succuessfully registered';
											 
										    $email_msg='   <tr>
                         <td style="padding:5px 10px;">
                            <strong>Name</strong>
                         </td>
                         <td style="padding:5px 10px;">
                                  <p><strong></strong>'.$customer_data->name.' '.$customer_data->last_name.'</p>
                         </td>
                     </tr>
                     <tr>
                         <td style="padding:5px 10px;">
                            <strong>Email</strong>
                         </td>
                         <td style="padding:5px 10px;">
                                  <p><strong></strong>'.$customer_data->email.'</p>
                         </td>
                     </tr>
                     <tr>
                         <td style="padding:5px 10px;">
                            <strong>Phone</strong>
                         </td>
                         <td style="padding:5px 10px;">
                                  <p><strong></strong>'.$customer_data->phone.'</p>
                         </td>
                     </tr>';
	           $email_data = [
                            'to'=>$customer_data->email,
                            'subject'=>'Registration',
                            "body"=>view("emails_template.customer_register",
                            array(
                            'data'=>array(
                            'name' => $customer_data->name
                            )
                            ) )->render(),
                            'phone'=>$customer_data->phone,
                            'phone_msg'=>$msg
                         ];
                    $admin_email_data = [
                        'to'=>Config::get('constants.email.admin_to'),
                        'subject'=>'Registeration',
                        "body"=>view("emails_template.customer_register",
                        array(
                        'data'=>array(
									'name' => $customer_data->name
                        )
                        ) )->render(),
                        'phone'=>$customer_data->phone,
                        'phone_msg'=>$msg
                    ];
                CommonHelper::SendmailCustom($email_data);
                // CommonHelper::SendmailCustom($admin_email_data);
                 CommonHelper::SendMsg($email_data);
		   
													$res=Customer::where('id',$user_data['User_id'])
													->update([
													'isOtpVerified'=>1
													]);
		MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
							//return redirect()->route('customer_login');
								$user = Customer::find($user_data['User_id']);
								Auth::guard('customer')->login($user);
								return redirect()->intended('/');		
                               /*if () {                 
                               
								 		
					                 }*/			
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

public function OnR_codeRegister($input,$cust_id){
    $refer_d=Customer::select('id')->where('r_code',$input['r_code'])->first();
    if($refer_d){
        
         $refer_price=DB::table('store_info')
	      ->select('child_amount')
	    ->first();
	    
	    // 	transfer to child
	    DB::table('tbl_refer_earn')
	    ->insert(array(
                'user_id'=>$cust_id,
                'rel_id'=>$refer_d->id,
                'amount'=>$refer_price->child_amount,
                'mode'=>0
	        ));
	        
	         //insert in referarls
	        DB::table('user_referrals')
	        ->insert(array(
                'c_id'=>$cust_id,
                'p_id'=>$refer_d->id,
                'r_code'=>$input['r_code']
	        ));
	        
	         // update parents refer amount
                Customer::
                where('id',$cust_id)
                ->increment('r_amount',$refer_price->child_amount);
                
                //update wallet amount of customer
                 Customer::
                where('id',$cust_id)
                ->increment('total_reward_points',$refer_price->child_amount);
                
                //update parent of child
                 Customer::
                    where('id',$cust_id)
                    ->update(
                    array('r_by'=>$refer_d->id)
                    );
                
      // create wallet history
	    DB::table('tbl_wallet_history')
	    ->insert(array(
                'fld_customer_id'=>$cust_id,
                'fld_order_id'=>0,
                'fld_order_detail_id'=>0,
                'fld_reward_points'=>$refer_price->child_amount,
                'fld_reward_narration'=>'Earned',
                'mode'=>1
	        ));
    }
                   
}


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
			  'disabled'=>'',
			   'script'=>'onkeypress="javascript:return onlyPincodeDigit(event,this.value,9)"'
			  
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
		  "r_code_field"=>array(
			  'label'=>'',
			  'type'=>'text',
			  'name'=>'r_code',
			  'id'=>'r_code',
			  'classes'=>'form-control',
			  'placeholder'=>'Referal Code',
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
					'name' => 'required|max:50:min:5',
                    'phone' =>  'required|regex:/[0-9]{10}/|unique:customers,phone,1,isdeleted|max:50',
                    'email' =>  'required|email|unique:customers,email,1,isdeleted|max:50',
                    'password' => 'required|min:6|max:50',
                    'confirm_password' => 'min:6|required_with:password|same:password',
            ]
			);
			$Customer = new Customer;
				$Customer->name = $input['name'];
				$Customer->phone = $input['phone'];
				$Customer->email =  $input['email'];
				$Customer->password = Hash::make(trim($input['password']));
	            $Customer->status = 1;
     
      /* save the following details */
      if($Customer->save()){
          
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'.date('dmYHis'); 
                        $code = ''; 
                        for ($i = 0; $i < 5; $i++) { 
                        $index = rand(0, strlen($characters) - 1); 
                        $code .= $characters[$index]; 
                        }
                    $code.=$Customer->id;
                    Customer::
                    where('id',$Customer->id)
                    ->update(
                    array('r_code'=>$code)
                    );
                    
                    if (array_key_exists("r_code",$input))
                    {
                    if($input['r_code']!=''){
                    $this->OnR_codeRegister($input,$Customer->id);
                    }
                    }
                  
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
	
	public function OTP_login(Request $request)
    {
        $page_details=array(
       "Title"=>"Customer Login",
	   "Method"=>"1",
       "Box_Title"=>"",
       "Action_route"=>route('OTP_login'),
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
                'phone' => 'required|max:255'
            ]);
			
			
			
		$validate_user= Customer::select(
						'id',
						'name',
						'email',
						'phone',
						'isOtpVerified',
						'password'
			)
			
			->where('phone',trim($input['phone']))
			->orwhere('email',trim($input['phone']))
			->first();

		
if ($validate_user) {
	
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
		if (filter_var($input['phone'], FILTER_VALIDATE_EMAIL)) {
		    
		    $request->session()->put('customer_login_details',array(
                        "Phone"=>$validate_user->phone,
                        "password"=>$request->password,
                        "email"=>$validate_user->email,
                        "User_id"=>$validate_user->id
		   ));
		          CommonHelper::generate_customer_login_otp(array(
                            "Phone"=>$validate_user->phone,
                            "password"=>$request->password,
                            "email"=>$validate_user->email,
                            "User_id"=>$validate_user->id
                            ));
			                return redirect()->route('login_with_otp')->withErrors(['Enter Otp To login.']);
					} else{
                            $request->session()->put('customer_login_details',array(
                                "Phone"=>$validate_user->phone,
                                "password"=>$request->password,
                                "email"=>$validate_user->email,
                                "User_id"=>$validate_user->id
                            ));
                            CommonHelper::generate_customer_login_otp(array(
                            "Phone"=>$validate_user->phone,
                            "password"=>$request->password,
                            "email"=>$validate_user->email,
                            "User_id"=>$validate_user->id
                            ));
                            return redirect()->route('login_with_otp')->withErrors(['Enter Otp To login.']);
					}

        
        
        
	}
} else{
	 return Redirect::back()->withErrors(['Credentials do not match our database .']);
}

	
	
         return Redirect::back()->withErrors(['Credentials do not match our database.']);	
		}
		return view('fronted.auth.customer_with_out_password',['page_details'=>$page_details]);
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
						'status',
						'isOtpVerified',
						'password'
			)
			
			->where('phone',trim($input['phone']))
			->orwhere('email',trim($input['phone']))
			->first();

		if(sizeof($validate_user) !='0'){
		if ($validate_user && Hash::check($input['password'], $validate_user->password)) 
		{
			$user_data = array(
			"fld_user_id"=>$validate_user->fld_user_id,
			"fld_user_name"=>$validate_user->name,
			"fld_l_name"=>$validate_user->last_name,
			"fld_user_email"=>$validate_user->email,
			"fld_user_email"=>$validate_user->email,
			"fld_user_email"=>$validate_user->email,
			"fld_user_email"=>$validate_user->email,
			);
			
			
           if($validate_user->status==1){
           	
           	    if($validate_user->isOtpVerified==1){
           	    	
           	    	        
           	    	$user = Customer::find($validate_user->id);
					Auth::guard('customer')->login($user);
					return redirect()->intended('/');	
           	    	
						}
						else{
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
		
		return redirect()->route('verifiy')->withErrors(['phaukat E-shop send OTP to your register mobile number verify to continue login.']);
		
						}
				
					}else{
						
	return redirect()->route('customer_login')->withErrors(['Your account is deactive contact to administrator.']);					
	
					}	
		}
	    else{
			return redirect()->route('customer_login')->withErrors(['Password Invalid.']);	
		}               
		}
        else{
			
		return redirect()->route('customer_login')->withErrors(['Email Or Mobile is not exits.']);	
	
			
			}
        
		}
		else{
			return view('fronted.auth.customer_login',['page_details'=>$page_details]);
		}
    }
	public function customer_login_yogi(Request $request)
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
						'password'
			)
			
			->where('phone',trim($input['phone']))
			->orwhere('email',trim($input['phone']))
			->first();

		
if ($validate_user && Hash::check($input['password'], $validate_user->password)) {
	
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
		if (filter_var($input['phone'], FILTER_VALIDATE_EMAIL)) {
		    
		    $request->session()->put('customer_login_details',array(
                        "Phone"=>$validate_user->phone,
                        "password"=>$request->password,
                        "email"=>$validate_user->email,
                        "User_id"=>$validate_user->id
		   ));
		          CommonHelper::generate_customer_login_otp(array(
                            "Phone"=>$validate_user->phone,
                            "password"=>$request->password,
                            "email"=>$validate_user->email,
                            "User_id"=>$validate_user->id
                            ));
			                return redirect()->route('login_with_otp')->withErrors(['Enter Otp To login.']);
					} else{
                            $request->session()->put('customer_login_details',array(
                                "Phone"=>$validate_user->phone,
                                "password"=>$request->password,
                                "email"=>$validate_user->email,
                                "User_id"=>$validate_user->id
                            ));
                            CommonHelper::generate_customer_login_otp(array(
                            "Phone"=>$validate_user->phone,
                            "password"=>$request->password,
                            "email"=>$validate_user->email,
                            "User_id"=>$validate_user->id
                            ));
                            return redirect()->route('login_with_otp')->withErrors(['Enter Otp To login.']);
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
		CommonHelper::generate_user_otp($custmor_array);
		 
		echo json_encode( array(
			"MSG"=>"OTP resent to your email as well on your given number"
		));	
	}
	
	public function customer_resend_login_otp_message(Request $request){
		$input=$request->all();
		 
		$user_data=$request->session()->get('customer_login_details');
		CommonHelper::generate_customer_login_otp($user_data);
		 
		echo json_encode( array(
			"MSG"=>"'OTP resent to your email as well on your given number'"
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
			
		$user= Customer::where('phone',$input['phone'])->orwhere('email',$input['phone'])->first();
				if($user){
					
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
		       CommonHelper::generate_user_otp($custmor_array);
			   
					 return redirect()->route('update_password');
					
				} else{
					return redirect()->back()->withErrors(['Mobile no/Email doest not exits']);
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
                
                $email_msg='<tr>
                  <td style="padding:5px 10px;">
                          <p><strong></strong>Dear '.$customer_data->name.' '.$customer_data->last_name.' Your password has been changed.</p>
                 </td>
                </tr>';
                
                $email_data = [
                'to'=>$customer_data->email,
                'subject'=>'Password Changed',
                 "body"=>view("emails_template.forget_password",
                     array(
                    'data'=>array(
                        'message'=>$email_msg
                        )
                     ) )->render(),
                     'phone'=>'',
                     'phone_msg'=>''
                 	];
						CommonHelper::SendmailCustom($email_data);
						
							$res=Customer::where('id',$user_data['User_id'])
							->update([
							'password'=>Hash::make(	trim($input['password']))
							]);
								MsgHelper::save_session_message('success',Config::get('messages.common_msg.update_password'),$request);
							return redirect()->route('customer_login');
				}
									
						} 
						else{
							return Redirect::back()->withErrors(['No record found']);
						}
			
		
		}	
        return view('fronted.mod_customer.updatepassword');
		
    }

	  
}
