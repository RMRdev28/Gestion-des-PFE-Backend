<?php

namespace App\Traits;

use App\Events\NewNotification;
use App\Models\Binom;
use App\Models\Notification;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

trait NotifyTrait
{
    public function notify($user, $type, $content)
    {
        $notification = new Notification();
        $notification->type = $type;
        $notification->content = $content;
        $notification->idUser = $user;
        $notification->status = 0;

        $notification->save();
        event(new NewNotification($notification));
        return true;

    }

    public function notifyBinom($idBinom, $type, $content){
        $binom = Binom::find($idBinom);
        $student1 = Student::find($binom->idEtu1);
        $student2 = Student::find($binom->idEtu2);
        $this->notify($student1->idUser,$type,$content);
        $this->notify($student2->idUser,$type,$content);
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
