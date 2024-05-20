<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Pfe;
use App\Traits\GetUserTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{

    use GetUserTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chats = [];
        if(Auth::user()->typeUser == 1){
            $pfes = Pfe::where('idEns',$this->user()->profDetail->id)->get();
            // dd($pfes);
            foreach ($pfes as $pfe) {
                $chat = Chat::where('idPfe',$pfe->id)->first();
                $chat->title = $pfe->title;
                $chats[] = $chat;
            }
        }
        return response()->json($chats);
    }



    public function sendMessage(Request $request){
        $status = "bad";
        $message = new Message();
        $message->idChat = $request->idPfe;
        $message->idSender = Auth::user()->id;
        $message->typeMessage = "1";
        $message->content = $request->content;
        if($message->save()){
            event(new NewMessage($message, $request->idPfe));
            $status = "good";

        }
        return response()->json([
            'status' => $status,
        ]);

    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $messages = Message::where('idChat',$id)->get();
        return response()->json($messages);
    }


}
