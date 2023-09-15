<?php

namespace App\Http\Controllers\sws_Admin;

use Illuminate\Http\Request;
use App\Modules;
use App\UserRoleType;
use App\Permissions;
use App\Helpers\MsgHelper;
use Redirect;
use Validator;
use DB;
use Config;
class PermissionController extends Controller
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
    public function index(Request $request)
    {

$page_details=array(
       "Title"=>"Permissions",
       "Box_Title"=>"Permissions(s)"
     );
        $Rights_list = Permissions::select('permissions.user_role_id', DB::raw('count(*) as total'), DB::raw('user_role_type.user_role_name'))
                     ->join('user_role_type', 'user_role_type.id', '=', 'permissions.user_role_id')
                  ->where('permissions.isdeleted', 0)
                  ->where('user_role_type.isdeleted', 0)
                 ->groupBy('permissions.user_role_id', 'user_role_type.user_role_name')
                 ->get();
		
		 return view('admin.permission.list',['Rights_list'=>$Rights_list,'page_details'=>$page_details]);
	
    }
    
    public function add_permissions(Request $request)
    { 
			$Modules = Modules::where('isdeleted', 0)->get();
			$UserRoles = UserRoleType::select('id','user_role_name as name')->where('isdeleted', 0)->get();
			
			
		
 $page_details=array(
       "Title"=>"Permissions",
	     "Method"=>"1",
       "Box_Title"=>"Add Permissions",
       "Action_route"=>route('addpermissions'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'User Role',
            'type'=>'select',
            'name'=>'user_role',
            'id'=>'user_role',
            'classes'=>'custom-select form-control',
            'placeholder'=>'Name',
			'value'=>$UserRoles,
			'disabled'=>'',
			'selected'=>''
           ),
		   "checkbox_field"=>array(
              'label'=>'Modules',
            'type'=>'checkbox_array_dynamic_name',
            'classes'=>'custom-select form-control',
			'checked'=>'',
            'placeholder'=>'Name',
            'value'=>$Modules
           ),
               "submit_button_field"=>array(
				  'label'=>'',
                  'type'=>'submit',
                  'name'=>'submit',
                  'id'=>'submit',
                  'classes'=>'btn btn-danger',
                  'placeholder'=>'',
                  'value'=>'Save'
            ),"set_value"=>array(
					'User_role_name'=>'',
					'check_box_data'=>array(
						'label'=>'',
						'type'=>'checkbox_array_dynamic_name',
						'checked'=>'',
						'classes'=>'custom-select form-control',
						'placeholder'=>'Name',
						'value'=>array()
           )   
            )
         )
       )
     );
	 
	  if ($request->isMethod('post')) {
		  
		   $input=$request->all();
        $request->validate([
            'user_role' => 'required|unique:permissions,user_role_id,1,isdeleted|max:255',
          
             
         ],[
          'user_role.required' => 'User role is required',
          'user_role.unique' => 'User role is already exists',
         ]
        );
       
      
      
        $output = array_slice($input, 2); 
        if(sizeof($output)==0){
           MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
           return Redirect::back();
        }
      
    
        $whole_array=array();
        foreach($output as $key=>$val){
 $single_array= array('user_role_id'=>$input['user_role'], 'module_id'=> explode("_",$key)[1]);
           array_push($whole_array,$single_array);


        }

         $res=Permissions::insert($whole_array); 

         if($res){
             MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
          } else{
             MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
          }
         return Redirect::back();
		 
	  }
        
		
		 return view('admin.permission.form',['Modules'=>$Modules,'UserRoles'=>$UserRoles,'page_details'=>$page_details]);
	
    }

      
      public function edit_permissions(Request $request)
      {
              $id=base64_decode($request->id);

              $userModulesid = Permissions::select('module_id')->where('isdeleted', 0)->where('user_role_id',$id)->get()->toarray();
              $allModules = Modules::where('isdeleted', 0)->whereNotIn('id', $userModulesid)->get();
              $UserRole = UserRoleType::where('isdeleted', 0)->where('id', $id)->first();

              $userModules=Permissions::select('permissions.module_id as id', 'modules.name')
                     ->join('modules', 'modules.id', '=', 'permissions.module_id')
                  ->where('permissions.isdeleted', 0)
                  ->where('permissions.user_role_id',$id)->get();
				  
				
			$UserRoles = UserRoleType::where('isdeleted', 0)->get();
			
 $page_details=array(
       "Title"=>"Edit Permissions",
	     "Method"=>"2",
       "Box_Title"=>"Edit Permissions",
       "Action_route"=>route('editpermissions', base64_encode($UserRole->id)),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'User Role',
            'type'=>'select',
            'name'=>'user_role',
            'id'=>'user_role',
            'classes'=>'custom-select form-control',
            'placeholder'=>'Name',
            'value'=>$UserRoles
           ),
		   "checkbox_field"=>array(
              'label'=>'Modules',
            'type'=>'checkbox_array_dynamic_name',
            'classes'=>'custom-select form-control',
			'checked'=>'',
            'placeholder'=>'Name',
            'value'=>$allModules
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
			  "set_value"=>array(
					'User_role_name'=>$UserRole->user_role_name,
					'check_box_data'=>array(
						'label'=>'',
						'type'=>'checkbox_array_dynamic_name',
						'classes'=>'custom-select form-control',
						'checked'=>'checked',
						'placeholder'=>'Name',
						'value'=>$userModules
           )   
            )
         )
       )
     );
	 
	  if ($request->isMethod('post')) {
		  
		    $input=$request->all();
        $id=base64_decode($request->id);
      
      
        $output = array_slice($input, 1); 
       

        if(sizeof($output)==0){
            MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
           return Redirect::back();
        }
      
    
        $whole_array=array();
        Permissions::where('user_role_id',$id)
                      ->delete();

        foreach($output as $key=>$val){
 $single_array= array('user_role_id'=>$id, 'module_id'=> explode("_",$key)[1]);
           array_push($whole_array,$single_array);


        }

         $res=Permissions::insert($whole_array); 

         if($res){
				MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
          } else{
             MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
          
          }
          return Redirect::back();
	  }
        
            
              return view('admin.permission.form',[
                  'UserRole'=>$UserRole,
                  'allModules'=>$allModules,
                  'userModules'=>$userModules,
				  'page_details'=>$page_details
                  
                  ]);
           
   
      }


      public function delete_permissions(Request $request)
      {
              $id=base64_decode($request->id);
  
              $res=Permissions::where('user_role_id',$id)
                      ->delete();
  
              if ($res) {
                  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
              } else {
                  MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
              }
  
                      return Redirect::back();
      }
}
