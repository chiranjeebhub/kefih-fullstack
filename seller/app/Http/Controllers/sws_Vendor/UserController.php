<?php

namespace App\Http\Controllers\sws_Vendor;

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
        //  $this->middleware('vendor')->except('logout');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     public function index(Request $request){
         echo "hiiiii";
         
     }
     public function cron_track(Request $request){

		
$size =DB::table('product_attributes')->select('sizes.*','product_attributes.qty','product_attributes.product_id')					   	   
                     ->join('sizes','sizes.id','product_attributes.size_id')
                    //->where('product_attributes.product_id',$row->id)
					->groupBy('product_attributes.size_id')->orderBy('sizes.name','ASC')->get();
foreach($size as $size_row){
	
	$row=DB::table('products')->select('id','name')->where(['id'=>$size_row->product_id,'status'=>1,'isdeleted'=>0])->first();
	$details= base64_encode($size_row->product_id);						
				$prdimage=URL::to('admin/addStock/'.$details);
	if($size_row->qty < 11){
	
			        $email_data = [
                                    //'to'=>$cust_info->email,$row->name
                                    'subject'=>'Stock Notification',
                                    "body"=>view("emails_template.customer_order_ofd",
                                    array(
                                    'stock'=>$size_row->qty,
                                    'product'=>$row->name,
                                    'size'=>$size_row->name,
                                    'url'=>$prdimage
                                    ))->render(),
                                    'phone'=>'',
                                    'phone_msg'=>''
                                  ];

                    
                 CommonHelper::SendmailAdminCustom($email_data);

				//echo "success";		

			}	

		
			}
		

    }
     
      public function vendor_logout(Request $request){
          Auth::logout();
          Auth::guard('vendor')->logout();
         return redirect()->route('sellerLogin');
         
     }
      public function admin_logout(Request $request){
          Auth::logout();
          Auth::guard('vendor')->logout();
         return redirect()->route('sellerLogin');
         
     }
     
     public function vendor_phone_resend_otp(Request $request){
         	if ($request->isMethod('post')) {
				
				 $user_data=$request->session()->get('vendor_details');
			
				 $vendor_array=array(
                    "Phone"=>$user_data[0]['phone'],
                    "Email"=>$user_data[0]['email'],
                    "Id"=>$user_data[0]['id'],
					);
					
		          CommonHelper::vendor_resend_otpsend($vendor_array);
				echo json_encode( array(
				"MSG"=>"<br>".'OTP resent to your phone'
				));	
			
				}
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
				"MSG"=>"<br>".'OTP resent to your mail'
				));	
			
				}
     }
     public function verify_phone(Request $request){
            $user_data=$request->session()->get('vendor_forgot_password_details');
           
		
		if ($request->isMethod('post')) {
			
			 $input=$request->all();

			 $request->validate([
					'otp' => 'required|max:4|min:4'
            ]
			);
			
			 $user_data=$request->session()->get('vendor_forgot_password_details');
	
			$record = Vendor::where('phone_otp',$input['otp'])
                                ->where('email',$user_data['Email'])
					         	->first();
					         	
					         
						if($record){
						   	$res=Vendor::where('id',$user_data['Id'])
													->update([
    													'is_phone_verify'=>1
													]);
													 MsgHelper::save_session_message('success','Phone Verified',$request);
													return redirect()->route('vendor_login');
									
						} 
						else{
							return Redirect::back()->withErrors(['OTP did not matched']);
						}
			
		
		}	
        return view('auth.re_phone_verify');
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
	
			$record = Vendor::where('email_otp',$input['otp'])
                                ->where('email',$user_data['Email'])
					         	->first();
					         	
					         
						if($record){
						   	$res=Vendor::where('id',$user_data['Id'])
													->update([
													'password'=>Hash::make($input['password'])
													]);
													 MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
													return redirect()->route('vendor_login');
									
						} 
						else{
							return Redirect::back()->withErrors(['OTP did not matched']);
						}
			
		
		}	
        return view('auth.vendor_update_password');
       }
     
        public function vendor_forgot_password(Request $request){
            	if ($request->isMethod('post')) {
			  $input=$request->all();
				$Vendor= Vendor::where('email',$input['email'])->first();
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
                    "to"=>$Vendor->email,
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
					MsgHelper::save_session_message('success',Config::get('messages.common_msg.vendor_email_verification_success'),$request);
					return redirect()->route('vendor_login');
		} else{
			
				MsgHelper::save_session_message('success','Invalid Verification code',$request);
				return redirect()->route('vendor_login');
			
		}
	}
    public function vendor_register(Request $request)
    {
      $last_vendor = Vendor::orderby('id','DESC')->first();
	  $rid = $last_vendor->id + 1;
	  $rid = str_pad($rid, 4, '0', STR_PAD_LEFT);
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
            'placeholder'=>'Enter user name',
            'value'=>'',
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
              'label'=>'Enter the 4-digit code sent via SMS to your mobile number.',
            'type'=>'text',
            'name'=>'otp',
            'id'=>'otp',
            'classes'=>' form-control',
            'placeholder'=>'1234',
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
			  'classes'=>'btn btn-login-register btn-md btn-blue',
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
                        'password' => 'required|max:50 min:6',
                
            ]
			);
			$otp=rand(0,9).rand(0,9).rand(0,9).rand(0,9);
			$otp="1234";
            $User = new Vendor;
            $User->phone = $input['phone'];
            $User->email = $input['email'];
            $User->password = Hash::make($input['password']);
            $User->email_otp = $otp;
            $User->phone_otp =  $otp;
            $User->registration_id =  "KFH".$rid;
			
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
					'email' => $input['email']
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
			CommonHelper::email_verification_mail($data);
			// MsgHelper::save_session_message('success',Config::get('messages.common_msg.vendor_register_success'),$request);
			return redirect()->route('vendor_register', ['level' => base64_encode(1)]);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.vendor_register_error'),$request);
      }
      return Redirect::back();
			 break;
			 
				case 1;
					$input=$request->all();
					$request->validate([
                            'username' => 'required|unique:vendors,username,1,isdeleted|max:50|min:1',
                            'otp' => 'required|max:6',
                            'gst_no' => 'max:16',
                            //'company_name' => 'min:15|max:50',
                            'pincode' => 'max:100',
					],
					[
					    'pincode.max'=>'Company IE code is not more than 100 characters']);
				$vendor_details = session('vendor_details');
						$record = DB::table('vendors')
						->where('phone',$vendor_details[0]['phone'])
						->where('phone_otp',$input['otp'])
						->first();
						if($record){
							
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
								'is_phone_verify'=>1,
								'username'=>$input['username'],
								'application_level'=>1
								]);
				// 			MsgHelper::save_session_message('success',Config::get('messages.common_msg.vendor_register_success'),$request);
						return redirect()->route('vendor_register', ['level' => base64_encode(2)]);
							
								
						} else{
							   return Redirect::back()->withErrors(['OTP  mathed failed.']);

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
						DB::table('vendors')
								->where('id', $vendor_details[0]['id'])
								->update([
								'application_level'=>2
								]);
					MsgHelper::save_session_message('success',Config::get('messages.common_msg.vendor_register_success'),$request);
				return redirect()->route('vendor_login')->with('message','Seller registration successful.  Your account will be activated shortly.');;
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
    
        //   echo Hash::make(trim(12345678));;
    
//      	 $validate_admin = DB::table('vendors')
// 			->where('email', 'vendor@awam.com')
// 			->first();
		
// 			echo "<br>";
// if ($validate_admin && Hash::check('12345678', $validate_admin->password)) {
//     echo "yes";
// }
// else{
//     echo "no";
// }
// die();
 
			 $page_details=array(
       "Title"=>"Vendor Login",
	     "Method"=>"1",
       "Box_Title"=>"",
       "Action_route"=>route('sellerLogin'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "email_field"=>array(
              'label'=>'Email ID #',
            'type'=>'text',
            'name'=>'email',
            'id'=>'email',
            'classes'=>'form-control',
            'placeholder'=>'Email #',
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
                                    ->where('isdeleted', '=',0)
                                    ->where('status', '=',1)
                                    ->first();
	
			
if ($validate_admin) {
    if(Hash::check($request->password, $validate_admin->password)){
      
    	
	
	
		if($validate_admin->application_level==0){
                $request->session()->flush();
                $request->session()->push('vendor_details', [
                	'id' =>$validate_admin->id,
                	'phone' => $validate_admin->phone,
                	'email' =>$validate_admin->email
                ]);
                
		  return redirect()->route('vendor_register', ['level' => base64_encode(1)])->withErrors(['Complete your profile.']);
	}
		if($validate_admin->application_level==1){
            $request->session()->flush();
            $request->session()->push('vendor_details', [
                'id' =>$validate_admin->id,
                'phone' => $validate_admin->phone,
                'email' =>$validate_admin->email
            ]);
		  return redirect()->route('vendor_register', ['level' => base64_encode(2)])->withErrors(['Complete your profile.']);
	}
	

	
	if($validate_admin->is_phone_verify!=1){
		   
            	     $request->session()->put('vendor_forgot_password_details',array(
                        "Phone"=>$validate_admin->phone,
                        "Email"=>$validate_admin->email,
                        "Id"=>$validate_admin->id,
                        "Email_for"=>0,
                        ));
		   	return redirect()->route('verify_phone')->withErrors(['Your phone is not verified.']);
	}
	
	/*if($validate_admin->is_email_verify!=1){
                    $data = [
                    'to' => $validate_admin->email
                    ];
                    	CommonHelper::email_verification_mail($data);
		   return Redirect::back()->withErrors(['Your email is not verified , plz check email to verify.']);
		   
		   
	}*/
	
	
           if (Auth::guard('vendor')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            return redirect()->intended('admin/vendor_home');
        }else{
            return Redirect::back()->withErrors(['Something went wrong']);
        }
} else{
      file_put_contents('vednor.txt',json_encode("Password did not match"));
	 return Redirect::back()->withErrors(['Password did not match']);
}
	
		} else{
		      return Redirect::back()->withErrors(['No user found.']);
		    
		}	
			
		
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
			CommonHelper::generate_otp($data);
				echo json_encode( array(
				"MSG"=>"'OTP resent to your email as well on your given number'"
				));	
			
				}

    }
   
}
