<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Vendor;
use App\Helpers\MsgHelper;
use Illuminate\Support\Facades\Hash;
use Redirect;
use Validator;
use DB;
use Config;
use Auth;
use Carbon\Carbon;
use App\Helpers\CommonHelper;
class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
         $this->middleware('vendor')->except('logout');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
      public function admin_resend_otp(Request $request){
         	if ($request->isMethod('post')) {
				
				 $user_data=$request->session()->get('admin_forgot_password_details');
				 $admin_array=array(
                    "Phone"=>$user_data['Phone'],
                    "Email"=>$user_data['Email'],
                    "Id"=>$user_data['Id'],
                    "Email_for"=>1
					);
					
		          CommonHelper::vendor_forgot_password($admin_array);
				echo json_encode( array(
				"MSG"=>"'OTP resent to your email as well on your given number'"
				));	
			
				}
     }
       public function admin_update_password(Request $request){
            $user_data=$request->session()->get('admin_forgot_password_details');
           
		
		if ($request->isMethod('post')) {
			
			 $input=$request->all();

			 $request->validate([
					'password' => 'required|max:50',
					'otp' => 'required|max:4|min:4'
            ]
			);
			
			 $user_data=$request->session()->get('admin_forgot_password_details');
		
					
			$record = DB::table('vendor_admin_forgot_password')
                                ->where('phone',$user_data['Phone'])
                                ->where('otp',$input['otp'])
                                ->where('email_for',$user_data['Email_for'])
					         	->first();
					         	
					         
						if($record){
						    if($record->expired_on<Carbon::now()){
									       return Redirect::back()->withErrors(['Otp expired']);
										} else{
													$res=User::where('id',$user_data['Id'])
													->update([
													'password'=>Hash::make(	trim($input['password']))
													]);
													
													 
													 MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
													return redirect()->route('vendor_login');
										}
									
						} 
						else{
							return Redirect::back()->withErrors(['No record found']);
						}
			
		
		}	
        return view('auth.admin_update_password');
       }
     
        public function admin_forgot_password(Request $request){
            	if ($request->isMethod('post')) {
			         $input=$request->all();
			         
				     $Vendor= User::where('email',$input['email'])->where('isdeleted',0)->first();
				if($Vendor){
                $request->session()->put('admin_forgot_password_details',array(
                "Phone"=>$Vendor->phone,
                "Email"=>$Vendor->email,
                "Id"=>$Vendor->id,
                "Email_for"=>1,
                ));
		  
		  
		  $vendor_array=array(
                    "Phone"=>$Vendor->phone,
                    "Email"=>$Vendor->email,
                    "Id"=>$Vendor->id,
                    "Email_for"=>1
					);
					
		          CommonHelper::vendor_forgot_password($vendor_array);
			   
				    return redirect()->route('admin_update_password');
				}
				else{
					return redirect()->back()->withErrors(['Record is not exist in our database']);
				}
                  
			 
            	}
        return view('auth.admin_forgot_password');
        }
        
     public function vendor_resend_otp(Request $request){
         	if ($request->isMethod('post')) {
				
				 $user_data=$request->session()->get('vendor_forgot_password_details');
				 $vendor_array=array(
                    "Phone"=>$user_data['Phone'],
                    "Email"=>$user_data['Email'],
                    "Id"=>$user_data['Id'],
                    "Email_for"=>0
					);
					
		          CommonHelper::vendor_forgot_password($vendor_array);
				echo json_encode( array(
				"MSG"=>"'OTP resent to your email as well on your given number'"
				));	
			
				}
     }
       public function vendor_update_password(Request $request){
            $user_data=$request->session()->get('vendor_forgot_password_details');
           
		
		if ($request->isMethod('post')) {
			
			 $input=$request->all();

			 $request->validate([
					'password' => 'required|max:50',
					'otp' => 'required|max:4|min:4'
            ]
			);
			
			 $user_data=$request->session()->get('vendor_forgot_password_details');
		
					
			$record = DB::table('vendor_admin_forgot_password')
                                ->where('phone',$user_data['Phone'])
                                ->where('otp',$input['otp'])
                                ->where('email_for',$user_data['Email_for'])
					         	->first();
					         	
					         
						if($record){
						    if($record->expired_on<Carbon::now()){
									       return Redirect::back()->withErrors(['Otp expired']);
										} else{
													$res=Vendor::where('id',$user_data['Id'])
													->update([
													'password'=>Hash::make(	trim($input['password']))
													]);
													
													 $cdata = array(
													                 'phone' => $user_data['Phone']
													              );
													     
													 
													 CommonHelper::vendor_forgot_password_success($cdata);
													 
													 MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
													return redirect()->route('vendor_login');
										}
									
						} 
						else{
							return Redirect::back()->withErrors(['No record found']);
						}
			
		
		}	
        return view('auth.vendor_update_password');
       }
     
        public function vendor_forgot_password(Request $request){
            	if ($request->isMethod('post')) {
			  $input=$request->all();
				$Vendor= Vendor::where('email',$input['email'])->where('isdeleted',0)->first();
				if($Vendor){
                $request->session()->put('vendor_forgot_password_details',array(
                "Phone"=>$Vendor->phone,
                "Email"=>$Vendor->email,
                "Id"=>$Vendor->id,
                "Email_for"=>0,
                ));
		  
		  
		            $vendor_array=array(
                    "Phone"=>$Vendor->phone,
                    "Email"=>$Vendor->email,
                    "Id"=>$Vendor->id,
                    "Email_for"=>0
					);
					
		          CommonHelper::vendor_forgot_password($vendor_array);
			   
				    return redirect()->route('vendor_update_password');
				}
				else{
					return redirect()->back()->withErrors(['Record is not exist in our database']);
				}
                  
			 
            	}
                return view('auth.vendor_forgot_password');
        }
	 public function email_verify(Request $request)
    {
		 $email= base64_decode($request->email);
		 $code= base64_decode($request->code);
		 
		
		 $record = DB::table('email_verification')
		 ->where('email',$email)
		 ->where('code',$code)
		 ->first();
		
		if($record){
		    
		    
			 DB::table('vendors')
                ->where('email', $email)
                ->update([
			     'is_email_verify' =>1
				]);
				
				$Vendor= Vendor::where('email',$email)->where('isdeleted',0)->first();
				$vendor_array=array(
                    "Phone"=>$Vendor->phone,
                    "to"=>$Vendor->email,
                    "Id"=>$Vendor->id,
                    "username"=>$Vendor->username,
                   
					);
		          CommonHelper::vendor_account_verified($vendor_array);
		          
					MsgHelper::save_session_message('success',Config::get('messages.common_msg.vendor_email_verification_success'),$request);
					return redirect()->route('vendor_login');
		} else{
			
				MsgHelper::save_session_message('success',Config::get('messages.common_msg.vendor_email_verification_error'),$request);
				return redirect()->route('vendor_login');
			
		}
	}
    public function vendor_register(Request $request)
    {
		
		   $level= base64_decode($request->level);
		 switch($level){
			 case 0;
			 $page_details=array(
       "Title"=>"Vendor Registration",
       "Box_Title"=>"",
       "Action_route"=>route('vendor_register', base64_encode(0)),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "email_field"=>array(
              'label'=>'Email ID',
            'type'=>'text',
            'name'=>'email',
            'id'=>'email',
            'classes'=>'form-control',
            'placeholder'=>'a@a.com',
            'value'=>'',
			'disabled'=>''
           ),
		   "phone_field"=>array(
              'label'=>'Phone',
            'type'=>'text',
            'name'=>'phone',
            'id'=>'phone',
            'classes'=>' form-control',
            'placeholder'=>'999999999',
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
			  'value'=>'Start Selling'
		)
         )
       )
     );
			 break;
			 
				case 1;
			$vendor_details = session('vendor_details');
				 $page_details=array(
       "Title"=>"Vendor Phone Verification",
       "Box_Title"=>"",
       "Action_route"=>route('vendor_register', base64_encode(1)),
       "Form_data"=>array(

         "Form_field"=>array(
           
		    "seller_name_field"=>array(
              'label'=>'Seller Name(Display Name)',
            'type'=>'text',
            'name'=>'username',
            'id'=>'username',
            'classes'=>'form-control',
            'placeholder'=>'aaa',
            'value'=>old('username'),
			'disabled'=>''
           ),
           "email_field"=>array(
              'label'=>'Email ID',
            'type'=>'text',
            'name'=>'email',
            'id'=>'email',
            'classes'=>'form-control',
            'placeholder'=>'aaa',
            'value'=>$vendor_details[0]['email'],
			'disabled'=>'disabled'
           ),
		   "phone_field"=>array(
              'label'=>'Phone',
            'type'=>'text',
            'name'=>'phone',
            'id'=>'phone',
            'classes'=>'phone form-control',
            'placeholder'=>'999999999',
            'value'=>$vendor_details[0]['phone'],
			'disabled'=>'disabled'
           ),
		   "otp_field"=>array(
              'label'=>'Enter the 6-digit code sent via SMS to your mobile number.',
            'type'=>'text',
            'name'=>'otp',
            'id'=>'otp',
            'classes'=>' form-control',
            'placeholder'=>'99999',
            'value'=>'',
			'disabled'=>''
           ),
		   "password_field"=>array(
              'label'=>'Password',
            'type'=>'password',
            'name'=>'password',
            'id'=>'password',
            'classes'=>' form-control',
            'placeholder'=>'*****',
            'value'=>'',
			'disabled'=>''
           ),
           
           "confirm_password_field"=>array(
              'label'=>'Confirm Password',
            'type'=>'password',
            'name'=>'confirm_password',
            'id'=>'confirm_password',
            'classes'=>' form-control',
            'placeholder'=>'*****',
            'value'=>'',
			'disabled'=>''
           ),
           "vendor_company_name_field"=>array(
						'label'=>'Company Name',
						'type'=>'text',
						'name'=>'company_name',
						'id'=>'company_name',
						'classes'=>'form-control',
						'placeholder'=>'Company Name',
						'value'=>old('company_name'),
						'disabled'=>''
				),
				"vendor_company_pincode_field"=>array(
						'label'=>'Company Pincode',
						'type'=>'text',
						'name'=>'company_pincode',
						'id'=>'company_pincode',
						'classes'=>'form-control',
						'placeholder'=>'Company Pincode',
						'value'=>old('company_pincode'),
						'disabled'=>''
				),
				"vendor_gst_field"=>array(
				'label'=>'GST NO',
				'type'=>'text',
				'name'=>'gst_no',
				'id'=>'gst_no',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>old('gst_no'),
				'disabled'=>''
				),
		   "submit_button_field"=>array(
			  'label'=>'',
			  'type'=>'submit',
			  'name'=>'submit',
			  'id'=>'submit',
			  'classes'=>'btn btn-login-register btn-md btn-blue',
			  'placeholder'=>'',
			  'value'=>'Continue'
		)
         )
       )
     );
				break;
			 
				case 2;
				$page_details=array(
       "Title"=>"Category Selection",
		"Method"=>"1",
		"Box_Title"=>"",
	 "Action_route"=>route('vendor_register', base64_encode(2)),
       "Form_data"=>array(

         "Form_field"=>array(
		   "submit_button_field"=>array(
			  'label'=>'',
			  'type'=>'submit',
			  'name'=>'submit',
			  'id'=>'submit',
			  'classes'=>'btn btn-login-register btn-md btn-blue registrbtn',
			  'placeholder'=>'',
			  'value'=>'Continue'
		)
         )
       )
     );
				break;
		 }
		
		if ($request->isMethod('post')) {
			
			 $input=$request->all();
			
			switch($level){
			 case 0;
			 $request->validate([
					'email' => 'required|email|unique:vendors,email,1,isdeleted|max:50',
					'phone' => 'required|regex:/[0-9]{10}/|unique:vendors,phone,1,isdeleted|max:10',
                  
            ]
			);
		
			$User = new Vendor;
				$User->phone = $input['phone'];
				$User->email = $input['email'];
                        // if (in_array("policy", $input))
                        // {
                       	// $User->term_accepted = 1;
                        // }
                        if (!empty($input['policy'])) {
                         $User->term_accepted = 1;
                         }
				$User->password = Hash::make(rand(0,9).rand(0,9).rand(0,9).rand(0,9));
				$User->user_role = 2;
	
     
      /* save the following details */
      if($User->save()){
          
          DB::table('vendor_bank_info')->insert(
                    ['vendor_id' => $User->id]
                    );

 DB::table('vendor_categories')->insert(
                    ['vendor_id' => $User->id]
                    );
                    
                    DB::table('vendor_company_info')->insert(
                    ['vendor_id' => $User->id]
                    );
                    
                     DB::table('vendor_seo_info')->insert(
                    ['vendor_id' => $User->id]
                    );
                    
                      DB::table('vendor_support_info')->insert(
                    ['vendor_id' => $User->id]
                    );
                    
                      DB::table('vendor_tax_info')->insert(
                    ['vendor_id' => $User->id]
                    );
                    
		  $request->session()->flush();
		  $request->session()->push('vendor_details', [
					'id' => $User->id,
					'phone' =>  $input['phone'],
					'email' => $input['email'],
					'OTPmethod' => 0
				]);
		  
		  $data = [
		'message' => 'This is a test!',
		'to' => $input['email'],
		'phone' => $input['phone'],
		'from' => 'vendor@mailinator.com',
		'cc' => 'vendor@mailinator.com',
		'bcc' => 'vendor@mailinator.com',
		'replyTo' => 'vendor@mailinator.com',
		'subject'=>'Email Otp',
		'from_name'=>'yogendra verma',
		'to_name'=>'yogendra verma',
		'method'=>'email_otp'
		];

		
			 CommonHelper::generate_otp($data);
			 //CommonHelper::email_verification_mail($data);
			MsgHelper::save_session_message('success',Config::get('messages.common_msg.vendor_register_success'),$request);
			return redirect()->route('vendor_register', ['level' => base64_encode(1)]);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.vendor_register_error'),$request);
      }
      return Redirect::back();
			 break;
			 
				case 1;
					$input=$request->all();
					$request->validate([
					'username' => 'required|unique:vendors,username,1,isdeleted|max:50|min:6',
					'password' => 'required|max:20 min:6',
                    'confirm_password' => 'min:6|required_with:password|same:password',
                    'gst_no' => 'min:15|max:15',
                    'pincode' => 'min:6|max:6',
					'otp' => 'required|max:6',
					]);
				$vendor_details = session('vendor_details');
						$record = DB::table('phone_otp')
						->where('phone',$vendor_details[0]['phone'])
						->where('otp',$input['otp'])
						->first();
						if($record){
							
						if($record->expired_on<Carbon::now()){
							 return Redirect::back()->withErrors(['OTP  Expired.']);
							
						} else{
						    
						    	DB::table('vendor_tax_info')
								->where('vendor_id', $vendor_details[0]['id'])
								->update([
								'gst_no' =>$input['gst_no']
								]);
								
								
								DB::table('vendor_company_info')
								->where('vendor_id', $vendor_details[0]['id'])
								->update([
                                    'pincode' =>$input['pincode'],
                                    'name' =>$input['company_name']
								]);
						    
								DB::table('vendors')
								->where('phone', $vendor_details[0]['phone'])
								->update([
								'password' =>Hash::make($input['password'])  ,
								'is_phone_verify'=>1,
								'username'=>$input['username'],
								'application_level'=>1
								]);
								
							
								
							MsgHelper::save_session_message('success',Config::get('messages.common_msg.vendor_register_success'),$request);
							return redirect()->route('vendor_register', ['level' => base64_encode(2)]);
						}
							
								
						} else{
							   return Redirect::back()->withErrors(['Invalid OTP.']);

						}
					
					
				break;
			 
				case 2;
				$input=$request->all();
				$vendor_details = session('vendor_details');
			
				
				$cats_string='';
	if (array_key_exists("cat",$input))
				{
				
				$cats=$input['cat'];
				sort($cats, SORT_NATURAL | SORT_FLAG_CASE);
				$cats_string=implode(",",$cats);
				$res=DB::table('vendor_categories')
					->where('vendor_id', $vendor_details[0]['id'])
					->update([
					   'selected_cats' =>$cats_string
					]);
					
                $data = [
                            'to' => $vendor_details[0]['email'], // used in email verification mail to index is set there 
                    		'email' => $vendor_details[0]['email'],
                    		'phone' => $vendor_details[0]['phone']
                    	];
                    	
                //CommonHelper::vendor_registration_success($data);	
                CommonHelper::email_verification_mail($data);
					
					MsgHelper::save_session_message('success',Config::get('messages.common_msg.vendor_register_success'),$request);
				return redirect()->route('vendor_login');
			}
				
				else
				{
					return Redirect::back()->withErrors(['Please select category']);
					
				}
				break;
		 }
			
   
	
	 
		}
		
		switch($level){
			 case 0;
			//return view('auth.vendor_register',['page_details'=>$page_details]);
				return view('fronted.mod_pages.seller_register',['page_details'=>$page_details]);
			 break;
			 
				case 1;
				return view('auth.vendor_phone_verification',['page_details'=>$page_details]);
				break;
			 
				case 2;
				 return view('auth.vendor_category_selection',['page_details'=>$page_details]);
				break;
		 }
		

    }
	
	public function vendor_login(Request $request)
    {
			 $page_details=array(
       "Title"=>"Vendor Login",
	     "Method"=>"1",
       "Box_Title"=>"",
       "Action_route"=>route('vendor_login'),
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
			  'value'=>'Start Selling'
		)
         )
       )
     );
		if ($request->isMethod('post')) {
			$input=$request->all();
			 $request->validate([
                'email' => 'required|max:255',
				'password' => 'required|max:255'
            ]);

			
			$validate_admin = DB::table('vendors')
			->where('email', $request->email)
			->where('isdeleted',0)
		      ->first();
		      
		
if ($validate_admin && Hash::check($request->password, $validate_admin->password)) {
	
	
	
	if($validate_admin->is_phone_verify!=1){
		   return Redirect::back()->withErrors(['Your phone is not verified.']);
	}
	
		
	if($validate_admin->is_email_verify!=1){
	    
                    $data = [
                    'to' => $validate_admin->email
                    ];
                     $data = [
                        		'message' => 'This is a test!',
                        		'to' => $validate_admin->email,
                        		'phone' => '',
                        		'from' => 'vendor@mailinator.com',
                        		'cc' => 'vendor@mailinator.com',
                        		'bcc' => 'vendor@mailinator.com',
                        		'replyTo' => 'vendor@mailinator.com',
                        		'subject'=>'Email Otp',
                        		'from_name'=>'yogendra verma',
                        		'to_name'=>'yogendra verma',
                        		'method'=>'email_otp'
                        		];
                    	CommonHelper::email_verification_mail($data);
		   return Redirect::back()->withErrors(['Your email is not verified , plz check email to verify.']);
		   
		   
	}
		if($validate_admin->status!=1){
		   return Redirect::back()->withErrors(['Your account is not activated plz contact to admin.']);
	}
	
	if($validate_admin->term_accepted!=1){
		   return Redirect::back()->withErrors(['As You did not accepted term and condition ,can not login.']);
	}
} else{
	 return Redirect::back()->withErrors(['Credentials do not match our database .']);
}
		
			
			 if (Auth::guard('vendor')->attempt(['email' => $request->email, 'password' => $request->password ,'isdeleted' =>0,'status' =>1], $request->get('remember'))) {

            return redirect()->intended('admin/vendor_home');
        }
      return Redirect::back()->withErrors(['Credentials do not match our database .']);
	
		}
		//return view('auth.vendor_login',['page_details'=>$page_details]);
			return view('fronted.mod_pages.seller_login',['page_details'=>$page_details]);

    }
	
	public function test(Request $request)
    {
		 $page_details=array(
       "Title"=>"Vendor Login",
	     "Method"=>"1",
       "Box_Title"=>"",
       "Action_route"=>route('vendor_login'),
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
			  'value'=>'Start Selling'
		)
         )
       )
     );
	 return view('auth.vendor_category_selection',['page_details'=>$page_details]);
	}
	
	
	public function resend_otp(Request $request)
    {
			if ($request->isMethod('post')) {
				
				$vendor_details = session('vendor_details');
					$data = [
					'message' => 'This is a test!',
					'to' => $vendor_details[0]['email'],
					'phone' => $vendor_details[0]['phone'],
					'from' => 'vendor@mailinator.com',
					'cc' => 'vendor@mailinator.com',
					'bcc' => 'vendor@mailinator.com',
					'replyTo' => 'vendor@mailinator.com',
					'subject'=>'Email Otp',
					'from_name'=>'yogendra verma',
					'to_name'=>'',
					'method'=>'email_otp'
					];
			CommonHelper::generate_otp($data,1);
				echo json_encode( array(
				"MSG"=>"'OTP resent to your email as well on your given number'"
				));	
			
				}

    }
   
}
