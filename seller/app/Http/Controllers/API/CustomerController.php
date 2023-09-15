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
class CustomerController extends Controller 
{
public $successStatus = 200;
	public $site_base_path='http://aptechbangalore.com/test/';
		
		
		public function __construct()
    {
        $this->site_base_path=Config::get('constants.Url.public_url');
        //$this->middleware('auth');
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
				"message"=>"Something went wrong",
				"signup_data"=>null
				);
				echo json_encode($res);
				die();
            }
            
            $res=array(
							"status"=>true,
							"statusCode"=>201,
							"message"=>"Query submmitted , we will get back to you soon"
							);
            
            echo json_encode($res);
            
	}
	
    public function login(Request $request) 
	{
		
		$input = json_decode(file_get_contents('php://input'), true);
			 //file_put_contents('sdflogin.txt',file_get_contents('php://input'));
        
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
						  
						  CommonHelper::generate_user_otp($custmor_array);
						
							$res=array(
							"status"=>true,
							"statusCode"=>404,
							"message"=>"Phone not verified",
							"isOtpVerified"=>$validate_user->isOtpVerified,
							"login_data"=>$validate_user
							);
					} else{
						$res=array(
							"status"=>true,
							"statusCode"=>201,
							"message"=>"login successfully done",
							"isOtpVerified"=>$validate_user->isOtpVerified,
							"login_data"=>$validate_user
							);
					}	
				} else{
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
		       CommonHelper::generate_user_otp($custmor_array);
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
			} else{
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
				$record = DB::table('customer_phone_otp')
						->where('user_id',$input['fld_user_id'])
						->where('otp',$input['otp'])
						->where('flag',$input['flag'])
						->first();
						if($record){
						    if($record->expired_on<Carbon::now()){
									$res=array(
									"status"=>true,
									"statusCode"=>404,
									"message"=>"OTP expired",
									"user_data"=>null
									);
						} else{
							$res=Customer::where('id',$input['fld_user_id'])
													->update([
													'isOtpVerified'=>1,
													'status'=>1
													]);
								$user_data= Customer::select(
								'id as fld_user_id',
								'name as fld_user_name',
								'email as fld_user_email',
								'phone as fld_user_phone',
								'isOtpVerified'
								)->where('id',$input['fld_user_id'])->first();
								if($input['flag']==0){
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
						
						echo json_encode($res);
			}
			
			public function resend_otp(Request $request) {
			    
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
					  
					  CommonHelper::generate_user_otp($custmor_array);
					
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
					  
					  CommonHelper::generate_user_otp($custmor_array);
					
					
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
								"message"=>"Passward is changed"
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
     	 $user= Customer::where('id',$input['fld_user_id'])->first();
				if($user){
				    
				    
				    	$quey_exe=Customer::where('id', $input['fld_user_id'])
									->update([
                                        'name' =>$input['fld_f_name'],
                                        'last_name' =>$input['fld_l_name']
									
									]);
									
									if($quey_exe){
									    $validate_user= Customer::select(
                                'name as fld_f_name',
                                'last_name as fld_l_name'
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
								'profile_data'=>null
								);
									}
									
				} else{
				    		$res=array(
								"status"=>true,
								"statusCode"=>404,
								"message"=>"something went wrong",
								'profile_data'=>null
								);
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
					
					$res=array(
							"status"=>true,
							"statusCode"=>201,
							"message"=>"User Wallet Amount Found",
							'profile_wallet_amount'=>$amt,
							'wallet_consume_percent'=>$wallet_percent
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

}