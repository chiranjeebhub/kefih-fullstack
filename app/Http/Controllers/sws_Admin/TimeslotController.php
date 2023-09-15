<?php

namespace App\Http\Controllers\sws_Admin;


use Illuminate\Http\Request;
use App\Timeslot;
use App\ProductSlider;
use App\Products;
use App\Pages;
use App\Helpers\CustomFormHelper;
use App\Helpers\MsgHelper;
use App\Helpers\FIleUploadingHelper;
use Redirect;
use Validator;
use URL;
use DB;
use Config;
class TimeslotController extends Controller
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
       "page"=>"Timeslot",
       "Box_Title"=>"Timeslot",
		"search_route"=>'',
		"reset_route"=>'',
		'url'=>route('addTimeslot')
     );
     
	 
		$list=Timeslot::orderBy('id', 'DESC')->paginate(10);
		
		return view('admin.timeslot.vwtimeslot',['list'=>$list,'page_details'=>$page_details]);
    }

   public function add(Request $request){

     $page_details=array(
       "Title"=>"Add Timeslot",
       "page"=>"Add Timeslot",
	   "Method"=>"1",
       "Box_Title"=>"Add Timeslot",
       "backurl"=>route('timeslot'),
       "Action_route"=>route('addTimeslot')
     );

if ($request->isMethod('post')) {
    $input=$request->all();
      
         
           
        
		 	 $request->validate([
             'name' => 'required|max:500|unique:tbl_timeslot,name,date',
               
             ]
			);
		
		 $chk = Timeslot::where(['name'=>$input['name']])->get();
		 if(sizeof($chk)==0){
		
		 
        $Timeslot = new Timeslot;
       
         $Timeslot->name = $input['name'];
         $Timeslot->price = $input['price'];
         //$Timeslot->date = $input['date'];
      /* save the following details */
      if($Timeslot->save()){
      	 return redirect()->route('timeslot')->with('success', 'Successfully added');
		  //MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
      } 
else{
		   MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
      }
      
			}
			else{
		 return redirect()->back()->with('error', 'Alreday Exits');
				
			}
					
}
    
    return view('admin.timeslot.vwtimeslot',['page_details'=>$page_details]);
   }
public function edit(Request $request)
   {
	      $id=$request->id;
	     
	$Timeslot = Timeslot::where('id', $id)->first();
		
 $page_details=array(
       "Title"=>"Edit Timeslot",
       "page"=>"Edit Timeslot",
		"Method"=>"1",
		"Box_Title"=>"Edit Timeslot",
		"backurl"=>"timeslot",
       "Action_route"=>route('timeslotEdit', [$id]),
     );
	
	  if ($request->isMethod('post')) {
		     $input=$request->all();
		     
		    
  Timeslot::whereId($id)->update($request->all());
		 
	return redirect()->route('timeslot')->with('success', 'successfully updated');	 
	  }
       
     else{
	 	return view('admin.timeslot.vwtimeslot',['page_details'=>$page_details,"row"=>$Timeslot]);	
	 }
	}

    public function delete(Request $request)
    {
            $id=$request->id;

            $res=Timeslot::where('id',$id)
                    ->delete();

            if ($res) {
              MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

                  return Redirect::back();
    }

    public function Timeslot_sts(Request $request)
    {
            $id=base64_decode($request->id);
            $sts=base64_decode($request->sts);

            $res=Timeslot::where('id',$id)
                    ->update(['status' => ($sts==0) ? 1 : 0]);

            if ($res) {
                MsgHelper::save_session_message('success',Config::get('messages.common_msg.data_save_success'),$request);
            } else {
                MsgHelper::save_session_message('danger',Config::get('messages.common_msg.data_save_error'),$request);
            }

            return Redirect::back();
    }

}
