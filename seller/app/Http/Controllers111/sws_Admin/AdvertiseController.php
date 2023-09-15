<?php

namespace App\Http\Controllers\sws_Admin;


use Illuminate\Http\Request;
use App\Advertise;
use App\ProductSlider;
use App\Pages;
use App\Helpers\CustomFormHelper;
use App\Helpers\MsgHelper;
use App\Helpers\FIleUploadingHelper;
use Redirect;
use Validator;
use URL;
use DB;
use Config;
use App\Helpers\CommonHelper;
class AdvertiseController extends Controller
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
     
    public function advertise(Request $request)
    {
    	$page_details=array(
       "Title"=>"Advertise",
       "Box_Title"=>"Advertise",
		"search_route"=>'',
		"reset_route"=>''
     );
	 
		$banners=Advertise::get();
		
		return view('admin.advertise.listing',['advertises'=>$banners,'page_details'=>$page_details]);
    }
 public function addadvertise(Request $request){

     $page_details=array(
       "Title"=>"Add Advertise",
        "back_url"=>route('advertise'),
	     "Method"=>"1",
       "Box_Title"=>"Add Advertise",
       "Action_route"=>route('addadvertise'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
                'label'=>'Name',
                'type'=>'text',
                'name'=>'text',
                'id'=>'text',
                'classes'=>'form-control',
                'placeholder'=>'Text',
                'value'=>old('text'),
                'disabled'=>''
           ),
            "url_field"=>array(
                'label'=>'URL',
                'type'=>'text',
                'name'=>'url',
                'id'=>'url',
                'classes'=>'form-control',
                'placeholder'=>'Url',
                'value'=>old('url'),
                'disabled'=>''
           ),
           "category_field"=>array(
              'label'=>'Category',
            'type'=>'select_with_inner_loop',
            'name'=>'category_field',
            'id'=>'category_field',
            'classes'=>'custom-select form-control catClass',
            'placeholder'=>'Name',
            'value'=>CommonHelper::getAdminChildsAdver(1,'',0)
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
               "mobile_file_field"=>array(
			    'label'=>'Mobile Image *',
                'type'=>'file_special_imagepreview',
                'name'=>'mobile_image',
                'id'=>'file-3',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>'',
                'onchange'=>'image_preview(event)'
                
               ),
               "banner_description_field"=>array(
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
                  'banner_image'=>'',
                  'mobile_image'=>''
            )
         )
       )
     );

if ($request->isMethod('post')) {
    $input=$request->all();
      
          $request->validate([
              'text' => 'max:50',
              'description' => 'max:255',
              'url' => 'max:255', 
              'category_field' => 'required',       
              'banner_image' => 'required|mimes:jpeg,bmp,png|min:'.Config::get('constants.size.adimage_min').'|max:'.Config::get('constants.size.adimage_max').'',
                'mobile_image' => 'mimes:jpeg,bmp,png|min:'.Config::get('constants.size.adimage_min').'|max:'.Config::get('constants.size.adimage_max').''

                
            ]
			);
           
           
            $banner_image = $request->file('banner_image');
            $destinationPath2 =  Config::get('constants.uploads.advertise');
            $file_name2=$banner_image->getClientOriginalName();

      
        $file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$banner_image,$file_name2);
      if($file_name2==''){
        MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
         return Redirect::back();
      }
     
        $fld_type='';
        $assign_id='';
if($input['url']!=''){
                $url=$input['url'];
            if (preg_match("/cat/", $url))
            {
                    $url_array=explode("/",$url);
                    
                    $fld_type='fld_cat_id';
                    $assign_id=base64_decode(end($url_array));
                   
            }

        if (preg_match("/p/", $url))
        {
            $url_array=explode("/",$url);
            
            $fld_type='fld_product_id';
            $decodeInput=explode("~~~",base64_decode(end($url_array)));
            $assign_id=$decodeInput[0];
           
        }
        
        if (preg_match("/brand/", $url))
        {
            $url_array=explode("/",$url);
            $br_name=Brands::select('id')->where('name',end($url_array))->where('status',1)->where('isdeleted',0)->first();
            if($br_name){
                $fld_type='fld_brand_id';
                $assign_id=$br_name->id;
            }
       
        }
        
            }
            
           
        $Advertise = new Advertise;
         if ($request->hasFile('mobile_image')) {
			$mobile_image = $request->file('mobile_image');
			$destinationPath2 =  Config::get('constants.uploads.advertise');
			$file_name3=$mobile_image->getClientOriginalName();

			$file_name3= FIleUploadingHelper::UploadImage($destinationPath2,$mobile_image,$file_name3);
			  if($file_name3==''){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
				 return Redirect::back();
			  }
			$Advertise->mobile_image = $file_name3;
		}
		
		 if($fld_type!='' && $assign_id!=''){
                    $Advertise->product_type = $fld_type;
                    $Advertise->cat_prd_id = $assign_id;  
            }
        $Advertise->short_text = $input['text'];
        $Advertise->url = $input['url'];
        $Advertise->description = $input['description'];
        $Advertise->image = $file_name2;
        $Advertise->categories_id = $input['category_field'];
        
     
      /* save the following details */
      if($Advertise->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
}
    
    return view('admin.advertise.form',['page_details'=>$page_details,'back_url'=>route('advertise')]);
   }


   public function edit_advertise(Request $request)
   {
	    $id=base64_decode($request->id);
		 $Advertise_details = Advertise::where('id', $id)->first();
		   
		   
     $page_details=array(
       "Title"=>"Update Advertise",
        "back_url"=>route('advertise'),
	     "Method"=>"2",
       "Box_Title"=>"Update Advertise",
         "Action_route"=>route('edit_advertise', base64_encode($id)),
       "Form_data"=>array(

         "Form_field"=>array(
           "category_field"=>array(
              'label'=>'Category',
            'type'=>'select_with_inner_loop',
            'name'=>'category_field',
            'id'=>'category_field',
            'classes'=>'custom-select form-control catClass',
            'placeholder'=>'Name',
            'value'=>CommonHelper::getAdminChildsAdver(1,'',$Advertise_details->categories_id)
           ),
           "text_field"=>array(
                'label'=>'Name',
                'type'=>'text',
                'name'=>'text',
                'id'=>'text',
                'classes'=>'form-control',
                'placeholder'=>'Text',
                'value'=>$Advertise_details->short_text,
                'disabled'=>''
           ),
           "cat_url"=>array(
                'label'=>'Category ID',
                'type'=>'number',
                'name'=>'cat_id',
                'id'=>'cat_id',
                'classes'=>'form-control',
                'placeholder'=>'CAT ID',
                'value'=>$Advertise_details->mobile_cat_id,
                'disabled'=>''
           ),
            "url_field"=>array(
                'label'=>'URL',
                'type'=>'text',
                'name'=>'url',
                'id'=>'url',
                'classes'=>'form-control',
                'placeholder'=>'Url',
                'value'=>$Advertise_details->url,
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
               "mobile_file_field"=>array(
			        'label'=>'Mobile Image *',
                'type'=>'file_special_imagepreview',
                'name'=>'mobile_image',
                'id'=>'file-3',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>'',
                'onchange'=>'image_preview(event)'
                
               ),
               "banner_description_field"=>array(
							'label'=>'Description',
							'type'=>'textarea',
							'name'=>'description',
							'id'=>'description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'description',
							'value'=>$Advertise_details->description,
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
                  'banner_image'=>$Advertise_details->image,
                  'mobile_image'=>$Advertise_details->mobile_image
            )
         )
       )
     );

if ($request->isMethod('post')) {
    $input=$request->all();
           
    
    $id=base64_decode($request->id);
       $advertise = Advertise::find($id);
          $request->validate([
              'text' => 'max:50',
              'description' => 'max:255',
               'url' => 'max:255',    
               'cat_id' => 'required',
              'banner_image' => 'mimes:jpeg,bmp,png|min:'.Config::get('constants.size.adimage_min').'|max:'.Config::get('constants.size.adimage_max').'',
               'mobile_image' => 'mimes:jpeg,bmp,png|min:'.Config::get('constants.size.adimage_min').'|max:'.Config::get('constants.size.adimage_max').''

                
            ]
			);
           
           

		
$advertise->short_text = $input['text'];

$advertise->url = $input['url'];
$advertise->mobile_cat_id = $input['cat_id'];
$advertise->categories_id = $input['category_field'];
$advertise->description = $input['description'];
		    $fld_type='';
        $assign_id='';
if($input['url']!=''){
                $url=$input['url'];
            if (preg_match("/cat/", $url))
            {
                    $url_array=explode("/",$url);
                    
                    $fld_type='fld_cat_id';
                    $assign_id=base64_decode(end($url_array));
                   
            }

        if (preg_match("/p/", $url))
        {
            $url_array=explode("/",$url);
            
            $fld_type='fld_product_id';
            $decodeInput=explode("~~~",base64_decode(end($url_array)));
            $assign_id=$decodeInput[0];
           
        }
        
        if (preg_match("/brand/", $url))
        {
            $url_array=explode("/",$url);
            $br_name=Brands::select('id')->where('name',end($url_array))->where('status',1)->where('isdeleted',0)->first();
            if($br_name){
                $fld_type='fld_brand_id';
                $assign_id=$br_name->id;
            }
       
        }
        
            }
            if($fld_type!='' && $assign_id!=''){
                    $advertise->product_type = $fld_type;
                    $advertise->cat_prd_id = $assign_id;  
            }
             else{
                        $advertise->product_type = '';
                        $advertise->cat_prd_id = '';  
            }
            
        if ($request->hasFile('banner_image')) {
			$banner_image = $request->file('banner_image');
			$destinationPath2 =  Config::get('constants.uploads.advertise');
			$file_name2=$banner_image->getClientOriginalName();

			$file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$banner_image,$file_name2);
			  if($file_name2==''){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
				 return Redirect::back();
			  }
			$advertise->image = $file_name2;
		}
		 if ($request->hasFile('mobile_image')) {
			$mobile_image = $request->file('mobile_image');
			$destinationPath2 =  Config::get('constants.uploads.advertise');
			$file_name3=$mobile_image->getClientOriginalName();

			$file_name3= FIleUploadingHelper::UploadImage($destinationPath2,$mobile_image,$file_name3);
			  if($file_name3==''){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
				 return Redirect::back();
			  }
			$advertise->mobile_image = $file_name3;
		}
		   
    
     
      /* save the following details */
      if($advertise->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
}
    
    return view('admin.advertise.form',['page_details'=>$page_details,'back_url'=>route('advertise')]);
	  

   }
   public function addadvertise_old(Request $request){

     $page_details=array(
       "Title"=>"Add Advertise",
       "back_url"=>route('advertise'),
	     "Method"=>"1",
       "Box_Title"=>"Add Advertise",
       "Action_route"=>route('addadvertise'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
                'label'=>'Name',
                'type'=>'text',
                'name'=>'text',
                'id'=>'text',
                'classes'=>'form-control',
                'placeholder'=>'Text',
                'value'=>old('text'),
                'disabled'=>''
           ),
            "url_field"=>array(
                'label'=>'URL',
                'type'=>'text',
                'name'=>'url',
                'id'=>'url',
                'classes'=>'form-control',
                'placeholder'=>'Url',
                'value'=>old('url'),
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
               "banner_description_field"=>array(
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
                  'banner_image'=>''
            )
         )
       )
     );

if ($request->isMethod('post')) {
    $input=$request->all();
      
          $request->validate([
              'text' => 'max:50',
              'description' => 'max:255',
               'url' => 'max:255',
              'banner_image' => 'required|mimes:jpeg,bmp,png|min:'.Config::get('constants.size.adimage_min').'|max:'.Config::get('constants.size.adimage_max').''
                
            ]
			);
           
           
            $banner_image = $request->file('banner_image');
            $destinationPath2 =  Config::get('constants.uploads.advertise');
            $file_name2=$banner_image->getClientOriginalName();

      
        $file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$banner_image,$file_name2);
      if($file_name2==''){
        MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
         return Redirect::back();
      }

        $Advertise = new Advertise;
        $Advertise->short_text = $input['text'];
        $Advertise->url = $input['url'];
        $Advertise->description = $input['description'];
        $Advertise->image = $file_name2;
     
      /* save the following details */
      if($Advertise->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
}
    
    return view('admin.advertise.form',['page_details'=>$page_details]);
   }


   public function edit_advertise_old(Request $request)
   {
	    $id=base64_decode($request->id);
		 $Advertise_details = Advertise::where('id', $id)->first();
		   
		   
     $page_details=array(
       "Title"=>"Update Advertise",
       "back_url"=>route('advertise'),
	     "Method"=>"2",
       "Box_Title"=>"Update Advertise",
         "Action_route"=>route('edit_advertise', base64_encode($id)),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
                'label'=>'Name',
                'type'=>'text',
                'name'=>'text',
                'id'=>'text',
                'classes'=>'form-control',
                'placeholder'=>'Text',
                'value'=>$Advertise_details->short_text,
                'disabled'=>''
           ),
            "url_field"=>array(
                'label'=>'URL',
                'type'=>'text',
                'name'=>'url',
                'id'=>'url',
                'classes'=>'form-control',
                'placeholder'=>'Url',
                'value'=>$Advertise_details->url,
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
               "banner_description_field"=>array(
							'label'=>'Description',
							'type'=>'textarea',
							'name'=>'description',
							'id'=>'description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'description',
							'value'=>$Advertise_details->description,
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
                  'banner_image'=>$Advertise_details->image
            )
         )
       )
     );

if ($request->isMethod('post')) {
    $input=$request->all();
    $id=base64_decode($request->id);
       $advertise = Advertise::find($id);
          $request->validate([
              'text' => 'max:50',
              'description' => 'max:255',
               'url' => 'max:255',
              'banner_image' => 'mimes:jpeg,bmp,png|min:'.Config::get('constants.size.adimage_min').'|max:'.Config::get('constants.size.adimage_max').''
                
            ]
			);
           
           

		if($input['text']!='')
			$advertise->short_text = $input['text'];
		if($input['url']!='')
			$advertise->url = $input['url'];
		if($input['description']!='')
			$advertise->description = $input['description'];
		
        
        if ($request->hasFile('banner_image')) {
			$banner_image = $request->file('banner_image');
			$destinationPath2 =  Config::get('constants.uploads.advertise');
			$file_name2=$banner_image->getClientOriginalName();

			$file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$banner_image,$file_name2);
			  if($file_name2==''){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
				 return Redirect::back();
			  }
			$advertise->image = $file_name2;
		}
		   
    
     
      /* save the following details */
      if($advertise->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
}
    
    return view('admin.advertise.form',['page_details'=>$page_details]);
	  

   }
   
   public function position_update_advertise(Request $request)
   {
	    $id=base64_decode($request->id);
		
		if ($request->isMethod('post')) {
			$input=$request->all();
				 
			$id=$input['id'];
			$advertise = Advertise::find($id);
				 
			if($input['advertisepos']!='')
				$advertise->advertise_position = $input['advertisepos'];
				
			  /* save the following details */
			  if($advertise->save()){
				  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
			  } else{
				   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			  }
			  return Redirect::back();
		}
   }

 public function position_update_mobile(Request $request)
   {
	    $id=base64_decode($request->id);
		
		if ($request->isMethod('post')) {
			$input=$request->all();
				 
			$id=$input['id'];
			$advertise = Advertise::find($id);
				 
			if($input['advertisepos1']!='')
				$advertise->mobile_position = $input['advertisepos1'];
				
			  /* save the following details */
			  if($advertise->save()){
				  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
			  } else{
				   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
			  }
			  return Redirect::back();
		}
   }
      public function delete_advertise(Request $request)
    {
            $id=base64_decode($request->id);

            $res=Advertise::where('id',$id)
                    ->delete();

            if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

                  return Redirect::back();
    }

    public function advertise_sts(Request $request)
    {
            $id=base64_decode($request->id);
            $sts=base64_decode($request->sts);

            $res=Advertise::where('id',$id)
                    ->update(['status' => ($sts==0) ? 1 : 0]);

            if ($res) {
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
    }

}
