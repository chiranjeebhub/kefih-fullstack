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
use App\Products;
use App\ProductAttributes;
use App\Colors;
use App\Sizes;
use App\ProductCategories;
use App\ProductImages;
use URL;
use Config;

class SocialController extends Controller {
	public $successStatus = 200;
	public $site_base_path='http://aptechbangalore.com/test/';
	public function __construct()
    {
        $this->site_base_path=Config::get('constants.Url.public_url');
        //$this->middleware('auth');
    }
	
	public function savedata(Request $request){
		$input = json_decode(file_get_contents('php://input'), true);
	
	
		$email = @$input['email'];
        $existUser = Customer::where('email',$email)->count();
        
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
						'profile_pic as profile_pic',
						'isOtpVerified',
						'password'
				)->where('email',$email)->first();
		
		if($validate_email){
			$url=$this->site_base_path.Config::get('constants.uploads.customer_profile_pic').'/'.$validate_email->profile_pic;
		             $res=array(
							"status"=>true,
							"statusCode"=>201,
							"message"=>"login successfully done",
							"Profile_data"=>$url,
							"isOtpVerified"=>$validate_email->isOtpVerified,
							"login_data"=>$validate_email
							);
    		  
            
		}
		else{
			
			$name="";
            $Customer = new Customer;
        	$Customer->name     = @$input['name'];
        	$Customer->last_name     = @$input['lname'];
        	if(@$input['socialtype']==1){
			$Customer->facebook_id = @$input['socialkey'];	
			}
        	if(@$input['socialtype']==0){
			$Customer->google_id = @$input['socialkey'];	
			}
			
        	$Customer->email    =  @$input['email'];
        	$Customer->isOtpVerified    =  1;
        	$Customer->password = Hash::make(trim(12345678));
        	$Customer->device_id =@$input['device_id'];
        	$Customer->status =1;
            if($Customer->save()){
            	if($input['profile_image']!='' && @$input['socialtype']==0){
            
       
                $b64image = base64_encode(file_get_contents($input['profile_image']));
                $data = $b64image;
                $data = base64_decode($data);
                $name=uniqid().".png";
            
	               // $img = $input['profile_image'];
                //     $img = str_replace('data:image/png;base64,', '', $img);
                //     $img = str_replace(' ', '+', $img);
                //     $img_data = base64_decode($img);
                //     $name=uniqid() . '.png';
                //     $uploads=$name;
                    $file = Config::get('constants.uploads.customer_profile_pic').'/'.$name ;
                    file_put_contents($file, $data);
                    	$res=Customer::where('id',$Customer->id)
													->update([
													'profile_pic'=>$name
													]);
			}
			
			if($input['profile_image']!='' && @$input['socialtype']==1){
            	    
            
            $url=$input['profile_image'];
           
             $b64image = base64_encode(file_get_contents($url));
                $data = $b64image;
                $data = base64_decode($data);
                $name=uniqid().".png";
            
	               // $img = $input['profile_image'];
                //     $img = str_replace('data:image/png;base64,', '', $img);
                //     $img = str_replace(' ', '+', $img);
                //     $img_data = base64_decode($img);
                //     $name=uniqid() . '.png';
                //     $uploads=$name;
                    $file = Config::get('constants.uploads.customer_profile_pic').'/'.$name ;
                    file_put_contents($file, $data);
                    	$res=Customer::where('id',$Customer->id)
													->update([
													'profile_pic'=>$name
													]);
			}
            	
    $url=$this->site_base_path.Config::get('constants.uploads.customer_profile_pic').'/'.$name;
            	
                           $res=array(
							"status"=>true,
							"statusCode"=>201,
							"message"=>"login successfully done",
							"isOtpVerified"=>$Customer->isOtpVerified,
							"Profile_data"=>$url,
							"login_data"=>$Customer
							);

               }
        
	     }
	     echo json_encode($res);
	}
}


?>