<?php

namespace App\Traits;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait GetUserTrait{

    public function user():User
    {
        $user = User::where('id', Auth::user()->id)->first();

        if ($user) {
            $user->load([
                'propositions',
                'userDetail',
                'userDetail.binomRequest' => function ($query) {
                    $query->where('type', 'request');
                },
                'userDetail.binomDemandes' => function ($query) {
                    $query->where('type', 'request');
                },
                'userDetail.binom' => function ($query){
                    $query->where('type', 'valid');
                },
                // 'userDetail.binom2' => function ($query){
                //     $query->where('type', 'valid');
                // }
            ]);


        }
        return $user;
    }
}
