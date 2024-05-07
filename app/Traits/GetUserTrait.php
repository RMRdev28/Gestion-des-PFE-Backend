<?php

namespace App\Traits;
use App\Models\Binom;
use App\Models\Student;
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
                ]);
                $student = Student::find($user->id);
                dd($student);
                $binom = Binom::where('idEtu1',$student->id)->orWhere('idEtu2',$student->id)->where('type', 'valid')->first();
                dd($binom);
                if($binom){
                    $user->binom = $binom;
                    if($binom->idEtu1 != $student->id){
                        $student = Student::find($binom->idEtu1);
                        $binomDetail = User::find($student->idUser);
                        $user->binomName = $binomDetail->fname;
                        $user->binomLname = $binomDetail->lname;
                    }else{
                        $student = Student::find($binom->idEtu2);
                        $binomDetail = User::find($student->idUser);
                        $user->binomName = $binomDetail->fname;
                        $user->binomLname = $binomDetail->lname;
                    }
                }
            }else if($user->typeUser == 1){
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
