<?php

namespace App\Http\Controllers;

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
		
		return view('fronted.mod_chat.chat',['page_details'=>$page_details]);
    }
	
	public function postSendMessage(Request $request)
	{
		Messages::postSendMessage($request);
	}
	
		
	
	
 
}
