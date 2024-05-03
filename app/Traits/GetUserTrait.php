<?php

namespace App\Traits;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait GetUserTrait{

    public function user():User
    {
        $user = User::where('id', Auth::user()->id)->first();

        if ($user) {
            if($user->typeUser == 0){
                $user->load([
                    'propositions',
                    'studentDetail',
                    'studentDetail.binomRequest' => function ($query) {
                        $query->where('type', 'request');
                    },
                    'studentDetail.binomDemandes' => function ($query) {
                        $query->where('type', 'request');
                    },
                    'studentDetail.binom' => function ($query){
                        $query->where('type', 'valid');
                    },

                ]);
            }else if($user->typeUser == 0){
                $user->load([
                    'propositions',
                    'profDetail',
                ]);
            }else{
                $user->load([
                    'propositions',
                    'adminDetail',
                ]);
            }



        }
        return $user;
    }
}
