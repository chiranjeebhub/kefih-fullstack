<?php

namespace App\Http\Controllers\sws_Admin;

use Illuminate\Http\Request;
use App\CouponDetails;
use App\Coupon;
use Redirect;
use Validator;
use DB;
use Config;
use App\Helpers\MsgHelper;
use App\Helpers\CommonHelper;
use App\Helpers\FIleUploadingHelper;
use App\Brands;
use App\Products;
use URL;
use Auth;
class CouponController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        //  $this->middleware('auth') ||  $this->middleware('auth:vendor');
		 $this->middleware('auth:vendor');
            // $this->middleware('auth');
          
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
	 public function couponaAssign(Request $request){
		 $c_id=base64_decode($request->id);
		 $coupon_assign=DB::table('tbl_coupon_assign')
                        ->where('fld_coupon_id',$c_id)
                        ->first();
                        
                        
                       
		  if ($request->isMethod('post')) {
    $input=$request->all();
  
    $data=array();
 switch($input['for_category_or_brand_or_product']){
        case 1:
                $request->validate([
                'category_field' => 'required',
                ]
			); 
			$data=array(
"fld_coupon_id"=>$c_id,
"fld_coupon_assign_type"=>$input['for_category_or_brand_or_product'],
"fld_assign_type_id"=>$input['category_field']
			    );
        break;
        
         case 2:
                 $request->validate([
                'brand_field' => 'required',
                ]
			);
			$data=array(
        "fld_coupon_id"=>$c_id,
        "fld_coupon_assign_type"=>$input['for_category_or_brand_or_product'],
        "fld_assign_type_id"=>$input['brand_field']
			    );
        break;
        
         case 3:
                $request->validate([
                'product_field' => 'required',
                ]
			);
				$data=array(
    "fld_coupon_id"=>$c_id,
    "fld_coupon_assign_type"=>$input['for_category_or_brand_or_product'],
    "fld_assign_type_id"=>$input['product_field']
			    );
        break;
 }

  
 		$coupon_assign=DB::table('tbl_coupon_assign')
                        ->where('fld_coupon_id',$c_id)
                        ->first();
                        if($coupon_assign){
                            $res=DB::table('tbl_coupon_assign')
                            ->where('fld_coupon_id',$c_id)
                            ->update($data);
                        } else{
                             $res=DB::table('tbl_coupon_assign')
                             ->insert($data);
                        }
              
              if($res){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
    
           }
          $brands = Brands::select('id','name')->where('isdeleted', 0)->where('status', 1)->get();
		    $products = Products::select('id','name')->where('isdeleted', 0)->where('status', 1)->get();
         	$page_details=array(
            "Title"=>"Assign Coupon",
            "Box_Title"=>"Assign Coupon",
            "search_route"=>'',
            "method"=>0,
            "Action_route"=>route('couponaAssign',base64_encode($c_id)),
		"Form_data"=>array(

         "Form_field"=>array(
           
              "for_category_or_brand_or_product"=>array(
				'label'=>'Offer Type',
				'type'=>'radio',
				'name'=>'for_category_or_brand_or_product',
				'id'=>'for_category_or_brand_or_product',
				'classes'=>' CouponAssignType',
				'placeholder'=>'',
				'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Category Wise",
							),(object)array(
							"id"=>"2",
							"name"=>"Brand Wise",
							),(object)array(
							"id"=>"3",
							"name"=>"Product Wise",
							)
							),
				'disabled'=>'',
				'selected'=>($coupon_assign)?$coupon_assign->fld_coupon_assign_type:1
				),
			
			
			"category_field"=>array(
				'label'=>'Category',
				'type'=>'select_with_inner_loop',
				'name'=>'category_field',
				'id'=>'category_field',
				'classes'=>'custom-select form-control catClass',
				'placeholder'=>'Name',
				'value'=>CommonHelper::getAdminChilds(1,'',($coupon_assign)?$coupon_assign->fld_assign_type_id:0)
			),
				"brand_field"=>array(
							'label'=>'Brand',
							'type'=>'select',
							'name'=>'brand_field',
							'id'=>'brand_field',
							'classes'=>'form-control brandClass',
							'placeholder'=>'',
							'value'=>$brands,
							'disabled'=>'',
							'selected'=>($coupon_assign)?$coupon_assign->fld_assign_type_id:1
			),
			"product_field"=>array(
							'label'=>'Product',
							'type'=>'select',
							'name'=>'product_field',
							'id'=>'product_field',
							'classes'=>'form-control ProductClass',
							'placeholder'=>'',
							'value'=>$products,
							'disabled'=>'',
							'selected'=>($coupon_assign)?$coupon_assign->fld_assign_type_id:1
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
 return view('admin.coupons.assign',['page_details'=>$page_details]);
     }
     
     
      public function edit_offer(Request $request){
             $id=base64_decode($request->id);
            $offer_data=DB::table('offer_categories')->where('id',$id)->first();
           if ($request->isMethod('post')) {
    $input=$request->all();
    $data=array();
  if($input['for_category_or_brand']==0){
    $request->validate([
                'offer_name' => 'required|max:254',
                'discount' => 'required|min:1|max:99',
                'category_field' => 'required',
                
            ]
			);  
			
			$data=array(
                "offer_name"=>$input['offer_name'],
                "offer_discount"=>$input['discount'],
                "offer_below_above"=>$input['discount_below_or_above'],
                "offer_zone_type"=>$input['for_category_or_brand'],
                "categories_id"=>$input['category_field'],
			    );
  } else{
   $request->validate([
                'offer_name' => 'required|max:254',
                'discount' => 'required|min:1|max:99',
                'brand_field' => 'required',
                
            ]
			);  
				$data=array(
                "offer_name"=>$input['offer_name'],
                "offer_discount"=>$input['discount'],
                "offer_below_above"=>$input['discount_below_or_above'],
                "offer_zone_type"=>$input['for_category_or_brand'],
                "categories_id"=>$input['brand_field']
			    );
  }
  
 		 $res=DB::table('offer_categories')
 		            ->where('id',$id)
                   ->update($data);
              
              if($res){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
    
           }
          $brands = Brands::select('id','name')->where('isdeleted', 0)->get();
         	$page_details=array(
            "Title"=>"Edit Offer",
            "Box_Title"=>"Edit Offer",
            "search_route"=>'',
            "method"=>1,
            "Action_route"=>route('edit_offer', [base64_encode($id)]),
		"Form_data"=>array(

         "Form_field"=>array(
           
           "offer_name"=>array(
                'label'=>'Offer Name',
                'type'=>'text',
                'name'=>'offer_name',
                'id'=>'offer_name',
                'classes'=>'form-control',
                'placeholder'=>'Name',
                'value'=>$offer_data->offer_name,
                'disabled'=>''
           ),
             
           "discount"=>array(
                'label'=>'Discount (%)',
                'type'=>'number',
                'name'=>'discount',
                'id'=>'discount',
                'classes'=>'form-control',
                'placeholder'=>'0',
				'value'=>$offer_data->offer_discount,
                'disabled'=>''
           ),
           
           "discount_below_or_above"=>array(
				'label'=>'Offer Apply On <br>',
				'type'=>'radio',
				'name'=>'discount_below_or_above',
				'id'=>'discount_below_or_above',
				'classes'=>'radioSelection',
				'placeholder'=>'',
				'value'=>array(
							(object)array(
							"id"=>"0",
							"name"=>"Smaller or Equal to (>=)",
							),(object)array(
							"id"=>"1",
							"name"=>"Greater or Equal to (<=)",
							)
							),
				'disabled'=>'',
				'selected'=>$offer_data->offer_below_above
				),
              "for_category_or_brand"=>array(
				'label'=>'Offer Type',
				'type'=>'radio',
				'name'=>'for_category_or_brand',
				'id'=>'for_category_or_brand',
				'classes'=>'radioSelection  offerType',
				'placeholder'=>'',
				'value'=>array(
							(object)array(
							"id"=>"0",
							"name"=>"Category Wise",
							),(object)array(
							"id"=>"1",
							"name"=>"Brand Wise",
							)
							),
				'disabled'=>'',
				'selected'=>$offer_data->offer_zone_type
				),
			
			
			"category_field"=>array(
              'label'=>'Category',
            'type'=>'select_with_inner_loop',
            'name'=>'category_field',
            'id'=>'category_field',
            'classes'=>'custom-select form-control catClass',
            'placeholder'=>'Name',
            'value'=>CommonHelper::getAdminChilds(1,'',$offer_data->categories_id)
           ),
				"brand_field"=>array(
							'label'=>'Brand',
							'type'=>'select',
							'name'=>'brand_field',
							'id'=>'brand_field',
							'classes'=>'form-control brandClass',
							'placeholder'=>'',
							'value'=>$brands,
							'disabled'=>'',
							'selected'=>$offer_data->categories_id
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
	 
return view('admin.extrafeature.offers.form',['page_details'=>$page_details]);
	 }
    public function lists(Request $request)
    {
      
       
		$type=base64_decode($request->type);	
		$page_details=array(
       "Title"=>"Coupon List",
       "Box_Title"=>"Coupon(s)",
	   	"search_route"=>'',
		"reset_route"=>''
     );
     if(Auth::guard('vendor')->check()){
          $coupons=Coupon::where('coupon_type',$type)->where('vendor_id', Auth::guard('vendor')->user()->id)->get();
      
     } else{
         $coupons=Coupon::where('coupon_type',$type)->get();  
     }
				
        return view('admin.coupons.list',['coupons'=>$coupons,'page_details'=>$page_details]);
    }
    
    
    public function couponDetail(Request $request)
    {
      	$id=base64_decode($request->id);
      	
		$page_details=array(
		   "Title"=>"Coupon Detail",
		   "Box_Title"=>"Coupon Detail",
			"search_route"=>'',
			"reset_route"=>''
		 );
      
      	$coupons=Coupon::where('id',$id)->with('CouponDetail')->first();
      	
      	 return view('admin.coupons.coupon-details',['coupons'=>$coupons,'page_details'=>$page_details]);
      	
    }
    
    	 public function couponDelete(Request $request)
    {
      	$node=base64_decode($request->node);
      	$id=base64_decode($request->id);
        MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      	if($node==1){
                $coupon = CouponDetails::where('id',$id)->first();
                $coupon_count = count(CouponDetails::where('coupon_id',$coupon->coupon_id)->get());
                
      	        if($coupon_count == 1){
      	            CouponDetails::where('id',$id)->delete(); 
      	            Coupon::where('id',$coupon->coupon_id)->delete();
                    return Redirect::route('coupons',base64_encode(0));
      	        }else{
      	           CouponDetails::where('id',$id)->delete(); 
      	        }
      	        
      	                           
		 
      	} elseif($node==0){
                    Coupon::where('id',$id)->delete();
                    CouponDetails::where('coupon_id',$id)->delete();
      	}
      	else{
      	    	MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
		
      	}
      		return Redirect::back();
          
      	
    }
             public function couponEdit(Request $request)
            {
            $id=base64_decode($request->id);
            $data=Coupon::where('id',$id)->first();
            
                        if ($request->isMethod('post')) {
                              $input=$request->all();
                           $request->validate([
                        'name' => 'required|max:25',
                        'couponType' => 'required',
                        'short_description' => 'required|max:255',
                        'discount' => 'required|numeric|min:1|max:99|not_in:0',
                        'couponNumber' => 'required|numeric|min:1|not_in:0'
                        
                    ]
                    );
                    if($input['couponType']==0){
                        $request->validate([
                        'spcl_from_date' => 'date|date_format:m/d/Y|before:spcl_to_date|required',
                        'spcl_to_date'=> 'date|date_format:m/d/Y|after:spcl_from_date|required',
                        ]
                        );
                    }
                                  
                                    
                                    $start_date=($input['issuedate']!='')?date("Y-m-d", strtotime($input['issuedate'])):null;
                                    $end_date=($input['expire_date_field']!='')?date("Y-m-d", strtotime($input['expire_date_field'])):null;
                            $id=base64_decode($request->id);
                            
            $Coupon = Coupon::find($id);
            $Coupon->coupon_name = $input['name'];
            $Coupon->coupon_type =$input['couponType'];
            $Coupon->description =$input['short_description'];
            
            $Coupon->started_date =$start_date;
            $Coupon->end_date =$end_date;
            $Coupon->discount_value = $input['discount'];
            $Coupon->total_coupon = $input['couponNumber'];
			
     
      /* save the following details */
      if($Coupon->save()){
          
          CouponDetails::where('coupon_id',$id)->update(
              array(
                  'started_date'=>$start_date,
                  'end_date'=>$end_date,
                  )
                 );
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
               	return Redirect::back();
                        }
                        
                    	$page_details=array(
       "Title"=>"Update Coupon",
       "Action_route"=>route('couponEdit', base64_encode($id)),
       "Box_Title"=>"Update Coupon",
	   	"search_route"=>'',
		"reset_route"=>'',
		"Form_data"=>array(
		      "Form_field"=>array(
		           "coupon_name_field"=>array(
							'label'=>'Name *',
							'type'=>'text',
							'name'=>'name',
							'id'=>'name',
							'classes'=>'form-control',
							'placeholder'=>'Name',
							'value'=>$data->coupon_name,
							'disabled'=>''
									),
			 "short_description_field"=>array(
							'label'=>'Short Description *',
							'type'=>'textarea',
							'name'=>'short_description',
							'id'=>'short_description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'Short description',
						    'value'=>$data->description,
							'disabled'=>''
			),
		          "issue_date_field"=>array(
							'label'=>'Issue Date *',
							'type'=>'date',
							'name'=>'issuedate',
							'id'=>'issuedate',
							'classes'=>'datepicker form-control',
							'placeholder'=>'',
							'value'=>($data->started_date!='')?date("m/d/Y", strtotime($data->started_date)):'',
							'disabled'=>''
			),
			"expire_date_field"=>array(
							'label'=>'Expiry Date *',
							'type'=>'date',
							'name'=>'expire_date_field',
							'id'=>'expire_date_field',
							'classes'=>'datepicker form-control',
							'placeholder'=>'',
								'value'=>($data->end_date!='')?date("m/d/Y", strtotime($data->end_date)):'',
							'disabled'=>''
			),
			"coupon_type_field"=>array(
							'label'=>'Coupon Type *',
							'type'=>'select',
							'name'=>'couponType',
							'id'=>'couponType',
							'classes'=>'form-control couponType',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"0",
							"name"=>"Static",
							),(object)array(
							"id"=>"1",
							"name"=>"Offer",
							)
							),
							'disabled'=>'disabled',
							'selected'=>$data->coupon_type
			),
				"discount_field"=>array(
							'label'=>'Discount % *',
							'type'=>'number',
							'name'=>'discount',
							'id'=>'discount',
							'classes'=>'form-control',
							'placeholder'=>'0',
							 'value'=>$data->discount_value,
							'disabled'=>''
			),
				"number_coupon_feild"=>array(
							'label'=>'Total Number Coupon *',
							'type'=>'number',
							'name'=>'couponNumber',
							'id'=>'couponNumber',
							'classes'=>'form-control couponNumber',
							'placeholder'=>'0',
							 'value'=>$data->total_coupon,
							'disabled'=>''
			),
			 "banner_file_field"=>array(
			    'label'=>'Banner Image *',
                'type'=>'file_special',
                'name'=>'banner_image',
               'id'=>'file-2',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>''
                
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
		    ),
     );
        return view('admin.coupons.form',['page_details'=>$page_details]);
       
          
      	
    }
    
	 public function add(Request $request)
    {
        
        
	  if ($request->isMethod('post')) {
	       $input=$request->all();
                    $request->validate([
                        'name' => 'required|max:25',
                        'couponType' => 'required',
                        'short_description' => 'max:255',
                        'discount' => 'required|numeric|min:1|max:99|not_in:0',
                        'couponNumber' => 'required|numeric|min:1|not_in:0',
                        //  'minimum_amount' => 'required|numeric|min:1|not_in:0',					
						'banner_image' => 'required|mimes:jpeg,bmp,png|min:'.Config::get('constants.size.banner_min').'|max:'.Config::get('constants.size.banner_max').''    

                        
                    ],
					
					[
					'discount.required'=>"The discount must be at least 1"
					]
                    );
                    switch ($input['couponType']) {
                        case "1" || "5":
                       $request->validate([
                        'minimum_amount' => 'required|numeric|min:1|not_in:0',
                         
                    ]
                    );
                        break;
                        
                        case "2" || "6":
                            
                        $request->validate([
                        'issuedate' => 'date|date_format:m/d/Y|before:expire_date_field|required',
                        'expire_date_field'=> 'date|date_format:m/d/Y|after:issuedate|required',
                        ]
                        );
                        
                        break;
                        
                        case "3" || "7":
                            
                            $request->validate([
                            'issuedate' => 'date|date_format:m/d/Y|before:expire_date_field|required',
                            'expire_date_field'=> 'date|date_format:m/d/Y|after:issuedate|required',
                            'minimum_amount' => 'required|numeric|min:1|not_in:0',
                        ]
                        );
                        
                        break;
                        
                     
                    
                    }
                   
                    
                 
		    
		  
            $start_date=($input['issuedate']!='')?date("Y-m-d", strtotime($input['issuedate'])):null;
            $end_date=($input['expire_date_field']!='')?date("Y-m-d", strtotime($input['expire_date_field'])):null;
		      
        $Coupon = new Coupon;
        
            if ($request->hasFile('banner_image')) {
			$banner_image = $request->file('banner_image');
			$destinationPath2 =  Config::get('constants.uploads.coupon_banner');
			$file_name2=$banner_image->getClientOriginalName();

        $file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$banner_image,$file_name2,150,150);
      if($file_name2==''){
        MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
         return Redirect::back();
      }
              $Coupon->banner = $file_name2;
    }
    
    if(Auth::guard('vendor')->check()){
          $Coupon->vendor_id = Auth::guard('vendor')->user()->id;
      
     } else{
        $Coupon->vendor_id = 0;
     }
        $Coupon->coupon_name = $input['name'];
        $Coupon->below_cart_amt = $input['minimum_amount'];
         $Coupon->above_cart_amt = $input['above_cart_amt'];
        $Coupon->coupon_type =$input['couponType'];
        $Coupon->description =$input['short_description'];
        
        $Coupon->started_date =$start_date;
        $Coupon->end_date =$end_date;
        $Coupon->discount_value = $input['discount'];
        $Coupon->total_coupon = $input['couponNumber'];
			
     
      /* save the following details */
      if($Coupon->save()){
          $id=$Coupon->id;
          $coupons=array();
          for($j=1;$j<=$input['couponNumber'];$j++){
              $characters = date('YmdHis').'0123456789abcdefghijklmnopqrstuvwxyz'; 
                $code = ''; 
                for ($i = 0; $i < 8; $i++) { 
                $index = rand(0, strlen($characters) - 1); 
                $code .= $characters[$index]; 
                }
              $single_coupon=array(
                    'coupon_code'=>$code,
                    'coupon_id'=>$id,
                    'coupon_used'=>0,
                    'started_date'=>$start_date,
                    'end_date'=>$end_date
                  );
                  array_push($coupons,$single_coupon);
          }
               
               CouponDetails::insert($coupons);
               
		  //MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  MsgHelper::save_session_message('success','Coupon code added successfully',$request);
		  return redirect()->route('addCoupon');
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
		     
	  }
		$page_details=array(
       "Title"=>"Add Coupon",
       "Action_route"=>route('addCoupon'),
       "Box_Title"=>"Add Coupon",
	   	"search_route"=>'',
		"reset_route"=>'',
		"Form_data"=>array(
		      "Form_field"=>array(
		           "coupon_name_field"=>array(
							'label'=>'Name *',
							'type'=>'text',
							'name'=>'name',
							'id'=>'name',
							'classes'=>'form-control',
							'placeholder'=>'Name',
							'value'=>'',
							'disabled'=>''
									),
			 "short_description_field"=>array(
							'label'=>'Short Description',
							'type'=>'textarea',
							'name'=>'short_description',
							'id'=>'short_description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'Short description',
							'value'=>'',
							'disabled'=>''
			),
		          "issue_date_field"=>array(
							'label'=>'Issue Date *',
							'type'=>'date',
							'name'=>'issuedate',
							'id'=>'issuedate',
							'classes'=>'datepicker form-control',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>''
			),
			"expire_date_field"=>array(
							'label'=>'Expiry Date *',
							'type'=>'date',
							'name'=>'expire_date_field',
							'id'=>'expire_date_field',
							'classes'=>'datepicker form-control',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>''
			),
			"coupon_type_field"=>array(
							'label'=>'Coupon Type *',
							'type'=>'select',
							'name'=>'couponType',
							'id'=>'couponType',
							'classes'=>'form-control couponType',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"0",
							"name"=>"Static",
							),(object)array(
							"id"=>"1",
							"name"=>"Static with Cart",
							),(object)array(
							"id"=>"2",
							"name"=>"Static with Periodic",
							),(object)array(
							"id"=>"3",
							"name"=>"Static with Periodic & Cart",
							),(object)array(
							"id"=>"4",
							"name"=>"Offer",
							),(object)array(
							"id"=>"5",
							"name"=>"Offer with Cart",
							),(object)array(
							"id"=>"6",
							"name"=>"Offer with Periodic",
							),(object)array(
							"id"=>"7",
							"name"=>"Offer with Periodic & Cart",
							)
							),
							'disabled'=>'',
							'selected'=>''
			),
			"discount_field"=>array(
							'label'=>'Discount % *',
							'type'=>'number',
							'name'=>'discount',
							'id'=>'discount',
							'classes'=>'form-control',
							'placeholder'=>'0',
							'value'=>'',
							'disabled'=>''
			),
				"number_coupon_feild"=>array(
							'label'=>'Total Number Coupon *',
							'type'=>'number',
							'name'=>'couponNumber',
							'id'=>'couponNumber',
							'classes'=>'form-control couponNumber',
							'placeholder'=>'0',
							'value'=>1,
							'disabled'=>''
			),
				"cart_amount_feild"=>array(
							'label'=>'Minimum cart amount *',
							'type'=>'number',
							'name'=>'minimum_amount',
							'id'=>'minimum_amount',
							'classes'=>'form-control',
							'placeholder'=>'0',
							'value'=>1,
							'disabled'=>''
			),
			"cart_max_amount_feild"=>array(
							'label'=>'Maximum cart amount *',
							'type'=>'number',
							'name'=>'above_cart_amt',
							'id'=>'above_cart_amt',
							'classes'=>'form-control',
							'placeholder'=>'0',
							'value'=>1,
							'disabled'=>''
			),
			 "banner_file_field"=>array(
			    'label'=>'Banner Image *',
                'type'=>'file_special_imagepreview',
                'name'=>'banner_image',
               'id'=>'file-2',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
				'value'=>'',
				'onchange'=>'image_preview(event)'
                
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
		    ),
     );
        return view('admin.coupons.form',['page_details'=>$page_details]);
    }
	
	public function coupon_sts(Request $request)
    {
            $id=base64_decode($request->id);
            $sts=base64_decode($request->sts);

			$res=DB::table('coupons')
					->where('id',$id)
                    ->update(['status' => ($sts==0) ? 1 : 0]);

            if ($res) {
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
    }

	


}
