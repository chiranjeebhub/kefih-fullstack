<?php

namespace App\Http\Controllers\sws_Admin;

use Illuminate\Http\Request;
use App\Products;
use App\ProductImages;
use App\ProductCategories;
use App\ProductRelation;
use App\VendorMultiAddress;
use App\Brands;
use App\Colors;
use App\Sizes;
use App\User;
use App\ProductAttributes;
use App\Customer;
use App\Wallet;
use App\OrdersDetail;
use App\Orders;
use App\Vendor;
use App\CheckoutShipping;
use App\VendorContactUs;
use Auth;

use Redirect;
use Validator;
use DB;
use Config;
use App\Helpers\CommonHelper;
use App\Helpers\MsgHelper;
use App\Helpers\FIleUploadingHelper;
use App\Helpers\CustomFormHelper;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExport;
use App\Exports\Customers_export;
use App\Exports\CustomerPaymentHistory;
use URL;
use Hash;
class CommonController extends Controller 
{
    
    public function getReaplcedOrder(Request $request){
         $input=$request->all();
         	$replaced_data=DB::table('replace_order_details')->where('sub_order_id',$input['suborder_id'])->first();
         	$html='';
         	if($replaced_data){
         	   $html=' <table border="1">
                    <tr>
                    <th>Product Name</th>
                    <td>'.$replaced_data->product_name	.'</td>
                    </tr>
                    <tr>';
                    
                    if($replaced_data->size!=''){
                            $html.='<th>Size</th>
                            <td>'.$replaced_data->size	.'</td>
                            </tr>
                            <tr>';
                    }
                    
                    if($replaced_data->color!=''){
                    $html.='<th>Color</th>
                    <td>'.$replaced_data->color	.'</td>
                    </tr>
                    <tr>';
                    }
                    
                    
                    
                   $html.='<th>Price</th>
                    <td>Rs.'.$replaced_data->product_price	.'</td>
                    </tr
                    <tr>
                    <th>Qty</th>
                    <td>'.$replaced_data->product_qty	.'</td>
                    </tr>
         </table>'; 
         	}
         	
            $response=array(
            "HTML"=>$html,
            );
                        	echo json_encode($response);
    }
    public function replaceOrder(Request $request){
         $input=$request->all();
          $order_previous_data=OrdersDetail::where('id',$input['order_id'])->first();
          $order_previous_msater=Orders::where('id',$order_previous_data->order_id)->first();
          $order_detail_for_new=array(
				'suborder_no'=>$order_previous_msater->order_no.'_item_'.$order_previous_data->product_id,
				'order_id'=>$order_previous_data->order_id,
				'product_id'=>$order_previous_data->product_id,
				'product_name'=>$order_previous_data->product_name,
				'product_qty'=>$order_previous_data->product_qty,
                'product_price'=>$order_previous_data->product_price,
                'product_price_old'=>$order_previous_data->product_price_old,
                'size'=> Products::getSizeName($input['size_id']),
                'color'=> Products::getcolorName($input['color_id']),
                'size_id'=>$input['size_id'],
                'color_id'=>$input['color_id'],
				'order_reward_points'=>$order_previous_data->order_reward_points,
                'order_shipping_charges'=>$order_previous_data->order_shipping_charges,
                'order_commission_rate'=>$order_previous_data->order_commission_rate,
                 'return_days'=>$order_previous_data->return_days
				);
				
				$order_detail_for_replace=array(
				'suborder_no'=>$order_previous_msater->order_no.'_item_'.$order_previous_data->product_id,
				'order_id'=>$order_previous_data->order_id,
                'sub_order_id'=>$input['order_id'],
				'product_id'=>$order_previous_data->product_id,
				'product_name'=>$order_previous_data->product_name,
				'product_qty'=>$order_previous_data->product_qty,
                'product_price'=>$order_previous_data->product_price,
                'product_price_old'=>$order_previous_data->product_price_old,
                'size'=> Products::getSizeName($input['size_id']),
                'color'=> Products::getcolorName($input['color_id']),
                'size_id'=>$input['size_id'],
                'color_id'=>$input['color_id'],
				'order_reward_points'=>$order_previous_data->order_reward_points,
                'order_shipping_charges'=>$order_previous_data->order_shipping_charges,
                'order_commission_rate'=>$order_previous_data->order_commission_rate,
                 'return_days'=>$order_previous_data->return_days,
                  'remarks'=>$input['remarks']
				);
				
				$res=DB::table('replace_order_details')->insert($order_detail_for_replace);
				$res=DB::table('order_details')->insert($order_detail_for_new);
				
				if($res){
                        $response=array(
                        "ERROR"=>'NONE',
                        );
				} else{
				   $response=array(
                        "ERROR"=>'YES',
                        );
				}
       
      
				
				echo json_encode($response);
    }
     public function suborder_product_details(Request $request){
         
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
						<a href="javascript:void(0)" class="badge badge-danger  sizeClass '.$class.'"  size_id="'.$size['size_id'].'" prd_id="'.$input['product_id'].'">'.$attr_name.'</a>  
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
						<a href="javascript:void(0)" class="colorClass '.$clr_class.'"  color_id="'.$color['color_id'].'" prd_id="'.$input['product_id'].'" prd_type="'.$prd_data->product_type.'" title="'.$color_data->name.'">
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
     public function admin_get_attr_dependend(Request $request){
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
				$html.='<span class="colorClass" 
				color_id="'.$row['color_id'].'" 
				prd_id='.$input['prd_id'].' 
				prd_type='.$prd_data->product_type.' 
				title="'.$color_data->name.'"><img src="'.$url.'" height="40" width="40" alt="'.$color_data->name.'"></span>';
				//$html.='<span title="small"><a href="javascript:void(0)" class="badge badge-primary colorClass" prd_id='.$input['prd_id'].' color_id='.$row['color_id'].'>'.$name.'</a></span>';
			}
		}
	
		$color_id=(sizeof($data)==1)?$data[0]['color_id']:0;
			    $appendto='colors_html';
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
    public function changePassword(Request $request)
    {
        
         $page_details=array(
            "Title"=>"Change password",
            "Method"=>"1",
            "Box_Title"=>"Change password",
       "Action_route"=>route('changePassword'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "currentPassword_field"=>array(
              'label'=>'Current password',
            'type'=>'password',
            'name'=>'current_password',
            'id'=>'current_password',
            'classes'=>'form-control',
            'placeholder'=>'Current Password',
            'value'=>'',
			'disabled'=>''
           ),
           
           "newPassword_field"=>array(
              'label'=>'New Password',
            'type'=>'password',
            'name'=>'new_password',
            'id'=>'new_password',
            'maxlength'=>'6',
            'classes'=>'form-control',
            'placeholder'=>'New Password',
            'value'=>'',
			'disabled'=>''
           ),
           "confirmPassword_field"=>array(
              'label'=>'Confirm Password',
            'type'=>'password',
            'name'=>'confirm_password',
            'id'=>'confirm_password',
            'maxlength'=>'6',
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
                  'classes'=>'btn btn-danger',
                  'placeholder'=>'',
                  'value'=>'Save'
            )
         )
       )
     );
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
                'confirm_password.same' => 'Confirm password not matched with new password',
            ]
        );


if($input['owner']==0){
        $vdr_id = auth()->guard('vendor')->user()->id;
         $data=Vendor::select('password')->where('id',$vdr_id)->first();
	
            if ($data && Hash::check($input['current_password'], $data->password)) {
                 Vendor::where('id',$vdr_id)->update(array('password'=>Hash::make($input['new_password'])));
               return Redirect::route('changePassword')->withErrors(['password is changed']);
            } else{
            return Redirect::route('changePassword')->withErrors(['Current password is not matched']);
            }
} else{
    $admin_id=auth()->user()->id;
    $data=User::select('password')->where('id',$admin_id)->first();
	
            if ($data && Hash::check($input['current_password'], $data->password)) {
                 User::where('id',$admin_id)->update(array('password'=>Hash::make($input['new_password'])));
               return Redirect::route('changePassword')->withErrors(['password is changed']);
            } else{
            return Redirect::route('changePassword')->withErrors(['Current password is not matched']);
            }
}


        

		 
		

         }
         
        return view('admin.extrafeature.changePassword',['page_details'=>$page_details]);
    }
      public function vendor_address(Request $request)
    {
      
    
        $vendor_id= base64_decode($request->vendor_id);
          $vendor_info=Vendor::where('id',$vendor_id)->first();
        	$page_details=array(
				"Title"=>"( ".$vendor_info->username." )'s  address",
				"Box_Title"=>"( ".$vendor_info->username." )'s  address",
     );
        $addresss=DB::table('vendor_address')->where('vendor_id',$vendor_id)->get();
    
        return view('admin.vendors.address',['addresss'=>$addresss,'page_details'=>$page_details,"vendor_id"=>$vendor_id]);
        
    }
    
      public function add_address(Request $request)
    {
         $vendor_id= base64_decode($request->vendor_id);
          $vendor_info=Vendor::where('id',$vendor_id)->first();
      	 $page_details=array(
            "Title"=>"Add  ( ".$vendor_info->username." )'s  address",
            "Method"=>"1",
            "Box_Title"=>"Add ( ".$vendor_info->username." )'s  address",
       "Action_route"=>route('add_address',(base64_encode($vendor_id))),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "address_field"=>array(
              'label'=>'Address *',
            'type'=>'text',
            'name'=>'address',
            'id'=>'address',
            'classes'=>'form-control',
            'placeholder'=>'Address',
            'value'=>'',
			'disabled'=>''
           ),
           
           "state_field"=>array(
              'label'=>'State *',
            'type'=>'selectcustom',
            'name'=>'state',
            'id'=>'selectState',
            'classes'=>'form-control',
            'placeholder'=>'state',
            'value'=>CommonHelper::getState('101'),
			'disabled'=>'',
			'selected'=>''
           ),
           "city_field"=>array(
              'label'=>'City *',
            'type'=>'selectcustom',
            'name'=>'city',
            'id'=>'selectcity',
            'classes'=>'form-control',
            'placeholder'=>'city',
            'value'=>array(),
			'disabled'=>'',
			'selected'=>''
           ),
           "pincode_field"=>array(
              'label'=>'Pincode *',
            'type'=>'text',
            'name'=>'pincode',
            'id'=>'pincode',
            'classes'=>'form-control',
            'placeholder'=>'pincode',
            'value'=>'',
			'disabled'=>''
           ),
          
               "submit_button_field"=>array(
				  'label'=>'',
                  'type'=>'submit',
                  'name'=>'submit',
                  'id'=>'submit',
                  'classes'=>'btn btn-danger',
                  'placeholder'=>'',
                  'value'=>'Save'
            )
         )
       )
     );

	 if ($request->isMethod('post')) {
		 
		$input=$request->all();
	
            $request->validate([
				'address' => 'required|max:255',
				'state' => 'required|regex:/^[a-zA-Z\s]*$/|max:50',
				'city' => 'required|regex:/^[a-zA-Z\s]*$/|max:25',
				'pincode' => 'required|numeric|digits:6'
				//'pincode' => 'required|numeric|min:6|max:6'
				//'pincode' => 'required|regex:/^[0-9]{6,6}$/'
             ]
        );
			
		$res=DB::table('vendor_address')->insert(array(
                "address"=>$input['address'],
                "state"=>$input['state'],
                "city"=>$input['city'],
                "zip"=>$input['pincode'],
                "vendor_id"=>$vendor_id
		    ));
	
       /* save the following details */
      if(!$res){
				 MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      } else{
				 MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		 }
      return Redirect::back();
	 }
		return view('admin.vendors.address_form',['page_details'=>$page_details]);
    }
    
     public function edit_address(Request $request)
    {
        
         $vendor_id= base64_decode($request->vendor_id);
           $address_id= base64_decode($request->address_id);
          $vendor_info=Vendor::where('id',$vendor_id)->first();
          $vendor_address=DB::table('vendor_address')->where('id',$address_id)->first();
          
          $cities = DB::table('cities')
		              ->select('cities.name as name', 'cities.id as id', 'states.name as state_name')
		              ->join('states', 'states.id', '=', 'cities.state_id')
		              ->where('states.name',$vendor_address->state)->get();
		              
      	 $page_details=array(
            "Title"=>"Update  ( ".$vendor_info->username." )'s  address",
            "Method"=>"1",
            "Box_Title"=>"Update ( ".$vendor_info->username." )'s  address",
       "Action_route"=>route('edit_address', [base64_encode($vendor_id),base64_encode($address_id)]),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "address_field"=>array(
              'label'=>'Address *',
            'type'=>'text',
            'name'=>'address',
            'id'=>'address',
            'classes'=>'form-control',
            'placeholder'=>'Address',
            'value'=>$vendor_address->address,
			'disabled'=>''
           ),
           
            "state_field"=>array(
              'label'=>'State *',
            'type'=>'selectcustom',
            'name'=>'state',
            'id'=>'selectState',
            'classes'=>'form-control',
            'placeholder'=>'state',
            'value'=>CommonHelper::getState('101'),
			'disabled'=>'',
			'selected'=>$vendor_address->state
           ),
           "city_field"=>array(
              'label'=>'City *',
            'type'=>'selectcustom',
            'name'=>'city',
            'id'=>'selectcity',
            'classes'=>'form-control',
            'placeholder'=>'city',
            'value'=>$cities,
			'disabled'=>'',
			'selected'=>$vendor_address->city
           ),
           
//           "state_field"=>array(
//               'label'=>'State',
//             'type'=>'text',
//             'name'=>'state',
//             'id'=>'state',
//             'classes'=>'form-control',
//             'placeholder'=>'state',
//           'value'=>$vendor_address->state,
// 			'disabled'=>''
//           ),
//           "city_field"=>array(
//               'label'=>'City',
//             'type'=>'text',
//             'name'=>'city',
//             'id'=>'city',
//             'classes'=>'form-control',
//             'placeholder'=>'city',
//              'value'=>$vendor_address->city,
// 			'disabled'=>''
//           ),

           "pincode_field"=>array(
              'label'=>'Pincode *',
            'type'=>'text',
            'name'=>'pincode',
            'id'=>'pincode',
            'classes'=>'form-control',
            'placeholder'=>'pincode',
             'value'=>$vendor_address->zip,
			'disabled'=>''
           ),
          
               "submit_button_field"=>array(
				  'label'=>'',
                  'type'=>'submit',
                  'name'=>'submit',
                  'id'=>'submit',
                  'classes'=>'btn btn-danger',
                  'placeholder'=>'',
                  'value'=>'Save'
            )
         )
       )
     );

	 if ($request->isMethod('post')) {
		 
		$input=$request->all(); 
	
            $request->validate([
				'address' => 'required|max:255',
				'state' => 'required|regex:/^[a-zA-Z\s]*$/|max:50',
				'city' => 'required|regex:/^[a-zA-Z\s]*$/|max:25',
				'pincode' => 'required|numeric|digits:6'
				//'pincode' => 'required|numeric|min:6|max:6'
				//'pincode' => 'required|regex:/^[0-9]{6,6}$/'
             ]
        );
			
		$res=VendorMultiAddress::where('id',$address_id)->update(array(
                "address"=>$input['address'],
                "state"=>$input['state'],
                "city"=>$input['city'],
                "zip"=>$input['pincode']
		    ));
		/*$res=DB::table('vendor_address')->where('id',$address_id)->update(array(
                "address"=>$input['address'],
                "state"=>$input['state'],
                "city"=>$input['city'],
                "zip"=>$input['pincode']
		    ));*/
	
       /* save the following details */
      if(!$res){
				 MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      } else{
				 MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		 }
      return Redirect::back();
	 }
		return view('admin.vendors.address_form',['page_details'=>$page_details]);
    }
    
      public function delete_address(Request $request)
    {
                $address_id=base64_decode($request->address_id);
                
                $res=DB::table('vendor_address')->where('id',$address_id)->delete();
		

            if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_delete_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

                  return Redirect::back();
    }
   	 public function up_sell_product_search(Request $request)
    {
			$inputs=$request->all();
			$res=$this->productSearchCommon($request->session()->get('product_id'),$inputs,1);
			echo json_encode($res);
			
			
    }
    
     public function editcustomer(Request $request){
         	$Customer =Customer::where('id',base64_decode($request->id))->first();
         	
         $input=$request->all();
         $page_details=array(
        "Title"=>"Update Cusomter",
        "Method"=>"1",
        "Box_Title"=>"Update Cusomter",
        "Action_route"=>route('editcustomer',$request->id),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "name_field"=>array(
              'label'=>'Customer Name',
            'type'=>'text',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control',
            'placeholder'=>'Name',
             'value'=>$Customer->name,
			'disabled'=>''
           ),
           
            "email_field"=>array(
              'label'=>'Customer Email',
            'type'=>'text',
            'name'=>'email',
            'id'=>'email',
            'classes'=>'form-control',
            'placeholder'=>'Email',
            'value'=>$Customer->name,
			'disabled'=>''
           ),
           
            "phone_field"=>array(
              'label'=>'Customer Phone',
            'type'=>'text',
            'name'=>'phone',
            'id'=>'phone',
            'classes'=>'form-control',
            'placeholder'=>'Phone',
              'value'=>$Customer->phone,
			'disabled'=>''
           ),
            "password_field"=>array(
              'label'=>'password',
            'type'=>'password',
            'name'=>'password',
            'id'=>'password',
            'classes'=>'form-control',
            'placeholder'=>'*****',
            'value'=>'',
			'disabled'=>''
           ),
           
               "submit_button_field"=>array(
				  'label'=>'',
                  'type'=>'submit',
                  'name'=>'submit',
                  'id'=>'submit',
                  'classes'=>'btn btn-danger',
                  'placeholder'=>'',
                  'value'=>'Save'
            ),
			"images"=>array(
                  'color_image'=>''
            )
         )
       )
     );

	 if ($request->isMethod('post')) {
		 $id=base64_decode($request->id);
		$input=$request->all();
             $request->validate([
                'name' => 'required|max:50',
                'phone' => 'regex:/[0-9]{10}/|unique:customers,phone,'.$id.',id,isdeleted,0',
                'email' => 'unique:customers,email,'.$id.',id,isdeleted,0'
                
            ]);
			
		if($input['password']!=''){
		    $request->validate([
                
                'password' => 'min:6|max:50',
                
            ]);
		   	$res=Customer::where('id',$id)->update(
			    array(
                        "name"=>$input['name'],
                        "phone"=>$input['name'],
                        "email"=>$input['name'],
                        "password"=>Hash::make(trim($input['password'])
			        )
			    ));
		} else{
		    	$res=Customer::where('id',$id)->update(
			    array(
                        "name"=>$input['name'],
                        "phone"=>$input['name'],
                        "email"=>$input['name']
			        )
			    );
		}
		
			
     
      /* save the following details */
      if($res){
           MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
				
      } else{
			 MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);	
		 }
      return Redirect::back();
	 }
		return view('admin.customers.form',['page_details'=>$page_details]);
     }
     
      public function addCustomer(Request $request){
         $input=$request->all();
         $page_details=array(
        "Title"=>"Add Cusomter",
        "Method"=>"1",
        "Box_Title"=>"Add Cusomter",
        "Action_route"=>route('addCustomer'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "name_field"=>array(
              'label'=>'Customer Name',
            'type'=>'text',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control',
            'placeholder'=>'Name',
             'value'=>old('name'),
			'disabled'=>''
           ),
           
            "email_field"=>array(
              'label'=>'Customer Email',
            'type'=>'text',
            'name'=>'email',
            'id'=>'email',
            'classes'=>'form-control',
            'placeholder'=>'Email',
            'value'=>old('email'),
			'disabled'=>''
           ),
           
            "phone_field"=>array(
              'label'=>'Customer Phone',
            'type'=>'text',
            'name'=>'phone',
            'id'=>'phone',
            'classes'=>'form-control',
            'placeholder'=>'Phone',
              'value'=>old('phone'),
			'disabled'=>''
           ),
            "password_field"=>array(
              'label'=>'password',
            'type'=>'password',
            'name'=>'password',
            'id'=>'password',
            'classes'=>'form-control',
            'placeholder'=>'*****',
            'value'=>'',
			'disabled'=>''
           ),
           
               "submit_button_field"=>array(
				  'label'=>'',
                  'type'=>'submit',
                  'name'=>'submit',
                  'id'=>'submit',
                  'classes'=>'btn btn-danger',
                  'placeholder'=>'',
                  'value'=>'Save'
            ),
			"images"=>array(
                  'color_image'=>''
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
                
            ]);
			
		
			
				$Customer = new Customer;
				$Customer->name = $input['name'];
				$Customer->phone = $input['phone'];
				$Customer->email =  $input['email'];
				$Customer->password = Hash::make(trim($input['password']));
	
     
      /* save the following details */
      if($Customer->save()){
				 MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      } else{
				 MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		 }
      return Redirect::back();
	 }
		return view('admin.customers.form',['page_details'=>$page_details]);
     }
    
	public function customers(Request $request){
        
		$customers=Customer::select(
						'customers.*'
						);
		
		$search=$request->name;
		$isOtpVerified=$request->phonestatus;
		$status=$request->status;
			$row=$request->row;
		//$daterange=$request->daterange;		
		
			if($search!=''){
				    	$export=URL::to('admin/customers_search_export').'/'.$search.'/'.$status.'/'.$isOtpVerified;
				} else{
				    	$export=URL::to('admin/customers_export');
				}
		if($search=='all'){
			$search='';
		}
		
		if($isOtpVerified=='all'){
			$isOtpVerified='';
		}
                    if($row=='all'){
                    $row='';
                    }
		if($status=='all'){
			$status='';
		}
		
		if($search!='' && $search!='all'){
		  $customers=$customers
				->where('customers.name','LIKE',$search.'%')
				->orWhere('customers.email','LIKE',$search.'%')
				->orWhere('customers.phone','LIKE',$search.'%');
		}
		
		if($isOtpVerified!='' && $isOtpVerified!='all'){
		  $customers=$customers
				->where('customers.isOtpVerified','=',$isOtpVerified);
		}
		
		if($status!=''){
		  $customers=$customers
				->where('customers.status','=',$status);
		}
		 $customers=$customers
				->where('customers.isdeleted','=',0);
			
		
				
		$customers=$customers->orderBy('id', 'DESC');
		if($row!=''){
		    switch($row){
                    case 1:
                          $customers=$customers->paginate(50);
                    break;
                    
                    case 2:
                          $customers=$customers->paginate(100);
                    break;
                    
                    case 3:
                          $customers=$customers->paginate(200);
                    break;
                    
                    case 4:
                          $customers=$customers->paginate(300);
                    break;
		    }
		  
		} else{
		      $customers=$customers->paginate(500);
		}
		
	
		
	
        
		$page_details=array(
							"Title"=>"Customers List",
							"Box_Title"=>"Customer(s)",
							"search_route"=>URL::to('admin/customers_search'),
							 "export"=>$export,
							"reset_route"=>route('customers')
					 );
	 
        return view('admin.customers.list',['customers'=>$customers,'page_details'=>$page_details,'isOtpVerified'=>$isOtpVerified,'status'=>$status,'row'=>$row]);   
    }
    	public function customers_export(Request $request)
    {
	
                $search=$request->name;
                $isOtpVerified=$request->phonestatus;
                $status=$request->status;
		return Excel::download(new Customers_export($search,$isOtpVerified,$status), 'Customers'.date('d-m-Y H:i:s').'.csv');	
    }
    	public function customers_pay_export(Request $request)
    {
	        $daterange=$request->daterange;	
		return Excel::download(new CustomerPaymentHistory($daterange), 'CustomerPaymentHistory'.date('d-m-Y H:i:s').'.csv');	
    }
	
	public function customers_payment(Request $request){
          $daterange=$request->daterange;	
		$customers=Customer::select(
						'customers.*','orders.order_no','orders.grand_total','orders.txn_id','orders.txn_status','orders.order_date'
						);
	         $export=URL::to('admin/customers_pay_export');
		$customers=$customers
				->join('orders','customers.id','=','orders.customer_id')
				->whereRaw('orders.txn_id <> ""')
				->orderBy('orders.id','desc');
				
				if($daterange!='All' && $daterange!=''){
				     $export=URL::to('admin/customers_pay_export_dt').'/'.$daterange;
                    	$daterange_array=explode('.',$daterange);
                    	$from= date("Y-m-d", strtotime($daterange_array[0]));
                    	$to=date("Y-m-d", strtotime($daterange_array[1]));
                   $customers=$customers
				 			 ->whereBetween('orders.order_date',[$from,$to]);
                }
		
		$customers=$customers->paginate(10);
        
		$page_details=array(
							"Title"=>"Customers Payment List",
							"Box_Title"=>"Customer(s) Payment",
							"search_route"=>URL::to('admin/customers_pay_search'),
							"export"=>$export,
							"reset_route"=>''
					 );
	 
        return view('admin.customers.customer_payment_list',['customers'=>$customers,'page_details'=>$page_details,'daterange'=>$daterange]);   
    }
	
    public function customer_detail(Request $request){
        $id=base64_decode($request->id);
		
	
	 		$page_details=array(
				"Title"=>"Customer Detail",
				"Box_Title"=>"Customer Detail",
				"search_route"=>'',
				 "export"=>'',
				"reset_route"=>route('vendors')
     );
	 
	 $customer=Customer::where('id',$id)->first();
	
		$ship_address_list = CheckoutShipping::getshippingAddress($id);
        return view('admin.customers.detail',['customer'=>$customer,'page_details'=>$page_details,'shipping_listing'=>$ship_address_list]);
       
    }
	
	 public function customer_orders(Request $request){
        $id=base64_decode($request->id);
		
		$type= base64_decode($request->type);
		$str= $request->str;
		$daterange= $request->daterange;
		
		$page_details=array(
		   "Title"=>"Customer Orders",
		   "Box_Title"=>"List",
		   "search_route"=>URL::to('admin/customer_orders_search')."/".$request->id."/".$request->type,
		   "export"=>"",
			"reset_route"=>route('customer_orders',[$request->id, $request->type])
		 );
		 
		 if($str=='all'){
			$str=''; 
		 }
		 if($daterange=='all'){
			$daterange=''; 
		 }
		
		$customer=Customer::where('id',$id)->first();
					
		$Orders=OrdersDetail::select(
						'orders.order_no',
						'orders.order_date',
						'order_details.*'
						)
						->join('orders', 'orders.id', '=', 'order_details.order_id');
						
				if($id!=0){
				  $Orders=$Orders->join('products', 'products.id', '=', 'order_details.product_id')
				                    ->where('orders.customer_id',$id);
						
				}
				
				if($str!=''){
				  $Orders=$Orders
						->where('orders.order_no','LIKE',$str.'%')
						->orWhere('order_details.suborder_no','LIKE',$str.'%');
				}

				if($daterange!=''){
				 	$daterange=explode('--',$daterange);
				 	$daterange[0] = explode("-", $daterange[0]);
				 	$from = $daterange[0][2] . "-" . $daterange[0][1] . "-" . $daterange[0][0]; 

				 	$daterange[1] = explode("-", $daterange[1]);
				 	$to = $daterange[1][2] . "-" . $daterange[1][1] . "-" . $daterange[1][0];
					
				 	  $Orders=$Orders
				 			 ->whereBetween('orders.order_date',[$from,$to]);
				 } 
                
			if($type=='0')
			{	
				$Orders=$Orders->whereIn('order_details.order_status',array(0,1,2,3))->orderBy('order_details.id','desc')->paginate(100);
			}else{
				$Orders=$Orders->where('order_details.order_status',$type)->orderBy('order_details.id','desc')->paginate(100);
			}
			
			
			$parameters_level=$request->type;
			
		
        return view('admin.customers.orders',['customer'=>$customer,'page_details'=>$page_details,'orders'=>$Orders,'parameters_level'=>$parameters_level,'daterange'=>$request->daterange,'str'=>$str]);
    }
	
	public function customer_sts(Request $request)
    {
            $id=base64_decode($request->id);
            $sts=base64_decode($request->sts);

            $res=Customer::where('id',$id)
                    ->update(['status' => ($sts==0) ? 1 : 0]);

            if ($res) {
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
    }
	
	public function del(Request $request)
    {
            $id=base64_decode($request->id);

            $res=Customer::where('id',$id)
                    ->update(['isdeleted' => 1]);

            if ($res) {
                $input['user_id']=array($id);
         
    $customers_data=DB::table('customers')->select('phone')->whereIn('id',$input['user_id'])->get();
    $customer_phone=array();
        foreach($customers_data as $customer){
            array_push($customer_phone,$customer->phone);
        }
        if(sizeof($customer_phone)>0){
            
             DB::table('customer_phone_otp')
                    ->whereIn('phone',$customer_phone)
                    ->delete();
        }
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

                    return Redirect::route('customers');
    }
	
	public function customer_wallet(Request $request){
        $id=base64_decode($request->id);
		
		$page_details=array(
					"Title"=>"Customer Wallet",
					"Box_Title"=>"Customer Wallet",
					"search_route"=>'',
					"export"=>'',
					"reset_route"=>route('customers'),
					"back_route"=>route('customers'),
				);
	 
		$customer=Customer::where('id',$id)->first();
	
		$wallet_list = Wallet::wallet_history($id);
		
		return view('admin.customers.wallet_listing',['customer'=>$customer,'page_details'=>$page_details,'wallet_listing'=>$wallet_list]);
       
    }
    
     public function verify_customer_phone(Request $request)
    {
            $id=base64_decode($request->id);
            $sts=base64_decode($request->sts);

            $res=Customer::where('id',$id)
                    ->update(['isOtpVerified' => ($sts==0) ? 1 : 0,'status' => ($sts==0) ? 1 : 0]);

            if ($res) {
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
    }
     public function related_products_search(Request $request)
    {
		$inputs=$request->all();
		$res=$this->productSearchCommon($request->session()->get('product_id'),$inputs,0);
		echo json_encode($res);
    }
            public function product_review(Request $request){
                 $prd_id=base64_decode($request->id);
                 $prd_detail = Products::select('products.*')
            						   ->where('products.id','=',$prd_id)
            						   ->first();
                $ratings=Products::getAllReview($prd_id,50);
             
            return view('admin.product.reviews',["ratings"=>$ratings,'prd_detail'=>$prd_detail]);
             }
	
	 public function cross_sell_product_search(Request $request)
    {
		$inputs=$request->all();
		$res=$this->productSearchCommon($request->session()->get('product_id'),$inputs,2);
		echo json_encode($res);
    }
    
    	public function  productSearchCommon($prd_id,$inputs,$type){
		$ProductCategories = new ProductCategories;
					$cats=$ProductCategories->getCategories($prd_id);
					$ProductRelation = new ProductRelation;
					$relative_product=$ProductRelation->getRelativeProduct($prd_id,$type,$inputs);
				
				
			 $q=@$inputs['SearchByName'];
			 $res=Products::select('product_id','products.name')
								->join('product_categories', 'products.id', '=', 'product_categories.product_id')
								->where('products.name','LIKE',$q.'%');
								if(@$inputs['SearchByVisibility']!='' && @$inputs['SearchByVisibility']!=null){
										$res=$res->where('products.visibility',@$inputs['SearchByVisibility']);
								}
								if(@$inputs['SearchByStatus']!='' && @$inputs['SearchByStatus']!=null){
										$res=$res->where('products.prd_sts',@$inputs['SearchByStatus']);
								}
								 $res=$res->where('products.isdeleted',0)
								->whereIn('product_categories.cat_id',$cats)
								->where('product_id','!=',$prd_id)
								->whereNotIn('product_id',$relative_product)
								->orderBy('products.id', 'DESC')
								 ->groupBy('product_categories.product_id','products.name')
								->paginate(50);
								
									
								$total=ProductCategories::select('product_id','products.name')
								->join('products', 'products.id', '=', 'product_categories.product_id')
								->where('products.name','LIKE',$q.'%');
								if(@$inputs['SearchByVisibility']!=''){
										$total=$total->where('products.visibility',@$inputs['SearchByVisibility']);
								}
								if(@$inputs['SearchByStatus']!=''){
										$total=$total->where('products.prd_sts',@$inputs['SearchByStatus']);
								}
								$total=$total->where('isdeleted',0)
								 ->whereIn('product_categories.cat_id',$cats)
								 ->whereNotIn('product_id',$relative_product)
								 ->where('product_id','!=',$prd_id)
								->orderBy('products.id', 'DESC')
								 ->groupBy('product_categories.product_id','products.name')
								->get()
								->toarray();
							
				
				$data = array();
					
					$table_data=array();
					
					
					foreach ($relative_product as $rel_product) {
	
	$product = Products::select('id','name','prd_sts','visibility')
				->where('id',$rel_product)
				->first();
				
				$selected_id='';
			if (in_array($rel_product, $relative_product))
			{
			$selected_id="checked";
			}
				
	$checkbox='<input type="checkbox" name="related_product_id[]" value="'.$product->id.'" class="product_checkbox_child" '.$selected_id.'>';
	$sts='';
	switch($product->prd_sts){
			case(0);
		$sts.='<span>Disabled</span>';
		break;
		
		case(1);
		$sts.='<span>Enabled</span>';
		break;
	}
	
	$vis='';
	switch($product->visibility){
		case('');
		$vis.='<span>Not Selected</span>';
		break;
		case(1);
		$vis.='<span>Not visible individualy</span>';
		break;
		
		case(2);
		$vis.='<span>Catalog</span>';
		break;
		
		case(3);
		$vis.='<span>Search</span>';
		break;
		
		case(4);
		$vis.='<span>Catalog,Search</span>';
		break;
	}
 
		$html='<tr>';
		$html.='<td>'.$checkbox.'</td>';
		$html.='<td>'.$product->name.'</td>';
		$html.='<td>'.$sts.'</td>';
		$html.='<td>'.$vis.'</td>';
		$html.='</tr>';
		array_push($table_data,$html);
}
					
foreach ($res as $row) {
	
	$product = Products::select('id','name','prd_sts','visibility')
				->where('id',$row->product_id)
				->first();
				
				$selected_id='';
			if (in_array($row->product_id, $relative_product))
			{
			$selected_id="checked";
			}
				
	$checkbox='<input type="checkbox" name="related_product_id[]" value="'.$product->id.'" class="product_checkbox_child" '.$selected_id.'>';
	$sts='';
	switch($product->prd_sts){
			case(0);
		$sts.='<span>Disabled</span>';
		break;
		
		case(1);
		$sts.='<span>Enabled</span>';
		break;
	}
	
	$vis='';
	switch($product->visibility){
		case('');
		$vis.='<span>Not Selected</span>';
		break;
		case(1);
		$vis.='<span>Not visible individualy</span>';
		break;
		
		case(2);
		$vis.='<span>Catalog</span>';
		break;
		
		case(3);
		$vis.='<span>Search</span>';
		break;
		
		case(4);
		$vis.='<span>Catalog,Search</span>';
		break;
	}
 
		$html='<tr>';
		$html.='<td>'.$checkbox.'</td>';
		$html.='<td>'.$product->name.'</td>';
		$html.='<td>'.$sts.'</td>';
		$html.='<td>'.$vis.'</td>';
		$html.='</tr>';
		array_push($table_data,$html);
}


$json_data = array(
		"draw"            => 3,  
		"recordsTotal"    => sizeof($total),  
		"recordsFiltered" => sizeof($total),
		"table_data"     => (sizeof($table_data)>0?$table_data:'<tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">No data available in table</td></tr>')   // total data array
		);
		return $json_data;
	}


  public function contactusEnquiry(Request $request){
    $page_details=array(
      "Title"=>"Contact us", 
      "Box_Title"=>"Contact us"
    );


    if ($request->isMethod('post')) {
      $input=$request->all();
      $input=$request->all();
       $request->validate([
                'name' => 'required||max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|max:10',
                'message' => 'required|max:255',
            ]);
            
           
           $msg='   <tr>
                         <td style="padding:5px 10px;">
                            <strong>Name</strong>
                         </td>
                         <td style="padding:5px 10px;">
                                  <p><strong></strong>'.$input['name'].'</p>
                         </td>
                     </tr>
                     <tr>
                         <td style="padding:5px 10px;">
                            <strong>Email</strong>
                         </td>
                         <td style="padding:5px 10px;">
                                  <p><strong></strong>'.$input['email'].'</p>
                         </td>
                     </tr>
                     <tr>
                         <td style="padding:5px 10px;">
                            <strong>Phone</strong>
                         </td>
                         <td style="padding:5px 10px;">
                                  <p><strong></strong>'.$input['phone'].'</p>
                         </td>
                     </tr>
                  
                      
                     <tr>
                         <td style="padding:5px 10px;">
                            <strong>Message</strong>
                         </td>
                         <td style="padding:5px 10px;">
                                  <p><strong></strong>'.$input['message'].'</p>
                         </td>
                     </tr>

               ';
            
            $data = [
                'to'=>Config::get('constants.email.admin_to'),
                'subject'=>'Query',
                 "body"=>view("emails_template.contact_us",
                     array(
				    'data'=>array(
				        'message'=>$msg
				        )
				     ) )->render(),
				     'phone'=>$input['phone'],
				     'phone_msg'=>'Thank you ('.$input['name'].') for contacting us (18UP.in)'
                 ];
                // CommonHelper::SendmailCustom($data);
                // CommonHelper::SendMsg($data);
              
                $id=auth()->guard('vendor')->user()->id;
                 $newcontactus =  new VendorContactUs;
                 $newcontactus->vendor_id = $id; 
                 $newcontactus->name = $input['name'];
                 $newcontactus->mobile = $input['phone'];
                 $newcontactus->email = $input['email'];
                 $newcontactus->message = $input['message'];

            if($newcontactus->save()){
                MsgHelper::save_session_message('success','Thanks for contacting, we will get back to you soon.',$request);
                return Redirect::route('contact-us');
             } else{
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
                return Redirect::route('contact-us');
             }



    }
    return view('vendor.contactus',compact('page_details'));
  }
	
}
