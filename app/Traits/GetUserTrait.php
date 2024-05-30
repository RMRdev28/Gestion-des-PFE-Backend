<?php

namespace App\Traits;
use App\Models\Binom;
use App\Models\Pfe;
use App\Models\Prof;
use App\Models\Proposition;
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
                $student = Student::where('idUser',$user->id)->first();
                // dd($student);
                $binom = Binom::where('idEtu1',$student->id)->orWhere('idEtu2',$student->id)->where('type', 'valid')->first();



                // dd($binom);
                if($binom){
                    $pfe = Pfe::where('idBinom',$binom->id)->first();
                    if($pfe){
                        $user->pfeTitle = $pfe->title;
                        $user->idPfe = $pfe->id;
                        if($pfe->idEns != null){
                            $prof = Prof::find($pfe->idEns);
                            $profUser = User::find($prof->idUser);
                            $user->encadreurFname = $profUser->fname;
                            $user->encadreurLname = $profUser->lname;
                        }else{
                            $user->encadreurFname = "Pas de";
                            $user->encadreurLname = "Encadreur ";
                        }

                    }else{
                        $user->propositionTitle = null;
                    }
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
                $pfeEncadre = Pfe::where('idEns',$user->profDetail->id)->pluck('id');
            $user->pfeEncadre = $pfeEncadre;
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
