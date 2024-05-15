<?php

namespace App\Http\Controllers;

use App\Mail\BinomAskForRdv;
use App\Mail\ProfAccRdv;
use App\Models\Binom;
use App\Models\Pfe;
use App\Models\Prof;
use App\Models\RendezVous;
use App\Models\Student;
use App\Models\SuiviPfe;
use App\Models\User;
use App\Traits\GetUserTrait;
use App\Traits\NotifyTrait;
use App\Traits\SendEmailTrait;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuiviPfeController extends Controller
{
    use UploadTrait, SendEmailTrait, NotifyTrait, GetUserTrait;



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

    public function askForRdv(Request $request){
        $isAlradySent = RendezVous::where('idBinom',$this->user()->binom->id)->where('status',0)->first();
        if($isAlradySent){
            return response()->json([
                'status' => 'bad',
                'message' => 'You have already sent a request'
            ]);
        }else{
            $rdv = new RendezVous();
            $rdv->idBinom = $this->user()->binom->id;
            $rdv->save();
            $pfe = Pfe::where('idBinom', $rdv->idBinom)->first();
            $prof = Prof::find($pfe->idEns);
            $profUser = User::find($prof->idUser);
            $mailAbleClass = new BinomAskForRdv($pfe->title);
            $this->sendEmail($profUser->email,$mailAbleClass);
            $this->notify($profUser->id, "Ask for RDV","The binom of $pfe->title Ask For RDV");
            return response()->json([
                'status' => 'good',
                'message' => "The request is sent secssfully"
            ]);
        }



    }

    public function sendResume(Request $request){
        $rdv = RendezVous::find($request->idRdv);
        $rdv->resume = $request->resume;
        $rdv->status = 2;
        $rdv->save();
        return response()->json([
            'status'   => 'good',
        ]);
    }


    public function getAllRdv(Request $request){
        $rdv = RendezVous::where('idBinom',$this->user()->binom->id)->where('status',2)->get();
        $nextRdv = RendezVous::where('idBinom',$this->user()->binom->id)->where('status',1)->first();
        return response()->json([
            'rdv' => $rdv,
            'next' => $nextRdv
        ]);
    }

    public function getAllRdvProf(){

        $rdvs= [];
        $demandes= [];
        $nexts= [];
        $binoms = Pfe::where('idEns', $this->user()->profDetail->id)->pluck('idBinom');
        $rdv = RendezVous::whereIn('idBinom',$binoms)->where('status',2)->get();
        foreach($rdv as $el){
            $pfe = Pfe::where('idBinom',$el->idBinom)->first();
            $el->pfe = $pfe->title;
            $rdvs[] = $el;
        }
        $nextRdv = RendezVous::whereIn('idBinom',$binoms)->where('status',1)->get();
        foreach($nextRdv as $el){
            $pfe = Pfe::where('idBinom',$el->idBinom)->first();
            $el->pfe = $pfe->title;
            $nexts[] = $el;
        }
        $demande = RendezVous::whereIn('idBinom',$binoms)->where('status',0)->get();
        foreach($demande as $el){
            $pfe = Pfe::where('idBinom',$el->idBinom)->first();
            $el->pfe = $pfe->title;
            $demandes[] = $el;
        }
        return response()->json([
            'rdv' => $rdvs,
            'next' => $nexts,
            'demande' => $demandes
        ]);
    }



    public function acceptRdv(Request $request){

        $rdv = RendezVous::find($request->idRdv);
        $rdv->date = $request->date;
        $rdv->status = 1;
        $rdv->save();

        $binom = Binom::find($rdv->idBinom);

        $student = Student::find($binom->idEtu1);
        $studentUser = User::find($student->idUser);
        $mailAbleClass = new ProfAccRdv($rdv);
        $this->sendEmail($studentUser->email,$mailAbleClass);
        $this->notify($studentUser->id, "Accept RDV","The Prof  Fix RDV  at  $rdv->date");

        $student = Student::find($binom->idEtu2);
        $studentUser = User::find($student->idUser);
        $mailAbleClass = new ProfAccRdv($rdv);
        $this->sendEmail($studentUser->email,$mailAbleClass);
        $this->notify($studentUser->id, "Accept RDV","The Prof  Fix RDV  at  $rdv->date");

        return response()->json([
            'status' => 'good',
        ]);
    }

    public function rdvDone(Request $request){
        $rdv = RendezVous::find($request->idRdv);
        $rdv->status = 2;
        $rdv->resume = $request->resume
        $rdv->save();
        return response([
            'status' => "good"
        ]);
    }


}
