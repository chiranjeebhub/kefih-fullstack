<?php

namespace App\Http\Controllers\sws_Admin;

use Illuminate\Http\Request;
use App\UserRoleType;
use App\User;
use App\Helpers\CustomFormHelper;
use App\Helpers\MsgHelper;
use App\Helpers\FIleUploadingHelper;
use Redirect;
use Validator;
use URL;
use Config;
use Hash;
class UserRoleController extends Controller
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
     
       public function edit_user(Request $request)
    {
		  $id=base64_decode($request->id);
		    $user_id=base64_decode($request->user_id);
		 
		 $role_data= UserRoleType::where('id',$id)->first();
		  $user_data= User::where('id',$user_id)->first();
		 
	  $page_details=array(
      "Title"=>"Update User in ".$role_data->user_role_name,
       "Box_Title"=>"Update User in ".$role_data->user_role_name,
       "back_route"=>route('role_users',base64_encode($id)),
       	"Action_route"=>route('edit_user',[base64_encode($id),base64_encode($user_id)] ),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "name_field"=>array(
              'label'=>'Name',
            'type'=>'text',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control',
            'placeholder'=>'Name',
            'value'=>$user_data->username,
			'disabled'=>''
           ),
           "email_field"=>array(
              'label'=>'Email',
            'type'=>'text',
            'name'=>'email',
            'id'=>'email',
            'classes'=>'form-control',
            'placeholder'=>'Email',
            'value'=>$user_data->email,
			'disabled'=>''
           ),
           "phone_field"=>array(
              'label'=>'Phone',
            'type'=>'text',
            'name'=>'phone',
            'id'=>'phone',
            'classes'=>'form-control',
            'placeholder'=>'Phone',
             'value'=>$user_data->phone,
			'disabled'=>''
           ),
		   "password_field"=>array(
                    'label'=>'Password',
                    'type'=>'password',
                    'name'=>'password',
                    'id'=>'password',
                    'classes'=>'form-control',
                    'placeholder'=>'Password',
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
                  'value'=>'Save',
				  'disabled'=>''
            )
         )
       )
     );
	 
	 
	   if ($request->isMethod('post')) {
	        $id=base64_decode($request->id);
	          $user_id=base64_decode($request->user_id);
	        $input=$request->all();
	        
	        
	             $request->validate([
                        'phone' => 'max:500|required|unique:users,email,'.$user_id.',id,isdeleted,0',
                        'email' => 'max:500|required|unique:users,phone,'.$user_id.',id,isdeleted,0',
                        'name' => 'required|max:255'
                          ]);
	            if($input['password']!=''){
	                $res=User::where('id',$user_id)->update(
	                    array(
                        'phone'=>$input['phone'],
                        'username'=>$input['name'],
                        'email'=>$input['email'],
                        'password'=> Hash::make($input['password'])
	                    )
	                    );
	            } else{
	                $res=User::where('id',$user_id)->update(
	                    array(
                        'phone'=>$input['phone'],
                        'username'=>$input['name'],
                        'email'=>$input['email']
	                    )
	                    );
	            }
                          
		  
	
     
      /* save the following details */
        if ($res) {
          MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
        } else {
            MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
        }

                    return Redirect::back();
		 
	   }
		 return view('admin.users_role.admin_users.form',['page_details'=>$page_details,'role_id'=>$id]);
    }
       public function add_user(Request $request)
    {
		  $id=base64_decode($request->id);
		 
		 $role_data= UserRoleType::where('id',$id)->first();
		 
	  $page_details=array(
      "Title"=>"Add User in ".$role_data->user_role_name,
       "Box_Title"=>"Add User in ".$role_data->user_role_name,
       "back_route"=>route('role_users',base64_encode($id)),
       	"Action_route"=>route('add_user',(base64_encode($id))),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "name_field"=>array(
              'label'=>'Name',
            'type'=>'text',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control',
            'placeholder'=>'Name',
            'value'=>old('name'),
			'disabled'=>''
           ),
           "email_field"=>array(
              'label'=>'Email',
            'type'=>'text',
            'name'=>'email',
            'id'=>'email',
            'classes'=>'form-control',
            'placeholder'=>'Email',
            'value'=>old('email'),
			'disabled'=>''
           ),
           "phone_field"=>array(
              'label'=>'Phone',
            'type'=>'text',
            'min'=>'0',
            'maxlength'=>'10',
            'name'=>'phone',
            'id'=>'phone',
            'classes'=>'form-control',
            'placeholder'=>'Phone',
            'value'=>old('phone'),
			'disabled'=>''
           ),
		   "password_field"=>array(
                    'label'=>'Password',
                    'type'=>'password',
                    'name'=>'password',
                    'id'=>'password',
                    'classes'=>'form-control',
                    'placeholder'=>'Password',
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
                  'value'=>'Save',
				  'disabled'=>''
            )
         )
       )
     );
	 
	 
	   if ($request->isMethod('post')) {
	        $id=base64_decode($request->id);
	            $request->validate([
                        'email' => 'required|unique:users,email,1,isdeleted|max:255',
                        'phone' => 'required|unique:users,phone,1,isdeleted|regex:/[0-9]{10}$/',
                        'password' => 'required|max:255',
                        'name' => 'required|max:255'
                          ],[
                            'phone.regex' => 'Phone should be 10 digit number'
                          ]);
                          
		     $input=$request->all();
		     
            $User = new User;
            $User->username = $input['name'];
            $User->phone = $input['phone'];
            $User->email = $input['email'];
            $User->password =   Hash::make($input['password']);
            $User->user_role = $id;
	
     
      /* save the following details */
        if ($User->save()) {
          MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
        } else {
            MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
        }

                    return Redirect::back();
		 
	   }
		 return view('admin.users_role.admin_users.form',['page_details'=>$page_details,'role_id'=>$id]);
    }
      public function role_users(Request $request)
    {
		  $id=base64_decode($request->id);
		 
		 $role_data= UserRoleType::where('id',$id)->first();
		 
	  $page_details=array(
      "Title"=>"Users on  ".$role_data->user_role_name,
       "Box_Title"=>"Users on ".$role_data->user_role_name,
       "back_route"=>route('users_role'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'Role Name',
            'type'=>'text_required',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control name_field',
            'placeholder'=>'Name',
            'value'=>'',
			'disabled'=>''
           ),
		   "text2_field"=>array(
              'label'=>'Role Name',
            'type'=>'text_required',
            'name'=>'name',
            'id'=>'edit_name',
            'classes'=>'form-control name_field',
            'placeholder'=>'Name',
            'value'=>'',
			'disabled'=>''
           ),
		   "submit_button_field"=>array(
				  'label'=>'',
                  'type'=>'submit',
                  'name'=>'submit',
                  'id'=>'submit',
                  'classes'=>'btn btn-danger searchButton',
                  'placeholder'=>'',
                  'value'=>'Save',
				  'disabled'=>'disabled'
            ),
			"hidden_button_field"=>array(
				  'label'=>'',
                  'type'=>'hidden',
                  'name'=>'role_id',
                  'id'=>'user_role_id',
                  'classes'=>'btn btn-danger searchButton',
                  'placeholder'=>'',
                  'value'=>'Save',
				  'disabled'=>'disabled'
            ),
         )
       )
     );
	 
	 
	  
	 $users= User::where('isdeleted', 0)->where('user_role',$id)->get();
	 
	 
	
		
		 return view('admin.users_role.admin_users.list',['users'=>$users,'page_details'=>$page_details,'role_id'=>$id]);
    }
    
     public function delete_users(Request $request)
    {
            $id=base64_decode($request->id);

            $res=User::where('id',$id)
                    ->update(['isdeleted' => 1]);

            if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

                    return Redirect::back();
    }
     public function change_sts_users(Request $request)
    {
                $id=base64_decode($request->id);
                $sts=base64_decode($request->sts);
            $res=User::where('id',$id)
                    ->update(['status' => ($sts==0) ? 1 : 0]);

            if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

                    return Redirect::back();
    }
    public function lists(Request $request)
    {
		
	  $page_details=array(
      "Title"=>"Users Role",
       "Box_Title"=>"Role(s)",
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'Role Name',
            'type'=>'text_required',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control name_field',
            'placeholder'=>'Name',
            'value'=>'',
			'disabled'=>''
           ),
		   "text2_field"=>array(
              'label'=>'Role Name',
            'type'=>'text_required',
            'name'=>'name',
            'id'=>'edit_name',
            'classes'=>'form-control name_field',
            'placeholder'=>'Name',
            'value'=>'',
			'disabled'=>''
           ),
		   "submit_button_field"=>array(
				  'label'=>'',
                  'type'=>'submit',
                  'name'=>'submit',
                  'id'=>'submit',
                  'classes'=>'btn btn-danger searchButton',
                  'placeholder'=>'',
                  'value'=>'Save',
				  'disabled'=>'disabled'
            ),
			"hidden_button_field"=>array(
				  'label'=>'',
                  'type'=>'hidden',
                  'name'=>'role_id',
                  'id'=>'user_role_id',
                  'classes'=>'btn btn-danger searchButton',
                  'placeholder'=>'',
                  'value'=>'Save',
				  'disabled'=>'disabled'
            ),
         )
       )
     );
	 
	 
	  
	 $roles= UserRoleType::where('isdeleted', 0)->get();
	 
	 
	
		
		 return view('admin.users_role.list',['roles'=>$roles,'page_details'=>$page_details]);
    }

public function add(Request $request){
 if ($request->isMethod('post')) {
		 $input=$request->all();
		 
		  $request->validate([
              'name' => 'required|unique:user_role_type,user_role_name,1,isdeleted|max:255'
             ]
			);
			
			$UserRoleType = new UserRoleType;
      $UserRoleType->user_role_name = $input['name'];
     
      /* save the following details */
      if($UserRoleType->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
	  }
}
public function edit(Request $request){
	$input=$request->all();
	$id=$input['role_id'];
       $UserRoleType = UserRoleType::find($id);
         $request->validate([
            'name' => 'required|unique:user_role_type,user_role_name,'.$id.',id,isdeleted,0',
             
         ]
        );
		
		   $UserRoleType->user_role_name = $input['name'];
		   if($UserRoleType->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
  
   return Redirect::back();
}
     public function del(Request $request)
    {
            $id=base64_decode($request->id);

            $res=UserRoleType::where('id',$id)
                    ->update(['isdeleted' => 1]);

            if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

                    return Redirect::route('users_role');
    }

}
