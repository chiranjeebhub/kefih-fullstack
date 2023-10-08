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
use URL;

class CustomerController extends Controller
{
   
    public function redirect_fb()
    {
        return Socialite::driver('facebook')->redirect();
    }
    
    
    
     public function cron_track(Request $request)
       {
                   
	  
	
		       CommonHelper::sendMailToVendorQtyLess();
		
		
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
            setcookie('shipping_address_id', $res->id, time() + (86400 * 30), "/");
                     
            }
                                    
                          
        if(Session::get('checout')){
            return Redirect::route('review_order');
        }else{
        return redirect()->intended('/');	
        }
                                        
                                        
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
                        $res= CheckoutShipping::where('shipping_address_default',1)->where('customer_id',$user_data['User_id'])->first();
                        if($res){
                        setcookie('shipping_address_id', $res->id, time() + (86400 * 30), "/");
                        }
								Auth::guard('customer')->login($user);
				  if(Session::get('checout')){
                    return Redirect::route('review_order');
                }else{
                return redirect()->intended('/');	
                }	
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
	      ->select('child_amount','parent_amount')
	    ->first();
	    
	    // 	transfer to child
	    DB::table('tbl_refer_earn')
	    ->insert(array(
                'user_id'=>$cust_id,
                'rel_id'=>$refer_d->id,
                'amount'=>$refer_price->child_amount,
                'mode'=>0,
				'created_at' => date('Y-m-d H:i:s')
	        ));


			if($refer_price->parent_amount > 0){

				// 	transfer to parent
				DB::table('tbl_refer_earn')
				->insert(array(
						'user_id'=>$refer_d->id,
						'rel_id'=>$cust_id,
						'amount'=>$refer_price->parent_amount,
						'mode'=>0,
						'created_at' => date('Y-m-d H:i:s')
					));

			}
			  

	        
	         //insert in referarls
	        DB::table('user_referrals')
	        ->insert(array(
                'c_id'=>$cust_id,
                'p_id'=>$refer_d->id,
                'r_code'=>$input['r_code']
	        ));
	        
	         // update customer refer amount
                Customer::
                where('id',$cust_id)
                ->increment('r_amount',$refer_price->child_amount);
                
                //update wallet amount of customer
                 Customer::
                where('id',$cust_id)
                ->increment('total_reward_points',$refer_price->child_amount);

				
				     // update parent refer amount
					 Customer::
					 where('id',$refer_d->id)
					 ->increment('r_amount',$refer_price->parent_amount);
					 
					 //update wallet amount of parent
					  Customer::
					 where('id',$refer_d->id)
					 ->increment('total_reward_points',$refer_price->parent_amount);
					 

                
                //update parent of child
                 Customer::
                    where('id',$cust_id)
                    ->update(
                    array('r_by'=>$refer_d->id)
                    );
                
      // create wallet history child
	   DB::table('tbl_wallet_history')
	   ->insert(array(
                'fld_customer_id'=>$cust_id,
				'r_by' => $refer_d->id,
                'fld_order_id'=>0,
                'fld_order_detail_id'=>0,
                'fld_reward_points'=>$refer_price->child_amount,
                'fld_reward_narration'=>'Earned',
                'mode'=>1
	       ));


	  // create wallet history parent
	  DB::table('tbl_wallet_history')
	  ->insert(array(
			   'fld_customer_id'=>$refer_d->id,
			   'fld_order_id'=>0,
			   'r_to' => $cust_id,
			   'fld_order_detail_id'=>0,
			   'fld_reward_points'=>$refer_price->parent_amount,
			   'fld_reward_narration'=>'Earned',
			   'mode'=>3
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
			  'disabled'=>''
			 //  'script'=>'onkeypress="javascript:return onlyPincodeDigit(event,this.value,9)"'
			  
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
                    'name' => 'required|regex:/^[a-zA-Z]+$/u|max:50:min:5',
                    'phone' =>  'required|regex:/[0-9]{10}/|unique:customers,phone,1,isdeleted|max:50',
                    // 'phone' =>  'required|regex:/[0-9]{10}/|unique:customers,phone,1,isdeleted|max:50',
                    'email' =>  'required|email|unique:customers,email,1,isdeleted|max:50',
                    'password' => 'required|min:6|max:50',
                    'term_and_condition' => 'required',
                    'confirm_password' => 'min:6|required_with:password|same:password'
            ]
			);

			if(!empty($input['r_code'])){
				$isReferExist = Customer::where('r_code',$input['r_code'])->first();

				if(empty($isReferExist)){
					MsgHelper::save_session_message('danger','Invalid Referral Code.',$request);
					return Redirect::back();
				}

			}
                    
		
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
			MsgHelper::save_session_message('success','OTP has been sent to your mobile number and email address',$request);
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

	/**
	 * Method for user login/signup
	 */

	 public function UserLoginSignup(Request $request){		   
		try{ 		

			 $input=$request->all();
		     $validator = Validator::make($request->all(), [
				'login_phone' => 'required|min:10|max:10',
				'login_country_code'  =>'required',
				'term_accepted' =>'required',
             ],[
				'login_phone.required' => 'Mobile no. is required',
				'login_phone.min' => 'Mobile no. should be at least 10 digit',
				'login_phone.max' => 'Mobile no. can not be more than 10 digit',
				'login_country_code.required' => 'Country code is required',
				'term_accepted.required' => 'Terms of Use & Privacy Policy should be accepted',
			 ]);       

             if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
             }
			
		     $validate_user= Customer::select(
						'id',
						'name',
						'email',
						'phone',
						'country_code'					
			 )			
			->where(['country_code' => $input['login_country_code'], 'phone' => trim($input['login_phone'])])
			->first();

			if ($validate_user){
				 /**
				  * Login here 
				  */
				  $request->session()->put('customer_details',array(
					"Phone"=>$validate_user->phone,
					"country_code"=>$validate_user->country_code,
					"User_id"=>$validate_user->id,
					"Flag"=>0,
				   ));
				   
				   /**
				  * Generate OTP
				  */
				  CommonHelper::generate_customer_login_otp(array(
					"Phone"=>$validate_user->phone,					
					"User_id"=>$validate_user->id
					));

				 return response()->json([
                    // 'message'=>'OTP has been sent valid for 10 minutes',
                    'message'=>'OTP has been sent valid for 1 minutes',
                    'status'=>true,
                    "data"=> $validate_user
                 ], 200);


			}else{
				/**
				 * Register New User
				 */
				 $newCustomer = new Customer;
				 $newCustomer->phone = trim($input['login_phone']);
				 $newCustomer->country_code = trim($input['login_country_code']);
				 $newCustomer->save(); 

				 $request->session()->put('customer_details',array(
					"Phone"=>$newCustomer->phone,
					"country_code"=>$newCustomer->country_code,
					"User_id"=>$newCustomer->id,
					"Flag"=>1,
				   ));
				   

				 /**
				  * Generate OTP
				  */
				  CommonHelper::generate_customer_login_otp(array(
							"Phone"=>$newCustomer->phone,					
							"User_id"=>$newCustomer->id
					));

				return response()->json([
						'message'=>'OTP sent valid for 10 minutes',
						'status'=>true,
						"data"=> ['country_code' => $newCustomer->country_code, 'phone' => $newCustomer->phone]
					 ], 200);
			}

		} catch(Exception $e){
            return response()->json([
                    'message'=>'Something went wrong! Try Later.',
                    'status'=>false,
                    "data"=>null
                ], 500);
        }

	 }



	 public function UserLoginSignupVerifiy(Request $request){		
		try{ 
		        $user_data=$request->session()->get('customer_details');
			    $input=$request->all();			
				$validator = Validator::make($request->all(), [
					'otp' => 'required|min:4|max:4'
				 ],[
					'otp.required' => 'OTP is required',
					'otp.min' => 'OTP should be at least 4 digit',
					'otp.max' => 'OTP can not be more than 4 digit',					
				 ]);       
	
				 if($validator->fails()){
					return $this->sendError('Validation Error.', $validator->errors());       
				 }


			$record = DB::table('customer_login_otp')
						->where('user_id',$user_data['User_id'])
						->where('otp',$input['otp'])
						->first();


						if($record){
						    if($record->expired_on<Carbon::now()){
											return response()->json([
												'message'=>'OTP has been expired',
												'status'=>false,
												"data"=> ''
											], 200);

										} else{
										    $customer_data=DB::table('customers')->where('id',$user_data['User_id'])->first();
											if($user_data['Flag'] == 1){
											
											/*
											 $msg=view("message_template.welcome_message",
												array(
												'data'=>array(
												'name'=>$customer_data->name
												)
											) )->render();	
											 
												
												$email_msg='                        <tr>
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
												//  CommonHelper::SendmailCustom($admin_email_data);
												CommonHelper::SendMsg($email_data);
												*/

												$res=Customer::where('id',$user_data['User_id'])
												->update([
												'isOtpVerified'=>1
												]);

											}						
								$user = Customer::find($user_data['User_id']);
								$res= CheckoutShipping::where('shipping_address_default',1)->where('customer_id',$user_data['User_id'])->first();
								if($res){
								setcookie('shipping_address_id', $res->id, time() + (86400 * 30), "/");
								}

								/**
								 * User Login
								 */
								Auth::guard('customer')->login($user);

								// $redirectionURL = URL::to('/');
								$redirectionURL = request()->headers->get('referer');
								if(Session::get('checout')){
									$redirectionURL = URL::to('/review_order');
								}
								
								return response()->json([
									'message'=>'Login Successfull',
									'status'=>true,
									"data"=> $redirectionURL
								], 200);

							}
									
						} 
						else{
							return response()->json([
								'message'=>'Invalid OTP',
								'status'=>false,
								"data"=> ''
							], 200);
						}

			} catch(Exception $e){
				return response()->json([
						'message'=>'Something went wrong! Try Later.',
						'status'=>false,
						"data"=>null
					], 500);
			}
				
		
	}


	public function UserLoginOTP(Request $request){
		try{
			 
		$user_data=$request->session()->get('customer_details');
		$custmor_array=array(
			"Phone"=>$user_data['Phone'],
			"User_id"=>$user_data['User_id'],			
		);

		$record = DB::table('customer_login_otp')
						->where('user_id',$user_data['User_id'])
						->first();


$start = Carbon::parse(Carbon::now());
$end = Carbon::parse($record->expired_on);

// $remainingminutes = $end->diffInMinutes($start);
$remainingminutes = 0;
if($start->lt($end)){
	$remainingminutes = $end->diffInSeconds($start);
}


// dd($remainingminutes);


		if($remainingminutes<=0){
		 /**
		 * Generate OTP
		 */
		 CommonHelper::generate_customer_login_otp($custmor_array);

			return response()->json([
				'message'=>'OTP has been sent ',
				'status'=>true,
				"data"=>''
			], 200);
		}else{
			return response()->json([
				'message'=>'Please resend after '.$remainingminutes.' seconds',
				'status'=>false,
				'start' => $start,
				'end' => $end,
				"data"=> ''
			], 200);
		}

		

		} catch(Exception $e){
			return response()->json([
					'message'=>'Something went wrong! Try Later.',
					'status'=>false,
					"data"=>null
				], 500);
		}
	}



	 /**
     * Setting validation error response
     */
    public function sendError($error, $errorMessages = [], $code = 202)
    {
    	$response = [
            'status' => false,
            'message' => $error,
        ];        
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
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
			->where('email',trim($input['phone']))
			// ->where('phone',trim($input['phone']))
			// ->orwhere('email',trim($input['phone']))
			->first();

		if(!empty($validate_user)){
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
                    $res= CheckoutShipping::where('shipping_address_default',1)->where('customer_id',$validate_user->id)->first();
                    if($res){
                          setcookie('shipping_address_id', $res->id, time() + (86400 * 30), "/");
                    }
					Auth::guard('customer')->login($user);
                            if(Session::get('checout')){
                                return Redirect::route('review_order');
                            }else{
                            return redirect()->intended('/');	
                            }

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
		
		return redirect()->route('verifiy')->withErrors(['Jaldi Kharido E-shop send OTP to your register mobile number verify to continue login.']);
		
						}
				
					}else{
						
	return redirect()->route('customer_login')->withErrors(['Your account is deactive contact to administrator.']);					
	
					}	
		}
	    else{
			return redirect()->route('customer_login')->withErrors(['Invalid Password.']);	
		}               
		}
        else{
			
		return redirect()->route('customer_login')->withErrors(['Email ID does not exists.']);	
	
			
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
		
			 $request->validate([
				'phone' => 'required|email|max:255',
		   ],[
			  'phone.required' => 'Email ID is required',
			  'phone.max' => 'Email ID can not be more than 255 characters',
		   ]
		  );
/*
		if (filter_var($input['phone'], FILTER_VALIDATE_EMAIL)) {
		
			 $request->validate([
				  'phone' => 'required|email|max:255',
			 ],[
				'phone.required' => 'Email ID is required',
				'phone.max' => 'Email ID can not be more than 255 characters',
			 ]
			);
			}else{
				 $request->validate([
					'phone' => 'required|regex:/[0-9]{10}/'
            ]
			);
			}
			*/
			
		// $user= Customer::where('phone',$input['phone'])->orwhere('email',$input['phone'])->first();
		$user= Customer::where('email',$input['phone'])->first();
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
			   
			   MsgHelper::save_session_message('success','Please check mail for OTP',$request);

					 return redirect()->route('update_password');
					
				} else{
					return redirect()->back()->withErrors(['Email doest not exists']);
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
					'password' => 'required|max:50|min:6',
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


	public function update_fcm(Request $request){
		try{
	
		$user = auth()->guard('customer')->user();
		Customer::where('id',$user->id)->update(['fcm_token' => $request->fcmtoken]);
		
		return response()->json([
			'success'=>true
		]);

	}catch(\Exception $e){
        report($e);
        return response()->json([
            'success'=>false
        ],500);
    }
	}

	  
}
