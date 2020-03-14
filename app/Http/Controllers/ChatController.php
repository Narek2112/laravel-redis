<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Chat;
use App\Messages;
use App\Events\SendMsgEvent;
use App\Jobs\SendEmailJob;

class ChatController extends Controller
{
    
    public function createChat( Request $request )
    {
    	$name   = $request->chatName;
    	$token  = md5($name.microtime());

    	$chat = new Chat();

    	$chat->name  = $name;
    	$chat->token = $token;
    	$chat->save();

        return response()->json(['status' => 'success','token'=>$token], 200); 
    }
    
    public function chat(Request $request)
    {
    	$chanel = $request->input('channel');
        $chatName = Chat::select('name')->where(['token'=>$chanel])->get()->pluck('name');

        broadcast(new SendMsgEvent($chanel));

    	return response()->json(['status' => 'success','chatName'=>$chatName], 200); 
    }

    public function sendmsg( Request $request )
    {
    	$msg 	 = $request->message;
    	$chanel  = $request->channel;
		if ($msg) {
            $message = new Messages();
    		
    		$message->chat 	  = $chanel;
    		$message->message = $msg;
        	$message->save();

        	dispatch(new SendEmailJob('test@gmail.com',$msg));

        	broadcast(new SendMsgEvent($chanel,$msg))->toOthers();
        }

    	return response()->json(['status' => 'success'], 200); 
    
    }
}
