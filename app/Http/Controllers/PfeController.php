<?php

namespace App\Http\Controllers;

use App\Models\Pfe;
use App\Models\Prof;
use App\Models\User;
use App\Models\ValidationPfe;
use App\Traits\GetUserTrait;
use App\Traits\UploadTrait;
use App\Traits\SemanticSearchTrait;
use Gemini;
use Illuminate\Http\Request;


class PfeController extends Controller
{
    use UploadTrait, GetUserTrait, SemanticSearchTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pfes = Pfe::all();
        foreach ($pfes as $pfe) {
            $validationPfe = ValidationPfe::where('idPfe',$pfe->id)->get();
            if($validationPfe){

                $pfe->validator1 = null;
                $pfe->validator2 =null;
                $prof = Prof::find($validationPfe[0]->idProf);
                $profUser = User::find($prof->idUser);
                $pfe->validator1 = $profUser;
                if(count($validationPfe) > 1){
                    $prof = Prof::find($validationPfe[1]->idProf);
                    $profUser = User::find($prof->idUser);
                    $pfe->validator2 = $profUser;

                }

            }
        }
        return response()->json($pfes);
    }


    public function pfeStatus()
    {
        $status = "";
        dd($this->user());
        $pfe = Pfe::where('idBinom', $this->user()->binom->id);
        switch ($pfe->status) {
            case 'valide':
                $status = "Valide";
                break;
            case 'termine':
                $status = "Termine";
                break;
            case 'revu':
                $status = "Demmande de modification";
                break;
            default:
                $status = "Pas encore valide";
                break;
        }

        return response()->json($status);
    }

    public function recomandationSjtPfes(Request $request)
    {
        $categories = "(";
        foreach ($request->categories as $category) {
            $categories .= $category . " ,";
        }
        $categories .= ")";
        $level = "2eme ane master";
        $specialite = $this->user()->studentDetail->specialite;
        if ($this->user()->studentDetail->level == "l3") {
            $level = "3eme anne licence";
        }

        $client = Gemini::client(env('GOOGLE_API_KEY'));
        $result = $client->geminiPro()->generateContent("Pourriez-vous me recommander des sujets de PFE dans les category de $categories ? Je suis étudiant en $level à l'USHTB, spécialité $specialite . S'il vous plaît, donnez les sujets de manière concise sous format de reactNative.");
        return response()->json([
            'data' => $result->text(),
        ]);
    }

    public function semanticSearchFunction()
    {
        $pdf = $this->pdfToText("pdf.pdf");
        $data = $this->performSemanticSearch($pdf, "samsoum");
        response()->json($data);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $message = "";
        $status = "bad";
        $data = $request->all();
        $pfe = Pfe::create($data);
        if ($pfe) {
            if ($request->hasFile('pfe')) {
                $fileUploaded = $this->upload($request->pfe, 'pfe');
                if ($fileUploaded) {
                    $pfe->pfe = $fileUploaded['originalName'];
                    $pfe->save();
                }
            }
            $message = "The pfe is updated secessfully";
            $status = "good";
        } else {
            $message = "Error adding pfe";
        }
        return response()->json([
            'message' => $message,
            'status' => $status
        ]);


    }

    public function selectionerPfePourCommissionSuivis(Request $request)
    {
        $message = "All pfe have commission de suivis";
        $status = "good";
        $pfes = [];
        foreach ($request->pfes as $idPfe) {
            $pfe = Pfe::find($idPfe);
            $pfe->need_suivis = 1;
            $pfe->save();
            if (!$this->assignComissionDeSuivis($pfe)) {
                $pfes[] = $pfe;
                $status = "bed";
                $message = "They are problem in some pfe";
            }
        }
        return response()->json([
            'message' => $message,
            'status' => $status,
            'pfes' => $pfes
        ]);
    }

    private function assignComissionDeSuivis(Pfe $pfe)
    {
        $validators = Prof::with(['categories'])->where('isValidator', 1)->get();
        $pfeCategories = $pfe->categories->pluck('id')->toArray();
        $validatingProf = null;
        foreach ($validators as $validator) {
            $validatorCategories = $validator->categories->pluck('id')->toArray();

            if ((count(array_intersect($pfeCategories, $validatorCategories)) > 0) && ($pfe->idEns != $validator->id)) {
                $validatingProf = $validator;
                if ($validatingProf != null) {
                    break;
                }
            }
        }
        if ($validatingProf == null) {
            $validatingProf = $validators->random(1);
            while ($validatingProf->id == $pfe->idEns) {
                $validatingProf = $validators->random(1);
            }
        }
        $pfe->jury1 = $validatingProf->id;
        if ($pfe->save())
            return true;
        return false;
    }

    /**
     * Display the specified resource.
     */
    public function show(Pfe $pfe)
    {
        $validationPfe = ValidationPfe::where('idPfe',$pfe->id)->get();
        if($validationPfe){

            $pfe->validator1 = null;
            $pfe->validator2 =null;
            $prof = Prof::find($validationPfe[0]->idProf);
            $profUser = User::find($prof->idUser);
            $pfe->validator1 = $profUser;
            if(count($validationPfe) > 1){
                $prof = Prof::find($validationPfe[1]->idProf);
                $profUser = User::find($prof->idUser);
                $pfe->validator2 = $profUser;

            }

        }
        return response()->json($pfe);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pfe $pfe)
    {
        $message = "";
        $status = "bad";
        $data = $request->all();
        if ($pfe->update($data)) {
            if ($request->hasFile('pfe')) {
                $fileUploaded = $this->upload($request->file, 'pfe');
                if ($fileUploaded) {
                    $deletedFile = $this->deleteFileFromStorage($pfe->pfe, 'pfe');
                    if ($deletedFile) {
                        $pfe->pfe = $fileUploaded['originalName'];
                        if ($pfe->save()) {
                            $message = "The pfe is updated secssfully";
                            $status = "good";
                        }
                    }

                } else {
                    $message = "Error editing file";
                }


            }
            $message = "The pfe is updated secssfully";
            $status = "good";
        } else {
            $message = "Error updating info pfe";
        }
        return response()->json([
            'message' => $message,
            'status' => $status,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pfe $pfe)
    {
        $message = "";
        $status = "bad";
        $fileDeleted = $this->deleteFileFromStorage($pfe->pfe, 'pfe');
        if ($fileDeleted) {
            $pfe->delete();
            $message = "The pfe is deleted secessfully";
            $status = "good";
        } else {
            $message = "Error delteing file";
        }
        return response()->json([
            'message' => $message,
            'status' => $status
        ]);
    }

    public function chooseValidatorsManually(Request $request){
        $pfe = Pfe::find($request->idPfe);

    }
}
