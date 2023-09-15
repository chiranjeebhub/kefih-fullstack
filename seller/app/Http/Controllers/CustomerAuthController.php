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
use App\Helpers\XpressbeesHelper;
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
         
        $colors=Products::getProductsAttributes2('Colors',0,$input['product_id']);
        $sizes=Products::getProductsAttributes2('Sizes',0,$input['product_id']);
        $size_html='';
        $color_html='';
        
        foreach($sizes as $size){
            $attr_name=Products::getAttrName('Sizes',$size['size_id']);
            $class=($order_details->size_id==$size['size_id'])?'active':'';
            $size_html.='<span title="small">
						<a href="javascript:void(0)" class="badge badge-danger  ordersizeClass '.$class.'"  size_id="'.$size['size_id'].'" prd_id="'.$input['product_id'].'">'.$attr_name.'</a>  
					</span>';
        }
        
        foreach($colors as $color){
             $prd_data=Products::productDetails($input['product_id']);
            $color_data=Products::getcolorNameAndCode('Colors',$color['color_id']);
                $colorwise_images=DB::table('product_configuration_images')
                ->where('product_id',$input['product_id'])
                ->where('color_id',$color['color_id'])
                ->first();
                
                if($colorwise_images){
                    $url=URL::to('/uploads/products/240-180').'/'.$colorwise_images->product_config_image;
                } else{
                    $url=URL::to('/uploads/color').'/'.$color_data->color_image; 
                }
            $clr_class=($order_details->color_id==$color['color_id'])?'active':'';
            $color_html.='<span title="small">
						<a href="javascript:void(0)" class="ordercolorClass '.$clr_class.'"  color_id="'.$color['color_id'].'" prd_id="'.$input['product_id'].'" prd_type="'.$prd_data->product_type.'" title="'.$color_data->name.'">
						<img src="'.$url.'" height="40" width="40">
						</a>  
					</span>';
        }
    $input='<input type="hidden" value="'.$order_details->color_id.'" name="color_id" id="color_id">';
    $input.='<input type="hidden" value="'.$order_details->size_id.'" name="size_id" id="size_id">';
    $input.='<input type="hidden" value="'.$prd_data->product_type.'" name="product_type" id="product_type">';
    $input.='<input type="hidden" value="'.$ord.'" name="order_id" id="order_id">';
    $input.='<input type="hidden" value="'.$prd.'" name="product_id" id="product_id">';

         $res=array(
            "product_name"=>'Replace '.$order_details->product_name,
            "sizes_html"=>$size_html,
            "color_html"=>$color_html,
            "colors"=>sizeof($colors),
            "sizes"=>sizeof($sizes),
            "inputs"=>$input
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
				$appendto='colors_html';
				$color_data=Products::getcolorNameAndCode('Colors',$row['color_id']);
				  if($colorwise_images){
				      	$url=URL::to("/uploads/products/240-180").'/'.$colorwise_images->product_config_image;
				  } else{
				      	$url=URL::to("/uploads/color").'/'.$color_data->color_image;
				  }
			
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
        
		return view('fronted.mod_account.shipping_details',['shipping_listing'=>$ship_address_list,'shippingDetails'=>'active']);
   
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
            'shipping_pincode' => 'required|max:6',
            'shipping_address_type' => 'required'
            ],[
'shipping_name.required' =>'Name is required',
'shipping_name.max' =>'Name can not exceed to 50 characters',
'shipping_mobile.required' =>'Mobile is required',
'shipping_mobile.max' =>'Mobile can not exceed to 50 characters',
'shipping_address.required' =>'Address is required',
'shipping_address.max' =>'Address can not exceed to 50 characters',
'shipping_pincode.required' =>'Pincode is required',
'shipping_pincode.max' =>'Pincode can not exceed to 50 characters',
'shipping_state.required' =>'State is required',
'shipping_state.max' =>'State can not exceed to 50 characters',
'shipping_city.required' =>'City is required',
'shipping_city.max' =>'City can not exceed to 50 characters',
'shipping_address1.max' =>'Area can not exceed to 50 characters',
'shipping_address2.max' =>'Landmark can not exceed to 50 characters',
'shipping_address_type.required' =>'Shipping type is required',
             ]
			);
			
			
			$CheckoutShipping = new CheckoutShipping;
			$input_array=
			    	array('shipping_name' => $input['shipping_name'],
			'shipping_mobile' => $input['shipping_mobile'],
			'shipping_address' => $input['shipping_address'],
			'shipping_address1' => $input['shipping_address1'],
			'shipping_address2' => $input['shipping_address2'],
			'shipping_city' => $input['shipping_city'],
			'shipping_state' => $input['shipping_state'],
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
			  return Redirect::route('shippingDetails');
		  }
		  
			}
		$data=CheckoutShipping::select('customer_shipping_address.*')->where('id',$id)->first();
	
			return view('fronted.mod_account.shipping_address_edit',[
			    'shipping_data'=>$data,
			    'id'=>$id
			    ]);
		

    }
    public function myReviews(Request $request){
         $id=auth()->guard('customer')->user()->id ;
        $ratings=ProductRating::
            select('product_rating.*','products.name','vendors.public_name')
            ->join('products','products.id','product_rating.product_id')
             ->join('vendors','products.vendor_id','vendors.id')
            ->where('user_id',$id)->paginate(9);
            
      return view('fronted.mod_account.myReview',['reviews'=>'active','ratings'=>$ratings,'review_type'=>0]);
    }
    
    public function mySellerReviews(Request $request){
            $id=auth()->guard('customer')->user()->id ;
            
            $ratings=DB::table('vendor_rating')->select('vendor_rating.*','vendors.public_name')->join('vendors','vendor_rating.vendor_id','vendors.id')->where('user_id',$id)->paginate(9);
            return view('fronted.mod_account.myReview',['mySellerReviews'=>'active','ratings'=>$ratings,'review_type'=>1]);
    }
    public function sellerRating(Request $request){
         $input=$request->all();
        
          $dt=Products::productDetails($input['prd_id']);
         $request->validate([
				'rating' => 'required'
            ]);
          $id=auth()->guard('customer')->user()->id ;
           $res=DB::table('vendor_rating')
           ->where('user_id',$id)
           ->where('vendor_id',$dt->vendor_id)
           ->first();
         if(!$res){
            
            
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
        	$request->validate([
                'address' => 'max:110',
                'city' => 'max:25',
                'state' => 'max:25',
                'pincode' => 'max:6',
                'gender' => 'required',
                'dob' => 'required'
            ]
        );
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
                $input=$request->all();
                
                 if($file_name!=''){
                     $data=array(
                    'address'=>$input['address'],
                    'city'=>$input['city'],
                    'state'=>$input['state'],
                    'pincode'=>$input['pincode'],
                    'gender'=>$input['gender'],
                     'dob'=>$input['dob'],
                      'profile_pic'=>$file_name
                    );
                 } else{
                    $data=array(
                    'address'=>$input['address'],
                    'city'=>$input['city'],
                    'state'=>$input['state'],
                    'pincode'=>$input['pincode'],
                    'gender'=>$input['gender'],
                     'dob'=>$input['dob']
                    ); 
                 }
                
                    
            Customer::where('id',$id)->update($data);
           
		              
            return Redirect::route('accountinfo')->withErrors(['Profile Updated']);;
        }
        $states=CommonHelper::getState('101');
    	$cities = DB::table('cities')
		              ->select('cities.name as name', 'states.name as state_name')
		              ->join('states', 'states.id', '=', 'cities.state_id')
		              ->where('states.name',auth()->guard('customer')->user()->state)->get();
        return view('fronted.mod_account.account-info',['accountinfo'=>'active','states'=>$states,'cities'=>$cities]);
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
        'new_password' => 'required|max:25',
        'confirm_password' => 'required|max:25',
        'confirm_password' => 'required_with:new_password|same:new_password'
            ],[
                'current_password.required' => 'Current password is required',
                'current_password.max' => 'Current password can not exceed to 25 character',
                'new_password.required' => 'New password is required',
                'new_password.max' => 'New password can not exceed to 25 character',
                'confirm_password.required_with' => 'Confirm password is required',
                'confirm_password.max' => 'Confirm password can not exceed to 25 character',
                'confirm_password.same' => 'Confirm password not mathced with new password',
            ]
        );


		 	$id=auth()->guard('customer')->user()->id ;
		 $data=Customer::select('password')->where('id',$id)->first();
	
            if ($data && Hash::check($input['current_password'], $data->password)) {
                 Customer::where('id',$id)->update(array('password'=>Hash::make($input['new_password'])));
                 $customer_data=Customer::where('id',$id)->first();
                 
                //  $msg='Dear '.$customer_data->name.' '.$customer_data->last_name.'  you have succuessfully change your passowrd In (18Up.in)';
                    
                //     $email_msg='<tr>
                //     <td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
                //     <p>Hi '.$customer_data->name.' '.$customer_data->last_name.'</p>
                //     <p>succuessfully change your passowrd</p>
                //     </td>
                //     </tr>';
            
            
	           $email_data = [
                            'to'=>$customer_data->email,
                            'subject'=>'Change password',
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
             
               return Redirect::route('changepass')->withErrors(['password is changed']);
            } else{
            return Redirect::route('changepass')->withErrors(['Current password is not matched']);
            }

         }
        return view('fronted.mod_account.change-password',['changepass'=>'active']);
    }
	
	public function orderlist(Request $request)
    {	 $id=auth()->guard('customer')->user()->id ;
         $type=base64_decode($request->type);
        
         switch($type){
                case 0:
                    /*$orders=Orders::select('orders.*',
						   'order_details.id as fld_order_detail_id',
                           'order_details.product_price as grand_total',
						   'order_details.product_qty as qty',
						   'order_details.order_deduct_reward_points as order_deduct_reward_points',
						   'order_details.order_coupon_amount as order_coupon_amount',
							DB::raw("( SELECT COUNT(*) FROM order_details where order_id=orders.id and order_status=1) as  isInvoiceGenerate ")
                    )
                    ->join('order_details','orders.id','order_details.order_id')
                        ->where('orders.customer_id',$id)
                        ->where(function($query){
                        $query->where('order_details.order_status',0);
                        $query->orwhere('order_details.order_status',1);
                        $query->orwhere('order_details.order_status',2);
                        
                        })
					->orderBy('orders.id','desc')->paginate(10);*/
					
					$orders=Orders::select('orders.*',
						   'order_details.id as fld_order_detail_id',
                           //'order_details.product_price as grand_total',
						   'order_details.product_qty as qty',
						   'order_details.order_deduct_reward_points as order_deduct_reward_points',
						   'order_details.order_coupon_amount as order_coupon_amount',
							DB::raw("( SELECT COUNT(*) FROM order_details where order_id=orders.id and order_status=1) as  isInvoiceGenerate ")
                    )
                    ->join('order_details','orders.id','order_details.order_id')
                        ->where('orders.customer_id',$id)
                        ->where(function($query){
                        $query->where('order_details.order_status',0);
                        $query->orwhere('order_details.order_status',1);
                        $query->orwhere('order_details.order_status',2);
                        
                        })
					->groupBy('orders.id')->orderBy('orders.id','desc')->paginate(10);
                break;
                
                 case 3:
                      /*$orders=Orders::select('orders.*',
                           'order_details.id as fld_order_detail_id',
						   'order_details.product_price as grand_total',
						   'order_details.product_qty as qty',
						   'order_details.order_deduct_reward_points as order_deduct_reward_points',
						   'order_details.order_coupon_amount as order_coupon_amount',
							DB::raw("( SELECT COUNT(*) 
							FROM order_details where order_id=orders.id and order_status=1) as  isInvoiceGenerate ")
                    )
                    ->join('order_details','orders.id','order_details.order_id')
                    ->where('order_details.order_status',3)
                    
                    ->where('orders.customer_id',$id)
					->orderBy('orders.id','desc')->paginate(10);*/
					
					$orders=Orders::select('orders.*',
                           'order_details.id as fld_order_detail_id',
						   //'order_details.product_price as grand_total',
						   'order_details.product_qty as qty',
						   'order_details.order_deduct_reward_points as order_deduct_reward_points',
						   'order_details.order_coupon_amount as order_coupon_amount',
							DB::raw("( SELECT COUNT(*) 
							FROM order_details where order_id=orders.id and order_status=1) as  isInvoiceGenerate ")
                    )
                    ->join('order_details','orders.id','order_details.order_id')
                    ->where('order_details.order_status',3)
                    
                    ->where('orders.customer_id',$id)
					->groupBy('orders.id')->orderBy('orders.id','desc')->paginate(10);
                break;
                case 5:
                      $orders=Orders::select('orders.*',
                           'order_details.id as fld_order_detail_id',
						   'order_details.product_price as grand_total',
						   'order_details.product_qty as qty',
						   'order_details.order_deduct_reward_points as order_deduct_reward_points',
						   'order_details.order_coupon_amount as order_coupon_amount',
							DB::raw("( SELECT COUNT(*) 
							FROM order_details where order_id=orders.id and order_status=1) as  isInvoiceGenerate ")
                    )
                    ->join('order_details','orders.id','order_details.order_id')
                    ->where('order_details.order_status',5)
                    
                    ->where('orders.customer_id',$id)
					->orderBy('orders.id','desc')->paginate(10);
                break;
                
                case 4:
                    $orders=Orders::select(
                        'order_details.suborder_no as order_no',
                        //'order_details.id',
						'orders.id',
						'order_details.id as fld_order_detail_id',
                        'order_details.product_price as grand_total',
						'order_details.product_qty as qty',
						'order_details.order_deduct_reward_points as order_deduct_reward_points',
						'order_details.order_coupon_amount as order_coupon_amount',
						'order_details.order_shipping_charges as total_shipping_charges',
                        'orders.payment_mode',
                        'orders.order_date',
                        'orders.id as master_id'
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
         $level=base64_decode($request->level);
         
         $orders=Orders::select('orders.*')->where('orders.order_status',3)
         ->join('order_details','order_details.order_id','orders.id')
         ->where('customer_id',$id)
         ->orderBy('orders.id','desc')
           ->groupBy('orders.id');
         
         
        //  level 1=> last 15 order , 2=> last 1 month , 3=> 6 months , 4=> last year
          switch($level){
              case 1:
                    $orders= $orders->offset(0)
                    ->limit(15);
                break;
                
                case 2:
                    $orders= $orders->whereMonth('orders.updated_at','<', Carbon::now()->subMonth()->month);  
                break;
                
                case 3:
                       $orders= $orders->whereMonth('orders.updated_at', '<',(new \Carbon\Carbon)->submonths(5)); 
                break;
                
                case 4:
                      $orders= $orders->whereDate('orders.updated_at','<', Carbon::now()->subDays(365));  
                break;
                
               
         }
         
        $orders= $orders->paginate(10);
					  
        
       
    
        return view('fronted.mod_account.orders',['myorder'=>'active','orders'=>$orders]);
    }
	
	public function orderdetail(Request $request){
	    
        $order_id=base64_decode($request->order_id);
            $master_order=Orders::select('orders.*')->where('id',$order_id)->first();
            
            $sub_orders=DB::table('order_details')->select('order_details.*','products.default_image')
            ->join('products','order_details.product_id','products.id')
            ->where('order_id',$order_id)
            ->groupBy('order_details.id')
            ->get();
            
        return view('fronted.mod_account.my-orders-details',[
            'myorder'=>'active',
            'master_order'=>$master_order,
            'sub_orders'=>$sub_orders
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
    
    public function my_order_track(Request $request){
	    
        $id= base64_decode($request->order_detail_id);
		
		$data=DB::table('order_track')->where('fld_order_detail_id','=',$id)->first();
				
		return view('fronted.mod_account.my_order_track',['track_data'=>$data]);
    }
    
    public function order_track(Request $request){
	    
        $id= base64_decode($request->order_detail_id);
		
		$inputs['docket_no']='14147020916346';
		
		$ss=XpressbeesHelper::OrderTrack($inputs);
		$data=$ss->GetBulkShipmentStatus[0];
		
		return view('fronted.mod_account.order_track',['track'=>'active','track_data'=>$data]);
    }
    
    
     public function return_refund_order(Request $request){
      
      	
        if ($request->isMethod('post')) {
            	$input=$request->all();
            
            	
              $order_detail_id=$input['master_order_id'];
             
             	
             	 $request->validate([
                    'order_id.*' => 'required',
					'condition_accepted' => 'required',
                    'reason' => 'required',
                    'return_or_refund' => 'required',
                     'remarks' => 'max:50'
            ],[
                    'order_id.*.required'=>'Select product to cancel',
					'reason.required'=>'Reason is required to return order',
                    
                    'condition_accepted.required'=>'Please select Term and condition',
                    'return_or_refund.required'=>'Please select what to want return or refund'
            ]
			);
				
			$canceled_orders=array();
			$total_deduct_points=0;
			$order_id='';
			foreach($input['order_id'] as $row){
			  $single_order=array(
			  'sub_order_id'=>$row,
			  'reason'=>$input['reason'] ,
			  'type'=>5,
			  'return_type'=>$input['return_or_refund']
			  );
			  DB::table('order_details')->where('id',$row)->update([
                    'order_status'=>5,
                    'return_what'=>$input['return_or_refund']
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
                   if($input['return_or_refund']==1 || $input['return_or_refund']==0) {
                       
                       
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
                            'color_id'=>$input['color_id'],
                            'order_reward_points'=>$order_previous_data->order_reward_points,
                            'order_shipping_charges'=>$order_previous_data->order_shipping_charges,
                            'order_commission_rate'=>$order_previous_data->order_commission_rate,
                            'return_days'=>$order_previous_data->return_days,
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
			$total_order_deduct_points=0;
			$order_id='';
			foreach($input['order_id'] as $row){
			    
			  $single_order=array('sub_order_id'=>$row,'reason'=>$input['reason'] ,'type'=>4);
			  DB::table('order_details')->where('id',$row)->update(['order_status'=>4]);
			  
			  $this->generateMailforCancelAndReturnOrder($row,0);
			  array_push($canceled_orders,$single_order);
				
			  $deduct_points=DB::table('order_details')->where('id',$row)->first();
				
					CommonHelper::orderDetailsLog($deduct_points,3);
					
					Products::increaseProductQty($deduct_points->product_id,$deduct_points->size_id,$deduct_points->color_id,$deduct_points->product_qty);
				
				$total_deduct_points+=$deduct_points->order_reward_points;
				$total_order_deduct_points+=$deduct_points->order_deduct_reward_points;
				$order_id=$deduct_points->order_id;
			}
			
			$cust_id=auth()->guard('customer')->user()->id ;
			
			if($total_deduct_points!=0){
				$wallet=array(
							'fld_customer_id'=>$cust_id,
							'fld_order_id'=>$order_id,
							'fld_order_detail_id'=>0,
							'fld_reward_narration'=>'Cancelled',
							'fld_reward_deduct_points'=>$total_deduct_points,
							'fld_order_consume_reward_points'=>0,
							'fld_order_deduct_reward_points'=>$total_order_deduct_points
						);
				
				DB::table('tbl_wallet_history')->insert($wallet);
				
				$master_points=DB::table('customers')->where('id',$cust_id)->first();
				$amt=$master_points->total_reward_points-$total_deduct_points;
				
				DB::table('customers')->where('id',$cust_id)->update(['total_reward_points'=>$amt]);
			}
			else if($total_order_deduct_points!=0){	
				$wallet=array(
							'fld_customer_id'=>$cust_id,
							'fld_order_id'=>$order_id,
							'fld_order_detail_id'=>0,
							'fld_reward_narration'=>'Cancelled Wallet Refund',
							'fld_reward_deduct_points'=>0,
							'fld_order_consume_reward_points'=>0,
							'fld_order_deduct_reward_points'=>$total_order_deduct_points
						);
				
				DB::table('tbl_wallet_history')->insert($wallet);
				
				$master_points=DB::table('customers')->where('id',$cust_id)->first();
				$amt=$master_points->total_reward_points+$total_order_deduct_points;
				
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
         $master_orders=OrdersDetail::where('id',$order_id)->first();
         $master_order=Orders::where('id',$master_orders->order_id)->first();
         
        $opeation_pr='Return';
        
		$mail_template_file="order_return";
		$admin_mail_template_file="order_return_admin";
		$vendor_mail_template_file="order_return_vendor";
		
        if($master_order->payment_mode==0)
            $template_file="cod_order_return";
        else
            $template_file="online_order_return";
                
                
        if($type==0){
            $opeation_pr='Cancel';
			//$mail_template_file="order_sts_change";
			$mail_template_file="order_cancel";
			$admin_mail_template_file="order_cancel_admin";
			$vendor_mail_template_file="order_cancel_vendor";
            
            if($master_order->payment_mode==0)
                $template_file="cod_order_cancelled_message";
            else
                $template_file="online_order_cancelled_message";
        }
                
                $customer_data=Customer::where('id',$master_order->customer_id)->first();
                $shipping_data=OrdersShipping::where('order_id',$master_orders->order_id)->first();
                
                
    			$msg=view("message_template.".$template_file,
    								array(
        							'data'=>array(
        								'order_id'=>$master_orders->order_id,
        								'product_name'=>$master_orders->product_name,
        								'product_price'=>$master_orders->product_price,
        								//'suborder_no'=>$master_orders->suborder_no
										'suborder_no'=>$master_orders->id
        								)
    								) )->render();
								
                //$msg='Dear '.$customer_data->name.' '.$customer_data->last_name.'  your request for '.$opeation_pr.' order('.$master_orders->suborder_no.') is proceesing.';
                
                    $mode= ($master_order->payment_mode==0)?"'COD'":"'Paid'";
					
					$email_msg=array(
										'payment_mode'=>$mode,
										'master_order'=>$master_order,
										'master_orders'=>$master_orders,
										'customer_data'=>$customer_data,
										'shipping_data'=>$shipping_data,
									);
									
                    /*$email_msg='<tr>
            	<td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
                	<p>Hi '.$customer_data->name.' '.$customer_data->last_name.'</p>
                    <p>We have received your query for '.$opeation_pr.' order. We will send you an Email and SMS for further process</p>
                    <p>
                    Order ID: <span style="color:#00bbe6;">'.$master_orders->suborder_no.'</span><br />
                    Order Date: <span style="color:#00bbe6;">'.$master_order->order_date.'</span><br />
                    Payment Mode: <span style="color:#00bbe6;">'.$mode.'</span>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="border-bottom:solid 1px #999; border-right:solid 1px #999; padding:0px 10px; width:50%;">
                	<p><strong>Billing Info</strong><br />
                            '.$shipping_data->order_shipping_name.'<br />
                            '.$shipping_data->order_shipping_phone.'<br />
                            '.$shipping_data->order_shipping_email.'<br />
                            '.$shipping_data->order_shipping_address.'<br />
                            '.$shipping_data->order_shipping_address1.'<br />
                            '.$shipping_data->order_shipping_address2.'<br />
                            '.$shipping_data->order_shipping_city.'<br />
                            '.$shipping_data->order_shipping_state.'<br />
                            '.$shipping_data->order_shipping_zip.'<br />
                    </p
                </td>
                <td style="border-bottom:solid 1px #999; padding:0px 10px; width:50%;">
                	<p><strong>Pick up Info</strong><br />
                    '.$shipping_data->order_shipping_name.'<br />
                    '.$shipping_data->order_shipping_phone.'<br />
                    '.$shipping_data->order_shipping_email.'<br />
                    '.$shipping_data->order_shipping_address.'<br />
                    '.$shipping_data->order_shipping_address1.'<br />
                    '.$shipping_data->order_shipping_address2.'<br />
                    '.$shipping_data->order_shipping_city.'<br />
                    '.$shipping_data->order_shipping_state.'<br />
                    '.$shipping_data->order_shipping_zip.'<br />
                        
                    </p>
                </td>
            </tr> 
            <tr>
            	<td colspan="2" style="border-bottom:solid 1px #999; padding:0px 10px;">
                	<p>Order Summary</p>
                </td>
            </tr>';
            
            $email_msg.='<tr>
            	<td colspan="2">
                	<table cellpadding="0" cellspacing="0" style="width:100%; text-align:left; padding:5px 10px;">
                      <tr>
                        <th style="padding:5px 0px;">S.no.</th>
                        <th>Item Name</th>
						<th>Quantity</th>
                        <th>Price</th>
						<th>Amt</th>
                      </tr>';
                      
                      $i=1;
                    $email_msg.='<tr>
                    <td style="padding:10px 10px 5px; border-bottom:dashed 1px #ccc;">'.$i.'</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_name.'('.$master_orders->size.' '.$master_orders->color.')</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_qty.'</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_price.'</td>
                    <td style="border-bottom:dashed 1px #ccc;">'.$master_orders->product_qty*$master_orders->product_price.'</td></tr>';
                            
                           
                      
						
						
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
                     
					 
                    $email_msg.='<tr bgcolor="#d1d4d1">
                    <td style="padding:5px 10px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><strong>Total Amount Rs.:'.$master_orders->product_qty*$master_orders->product_price.' </strong></td>
                    </tr>';
					  
                    $email_msg.='</table>

                </td>
            </tr>';*/
            
            
	           $email_data = [
                            'to'=>$customer_data->email,
                            'subject'=>$opeation_pr.' Order',
                            "body"=>view("emails_template.".$mail_template_file,
                            array(
                            'data'=>array(
                            'message'=>$email_msg
                            )
                            ) )->render(),
                            'phone'=>$customer_data->phone,
                            'phone_msg'=>urlencode($msg)
                         ];
                   
            CommonHelper::SendmailCustom($email_data);
           CommonHelper::SendMsg($email_data);
		   
		   $admin_email_data = [
                            'to'=>Config::get('constants.email.admin_to'),
                            'subject'=>'Customer have '.$opeation_pr.' Order',
                            "body"=>view("emails_template.".$admin_mail_template_file,
                            array(
                            'data'=>array(
                            'message'=>$email_msg
                            )
                            ) )->render(),
                            'phone'=>$customer_data->phone,
                            'phone_msg'=>urlencode($msg)
                         ];
                   
            CommonHelper::SendmailAdminCustom($admin_email_data);
           //CommonHelper::SendMsg($admin_email_data);
		   
		   
				$product_info=Products::where('id',$master_orders->product_id)->first();
				$vendor_info=DB::table('vendors')->where('id',$product_info->vendor_id)->first();
				
				if($vendor_info->email!='')
				{
					$vendor_email_data = [
								'to'=>$vendor_info->email,
								'subject'=>'Customer have '.$opeation_pr.' Order '.$master_orders->id.' ',
								//"body"=>view("emails_template.order_confirmation",
								"body"=>view("emails_template.".$vendor_mail_template_file,
								array(
								'data'=>array(
								'message'=>$email_msg
								)
								) )->render(),
								'phone'=>$vendor_info->phone,
								'phone_msg'=>$msg
							 ];
					   
					CommonHelper::SendmailVendorCustom($vendor_email_data);
					//CommonHelper::SendMsg($vendor_email_data);
				}
			
      }
    
    public function reason_and_policy(Request $request){
         $input=$request->all();
         $html='';
          $html.="<option value='0'>Others</option>";
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
   
    	public function savelater()
    {
		$id=auth()->guard('customer')->user()->id ;
		$data = DB::select("SELECT prd.*,savelater.fld_save_later_id,savelater.fld_product_id,savelater.color_id,savelater.size_id FROM products prd inner join tbl_save_later savelater on savelater.fld_product_id=prd.id where savelater.fld_user_id='".$id."'");
		
		return view('fronted.mod_account.savelater',['savelater'=>'active',"savelater_data"=>$data]);
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
	
	public function wallet()
    {
		$id=auth()->guard('customer')->user()->id ;
		
		$cust_info=DB::table('customers')->where('id',$id)->first();
		
		$current_wallet_history=DB::table('tbl_wallet_history')->select('tbl_wallet_history.fld_order_id','tbl_wallet_history.fld_reward_points','tbl_wallet_history.fld_reward_deduct_points','tbl_wallet_history.fld_order_deduct_reward_points','tbl_wallet_history.fld_reward_narration','tbl_wallet_history.fld_wallet_date')
		/*->join('order_details','order_details.id','tbl_wallet_history.fld_order_detail_id')*/
		->where('fld_customer_id',$id)->orderBy('tbl_wallet_history.id','desc')->get();
		
		$wallet_history=DB::table('tbl_wallet_history')->select('tbl_wallet_history.fld_order_id','tbl_wallet_history.fld_reward_points','tbl_wallet_history.fld_reward_deduct_points','tbl_wallet_history.fld_order_deduct_reward_points','tbl_wallet_history.fld_reward_narration','tbl_wallet_history.fld_wallet_date')
		/*->join('order_details','order_details.id','tbl_wallet_history.fld_order_detail_id')*/
		->where('fld_customer_id',$id)->orderBy('tbl_wallet_history.id','desc')->get();
		
		return view('fronted.mod_wallet.my-wallet',['wallet'=>'active','cust_info'=>$cust_info,'current_wallet_history'=>$current_wallet_history,'wallet_history'=>$wallet_history]);
    }
}
