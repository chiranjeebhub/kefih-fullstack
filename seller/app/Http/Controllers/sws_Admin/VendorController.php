<?php

namespace App\Http\Controllers\sws_Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;
use App\Http\Controllers\Vendors;
use App\Helpers\MsgHelper;
use App\Brands;
use App\Vendor;
use App\Products;
use App\ProductImages;
use App\ProductCategories;
use App\VendorCategory;
use App\ProductRelation;
use App\ProductAttributes;
use App\ProductRewards;
use Illuminate\Support\Facades\Hash;
use Redirect;
use Validator;
use DB;
use Config;
use Auth;
use Session;
use View;
use Exception;
use App\Helpers\CommonHelper;
use App\Helpers\FIleUploadingHelper;
use App\Helpers\CustomFormHelper;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BrandExport;
use App\Exports\ProductExport;
use URL;
class VendorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		$this->middleware('auth:vendor');
    }

    protected $rules =
    [
        'price' => 'required',
        'qty' => 'required',
    ];
	
    protected $messages =
    [
        'price.required' => 'Price can not be blank',
        'qty.required' => 'Quantity can not be blank'
    ];
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     
     public function vendor_logout(Request $request){
            Auth::logout();
         return redirect()->route('vendor_login');
     }
     
     public function vendor_editbrand(Request $request)
   {
	    $id=base64_decode($request->id);
		 $Brand_detail = Brands::where('id', $id)->first();
		   
	   $page_details=array(
       "Title"=>"Edit Brand",
	    "Method"=>"2",
       "Box_Title"=>"Edit Brand",
       "Action_route"=>route('vendor_editbrand', base64_encode($id)),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'Brand Name *',
            'type'=>'text',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control',
            'placeholder'=>'Name',
            'value'=>$Brand_detail['name'],
				'disabled'=>''
           ),
             "logo_file_field"=>array(
			   'label'=>'Logo Image *',
              'type'=>'file_special_imagepreview',
              'name'=>'logo_image',
               'id'=>'file-1',
              'classes'=>'inputfile inputfile-4',
              'placeholder'=>'',
			  'value'=>'',
			  'onchange'=>'image_preview_brandlogo(event)'
             ),
             
             
               "banner_file_field"=>array(
			    'label'=>'Banner Image *',
                'type'=>'file_special_imagepreview',
                'name'=>'banner_image',
               'id'=>'file-2',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
				'value'=>'',
				'onchange'=>'image_preview_bannerlogo(event)'
                
               ),
                "noc_file_field"=>array(
			    'label'=>'NOC FILE ',
                'type'=>'file_special_imagepreview',
                'name'=>'noc_file',
                'id'=>'file-3',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
				'value'=>'',
				'onchange'=>'image_preview_bannernoc(event)'
                
               ),
               "brand_description_field"=>array(
							'label'=>'Description',
							'type'=>'textarea',
							'name'=>'description',
							'id'=>'description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'description',
							'value'=>$Brand_detail->description,
							'disabled'=>''),
							
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
				  'logo_image'=>$Brand_detail->logo,
                  'banner_image'=>$Brand_detail->banner_image,
                     'noc_file'=>$Brand_detail->brand_noc
            )
         )
       )
     );
	 if ($request->isMethod('post')) {
		 $input=$request->all();
    $id=base64_decode($request->id);
       $Brand = Brands::find($id);
         $request->validate([
            'name' => 'required|unique:brands,name,'.$id.',id,isdeleted,0',
             'description' => 'max:60000',         
// 			'logo_image' => 'mimes:jpeg,bmp,png|min:'.Config::get('constants.size.logo_min').'|max:'.Config::get('constants.size.logo_max').'',
//             'banner_image' => 'mimes:jpeg,bmp,png|min:'.Config::get('constants.size.banner_min').'|max:'.Config::get('constants.size.banner_max').''    
                
             
         ],[
				'name.required'=> Config::get('messages.brand.error_msg.name_required'),
				'name.unique'=>Config::get('messages.brand.error_msg.name_unique'),
				'name.max'=>Config::get('messages.brand.error_msg.name_max'),
				'logo_image.required'=>Config::get('messages.brand.error_msg.logo_image_required'),
				'logo_image.mimes'=>Config::get('messages.brand.error_msg.logo_image_mimes'),
				'banner_image.required'=>Config::get('messages.brand.error_msg.banner_image_required'),
				'banner_image.mimes'=>Config::get('messages.brand.error_msg.banner_image_mimes'),
			]
        );

         
   
    $Brand->name = $input['name'];
    $Brand->description = $input['description'];
	  
    if ($request->hasFile('logo_image')) {
		
				$logo_image = $request->file('logo_image');
				$destinationPath =Config::get('constants.uploads.brand_logo');
				$file_name=$logo_image->getClientOriginalName();
 $file_name= FIleUploadingHelper::UploadImage($destinationPath,$logo_image,$file_name);
         if($file_name==''){
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
		    return Redirect::back();
      }
		
        $Brand->logo = $file_name;
  }

      if ($request->hasFile('banner_image')) {
			$banner_image = $request->file('banner_image');
			$destinationPath2 =  Config::get('constants.uploads.brand_banner');
			$file_name2=$banner_image->getClientOriginalName();

        $file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$banner_image,$file_name2);
      if($file_name2==''){
        MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
         return Redirect::back();
      }
              $Brand->banner_image = $file_name2;
    }
        if ($request->hasFile('noc_file')) {
			$noc_file = $request->file('noc_file');
			$destinationPath2 =  Config::get('constants.uploads.brand_banner');
			$file_name3=$noc_file->getClientOriginalName();

        $file_name3= FIleUploadingHelper::UploadDoc($destinationPath2,$noc_file,$file_name3);
      if($file_name3==''){
        MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
         return Redirect::back();
      }
              $Brand->brand_noc = $file_name3;
    } 

  
   /* save the following details */
   if($Brand->save()){
		  //MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  MsgHelper::save_session_message('success','Data updated Successfully',$request);
	  } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
  
   return Redirect::back();
	 }
        
		   
		    return view('vendor.brands.form',['page_details'=>$page_details ,'Brand_detail'=>$Brand_detail]);

   }
 public function vendor_deletebrand(Request $request)
    {
            $id=base64_decode($request->id);

            $res=Brands::where('id',$id)
                    ->update(['isdeleted' => 1]);

            if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

                    return Redirect::route('vendor_brands');
    }
     public function brand_export(Request $request)
    {
		
		return Excel::download(new BrandExport(), 'Brands'.date('d-m-Y H:i:s').'.csv');	
    }
    public function lists(Request $request)
    {
		$parameters=$request->str;
		$status=$request->status;		
			$export=URL::to('admin/vendor_brand_export');
		$page_details=array(
       "Title"=>"Brands",
       "Box_Title"=>"Brand(s)",
		"search_route"=>URL::to('admin/vendor_brand_search'),
		"reset_route"=>route('vendor_brands'),
		  "export"=>$export,
     );
	 
	 
	 $Brands= Brands::where('isdeleted', 0);
		
		if($parameters=='all'){
			$parameters='';
		}
		
		if($parameters!=''){
		  $Brands=$Brands
				->where('brands.name','LIKE',$parameters.'%');
		} 
		
		if($status!=''){
		  $Brands=$Brands
				->where('brands.status','=',$status);
		} 
				
		$Brands=$Brands->orderBy('id', 'DESC')->where('brands.vendor_id','=',auth()->guard('vendor')->user()->id)->paginate(100);
		
		return view('admin.brands.list',['Brands'=>$Brands,'page_details'=>$page_details,'status'=>$status]);
		
    }


   public function vendor_addbrand(Request $request){

     $page_details=array(
       "Title"=>"Add Brand",
	     "Method"=>"1",
       "Box_Title"=>"Add Brand",
       "Action_route"=>route('vendor_addbrand'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'Brand Name *',
            'type'=>'text',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control',
            'placeholder'=>'Name',
            'value'=>old('name'),
				'disabled'=>''
           ),
             "logo_file_field"=>array(
			   'label'=>'Logo Image *',
              'type'=>'file_special_imagepreview',
              'name'=>'logo_image',
              'id'=>'file-1',
              'classes'=>'inputfile inputfile-4',
              'placeholder'=>'',
			  'value'=>'',
			  'onchange'=>'image_preview_brandlogo(event)'
             ),
               "banner_file_field"=>array(
			    'label'=>'Banner Image *',
                'type'=>'file_special_imagepreview',
                'name'=>'banner_image',
                'id'=>'file-2',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
				'value'=>'',
				'onchange'=>'image_preview_bannerlogo(event)'
                
               ),
               "noc_file_field"=>array(
			    'label'=>'NOC FILE ',
                'type'=>'file_special_imagepreview',
                'name'=>'noc_file',
                'id'=>'file-3',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
				'value'=>'',
				'onchange'=>'image_preview_bannernoc(event)'
                
               ),
               "brand_description_field"=>array(
							'label'=>'Description',
							'type'=>'textarea',
							'name'=>'description',
							'id'=>'description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'description',
							'value'=>old('description'),
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
				  'logo_image'=>'',
                  'banner_image'=>'',
                  'noc_file'=>''
            )
         )
       )
     );

if ($request->isMethod('post')) {
    $input=$request->all();
      
          $request->validate([
                'name' => 'required|unique:brands,name,1,isdeleted|max:255|regex:/^[a-zA-Z0-9\s]+$/',
                'description' => 'max:60000',              
                'logo_image' => 'required|mimes:jpeg,bmp,png|min:'.Config::get('constants.size.logo_min').'|max:'.Config::get('constants.size.logo_max').'',
                'banner_image' => 'required|mimes:jpeg,bmp,png|min:'.Config::get('constants.size.banner_min').'|max:'.Config::get('constants.size.banner_max').'',
                'noc_file' => 'mimes:pdf|min:'.Config::get('constants.size.noc_min').'|max:'.Config::get('constants.size.noc_max').'',
            ],
			[
				'name.required'=> Config::get('messages.brand.error_msg.name_required'),
				'name.unique'=>Config::get('messages.brand.error_msg.name_unique'),
				'name.max'=>Config::get('messages.brand.error_msg.name_max'),
				'logo_image.required'=>Config::get('messages.brand.error_msg.logo_image_required'),
				'logo_image.mimes'=>Config::get('messages.brand.error_msg.logo_image_mimes'),
				'banner_image.required'=>Config::get('messages.brand.error_msg.banner_image_required'),
				'banner_image.mimes'=>Config::get('messages.brand.error_msg.banner_image_mimes'),
			]
			);
           
            $logo_image = $request->file('logo_image');
            $destinationPath =Config::get('constants.uploads.brand_logo');
            $file_name=$logo_image->getClientOriginalName();

            $banner_image = $request->file('banner_image');
            $destinationPath2 =  Config::get('constants.uploads.brand_banner');
            $file_name2=$banner_image->getClientOriginalName();
            
             $noc_file = $request->file('noc_file');
			 $file_name3='';
			 if($noc_file!='')
			 {
				$destinationPath3 =  Config::get('constants.uploads.brand_banner');
				$file_name3=$noc_file->getClientOriginalName();
			 }
            
            

       $file_name= FIleUploadingHelper::UploadImage($destinationPath,$logo_image,$file_name);
      if($file_name==''){
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
		   return Redirect::back();
      }
        $file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$banner_image,$file_name2);
      if($file_name2==''){
        MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
         return Redirect::back();
      }
      
	  if($noc_file!='')
	  {
		$file_name3= FIleUploadingHelper::UploadDoc($destinationPath3,$noc_file,$file_name3);
	  
		  if($file_name3==''){
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
			 return Redirect::back();
		  }
	  }

        $Brands = new Brands;
        $Brands->name = $input['name'];
        $Brands->description = $input['description'];
        $Brands->logo = $file_name;
        $Brands->banner_image = $file_name2;
        $Brands->brand_noc = $file_name3;
        $Brands->vendor_id = auth()->guard('vendor')->user()->id;
     
      /* save the following details */
      if($Brands->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
}
    
    return view('vendor.brands.form',['page_details'=>$page_details]);
   }


   public function edit(Request $request)
   {
	    $id=base64_decode($request->id);
	    echo $id;
		 $Brand_detail = Brands::where('id', $id)->first();
		   
	   $page_details=array(
       "Title"=>"Edit Brand",
	    "Method"=>"2",
       "Box_Title"=>"Edit Brand",
       "Action_route"=>route('vendor_editbrand', base64_encode($id)),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'Brand Name',
            'type'=>'text',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control',
            'placeholder'=>'Name',
            'value'=>$Brand_detail['name'],
				'disabled'=>''
           ),
             "logo_file_field"=>array(
			   'label'=>'Logo Image',
              'type'=>'file_special',
              'name'=>'logo_image',
               'id'=>'file-1',
              'classes'=>'inputfile inputfile-4',
              'placeholder'=>'',
              'value'=>''
             ),
             
             
               "banner_file_field"=>array(
			    'label'=>'Banner Image',
                'type'=>'file_special',
                'name'=>'banner_image',
               'id'=>'file-2',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>''
                
               ),
               "noc_file_field"=>array(
			    'label'=>'NOC FILE',
                'type'=>'file_special_imagepreview',
                'name'=>'noc_file',
                'id'=>'file-3',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
				'value'=>'',
				'onchange'=>''
                
               ),
               "brand_description_field"=>array(
							'label'=>'Description',
							'type'=>'textarea',
							'name'=>'description',
							'id'=>'description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'description',
							'value'=>$Brand_detail->description,
							'disabled'=>''),
							
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
				  'logo_image'=>$Brand_detail->logo,
                  'banner_image'=>$Brand_detail->banner_image,
                   'noc_file'=>$Brand_detail->brand_noc
            )
         )
       )
     );
	 if ($request->isMethod('post')) {
		 $input=$request->all();
    $id=base64_decode($request->id);
       $Brand = Brands::find($id);
         $request->validate([
            'name' => 'required|unique:brands,name,'.$id.',id,isdeleted,0|regex:/^[a-zA-Z0-9\s]+$/',
             'description' => 'max:60000',
            'logo_image' => 'required|mimes:jpeg,bmp,png|min:'.Config::get('constants.size.logo_min').'|max:'.Config::get('constants.size.logo_max').'',
            'banner_image' => 'required|mimes:jpeg,bmp,png|min:'.Config::get('constants.size.banner_min').'|max:'.Config::get('constants.size.banner_max').'',
            'noc_file' => 'mimes:pdf|min:'.Config::get('constants.size.noc_min').'|max:'.Config::get('constants.size.noc_max').'',
             
         ],[
				'name.required'=> Config::get('messages.brand.error_msg.name_required'),
				'name.unique'=>Config::get('messages.brand.error_msg.name_unique'),
				'name.max'=>Config::get('messages.brand.error_msg.name_max'),
				'logo_image.required'=>Config::get('messages.brand.error_msg.logo_image_required'),
				'logo_image.mimes'=>Config::get('messages.brand.error_msg.logo_image_mimes'),
				'banner_image.required'=>Config::get('messages.brand.error_msg.banner_image_required'),
				'banner_image.mimes'=>Config::get('messages.brand.error_msg.banner_image_mimes'),
			]
        );

         
   
    $Brand->name = $input['name'];
    $Brand->description = $input['description'];
	  
    if ($request->hasFile('logo_image')) {
		
				$logo_image = $request->file('logo_image');
				$destinationPath =Config::get('constants.uploads.brand_logo');
				$file_name=$logo_image->getClientOriginalName();
 $file_name= FIleUploadingHelper::UploadImage($destinationPath,$logo_image,$file_name);
         if($file_name==''){
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
		    return Redirect::back();
      }
		
        $Brand->logo = $file_name;
  }

      if ($request->hasFile('banner_image')) {
			$banner_image = $request->file('banner_image');
			$destinationPath2 =  Config::get('constants.uploads.brand_banner');
			$file_name2=$banner_image->getClientOriginalName();

        $file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$banner_image,$file_name2);
      if($file_name2==''){
        MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
         return Redirect::back();
      }
              $Brand->banner_image = $file_name2;
    }
      if ($request->hasFile('noc_file')) {
			$noc_file = $request->file('noc_file');
			$destinationPath2 =  Config::get('constants.uploads.brand_banner');
			$file_name3=$noc_file->getClientOriginalName();

        $file_name3= FIleUploadingHelper::UploadDoc($destinationPath2,$noc_file,$file_name3);
      if($file_name3==''){
        MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
         return Redirect::back();
      }
              $Brand->brand_noc = $file_name3;
    }  

  
   /* save the following details */
   if($Brand->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
  
   return Redirect::back();
	 }
        
		   
		    return view('vendor.brands.form',['page_details'=>$page_details ,'Brand_detail'=>$Brand_detail]);

   }


    
   
   public function existing_product(){

	   $page_details=array(
        "Title"=>"Existing Product",
        "Box_Title"=>"Search product",
		"Form_data"=>array(

         "Form_field"=>array(
		 "product_qty_field"=>array(
							'label'=>'Qty',
							'type'=>'number',
							'name'=>'qty',
							'id'=>'qty',
							'classes'=>'form-control',
							'placeholder'=>'0',
							'value'=>'',
							'disabled'=>''
			),
			"product_atr_sku_field"=>array(
				'label'=>'Sku',
				'type'=>'text',
				'name'=>'atr_sku[]',
				'id'=>'atr_sku',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>'',
				'disabled'=>''
						),
			"product_price_field"=>array(
							'label'=>'Price *',
							'type'=>'text',
							'name'=>'price',
							'id'=>'price',
							'classes'=>'form-control',
							'placeholder'=>'Price',
							'value'=>'',
							'disabled'=>''
			),
			"product_spcl_price_field"=>array(
							'label'=>'Special Price',
							'type'=>'text',
							'name'=>'spcl_price',
							'id'=>'spcl_price',
							'classes'=>'form-control',
							'placeholder'=>'Special Price',
							'value'=>'',
							'disabled'=>''
			),
				"product_spcl_from_date_field"=>array(
							'label'=>'Special Price From Date',
							'type'=>'date',
							'name'=>'spcl_from_date',
							'id'=>'spcl_from_date',
							'classes'=>'datepicker form-control',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>''
			),
			"product_spcl_to_date_field"=>array(
							'label'=>'Special Price To Date',
							'type'=>'date',
							'name'=>'spcl_to_date',
							'id'=>'spcl_to_date',
							'classes'=>'datepicker form-control',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>''
			),
			"product_manage_stock_field"=>array(
							'label'=>'Manage Stock',
							'type'=>'select',
							'name'=>'manage_stock',
							'id'=>'manage_stock',
							'classes'=>'form-control',
							'placeholder'=>'manage_stock',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Yes",
							),(object)array(
							"id"=>"0",
							"name"=>"NO",
							)
							),
							'disabled'=>'',
							'selected'=>''
			),
			
         "product_qty_for_out_stock_field"=>array(
							'label'=>"Qty to Become Out of Stock",
							'type'=>'number',
							'name'=>'qty_out',
							'id'=>'qty_out',
							'classes'=>'form-control',
							'placeholder'=>'0',
							'value'=>'',
							'disabled'=>''
			),
			"product_stock_availability_field"=>array(
							'label'=>'Stock Availability',
							'type'=>'select',
							'name'=>'stock_availability',
							'id'=>'stock_availability',
							'classes'=>'form-control',
							'placeholder'=>'stock_availability',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"In Stock",
							),(object)array(
							"id"=>"0",
							"name"=>"Out Of Stock",
							)
							),
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
		"product_size_field"=>array(
							'label'=>'Size',
							'type'=>'select',
							'name'=>'atr_size[]',
							'id'=>'atr_size',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(),
							'disabled'=>'',
							'selected'=>''
			),
			"product_color_field"=>array(
							'label'=>'Color',
							'type'=>'select',
							'name'=>'atr_color[]',
							'id'=>'atr_color',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(),
							'disabled'=>'',
							'selected'=>''
			),
				"product_barcode_field"=>array(
                                'label'=>'Barcode',
                                'type'=>'text',
                                'name'=>'barcode[]',
                                'id'=>'barcode',
                                'classes'=>'form-control',
                                'placeholder'=>'',
                                'value'=>'',
                                'disabled'=>''
                ),
			 "product_atr_qty_field"=>array(
							'label'=>'Qty',
							'type'=>'text',
							'name'=>'atr_qty[]',
							'id'=>'atr_qty',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>''
									),
									 "product_atr_price_field"=>array(
							'label'=>'Price',
							'type'=>'text',
							'name'=>'atr_price[]',
							'id'=>'atr_price',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>''
									),
		)
         ),"return_data"=>array(
							'attr'=>array(),
							'image_html'=>'',
							'product_images'=>array()
       )
        );
	  return view('vendor.product.search_existing_product',['page_details'=>$page_details]);
   }
   public function gotHome(){
       $page_details=array(
       "Title"=>"Home",
		"Method"=>"1",
		"Box_Title"=>"Home"
		);
	    return view('vendor.home',['page_details'=>$page_details]);
   }
    public function index(Request $request)
    {
     //echo auth()->guard('vendor')->user()->id;
      $Brands= Brands::where('isdeleted', 0);
                    $Brands=$Brands->orderBy('id', 'DESC')->paginate(100);
   $vendors=Vendor::get();
		$parameters=$request->str;	
		
		if($parameters!=''){
			$export=route('exportProduct_with_Search',($request->str));
			} else{
			$export=route('exportProduct');
		}
		
	 	$page_details=array(
			"Title"=>"Product List",
			"Box_Title"=>"Product(s)",
			  "search_route"=>URL::to('admin/vendor_filters_products'),
			"reset_route"=>route('vendor_product'),
			 "export"=>$export,
				 "Form_data"=>array(
				 "Form_field"=>array(
						 "submit_button_field"=>array(
						  'label'=>'',
						  'type'=>'submit',
						  'name'=>'submit',
						  'id'=>'submit',
						  'classes'=>'btn btn-danger disableAfterClick',
						  'placeholder'=>'',
						  'value'=>'Save'
						),
                    "select_field"=>array(
                    'label'=>'Select Parent',
                    'type'=>'select_with_inner_loop_for_filter',
                    'name'=>'category_id',
                    'id'=>'category_id',
                    'classes'=>'custom-select form-control category_id',
                    'placeholder'=>'Name',
                    'value'=>CommonHelper::getAdminChilds(1,'',old('parent_id'))
                    )
					)
			)
		);
		
        $Products= Products::select('products.*')->where('products.isdeleted', 0);
		
		if($parameters!=''){
				  /*$Products=$Products->where('products.name','LIKE',$parameters.'%');*/
				  $Products =$Products
						->join('product_categories','products.id','=','product_categories.product_id')
						->join('categories','product_categories.product_id','=','categories.id')
						->where('products.isexisting',0)
						->Where(function($query) use ($parameters){
							 $query->orWhere('products.name','LIKE', '%' . $parameters . '%');
							 $query->orWhere('products.sku','LIKE', '%' . $parameters . '%');
							 $query->orWhere('categories.name','LIKE', '%' . $parameters . '%');
						 });
		} 
		/*$products=$Products->orderBy('id', 'DESC')->where('products.name','LIKE',$parameters.'%')->paginate(100);*/
		
		 if(Auth::guard('vendor')->check()){
           $Products=$Products->where('products.vendor_id','=',auth()->guard('vendor')->user()->id);
        } 
						
		$products=$Products->groupBy('products.id')->orderBy('products.id', 'DESC')->paginate(100);
		
		return view('vendor.product.list',['products'=>$products,'page_details'=>$page_details,'vendors'=>$vendors,'Brands'=>$Brands]);
	      
    }
    
     public function vendor_filters_products(Request $request)
    {
		$vendors=Vendor::get();
		$Brands= Brands::where('isdeleted', 0);
                    $Brands=$Brands->orderBy('id', 'DESC')->paginate(100);
    $parameters=$request->str;	
    $sts=$request->sts;	
    $vendor=$request->vendor;
      $type=$request->type;	
       $category_id =$request->category_id;
        $blocked =$request->blocked;
         $brands =$request->brands;
	
		
		if($parameters!=''){
			$export=route('exportProduct_with_Search',($request->str));
			} else{
			$export=route('exportProduct');
		}
		
	 	$page_details=array(
			"Title"=>"Product List",
			"Box_Title"=>"Product(s)",
		     "search_route"=>URL::to('admin/vendor_filters_products'),
			"reset_route"=>route('vendor_product'),
			 "export"=>$export,
			 	"Form_data"=>array(
				 "Form_field"=>array(
						 "submit_button_field"=>array(
						  'label'=>'',
						  'type'=>'submit',
						  'name'=>'submit',
						  'id'=>'submit',
						  'classes'=>'btn btn-danger disableAfterClick',
						  'placeholder'=>'',
						  'value'=>'Save'
						),"select_field"=>array(
                    'label'=>'Select Parent',
                    'type'=>'select_with_inner_loop_for_filter',
                    'name'=>'category_id',
                    'id'=>'category_id',
                    'classes'=>'custom-select form-control category_id',
                    'placeholder'=>'Name',
                    'value'=>CommonHelper::getAdminChilds(1,'',($category_id!='All')?$category_id:0)
                    )
					)
			)
		);
		
        $Products= Products::select('products.*')->where('products.isdeleted', 0);
		if(Auth::guard('vendor')->check()){
           $Products=$Products->where('products.vendor_id','=',auth()->guard('vendor')->user()->id);
        } 
		if( ($category_id!='All' && $category_id!='')  || ($parameters!='All' && $parameters!='')){
		   
		        $Products =$Products
				  		->join('product_categories','product_categories.product_id','=','products.id')
						->join('categories','categories.id','=','product_categories.cat_id');
				  
		} 
		
		if($brands!='All' &&  $brands!=''){
		    	$selcted_brand=explode(",",$brands);
		    
				   $Products=$Products->whereIn('products.product_brand',$selcted_brand);
		} 
	
        
		if( $sts!='' && $sts!='All'){
				$Products=$Products->where('products.status','=',$sts);
		} 
	
	if( $blocked!='' && $blocked!='All'){
				$Products=$Products->where('products.isblocked','=',$blocked);
		} 
		
		if($type!='All' && $type!=''){
		     $Products=$Products->where('products.isexisting','=',$type);
		}
		
		
		if($parameters!='All' && $parameters!=''){
		       $Products =$Products
						   ->Where(function($query) use ($parameters){
							 $query->Where('products.name','LIKE', '%' . $parameters . '%');
							 $query->orWhere('products.sku','LIKE', '%' . $parameters . '%');
							 $query->orWhere('categories.name','LIKE', '%' . $parameters . '%');
						 });
		} 
		if($category_id!='All' && $category_id!=''){
		  	$Products =$Products->where('product_categories.cat_id',$category_id);
		}  
						
		$products=$Products->groupBy('products.id')->orderBy('products.id', 'DESC')->paginate(100);
		
		return view('vendor.product.list',['products'=>$products,'page_details'=>$page_details,'vendors'=>$vendors,'Brands'=>$Brands]);
    }
	 public function getProDetails(Request $request)
		{
			
			$dt=array(
			"product_size_field"=>array(
							'label'=>'Size',
							'type'=>'select',
							'name'=>'atr_size[]',
							'id'=>'atr_size',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(),
							'disabled'=>'',
							'selected'=>''
			),
			"product_color_field"=>array(
							'label'=>'Color',
							'type'=>'select',
							'name'=>'atr_color[]',
							'id'=>'atr_color',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(),
							'disabled'=>'',
							'selected'=>''
			),
			 "product_atr_qty_field"=>array(
							'label'=>'Qty',
							'type'=>'text',
							'name'=>'atr_qty[]',
							'id'=>'atr_qty',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>''
									));
			$input=$request->all();
			 $Products= Products::select('id','price','qty','spcl_price','spcl_from_date','spcl_to_date','manage_stock','qty_out','stock_availability')
							->where('isdeleted', 0)
							->where('products.id', '=',$input['prd_id'])
							->first()->toarray();
							
					$Products['spcl_from_date']=($Products['spcl_from_date']!='')?date("m/d/Y", strtotime($Products['spcl_from_date'])):'';	
					$Products['spcl_to_date']=($Products['spcl_to_date']!='')?date("m/d/Y", strtotime($Products['spcl_to_date'])):'';	
							$obj= new ProductAttributes;
							$data=$obj->getProductAttributes($input['prd_id']);
							$html='';
							foreach($data as $row){
								
								$html.='<div class="row">';
	
	$html.='<div class="col-md-3">';
	$html.=CustomFormHelper::getSizeHtml($dt["product_size_field"],$row['size_id']);
	$html.='</div>';
	
	
	$html.='<div class="col-md-3">';
	$html.=CustomFormHelper::getColorHtml($dt["product_color_field"],$row['color_id']);
	$html.='</div>';
	
		$html.='<div class="col-md-3">';
		$html.=CustomFormHelper::getQtyHtml($dt["product_atr_qty_field"],$row['qty']);
		$html.='</div>';
	
	
	$html.='<div class="col-md-3">';
	$html.='<div class="form-group">';
	$html.='<span class="remove_atr pointer"><i class="fa fa-trash"></i></span>';
	$html.='</div>';
	$html.='</div>';
	
	$html.='</div>';
							}
							 echo json_encode(array(
							 "product_details"=>$Products,
							 "product_attr"=>$html,
							  "product_attr_length"=>sizeof($data)
							 ));
							
							
		}
		
 public function update_vdr_profile(Request $request)
    {
		$id=Auth::user()->id;
		$request->session()->put('vendor_id', $id);
		$level= base64_decode($request->level);


		$Vendor = new Vendor;		  
		$return_data= $Vendor->getVendorDetails($request->session()->get('vendor_id'));

		$select_cats='';
		$dr=DB::table('vendor_categories')->where('vendor_id',$id)->first();
		if($dr){
		   $select_cats= $dr->selected_cats;
		}

		$bank_details = DB::table('vendor_bank_info')->where('vendor_id',$id)->get();
		$bank_ac_details = DB::table('vendor_bank_info')->where('id',base64_decode($request->bid))->first();
	

			$cities = DB::table('cities')
		              ->select('cities.name as name', 'cities.id as id', 'states.name as state_name')
		              ->join('states', 'states.id', '=', 'cities.state_id')
		              ->where('states.name',$return_data['company_state'])->get();
		
 	$page_details=array(
       "Title"=>"Update Profile",
		"Method"=>"1",
		"Box_Title"=>"Update Profile",
        "Action_route"=>route('update_vdr_profile', [base64_encode($level),base64_encode($id)]),
       "Form_data"=>array(

         "Form_field"=>array(
             "vendor_reg_id_field"=>array(
							'label'=>'Registration ID',
							'type'=>'text',
							'name'=>'registration_id',
							'id'=>'registration_id',
							'classes'=>'form-control',
							'placeholder'=>'Registration ID',
							'value'=>$return_data['registration_id'],
							'disabled'=>'disabled'
									),
             "vendor_f_name_field"=>array(
							'label'=>'First Name',
							'type'=>'text',
							'name'=>'f_name',
							'id'=>'f_name',
							'classes'=>'form-control',
							'placeholder'=>'First Name',
							'value'=>$return_data['f_name'],
							'disabled'=>''
									),
				"vendor_l_name_field"=>array(
						'label'=>'Last Name',
						'type'=>'text',
						'name'=>'l_name',
						'id'=>'l_name',
						'classes'=>'form-control',
						'placeholder'=>'Last Name',
						'value'=>$return_data['l_name'],
						'disabled'=>''
				),
				"vendor_user_name_field"=>array(
						'label'=>'User Name *',
						'type'=>'text',
						'name'=>'username',
						'id'=>'username',
						'classes'=>'form-control',
						'placeholder'=>'User Name',
						'value'=>$return_data['username'],
						'disabled'=>''
				),
				"vendor_public_name_field"=>array(
						'label'=>'Public Name *',
						'type'=>'text',
						'name'=>'public_name',
						'id'=>'public_name',
						'classes'=>'form-control',
						'placeholder'=>'Public Name',
						'value'=>$return_data['public_name'],
						'disabled'=>''
				),
				"vendor_email_field"=>array(
						'label'=>'Email *',
						'type'=>'text',
						'name'=>'email',
						'id'=>'email',
						'classes'=>'form-control',
						'placeholder'=>'Email',
						'value'=>$return_data['email'],
						'disabled'=>'disabled'
				),
				"vendor_phone_field"=>array(
						'label'=>'Phone *',
						'type'=>'text',
						'name'=>'phone',
						'id'=>'phone',
						'classes'=>'form-control',
						'placeholder'=>'Phone',
						'value'=>$return_data['phone'],
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
							'selected'=>$return_data['gender']
			),
			"vendor_profile_pic_field"=>array(
						'label'=>'Profile Pic',
						'type'=>'file_special_imagepreview',
						'name'=>'profile_pic',
						'id'=>'file-5',
                        'classes'=>'inputfile inputfile-4',
						'placeholder'=>'',
						'value'=>'',
						'disabled'=>'',
						'onchange'=>'image_preview(event)'
				),
				"vendor_signature_pic_field"=>array(
					'label'=>'Signature pic',
					'type'=>'file_special_imagepreview',
					'name'=>'signature_pic',
					'id'=>'file-53456',
					'classes'=>'inputfile inputfile-4',
					'placeholder'=>'',
					'value'=>'',
					'disabled'=>'',
					'onchange'=>'image_preview(event)'
			),
				"vendor_company_name_field"=>array(
						'label'=>'Company Name',
						'type'=>'text',
						'name'=>'company_name',
						'id'=>'company_name',
						'classes'=>'form-control',
						'placeholder'=>'Company Name',
						'value'=>$return_data['company_name'],
						'disabled'=>''
				),
				"vendor_company_address_field"=>array(
						'label'=>'Company Address',
						'type'=>'text',
						'name'=>'company_address',
						'id'=>'company_address',
						'classes'=>'form-control',
						'placeholder'=>'Company Address',
						'value'=>$return_data['company_address'],
						'disabled'=>''
				),
				// "vendor_company_state_field"=>array(
				// 		'label'=>'Company State',
				// 		'type'=>'text',
				// 		'name'=>'company_state',
				// 		'id'=>'company_state',
				// 		'classes'=>'form-control',
				// 		'placeholder'=>'Company State',
				// 		'value'=>$return_data['company_state'],
				// 		'disabled'=>''
				// ),
				// "vendor_company_city_field"=>array(
				// 		'label'=>'Company City',
				// 		'type'=>'text',
				// 		'name'=>'company_city',
				// 		'id'=>'company_city',
				// 		'classes'=>'form-control',
				// 		'placeholder'=>'Company City',
				// 		'value'=>$return_data['company_city'],
				// 		'disabled'=>''
				// ),
				
			"vendor_company_state_field"=>array(
                                'label'=>'Company State',
                                'type'=>'selectcustom',
                                'name'=>'company_state',
                                'id'=>'selectState',
                                'classes'=>'form-control',
                                'placeholder'=>'Company State',
                                'value'=>CommonHelper::getState('101'),
                    			'disabled'=>'',
                    			'selected'=>$return_data['company_state']
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
                    			'selected'=>$return_data['company_type']
                               ),
			"vendor_pancard_field"=>array(
								'label'=>'PAN Card',
								'type'=>'file_special_imagepreview',
								'name'=>'pancard',
								'id'=>'file-2',
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
								'id'=>'file-3',
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
								'id'=>'file-4',
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
                                'value'=>$return_data['pannumber'],
                    			'disabled'=>'',
                    			
                               ),
                "vendor_company_city_field"=>array(
                                 'label'=>'Company City',
                                'type'=>'selectcustom',
                                'name'=>'company_city',
                                'id'=>'selectcity',
                                'classes'=>'form-control',
                                'placeholder'=>'Company City',
                                'value'=>$cities,
                    			'disabled'=>'',
                    			'selected'=>$return_data['company_city']
                            ),
                            
                            
				"vendor_company_pincode_field"=>array(
						'label'=>'Pincode',
						'type'=>'text',
						'name'=>'company_pincode',
						'id'=>'company_pincode1',
						'classes'=>'form-control',
						'placeholder'=>'Pincode',
						'value'=>$return_data['company_pincode'],
						'disabled'=>''
				),
				"vendor_company_pannumber_field"=>array(
						'label'=>'PAN Number',
						'type'=>'text',
						'name'=>'pan_number',
						'id'=>'pan_number',
						'classes'=>'form-control',
						'placeholder'=>'PAN Number',
						'value'=> '',
						'disabled'=>''
				),
				"vendor_company_about_field"=>array(
					'label'=>'About Us',
					'type'=>'textarea',
					'name'=>'company_about',
					'id'=>'company_about',
					'classes'=>'ckeditor form-control',
					'placeholder'=>'About',
					'value'=>$return_data['company_about_us'],
					'disabled'=>''
					),
				"vendor_company_logo_field"=>array(
						'label'=>'Company Logo',
						'type'=>'file_special_imagepreview',
						'name'=>'company_logo',
						'id'=>'file-1',
                        'classes'=>'inputfile inputfile-4',
						'placeholder'=>'',
						'value'=>'',
						'disabled'=>'',
						'onchange'=>'image_preview(event)'
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
				'selected'=>$return_data['tax_type']
				),
				"vendor_tax_rate_field"=>array(
						'label'=>'Tax',
						'type'=>'text',
						'name'=>'tx_rate',
						'id'=>'tx_rate',
						'classes'=>'form-control hideextraField',
						'placeholder'=>'',
						'value'=>$return_data['tax_rate'],
						'disabled'=>''
				),
				"vendor_support_number_field"=>array(
						'label'=>'Phone',
						'type'=>'text',
						'name'=>'phone',
						'id'=>'phone_suport',
						'classes'=>'form-control',
						'placeholder'=>'Phone',
						'value'=>$return_data['support_phone'],
						'disabled'=>''
				),
				"vendor_support_email_field"=>array(
						'label'=>'Email',
						'type'=>'text',
						'name'=>'email',
						'id'=>'email',
						'classes'=>'form-control',
						'placeholder'=>'Email',
						'value'=>$return_data['support_email'],
						'disabled'=>''
				),
				"vendor_support_fb_field"=>array(
						'label'=>'Facebook Id',
						'type'=>'text',
						'name'=>'fb_id',
						'id'=>'fb_id',
						'classes'=>'form-control',
						'placeholder'=>'',
						'value'=>$return_data['support_fb_id'],
						'disabled'=>''
				),
				"vendor_support_tw_field"=>array(
						'label'=>'Twitter Id',
						'type'=>'text',
						'name'=>'tw_id',
						'id'=>'tw_id',
						'classes'=>'form-control',
						'placeholder'=>'',
						'value'=>$return_data['support_tw_id'],
						'disabled'=>''
				),
			 "vendoe_seo_meta_title_field"=>array(
							'label'=>'Meta Title',
							'type'=>'text',
							'name'=>'meta_title',
							'id'=>'meta_title',
							'classes'=>'form-control meta_tile',
							'placeholder'=>'Meta Title',
							'value'=>$return_data['meta_title'],
							'disabled'=>''
									),
			"vendoe_seo_meta_description_field"=>array(
						'label'=>'Meta Description',
						'type'=>'textarea',
						'name'=>'meta_description',
						'id'=>'meta_description',
						'classes'=>'form-control meta_description',
						'placeholder'=>'Meta description',
						'value'=>$return_data['meta_description'],
						'disabled'=>''
			),
			"vendoe_seo_meta_keyword_field"=>array(
						'label'=>'Meta Keyword',
						'type'=>'textarea',
						'name'=>'meta_keyword',
						'id'=>'meta_keyword',
						'classes'=>' form-control meta_keyword',
						'placeholder'=>'Meta Keyword',
						'value'=>$return_data['meta_keyword'],
						'disabled'=>''
			),
				"vendor_bank_ac_holder_name_field"=>array(
				'label'=>'Account Holder Name.',
				'type'=>'text',
				'name'=>'ac_holder_name',
				'id'=>'ac_holder_name',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>(@$bank_ac_details->ac_holder_name)?@$bank_ac_details->ac_holder_name:'',
				'disabled'=>''
				),
				"vendor_bank_ac_no_field"=>array(
				'label'=>'Account No.',
				'type'=>'text',
				'name'=>'ac_no',
				'id'=>'ac_no',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>(@$bank_ac_details->account_no)?@$bank_ac_details->account_no:'',
				'disabled'=>''
				),
				"vendor_bank_name_field"=>array(
				'label'=>'Bank Name',
				'type'=>'text',
				'name'=>'bank_name',
				'id'=>'bank_name',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>(@$bank_ac_details->name)?@$bank_ac_details->name:'',
				'disabled'=>''
				),
				"vendor_bank_branch_name_field"=>array(
				'label'=>'Branch Name',
				'type'=>'text',
				'name'=>'branch_name',
				'id'=>'branch_name',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>(@$bank_ac_details->branch)?@$bank_ac_details->branch:'',
				'disabled'=>''
				),
				"vendor_bank_city_field"=>array(
				'label'=>'City',
				'type'=>'text',
				'name'=>'bank_city',
				'id'=>'bank_city',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>(@$bank_ac_details->city)?@$bank_ac_details->city:'',
				'disabled'=>''
				),
				"vendor_bank_ifsc_field"=>array(
				'label'=>'IFSC Code',
				'type'=>'text',
				'name'=>'ifsc_code',
				'id'=>'ifsc_code',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>(@$bank_ac_details->ifsc_code)?@$bank_ac_details->ifsc_code:'',
				'disabled'=>''
				),
				"vendor_gst_field"=>array(
				'label'=>'GST NO.',
				'type'=>'text',
				'name'=>'gst_no',
				'id'=>'gst_no',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>$return_data['gst_no'],
				'disabled'=>''
				),
				"vendor_pan_field"=>array(
				'label'=>'PAN NO',
				'type'=>'text',
				'name'=>'pan_no',
				'id'=>'pan_no',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>$return_data['pan_no'],
				'disabled'=>''
				),
				"vendor_gst_file_field"=>array(
						'label'=>'GST File',
						'type'=>'file_special_imagepreview',
						'name'=>'gst_file',
						'id'=>'file-222',
                        'classes'=>'inputfile inputfile-4',
						'placeholder'=>'',
						'value'=>$return_data['gst_file'],
						'disabled'=>'',
						'onchange'=>''
				),
				"vendor_pan_file_field"=>array(
						'label'=>'Pan File',
						'type'=>'file_special_imagepreview',
						'name'=>'pan_file',
					'id'=>'file-3',
                        'classes'=>'inputfile inputfile-4',
						'placeholder'=>'',
						'value'=>$return_data['pan_file'],
						'disabled'=>'',
						'onchange'=>'image_preview(event)'
				),	"vendor_cheque_file_field"=>array(
						'label'=>'Cancel cheque',
						'type'=>'file_special_imagepreview',
						'name'=>'cheque',
						'id'=>'file-111',
					     'classes'=>'inputfile inputfile-5',
						'placeholder'=>'',
						'value'=>$return_data['cancel_cheque_file'],
						'disabled'=>'',
						'onchange'=>'image_preview(event)'
				),
				"vendor_signature_file_field"=>array(
						'label'=>'Signature',
						'type'=>'file_special_imagepreview',
						'name'=>'signature',
						'id'=>'file-7777',
					     'classes'=>'inputfile inputfile-7',
						'placeholder'=>'',
						'value'=>$return_data['signature_file'],
						'disabled'=>'',
						'onchange'=>'image_preview(event)'
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
				'selected'=>$return_data['invoice_address']
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
				'selected'=>$return_data['invoice_logo']
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
							'company_logo'=>$return_data['company_logo'],
							'profile_pic'=>$return_data['profile_pic'],
							'signature_pic'=>$return_data['signature_pic'],
							'pancard'=>$return_data['pancard'],
							'other_documents'=>$return_data['other_documents'],
							'certificate'=>$return_data['certificate'],
							'adharcard'=>$return_data['adharcard'],
							'address_proof'=>$return_data['address_proof'],
							'data'=>$bank_details
							
		),
		
     );
	
	  if ($request->isMethod('post')) {
		     $input=$request->all();
			 
		
			 
		  		 switch($level){
			 case 0;  // general info tab
			
			$request->validate([
				'username' => 'max:25|required|unique:vendors,username,'.$id.',id,isdeleted,1',
				'public_name' => 'max:25|required|unique:vendors,public_name,'.$id.',id,isdeleted,1',
				'f_name' => 'max:25',
				'l_name' => 'max:25',
                'profile_pic' => 'mimes:jpeg,bmp,png|min:'.Config::get('constants.size.vendor_profile_image_min').'|max:'.Config::get('constants.size.vendor_profile_image_max').''
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
						'password' => 'min:6|max:20',
						]
						);
					   $Vendor->password =Hash::make($input['password']);
				  } 
		 
      
			
			
			$Vendor->f_name = $input['f_name'];
			$Vendor->l_name =$input['l_name'];
			$Vendor->username =$input['username'];
			$Vendor->public_name =$input['public_name'];
			$Vendor->gender = $input['gender'];
     
      /* save the following details */
      if($Vendor->save()){
		       //Vendor::VendorStsInactive($id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		 return Redirect::route('update_vdr_profile', [base64_encode(0),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			 return Redirect::route('update_vdr_profile', [base64_encode(0),base64_encode($id)]);
      }
     
	  
			 break;
			  case 1;  // categories tab
				$Vendor = new Vendor;
			
      if($Vendor->updateCategories($input,$request->session()->get('vendor_id'))){
              //Vendor::VendorStsInactive($id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return Redirect::route('update_vdr_profile', [base64_encode(1),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			 return Redirect::route('update_vdr_profile', [base64_encode(1),base64_encode($id)]);
      }
			   break;
			   
			 
			   case 2: // company info tab
				 
			$request->validate([
			'company_name' => 'max:25',
			'company_address' => 'max:100',
            'company_state' => 'max:25',
            'company_city' => 'max:25',
            'company_pincode' => 'max:12',
			'about_us' => 'max:200',
			'company_logo' => 'mimes:jpeg,bmp,png|min:'.Config::get('constants.size.vendor_company_logo_min').'|max:'.Config::get('constants.size.vendor_company_logo_max').''
			]
			);
			
	 if ($request->hasFile('company_logo')) {
						$company_logo = $request->file('company_logo');
						$destinationPath =Config::get('constants.uploads.company_logo');
						$file_name=$company_logo->getClientOriginalName();
						$file_name= FIleUploadingHelper::UploadImage($destinationPath,$company_logo,$file_name);
							if($file_name==''){
							MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
							 return Redirect::route('update_vdr_profile', [base64_encode(2),base64_encode($id)]);
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
              //Vendor::VendorStsInactive($id);
			  
		    MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		    return Redirect::route('update_vdr_profile', [base64_encode(2),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			 return Redirect::route('update_vdr_profile', [base64_encode(2),base64_encode($id)]);
      }
				 break;
			   
			    case 3;  // support info tab
				$request->validate([
			'phone' => 'max:10|regex:/[0-9]{10}/',
			'email' => 'max:50|email',
			'fb_id' => 'max:200',
			'tw_id' => 'max:200',
			]
			);
				$Vendor = new Vendor;
      if($Vendor->updateSupportInfo($input,$request->session()->get('vendor_id'))){
              //Vendor::VendorStsInactive($id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		     return Redirect::route('update_vdr_profile', [base64_encode(3),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			 return Redirect::route('update_vdr_profile', [base64_encode(3),base64_encode($id)]);
      }
				 break;
				 
				   case 4;   // seo info info tab
				   
				 
			$request->validate([
			'meta_title' => 'max:60',
			'meta_description' => 'max:160',
			'meta_keyword' => 'max:360',
			]
			);
					$Vendor = new Vendor;
      if($Vendor->updateMetaInfo($input,$request->session()->get('vendor_id'))){
              //Vendor::VendorStsInactive($id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return Redirect::route('update_vdr_profile', [base64_encode(4),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('update_vdr_profile', [base64_encode(4),base64_encode($id)]);
      }
			   break;
			   
			   
			    case 5;   // bank  info info tab
				   
			
			$request->validate([
				'ac_no' => 'required|max:25',
				'bank_name' => 'required|max:50',
				'branch_name' => 'required|max:50',
				'bank_city' => 'required|max:25',
				'ifsc_code' => 'required|max:25',
				'cheque' => 'mimes:jpeg,png|min:'.Config::get('constants.size.vendor_cancel_cheque_image_min').'|max:'.Config::get('constants.size.vendor_cancel_cheque_image_max').'',
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
			return Redirect::route('update_vdr_profile', [base64_encode(5),base64_encode($id)]);
				}
				$Vendor->updateDOcs($file_name,'cancel_cheque',$request->session()->get('vendor_id'));
			}

		

      if($Vendor->updateBankInfo($input,$request->session()->get('vendor_id'))){
              //Vendor::VendorStsInactive($id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return Redirect::route('update_vdr_profile', [base64_encode(5),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('update_vdr_profile', [base64_encode(5),base64_encode($id)]);
      }
			   break;
			   
			  
			
			     case 6;   // tax  info info tab
			     
			     	$request->validate([
                'gst_no' => 'min:15|max:15',
                // 'pan_no' => 'min:10|max:10|alpha_num',
                'gst_file' => 'mimes:pdf|min:'.Config::get('constants.size.vendor_gst_image_min').'|max:'.Config::get('constants.size.vendor_gst_image_max').'',
                // 'pan_file' => 'mimes:jpeg,png|min:'.Config::get('constants.size.vendor_pan_image_min').'|max:'.Config::get('constants.size.vendor_pan_image_max').'',
                
                'signature' => 'mimes:jpeg,png|min:'.Config::get('constants.size.vendor_sign_image_min').'|max:'.Config::get('constants.size.vendor_sign_image_max').'',
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
				 return Redirect::route('update_vdr_profile', [base64_encode(6),base64_encode($id)]);
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
				 return Redirect::route('update_vdr_profile', [base64_encode(6),base64_encode($id)]);
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
				return Redirect::route('update_vdr_profile', [base64_encode(6),base64_encode($id)]);
				}
				$Vendor->updateDOcs($file_name,'signature',$request->session()->get('vendor_id'));
			}
			
			$Vendor->updateTaxInfo($input,$request->session()->get('vendor_id'));
			MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
			return Redirect::route('update_vdr_profile', [base64_encode(6),base64_encode($id)]);
	/*
      if($Vendor->updateTaxInfo($input,$request->session()->get('vendor_id'))){
              //Vendor::VendorStsInactive($id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return Redirect::route('update_vdr_profile', [base64_encode(6),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('update_vdr_profile', [base64_encode(6),base64_encode($id)]);
      }
	  */
			   break;
			   
			   case 7;   // invoice details  info info tab
				 
			
			
					$Vendor = new Vendor;
      if($Vendor->updateInvoiceInfo($input,$request->session()->get('vendor_id'))){
              //Vendor::VendorStsInactive($id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return Redirect::route('update_vdr_profile', [base64_encode(7),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('update_vdr_profile', [base64_encode(7),base64_encode($id)]);
      }
			   break;
			   
			
				 
				 }
		
	  }
        
		
		 return view('vendor.profile.edit',['page_details'=>$page_details,'vendor_data'=>$return_data,'bank_ac_details'=>$bank_ac_details]);
	
    }
	
    public function search(Request $request)
		{ 
		    $cats='';
		    $output='';
		  $vendorID=Auth::user()->id;
		 	$vendor_products=DB::table('vendor_existing_product')
                ->join('products','products.id','vendor_existing_product.product_id')
                ->where('products.isdeleted',0)
			->where('vendor_existing_product.vendor_id','=',$vendorID)
			->pluck('vendor_existing_product.master_product_id'); 
			
	          $vdr_cat=VendorCategory::select('selected_cats')->where('vendor_id',$vendorID)->first();
	          if($vdr_cat){
	               $cats= $vdr_cat->selected_cats;
	          }
            $vdr_cats=explode(',',$cats);
           
		if($request->search)
		{
			$products=DB::table('products')
            ->select('products.id','products.name','products.spcl_price as price','products.qty')
            ->join('product_categories','product_categories.product_id','products.id')
            ->where('products.name', 'like', '%'.$request->search."%")
            ->where('products.isexisting', '=',0)
            ->where('products.isdeleted',0)
            ->where('products.vendor_id','!=',$vendorID)
            ->whereNotIn('products.id',$vendor_products)
            ->orderBy('products.price','ASC')
             ->groupBy('products.id')
            ->limit(50)
            ->get();
			
			if($products)
			{
			foreach ($products as $key => $product) {
			   $url= route('sellThisProduct',base64_encode($product->id));
			$output.='<tr id="product_row_'.$product->id.'">'.
			'<td>'.$product->name.'</td>'.
			'<td>'.$product->price.'</td>'.
			'<td><a href="'.$url.'" class="btn btn-primary" data="'.$product->id.'">Sell</a></td>'.
			'</tr>';
			}
			}
			
		   }
		   echo json_encode(array("data"=>$output));
		}	
		
		public function sellThisProduct(Request $request){
                $prd_id= base64_decode($request->product_id);
                $vdr_id=Auth::user()->id;
		     $isAlready=DB::table('vendor_existing_product')
                ->select('vendor_existing_product.id')
                ->join('products','products.id','vendor_existing_product.master_product_id')
                ->where('vendor_existing_product.vendor_id',$vdr_id)
                ->where('vendor_existing_product.product_id',$prd_id)
                ->where('products.isdeleted',0)
			->first();
			
			if($isAlready){
		           MsgHelper::save_session_message('danger',$output,$request);
                return Redirect::back();
			} else{
                        $res=$this->copy_product($prd_id,$vdr_id);
                        if($res>0){
                              MsgHelper::save_session_message('success','Product Copied succesfullly ,please set your price',$request);
                    return redirect()->route('edit_product', [base64_encode(0),base64_encode($res)]);
                        } else{
                            MsgHelper::save_session_message('danger','Something went wrong',$request);
                            return Redirect::back();
                        }
                      
                  
			}
		}
		
		
		public function  copy_product($prd,$vdr_id){
                try {
                    $new_id=0;
                        DB::beginTransaction();
                           $model = Products::find($prd);
                        $newTask = $model->replicate();
                        $newTask->vendor_id = $vdr_id;
                         $newTask->name.=$vdr_id;
                         $newTask->gtin.=$vdr_id;
                        $newTask->isexisting = 1;
                        if($newTask->save()){
                             $new_id=$newTask->id;
                             DB::table('vendor_existing_product')->insert(array(
                                                "vendor_id" =>$vdr_id,
                                                "product_id"=>$new_id,
                                                "master_product_id"=>$prd
                                    ));
                            
                           
                        }
                      
                        $color_images=DB::table('product_configuration_images')->where('product_id',$prd)->get();
                          foreach($color_images as $color_image){
                                DB::table('product_configuration_images')->insert(array(
                                                "product_id" =>$new_id,
                                                "color_id"=>$color_image->color_id,
                                                "product_config_image"=>$color_image->product_config_image,
                                                "configuration_status"=>$color_image->configuration_status
                                    ));
                        }
                        
                        
                          $descriptions=DB::table('product_extra_description')->where('product_id',$prd)->get();
                          foreach($descriptions as $description){
                                DB::table('product_extra_description')->insert(array(
                                                "product_id" =>$new_id,
                                                "product_descrip_title"=>$description->product_descrip_title,
                                                "product_descrip_content"=>$description->product_descrip_content,
                                                "product_descrip_image"=>$description->product_descrip_image
                                    ));
                        }
                        
                       
                       
                          $descriptions_extras=DB::table('product_extra_general')->where('product_id',$prd)->get();
                          foreach($descriptions_extras as $descriptions_extra){
                                DB::table('product_extra_general')->insert(array(
                                                "product_id" =>$new_id,
                                                "product_general_descrip_title"=>$descriptions_extra->product_general_descrip_content,
                                                "product_general_descrip_content"=>$descriptions_extra->product_general_descrip_content
                                    ));
                        }
                        
                        
                        
                            $images=DB::table('product_images')->where('product_id',$prd)->get();
                          foreach($images as $image){
                                DB::table('product_images')->insert(array(
                                                "product_id" =>$new_id,
                                                "image"=>$image->image
                                    ));
                        }
                        
                            $qas=DB::table('product_question_answer')->where('product_id',$prd)->get();
                          foreach($qas as $qa){
                                DB::table('product_question_answer')->insert(array(
                                                "product_id" =>$new_id,
                                                "product_question"=>$qa->product_question,
                                                "product_answer"=>$qa->product_answer,
                                                "product_question_answer_status"=>$qa->product_question_answer_status
                                    ));
                        }
                        
                          $filsters=DB::table('product_filters')->where('product_id',$prd)->get();
                          foreach($filsters as $filster){
                                DB::table('product_filters')->insert(array(
                                                "product_id" =>$new_id,
                                                "filters_id"=>$filster->filters_id,
                                                "filters_input_value"=>$filster->filters_input_value
                                    ));
                        }
                        
                        $cats=ProductCategories::where('product_id',$prd)->get();
                          foreach($cats as $cat){
                              ProductCategories::insert(array(
                                            "product_id" =>$new_id,
                                            "cat_id"=>$cat->cat_id
                                ));
                        }
                        
                        $attrs=ProductAttributes::where('product_id',$prd)->get();
                        foreach($attrs as $attr){
                              ProductAttributes::insert(array(
                                            "product_id" =>$new_id,
                                            "size_id" => $attr->size_id,
                                            "women_size_id" => $attr->women_size_id,
                                            "unisex_type" => $attr->unisex_type,
                                            "color_id" => $attr->color_id,
                                            "qty" => $attr->qty,
                                            "price" => $attr->price,
                                            "sku" => $attr->sku,
                                            "default_load" => $attr->default_load,
                                            "barcode" => $attr->barcode,
                                            "gtin" => $attr->gtin
                                ));
                        }
                       
                            $points= ProductRewards::select('reward_points')->where('product_id',$prd)->first();
                            ProductRewards::insert(array(
                                "product_id"=>$new_id,
                                 "reward_points"=>$points->reward_points
                                ));
                           
                        
                      

                        
                        DB::commit();
                      
                      return $new_id;
                  }
                catch(Exception $e) {
                    DB::rollback();
                      return 0;
                }
                    
		}
		public function add(Request $request)
    {
		 $brands = Brands::select('id','name')->where('isdeleted', 0)->get();
		  $level= base64_decode($request->level);	
		
		
		
 $page_details=array(
       "Title"=>"Add Product",
		"Method"=>"1",
		"Box_Title"=>"Add Product",
       "Action_route"=>route('add_product_vdr',(base64_encode($level))),
       "Form_data"=>array(

         "Form_field"=>array(
             "product_name_field"=>array(
							'label'=>'Product Name *',
							'type'=>'text',
							'name'=>'name',
							'id'=>'name',
							'classes'=>'form-control',
							'placeholder'=>'Name',
							'value'=>'',
							'disabled'=>''
									),
			 "product_short_description_field"=>array(
							'label'=>'Short Description *',
							'type'=>'textarea',
							'name'=>'short_description',
							'id'=>'short_description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'Short description',
							'value'=>'',
							'disabled'=>''
			),
			"product_atr_sku_field"=>array(
				'label'=>'Sku',
				'type'=>'text',
				'name'=>'atr_sku[]',
				'id'=>'atr_sku',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>'',
				'disabled'=>''
						),
			 "product_long_description_field"=>array(
							'label'=>'Long Description *',
							'type'=>'textarea',
							'name'=>'long_description',
							'id'=>'long_description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'Long description',
							'value'=>'',
							'disabled'=>''
			),
			 "product_sku_field"=>array(
							'label'=>'SKU *',
							'type'=>'text',
							'name'=>'sku',
							'id'=>'sku',
							'classes'=>'form-control',
							'placeholder'=>'sku',
							'value'=>'',
							'disabled'=>''
									),
									"product_code_field"=>array(
                    'label'=>'Product Code',
                    'type'=>'text',
                    'name'=>'product_code',
                    'id'=>'product_code',
                    'classes'=>'form-control',
                    'placeholder'=>'123',
                    'value'=>'',
                    'disabled'=>''
                    ),
			"product_weight_field"=>array(
							'label'=>'Weight',
							'type'=>'text',
							'name'=>'weight',
							'id'=>'weight',
							'classes'=>'form-control',
							'placeholder'=>'weight',
							'value'=>'',
							'disabled'=>''
			),
			"product_status_field"=>array(
							'label'=>'Status *',
							'type'=>'select',
							'name'=>'status',
							'id'=>'status',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Enabled",
							),(object)array(
							"id"=>"0",
							"name"=>"Disabled",
							)
							),
							'disabled'=>'',
							'selected'=>''
			),
			 "product_hsn_field"=>array(
							'label'=>'HSN CODE',
							'type'=>'text',
							'name'=>'hsn_code',
							'id'=>'hsn_code',
							'classes'=>'form-control',
							'placeholder'=>'123',
							'value'=>'',
							'disabled'=>''
									),
			"product_tax_field"=>array(
							'label'=>'Tax Class *',
							'type'=>'select',
							'name'=>'tax_class',
							'id'=>'tax_class',
							'classes'=>'form-control',
							'placeholder'=>'Tax',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"None",
							),(object)array(
							"id"=>"0",
							"name"=>"Taxable Goods",
							)
							),
							'disabled'=>'',
							'selected'=>''
			),
			"product_visibility_field"=>array(
							'label'=>'Visibility',
							'type'=>'select',
							'name'=>'visibility',
							'id'=>'visibility',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Not visible individualy",
							),(object)array(
							"id"=>"2",
							"name"=>"Catalog",
							),(object)array(
							"id"=>"3",
							"name"=>"Search",
							),(object)array(
							"id"=>"4",
							"name"=>"Catalog,Search",
							)
							),
							'disabled'=>'',
							'selected'=>''
			),
			"product_image_field"=>array(
							'label'=>'Default Image',
							'type'=>'file_special',
							'name'=>'default_image',
                            'id'=>'file-1',
                            'classes'=>'inputfile inputfile-4',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>'',
							'selected'=>''
			),
			"product_price_field"=>array(
							'label'=>'Price *',
							'type'=>'text',
							'name'=>'price',
							'id'=>'price',
							'classes'=>'form-control',
							'placeholder'=>'Price',
							'value'=>'',
							'disabled'=>''
			),
			"product_spcl_price_field"=>array(
							'label'=>'Special Price',
							'type'=>'text',
							'name'=>'spcl_price',
							'id'=>'spcl_price',
							'classes'=>'form-control',
							'placeholder'=>'Special Price',
							'value'=>'',
							'disabled'=>''
			),
				"product_spcl_from_date_field"=>array(
							'label'=>'Special Price From Date',
							'type'=>'date',
							'name'=>'spcl_from_date',
							'id'=>'spcl_from_date',
							'classes'=>'datepicker form-control',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>''
			),
			"product_spcl_to_date_field"=>array(
							'label'=>'Special Price To Date',
							'type'=>'date',
							'name'=>'spcl_to_date',
							'id'=>'spcl_to_date',
							'classes'=>'datepicker form-control',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>''
			),
			 "product_meta_title_field"=>array(
							'label'=>'Meta Title',
							'type'=>'text',
							'name'=>'meta_title',
							'id'=>'meta_title',
							'classes'=>'form-control meta_tile',
							'placeholder'=>'Meta Title',
							'value'=>'',
							'disabled'=>''
									),
			"product_meta_description_field"=>array(
						'label'=>'Meta Description',
						'type'=>'textarea',
						'name'=>'meta_description',
						'id'=>'meta_description',
						'classes'=>'form-control meta_description',
						'placeholder'=>'Meta description',
						'value'=>'',
						'disabled'=>''
			),
			"product_meta_keyword_field"=>array(
						'label'=>'Meta Keyword',
						'type'=>'textarea',
						'name'=>'meta_keyword',
						'id'=>'meta_keyword',
						'classes'=>' form-control meta_keyword',
						'placeholder'=>'Meta Keyword',
						'value'=>'',
						'disabled'=>''
			),
			"product_deleivery_days_field"=>array(
							'label'=>"Delivery Days",
							'type'=>'number',
							'name'=>'delivery_days',
							'id'=>'delivery_days',
							'classes'=>'form-control',
							'placeholder'=>'0',
							'value'=>'',
							'disabled'=>''
			),
			"product_shipping_charge_stock_field"=>array(
							'label'=>"Shipping Charges",
							'type'=>'number',
							'name'=>'shipping_charge',
							'id'=>'shipping_charge',
							'classes'=>'form-control',
							'placeholder'=>'0',
							'value'=>'',
							'disabled'=>''
			),
			"product_manage_stock_field"=>array(
							'label'=>'Manage Stock',
							'type'=>'select',
							'name'=>'manage_stock',
							'id'=>'manage_stock',
							'classes'=>'form-control',
							'placeholder'=>'manage_stock',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Yes",
							),(object)array(
							"id"=>"0",
							"name"=>"NO",
							)
							),
							'disabled'=>'',
							'selected'=>''
			),
			"product_qty_field"=>array(
							'label'=>'Qty',
							'type'=>'number',
							'name'=>'qty',
							'id'=>'qty',
							'classes'=>'form-control',
							'placeholder'=>'0',
							'value'=>'',
							'disabled'=>''
			),
         "product_qty_for_out_stock_field"=>array(
							'label'=>"Qty for Item's Status to Become Out of Stock",
							'type'=>'number',
							'name'=>'qty_out',
							'id'=>'qty_out',
							'classes'=>'form-control',
							'placeholder'=>'0',
							'value'=>'',
							'disabled'=>''
			),
			"product_stock_availability_field"=>array(
							'label'=>'Stock Availability',
							'type'=>'select',
							'name'=>'stock_availability',
							'id'=>'stock_availability',
							'classes'=>'form-control',
							'placeholder'=>'stock_availability',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"In Stock",
							),(object)array(
							"id"=>"0",
							"name"=>"Out Of Stock",
							)
							),
							'disabled'=>'',
							'selected'=>''
			),
			"product_material_field"=>array(
							'label'=>'Material',
							'type'=>'select',
							'name'=>'material',
							'id'=>'Material',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Test Material 1",
							),(object)array(
							"id"=>"0",
							"name"=>"Test Material 2",
							)
							),
							'disabled'=>'',
							'selected'=>''
			),
			"product_brands_field"=>array(
							'label'=>'Brands',
							'type'=>'select',
							'name'=>'product_brand',
							'id'=>'product_brands_field',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>$brands,
							'disabled'=>'',
							'selected'=>''
			),
			"product_size_field"=>array(
							'label'=>'Size',
							'type'=>'select',
							'name'=>'atr_size[]',
							'id'=>'atr_size',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>CommonHelper::getSizes($request->session()->get('product_id')),
							'disabled'=>'',
							'selected'=>''
			),
			"product_color_field"=>array(
							'label'=>'Color',
							'type'=>'select',
							'name'=>'atr_color[]',
							'id'=>'atr_color',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>CommonHelper::getColors($request->session()->get('product_id')),
							'disabled'=>'',
							'selected'=>''
			),
			 "product_atr_qty_field"=>array(
							'label'=>'Qty',
							'type'=>'text',
							'name'=>'atr_qty[]',
							'id'=>'atr_qty',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>''
									),
			"product_related_is_shown_field"=>array(
							'label'=>'',
							'type'=>'select',
							'name'=>'is_related_product_shown',
							'id'=>'is_related_product_shown',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Yes",
							),(object)array(
							"id"=>"0",
							"name"=>"No",
							)
							),
							'disabled'=>'',
							'selected'=>''
			),
			"product_up_sell_is_shown_field"=>array(
							'label'=>'',
							'type'=>'select',
							'name'=>'is_up_sell_product_shown',
							'id'=>'is_up_sell_product_shown',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Yes",
							),(object)array(
							"id"=>"0",
							"name"=>"No",
							)
							),
							'disabled'=>'',
							'selected'=>''
			),
			"product_cross_sell_is_shown_field"=>array(
							'label'=>'',
							'type'=>'select',
							'name'=>'is_cross_sell_product_shown',
							'id'=>'is_cross_sell_product_shown',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Yes",
							),(object)array(
							"id"=>"0",
							"name"=>"No",
							)
							),
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
		)
         )
       ),
	    "return_data"=>array(
							'attr'=>array(),
							'image_html'=>'',
							'product_images'=>array()
		)
     );
	
	  if ($request->isMethod('post')) {
		     $input=$request->all();
			 
		  		 switch($level){
			 case 0;  // general info tab
			 $request->validate([
			'name' => 'required|unique:products,name,1,isdeleted|max:25',
			'short_description' => 'required|max:200',
			'long_description' => 'required|max:2000',
			'sku' => 'required|max:25',
			'product_code' => 'required|max:25',
			'weight' => 'max:25',
			'status' => 'required|max:25',		
			 'default_image' => 'mimes:jpeg,bmp,png|min:'.Config::get('constants.size.vendor_profile_image_min').'|max:'.Config::get('constants.size.vendor_profile_image_max').''    

             ]
        );
		
		if ($request->hasFile('default_image')) {
			   $defualt_image = $request->file('default_image');
            $destinationPath = Config::get('constants.uploads.product_images');
            $file_name=$defualt_image->getClientOriginalName();

       $file_name= FIleUploadingHelper::UploadImage($destinationPath,$defualt_image,$file_name);
      if($file_name==''){
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
		   return Redirect::back();
      }else{
		    $input['default_image']=$file_name;
	  }
		  }
		  
         $vendorID=Auth::user()->id;
			$Products = new Products;
			$Products->name = $input['name'];
			$Products->short_description =$input['short_description'];
			$Products->long_description =$input['long_description'];
			$Products->sku =$input['sku'];
				$Products->product_code =$input['product_code'];
			$Products->weight = $input['weight'];
			$Products->prd_sts = $input['status'];
			$Products->default_image = $input['default_image'];
			$Products->vendor_id = $vendorID;
     
      /* save the following details */
      if($Products->save()){
		  $request->session()->put('product_id', $Products->id);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return redirect()->route('add_product_vdr', ['level' => base64_encode(1)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
     
	  
			 break;
			  case 1;  // price tab
			$request->validate([
			'price' => 'required',
			'spcl_price' => '',
			'tax_class' => 'required'
			]
			);
			
			if($input['spcl_from_date']!='' || $input['spcl_to_date']!=''){
				$request->validate([
					'spcl_from_date' => 'date|date_format:m/d/Y|before:spcl_to_date|required_with:spcl_from_date',
					'spcl_to_date'=> 'date|date_format:m/d/Y|after:spcl_from_date|required_with:spcl_to_date',
			]
			);
			}
		
		
			$Products = new Products;
      /* save the following details */
      if($Products->updatePrice($input,$request->session()->get('product_id'))){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return redirect()->route('add_product_vdr', ['level' => base64_encode(2)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
			   break;
			   
			   
			   
			     case 2;    // categories tab
					$ProductCategories = new ProductCategories;
      if($ProductCategories->updateCategories($input,$request->session()->get('product_id'))){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return redirect()->route('add_product_vdr', ['level' => base64_encode(3)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
			     break;
				 
				 
			  
			   
			   
			   case 3: // attributes tab
				 
			 if (array_key_exists("atr_color",$input))
					{
						$color_validator = Validator::make($request->all(),[
					'atr_color.*' => 'required',
					]);
						
						
					$size_validator = Validator::make($request->all(),[
					'atr_size.*' => 'required',
					]);
					
					if($color_validator->fails() && $size_validator->fails() ) {
						 return Redirect::back()->withErrors(['Size or color is mandatory']);
					}
					
					if($color_validator->fails() ) {
						 $this->validate($request, [
							'atr_size.*' => 'distinct|required',
							
							],[
							'atr_size.*.distinct' => 'Size  needs to be unique',
							'atr_size.*.required' => 'Size  is mandatory'
							]
							);
					}
						
						if($size_validator->fails() ) {
						 $this->validate($request, [
							'atr_color.*' => 'distinct|required',
							
							],[
							'atr_color.*.distinct' => 'Color  needs to be unique',
							'atr_color.*.required' => 'atr_color  is mandatory'
							]
							);
					}
							$this->validate($request, [
							'atr_qty.*' => 'required',
							
							],[
							'atr_qty.*.required' => 'Quantity  is mandatory'
							]
							);
							if($color_validator->fails()==0 && $size_validator->fails()==0) {
								for($i=0;$i<sizeof($input['atr_size'])-1;$i++){
								for($k=$i;$k<sizeof($input['atr_size'])-1;$k++){
								if($input['atr_size'][$i]==$input['atr_size'][$k+1] && $input['atr_color'][$i]==$input['atr_color'][$k+1]){
								return Redirect::back()->withErrors(['Same size and color combination can not enterd']);
								} 
								}
								}
							}

							
					}
					$ProductAttributes = new ProductAttributes;
			 if($ProductAttributes->updateAttributes($input,$request->session()->get('product_id'))){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		    return redirect()->route('add_product_vdr', ['level' => base64_encode(4)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
				 break;
			   
			    case 4;  // images tab
			
				$images_name=array();
			if($request->hasfile('images'))
				 {
					foreach($request->file('images') as $image)
					{
						$destinationPath =Config::get('constants.uploads.product_images');
						$file_name=$image->getClientOriginalName();
						$images_name[] = FIleUploadingHelper::UploadImage($destinationPath,$image,$file_name);						
					}
				 }
				$ProductImages = new ProductImages;
      if($ProductImages->updateImages($images_name,$request->session()->get('product_id'))){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return redirect()->route('add_product_vdr', ['level' => base64_encode(5)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
			   break;
			   
			   
			   case 5;  // manage stock tab
			  $request->validate([
			'manage_stock' => '',
			'qty' => '',
			'qty_out' => '',
			'stock_availability' => ''
			]
			);
		
			$Products = new Products;
      if($Products->updateStock($input,$request->session()->get('product_id'))){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return redirect()->route('add_product_vdr', ['level' => base64_encode(6)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
			
			   break;
			   
			   
			   
			  
				 
				 case 6;  // extra info tab
					
		
					$Products = new Products;
      if($Products->updateExtras($input,$request->session()->get('product_id'))){
		   MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		      return redirect()->route('add_product_vdr', ['level' => base64_encode(7)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
			     break;
				 
				   case 7;   // meta info info tab
			$request->validate([
			'meta_title' => 'max:60',
			'meta_description' => 'max:160',
				'meta_keyword' => 'max:255',
			]
			);
				$Products = new Products;
      if($Products->updatemetaInfo($input,$request->session()->get('product_id'))){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return redirect()->route('add_product_vdr', ['level' => base64_encode(8)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
			   break;
			   
			   
			    case 8;  /// related product tab
			
				$ProductRelation = new ProductRelation;
				
      if($ProductRelation->updateRelation($input,$request->session()->get('product_id'),0)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
				 return redirect()->route('add_product_vdr', ['level' => base64_encode(9)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
			   break;
			   
			   case 9; /// up sell tab
			
				$ProductRelation = new ProductRelation;
				if($ProductRelation->updateRelation($input,$request->session()->get('product_id'),1)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
				 return redirect()->route('add_product_vdr', ['level' => base64_encode(10)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
			   break;
			   
			   
			    case 10; /// cross sell tab
			
				$ProductRelation = new ProductRelation;
							
      if($ProductRelation->updateRelation($input,$request->session()->get('product_id'),2)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
				return redirect()->route('vendor_product');
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::back();
      }
			   break;
				 
				 
				 }
		
	  }
        
		
		 return view('vendor.product.form_add',['page_details'=>$page_details]);
	
    }
	
	public function edit_product(Request $request)
   {
	      $id=base64_decode($request->id);
		   $request->session()->put('product_id', $id);
		   
		  $products_list = Products::where('isdeleted', 0)->where('id','!=',$id)->orderBy('id', 'DESC')->paginate(10);
		  
			$ProductImages = new ProductImages;
			$images=$ProductImages->getImagesHtml($id);

			$ProductAttributes = new ProductAttributes;

			$attr=$ProductAttributes->getProductAttributes($id);
	  
	  
		$Products = Products::where('id', $id)->first();
					$brands = Brands::select('id','name')->where('isdeleted', 0)->get();
					$level= base64_decode($request->level);	
		
 $page_details=array(
       "Title"=>"Edit Product",
		"Method"=>"1",
		"Box_Title"=>"Edit Product",
       "Action_route"=>route('edit_product_vdr', [base64_encode($level),base64_encode($id)]),
       "Form_data"=>array(

         "Form_field"=>array(
             "product_name_field"=>array(
							'label'=>'Product Name *',
							'type'=>'text',
							'name'=>'name',
							'id'=>'name',
							'classes'=>'form-control',
							'placeholder'=>'Name',
							'value'=>$Products->name,
							'disabled'=>''
									),
			 "product_short_description_field"=>array(
							'label'=>'Short Description *',
							'type'=>'textarea',
							'name'=>'short_description',
							'id'=>'short_description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'Short description',
							'value'=>$Products->short_description,
							'disabled'=>''
			),
			 "product_long_description_field"=>array(
							'label'=>'Long Description *',
							'type'=>'textarea',
							'name'=>'long_description',
							'id'=>'long_description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'Long description',
							'value'=>$Products->long_description,
							'disabled'=>''
			),
			"product_atr_sku_field"=>array(
				'label'=>'Sku',
				'type'=>'text',
				'name'=>'atr_sku[]',
				'id'=>'atr_sku',
				'classes'=>'form-control',
				'placeholder'=>'',
				'value'=>'',
				'disabled'=>''
						),
			 "product_sku_field"=>array(
							'label'=>'SKU *',
							'type'=>'text',
							'name'=>'sku',
							'id'=>'sku',
							'classes'=>'form-control',
							'placeholder'=>'sku',
							'value'=>$Products->sku,
							'disabled'=>''
									),
									"product_code_field"=>array(
                    'label'=>'Product Code',
                    'type'=>'text',
                    'name'=>'product_code',
                    'id'=>'product_code',
                    'classes'=>'form-control',
                    'placeholder'=>'123',
                    'value'=>$Products->product_code,
                    'disabled'=>''
                    ),
			"product_weight_field"=>array(
							'label'=>'Weight',
							'type'=>'text',
							'name'=>'weight',
							'id'=>'weight',
							'classes'=>'form-control',
							'placeholder'=>'weight',
							'value'=>$Products->weight,
							'disabled'=>''
			),
			"product_status_field"=>array(
							'label'=>'Status *',
							'type'=>'select',
							'name'=>'status',
							'id'=>'status',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Enabled",
							),(object)array(
							"id"=>"0",
							"name"=>"Disabled",
							)
							),
							'disabled'=>'',
							'selected'=>$Products->prd_sts
			),
			"product_visibility_field"=>array(
							'label'=>'Visibility',
							'type'=>'select',
							'name'=>'visibility',
							'id'=>'visibility',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Not visible individualy",
							),(object)array(
							"id"=>"2",
							"name"=>"Catalog",
							),(object)array(
							"id"=>"3",
							"name"=>"Search",
							),(object)array(
							"id"=>"4",
							"name"=>"Catalog,Search",
							)
							),
							'disabled'=>'',
							'selected'=>$Products->visibility
			),
			"product_image_field"=>array(
							'label'=>'Default Image',
							'type'=>'file_special',
							'name'=>'default_image',
        					  'id'=>'file-1',
                            'classes'=>'inputfile inputfile-4',
							'placeholder'=>'',
							'value'=>$Products->default_image,
							'disabled'=>'',
							'selected'=>''
			),
			 "product_hsn_field"=>array(
							'label'=>'HSN CODE',
							'type'=>'text',
							'name'=>'hsn_code',
							'id'=>'hsn_code',
							'classes'=>'form-control',
							'placeholder'=>'123',
							'value'=>$Products->hsn_code,
							'disabled'=>''
									),
			"product_tax_field"=>array(
							'label'=>'Tax Class *',
							'type'=>'select',
							'name'=>'tax_class',
							'id'=>'tax_class',
							'classes'=>'form-control',
							'placeholder'=>'Tax',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"None",
							),(object)array(
							"id"=>"0",
							"name"=>"Taxable Goods",
							)
							),
							'disabled'=>'',
							'selected'=>$Products->tax_class
			),
			"product_price_field"=>array(
							'label'=>'Price *',
							'type'=>'text',
							'name'=>'price',
							'id'=>'price',
							'classes'=>'form-control',
							'placeholder'=>'Price',
							'value'=>$Products->price,
							'disabled'=>''
			),
			"product_spcl_price_field"=>array(
							'label'=>'Special Price',
							'type'=>'text',
							'name'=>'spcl_price',
							'id'=>'spcl_price',
							'classes'=>'form-control',
							'placeholder'=>'Special Price',
							'value'=>$Products->spcl_price,
							'disabled'=>''
			),
				"product_spcl_from_date_field"=>array(
							'label'=>'Special Price From Date',
							'type'=>'date',
							'name'=>'spcl_from_date',
							'id'=>'spcl_from_date',
							'classes'=>'datepicker form-control',
							'placeholder'=>'',
							'value'=> ($Products->spcl_from_date!='')?date("m/d/Y", strtotime($Products->spcl_from_date)):'',
							'disabled'=>''
			),
			"product_spcl_to_date_field"=>array(
							'label'=>'Special Price To Date',
							'type'=>'date',
							'name'=>'spcl_to_date',
							'id'=>'spcl_to_date',
							'classes'=>'datepicker form-control',
							'placeholder'=>'',
							'value'=>($Products->spcl_to_date!='')?date("m/d/Y", strtotime($Products->spcl_to_date)):'',
							'disabled'=>''
			),
			 "product_meta_title_field"=>array(
							'label'=>'Meta Title',
							'type'=>'text',
							'name'=>'meta_title',
							'id'=>'meta_title',
							'classes'=>'form-control',
							'placeholder'=>'Meta Title',
							'value'=>$Products->meta_title,
							'disabled'=>''
									),
			"product_meta_description_field"=>array(
						'label'=>'Meta Description',
						'type'=>'textarea',
						'name'=>'meta_description',
						'id'=>'meta_description',
						'classes'=>'form-control',
						'placeholder'=>'Meta description',
						'value'=>$Products->meta_description,
						'disabled'=>''
			),
			"product_meta_keyword_field"=>array(
						'label'=>'Meta Keyword',
						'type'=>'textarea',
						'name'=>'meta_keyword',
						'id'=>'meta_keyword',
						'classes'=>'cat_url form-control',
						'placeholder'=>'Meta Keyword',
						'value'=>$Products->meta_keyword,
						'disabled'=>''
			),
			"product_manage_stock_field"=>array(
							'label'=>'Manage Stock',
							'type'=>'select',
							'name'=>'manage_stock',
							'id'=>'manage_stock',
							'classes'=>'form-control',
							'placeholder'=>'manage_stock',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Yes",
							),(object)array(
							"id"=>"0",
							"name"=>"NO",
							)
							),
							'disabled'=>'',
							'selected'=>$Products->manage_stock
			),
			"product_deleivery_days_field"=>array(
							'label'=>"Delivery Days",
							'type'=>'number',
							'name'=>'delivery_days',
							'id'=>'delivery_days',
							'classes'=>'form-control',
							'placeholder'=>'0',
							'value'=>$Products->delivery_days,
							'disabled'=>''
			),
			"product_shipping_charge_stock_field"=>array(
							'label'=>"Shipping Charges",
							'type'=>'number',
							'name'=>'shipping_charge',
							'id'=>'shipping_charge',
							'classes'=>'form-control',
							'placeholder'=>'0',
							'value'=>$Products->shipping_charges,
							'disabled'=>''
			),
			"product_qty_field"=>array(
							'label'=>'Qty',
							'type'=>'number',
							'name'=>'qty',
							'id'=>'qty',
							'classes'=>'form-control',
							'placeholder'=>'0',
							'value'=>$Products->qty,
							'disabled'=>''
			),
         "product_qty_for_out_stock_field"=>array(
							'label'=>"Qty for Item's Status to Become Out of Stock",
							'type'=>'number',
							'name'=>'qty_out',
							'id'=>'qty_out',
							'classes'=>'form-control',
							'placeholder'=>'0',
							'value'=>$Products->qty_out,
							'disabled'=>''
			),
			"product_stock_availability_field"=>array(
							'label'=>'Stock Availability',
							'type'=>'select',
							'name'=>'stock_availability',
							'id'=>'stock_availability',
							'classes'=>'form-control',
							'placeholder'=>'stock_availability',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"In Stock",
							),(object)array(
							"id"=>"0",
							"name"=>"Out Of Stock",
							)
							),
							'disabled'=>'',
							'selected'=>$Products->stock_availability
			),
			"product_material_field"=>array(
							'label'=>'Material',
							'type'=>'select',
							'name'=>'material',
							'id'=>'Material',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Test Material 1",
							),(object)array(
							"id"=>"0",
							"name"=>"Test Material 2",
							)
							),
							'disabled'=>'',
							'selected'=>$Products->material
			),
			"product_related_is_shown_field"=>array(
							'label'=>'',
							'type'=>'select',
							'name'=>'is_related_product_shown',
							'id'=>'is_related_product_shown',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Yes",
							),(object)array(
							"id"=>"0",
							"name"=>"No",
							)
							),
							'disabled'=>'',
							'selected'=>$Products->is_related_product_shown
			),
			"product_up_sell_is_shown_field"=>array(
							'label'=>'',
							'type'=>'select',
							'name'=>'is_up_sell_product_shown',
							'id'=>'is_up_sell_product_shown',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Yes",
							),(object)array(
							"id"=>"0",
							"name"=>"No",
							)
							),
							'disabled'=>'',
							'selected'=>$Products->is_up_sell_product_shown
			),
			"product_cross_sell_is_shown_field"=>array(
							'label'=>'',
							'type'=>'select',
							'name'=>'is_cross_sell_product_shown',
							'id'=>'is_cross_sell_product_shown',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>array(
							(object)array(
							"id"=>"1",
							"name"=>"Yes",
							),(object)array(
							"id"=>"0",
							"name"=>"No",
							)
							),
							'disabled'=>'',
							'selected'=>$Products->is_cross_sell_product_shown
			),
			"product_brands_field"=>array(
							'label'=>'Brands',
							'type'=>'select',
							'name'=>'product_brand',
							'id'=>'product_brands_field',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>$brands,
							'disabled'=>'',
							'selected'=>$Products->product_brand
			),
			"product_size_field"=>array(
							'label'=>'Size',
							'type'=>'select',
							'name'=>'atr_size[]',
							'id'=>'atr_size',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>CommonHelper::getSizes($id),
							'disabled'=>'',
							'selected'=>''
			),
			"product_color_field"=>array(
							'label'=>'Color',
							'type'=>'select',
							'name'=>'atr_color[]',
							'id'=>'atr_color',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>CommonHelper::getColors($id),
							'disabled'=>'',
							'selected'=>''
			),
			 "product_atr_qty_field"=>array(
							'label'=>'Qty',
							'type'=>'text',
							'name'=>'atr_qty[]',
							'id'=>'atr_qty',
							'classes'=>'form-control',
							'placeholder'=>'',
							'value'=>'',
							'disabled'=>''
									),
		   "submit_button_field"=>array(
							'label'=>'',
							'type'=>'submit',
							'name'=>'submit',
							'id'=>'submit',
							'classes'=>'btn btn-danger disableAfterCick',
							'placeholder'=>'',
							'value'=>'Save'
		)
         )
       ),
	    "return_data"=>array(
							'product_category'=>CustomFormHelper::getProductCategory($id),
							'attr'=>$attr,
							'image_html'=>$images,
							'product_images'=>$ProductImages->getImages($id)
							
							
		)
     );
	
	  if ($request->isMethod('post')) {
		     $input=$request->all();
			
		  		 switch($level){
			 case 0;    /// general info tab
			 $request->validate([
				'name' => 'max:25|required|unique:products,name,'.$id.',id,isdeleted,0',
				'short_description' => 'required|max:200',
				'long_description' => 'required|max:2000',
				'sku' => 'required|max:25',
				'product_code' => 'required|max:25',
				'weight' => 'max:25',
				'status' => 'required|max:25',
				'default_image' => 'mimes:jpeg,png|min:'.Config::get('constants.size.vendor_profile_image_min').'|max:'.Config::get('constants.size.vendor_profile_image_max').''    

             ]
        );
		
		
		  if ($request->hasFile('default_image')) {
			   $defualt_image = $request->file('default_image');
            $destinationPath = Config::get('constants.uploads.product_images');
            $file_name=$defualt_image->getClientOriginalName();

       $file_name= FIleUploadingHelper::UploadImage($destinationPath,$defualt_image,$file_name);
      if($file_name==''){
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
		   return Redirect::back();
      }else{
		    $input['default_image']=$file_name;
	  }
		  }
		     
     

			$Products = new Products;
				
      if($Products->updateInfo($input,$id)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		   return Redirect::route('edit_product_vdr', [base64_encode(0),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_product_vdr', [base64_encode(0),base64_encode($id)]);
      }
     
	  
			 break;
			  case 1;  /// price tab
			$request->validate([
			'price' => 'required',
			'spcl_price' => '',
			'tax_class' => 'required'
			]
			);
			if($input['spcl_from_date']!='' || $input['spcl_to_date']!=''){
				$request->validate([
					'spcl_from_date' => 'date|date_format:m/d/Y|before:spcl_to_date|required_with:spcl_from_date',
					'spcl_to_date'=> 'date|date_format:m/d/Y|after:spcl_from_date|required_with:spcl_to_date',
			]
			);
			}
		
		
			$Products = new Products;
      /* save the following details */
      if($Products->updatePrice($input,$id)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		 return Redirect::route('edit_product_vdr', [base64_encode(1),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_product_vdr', [base64_encode(1),base64_encode($id)]);
      }
			   break;
			   
			   
			    case 2;  /// categories tab
					$ProductCategories = new ProductCategories;
      if($ProductCategories->updateCategories($input,$id)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		  return Redirect::route('edit_product_vdr', [base64_encode(2),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_product_vdr', [base64_encode(2),base64_encode($id)]);
      }
			     break;
			   
			   
			    
			    case 3:    /// attributes tab
				 
				  if (array_key_exists("atr_color",$input))
					{
						$color_validator = Validator::make($request->all(),[
					'atr_color.*' => 'required',
					]);
						
						
					$size_validator = Validator::make($request->all(),[
					'atr_size.*' => 'required',
					]);
					
					if($color_validator->fails() && $size_validator->fails() ) {
						 return Redirect::back()->withErrors(['Size or color is mandatory']);
					}
					
					if($color_validator->fails() ) {
						 $this->validate($request, [
							'atr_size.*' => 'distinct|required',
							
							],[
							'atr_size.*.distinct' => 'Size  needs to be unique',
							'atr_size.*.required' => 'Size  is mandatory'
							]
							);
					}
						
						if($size_validator->fails() ) {
						 $this->validate($request, [
							'atr_color.*' => 'distinct|required',
							
							],[
							'atr_color.*.distinct' => 'Color  needs to be unique',
							'atr_color.*.required' => 'atr_color  is mandatory'
							]
							);
					}
							$this->validate($request, [
							'atr_qty.*' => 'required',
							
							],[
							'atr_qty.*.required' => 'Quantity  is mandatory'
							]
							);
							if($color_validator->fails()==0 && $size_validator->fails()==0) {
								for($i=0;$i<sizeof($input['atr_size'])-1;$i++){
								for($k=$i;$k<sizeof($input['atr_size'])-1;$k++){
								if($input['atr_size'][$i]==$input['atr_size'][$k+1] && $input['atr_color'][$i]==$input['atr_color'][$k+1]){
								return Redirect::back()->withErrors(['Same size and color combination can not enterd']);
								} 
								}
								}
							}

							
					}
					
				 $ProductAttributes = new ProductAttributes;
			 if($ProductAttributes->updateAttributes($input,$id)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		   return Redirect::route('edit_product_vdr', [base64_encode(3),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_product_vdr', [base64_encode(3),base64_encode($id)]);
      }
				 break;
			   
			   
			    case 4;  /// images tab
				
		      
			
				$images_name=array();
							if (array_key_exists("product_images",$input))
							{
							foreach($input['product_images'] as $row){
							array_push($images_name,$row);
							}
							}
				
			
		
			
			if($request->hasfile('images'))
				 {
					foreach($request->file('images') as $image)
					{
						$destinationPath =Config::get('constants.uploads.product_images');
						$file_name=$image->getClientOriginalName();
						$fn= FIleUploadingHelper::UploadImage($destinationPath,$image,$file_name);	
						array_push($images_name,$fn);						
					}
				 }
		 
      if($ProductImages->updateImages($images_name,$id)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		return Redirect::route('edit_product_vdr', [base64_encode(4),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_product_vdr', [base64_encode(4),base64_encode($id)]);
      }
			   break;
			   
			   
			   case 5;   ///manage stock tab
			  $request->validate([
			'manage_stock' => '',
			'qty' => '',
			'qty_out' => '',
			'stock_availability' => ''
			]
			);
		
			$Products = new Products;
      if($Products->updateStock($input,$id)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		return Redirect::route('edit_product_vdr', [base64_encode(5),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_product_vdr', [base64_encode(5),base64_encode($id)]);
      }
			
			   break;
			   
			   
			   
			   
				 
				 case 6;  /// extra info tab
				
					
					
					$Products = new Products;
      if($Products->updateExtras($input,$id)){
		MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		   return Redirect::route('edit_product_vdr', [base64_encode(6),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_product_vdr', [base64_encode(6),base64_encode($id)]);
      }
			     break;
				 
				  case 7;   /// meta data tab
			$request->validate([
			'meta_title' => 'max:160',
			'meta_description' => 'max:360',
				'meta_keyword' => 'max:255',
			]
			);
				$Products = new Products;
      if($Products->updatemetaInfo($input,$id)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
				return Redirect::route('edit_product_vdr', [base64_encode(7),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_product_vdr', [base64_encode(7),base64_encode($id)]);
      }
			   break;
			   
			   
			    case 8;  /// related product tab
			
				$ProductRelation = new ProductRelation;
				
				
      if($ProductRelation->updateRelation($input,$id,0)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
				return Redirect::route('edit_product_vdr', [base64_encode(8),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_product_vdr', [base64_encode(8),base64_encode($id)]);
      }
			   break;
			   
			   
			   
			   case 9; /// up sell tab
			
				$ProductRelation = new ProductRelation;
				
				
      if($ProductRelation->updateRelation($input,$id,1)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
				return Redirect::route('edit_product_vdr', [base64_encode(9),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_product_vdr', [base64_encode(9),base64_encode($id)]);
      }
			   break;
			   
			   
			    case 10; /// cross sell tab
			
				$ProductRelation = new ProductRelation;
				
				
      if($ProductRelation->updateRelation($input,$id,2)){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
				return Redirect::route('edit_product_vdr', [base64_encode(10),base64_encode($id)]);
      } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			return Redirect::route('edit_product_vdr', [base64_encode(10),base64_encode($id)]);
      }
			   break;
				 
				
				 }
		
	  }
        
		
		 return view('vendor.product.form_edit',['page_details'=>$page_details,'products_list'=>$products_list]);

   }
    public function addSellProduct(Request $request)
		{ 
		
		
       
			$error="NO";
		$input=$request->all();

		$request->validate([
                'price' => 'required',
                'spcl_price' => 'lt:price',
                'qty' => 'required'
			]
			);
			
// 			if($input['spcl_from_date']!='' || $input['spcl_to_date']!=''){
// 				$request->validate([
// 					'spcl_from_date' => 'date|date_format:m/d/Y|before:spcl_to_date|required_with:spcl_from_date',
// 					'spcl_to_date'=> 'date|date_format:m/d/Y|after:spcl_from_date|required_with:spcl_to_date',
// 			]
// 			);
// 			}
			
			 if (array_key_exists("atr_color",$input))
					{
							$this->validate($request, [
							'atr_qty.*' => 'required',
							'atr_size.*' => 'required',
							'atr_color.*' => 'required'
							],[
							'atr_qty.*.required' => 'Quantity  is mandatory',
							'atr_size.*.required' => 'Size is mandatory',
							'atr_color.*.required' => 'Color is mandatory'
							]
							);
							

						for($i=0;$i<sizeof($input['atr_size'])-1;$i++){
							for($k=$i;$k<sizeof($input['atr_size'])-1;$k++){
							if($input['atr_size'][$i]==$input['atr_size'][$k+1] && $input['atr_color'][$i]==$input['atr_color'][$k+1]){
								$output="same size and color combination can not be entered";
								$error="YES";
									echo json_encode(array(
									"Msg"=>$output,
									"Error"=>$error
									));
									die();
							} 
							
							}
						}	
					}
					
					$vdr_id=Auth::user()->id;
			
			$isAlready=DB::table('vendor_existing_product')
                ->select('vendor_existing_product.id')
                ->join('products','products.id','vendor_existing_product.master_product_id')
                ->where('vendor_existing_product.vendor_id',$vdr_id)
                ->where('vendor_existing_product.product_id',$request->product_id)
                ->where('products.isdeleted',0)
			->first();
			
			if($isAlready){
				 $output="You have this product already";
				 $error="YES";
			} else{
				
				
							$prd_info=DB::table('products')
							->where('products.id',$request->product_id)
							->first();
							
			
						$Products= new Products;
						$Products->vendor_id =$vdr_id;
						$Products->name=$prd_info->name;
						$Products->short_description=$prd_info->short_description;
						$Products->long_description=$prd_info->long_description;
						$Products->sku=$prd_info->sku;
						$Products->weight=$prd_info->weight;
						$Products->visibility=$prd_info->visibility;
						$Products->hsn_code=$prd_info->hsn_code;
						$Products->prd_sts=$prd_info->prd_sts;
						$Products->tax=$prd_info->tax;
						$Products->price=$request->price;
						$Products->spcl_price=$request->spcl_price;
						//$Products->price=$prd_info->price;
						//$Products->spcl_price=$prd_info->spcl_price;
				// 		$Products->spcl_from_date=($input['spcl_from_date']!='')?date("Y-m-d", strtotime($input['spcl_from_date'])):null;
				// 		$Products->spcl_to_date=($input['spcl_from_date']!='')?date("Y-m-d", strtotime($input['spcl_from_date'])):null;
						$Products->tax_class=$prd_info->tax_class;
						$Products->meta_title=$prd_info->meta_title;
						$Products->meta_keyword=$prd_info->meta_keyword;
						$Products->meta_description=$prd_info->meta_description;
						$Products->default_image=$prd_info->default_image;
						$Products->qty=$prd_info->qty;
						$Products->manage_stock=$prd_info->manage_stock;
						$Products->qty_out=$prd_info->qty_out;
						$Products->stock_availability=$prd_info->stock_availability;
						$Products->product_brand=$prd_info->product_brand;
						$Products->material=$prd_info->material;
						$Products->is_related_product_shown=$prd_info->is_related_product_shown;
						$Products->is_up_sell_product_shown=$prd_info->is_up_sell_product_shown;
						$Products->is_cross_sell_product_shown=$prd_info->is_cross_sell_product_shown;
						$Products->isexisting=1;
						$Products->product_type=$prd_info->product_type;;
						$Products->status=0;
						
						 $Products->save();
						
					
			 if($Products->save()){
			    $ext_prd_id= $Products->id;
				 	DB::table('vendor_existing_product')->insert(
					[
					'vendor_id' =>$vdr_id,
					'product_id' =>$ext_prd_id,
					'master_product_id' =>$request->product_id
					]
					);
					
							$pr_cat=new ProductCategories;
							$cats['cat']=$pr_cat->getCategories($request->product_id);
							$pr_cat->updateCategories($cats,$Products->id);
							
							$pr_images=new ProductImages;
							$images=$pr_images->getImages($request->product_id);
							$imgs=array();
							
							foreach($images as $row){
								array_push($imgs,$row['image']);
							}
							
							$config_images=DB::table('product_configuration_images')->where('product_id',$request->product_id)->get();
							$whole_img=array();
							foreach($config_images as $config_img){
							DB::table('product_configuration_images')->insert(
							    array(
                                            "product_id"=>$ext_prd_id,
                                            "color_id"=>$config_img->color_id,
                                            "product_config_image"=>$config_img->product_config_image
							        )
							    );
							}
						
							
						      $images=$pr_images->updateImages($imgs,$ext_prd_id);
							$pd_atr=new ProductAttributes;
							$pd_atr_data=$pd_atr->getProductAttributesForSell($request->product_id);
							$pd_atr->updateAttributesForExiting($pd_atr_data,$ext_prd_id);
							
				
					
				 $output="Product Copied Saved Successfully!";
				 $error="NO";
			 }else{
				 $output="Something went wrong , Please Try Again!";
				 $error="YES";
			 }
			}
				
			
			 echo json_encode(array(
			 "Msg"=>$output,
			 "Error"=>$error
			 ));
			
	    }
	public function del(Request $request)
        {
            $id=base64_decode($request->id);
            $res=vendor_products::where('id',$id);   
            // dd($res);
            if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }
                    return Redirect::route('vendor_product');
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
