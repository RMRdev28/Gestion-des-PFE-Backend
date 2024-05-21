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
                if($chat){
                    $chat->title = $pfe->title;
                $chats[] = $chat;
                }

            }
        }
        return response()->json($chats);
    }



    public function sendMessage(Request $request){
        $status = "bad";
        $message = new Message();
        if(Auth::user()->typeUser == 0){
            $chat  = Chat::where('idPfe',$request->idPfe)->first();
            $id = $chat->id;
        }else{
            $id =$request->idPfe;
        }
        $message->idChat =$id;
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
        if(Auth::user()->typeUser == 0){
            $chat  = Chat::where('idPfe',$id)->first();
            $id = $chat->id;
        }
        $messages = Message::where('idChat',$id)->get();
        return response()->json($messages);
    }


}
