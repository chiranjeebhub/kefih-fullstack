<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\Customer; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Validator;
use DB;
use App\Helpers\CommonHelper;
use Config;
error_reporting(0);
class CustomerController extends Controller 
{
    public $successStatus = 200;
	public $site_base_path='https://phaukat.com/';
		
		
		public function __construct()
    {
        $this->site_base_path=Config::get('constants.Url.public_url');
        //$this->middleware('auth');
    }
    
     public function testLogin(Request $request){
         $inputs=$request->all();
        $user=Customer::select('id')->where('phone',$inputs['user_name'])->first();
        $res=array(
            "error"=>1,
            "msg"=>"Something went wrong",
            "data"=>null
            );
        if($user){
            $res=array(
            "error"=>0,
            "msg"=>"login successfully done",
            "data"=>$user
            );
        }
          echo json_encode($res);  
    
     }
   public function order_track(Request $request){
	    
        $input = json_decode(file_get_contents('php://input'), true);
		$id=$input['order_detail_id'];
		$delivery=0;
		$shipping=0;
		$invoice=0;
		$pending=0;
		$track_data=DB::table('order_details')->where('id','=',$id)->first();
	
		 if($track_data->order_status==0 || $track_data->order_status==1 || $track_data->order_status==2 || $track_data->order_status==3){
		 	$pending = 1;
		 }
		 if($track_data->order_status==1 || $track_data->order_status==2 || $track_data->order_status==3){
		 	$invoice = 1;
		 }
		 if($track_data->order_status==2 || $track_data->order_status==3){
		 	$shipping = 1;
		 }
		 if($track_data->order_status==3){
		 	$delivery=1;
		 }
		
		//date("h:sa M d, Y")date('M d, Y',strtotime($row->fld_wallet_date))
		$data = array(
		    //"fld_order_track_id"=> 1,
            "fld_order_detail_id"=> $id,
            "fld_pending_order"=> $pending,
            "fld_order_date"=>date('h:sa M d, Y',strtotime($track_data->order_date)),
            "fld_invoice_order"=> $invoice,
            "fld_invoice_date"=> date('h:sa M d, Y',strtotime($track_data->order_detail_invoice_date)),
            "fld_shipping_order"=> $shipping,
            "fld_shipping_date"=> ($track_data->order_detail_onshipping_date)?$track_data->order_detail_onshipping_date:'',
            "fld_order_intransit"=> 0,
            "fld_order_outofdelivery"=> 0,
            "fld_delivered_order"=> $delivery,
            "fld_delivered_date"=>date('h:sa M d, Y',strtotime($track_data->order_detail_delivered_date))
		);
		

		
		$res=array(
				"status"=>true,
				"statusCode"=>201,
				"order_track_data"=>$data
				);			
		echo json_encode($res);	
		
    }
	  
    
    
    public function getProfileOTP(Request $request){
		$input = json_decode(file_get_contents('php://input'), true);
		
		$validate_user = DB::table('customers')->where('id',$input['fld_user_id'])->first();
		CommonHelper::generate_profile_otp(array(
                            "phone"=>$input['fld_user_phone'],
                            "email"=>$input['fld_user_email'],
                            "userId"=>$input['fld_user_id']
                            ));
			$res=array(
				"status"=>true,
				"statusCode"=>201,
				"message"=>"phaukat E-shop send OTP for profile chanage.",
				//"isOtpVerified"=>'0',
				"login_data"=>$validate_user
				);			
		echo json_encode($res);	
	}
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
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
     
      public function getReason(Request $request) 
	{
		
		$input = json_decode(file_get_contents('php://input'), true);
		echo "<pre>";
		print_r($input);
	}
	public function getProfileImage(Request $request) 
	{
	    	$input = json_decode(file_get_contents('php://input'), true);
	    	$user= Customer::select(
						'profile_pic'
				)->where('id',$input['fld_user_id'])->first();
				
				
                $cart_count=DB::table('cart')->where('user_id',$input['fld_user_id'])->count();
                $wish_list_count=DB::table('tbl_wishlist')->where('fld_user_id',$input['fld_user_id'])->count();
				
	    if(@$user->profile_pic!=''){
	        $url=$this->site_base_path.Config::get('constants.uploads.customer_profile_pic').'/'.$user->profile_pic;
	      
	    } else{
	         $url=$this->site_base_path.Config::get('constants.uploads.customer_profile_pic').'/no_user_Image.png';
	    }
	    $res=array(
                                    "status"=>true,
                                    "statusCode"=>201,
                                    "message"=>"Profile data return",
                                    "Profile_data"=>$url,
                                    "cart_count"=>$cart_count,
                                    "wishlist_count"=>$wish_list_count
							);
            
            echo json_encode($res);
	}
	public function setProfileImage(Request $request) 
	{
	    	$input = json_decode(file_get_contents('php://input'), true);
	     if($input['fld_profile_image']!=''){
	          $img = $input['fld_profile_image'];
                    $img = str_replace('data:image/png;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $img_data = base64_decode($img);
                    $name=uniqid() . '.png';
                    $uploads=$name;
                    $file = Config::get('constants.uploads.customer_profile_pic').'/'.$name ;
                    file_put_contents($file, $img_data);
                    	$res=Customer::where('id',$input['fld_user_id'])
													->update([
													'profile_pic'=>$uploads
													]);
													
													if($res){
													      $url=$this->site_base_path.Config::get('constants.uploads.customer_profile_pic').'/'.$name;
													    $res=array(
                                                        "status"=>true,
                                                        "statusCode"=>201,
                                                        "message"=>"Image Updated",
                                                        "Profile_data"=>$url
                                                        );
													} else{
                                                        $res=array(
                                                        "status"=>true,
                                                        "statusCode"=>404,
                                                        "message"=>"Something went wrong",
                                                        "Profile_data"=>null
                                                        );
													}
                    
                    
	     }  else{
                $res=array(
                    "status"=>true,
                    "statusCode"=>404,
                    "message"=>"Plz select profile image",
                    "Profile_data"=>null
                );
						
	     }
	     echo json_encode($res);
	}
     public function contactUs(Request $request) 
	{
		
		$input = json_decode(file_get_contents('php://input'), true);
        
	if(!$input){
                 	$res=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>Config::get('messages.common_msg.unsuccessAPIRequest'), 
				"signup_data"=>null
				);
				echo json_encode($res);
				die();
            }
            
            $res=array(
							"status"=>true,
							"statusCode"=>201,
							"message"=>Config::get('messages.common_msg.contactUsSuccess')
							);
            
            echo json_encode($res);
            
	}
	
	public function mobile_login(Request $request) 
	{
		
		$input = json_decode(file_get_contents('php://input'), true);
			file_put_contents('sdfgfgfgfghghlogin.txt',file_get_contents('php://input'));
        
	    if(!$input){
                 	$res=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>"Something went wrong",
				"signup_data"=>null
				);
				echo json_encode($res);
				die();
        }
		
		if ($input['fld_user_phone']!='') {
		  
		  $validate_user= Customer::select(
                        'id as fld_user_id',
                        'name as fld_user_name',
                          'last_name as fld_l_name',
                        'email as fld_user_email',
                        'phone as fld_user_phone',
                        'address as fld_user_address',
                        'address1 as fld_user_locality',
						'city as fld_user_city',
						'state as fld_user_state',
						'pincode as fld_user_pincode',
						'isOtpVerified',
						'password'
				)->where('phone',$input['fld_user_phone'])->first();
				
			if ($validate_user=="") {
				$res=array(
					"status"=>false,
					"statusCode"=>404,
					"message"=>"Mobile no. not found",
					"isOtpVerified"=>0,
					"login_data"=>null
					);
					
				echo json_encode($res);
				exit;
			}else{
			    $user_id=$validate_user->fld_user_id;
			    
			    $custmor_array=array(
							"phone"=>$validate_user->fld_user_phone,
							"userId"=>$validate_user->fld_user_id
						);
						  
			//	CommonHelper::generate_user_otp($custmor_array);
			    
			    $res=array(
							"status"=>true,
							"statusCode"=>201,
							"message"=>"OTP Send",
							"login_otp_data"=>$validate_user
							);
							
				echo json_encode($res);
			}
			
		}
		
			
		}
		
   public function OTPlogin(Request $request)
    {
       
		$input = json_decode(file_get_contents('php://input'), true);
		
		$fake_data=array(
			            'fld_user_id'=>0,
                        'fld_user_name'=>'',
                        'fld_l_name'=>'',
                        'fld_user_email'=>'',
                        'fld_user_phone'=>'',
                        'fld_user_address'=>'',
                        'fld_user_locality'=>'',
						'fld_user_city'=>'',
						'fld_user_state'=>'',
						'fld_user_pincode'=>'',
						'isOtpVerified'=>0,
						'r_code'=>'',
						'gender'=>'',
						'dob'=>'',
						'status'=>0,
						'password'=>''
			     );
	    if(!$input){
                 	$res=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>"Something went wrong",
				"signup_data"=>null
				);
				echo json_encode($res);
				die();
        }
		
		if ($input['fld_user_phone']!='') {
		   
		$validate_user= Customer::select(
						'id',
						'name',
						'email',
						'phone',
						'id as fld_user_id',
                        'name as fld_user_name',
                        'last_name as fld_l_name',
                        'email as fld_user_email',
                        'phone as fld_user_phone',
                        'address as fld_user_address',
                        'address1 as fld_user_locality',
						'city as fld_user_city',
						'state as fld_user_state',
						'pincode as fld_user_pincode',
						'gender',
						'dob',
						'r_code',
						'status',
						'password'
			)
			
			->where('phone',trim($input['fld_user_phone']))
			->orwhere('email',trim($input['fld_user_phone']))
			->first();

		
if ($validate_user) {
	
	if($validate_user->status!=1){
		
		
		       
		  $res=array(
				"status"=>true,
				"statusCode"=>201,
				"message"=>"Your account is deactive contact to administrator",
				//"isOtpVerified"=>'0',
				"login_data"=>$validate_user
				);			
		echo json_encode($res);	     
			
			
	} 
else{
		if (filter_var($input['fld_user_phone'], FILTER_VALIDATE_EMAIL)) {
		    
		    
		          //CommonHelper::generate_user_otp(array(
            //                 //"Phone"=>$validate_user->phone,
            //               // "password"=>$request->password,
            //                 "phone"=>$validate_user->phone,
            //                 "userId"=>$validate_user->id
            //                 ));
			$res=array(
				"status"=>true,
				"statusCode"=>201,
				"message"=>"phaukat E-shop send OTP to your register email.",
				"isOtpVerified"=>'0',
				"login_data"=>$validate_user
				);			
		echo json_encode($res);	
					} else{
                           
                        //     CommonHelper::generate_user_otp(array(
                        //     "phone"=>$validate_user->phone,
                        //   // "password"=>$request->password,
                        //     //"email"=>$validate_user->email,
                        //     "userId"=>$validate_user->id
                        //     ));
                $res=array(
				"status"=>true,
				"statusCode"=>201,
				"message"=>"phaukat E-shop send OTP to your register mobile number continue to login.",
				"isOtpVerified"=>'0',
				"login_data"=>$validate_user
				);			
		echo json_encode($res);	
					}

	}
} else{
	
	 $res=array(
				"status"=>true,
				"statusCode"=>201,
				"message"=>"Mobile or Email does not exits.",
				"login_data"=>$fake_data
				);			
		echo json_encode($res);	
}

	
		}
		
    }
		
	public function jlogin(Request $request)
    {
        $input = json_decode(file_get_contents('php://input'), true);
		//file_put_contents('logincccc.txt',json_encode($input));
        //print_r($input);die;
        
    	$fake_data=array(
			            'fld_user_id'=>0,
                        'fld_user_name'=>'',
                        'fld_l_name'=>'',
                        'fld_user_email'=>'',
                        'fld_user_phone'=>'',
                        'fld_user_address'=>'',
                        'fld_user_locality'=>'',
						'fld_user_city'=>'',
						'fld_user_state'=>'',
						'fld_user_pincode'=>'',
						'isOtpVerified'=>0,
						'r_code'=>'',
						'gender'=>'',
						'dob'=>'',
						'status'=>0,
						'password'=>''
			     );
			     
	    
		if ($input['fld_user_phone']!='') 
		{
			
    		$validate_user= Customer::select(
    						'id as fld_user_id',
                            'name as fld_user_name',
                            'last_name as fld_l_name',
                            'email as fld_user_email',
                            'phone as fld_user_phone',
                            'address as fld_user_address',
                            'address1 as fld_user_locality',
    						'city as fld_user_city',
    						'state as fld_user_state',
    						'pincode as fld_user_pincode',
    						'isOtpVerified',
    						'r_code',
    						'gender',
    						'dob',
    						'status',
    						'password'
    			          )
			
			->where('phone',trim($input['fld_user_phone']))
			->orwhere('email',trim($input['fld_user_phone']))
			->first();
       	
		if($validate_user->fld_user_id !='')
		{
    		if ($validate_user && Hash::check($input['fld_user_password'], $validate_user->password)) 
    		{
    			$user_data = array(
        			"fld_user_id"=>$validate_user->fld_user_id,
        			"fld_user_name"=>$validate_user->name,
        			"fld_l_name"=>$validate_user->last_name,
        			"fld_user_email"=>$validate_user->email,
        			"r_code"=>$validate_user->r_code,
        			"fld_user_address"=>$validate_user->fld_user_address,
        			"gender"=>$validate_user->gender,
        			"dob"=>$validate_user->dob
    			);
    			
               if($validate_user->status==1){
               	
               	    if($validate_user->isOtpVerified==1){
               	    	
               	    	$res=array(
    							"status"=>true,
    							"statusCode"=>201,
    							"message"=>"login successfully done",
    							"isOtpVerified"=>$validate_user->isOtpVerified,
    							"login_data"=>$validate_user
    							);
               	    	
    				}else{
    					$custmor_array=array(
    					"phone"=>$validate_user->fld_user_phone,
    					"userId"=>$validate_user->fld_user_id
    					);
                		//CommonHelper::generate_user_otp($custmor_array);
                         $res=array(
                				"status"=>true,
                				"statusCode"=>201,
                				"message"=>"phaukat E-shop send OTP to your register mobile number verify to continue login.",
                				"login_data"=>$validate_user
                				);			
                		
    				}
    				
    			}else{
    					$res=array(
    						"status"=>TRUE,
    						"statusCode"=>201,
    						"message"=>"Your account is deactive contact to administrator",
    						"login_data"=>$validate_user
    						);
    						
    			        
    			}	
    		}else{
    				 $res=array(
    							"status"=>FALSE,
    							"statusCode"=>404,
    							"message"=>Config::get('messages.common_msg_API.incorrectdetails'),
    							"login_data"=>$fake_data
    							);
    							
    				
    		}
	                   
		}else{
				 
				 $res=array(
							"status"=>FALSE,
							"statusCode"=>404,
							"message"=>Config::get('messages.common_msg_API.incorrectdetails'),
							"login_data"=>$fake_data
							);
			}
        
		}else{
		
			$res=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>"Email or Mobile and Password is required.",
				"login_data"=>$fake_data
				);
		
		}
		echo json_encode($res);
    }
	
	
	
    public function login(Request $request) 
	{
		
		$input = json_decode(file_get_contents('php://input'), true);
			file_put_contents('logincontent.txt',json_encode($input));
        
	          if(!$input){
                 	$res=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>"Something went wrong",
				"signup_data"=>null
				);
				echo json_encode($res);
				die();
            }
		
		if (filter_var($input['fld_user_phone'], FILTER_VALIDATE_EMAIL)) {
		  $validate_email= Customer::select(
						'id as fld_user_id',
						'name as fld_user_name',
						  'last_name as fld_l_name',
						'email as fld_user_email',
						'phone as fld_user_phone',
						'address as fld_user_address',
						'address1 as fld_user_locality',
						'city as fld_user_city',
						'state as fld_user_state',
						'pincode as fld_user_pincode',
						'isOtpVerified',
						'password'
				)->where('email',$input['fld_user_phone'])->first();
				
			if ($validate_email=="") {
				$res=array(
					"status"=>false,
					"statusCode"=>404,
					"message"=>"Email Address not found",
					"isOtpVerified"=>0,
					"login_data"=>null
					);
					
				echo json_encode($res);
				exit;
			}
		
		} else {
		  $validate_user= Customer::select(
                        'id as fld_user_id',
                        'name as fld_user_name',
                          'last_name as fld_l_name',
                        'email as fld_user_email',
                        'phone as fld_user_phone',
                        'address as fld_user_address',
                        'address1 as fld_user_locality',
						'city as fld_user_city',
						'state as fld_user_state',
						'pincode as fld_user_pincode',
						'isOtpVerified',
						'status',
						'password'
				)->where('phone',$input['fld_user_phone'])->first();
				
			if ($validate_user=="") {
				$res=array(
					"status"=>false,
					"statusCode"=>404,
					"message"=>"Mobile no. not found",
					"isOtpVerified"=>0,
					"login_data"=>null
					);
					
				echo json_encode($res);
				exit;
			}
			
		}
		if (!Hash::check($input['fld_user_password'], $validate_user->password)){
		    
		    $res=array(
					"status"=>false,
					"statusCode"=>404,
					"message"=>"Phone/Email Or password is not matched",
					"isOtpVerified"=>0,
					"login_data"=>null
					);
					
				echo json_encode($res);
				exit;
				
		}else if ($validate_user && Hash::check($input['fld_user_password'], $validate_user->password)) {
	      
		   
					if($validate_user->isOtpVerified==0){
						
						$custmor_array=array(
							"phone"=>$validate_user->fld_user_phone,
							"userId"=>$validate_user->fld_user_id
						);
						  
						  //CommonHelper::generate_user_otp($custmor_array);
						
							$res=array(
							"status"=>true,
							"statusCode"=>404,
							"message"=>"Phone not verified",
							"isOtpVerified"=>$validate_user->isOtpVerified,
							"login_data"=>$validate_user
							);
					} 
                else{
						$res=array(
							"status"=>true,
							"statusCode"=>201,
							"message"=>"login successfully done",
							"isOtpVerified"=>$validate_user->isOtpVerified,
							"login_data"=>$validate_user
							);
					}	
				
		}  else{
					$res=array(
								"status"=>true,
								"statusCode"=>404,
								"message"=>"no user found",
								"isOtpVerified"=>0,
								"login_data"=>null
								);
				}

			echo json_encode($res);
		}

 public function signup(Request $request)
       {
                   
	   $input = json_decode(file_get_contents('php://input'), true);
	   
	   $fake_data=array(
					"fld_user_id"=>0,
					"fld_user_email"=>"",
					"fld_user_phone"=>0,
					"fld_user_name"=>"",
					"r_code"=>""
			 );
	   
 	   if(!$input){
                $res=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>"Something went wrong",
				"signup_data"=>$fake_data
				);
			echo json_encode($res);
				die();
        }
	
		$isemailExist= Customer::where('email',$input['fld_user_email'])->first();
 	    if($isemailExist){
				$res=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>"Email already exists",
				"signup_data"=>$fake_data
				);
				echo json_encode($res);
				die();
				
			}
			
	$isphoneExist= Customer::where('phone',$input['fld_user_phone'])->first();
 	if($isphoneExist){
				$res=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>"Phone already exists",
				"signup_data"=>$fake_data
				);
				echo json_encode($res);
				die();
			}
		  
	    $Customer = new Customer;
        $Customer->name = $input['fld_user_name'];
        $Customer->email = $input['fld_user_email'];
        $Customer->password = Hash::make($input['fld_user_password']);
        $Customer->phone =$input['fld_user_phone'];
        $Customer->device_id =$input['fld_device_id'];
        $Customer->status =1;
     
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
                  
		
		 
				$custmor_array=array(
					"phone"=>$input['fld_user_phone'],
					"userId"=>$Customer->id
					);
		      //CommonHelper::generate_user_otp($custmor_array);
			$signup_data=array(
					"fld_user_id"=>$Customer->id,
					"fld_user_email"=>$input['fld_user_email'],
					"fld_user_phone"=>$input['fld_user_phone'],
					"fld_user_name"=>$input['fld_user_name'],
					"r_code"=>@$input['r_code']
			 );
			$res=array(
				"status"=>true,
				"statusCode"=>201,
				"message"=>"account created successfully send OTP your register mobile or email verify account to continue shopping.",
				"signup_data"=>$signup_data
				);
			echo json_encode($res);
			
      }  

      else{
				 $res=array(
						"status"=>false,
						"statusCode"=>404,
						"message"=>"something went wrong",
						"signup_data"=>$fake_data
						);
			echo json_encode($res);
	}

		
		}		
		
	
 
			public function signup_yogi(Request $request) 
			{ 
// 			$input=$request->all();
			  $input = json_decode(file_get_contents('php://input'), true);
            
            if(!$input){
                 	$res=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>"Something went wrong",
				"signup_data"=>null
				);
				echo json_encode($res);
				die();
            }
           
           if(
            $input['fld_user_email']=='' ||
            $input['fld_user_phone']=='' || 
            $input['fld_user_name']==''||
            $input['fld_user_password']==''
           ){
              	$res=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>"Plz fill all the fields",
				"signup_data"=>null
				);
				echo json_encode($res);
				die(); 
           }
 $isemailExist= Customer::where('email',$input['fld_user_email'])->first();
 	if($isemailExist){
				
				$res=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>"Email already exists",
				"signup_data"=>null
				);
				echo json_encode($res);
				die();
				
			}
			
	$isphoneExist= Customer::where('phone',$input['fld_user_phone'])->first();
 	if($isphoneExist){
				
				$res=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>"Phone already exists",
				"signup_data"=>null
				);
				echo json_encode($res);
				die();
				
			}
			
				
		
		
				
		                $Customer = new Customer;
                        $Customer->name = $input['fld_user_name'];
                        $Customer->email = $input['fld_user_email'];
                        $Customer->password = Hash::make($input['fld_user_password']);
                        $Customer->phone =$input['fld_user_phone'];
                        $Customer->device_id =$input['fld_device_id'];
					
				// 	if($input['fld_profile_image']!=''){
				// 	    $img = $input['fld_profile_image'];
    //                 $img = str_replace('data:image/png;base64,', '', $img);
    //                 $img = str_replace(' ', '+', $img);
    //                 $img_data = base64_decode($img);
    //                 $name=uniqid() . '.png';
    //                 $uploads=$name;
    //                 $file = Config::get('constants.uploads.customer_profile_pic').'/'.$name ;
    //                 file_put_contents($file, $img_data);
    //                 	$Customer->profile_pic =$name;
				// 	}
	
     
      if($Customer->save()){
		  
					$custmor_array=array(
					"phone"=>$input['fld_user_phone'],
					"userId"=>$Customer->id
					);
		       //CommonHelper::generate_user_otp($custmor_array);
		  $signup_data=array(
					"fld_user_id"=>$Customer->id,
					"fld_user_email"=>$input['fld_user_email'],
					"fld_user_phone"=>$input['fld_user_phone'],
					"fld_user_name"=>$input['fld_user_name']
			 );
				$res=array(
				"status"=>true,
				"statusCode"=>201,
				"message"=>"Registration done successfully",
				"signup_data"=>$signup_data
				);
			} 
else{
			    $res=array(
						"status"=>false,
						"statusCode"=>404,
						"message"=>"something went wrong"
						);
			    
			}
		
				echo json_encode($res);
			}
			public function verify(Request $request) {
			   
				    $input = json_decode(file_get_contents('php://input'), true);
				    
				   if(!$input){
                 	$res=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>"Something went wrong",
				"signup_data"=>null
				);
				echo json_encode($res);
				die();
            }
				$record =Customer::where('id',$input['fld_user_id'])
						//->where('flag',$input['flag'])
						->first();
						//print_r($record);die;
						if($record){
                                if($input['otp']==false){
                                //  if($record->expired_on<Carbon::now()){
                                	$res=array(
                                	"status"=>true,
                                	"statusCode"=>404,
                                	"message"=>"OTP expired",
                                	"user_data"=>null
                                	);
                                } 
                           if($input['otp']==true){
							$res=Customer::where('id',$input['fld_user_id'])
													->update([
													'isOtpVerified'=>1,
													
													]);
								$user_data= Customer::select(
								'id as fld_user_id',
								'name as fld_user_name',
								'email as fld_user_email',
								'phone as fld_user_phone',
								'isOtpVerified',
								'r_code'
								)->where('id',$input['fld_user_id'])->first();
								
								$res=array(
									"status"=>true,
									"statusCode"=>201,
									"message"=>"Verification Done",
									"user_data"=>$user_data
									);	
								/*if($input['flag']==0){
									$res=array(
									"status"=>true,
									"statusCode"=>201,
									"message"=>"Verification Done",
									"user_data"=>$user_data
									);	
								} else{
								    	$res=array(
									"status"=>true,
									"statusCode"=>201,
									"message"=>"Verification Done",
									"user_data"=>null
									);	
								//}*/
						
						} else{
						    	$res=array(
									"status"=>true,
									"statusCode"=>404,
									"message"=>"OTP invalid",
									"user_data"=>null
									);
						}
									
						} 
						else{
							$res=array(
									"status"=>true,
									"statusCode"=>404,
									"message"=>"No user found",
									"user_data"=>null
									);
						}
						
						echo json_encode($res);
			}
			
			public function resend_otp(Request $request) {
			    
			      $input = json_decode(file_get_contents('php://input'), true);
			      if(!$input){
                 	$res=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>"Something went wrong",
				//"signup_data"=>null
				);
				echo json_encode($res);
				die();
            }
				
				 $user= Customer::where('phone',$input['fld_user_phone'])->first();
				if($user){
					
					$custmor_array=array(
                            "phone"=>$input['fld_user_phone'],
                            "userId"=>$user->id,
                            "flag"=>$input['flag']
					);
					  
					  //CommonHelper::generate_user_otp($custmor_array);
					
					if($input['flag']){
					$res=array(
						"status"=>true,
						"statusCode"=>201,
				    	"message"=>"OTP is sent to your register phone number"
						);
					} else{
					   $res=array(
						"status"=>true,
						"statusCode"=>201,
				    	"message"=>"OTP is sent to your number"
						); 
					}
					
						
				} else{
					$res=array(
						"status"=>true,
						"statusCode"=>404,
						"message"=>"something went wrong"
						);
				}
				echo json_encode($res);
			}
			
			public function forgot_password(Request $request) {
	           $input = json_decode(file_get_contents('php://input'), true);
	          
			      if(!$input){
                 	$res=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>"Something went wrong",
				"signup_data"=>null
				);
				echo json_encode($res);
				die();
            }
				 $user= Customer::where('phone',$input['fld_user_phone'])->first();
				if($user){
					
					$custmor_array=array(
				 "phone"=>$input['fld_user_phone'],
                            "userId"=>$user->id,
                            "flag"=>$input['flag']
					);
					  
					  //CommonHelper::generate_user_otp($custmor_array);
					
					
						$res=array(
						"status"=>true,
						"fld_user_id"=>$user->id,
						"statusCode"=>201,
						"message"=>"OTP is sent to your register phone number"
						);
				} else{
					$res=array(
						"status"=>true,
							"fld_user_id"=>null,
						"statusCode"=>404,
						"message"=>"something went wrong"
						);
				}
				echo json_encode($res);
}

public function update_password(Request $request) {
	 $input = json_decode(file_get_contents('php://input'), true);
// 	 file_put_contents('sdf.txt',file_get_contents('php://input'));
	          if(!$input){
                 	$res=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>"Something went wrong",
				"signup_data"=>null
				);
				echo json_encode($res);
				die();
            }
			     
						    	$res=Customer::where('phone', $input['fld_user_phone'])
									->update([
									'password' =>Hash::make($input['fld_user_password'])
									]);
									
									if($res){
										
									
			
										$res=array(
								"status"=>true,
								"statusCode"=>201,
								"message"=>"password changed",
							
								);
									} else{
										$res=array(
								"status"=>true,
								"statusCode"=>404,
								"message"=>"something went wrong",
								
								);
									}
						
						
					
							echo json_encode($res);
}
public function userProfile(){
    $input = json_decode(file_get_contents('php://input'), true);
    if(!$input){
                 	$res=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>"Something went wrong",
				"signup_data"=>null
				);
				echo json_encode($res);
				die();
            }
    	 $validate_user= Customer::select(
					    'id as fld_user_id',
					    'r_code',
					    'dob',
					    'address',
					    'gender',
					    'total_reward_points as total_reward_points',
					     DB::raw("(IF(customers.profile_pic!='', CONCAT('".$this->site_base_path."uploads/customers/profile_pic/',customers.profile_pic),null)) as fld_profile_image")
			)->where('id',$input['fld_user_id'])->first();
				if($validate_user){
				    
				    $check_how_address=DB::table('customer_shipping_address')
				    ->where('customer_id',$input['fld_user_id'])
				    ->get();
				    if(sizeof($check_how_address)>0){
                                $dfa=DB::table('customer_shipping_address')
                                ->where('customer_id',$input['fld_user_id'])
                                ->where('shipping_address_default',1)
                                ->first();
				    
				         if($dfa){
				              $validate_user->fld_wallet_amt=$validate_user->total_reward_points;
				               $validate_user->fld_shipping_add_flag=true;
				        $validate_user->fld_shipping_id=$dfa->id;
				         $validate_user->fld_shipping_name=$dfa->shipping_name;
				          $validate_user->fld_shipping_mobile=$dfa->shipping_mobile;
                        $validate_user->fld_shipping_email=$dfa->shipping_email;
                        
                        $validate_user->fld_shipping_user_city=$dfa->shipping_city;
                        $validate_user->fld_shipping_user_address=$dfa->shipping_address;
                        $validate_user->fld_shipping_user_locality=$dfa->shipping_address1;
                        $validate_user->fld_shipping_user_state=$dfa->shipping_state;
                        $validate_user->fld_shipping_user_pincode=$dfa->shipping_pincode;
                        $validate_user->fld_shipping_address_type=$dfa->shipping_address_type;
                        $validate_user->r_code =$validate_user->r_code;
                        $validate_user->gender =$validate_user->gender;
                     $validate_user->address =$validate_user->address;
                    $validate_user->dob =$validate_user->dob;
                        
				    } else{
				          $dfa=DB::table('customer_shipping_address')
                                ->where('customer_id',$input['fld_user_id'])
                                ->first();
                                $validate_user->fld_wallet_amt=$validate_user->total_reward_points;
                                 $validate_user->fld_shipping_add_flag=true;
                     $validate_user->fld_shipping_id=$dfa->id;
				         $validate_user->fld_shipping_name=$dfa->shipping_name;
				          $validate_user->fld_shipping_mobile=$dfa->shipping_mobile;
                        $validate_user->fld_shipping_email=$dfa->shipping_email;
                        
                        $validate_user->fld_shipping_user_city=$dfa->shipping_city;
                        $validate_user->fld_shipping_user_address=$dfa->shipping_address;
                        $validate_user->fld_shipping_user_locality=$dfa->shipping_address1;
                        $validate_user->fld_shipping_user_state=$dfa->shipping_state;
                        $validate_user->fld_shipping_user_pincode=$dfa->shipping_pincode;
                        $validate_user->fld_shipping_address_type=$dfa->shipping_address_type;
				    }
				        
				        
				        
				    } else{
				        $validate_user->fld_wallet_amt=$validate_user->total_reward_points;
				         $validate_user->fld_shipping_add_flag=false;
				         $validate_user->fld_shipping_id=0;
				        $validate_user->fld_shipping_name="";
				          $validate_user->fld_shipping_mobile="";
                        $validate_user->fld_shipping_email="";
				      
                        $validate_user->fld_shipping_user_city="";
                        $validate_user->fld_shipping_user_address="";
                        $validate_user->fld_shipping_user_locality="";
                        $validate_user->fld_shipping_user_state=" ";
                        $validate_user->fld_shipping_user_pincode="";
                        $validate_user->fld_shipping_address_type="";
				    }
				    
				    
				   
				    	$res=array(
								"status"=>true,
								"statusCode"=>201,
								"message"=>"User Found",
								'profile_data'=>$validate_user
								); 
				} else{
				    $res=array(
								"status"=>true,
								"statusCode"=>404,
								"message"=>"No user found",
								'profile_data'=>null
								);
				    
				}
					echo json_encode($res);
    
}

public function changePassword(){
      $input = json_decode(file_get_contents('php://input'), true);
			//file_put_contents('login.txt',file_get_contents('php://input'));
			if(!$input){
                 	$res=array(
							"status"=>true,
							"statusCode"=>404,
							"message"=>"Something went wrong",
							"signup_data"=>null
						);
				echo json_encode($res);
				die();
            }
			
			$data=Customer::select('password')->where('id',$input['fld_user_id'])->first();
	
            if ($data && Hash::check($input['fld_current_password'], $data->password)) {
                 Customer::where('id',$input['fld_user_id'])->update(array('password'=>Hash::make($input['fld_new_password'])));
                 
                  $res=array(
								"status"=>true,
								"statusCode"=>201,
								"message"=>"Password is successfully changed"
							);
            } else{
				  $res=array(
								"status"=>true,
								"statusCode"=>404,
								"message"=>"Current Password is not matched"
							);
            }
          echo json_encode($res);
}

public function updateProfile(Request $request) {
     $input = json_decode(file_get_contents('php://input'), true);
     //file_put_contents('profile.txt',file_get_contents('php://input'));
     
     $fake_data=array(
			            'fld_user_id'=>0,
                        'fld_user_name'=>'',
                        'fld_l_name'=>'',
                        'fld_user_email'=>'',
                        'fld_user_phone'=>'',
                        'fld_user_address'=>'',
                        'fld_user_locality'=>'',
						'fld_user_city'=>'',
						'fld_user_state'=>'',
						'fld_user_pincode'=>'',
						'isOtpVerified'=>0,
						'r_code'=>'',
						'gender'=>'',
						'dob'=>'',
						'status'=>0,
						'password'=>''
			     );
			     
     if(!$input){
            $res=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>"Something went wrong",
				"signup_data"=>null
				);
				echo json_encode($res);
				die();
            }
            if($input['otp']!=''){
				 $record = DB::table('customer_phone_otp')
						->where('user_id',$input['fld_user_id'])
						->where('otp',$input['otp'])
						->first();
						if($record){
						    if($record->expired_on<Carbon::now()){
									$res=array(
									"status"=>true,
									"statusCode"=>404,
									"message"=>"OTP expired",
									"user_data"=>null
									);
						} 
                           else{  
            
     	 $user= Customer::where('id',$input['fld_user_id'])->first();
				if($user){
				    
				    
                    
				    	$quey_exe=Customer::where('id', $input['fld_user_id'])
									->update([
                                        'name' =>$input['fld_f_name'],
                                        'last_name' =>$input['fld_l_name'],
                                        'dob' =>$input['dob'],
                                        'email' =>$input['fld_user_email'],
                                        'phone' =>$input['fld_user_phone'],
                                        'gender' =>$input['gender'],
                                        'address' =>$input['fld_user_address'],
                                        //'pincode' =>$input['pincode'],
                                       
									
									]);
									
									if($quey_exe){
									    $validate_user= Customer::select(
									    'id as fld_user_id',
									    'r_code',
                                        'name as fld_user_name',
                                        'last_name as fld_l_name',
                                        'email as fld_user_email',
                                        'phone as fld_user_phone',
                                        'gender',
                                        'dob',
                                        'address as fld_user_address' 
                                        
                                        
                       
			)->where('id',$input['fld_user_id'])->first();
									   	$res=array(
								"status"=>true,
								"statusCode"=>201,
								"message"=>"Profile Update",
								'profile_data'=>$validate_user
								); 
									} else{
									    	$res=array(
								"status"=>true,
								"statusCode"=>404,
								"message"=>"something went wrong",
								'profile_data'=>$fake_data
								);
									}
									
				} 
                 else{
				    		
				    		$res=array(
								"status"=>true,
								"statusCode"=>404,
								"message"=>"something went wrong",
								'profile_data'=>$fake_data
								);
				}
			}
		}   
                         else{
			                 $res=array(
									"status"=>true,
									"statusCode"=>404,
									"message"=>"No data found",
									"user_data"=>null
									);
			
		                      }
			}
         else{
		 	 $user= Customer::where('id',$input['fld_user_id'])->first();
				if($user){
				    
				    
                    
				    	$quey_exe=Customer::where('id', $input['fld_user_id'])
									->update([
                                        'name' =>$input['fld_f_name'],
                                        'last_name' =>$input['fld_l_name'],
                                        'dob' =>$input['dob'],
                                        'email' =>$input['fld_user_email'],
                                        'phone' =>$input['fld_user_phone'],
                                        'gender' =>$input['gender'],
                                        'address' =>$input['fld_user_address'],
                                        //'pincode' =>$input['pincode'],
                                       
									
									]);
									
									if($quey_exe){
									    $validate_user= Customer::select(
									     'id as fld_user_id',
									    'r_code',
                                'name as fld_user_name',
                                'last_name as fld_l_name',
                              
                        'email as fld_user_email',
                        'phone as fld_user_phone',
                         'gender',
                         'dob',
                        'address as fld_user_address'
                       
			)->where('id',$input['fld_user_id'])->first();
									   	$res=array(
								"status"=>true,
								"statusCode"=>201,
								"message"=>"Profile Update",
								'profile_data'=>$validate_user
								); 
									} else{
									    	$res=array(
								"status"=>true,
								"statusCode"=>404,
								"message"=>"something went wrong",
								'profile_data'=>$fake_data
								);
									}
									
				} 
                 else{
				    		$res=array(
								"status"=>true,
								"statusCode"=>404,
								"message"=>"something went wrong",
								'profile_data'=>$fake_data
								);
				}
		 }
					echo json_encode($res);
    
}
public function userAddresss(Request $request) {
  $input = json_decode(file_get_contents('php://input'), true);
  

  if(!$input){
                 	$res=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>"Something went wrong",
				"signup_data"=>null
				);
				echo json_encode($res);
				die();
            }
            
		
		
  $address=DB::table('customer_shipping_address')
  ->select(
            'customer_shipping_address.id as fld_address_id',
            DB::raw("( false ) as  fld_profile_address "),
            'customer_shipping_address.shipping_name as fld_user_name',
            'customer_shipping_address.shipping_mobile as fld_user_phone',
            'customer_shipping_address.shipping_email as fld_user_email',
            'customer_shipping_address.shipping_city as fld_user_city',
            'customer_shipping_address.shipping_address as fld_user_address',
            'customer_shipping_address.shipping_address1 as fld_user_locality',
            'customer_shipping_address.shipping_state as fld_user_state',
            'customer_shipping_address.shipping_pincode as fld_user_pincode',
            'customer_shipping_address.shipping_address_type as fld_address_type',
            'customer_shipping_address.shipping_address_default as fld_address_default'
			)
  ->where('customer_id',$input['fld_user_id'])
  ->orderBy('customer_shipping_address.shipping_address_default', 'DESC')
  ->get()
   
  ->toarray();
 
  if(sizeof($address)==0){
    $res=array(
    "status"=>true,
    "statusCode"=>404,
    "message"=>"No address is there",
    "user_address"=>null
    );  
  } else{
     $res=array(
    "status"=>true,
    "statusCode"=>201,
    "message"=>"Address Found",
       "user_address"=>$address
    ); 
  }
  	echo json_encode($res);  
  
}

public function addAddress(Request $request) {
  $input = json_decode(file_get_contents('php://input'), true);

  if(!$input){
                 	$res=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>"Something went wrong",
				"signup_data"=>null
				);
				echo json_encode($res);
				die();
            }
            
            if($input['fld_address_default']==1){
                DB::table('customer_shipping_address')
                ->where('customer_id',$input['fld_user_id'])
                ->update(
                    array(
                        "shipping_address_default"=>0
                        )
                    );
            }
 
 $data=array(
        'customer_id'=>$input['fld_user_id'],
        'shipping_name'=>$input['fld_user_name'],
        'shipping_mobile'=>$input['fld_user_phone'],
        'shipping_state'=>$input['fld_user_state'],
        'shipping_city'=>$input['fld_user_city'],
        'shipping_pincode'=>$input['fld_user_pincode'],
        'shipping_address'=>$input['fld_user_address'],
         'shipping_address1'=>$input['fld_user_locality'],
        'shipping_email'=>$input['fld_user_email'],
        'shipping_address_type'=>$input['fld_address_type'],
         'shipping_address_default'=>$input['fld_address_default'],
         'shipping_country'=>'IN'
     );
  $address=DB::table('customer_shipping_address')->insert($data);
  if($address){
      $res=array(
    "status"=>true,
    "statusCode"=>201,
    "message"=>"Address successfully added"
    ); 
  } else{
      $res=array(
    "status"=>true,
    "statusCode"=>404,
    "message"=>"Something is went wrong"
    ); 
  }
 
  	echo json_encode($res);  
}
public function updateAddress(Request $request) {
  $input = json_decode(file_get_contents('php://input'), true);
  if(!$input){
                 	$res=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>"Something went wrong",
				"signup_data"=>null
				);
				echo json_encode($res);
				die();
            }
   if($input['fld_address_default']==1){
                DB::table('customer_shipping_address')
                ->where('customer_id',$input['fld_user_id'])
                ->update(
                    array(
                        "shipping_address_default"=>0
                        )
                    );
            }
 $data=array(
        'shipping_name'=>$input['fld_user_name'],
        'shipping_mobile'=>$input['fld_user_phone'],
        'shipping_state'=>$input['fld_user_state'],
        'shipping_city'=>$input['fld_user_city'],
        'shipping_pincode'=>$input['fld_user_pincode'],
        'shipping_address'=>$input['fld_user_address'],
           'shipping_address1'=>$input['fld_user_locality'],
        'shipping_email'=>$input['fld_user_email'],
        'shipping_address_type'=>$input['fld_address_type'],
         'shipping_address_default'=>$input['fld_address_default'],
        'shipping_country'=>'IN'
     );
  $address=DB::table('customer_shipping_address')->where('customer_id',$input['fld_user_id'])->where('id',$input['fld_address_id'])->update($data);
  if($address){
      $res=array(
    "status"=>true,
    "statusCode"=>201,
    "message"=>"Address successfully updated"
    ); 
  } else{
      $res=array(
    "status"=>true,
    "statusCode"=>404,
    "message"=>"Something is went wrong"
    ); 
  }
 
  	echo json_encode($res);  
}
public function deleteAddress(Request $request) {
  $input = json_decode(file_get_contents('php://input'), true);
  if(!$input){
                 	$res=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>"Something went wrong",
				"signup_data"=>null
				);
				echo json_encode($res);
				die();
            }
 
  $address=DB::table('customer_shipping_address')->where('customer_id',$input['fld_user_id'])->where('id',$input['fld_address_id'])->delete();
  if($address){
      $res=array(
    "status"=>true,
    "statusCode"=>201,
    "message"=>"Address successfully deleted"
    ); 
  } else{
      $res=array(
    "status"=>true,
    "statusCode"=>404,
    "message"=>"Something is went wrong"
    ); 
  }
 
  	echo json_encode($res);  
}
public function addressDetails(Request $request) {
  $input = json_decode(file_get_contents('php://input'), true);
  if(!$input){
                 	$res=array(
				"status"=>true,
				"statusCode"=>404,
				"message"=>"Something went wrong",
				"signup_data"=>null
				);
				echo json_encode($res);
				die();
            }
 
  $address=DB::table('customer_shipping_address')
  ->select(
                'customer_shipping_address.shipping_name as fld_user_name',
                'customer_shipping_address.shipping_mobile as fld_user_phone',
                'customer_shipping_address.shipping_email as fld_user_email',
                'customer_shipping_address.shipping_city as fld_user_city',
                'customer_shipping_address.shipping_address as fld_user_address',
                 'customer_shipping_address.shipping_address1 as fld_user_locality',
                'customer_shipping_address.shipping_state as fld_user_state',
                'customer_shipping_address.shipping_pincode as fld_user_pincode',
                'customer_shipping_address.shipping_address_type as fld_address_type'
			)
  ->where('customer_id',$input['fld_user_id'])->where('id',$input['fld_address_id'])->first();
  if($address){
      $res=array(
    "status"=>true,
    "statusCode"=>201,
    "message"=>"Address found",
    "address_data"=>$address
    ); 
  } else{
      $res=array(
    "status"=>true,
    "statusCode"=>404,
    "message"=>"Something is went wrong",
    "address_data"=>null
    ); 
  }
 
  	echo json_encode($res);  
}

	public function userWalletAmt(){
			$input = json_decode(file_get_contents('php://input'), true);
			if(!$input)
			{
                 	$res=array(
						"status"=>true,
						"statusCode"=>404,
						"message"=>"Something went wrong",
					);
				echo json_encode($res);
				die();
            }
			
			$wallet_setting = DB::select("SELECT * FROM wallet_setting");
			
			$validate_user= Customer::select(
									'id as fld_user_id',
									'total_reward_points as total_reward_points'
								)
							->where('id',$input['fld_user_id'])->first();
			
			if($validate_user){
				    
					$amt=$validate_user->total_reward_points;
					$wallet_percent=$wallet_setting[0]->wallet_consume_percent;
					$offer_products=0;
					$record = DB::table('cart')
		                ->select(
							'products.price as fld_product_price',
							'products.spcl_price as fld_spcl_price'
		                )
		                ->join('products','cart.prd_id','products.id')
		                 ->where('user_id',$input['fld_user_id'])
						->get()
						->toarray();
						  foreach($record as $row){
						      if($row->fld_spcl_price!=''){
						         $offer_products++; 
						      }
						  }
						
					
					$res=array(
							"status"=>true,
							"statusCode"=>201,
							"message"=>"User Wallet Amount Found",
							'profile_wallet_amount'=>$amt,
							'term_and_conditions'=>"<p>Lorum ipsume site emit</p>",
							'wallet_consume_percent'=>($offer_products==0)?$wallet_percent:0
							); 
			} else{
				    $res=array(
								"status"=>true,
								"statusCode"=>404,
								"message"=>"No user Wallet amount found",
							  );
			}
			
			echo json_encode($res);
    
	}
	
	public function getDeviceToken(){
	    	$input = json_decode(file_get_contents('php://input'), true);
	    	
	    	$validate_user_token= Customer::select(
									'id as fld_user_id'
							                      )
							      ->where('id',$input['fld_user_id'])
							      ->where('device_token',$input['fld_device_token'])
							      ->first();
							      
				$res=array(
				    "Msg"=>"Something went wrong",
				    "status"=>false,
				    "statusCode"=>404
				    );
    		      if($validate_user_token){
    		          Customer::where('id', $input['fld_user_id'])
									->update([
                                        'device_token' =>$input['fld_device_token'],
                                        'platform' =>$input['fld_platform']
                                       ]);
                                       
                        $res=array(
                                "Msg"=>"Token Updated",
                                "status"=>true,
                                "statusCode"=>201
                        );
    		      } else{
    		          Customer::where('id', $input['fld_user_id'])
									->update([
                                        'device_token' =>$input['fld_device_token'],
                                        'platform' =>$input['fld_platform']
                                       ]);
                                       
                            $res=array(
                                "Msg"=>"Token Inserted",
                                "status"=>true,
                                "statusCode"=>201
                            );
    		          
    		      }
    		 echo json_encode($res);
							
	    	
	}

}