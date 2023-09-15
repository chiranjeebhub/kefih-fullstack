<?php

namespace App\Http\Controllers\sws_Admin;

use Illuminate\Http\Request;
use App\Products;
use App\TireProducts;
use App\Zone;
use App\ProductImages;
use App\ProductCategories;
use App\ProductRelation;
use App\Brands;
use App\Category;
use App\Colors;
use App\Materials;
use App\Sizes;
use App\Vendor;
use App\ProductAttributes;
use Redirect;
use Validator;
use DB;
use Config;
use App\Helpers\CommonHelper;
use App\Helpers\MsgHelper;
use App\Helpers\FIleUploadingHelper;
use App\Helpers\CustomFormHelper;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\zoneExport;
use URL;
use Auth;
class ZoneController extends Controller 
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
		
		$parameters=$request->str;	
		$export=route('exportzone');
	 	$page_details=array(
			"Title"=>"Zone List",
			"reset_route"=>route('zone'),
			"search_route"=>URL::to('admin/filters_model'),
			"url"=>route('add_zone'),
			"multipe_delete"=>route('multi_delete_zone'),
			"export"=>$export,
			 "Form_data"=>array(
				 "Form_field"=>array(
						 "submit_button_field"=>array(
						  'label'=>'',
						  'type'=>'submit',
						  'name'=>'submit',
						  'id'=>'submit',
						  'classes'=>'btn btn-danger disableAfterClick',
						  'placeholder'=>'',
						  'value'=>'Save'
						),
                    "select_field"=>array(
                    'label'=>'Select Parent',
                    'type'=>'select_with_inner_loop_for_filter',
                    'name'=>'category_id',
                    'id'=>'category_id',
                    'classes'=>'custom-select form-control category_id',
                    'placeholder'=>'Name',
                    'value'=>CommonHelper::getAdminChilds(1,'',old('parent_id'))
                    )
					)
			)
		);
		
        $list=zone::where('isdeleted', 0)->orderBy('name','asc')->paginate(20);
		return view('admin.zone.vwzone',['list'=>$list,'page_details'=>$page_details]);
    }
    	 public function add(Request $request){
    	$page_details=array(
    	"Title"=>"Add zone",
		"backurl"=>route("zone"),
		"Action_route"=>route('add_zone'),
		"return_data"=>array(              
		)
		
		);
		
		
		if ($request->isMethod('post')) {
		
		     $input=$request->all();
			 $request->validate([
                'name' => 'required',
               // 'area_id' => 'required',
                ],
                [
            'name.required'=>'zone Name is required',
     //'area_id.required'=>'Area is required',
               ]
                
                
                );
		$checkIsAlraedy =Zone::where(['name'=>trim($input['name']),'isdeleted'=>0])->first();
		if(sizeof($checkIsAlraedy)==0){
		
			  $area_id ='';
	 for($i=0; $i < count(@$input['area_id']); $i++){
	 	$area_id .=$input['area_id'][$i]?$input['area_id'][$i].',' : '';
	  }  
	  
	  	$id = Zone::insertGetId(["area_id"=>$area_id,'city_id'=>$input['city_id'],'name'=>$input['name']]);
//Zone::where('id', '=',$id)->update(['area_id'=>$area_id]);   
			 return redirect()->route('zone')->with('success', 'Successfully Added');
		}else{
		 return redirect()->route('add_zone')->with('danger', 'Data alraedy exits!');	
		}
 
 
      
	}
        
        else{
            $states = CommonHelper::getState('101');
		return view('admin.zone.vwzone',['states' => $states, 'page_details'=>$page_details]);	
		}
	 }
 


public function edit(Request $request)
   {
	$id=$request->id;
	$row = zone::where('id', $id)->first();		
		
 $page_details=array(
       "Title"=>"Edit zone",
		"backurl"=>route("zone"),
       "Action_route"=>route('edit_zone', [$id]),
     );
	
	  if ($request->isMethod('post')) {
		        $input=$request->all();
			 $request->validate([
               
                'name' => 'required',
                ],
                [
            
            'name.required'=>'zone Name is required',
    
               ]
                
                
                );
		  zone::whereId($id)->update($request->all());

return Redirect::route('zone')->with('success', 'Successfully updated');	

		 
	  }
       
     else{
	 	return view('admin.zone.vwzone',['page_details'=>$page_details,'row'=>$row]);
	 }
	}


	
	 public function delete(Request $request)
    {
            $id=base64_decode($request->id);

            $res=zone::where('id',$id)
                    ->update(['isdeleted' => 1]);
                    
                    
            if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

                  return Redirect::back();
    }
	
	
    public function status(Request $request)
    {
            $id=base64_decode($request->id);
            $sts=base64_decode($request->sts);

            $res=zone::where('id',$id)
                    ->update(['status' => ($sts==0) ? 1 : 0]);

          
            return Redirect::back()->with('success', 'Successfully Changed');
    }
   

	public function multi_delete(Request $request)
    {
		$input=$request->all();
		if($input['status']==1){
			zone::whereIn('id', $input['id'])
				->update([
					'status' =>1
				]);
		}
		if($input['status']==2){
			zone::whereIn('id', $input['id'])
				->update([
					'status' =>0
				]);
		}
		if($input['status']==3){
		zone::whereIn('id', $input['id'])
				->update([
					'isdeleted' =>1
				]);	
		}
		
				
				
				
		MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
		
		  return redirect()->route('zone');
	
    }


	public function exportdata(Request $request)
    {
		$str=$request->str;
	return Excel::download(new zoneExport($str), 'zone'.date('d-m-Y H:i:s').'.csv');	
    }

}
