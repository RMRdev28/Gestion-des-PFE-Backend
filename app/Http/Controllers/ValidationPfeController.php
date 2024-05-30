<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Pfe;
use App\Models\Prof;
use App\Models\ValidationPfe;
use App\Traits\GetUserTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ValidationPfeController extends Controller
{
    use GetUserTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $validateursPfe = Prof::where('isValidator', 1)->with(['user'])->get();
        return response()->json($validateursPfe);
    }

    public function getRecomandedCommissionDeSuivi($pfe){
        $categoryIds = DB::table('pfe_categories')->where('idPfe',$pfe)->pluck('idCategory');
        $profIds = DB::table('prof_categories')->whereIn('idCategory', $categoryIds)->pluck('idProf');
        $validateursPfe = Prof::whereIn('id',$profIds)->with(['user'])->get();
        return response()->json($validateursPfe);
    }



    /**
     * this function is to mak a prof as validator
     */
    public function store(Request $request)
    {

        $errors = [];
        foreach ($request->profs as $prof) {

            $prof = Prof::find($prof);
            $prof->isValidator = 1;
            if (!$prof->save()) {
                $errors[] = $prof->name;
            }
        }
        if (count($errors) > 0) {
            $message = "Not all selected prof are validated";
            $status = "bad";
        } else {
            $message = "good";
            $status = "good";
        }
        return response()->json([
            'message' => $message,
            'status' => $status,
            'errors' => $errors
        ]);


    }


    public function validatePfe(Request $request)
    {
        $message = "";
        $status = "";
        $pfe = Pfe::find($request->pfe);
        if ($pfe->status == "pasencore" || $pfe->status == "revu") {
                $validationPfe = ValidationPfe::where('idProf',$this->user()->profDetail->id)->where('idPfe',$pfe->id)->first();
                $validationPfe->decision = $request->decision;
                $validationPfe->comment = $request->comment;
                $validationPfe->save();
        }
        $nbrValidator = ValidationPfe::where('idPfe', $pfe->id)->where('decision',1)->count();
        $nbrValidatorRef = ValidationPfe::where('idPfe', $pfe->id)->where('decision',-1)->count();
        if($nbrValidator == 1 || $nbrValidatorRef > 0){
            $pfe->status = "revu";
        }
        $nbrValidator = ValidationPfe::where('idPfe', $pfe->id)->where('decision',1)->count();
        if($nbrValidator == 2){
            $this->createAChat($pfe->id);
            $pfe->status = "valide";
        }
        if($pfe->save()){
            $message = "you choose";
            $status  = "good";
        }

        return response()->json([
            'message' => $message,
            'status' => $status
        ]);

    }

    public function createAChat($idPfe){
        $chat = new Chat();
        $chat->idPfe = $idPfe;
        $chat->save();

    }

    public function pfeShouldValidatedByProf(){
        $validator = ValidationPfe::where('decision',0)->where('idProf',$this->user()->profDetail->id)->get();
        $pfeToValidate = [];
        foreach ($validator as $v){
            $pfe = Pfe::find($v->idPfe);
                $pfeToValidate[] = $pfe;
        }
        return response()->json($pfeToValidate);

    }
    public function askForValidators(Pfe $pfe){
        $categories = DB::table('pves_categories')->where('idPfe',$pfe->id)->get();

    }
}
