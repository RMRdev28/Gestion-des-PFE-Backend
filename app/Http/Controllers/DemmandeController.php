<?php

namespace App\Http\Controllers;

use App\Mail\AcceptDemande;
use App\Mail\RejectDemande;
use App\Models\Demmande;
use App\Models\Pfe;
use App\Models\Prof;
use App\Models\Proposition;
use App\Models\propositionCategory;
use App\Models\User;
use App\Traits\GetUserTrait;
use App\Traits\SendEmailTrait;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DemmandeController extends Controller
{
    use UploadTrait, SendEmailTrait, GetUserTrait;
    /**
     * Store a newly created resource in storage.
     */


    public function getDemandeProp($idProp)
    {
        $demandes = Demmande::where('idProp', $idProp)->with(['binom', 'binom.student1', 'binom.student2', 'binom.student1.user', 'binom.student2.user'])->get();
        return response()->json($demandes);
    }





    public function store(Request $request)
    {
        $message = "";
        $status = "bad";
        $fileUploade ="";
        if ($request->releverNote) {
            $file = $request->releverNote;
            $base64File = 'data:application/pdf;base64,' . $file;
            $request->except(['releverNote']);
            $fileUploade = $this->upload($base64File, "relever");
        }
        $demmande = new Demmande();
        $demmande->idProp = $request->idProp;
        $demmande->idBinom = $this->user()->binom->id;
        $demmande->releverNote = null;
        $demmande->save();
        if ($demmande) {


            if ($fileUploade) {
                $demmande->releverNote = $fileUploade['filePath'];
                $demmande->save();
                $message = "The demmande is saved";
                $status = "good";
            } else {
                $message = "Problem uploading file";
            }


        } else {
            $message = "Error saving file";
        }
        return response()->json([
            'message' => $message,
            'status' => $status
        ]);

    }

    // ki l binom yb3t demande bach yrfdha l prof wla y9blha
    public function acceptOrRejectDemande(Request $request)
    {
        $message = "";
        $status = "bad";
        $demmande = Demmande::where('id', $request->idDemmande)->with(['binom', 'binom.student1', 'binom.student2', 'binom.student1.user', 'binom.student2.user'])->first();
        $proposition = Proposition::find($demmande->idProp);
        $propositionCategories = propositionCategory::where('idProp', $proposition->id)->pluck('idCategory');
        $user = User::find($proposition->idUser);
        $prof = Prof::where('idUser', $user->id)->first();
        $student1 = $demmande->binom->student1->user;
        $student2 = $demmande->binom->student2->user;
        if ($request->status == 0) {
            if ($demmande->delete()) {
                $mailAbleClass = new RejectDemande($user, $student1, $proposition);
                $this->sendEmail($student1->email, $mailAbleClass);
                $mailAbleClass = new RejectDemande($user, $student2, $proposition);
                $this->sendEmail($student2->email, $mailAbleClass);
                $message = "La demmande a ete rejete";
                $status = "good";
            } else {
                $message = "Erreur";
            }
            return response()->json([
                'message' => $message,
                'status' => $status
            ]);
        } else {
            $pfe = new Pfe();
            $pfe->title = $proposition->title;
            $pfe->idBinom = $demmande->binom->id;
            $pfe->canSend = 0;
            $pfe->description = $proposition->description;
            $pfe->need_suivis = $proposition->need_suivis;
            $pfe->year = date('Y');
            $pfe->type = $proposition->type;
            if ($prof) {
                $pfe->idEns = $prof->id;

            } else {
                $pfe->idEns = null;
            }

            $pfe->level = $proposition->level;
            $pfe->note = 0;
            $pfe->branch = $demmande->binom->student1->specialite;
            if ($pfe->save()) {
                foreach ($propositionCategories as $cat) {
                    $query = DB::insert('INSERT INTO pfe_categories (idPfe, idCategory) VALUES (?, ?)', [$pfe->id, $cat]);
                }
                $proposition->delete();
                if ($demmande->delete()) {
                    $mailAbleClass = new AcceptDemande($user, $student1, $proposition);
                    $this->sendEmail($student1->email, $mailAbleClass);
                    $mailAbleClass = new AcceptDemande($user, $student2, $proposition);
                    $this->sendEmail($student2->email, $mailAbleClass);
                    $message = "La demande a ete accepter";
                    $status = "good";
                } else {
                    $message = "Erreur";
                }
                return response()->json([
                    'message' => $message,
                    'status' => $status
                ]);
            }

        }




    }

    /**
     * Display the specified resource.
     */
    public function show(Demmande $demmande)
    {
        return response()->json($demmande);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Demmande $demmande)
    {
        $message = "";
        $status = "bad";
        $user = Auth::user()->with(['userDetail', 'userDetail.binom']);
        $request->request->add(['idUser', $user->userDetail->binom->id]);
        $data = $request->all();
        if ($demmande->update($data)) {
            if ($request->hasFile('releverNote')) {
                $fileDeleted = $this->deleteFileFromStorage($demmande->releverNote, 'relever');
                if ($fileDeleted) {
                    $fileUploaded = $this->upload($request->releverNote, "relever");
                    if ($fileUploaded) {
                        $demmande->releverNote = $fileUploaded['originalName'];
                        $demmande->save();
                        $message = "The demmande is updated seccessfully";
                        $status = "good";
                    } else {
                        $message = "Error uploading file";
                    }
                } else {
                    $message = "Erro deliting file";
                }

            } else {
                $message = "The demmande is updated seccessfully";
                $status = "good";
            }
        } else {
            $message = "Error updating demmande";
        }
        return response()->json([
            'message' => $message,
            'status' => $status
        ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Demmande $demmande)
    {
        $message = "";
        $status = "bad";
        $fileDeleted = $this->deleteFileFromStorage($demmande->releverNote, 'relever');
        if ($fileDeleted) {
            $demmande->delete();
            $message = "The demmande is delted secssfully";
            $status = "good";
        } else {
            $message = "Error deleting file";
        }
        return response()->json([
            'message' => $message,
            'status' => $status
        ]);
    }
}
