<?php

namespace App\Http\Controllers\sws_Admin;

use Illuminate\Http\Request;
use Redirect;
use Validator;
use DB;
use Config;
use Auth;
use App\Vendor;
use App\Messages;

use App\Helpers\MsgHelper;
use App\Helpers\CommonHelper;


class ChatController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        	//$this->middleware('auth:customer');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     
    public function index(Request $request)
    {
        $page_details=array(
			"Title"=>"Chat Support ",
			"Box_Title"=>"Chat Support ",
		);
		
		if(Auth::guard('vendor')->check()){
			$vdr_id=auth()->guard('vendor')->user()->id;
			$vendor_list=Vendor::select('id','f_name as name')->whereNotNull('f_name')->where('id',$vdr_id)->where('status',1)->orderBy('f_name')->get()->toArray();
		}else{
			$vendor_list=Vendor::select('id','f_name as name')->whereNotNull('f_name')->where('status',1)->orderBy('f_name')->get()->toArray();
		}
		
		return view('admin.mod_chat.chat',['page_details'=>$page_details,'customer_list'=>$vendor_list]);
    }
	
	public function postSendMessage(Request $request)
	{
		Messages::adminSendMessage($request);
	}
	
	public function getLoadLatestMessages(Request $request)
	{
		Messages::adminLoadLatestMessages($request);
	}
	
	public function getOldMessages(Request $request)
    {
        Messages::adminLoadOldMessages($request);
    }
	
	
		
	
	
 
}
