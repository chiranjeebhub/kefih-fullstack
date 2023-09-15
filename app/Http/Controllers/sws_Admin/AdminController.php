<?php

namespace App\Http\Controllers\sws_Admin;

use Redirect;
use Validator;
use DB;
use Config;
use Auth;
use Illuminate\Http\Request;
use App\Logistics;
use App\Products;
use App\ProductImages;
use App\ProductCategories;
use App\ProductRelation;
use App\Brands;
use App\Colors;
use App\Sizes;
use App\User;
use App\Vendor;
use App\OrdersDetail;
use App\ProductAttributes;
use App\Helpers\CommonHelper;
use App\Helpers\MsgHelper;
use App\Helpers\FIleUploadingHelper;
use App\Helpers\CustomFormHelper;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VendorExport;
use App\Exports\Invoice;
use URL;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     public function admin_logout(Request $request){
          Auth::logout();
         return redirect()->route('admin_login');
     }
     
    
     public function index(Request $request)
    {
        
       
				$parameters=$request->str;	
				$status=$request->status;	
		
		if($parameters=='all'){
			$parameters='';
		}
		if($status=='all'){
			$status='';
		}
		
		if($parameters!=''){
			$export=route('exportvendor_with_Search',($request->str));
			} else{
			$export=route('exportVendor');
		  }
		
	 		$page_details=array(
				"Title"=>"Vendor List",
				"Box_Title"=>"Vendor(s)",
				"search_route"=>URL::to('admin/vendor_search'),
				 "export"=>$export,
				"reset_route"=>route('vendors')
     );
	 
	
	
        $vendors= Vendor::where('user_role','!=', 0);
	
		if($parameters!=''){
          /*
				  $vendors=$vendors
						->where('vendors.username','LIKE',$parameters.'%')
						->orWhere('vendors.public_name','LIKE',$parameters.'%')
						->orWhere('vendors.email','LIKE',$parameters.'%')
						->orWhere('vendors.phone','LIKE',$parameters.'%')
						->orWhere('vendors.id','LIKE',$parameters.'%');
          */
            $vendors=$vendors
						->where(function ($query) use ($parameters) {
    $query->where('vendors.username', 'like', '%' . $parameters . '%')
          ->orWhere('vendors.public_name', '%' . $parameters . '%')
          ->orWhere('vendors.email', '%' . $parameters . '%')
       ->orWhere('vendors.phone', '%' . $parameters . '%')
       ->orWhere('vendors.id', '%' . $parameters . '%');
});

				} 
		if($status!=''){
				  $vendors=$vendors
						->where('vendors.status','=',$status);
				} 
      
		$vendors=$vendors->orderBy('id', 'DESC')->where(['vendors.isdeleted'=>0])->groupBy('vendors.id')->paginate(100);
	
        return view('admin.vendors.list',['vendors'=>$vendors,'page_details'=>$page_details,'status'=>$status]);
    }
	
	
	public function multi_delete_vendor(Request $request)
    {
			$input=$request->all();
			Vendor::whereIn('id', $input['vendor_id'])
    ->update([
        'isdeleted' =>1
    ]);
	
	  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		   return redirect()->route('vendors');
			
    }
	 public function exportVendor(Request $request)
    {
		$str=$request->str;
		return Excel::download(new VendorExport($str), 'Vendors'.date('d-m-Y H:i:s').'.csv');
		
    }
     public function pdfVendor(Request $request)
    {
		$str=$request->str;
		return DOMPDF::download(new Invoice($str), 'Vendorsfrgrgrg'.date('d-m-Y H:i:s').'.csv');
		
    }
	  public function add_vendor(Request $request)
    {
           $last_vendor = Vendor::orderby('id','DESC')->first();
	  $rid = $last_vendor->id + 1;
	  $rid = str_pad($rid, 4, '0', STR_PAD_LEFT);
		  $level= base64_decode($request->level);	
		
		
	
 $page_details=array(
       "Title"=>"Add Vendor",
		"Method"=>"1",
		"Box_Title"=>"Add Vendor",
       "Action_route"=>route('add_vendor',(base64_encode($level))),
       "Form_data"=>array(

         "Form_field"=>array(
             "vendor_f_name_field"=>array(
							'label'=>'First Name',
							'type'=>'text',
							'name'=>'f_name',
							'id'=>'f_name',
							'classes'=>'form-control',
							'placeholder'=>'First Name',
							'value'=>old('f_name'),
							'disabled'=>''
									),
				"vendor_l_name_field"=>array(
						'label'=>'Last Name',
						'type'=>'text',
						'name'=>'l_name',
						'id'=>'l_name',
						'classes'=>'form-control',
						'placeholder'=>'Last Name',
						'value'=>old('l_name'),
						'disabled'=>''
				),
				"vendor_user_name_field"=>array(
						'label'=>'User Name *',
						'type'=>'text',
						'name'=>'username',
						'id'=>'username',
						'classes'=>'form-control',
						'placeholder'=>'User Name',
						'value'=>old('username'),
						'disabled'=>''
				),
				"vendor_public_name_field"=>array(
						'label'=>'Public Name',
						'type'=>'text',
						'name'=>'public_name',
						'id'=>'public_name',
						'classes'=>'form-control',
						'placeholder'=>'Public Name',
						'value'=>old('public_name'),
						'disabled'=>''
				),
				"vendor_email_field"=>array(
						'label'=>'Email *',
						'type'=>'text',
						'name'=>'email',
						'id'=>'email',
						'classes'=>'form-control',
						'placeholder'=>'Email',
						'value'=>old('email'),
						'disabled'=>''
				),
				"vendor_phone_field"=>array(
						'label'=>'Phone *',
						'type'=>'text',
						'name'=>'phone',
						'id'=>'phone',
						'classes'=>'form-control',
						'placeholder'=>'Phone',
						'value'=>old('phone'),
						'disabled'=>''
				),
				"vendor_password_field"=>array(
						'label'=>'Password *',
						'type'=>'password',
						'name'=>'password',
						'id'=>'password',
						'classes'=>'form-control',
						'placeholder'=>'******',
						'value'=>'',
						'disabled'=>''
				),
			"vednor_gender_field"=>array(
							'label'=>'Gender',
							'type'=>'select',
							'name'=>'gender',
							'id'=>'gender',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Male",
							),(object)array(
							"id"=>"2",
							"name"=>"Female",
							)
							),
							'disabled'=>'',
							'selected'=>old('gender')
			),
			"vendor_profile_pic_field"=>array(
						'label'=>'Profile Pic',
						'type'=>'file_special',
						'name'=>'profile_pic',
                        'id'=>'file-1',
                        'classes'=>'inputfile inputfile-4',
						'placeholder'=>'',
						'value'=>'',
						'disabled'=>''
				),
             "vendor_signature_pic_field"=>array(
						'label'=>'Signature',
						'type'=>'file_special',
						'name'=>'signature_pic',
                        'id'=>'file-2',
                        'classes'=>'inputfile inputfile-4',
						'placeholder'=>'',
						'value'=>'',
						'disabled'=>''
				),
				 "vendor_tax_type_field"=>array(
				'label'=>'Tax Type',
				'type'=>'radio',
				'name'=>'tax_type',
				'id'=>'tax_type',
				'classes'=>'radioSelection',
				'placeholder'=>'',
				'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Flat Percent",
							),(object)array(
							"id"=>"2",
							"name"=>"Fixed Rate",
							)
							),
				'disabled'=>'',
				'selected'=>1
				),
				"vendor_tax_rate_field"=>array(
						'label'=>'Tax',
						'type'=>'text',
						'name'=>'tx_rate',
						'id'=>'tx_rate',
						'classes'=>'form-control hideextraField',
						'placeholder'=>'',
						'value'=>old('tx_rate'),
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
				"vendor_company_address_field"=>array(
						'label'=>'Company Address',
						'type'=>'text',
						'name'=>'company_address',
						'id'=>'company_address',
						'classes'=>'form-control',
						'placeholder'=>'Company Address',
						'value'=>old('company_address'),
						'disabled'=>''
				),
				"vendor_company_state_field"=>array(
						'label'=>'Company State',
						'type'=>'text',
						'name'=>'company_state',
						'id'=>'company_state',
						'classes'=>'form-control',
						'placeholder'=>'Company State',
						'value'=>old('company_state'),
						'disabled'=>''
				),
				"vendor_company_city_field"=>array(
						'label'=>'Company City',
						'type'=>'text',
						'name'=>'company_city',
						'id'=>'company_city',
						'classes'=>'form-control',
						'placeholder'=>'Company City',
						'value'=>old('company_city'),
						'disabled'=>''
				),
				"vendor_company_pincode_field"=>array(
						'label'=>'Pincode',
						'type'=>'text',
						'name'=>'company_pincode',
						'id'=>'company_pincode',
						'classes'=>'form-control',
						'placeholder'=>'Pincode',
						'value'=>old('company_pincode'),
						'disabled'=>''
				),
						"vendor_company_type_field"=>array(
                                'label'=>'Company Type',
                                'type'=>'selectcustom',
                                'name'=>'company_type',
                                'id'=>'selectype',
                                'classes'=>'form-control',
                                'placeholder'=>'Company Type',
                                'value'=>CommonHelper::getType(),
                    			'disabled'=>'',
                    			'selected'=>''
                               ),
			"vendor_pancard_field"=>array(
								'label'=>'PAN Card',
								'type'=>'file_special_imagepreview',
								'name'=>'pancard',
								'id'=>'file-23',
								'classes'=>'inputfile inputfile-4',
								'placeholder'=>'',
								'value'=>'',
								'disabled'=>'',
								'onchange'=>'image_preview(event)'
						),
			"vendor_certificate_field"=>array(
								'label'=>'Trademark certificate',
								'type'=>'file_special_imagepreview',
								'name'=>'certificate',
								'id'=>'file-24',
								'classes'=>'inputfile inputfile-4',
								'placeholder'=>'',
								'value'=>'',
								'disabled'=>'',
								'onchange'=>'image_preview(event)'
						),
			"vendor_other_field"=>array(
								'label'=>'Other Documents',
								'type'=>'file_special_imagepreview',
								'name'=>'other_documents',
								'id'=>'file-25',
								'classes'=>'inputfile inputfile-4',
								'placeholder'=>'',
								'value'=>'',
								'disabled'=>'',
								'onchange'=>'image_preview(event)'
						),
			"vendor_adharcard_field"=>array(
								'label'=>'Aadhar Card',
								'type'=>'file_special_imagepreview',
								'name'=>'adharcard',
								'id'=>'file-26',
								'classes'=>'inputfile inputfile-4',
								'placeholder'=>'',
								'value'=>'',
								'disabled'=>'',
								'onchange'=>'image_preview(event)'
						),
			"vendor_address_proof_field"=>array(
								'label'=>'Address Proof',
								'type'=>'file_special_imagepreview',
								'name'=>'address_proof',
								'id'=>'file-27',
								'classes'=>'inputfile inputfile-4',
								'placeholder'=>'',
								'value'=>'',
								'disabled'=>'',
								'onchange'=>'image_preview(event)'
						),
				"vendor_pannumber_field"=>array(
                                'label'=>'PAN Number',
                                'type'=>'text',
                                'name'=>'pannumber',
                                'id'=>'pannumber',
                                'classes'=>'form-control',
                                'placeholder'=>'PAN Number',
                                'value'=>old('pannumber'),
                    			'disabled'=>'',
                    			
                               ),
				"vendor_company_about_field"=>array(
					'label'=>'About Us',
					'type'=>'textarea',
					'name'=>'company_about',
					'id'=>'company_about',
					'classes'=>'ckeditor form-control',
					'placeholder'=>'About',
					'value'=>old('company_about'),
					'disabled'=>''
					),
				"vendor_company_logo_field"=>array(
						'label'=>'Company Logo',
						 'type'=>'file_special',
						'name'=>'company_logo',
					'id'=>'file-5',
					'classes'=>'inputfile inputfile-4',
						'placeholder'=>'',
						'value'=>'',
						'disabled'=>''
				),
				"vendor_support_number_field"=>array(
						'label'=>'Phone',
						'type'=>'text',
						'name'=>'phone',
						'id'=>'phone',
						'classes'=>'form-control',
						'placeholder'=>'Phone',
						'value'=>old('phone'),
						'disabled'=>''
				),
				"vendor_support_email_field"=>array(
						'label'=>'Email',
						'type'=>'text',
						'name'=>'email',
						'id'=>'email',
						'classes'=>'form-control',
						'placeholder'=>'Email',
						'value'=>old('email'),
						'disabled'=>''
				),
				"vendor_support_fb_field"=>array(
						'label'=>'Facebook Id',
						'type'=>'text',
						'name'=>'fb_id',
						'id'=>'fb_id',
						'classes'=>'form-control',
						'placeholder'=>'',
						'value'=>old('fb_id'),
						'disabled'=>''
				),
				"vendor_support_tw_field"=>array(
						'label'=>'Twitter Id',
						'type'=>'text',
						'name'=>'tw_id',
						'id'=>'tw_id',
						'classes'=>'form-control',
						'placeholder'=>'',
						'value'=>old('tw_id'),
						'disabled'=>''
				),
			 "vendor_seo_meta_title_field"=>array(
							'label'=>'Meta Title',
							'type'=>'text',
							'name'=>'meta_title',
							'id'=>'meta_title',
							'classes'=>'form-control',
							'placeholder'=>'Meta Title',
							'value'=>old('meta_title'),
							'disabled'=>''
									),
			"vendor_seo_meta_description_field"=>array(
						'label'=>'Meta Description',
						'type'=>'textarea',
						'name'=>'meta_description',
						'id'=>'meta_description',
						'classes'=>'form-control',
						'placeholder'=>'Meta description',
						'value'=>old('meta_description'),
						'disabled'=>''
			),
			"vendor_seo_meta_keyword_field"=>array(
						'label'=>'Meta Keyword',
						'type'=>'textarea',
						'name'=>'meta_keyword',
						'id'=>'meta_keyword',
						'classes'=>'cat_url form-control',
						'placeholder'=>'Meta Keyword',
						'value'=>old('meta_keyword'),
						'disabled'=>''
			),
			"vendor_bank_ac_holder_name_field"=>array(
				'label'=>'Account Holder Name.',
				'type'=>'text',
				'name'=>'ac_holder_name',
				'id'=>'ac_holder_name',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>old('ac_holder_name'),
				'disabled'=>''
				),
			"vendor_bank_ac_no_field"=>array(
				'label'=>'Account No.',
				'type'=>'text',
				'name'=>'ac_no',
				'id'=>'ac_no',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>old('ac_no'),
				'disabled'=>''
				),
				"vendor_bank_name_field"=>array(
				'label'=>'Bank Name',
				'type'=>'text',
				'name'=>'bank_name',
				'id'=>'bank_name',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>old('bank_name'),
				'disabled'=>''
				),
				"vendor_bank_branch_name_field"=>array(
				'label'=>'Branch Name',
				'type'=>'text',
				'name'=>'branch_name',
				'id'=>'branch_name',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>old('branch_name'),
				'disabled'=>''
				),
				"vendor_bank_city_field"=>array(
				'label'=>'City',
				'type'=>'text',
				'name'=>'bank_city',
				'id'=>'bank_city',
				'classes'=>'form-control',
				'placeholder'=>'',
		      	'value'=>old('bank_city'),
				'disabled'=>''
				),
				"vendor_bank_ifsc_field"=>array(
				'label'=>'IFSC Code',
				'type'=>'text',
				'name'=>'ifsc_code',
				'id'=>'ifsc_code',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>old('ifsc_code'),
				'disabled'=>''
				),"vendor_gst_field"=>array(
				'label'=>'GST NO',
				'type'=>'text',
				'name'=>'gst_no',
				'id'=>'gst_no',
				'classes'=>'form-control',
				'placeholder'=>'',
					'value'=>old('gst_no'),
				'disabled'=>''
				),
				"vendor_pan_field"=>array(
				'label'=>'PAN NO',
				'type'=>'text',
				'name'=>'pan_no',
				'id'=>'pan_no',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>old('pan_no'),
				'disabled'=>''
				),
				"vendor_gst_file_field"=>array(
						'label'=>'GST Doc',
						'type'=>'file_special',
						'name'=>'gst_file',
					     'id'=>'file-2',
						'classes'=>'inputfile inputfile-4',
						'placeholder'=>'',
						'value'=>'',
						'disabled'=>''
				),
				"vendor_pan_file_field"=>array(
						'label'=>'Pan Doc',
						'type'=>'file_special',
						'name'=>'pan_file',
						'id'=>'file-3',
						'classes'=>'inputfile inputfile-4',
						'placeholder'=>'',
						'value'=>'',
						'disabled'=>''
				),"vendor_cheque_file_field"=>array(
						'label'=>'Cancel cheque',
						'type'=>'file_special',
						'name'=>'cheque',
						'id'=>'file-5',
					     'classes'=>'inputfile inputfile-5',
						'placeholder'=>'',
						'value'=>'',
						'disabled'=>''
				),
				"vendor_signature_file_field"=>array(
						'label'=>'Signature',
						'type'=>'file_special',
						'name'=>'signature',
						'id'=>'file-7',
					     'classes'=>'inputfile inputfile-7',
						'placeholder'=>'',
						'value'=>'',
						'disabled'=>''
				),
				"vendor_invoice_address_field"=>array(
				'label'=>'Address On Invoice',
				'type'=>'radio',
				'name'=>'invoice_address',
				'id'=>'invoice_address',
				'classes'=>'',
				'placeholder'=>'',
				'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Same as Company Address",
							),(object)array(
							"id"=>"2",
							"name"=>"Same as Store Address",
							)
							),
				'disabled'=>'',
				'selected'=>1
				),
				"vendor_invoice_logo_field"=>array(
				'label'=>'Logo on invoice',
				'type'=>'radio',
				'name'=>'invoice_logo',
				'id'=>'invoice_logo',
				'classes'=>'',
				'placeholder'=>'',
				'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Company Logo",
							),(object)array(
							"id"=>"2",
							"name"=>"Store Logo",
							)
							),
				'disabled'=>'',
				'selected'=>1
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
	     "return_data"=>array(
							'category'=>array(),
							'company_logo'=>'',
							'profile_pic'=>''
		)
     );
	
	  if ($request->isMethod('post')) {
		     $input=$request->all();

			 
		  		 switch($level){
			 case 0;  // general info tab
			 $request->validate([
				'username' => 'required|unique:vendors,username,1,isdeleted|max:25',
				//'public_name' => 'required|unique:vendors,public_name,1,isdeleted|max:25',
				'email' => 'required|unique:vendors,email,1,isdeleted|max:255',
				'phone' => 'required|unique:vendors,phone,1,isdeleted|max:10',
				'f_name' => 'max:25',
				'l_name' => 'max:25',
				'password' => 'min:5|max:15',
				'profile_pic' => 'mimes:jpeg,bmp,png',
             ]
        );
		$proifle_pic='';
	
	 if ($request->hasFile('profile_pic')) {
						$profile_pic = $request->file('profile_pic');
						$destinationPath =Config::get('constants.uploads.vendor_profile_pic');
						
						$file_name=$profile_pic->getClientOriginalName();
						
							$file_name= FIleUploadingHelper::UploadImage($destinationPath,$profile_pic,$file_name);
							if($file_name==''){
							MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
							return Redirect::back();
							}
							$proifle_pic=$file_name;
				  } 
				  $signature_pic='';
				  if ($request->hasFile('signature_pic')) {
					$signature_pic = $request->file('signature_pic');
					$destinationPath =Config::get('constants.uploads.vendor_signature_pic');
					$file_name=$signature_pic->getClientOriginalName();
					
						$file_name= FIleUploadingHelper::UploadImage($destinationPath,$signature_pic,$file_name);
						if($file_name==''){
						MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
						return Redirect::back();
						}
						$signature_pic=$file_name;
			  } 
				  
		 
       
			$Vendor = new Vendor;
			$Vendor->f_name = $input['f_name'];
			$Vendor->l_name =$input['l_name'];
			$Vendor->username =$input['username'];
			$Vendor->public_name =$input['public_name'];
			$Vendor->email = $input['email'];
			$Vendor->phone = $input['phone'];
			$Vendor->gender = $input['gender'];
			$Vendor->profile_pic = $proifle_pic;
			$Vendor->signature_pic = $signature_pic;			
			$Vendor->password =Hash::make($input['password']);
			  $Vendor->registration_id =  "KFH".$rid;
			$Vendor->user_role =2;
     
      /* save the following details */
      if($Vendor->save()){
          
                    // DB::table('vendor_bank_info')->insert(
                    // ['vendor_id' => $Vendor->id]
                    // );

 DB::table('vendor_categories')->insert(
                    ['vendor_id' => $Vendor->id]
                    );
                    
                    DB::table('vendor_company_info')->insert(
                    ['vendor_id' => $Vendor->id]
                    );
                    
                     DB::table('vendor_seo_info')->insert(
                    ['vendor_id' => $Vendor->id]
                    );
                    
                      DB::table('vendor_support_info')->insert(
                    ['vendor_id' => $Vendor->id]
                    );
                    
                      DB::table('vendor_tax_info')->insert(
                    ['vendor_id' => $Vendor->id]
                    );


		  $request->session()->put('vendor_id', $Vendor->id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		return redirect()->route('add_vendor', ['level' => base64_encode(1)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
     
	  
			 break;
			  case 1;  // categories tab
				$Vendor = new Vendor;
      if($Vendor->updateCategories($input,$request->session()->get('vendor_id'))){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return redirect()->route('add_vendor', ['level' => base64_encode(2)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
			   break;
			   
			 
			   case 2: // company info tab
				 
					$request->validate([
			'name' => 'max:25',
			'company_address' => 'max:600',
			'company_state' => 'max:100',
			'company_city' => 'max:100',
			'company_pincode' => 'max:6|min:6',
			'about_us' => 'max:200',
			'company_logo' => 'mimes:jpeg,bmp,png',
			'pincode' => 'max:6',
			]
			);
			
	 if ($request->hasFile('company_logo')) {
						$company_logo = $request->file('company_logo');
						$destinationPath =Config::get('constants.uploads.company_logo');
						$file_name=$company_logo->getClientOriginalName();
						
							$file_name= FIleUploadingHelper::UploadImage($destinationPath,$company_logo,$file_name);
							if($file_name==''){
							MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
							return Redirect::back();
							}
							$input['cm_logo']=$file_name;
				  } 
				  if ($request->hasFile('pancard')) {
						$pancard = $request->file('pancard');
						$destinationPath =Config::get('constants.uploads.company_logo');
						$file_name=$pancard->getClientOriginalName();
						$file_name= FIleUploadingHelper::UploadImage($destinationPath,$pancard,$file_name);
							if($file_name==''){
							MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
							 return Redirect::route('update_vdr_profile', [base64_encode(2),base64_encode($id)]);
							}
							$input['pancard']=$file_name;
				  } 
			 if ($request->hasFile('other_documents')) {
						$other_documents = $request->file('other_documents');
						$destinationPath =Config::get('constants.uploads.company_logo');
						$file_name=$other_documents->getClientOriginalName();
						$file_name= FIleUploadingHelper::UploadImage($destinationPath,$other_documents,$file_name);
							if($file_name==''){
							MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
							 return Redirect::route('update_vdr_profile', [base64_encode(2),base64_encode($id)]);
							}
							$input['other_documents']=$file_name;
				  } 
			 if ($request->hasFile('adharcard')) {
						$adharcard = $request->file('adharcard');
						$destinationPath =Config::get('constants.uploads.company_logo');
						$file_name=$adharcard->getClientOriginalName();
						$file_name= FIleUploadingHelper::UploadImage($destinationPath,$adharcard,$file_name);
							if($file_name==''){
							MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
							 return Redirect::route('update_vdr_profile', [base64_encode(2),base64_encode($id)]);
							}
							$input['adharcard']=$file_name;
				  } 
			 if ($request->hasFile('address_proof')) {
						$address_proof = $request->file('address_proof');
						$destinationPath =Config::get('constants.uploads.company_logo');
						$file_name=$address_proof->getClientOriginalName();
						$file_name= FIleUploadingHelper::UploadImage($destinationPath,$address_proof,$file_name);
							if($file_name==''){
							MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
							 return Redirect::route('update_vdr_profile', [base64_encode(2),base64_encode($id)]);
							}
							$input['address_proof']=$file_name;
				  } 
			 if ($request->hasFile('certificate')) {
						$certificate = $request->file('certificate');
						$destinationPath =Config::get('constants.uploads.company_logo');
						$file_name=$certificate->getClientOriginalName();
						$file_name= FIleUploadingHelper::UploadImage($destinationPath,$certificate,$file_name);
							if($file_name==''){
							MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
							 return Redirect::route('update_vdr_profile', [base64_encode(2),base64_encode($id)]);
							}
							$input['certificate']=$file_name;
				  } 
		 
		 
				
				$Vendor = new Vendor;
      if($Vendor->updateCompanyInfo($input,$request->session()->get('vendor_id'))){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		    return redirect()->route('add_vendor', ['level' => base64_encode(3)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
				 break;
			   
			    case 3;  // support info tab
				$request->validate([
			'phone' => 'max:10',
			'email' => 'max:50',
			'fb_id' => 'max:200',
			'tw_id' => 'max:200',
			]
			);
				$Vendor = new Vendor;
      if($Vendor->updateSupportInfo($input,$request->session()->get('vendor_id'))){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		    return redirect()->route('add_vendor', ['level' => base64_encode(4)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
				 break;
				 
				   case 4;   // seo info info tab
				   
				 
			$request->validate([
			'meta_title' => 'max:50',
			'meta_description' => 'max:255',
			'meta_keyword' => 'max:255',
			]
			);
					$Vendor = new Vendor;
					if($Vendor->updateMetaInfo($input,$request->session()->get('vendor_id'))){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		 // return redirect()->route('vendors');
		 return redirect()->route('add_vendor', ['level' => base64_encode(5)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
      if($Vendor->updateMetaInfo($input,$request->session()->get('vendor_id'))){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		   return redirect()->route('add_vendor', ['level' => base64_encode(5)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
			   break;
			   
			   
			   case 5;   // seo info info tab
				   
				 
			$request->validate([
				'ac_no' => 'max:25',
				'bank_name' => 'max:50',
				'branch_name' => 'max:25',
				'bank_city' => 'max:25',
				'ifsc_code' => 'max:25',
				 'cheque' => 'mimes:jpeg,png',
			]
			);
					$Vendor = new Vendor;
						if ($request->hasFile('cheque')) {
			$cheque_file = $request->file('cheque');
			$destinationPath =Config::get('constants.uploads.cheque_file');
			$file_name=$cheque_file->getClientOriginalName();

				$file_name= FIleUploadingHelper::UploadImage($destinationPath,$cheque_file,$file_name);
				if($file_name==''){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
				
				}
				$Vendor->updateDOcs($file_name,'cancel_cheque',$request->session()->get('vendor_id'));
			}
      if($Vendor->updateBankInfo($input,$request->session()->get('vendor_id'))){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  // return redirect()->route('add_vendor', ['level' => base64_encode(6)]);
		  return redirect()->route('vendors');
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
			   break;
			   
			    /*case 6;   // tax  info info tab
				   
				
// 		$request->validate([
//                 'gst_no' => 'min:15|max:15',
//                 'pan_no' => 'min:10|max:10|alpha_num',
//                 'gst_file' => 'mimes:jpeg,png',
//                 'pan_file' => 'mimes:jpeg,png',
               
//                 'signature' => 'mimes:jpeg,png',
// 			]
// 			);
			
			
					$Vendor = new Vendor;
			if ($request->hasFile('gst_file')) {
			$gst_file = $request->file('gst_file');
			$destinationPath =Config::get('constants.uploads.gst_file');
			$file_name=$gst_file->getClientOriginalName();

				$file_name= FIleUploadingHelper::UploadImage($destinationPath,$gst_file,$file_name);
				if($file_name==''){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
			        return redirect()->route('add_vendor', ['level' => base64_encode(6)]);
				}
				$Vendor->updateDOcs($file_name,'gst_file',$request->session()->get('vendor_id'));
			} 
			
			if ($request->hasFile('pan_file')) {
			$pan_file = $request->file('pan_file');
			$destinationPath =Config::get('constants.uploads.pan_file');
			$file_name=$pan_file->getClientOriginalName();

				$file_name= FIleUploadingHelper::UploadImage($destinationPath,$pan_file,$file_name);
				if($file_name==''){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
				  return redirect()->route('add_vendor', ['level' => base64_encode(6)]);
				}
				$Vendor->updateDOcs($file_name,'pan_file',$request->session()->get('vendor_id'));
			}
			
			if ($request->hasFile('cheque')) {
			$cheque_file = $request->file('cheque');
			$destinationPath =Config::get('constants.uploads.cheque_file');
			$file_name=$cheque_file->getClientOriginalName();

				$file_name= FIleUploadingHelper::UploadImage($destinationPath,$cheque_file,$file_name);
				if($file_name==''){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
				  return redirect()->route('add_vendor', ['level' => base64_encode(6)]);
				}
				$Vendor->updateDOcs($file_name,'cancel_cheque',$request->session()->get('vendor_id'));
			}
			if ($request->hasFile('signature')) {
			$signature_file = $request->file('signature');
			$destinationPath =Config::get('constants.uploads.signature_file');
			$file_name=$signature_file->getClientOriginalName();

				$file_name= FIleUploadingHelper::UploadImage($destinationPath,$signature_file,$file_name);
				if($file_name==''){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
				 return redirect()->route('add_vendor', ['level' => base64_encode(6)]);
				}
				$Vendor->updateDOcs($file_name,'signature',$request->session()->get('vendor_id'));
			}
			
      if($Vendor->updateTaxInfo($input,$request->session()->get('vendor_id'))){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		    return redirect()->route('add_vendor', ['level' => base64_encode(7)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
		return Redirect::back();
      }
			   break;
			   
			   case 7;   // invoice details  info info tab
				 
			
			
					$Vendor = new Vendor;
      if($Vendor->updateInvoiceInfo($input,$request->session()->get('vendor_id'))){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return redirect()->route('vendors');
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }*/
			   //break;
			
				 
				 }
		
	  }
        
		
		 return view('admin.vendors.form_add',['page_details'=>$page_details]);
	
    }
	
	public function edit_vendor(Request $request)
    {
		$id=base64_decode($request->id);
		$request->session()->put('vendor_id', $id);
		$level= base64_decode($request->level);
	
		$Vendor =  Vendor::where('id',$id)->first();

		$return_data=$Vendor->getVendorDetails1($request->session()->get('vendor_id'));
		
		$return_support_info_data=$Vendor->getSuppotVendorDetails($request->session()->get('vendor_id'));
		$return_seo_info_data=$Vendor->getSEOVendorDetails($request->session()->get('vendor_id'));
		$return_tax_info_data=$Vendor->getTaxVendorDetails($request->session()->get('vendor_id'));
		
		$cities = DB::table('cities')
		              ->select('cities.name as name', 'cities.id as id', 'states.name as state_name')
		              ->join('states', 'states.id', '=', 'cities.state_id')
		              ->where('states.name',$return_data->state)->get();

			$select_cats='';
		$dr=DB::table('vendor_categories')->where('vendor_id',$id)->first();
		if($dr){
		   $select_cats= $dr->selected_cats;
		}
			$bank_details = DB::table('vendor_bank_info')->where('vendor_id',$id)->get();
			
		
 $page_details=array(
       "Title"=>"Edit Vendor",
		"Method"=>"1",
		"Box_Title"=>"Edit Vendor",
        "Action_route"=>route('edit_vendor', [base64_encode($level),base64_encode($id)]),
       "Form_data"=>array(

         "Form_field"=>array(
              "vendor_reg_id_field"=>array(
							'label'=>'Registration ID',
							'type'=>'text',
							'name'=>'registration_id',
							'id'=>'registration_id',
							'classes'=>'form-control',
							'placeholder'=>'Registration ID',
							//'value'=>@$return_data['registration_id'],
							'value'=>@$Vendor['registration_id'],
							'disabled'=>'disabled'
									),
             "vendor_f_name_field"=>array(
							'label'=>'First Name',
							'type'=>'text',
							'name'=>'f_name',
							'id'=>'f_name',
							'classes'=>'form-control',
							'placeholder'=>'First Name',
							//'value'=>@$return_data['f_name'],
							'value'=>@$Vendor['f_name'],
							'disabled'=>''
									),
				"vendor_l_name_field"=>array(
						'label'=>'Last Name',
						'type'=>'text',
						'name'=>'l_name',
						'id'=>'l_name',
						'classes'=>'form-control',
						'placeholder'=>'Last Name',
						//'value'=>@$return_data['l_name'],
						'value'=>@$Vendor['l_name'],
						'disabled'=>''
				),
				"vendor_user_name_field"=>array(
						'label'=>'User Name *',
						'type'=>'text',
						'name'=>'username',
						'id'=>'username',
						'classes'=>'form-control',
						'placeholder'=>'User Name',
						//'value'=>@$return_data['username'],
						'value'=>@$Vendor['username'],
						'disabled'=>''
				),
				"vendor_public_name_field"=>array(
						'label'=>'Public Name ',
						'type'=>'text',
						'name'=>'public_name',
						'id'=>'public_name',
						'classes'=>'form-control',
						'placeholder'=>'Public Name',
						//'value'=>@$return_data['public_name'],
						'value'=>@$Vendor['public_name'],
						'disabled'=>''
				),
				"vendor_email_field"=>array(
						'label'=>'Email *',
						'type'=>'text',
						'name'=>'email',
						'id'=>'email',
						'classes'=>'form-control',
						'placeholder'=>'Email',
						//'value'=>@$return_data['email'],
						'value'=>@$Vendor['email'],
						'disabled'=>'disabled'
				),
				"vendor_phone_field"=>array(
						'label'=>'Phone *',
						'type'=>'text',
						'name'=>'phone',
						'id'=>'phone',
						'classes'=>'form-control',
						'placeholder'=>'Phone',
						//'value'=>@$return_data['phone'],
						'value'=>@$Vendor['phone'],
						'disabled'=>'disabled'
				),
				"vendor_password_field"=>array(
						'label'=>'Password *',
						'type'=>'password',
						'name'=>'password',
						'id'=>'password',
						'classes'=>'form-control',
						'placeholder'=>'******',
						'value'=>'',
						'disabled'=>''
				),
			"vednor_gender_field"=>array(
							'label'=>'Gender',
							'type'=>'select',
							'name'=>'gender',
							'id'=>'gender',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Male",
							),(object)array(
							"id"=>"2",
							"name"=>"Female",
							)
							),
							'disabled'=>'',
							'selected'=>@$Vendor['gender']
			),
			"vendor_profile_pic_field"=>array(
                        'label'=>'Profile Pic',
                        'type'=>'file_special',
                        'name'=>'profile_pic',
                        'id'=>'file-1',
                        'classes'=>'inputfile inputfile-4',
                        'placeholder'=>'',
						'value'=>'',
						'disabled'=>''
				),
             "vendor_signature_pic_field"=>array(
                        'label'=>'Signature test',
                        'type'=>'file_special',
                        'name'=>'signature_pic',
                        'id'=>'file-2',
                        'classes'=>'inputfile inputfile-4',
                        'placeholder'=>'',
						'value'=>'',
						'disabled'=>''
				),
				"vendor_company_name_field"=>array(
						'label'=>'Company Name',
						'type'=>'text',
						'name'=>'company_name',
						'id'=>'company_name',
						'maxlength'=>'255',
						'classes'=>'form-control',
						'placeholder'=>'Company Name',
						'value'=>@$return_data->name,
						'disabled'=>''
				),
				"vendor_company_address_field"=>array(
						'label'=>'Company Address',
						'type'=>'text',
						'name'=>'company_address',
						'id'=>'company_address',
						'maxlength'=>'255',
						'classes'=>'form-control',
						'placeholder'=>'Company Address',
						'value'=>@$return_data->address,
						'disabled'=>''
				),
				"vendor_company_about_field"=>array(
					'label'=>'About Us',
					'type'=>'textarea',
					'name'=>'company_about',
					'id'=>'company_about',
					'classes'=>'ckeditor form-control',
					'placeholder'=>'About',
					'value'=>@$return_data->about_us,
					'disabled'=>''
					),
				"vendor_company_state_field"=>array(
						'label'=>'Company State',
						'type'=>'text',
						'name'=>'company_state',
						'id'=>'company_state',
						'classes'=>'form-control',
						'placeholder'=>'Company State',
						'value'=>@$return_data->state,
						'disabled'=>''
				),
				/*"vendor_company_state_field"=>array(
                                'label'=>'Company State',
                                'type'=>'selectcustom',
                                'name'=>'company_state',
                                'id'=>'selectState',
                                'classes'=>'form-control',
                                'placeholder'=>'Company State',
                                'value'=>CommonHelper::getState('101'),
                    			'disabled'=>'',
                    			'selected'=>$return_data->state
                               ),*/
				"vendor_company_type_field"=>array(
                                'label'=>'Company Type',
                                'type'=>'selectcustom',
                                'name'=>'company_type',
                                'id'=>'selectype',
                                'classes'=>'form-control',
                                'placeholder'=>'Company Type',
                                'value'=>CommonHelper::getType(),
                    			'disabled'=>'',
                    			'selected'=>@$return_data->company_type
                               ),
			"vendor_pancard_field"=>array(
								'label'=>'PAN Card',
								'type'=>'file_special_imagepreview',
								'name'=>'pancard',
								'id'=>'file-22',
								'classes'=>'inputfile inputfile-4',
								'placeholder'=>'',
								'value'=>'',
								'disabled'=>'',
								'onchange'=>'image_preview(event)'
						),
			"vendor_certificate_field"=>array(
								'label'=>'Trademark certificate',
								'type'=>'file_special_imagepreview',
								'name'=>'certificate',
								'id'=>'file-6',
								'classes'=>'inputfile inputfile-4',
								'placeholder'=>'',
								'value'=>'',
								'disabled'=>'',
								'onchange'=>'image_preview(event)'
						),
			"vendor_other_field"=>array(
								'label'=>'Other Documents',
								'type'=>'file_special_imagepreview',
								'name'=>'other_documents',
								'id'=>'file-7',
								'classes'=>'inputfile inputfile-4',
								'placeholder'=>'',
								'value'=>'',
								'disabled'=>'',
								'onchange'=>'image_preview(event)'
						),
			"vendor_adharcard_field"=>array(
								'label'=>'Aadhar Card',
								'type'=>'file_special_imagepreview',
								'name'=>'adharcard',
								'id'=>'file-33',
								'classes'=>'inputfile inputfile-4',
								'placeholder'=>'',
								'value'=>'',
								'disabled'=>'',
								'onchange'=>'image_preview(event)'
						),
			"vendor_address_proof_field"=>array(
								'label'=>'Address Proof',
								'type'=>'file_special_imagepreview',
								'name'=>'address_proof',
								'id'=>'file-44',
								'classes'=>'inputfile inputfile-4',
								'placeholder'=>'',
								'value'=>'',
								'disabled'=>'',
								'onchange'=>'image_preview(event)'
						),
				"vendor_pannumber_field"=>array(
                                'label'=>'PAN Number',
                                'type'=>'text',
                                'name'=>'pannumber',
                                'id'=>'pannumber',
                                'classes'=>'form-control',
                                'placeholder'=>'PAN Number',
                                'value'=>@$return_data->pannumber,
                    			'disabled'=>'',
                    			
                               ),
				"vendor_company_city_field"=>array(
						'label'=>'Company City',
						'type'=>'text',
						'name'=>'company_city',
						'id'=>'company_city',
						'classes'=>'form-control',
						'placeholder'=>'Company City',
						'value'=>@$return_data->city,
						'disabled'=>''
				),
				/*"vendor_company_city_field"=>array(
					'label'=>'Company City',
				   'type'=>'selectcustom',
				   'name'=>'company_city',
				   'id'=>'selectcity',
				   'classes'=>'form-control',
				   'placeholder'=>'Company City',
				   'value'=>$cities,
				   'disabled'=>'',
				   'selected'=>$return_data->city
			   ),*/
				"vendor_company_pincode_field"=>array(
						'label'=>'Pincode',
						'type'=>'text',
						'name'=>'company_pincode',
						'id'=>'company_pincode',
						'classes'=>'form-control',
						'placeholder'=>'Pincode',
						'value'=>@$return_data->pincode,
						'disabled'=>''
				),
				
				"vendor_company_logo_field"=>array(
						'label'=>'Company Logo',
						'type'=>'file_special',
						'name'=>'company_logo',
					    'id'=>'file-2companylogo',
					 'classes'=>'inputfile inputfile-4',
						'placeholder'=>'',
						'value'=>'',
						'disabled'=>''
				),
				 "vendor_tax_type_field"=>array(
				'label'=>'Tax Type',
				'type'=>'radio',
				'name'=>'tax_type',
				'id'=>'tax_type',
				'classes'=>'',
				'placeholder'=>'',
				'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Flat Percent",
							),(object)array(
							"id"=>"2",
							"name"=>"Fixed Rate",
							)
							),
				'disabled'=>'',
				'selected'=>@$return_data->tax_type
				),
				"vendor_tax_rate_field"=>array(
						'label'=>'Tax',
						'type'=>'text',
						'name'=>'tx_rate',
						'id'=>'tx_rate',
						'classes'=>'form-control hideextraField',
						'placeholder'=>'',
						'value'=>@$return_data->tax_rate,
						'disabled'=>''
				),
				"vendor_support_number_field"=>array(
						'label'=>'Phone',
						'type'=>'text',
						'name'=>'phone',
						'id'=>'phone',
						'classes'=>'form-control',
						'placeholder'=>'Phone',
						'value'=>@$return_support_info_data->phone,
						'disabled'=>''
				),
				"vendor_support_email_field"=>array(
						'label'=>'Email',
						'type'=>'text',
						'name'=>'email',
						'id'=>'email',
						'classes'=>'form-control',
						'placeholder'=>'Email',
						'value'=>@$return_support_info_data->email,
						'disabled'=>''
				),
				"vendor_support_fb_field"=>array(
						'label'=>'Facebook Id',
						'type'=>'text',
						'name'=>'fb_id',
						'id'=>'fb_id',
						'classes'=>'form-control',
						'placeholder'=>'',
						'value'=>@$return_support_info_data->fb_id,
						'disabled'=>''
				),
				"vendor_support_tw_field"=>array(
						'label'=>'Twitter Id',
						'type'=>'text',
						'name'=>'tw_id',
						'id'=>'tw_id',
						'classes'=>'form-control',
						'placeholder'=>'',
						'value'=>@$return_support_info_data->tw_id,
						'disabled'=>''
				),
			 "vendoe_seo_meta_title_field"=>array(
							'label'=>'Meta Title',
							'type'=>'text',
							'name'=>'meta_title',
							'id'=>'meta_title',
							'classes'=>'form-control',
							'placeholder'=>'Meta Title',
							'value'=>@$return_seo_info_data->meta_title,
							'disabled'=>''
									),
			"vendoe_seo_meta_description_field"=>array(
						'label'=>'Meta Description',
						'type'=>'textarea',
						'name'=>'meta_description',
						'id'=>'meta_description',
						'classes'=>'form-control',
						'placeholder'=>'Meta description',
						'value'=>@$return_seo_info_data->meta_description,
						'disabled'=>''
			),
			"vendoe_seo_meta_keyword_field"=>array(
						'label'=>'Meta Keyword',
						'type'=>'textarea',
						'name'=>'meta_keyword',
						'id'=>'meta_keyword',
						'classes'=>'cat_url form-control',
						'placeholder'=>'Meta Keyword',
						'value'=>@$return_seo_info_data->meta_keyword,
						'disabled'=>''
			),
				"vendor_bank_ac_holder_name_field"=>array(
				'label'=>'Account Holder Name.',
				'type'=>'text',
				'name'=>'ac_holder_name',
				'id'=>'ac_holder_name',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>'',
				'disabled'=>''
				),
				"vendor_bank_ac_no_field"=>array(
				'label'=>'Account No.',
				'type'=>'text',
				'name'=>'ac_no',
				'id'=>'ac_no',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>'',
				'disabled'=>''
				),
				"vendor_bank_name_field"=>array(
				'label'=>'Bank Name',
				'type'=>'text',
				'name'=>'bank_name',
				'id'=>'bank_name',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>'',
				'disabled'=>''
				),
				"vendor_bank_branch_name_field"=>array(
				'label'=>'Branch Name',
				'type'=>'text',
				'name'=>'branch_name',
				'id'=>'branch_name',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>'',
				'disabled'=>''
				),
				"vendor_bank_city_field"=>array(
				'label'=>'City',
				'type'=>'text',
				'name'=>'bank_city',
				'id'=>'bank_city',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>'',
				'disabled'=>''
				),
				"vendor_bank_ifsc_field"=>array(
				'label'=>'IFSC Code',
				'type'=>'text',
				'name'=>'ifsc_code',
				'id'=>'ifsc_code',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>'',
				'disabled'=>''
				),
				"vendor_gst_field"=>array(
				'label'=>'GST NO',
				'type'=>'text',
				'name'=>'gst_no',
				'id'=>'gst_no',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>@$return_tax_info_data->gst_no,
				'disabled'=>''
				),
				"vendor_pan_field"=>array(
				'label'=>'PAN NO',
				'type'=>'text',
				'name'=>'pan_no',
				'id'=>'pan_no',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>@$return_tax_info_data->pan_no,
				'disabled'=>''
				),
				"vendor_gst_file_field"=>array(
						'label'=>'GST Doc',
						'type'=>'file_special',
						'name'=>'gst_file',
						'id'=>'file-3',
					 'classes'=>'inputfile inputfile-4',
						'placeholder'=>'',
						'value'=>@$return_tax_info_data->gst_file,
						'disabled'=>''
				),
				"vendor_pan_file_field"=>array(
						'label'=>'Pan Doc',
						'type'=>'file_special',
						'name'=>'pan_file',
						'id'=>'file-4',
					     'classes'=>'inputfile inputfile-4',
						'placeholder'=>'',
						'value'=>@$return_tax_info_data->pan_file,
						'disabled'=>''
				),
				"vendor_cheque_file_field"=>array(
						'label'=>'Cancel cheque',
						'type'=>'file_special',
						'name'=>'cheque',
						'id'=>'file-5',
					     'classes'=>'inputfile inputfile-5',
						'placeholder'=>'',
						'value'=>@$return_tax_info_data->cancel_cheque_file,
						'disabled'=>''
				),
				"vendor_signature_file_field"=>array(
						'label'=>'Signature',
						'type'=>'file_special',
						'name'=>'signature',
						'id'=>'file-7',
					     'classes'=>'inputfile inputfile-7',
						'placeholder'=>'',
						'value'=>@$return_tax_info_data->signature_file,
						'disabled'=>''
				),
				"vendor_invoice_address_field"=>array(
				'label'=>'Address On Invoice',
				'type'=>'radio',
				'name'=>'invoice_address',
				'id'=>'invoice_address',
				'classes'=>'',
				'placeholder'=>'',
				'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Same as Company Address",
							),(object)array(
							"id"=>"2",
							"name"=>"Same as Store Address",
							)
							),
				'disabled'=>'',
				'selected'=>@$return_tax_info_data->invoice_address
				),
				"vendor_invoice_logo_field"=>array(
				'label'=>'Logo on invoice',
				'type'=>'radio',
				'name'=>'invoice_logo',
				'id'=>'invoice_logo',
				'classes'=>'',
				'placeholder'=>'',
				'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Company Logo",
							),(object)array(
							"id"=>"2",
							"name"=>"Store Logo",
							)
							),
				'disabled'=>'',
				'selected'=>@$return_tax_info_data->invoice_logo
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
	    "return_data"=>array(
							'category'=>explode(",",$select_cats),
							'company_logo'=>@$return_data->logo,
							'profile_pic'=>@$Vendor['profile_pic'],
							'signature_pic'=>@$Vendor['signature_pic'],
							'pancard'=>@$return_data->pancard,
							'other_documents'=>@$return_data->other_documents,
							'certificate'=>@$return_data->certificate,
							'adharcard'=>@$return_data->adharcard,
							'address_proof'=>@$return_data->address_proof,
								'data'=>$bank_details
		)
     );
	
	  if ($request->isMethod('post')) {

		     $input=$request->all();

		  		 switch($level){
			 case 0;  // general info tab
			
			$request->validate([
				'username' => 'max:25|required|unique:vendors,username,'.$id.',id,isdeleted,1',
				//'public_name' => 'max:25|required|unique:vendors,public_name,'.$id.',id,isdeleted,1',
				'f_name' => 'max:25',
				'l_name' => 'max:25',
                'profile_pic' => 'mimes:jpeg,bmp,png',
             ]
        );
	   $Vendor = Vendor::where('id', $id)->first();

	 if ($request->hasFile('profile_pic')) {
						$profile_pic = $request->file('profile_pic');
						
						$destinationPath =Config::get('constants.uploads.vendor_profile_pic');
						$file_name=$profile_pic->getClientOriginalName();
						
							$file_name= FIleUploadingHelper::UploadImage($destinationPath,$profile_pic,$file_name);
							if($file_name==''){
							MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
							return Redirect::back();
							}
							$proifle_pic=$file_name;
							$Vendor->profile_pic = $proifle_pic;
				  }
				  if ($request->hasFile('signature_pic')) {
					$signature_pic = $request->file('signature_pic');
					$destinationPath =Config::get('constants.uploads.vendor_signature_pic');
					$file_name=$signature_pic->getClientOriginalName();
					
						$file_name= FIleUploadingHelper::UploadImage($destinationPath,$signature_pic,$file_name);
						if($file_name==''){
						MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
						return Redirect::back();
						}
						$signature_pic=$file_name;
						$Vendor->signature_pic = $signature_pic;
			  }
				  
				  
				   if ($input['password']!='') {
						$request->validate([
						'password' => 'min:5|max:15',
						]
						);
					   $Vendor->password =Hash::make($input['password']);
				  } 
		 
      
			
			
			$Vendor->registration_id = $input['registration_id'];
			$Vendor->f_name = $input['f_name'];
			$Vendor->l_name =$input['l_name'];
			$Vendor->username =$input['username'];
			$Vendor->phone =$input['phone'];
			$Vendor->public_name =$input['public_name'];
			$Vendor->gender = $input['gender'];
     
      /* save the following details */
      if($Vendor->save()){
		   Vendor::VendorStsInactive($id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		 return Redirect::route('edit_vendor', [base64_encode(0),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			 return Redirect::route('edit_vendor', [base64_encode(0),base64_encode($id)]);
      }
     
	  
			 break;
			  case 1;  // categories tab
				$Vendor = new Vendor;
				//dd($input);
      if($Vendor->updateCategories($input,$request->session()->get('vendor_id'))){

           Vendor::VendorStsInactive($id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return Redirect::route('edit_vendor', [base64_encode(1),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			 return Redirect::route('edit_vendor', [base64_encode(1),base64_encode($id)]);
      }
			   break;
			   
			 
			   case 2: // company info tab
				 
					$request->validate([
			'name' => 'max:25',
                'company_address' => 'max:600',
                'company_state' => 'max:100',
                'company_city' => 'max:100',
                'company_pincode' => 'max:6|min:6',
			'about_us' => 'max:200',
			'company_logo' => 'mimes:jpeg,bmp,png',
			'pincode' => 'max:6',
			]
			);
			
	 if ($request->hasFile('company_logo')) {
						$company_logo = $request->file('company_logo');
						$destinationPath =Config::get('constants.uploads.company_logo');
						$file_name=$company_logo->getClientOriginalName();
						
							$file_name= FIleUploadingHelper::UploadImage($destinationPath,$company_logo,$file_name);
							if($file_name==''){
							MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
							 return Redirect::route('edit_vendor', [base64_encode(2),base64_encode($id)]);
							}
							$input['cm_logo']=$file_name;
				  } 
				  
				   if ($request->hasFile('pancard')) {
						$pancard = $request->file('pancard');
						$destinationPath =Config::get('constants.uploads.company_logo');
						$file_name=$pancard->getClientOriginalName();
						$file_name= FIleUploadingHelper::UploadImage($destinationPath,$pancard,$file_name);
							if($file_name==''){
							MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
							 return Redirect::route('update_vdr_profile', [base64_encode(2),base64_encode($id)]);
							}
							$input['pancard']=$file_name;
				  } 
			 if ($request->hasFile('other_documents')) {
						$other_documents = $request->file('other_documents');
						$destinationPath =Config::get('constants.uploads.company_logo');
						$file_name=$other_documents->getClientOriginalName();
						$file_name= FIleUploadingHelper::UploadImage($destinationPath,$other_documents,$file_name);
							if($file_name==''){
							MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
							 return Redirect::route('update_vdr_profile', [base64_encode(2),base64_encode($id)]);
							}
							$input['other_documents']=$file_name;
				  } 
			 if ($request->hasFile('adharcard')) {
						$adharcard = $request->file('adharcard');
						$destinationPath =Config::get('constants.uploads.company_logo');
						$file_name=$adharcard->getClientOriginalName();
						$file_name= FIleUploadingHelper::UploadImage($destinationPath,$adharcard,$file_name);
							if($file_name==''){
							MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
							 return Redirect::route('update_vdr_profile', [base64_encode(2),base64_encode($id)]);
							}
							$input['adharcard']=$file_name;
				  } 
			 if ($request->hasFile('address_proof')) {
						$address_proof = $request->file('address_proof');
						$destinationPath =Config::get('constants.uploads.company_logo');
						$file_name=$address_proof->getClientOriginalName();
						$file_name= FIleUploadingHelper::UploadImage($destinationPath,$address_proof,$file_name);
							if($file_name==''){
							MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
							 return Redirect::route('update_vdr_profile', [base64_encode(2),base64_encode($id)]);
							}
							$input['address_proof']=$file_name;
				  } 
			 if ($request->hasFile('certificate')) {
						$certificate = $request->file('certificate');
						$destinationPath =Config::get('constants.uploads.company_logo');
						$file_name=$certificate->getClientOriginalName();
						$file_name= FIleUploadingHelper::UploadImage($destinationPath,$certificate,$file_name);
							if($file_name==''){
							MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
							 return Redirect::route('update_vdr_profile', [base64_encode(2),base64_encode($id)]);
							}
							$input['certificate']=$file_name;
				  } 
		 
				
				$Vendor = new Vendor;

      if($Vendor->updateCompanyInfo($input,$request->session()->get('vendor_id'))){
           Vendor::VendorStsInactive($id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		    return Redirect::route('edit_vendor', [base64_encode(2),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			 return Redirect::route('edit_vendor', [base64_encode(2),base64_encode($id)]);
      }
				 break;
			   
			    case 3;  // support info tab
				$request->validate([
			'phone' => 'max:10',
			'email' => 'max:50',
			'fb_id' => 'max:200',
			'tw_id' => 'max:200',
			]
			);
				$Vendor = new Vendor;
      if($Vendor->updateSupportInfo($input,$request->session()->get('vendor_id'))){
           Vendor::VendorStsInactive($id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		     return Redirect::route('edit_vendor', [base64_encode(3),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			 return Redirect::route('edit_vendor', [base64_encode(3),base64_encode($id)]);
      }
				 break;
				 
				   case 4;   // seo info info tab
				   
				 
			$request->validate([
			'meta_title' => 'max:50',
			'meta_description' => 'max:255',
			'meta_keyword' => 'max:255',
			]
			);
					$Vendor = new Vendor;
      if($Vendor->updateMetaInfo($input,$request->session()->get('vendor_id'))){
           Vendor::VendorStsInactive($id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return Redirect::route('edit_vendor', [base64_encode(4),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_vendor', [base64_encode(4),base64_encode($id)]);
      }
			   break;
			   
			   
			    case 5;   // bank  info info tab
				   
				
			$request->validate([
				'ac_no' => 'max:25',
				'bank_name' => 'max:50',
				'branch_name' => 'max:25',
				'bank_city' => 'max:25',
				'ifsc_code' => 'max:25',
				'cheque' => 'mimes:jpeg,png',
			]
			);
					$Vendor = new Vendor;
						if ($request->hasFile('cheque')) {
			$cheque_file = $request->file('cheque');
			$destinationPath =Config::get('constants.uploads.cheque_file');
			$file_name=$cheque_file->getClientOriginalName();

				$file_name= FIleUploadingHelper::UploadImage($destinationPath,$cheque_file,$file_name);
				if($file_name==''){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
				 return Redirect::route('edit_vendor', [base64_encode(6),base64_encode($id)]);
				}
				$Vendor->updateDOcs($file_name,'cancel_cheque',$request->session()->get('vendor_id'));
			}
			
      if($Vendor->updateBankInfo($input,$request->session()->get('vendor_id'))){
           Vendor::VendorStsInactive($id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return Redirect::route('edit_vendor', [base64_encode(5),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_vendor', [base64_encode(5),base64_encode($id)]);
      }
			   break;
			   
			   
			     case 6;   // tax  info info tab
				   
				
			$request->validate([
//                 'gst_no' => 'min:15|max:15',
//                 'pan_no' => 'min:10|max:10|alpha_num',
                'gst_file' => 'mimes:pdf'
//                 'pan_file' => 'mimes:jpeg,png',
                
//                 'signature' => 'mimes:jpeg,png',
			]
			);
			
			
					   $Vendor = new Vendor;
			if ($request->hasFile('gst_file')) {
			$gst_file = $request->file('gst_file');
			$destinationPath =Config::get('constants.uploads.gst_file');
			$file_name=$gst_file->getClientOriginalName();

				// $file_name= FIleUploadingHelper::UploadImage($destinationPath,$gst_file,$file_name);
				$file_name= FIleUploadingHelper::UploadPDF($destinationPath,$gst_file,$file_name);
				
				if($file_name==''){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
				 return Redirect::route('edit_vendor', [base64_encode(6),base64_encode($id)]);
				}
				$Vendor->updateDOcs($file_name,'gst_file',$request->session()->get('vendor_id'));
			} 
			
			if ($request->hasFile('pan_file')) {
			$pan_file = $request->file('pan_file');
			$destinationPath =Config::get('constants.uploads.pan_file');
			$file_name=$pan_file->getClientOriginalName();

				$file_name= FIleUploadingHelper::UploadImage($destinationPath,$pan_file,$file_name);
				if($file_name==''){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
				 return Redirect::route('edit_vendor', [base64_encode(6),base64_encode($id)]);
				}
				$Vendor->updateDOcs($file_name,'pan_file',$request->session()->get('vendor_id'));
			}
			
		
			if ($request->hasFile('signature')) {
			$signature_file = $request->file('signature');
			$destinationPath =Config::get('constants.uploads.signature_file');
			$file_name=$signature_file->getClientOriginalName();

				$file_name= FIleUploadingHelper::UploadImage($destinationPath,$signature_file,$file_name);
				if($file_name==''){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
				 return Redirect::route('edit_vendor', [base64_encode(6),base64_encode($id)]);
				}
				$Vendor->updateDOcs($file_name,'signature',$request->session()->get('vendor_id'));
			}
      if($Vendor->updateTaxInfo($input,$request->session()->get('vendor_id'))){
           Vendor::VendorStsInactive($id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return Redirect::route('edit_vendor', [base64_encode(6),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_vendor', [base64_encode(6),base64_encode($id)]);
      }
			   break;
			   
			   case 7;   // invoice details  info info tab
				 
			
			
					$Vendor = new Vendor;
      if($Vendor->updateInvoiceInfo($input,$request->session()->get('vendor_id'))){
           Vendor::VendorStsInactive($id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return Redirect::route('edit_vendor', [base64_encode(7),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_vendor', [base64_encode(7),base64_encode($id)]);
      }
			   break;
			   
			
				 
				 }
		
	  }
        
		
		 return view('admin.vendors.form_edit',['page_details'=>$page_details]);
	
    }
	public function vendor_orders(Request $request)
    {
            $id=base64_decode($request->id);
            $type=base64_decode($request->type);
			$str= $request->str;
			$daterange= $request->daterange;

            $page_details=array(
			   "Title"=>"Vendor Orders",
			   "Box_Title"=>"List",
			   "search_route"=>URL::to('admin/vendor_orders_search')."/".$request->id."/".$request->type,
				"export"=>"",
				"reset_route"=>route('vendors_order',[$request->id, $request->type])
			 );
			 
			 if($str=='all'){
				$str=''; 
			 }
			 if($daterange=='all'){
				$daterange=''; 
			 }
		
		$vendor=Vendor::where('id',$id)->first();
		$vendor_details = DB::table('vendor_tax_info')->where('vendor_id',$id)->first();		 
		$vendor_company_info = DB::table('vendor_company_info')->where('vendor_id',$id)->first();		 
		$vendor->pancard = (!empty($vendor_company_info->pancard))?url('/uploads/vendor/company_logo/')."/".$vendor_company_info->pancard:'';
		$vendor->adharcard = (!empty($vendor_company_info->adharcard))?url('/uploads/vendor/company_logo/')."/".$vendor_company_info->adharcard:'';
		$vendor->address_proof = (!empty($vendor_company_info->address_proof))?url('/uploads/vendor/company_logo/')."/".$vendor_company_info->address_proof:'';
		$vendor->certificate = (!empty($vendor_company_info->certificate))?url('/uploads/vendor/company_logo/')."/".$vendor_company_info->certificate:'';
		$vendor->other_documents = (!empty($vendor_company_info->other_documents))?url('/uploads/vendor/company_logo/')."/".$vendor_company_info->other_documents:'';
	
		if(!empty($vendor_details)){
			$vendor->gst_no = $vendor_details->gst_no;		
		}
		
		$Orders=OrdersDetail::select(
						'orders.order_no',
						'orders.order_date',
						'order_details.*'
						)
						->join('orders', 'orders.id', '=', 'order_details.order_id');
						
				if($id!=0){
				  $Orders=$Orders->join('products', 'products.id', '=', 'order_details.product_id')
				                    ->where('products.vendor_id',$id);
						
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
                
// 			if($type=='0')
// 			{	
// 				$Orders=$Orders->whereIn('order_details.order_status',array(0,1,2,3))->orderBy('order_details.id','desc')->paginate(100);
// 			}else{
// 				$Orders=$Orders->where('order_details.order_status',$type)->orderBy('order_details.id','desc')->paginate(100);
// 			}
			
			$Orders=$Orders->where('order_details.order_status',$type)->orderBy('order_details.id','desc')->paginate(100);
		
		
			
			$parameters_level=$request->type;
			
				$order_count=OrdersDetail::select(
						'orders.order_no',
						'orders.order_date',
						'order_details.*'
						)
						->join('orders', 'orders.id', '=', 'order_details.order_id');
						if($id!=0){
				  $order_count=$order_count->join('products', 'products.id', '=', 'order_details.product_id')
				                    ->where('products.vendor_id',$id)->get();
						
				}
				// 		$pending=$invoice=$shipping=$delivery=$cancel=$return=$failed=$order_count;
						
$pending=$order_count->where('order_status',0)->count();
$invoice=$order_count->where('order_status',1)->count();
$shipping=$order_count->where('order_status',2)->count();
$delivery=$order_count->where('order_status',3)->count();
$cancel=$order_count->where('order_status',4)->count();
$return=$order_count->where('order_status',5)->count();
$failed=$order_count->where('order_status',7)->count();
			$counts=array(
                "pending"=>$pending,
                "invoice"=>$invoice,
                "shipping"=>$shipping,
                "delivery"=>$delivery,
                "cancel"=>$cancel,
                "return"=>$return,
                "failed"=>$failed,
			    );
			    
			    
		
        return view('admin.vendors.orders',['counts'=>$counts,'vendor_details'=>$vendor_details,'vendor'=>$vendor,'page_details'=>$page_details,'orders'=>$Orders,'parameters_level'=>$parameters_level,'daterange'=>$request->daterange,'str'=>$str]);
    }
	
	public function vdr_sts(Request $request)
    {
            $id=base64_decode($request->id);
            $sts=base64_decode($request->sts);

            $res=Vendor::where('id',$id)
                    ->update(['status' => ($sts==0) ? 1 : 0]);

            if ($res) {
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
    }
	public function vdr_verify(Request $request)
    {
			$id=base64_decode($request->id);
			$sts=base64_decode($request->sts);
			$type=base64_decode($request->type);
			 
			 switch($type){
				case 'phone':
				 $res=Vendor::where('id',$id)
                    ->update(['is_phone_verify' => ($sts==0) ? 1 : 0]);

            if ($res) {
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
				break;
				
				case 'email':
				 $res=Vendor::where('id',$id)
                    ->update(['is_email_verify' => ($sts==0) ? 1 : 0]);

            if ($res) {
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
				break;

			 }    
    }
	
	 public function delete_vdr(Request $request)
    {
            $id=base64_decode($request->id);

            $res=Vendor::where('id',$id)
                    ->update(['isdeleted' => 1]);

            if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

                  return Redirect::back();
    }
    public function vendor_pincode(Request $request)
    {
		$parameters=$request->str;	
		$status=$request->status;	
		//dd($parameters);
		if($parameters=='all')
		{
			$parameters='';
		}
		if($status=='all')
		{
			$status='';
		}
		
		$page_details=array(
					   "Title"=>"Port Code",
					   "Box_Title"=>"Port Code(s)",
					   "search_route"=>URL::to('admin/vendor_pincode_search'),
					   "reset_route"=>route('vendor_pincode')
					 );
	 
		 $pincodes= DB::table('logistic_vendor_pincode')
				 ->select('vendors.public_name as name','logistic_vendor_pincode.pincode','logistic_vendor_pincode.price','logistic_vendor_pincode.id as delete_loggistics','logistic_vendor_pincode.id','logistic_vendor_pincode.status')
				 ->join('vendors', 'logistic_vendor_pincode.vendor_id', '=', 'vendors.id')
				 ->where('logistic_vendor_pincode.isdeleted', 0);
	 
		 if($parameters!='')  
		 {
		 	//dd($parameters);
			 $pincodes->where('logistic_vendor_pincode.pincode','LIKE', '%'.$parameters.'%')->orWhere('vendors.public_name','LIKE', '%'.$parameters.'%');
		 }
		 
		 if($status!='')
		 {
			 $pincodes->where('logistic_vendor_pincode.status','=', $status);
		 }
	 
		$pincodes=$pincodes->orderBy('logistic_vendor_pincode.id', 'DESC')->paginate(100);
		
        return view('admin.vendors.pincodes',['pincodes'=>$pincodes,'page_details'=>$page_details,'status'=>$status]);
		
    }
	
	public function edit_pin(Request $request)
    {
          
		
				$id=base64_decode($request->id);
				
				$logistic_vendor_pincode_details = DB::table('logistic_vendor_pincode')->where('id', $id)->first();
				
	
		
				$logistics= DB::table('logistic_partner')->select('id','name')->where('isdeleted', 0)->orderBy('id', 'DESC')->get();
		 $vendors= Vendor::select('id','public_name as name')->where('isdeleted', 0)->where('user_role','!=', 0)->orderBy('id', 'DESC')->get();
          $page_details=array(
       "Title"=>"Edit Port Code",
	     "Method"=>"2",
       "Box_Title"=>"Edit Port Code",
       "Action_route"=>route('edit_pin', base64_encode($id)),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'Port code',
            'type'=>'text',
            'name'=>'pincode',
            'id'=>'pincode',
            'classes'=>'form-control',
            'placeholder'=>'Name',
            'value'=>$logistic_vendor_pincode_details->pincode,
				'disabled'=>''
           ),
		   "price_field"=>array(
			'label'=>'Price',
		  'type'=>'number',
		  'name'=>'price',
		  'id'=>'price',
		  'classes'=>'form-control',
		  'placeholder'=>'Enter Price',
		  'value'=>$logistic_vendor_pincode_details->price,
		  'disabled'=>''
		 ),
		   "select_field"=>array(
              'label'=>'Select Vendor',
            'type'=>'select',
            'name'=>'vendor',
            'id'=>'vendor',
            'classes'=>'form-control',
            'placeholder'=>'Name',
            'value'=>$vendors,
			'disabled'=>'',
			'selected'=>$logistic_vendor_pincode_details->vendor_id
           ),
		    "Logicticsfield"=>array(
              'label'=>'Logictics',
            'type'=>'select',
            'name'=>'logistics',
            'id'=>'logistics',
            'classes'=>'form-control',
            'placeholder'=>'Name',
            'value'=>$logistics,
			'disabled'=>'',
			'selected'=>$logistic_vendor_pincode_details->logistic_partner_id,
           ),
		   "vendor_code_with_logistics_field"=>array(
				'label'=>'',
				'type'=>'radio',
				'name'=>'with_logistics',
				'id'=>'with_logistics',
				'classes'=>'show_hide',
				'placeholder'=>'',
				'value'=>array(
							(object)array(
							"id"=>"0",
							"name"=>"With out logistics",
							),(object)array(
							"id"=>"1",
							"name"=>"With logistics",
							)
							),
				'disabled'=>'',
				'selected'=>$logistic_vendor_pincode_details->withLogistics
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
		 		
	  if ($input['with_logistics']==1){
     $request->validate([
              'vendor' => 'required',
              'pincode' => 'required',
			  'price' => 'required',
               'logistics' => 'required'
	 ],['pincode.required'=>'Port Code is required']
			);
}
else {
    $request->validate([
              'vendor' => 'required',
              'pincode' => 'required',
			  'price' => 'required',
                
            ],['pincode.required'=>'Port Code is required']
			);
}
		
		 
/*
$res=DB::table('logistic_vendor_pincode')->select('id')->where('vendor_id',$input['vendor'])->where('pincode',$input['pincode'])->first();
			if($res){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			} else{
				*/
				
				$res=DB::table('logistic_vendor_pincode')->where('id',$id)
                    ->update([
					'pincode' => $input['pincode'] ,
					'price' => $input['price'] ,
					'logistic_partner_id'=>$input['logistics'],
					'withLogistics'=>$input['with_logistics']
					]);
				
				
				 MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
			/* } */
		 return redirect()->route('edit_pin', ['id' => base64_encode($id)]);     
		
	 }	
	 return view('admin.vendors.pincode_form',['page_details'=>$page_details]);
		
    
		
    }


	public function add_vendor_code(Request $request)
    {
	
		
		 $vendors= Vendor::where('public_name','!=',null)->select('id','public_name as name')->where('isdeleted', 0)->where('user_role','!=', 0)->orderBy('id', 'DESC')->get();
		 //dd($vendors);
		  $logistics= DB::table('logistic_partner')->select('id','name')->where('isdeleted', 0)->orderBy('id', 'DESC')->get();
          $page_details=array(
       "Title"=>"Add Port Code",
	     "Method"=>"1",
       "Box_Title"=>"Add Port Code",
       "Action_route"=>route('vendor_pincode_assign'),
       "back_url"=>route('vendor_pincode'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'Port code',
            'type'=>'text',
            'name'=>'pincode',
            'id'=>'pincode',
            'classes'=>'form-control',
            'placeholder'=>'Enter Port code',
            'value'=>'',
				'disabled'=>''
           ),
		   "price_field"=>array(
			'label'=>'Price',
		  'type'=>'number',
		  'name'=>'price',
		  'id'=>'price',
		  'classes'=>'form-control',
		  'placeholder'=>'Enter Price',
		  'value'=>'',
		  'disabled'=>''
		 ),
           	"csv_select_field"=>array(
						'label'=>'',
						'type'=>'file_special',
						'name'=>'csv',
                        'id'=>'file-1',
                        'classes'=>'inputfile inputfile-4',
						'placeholder'=>'',
						'value'=>'',
						'disabled'=>''
				),
		   "select_field"=>array(
              'label'=>'Vendor',
            'type'=>'select',
            'name'=>'vendor',
            'id'=>'vendor',
            'classes'=>'form-control',
            'placeholder'=>'Name',
            'value'=>$vendors,
			'disabled'=>'',
			'selected'=>''
           ),
		   "vendor_code_with_logistics_field"=>array(
				'label'=>'',
				'type'=>'radio',
				'name'=>'with_logistics',
				'id'=>'with_logistics',
				'classes'=>'show_hide',
				'placeholder'=>'',
				'value'=>array(
							(object)array(
							"id"=>"0",
							"name"=>"With out logistics",
							),(object)array(
							"id"=>"1",
							"name"=>"With logistics",
							)
							),
				'disabled'=>'',
				'selected'=>1
				),
		    "Logicticsfield"=>array(
              'label'=>'Logictics',
            'type'=>'select',
            'name'=>'logistics',
            'id'=>'logistics',
            'classes'=>'form-control',
            'placeholder'=>'Name',
            'value'=>$logistics,
			'disabled'=>'',
			'selected'=>''
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
       )
	   )
     );
	 
	
		  return view('admin.vendors.pincode_form',['page_details'=>$page_details]);
		
    }
	
		public function vendor_pincode_assign_csv(Request $request)
    {
		
		 $input=$request->all();
      
          $request->validate([
              'csv' => 'required'
                
            ]
			);
			
			$file = $request->file('csv');
			
			
			$file_name=$file->getClientOriginalName();
			$res = FIleUploadingHelper::UploadDoc(Config::get('constants.uploads.csv'),$file,$file_name);
			
			$filepath = Config::get('constants.Url.public_url').'/'.Config::get('constants.uploads.csv').'/'.$res;
			
			$file = fopen($filepath,"r");

          $importData_arr = array();
          $i = 0;

          while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
             $num = count($filedata );
             
             // Skip first row (Remove below comment if you want to skip the first row)
             /*if($i == 0){
                $i++;
                continue; 
             }*/
             for ($c=0; $c < $num; $c++) {
                $importData_arr[$i][] = $filedata [$c];
             }
             $i++;
          }
          fclose($file);
		  
		 
				$j=0;
				
			
				foreach($importData_arr as $importData){
				if( $j>0){
					$res=DB::table('logistic_vendor_pincode')->select('id')->where('vendor_id',$importData[0])->where('pincode',$importData[2])->first();
					if($res){
						$values = array(
							'vendor_id' =>$importData[0],
							'pincode' =>$importData[2],
							'price'=>$importData[4],
							'logistic_partner_id'=>$importData[1] ,
							'withLogistics'=>$importData[3] );
							DB::table('logistic_vendor_pincode')->where('vendor_id',$importData[0])->where('pincode',$importData[2])->update($values);
			} else{
				$values = array(
				'vendor_id' =>$importData[0],
				'pincode' =>$importData[2],
				'price'=>$importData[4],
				'logistic_partner_id'=>$importData[1] ,
				'withLogistics'=>$importData[3] );
				DB::table('logistic_vendor_pincode')->insert($values);
				
			}
				}

				$j++;
				}
				
				 MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
				 return Redirect::back();
		
    }
	
	public function vendor_pincode_assign(Request $request)
    { $input=$request->all();
     
	 
			
	  if ($input['with_logistics']==1){
     $request->validate([
              'vendor' => 'required',
              'pincode' => 'required',
			  'price' => 'required',
               'logistics' => 'required'
	 ],[
		 'pincode.required'=>'Port Code is required'
	 ]
			);
}
else {
    $request->validate([
              'vendor' => 'required',
              'pincode' => 'required',
			
                
            ]
			);
}
	 
         
			$res=DB::table('logistic_vendor_pincode')->select('id')->where('vendor_id',$input['vendor'])->where('pincode',$input['pincode'])->first();
			if($res){
				MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
			} else{
				$values = array(
				'vendor_id' => $input['vendor'],
				'pincode' => $input['pincode'], 
				'price' => $input['price'], 

				'logistic_partner_id'=>$input['logistics'],
				'withLogistics'=>$input['with_logistics']
				);
				DB::table('logistic_vendor_pincode')->insert($values);
				 MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
			}
				
				
			
			
				
				
				 return Redirect::back();
		
    }
    
     public function delete_pin(Request $request)
    {
            $id=base64_decode($request->id);

            $res=DB::table('logistic_vendor_pincode')->where('id',$id)
                    ->update(['isdeleted' => 1]);

            if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

                    return Redirect::route('vendor_pincode');
    }
	
	public function vendor_pincode_sts(Request $request)
    {
            $id=base64_decode($request->id);
            $sts=base64_decode($request->sts);

            $res=DB::table('logistic_vendor_pincode')->where('id',$id)
                    ->update(['status' => ($sts==0) ? 1 : 0]);

            if ($res) {
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
    }
	
	
	
	public function logistics(Request $request)
    {
		
		$page_details=array(
							"Title"=>"logistics List",
							"Box_Title"=>"logistic(s)",
							"search_route"=>URL::to('admin/logistics_search'),
							"reset_route"=>route('logistics')
						 );
						 
		$str=$request->str;
		$status=$request->status;
	  
		if($str=='all'){
			$str='';
		}
		if($status=='all'){
			$status='';
		}
		
		$Logistics= Logistics::where('isdeleted', 0);

		if($str!=''){
		  $Logistics->where('logistic_partner.name','LIKE',$str.'%');
		}
		if($status!=''){
		  $Logistics->where('logistic_partner.status','=',$status);
		}
		$Logistics=$Logistics->orderBy('id', 'DESC')->paginate(100);
		
        return view('admin.logistics.list',['Logistics'=>$Logistics,'page_details'=>$page_details,'status'=>$status]);
    }
	
	 public function addlogistic(Request $request){

     $page_details=array(
       "Title"=>"Add logistic",
	     "Method"=>"1",
       "Box_Title"=>"Add logistic",
       "Action_route"=>route('addlogistic'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "name_field"=>array(
              'label'=>'Name',
            'type'=>'text',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control',
            'placeholder'=>'Name',
            'value'=>'',
				'disabled'=>''
           ),
		   "logistic_link_field"=>array(
              'label'=>'Logistic Link',
            'type'=>'text',
            'name'=>'logistic_link',
            'id'=>'logistic_link',
            'classes'=>'form-control',
            'placeholder'=>'Logistic Link',
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
         )
       )
     );

if ($request->isMethod('post')) {
    $input=$request->all();
      
          $request->validate([
              'name' => 'required|unique:logistic_partner,name,1,isdeleted|max:255',
                
            ]
			);
           
          
      $Logistics = new Logistics;
      $Logistics->name = $input['name'];
	  $Logistics->logistic_link = $input['logistic_link'];
   
     
      /* save the following details */
      if($Logistics->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
}
    
    return view('admin.logistics.form',['page_details'=>$page_details]);
   }
   
    public function edit_logistic(Request $request)
   {
	    $id=base64_decode($request->id);
		
		 $Logistics_details = Logistics::where('id', $id)->first();
		 
	   $page_details=array(
       "Title"=>"Edit Logistic",
	    "Method"=>"2",
       "Box_Title"=>"Edit Logistic",
       "Action_route"=>route('edit_logistic', base64_encode($id)),
       "Form_data"=>array(

         "Form_field"=>array(
           
		   "name_field"=>array(
              'label'=>'Name',
            'type'=>'text',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control',
            'placeholder'=>'Name',
            'value'=>$Logistics_details->name,
				'disabled'=>''
           ),
		   "logistic_link_field"=>array(
              'label'=>'Logistic Link',
            'type'=>'text',
            'name'=>'logistic_link',
            'id'=>'logistic_link',
            'classes'=>'form-control',
            'placeholder'=>'Logistic Link',
            'value'=>$Logistics_details->logistic_link,
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
    $id=base64_decode($request->id);
       $Logistics = Logistics::find($id);
         $request->validate([
            'name' => 'required|unique:logistic_partner,name,'.$id.',id,isdeleted,0',
             
         ]
        );

         
   
    $Logistics->name = $input['name'];
	$Logistics->logistic_link = $input['logistic_link'];
	

  
   /* save the following details */
   if($Logistics->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
  
   return Redirect::back();
	 }
        
		   
		    return view('admin.logistics.form',['page_details'=>$page_details ,'Logistics_details'=>$Logistics_details]);

   }
   
   public function delete_loggistics(Request $request)
    {
            $id=base64_decode($request->id);

            $res=Logistics::where('id',$id)
                    ->update(['isdeleted' => 1]);

            if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

                    return Redirect::route('logistics');
    }
	public function vendornotfeaturemart(Request $request,$id)
    {

      $description_id=$id;
        $insert_data=array(
          "featuremart"=>'0'
        );        
        $res=DB::table('vendors')->where('id',$description_id)->update($insert_data);
        if($res){
          MsgHelper::save_session_message('success','Active Exibition',$request);
        } else{
           MsgHelper::save_session_message('danger','No data found',$request);
        }
        return Redirect::back();
    }
    public function vendorfeaturemart(Request $request,$id)
    {
      
      $description_id=$id;
        $insert_data=array(
          "featuremart"=>'1'
        );        
        $res=DB::table('vendors')->where('id',$description_id)->update($insert_data);
        if($res){
          MsgHelper::save_session_message('success','Deactive Exibition',$request);
        } else{
           MsgHelper::save_session_message('danger','No data found',$request);
        }
        return Redirect::back();
        
    }
	public function logistic_sts(Request $request)
    {
            $id=base64_decode($request->id);
            $sts=base64_decode($request->sts);

            $res=Logistics::where('id',$id)
                    ->update(['status' => ($sts==0) ? 1 : 0]);

            if ($res) {
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
    }
    
    	public function deleteBankDetails(Request $request)
	{
		$id=base64_decode($request->id);
		$check_details =  DB::table("vendor_bank_info")->where('id',$id)->delete();
		if($check_details){
			// $check_details->delete();
			MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		}else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
		}
		return Redirect::back();
	}
   
}
