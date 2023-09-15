<?php

namespace App\Http\Controllers\sws_Admin;


use Illuminate\Http\Request;
use App\Brands;
use App\Helpers\CustomFormHelper;
use App\Helpers\MsgHelper;
use App\Helpers\FIleUploadingHelper;
use Redirect;
use Validator;
use URL;
use Config;
class BrandController extends Controller
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
    public function lists(Request $request)
    {
		$parameters=$request->str;
		$status=$request->status;		
		
		$page_details=array(
       "Title"=>"Brands",
       "Box_Title"=>"Brand(s)",
		"search_route"=>URL::to('admin/brand_search'),
		"reset_route"=>route('brands')
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
				
		$Brands=$Brands->orderBy('id', 'DESC')->paginate(100);
		
		return view('admin.brands.list',['Brands'=>$Brands,'page_details'=>$page_details,'status'=>$status]);
		
    }
	public function multi_delete_brand(Request $request)
    {
			$input=$request->all();
			Brands::whereIn('id', $input['brand_id'])
    ->update([
        'isdeleted' =>1
    ]);
	
	  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		   return redirect()->route('brands');
			
    }

   public function add(Request $request){

     $page_details=array(
       "Title"=>"Add Brand",
       "Method"=>"1",
       "back_url"=>route('brands'),
       "Box_Title"=>"Add Brand",
       "Action_route"=>route('addbrand'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'Brand Name',
            'type'=>'text',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control',
            'placeholder'=>'Name',
            'value'=>old('name'),
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
                  'banner_image'=>''
            )
         )
       )
     );

if ($request->isMethod('post')) {
    $input=$request->all();
      
          $request->validate([
              'name' => 'required|unique:brands,name,1,isdeleted|max:255',
              'description' => 'max:60000',
              'logo_image' => 'required|mimes:jpeg,bmp,png|min:'.Config::get('constants.size.logo_min').'|max:'.Config::get('constants.size.logo_max').'',
              'banner_image' => 'required|mimes:jpeg,bmp,png|min:'.Config::get('constants.size.banner_min').'|max:'.Config::get('constants.size.banner_max').''
                
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

      $Brands = new Brands;
      $Brands->name = $input['name'];
       $Brands->description = $input['description'];
      $Brands->logo = $file_name;
      $Brands->banner_image = $file_name2;
     
      /* save the following details */
      if($Brands->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
}
    
    return view('admin.brands.form',['page_details'=>$page_details]);
   }


   public function edit(Request $request)
   {
	    $id=base64_decode($request->id);
		 $Brand_detail = Brands::where('id', $id)->first();
		   
	   $page_details=array(
       "Title"=>"Edit Brand",
      "Method"=>"2",
      "back_url"=>route('brands'),
       "Box_Title"=>"Edit Brand",
       "Action_route"=>route('editbrand', base64_encode($id)),
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
                  'banner_image'=>$Brand_detail->banner_image
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
            'logo_image' => 'mimes:jpeg,bmp,png|min:'.Config::get('constants.size.logo_min').'|max:'.Config::get('constants.size.logo_max').'',
            'banner_image' => 'mimes:jpeg,bmp,png|min:'.Config::get('constants.size.banner_min').'|max:'.Config::get('constants.size.banner_max').''
              
             
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
        

  
   /* save the following details */
   if($Brand->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
  
   return Redirect::back();
	 }
        
		   
		    return view('admin.brands.form',['page_details'=>$page_details ,'Brand_detail'=>$Brand_detail]);

   }


     public function del(Request $request)
    {
            $id=base64_decode($request->id);

            $res=Brands::where('id',$id)
                    ->update(['isdeleted' => 1]);

            if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

                    return Redirect::route('brands');
    }
	
	public function brand_sts(Request $request)
    {
            $id=base64_decode($request->id);
            $sts=base64_decode($request->sts);

            $res=Brands::where('id',$id)
                    ->update(['status' => ($sts==0) ? 1 : 0]);

            if ($res) {
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
    }

}
