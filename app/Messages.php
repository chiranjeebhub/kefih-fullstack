<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Config;
use Auth;
class Messages extends Model
{
	
	
    protected $table = 'messages';
  
    protected $dates = ['created_at', 'updated_at'];

   
    public static function postSendMessage($request)
    {
        if(!$request->to_user || !$request->message) {
            return;
        }

        $message = new Messages();
        //$message->from_user = Auth::user()->id;
		$message->from_user =auth()->guard('customer')->user()->id;
		$message->to_user = $request->to_user;
        $message->content = $request->message;
        $message->save();

        // prepare some data to send with the response
        /*$message->dateTimeStr = date("Y-m-dTH:i", strtotime($message->created_at->toDateTimeString()));
        $message->dateHumanReadable = $message->created_at->diffForHumans();
        $message->fromUserName = $message->fromUser->name;
        //$message->from_user_id = Auth::user()->id;
		$message->from_user_id = 1;
        $message->toUserName = $message->toUser->name;
        $message->to_user_id = $request->to_user;*/

        //PusherFactory::make()->trigger('chat', 'send', ['data' => $message]);

        return response()->json(['state' => 1, 'data' => $message]);
    }
	
	public static function adminSendMessage($request)
    {
        if(!$request->to_user || !$request->message) {
            return;
        }

        $message = new Messages();
        $message->from_user = 99999999999;
		$message->to_user = $request->to_user;
        $message->content = $request->message;
        $message->save();

        return response()->json(['state' => 1, 'data' => $message]);
    }
	
	public static function admingetLoadLatestMessages($request)
    {
        if(!$request->user_id) {
            return;
        }

        $messages = Messages::where(function($query) use ($request) {
            $query->where('from_user', '99999999999')->where('to_user', $request->user_id);
        })->orWhere(function ($query) use ($request) {
            $query->where('from_user', $request->user_id)->where('to_user', '99999999999');
        })->orderBy('created_at', 'ASC')->limit(10)->get();

        $return = [];

		foreach ($messages as $message) {
			
            $return[] = view('admin.mod_chat.message-line')->with('message', $message)->render();
        }
		  
        return response()->json(['state' => 1, 'messages' => $return]);
    }
	
	/*public function getLoadLatestMessages(Request $request)
    {
        if(!$request->user_id) {
            return;
        }

        $messages = Message::where(function($query) use ($request) {
            $query->where('from_user', Auth::user()->id)->where('to_user', $request->user_id);
        })->orWhere(function ($query) use ($request) {
            $query->where('from_user', $request->user_id)->where('to_user', Auth::user()->id);
        })->orderBy('created_at', 'ASC')->limit(10)->get();

        $return = [];

        foreach ($messages as $message) {

            $return[] = view('message-line')->with('message', $message)->render();
        }


        return response()->json(['state' => 1, 'messages' => $return]);
    }
	
	public function getOldMessages(Request $request)
    {
        if(!$request->old_message_id || !$request->to_user)
            return;

        $message = Message::find($request->old_message_id);

        $lastMessages = Message::where(function($query) use ($request, $message) {
            $query->where('from_user', Auth::user()->id)
                ->where('to_user', $request->to_user)
                ->where('created_at', '<', $message->created_at);
        })
            ->orWhere(function ($query) use ($request, $message) {
            $query->where('from_user', $request->to_user)
                ->where('to_user', Auth::user()->id)
                ->where('created_at', '<', $message->created_at);
        })
            ->orderBy('created_at', 'ASC')->limit(10)->get();

        $return = [];

        if($lastMessages->count() > 0) {

            foreach ($lastMessages as $message) {

                $return[] = view('message-line')->with('message', $message)->render();
            }

            //PusherFactory::make()->trigger('chat', 'oldMsgs', ['to_user' => $request->to_user, 'data' => $return]);
        }

        return response()->json(['state' => 1, 'data' => $return]);
    }
	*/
	
	
}
