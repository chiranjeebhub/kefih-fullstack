<?php

namespace App\Http\Controllers\sws_Admin;


use Illuminate\Http\Request;
use App\Testimonials;
use App\Helpers\CustomFormHelper;
use App\Helpers\MsgHelper;
use App\Helpers\FIleUploadingHelper;
use Redirect;
use Validator;
use URL;
use Config;
class TestimonialController extends Controller
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
		   "Title"=>"Testimonials",
		   "Box_Title"=>"Testimonial(s)",
			"search_route"=>URL::to('admin/testimonial_search'),
			"reset_route"=>route('testimonials')
		 );
	 
	 if($parameters=='all'){
		 $parameters='';
	 }
	 
	 if($status=='all'){
		 $status='';
	 }
	 
	 $Testimonials= Testimonials::where('isdeleted', 0);
		
		if($parameters!=''){
				  $Testimonials=$Testimonials
						->where('testimonials.name','LIKE',$parameters.'%');
				} 
		if($status!=''){
				  $Testimonials=$Testimonials
						->where('testimonials.status','=',$status);
				} 
				
		$Testimonials=$Testimonials->orderBy('id', 'DESC')->paginate(100);
		
		
        return view('admin.testimonials.list',['Testimonials'=>$Testimonials,'page_details'=>$page_details,'status'=>$status]);
    }
	public function multi_delete_testimonial(Request $request)
    {
			$input=$request->all();
			Testimonials::whereIn('id', $input['testimonial_id'])
    ->update([
        'isdeleted' =>1
    ]);
	
	  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		   return redirect()->route('testimonials');
			
    }

   public function add(Request $request){

     $page_details=array(
       "Title"=>"Add Testimonial",
       "back_url"=>route('testimonials'),
	     "Method"=>"1",
       "Box_Title"=>"Add Testimonial",
       "Action_route"=>route('addtestimonial'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'User Name',
            'type'=>'text',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control',
            'placeholder'=>'Name',
            'value'=>old('name'),
				'disabled'=>''
           ),
             
               "testimonial_file_field"=>array(
			    'label'=>'User Image',
                'type'=>'file_special_imagepreview',
                'name'=>'testimonial_image',
                'id'=>'file-2',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>'',
                'onchange'=>'image_preview(event)'
                
               ),
               "testimonial_description_field"=>array(
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
				  'testimonial_image'=>''
            )
         )
       )
     );

if ($request->isMethod('post')) {
    $input=$request->all();
      
          $request->validate([
              'name' => 'required|unique:testimonials,name,1,isdeleted|max:255',
              'description' => 'max:60000',
              'testimonial_image' => 'required|mimes:jpeg,bmp,png|min:'.Config::get('constants.size.testimonial_image_min').'|max:'.Config::get('constants.size.testimonial_image_max').''
                
            ],
			[
				'name.required'=> Config::get('messages.testimonial.error_msg.name_required'),
				'name.unique'=>Config::get('messages.testimonial.error_msg.name_unique'),
				'name.max'=>Config::get('messages.testimonial.error_msg.name_max'),
				'testimonial_image.required'=>Config::get('messages.testimonial.error_msg.testimonial_image_required'),
				'testimonial_image.mimes'=>Config::get('messages.testimonial.error_msg.testimonial_image_mimes'),
			]
			);
           
            $testimonial_image = $request->file('testimonial_image');
            $destinationPath2 =  Config::get('constants.uploads.testimonial_banner');
            $file_name2=$testimonial_image->getClientOriginalName();

        $file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$testimonial_image,$file_name2);
		if($file_name2==''){
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
			return Redirect::back();
		}

		$Testimonials = new Testimonials;
		$Testimonials->name = $input['name'];
		$Testimonials->description = $input['description'];
		$Testimonials->banner_image = $file_name2;
     
      /* save the following details */
      if($Testimonials->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
}
    
    return view('admin.testimonials.form',['page_details'=>$page_details]);
   }


   public function edit(Request $request)
   {
	    $id=base64_decode($request->id);
		 $Testimonial_detail = Testimonials::where('id', $id)->first();
		   
	   $page_details=array(
       "Title"=>"Edit Testimonial",
       "back_url"=>route('testimonials'),
	    "Method"=>"2",
       "Box_Title"=>"Edit Testimonial",
       "Action_route"=>route('edittestimonial', base64_encode($id)),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'User Name',
            'type'=>'text',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control',
            'placeholder'=>'Name',
            'value'=>$Testimonial_detail['name'],
				'disabled'=>''
           ),
               "testimonial_file_field"=>array(
			    'label'=>'User Image',
                'type'=>'file_special_imagepreview',
                'name'=>'testimonial_image',
               'id'=>'file-2',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>'',
                'onchange'=>'image_preview(event)'
                
               ),
               "testimonial_description_field"=>array(
							'label'=>'Description',
							'type'=>'textarea',
							'name'=>'description',
							'id'=>'description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'description',
							'value'=>$Testimonial_detail->description,
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
				  'testimonial_image'=>$Testimonial_detail->banner_image
            )
         )
       )
     );
	 if ($request->isMethod('post')) {
		 $input=$request->all();
    $id=base64_decode($request->id);
       $Testimonial = Testimonials::find($id);
         $request->validate([
            'name' => 'required|unique:testimonials,name,'.$id.',id,isdeleted,0',
             'description' => 'max:60000',
            'testimonial_image' => 'mimes:jpeg,bmp,png|min:'.Config::get('constants.size.testimonial_image_min').'|max:'.Config::get('constants.size.testimonial_image_max').''
             
         ],[
				'name.required'=> Config::get('messages.testimonial.error_msg.name_required'),
				'name.unique'=>Config::get('messages.testimonial.error_msg.name_unique'),
				'name.max'=>Config::get('messages.testimonial.error_msg.name_max'),
				'testimonial_image.required'=>Config::get('messages.testimonial.error_msg.testimonial_image_required'),
				'testimonial_image.mimes'=>Config::get('messages.testimonial.error_msg.testimonial_image_mimes'),
			]
        );

    $Testimonial->name = $input['name'];
    $Testimonial->description = $input['description'];
	  
      if ($request->hasFile('testimonial_image')) {
			$testimonial_image = $request->file('testimonial_image');
			$destinationPath2 =  Config::get('constants.uploads.testimonial_banner');
			$file_name2=$testimonial_image->getClientOriginalName();

			$file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$testimonial_image,$file_name2);
			if($file_name2==''){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
				return Redirect::back();
			}
        $Testimonial->banner_image = $file_name2;
    }
        

  
   /* save the following details */
   if($Testimonial->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
  
   return Redirect::back();
	 }
        
		   
		    return view('admin.testimonials.form',['page_details'=>$page_details ,'Testimonial_detail'=>$Testimonial_detail]);

   }


     public function del(Request $request)
    {
            $id=base64_decode($request->id);

            $res=Testimonials::where('id',$id)
                    ->delete();

            if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

           return Redirect::route('testimonials');
    }
	
	public function testimonial_sts(Request $request)
    {
            $id=base64_decode($request->id);
            $sts=base64_decode($request->sts);

            $res=Testimonials::where('id',$id)
                    ->update(['status' => ($sts==0) ? 1 : 0]);

            if ($res) {
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
    }

}
