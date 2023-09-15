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
use App\Customer;
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
use App\Helpers\FirebaseHelper;

class NotificationController extends Controller
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
     
     
    
    public function notifications(Request $request)
    {
       
        	$page_details=array(
				"Title"=>"Notification List",
				"Box_Title"=>"Notification(s)",
				"search_route"=>"",
				 "export"=>"",
				"reset_route"=>""
     );
	 
	
	
        $notifications=DB::table('tbl_notification')->where('user_id',null)->orderBy('id', 'DESC')->paginate(100);
    
        return view('admin.extrafeature.notification.list',['notifications'=>$notifications,'page_details'=>$page_details])->with('no', 1);;
    }
	
	
	 public function addNotification(Request $request){
	     
	       
	    
	    
                            
				 $page_details=array(
       "Title"=>"Add Notification",
	     "Method"=>"1",
       "Box_Title"=>"Add Notification",
       "Action_route"=>route('addNotification'),
       "back_url"=>route('notifications'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'Title *',
            'type'=>'text',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control',
            'placeholder'=>'Title',
            'value'=>'',
			'disabled'=>''
           ),
           "description_field"=>array(
              'label'=>'Description *',
            'type'=>'textarea',
            'name'=>'description',
            'id'=>'description',
            'classes'=>'form-control',
            'placeholder'=>'',
            'value'=>'',
			'disabled'=>''
           ),
         
		    "color_file_field"=>array(
			    'label'=>'Image',
                'type'=>'file_special',
                'name'=>'color_image',
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
       )
     );


	 if ($request->isMethod('post')) {
		 $file_name='';
		$input=$request->all();
             $request->validate([
                'name' => 'required|max:255',
                'description' => 'required|max:255',
                'color_image' => 'mimes:jpeg,png'
                
            ],[
              'color_image.mimes'=>'image should be one of (JPEG,PNG,JPG)' 
            ]);
			
			if ($request->hasFile('color_image')) {
                        	$color_image = $request->file('color_image');
                    $destinationPath2 =  Config::get('constants.uploads.notification');
                    $file_name=$color_image->getClientOriginalName();
                    
                    
                    $file_name= FIleUploadingHelper::UploadImage($destinationPath2,$color_image,$file_name);
                    if($file_name==''){
                    MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
                    return Redirect::back();
                    }
                    $file_name=URL::to('uploads/notification/'.$file_name);
			}
		
			
			$error=DB::table('tbl_notification')->insert(
						[
                            'title' =>$input['name'],
                            'body' =>$input['description'],
                            'image' => $file_name,
                            'payload_url' =>'custom',
                            'payload_type' =>'custom',
                            'date' => date('Y-m-d').'T'.date('H:i:s')
						  ]
						);
						
						
   
	
       /* save the following details */
      if($error==0){
          
         
    
				 MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
				 
      } else{
            $notificaion_array=array(
	         'title'=>$input['name'],
	         'body'=>$input['description'],
	         'image'=>$file_name,
	         'payload_url'=>URL::to('/'),
	         'payload_type'=>'custom'
	         );
	         
	         
	   
         FirebaseHelper::CustomNotification($notificaion_array); 
				 MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		 }
      return Redirect::back();
	 }
		return view('admin.extrafeature.notification.form',['page_details'=>$page_details]);
   }
	public function delete_notification(Request $request){
	     $id=base64_decode($request->id);
	    
           $res= DB::table('tbl_notification')->where('id', $id)->delete();
           if($res){
          MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
    		 
      } else{
				 MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);	 
		 }
      return Redirect::back();
	}
	 

   
}
