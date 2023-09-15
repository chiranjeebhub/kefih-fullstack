<?php

namespace App\Http\Controllers\sws_Admin;

use Illuminate\Http\Request;
use Redirect;
use Validator;
use DB;
use Config;
use Auth;
use App\Customer;
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
			"Title"=>"Chat ",
			"Box_Title"=>"Chat ",
			"Action_route"=>'',
            "back_route"=>'',
			"reset_route"=>''
		);
		
		$customer_list=Customer::select('id','name')->where('status',1)->get()->toArray();
		
		return view('admin.mod_chat.chat',['page_details'=>$page_details,'customer_list'=>$customer_list]);
    }
	
	public function postSendMessage(Request $request)
	{
		Messages::adminSendMessage($request);
	}
	
	public function getLoadLatestMessages(Request $request)
	{
		Messages::admingetLoadLatestMessages($request);
	}
		
	
	
 
}
