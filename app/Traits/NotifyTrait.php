<?php

namespace App\Traits;

use App\Events\NewNotification;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

trait NotifyTrait
{
    public function notify($user, $type, $content)
    {
        $notification = new Notification();
        $notification->type = $type;
        $notification->type = $content;
        $notification->idUser = $user;

        $notification->save();
        event(new NewNotification($notification));
        return true;

    }

    public function markNotificationAsRead(Notification $notification){
        $notification->status = 1;
        return  $notification->save();
    }


    public function deleteNotification($user){
        Notification::where('idUser',$user)->delete();
        return true;
    }
}
