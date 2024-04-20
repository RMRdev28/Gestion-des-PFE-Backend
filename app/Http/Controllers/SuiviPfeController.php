<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use App\Models\SuiviPfe;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuiviPfeController extends Controller
{
    use UploadTrait;



    public function noteEssaie(Request $request){
        $message = "";
        $status = "bad";
        $pfeS = SuiviPfe::where($request->idSuivis)->first();
        $pfeS->note = $request->note;
        $pfeS->observation = $request->observation;
        if($pfeS->save()){
            $message = "The note is added secssfully";
            $status = "good";
        }else{
            $message = "Error adding note";
        }
        return response()->json([
            'message'=>$message,
            'status'=>$status,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $message ="";
        $status = "bad";
        $pfeS = SuiviPfe::create();
        if($pfeS){
            $fileUploaded = $this->upload($request->essaie,'essaie');
            if($fileUploaded){
                $pfeS->pathPfeEssaie = $fileUploaded['originalName'];
                $pfeS->save();
            }
            $message = "The draft is added secssfully";
            $status = "Good";
        }else{
            $message = "Error adding draft";
        }
        return response()->json([
            'message'=>$message,
            'status'=>$status
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(SuiviPfe $suiviPfe)
    {
        return response()->json($suiviPfe);
    }

    // les suivis de lenginatnas et les etudiants
    // mzal mkmlthach
    public function mesSuivis(){
        $message = "";
        $status = "bad";
        $user = Auth::user();
        $pfeS = null;
        if($user->typeUser == 0){
            $pfeS = SuiviPfe::where('idBinom',$user->binom->id)->get();
        }else{
            $idBinomEncadre = $user->binomsEncadre();
            $ids = [];
            foreach ($idBinomEncadre as $id) {
                $ids[] = $id->idBinom;
            }
            $pfeS = SuiviPfe::whereIn('idBinom',$ids)->get();
        }

        return response()->json([
            'message' => $message,
            'status' => $status,
            'pfeS' => $pfeS
        ]);

    }


}
