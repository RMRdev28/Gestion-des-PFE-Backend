<?php

namespace App\Traits;
use Illuminate\Support\Facades\Mail;

trait SendEmailTrait
{
    public function sendEmail($email, $mailAbleClass){
        if(Mail::to($email)->send($mailAbleClass))
            return true;
        return false;
    }
}
