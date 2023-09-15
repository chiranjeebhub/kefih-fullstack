<?php

namespace App\Http\Controllers\sws_Admin;


use Illuminate\Http\Request;
use App\Whatsmore;
use App\Helpers\CustomFormHelper;
use App\Helpers\MsgHelper;
use App\Helpers\FIleUploadingHelper;
use Redirect;
use Validator;
use URL;
use Config;
class WhatsmoreController extends Controller
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
       "Title"=>"What's More",
       "Box_Title"=>"Whats More(s)",
		"search_route"=>URL::to('admin/whatsmore_search'),
		"reset_route"=>route('whatsmore')
     );
	 
	 if($parameters=='all'){
		 $parameters='';
	 }
	 
	 if($status=='all'){
		 $status='';
	 }
	 
	 $Whatsmore= Whatsmore::where('isdeleted', 0);
		
		if($parameters!=''){
				  $Whatsmore=$Whatsmore
						->where('tbl_whatsmore.name','LIKE',$parameters.'%');
				} 
		if($status!=''){
				  $Whatsmore=$Whatsmore
						->where('whatsmore.status','=',$status);
				} 
				
		$Whatsmore=$Whatsmore->orderBy('id', 'DESC')->paginate(100);
		
		
        return view('admin.whatsmore.list',['Whatsmore'=>$Whatsmore,'page_details'=>$page_details,'status'=>$status]);
    }
	public function multi_delete_whatsmore(Request $request)
    {
			$input=$request->all();
			Whatsmore::whereIn('id', $input['whatsmore_id'])
    ->update([
        'isdeleted' =>1
    ]);
	
	  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		   return redirect()->route('whatsmore');
			
    }

   public function add(Request $request){

     $page_details=array(
       "Title"=>"Add What's More",
	     "Method"=>"1",
       "Box_Title"=>"Add What's More",
       "Action_route"=>route('addwhatsmore'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'Name',
            'type'=>'text',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control',
            'placeholder'=>'Name',
            'value'=>old('name'),
				'disabled'=>''
           ),
             
               "whatsmore_file_field"=>array(
			    'label'=>'Image',
                'type'=>'file_special_imagepreview',
                'name'=>'whatsmore_image',
                'id'=>'file-2',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>'',
                'onchange'=>'image_preview(event)'
                
               ),
               "whatsmore_description_field"=>array(
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
				  'whatsmore_image'=>''
            )
         )
       )
     );

if ($request->isMethod('post')) {
    $input=$request->all();
      
          $request->validate([
              'name' => 'required|unique:tbl_whatsmore,name,1,isdeleted|max:255',
              'description' => 'max:60000',             
              'whatsmore_image' => 'required|mimes:jpeg,bmp,png|min:'.Config::get('constants.size.whats_more_image_min').'|max:'.Config::get('constants.size.whats_more_image_m').''    

                
            ],
			[
				'name.required'=> Config::get('messages.whatsmore.error_msg.name_required'),
				'name.unique'=>Config::get('messages.whatsmore.error_msg.name_unique'),
				'name.max'=>Config::get('messages.whatsmore.error_msg.name_max'),
				'whatsmore_image.required'=>Config::get('messages.whatsmore.error_msg.whatsmore_image_required'),
				'whatsmore_image.mimes'=>Config::get('messages.whatsmore.error_msg.whatsmore_image_mimes'),
			]
			);
           
            $whatsmore_image = $request->file('whatsmore_image');
            $destinationPath2 =  Config::get('constants.uploads.blog_banner');
            $file_name2=$whatsmore_image->getClientOriginalName();

        $file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$whatsmore_image,$file_name2);
		if($file_name2==''){
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
			return Redirect::back();
		}

		$Whatsmore = new Whatsmore;
		$Whatsmore->name = $input['name'];
		$Whatsmore->description = $input['description'];
		$Whatsmore->banner_image = $file_name2;
     
      /* save the following details */
      if($Whatsmore->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
}
    
    return view('admin.whatsmore.form',['page_details'=>$page_details]);
   }


   public function edit(Request $request)
   {
	    $id=base64_decode($request->id);
		 $Whatsmore_detail = Whatsmore::where('id', $id)->first();
		   
	   $page_details=array(
       "Title"=>"Edit Whats more",
	    "Method"=>"2",
       "Box_Title"=>"Edit Whats more",
       "Action_route"=>route('editwhatsmore', base64_encode($id)),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'Name',
            'type'=>'text',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control',
            'placeholder'=>'Name',
            'value'=>$Whatsmore_detail['name'],
				'disabled'=>''
           ),
               "whatsmore_file_field"=>array(
			    'label'=>'Image',
                'type'=>'file_special_imagepreview',
                'name'=>'whatsmore_image',
               'id'=>'file-2',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>'',
                'onchange'=>'image_preview(event)'
                
               ),
               "whatsmore_description_field"=>array(
							'label'=>'Description',
							'type'=>'textarea',
							'name'=>'description',
							'id'=>'description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'description',
							'value'=>$Whatsmore_detail->description,
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
				  'whatsmore_image'=>$Whatsmore_detail->banner_image
            )
         )
       )
     );
	 if ($request->isMethod('post')) {
		 $input=$request->all();
    $id=base64_decode($request->id);
       $Whatsmore = Whatsmore::find($id);
         $request->validate([
            'name' => 'required|unique:tbl_whatsmore,name,'.$id.',id,isdeleted,0',
            'description' => 'max:60000',        
            'whatsmore_image' => 'mimes:jpeg,bmp,png|min:'.Config::get('constants.size.whats_more_image_min').'|max:'.Config::get('constants.size.whats_more_image_max').''    

             
         ],[
				'name.required'=> Config::get('messages.whatsmore.error_msg.name_required'),
				'name.unique'=>Config::get('messages.whatsmore.error_msg.name_unique'),
				'name.max'=>Config::get('messages.whatsmore.error_msg.name_max'),
				'whatsmore_image.required'=>Config::get('messages.whatsmore.error_msg.whatsmore_image_required'),
				'whatsmore_image.mimes'=>Config::get('messages.whatsmore.error_msg.whatsmore_image_mimes'),
			]
        );

    $Whatsmore->name = $input['name'];
    $Whatsmore->description = $input['description'];
	  
      if ($request->hasFile('whatsmore_image')) {
			$whatsmore_image = $request->file('whatsmore_image');
			$destinationPath2 =  Config::get('constants.uploads.blog_banner');
			$file_name2=$whatsmore_image->getClientOriginalName();

			$file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$whatsmore_image,$file_name2);
			if($file_name2==''){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
				return Redirect::back();
			}
        $Whatsmore->banner_image = $file_name2;
    }
        

  
   /* save the following details */
   if($Whatsmore->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
  
   return Redirect::back();
	 }
        
		   
		    return view('admin.whatsmore.form',['page_details'=>$page_details ,'Whatsmore_detail'=>$Whatsmore_detail]);

   }


     public function del(Request $request)
    {
            $id=base64_decode($request->id);

            $res=Whatsmore::where('id',$id)
                    ->delete();

            if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

                    return Redirect::route('whatsmore');
    }
	
	public function whatsmore_sts(Request $request)
    {
            $id=base64_decode($request->id);
            $sts=base64_decode($request->sts);

            $res=Whatsmore::where('id',$id)
                    ->update(['status' => ($sts==0) ? 1 : 0]);

            if ($res) {
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
    }

}
