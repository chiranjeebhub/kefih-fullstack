<?php

namespace App\Http\Controllers\sws_Admin;


use Illuminate\Http\Request;
use App\Materials;
use App\Helpers\CustomFormHelper;
use App\Helpers\MsgHelper;
use App\Helpers\FIleUploadingHelper;
use Redirect;
use Validator;
use URL;
use Config;
class MaterialsController extends Controller
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
       "Title"=>"Materials",
       "Box_Title"=>"Material(s)",
		"search_route"=>URL::to('admin/material_search'),
		"reset_route"=>route('materials')
     );
	 
	 
	 $Materials= Materials::where('isdeleted', 0);
		
		if($parameters=='all')
		{
			$parameters='';
		}
		if($status=='all')
		{
			$status='';
		}
		
		if($parameters!=''){
		  $Materials=$Materials
				->where('materials.name','LIKE',$parameters.'%');
		} 
		if($status!=''){
		  $Materials=$Materials
				->where('materials.status','=',$status);
		} 
		
		$Materials=$Materials->orderBy('id', 'DESC')->paginate(100);
		
        return view('admin.materials.list',['Materials'=>$Materials,'page_details'=>$page_details,'status'=>$status]);
    }
	public function multi_delete_material(Request $request)
    {
			$input=$request->all();
			Materials::whereIn('id', $input['material_id'])
    ->update([
        'isdeleted' =>1
    ]);
	
	  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		   return redirect()->route('materials');
			
    }

   public function add(Request $request){

     $page_details=array(
       "Title"=>"Add Materials",
       "back_url"=>route('materials'),
	     "Method"=>"1",
       "Box_Title"=>"Add Material",
       "Action_route"=>route('addmaterial'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'Material Name',
            'type'=>'text',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control',
            'placeholder'=>'Name',
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
            )
         )
       )
     );

if ($request->isMethod('post')) {
    $input=$request->all();
      
          $request->validate([
              'name' => 'required|unique:materials,name,1,isdeleted|max:255',
              'input_type' => 'max:60000'
            ],
			[
				'name.required'=> Config::get('messages.material.error_msg.name_required'),
				'name.unique'=>Config::get('messages.material.error_msg.name_unique'),
				'name.max'=>Config::get('messages.material.error_msg.name_max'),
			]
			);
       
      $Materials = new Materials;
      $Materials->name = $input['name'];
      
      /* save the following details */
      if($Materials->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
}
    
    return view('admin.materials.form',['page_details'=>$page_details]);
   }


   public function edit(Request $request)
   {
	    $id=base64_decode($request->id);
		 $Material_detail = Materials::where('id', $id)->first();
		   
	   $page_details=array(
       "Title"=>"Edit Material",
       "back_url"=>route('materials'),
	    "Method"=>"2",
       "Box_Title"=>"Edit Material",
       "Action_route"=>route('editmaterial', base64_encode($id)),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'Material Name',
            'type'=>'text',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control',
            'placeholder'=>'Name',
            'value'=>$Material_detail['name'],
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
       $Material = Materials::find($id);
         $request->validate([
            'name' => 'required|unique:materials,name,'.$id.',id,isdeleted,0'
             
         ],[
				'name.required'=> Config::get('messages.material.error_msg.name_required'),
				'name.unique'=>Config::get('messages.material.error_msg.name_unique'),
				'name.max'=>Config::get('messages.material.error_msg.name_max'),
			]
        );

         
   
    $Material->name = $input['name'];
    
   /* save the following details */
   if($Material->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
  
   return Redirect::back();
	 }
           
		return view('admin.materials.form',['page_details'=>$page_details ,'Material_detail'=>$Material_detail]);

   }


     public function del(Request $request)
    {
            $id=base64_decode($request->id);

            $res=Materials::where('id',$id)
                    ->update(['isdeleted' => 1]);

            if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

                    return Redirect::route('materials');
    }
	
	public function material_sts(Request $request)
    {
            $id=base64_decode($request->id);
            $sts=base64_decode($request->sts);

            $res=Materials::where('id',$id)
                    ->update(['status' => ($sts==0) ? 1 : 0]);

            if ($res) {
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
    }

}
