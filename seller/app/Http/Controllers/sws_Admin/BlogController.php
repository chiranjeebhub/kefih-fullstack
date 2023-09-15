<?php

namespace App\Http\Controllers\sws_Admin;


use Illuminate\Http\Request;
use App\Blogs;
use App\Helpers\CustomFormHelper;
use App\Helpers\MsgHelper;
use App\Helpers\FIleUploadingHelper;
use Redirect;
use Validator;
use URL;
use Config;
class BlogController extends Controller
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
       "Title"=>"Blogs",
       "Box_Title"=>"Blog(s)",
		"search_route"=>URL::to('admin/blog_search'),
		"reset_route"=>route('blogs')
     );
	 
	 if($parameters=='all'){
		 $parameters='';
	 }
	 
	 if($status=='all'){
		 $status='';
	 }
	 
	 $Blogs= Blogs::where('isdeleted', 0);
		
		if($parameters!=''){
				  $Blogs=$Blogs
						->where('blogs.name','LIKE',$parameters.'%');
				} 
		if($status!=''){
				  $Blogs=$Blogs
						->where('blogs.status','=',$status);
				} 
				
		$Blogs=$Blogs->orderBy('id', 'DESC')->paginate(100);
		
		
        return view('admin.blogs.list',['Blogs'=>$Blogs,'page_details'=>$page_details,'status'=>$status]);
    }
	public function multi_delete_blog(Request $request)
    {
			$input=$request->all();
			Blogs::whereIn('id', $input['blog_id'])
    ->update([
        'isdeleted' =>1
    ]);
	
	  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		   return redirect()->route('blogs');
			
    }

   public function add(Request $request){

     $page_details=array(
       "Title"=>"Add Blog",
	     "Method"=>"1",
       "Box_Title"=>"Add Blog",
       "Action_route"=>route('addblog'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'Blog Name',
            'type'=>'text',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control',
            'placeholder'=>'Name',
            'value'=>old('name'),
				'disabled'=>''
           ),
             
               "blog_file_field"=>array(
			          'label'=>'Banner Image',
                'type'=>'file_special_imagepreview',
                'name'=>'blog_image',
                'id'=>'file-2',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>'',
                'onchange'=>'image_preview(event)'
                
               ),
               "blog_description_field"=>array(
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
				  'blog_image'=>''
            )
         )
       )
     );

if ($request->isMethod('post')) {
    $input=$request->all();
      
          $request->validate([
              'name' => 'required|unique:blogs,name,1,isdeleted|max:255',
              'description' => 'max:60000',       
              'blog_image' => 'required|mimes:jpeg,bmp,png|min:'.Config::get('constants.size.banner_min').'|max:'.Config::get('constants.size.banner_max').''    

                
            ],
			[
				'name.required'=> Config::get('messages.blog.error_msg.name_required'),
				'name.unique'=>Config::get('messages.blog.error_msg.name_unique'),
				'name.max'=>Config::get('messages.blog.error_msg.name_max'),
				'blog_image.required'=>Config::get('messages.blog.error_msg.blog_image_required'),
				'blog_image.mimes'=>Config::get('messages.blog.error_msg.blog_image_mimes'),
			]
			);
           
            $blog_image = $request->file('blog_image');
            $destinationPath2 =  Config::get('constants.uploads.blog_banner');
            $file_name2=$blog_image->getClientOriginalName();

        $file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$blog_image,$file_name2);
		if($file_name2==''){
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
			return Redirect::back();
		}

		$Blogs = new Blogs;
		$Blogs->name = $input['name'];
		$Blogs->description = $input['description'];
		$Blogs->banner_image = $file_name2;
     
      /* save the following details */
      if($Blogs->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
}
    
    return view('admin.blogs.form',['page_details'=>$page_details]);
   }


   public function edit(Request $request)
   {
	    $id=base64_decode($request->id);
		 $Blog_detail = Blogs::where('id', $id)->first();
		   
	   $page_details=array(
       "Title"=>"Edit Blog",
	    "Method"=>"2",
       "Box_Title"=>"Edit Blog",
       "Action_route"=>route('editblog', base64_encode($id)),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'Blog Name',
            'type'=>'text',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control',
            'placeholder'=>'Name',
            'value'=>$Blog_detail['name'],
				'disabled'=>''
           ),
               "blog_file_field"=>array(
			    'label'=>'Blog Image',
                'type'=>'file_special_imagepreview',
                'name'=>'blog_image',
               'id'=>'file-2',
                'classes'=>'inputfile inputfile-4',
                'placeholder'=>'',
                'value'=>'',
                'onchange'=>'image_preview(event)'
                
               ),
               "blog_description_field"=>array(
							'label'=>'Description',
							'type'=>'textarea',
							'name'=>'description',
							'id'=>'description',
							'classes'=>'ckeditor form-control',
							'placeholder'=>'description',
							'value'=>$Blog_detail->description,
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
				  'blog_image'=>$Blog_detail->banner_image
            )
         )
       )
     );
	 if ($request->isMethod('post')) {
		 $input=$request->all();
    $id=base64_decode($request->id);
       $Blog = Blogs::find($id);
         $request->validate([
            'name' => 'required|unique:blogs,name,'.$id.',id,isdeleted,0',
            'description' => 'max:60000',     
            'blog_image' => 'mimes:jpeg,bmp,png|min:'.Config::get('constants.size.banner_min').'|max:'.Config::get('constants.size.banner_max').''    
            
         ],[
				'name.required'=> Config::get('messages.blog.error_msg.name_required'),
				'name.unique'=>Config::get('messages.blog.error_msg.name_unique'),
				'name.max'=>Config::get('messages.blog.error_msg.name_max'),
				'blog_image.required'=>Config::get('messages.blog.error_msg.blog_image_required'),
				'blog_image.mimes'=>Config::get('messages.blog.error_msg.blog_image_mimes'),
			]
        );

    $Blog->name = $input['name'];
    $Blog->description = $input['description'];
	  
      if ($request->hasFile('blog_image')) {
			$blog_image = $request->file('blog_image');
			$destinationPath2 =  Config::get('constants.uploads.blog_banner');
			$file_name2=$blog_image->getClientOriginalName();

			$file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$blog_image,$file_name2);
			if($file_name2==''){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
				return Redirect::back();
			}
        $Blog->banner_image = $file_name2;
    }
        

  
   /* save the following details */
   if($Blog->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
  
   return Redirect::back();
	 }
        
		   
		    return view('admin.blogs.form',['page_details'=>$page_details ,'Blog_detail'=>$Blog_detail]);

   }


     public function del(Request $request)
    {
            $id=base64_decode($request->id);

            $res=Blogs::where('id',$id)
                    ->delete();

            if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

                    return Redirect::route('blogs');
    }
	
	public function blog_sts(Request $request)
    {
            $id=base64_decode($request->id);
            $sts=base64_decode($request->sts);

            $res=Blogs::where('id',$id)
                    ->update(['status' => ($sts==0) ? 1 : 0]);

            if ($res) {
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
    }

}
