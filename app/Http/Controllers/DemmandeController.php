<?php

namespace App\Http\Controllers;

use App\Models\Demmande;
use App\Models\User;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemmandeController extends Controller
{
    use UploadTrait;
    /**
     * Store a newly created resource in storage.
     */


    public function getDemandeProp($idProp)
    {
        $demandes = Demmande::where('idProp', $idProp)->get();
        return response()->json($demandes);
    }



    public function store(Request $request)
    {
        $message = "";
        $status = "bad";


        $user = User::where('id', Auth::user()->id)->with(['userDetail', 'userDetail.binom'])->first();
        $request->merge(['idBinom' => $user->userDetail->binom->id]);
        $data = $request->all();
        $demmande = Demmande::create($data);
        if ($demmande) {
            if ($request->hasFile('releverNote')) {
                $file = $request->file('releverNote');
                $base64File = 'data:' . $file->getClientMimeType() . ';base64,' . base64_encode(file_get_contents($file));

                $fileUploade = $this->upload($base64File, "relever");
                if ($fileUploade) {
                    $demmande->releverNote = $fileUploade['fileName'];
                    $demmande->save();
                    $message = "The demmande is saved";
                    $status = "good";
                } else {
                    $message = "Problem uploading file";
                }
            } else {
                $message = "The demmande is saved without file";
                $status = "good";
            }

        } else {
            $message = "Error saving file";
        }
        return response()->json([
            'message' => $message,
            'status' => $status
        ]);

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
