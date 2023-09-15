<?php

namespace App\Http\Controllers\sws_Admin;


use Illuminate\Http\Request;
use App\Filters;
use App\Helpers\CustomFormHelper;
use App\Helpers\MsgHelper;
use App\Helpers\FIleUploadingHelper;
use Redirect;
use Validator;
use URL;
use DB;
use Config;
class FiltersController extends Controller
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
       "Title"=>"Filters",
       "Box_Title"=>"Filter(s)",
		"search_route"=>URL::to('admin/filter_search'),
		"reset_route"=>route('filters')
     );
	 
	 
	 $Filters= Filters::where('isdeleted', 0);
		
		if($parameters=='all')
		{
			$parameters='';
		}
		if($status=='all')
		{
			$status='';
		}
		
		if($parameters!=''){
		  $Filters=$Filters
				->where('filters.name','LIKE',$parameters.'%');
		} 
		if($status!=''){
		  $Filters=$Filters
				->where('filters.status','=',$status);
		} 
		
		$Filters=$Filters->orderBy('id', 'DESC')->paginate(100);
		
		
        return view('admin.filters.list',['Filters'=>$Filters,'page_details'=>$page_details,'status'=>$status]);
    }
	public function multi_delete_filter(Request $request)
    {
			$input=$request->all();
			Filters::whereIn('id', $input['filter_id'])
    ->update([
        'isdeleted' =>1
    ]);
	
	  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		   return redirect()->route('filters');
			
    }


   public function add(Request $request){

     $page_details=array(
       "Title"=>"Add Filters",
       "back_url"=>route('filters'),
	     "Method"=>"1",
       "Box_Title"=>"Add Filter",
       "Action_route"=>route('addfilter'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'Filter Name',
            'type'=>'text',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control',
            'placeholder'=>'Name',
            'value'=>'',
				'disabled'=>''
           ),
               "filter_description_field"=>array(
							'label'=>'Filter Values',
							'type'=>'textarea',
							'name'=>'input_type',
							'id'=>'input_type',
							'classes'=>'form-control',
							'placeholder'=>'Filter Values',
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
              'name' => 'required|unique:filters,name,1,isdeleted|max:255',
              'input_type' => 'required|max:60000'
            ],
			[
				'name.required'=> Config::get('messages.filter.error_msg.name_required'),
				'name.unique'=>Config::get('messages.filter.error_msg.name_unique'),
				'name.max'=>Config::get('messages.filter.error_msg.name_max'),
			]
			);
       
      $Filters = new Filters;
      $Filters->name = $input['name'];
      /* save the following details */
      if($Filters->save()){
          $filter_id=$Filters->id;
         $values=explode(',',$input['input_type']);
         $whole_values=array();
         foreach($values as $row){
             array_push($whole_values,array(
                 'filter_id'=>$filter_id,
                 'filter_value'=>$row
                 ));
         }
            DB::table('filter_values')->insert($whole_values);
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      return Redirect::back();
}
    
    return view('admin.filters.form',['page_details'=>$page_details]);
   }


 public function assign_cat_to_filter(Request $request)
   {
       
        if ($request->isMethod('post')) {
                $input=$request->all();
                $id=base64_decode($request->id);
            DB::table('filters_category')->where('filter_id',$id)->delete();
                 $request->validate([
                'cat.*' => 'required'
             ]
            );
            
            	if (array_key_exists("cat",$input))
		{    
				$data=array();
		
			foreach($input['cat'] as $row){
				array_push($data,array('filter_id'=>$id, 'cat_id'=> $row));
			}
			$res=DB::table('filters_category')->insert($data);
		} 
		
		
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
     
  
   return Redirect::back();
        }
	    $id=base64_decode($request->id);
		 $Filter_detail = Filters::where('id', $id)->first();
		 
		 $cats=DB::table('filters_category')->select('cat_id')->where('filter_id',$id)->get();
		 $cats_data=array();
		 foreach($cats as $row){
		     array_push($cats_data,$row->cat_id);
		 }
		
		 
		 $page_details=array(
       "Title"=>"Assign Category",
	    "Method"=>"2",
       "Box_Title"=>"Assign Category to ".$Filter_detail['name'],
       "Action_route"=>route('assign_cat_to_filter', base64_encode($id)),
       "cats"=>$cats_data,
       "Form_data"=>array(

         "Form_field"=>array(
           
               "filter_description_field"=>array(
							'label'=>'Filter Values',
							'type'=>'textarea',
							'name'=>'input_type',
							'id'=>'input_type',
							'classes'=>'form-control',
							'placeholder'=>'Filter Values',
							'value'=>$Filter_detail->input_type,
							'disabled'=>''),
							
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
     
     return view('admin.filters.assign_cat',['page_details'=>$page_details ,'Filter_detail'=>$Filter_detail]);
   }
   public function update_filter_value(Request $request){
       
    $id=base64_decode($request->id);
    
    $Filter_detail = Filters::where('id', $id)->first();
    $filter_values=DB::table('filter_values')->where('filter_id',$id)->get();
    
        $page_details=array(
        "Title"=>"Filters Values",
        "Method"=>"2",
        "Box_Title"=>"Filters Values ( ".$Filter_detail->name." )",
        "Action_route"=>'',
        "Data"=>array(
            'filter_data'=>$Filter_detail,
            'filter_values'=>$filter_values
            )
        );
        
       // var_dump($filter_values);die;
        
        return view('admin.filters.filter_values_list',['page_details'=>$page_details]);
		 
}
   public function edit(Request $request)
   {
	    $id=base64_decode($request->id);
		 $Filter_detail = Filters::where('id', $id)->first();
		   
	   $page_details=array(
       "Title"=>"Edit Filter",
       "back_url"=>route('filters'),
	    "Method"=>"2",
       "Box_Title"=>"Edit Filter",
       "Action_route"=>route('editfilter', base64_encode($id)),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'Filter Name',
            'type'=>'text',
            'name'=>'name',
            'id'=>'name',
            'classes'=>'form-control',
            'placeholder'=>'Name',
            'value'=>$Filter_detail['name'],
				'disabled'=>''
           ),
               "filter_description_field"=>array(
							'label'=>'Filter Values',
							'type'=>'textarea',
							'name'=>'input_type',
							'id'=>'input_type',
							'classes'=>'form-control',
							'placeholder'=>'Filter Values',
							'value'=>$Filter_detail->input_type,
							'disabled'=>''),
							
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
       $Filter = Filters::find($id);
         $request->validate([
            'name' => 'required|unique:filters,name,'.$id.',id,isdeleted,0'
             
         ],[
				'name.required'=> Config::get('messages.filter.error_msg.name_required'),
				'name.unique'=>Config::get('messages.filter.error_msg.name_unique'),
				'name.max'=>Config::get('messages.filter.error_msg.name_max'),
			]
        );

         
   
    $Filter->name = $input['name'];
   
   /* save the following details */
   if($Filter->save()){
		  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
  
   return Redirect::back();
	 }
           
		return view('admin.filters.form',['page_details'=>$page_details ,'Filter_detail'=>$Filter_detail]);

   }


public function filterValue(Request $request)
    {
        
         if ($request->isMethod('post')) {
        $request->validate([
        'filter_value' => 'required'
        ]
        );
		  $input=$request->all();
		  if($input['mode']==1){
		        $res=DB::table('filter_values')->where('id',$input['filter_value_id'])
                    ->update(array(
                        'filter_value'=>$input['filter_value']
                        ));
                          if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

                    
		      
		  } else{
		     $res=DB::table('filter_values')
                    ->insert(array(
                'filter_value'=>$input['filter_value'],
                'filter_id'=>$input['filter_value_id']
                        ));
                          if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            } 
		  }
		   return Redirect::back();
         }
       
    }
 public function deletefilterValue(Request $request)
    {
            $id=base64_decode($request->id);

            $res=DB::table('filter_values')->where('id',$id)
                    ->delete();
                    
                      $res=DB::table('product_filters')->where('filters_input_value',$id)
                    ->delete();

            if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

                     return Redirect::back();
    }

     public function del(Request $request)
    {
            $id=base64_decode($request->id);

            $res=Filters::where('id',$id)->delete();
                $res=DB::table('product_filters')->where('filters_id',$id)
                ->delete();
            if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

                    return Redirect::route('filters');
    }
	
	public function filter_sts(Request $request)
    {
            $id=base64_decode($request->id);
            $sts=base64_decode($request->sts);

            $res=Filters::where('id',$id)
                    ->update(['status' => ($sts==0) ? 1 : 0]);

            if ($res) {
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
    }

}
