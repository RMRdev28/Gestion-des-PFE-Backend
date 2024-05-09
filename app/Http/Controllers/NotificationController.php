<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Traits\NotifyTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    use NotifyTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = Notification::where('idUser',Auth::user()->id)->get();
        return response()->json($notifications);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($this->notify(Auth::user()->id,"test","notification Test"))
            return response()->json(['status'=>"good"]);
        return response()->json(['status'=>"bad"]);
    }

    /**
     * Display the specified resource.
     */
    public function read()
    {
        // $this->markNotificationAsRead()
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        if($this->deleteNotification(Auth::user()->id))
            return response()->json(['status'=>"good"]);
        return response()->json(['status'=>"bad"]);
    }
}
