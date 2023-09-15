<?php

namespace App\Http\Controllers\sws_Admin;

use Illuminate\Http\Request;
use App\Helpers\MsgHelper;
use App\Colors;
use App\Sizes;
use Redirect;
use Validator;
use Config;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ColorExport;
use App\Exports\SizeExport;
use DB;
use URL;

use App\Helpers\FIleUploadingHelper;


class AttributeController extends Controller
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
     
     public function color_export(Request $request)
    {
		$parameters=$request->str;
		$status=$request->status;
	
		if($parameters=='all')
		{
			$parameters='';
		}
		
		if($status=='all')
		{
			$status='';
		}
		
		return Excel::download(new ColorExport($parameters,$status), 'Colors'.date('d-m-Y H:i:s').'.csv');	
    }
    public function size_export(Request $request)
    {
		$parameters=$request->str;
		$status=$request->status;
	
		if($parameters=='all')
		{
			$parameters='';
		}
		
		if($status=='all')
		{
			$status='';
		}
		return Excel::download(new SizeExport($parameters,$status), 'Sizes'.date('d-m-Y H:i:s').'.csv');	
    }
     public function materirals(Request $request){
         
     }
     public function updateAttributes(Request $request){
		 $cat_id=base64_decode($request->cat_id);
			$attribute_type=base64_decode($request->type);
		 
		  if ($request->isMethod('post')) {
			  DB::table('categories_attributes')
					->where('categories_attributes.attribute_type',$attribute_type)
					->where('categories_attributes.category_id',$cat_id)
					->delete();
			  $input=$request->all();
			  	if (array_key_exists("arrt_id",$input))
		{    
				$data=array();
					
			foreach($input['arrt_id'] as $row){
				array_push($data,
				array(
				'category_id'=>$cat_id,
				'attribute_type'=> $attribute_type,
				'attrinbute_id'=> $row
				));
			}
			$res=DB::table('categories_attributes')->insert($data);
		} else{
			
			
		}
		  }
			
			
				
		
		if($attribute_type==0){
			$modal_header='Colors';
			$attrs = DB::table('categories_attributes')
		  ->select('categories_attributes.attrinbute_id','colors.name','colors.id')
		 ->join('colors', 'colors.id', '=', 'categories_attributes.attrinbute_id')
		->where('categories_attributes.attribute_type',$attribute_type)
		->where('categories_attributes.category_id',$cat_id)
		->get();
		
		$ids=array();
		foreach($attrs as $row){
			array_push($ids,$row->attrinbute_id);
		}
		
		
					$Colors= Colors::where('isdeleted', 0)->whereNotIn('id',$ids);
					$attr_list =$Colors->get();
		} else{
			
					
			$modal_header='Sizes';
			$attrs = DB::table('categories_attributes')
		  ->select('categories_attributes.attrinbute_id','sizes.name','sizes.id')
		 ->join('sizes', 'sizes.id', '=', 'categories_attributes.attrinbute_id')
		->where('categories_attributes.attribute_type',$attribute_type)
		->where('categories_attributes.category_id',$cat_id)
		->get();
		$ids=array();
		foreach($attrs as $row){
			array_push($ids,$row->attrinbute_id);
		}
		$Sizes= Sizes::where('isdeleted', 0)->whereNotIn('id',$ids);
					$attr_list =$Sizes->get();
		}
		$page_details=array(
		"Title"=>"Update ".$modal_header,
		"Box_Title"=>"Update ".$modal_header,
		"search_route"=>'',
		"reset_route"=>'',
		'cat_id'=>$cat_id,
		'attributesType'=>$attribute_type
		);
			return view('admin.attributes.categoryAttributes.form',[
				'page_details'=>$page_details,
				'modal_header'=>$modal_header,
				'data'=>$attrs,
				'attr_list'=>$attr_list
				]);
			
	 }
	public function getCategoryAttributes(Request $request){
		$input=$request->all();
		if($input['attributesType']==0){
			$modal_header='Colors';
			$attrs = DB::table('categories_attributes')
		  ->select('categories_attributes.attrinbute_id','colors.name')
		 ->join('colors', 'colors.id', '=', 'categories_attributes.attrinbute_id')
		->where('categories_attributes.attribute_type',$input['attributesType'])->where('categories_attributes.category_id',$input['cat_id'])->get();
				
		} else{
			$modal_header='Sizes';
			$attrs = DB::table('categories_attributes')
		  ->select('categories_attributes.attrinbute_id','sizes.name')
		 ->join('sizes', 'sizes.id', '=', 'categories_attributes.attrinbute_id')
		->where('categories_attributes.attribute_type',$input['attributesType'])->where('categories_attributes.category_id',$input['cat_id'])->get();
		}
		
		echo json_encode(
		array(
		"html"=>view("admin.attributes.categoryAttributes.attributesModal",array(
		'modal_header'=>$modal_header,
		'data'=>$attrs ,
		'cat_id'=>$input['cat_id'],
		'attributesType'=>$input['attributesType']
		))->render()
		)
		);
	}
	
    public function colors(Request $request)
    {
		
		$parameters=$request->str;
		$status=$request->status;
		$export=URL::to('admin/color_export');
		if($parameters=='all')
		{
			$parameters='';
		}
		
		if($status=='all')
		{
			$status='';
		}
		
		if($parameters!=''){
			  $export=URL::to('admin/color_search_export_str').'/'.$parameters;  
			}
			
		if($status!='' && $parameters!=''){
		     $export=URL::to('admin/color_search_export').'/'.$parameters.'/'.$status;  
		}
			
			
			
		$page_details=array(
		   "Title"=>"Colors List",
		   "Box_Title"=>"Colors(s)",
		   "search_route"=>URL::to('admin/color_search'),
			"reset_route"=>route('colors'),
			  "export"=>$export,
		 );

		$Colors= Colors::where('isdeleted', 0);
		
		
		
		if($parameters!=''){
		  $Colors=$Colors
				->where('colors.name','LIKE',$parameters.'%');
		} 
		
		if($status!=''){
		  $Colors=$Colors
				->where('colors.status','=',$status);
		} 
		
        $Colors =$Colors->orderBy('id', 'DESC')->paginate(100);
	 
		return view('admin.attributes.colors.list',['Colors'=>$Colors,'page_details'=>$page_details,'status'=>$status]);
	
    }
	
	public function color_sts(Request $request)
    {
            $id=base64_decode($request->id);
            $sts=base64_decode($request->sts);

            $res=Colors::where('id',$id)
                    ->update(['status' => ($sts==0) ? 1 : 0]);

            if ($res) {
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
    }
	
	public function multi_delete_color(Request $request)
    {
			$input=$request->all();
			Colors::whereIn('id', $input['color_id'])
    ->update([
        'isdeleted' =>1
    ]);
	
	  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		   return redirect()->route('colors');
			
    }
     public function addcolor(Request $request){
				 $page_details=array(
       "Title"=>"Add Color",
	     "Method"=>"1",
       "Box_Title"=>"Add Color",
       "Action_route"=>route('addcolor'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'Color Name',
            'type'=>'text',
            'name'=>'name',
            'id'=>'color_name',
            'classes'=>'form-control onlyalphanumspace',
            'placeholder'=>'Name',
            'value'=>'',
			'disabled'=>''
           ),
           "color_code_field"=>array(
                'label'=>'Color Code',
                'type'=>'color',
                'name'=>'color_code',
                'id'=>'color_code',
                'value'=>'',
                'disabled'=>''
           ),
		    "color_file_field"=>array(
			    'label'=>'Color Image *',
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
            ),
			"images"=>array(
                  'color_image'=>''
            )
         )
       )
     );

	 if ($request->isMethod('post')) {
		 
		$input=$request->all();
             $request->validate([
                'name' => 'required|unique:colors,name,1,isdeleted|max:255',
                 'color_code' => 'required|unique:colors,color_code,1,isdeleted|max:255',
				 'color_image' => 'required|mimes:jpeg,bmp,png'
                
            ]);
			
			$color_image = $request->file('color_image');
            $destinationPath2 =  Config::get('constants.uploads.color');
            $file_name2=$color_image->getClientOriginalName();

      
        $file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$color_image,$file_name2);
		if($file_name2==''){
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
			return Redirect::back();
		}
			
			$error=DB::table('colors')->insert(
						[
                            'name' =>$input['name'],
                            'color_code' =>$input['color_code'],
						    'color_image' => $file_name2
						  ]
						);
	
       /* save the following details */
      if($error==0){
				 MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      } else{
				 MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		 }
      return Redirect::back();
	 }
		return view('admin.attributes.colors.form',['page_details'=>$page_details]);
   }
   

   
   
   public function editcolor(Request $request)
   {
           $id=base64_decode($request->id);
           $Colors = Colors::where('id', $id)->first();
		   
		    $page_details=array(
       "Title"=>"Edit Color",
	     "Method"=>"2",
       "Box_Title"=>"Edit Color",
       "Action_route"=>route('editcolor', base64_encode($id)),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'Color Name',
            'type'=>'text',
            'name'=>'name',
            'id'=>'color_name',
            'classes'=>' form-control onlyalphanumspace',
            'placeholder'=>'Name',
            'value'=>$Colors['name'],
				'disabled'=>''
           ),
            "color_code_field"=>array(
              'label'=>'Color Code',
            'type'=>'color',
            'name'=>'color_code',
            'id'=>'color_code',
            'classes'=>'onlych form-control',
            'placeholder'=>'Name',
            'value'=>$Colors['color_code'],
				'disabled'=>''
           ),
		   
		   "color_file_field"=>array(
			    'label'=>'Color Image *',
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
            ),
			 "images"=>array(
                  'color_image'=>$Colors['color_image']
            )
         )
       )
     );
	 
	 if ($request->isMethod('post')) {
		$input=$request->all();
		$id=base64_decode($request->id);
		$Colors = Colors::find($id);
         
		 $request->validate([
                'name' => 'required|unique:colors,name,'.$id.',id,isdeleted,0',
                'color_code' => 'required|unique:colors,color_code,'.$id.',id,isdeleted,0',
				'color_image' => 'mimes:jpeg,bmp,png'
             
         ],[
            'name.unique' => $input['name'].' Color name  already exists',
            'color_code.unique' => $input['color_code'].' Color code  already exists',
         ]
        );

     
		$Colors->name = $input['name'];
		$Colors->color_code = $input['color_code'];
	 
	 if ($request->hasFile('color_image')) {
			$color_image = $request->file('color_image');
			$destinationPath2 =  Config::get('constants.uploads.color');
			$file_name2=$color_image->getClientOriginalName();

			$file_name2= FIleUploadingHelper::UploadImage($destinationPath2,$color_image,$file_name2);
			  if($file_name2==''){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.image_upload_error'),$request);
				 return Redirect::back();
			  }
			$Colors->color_image = $file_name2;
		}
	 
   if($Colors->save()){
	   MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
   } else{
      MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
   }
  
   return Redirect::back();
	 }
           return view('admin.attributes.colors.form',['Colors'=>$Colors,'id'=>$id,'page_details'=>$page_details]);

   }
   
 

     public function deletecolor(Request $request)
    {
            $id=base64_decode($request->id);

            $res=Colors::where('id',$id)
                    ->update(['isdeleted' => 1]);

           if ($res) {
                  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
              } else {
                  MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
              }

                    return Redirect::back();
    }
    
	public function sizes(Request $request)
    {
		$parameters=$request->str;	
		$status=$request->status;	
		
	
		
		
		if($parameters=='all')
		{
			$parameters='';
		}
		
		if($status=='all')
		{
			$status='';
		}
		
		if($parameters!=''){
			  $export=URL::to('admin/size_search_export_str').'/'.$parameters;  
			}
			
		if($status!='' && $parameters!=''){
		     $export=URL::to('admin/size_search_export').'/'.$parameters.'/'.$status;  
		}
		
		
			$export=URL::to('admin/size_export');
		$page_details=array(
		   "Title"=>"Size List",
		   "Box_Title"=>"Size(s)",
		   "search_route"=>URL::to('admin/size_search'),
			"reset_route"=>route('sizes'),
			"export"=>$export,
		 );
		
		$Sizes= Sizes::where('isdeleted', 0);
		
		
		
		if($parameters!=''){
		  $Sizes=$Sizes
				->where('sizes.name','LIKE',$parameters.'%');
		} 
		
		if($status!=''){
		  $Sizes=$Sizes
				->where('sizes.status','=',$status);
		} 
		
        $Sizes =$Sizes->orderBy('id', 'DESC')->paginate(100);
		
		return view('admin.attributes.sizes.list',['Sizes'=>$Sizes,'page_details'=>$page_details,'status'=>$status]);
    }
	
	public function size_sts(Request $request)
    {
            $id=base64_decode($request->id);
            $sts=base64_decode($request->sts);

            $res=Sizes::where('id',$id)
                    ->update(['status' => ($sts==0) ? 1 : 0]);

            if ($res) {
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
    }
	
	 public function multi_delete_size(Request $request)
    {
			$input=$request->all();
			Sizes::whereIn('id', $input['size_id'])
    ->update([
        'isdeleted' =>1
    ]);
	
	  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		   return redirect()->route('sizes');
			
    }
     public function addsize(Request $request){
		 $page_details=array(
       "Title"=>"Add Size",
	     "Method"=>"1",
       "Box_Title"=>"Add Size",
       "Action_route"=>route('addsize'),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'Size Name',
            'type'=>'text',
            'name'=>'name',
            'id'=>'size_name',
            'classes'=>'form-control onlyalphanumspace',
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
                'name' => 'required|unique:sizes,name,1,isdeleted|max:255'
                
            ]);
			
			
			$sizes_array=explode("-",$input['name']);
		  $error=0;
		 foreach($sizes_array as $size){
			 if($size!=''){
				 $Sizes = Sizes::where('name', $size)
                  ->first();
				  if($Sizes){
					  $error=1;
				  } else{
						DB::table('sizes')->insert(
						['name' =>$size]
						);
				  }
			 }
			   
		 }
		 
       
       /* save the following details */
				if($error){
				MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
				} else{
				MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
				}
		 return Redirect::back();
	 }
		return view('admin.attributes.sizes.form',['page_details'=>$page_details]);
   }
   
   
   public function editsize(Request $request)
   {
           $id=base64_decode($request->id);
           $Sizes = Sizes::where('id', $id)->first();
		   
		   		 $page_details=array(
       "Title"=>"Edt Size",
	     "Method"=>"1",
       "Box_Title"=>"Edit Size",
       "Action_route"=>route('editsize', base64_encode($id)),
       "Form_data"=>array(

         "Form_field"=>array(
           
           "text_field"=>array(
              'label'=>'Size Name',
            'type'=>'text',
            'name'=>'name',
            'id'=>'size_name',
            'classes'=>'form-control onlyalphanumspace',
            'placeholder'=>'Name',
            'value'=>$Sizes['name'],
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
       $Sizes = Sizes::find($id);
         $request->validate([
            'name' => 'required|unique:sizes,name,'.$id.',id,isdeleted,0'
             
         ],[
          'name.unique' => $input['name'].' Color name  already exists',
         ]
        );

         
   
    $Sizes->name = $input['name'];
  
    
   /* save the following details */
   if($Sizes->save()){
			MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
   } else{
			MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
   }
  
   return Redirect::back();
	  }
           return view('admin.attributes.sizes.form',['Sizes'=>$Sizes,'id'=>$id,'page_details'=>$page_details]);

   }
   
     public function deletesize(Request $request)
    {
            $id=base64_decode($request->id);

            $res=Sizes::where('id',$id)
                    ->update(['isdeleted' => 1]);

           if ($res) {
                  MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
              } else {
                  MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
              }

                    return Redirect::back();
    }
}
